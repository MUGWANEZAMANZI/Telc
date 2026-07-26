<?php

namespace App\Livewire;

use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
use App\Models\Subscription;
use App\Models\VoiceTopic;
use App\Services\SpeakingPromptService;
use Gemini\Data\Content;
use Gemini\Enums\Role;
use Gemini\Laravel\Facades\Gemini;
use Gemini\Resources\ChatSession;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;
use Livewire\Component;

class VoiceInput extends Component
{
    public string $selectedProvider = 'telc';

    public string $selectedLevel = 'B1';

    public bool $examStarted = false;

    public bool $examFinished = false;

    public ?int $activeVoiceTopicId = null;

    /** @var array<int, array{role: string, text: string}> */
    public array $chatHistory = [];

    public bool $aiResponding = false;

    public bool $autoPlayTts = true;

    public bool $examComplete = false;

    public string $userInput = '';

    public bool $isRecording = false;

    public int $examTimer = 0;

    public bool $timerRunning = false;

    public int $currentTeil = 0;

    public string $currentTeilLabel = '';

    /** @var array<int, array{teil: int, label: string, seconds: int}> */
    public array $teilTimings = [];

    public int $totalExamTime = 0;

    /** @var array<string, mixed>|null */
    public ?array $result = null;

    public bool $isGuest = false;

    public int $guestPracticeCount = 0;

    public bool $showTierPrompt = false;

    public string $tierPromptMessage = '';

    public bool $isAiSpeaking = false;

    public bool $waitingForTopicSelection = false;

