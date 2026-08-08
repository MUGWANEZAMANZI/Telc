<?php

use App\Livewire\GuestExam;
use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
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
        'is_free' => true,
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
        ->assertSee('Start Exam');
});

test('level query param selects the correct exam', function () {
    ExamTemplate::query()->delete();
    ExamTemplate::create([
        'title' => 'B1 Exam', 'level' => 'B1', 'is_published' => true, 'is_free' => true,
        'theme' => [], 'timings' => [],
        'sections' => [['id' => 's1', 'title' => 'Section 1', 'minutes' => 5, 'elements' => []]],
    ]);
    ExamTemplate::create([
        'title' => 'B2 Exam', 'level' => 'B2', 'is_published' => true, 'is_free' => true,
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
        'title' => 'Test B1', 'level' => 'B1', 'is_published' => true, 'is_free' => true,
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
        'title' => 'Test B1', 'level' => 'B1', 'is_published' => true, 'is_free' => true,
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
    // First-time users get freeSectionLimit=999, so remaining = 999 - 0 = 999
    expect($component->get('remainingSections'))->toBe(999);

    // Complete first section
    $component->call('startSection', 0)
        ->set('answers', ['q1' => 'a'])
        ->call('submit')
        ->assertSet('result.earned', 1);

    expect($component->get('completedSectionIds'))->toContain('sec-a');
    expect(count($component->get('completedSectionIds')))->toBe(1);
    expect($component->get('remainingSections'))->toBe(998);
});

test('retry does not consume another free slot', function () {
    ExamTemplate::query()->delete();
    ExamTemplate::create([
        'title' => 'Test B1', 'level' => 'B1', 'is_published' => true, 'is_free' => true,
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
    expect($component->get('remainingSections'))->toBe(998);

    // Retry - go back to chapters and start same section again
    $component->call('backToChapters')
        ->call('startSection', 0)
        ->set('answers', ['q1' => 'a'])
        ->call('submit');

    // Should still only be 1 completed (retry doesn't add another), remaining still 998
    expect(count($component->get('completedSectionIds')))->toBe(1);
    expect($component->get('remainingSections'))->toBe(998);
});

test('limit reached shows login prompt true', function () {
    ExamTemplate::query()->delete();
    ExamTemplate::create([
        'title' => 'Test B1', 'level' => 'B1', 'is_published' => true, 'is_free' => true,
        'theme' => [], 'timings' => [],
        'sections' => [
            ['id' => 'sec-1', 'title' => 'Section 1', 'minutes' => 5, 'elements' => [
                ['id' => 'q1', 'type' => 'multiple_choice', 'label' => 'Q1', 'options' => 'a,b,c', 'answer' => 'a', 'points' => 1],
            ]],
        ],
    ]);

    $component = Livewire::test(GuestExam::class);

    // 1 section exam with is_free=true means all 1 sections are free
    $component->call('startSection', 0)
        ->set('answers', ['q1' => 'a'])
        ->call('submit')
        ->assertSet('showLoginPrompt', false);
});

test('guest sequential navigation reaches the writing section', function () {
    ExamTemplate::query()->delete();
    $template = ExamTemplate::create([
        'title' => 'Test C1', 'level' => 'C1', 'is_published' => true, 'is_free' => true,
        'theme' => [], 'timings' => [],
        'sections' => [
            ['id' => 'c1-reading', 'title' => 'Reading', 'minutes' => 10, 'elements' => [
                ['id' => 'q1', 'type' => 'multiple_choice', 'label' => 'Q1', 'options' => 'a,b,c', 'answer' => 'a', 'points' => 2],
            ]],
            ['id' => 'c1-listening-intro', 'title' => 'Listening Intro', 'minutes' => 5, 'elements' => [
                ['id' => 'q2', 'type' => 'multiple_choice', 'label' => 'Q2', 'options' => 'a,b,c', 'answer' => 'b', 'points' => 2],
            ]],
            ['id' => 'c1-listening', 'title' => 'Listening', 'minutes' => 5, 'elements' => [
                ['id' => 'q3', 'type' => 'multiple_choice', 'label' => 'Q3', 'options' => 'a,b,c', 'answer' => 'c', 'points' => 2],
            ]],
            ['id' => 'c1-writing', 'title' => 'Schriftlicher Ausdruck', 'minutes' => 15, 'elements' => [
                ['id' => 'w1', 'type' => 'writing', 'label' => 'Diskussionsbeitrag', 'points' => 48],
            ]],
        ],
    ]);

    $component = Livewire::test(GuestExam::class, ['templateId' => $template->id]);

    $component->call('startSection', 0)->call('submit');
    expect($component->get('nextSectionIndex'))->toBe(1);

    $component->call('startSection', 1)->call('submit');
    expect($component->get('nextSectionIndex'))->toBe(2);

    $component->call('startSection', 2)->call('submit');
    expect($component->get('nextSectionIndex'))->toBe(3);

    $component->call('startSection', 3)
        ->assertSee('Schriftlicher Ausdruck')
        ->assertSee('Diskussionsbeitrag')
        ->call('submit')
        ->assertSet('result.earned', 0)
        ->assertSet('result.possible', 54);
});

test('revisiting a completed section restores graded answers and highlights', function () {
    ExamTemplate::query()->delete();
    $template = ExamTemplate::create([
        'title' => 'Test C1', 'level' => 'C1', 'is_published' => true, 'is_free' => true,
        'theme' => [], 'timings' => [],
        'sections' => [
            ['id' => 'c1-reading', 'title' => 'Leseverstehen', 'minutes' => 10, 'elements' => [
                ['id' => 'q1', 'type' => 'multiple_choice', 'label' => 'Q1', 'options' => 'a,b,c', 'answer' => 'a', 'points' => 2],
            ]],
            ['id' => 'c1-listening', 'title' => 'Hörverstehen', 'minutes' => 10, 'elements' => [
                ['id' => 'q2', 'type' => 'multiple_choice', 'label' => 'Q2', 'options' => 'a,b,c', 'answer' => 'b', 'points' => 2],
            ]],
        ],
    ]);

    $component = Livewire::test(GuestExam::class, ['templateId' => $template->id]);

    // Complete the reading section with a wrong answer
    $component->call('startSection', 0)
        ->set('answers', ['q1' => 'b'])
        ->call('submit')
        ->assertSet('result.earned', 0)
        ->assertSet('result.possible', 2);

    // Go back and reopen the completed reading section: answers + result must be restored
    $component->call('backToChapters')
        ->call('startSection', 0);

    expect($component->get('answers.q1'))->toBe('b');
    expect(collect($component->get('result.details'))->pluck('id'))->toContain('q1');

    // Now complete the listening section as well
    $component->call('startSection', 1)
        ->set('answers', ['q2' => 'b'])
        ->call('submit')
        ->assertSet('result.earned', 2);

    // Revisiting the reading section must restore the ACCUMULATED details from both sections
    $component->call('backToChapters')
        ->call('startSection', 0);

    $detailIds = collect($component->get('result.details'))->pluck('id');
    expect($detailIds)->toContain('q1');
    expect($detailIds)->toContain('q2');
    expect($component->get('answers.q1'))->toBe('b');
    expect($component->get('result.earned'))->toBe(2);
    expect($component->get('result.possible'))->toBe(4);
});

test('guest users can switch levels', function () {
    ExamTemplate::query()->delete();
    ExamTemplate::create([
        'title' => 'B1 Exam', 'level' => 'B1', 'is_published' => true, 'is_free' => true,
        'theme' => [], 'timings' => [],
        'sections' => [['id' => 's1', 'title' => 'S1', 'minutes' => 5, 'elements' => []]],
    ]);
    ExamTemplate::create([
        'title' => 'B2 Exam', 'level' => 'B2', 'is_published' => true, 'is_free' => true,
        'theme' => [], 'timings' => [],
        'sections' => [['id' => 's2', 'title' => 'S2', 'minutes' => 5, 'elements' => []]],
    ]);

    Livewire::test(GuestExam::class)
        ->assertSee('B1 Exam')
        ->call('selectLevel', 'B2')
        ->assertSee('B2 Exam')
        ->assertDontSee('B1 Exam');
});
