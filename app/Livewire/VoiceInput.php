<?php

namespace App\Livewire;

use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
use App\Models\Subscription;
use App\Models\VoiceTopic;
use App\Services\SpeakingPromptService;
use Gemini\Data\Content;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\View\View;
use Livewire\Component;

class VoiceInput extends Component
{
    public string $selectedProvider = 'telc';

    public string $selectedLevel = 'B1';

    public bool $examStarted = false;

    public bool $examFinished = false;

    public int $activeElementIndex = 0;

    public ?int $activeTemplateId = null;

    /** @var array<int, array<string, mixed>> */
    public array $speakingElements = [];

    /** @var array<int, string> */
    public array $aiGeneratedTopics = [];

    public string $selectedTopic = '';

    public string $unchosenTopic = '';

    public string $aiPresentationText = '';

    public string $transcribedText = '';

    /** @var array<string, array<int, array<string, string>>> */
    public array $speakingHistory = [];

    /** @var array<string, string> */
    public array $speakingFeedback = [];

    /** @var array<string, bool> */
    public array $speakingProcessing = [];

    /** @var array<string, mixed> */
    public array $answers = [];

    /** @var array<string, mixed>|null */
    public ?array $result = null;

    public function mount(): void
    {
        $this->seedVoiceTopicsIfEmpty();
    }

    private function seedVoiceTopicsIfEmpty(): void
    {
        if (VoiceTopic::count() === 0) {
            $topics = [
                ['title' => 'Mein Zuhause und meine Familie', 'level' => 'B1', 'exam_provider' => 'telc', 'is_active' => true],
                ['title' => 'Meine Arbeitswelt', 'level' => 'B1', 'exam_provider' => 'telc', 'is_active' => true],
                ['title' => 'Meine Freizeit und Hobbys', 'level' => 'B1', 'exam_provider' => 'goethe', 'is_active' => true],
                ['title' => 'Bildung und Weiterbildung', 'level' => 'B2', 'exam_provider' => 'telc', 'is_active' => true],
                ['title' => 'Gesellschaft und Politik', 'level' => 'C1', 'exam_provider' => 'telc', 'is_active' => true],
            ];
            foreach ($topics as $topic) {
                VoiceTopic::create($topic);
            }
        }
    }

    public function getFilteredTopicsProperty(): \Illuminate\Support\Collection
    {
        return VoiceTopic::query()
            ->where('exam_provider', $this->selectedProvider)
            ->where('level', $this->selectedLevel)
            ->where('is_active', true)
            ->get();
    }

