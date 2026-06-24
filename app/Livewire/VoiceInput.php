<?php

namespace App\Livewire;

use Gemini\Laravel\Facades\Gemini;
use Livewire\Component;

class VoiceInput extends Component
{
    public string $question = 'Was hat zur Rezeption dieses Texts beigetragen?';

    public string $transcribedText = '';

    public ?string $aiResponse = null;

    public bool $isProcessing = false;

    public function analyzeWithGemini(): void
    {
        $this->validate([
            'transcribedText' => 'required|string|min:1',
        ]);

        $this->isProcessing = true;
        $this->aiResponse = null;

        try {
            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')
                ->withSystemInstruction(
                    \Gemini\Data\Content::parse(
                        'Du bist ein TELC-Prüfer für Deutsch. '
                        . 'Bewerte die Antwort des Lernenden auf die gestellte Frage. '
                        . 'Gib konstruktives Feedback zur Korrektheit, Grammatik und Wortschatz. '
                        . 'Antworte auf Deutsch.'
                    )
                )
                ->generateContent(
                    "Frage: {$this->question}\n\nAntwort des Lernenden: {$this->transcribedText}\n\n"
                    . 'Bewerte diese Antwort und gib Verbesserungsvorschläge.'
                );

            $this->aiResponse = $result->text();
        } catch (\Throwable $e) {
            $this->aiResponse = 'Fehler bei der Analyse: ' . $e->getMessage();
        } finally {
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.voice-input');
    }
}
