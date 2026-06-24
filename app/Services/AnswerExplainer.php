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
            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')
                ->withSystemInstruction(
                    Content::parse(
                        'Du bist ein TELC-Prüfungsvorbereitungs-Tutor für Deutsch. '
                        . 'Erkläre Antworten auf verständliche Weise in 2-3 Sätzen. '
                        . 'Antworte auf Deutsch.'
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
            $question .= "\n" . $content;
        }

        $correctAnswer = $element['answer'] ?? '';

        if ($correctAnswer === '' || $question === '') {
            return '';
        }

        return $this->explain($question, $correctAnswer, $studentAnswer);
    }
}