    public function evaluateSpeech(): void
    {
        if (! auth()->check()) {
            $this->addError('login', 'Bitte loggen Sie sich ein.');

            return;
        }

        $count = session('speaking_practice_count', 0);
        if ($count >= 2) {
            $this->addError('speaking_limit', 'Sie haben das Übungslimit erreicht. Bitte abonnieren Sie ein Premium-Paket.');

            return;
        }

        $text = trim($this->transcribedText);
        if ($text === '') {
            return;
        }

        $selectedTopic = $this->selectedTopic;
        $level = $this->selectedLevel;
        $provider = $this->selectedProvider;

        try {
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(
                    Content::parse("Du bist ein {$provider} {$level} Prüfer. Bewerte die mündliche Leistung.")
                )
                ->generateContent(
                    "Thema: {$selectedTopic}\n\nTranskription:\n{$text}\n\n"
                    .'Bitte bewerten Sie die Leistung und geben Sie eine Punktzahl im Format SCORE: X/24 an.'
                );

            $feedback = $result->text();
            session(['speaking_practice_count' => $count + 1]);
        } catch (\Throwable $e) {
            $this->addError('exam', 'Fehler bei der Bewertung: '.$e->getMessage());
        }
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

    public function startExam(): void
    {
        if (! auth()->check()) {
            $this->addError('login', 'Bitte loggen Sie sich ein, um die Sprechprüfung zu starten.');

            return;
        }

        // Enforce 2 attempts limit for free users
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

        $template = ExamTemplate::query()
            ->where('exam_provider', $this->selectedProvider)
            ->where('level', $this->selectedLevel)
            ->first();

        if (! $template) {
            $this->addError('exam', 'Keine Prüfungsvorlage für diese Auswahl gefunden.');

            return;
        }

        $this->activeTemplateId = $template->id;

        // Find the speaking section
        $speakingSection = null;
        foreach ($template->sections as $sec) {
            $hasSp = false;
            foreach ($sec['elements'] ?? [] as $el) {
                if (($el['type'] ?? '') === 'speaking') {
                    $hasSp = true;
                    break;
                }
            }
            if ($hasSp) {
                $speakingSection = $sec;
                break;
            }
        }

        if (! $speakingSection) {
            $this->addError('exam', 'Diese Prüfung hat keinen mündlichen Teil.');

            return;
        }

        // Initialize elements
        $this->speakingElements = array_values(array_filter($speakingSection['elements'] ?? [], function ($el) {
            return ($el['type'] ?? '') === 'speaking';
        }));

        $this->activeElementIndex = 0;
        $this->speakingHistory = [];
        $this->speakingFeedback = [];
        $this->speakingProcessing = [];
        $this->answers = [];
        $this->selectedTopic = '';
        $this->unchosenTopic = '';
        $this->aiPresentationText = '';
        $this->examStarted = true;
        $this->examFinished = false;
        $this->result = null;

        $this->loadSpeakingTopics();
    }

    public function loadSpeakingTopics(): void
    {
        $el1a = $this->speakingElements[0] ?? null;
        if (! $el1a) {
            return;
        }

        $spContent = $el1a['content'] ?? '';
        $parsedTopics = [];
        if (preg_match_all('/(\d+)\.\s*(.+?)(?=\s*(?:\/|oder)\s*\d+\.|\s*$)/u', $spContent, $topicMatches)) {
            foreach ($topicMatches[2] as $t) {
                $parsedTopics[] = trim($t);
            }
        }

        if (count($parsedTopics) >= 2) {
            $this->aiGeneratedTopics = $parsedTopics;
        } else {
            $level = $this->selectedLevel;
            $provider = $this->selectedProvider;
            try {
                $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->generateContent(
                        "Generiere zwei verschiedene, niveauangemessene und typische Sprechthemen für eine mündliche {$provider} {$level} Prüfung.\n"
                        ."Antworte NUR im folgenden JSON-Format:\n"
                        .'["Thema 1", "Thema 2"]'
                    );

                $text = trim($result->text());
                if (str_starts_with($text, '```json')) {
                    $text = substr($text, 7, -3);
                }
                $decoded = json_decode(trim($text), true);
                if (is_array($decoded) && count($decoded) >= 2) {
                    $this->aiGeneratedTopics = $decoded;
                }
            } catch (\Throwable $e) {
                $this->aiGeneratedTopics = ['Freies Thema A', 'Freies Thema B'];
            }
        }
    }

    public function chooseTopic(string $topic): void
    {
        $this->selectedTopic = $topic;

        $remaining = array_values(array_filter($this->aiGeneratedTopics, fn ($t) => $t !== $topic));
        $this->unchosenTopic = ! empty($remaining) ? $remaining[0] : 'Das alternative Thema';

        $elId = $this->speakingElements[0]['id'];
        $this->speakingHistory[$elId] = [];
        $this->answers[$elId] = '';
    }

    public function generateAiPresentation(string $unchosenTopic): void
    {
        $sp1bId = $this->speakingElements[1]['id'] ?? 'SP-1b';
        $this->speakingProcessing[$sp1bId] = true;

        try {
            $prompt = "Schreibe einen gut strukturierten Vortrag (Einleitung, Hauptteil, Eigene Meinung, Schluss/Fazit) zum Thema: \"{$unchosenTopic}\". Der Vortrag soll klar und verständlich sein, mit einer deutlichen Argumentationslinie.";

            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(Content::parse('Du bist ein TELC-Prüfungskandidat, der einen Vortrag hält. Sprich den Lernenden direkt an.'))
                ->generateContent($prompt);

            $this->aiPresentationText = $result->text();

            $this->speakingHistory[$sp1bId] = [
                [
                    'role' => 'assistant',
                    'text' => $this->aiPresentationText,
                ],
            ];

            $this->answers[$sp1bId] = $this->speakingHistory[$sp1bId];
        } catch (\Throwable $e) {
            $this->aiPresentationText = 'Fehler bei der Vortragserstellung: '.$e->getMessage();
        } finally {
            $this->speakingProcessing[$sp1bId] = false;
        }
    }

    public function submitSpeech(string $elementId, string $text): void
    {
        $text = trim($text);
        if (empty($text)) {
            return;
        }

        $this->transcribedText = $text;
        $this->analyzeSpeaking($elementId, $text);
    }

    public function sendDiscussionMessage(string $elementId, string $text): void
    {
        $text = trim($text);
        if (empty($text)) {
            return;
        }

        if (! isset($this->speakingHistory[$elementId])) {
            $this->speakingHistory[$elementId] = [];
        }

        $this->speakingHistory[$elementId][] = [
            'role' => 'user',
            'text' => $text,
        ];

        $this->speakingProcessing[$elementId] = true;

        $element = collect($this->speakingElements)->firstWhere('id', $elementId);
        $prompt = $element['content'] ?? '';
        $level = $this->selectedLevel;
        $provider = $this->selectedProvider;
        $systemInstruction = SpeakingPromptService::getSystemInstruction($provider, $level);

        $historyContext = '';
        foreach ($this->speakingHistory[$elementId] as $turn) {
            $roleLabel = $turn['role'] === 'user' ? 'Lernender' : 'Prüfer';
            $historyContext .= "{$roleLabel}: {$turn['text']}\n";
        }

        try {
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(
                    Content::parse(
                        "Du bist ein {$provider} {$level} Prüfer für die mündliche Prüfung. "
                        .'Unterhalte dich interaktiv mit dem Lernenden über das gewählte Thema. '
                        .'Lies den Gesprächsverlauf. Reagiere auf die letzte Antwort des Lernenden, '
                        .'stelle eine kurze, logische Anschlussfrage oder führe das Gespräch fort. '
                        .'WICHTIG: Wenn dies der 3. oder 4. Redebeitrag des Lernenden ist, '
                        .'beende das Gespräch höflich und weise darauf hin, dass die Prüfung nun vorbei ist.'
                    )
                )
                ->generateContent(
                    "Aufgabenstellung/Thema: {$prompt}\n\n"
                    ."Bisheriger Gesprächsverlauf:\n{$historyContext}\n"
                    .'Generiere die nächste Antwort des Prüfers:'
                );

            $this->speakingHistory[$elementId][] = [
                'role' => 'assistant',
                'text' => $result->text(),
            ];

            $this->answers[$elementId] = $this->speakingHistory[$elementId];
        } catch (\Throwable $e) {
            $this->speakingHistory[$elementId][] = [
                'role' => 'assistant',
                'text' => 'Fehler: '.$e->getMessage(),
            ];
        } finally {
            $this->speakingProcessing[$elementId] = false;
        }
    }

    public function analyzeSpeaking(string $elementId, string $transcribedText): void
    {
        $this->speakingProcessing[$elementId] = true;
        $this->speakingFeedback[$elementId] = '';

        if (! isset($this->speakingHistory[$elementId])) {
            $this->speakingHistory[$elementId] = [];
        }

        $this->speakingHistory[$elementId][] = [
            'role' => 'user',
            'text' => $transcribedText,
        ];

        $element = collect($this->speakingElements)->firstWhere('id', $elementId);
        $maxPoints = (int) ($element['points'] ?? 6);
        $level = $this->selectedLevel;
        $provider = $this->selectedProvider;

        if (str_ends_with($elementId, '-SP-1a') || str_ends_with($elementId, '-sp-1a')) {
            $criteriaPrompt = "Bewerte den Vortrag des Lernenden zu dem von ihm gewählten Thema.\nKriterien:\n- Inhaltliche Angemessenheit & Relevanz (0-2 Punkte)\n- Struktur und logischer Aufbau (0-2 Punkte)\n- Ausdrucksweise und Kohärenz (0-2 Punkte)\n\nBitte gib eine kurze Begründung für jedes Kriterium an.\nGib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        } else {
            $criteriaPrompt = "Bewerte die Reaktion des Lernenden auf den Vortrag.\nKriterien:\n- Verständnis des gehörten Vortrags (0-2 Punkte)\n- Relevanz und Qualität der Anschlussfragen/Kommentare (0-2 Punkte)\n\nBitte gib eine kurze Begründung für jedes Kriterium an.\nGib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        }

        $systemInstruction = SpeakingPromptService::getSystemInstruction($provider, $level);

        if (str_ends_with($elementId, '-SP-1a') || str_ends_with($elementId, '-sp-1a')) {
            try {
                $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->withSystemInstruction(
                        Content::parse($systemInstruction."\n\n".$criteriaPrompt)
                    )
                    ->generateContent(
                        "Gewähltes Thema: {$this->selectedTopic}\n\n"
                        ."Transkription des Vortrags:\n{$transcribedText}\n\n"
                        .'Bitte bewerten Sie die Präsentation anhand der vier Kriterien:'
                    );

                $assistantReply = $result->text();
                $this->speakingHistory[$elementId][] = [
                    'role' => 'assistant',
                    'text' => $assistantReply,
                ];

                $this->speakingFeedback[$elementId] = $assistantReply;
                $this->answers[$elementId] = $transcribedText;

                // Move to step 1b
                $this->activeElementIndex = 1;
                $this->generateAiPresentation($this->unchosenTopic);
            } catch (\Throwable $e) {
                array_pop($this->speakingHistory[$elementId]);
                $this->speakingFeedback[$elementId] = 'Fehler: '.$e->getMessage();
            } finally {
                $this->speakingProcessing[$elementId] = false;
            }

            return;
        }

        if (str_ends_with($elementId, '-SP-1b') || str_ends_with($elementId, '-sp-1b')) {
            try {
                $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->withSystemInstruction(
                        Content::parse($systemInstruction."\n\n".$criteriaPrompt)
                    )
                    ->generateContent(
                        "AI Vortrag:\n{$this->aiPresentationText}\n\n"
                        ."Antwort des Lernenden:\n{$transcribedText}\n\n"
                        .'Bitte bewerten Sie die Reaktion anhand der vier Kriterien:'
                    );

                $assistantReply = $result->text();
                $this->speakingHistory[$elementId][] = [
                    'role' => 'assistant',
                    'text' => $assistantReply,
                ];

                $this->speakingFeedback[$elementId] = $assistantReply;
                $this->answers[$elementId] = $transcribedText;

                // Move to step 2 (Discussion)
                $this->activeElementIndex = 2;
                $sp2Id = $this->speakingElements[2]['id'] ?? 'SP-2';
                $this->speakingHistory[$sp2Id] = [];
            } catch (\Throwable $e) {
                array_pop($this->speakingHistory[$elementId]);
                $this->speakingFeedback[$elementId] = 'Fehler: '.$e->getMessage();
            } finally {
                $this->speakingProcessing[$elementId] = false;
            }
        }
    }

    public function finishDiscussionAndGrade(): void
    {
        $this->submitExam();
    }

    public function submitExam(): void
    {
        $this->speakingProcessing['global'] = true;

        try {
            $details = [];
            $autoScored = [];

            // Loop through SP-1a, SP-1b, SP-2 and evaluate them
            foreach ($this->speakingElements as $element) {
                $elId = $element['id'];
                if (str_ends_with($elId, '-GLOBAL') || str_ends_with($elId, '-global')) {
                    continue;
                }

                $history = $this->speakingHistory[$elId] ?? [];
                $speakingText = '';
                foreach ($history as $turn) {
                    $speakingText .= "{$turn['role']}: {$turn['text']}\n";
                }

                $maxPoints = (int) ($element['points'] ?? 6);
                $score = 0;
                $feedback = '';

                if (! empty($history)) {
                    $level = $this->selectedLevel;
                    $provider = $this->selectedProvider;
                    $model = Gemini::generativeModel(model: 'gemini-2.5-flash')
                        ->withSystemInstruction(
                            Content::parse(
                                "Du bist ein offizieller {$provider} {$level} Prüfer für die mündliche Prüfung. "
                                ."Bewerte das vorliegende Sprechprotokoll. Gib eine exakte Punktzahl von 0 bis {$maxPoints} Punkten zurück."
                            )
                        );
                    $resp = $model->generateContent(
                        "Sprechprotokoll:\n{$speakingText}\n\n"
                        ."Bitte antworte genau im Format 'SCORE: X/{$maxPoints}', gefolgt von einer kurzen Begründung."
                    );
                    $feedback = $resp->text();
                    preg_match('/SCORE:\s*(\d+)/i', $feedback, $matches);
                    if (isset($matches[1])) {
                        $score = (int) $matches[1];
                    }
                } else {
                    $feedback = 'Keine Beiträge aufgezeichnet.';
                }

                $details[] = [
                    'id' => $elId,
                    'label' => 'Mündliche Prüfung — '.$element['label'],
                    'given' => $history,
                    'expected' => 'Gemini AI Graded',
                    'points' => $score,
                    'max_points' => $maxPoints,
                    'correct' => $score >= (int) ceil($maxPoints * 0.6),
                    'explanation' => $feedback,
                ];
                $autoScored[$elId] = $score;
            }

            // Evaluate Global points
            $spGlobalEl = collect($this->speakingElements)->first(fn ($el) => str_ends_with((string) ($el['id'] ?? ''), '-GLOBAL') || str_ends_with((string) ($el['id'] ?? ''), '-global'));
            $maxGlobal = $spGlobalEl ? (int) ($spGlobalEl['points'] ?? 30) : 32;

            $fullHistoryText = '';
            foreach ($this->speakingElements as $element) {
                $elId = $element['id'];
                if (str_ends_with($elId, '-GLOBAL') || str_ends_with($elId, '-global')) {
                    continue;
                }
                $fullHistoryText .= "--- {$element['label']} ---\n";
                foreach ($this->speakingHistory[$elId] ?? [] as $turn) {
                    $fullHistoryText .= "{$turn['role']}: {$turn['text']}\n";
                }
            }

            $globalScore = 0;
            $globalFeedback = '';
            try {
                $level = $this->selectedLevel;
                $provider = $this->selectedProvider;
                $model = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->withSystemInstruction(
                        Content::parse(
                            "Du bist ein {$provider} {$level} Mündlicher Prüfer. "
                            .'Bewerte das gesamte Sprechprotokoll der Prüfung hinsichtlich globaler Kriterien wie Flüssigkeit, Wortschatz, Grammatik und Aussprache. '
                            ."Gib eine Gesamtpunktzahl von 0 bis {$maxGlobal} Punkten im Format 'SCORE: X/{$maxGlobal}' zurück, gefolgt von einer detaillierten Analyse."
                        )
                    );
                $resp = $model->generateContent($fullHistoryText);
                $globalFeedback = $resp->text();
                preg_match('/SCORE:\s*(\d+)/i', $globalFeedback, $matches);
                if (isset($matches[1])) {
                    $globalScore = (int) $matches[1];
                }
            } catch (\Throwable $e) {
                $globalScore = (int) ($maxGlobal * 0.6);
                $globalFeedback = 'Automatisch berechnet: '.$e->getMessage();
            }

            $details[] = [
                'id' => $spGlobalEl ? $spGlobalEl['id'] : 'SP-GLOBAL',
                'label' => 'Mündliche Prüfung — '.($spGlobalEl ? $spGlobalEl['label'] : 'Globale Bewertung'),
                'given' => [],
                'expected' => 'Gemini AI Graded',
                'points' => $globalScore,
                'max_points' => $maxGlobal,
                'correct' => $globalScore >= (int) ceil($maxGlobal * 0.6),
                'explanation' => $globalFeedback,
            ];

            // Total score
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

            // Create ExamAttempt
            ExamAttempt::create([
                'exam_template_id' => $this->activeTemplateId,
                'user_id' => auth()->id(),
                'answers' => $this->answers,
                'result' => $this->result,
                'started_at' => now(),
                'submitted_at' => now(),
            ]);

            $this->examFinished = true;
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
        $this->result = null;
        $this->activeElementIndex = 0;
        $this->speakingHistory = [];
        $this->speakingFeedback = [];
        $this->answers = [];
        $this->selectedTopic = '';
        $this->unchosenTopic = '';
        $this->aiPresentationText = '';
        $this->transcribedText = '';
    }

    public function render(): View
    {
        return view('livewire.voice-input', [
            'filteredTopics' => $this->filteredTopics,
        ]);
    }
}
