<?php

namespace App\Livewire;

use App\Models\ExamAttempt;
use App\Models\ExamEnrollment;
use App\Models\ExamTemplate;
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

    public function mount(): void
    {
        $this->ensurePresets();

        $enrollment = ExamEnrollment::query()
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->whereHas('examTemplate', fn ($query) => $query->where('is_published', true))
            ->with('examTemplate')
            ->latest('enrolled_at')
            ->first();

        if ($enrollment?->examTemplate) {
            $this->loadTemplate($enrollment->examTemplate->id);
        }
    }

    public function render()
    {
        return view('livewire.student-exam', [
            'templates' => ExamTemplate::query()
                ->where('is_published', true)
                ->orderBy('level')
                ->orderBy('title')
                ->get(),
            'enrollments' => ExamEnrollment::query()
                ->where('user_id', Auth::id())
                ->where('status', 'active')
                ->pluck('exam_template_id')
                ->all(),
            'attempts' => ExamAttempt::query()
                ->where('user_id', Auth::id())
                ->latest()
                ->limit(5)
                ->get(),
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
        $this->activeSection = 0;
        $this->resetTimer();
    }

    public function enroll(int $id): void
    {
        $record = ExamTemplate::query()
            ->where('is_published', true)
            ->where('level', 'C1')
            ->findOrFail($id);

        ExamEnrollment::updateOrCreate(
            ['user_id' => Auth::id(), 'exam_template_id' => $record->id],
            [
                'status' => 'active',
                'payment_status' => 'manual_enrolled',
                'enrolled_at' => now(),
            ],
        );

        $this->loadTemplate($record->id);
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
        if (! $this->selectedTemplateId || ! $this->isEnrolled($this->selectedTemplateId)) {
            return;
        }

        $this->started = false;
        $this->result = app(ExamGrader::class)->grade($this->template['sections'] ?? [], $this->answers);

        $this->enrichExplanations();

        ExamAttempt::create([
            'exam_template_id' => $this->selectedTemplateId,
            'user_id' => Auth::id(),
            'answers' => $this->answers,
            'result' => $this->result,
            'started_at' => now(),
            'submitted_at' => now(),
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

    public function analyzeSpeaking(string $elementId, string $transcribedText): void
    {
        $this->speakingProcessing[$elementId] = true;
        $this->speakingFeedback[$elementId] = '';

        $element = collect($this->template['sections'] ?? [])
            ->pluck('elements')
            ->flatten(1)
            ->firstWhere('id', $elementId);

        $prompt = $element['content'] ?? '';

        try {
            $result = Gemini::generativeModel(model: 'gemini-2.0-flash')
                ->withSystemInstruction(
                    Content::parse(
                        'Du bist ein TELC-C1-Prüfer für die mündliche Prüfung. '
                        . 'Bewerte die Antwort des Lernenden auf die gegebene Aufgabenstellung. '
                        . 'Gib eine Punktzahl von 0 bis ' . ((int) ($element['points'] ?? 25)) . ' und detailliertes Feedback '
                        . 'zu Inhalt, Wortschatz, Grammatik, Aussprache und Flüssigkeit. '
                        . 'Antworte auf Deutsch.'
                    )
                )
                ->generateContent(
                    "Aufgabenstellung: {$prompt}\n\n"
                    . "Antwort des Lernenden: {$transcribedText}\n\n"
                    . 'Bewerte diese Antwort mit einer Punktzahl (z. B. "18/25") und gib Verbesserungsvorschläge.'
                );

            $this->speakingFeedback[$elementId] = $result->text();
        } catch (\Throwable $e) {
            $this->speakingFeedback[$elementId] = 'Fehler bei der Analyse: ' . $e->getMessage();
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

    public function setGapAnswer(string $elementId, int $gapIndex, string $value): void
    {
        if (! isset($this->answers[$elementId]) || ! is_array($this->answers[$elementId])) {
            $this->answers[$elementId] = [];
        }

        $this->answers[$elementId][(string) $gapIndex] = $value;
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
                ['level' => $preset['level'], 'title' => $preset['title']],
                $preset + ['is_published' => true],
            );
        }
    }
}
