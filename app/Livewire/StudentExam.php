<?php

namespace App\Livewire;

use App\Models\ExamAttempt;
use App\Models\ExamEnrollment;
use App\Models\ExamTemplate;
use App\Models\Subscription;
use App\Services\AnswerExplainer;
use App\Services\ExamGrader;
use App\Services\TelcTemplateFactory;
use Gemini\Data\Content;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('TELC Student Exam')]
class StudentExam extends Component
{
    public ?int $selectedTemplateId = null;

    /** @var array<string, mixed> */
    public array $template = [];

    /** @var array<string, mixed> */
    public array $answers = [];

    /** @var array<string, mixed>|null */
    public ?array $result = null;

    public int $activeSection = 0;

    public int $remainingSeconds = 0;

    public bool $started = false;

    /** @var array<string, string> */
    public array $speakingFeedback = [];

    /** @var array<string, bool> */
    public array $speakingProcessing = [];

    /** @var array<string, array<int, array<string, string>>> */
    public array $speakingHistory = [];

    public string $selectedTopic = '';

    public string $aiPresentationText = '';

    public int $speakingStep = 1;

    public string $unchosenTopic = '';

    public bool $showEnrollModal = false;

    public ?int $enrollTemplateId = null;

    public string $enrollModalError = '';

    public function startEnroll(int $id): void
    {
        $this->enrollTemplateId = $id;
        $this->showEnrollModal = true;
        $this->enrollModalError = '';
    }

    public function cancelEnroll(): void
    {
        $this->showEnrollModal = false;
        $this->enrollTemplateId = null;
        $this->enrollModalError = '';
    }

    public function selectPayment(string $method): void
    {
        if ($method === 'revolut') {
            $this->dispatch('open-revolut-modal', templateId: $this->enrollTemplateId);

            return;
        }

        if ($method === 'paypal') {
            if ($this->enrollTemplateId) {
                $this->redirect(route('paypal.exam.checkout', $this->enrollTemplateId));
            }
        }
    }

    public function mount(): void
    {
        $this->ensurePresets();
    }

    public function hasActiveSubscription(): bool
    {
        if (! Auth::check()) {
            return false;
        }

        return Subscription::query()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->exists();
    }

    public function render(): View
    {
        return view('livewire.student-exam', [
            'templates' => ExamTemplate::query()
                ->where('is_published', true)
                ->orderBy('level')
                ->orderBy('title')
                ->get(),
            'enrollments' => ExamEnrollment::query()
                ->where('user_id', Auth::id())
                ->get()
                ->keyBy('exam_template_id'),
            'attempts' => ExamAttempt::query()
                ->where('user_id', Auth::id())
                ->latest()
                ->limit(5)
                ->get(),
            'isSubscribed' => $this->hasActiveSubscription(),
        ]);
    }

