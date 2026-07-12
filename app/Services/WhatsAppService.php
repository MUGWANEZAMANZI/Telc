<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected ?string $token;

    protected ?string $phoneNumberId;

    protected string $version;

    public function __construct()
    {
        $this->token = config('services.whatsapp.token') ?: env('WHATSAPP_ACCESS_TOKEN');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id') ?: env('WHATSAPP_PHONE_NUMBER_ID');
        $this->version = config('services.whatsapp.version') ?: env('WHATSAPP_API_VERSION', 'v25.0');
    }

    /**
     * Send a template message.
     */
    public function sendTemplate(string $to, string $templateName, string $languageCode = 'en_US', array $components = []): array
    {
        return $this->sendMessage($to, [
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => [
                    'code' => $languageCode,
                ],
                'components' => $components,
            ],
        ]);
    }

    /**
     * Send a plain text message.
     */
    public function sendTextMessage(string $to, string $text): array
    {
        return $this->sendMessage($to, [
            'type' => 'text',
            'text' => [
                'body' => $text,
            ],
        ]);
    }

    /**
     * Send raw payload.
     */
    public function sendMessage(string $to, array $messageData): array
    {
        // Strip out any non-digit characters
        $cleanTo = preg_replace('/[^0-9]/', '', $to);

        $url = "https://graph.facebook.com/{$this->version}/{$this->phoneNumberId}/messages";

        $payload = array_merge([
            'messaging_product' => 'whatsapp',
            'recipient_type' => 'individual',
            'to' => $cleanTo,
        ], $messageData);

        Log::info('Sending WhatsApp message', [
            'url' => $url,
            'to' => $cleanTo,
            'payload' => $payload,
        ]);

        if (empty($this->token) || empty($this->phoneNumberId)) {
            Log::error('WhatsApp configuration is missing.');

            return [
                'success' => false,
                'error' => 'Configuration missing',
            ];
        }

        $response = Http::withToken($this->token)
            ->post($url, $payload);

        if ($response->successful()) {
            Log::info('WhatsApp message sent successfully', $response->json());

            return [
                'success' => true,
                'data' => $response->json(),
            ];
        }

        Log::error('WhatsApp message failed to send', [
            'status' => $response->status(),
            'body' => $response->body(),
        ]);

        return [
            'success' => false,
            'status' => $response->status(),
            'error' => $response->body(),
        ];
    }
}
