<?php

namespace App\Http\Controllers;

use App\Mail\SubscriptionConfirmedMail;
use App\Models\ExamEnrollment;
use App\Models\ExamTemplate;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\PayPalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PayPalCheckoutController extends Controller
{
    public function examCheckout(Request $request, ExamTemplate $examTemplate): RedirectResponse
    {
        return redirect()->route('pricing')
            ->with('error', 'Einmalige Prüfungen sind nicht mehr verfügbar. Bitte erwerben Sie ein Abonnement.');
    }

    public function planCheckout(Request $request): RedirectResponse
    {
        $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'duration' => ['required', 'integer', 'in:1,3,6,12'],
        ]);

        /** @var Plan $plan */
        $plan = Plan::query()->findOrFail($request->plan_id);
        $duration = (int) $request->duration;
        $amount = (float) $plan->getPriceForDuration($duration);

        $service = app(PayPalService::class);

        $order = $service->createOrder(
            $amount,
            $plan->name.' ('.$duration.' Monat(e))',
            route('paypal.success'),
            route('paypal.cancel'),
            'plan_'.$plan->id.'_'.$duration
        );

        if (! isset($order['id'])) {
            return redirect()->route('pricing')
                ->with('error', 'PayPal-Zahlung konnte nicht erstellt werden.');
        }

        session([
            'paypal_intent' => 'plan',
            'plan_id' => $plan->id,
            'paypal_duration' => $duration,
            'paypal_amount' => $amount,
            'paypal_order_id' => $order['id'],
        ]);

        $approvalUrl = $service->getApprovalLink($order);

        if ($approvalUrl) {
            return redirect()->away($approvalUrl);
        }

        return redirect()->route('pricing')
            ->with('error', 'PayPal-Zahlungslink konnte nicht gefunden werden.');
    }

    public function success(Request $request): RedirectResponse
    {
        $token = $request->query('token');
        $intent = session('paypal_intent');

        if (! $intent && $token) {
            try {
                $service = app(PayPalService::class);
                $orderDetails = $service->showOrderDetails($token);
                $customId = $orderDetails['purchase_units'][0]['custom_id'] ?? '';

                if (str_starts_with($customId, 'plan_')) {
                    $parts = explode('_', $customId);
                    $planId = (int) ($parts[1] ?? 0);
                    $duration = (int) ($parts[2] ?? 1);
                    session([
                        'paypal_intent' => 'plan',
                        'plan_id' => $planId,
                        'paypal_duration' => $duration,
                        'paypal_amount' => (float) ($orderDetails['purchase_units'][0]['amount']['value'] ?? 0),
                        'paypal_order_id' => $token,
                    ]);
                    $intent = 'plan';
                } elseif (str_starts_with($customId, 'exam_')) {
                    $examTemplateId = (int) str_replace('exam_', '', $customId);
                    session([
                        'paypal_intent' => 'exam',
                        'exam_template_id' => $examTemplateId,
                        'paypal_order_id' => $token,
                    ]);
                    $intent = 'exam';
                }
            } catch (\Exception $e) {
                Log::error('PayPal success session recovery failed', ['error' => $e->getMessage()]);
            }
        }

        if ($intent === 'exam') {
            return $this->handleExamSuccess($request);
        }

        if ($intent === 'plan') {
            return $this->handlePlanSuccess($request);
        }

        return redirect()->route('dashboard');
    }

    protected function handleExamSuccess(Request $request): RedirectResponse
    {
        $token = $request->query('token');
        $examTemplateId = session('exam_template_id');

        if (! $token || ! $examTemplateId) {
            return redirect()->route('dashboard')
                ->with('error', 'Ungültige PayPal-Rückgabe.');
        }

        $service = app(PayPalService::class);
        $capture = $service->captureOrder($token);

        if (($capture['status'] ?? '') === 'COMPLETED') {
            ExamEnrollment::updateOrCreate(
                ['user_id' => $request->user()->id, 'exam_template_id' => $examTemplateId],
                [
                    'status' => 'active',
                    'payment_status' => 'paid',
                    'paypal_order_id' => $token,
                    'enrolled_at' => now(),
                    'active_section' => 0,
                ],
            );

            session()->forget(['paypal_intent', 'exam_template_id', 'plan_id', 'paypal_duration', 'paypal_amount', 'paypal_order_id']);

            return redirect()->route('dashboard')
                ->with('success', 'Zahlung erfolgreich! Die Prüfung ist jetzt freigeschaltet.');
        }

        return redirect()->route('dashboard')
            ->with('error', 'PayPal-Zahlung fehlgeschlagen. Bitte versuchen Sie es erneut.');
    }

    protected function handlePlanSuccess(Request $request): RedirectResponse
    {
        $planId = session('plan_id');
        $duration = session('paypal_duration', 1);
        $amount = session('paypal_amount', 0);
        $orderId = session('paypal_order_id');

        if (! $planId) {
            return redirect()->route('billing')
                ->with('error', 'Ungültige PayPal-Rückgabe.');
        }

        $token = $request->query('token');

        if ($token) {
            $service = app(PayPalService::class);
            $capture = $service->captureOrder($token);

            if (($capture['status'] ?? '') !== 'COMPLETED') {
                return redirect()->route('billing')
                    ->with('error', 'PayPal-Zahlung fehlgeschlagen. Bitte versuchen Sie es erneut.');
            }
        }

        // Deactivate any existing active subscriptions for this user
        Subscription::query()
            ->where('user_id', $request->user()->id)
            ->where('status', 'active')
            ->update(['status' => 'cancelled', 'ends_at' => now()]);

        $endsAt = now()->addMonths($duration);

        $subscription = Subscription::create([
            'user_id' => $request->user()->id,
            'plan_id' => $planId,
            'provider' => 'paypal',
            'provider_order_id' => $orderId,
            'status' => 'active',
            'price_paid' => $amount,
            'duration_months' => $duration,
            'starts_at' => now(),
            'ends_at' => $endsAt,
        ]);

        $plan = Plan::query()->find($planId);
        if ($plan instanceof Plan) {
            try {
                Mail::mailer('zoho_sales')->to($request->user()->email)->send(new SubscriptionConfirmedMail($request->user(), $subscription, $plan));
            } catch (\Throwable $e) {
                Log::error('Could not send subscription confirmation email (PayPal success): '.$e->getMessage());
            }
        }

        session()->forget(['paypal_intent', 'exam_template_id', 'plan_id', 'paypal_duration', 'paypal_amount', 'paypal_order_id']);

        return redirect()->route('billing')
            ->with('success', 'Zahlung erfolgreich! Ihr Zugang ist bis zum '.$endsAt->format('d.m.Y').' aktiv.');
    }

    public function cancel(Request $request): RedirectResponse
    {
        session()->forget(['paypal_intent', 'exam_template_id', 'plan_id', 'paypal_duration', 'paypal_amount', 'paypal_order_id']);

        return redirect()->route('dashboard')
            ->with('info', 'PayPal-Zahlung abgebrochen.');
    }

    public function webhook(Request $request): Response
    {
        $payload = $request->all();
        $eventType = $payload['event_type'] ?? '';

        Log::info('PayPal webhook received', ['event_type' => $eventType]);

        try {
            if ($eventType === 'PAYMENT.CAPTURE.COMPLETED') {
                $this->handlePaymentCaptureCompleted($payload);
            }
        } catch (\Exception $e) {
            Log::error('PayPal webhook error', ['error' => $e->getMessage()]);
        }

        return response('OK', 200);
    }

    /** @param array<string, mixed> $payload */
    protected function handlePaymentCaptureCompleted(array $payload): void
    {
        $customId = $payload['resource']['custom_id'] ?? '';

        if (! str_starts_with((string) $customId, 'exam_') && ! str_starts_with((string) $customId, 'plan_')) {
            return;
        }

        if (str_starts_with((string) $customId, 'exam_')) {
            $examTemplateId = (int) str_replace('exam_', '', (string) $customId);
            $payerEmail = $payload['resource']['payer']['email_address'] ?? '';

            $user = User::query()->where('email', $payerEmail)->first();

            if (! $user) {
                Log::warning('PayPal webhook: user not found', ['email' => $payerEmail]);

                return;
            }

            ExamEnrollment::updateOrCreate(
                ['user_id' => $user->id, 'exam_template_id' => $examTemplateId],
                [
                    'status' => 'active',
                    'payment_status' => 'paid',
                    'enrolled_at' => now(),
                    'active_section' => 0,
                ],
            );

            Log::info('PayPal exam enrollment completed via webhook', [
                'user_id' => $user->id,
                'exam_template_id' => $examTemplateId,
            ]);
        }

        if (str_starts_with((string) $customId, 'plan_')) {
            $parts = explode('_', (string) $customId);
            $planId = (int) ($parts[1] ?? 0);
            $duration = (int) ($parts[2] ?? 1);
            $payerEmail = $payload['resource']['payer']['email_address'] ?? '';

            $user = User::query()->where('email', $payerEmail)->first();

            if (! $user || ! $planId) {
                Log::warning('PayPal webhook: user or plan not found', ['email' => $payerEmail, 'plan_id' => $planId]);

                return;
            }

            // Deactivate any existing active subscriptions for this user
            Subscription::query()
                ->where('user_id', $user->id)
                ->where('status', 'active')
                ->update(['status' => 'cancelled', 'ends_at' => now()]);

            $endsAt = now()->addMonths($duration);
            $amount = (float) ($payload['resource']['amount']['value'] ?? 0);
            if ($amount <= 0) {
                $plan = Plan::query()->find($planId);
                if ($plan) {
                    $amount = $plan->getPriceForDuration($duration);
                }
            }

            $subscription = Subscription::create([
                'user_id' => $user->id,
                'plan_id' => $planId,
                'provider' => 'paypal',
                'provider_order_id' => $payload['resource']['id'] ?? '',
                'status' => 'active',
                'price_paid' => $amount,
                'duration_months' => $duration,
                'starts_at' => now(),
                'ends_at' => $endsAt,
            ]);

            $plan = Plan::query()->find($planId);
            if ($plan instanceof Plan) {
                try {
                    Mail::mailer('zoho_sales')->to($user->email)->send(new SubscriptionConfirmedMail($user, $subscription, $plan));
                } catch (\Throwable $e) {
                    Log::error('Could not send subscription confirmation email (PayPal webhook): '.$e->getMessage());
                }
            }

            Log::info('PayPal plan subscription created via webhook', [
                'user_id' => $user->id,
                'plan_id' => $planId,
            ]);
        }
    }
}
