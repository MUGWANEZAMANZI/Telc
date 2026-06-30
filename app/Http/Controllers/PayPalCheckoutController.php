<?php

namespace App\Http\Controllers;

use App\Models\ExamEnrollment;
use App\Models\ExamTemplate;
use App\Models\Plan;
use App\Models\Subscription;
use App\Services\PayPalService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class PayPalCheckoutController extends Controller
{
    public function examCheckout(Request $request, ExamTemplate $examTemplate): RedirectResponse
    {
        $service = app(PayPalService::class);

        $order = $service->createOrder(
            (float) ($examTemplate->price ?? 5.00),
            $examTemplate->title,
            route('paypal.success'),
            route('paypal.cancel'),
        );

        if (! isset($order['id'])) {
            return redirect()->route('dashboard')
                ->with('error', 'PayPal-Checkout konnte nicht erstellt werden.');
        }

        session([
            'paypal_intent' => 'exam',
            'exam_template_id' => $examTemplate->id,
        ]);

        $approvalUrl = $service->getApprovalLink($order);

        if ($approvalUrl) {
            return redirect()->away($approvalUrl);
        }

        return redirect()->route('dashboard')
            ->with('error', 'PayPal-Zahlungslink konnte nicht gefunden werden.');
    }

    public function planCheckout(Request $request, Plan $plan): RedirectResponse
    {
        if ($plan->interval !== 'month') {
            return redirect()->route('pricing')
                ->with('error', 'Dieser Plan kann nicht als Abonnement abgeschlossen werden.');
        }

        if (! $plan->paypal_plan_id) {
            return redirect()->route('pricing')
                ->with('error', 'Für diesen Plan ist kein PayPal-Abo konfiguriert.');
        }

        $service = app(PayPalService::class);

        $subscription = $service->createSubscription(
            $plan->paypal_plan_id,
            [
                'name' => [
                    'given_name' => $request->user()->name,
                    'surname' => '',
                ],
                'email_address' => $request->user()->email,
            ],
            route('paypal.success'),
            route('paypal.cancel'),
        );

        if (! isset($subscription['id'])) {
            return redirect()->route('pricing')
                ->with('error', 'PayPal-Abo konnte nicht erstellt werden.');
        }

        session([
            'paypal_intent' => 'subscription',
            'plan_id' => $plan->id,
            'paypal_subscription_id' => $subscription['id'],
        ]);

        $approvalUrl = $service->getApprovalLink($subscription);

        if ($approvalUrl) {
            return redirect()->away($approvalUrl);
        }

        return redirect()->route('pricing')
            ->with('error', 'PayPal-Zahlungslink konnte nicht gefunden werden.');
    }

    public function success(Request $request): RedirectResponse
    {
        $intent = session('paypal_intent');

        if ($intent === 'exam') {
            return $this->handleExamSuccess($request);
        }

        if ($intent === 'subscription') {
            return $this->handleSubscriptionSuccess($request);
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

            session()->forget(['paypal_intent', 'exam_template_id']);

            return redirect()->route('dashboard')
                ->with('success', 'Zahlung erfolgreich! Die Prüfung ist jetzt freigeschaltet.');
        }

        return redirect()->route('dashboard')
            ->with('error', 'PayPal-Zahlung fehlgeschlagen. Bitte versuchen Sie es erneut.');
    }

    protected function handleSubscriptionSuccess(Request $request): RedirectResponse
    {
        $planId = session('plan_id');
        $paypalSubId = session('paypal_subscription_id');

        if (! $planId || ! $paypalSubId) {
            return redirect()->route('billing')
                ->with('error', 'Ungültige PayPal-Rückgabe.');
        }

        Subscription::create([
            'user_id' => $request->user()->id,
            'plan_id' => $planId,
            'type' => 'default',
            'stripe_id' => 'paypal_'.$paypalSubId,
            'paypal_subscription_id' => $paypalSubId,
            'stripe_status' => 'active',
            'quantity' => 1,
        ]);

        session()->forget(['paypal_intent', 'plan_id', 'paypal_subscription_id']);

        return redirect()->route('billing')
            ->with('success', 'Abonnement erfolgreich eingerichtet!');
    }

    public function cancel(Request $request): RedirectResponse
    {
        session()->forget(['paypal_intent', 'exam_template_id', 'plan_id', 'paypal_subscription_id']);

        return redirect()->route('dashboard')
            ->with('info', 'PayPal-Zahlung abgebrochen.');
    }

    public function webhook(Request $request): \Illuminate\Http\Response
    {
        $payload = $request->all();
        $eventType = $payload['event_type'] ?? '';

        Log::info('PayPal webhook received', ['event_type' => $eventType, 'payload' => $payload]);

        try {
            match ($eventType) {
                'BILLING.SUBSCRIPTION.ACTIVATED' => $this->handleSubscriptionActivated($payload),
                'BILLING.SUBSCRIPTION.CANCELLED' => $this->handleSubscriptionCancelled($payload),
                'PAYMENT.CAPTURE.COMPLETED' => $this->handlePaymentCaptureCompleted($payload),
                default => Log::info('PayPal webhook ignored', ['event_type' => $eventType]),
            };
        } catch (\Exception $e) {
            Log::error('PayPal webhook error', ['error' => $e->getMessage()]);
        }

        return response('OK', 200);
    }

    protected function handleSubscriptionActivated(array $payload): void
    {
        $subId = $payload['resource']['id'] ?? '';

        if (! $subId) {
            return;
        }

        $subscription = Subscription::query()
            ->where('paypal_subscription_id', $subId)
            ->first();

        if ($subscription && $subscription->stripe_status !== 'active') {
            $subscription->update(['stripe_status' => 'active']);
            Log::info('PayPal subscription activated', ['subscription_id' => $subId]);
        }
    }

    protected function handleSubscriptionCancelled(array $payload): void
    {
        $subId = $payload['resource']['id'] ?? '';

        if (! $subId) {
            return;
        }

        $subscription = Subscription::query()
            ->where('paypal_subscription_id', $subId)
            ->first();

        if ($subscription && $subscription->stripe_status !== 'cancelled') {
            $subscription->update(['stripe_status' => 'cancelled']);
            Log::info('PayPal subscription cancelled', ['subscription_id' => $subId]);
        }
    }

    protected function handlePaymentCaptureCompleted(array $payload): void
    {
        $customId = $payload['resource']['custom_id'] ?? '';

        if (! str_starts_with((string) $customId, 'exam_')) {
            return;
        }

        $examTemplateId = (int) str_replace('exam_', '', (string) $customId);
        $payerEmail = $payload['resource']['payer']['email_address'] ?? '';

        $user = \App\Models\User::query()->where('email', $payerEmail)->first();

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
}
