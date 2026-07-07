<?php

namespace App\Livewire;

use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
use App\Models\Setting;
use App\Services\ExamGrader;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;
use Livewire\Component;

class GuestExam extends Component
{
    public string $selectedLevel = 'B1';

    public int $freeSectionLimit = 4;

    /** @var array<int, string> */
    public array $completedSectionIds = [];

    /** @var array<int, string> */
    public array $noRetrySectionIds = [];

    public int $remainingSections = 4;

    public bool $hasReachedLimit = false;

    public ?int $selectedTemplateId = null;

    /** @var array<string, mixed> */
    public array $template = [];

    public ?int $activeSectionIndex = null;

    /** @var array<string, mixed> */
    public array $answers = [];

    /** @var array<string, mixed>|null */
    public ?array $result = null;

    public int $activeSection = 0;

    public int $remainingSeconds = 0;

    public bool $started = false;

    public function mount(): void
    {
        $this->freeSectionLimit = max(1, (int) Setting::value('free_section_limit', '4'));

        $this->loadCompletedFromCookie();
        $this->loadNoRetryFromCookie();

        $level = request()->query('level', '');
        if (in_array($level, ['B1', 'B2', 'C2'], true)) {
            $this->selectedLevel = $level;
        }

        $this->loadTemplateForLevel();
        $this->recalculateRemaining();
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

    public function recalculateRemaining(): void
    {
        $completed = count($this->completedSectionIds);
        $this->remainingSections = max(0, $this->freeSectionLimit - $completed);
        $this->hasReachedLimit = $this->remainingSections <= 0;
    }

    public function selectLevel(string $level): void
    {
        if (! in_array($level, ['B1', 'B2', 'C2'], true)) {
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
            ->where('level', $this->selectedLevel)
            ->first();

        if (! $record) {
            $this->template = [];
            $this->selectedTemplateId = null;

            return;
        }

        $this->selectedTemplateId = $record->id;
        $this->template = $record->only(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections']);

        $this->recalculateRemaining();
    }

    /** @return list<string> */
    public function getLevels(): array
    {
        return array_values(ExamTemplate::query()
            ->where('is_published', true)
            ->whereIn('level', ['B1', 'B2', 'C2'])
            ->pluck('level')
            ->unique()
            ->sort()
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
        if (! $this->selectedTemplateId || $this->activeSectionIndex === null) {
            return;
        }

        $sections = $this->template['sections'] ?? [];
        $currentSection = $sections[$this->activeSectionIndex] ?? null;

        if (! $currentSection) {
            return;
        }

        $this->started = false;
        $this->result = app(ExamGrader::class)->grade(
            [$currentSection],
            $this->answers
        );

        $sectionId = $currentSection['id'];
        $isFirstCompletion = ! in_array($sectionId, $this->completedSectionIds, true);

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
    }

    public function backToChapters(): void
    {
        $this->activeSectionIndex = null;
        $this->answers = [];
        $this->result = null;
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
}
