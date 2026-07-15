<?php

namespace App\Livewire;

use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
use App\Models\Subscription;
use App\Models\VoiceTopic;
use App\Services\SpeakingPromptService;
use Gemini\Data\Content;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\View;
use Livewire\Component;

class VoiceInput extends Component
{
    public string $selectedProvider = 'telc';

    public string $selectedLevel = 'B1';

    public bool $examStarted = false;

    public bool $examFinished = false;

    public int $currentStage = 0;

    public ?int $activeVoiceTopicId = null;

    public string $selectedTopic = '';

    public string $selectedCounterTopic = '';

    public string $aiPresentationText = '';

    public string $transcribedText = '';

    public array $answers = [];

    public int $stageTimer = 0;

    public bool $timerRunning = false;

    public int $stage1Duration = 180;

    public int $stage2Duration = 240;

    public int $stage3Duration = 120;

    /** @var array<string, array<int, array<string, string>>> */
    public array $speakingHistory = [];

    /** @var array<string, string> */
    public array $speakingFeedback = [];

    /** @var array<string, bool> */
    public array $speakingProcessing = [];

    /** @var array<string, mixed>|null */
    public ?array $result = null;

    public bool $aiSpeakingDone = false;

    public function mount(): void
    {
        $this->seedVoiceTopicsIfEmpty();
    }

    private function seedVoiceTopicsIfEmpty(): void
    {
        if (VoiceTopic::count() === 0) {
            $topics = [
                ['title' => 'Mein Zuhause und meine Familie', 'counter_topic' => 'Leben in der Großstadt vs. auf dem Land', 'level' => 'A1', 'exam_provider' => 'telc', 'is_active' => true],
                ['title' => 'Meine Freizeit und Hobbys', 'counter_topic' => 'Digitale Medien und Freizeitgestaltung', 'level' => 'A2', 'exam_provider' => 'telc', 'is_active' => true],
                ['title' => 'Meine Arbeitswelt', 'counter_topic' => 'Arbeit und Lebensqualität', 'level' => 'B1', 'exam_provider' => 'telc', 'is_active' => true],
                ['title' => 'Bildung und Weiterbildung', 'counter_topic' => 'Bildung im digitalen Zeitalter', 'level' => 'B2', 'exam_provider' => 'telc', 'is_active' => true],
                ['title' => 'Gesellschaft und Politik', 'counter_topic' => 'Demokratie und gesellschaftlicher Wandel', 'level' => 'C1', 'exam_provider' => 'telc', 'is_active' => true],
                ['title' => 'Umweltschutz und Nachhaltigkeit', 'counter_topic' => 'Wirtschaftswachstum vs. Umweltschutz', 'level' => 'C2', 'exam_provider' => 'telc', 'is_active' => true],
            ];
            foreach ($topics as $topic) {
                VoiceTopic::create($topic);
            }
        }
    }

    /** @return Collection<int, VoiceTopic> */
    public function getFilteredTopicsProperty()
    {
        return VoiceTopic::query()
            ->where('exam_provider', $this->selectedProvider)
            ->where('level', $this->selectedLevel)
            ->where('is_active', true)
            ->get();
    }

    public function hasActiveSubscription(): bool
    {
        if (! auth()->check()) {
            return false;
        }

        return Subscription::query()
            ->where('user_id', auth()->id())
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->exists();
    }

    public function chooseTopic(int $topicId): void
    {
        $topic = VoiceTopic::findOrFail($topicId);
        $this->activeVoiceTopicId = $topic->id;
        $this->selectedTopic = $topic->title;
        $this->selectedCounterTopic = $topic->counter_topic ?? 'Das alternative Thema';
    }

