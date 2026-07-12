<?php

use App\Services\WhatsAppService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

test('whatsapp webhook verification succeeds with correct token', function () {
    config(['services.whatsapp.verify_token' => 'my_secret_verify_token']);

    $response = $this->get('api/webhooks/whatsapp?hub_mode=subscribe&hub_verify_token=my_secret_verify_token&hub_challenge=challenge_accepted');

    $response->assertStatus(200);
    expect($response->getContent())->toBe('challenge_accepted');
});

test('whatsapp webhook verification fails with incorrect token', function () {
    config(['services.whatsapp.verify_token' => 'my_secret_verify_token']);

    $response = $this->get('api/webhooks/whatsapp?hub_mode=subscribe&hub_verify_token=wrong_token&hub_challenge=challenge_accepted');

    $response->assertStatus(403);
});

test('whatsapp webhook handles payload successfully', function () {
    $payload = [
        'object' => 'whatsapp_business_account',
        'entry' => [
            [
                'id' => '1539766337933924',
                'changes' => [
                    [
                        'value' => [
                            'messaging_product' => 'whatsapp',
                            'metadata' => [
                                'display_phone_number' => '15551469614',
                                'phone_number_id' => '1180332071833575'
                            ],
                            'messages' => [
                                [
                                    'from' => '250734056532',
                                    'id' => 'ABGGFlA5FpaGAPIBGgNVb1p',
                                    'timestamp' => '1604928172',
                                    'text' => [
                                        'body' => 'Hello there!'
                                    ],
                                    'type' => 'text'
                                ]
                            ]
                        ],
                        'field' => 'messages'
                    ]
                ]
            ]
        ]
    ];

    $response = $this->postJson('api/webhooks/whatsapp', $payload);

    $response->assertStatus(200);
    $response->assertJson(['status' => 'success']);
});

test('WhatsAppService sends text messages correctly', function () {
    config([
        'services.whatsapp.token' => 'test_token',
        'services.whatsapp.phone_number_id' => 'test_phone_id',
        'services.whatsapp.version' => 'v25.0',
    ]);

    Http::fake([
        'https://graph.facebook.com/v25.0/test_phone_id/messages' => Http::response([
            'messaging_product' => 'whatsapp',
            'contacts' => [['input' => '250734056532', 'wa_id' => '250734056532']],
            'messages' => [['id' => 'wamid.HBgLMjUwNzM0MDU2NTMyFQIAERgSRTI0RDA0RDk1QTNEMDcyN0FFQUE=']]
        ], 200)
    ]);

    $service = new WhatsAppService();
    $result = $service->sendTextMessage('+250 734-056-532', 'Hello World');

    expect($result['success'])->toBeTrue();
    expect($result['data']['messages'][0]['id'])->toBe('wamid.HBgLMjUwNzM0MDU2NTMyFQIAERgSRTI0RDA0RDk1QTNEMDcyN0FFQUE=');

    Http::assertSent(function ($request) {
        return $request->url() === 'https://graph.facebook.com/v25.0/test_phone_id/messages'
            && $request->hasHeader('Authorization', 'Bearer test_token')
            && $request['to'] === '250734056532'
            && $request['text']['body'] === 'Hello World';
    });
});

test('WhatsAppService sends template messages correctly', function () {
    config([
        'services.whatsapp.token' => 'test_token',
        'services.whatsapp.phone_number_id' => 'test_phone_id',
        'services.whatsapp.version' => 'v25.0',
    ]);

    Http::fake([
        'https://graph.facebook.com/v25.0/test_phone_id/messages' => Http::response(['success' => true], 200)
    ]);

    $service = new WhatsAppService();
    $result = $service->sendTemplate('250734056532', 'order_confirmation', 'en_US');

    expect($result['success'])->toBeTrue();

    Http::assertSent(function ($request) {
        return $request['template']['name'] === 'order_confirmation'
            && $request['template']['language']['code'] === 'en_US';
    });
});
