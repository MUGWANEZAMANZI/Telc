<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class CheckoutController extends Controller
{
    public function checkout(Request $request): RedirectResponse
    {
        $plan = Plan::query()->findOrFail($request->query('plan_id'));
        $payLink = config('services.paypal.me_url');

        // Automatically activate subscription/enrollment simulation when clicking checkout
        $user = Auth::user();
        Subscription::updateOrCreate(
            ['user_id' => $user->id, 'plan_id' => $plan->id],
            [
                'stripe_subscription_id' => 'paypal_sub_' . uniqid(),
                'stripe_status' => 'active',
            ]
        );

        return redirect()->away($payLink);
    }

    public function webhook(Request $request): void
    {
        Stripe::setApiKey(config('stripe.secret'));

        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('stripe.webhook_secret'),
            );
        } catch (\UnexpectedValueException) {
            abort(400);
        } catch (\Stripe\Exception\SignatureVerificationException) {
            abort(400);
        }

        match ($event->type) {
            'checkout.session.completed' => $this->handleCheckoutCompleted($event->data->object),
            'customer.subscription.updated' => $this->handleSubscriptionUpdated($event->data->object),
            'customer.subscription.deleted' => $this->handleSubscriptionDeleted($event->data->object),
            default => null,
        };
    }

    private function handleCheckoutCompleted(Session $session): void
    {
        $planId = (int) ($session->metadata->plan_id ?? 0);
        $userId = (int) ($session->metadata->user_id ?? 0);

        if (! $planId || ! $userId) {
            return;
        }

        $subscriptionId = $session->subscription;

        Subscription::query()->updateOrCreate(
            ['user_id' => $userId, 'plan_id' => $planId],
            [
                'stripe_subscription_id' => $subscriptionId,
                'stripe_status' => 'active',
            ],
        );
    }

    private function handleSubscriptionUpdated(\Stripe\Subscription $stripeSubscription): void
    {
        $subscription = Subscription::query()
            ->where('stripe_subscription_id', $stripeSubscription->id)
            ->first();

        if (! $subscription) {
            return;
        }

        $subscription->update([
            'stripe_status' => $stripeSubscription->status,
            'ends_at' => $stripeSubscription->current_period_end
                ? now()->createFromTimestamp($stripeSubscription->current_period_end)
                : null,
        ]);
    }

    private function handleSubscriptionDeleted(\Stripe\Subscription $stripeSubscription): void
    {
        Subscription::query()
            ->where('stripe_subscription_id', $stripeSubscription->id)
            ->update(['stripe_status' => 'canceled', 'ends_at' => now()]);
    }
}
