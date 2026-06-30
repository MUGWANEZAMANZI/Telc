<?php

namespace App\Listeners;

use App\Models\ExamEnrollment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Laravel\Cashier\Events\WebhookReceived;

class StripeEventListener
{
    public function handle(WebhookReceived $event): void
    {
        $payload = $event->payload;
        $type = $payload['type'] ?? '';
        $object = $payload['data']['object'] ?? [];

        match ($type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($object),
            default => null,
        };
    }

    private function handleCheckoutCompleted(array $session): void
    {
        $metadata = $session['metadata'] ?? [];
        $stripeCustomerId = $session['customer'] ?? null;

        if (! $stripeCustomerId) {
            return;
        }

        $user = User::query()->where('stripe_id', $stripeCustomerId)->first();

        if (! $user) {
            return;
        }

        $examTemplateId = $metadata['exam_template_id'] ?? null;

        if ($examTemplateId) {
            ExamEnrollment::updateOrCreate(
                ['user_id' => $user->id, 'exam_template_id' => $examTemplateId],
                [
                    'status' => 'active',
                    'payment_status' => 'paid',
                    'enrolled_at' => now(),
                ],
            );
            return;
        }

        $planId = $metadata['plan_id'] ?? null;

        if (! $planId) {
            return;
        }

        $stripeSubscriptionId = $session['subscription'] ?? null;

        if ($stripeSubscriptionId) {
            Subscription::query()
                ->where('stripe_id', $stripeSubscriptionId)
                ->update(['plan_id' => $planId]);
            return;
        }

        $plan = Plan::query()->find($planId);

        Subscription::create([
            'user_id' => $user->id,
            'plan_id' => $planId,
            'type' => 'default',
            'stripe_id' => $session['id'],
            'stripe_status' => 'active',
            'stripe_price' => $plan?->stripe_price_id,
            'ends_at' => $plan && $plan->interval !== 'month' ? now()->addMonth() : null,
        ]);
    }
}
