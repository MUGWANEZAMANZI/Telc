<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RevolutService
{
    protected string $secretKey;

    protected string $publicKey;

    protected string $baseUrl;

    protected string $mode;

    public function __construct()
    {
        $this->secretKey = config('services.revolut.secret');
        $this->publicKey = config('services.revolut.public');
        $this->mode = config('services.revolut.sandbox') ? 'sandbox' : 'prod';
        $this->baseUrl = $this->mode === 'sandbox'
            ? 'https://sandbox-merchant.revolut.com/api'
            : 'https://merchant.revolut.com/api';
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    public function getMode(): string
    {
        return $this->mode;
    }

    /** @return array<string, mixed> */
    public function createOrder(float $amount, string $currency = 'EUR', ?string $description = null, ?string $customData = null): array
    {
        $payload = [
            'amount' => (int) round($amount * 100),
            'currency' => $currency,
        ];

        if ($description) {
            $payload['description'] = $description;
        }

        if ($customData) {
            $payload['merchant_order_ext_ref'] = $customData;
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->secretKey,
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post("{$this->baseUrl}/1.0/orders", $payload);

        if ($response->failed()) {
            Log::error('Revolut order creation failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('Revolut order creation failed: '.$response->body());
        }

        return $response->json();
    }

    /** @return array<string, mixed> */
    public function captureOrder(string $orderId): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->secretKey,
            'Accept' => 'application/json',
        ])->post("{$this->baseUrl}/1.0/orders/{$orderId}/capture");

        if ($response->failed()) {
            Log::error('Revolut order capture failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('Revolut order capture failed: '.$response->body());
        }

        return $response->json();
    }

    /** @return array<string, mixed> */
    public function getOrder(string $orderId): array
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer '.$this->secretKey,
            'Accept' => 'application/json',
        ])->get("{$this->baseUrl}/1.0/orders/{$orderId}");

        if ($response->failed()) {
            Log::error('Revolut get order failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \RuntimeException('Revolut get order failed: '.$response->body());
        }

        return $response->json();
    }

    /** @param array<string, mixed> $headers */
    public function verifyWebhookSignature(array $headers, string $body): bool
    {
        $signature = $headers['revolut-signature'] ?? $headers['Revolut-Signature'] ?? '';
        if (! $signature) {
            return false;
        }

        $parts = explode(',', $signature);
        $timestamp = '';
        $expectedSignature = '';

        foreach ($parts as $part) {
            if (str_starts_with($part, 't=')) {
                $timestamp = substr($part, 2);
            } elseif (str_starts_with($part, 's=')) {
                $expectedSignature = substr($part, 2);
            }
        }

        if (! $timestamp || ! $expectedSignature) {
            return false;
        }

        $signedPayload = $timestamp.'.'.$body;
        $computedSignature = hash_hmac('sha256', $signedPayload, $this->secretKey);

        return hash_equals($computedSignature, $expectedSignature);
    }
}
