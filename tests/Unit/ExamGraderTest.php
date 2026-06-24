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
