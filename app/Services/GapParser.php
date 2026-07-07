<?php

namespace App\Services;

class GapParser
{
    /**
     * Parse content with [[gap:X]] markers and return rendered HTML segments.
     *
     * @return array<int, array{type: string, content?: string, gapId?: string, gapDef?: array<string, mixed>}>
     */
    /**
     * @param  array<string, array<string, mixed>>  $gaps
     * @return array<int, array{type: string, content?: string, gapId?: string, gapDef?: array<string, mixed>}>
     */
    public function parse(string $content, array $gaps): array
    {
        $pattern = '/\[\[gap:(\d+)\]\]/';
        $parts = preg_split($pattern, $content, -1, PREG_SPLIT_DELIM_CAPTURE);
        if ($parts === false) {
            return [];
        }
        $segments = [];

        foreach ($parts as $i => $part) {
            if ($i % 2 === 0) {
                if ($part !== '') {
                    $segments[] = ['type' => 'text', 'content' => $part];
                }
            } else {
                $gapId = $part;
                $gapDef = $gaps[$gapId] ?? null;

                if ($gapDef) {
                    $segments[] = [
                        'type' => 'gap',
                        'gapId' => $gapId,
                        'gapDef' => $gapDef,
                    ];
                } else {
                    $segments[] = ['type' => 'text', 'content' => "[[gap:{$gapId}]]"];
                }
            }
        }

        return $segments;
    }

    /**
     * Get all gaps from all interactive_text elements across sections.
     *
     * @param  array<int, array<string, mixed>>  $sections
     * @return array<int, array{elementId: string, gapId: string, label: string, type: string, options: array, answer: string}>
     */
    public function allGaps(array $sections): array
    {
        $items = [];

        foreach ($sections as $section) {
            foreach ($section['elements'] ?? [] as $element) {
                if (($element['type'] ?? '') !== 'interactive_text') {
                    continue;
                }

                $gaps = $element['gaps'] ?? [];

                foreach ($gaps as $gapId => $gapDef) {
                    $items[] = [
                        'elementId' => $element['id'],
                        'gapId' => (string) $gapId,
                        'label' => $this->labelFor($gapDef, $gapId, $element),
                        'type' => $gapDef['type'] ?? 'dropdown',
                        'options' => $this->optionList($gapDef),
                        'answer' => $gapDef['answer'] ?? '',
                    ];
                }
            }
        }

        return $items;
    }

    public function labelFor(array $gapDef, string|int $gapId, array $element): string
    {
        $num = preg_replace('/[^0-9]/', '', $element['label'] ?? '');
        $num = $num ?: (string) $gapId;

        return $num;
    }

    /**
     * @return array<int, array{value: string, label: string}>
     */
    public function optionList(array $gapDef): array
    {
        $options = $gapDef['options'] ?? [];

        if (is_array($options)) {
            $list = [];
            foreach ($options as $value => $label) {
                if (is_numeric($value) && is_string($label)) {
                    $list[] = ['value' => $label, 'label' => $label];
                } else {
                    $list[] = ['value' => (string) $value, 'label' => (string) $label];
                }
            }

            return $list;
        }

        if (is_string($options)) {
            return collect(array_filter(array_map('trim', explode(',', $options))))
                ->map(function ($part) {
                    $parts = explode(':', $part, 2);
                    $value = trim($parts[0]);
                    $label = trim($parts[1] ?? $value);

                    return ['value' => $value, 'label' => $label];
                })
                ->values()
                ->all();
        }

        return [];
    }

    public static function optionsCsv(array $gapDef): string
    {
        $options = $gapDef['options'] ?? [];

        if (is_string($options)) {
            return $options;
        }

        if (is_array($options)) {
            return collect($options)
                ->map(fn ($label, $value) => "{$value}:{$label}")
                ->implode(',');
        }

        return '';
    }

    /**
     * Parse a CSV string like "a:Tag,b:Baum,c:Hund" into an associative array.
     */
    public static function parseOptionsCsv(string $csv): array
    {
        $result = [];

        foreach (array_filter(array_map('trim', explode(',', $csv))) as $part) {
            $parts = explode(':', $part, 2);
            $value = trim($parts[0]);
            $label = trim($parts[1] ?? $value);
            if ($value !== '') {
                $result[$value] = $label;
            }
        }

        return $result;
    }
}
