<?php

use App\Services\ExamGrader;

test('it grades bound answer keys case insensitively', function () {
    $sections = [[
        'title' => 'Sample',
        'elements' => [
            ['id' => 'q1', 'type' => 'multiple_choice', 'label' => 'Aufgabe 1', 'answer' => 'A', 'points' => 1],
            ['id' => 'q2', 'type' => 'true_false', 'label' => 'Aufgabe 2', 'answer' => '+', 'points' => 1],
            ['id' => 'copy', 'type' => 'text', 'label' => 'Prompt', 'answer' => '', 'points' => 0],
        ],
    ]];

    $result = app(ExamGrader::class)->grade($sections, [
        'q1' => 'a',
        'q2' => 'richtig',
    ]);

    expect($result['earned'])->toBe(2.0)
        ->and($result['possible'])->toBe(2.0)
        ->and($result['percentage'])->toBe(100.0);
});

test('it grades inline gaps with non-sequential associative keys correctly', function () {
    $sections = [[
        'title' => 'Sample Inline Gaps',
        'elements' => [
            [
                'id' => 'inline-1',
                'type' => 'inline_gap',
                'label' => 'Gap Passage',
                'options' => json_encode([
                    0 => ['options' => '0|0', 'answer' => '0'],
                    10 => ['options' => 'a,b,c', 'answer' => 'c'],
                    15 => ['options' => 'a,b,c', 'answer' => 'b'],
                ]),
                'points' => 1.5,
            ],
        ],
    ]];

    $result = app(ExamGrader::class)->grade($sections, [
        'inline-1' => [
            0 => '0',
            10 => 'c',
            15 => 'a', // incorrect answer
        ],
    ]);

    expect($result['earned'])->toBe(3.0)
        ->and($result['possible'])->toBe(4.5)
        ->and($result['percentage'])->toBe(66.67);
});