    public function startExam(): void
    {
        if (! auth()->check()) {
            $this->addError('login', 'Bitte loggen Sie sich ein, um die Sprechprüfung zu starten.');

            return;
        }

        if (! auth()->user()->isAdmin() && ! $this->hasActiveSubscription()) {
            $count = ExamAttempt::query()
                ->where('user_id', auth()->id())
                ->where('result->is_speaking_only', true)
                ->count();
            if ($count >= 2) {
                $this->addError('limit', 'Sie haben Ihre 2 kostenlosen Sprechprüfungen aufgebraucht. Bitte abonnieren Sie ein Premium-Paket.');

                return;
            }
        }

        if (! $this->activeVoiceTopicId) {
            $this->addError('exam', 'Bitte wählen Sie zuerst ein Thema aus.');

            return;
        }

        $this->examStarted = true;
        $this->examFinished = false;
        $this->currentStage = 1;
        $this->stageTimer = $this->stage1Duration;
        $this->timerRunning = true;
        $this->transcribedText = '';
        $this->aiPresentationText = '';
        $this->speakingHistory = [];
        $this->speakingFeedback = [];
        $this->speakingProcessing = [];
        $this->result = null;
        $this->aiSpeakingDone = false;
    }

    public function tick(): void
    {
        if (! $this->timerRunning || $this->stageTimer <= 0) {
            return;
        }

        $this->stageTimer--;

        if ($this->stageTimer <= 0) {
            $this->timerRunning = false;

            if ($this->currentStage === 1) {
                $this->submitStage1();
            } elseif ($this->currentStage === 3) {
                $this->submitStage3();
            }
        }
    }

    public function submitStage1(): void
    {
        $text = trim($this->transcribedText);
        if ($text === '' && $this->stageTimer > 0) {
            return;
        }

        $this->timerRunning = false;
        $this->speakingProcessing['SP-1a'] = true;

        $level = $this->selectedLevel;
        $provider = $this->selectedProvider;
        $systemInstruction = SpeakingPromptService::getSystemInstruction($provider, $level);
        $maxPoints = 24;

        $criteriaPrompt = "Bewerte den Vortrag des Lernenden zu dem von ihm gewählten Thema.
Kriterien:
- Inhaltliche Angemessenheit & Relevanz (0-8 Punkte)
- Struktur und logischer Aufbau (0-8 Punkte)
- Ausdrucksweise und Kohärenz (0-8 Punkte)

Bitte gib eine kurze Begründung für jedes Kriterium an.
Gib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";

        try {
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(
                    Content::parse($systemInstruction."\n\n".$criteriaPrompt)
                )
                ->generateContent(
                    "Gewähltes Thema: {$this->selectedTopic}\n\n"
                    ."Transkription des Vortrags:\n{$text}\n\n"
                    .'Bitte bewerten Sie die Präsentation:'
                );

            $feedback = $result->text();
            $this->speakingFeedback['SP-1a'] = $feedback;
            $this->speakingHistory['SP-1a'] = [
                ['role' => 'user', 'text' => $text],
                ['role' => 'assistant', 'text' => $feedback],
            ];

            $this->answers['SP-1a'] = $text;
        } catch (\Throwable $e) {
            $this->speakingFeedback['SP-1a'] = 'Fehler bei der Bewertung: '.$e->getMessage();
            $this->speakingHistory['SP-1a'] = [
                ['role' => 'user', 'text' => $text],
            ];
        } finally {
            $this->speakingProcessing['SP-1a'] = false;
        }

        $this->currentStage = 2;
        $this->stageTimer = $this->stage2Duration;
        $this->aiSpeakingDone = false;
        $this->generateAiPresentation();
    }

    public function generateAiPresentation(): void
    {
        $this->speakingProcessing['SP-1b'] = true;

        try {
            $prompt = "Schreibe einen gut strukturierten Vortrag (Einleitung, Hauptteil, Eigene Meinung, Schluss/Fazit) zum Thema: \"{$this->selectedCounterTopic}\". Der Vortrag soll klar und verständlich sein, mit einer deutlichen Argumentationslinie. Der Vortrag soll etwa 4 Minuten zum Vorlesen dauern.";

            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(Content::parse('Du bist ein TELC-Prüfungskandidat, der einen Vortrag hält. Sprich den Lernenden direkt an.'))
                ->generateContent($prompt);

            $this->aiPresentationText = $result->text();
            $this->timerRunning = true;
        } catch (\Throwable $e) {
            $this->aiPresentationText = 'Fehler bei der Vortragserstellung: '.$e->getMessage();
            $this->timerRunning = true;
        } finally {
            $this->speakingProcessing['SP-1b'] = false;
        }
    }

