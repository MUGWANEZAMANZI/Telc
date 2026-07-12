<?php

use App\Livewire\VoiceInput;
use App\Models\User;
use App\Models\VoiceTopic;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Responses\GenerativeModel\GenerateContentResponse;
use Livewire\Livewire;

test('voice topics are seeded on mount if database is empty', function () {
    VoiceTopic::query()->delete();

    expect(VoiceTopic::count())->toBe(0);

    Livewire::test(VoiceInput::class);

    expect(VoiceTopic::count())->toBeGreaterThan(0);
});

test('voice practice can filter topics by provider and level', function () {
    VoiceTopic::query()->delete();

    VoiceTopic::create(['title' => 'Topic 1 B1 telc', 'level' => 'B1', 'exam_provider' => 'telc', 'is_active' => true]);
    VoiceTopic::create(['title' => 'Topic 2 B1 goethe', 'level' => 'B1', 'exam_provider' => 'goethe', 'is_active' => true]);
    VoiceTopic::create(['title' => 'Topic 3 B2 telc', 'level' => 'B2', 'exam_provider' => 'telc', 'is_active' => true]);

    $component = Livewire::test(VoiceInput::class)
        ->set('selectedProvider', 'telc')
        ->set('selectedLevel', 'B1')
        ->assertSee('Topic 1 B1 telc')
        ->assertDontSee('Topic 2 B1 goethe')
        ->assertDontSee('Topic 3 B2 telc');

    $component->set('selectedProvider', 'goethe')
        ->assertSee('Topic 2 B1 goethe')
        ->assertDontSee('Topic 1 B1 telc');
});

test('non-subscribed users are limited to 2 speaking practices', function () {
    VoiceTopic::query()->delete();
    $topic = VoiceTopic::create(['title' => 'Topic 1', 'level' => 'B1', 'exam_provider' => 'telc', 'is_active' => true]);

    $student = User::factory()->create(['role' => 'student']);
    $this->actingAs($student);

    session(['speaking_practice_count' => 0]);

    Gemini::fake([
        GenerateContentResponse::fake([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            [
                                'text' => 'SCORE: 18/24 Gute Aussprache.',
                            ],
                        ],
                    ],
                ],
            ],
        ]),
        GenerateContentResponse::fake([
            'candidates' => [
                [
                    'content' => [
                        'parts' => [
                            [
                                'text' => 'SCORE: 19/24 Sehr flüssig.',
                            ],
                        ],
                    ],
                ],
            ],
        ]),
    ]);

    $component = Livewire::test(VoiceInput::class)
        ->set('selectedTopic', 'Topic 1')
        ->set('transcribedText', 'Das ist ein Vortrag über Reisen.');

    // 1st practice
    $component->call('evaluateSpeech')
        ->assertHasNoErrors();
    expect(session('speaking_practice_count'))->toBe(1);

    // 2nd practice
    $component->set('transcribedText', 'Das ist der zweite Vortrag.')
        ->call('evaluateSpeech')
        ->assertHasNoErrors();
    expect(session('speaking_practice_count'))->toBe(2);

    // 3rd practice should be blocked
    $component->set('transcribedText', 'Das ist der dritte Vortrag.')
        ->call('evaluateSpeech')
        ->assertHasErrors('speaking_limit');
});
