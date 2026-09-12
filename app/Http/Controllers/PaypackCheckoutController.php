<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirmedMail;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PaypackService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaypackCheckoutController extends Controller
{
    public function processCashin(Request $request): JsonResponse
    {
        $request->validate([
            'phone' => ['required', 'string', 'min:8', 'max:15'],
            'type' => ['required', 'string', 'in:exam,plan'],
            'id' => ['required', 'integer'],
            'duration_months' => ['nullable', 'integer', 'in:1,3,6,12'],
        ]);

        if ($request->type === 'exam') {
            return response()->json(['error' => 'Einmalige Prüfungen sind nicht mehr verfügbar. Bitte erwerben Sie ein Abonnement.'], 400);
        }

        /** @var Plan $plan */
        $plan = Plan::query()->findOrFail($request->id);
        $duration = $request->integer('duration_months', 1);
        $amount = (float) $plan->getPriceForDuration($duration);
        $phone = trim($request->input('phone'));

        try {
            $service = app(PaypackService::class);
            $response = $service->cashin($phone, $amount);

            $ref = $response['ref'] ?? null;
            $status = $response['status'] ?? 'pending';

            if (! $ref) {
                return response()->json(['error' => 'Paypack-Transaktion konnte nicht gestartet werden.'], 500);
            }

            session([
                'paypack_intent' => 'plan',
                'paypack_item_id' => $plan->id,
                'paypack_duration' => $duration,
                'paypack_ref' => $ref,
                'paypack_amount' => $amount,
                'paypack_phone' => $phone,
            ]);

            // Save pending subscription in DB for automated background sync
            Subscription::updateOrCreate(
                ['provider_order_id' => $ref],
                [
                    'user_id' => $request->user()->id,
                    'plan_id' => $plan->id,
                    'provider' => 'paypack',
                    'status' => 'pending',
                    'price_paid' => $amount,
                    'duration_months' => $duration,
                    'starts_at' => now(),
                    'ends_at' => null,
                ]
            );

            return response()->json([
                'status' => $status,
                'ref' => $ref,
                'amount' => $amount,
                'message' => 'Bitte bestätigen Sie die Zahlungsaufforderung auf Ihrem Mobiltelefon (MTN / Airtel).',
            ]);
        } catch (\Exception $e) {
            Log::error('Paypack process cashin error', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Zahlung konnte nicht eingeleitet werden: '.$e->getMessage()], 500);
        }
    }

    public function checkStatus(Request $request, string $ref): JsonResponse
    {
        try {
            $service = app(PaypackService::class);

            // First check if already recorded as active in DB
            $existingSub = Subscription::query()->where('provider_order_id', $ref)->first();
            if ($existingSub && $existingSub->status === 'active') {
                return response()->json([
                    'status' => 'successful',
                    'redirect' => route('billing'),
                    'message' => 'Zahlung erfolgreich verifiziert!',
                ]);
            }

            $tx = $service->getTransaction($ref);
            $status = strtolower($tx['status'] ?? 'pending');

            if ($status === 'successful' || $status === 'completed' || $status === 'success') {
                $intent = session('paypack_intent', 'plan');
                $itemId = session('paypack_item_id');
                $duration = session('paypack_duration', 1);
                $amount = session('paypack_amount', 0);
                $user = $request->user();

                if ($itemId && $user) {
                    /** @var Plan|null $plan */
                    $plan = Plan::query()->find($itemId);

                    if ($plan) {
                        Subscription::query()
                            ->where('user_id', $user->id)
                            ->where('status', 'active')
                            ->update(['status' => 'cancelled', 'ends_at' => now()]);

                        $endsAt = now()->addMonths($duration);

                        $subscription = Subscription::create([
                            'user_id' => $user->id,
                            'plan_id' => $plan->id,
                            'provider' => 'paypack',
                            'provider_order_id' => $ref,
                            'status' => 'active',
                            'price_paid' => $amount > 0 ? $amount : ($tx['amount'] ?? 0),
                            'duration_months' => $duration,
                            'starts_at' => now(),
                            'ends_at' => $endsAt,
                        ]);

                        try {
                            Mail::mailer('zoho_sales')->to($user->email)->send(new SubscriptionConfirmedMail($user, $subscription, $plan));
                        } catch (\Throwable $e) {
                            Log::error('Could not send subscription confirmation email (Paypack): '.$e->getMessage());
                        }

                        session()->forget(['paypack_intent', 'paypack_item_id', 'paypack_duration', 'paypack_ref', 'paypack_amount', 'paypack_phone']);

                        return response()->json([
                            'status' => 'successful',
                            'redirect' => route('billing'),
                            'message' => 'Zahlung erfolgreich! Ihr Abonnement ist aktiv.',
                        ]);
                    }
                }
            } elseif ($status === 'failed' || $status === 'cancelled') {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Zahlung wurde abgebrochen oder ist fehlgeschlagen.',
                ]);
            }

            return response()->json([
                'status' => 'pending',
                'message' => 'Warte auf Bestätigung auf dem Mobiltelefon...',
            ]);
        } catch (\Exception $e) {
            Log::error('Paypack checkStatus error', ['ref' => $ref, 'error' => $e->getMessage()]);

            return response()->json(['status' => 'pending', 'error' => $e->getMessage()]);
        }
    }

    public function webhook(Request $request): Response
    {
        $payload = $request->all();
        Log::info('Paypack webhook received', $payload);

        $eventKind = $payload['event_kind'] ?? $payload['event-kind'] ?? '';
        $data = $payload['data'] ?? $payload;
        $ref = $data['ref'] ?? '';
        $status = strtolower($data['status'] ?? '');

        if ($ref && ($status === 'successful' || $status === 'completed' || $eventKind === 'transaction:processed')) {
            $existingSub = Subscription::query()->where('provider_order_id', $ref)->first();
            if ($existingSub) {
                if ($existingSub->status !== 'active') {
                    $existingSub->update(['status' => 'active']);
                }

                return response('OK', 200);
            }

            // Find user from client phone if saved or stored in transaction data
            $clientPhone = $data['client'] ?? '';
            if ($clientPhone) {
                // If phone is associated with a user or matching transaction context
                Log::info('Paypack webhook transaction processed', ['ref' => $ref, 'client' => $clientPhone]);
            }
        }

        return response('OK', 200);
    }
}