    public function mount(): void
    {
        $this->isGuest = ! auth()->check();
        $this->loadGuestPracticeCountFromCookie();
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

                // Goethe topics
                ['title' => 'Mein Alltag und meine Freizeit', 'counter_topic' => 'Digitale Medien im Alltag', 'level' => 'A2', 'exam_provider' => 'goethe', 'is_active' => true],
                ['title' => 'Brauchen Kinder Mobiltelefone?', 'counter_topic' => 'Haustiere – Ja oder Nein?', 'level' => 'B1', 'exam_provider' => 'goethe', 'is_active' => true],
                ['title' => 'Alternative Wohnformen', 'counter_topic' => 'Konsumverhalten von Jugendlichen', 'level' => 'B2', 'exam_provider' => 'goethe', 'is_active' => true],
                ['title' => 'Chancen und Risiken des lebenslangen Lernens', 'counter_topic' => 'Digitalisierung in der Bildung', 'level' => 'C1', 'exam_provider' => 'goethe', 'is_active' => true],
            ];
            foreach ($topics as $topic) {
                VoiceTopic::create($topic);
            }
        }
    }

    public function loadGuestPracticeCountFromCookie(): void
    {
        $raw = request()->cookie('guest_voice_practice_count');
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            $this->guestPracticeCount = is_int($decoded) ? $decoded : 0;
        } else {
            $this->guestPracticeCount = 0;
        }
    }

    public function saveGuestPracticeCountToCookie(): void
    {
        Cookie::queue('guest_voice_practice_count', $this->guestPracticeCount, 60 * 24 * 365);
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
    }

    public function startExam(): void
    {
        if ($this->isGuest) {
            if ($this->guestPracticeCount >= 1) {
                $this->showTierPrompt = true;
                $this->tierPromptMessage = 'Sie haben Ihre kostenlose Sprechübung genutzt. Erstellen Sie ein Konto für weitere Übungen.';

                return;
            }
        } else {
            if (! auth()->user()->isAdmin() && ! $this->hasActiveSubscription()) {
                $count = ExamAttempt::query()
                    ->where('user_id', auth()->id())
                    ->where('result->is_speaking_only', true)
                    ->count();
                if ($count >= 2) {
                    $this->showTierPrompt = true;
                    $this->tierPromptMessage = 'Sie haben Ihre 2 kostenlosen Sprechprüfungen aufgebraucht. Bitte abonnieren Sie ein Premium-Paket.';

                    return;
                }
            }
        }

        if (! $this->hasPresentationInTeil2() && ! $this->activeVoiceTopicId) {
            $this->addError('exam', 'Bitte wählen Sie zuerst ein Thema aus.');

            return;
        }

        $this->configureTeilTimings();

        $systemPrompt = SpeakingPromptService::getSystemInstruction($this->selectedProvider, $this->selectedLevel);
        $systemPrompt .= "\n\nDu bist der KI-Prüfer namens \"ERFOLG\" der Speaking Exam Simulator Plattform (ähnlich wie Zara AI). Bitte stelle dich als \"ERFOLG\" vor und führe die Prüfung entsprechend durch.";
        $systemPrompt .= "\nCRITICAL CONVERSATIONAL RULES for ERFOLG:\n";
        $systemPrompt .= "- Speak entirely in clear, natural German suited for the designated exam tier ({$this->selectedLevel}).\n";
        $systemPrompt .= "- Maintain a formal, professional, and encouraging tone, matching the cadence of an official German language examiner.\n";
        $systemPrompt .= "- Adaptive Questioning: If the user provides an excellent answer, dynamically shift your next question to be slightly more complex. If they struggle, gently scale back the grammatical complexity of your next prompt.\n";
        $systemPrompt .= '- Do not output any meta-text, markers or formatting other than plain German speech (and [EXAM_COMPLETE] when the exam is finished).';

        $chat = Gemini::generativeModel(model: 'gemini-2.5-flash')
            ->withSystemInstruction(Content::parse($systemPrompt))
            ->startChat();

        $this->chatHistory = [];
        $this->examStarted = true;
        $this->examFinished = false;
        $this->examComplete = false;
        $this->result = null;
        $this->userInput = '';
        $this->aiResponding = true;
        $this->currentTeil = 1;
        $this->currentTeilLabel = $this->teilTimings[0]['label'] ?? 'Teil 1';
        $this->examTimer = $this->teilTimings[0]['seconds'] ?? 240;
        $this->timerRunning = true;

        try {
            $topic = VoiceTopic::find($this->activeVoiceTopicId);
            $topicContext = $topic ? "Das gewählte Thema des Lernenden ist: \"{$topic->title}\"." : '';
            $response = $chat->sendMessage("Bitte beginnen Sie mit der Prüfung. {$topicContext}");
            $aiText = $response->text();

            $cleanText = $this->stripExamCompleteMarker($aiText);
            $this->chatHistory[] = ['role' => 'model', 'text' => $cleanText];
            $this->checkExamComplete($aiText);

            $this->dispatch('playAiTts', text: $cleanText);
        } catch (\Throwable $e) {
            $this->chatHistory[] = ['role' => 'model', 'text' => 'Fehler bei der Initialisierung: '.$e->getMessage()];
        } finally {
            $this->aiResponding = false;
        }
    }

    public function sendMessage(): void
    {
        $text = trim($this->userInput);
        if ($text === '' || $this->aiResponding || $this->examComplete || $this->examFinished || ($this->examTimer <= 0 && $this->currentTeil >= count($this->teilTimings))) {
            return;
        }

        $this->chatHistory[] = ['role' => 'user', 'text' => $text];
        $this->userInput = '';
        $this->aiResponding = true;

        try {
            $chat = $this->rebuildChatSession();
            $response = $chat->sendMessage($text);
            $aiText = $response->text();

            $cleanText = $this->stripExamCompleteMarker($aiText);
            $this->chatHistory[] = ['role' => 'model', 'text' => $cleanText];

            $this->detectTeilTransition($aiText);
            $this->checkExamComplete($aiText);

            if (! $this->examComplete && ! $this->examFinished) {
                $this->dispatch('playAiTts', text: $cleanText);
            }

            if ($this->examComplete) {
                $this->timerRunning = false;
                $this->dispatch('stop-all-speech');
                $this->gradeExam();
            }
        } catch (\Throwable $e) {
            $this->chatHistory[] = ['role' => 'model', 'text' => 'Fehler bei der KI-Antwort: '.$e->getMessage()];
        } finally {
            $this->aiResponding = false;
        }
    }

    public function sendVoiceInput(string $transcript): void
    {
        $transcript = trim($transcript);
        if ($transcript !== '') {
            try {
                $response = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->generateContent("Füge dem folgenden gesprochenen deutschen Text korrekte Satzzeichen (Punkt, Komma, Fragezeichen, etc.) hinzu. Antworte NUR mit dem korrigierten Text, ändere keine Wörter oder Bedeutungen. Text: \"{$transcript}\"");
                $punctuated = trim($response->text());
                $punctuated = trim($punctuated, '"\'');
                if ($punctuated !== '') {
                    $transcript = $punctuated;
                }
            } catch (\Throwable $e) {
                // Fallback on error
            }
        }
        $this->userInput = $transcript;
        $this->isRecording = false;
        $this->sendMessage();
    }

    public function tick(): void
    {
        if ($this->isAiSpeaking || ! $this->timerRunning || $this->examTimer <= 0) {
            return;
        }

        $this->examTimer--;

        if ($this->examTimer <= 0) {
            $this->timerRunning = false;

            if ($this->examComplete) {
                $this->dispatch('stop-all-speech');
                $this->gradeExam();
            } elseif ($this->currentTeil < count($this->teilTimings)) {
                $prevTeil = $this->currentTeil;
                $this->advanceTeil();
                $this->dispatch('stop-all-speech');

                $transitionNotice = "--- Zeit für Teil {$prevTeil} ist abgelaufen. Beginn von {$this->currentTeilLabel}. ---";
                $this->chatHistory[] = ['role' => 'model', 'text' => $transitionNotice];

                try {
                    $chat = $this->rebuildChatSession();
                    $response = $chat->sendMessage("Die Zeit für Teil {$prevTeil} ist abgelaufen. Bitte beende Teil {$prevTeil} kurz und leite direkt den nächsten Teil ({$this->currentTeilLabel}) ein.");
                    $aiText = $response->text();
                    $cleanText = $this->stripExamCompleteMarker($aiText);
                    $this->chatHistory[] = ['role' => 'model', 'text' => $cleanText];
                    $this->checkExamComplete($aiText);
                    $this->dispatch('playAiTts', text: $cleanText);
                } catch (\Throwable $e) {
                    // Fallback if AI transition prompt fails
                }
            } else {
                $this->examComplete = true;
                $this->dispatch('stop-all-speech');
                $this->gradeExam();
            }
        }
    }

    public function hasPresentationInTeil2(): bool
    {
        return ($this->selectedProvider === 'telc' && $this->selectedLevel === 'B1') ||
               ($this->selectedProvider === 'goethe' && $this->selectedLevel === 'B1');
    }

    public function advanceTeil(): void
    {
        if ($this->currentTeil < count($this->teilTimings)) {
            $this->currentTeil++;
            $teil = $this->teilTimings[$this->currentTeil - 1];
            $this->currentTeilLabel = $teil['label'];
            $this->examTimer = $teil['seconds'];

            if ($this->currentTeil === 2 && $this->hasPresentationInTeil2() && ! $this->activeVoiceTopicId) {
                $this->waitingForTopicSelection = true;
                $this->timerRunning = false;
            } else {
                $this->timerRunning = true;
            }
        }
    }

    public function selectTransitionTopic(int $topicId): void
    {
        $topic = VoiceTopic::findOrFail($topicId);
        $this->activeVoiceTopicId = $topic->id;
        $this->waitingForTopicSelection = false;
        $this->timerRunning = true;

        $text = "Ich habe mich für das Thema \"{$topic->title}\" entschieden.";
        $this->chatHistory[] = ['role' => 'user', 'text' => $text];
        $this->aiResponding = true;

        try {
            $chat = $this->rebuildChatSession();
            $response = $chat->sendMessage($text);
            $aiText = $response->text();

            $cleanText = $this->stripExamCompleteMarker($aiText);
            $this->chatHistory[] = ['role' => 'model', 'text' => $cleanText];

            $this->detectTeilTransition($aiText);
            $this->checkExamComplete($aiText);

            if (! $this->examComplete && ! $this->examFinished) {
                $this->dispatch('playAiTts', text: $cleanText);
            }
        } catch (\Throwable $e) {
            $this->chatHistory[] = ['role' => 'model', 'text' => 'Fehler bei der Themenauswahl: '.$e->getMessage()];
        } finally {
            $this->aiResponding = false;
        }
    }

    public function submitExam(): void
    {
        if ($this->examComplete) {
            $this->timerRunning = false;
            $this->gradeExam();
        }
    }

    public function gradeExam(): void
    {
        if ($this->result !== null) {
            return;
        }

        $this->aiResponding = true;

        $conversationText = '';
        foreach ($this->chatHistory as $turn) {
            $speaker = $turn['role'] === 'user' ? 'Lernender' : 'Prüfer';
            $conversationText .= "{$speaker}: {$turn['text']}\n\n";
        }

        try {
            $gradingPrompt = "Analysiere das folgende Sprechprüfungsprotokoll und erstelle eine strukturierte Bewertung.

Protokoll:
{$conversationText}

Erstelle eine strukturierte Bewertung mit folgenden Kriterien basierend auf dem offiziellen Prüfungsformat ({$this->selectedProvider} {$this->selectedLevel}):

Gib das Ergebnis EXAKT im folgenden JSON-Format zurück (KEIN Markdown, KEIN Code-Block, nur纯 JSON):
{
  \"earned\": <Gesamtpunkte>,
  \"possible\": <Maximumpunkte>,
  \"percentage\": <Prozentsatz>,
  \"passed\": <true/false>,
  \"details\": [
    {
      \"id\": \"teil-1\",
      \"label\": \"Teil 1 — <Beschreibung>\",
      \"points\": <Punkte>,
      \"max_points\": <Max>,
      \"explanation\": \"<Bewertungstext>\"
    }
  ],
  \"musterSaeften\": [\"<Muster-Satz 1>\", \"<Muster-Satz 2>\"]
}

Bewerte jeden Teil einzeln. Gib Muster-Sätze für Korrekturen. Das Ergebnis muss valides JSON sein.";

            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(
                    Content::parse('Du bist ein präziser deutscher Prüfer. Antworte NUR mit validem JSON. Keine Erklärungen vor oder nach dem JSON.')
                )
                ->generateContent($gradingPrompt);

            $responseText = trim($result->text());
            $responseText = preg_replace('/```json\s*/i', '', $responseText);
            $responseText = preg_replace('/```\s*/i', '', $responseText);
            $responseText = trim($responseText);

            $parsed = json_decode($responseText, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($parsed)) {
                $parsed['is_speaking_only'] = true;
                $parsed['percentage'] = $parsed['percentage'] ?? ($parsed['possible'] > 0 ? round(($parsed['earned'] / $parsed['possible']) * 100, 2) : 0);
                $parsed['passed'] = $parsed['passed'] ?? ($parsed['percentage'] >= 60);

                $this->result = $parsed;
            } else {
                $this->result = $this->fallbackGrading($conversationText);
            }
        } catch (\Throwable $e) {
            $this->result = $this->fallbackGrading($conversationText);
        }

        $template = ExamTemplate::query()
            ->where('exam_provider', $this->selectedProvider)
            ->where('level', $this->selectedLevel)
            ->first();

        if ($template && auth()->check()) {
            ExamAttempt::create([
                'exam_template_id' => $template->id,
                'user_id' => auth()->id(),
                'answers' => ['chat_history' => $this->chatHistory],
                'result' => $this->result,
                'started_at' => now(),
                'submitted_at' => now(),
            ]);
        }

        if ($this->isGuest) {
            $this->guestPracticeCount++;
            $this->saveGuestPracticeCountToCookie();
        }

        $this->examFinished = true;
        $this->aiResponding = false;
    }

    /** @return array<string, mixed> */
    private function fallbackGrading(string $conversationText): array
    {
        try {
            $fallbackPrompt = "Bewerte dieses Sprechprüfungsprotokoll ({@{$this->selectedProvider}} {$this->selectedLevel}).

Protokoll:
{$conversationText}

Gib eine Gesamtpunktzahl und eine kurze Bewertung. Format: SCORE: X/Y gefolgt von der Bewertung.";

            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->generateContent($fallbackPrompt);

            $text = $result->text();
            $earned = 0;
            $possible = $this->getMaxPoints();

            if (preg_match('/(\d+)\s*\/\s*(\d+)/', $text, $m)) {
                $earned = (int) $m[1];
                $possible = (int) $m[2];
            } elseif (preg_match('/SCORE:\s*(\d+)/i', $text, $m)) {
                $earned = (int) $m[1];
            }

            $pct = $possible > 0 ? round(($earned / $possible) * 100, 2) : 0;

            return [
                'earned' => $earned,
                'possible' => $possible,
                'percentage' => $pct,
                'passed' => $pct >= 60,
                'details' => [
                    [
                        'id' => 'global',
                        'label' => 'Gesamtbewertung',
                        'points' => $earned,
                        'max_points' => $possible,
                        'explanation' => $text,
                    ],
                ],
                'musterSaeften' => [],
                'is_speaking_only' => true,
            ];
        } catch (\Throwable $e) {
            return [
                'earned' => 0,
                'possible' => $this->getMaxPoints(),
                'percentage' => 0,
                'passed' => false,
                'details' => [
                    [
                        'id' => 'error',
                        'label' => 'Bewertung fehlgeschlagen',
                        'points' => 0,
                        'max_points' => $this->getMaxPoints(),
                        'explanation' => 'Fehler bei der Bewertung: '.$e->getMessage(),
                    ],
                ],
                'musterSaeften' => [],
                'is_speaking_only' => true,
            ];
        }
    }

    private function getMaxPoints(): int
    {
        return match (true) {
            $this->selectedProvider === 'telc' && $this->selectedLevel === 'A1' => 15,
            $this->selectedProvider === 'telc' && $this->selectedLevel === 'A2' => 15,
            $this->selectedProvider === 'telc' && $this->selectedLevel === 'B1' => 75,
            $this->selectedProvider === 'telc' && $this->selectedLevel === 'B2' => 75,
            $this->selectedProvider === 'telc' && $this->selectedLevel === 'C1' => 48,
            $this->selectedProvider === 'goethe' && $this->selectedLevel === 'A2' => 15,
            $this->selectedProvider === 'goethe' && $this->selectedLevel === 'B1' => 100,
            $this->selectedProvider === 'goethe' && $this->selectedLevel === 'B2' => 100,
            $this->selectedProvider === 'goethe' && $this->selectedLevel === 'C1' => 100,
            default => 75,
        };
    }

    private function configureTeilTimings(): void
    {
        $this->teilTimings = match (true) {
            $this->selectedProvider === 'telc' && $this->selectedLevel === 'A1' => [
                ['teil' => 1, 'label' => 'Teil 1 — Sich vorstellen', 'seconds' => 120],
                ['teil' => 2, 'label' => 'Teil 2 — Fragen stellen', 'seconds' => 180],
                ['teil' => 3, 'label' => 'Teil 3 — Bitten formulieren', 'seconds' => 180],
            ],
            $this->selectedProvider === 'telc' && $this->selectedLevel === 'A2' => [
                ['teil' => 1, 'label' => 'Teil 1 — Präsentation', 'seconds' => 180],
                ['teil' => 2, 'label' => 'Teil 2 — Gespräch führen', 'seconds' => 180],
                ['teil' => 3, 'label' => 'Teil 3 — Etwas aushandeln', 'seconds' => 180],
            ],
            $this->selectedProvider === 'telc' && $this->selectedLevel === 'B1' => [
                ['teil' => 1, 'label' => 'Teil 1 — Kontaktaufnahme', 'seconds' => 180],
                ['teil' => 2, 'label' => 'Teil 2 — Gespräch über ein Thema (Präsentation)', 'seconds' => 300],
                ['teil' => 3, 'label' => 'Teil 3 — Gemeinsam planen', 'seconds' => 300],
            ],
            $this->selectedProvider === 'telc' && $this->selectedLevel === 'B2' => [
                ['teil' => 1, 'label' => 'Teil 1 — Präsentation', 'seconds' => 180],
                ['teil' => 2, 'label' => 'Teil 2 — Diskussion', 'seconds' => 300],
                ['teil' => 3, 'label' => 'Teil 3 — Problemlösung', 'seconds' => 300],
            ],
            $this->selectedProvider === 'telc' && $this->selectedLevel === 'C1' => [
                ['teil' => 1, 'label' => 'Teil 1 — Präsentation & Zusammenfassung', 'seconds' => 300],
                ['teil' => 2, 'label' => 'Teil 2 — Diskussion', 'seconds' => 360],
            ],
            $this->selectedProvider === 'goethe' && $this->selectedLevel === 'A2' => [
                ['teil' => 1, 'label' => 'Teil 1 — Fragen stellen', 'seconds' => 180],
                ['teil' => 2, 'label' => 'Teil 2 — Von sich erzählen', 'seconds' => 180],
                ['teil' => 3, 'label' => 'Teil 3 — Gemeinsam planen', 'seconds' => 180],
            ],
            $this->selectedProvider === 'goethe' && $this->selectedLevel === 'B1' => [
                ['teil' => 1, 'label' => 'Teil 1 — Gemeinsam planen', 'seconds' => 300],
                ['teil' => 2, 'label' => 'Teil 2 — Präsentation', 'seconds' => 180],
                ['teil' => 3, 'label' => 'Teil 3 — Rückmeldung & Fragen', 'seconds' => 240],
            ],
            $this->selectedProvider === 'goethe' && $this->selectedLevel === 'B2' => [
                ['teil' => 1, 'label' => 'Teil 1 — Vortrag halten', 'seconds' => 300],
                ['teil' => 2, 'label' => 'Teil 2 — Gespräch führen', 'seconds' => 300],
            ],
            $this->selectedProvider === 'goethe' && $this->selectedLevel === 'C1' => [
                ['teil' => 1, 'label' => 'Teil 1 — Vortrag & Fragen', 'seconds' => 300],
                ['teil' => 2, 'label' => 'Teil 2 — Gespräch führen', 'seconds' => 300],
            ],
            default => [
                ['teil' => 1, 'label' => 'Teil 1', 'seconds' => 240],
                ['teil' => 2, 'label' => 'Teil 2', 'seconds' => 300],
            ],
        };

        $this->totalExamTime = array_sum(array_column($this->teilTimings, 'seconds'));
    }

    private function detectTeilTransition(string $aiResponse): void
    {
        if (preg_match('/Teil\s+(\d)/i', $aiResponse, $m)) {
            $newTeil = (int) $m[1];
            if ($newTeil > $this->currentTeil && $newTeil <= count($this->teilTimings)) {
                $this->advanceTeil();
            }
        }
    }

    private function checkExamComplete(string $aiResponse): bool
    {
        if (str_contains($aiResponse, '[EXAM_COMPLETE]')) {
            $this->examComplete = true;

            return true;
        }

        return false;
    }

    private function stripExamCompleteMarker(string $text): string
    {
        return str_replace('[EXAM_COMPLETE]', '', $text);
    }

    private function rebuildChatSession(): ChatSession
    {
        $systemPrompt = SpeakingPromptService::getSystemInstruction($this->selectedProvider, $this->selectedLevel);
        $systemPrompt .= "\n\nDu bist der KI-Prüfer namens \"ERFOLG\" der Speaking Exam Simulator Plattform (ähnlich wie Zara AI). Bitte stelle dich als \"ERFOLG\" vor und führe die Prüfung entsprechend durch.";
        $systemPrompt .= "\nCRITICAL CONVERSATIONAL RULES for ERFOLG:\n";
        $systemPrompt .= "- Speak entirely in clear, natural German suited for the designated exam tier ({$this->selectedLevel}).\n";
        $systemPrompt .= "- Maintain a formal, professional, and encouraging tone, matching the cadence of an official German language examiner.\n";
        $systemPrompt .= "- Adaptive Questioning: If the user provides an excellent answer, dynamically shift your next question to be slightly more complex. If they struggle, gently scale back the grammatical complexity of your next prompt.\n";
        $systemPrompt .= '- Do not output any meta-text, markers or formatting other than plain German speech (and [EXAM_COMPLETE] when the exam is finished).';

        $model = Gemini::generativeModel(model: 'gemini-2.5-flash')
            ->withSystemInstruction(Content::parse($systemPrompt));

        /** @var array<Content> $history */
        $history = [];
        foreach ($this->chatHistory as $turn) {
            $role = $turn['role'] === 'user' ? Role::USER : Role::MODEL;
            $history[] = Content::parse($turn['text'], $role);
        }

        return $model->startChat(history: $history);
    }

    public function repeatExam(): void
    {
        $this->dispatch('stop-all-speech');
        $this->examStarted = false;
        $this->examFinished = false;
        $this->examComplete = false;
        $this->activeVoiceTopicId = null;
        $this->chatHistory = [];
        $this->userInput = '';
        $this->isRecording = false;
        $this->examTimer = 0;
        $this->timerRunning = false;
        $this->currentTeil = 0;
        $this->currentTeilLabel = '';
        $this->teilTimings = [];
        $this->totalExamTime = 0;
        $this->result = null;
        $this->aiResponding = false;
    }

    public function render(): View
    {
        return view('livewire.voice-input', [
            'filteredTopics' => $this->getFilteredTopicsProperty(),
        ]);
    }
}
