<?php

namespace App\Livewire;

use App\Models\VoiceTopic;
use App\Services\SpeakingPromptService;
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

    public string $selectedProvider = 'telc';

    public string $selectedLevel = 'B1';

    public function mount(): void
    {
        if (VoiceTopic::count() === 0) {
            $defaults = [
                ['title' => 'Vorteile und Nachteile von Social Media (Goethe B1)', 'level' => 'B1', 'exam_provider' => 'goethe'],
                ['title' => 'Reisen bildet — Stimmt das? (telc B1)', 'level' => 'B1', 'exam_provider' => 'telc'],
                ['title' => 'Homeoffice: Flexibilität vs. Isolation (Goethe B2)', 'level' => 'B2', 'exam_provider' => 'goethe'],
                ['title' => 'Umweltschutz im Alltag (telc B2)', 'level' => 'B2', 'exam_provider' => 'telc'],
                ['title' => 'Die Rolle der künstlichen Intelligenz in der Zukunft (Goethe C1)', 'level' => 'C1', 'exam_provider' => 'goethe'],
                ['title' => 'Bedeutung des lebenslangen Lernens (telc C1)', 'level' => 'C1', 'exam_provider' => 'telc'],
            ];
            foreach ($defaults as $d) {
                VoiceTopic::create($d + ['is_active' => true]);
            }
        }
        $this->selectFirstTopic();
    }

    public function updatedSelectedProvider(): void
    {
        $this->selectFirstTopic();
    }

    public function updatedSelectedLevel(): void
    {
        $this->selectFirstTopic();
    }

    private function selectFirstTopic(): void
    {
        $first = VoiceTopic::query()
            ->where('is_active', true)
            ->where('exam_provider', $this->selectedProvider)
            ->where('level', $this->selectedLevel)
            ->orderBy('title')
            ->first();

        $this->selectedTopic = $first ? $first->title : '';
        
        $this->dispatch('topic-changed', topic: $this->selectedTopic);
    }

    public function hasActiveSubscription(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return \App\Models\Subscription::query()
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->exists();
    }

    public function evaluateSpeech(): void
    {
        if (!$this->hasActiveSubscription() && !auth()->user()?->isAdmin()) {
            $count = session('speaking_practice_count', 0);
            if ($count >= 2) {
                $this->addError('speaking_limit', 'Sie haben Ihre 2 kostenlosen Sprechübungen aufgebraucht. Bitte abonnieren Sie ein Paket.');
                return;
            }
        }

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
            $topicModel = VoiceTopic::query()->where('title', $this->selectedTopic)->first();
            $level = $topicModel ? $topicModel->level : $this->selectedLevel;
            $provider = $topicModel ? ($topicModel->exam_provider ?: 'telc') : $this->selectedProvider;
            $systemInstruction = SpeakingPromptService::getSystemInstruction($provider, $level);

            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(
                    Content::parse($systemInstruction)
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

            if (!$this->hasActiveSubscription() && !auth()->user()?->isAdmin()) {
                session(['speaking_practice_count' => session('speaking_practice_count', 0) + 1]);
            }
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
                ->where('exam_provider', $this->selectedProvider)
                ->where('level', $this->selectedLevel)
                ->orderBy('title')
                ->get(),
        ]);
    }
}
