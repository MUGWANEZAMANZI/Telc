<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use Paypack\Paypack;

class PaypackService
{
    protected bool $configured = false;

    public function __construct()
    {
        $clientId = config('services.paypack.client_id');
        $clientSecret = config('services.paypack.client_secret');
        $webhookMode = config('services.paypack.webhook_mode', 'development');

        if ($clientId && $clientSecret) {
            try {
                Paypack::config([
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'webhook_mode' => $webhookMode,
                ]);
                $this->configured = true;
            } catch (\Throwable $e) {
                Log::error('Paypack SDK configuration failed', ['error' => $e->getMessage()]);
            }
        }
    }

    public function isConfigured(): bool
    {
        return $this->configured;
    }

    /**
     * Request cashin from a mobile money phone number.
     *
     * @param string $phone Phone number e.g. "078xxxxxxx"
     * @param float|int $amount Amount to charge
     * @return array<string, mixed>
     */
    public function cashin(string $phone, float|int $amount): array
    {
        if (! $this->configured) {
            // Ensure config call is attempted again if env vars were added dynamically
            $clientId = config('services.paypack.client_id');
            $clientSecret = config('services.paypack.client_secret');
            $webhookMode = config('services.paypack.webhook_mode', 'development');

            if ($clientId && $clientSecret) {
                Paypack::config([
                    'client_id' => $clientId,
                    'client_secret' => $clientSecret,
                    'webhook_mode' => $webhookMode,
                ]);
                $this->configured = true;
            } else {
                throw new \RuntimeException('Paypack credentials (CLIENT_ID, CLIENT_SECRET) are not configured in environment.');
            }
        }

        try {
            /** @var array<string, mixed> $result */
            $result = Paypack::Cashin([
                'phone' => $phone,
                'amount' => (int) round($amount),
            ]);

            return $result;
        } catch (\Throwable $e) {
            Log::error('Paypack cashin error', [
                'phone' => $phone,
                'amount' => $amount,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * Fetch transaction by reference.
     *
     * @param string $ref
     * @return array<string, mixed>
     */
    public function getTransaction(string $ref): array
    {
        if (! $this->configured) {
            Paypack::config([
                'client_id' => config('services.paypack.client_id'),
                'client_secret' => config('services.paypack.client_secret'),
                'webhook_mode' => config('services.paypack.webhook_mode', 'development'),
            ]);
            $this->configured = true;
        }

        try {
            /** @var array<string, mixed> $result */
            $result = Paypack::Transaction($ref);

            return $result;
        } catch (\Throwable $e) {
            Log::error('Paypack getTransaction error', [
                'ref' => $ref,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException($e->getMessage());
        }
    }

    /**
     * Fetch events log.
     *
     * @param array<string, mixed> $params
     * @return array<string, mixed>
     */
    public function getEvents(array $params = []): array
    {
        if (! $this->configured) {
            Paypack::config([
                'client_id' => config('services.paypack.client_id'),
                'client_secret' => config('services.paypack.client_secret'),
                'webhook_mode' => config('services.paypack.webhook_mode', 'development'),
            ]);
            $this->configured = true;
        }

        try {
            /** @var array<string, mixed> $result */
            $result = Paypack::Events($params);

            return $result;
        } catch (\Throwable $e) {
            Log::error('Paypack getEvents error', [
                'params' => $params,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException($e->getMessage());
        }
    }
}
