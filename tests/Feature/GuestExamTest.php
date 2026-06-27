<?php

use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
use App\Models\User;
use App\Livewire\GuestExam;
use Livewire\Livewire;

test('guest users can access the free exam page without being logged in', function () {
    $response = $this->get(route('free.exam'));
    $response->assertOk();
    $response->assertSeeLivewire(GuestExam::class);
});

test('guest users can view published exam templates', function () {
    ExamTemplate::query()->delete();
    $template = ExamTemplate::create([
        'title' => 'Test Free Exam B1',
        'level' => 'B1',
        'is_published' => true,
        'theme' => ['accent' => '#ffcc00', 'ink' => '#202020'],
        'timings' => [],
        'sections' => [
            [
                'id' => 'sec1',
                'title' => 'Reading Section',
                'minutes' => 10,
                'elements' => [
                    [
                        'id' => 'el1',
                        'type' => 'multiple_choice',
                        'label' => 'Q1',
                        'options' => 'a,b,c',
                        'answer' => 'a',
                        'points' => 2,
                    ]
                ]
            ]
        ]
    ]);

    Livewire::test(GuestExam::class)
        ->assertSee('Test Free Exam B1')
        ->assertSee('Start Free Exam');
});

test('guest users can complete a free exam and it is recorded', function () {
    ExamTemplate::query()->delete();
    $template = ExamTemplate::create([
        'title' => 'Test Free Exam B2',
        'level' => 'B2',
        'is_published' => true,
        'theme' => ['accent' => '#d8232a', 'ink' => '#202020'],
        'timings' => [],
        'sections' => [
            [
                'id' => 'sec1',
                'title' => 'Reading Section',
                'minutes' => 10,
                'elements' => [
                    [
                        'id' => 'el1',
                        'type' => 'multiple_choice',
                        'label' => 'Q1',
                        'options' => 'a,b,c',
                        'answer' => 'b',
                        'points' => 2,
                    ]
                ]
            ]
        ]
    ]);

    expect(ExamAttempt::count())->toBe(0);

    Livewire::test(GuestExam::class)
        ->call('loadTemplate', $template->id)
        ->set('answers', ['el1' => 'b'])
        ->call('submit')
        ->assertSet('result.earned', 2)
        ->assertSet('result.possible', 2);

    expect(ExamAttempt::count())->toBe(1);
    $attempt = ExamAttempt::first();
    expect($attempt->user_id)->toBeNull();
    expect($attempt->exam_template_id)->toBe($template->id);
});

test('guest users are restricted to two free exams a day via cookies', function () {
    ExamTemplate::query()->delete();
    $template = ExamTemplate::create([
        'title' => 'Daily Limit Exam',
        'level' => 'C1',
        'is_published' => true,
        'theme' => [],
        'timings' => [],
        'sections' => [
            [
                'id' => 'sec1',
                'title' => 'Part 1',
                'minutes' => 5,
                'elements' => []
            ]
        ]
    ]);

    // 1st exam
    $component = Livewire::test(GuestExam::class)
        ->call('loadTemplate', $template->id)
        ->call('submit')
        ->assertSet('hasReachedLimit', false);

    // Simulate 2nd exam with a cookie containing "1" exam done today
    $today = today()->toDateString();
    
    // Test with cookie simulation
    Livewire::withCookie('free_exams_limit_tracker', "$today:2")
        ->test(GuestExam::class)
        ->assertSet('hasReachedLimit', true)
        ->call('loadTemplate', $template->id)
        ->assertSet('template', []); // template should not be loaded
});
