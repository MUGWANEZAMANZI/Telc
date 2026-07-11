<?php

use App\Livewire\ExamDashboard;
use App\Models\ExamTemplate;
use App\Models\User;
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
