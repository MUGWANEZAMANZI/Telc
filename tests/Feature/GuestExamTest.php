<?php

use App\Livewire\GuestExam;
use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
use App\Models\Setting;
use Livewire\Livewire;

test('guest users can access the free exam page without being logged in', function () {
    $response = $this->get(route('free.exam'));
    $response->assertOk();
    $response->assertSeeLivewire(GuestExam::class);
});

test('guest users see the module with sections as chapters', function () {
    ExamTemplate::query()->delete();
    $template = ExamTemplate::create([
        'title' => 'Test Free Exam B1',
        'level' => 'B1',
        'is_published' => true,
        'theme' => ['accent' => '#ffcc00', 'ink' => '#202020'],
        'timings' => [],
        'sections' => [
            [
                'id' => 'sec-reading',
                'title' => 'Leseverstehen',
                'minutes' => 10,
                'elements' => [
                    ['id' => 'el1', 'type' => 'multiple_choice', 'label' => 'Q1', 'options' => 'a,b,c', 'answer' => 'a', 'points' => 2],
                ],
            ],
            [
                'id' => 'sec-writing',
                'title' => 'Schriftlicher Ausdruck',
                'minutes' => 15,
                'elements' => [],
            ],
        ],
    ]);

    Livewire::test(GuestExam::class)
        ->assertSee('Test Free Exam B1')
        ->assertSee('Leseverstehen')
        ->assertSee('Schriftlicher Ausdruck')
        ->assertSee('Start');
});

test('level query param selects the correct exam', function () {
    ExamTemplate::query()->delete();
    ExamTemplate::create([
        'title' => 'B1 Exam', 'level' => 'B1', 'is_published' => true,
        'theme' => [], 'timings' => [],
        'sections' => [['id' => 's1', 'title' => 'Section 1', 'minutes' => 5, 'elements' => []]],
    ]);
    ExamTemplate::create([
        'title' => 'B2 Exam', 'level' => 'B2', 'is_published' => true,
        'theme' => [], 'timings' => [],
        'sections' => [['id' => 's2', 'title' => 'Section B2', 'minutes' => 5, 'elements' => []]],
    ]);

    Livewire::withQueryParams(['level' => 'B2'])
        ->test(GuestExam::class)
        ->assertSee('B2 Exam')
        ->assertDontSee('B1 Exam');
});

test('user can start a section and submit it', function () {
    ExamTemplate::query()->delete();
    $template = ExamTemplate::create([
        'title' => 'Test B1', 'level' => 'B1', 'is_published' => true,
        'theme' => ['accent' => '#d8232a', 'ink' => '#202020'],
        'timings' => [],
        'sections' => [
            ['id' => 'sec1', 'title' => 'Reading', 'minutes' => 10, 'elements' => [
                ['id' => 'q1', 'type' => 'multiple_choice', 'label' => 'Q1', 'options' => 'a,b,c', 'answer' => 'b', 'points' => 2],
            ]],
            ['id' => 'sec2', 'title' => 'Writing', 'minutes' => 10, 'elements' => []],
        ],
    ]);

    expect(ExamAttempt::count())->toBe(0);

    Livewire::test(GuestExam::class)
        ->call('startSection', 0)
        ->assertSet('activeSectionIndex', 0)
        ->assertSet('started', true)
        ->set('answers', ['q1' => 'b'])
        ->call('submit')
        ->assertSet('result.earned', 2)
        ->assertSet('result.possible', 2);

    expect(ExamAttempt::count())->toBe(1);
    $attempt = ExamAttempt::first();
    expect($attempt->user_id)->toBeNull();
    expect($attempt->exam_template_id)->toBe($template->id);
});

