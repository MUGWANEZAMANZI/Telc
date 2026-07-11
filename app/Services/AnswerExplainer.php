<?php

namespace App\Services;

use Gemini\Data\Content;
use Gemini\Laravel\Facades\Gemini;

class AnswerExplainer
{
    public function explain(string $question, string $correctAnswer, ?string $studentAnswer = null): string
    {
        $prompt = "Frage/Aufgabe: {$question}\nKorrekte Antwort: {$correctAnswer}";

        if ($studentAnswer !== null) {
            $prompt .= "\nAntwort des Lernenden: {$studentAnswer}";
            $prompt .= "\nErkläre, warum die korrekte Antwort richtig ist und warum die Antwort des Lernenden falsch ist. Gib einen Tipp, wie man in Zukunft die richtige Antwort finden kann.";
        } else {
            $prompt .= "\nErkläre, warum diese Antwort richtig ist, und gib einen kurzen Merksatz oder Tipp für die Prüfung.";
        }

        try {
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(
                    Content::parse(
                        'Du bist ein TELC-Prüfungsvorbereitungs-Tutor für Deutsch. '
                        .'Erkläre Antworten auf verständliche Weise in 2-3 Sätzen. '
                        .'Antworte auf Deutsch.'
                    )
                )
                ->generateContent($prompt);

            return $result->text();
        } catch (\Throwable) {
            return '';
        }
    }

    /**
     * @param  array<string, mixed>  $element
     */
    public function explainElement(array $element, ?string $studentAnswer = null): string
    {
        if (! empty($element['explanation'])) {
            return $element['explanation'];
        }

        $question = $element['label'] ?? '';
        $content = $element['content'] ?? '';

        if ($content !== '') {
            $question .= "\n".$content;
        }

        $correctAnswer = $element['answer'] ?? '';

        if ($correctAnswer === '' || $question === '') {
            return '';
        }

        return $this->explain($question, $correctAnswer, $studentAnswer);
    }

    /**
     * Explain a batch of questions in a single Gemini API call to prevent timeout.
     *
     * @param  array<int, array{id: string, question: string, correct_answer: string, student_answer: ?string}>  $items
     * @return array<string, string> Map of question ID to explanation text
     */
    public function explainBatch(array $items): array
    {
        if (empty($items)) {
            return [];
        }

        $prompt = 'Du bist ein TELC-Prüfungsvorbereitungs-Tutor für Deutsch. '
            .'Erkläre Antworten auf verständliche Weise in 2-3 Sätzen auf Deutsch. '
            .'Für jede Frage, erkläre, warum die korrekte Antwort richtig ist. Wenn die Antwort des Lernenden angegeben ist und falsch ist, erkläre auch, warum sie falsch ist. '
            ."Antworte NUR mit einem gültigen JSON-Objekt, bei dem die Schlüssel die Frage-IDs sind und die Werte die Erklärungen (als String). Keine Formatierung außer JSON.\n\n"
            ."Fragen:\n"
            .json_encode($items, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

        try {
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(
                    Content::parse(
                        'Du bist ein TELC-Prüfungsvorbereitungs-Tutor für Deutsch. '
                        .'Antworte ausschließlich im JSON-Format.'
                    )
                )
                ->generateContent($prompt);

            $text = trim($result->text());
            if (str_starts_with($text, '```json')) {
                $text = substr($text, 7);
                if (str_ends_with($text, '```')) {
                    $text = substr($text, 0, -3);
                }
            }
            $decoded = json_decode(trim($text), true);
            if (is_array($decoded)) {
                return $decoded;
            }
        } catch (\Throwable) {
            // fallback will be empty string for failed items
        }

        return [];
    }
}
