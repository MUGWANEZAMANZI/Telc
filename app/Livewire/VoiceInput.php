<?php

namespace App\Livewire;

use App\Models\VoiceTopic;
use Gemini\Data\Content;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\View\View;
use Livewire\Component;

class VoiceInput extends Component
{
    public string $transcribedText = '';

    public string $selectedTopic = '';

    public string $aiFeedback = '';

    public int $score = 0;

    public int $maxPoints = 24;

    public bool $evaluated = false;

    public bool $evaluating = false;

    public function evaluateSpeech(): void
    {
        $text = trim($this->transcribedText);
        if (empty($text)) {
            return;
        }

        $this->evaluating = true;
        $this->evaluated = false;
        $this->aiFeedback = '';
        $this->score = 0;

        $topic = $this->selectedTopic ?: 'das ausgewählte Thema';

        $criteriaPrompt = 'Bewerte die Leistung des Lernenden anhand der folgenden vier offiziellen TELC-Prüfungskriterien:
1. **Repertoire & Wortschatz** — Breite und Angemessenheit des verwendeten Vokabulars (0-6 Punkte)
2. **Grammatische Korrektheit** — Korrektheit der Satzstrukturen und Flexionen (0-6 Punkte)
3. **Aussprache/Intonation** — Deutlichkeit und Sprachmelodie (0-6 Punkte)
4. **Flüssigkeit** — Natürlicher Redefluss, Vermeidung störender Stockungen (0-6 Punkte)

Gib für jedes Kriterium eine Einzelpunktzahl und eine kurze Begründung.
Gib am Ende die Gesamtpunktzahl im Format "SCORE: X/'.$this->maxPoints.'" an.';

        try {
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(
                    Content::parse(
                        'Du bist ein offizieller TELC-C1-Prüfer für die mündliche Prüfung. '
                        .'Bewerte den mündlichen Vortrag des Lernenden.'
                    )
                )
                ->generateContent(
                    "Thema: {$topic}\n\n"
                    ."Transkription des Vortrags:\n{$text}\n\n"
                    .$criteriaPrompt
                );

            $this->aiFeedback = $result->text();
            preg_match('/SCORE:\s*(\d+)/i', $this->aiFeedback, $matches);
            if (isset($matches[1])) {
                $this->score = (int) $matches[1];
            }
            $this->evaluated = true;
        } catch (\Throwable $e) {
            $this->aiFeedback = 'Fehler bei der Bewertung: '.$e->getMessage();
            $this->score = 0;
            $this->evaluated = true;
        } finally {
            $this->evaluating = false;
        }
    }

    public function render(): View
    {
        return view('livewire.voice-input', [
            'topics' => VoiceTopic::query()
                ->where('is_active', true)
                ->orderBy('level')
                ->orderBy('title')
                ->get(),
        ]);
    }
}
