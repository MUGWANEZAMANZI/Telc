<?php

namespace App\Services;

use Illuminate\Http\Request;
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

    /** @return array<string, mixed> */
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

        /** @var array<string, mixed> $order */
        return $order;
    }

    /** @return array<string, mixed> */
    public function captureOrder(string $orderId): array
    {
        $result = $this->provider->capturePaymentOrder($orderId);

        /** @var array<string, mixed> $result */
        return $result;
    }

    /**
     * @param  array<string, mixed>  $subscriber
     * @return array<string, mixed>
     */
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

        /** @var array<string, mixed> $subscription */
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

    /** @return array<string, mixed> */
    public function showSubscriptionDetails(string $subscriptionId): array
    {
        $result = $this->provider->showSubscriptionDetails($subscriptionId);

        /** @var array<string, mixed> $result */
        return $result;
    }

    /** @return array<string, mixed> */
    public function showOrderDetails(string $orderId): array
    {
        $result = $this->provider->showOrderDetails($orderId);

        /** @var array<string, mixed> $result */
        return $result;
    }

    /** @param array<string, mixed> $response */
    public function getApprovalLink(array $response): ?string
    {
        foreach ($response['links'] ?? [] as $link) {
            if ($link['rel'] === 'approve') {
                return $link['href'];
            }
        }

        return null;
    }

    /** @param array<string, mixed> $headers */
    public function verifyWebhook(array $headers, string $body): bool
    {
        // PayPal webhook verification using the package's built-in IPN verification
        try {
            $request = new Request([], [], [], [], [], [], $body);
            $request->setMethod('POST');

            return (bool) $this->provider->verifyIPN($request);
        } catch (\Exception $e) {
            return false;
        }
    }
}
