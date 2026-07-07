<?php

namespace App\Services;

use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalService
{
    protected PayPalClient $provider;

    public function __construct()
    {
        $this->provider = new PayPalClient;
        $this->provider->setApiCredentials(config('paypal'));
        $this->provider->withExceptions();
        $this->provider->getAccessToken();
    }

    public function createOrder(float $amount, string $description, string $returnUrl, string $cancelUrl): array
    {
        $order = $this->provider->createOrder([
            'intent' => 'CAPTURE',
            'purchase_units' => [
                [
                    'amount' => [
                        'currency_code' => 'EUR',
                        'value' => number_format($amount, 2, '.', ''),
                    ],
                    'description' => $description,
                ],
            ],
            'application_context' => [
                'brand_name' => config('app.name'),
                'locale' => 'de-DE',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'PAY_NOW',
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
            ],
        ]);

        return $order;
    }

    public function captureOrder(string $orderId): array
    {
        return $this->provider->capturePaymentOrder($orderId);
    }

    public function createSubscription(string $paypalPlanId, array $subscriber, string $returnUrl, string $cancelUrl): array
    {
        $subscription = $this->provider->createSubscription([
            'plan_id' => $paypalPlanId,
            'quantity' => 1,
            'subscriber' => $subscriber,
            'application_context' => [
                'brand_name' => config('app.name'),
                'locale' => 'de-DE',
                'shipping_preference' => 'NO_SHIPPING',
                'user_action' => 'SUBSCRIBE_NOW',
                'return_url' => $returnUrl,
                'cancel_url' => $cancelUrl,
            ],
        ]);

        return $subscription;
    }

    public function cancelSubscription(string $subscriptionId): bool
    {
        try {
            $this->provider->cancelSubscription($subscriptionId, 'Vom Benutzer gekündigt.');

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function showSubscriptionDetails(string $subscriptionId): array
    {
        return $this->provider->showSubscriptionDetails($subscriptionId);
    }

    public function showOrderDetails(string $orderId): array
    {
        return $this->provider->showOrderDetails($orderId);
    }

    public function getApprovalLink(array $response): ?string
    {
        foreach ($response['links'] ?? [] as $link) {
            if ($link['rel'] === 'approve') {
                return $link['href'];
            }
        }

        return null;
    }

    public function verifyWebhook(array $headers, string $body): bool
    {
        // PayPal webhook verification using the package's built-in IPN verification
        try {
            $verification = $this->provider->verifyIPN($body);

            return $verification;
        } catch (\Exception $e) {
            return false;
        }
    }
}
