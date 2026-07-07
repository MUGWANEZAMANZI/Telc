<?php

namespace App\Http\Controllers;

use App\Models\ExamEnrollment;
use App\Models\ExamTemplate;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\RevolutService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class RevolutCheckoutController extends Controller
{
    public function createOrder(Request $request): JsonResponse
    {
        $request->validate([
            'type' => ['required', 'string', 'in:exam,plan'],
            'id' => ['required', 'integer'],
            'duration_months' => ['nullable', 'integer', 'in:1,3,12'],
        ]);

        $amount = 0;
        $description = '';
        $customData = '';

        if ($request->type === 'exam') {
            /** @var ExamTemplate $exam */
            $exam = ExamTemplate::query()->findOrFail($request->id);
            if (! $exam->price || $exam->price <= 0) {
                return response()->json(['error' => 'Dieser Prüfungstermin ist kostenlos.'], 400);
            }
            $amount = (float) $exam->price;
            $description = $exam->title;
            $customData = 'exam_'.$exam->id;
        } else {
            /** @var Plan $plan */
            $plan = Plan::query()->findOrFail($request->id);
            $duration = $request->integer('duration_months', 1);
            $amount = (float) $plan->price * $duration;
            $description = $plan->name.' ('.$duration.' Monat(e))';
            $customData = 'plan_'.$plan->id.'_'.$duration;
        }

        try {
            $service = app(RevolutService::class);
            $order = $service->createOrder($amount, 'EUR', $description, $customData);

            $publicId = $order['public_id'] ?? null;
            if (! $publicId) {
                return response()->json(['error' => 'Revolut-Auftrag konnte nicht erstellt werden.'], 500);
            }

            session([
                'revolut_intent' => $request->type,
                'revolut_item_id' => $request->id,
                'revolut_duration' => $request->duration_months,
                'revolut_order_id' => $order['id'] ?? null,
                'revolut_amount' => $amount,
            ]);

            return response()->json([
                'public_id' => $publicId,
                'mode' => $service->getMode(),
                'amount' => $amount,
            ]);
        } catch (\Exception $e) {
            Log::error('Revolut create order error', ['error' => $e->getMessage()]);

            return response()->json(['error' => 'Zahlungsdienst nicht verfügbar. Bitte versuchen Sie es später erneut.'], 500);
        }
    }

    public function success(Request $request): RedirectResponse
    {
        $intent = session('revolut_intent');
        $itemId = session('revolut_item_id');
        $duration = session('revolut_duration', 1);
        $orderId = session('revolut_order_id');
        $amount = session('revolut_amount', 0);

        if (! $intent || ! $itemId) {
            return redirect()->route('dashboard')->with('error', 'Ungültige Rückgabe.');
        }

        try {
            if ($intent === 'exam') {
                /** @var ExamTemplate|null $exam */
                $exam = ExamTemplate::query()->find($itemId);
                if (! $exam) {
                    return redirect()->route('dashboard')->with('error', 'Prüfung nicht gefunden.');
                }

                ExamEnrollment::updateOrCreate(
                    ['user_id' => $request->user()->id, 'exam_template_id' => $exam->id],
                    [
                        'status' => 'active',
                        'payment_status' => 'paid',
                        'enrolled_at' => now(),
                        'active_section' => 0,
                    ],
                );

                session()->forget(['revolut_intent', 'revolut_item_id', 'revolut_duration', 'revolut_order_id', 'revolut_amount']);

                return redirect()->route('dashboard')
                    ->with('success', 'Zahlung erfolgreich! Die Prüfung ist jetzt freigeschaltet.');
            }

            if ($intent === 'plan') {
                /** @var Plan|null $plan */
                $plan = Plan::query()->find($itemId);
                if (! $plan) {
                    return redirect()->route('billing')->with('error', 'Tarif nicht gefunden.');
                }

                $endsAt = now()->addMonths($duration ?? 1);

                Subscription::create([
                    'user_id' => $request->user()->id,
                    'plan_id' => $plan->id,
                    'provider' => 'revolut',
                    'provider_order_id' => $orderId,
                    'status' => 'active',
                    'price_paid' => $amount,
                    'duration_months' => $duration ?? 1,
                    'starts_at' => now(),
                    'ends_at' => $endsAt,
                ]);

                session()->forget(['revolut_intent', 'revolut_item_id', 'revolut_duration', 'revolut_order_id', 'revolut_amount']);

                return redirect()->route('billing')
                    ->with('success', 'Zahlung erfolgreich! Ihr Zugang ist bis zum '.$endsAt->format('d.m.Y').' aktiv.');
            }
        } catch (\Exception $e) {
            Log::error('Revolut success handling error', ['error' => $e->getMessage()]);

            return redirect()->route('dashboard')->with('error', 'Fehler bei der Verarbeitung der Zahlung.');
        }

        return redirect()->route('dashboard');
    }

    public function cancel(Request $request): RedirectResponse
    {
        session()->forget(['revolut_intent', 'revolut_item_id', 'revolut_duration', 'revolut_order_id', 'revolut_amount']);

        return redirect()->route('dashboard')->with('info', 'Revolut-Zahlung abgebrochen.');
    }

    public function webhook(Request $request): Response
    {
        $payload = $request->all();
        $event = $payload['event'] ?? '';

        Log::info('Revolut webhook received', ['event' => $event]);

        try {
            if ($event === 'ORDER_COMPLETED') {
                $orderId = $payload['order_id'] ?? $payload['id'] ?? '';
                $merchantOrderExtRef = $payload['merchant_order_ext_ref'] ?? '';

                if ($orderId && str_starts_with((string) $merchantOrderExtRef, 'exam_')) {
                    $examTemplateId = (int) str_replace('exam_', '', (string) $merchantOrderExtRef);

                    $service = app(RevolutService::class);
                    $order = $service->getOrder($orderId);

                    $payerEmail = $order['payer']['email'] ?? '';

                    if ($payerEmail) {
                        $user = User::query()->where('email', $payerEmail)->first();
                        if ($user) {
                            ExamEnrollment::updateOrCreate(
                                ['user_id' => $user->id, 'exam_template_id' => $examTemplateId],
                                [
                                    'status' => 'active',
                                    'payment_status' => 'paid',
                                    'enrolled_at' => now(),
                                    'active_section' => 0,
                                ],
                            );
                            Log::info('Revolut exam enrollment completed via webhook', [
                                'user_id' => $user->id,
                                'exam_template_id' => $examTemplateId,
                            ]);
                        }
                    }
                }

                if ($orderId && str_starts_with((string) $merchantOrderExtRef, 'plan_')) {
                    $parts = explode('_', (string) $merchantOrderExtRef);
                    $planId = (int) ($parts[1] ?? 0);
                    $duration = (int) ($parts[2] ?? 1);

                    $service = app(RevolutService::class);
                    $order = $service->getOrder($orderId);
                    $payerEmail = $order['payer']['email'] ?? '';

                    if ($payerEmail && $planId) {
                        $user = User::query()->where('email', $payerEmail)->first();
                        if ($user) {
                            $endsAt = now()->addMonths($duration);
                            Subscription::create([
                                'user_id' => $user->id,
                                'plan_id' => $planId,
                                'provider' => 'revolut',
                                'provider_order_id' => $orderId,
                                'status' => 'active',
                                'price_paid' => (float) ($order['amount'] / 100),
                                'duration_months' => $duration,
                                'starts_at' => now(),
                                'ends_at' => $endsAt,
                            ]);
                            Log::info('Revolut plan subscription created via webhook', [
                                'user_id' => $user->id,
                                'plan_id' => $planId,
                            ]);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            Log::error('Revolut webhook error', ['error' => $e->getMessage()]);
        }

        return response('OK', 200);
    }
}
