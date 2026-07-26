<?php

use App\Livewire\VoiceInput;
use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
use App\Models\User;
use App\Models\VoiceTopic;
use Livewire\Livewire;

test('voice topics are seeded on mount if database is empty', function () {
    VoiceTopic::query()->delete();

    expect(VoiceTopic::count())->toBe(0);

    Livewire::test(VoiceInput::class);

    expect(VoiceTopic::count())->toBeGreaterThan(0);
});

test('voice practice can filter topics by provider and level', function () {
    VoiceTopic::query()->delete();

    VoiceTopic::create(['title' => 'Topic 1 B2 telc', 'level' => 'B2', 'exam_provider' => 'telc', 'is_active' => true]);
    VoiceTopic::create(['title' => 'Topic 2 B2 goethe', 'level' => 'B2', 'exam_provider' => 'goethe', 'is_active' => true]);
    VoiceTopic::create(['title' => 'Topic 3 C1 telc', 'level' => 'C1', 'exam_provider' => 'telc', 'is_active' => true]);

    Livewire::test(VoiceInput::class)
        ->set('selectedProvider', 'telc')
        ->set('selectedLevel', 'B2')
        ->assertSee('Topic 1 B2 telc')
        ->assertDontSee('Topic 2 B2 goethe')
        ->assertDontSee('Topic 3 C1 telc')
        ->set('selectedProvider', 'goethe')
        ->assertSee('Topic 2 B2 goethe')
        ->assertDontSee('Topic 1 B2 telc');
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
