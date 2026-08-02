<?php

namespace App\Livewire;

use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
use App\Services\AnswerExplainer;
use App\Services\ExamGrader;
use App\Services\SpeakingPromptService;
use Gemini\Data\Content;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;
use Livewire\Component;

class GuestExam extends Component
{
    public string $selectedLevel = 'B1';

    public int $freeSectionLimit = 3;

    public bool $showLoginPrompt = false;

    /** @var array<int, string> */
    public array $completedSectionIds = [];

    /** @var array<int, string> */
    public array $noRetrySectionIds = [];

    public int $remainingSections = 4;

    public bool $hasReachedLimit = false;

    public bool $submitting = false;

    public int $freeExamsCompleted = 0;

    public bool $isBlocked = false;

    public ?int $selectedTemplateId = null;

    /** @var array<string, mixed> */
    public array $template = [];

    public ?int $activeSectionIndex = null;

    /** @var array<string, mixed> */
    public array $answers = [];

    /** @var array<string, mixed>|null */
    public ?array $result = null;

    public ?int $nextSectionIndex = null;

    public int $activeSection = 0;

    public int $remainingSeconds = 0;

    public bool $started = false;

    public string $selectedTopic = '';

    public string $transitionMessage = '';

    public string $aiPresentationText = '';

    public string $unchosenTopic = '';

    public int $speakingStep = 1;

    public string $c1SelectedTopic = '';

    public string $c1AiResponseText = '';

    public string $c1AiResponseAudio = '';

    public int $c1SpeakingStep = 1;

    /** @var array<string, mixed> */
    public array $speakingFeedback = [];

    /** @var array<string, mixed> */
    public array $speakingProcessing = [];

    /** @var array<string, array<int, array<string, string>>> */
    public array $speakingHistory = [];

    public function mount(string|int|null $templateId = null): void
    {
        if (auth()->check()) {
            if ($templateId) {
                $this->redirect(route('dashboard').'?filter=free&start_exam='.$templateId);
            } else {
                $this->redirect(route('dashboard').'?filter=free');
            }

            return;
        }

        if ($templateId) {
            if (is_numeric($templateId)) {
                $record = ExamTemplate::query()
                    ->where('is_published', true)
                    ->find((int) $templateId);
            } else {
                $level = strtoupper($templateId);
                $record = ExamTemplate::query()
                    ->where('is_published', true)
                    ->where('is_free', true)
                    ->where('level', $level)
                    ->first();
            }

            if ($record) {
                $status = $this->getTemplateFreeStatus($record);
                if (! $status['is_free']) {
                    session(['intended_enroll_exam' => $record->id]);
                    $this->redirect(route('register'));

                    return;
                }
                $this->selectedLevel = $record->level;
                $this->loadTemplate($record);
            }
        }

        if (empty($this->template)) {
            $level = request()->query('level', '');
            if (in_array($level, ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'], true)) {
                $this->selectedLevel = $level;
            }
            $this->loadTemplateForLevel();
        }

        $this->loadExamCountFromCookie();
        $this->loadCompletedFromCookie();
        $this->loadNoRetryFromCookie();

        if ($this->freeExamsCompleted >= 2) {
            $this->isBlocked = true;
            $this->hasReachedLimit = true;
            $this->remainingSections = 0;

            return;
        }

        $this->applyTierLimit();
        $this->recalculateRemaining();
    }

    public function loadTemplate(ExamTemplate $record): void
    {
        $this->selectedTemplateId = $record->id;
        $data = $record->only(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections', 'exam_provider']);
        $sections = [];
        foreach ($data['sections'] as $section) {
            $hasSpeaking = false;
            foreach ($section['elements'] ?? [] as $el) {
                if (($el['type'] ?? '') === 'speaking') {
                    $hasSpeaking = true;
                    break;
                }
            }
            if (! $hasSpeaking) {
                $sections[] = $section;
            }
        }
        $data['sections'] = $sections;
        $data['exam_provider'] = $record->exam_provider ?? 'telc';
        $this->template = $data;

        $this->loadExamCountFromCookie();

        if ($this->freeExamsCompleted >= 2) {
            $this->isBlocked = true;
            $this->hasReachedLimit = true;
            $this->remainingSections = 0;
        } else {
            $this->isBlocked = false;
            $this->recalculateRemaining();
        }
        $this->loadSpeakingTopics();
    }

    public function loadCompletedFromCookie(): void
    {
        $raw = request()->cookie('free_sections_completed');
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            $this->completedSectionIds = is_array($decoded) ? $decoded : [];
        } else {
            $this->completedSectionIds = [];
        }
    }

    public function saveCompletedToCookie(): void
    {
        Cookie::queue('free_sections_completed', json_encode($this->completedSectionIds), 60 * 24 * 365);
    }

    public function loadNoRetryFromCookie(): void
    {
        $raw = request()->cookie('free_no_retry_sections');
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            $this->noRetrySectionIds = is_array($decoded) ? $decoded : [];
        } else {
            $this->noRetrySectionIds = [];
        }
    }

