<?php

namespace App\Livewire;

use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
use App\Models\Setting;
use App\Services\ExamGrader;
use Livewire\Component;

class GuestExam extends Component
{
    public ?int $selectedTemplateId = null;

    public bool $hasReachedLimit = false;

    /** @var array<string, mixed> */
    public array $template = [];

    /** @var array<string, mixed> */
    public array $answers = [];

    /** @var array<string, mixed>|null */
    public ?array $result = null;

    public int $activeSection = 0;

    public int $remainingSeconds = 0;

    public bool $started = false;

    public function mount(): void
    {
        $this->checkLimit();
    }

    public function checkLimit(): void
    {
        $today = today()->toDateString();
        $tracker = request()->cookie('free_exams_limit_tracker');
        if ($tracker) {
            $parts = explode(':', $tracker);
            if (count($parts) === 2) {
                [$date, $count] = $parts;
                if ($date === $today && (int) $count >= 2) {
                    $this->hasReachedLimit = true;
                }
            }
        }
    }

    public function render()
    {
        return view('livewire.guest-exam', [
            'templates' => ExamTemplate::query()
                ->where('is_published', true)
                ->orderBy('level')
                ->orderBy('title')
                ->get(),
        ]);
    }

    public function loadTemplate(int $id): void
    {
        $this->checkLimit();

        if ($this->hasReachedLimit) {
            return;
        }

        $record = ExamTemplate::query()
            ->where('is_published', true)
            ->findOrFail($id);

        $this->selectedTemplateId = $record->id;
        $this->template = $record->only(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections']);
        $this->answers = [];
        $this->result = null;
        $this->activeSection = 0;
        $this->resetTimer();
    }

    public function startSection(int $index): void
    {
        if (! isset($this->template['sections'][$index])) {
            return;
        }

        $this->activeSection = $index;
        $this->started = true;
        $this->remainingSeconds = max((int) ($this->template['sections'][$index]['minutes'] ?? 1), 1) * 60;
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
        if (! $this->selectedTemplateId) {
            return;
        }

        $this->started = false;
        $this->result = app(ExamGrader::class)->grade($this->template['sections'] ?? [], $this->answers);

        ExamAttempt::create([
            'exam_template_id' => $this->selectedTemplateId,
            'user_id' => null,
            'answers' => $this->answers,
            'result' => $this->result,
            'started_at' => now(),
            'submitted_at' => now(),
        ]);

        $today = today()->toDateString();
        $tracker = request()->cookie('free_exams_limit_tracker');
        $count = 0;
        if ($tracker) {
            $parts = explode(':', $tracker);
            if (count($parts) === 2) {
                [$date, $cookieCount] = $parts;
                if ($date === $today) {
                    $count = (int) $cookieCount;
                }
            }
        }
        $count++;

        \Illuminate\Support\Facades\Cookie::queue('free_exams_limit_tracker', "$today:$count", 1440);

        if ($count >= 2) {
            $this->hasReachedLimit = true;
        }

        $sessionCount = (int) session('guest_exam_count', 0);
        session(['guest_exam_count' => $sessionCount + 1]);

        $ids = (array) session('guest_exam_ids', []);
        $ids[] = $this->selectedTemplateId;
        session(['guest_exam_ids' => $ids]);
    }

    public function resetTimer(): void
    {
        $this->started = false;
        $this->remainingSeconds = max((int) ($this->template['sections'][$this->activeSection]['minutes'] ?? 1), 1) * 60;
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