    public function advanceToStage3(): void
    {
        $this->currentStage = 3;
        $this->stageTimer = $this->stage3Duration;
        $this->timerRunning = true;
        $this->transcribedText = '';
    }

    public function submitStage3(): void
    {
        $text = trim($this->transcribedText);
        $this->timerRunning = false;
        $this->speakingProcessing['SP-2'] = true;

        $level = $this->selectedLevel;
        $provider = $this->selectedProvider;
        $systemInstruction = SpeakingPromptService::getSystemInstruction($provider, $level);
        $maxPoints = 24;

        $criteriaPrompt = "Bewerte die Zusammenfassung des Lernenden zum Vortrag des Prüfers.
Kriterien:
- Verständnis des gehörten Vortrags (0-8 Punkte)
- Treffende Zusammenfassung der Kernpunkte (0-8 Punkte)
- Sprachliche Richtigkeit und Kohärenz (0-8 Punkte)

Bitte gib eine kurze Begründung für jedes Kriterium an.
Gib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";

        try {
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(
                    Content::parse($systemInstruction."\n\n".$criteriaPrompt)
                )
                ->generateContent(
                    "Gegen-Thema: {$this->selectedCounterTopic}\n\n"
                    ."Vortrag des Prüfers:\n{$this->aiPresentationText}\n\n"
                    ."Zusammenfassung des Lernenden:\n{$text}\n\n"
                    .'Bitte bewerten Sie die Zusammenfassung:'
                );

            $feedback = $result->text();
            $this->speakingFeedback['SP-2'] = $feedback;
            $this->speakingHistory['SP-2'] = [
                ['role' => 'assistant', 'text' => $this->aiPresentationText],
                ['role' => 'user', 'text' => $text],
                ['role' => 'assistant', 'text' => $feedback],
            ];
        } catch (\Throwable $e) {
            $this->speakingFeedback['SP-2'] = 'Fehler bei der Bewertung: '.$e->getMessage();
            $this->speakingHistory['SP-2'] = [
                ['role' => 'assistant', 'text' => $this->aiPresentationText],
                ['role' => 'user', 'text' => $text],
            ];
        } finally {
            $this->speakingProcessing['SP-2'] = false;
        }

        $this->gradeExam();
    }

