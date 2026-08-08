<?php

namespace App\Grading;

interface GraderInterface
{
    /**
     * @param  array<int, array<string, mixed>>  $sections
     * @param  array<string, mixed>  $answers
     * @return array{earned: float, possible: float, percentage: float, details: array<int, array<string, mixed>>, ...}
     */
    public function grade(array $sections, array $answers): array;
}