test('first-time section submission is tracked as completed', function () {
    ExamTemplate::query()->delete();
    ExamTemplate::create([
        'title' => 'Test B1', 'level' => 'B1', 'is_published' => true,
        'theme' => [], 'timings' => [],
        'sections' => [
            ['id' => 'sec-a', 'title' => 'Section A', 'minutes' => 5, 'elements' => [
                ['id' => 'q1', 'type' => 'multiple_choice', 'label' => 'Q1', 'options' => 'a,b,c', 'answer' => 'a', 'points' => 1],
            ]],
            ['id' => 'sec-b', 'title' => 'Section B', 'minutes' => 5, 'elements' => [
                ['id' => 'q2', 'type' => 'multiple_choice', 'label' => 'Q2', 'options' => 'a,b,c', 'answer' => 'a', 'points' => 1],
            ]],
        ],
    ]);

    $component = Livewire::test(GuestExam::class);

    expect($component->get('completedSectionIds'))->toBe([]);
    expect($component->get('remainingSections'))->toBe(4);

    // Complete first section
    $component->call('startSection', 0)
        ->set('answers', ['q1' => 'a'])
        ->call('submit')
        ->assertSet('result.earned', 1);

    expect($component->get('completedSectionIds'))->toContain('sec-a');
    expect(count($component->get('completedSectionIds')))->toBe(1);
    expect($component->get('remainingSections'))->toBe(3);
});

test('retry does not consume another free slot', function () {
    ExamTemplate::query()->delete();
    ExamTemplate::create([
        'title' => 'Test B1', 'level' => 'B1', 'is_published' => true,
        'theme' => [], 'timings' => [],
        'sections' => [
            ['id' => 'sec-x', 'title' => 'Section X', 'minutes' => 5, 'elements' => [
                ['id' => 'q1', 'type' => 'multiple_choice', 'label' => 'Q1', 'options' => 'a,b,c', 'answer' => 'a', 'points' => 1],
            ]],
            ['id' => 'sec-y', 'title' => 'Section Y', 'minutes' => 5, 'elements' => []],
        ],
    ]);

    $component = Livewire::test(GuestExam::class);

    // First completion
    $component->call('startSection', 0)
        ->set('answers', ['q1' => 'a'])
        ->call('submit');

    expect(count($component->get('completedSectionIds')))->toBe(1);
    expect($component->get('remainingSections'))->toBe(3);

    // Retry - go back to chapters and start same section again
    $component->call('backToChapters')
        ->call('startSection', 0)
        ->set('answers', ['q1' => 'a'])
        ->call('submit');

    // Should still only be 1 completed, remaining still 3
    expect(count($component->get('completedSectionIds')))->toBe(1);
    expect($component->get('remainingSections'))->toBe(3);
});

test('limit reached shows login prompt true', function () {
    // Override freeSectionLimit to 1
    Setting::query()->updateOrCreate(
        ['key' => 'free_section_limit'],
        ['value' => '1'],
    );

    ExamTemplate::query()->delete();
    ExamTemplate::create([
        'title' => 'Test B1', 'level' => 'B1', 'is_published' => true,
        'theme' => [], 'timings' => [],
        'sections' => [
            ['id' => 'sec-1', 'title' => 'Section 1', 'minutes' => 5, 'elements' => [
                ['id' => 'q1', 'type' => 'multiple_choice', 'label' => 'Q1', 'options' => 'a,b,c', 'answer' => 'a', 'points' => 1],
            ]],
            ['id' => 'sec-2', 'title' => 'Section 2', 'minutes' => 5, 'elements' => [
                ['id' => 'q2', 'type' => 'multiple_choice', 'label' => 'Q2', 'options' => 'a,b,c', 'answer' => 'a', 'points' => 1],
            ]],
        ],
    ]);

    $component = Livewire::test(GuestExam::class);

    // 1 free section limit, complete 1 section
    $component->call('startSection', 0)
        ->set('answers', ['q1' => 'a'])
        ->call('submit')
        ->assertSet('showLoginPrompt', true);
});

test('guest users can switch levels', function () {
    ExamTemplate::query()->delete();
    ExamTemplate::create([
        'title' => 'B1 Exam', 'level' => 'B1', 'is_published' => true,
        'theme' => [], 'timings' => [],
        'sections' => [['id' => 's1', 'title' => 'S1', 'minutes' => 5, 'elements' => []]],
    ]);
    ExamTemplate::create([
        'title' => 'B2 Exam', 'level' => 'B2', 'is_published' => true,
        'theme' => [], 'timings' => [],
        'sections' => [['id' => 's2', 'title' => 'S2', 'minutes' => 5, 'elements' => []]],
    ]);

    Livewire::test(GuestExam::class)
        ->assertSee('B1 Exam')
        ->call('selectLevel', 'B2')
        ->assertSee('B2 Exam')
        ->assertDontSee('B1 Exam');
});
