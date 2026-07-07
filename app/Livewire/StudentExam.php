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

    public string $c1SelectedTopic = '';

    public string $c1AiResponseText = '';

    public string $c1AiResponseAudio = '';

    public int $c1SpeakingStep = 1; // 1 = 1A presentation, 2 = 1B AI presentation, 3 = 1B user feedback response

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

    public function render(): \Illuminate\View\View
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
        $this->template = $record->only(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections']);
        $this->answers = [];
        $this->result = null;
        $this->speakingFeedback = [];
        $this->speakingProcessing = [];
        $this->speakingHistory = [];
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
        $this->template = $record->only(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections']);

        if ($enrollment->active_answers !== null) {
            // Resume previous session
            $this->answers = $enrollment->active_answers;
            $this->activeSection = $enrollment->active_section;
            $this->remainingSeconds = $enrollment->remaining_seconds ?? 0;
            $this->started = $this->remainingSeconds > 0;

            // Restore speaking history from answers
            $this->speakingHistory = [];
            foreach ($record->sections ?? [] as $sec) {
                foreach ($sec['elements'] ?? [] as $el) {
                    if (($el['type'] ?? '') === 'speaking' && array_key_exists($el['id'], $this->answers)) {
                        /** @var array<int, array<string, string>> $history */
                        $history = $this->answers[$el['id']];
                        $this->speakingHistory[$el['id']] = $history;
                    }
                }
            }
        } else {
            // Start fresh
            $this->answers = [];
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
        $this->template = $record->only(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections']);
        $this->answers = $attempt->answers;
        $this->result = $attempt->result;
        $this->started = false;

        // Restore speaking history from answers if applicable
        $this->speakingHistory = [];
        foreach ($record->sections ?? [] as $sec) {
            foreach ($sec['elements'] ?? [] as $el) {
                if (($el['type'] ?? '') === 'speaking' && isset($this->answers[$el['id']])) {
                    $this->speakingHistory[$el['id']] = $this->answers[$el['id']];
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
            ExamEnrollment::query()
                ->where('user_id', Auth::id())
                ->where('exam_template_id', $this->selectedTemplateId)
                ->update([
                    'active_answers' => $this->answers,
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
        $this->result = app(ExamGrader::class)->grade($this->template['sections'] ?? [], $this->answers);

        $this->enrichExplanations();

        // Gemini AI Grading for Writing and Speaking
        $writingScore = 0;
        $writingFeedback = 'Keine Abgabe für den schriftlichen Ausdruck.';
        $hasWriting = false;

        // Find writing elements
        $writingElements = collect($this->template['sections'] ?? [])
            ->pluck('elements')
            ->flatten(1)
            ->where('type', 'writing');

        foreach ($writingElements as $element) {
            $hasWriting = true;
            $writingContent = (string) ($this->answers[$element['id']] ?? '');
            if (! empty(trim($writingContent))) {
                try {
                    $writeModel = Gemini::generativeModel(model: 'gemini-2.0-flash')
                        ->withSystemInstruction(
                            Content::parse(
                                'Du bist ein offizieller TELC-C1-Prüfer für die schriftliche Prüfung. '
                                .'Bewerte den gegebenen Aufsatz. Gib eine exakte Punktzahl von 0 bis 48 Punkten zurück.'
                            )
                        );
                    $resp = $writeModel->generateContent(
                        "Aufsatz:\n{$writingContent}\n\n"
                        ."Bitte antworte genau im Format 'SCORE: X/48', gefolgt von einer kurzen Begründung."
                    );
                    $writingFeedback = $resp->text();
                    preg_match('/SCORE:\s*(\d+)/i', $writingFeedback, $matches);
                    if (isset($matches[1])) {
                        $writingScore = (int) $matches[1];
                    }
                } catch (\Throwable $e) {
                    $writingScore = 24;
                    $writingFeedback = 'Fehler bei der automatischen Bewertung: '.$e->getMessage();
                }
            }
        }

        // Grade Speaking (C1-SP-1 / C1-SP-2 / speaking elements)
        $speakingScore = 0;
        $speakingElements = collect($this->template['sections'] ?? [])
            ->pluck('elements')
            ->flatten(1)
            ->where('type', 'speaking');

        $speakingDetailsToAdd = [];

        foreach ($speakingElements as $element) {
            $elId = $element['id'];
            $maxPoints = (int) ($element['points'] ?? 24);
            $history = $this->speakingHistory[$elId] ?? [];

            if (empty($history)) {
                $speakingDetailsToAdd[] = [
                    'id' => $elId,
                    'label' => 'Mündliche Prüfung — '.$element['label'],
                    'given' => [],
                    'expected' => 'Gemini AI Graded',
                    'points' => 0,
                    'max_points' => $maxPoints,
                    'correct' => false,
                    'explanation' => 'Keine Beiträge aufgezeichnet.',
                ];

                continue;
            }

            $speakingText = '';
            foreach ($history as $turn) {
                $speakingText .= "{$turn['role']}: {$turn['text']}\n";
            }

            $elScore = 0;
            $elFeedback = 'Keine Bewertung verfügbar.';

            // If we already parsed the score from interactive helper feedback, retrieve it
            $foundScore = false;
            foreach ($history as $turn) {
                if ($turn['role'] === 'assistant') {
                    preg_match('/SCORE:\s*(\d+)/i', $turn['text'], $matches);
                    if (isset($matches[1])) {
                        $elScore = (int) $matches[1];
                        $elFeedback = $turn['text'];
                        $foundScore = true;
                    }
                }
            }

            if (! $foundScore) {
                try {
                    $speakModel = Gemini::generativeModel(model: 'gemini-2.5-flash')
                        ->withSystemInstruction(
                            Content::parse(
                                'Du bist ein offizieller TELC-C1-Prüfer für die mündliche Prüfung. '
                                .'Bewerte das vorliegende Gesprächsprotokoll. Gib eine exakte Punktzahl von 0 bis '.$maxPoints.' Punkten zurück.'
                            )
                        );
                    $resp = $speakModel->generateContent(
                        "Gesprächsprotokoll:\n{$speakingText}\n\n"
                        ."Bitte antworte genau im Format 'SCORE: X/{$maxPoints}', gefolgt von einer kurzen Begründung."
                    );
                    $elFeedback = $resp->text();
                    preg_match('/SCORE:\s*(\d+)/i', $elFeedback, $matches);
                    if (isset($matches[1])) {
                        $elScore = (int) $matches[1];
                    }
                } catch (\Throwable $e) {
                    $elScore = (int) ($maxPoints / 2);
                    $elFeedback = 'Fehler bei der automatischen Bewertung: '.$e->getMessage();
                }
            }

            $speakingScore += $elScore;

            $speakingDetailsToAdd[] = [
                'id' => $elId,
                'label' => 'Mündliche Prüfung — '.$element['label'],
                'given' => $history,
                'expected' => 'Gemini AI Graded',
                'points' => $elScore,
                'max_points' => $maxPoints,
                'correct' => $elScore >= (int) ($maxPoints * 0.6),
                'explanation' => $elFeedback,
            ];
        }

        // Add Writing Details to result details
        if ($hasWriting) {
            $this->result['details'][] = [
                'id' => 'C1-WRITE-1',
                'label' => 'Schriftlicher Ausdruck',
                'given' => $this->answers['C1-WRITE-1'] ?? '',
                'expected' => 'Gemini AI Graded',
                'points' => $writingScore,
                'max_points' => 48,
                'correct' => $writingScore >= 29,
                'explanation' => $writingFeedback,
            ];
        }

        // Add Speaking Details to result details
        foreach ($speakingDetailsToAdd as $spDetail) {
            $this->result['details'][] = $spDetail;
        }

        // Re-calculate total earned and possible score
        $writtenEarned = 0;
        $oralEarned = 0;
        $possible = 0;

        foreach ($this->result['details'] as $detail) {
            $possible += $detail['max_points'];
            if (str_starts_with($detail['id'], 'C1-SP-')) {
                $oralEarned += $detail['points'];
            } else {
                $writtenEarned += $detail['points'];
            }
        }

        $earned = $writtenEarned + $oralEarned;

        $passedWritten = $writtenEarned >= 99;
        $passedOral = $oralEarned >= 29;
        $passed = $earned >= 128 && $passedWritten && $passedOral;

        $grade = 'Nicht bestanden';
        if ($passed) {
            if ($earned >= 193) {
                $grade = 'Sehr gut';
            } elseif ($earned >= 172) {
                $grade = 'Gut';
            } elseif ($earned >= 151) {
                $grade = 'Befriedigend';
            } else {
                $grade = 'Ausreichend';
            }
        }

        $this->result['earned'] = $earned;
        $this->result['possible'] = $possible;
        $this->result['percentage'] = $possible > 0 ? round(($earned / $possible) * 100, 2) : 0;
        $this->result['written_earned'] = $writtenEarned;
        $this->result['oral_earned'] = $oralEarned;
        $this->result['passed'] = $passed;
        $this->result['grade'] = $grade;

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

            $element = collect($this->template['sections'] ?? [])
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

    public function chooseC1Topic(string $topic): void
    {
        $this->c1SelectedTopic = $topic;
        $this->c1SpeakingStep = 1;
        $this->speakingHistory['C1-SP-1a'] = [];
        $this->speakingHistory['C1-SP-1b'] = [];
    }

    public function generateAiPresentation(string $unchosenTopic): void
    {
        try {
            $this->speakingProcessing['C1-SP-1b'] = true;

            $prompt = "Schreibe einen gut strukturierten Vortrag (Einleitung, Hauptteil, Eigene Meinung/In my view, Schluss/Fazit) zum C1-Thema: \"{$unchosenTopic}\". Der Vortrag soll exakt auf C1-Niveau verfasst sein und eine klare Argumentationslinie haben.";

            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(Content::parse('Du bist ein TELC-Prüfungskandidat, der einen C1-Vortrag hält. Sprich den Lernenden direkt an und halte den Vortrag.'))
                ->generateContent($prompt);

            $this->c1AiResponseText = $result->text();

            // For voice, since the browser needs sound, we will output text and leverage SpeechSynthesis in Javascript to play the audio back using a high-quality speech engine (or native browser audio synthesis).
            // We store the text and set step to 2 so the UI triggers SpeechSynthesis automatically.
            $this->speakingHistory['C1-SP-1b'][] = [
                'role' => 'assistant',
                'text' => $this->c1AiResponseText,
            ];

            $this->answers['C1-SP-1b'] = $this->speakingHistory['C1-SP-1b'];
            $this->c1SpeakingStep = 2;
            $this->saveProgress();
        } catch (\Throwable $e) {
            $this->c1AiResponseText = 'Fehler bei der Vortragserstellung: '.$e->getMessage();
        } finally {
            $this->speakingProcessing['C1-SP-1b'] = false;
        }
    }

    public function analyzeSpeaking(string $elementId, string $transcribedText): void
    {
        $this->speakingProcessing[$elementId] = true;
        $this->speakingFeedback[$elementId] = '';

        if (! isset($this->speakingHistory[$elementId])) {
            $this->speakingHistory[$elementId] = [];
        }

        // Add user statement to history
        $this->speakingHistory[$elementId][] = [
            'role' => 'user',
            'text' => $transcribedText,
        ];

        $element = collect($this->template['sections'] ?? [])
            ->pluck('elements')
            ->flatten(1)
            ->firstWhere('id', $elementId);

        $prompt = $element['content'] ?? '';
        $maxPoints = (int) ($element['points'] ?? 24);

        // If it is C1-SP-1a (Präsentation), we want to grade the user's presentation directly on 6 points criteria.
        if ($elementId === 'C1-SP-1a') {
            try {
                $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->withSystemInstruction(
                        Content::parse(
                            'Du bist ein offizieller TELC-C1-Prüfer. '
                            .'Bewerte den mündlichen Vortrag des Lernenden zu dem von ihm gewählten Thema. '
                            .'Achte besonders darauf, ob der Vortrag gut strukturiert ist, ob er das Thema klar darstellt und ob das Sprachniveau C1 entspricht. '
                            .'WICHTIG: Gib am Ende deiner Antwort exakt das Format: "SCORE: X/6" an (wobei X die Punktzahl von 0 bis 6 ist), gefolgt von einer kurzen Begründung.'
                        )
                    )
                    ->generateContent(
                        "Gewähltes Thema: {$this->c1SelectedTopic}\n\n"
                        ."Transkription des Vortrags:\n{$transcribedText}\n\n"
                        .'Bitte bewerten Sie die Präsentation:'
                    );

                $assistantReply = $result->text();
                $this->speakingHistory[$elementId][] = [
                    'role' => 'assistant',
                    'text' => $assistantReply,
                ];

                $this->speakingFeedback[$elementId] = $assistantReply;
                $this->answers[$elementId] = $this->speakingHistory[$elementId];

                // Switch to next step (AI presentation preparation)
                $unchosenTopic = ($this->c1SelectedTopic === 'Was ist der Nationalsport in einem Land Ihrer Wahl und welche Bedeutung hat er für die Gesellschaft?')
                    ? 'Wer gehört Ihrer Meinung nach zu den einflussreichsten lebenden Personen weltweit? Wodurch zeichnet sich diese Person aus? Erzählen Sie!'
                    : 'Was ist der Nationalsport in einem Land Ihrer Wahl und welche Bedeutung hat er für die Gesellschaft? Berichten Sie!';

                $this->generateAiPresentation($unchosenTopic);
            } catch (\Throwable $e) {
                $this->speakingFeedback[$elementId] = 'Fehler: '.$e->getMessage();
            } finally {
                $this->speakingProcessing[$elementId] = false;
            }

            return;
        }

        // If it is C1-SP-1b (User response to AI summary / presentation), grade it on 4 points criteria.
        if ($elementId === 'C1-SP-1b') {
            try {
                $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                    ->withSystemInstruction(
                        Content::parse(
                            'Du bist ein offizieller TELC-C1-Prüfer. '
                            .'Der Lernende reagiert auf deine Zusammenfassung / deinen Vortrag. '
                            .'Bewerte seine Reaktion darauf. Achte auf logischen Bezug, Sprachgefühl und syntaktische Korrektheit auf C1 Niveau. '
                            .'WICHTIG: Gib am Ende deiner Antwort exakt das Format: "SCORE: X/4" an (wobei X die Punktzahl von 0 bis 4 ist), gefolgt von einer kurzen Begründung.'
                        )
                    )
                    ->generateContent(
                        "AI Vortrag:\n{$this->c1AiResponseText}\n\n"
                        ."Antwort des Lernenden:\n{$transcribedText}\n\n"
                        .'Bitte bewerten Sie die Reaktion:'
                    );

                $assistantReply = $result->text();
                $this->speakingHistory[$elementId][] = [
                    'role' => 'assistant',
                    'text' => $assistantReply,
                ];

                $this->speakingFeedback[$elementId] = $assistantReply;
                $this->answers[$elementId] = $this->speakingHistory[$elementId];
                $this->c1SpeakingStep = 3; // Finished step 1b
                $this->saveProgress();
            } catch (\Throwable $e) {
                $this->speakingFeedback[$elementId] = 'Fehler: '.$e->getMessage();
            } finally {
                $this->speakingProcessing[$elementId] = false;
            }

            return;
        }

        // Default inter-active conversational handler (e.g. for discussion C1-SP-2)
        $historyContext = '';
        foreach ($this->speakingHistory[$elementId] as $turn) {
            $roleLabel = $turn['role'] === 'user' ? 'Lernender' : 'Prüfer';
            $historyContext .= "{$roleLabel}: {$turn['text']}\n";
        }

        try {
            $result = Gemini::generativeModel(model: 'gemini-2.5-flash')
                ->withSystemInstruction(
                    Content::parse(
                        'Du bist ein TELC-C1-Prüfer für die mündliche Prüfung. '
                        .'Unterhalte dich interaktiv mit dem Lernenden über das gewählte Thema. '
                        .'Lies den Gesprächsverlauf. Reagiere auf die letzte Antwort des Lernenden, '
                        .'stelle eine kurze, logische Anschlussfrage oder führe das Gespräch fort. '
                        .'WICHTIG: Wenn dies der 3. oder 4. Redebeitrag des Lernenden ist, '
                        .'beende das Gespräch höflich, bewerte die Leistung mit einer Punktzahl (0 bis '.$maxPoints.') '
                        .'und gib detailliertes, konstruktives Feedback zu Inhalt, Wortschatz und Grammatik auf Deutsch.'
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
            $this->answers[$elementId] = $this->speakingHistory[$elementId];
            $this->saveProgress();
        } catch (\Throwable $e) {
            $this->speakingFeedback[$elementId] = 'Fehler bei der Analyse: '.$e->getMessage();
        } finally {
            $this->speakingProcessing[$elementId] = false;
        }
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
