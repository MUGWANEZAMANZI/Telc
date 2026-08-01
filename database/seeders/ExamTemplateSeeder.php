<?php

namespace Database\Seeders;

use App\Models\ExamTemplate;
use App\Services\TelcTemplateFactory;
use Illuminate\Database\Seeder;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Data\Content;

class ExamTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed or update presets first
        foreach (TelcTemplateFactory::presets() as $preset) {
            $existing = ExamTemplate::where('title', $preset['title'])->first();
            $sections = $preset['sections'] ?? [];

            if ($existing) {
                $existingSections = $existing->sections ?? [];
                
                foreach ($sections as $sIdx => $section) {
                    $existingSection = collect($existingSections)->firstWhere('id', $section['id']);
                    if ($existingSection) {
                        foreach ($section['elements'] as $elIdx => $el) {
                            $existingEl = collect($existingSection['elements'] ?? [])->firstWhere('id', $el['id']);
                            if ($existingEl) {
                                if (!empty($existingEl['question'])) {
                                    $sections[$sIdx]['elements'][$elIdx]['question'] = $existingEl['question'];
                                }
                                if (!empty($existingEl['explanation'])) {
                                    $sections[$sIdx]['elements'][$elIdx]['explanation'] = $existingEl['explanation'];
                                }
                                if ($el['type'] === 'inline_gap' && !empty($existingEl['options'])) {
                                    $sections[$sIdx]['elements'][$elIdx]['options'] = $existingEl['options'];
                                }
                            }
                        }
                    }
                }
            }

            ExamTemplate::updateOrCreate(
                ['title' => $preset['title']],
                array_merge($preset, [
                    'sections' => $sections,
                    'is_published' => true,
                ])
            );
        }

        // 2. Load all templates from database and enrich them
        $allTemplates = ExamTemplate::all();
        foreach ($allTemplates as $template) {
            $sections = $template->sections ?? [];
            $changed = false;
            foreach ($sections as $sIdx => $section) {
                $elements = $section['elements'] ?? [];
                $originalElements = $elements;
                $this->enrichSectionElements($elements, $section['title'] ?? ('Teil ' . ($sIdx + 1)));
                if ($elements !== $originalElements) {
                    $sections[$sIdx]['elements'] = $elements;
                    $changed = true;
                }
            }
            if ($changed) {
                $template->sections = $sections;
                $template->save();
            }
        }
    }

    /**
     * Enrich elements of a section using Gemini API.
     */
    private function enrichSectionElements(array &$elements, string $sectionTitle): void
    {
        // 1. Gather all context text from text elements in this section
        $contextText = '';
        foreach ($elements as $el) {
            if (($el['type'] ?? '') === 'text' && !empty($el['content'])) {
                $contextText .= "\n\n=== " . ($el['label'] ?? '') . " ===\n" . $el['content'];
            }
        }

        // 2. Collect gradable items that need question/explanation
        $itemsToEnrich = [];
        foreach ($elements as $idx => $el) {
            $type = $el['type'] ?? '';
            $id = $el['id'] ?? '';

            if ($type === 'inline_gap') {
                $raw = is_string($el['options'] ?? null) ? json_decode($el['options'], true) : ($el['options'] ?? []);
                if (is_array($raw)) {
                    foreach ($raw as $gIdx => $gap) {
                        if (!empty($gap['question']) && !empty($gap['explanation'])) {
                            continue;
                        }
                        $itemsToEnrich[] = [
                            'id' => $id . '.gap.' . $gIdx,
                            'label' => ($el['label'] ?? $id) . ' — Lücke ' . ($gIdx + 1),
                            'correct_answer' => $gap['answer'] ?? '',
                            'element_idx' => $idx,
                            'gap_index' => $gIdx,
                        ];
                    }
                }
                continue;
            }

            if ($type === 'interactive_text') {
                continue;
            }

            if (!empty($el['answer']) && in_array($type, ['multiple_choice', 'matching', 'fill_blank', 'true_false'], true)) {
                if (!empty($el['question']) && !empty($el['explanation'])) {
                    continue;
                }

                $itemsToEnrich[] = [
                    'id' => $id,
                    'label' => $el['label'] ?? '',
                    'correct_answer' => $el['answer'],
                    'element_idx' => $idx,
                ];
            }
        }

        if (empty($itemsToEnrich)) {
            return;
        }

        // 3. Request Gemini to generate the specific question context and explanation
        $prompt = "Hier ist der Kontext (Text/Transkript) für den Prüfungsteil '{$sectionTitle}':\n"
            . $contextText . "\n\n"
            . "Hier sind die Aufgaben, die gelöst werden sollen:\n"
            . json_encode($itemsToEnrich, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "\n\n"
            . "Bitte generiere für jede Aufgabe:\n"
            . "1. Den genauen Fragetext bzw. den Satzkontext aus dem obigen Text (z. B. den Satz mit der Lücke oder die Aussage) als 'question'. Wenn kein Text vorhanden ist, verwende den Label.\n"
            . "2. Eine verständliche Erklärung (2-3 Sätze auf Deutsch), warum diese Antwort korrekt ist, als 'explanation'.\n\n"
            . "Antworte ausschließlich als gültiges JSON-Objekt im Format:\n"
            . "{\n"
            . "  \"AufgabenID\": {\n"
            . "    \"question\": \"...\",\n"
            . "    \"explanation\": \"...\"\n"
            . "  }\n"
            . "}\n\n"
            . "Antworte NUR mit dem JSON-Objekt, kein Markdown, kein ```json.";

        try {
            $response = Gemini::generativeModel(model: 'gemini-3.5-flash-lite')
                ->withSystemInstruction(
                    Content::parse(
                        'Du bist ein offzieller TELC- und Goethe-Prüfungsvorbereitungs-Tutor. '
                        .'Antworte ausschließlich im JSON-Format.'
                    )
                )
                ->generateContent($prompt);

            $text = trim($response->text());
            if (str_starts_with($text, '```json')) {
                $text = substr($text, 7);
                if (str_ends_with($text, '```')) {
                    $text = substr($text, 0, -3);
                }
            }
            $decoded = json_decode(trim($text), true);

            if (is_array($decoded)) {
                foreach ($itemsToEnrich as $item) {
                    $id = $item['id'];
                    $idx = $item['element_idx'];
                    if (isset($decoded[$id])) {
                        if (isset($item['gap_index'])) {
                            $gIdx = $item['gap_index'];
                            $raw = is_string($elements[$idx]['options'] ?? null) ? json_decode($elements[$idx]['options'], true) : ($elements[$idx]['options'] ?? []);
                            if (is_array($raw) && isset($raw[$gIdx])) {
                                $raw[$gIdx]['question'] = $decoded[$id]['question'] ?? '';
                                $raw[$gIdx]['explanation'] = $decoded[$id]['explanation'] ?? '';
                                $elements[$idx]['options'] = $raw;
                            }
                        } else {
                            $elements[$idx]['question'] = $decoded[$id]['question'] ?? '';
                            $elements[$idx]['explanation'] = $decoded[$id]['explanation'] ?? '';
                        }
                    }
                }
            }
        } catch (\Throwable $e) {
            // Fail silently or dump to logs
            dump("Fehler beim Generieren der Fragen für '{$sectionTitle}': " . $e->getMessage());
        }
    }
}