    public function gradeExam(): void
    {
        $this->speakingProcessing['global'] = true;

        try {
            $details = [];
            $level = $this->selectedLevel;
            $provider = $this->selectedProvider;

            // Grade SP-1a
            $sp1aScore = 0;
            $sp1aFeedback = $this->speakingFeedback['SP-1a'] ?? 'Keine Beiträge aufgezeichnet.';
            if (preg_match('/SCORE:\s*(\d+)/i', $sp1aFeedback, $m)) {
                $sp1aScore = (int) $m[1];
            }
            $details[] = [
                'id' => 'SP-1a',
                'label' => 'Teil 1 — Ihre Präsentation',
                'given' => $this->answers['SP-1a'] ?? '',
                'expected' => $this->selectedTopic,
                'points' => $sp1aScore,
                'max_points' => 24,
                'correct' => $sp1aScore >= (int) ceil(24 * 0.6),
                'explanation' => $sp1aFeedback,
            ];

            // Grade SP-2 (summary)
            $sp2Score = 0;
            $sp2Feedback = $this->speakingFeedback['SP-2'] ?? 'Keine Beiträge aufgezeichnet.';
            if (preg_match('/SCORE:\s*(\d+)/i', $sp2Feedback, $m)) {
                $sp2Score = (int) $m[1];
            }
            $details[] = [
                'id' => 'SP-2',
                'label' => 'Teil 3 — Zusammenfassung',
                'given' => $this->transcribedText ?? '',
                'expected' => $this->selectedCounterTopic,
                'points' => $sp2Score,
                'max_points' => 24,
                'correct' => $sp2Score >= (int) ceil(24 * 0.6),
                'explanation' => $sp2Feedback,
            ];

            // Global linguistic competence
            $fullHistory = '';
            foreach ($this->speakingHistory as $stage => $turns) {
                $fullHistory .= "--- {$stage} ---\n";
                foreach ($turns as $turn) {
                    $fullHistory .= "{$turn['role']}: {$turn['text']}\n";
                }
            }

            $maxGlobal = 24;
            $globalScore = 0;
            $globalFeedback = '';
            try {
                $model = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->withSystemInstruction(
                        Content::parse(
                            "Du bist ein {$provider} {$level} Mündlicher Prüfer. "
                            .'Bewerte das gesamte Sprechprotokoll hinsichtlich Flüssigkeit, Wortschatz, Grammatik und Aussprache. '
                            ."Gib eine Gesamtpunktzahl von 0 bis {$maxGlobal} Punkten im Format 'SCORE: X/{$maxGlobal}' zurück, gefolgt von einer Analyse."
                        )
                    );
                $resp = $model->generateContent($fullHistory);
                $globalFeedback = $resp->text();
                preg_match('/SCORE:\s*(\d+)/i', $globalFeedback, $gm);
                if (isset($gm[1])) {
                    $globalScore = (int) $gm[1];
                }
            } catch (\Throwable $e) {
                $globalScore = (int) ($maxGlobal * 0.6);
                $globalFeedback = 'Automatisch berechnet: '.$e->getMessage();
            }

            $details[] = [
                'id' => 'SP-GLOBAL',
                'label' => 'Gesamtbewertung — Sprachkompetenz',
                'given' => [],
                'expected' => 'Gemini AI Graded',
                'points' => $globalScore,
                'max_points' => $maxGlobal,
                'correct' => $globalScore >= (int) ceil($maxGlobal * 0.6),
                'explanation' => $globalFeedback,
            ];

            $earned = array_sum(array_column($details, 'points'));
            $possible = array_sum(array_column($details, 'max_points'));
            $pct = $possible > 0 ? round(($earned / $possible) * 100, 2) : 0;

            $this->result = [
                'earned' => $earned,
                'possible' => $possible,
                'percentage' => $pct,
                'passed' => $pct >= 60,
                'details' => $details,
                'is_speaking_only' => true,
            ];

            $template = ExamTemplate::query()
                ->where('exam_provider', $this->selectedProvider)
                ->where('level', $this->selectedLevel)
                ->first();

            if ($template) {
                ExamAttempt::create([
                    'exam_template_id' => $template->id,
                    'user_id' => auth()->id(),
                    'answers' => $this->answers,
                    'result' => $this->result,
                    'started_at' => now(),
                    'submitted_at' => now(),
                ]);
            }

            $this->examFinished = true;
            $this->currentStage = 4;
        } catch (\Throwable $e) {
            $this->addError('exam', 'Fehler bei der Bewertung: '.$e->getMessage());
        } finally {
            $this->speakingProcessing['global'] = false;
        }
    }

    public function repeatExam(): void
    {
        $this->examStarted = false;
        $this->examFinished = false;
        $this->currentStage = 0;
        $this->activeVoiceTopicId = null;
        $this->selectedTopic = '';
        $this->selectedCounterTopic = '';
        $this->aiPresentationText = '';
        $this->transcribedText = '';
        $this->stageTimer = 0;
        $this->timerRunning = false;
        $this->speakingHistory = [];
        $this->speakingFeedback = [];
        $this->speakingProcessing = [];
        $this->answers = [];
        $this->result = null;
        $this->aiSpeakingDone = false;
    }

    public function render(): View
    {
        return view('livewire.voice-input', [
            'filteredTopics' => $this->getFilteredTopicsProperty(),
        ]);
    }
}
