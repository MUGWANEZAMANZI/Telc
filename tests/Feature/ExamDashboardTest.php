<?php

use App\Livewire\ExamDashboard;
use App\Models\ExamTemplate;
use App\Models\User;
use Gemini\Laravel\Facades\Gemini;
use Livewire\Livewire;

test('admin can switch to student view on dashboard and run preview methods', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $template = ExamTemplate::create([
        'title' => 'C1 Premium Test',
        'level' => 'C1',
        'is_published' => true,
        'exam_provider' => 'telc',
        'theme' => [],
        'timings' => [],
        'sections' => [
            [
                'id' => 'sec-1',
                'title' => 'Leseverstehen',
                'minutes' => 90,
                'elements' => [
                    [
                        'id' => 'el-1',
                        'type' => 'text',
                        'content' => 'Passage content',
                    ],
                ],
            ],
        ],
    ]);

    $component = Livewire::test(ExamDashboard::class)
        ->call('loadTemplate', $template->id)
        ->assertSet('mode', 'builder');

    // Switch to student preview
    $component->call('switchMode', 'student')
        ->assertSet('mode', 'student');

    // Assert that the preview subscription and enrollment check methods exist and return true
    expect($component->instance()->hasActiveSubscription())->toBeTrue();
    expect($component->instance()->isFullyEnrolled($template->id))->toBeTrue();

    // Test preview grading and submit methods
    $component->set('answers', ['el-1' => 'Answer'])
        ->call('submit')
        ->assertSet('started', false)
        ->assertSet('result', [
            'earned' => 0,
            'possible' => 0,
            'percentage' => 0,
            'details' => [],
        ]);

    // Test exit exam preview resets state
    $component->call('exitExam')
        ->assertSet('started', false)
        ->assertSet('result', null)
        ->assertSet('answers', [])
        ->assertSet('activeSection', 0);
});

test('analyzeSpeaking retry flow on Gemini failure', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $template = ExamTemplate::create([
        'title' => 'B1 Test',
        'level' => 'B1',
        'is_published' => true,
        'exam_provider' => 'telc',
        'theme' => [],
        'timings' => [],
        'sections' => [
            [
                'id' => 'sec-1',
                'title' => 'Mündliche Prüfung',
                'minutes' => 15,
                'elements' => [
                    [
                        'id' => 'B1-SP-1a',
                        'type' => 'speaking',
                        'content' => '1. Hobby / 2. Reise',
                        'points' => 6,
                    ],
                ],
            ],
        ],
    ]);

    $component = Livewire::test(ExamDashboard::class)
        ->call('loadTemplate', $template->id)
        ->call('switchMode', 'student')
        ->set('selectedTopic', 'Hobby');

    // Mock Gemini to throw an exception
    Gemini::fake([
        new Exception('API Error'),
    ]);

    // Call analyzeSpeaking - it should fail but NOT keep user turn in history
    $component->call('analyzeSpeaking', 'B1-SP-1a', 'User Vortrag Text');

    // Assert that the user turn was popped/removed and error was set
    $history = $component->get('speakingHistory');
    expect($history['B1-SP-1a'] ?? [])->toBeEmpty();
    expect($component->get('speakingFeedback.B1-SP-1a'))->toContain('Fehler: API Error');
});

test('admin dashboard defaults to overview, can edit and delete exams', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $template = ExamTemplate::create([
        'title' => 'Delete Test Exam',
        'level' => 'B2',
        'is_published' => false,
        'exam_provider' => 'telc',
        'theme' => [],
        'timings' => [],
        'sections' => [
            [
                'id' => 'sec-1',
                'title' => 'Leseverstehen',
                'minutes' => 90,
                'elements' => [],
            ],
        ],
    ]);

    // 1. Initial state check
    $component = Livewire::test(ExamDashboard::class)
        ->assertSet('mode', 'overview')
        ->assertSet('selectedTemplateId', null);

    // 2. Edit exam check
    $component->call('editExam', $template->id)
        ->assertSet('mode', 'builder')
        ->assertSet('selectedTemplateId', $template->id);

    // 3. Delete exam check
    $component->call('deleteExam')
        ->assertSet('mode', 'overview')
        ->assertSet('selectedTemplateId', null);

    // Assert exam was deleted from database
    $this->assertDatabaseMissing('exam_templates', ['id' => $template->id]);
});
