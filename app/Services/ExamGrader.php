<?php

namespace App\Services;

class ExamGrader
{
    /**
     * @param  array<int, array<string, mixed>>  $sections
     * @param  array<string, mixed>  $answers
     * @return array<string, mixed>
     */
    public function grade(array $sections, array $answers): array
    {
        $items = $this->gradableItems($sections);
        $earned = 0;
        $details = [];

        foreach ($items as $item) {
            $id = (string) $item['id'];
            $points = (float) ($item['points'] ?? 1);
            $expected = $item['answer'] ?? null;
            $type = (string) ($item['type'] ?? '');

            if ($type === 'inline_gap') {
                $elementId = $item['element_id'] ?? $id;
                $gapIndex = (string) ($item['gap_index'] ?? 0);
                $given = $answers[$elementId][$gapIndex] ?? null;
            } else {
                $given = $answers[$id] ?? null;
            }

            $correct = $this->matches($given, $expected, $type);

            if ($correct) {
                $earned += $points;
            }

            $details[] = [
                'id' => $id,
                'label' => $item['label'] ?? $id,
                'given' => $given,
                'expected' => $expected,
                'points' => $correct ? $points : 0,
                'max_points' => $points,
                'correct' => $correct,
            ];
        }

        $possible = array_sum(array_map(fn ($item) => (float) ($item['points'] ?? 1), $items));

        return [
            'earned' => $earned,
            'possible' => $possible,
            'percentage' => $possible > 0 ? round(($earned / $possible) * 100, 2) : 0,
            'details' => $details,
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $sections
     * @return array<int, array<string, mixed>>
     */
    private function gradableItems(array $sections): array
    {
        $items = [];

        foreach ($sections as $section) {
            foreach (($section['elements'] ?? []) as $element) {
                $type = $element['type'] ?? '';

                if ($type === 'inline_gap') {
                    $gaps = $this->parseInlineGaps($element);
                    foreach ($gaps as $gIdx => $gap) {
                        $items[] = [
                            'id' => $element['id'].'.gap.'.$gIdx,
                            'type' => 'inline_gap',
                            'label' => $element['label'].' — Lücke '.$gIdx,
                            'element_id' => $element['id'],
                            'gap_index' => $gIdx,
                            'answer' => $gap['answer'] ?? '',
                            'points' => (float) ($element['points'] ?? 1),
                        ];
                    }
                    continue;
                }

                if ($type === 'interactive_text') {
                    $gaps = $element['gaps'] ?? [];
                    foreach ($gaps as $gapId => $gapDef) {
                        $items[] = [
                            'id' => $element['id'].'.gap.'.$gapId,
                            'type' => $gapDef['type'] ?? 'dropdown',
                            'label' => $element['label'].' — Lücke '.$gapId,
                            'element_id' => $element['id'],
                            'gap_index' => (string) $gapId,
                            'answer' => $gapDef['answer'] ?? '',
                            'points' => (float) ($element['points'] ?? 1),
                        ];
                    }
                    continue;
                }

                if (($element['answer'] ?? '') !== '' && in_array($type, ['multiple_choice', 'matching', 'fill_blank', 'true_false'], true)) {
                    $items[] = $element;
                }
            }
        }

        return $items;
    }

    /**
     * @param  array<string, mixed>  $element
     * @return array<int, array<string, mixed>>
     */
    private function parseInlineGaps(array $element): array
    {
        $raw = json_decode((string) ($element['options'] ?? '[]'), true);

        return is_array($raw) ? array_values($raw) : [];
    }

    private function matches(mixed $given, mixed $expected, string $type): bool
    {
        $normalize = fn (mixed $value): string => mb_strtolower(trim((string) $value));

        if ($type === 'true_false') {
            $map = ['true' => '+', 'richtig' => '+', '1' => '+', 'false' => '-', 'falsch' => '-', '0' => '-'];

            return ($map[$normalize($given)] ?? $normalize($given)) === ($map[$normalize($expected)] ?? $normalize($expected));
        }

        if ($type === 'fill_blank') {
            $givenText = preg_replace('/\s+/u', ' ', $normalize($given));
            $expectedText = preg_replace('/\s+/u', ' ', $normalize($expected));

            // C1 information-transfer answers are short notes. Accept exact
            // answers and answers that include the expected note in a sentence.
            return $givenText !== '' && $expectedText !== '' && str_contains($givenText, $expectedText);
        }

        return $normalize($given) === $normalize($expected);
    }
}
