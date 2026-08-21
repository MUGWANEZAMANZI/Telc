<?php

use App\Livewire\VoiceInput;
use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
use App\Models\User;
use App\Models\VoiceTopic;
use Gemini\Client;
use Gemini\Contracts\TransporterContract;
use Gemini\Transporters\DTOs\ResponseDTO;
use Livewire\Livewire;

test('voice topics are seeded on mount if database is empty', function () {
    VoiceTopic::query()->delete();

    expect(VoiceTopic::count())->toBe(0);

    Livewire::test(VoiceInput::class);

    expect(VoiceTopic::count())->toBeGreaterThan(0);
});

test('voice practice can filter topics by provider and level', function () {
    VoiceTopic::query()->delete();

    VoiceTopic::create(['title' => 'Topic 1 B2 goethe', 'level' => 'B2', 'exam_provider' => 'goethe', 'is_active' => true]);
    VoiceTopic::create(['title' => 'Topic 2 B2 telc', 'level' => 'B2', 'exam_provider' => 'telc', 'is_active' => true]);
    VoiceTopic::create(['title' => 'Topic 3 C1 goethe', 'level' => 'C1', 'exam_provider' => 'goethe', 'is_active' => true]);

    Livewire::test(VoiceInput::class)
        ->set('selectedProvider', 'goethe')
        ->set('selectedLevel', 'B2')
        ->assertSee('Topic 1 B2 goethe')
        ->assertDontSee('Topic 2 B2 telc')
        ->assertDontSee('Topic 3 C1 goethe')
        ->set('selectedLevel', 'C1')
        ->assertSee('Topic 3 C1 goethe')
        ->assertDontSee('Topic 1 B2 goethe');
});

test('non-subscribed users are limited to 2 speaking practices', function () {
    VoiceTopic::query()->delete();
    $topic = VoiceTopic::create([
        'title' => 'Topic 1',
        'counter_topic' => 'Counter Topic 1',
        'level' => 'B1',
        'exam_provider' => 'telc',
        'is_active' => true,
    ]);

    $template = ExamTemplate::create([
        'title' => 'B1 telc Test',
        'level' => 'B1',
        'exam_provider' => 'telc',
        'theme' => [],
        'timings' => [],
        'sections' => [],
        'is_published' => true,
    ]);

    $student = User::factory()->create(['role' => 'student']);
    $this->actingAs($student);

    // Create 2 speaking attempts for this user
    ExamAttempt::create([
        'exam_template_id' => $template->id,
        'user_id' => $student->id,
        'answers' => [],
        'result' => ['is_speaking_only' => true],
        'started_at' => now(),
        'submitted_at' => now(),
    ]);
    ExamAttempt::create([
        'exam_template_id' => $template->id,
        'user_id' => $student->id,
        'answers' => [],
        'result' => ['is_speaking_only' => true],
        'started_at' => now(),
        'submitted_at' => now(),
    ]);

    // 3rd attempt should be blocked
    Livewire::test(VoiceInput::class)
        ->call('chooseTopic', $topic->id)
        ->call('startExam')
        ->assertSet('showTierPrompt', true);
});

test('voice practice robustly transitions parts based on AI response content', function () {
    $rawResponse1 = [
        'candidates' => [
            [
                'content' => [
                    'parts' => [
                        [
                            'text' => 'Willkommen zu Teil 1 der Prüfung. Erzählen Sie etwas über sich.',
                        ],
                    ],
                    'role' => 'model',
                ],
                'finishReason' => 'STOP',
                'index' => 0,
            ],
        ],
        'usageMetadata' => [
            'promptTokenCount' => 8,
            'candidatesTokenCount' => 444,
            'totalTokenCount' => 452,
        ],
    ];

    $rawResponse2 = [
        'candidates' => [
            [
                'content' => [
                    'parts' => [
                        [
                            'text' => 'Damit ist Teil 1 abgeschlossen. Lassen Sie uns nun mit Teil zwei fortfahren.',
                        ],
                    ],
                    'role' => 'model',
                ],
                'finishReason' => 'STOP',
                'index' => 0,
            ],
        ],
        'usageMetadata' => [
            'promptTokenCount' => 8,
            'candidatesTokenCount' => 444,
            'totalTokenCount' => 452,
        ],
    ];

    $mockTransporter = Mockery::mock(TransporterContract::class);
    $mockTransporter->shouldReceive('request')
        ->andReturnValues([
            new ResponseDTO($rawResponse1),
            new ResponseDTO($rawResponse2),
        ]);

    $realClient = new Client($mockTransporter);
    app()->instance('gemini', $realClient);

    $component = Livewire::test(VoiceInput::class)
        ->set('selectedProvider', 'telc')
        ->set('selectedLevel', 'B1');

    $component->call('startExam');
    expect($component->get('currentTeil'))->toBe(1);

    $component->set('userInput', 'Hallo, ich bin Student.');
    $component->call('sendMessage');

    expect($component->get('currentTeil'))->toBe(2);
});