    public function loadTemplate(int $id): void
    {
        $record = ExamTemplate::query()
            ->where('is_published', true)
            ->findOrFail($id);

        abort_unless($this->isEnrolled($record->id), 403);

        $this->selectedTemplateId = $record->id;
        $this->template = $record->only(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections', 'is_free']);
        $this->answers = [];
        $this->result = null;
        $this->speakingFeedback = [];
        $this->speakingProcessing = [];
        $this->speakingHistory = [];
        $this->selectedTopic = '';
        $this->aiPresentationText = '';
        $this->speakingStep = 1;
        $this->unchosenTopic = '';
        $this->activeSection = 0;
        $this->resetTimer();
    }

    public function enroll(int $id): void
    {
        $record = ExamTemplate::query()
            ->where('is_published', true)
            ->findOrFail($id);

        ExamEnrollment::updateOrCreate(
            ['user_id' => Auth::id(), 'exam_template_id' => $record->id],
            [
                'status' => 'pending',
                'payment_status' => 'pending',
                'active_answers' => null,
                'remaining_seconds' => null,
                'active_section' => 0,
                'enrolled_at' => now(),
            ],
        );
    }

    public function startOrResumeExam(int $templateId): void
    {
        if ($this->hasActiveSubscription()) {
            ExamEnrollment::updateOrCreate(
                ['user_id' => Auth::id(), 'exam_template_id' => $templateId],
                [
                    'status' => 'active',
                    'payment_status' => 'subscribed',
                    'enrolled_at' => now(),
                ]
            );
        }

        $enrollment = ExamEnrollment::query()
            ->where('user_id', Auth::id())
            ->where('exam_template_id', $templateId)
            ->where('status', 'active')
            ->firstOrFail();

        $this->selectedTemplateId = $templateId;
        $record = $enrollment->examTemplate;
        $this->template = $record->only(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections', 'is_free']);

        if ($enrollment->active_answers !== null) {
            // Resume previous session
            $stored = $enrollment->active_answers;
            $this->answers = array_filter((array) $stored, fn ($key) => ! str_starts_with((string) $key, '_'), ARRAY_FILTER_USE_KEY);
            $this->selectedTopic = $stored['_selectedTopic'] ?? '';
            $this->aiPresentationText = $stored['_aiPresentationText'] ?? '';
            $this->speakingStep = (int) ($stored['_speakingStep'] ?? 1);
            $this->unchosenTopic = $stored['_unchosenTopic'] ?? '';
            $this->activeSection = min($enrollment->active_section, ! empty($this->template['is_free']) ? 2 : 999);
            $this->remainingSeconds = $enrollment->remaining_seconds ?? 0;
            $this->started = $this->remainingSeconds > 0;

            // Restore speaking history from answers
            $this->speakingHistory = [];
            foreach ($record->sections ?? [] as $sec) {
                foreach ($sec['elements'] ?? [] as $el) {
                    if (($el['type'] ?? '') === 'speaking' && array_key_exists($el['id'], $this->answers)) {
                        $history = $this->answers[$el['id']];
                        $this->speakingHistory[$el['id']] = is_string($history) ? [['role' => 'user', 'text' => $history]] : $history;
                    }
                }
            }
        } else {
            // Start fresh
            $this->answers = [];
            $this->selectedTopic = '';
            $this->aiPresentationText = '';
            $this->speakingStep = 1;
            $this->unchosenTopic = '';
            $this->activeSection = 0;
            if (($record->level ?? '') === 'C1') {
                // C1 is a unified single page, timer is sum of all section minutes
                $totalMinutes = collect($record->sections)->sum('minutes');
                $this->remainingSeconds = $totalMinutes * 60;
            } else {
                $this->remainingSeconds = max((int) ($record->sections[0]['minutes'] ?? 1), 1) * 60;
            }
            $this->started = true;

            $this->saveProgress();
        }
    }

    public function viewAttempt(int $attemptId): void
    {
        $attempt = ExamAttempt::query()
            ->where('user_id', Auth::id())
            ->findOrFail($attemptId);

        $this->selectedTemplateId = $attempt->exam_template_id;
        $record = $attempt->examTemplate;
        $this->template = $record->only(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections', 'is_free']);
        $stored = $attempt->answers;
        $this->answers = array_filter((array) $stored, fn ($key) => ! str_starts_with((string) $key, '_'), ARRAY_FILTER_USE_KEY);
        $this->selectedTopic = $stored['_selectedTopic'] ?? '';
        $this->aiPresentationText = $stored['_aiPresentationText'] ?? '';
        $this->speakingStep = (int) ($stored['_speakingStep'] ?? 3);
        $this->unchosenTopic = $stored['_unchosenTopic'] ?? '';
        $this->result = $attempt->result;
        $this->started = false;

        // Restore speaking history from answers if applicable
        $this->speakingHistory = [];
        foreach ($record->sections ?? [] as $sec) {
            foreach ($sec['elements'] ?? [] as $el) {
                if (($el['type'] ?? '') === 'speaking' && isset($this->answers[$el['id']])) {
                    $history = $this->answers[$el['id']];
                    $this->speakingHistory[$el['id']] = is_string($history) ? [['role' => 'user', 'text' => $history]] : $history;
                }
            }
        }
    }

    public function exitExam(): void
    {
        $this->selectedTemplateId = null;
        $this->template = [];
        $this->answers = [];
        $this->result = null;
        $this->started = false;
    }

    public function startSection(int $index): void
    {
        if (! isset($this->template['sections'][$index])) {
            return;
        }

        // Free exams are limited to 3 sections (indices 0, 1, 2)
        if (! empty($this->template['is_free']) && $index > 2) {
            return;
        }

        $this->activeSection = $index;
        $this->started = true;
        $this->remainingSeconds = max((int) ($this->template['sections'][$index]['minutes'] ?? 1), 1) * 60;

        $this->saveProgress();
    }

    public function tick(): void
    {
        if (! $this->started || $this->remainingSeconds <= 0) {
            return;
        }

        $this->remainingSeconds--;

        // Auto-save progress every 5 seconds to minimize DB writes
        if ($this->remainingSeconds % 5 === 0) {
            $this->saveProgress();
        }

        if ($this->remainingSeconds === 0) {
            $this->submit();
        }
    }

    public function saveProgress(): void
    {
        if ($this->selectedTemplateId) {
            $storage = $this->answers;
            $storage['_selectedTopic'] = $this->selectedTopic;
            $storage['_aiPresentationText'] = $this->aiPresentationText;
            $storage['_speakingStep'] = $this->speakingStep;
            $storage['_unchosenTopic'] = $this->unchosenTopic;

            ExamEnrollment::query()
                ->where('user_id', Auth::id())
                ->where('exam_template_id', $this->selectedTemplateId)
                ->update([
                    'active_answers' => $storage,
                    'remaining_seconds' => $this->remainingSeconds,
                    'active_section' => $this->activeSection,
                ]);
        }
    }

    public function updatedAnswers(): void
    {
        $this->saveProgress();
    }

    public function submit(): void
    {
        if (! $this->selectedTemplateId || ! $this->isEnrolled($this->selectedTemplateId)) {
            return;
        }

        $this->started = false;

        // Filter sections: for inline_gap sections, only grade inline_gap elements
        // (standard answer elements are not visible in the inline_gap view)
        $sections = $this->template['sections'] ?? [];
        $sectionsToGrade = array_map(function ($section) {
            /** @var array<int, array<string, mixed>> $elements */
            $elements = $section['elements'] ?? [];
            $hasInlineGap = collect($elements)
                ->contains(fn ($el) => ($el['type'] ?? '') === 'inline_gap');

            if ($hasInlineGap) {
                $section['elements'] = collect($elements)
                    ->filter(fn ($el) => ($el['type'] ?? '') === 'inline_gap')
                    ->values()
                    ->all();
            }

            return $section;
        }, $sections);

        $this->result = app(ExamGrader::class)->grade($sectionsToGrade, $this->answers);

        $this->enrichExplanations();

        $writingDetails = $this->gradeWriting();
        $speakingDetails = $this->gradeSpeaking();

        $this->result['details'] = array_merge($this->result['details'], $writingDetails, $speakingDetails);

        $this->result = $this->calculateFinalResult($this->result);

        ExamAttempt::create([
            'exam_template_id' => $this->selectedTemplateId,
            'user_id' => Auth::id(),
            'answers' => $this->answers,
            'result' => $this->result,
            'started_at' => now(),
            'submitted_at' => now(),
        ]);

        // Clean active session on successful submission
        ExamEnrollment::query()
            ->where('user_id', Auth::id())
            ->where('exam_template_id', $this->selectedTemplateId)
            ->update([
                'active_answers' => null,
                'remaining_seconds' => null,
                'active_section' => 0,
            ]);
    }

    private function enrichExplanations(): void
    {
        $explainer = app(AnswerExplainer::class);

        foreach ($this->result['details'] ?? [] as &$detail) {
            if ($detail['correct']) {
                continue;
            }

            $element = collect((array) ($this->template['sections'] ?? []))
                ->pluck('elements')
                ->flatten(1)
                ->firstWhere('id', $detail['id']);

            if (! $element) {
                continue;
            }

            $detail['explanation'] = $explainer->explainElement(
                $element,
                $detail['given'] ?? null,
            );
        }
    }

    public function resetTimer(): void
    {
        $this->started = false;
        $this->remainingSeconds = max((int) ($this->template['sections'][$this->activeSection]['minutes'] ?? 1), 1) * 60;
    }

    public function chooseTopic(string $topic, string $elementId = ''): void
    {
        $this->selectedTopic = $topic;
        $this->speakingStep = 1;
        $speakingIds = collect((array) ($this->template['sections'] ?? []))
            ->pluck('elements')
            ->flatten(1)
            ->where('type', 'speaking')
            ->pluck('id');

        if ($elementId === '') {
            $elementId = $speakingIds->first(fn ($id) => str_ends_with((string) $id, '-SP-1a')) ?? 'SP-1a';
        }

        foreach ($speakingIds as $sid) {
            $this->speakingHistory[(string) $sid] = [];
        }

        $this->speakingHistory[$elementId] = [];
        $this->answers[$elementId] = '';
    }

    public function loadAiPresentation(): void
    {
        $this->generateAiPresentation($this->unchosenTopic);
    }

    public function generateAiPresentation(string $unchosenTopic, string $sp1bId = ''): void
    {
        if ($sp1bId === '') {
            $elements = collect((array) ($this->template['sections'] ?? []))
                ->pluck('elements')
                ->flatten(1);
            $sp1bEl = $elements->firstWhere('id', 'C1-SP-1b')
                ?? $elements->first(fn ($el) => str_ends_with((string) ($el['id'] ?? ''), '-SP-1b'));
            $sp1bId = $sp1bEl['id'] ?? 'SP-1b';
        }

        try {
            $this->speakingProcessing[$sp1bId] = true;

            $prompt = "Schreibe einen gut strukturierten Vortrag (Einleitung, Hauptteil, Eigene Meinung, Schluss/Fazit) zum Thema: \"{$unchosenTopic}\". Der Vortrag soll klar und verständlich sein, mit einer deutlichen Argumentationslinie.";

            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(Content::parse('Du bist ein TELC-Prüfungskandidat, der einen Vortrag hält. Sprich den Lernenden direkt an.'))
                ->generateContent($prompt);

            $this->aiPresentationText = $result->text();

            $this->speakingHistory[$sp1bId][] = [
                'role' => 'assistant',
                'text' => $this->aiPresentationText,
            ];

            $this->answers[$sp1bId] = $this->speakingHistory[$sp1bId];
            $this->speakingStep = 2;
            $this->saveProgress();
        } catch (\Throwable $e) {
            $this->aiPresentationText = 'Fehler bei der Vortragserstellung: '.$e->getMessage();
        } finally {
            $this->speakingProcessing[$sp1bId] = false;
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

        $element = collect((array) ($this->template['sections'] ?? []))
            ->pluck('elements')
            ->flatten(1)
            ->firstWhere('id', $elementId);

        $prompt = $element['content'] ?? '';
        $maxPoints = (int) ($element['points'] ?? 24);
        $level = $this->template['level'] ?? 'C1';

        if (str_ends_with($elementId, '-SP-1a')) {
            $criteriaPrompt = "Bewerte den Vortrag des Lernenden zu dem von ihm gewählten Thema.
Kriterien:
- Inhaltliche Angemessenheit & Relevanz (0-2 Punkte)
- Struktur und logischer Aufbau (0-2 Punkte)
- Ausdrucksweise und Kohärenz (0-2 Punkte)

Bitte gib eine kurze Begründung für jedes Kriterium an.
Gib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        } elseif (str_ends_with($elementId, '-SP-1b')) {
            $criteriaPrompt = "Bewerte die Reaktion des Lernenden auf den Vortrag.
Kriterien:
- Verständnis des gehörten Vortrags (0-2 Punkte)
- Relevanz und Qualität der Anschlussfragen/Kommentare (0-2 Punkte)

Bitte gib eine kurze Begründung für jedes Kriterium an.
Gib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        } elseif (str_ends_with($elementId, '-SP-2')) {
            $criteriaPrompt = "Bewerte die Diskussionsleistung des Lernenden.
Kriterien:
- Argumentation und Eingehen auf den Gesprächspartner (0-2 Punkte)
- Interaktionsfähigkeit (0-2 Punkte)
- Syntaktische und inhaltliche Angemessenheit (0-2 Punkte)

Bitte gib eine kurze Begründung für jedes Kriterium an.
Gib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        } else {
            $criteriaPrompt = "Bewerte die mündliche Leistung des Lernenden.
Bitte gib eine Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        }

        // SP-1a: student's initial presentation on chosen topic
        if (str_ends_with($elementId, '-SP-1a')) {
            try {
                $levelLabel = "TELC-{$level}";
                $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->withSystemInstruction(
                        Content::parse(
                            "Du bist ein offizieller {$levelLabel}-Prüfer. "
                            .'Bewerte den mündlichen Vortrag des Lernenden zu dem von ihm gewählten Thema. '
                            .$criteriaPrompt
                        )
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

                // Extract the unchosen topic from the element content (lazy — generateAiPresentation will be called separately)
                $content = $element['content'] ?? '';
                $topics = self::parseTopicsFromContent($content);
                $this->unchosenTopic = '';
                if (count($topics) === 2) {
                    $this->unchosenTopic = $topics[0] === $this->selectedTopic ? $topics[1] : $topics[0];
                }
                if ($this->unchosenTopic === '') {
                    $this->unchosenTopic = 'Das alternative Thema';
                }

                $this->speakingStep = 2;
                $this->saveProgress();
            } catch (\Throwable $e) {
                $this->speakingFeedback[$elementId] = 'Fehler: '.$e->getMessage();
            } finally {
                $this->speakingProcessing[$elementId] = false;
            }

            return;
        }

        // SP-1b: student's reaction/summary of the AI presentation
        if (str_ends_with($elementId, '-SP-1b')) {
            try {
                $levelLabel = "TELC-{$level}";
                $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->withSystemInstruction(
                        Content::parse(
                            "Du bist ein offizieller {$levelLabel}-Prüfer. "
                            .'Der Lernende reagiert auf deine Zusammenfassung / deinen Vortrag. '
                            .'Bewerte seine Reaktion darauf. Achte auf logischen Bezug, Sprachgefühl und syntaktische Korrektheit. '
                            .$criteriaPrompt
                        )
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
                $this->speakingStep = 3;
                $this->saveProgress();
            } catch (\Throwable $e) {
                $this->speakingFeedback[$elementId] = 'Fehler: '.$e->getMessage();
            } finally {
                $this->speakingProcessing[$elementId] = false;
            }

            return;
        }

        // SP-2: interactive discussion handler
        $historyContext = '';
        foreach ($this->speakingHistory[$elementId] as $turn) {
            $roleLabel = $turn['role'] === 'user' ? 'Lernender' : 'Prüfer';
            $historyContext .= "{$roleLabel}: {$turn['text']}\n";
        }

        try {
            $levelLabel = "TELC-{$level}";
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(
                    Content::parse(
                        "Du bist ein {$levelLabel}-Prüfer für die mündliche Prüfung. "
                        .'Unterhalte dich interaktiv mit dem Lernenden über das gewählte Thema. '
                        .'Lies den Gesprächsverlauf. Reagiere auf die letzte Antwort des Lernenden, '
                        .'stelle eine kurze, logische Anschlussfrage oder führe das Gespräch fort. '
                        .'WICHTIG: Wenn dies der 3. oder 4. Redebeitrag des Lernenden ist, '
                        .'beende das Gespräch höflich und bewerte die Leistung. '
                        .$criteriaPrompt
                    )
                )
                ->generateContent(
                    "Aufgabenstellung/Thema: {$prompt}\n\n"
                    ."Bisheriger Gesprächsverlauf:\n{$historyContext}\n"
                    .'Generiere die nächste Antwort des Prüfers:'
                );

            $assistantReply = $result->text();

            $this->speakingHistory[$elementId][] = [
                'role' => 'assistant',
                'text' => $assistantReply,
            ];

            $this->speakingFeedback[$elementId] = $assistantReply;
            $this->answers[$elementId] = $transcribedText;
            $this->saveProgress();
        } catch (\Throwable $e) {
            $this->speakingFeedback[$elementId] = 'Fehler bei der Analyse: '.$e->getMessage();
        } finally {
            $this->speakingProcessing[$elementId] = false;
        }
    }

    /** @return array<int, string> */
    private static function parseTopicsFromContent(string $content): array
    {
        $topics = [];
        if (preg_match_all('/(\d+)\.\s*(.+?)\s*(?=\s*(?:\/|oder)\s*\d+\.|\s*$)/u', $content, $matches)) {
            foreach ($matches[2] as $m) {
                $trimmed = trim($m);
                if ($trimmed !== '') {
                    $topics[] = $trimmed;
                }
            }
        }
        if (count($topics) < 2) {
            $topics = [];
            if (preg_match_all('/Thema\s*\d+\s*[:.]?\s*(.+?)\s*(?=\s*\/\s*Thema|\s*$)/ui', $content, $matches)) {
                foreach ($matches[1] as $m) {
                    $trimmed = trim($m);
                    if ($trimmed !== '') {
                        $topics[] = $trimmed;
                    }
                }
            }
        }

        return $topics;
    }

    /**
     * @param  array<string, mixed>  $element
     * @return array<int, string>
     */
    public function optionsFor(array $element): array
    {
        $options = array_filter(array_map('trim', explode(',', (string) ($element['options'] ?? ''))));

        if ($options !== []) {
            return array_values($options);
        }

        return match ($element['type'] ?? '') {
            'true_false' => ['+', '-'],
            'multiple_choice' => ['a', 'b', 'c'],
            'matching' => range('a', 'j'),
            default => [],
        };
    }

    /**
     * @param  array<string, mixed>  $element
     * @return array<int, array<string, mixed>>
     */
    public function gapsFor(array $element): array
    {
        if (($element['type'] ?? '') !== 'inline_gap') {
            return [];
        }

        $raw = json_decode((string) ($element['options'] ?? '[]'), true);

        return is_array($raw) ? array_values($raw) : [];
    }

    public function setGapAnswer(string $elementId, int $gapIndex, string $value): void
    {
        if (! isset($this->answers[$elementId]) || ! is_array($this->answers[$elementId])) {
            $this->answers[$elementId] = [];
        }

        $this->answers[$elementId][(string) $gapIndex] = $value;

        $this->saveProgress();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function gradeWriting(): array
    {
        $details = [];
        $elements = collect((array) ($this->template['sections'] ?? []))
            ->pluck('elements')
            ->flatten(1)
            ->where('type', 'writing');

        foreach ($elements as $element) {
            $elId = $element['id'];
            $maxPoints = (int) ($element['points'] ?? 48);
            $passMark = (int) ceil($maxPoints * 0.6);
            $content = (string) ($this->answers[$elId] ?? '');
            $feedback = 'Keine Abgabe für den schriftlichen Ausdruck.';
            $score = 0;

            if (! empty(trim($content))) {
                try {
                    $model = Gemini::generativeModel(model: 'gemini-2.5-flash')
                        ->withSystemInstruction(
                            Content::parse(
                                'Du bist ein offizieller TELC-C1-Prüfer für die schriftliche Prüfung. '
                                .'Bewerte den gegebenen Aufsatz. Gib eine exakte Punktzahl von 0 bis '.$maxPoints.' Punkten zurück.'
                            )
                        );
                    $resp = $model->generateContent(
                        "Aufsatz:\n{$content}\n\n"
                        ."Bitte antworte genau im Format 'SCORE: X/{$maxPoints}', gefolgt von einer kurzen Begründung."
                    );
                    $feedback = $resp->text();
                    preg_match('/SCORE:\s*(\d+)/i', $feedback, $matches);
                    if (isset($matches[1])) {
                        $score = (int) $matches[1];
                    }
                } catch (\Throwable $e) {
                    $score = (int) ($maxPoints / 2);
                    $feedback = 'Fehler bei der automatischen Bewertung: '.$e->getMessage();
                }
            }

            $details[] = [
                'id' => $elId,
                'label' => 'Schriftlicher Ausdruck',
                'given' => $content,
                'expected' => 'Gemini AI Graded',
                'points' => $score,
                'max_points' => $maxPoints,
                'correct' => $score >= $passMark,
                'explanation' => $feedback,
            ];
        }

        return $details;
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function gradeSpeaking(): array
    {
        $details = [];
        $elements = collect((array) ($this->template['sections'] ?? []))
            ->pluck('elements')
            ->flatten(1)
            ->where('type', 'speaking');

        // Track auto-calculated scores for SP-GLOBAL
        $autoScored = [];

        foreach ($elements as $element) {
            $elId = $element['id'];
            $maxPoints = (int) ($element['points'] ?? 24);

            // SP-GLOBAL: auto-sum from SP-1a, SP-1b, SP-2
            if (str_ends_with($elId, '-SP-GLOBAL') || str_ends_with($elId, '-SP-global')) {
                continue; // handled after the loop
            }

            $history = $this->speakingHistory[$elId] ?? [];

            if (empty($history)) {
                $details[] = [
                    'id' => $elId,
                    'label' => 'Mündliche Prüfung — '.$element['label'],
                    'given' => [],
                    'expected' => 'Gemini AI Graded',
                    'points' => 0,
                    'max_points' => $maxPoints,
                    'correct' => false,
                    'explanation' => 'Keine Beiträge aufgezeichnet.',
                ];
                $autoScored[$elId] = 0;

                continue;
            }

            $speakingText = '';
            foreach ($history as $turn) {
                $speakingText .= "{$turn['role']}: {$turn['text']}\n";
            }

            $score = 0;
            $feedback = 'Keine Bewertung verfügbar.';

            $foundScore = false;
            foreach ($history as $turn) {
                if ($turn['role'] === 'assistant') {
                    // Try SCORE: format first (from gradeSpeaking fallback), then GESAMT: (from analyzeSpeaking)
                    if (preg_match('/SCORE:\s*(\d+)/i', $turn['text'], $matches)) {
                        $score = (int) $matches[1];
                        $feedback = $turn['text'];
                        $foundScore = true;
                    } elseif (preg_match('/GESAMT:\s*(\d+)/i', $turn['text'], $matches)) {
                        $score = (int) $matches[1];
                        $feedback = $turn['text'];
                        $foundScore = true;
                    }
                }
            }

            if (! $foundScore) {
                try {
                    $model = Gemini::generativeModel(model: 'gemini-2.5-flash')
                        ->withSystemInstruction(
                            Content::parse(
                                'Du bist ein offizieller TELC-Prüfer für die mündliche Prüfung. '
                                .'Bewerte das vorliegende Gesprächsprotokoll. Gib eine exakte Punktzahl von 0 bis '.$maxPoints.' Punkten zurück.'
                            )
                        );
                    $resp = $model->generateContent(
                        "Gesprächsprotokoll:\n{$speakingText}\n\n"
                        ."Bitte antworte genau im Format 'SCORE: X/{$maxPoints}', gefolgt von einer kurzen Begründung."
                    );
                    $feedback = $resp->text();
                    preg_match('/SCORE:\s*(\d+)/i', $feedback, $matches);
                    if (isset($matches[1])) {
                        $score = (int) $matches[1];
                    }
                } catch (\Throwable $e) {
                    $score = (int) ($maxPoints / 2);
                    $feedback = 'Fehler bei der automatischen Bewertung: '.$e->getMessage();
                }
            }

            $details[] = [
                'id' => $elId,
                'label' => 'Mündliche Prüfung — '.$element['label'],
                'given' => $history,
                'expected' => 'Gemini AI Graded',
                'points' => $score,
                'max_points' => $maxPoints,
                'correct' => $score >= (int) ($maxPoints * 0.6),
                'explanation' => $feedback,
            ];
            $autoScored[$elId] = $score;
        }

        // Auto-calculate SP-GLOBAL via Gemini based on all speaking parts
        $globalElement = $elements->first(fn ($el) => str_ends_with((string) ($el['id'] ?? ''), '-SP-GLOBAL'));
        if ($globalElement) {
            $globalId = $globalElement['id'];
            $globalMax = (int) ($globalElement['points'] ?? 32);
            $sp1a = $autoScored[collect($autoScored)->keys()->first(fn ($k) => str_ends_with($k, '-SP-1a'))] ?? 0;
            $sp1b = $autoScored[collect($autoScored)->keys()->first(fn ($k) => str_ends_with($k, '-SP-1b'))] ?? 0;
            $sp2 = $autoScored[collect($autoScored)->keys()->first(fn ($k) => str_ends_with($k, '-SP-2'))] ?? 0;

            $combinedTranscript = '';
            foreach ($elements as $el) {
                $id = $el['id'];
                if (str_ends_with($id, '-SP-GLOBAL') || str_ends_with($id, '-SP-global')) {
                    continue;
                }
                $history = $this->speakingHistory[$id] ?? [];
                if (! empty($history)) {
                    $combinedTranscript .= "\n--- Teil: ".$el['label']." ---\n";
                    if (is_array($history)) {
                        foreach ($history as $turn) {
                            $roleLabel = $turn['role'] === 'user' ? 'Lernender' : 'KI-Prüfer';
                            $combinedTranscript .= "{$roleLabel}: {$turn['text']}\n";
                        }
                    } else {
                        $combinedTranscript .= 'Lernender: '.$history."\n";
                    }
                }
            }

            $globalScore = 0;
            $feedback = '';

            if (! empty(trim($combinedTranscript))) {
                try {
                    $model = Gemini::generativeModel(model: 'gemini-2.5-flash')
                        ->withSystemInstruction(
                            Content::parse(
                                'Du bist ein offizieller TELC-Prüfer für die mündliche Prüfung. '
                                .'Bewerte die gesamte mündliche Leistung des Lernenden über alle Prüfungsteile hinweg anhand der sprachlichen Angemessenheit.'
                            )
                        );

                    $resp = $model->generateContent(
                        "Gesamtes Gesprächsprotokoll:\n{$combinedTranscript}\n\n"
                        ."Bitte bewerten Sie die Leistung nach folgenden vier Kriterien:\n"
                        ."1. Repertoire & Wortschatz (0-8 Punkte)\n"
                        ."2. Grammatische Korrektheit (0-8 Punkte)\n"
                        ."3. Aussprache und Intonation (0-8 Punkte)\n"
                        ."4. Flüssigkeit (0-8 Punkte)\n\n"
                        ."Antworte genau im Format 'SCORE: X/{$globalMax}', gefolgt von einer Begründung für die Kriterien."
                    );

                    $feedback = $resp->text();
                    preg_match('/SCORE:\s*(\d+)/i', $feedback, $matches);
                    if (isset($matches[1])) {
                        $globalScore = min((int) $matches[1], $globalMax);
                    } else {
                        $globalScore = min($sp1a + $sp1b + $sp2, $globalMax);
                    }
                } catch (\Throwable $e) {
                    $globalScore = min($sp1a + $sp1b + $sp2, $globalMax);
                    $feedback = 'Fehler bei der automatischen Bewertung der sprachlichen Angemessenheit: '.$e->getMessage();
                }
            } else {
                $feedback = 'Keine Beiträge für die mündliche Prüfung aufgezeichnet.';
            }

            $details[] = [
                'id' => $globalId,
                'label' => 'Mündliche Prüfung — '.$globalElement['label'],
                'given' => $autoScored,
                'expected' => 'Gemini AI Graded',
                'points' => $globalScore,
                'max_points' => $globalMax,
                'correct' => $globalScore >= (int) ($globalMax * 0.6),
                'explanation' => $feedback,
            ];
        }

        return $details;
    }

    /**
     * @param  array<string, mixed>  $result
     * @return array<string, mixed>
     */
    private function calculateFinalResult(array $result): array
    {
        $writtenEarned = 0;
        $oralEarned = 0;
        $possible = 0;

        foreach ($result['details'] as $detail) {
            $possible += $detail['max_points'];
            if (str_contains($detail['id'], '-SP-')) {
                $oralEarned += $detail['points'];
            } else {
                $writtenEarned += $detail['points'];
            }
        }

        $earned = $writtenEarned + $oralEarned;

        /** @var array<int, array<string, mixed>> $details */
        $details = $result['details'];
        $oralMax = collect($details)->filter(fn ($d) => str_contains((string) ($d['id'] ?? ''), '-SP-'))->sum('max_points');
        $writtenMax = $possible - $oralMax;

        $passedWritten = $writtenMax > 0 ? ($writtenEarned / $writtenMax) >= 0.6 : true;
        $passedOral = $oralMax > 0 ? ($oralEarned / $oralMax) >= 0.6 : true;
        $passed = $possible > 0
            ? ($earned / $possible) >= 0.6 && $passedWritten && $passedOral
            : false;

        $grade = 'Nicht bestanden';
        if ($passed) {
            $pct = $earned / $possible;
            if ($pct >= 0.9) {
                $grade = 'Sehr gut';
            } elseif ($pct >= 0.8) {
                $grade = 'Gut';
            } elseif ($pct >= 0.7) {
                $grade = 'Befriedigend';
            } else {
                $grade = 'Ausreichend';
            }
        }

        $result['earned'] = $earned;
        $result['possible'] = $possible;
        $result['percentage'] = $possible > 0 ? round(($earned / $possible) * 100, 2) : 0;
        $result['written_earned'] = $writtenEarned;
        $result['written_max'] = $writtenMax;
        $result['oral_earned'] = $oralEarned;
        $result['oral_max'] = $oralMax;
        $result['passed'] = $passed;
        $result['grade'] = $grade;

        return $result;
    }

    private function isEnrolled(int $templateId): bool
    {
        return ExamEnrollment::query()
            ->where('user_id', Auth::id())
            ->where('exam_template_id', $templateId)
            ->where('status', 'active')
            ->exists();
    }

    private function ensurePresets(): void
    {
        foreach (TelcTemplateFactory::presets() as $preset) {
            ExamTemplate::updateOrCreate(
                ['title' => $preset['title']],
                $preset + ['is_published' => true]
            );
        }
    }
}
