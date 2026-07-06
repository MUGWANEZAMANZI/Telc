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

    public function planCheckout(Request $request): RedirectResponse
    {
        $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'duration' => ['required', 'integer', 'in:1,3,12'],
        ]);

        $plan = Plan::query()->findOrFail($request->plan_id);
        $duration = (int) $request->duration;
        $amount = (float) $plan->price * $duration;

        $service = app(PayPalService::class);

        $order = $service->createOrder(
            $amount,
            $plan->name.' ('.$duration.' Monat(e))',
            route('paypal.success'),
            route('paypal.cancel'),
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
        $intent = session('paypal_intent');

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

        $endsAt = now()->addMonths($duration);

        Subscription::create([
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

    public function webhook(Request $request): \Illuminate\Http\Response
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