    public function saveNoRetryToCookie(): void
    {
        Cookie::queue('free_no_retry_sections', json_encode($this->noRetrySectionIds), 60 * 24 * 365);
    }

    public function loadExamCountFromCookie(): void
    {
        $provider = strtolower($this->template['exam_provider'] ?? 'telc');
        $raw = request()->cookie("free_exams_completed_{$provider}");
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            $this->freeExamsCompleted = is_int($decoded) ? $decoded : 0;
        } else {
            $this->freeExamsCompleted = 0;
        }
    }

    public function saveExamCountToCookie(): void
    {
        $provider = strtolower($this->template['exam_provider'] ?? 'telc');
        Cookie::queue("free_exams_completed_{$provider}", $this->freeExamsCompleted, 60 * 24 * 365);
    }

    public function incrementExamCount(): void
    {
        $this->freeExamsCompleted++;
        $this->saveExamCountToCookie();
    }

    public function applyTierLimit(): void
    {
        if ($this->freeExamsCompleted === 0) {
            $this->freeSectionLimit = 999;
        } else {
            $this->freeSectionLimit = 2;
        }
    }

    /**
     * @return array{is_free: bool, free_sections: int}
     */
    public function getTemplateFreeStatus(ExamTemplate $record): array
    {
        if ($record->is_free) {
            return ['is_free' => true, 'free_sections' => count($record->sections ?? [])];
        }

        return ['is_free' => false, 'free_sections' => 0];
    }

    public function recalculateRemaining(): void
    {
        if (! $this->selectedTemplateId) {
            $this->remainingSections = 0;
            $this->hasReachedLimit = true;

            return;
        }

        $record = ExamTemplate::find($this->selectedTemplateId);
        if (! $record) {
            $this->remainingSections = 0;
            $this->hasReachedLimit = true;

            return;
        }

        $this->applyTierLimit();

        $completed = count($this->completedSectionIds);

        $this->remainingSections = (int) max(0, $this->freeSectionLimit - $completed);
        $this->hasReachedLimit = $this->remainingSections <= 0;
    }

    public function selectLevel(string $level): void
    {
        if (! in_array($level, ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'], true)) {
            return;
        }

        $this->selectedLevel = $level;
        $this->activeSectionIndex = null;
        $this->answers = [];
        $this->result = null;
        $this->started = false;
        $this->loadTemplateForLevel();
    }

    public function loadTemplateForLevel(): void
    {
        $record = ExamTemplate::query()
            ->where('is_published', true)
            ->where('is_free', true)
            ->where('level', $this->selectedLevel)
            ->orderBy('id')
            ->first();

        if (! $record) {
            $this->template = [];
            $this->selectedTemplateId = null;

            return;
        }

        $this->loadTemplate($record);
    }

    /** @return list<string> */
    public function getLevels(): array
    {
        $order = ['A1' => 0, 'A2' => 1, 'B1' => 2, 'B2' => 3, 'C1' => 4, 'C2' => 5];

        return array_values(ExamTemplate::query()
            ->where('is_published', true)
            ->where('is_free', true)
            ->whereIn('level', ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])
            ->pluck('level')
            ->unique()
            ->sortBy(fn ($l) => $order[$l] ?? 99)
            ->toArray());
    }

    public function isSectionCompleted(int $sectionIndex): bool
    {
        $sections = $this->template['sections'] ?? [];
        if (! isset($sections[$sectionIndex])) {
            return false;
        }

        return in_array($sections[$sectionIndex]['id'], $this->completedSectionIds, true);
    }

    public function isNoRetrySection(int $sectionIndex): bool
    {
        $sections = $this->template['sections'] ?? [];
        if (! isset($sections[$sectionIndex])) {
            return false;
        }
        $id = $sections[$sectionIndex]['id'];

        return in_array($id, $this->noRetrySectionIds, true);
    }

    public function canRetry(int $sectionIndex): bool
    {
        if (! $this->isSectionCompleted($sectionIndex)) {
            return true;
        }

        return ! $this->isNoRetrySection($sectionIndex);
    }

    public function sectionHasAudio(int $sectionIndex): bool
    {
        $sections = $this->template['sections'] ?? [];
        if (! isset($sections[$sectionIndex])) {
            return false;
        }

        return collect((array) ($sections[$sectionIndex]['elements'] ?? []))
            ->contains(fn ($el) => ($el['type'] ?? '') === 'audio');
    }

    public function sectionHasSpeaking(int $sectionIndex): bool
    {
        $sections = $this->template['sections'] ?? [];
        if (! isset($sections[$sectionIndex])) {
            return false;
        }

        return collect((array) ($sections[$sectionIndex]['elements'] ?? []))
            ->contains(fn ($el) => ($el['type'] ?? '') === 'speaking');
    }

    public function sectionType(int $sectionIndex): string
    {
        if ($this->sectionHasSpeaking($sectionIndex)) {
            return 'speaking';
        }
        if ($this->sectionHasAudio($sectionIndex)) {
            return 'listening';
        }
        $sections = $this->template['sections'] ?? [];
        if (! isset($sections[$sectionIndex])) {
            return 'default';
        }
        $elements = $sections[$sectionIndex]['elements'] ?? [];
        $types = collect((array) $elements)->pluck('type')->unique()->values()->toArray();
        if (in_array('writing', $types, true)) {
            return 'writing';
        }
        if (in_array('inline_gap', $types, true) || in_array('interactive_text', $types, true)) {
            return 'reading';
        }

        return 'reading';
    }

    public function sectionStatus(int $sectionIndex): string
    {
        if ($this->isSectionCompleted($sectionIndex) && $this->isNoRetrySection($sectionIndex)) {
            return 'locked';
        }
        if ($this->isSectionCompleted($sectionIndex)) {
            return 'completed';
        }
        if ($this->activeSectionIndex === $sectionIndex && $this->started) {
            return 'in-progress';
        }

        return 'new';
    }

    public function startSection(int $index): void
    {
        $sections = $this->template['sections'] ?? [];
        if (! isset($sections[$index])) {
            return;
        }

        $this->recalculateRemaining();
        $isNew = ! $this->isSectionCompleted($index);

        if ($isNew && $this->hasReachedLimit) {
            return;
        }

        if ($this->isSectionCompleted($index) && $this->isNoRetrySection($index)) {
            return;
        }

        $this->activeSectionIndex = $index;
        $this->activeSection = $index;
        $this->answers = [];
        $this->result = null;
        $this->nextSectionIndex = null;
        $this->started = true;
        $this->remainingSeconds = max((int) ($sections[$index]['minutes'] ?? 1), 1) * 60;
    }

    public function tick(): void
    {
        if (! $this->started || $this->remainingSeconds <= 0) {
            return;
        }

        $this->remainingSeconds--;

        if ($this->remainingSeconds === 0) {
            $this->submit();
        }
    }

    public function submit(): void
    {
        @set_time_limit(180);
        $this->submitting = true;

        if (! $this->selectedTemplateId || $this->activeSectionIndex === null) {
            $this->submitting = false;

            return;
        }

        $sections = $this->template['sections'] ?? [];
        $currentSection = $sections[$this->activeSectionIndex] ?? null;

        if (! $currentSection) {
            $this->submitting = false;

            return;
        }

        $sectionId = $currentSection['id'];
        $isFirstCompletion = ! in_array($sectionId, $this->completedSectionIds, true);

        $tempCompleted = $this->completedSectionIds;
        if ($isFirstCompletion) {
            $tempCompleted[] = $sectionId;
        }

        $isLastSectionOfTemplate = ($this->activeSectionIndex === count($sections) - 1);
        $reachesFreeLimit = (count($tempCompleted) >= $this->freeSectionLimit);

        $level = strtoupper($this->template['level'] ?? '');
        $isB1B2C1 = in_array($level, ['B1', 'B2', 'C1'], true);

        if ($isB1B2C1) {
            if ($isLastSectionOfTemplate) {
                $this->started = false;
                $this->result = app(ExamGrader::class)->grade($sections, $this->answers);
                $this->enrichExplanations();

                $writingDetails = [];
                $allWritingElements = [];
                foreach ($sections as $section) {
                    foreach ($section['elements'] ?? [] as $element) {
                        if (($element['type'] ?? '') === 'writing') {
                            $allWritingElements[] = $element;
                        }
                    }
                }
                foreach ($allWritingElements as $element) {
                    $elId = $element['id'];
                    $maxPoints = (int) ($element['points'] ?? 48);
                    $passMark = (int) ceil($maxPoints * 0.6);
                    $content = (string) ($this->answers[$elId] ?? '');
                    $feedback = 'Keine Abgabe für den schriftlichen Ausdruck.';
                    $score = 0;

                    if (! empty(trim($content))) {
                        try {
                            $lvl = $this->template['level'] ?? 'C1';
                            $provider = $this->template['exam_provider'] ?? 'telc';
                            $model = Gemini::generativeModel(model: config('gemini.model'))
                                ->withSystemInstruction(
                                    Content::parse(
                                        "Du bist ein offizieller {$provider} {$lvl} Prüfer für die schriftliche Prüfung. "
                                        ."Bewerte den gegebenen Aufsatz. Gib eine exakte Punktzahl von 0 bis {$maxPoints} Punkten zurück."
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

                    $writingDetails[] = [
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

                if (! empty($writingDetails)) {
                    $this->result['details'] = array_merge($this->result['details'], $writingDetails);
                    $this->result['earned'] += array_sum(array_column($writingDetails, 'points'));
                    $this->result['possible'] += array_sum(array_column($writingDetails, 'max_points'));
                    $this->result['percentage'] = $this->result['possible'] > 0 ? round(($this->result['earned'] / $this->result['possible']) * 100, 2) : 0;
                }

                $this->completedSectionIds = [];
                $this->noRetrySectionIds = [];
                $this->saveCompletedToCookie();
                $this->saveNoRetryToCookie();

                $this->incrementExamCount();

                ExamAttempt::create([
                    'exam_template_id' => $this->selectedTemplateId,
                    'user_id' => null,
                    'answers' => $this->answers,
                    'result' => $this->result,
                    'started_at' => now(),
                    'submitted_at' => now(),
                ]);

                $this->submitting = false;

                return;
            }

            if ($reachesFreeLimit) {
                $this->started = false;

                $this->incrementExamCount();

                session([
                    'guest_pending_attempt' => [
                        'template_id' => $this->selectedTemplateId,
                        'active_section' => $this->activeSectionIndex,
                        'answers' => $this->answers,
                        'completed_sections' => array_unique(array_merge($this->completedSectionIds, [$sectionId])),
                        'no_retry_sections' => array_unique(array_merge($this->noRetrySectionIds, [$sectionId])),
                    ],
                ]);

                $this->showLoginPrompt = true;

                $this->submitting = false;

                return;
            }
        } else {
            if ($isLastSectionOfTemplate || $reachesFreeLimit) {
                $this->started = false;

                $this->incrementExamCount();

                // Save to session
                session([
                    'guest_pending_attempt' => [
                        'template_id' => $this->selectedTemplateId,
                        'active_section' => $this->activeSectionIndex,
                        'answers' => $this->answers,
                        'completed_sections' => array_unique(array_merge($this->completedSectionIds, [$sectionId])),
                        'no_retry_sections' => array_unique(array_merge($this->noRetrySectionIds, [$sectionId])),
                    ],
                ]);

                $this->showLoginPrompt = true;

                $this->submitting = false;

                return;
            }
        }

        $this->started = false;

        $this->result = app(ExamGrader::class)->grade(
            [$currentSection],
            $this->answers
        );

        $this->enrichExplanations();

        $writingDetails = [];
        $writingElements = [];
        foreach ($currentSection['elements'] ?? [] as $element) {
            if (($element['type'] ?? '') === 'writing') {
                $writingElements[] = $element;
            }
        }
        foreach ($writingElements as $element) {
            $elId = $element['id'];
            $maxPoints = (int) ($element['points'] ?? 48);
            $passMark = (int) ceil($maxPoints * 0.6);
            $content = (string) ($this->answers[$elId] ?? '');
            $feedback = 'Keine Abgabe für den schriftlichen Ausdruck.';
            $score = 0;

            if (! empty(trim($content))) {
                try {
                    $lvl = $this->template['level'] ?? 'C1';
                    $provider = $this->template['exam_provider'] ?? 'telc';
                    $model = Gemini::generativeModel(model: config('gemini.model'))
                        ->withSystemInstruction(
                            Content::parse(
                                "Du bist ein offizieller {$provider} {$lvl} Prüfer für die schriftliche Prüfung. "
                                ."Bewerte den gegebenen Aufsatz. Gib eine exakte Punktzahl von 0 bis {$maxPoints} Punkten zurück."
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

            $writingDetails[] = [
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

        if (! empty($writingDetails)) {
            $this->result['details'] = array_merge($this->result['details'], $writingDetails);
            $this->result['earned'] += array_sum(array_column($writingDetails, 'points'));
            $this->result['possible'] += array_sum(array_column($writingDetails, 'max_points'));
            $this->result['percentage'] = $this->result['possible'] > 0 ? round(($this->result['earned'] / $this->result['possible']) * 100, 2) : 0;
        }

        if ($isFirstCompletion) {
            $this->completedSectionIds[] = $sectionId;
            $this->saveCompletedToCookie();
            $this->recalculateRemaining();
        }

        if ($this->sectionHasSpeaking($this->activeSectionIndex) || $this->sectionHasAudio($this->activeSectionIndex)) {
            if (! in_array($sectionId, $this->noRetrySectionIds, true)) {
                $this->noRetrySectionIds[] = $sectionId;
                $this->saveNoRetryToCookie();
            }
        }

        ExamAttempt::create([
            'exam_template_id' => $this->selectedTemplateId,
            'user_id' => null,
            'answers' => $this->answers,
            'result' => $this->result,
            'started_at' => now(),
            'submitted_at' => now(),
        ]);

        $this->nextSectionIndex = $this->findNextSectionToJump();

        $this->submitting = false;
    }

    public function findNextSectionToJump(): ?int
    {
        $sections = $this->template['sections'] ?? [];
        $currentId = $sections[$this->activeSectionIndex]['id'] ?? null;

        foreach ($sections as $i => $section) {
            $id = $section['id'] ?? '';

            if ($id === $currentId) {
                continue;
            }

            $isCompleted = in_array($id, $this->completedSectionIds, true);
            $isLocked = $isCompleted && in_array($id, $this->noRetrySectionIds, true);

            if ($isLocked) {
                continue;
            }

            if (! $isCompleted && $this->hasReachedLimit) {
                continue;
            }

            return $i;
        }

        return null;
    }

    public function backToChapters(): void
    {
        $this->activeSectionIndex = null;
        $this->answers = [];
        $this->result = null;
        $this->nextSectionIndex = null;
        $this->started = false;
    }

    public function render(): View
    {
        $sections = $this->template['sections'] ?? [];

        return view('livewire.guest-exam', [
            'levels' => $this->getLevels(),
            'sections' => $sections,
            'currentSection' => ($this->activeSectionIndex !== null && isset($sections[$this->activeSectionIndex]))
                ? $sections[$this->activeSectionIndex]
                : null,
        ]);
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
            'multiple_choice' => ['a', 'b', 'c', 'd'],
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

        $raw = $element['options'] ?? '[]';
        if (is_string($raw)) {
            $raw = json_decode($raw, true);
        }

        return is_array($raw) ? array_values($raw) : [];
    }

    /** @var array<int, string> */
    public array $aiGeneratedTopics = [];

    public function loadSpeakingTopics(): void
    {
        $elements = collect((array) ($this->template['sections'] ?? []))
            ->pluck('elements')
            ->flatten(1);
        $sp1aEl = $elements->first(fn ($el) => str_ends_with((string) ($el['id'] ?? ''), '-SP-1a'));

        $spContent = $sp1aEl['content'] ?? '';
        $parsedTopics = [];
        if (preg_match_all('/(\d+)\.\s*(.+?)(?=\s*(?:\/|oder)\s*\d+\.|\s*$)/u', $spContent, $topicMatches)) {
            foreach ($topicMatches[2] as $t) {
                $parsedTopics[] = trim($t);
            }
        }

        if (count($parsedTopics) >= 2) {
            $this->aiGeneratedTopics = $parsedTopics;
        } else {
            $level = $this->template['level'] ?? 'C1';
            $provider = 'telc'; // default guest provider
            try {
                $result = Gemini::generativeModel(model: config('gemini.model'))
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

    public function chooseRandomTopic(string $elementId = ''): void
    {
        if (empty($this->aiGeneratedTopics)) {
            $this->loadSpeakingTopics();
        }
        if (! empty($this->aiGeneratedTopics)) {
            $topic = $this->aiGeneratedTopics[array_rand($this->aiGeneratedTopics)];
            $this->chooseTopic($topic, $elementId);
        }
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

        if (! empty($this->aiGeneratedTopics)) {
            $remaining = array_values(array_filter($this->aiGeneratedTopics, fn ($t) => $t !== $topic));
            if (! empty($remaining)) {
                $this->unchosenTopic = $remaining[array_rand($remaining)];
            }
        }
        if (empty($this->unchosenTopic)) {
            $this->unchosenTopic = 'Das alternative Thema';
        }
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

            $result = Gemini::generativeModel(model: config('gemini.model'))
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
            $criteriaPrompt = "Bewerte den Vortrag des Lernenden zu dem von ihm gewählten Thema.\nKriterien:\n- Inhaltliche Angemessenheit & Relevanz (0-2 Punkte)\n- Struktur und logischer Aufbau (0-2 Punkte)\n- Ausdrucksweise und Kohärenz (0-2 Punkte)\n\nBitte gib eine kurze Begründung für jedes Kriterium an.\nGib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        } elseif (str_ends_with($elementId, '-SP-1b')) {
            $criteriaPrompt = "Bewerte die Reaktion des Lernenden auf den Vortrag.\nKriterien:\n- Verständnis des gehörten Vortrags (0-2 Punkte)\n- Relevanz und Qualität der Anschlussfragen/Kommentare (0-2 Punkte)\n\nBitte gib eine kurze Begründung für jedes Kriterium an.\nGib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        } elseif (str_ends_with($elementId, '-SP-2')) {
            $criteriaPrompt = "Bewerte die Diskussionsleistung des Lernenden.\nKriterien:\n- Argumentation und Eingehen auf den Gesprächspartner (0-2 Punkte)\n- Interaktionsfähigkeit (0-2 Punkte)\n- Syntaktische und inhaltliche Angemessenheit (0-2 Punkte)\n\nBitte gib eine kurze Begründung für jedes Kriterium an.\nGib am Ende die Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        } else {
            $criteriaPrompt = "Bewerte die mündliche Leistung des Lernenden.\nBitte gib eine Gesamtpunktzahl im Format 'SCORE: X/{$maxPoints}' an.";
        }

        $provider = 'telc'; // default guest provider
        $systemInstruction = SpeakingPromptService::getSystemInstruction($provider, $level);

        // SP-1a: student's initial presentation on chosen topic
        if (str_ends_with($elementId, '-SP-1a')) {
            try {
                $result = Gemini::generativeModel(model: config('gemini.model'))
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

                // Extract the unchosen topic from the element content
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
                $this->transitionMessage = 'Teil 1a ist abgeschlossen. Sehr gut! Wir gehen jetzt zu Teil 1b über. Hören Sie sich bitte den KI-Vortrag an.';
                $this->saveProgress();
            } catch (\Throwable $e) {
                array_pop($this->speakingHistory[$elementId]);
                $this->speakingFeedback[$elementId] = 'Fehler: '.$e->getMessage();
            } finally {
                $this->speakingProcessing[$elementId] = false;
            }

            return;
        }

        // SP-1b: student's reaction/summary of the AI presentation
        if (str_ends_with($elementId, '-SP-1b')) {
            try {
                $result = Gemini::generativeModel(model: config('gemini.model'))
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
                $this->speakingStep = 3;
                $this->transitionMessage = 'Teil 1b ist abgeschlossen. Sehr gut! Wir gehen jetzt zur Diskussion über.';
                $this->saveProgress();
            } catch (\Throwable $e) {
                array_pop($this->speakingHistory[$elementId]);
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

        $userTurnCount = collect($this->speakingHistory[$elementId])->where('role', 'user')->count();
        $timerEnded = $this->remainingSeconds <= 0;
        $isLastTurn = $timerEnded || $userTurnCount >= 3;

        if ($isLastTurn) {
            $generationPrompt = "Aufgabenstellung/Thema: {$prompt}\n\n"
                ."Bisheriger Gesprächsverlauf:\n{$historyContext}\n"
                ."WICHTIG: Die Zeit für die Diskussion (Teil 2) ist abgelaufen bzw. die maximale Rundenanzahl ist erreicht. Generiere als Prüfer eine kurze, höfliche Verabschiedung zur Diskussion (z.B. 'Vielen Dank für das Gespräch, damit beenden wir die Diskussion.') und gib am Ende die finale Gesamtbewertung ab im Format 'SCORE: X/{$maxPoints}'. Stelle KEINE weiteren Fragen mehr!";
        } else {
            $generationPrompt = "Aufgabenstellung/Thema: {$prompt}\n\n"
                ."Bisheriger Gesprächsverlauf:\n{$historyContext}\n"
                .'Generiere die nächste Antwort des Prüfers:';
        }

        try {
            $result = Gemini::generativeModel(model: config('gemini.model'))
                ->withSystemInstruction(
                    Content::parse($systemInstruction."\n\n".$criteriaPrompt)
                )
                ->generateContent($generationPrompt);

            $assistantReply = $result->text();

            $this->speakingHistory[$elementId][] = [
                'role' => 'assistant',
                'text' => $assistantReply,
            ];

            $this->speakingFeedback[$elementId] = $assistantReply;
            $this->answers[$elementId] = $transcribedText;
            if ($isLastTurn || str_contains($assistantReply, 'SCORE:')) {
                $this->dispatch('stop-all-speech');
            }
            $this->saveProgress();
        } catch (\Throwable $e) {
            array_pop($this->speakingHistory[$elementId]);
            $this->speakingFeedback[$elementId] = 'Fehler bei der Analyse: '.$e->getMessage();
        } finally {
            $this->speakingProcessing[$elementId] = false;
        }
    }

    /**
     * @return array<int, string>
     */
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

    public function saveProgress(): void
    {
        // No-op for guest exam
    }

    private function enrichExplanations(): void
    {
        $explainer = app(AnswerExplainer::class);
        $batchItems = [];
        $detailsToUpdate = [];

        foreach ($this->result['details'] ?? [] as $key => $detail) {
            $elId = $detail['id'];
            $gapIndex = null;
            if (str_contains($elId, '.gap.')) {
                $parts = explode('.gap.', $elId);
                $elId = $parts[0];
                $gapIndex = (int) $parts[1];
            }

            $element = collect((array) ($this->template['sections'] ?? []))
                ->pluck('elements')
                ->flatten(1)
                ->firstWhere('id', $elId);

            if (! $element) {
                continue;
            }

            if ($gapIndex !== null) {
                $raw = $element['options'] ?? '[]';
                $gaps = is_string($raw) ? json_decode($raw, true) : $raw;
                $gap = $gaps[$gapIndex] ?? null;

                if ($gap) {
                    $this->result['details'][$key]['question'] = $gap['question'] ?? ('Lücke '.($gapIndex + 1));
                    if (! empty($gap['explanation'])) {
                        $this->result['details'][$key]['explanation'] = $gap['explanation'];

                        continue;
                    }
                }

                $gapOptionsStr = $gap['options'] ?? '';
                $question = 'Lücke '.($gapIndex + 1)." in folgendem Text:\n".($element['content'] ?? '')."\nOptionen: ".$gapOptionsStr;
                $correctAnswer = $gap['answer'] ?? '';
            } else {
                $this->result['details'][$key]['question'] = $element['question'] ?? $element['label'] ?? '';
                if (! empty($element['explanation'])) {
                    $this->result['details'][$key]['explanation'] = $element['explanation'];

                    continue;
                }

                $question = $element['question'] ?? $element['label'] ?? '';
                $content = $element['content'] ?? '';
                if ($content !== '' && empty($element['question'])) {
                    $question .= "\n".$content;
                }
                $correctAnswer = $element['answer'] ?? '';
            }

            if ($correctAnswer === '' || $question === '') {
                $this->result['details'][$key]['explanation'] = '';

                continue;
            }

            $this->result['details'][$key]['explanation'] = '';
            $batchItems[] = [
                'id' => $detail['id'],
                'question' => $question,
                'correct_answer' => $correctAnswer,
                'student_answer' => is_array($detail['given'] ?? null) ? json_encode($detail['given']) : ($detail['given'] ?? null),
            ];
            $detailsToUpdate[$detail['id']] = $key;
        }

        if (! empty($batchItems)) {
            $explanations = $explainer->explainBatch($batchItems);
            foreach ($explanations as $id => $explanation) {
                if (isset($detailsToUpdate[$id])) {
                    $key = $detailsToUpdate[$id];
                    $this->result['details'][$key]['explanation'] = $explanation;
                }
            }
        }
    }

    public function repeat(): void
    {
        if ($this->activeSectionIndex !== null) {
            $sections = $this->template['sections'] ?? [];
            $currentSection = $sections[$this->activeSectionIndex] ?? null;
            if ($currentSection) {
                $sectionId = $currentSection['id'];
                $this->completedSectionIds = array_values(array_diff($this->completedSectionIds, [$sectionId]));
                $this->noRetrySectionIds = array_values(array_diff($this->noRetrySectionIds, [$sectionId]));
                $this->saveCompletedToCookie();
                $this->saveNoRetryToCookie();
                $this->recalculateRemaining();
            }

            $this->answers = [];
            $this->result = null;
            $this->started = true;
            $this->remainingSeconds = max((int) ($sections[$this->activeSectionIndex]['minutes'] ?? 1), 1) * 60;
        }
    }
}
