<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class WhatsAppWebhookController extends Controller
{
    /**
     * Handle verification from Meta.
     */
    public function verify(Request $request): Response
    {
        // Try both underscore and dot notation, as PHP may convert dot notation in query parameters
        $mode = $request->query('hub_mode') ?: $request->query('hub.mode');
        $token = $request->query('hub_verify_token') ?: $request->query('hub.verify_token');
        $challenge = $request->query('hub_challenge') ?: $request->query('hub.challenge');

        $verifyToken = config('services.whatsapp.verify_token', 'telc_whatsapp_verify_token');

        Log::info('WhatsApp Webhook verification request', [
            'mode' => $mode,
            'token' => $token,
            'challenge' => $challenge,
            'expected_token' => $verifyToken,
        ]);

        if ($mode === 'subscribe' && $token === $verifyToken) {
            return response($challenge, 200)->header('Content-Type', 'text/plain');
        }

        return response('Forbidden', 403);
    }

    /**
     * Handle incoming webhooks.
     */
    public function handle(Request $request): Response
    {
        $payload = $request->all();

        Log::info('WhatsApp Webhook received', $payload);

        return response()->json(['status' => 'success']);
    }
}
