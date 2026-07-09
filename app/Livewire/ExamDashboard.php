<?php

namespace App\Livewire;

use App\Models\ExamAttempt;
use App\Models\ExamEnrollment;
use App\Models\ExamTemplate;
use App\Models\User;
use App\Services\ExamGrader;
use App\Services\TelcTemplateFactory;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Title('TELC Exam Builder')]
class ExamDashboard extends Component
{
    use WithFileUploads;

    public mixed $audioUpload = null;

    public ?int $selectedTemplateId = null;

    public string $mode = 'builder';

    /** @var array<string, mixed> */
    public array $template = [];

    /** @var array<string, mixed> */
    public array $answers = [];

    /** @var array<string, mixed>|null */
    public ?array $result = null;

    public int $activeSection = 0;

    public int $remainingSeconds = 0;

    public bool $started = false;

    public string $providerFilter = 'telc';

    public string $c1SelectedTopic = '';

    public string $c1AiResponseText = '';

    public string $c1AiResponseAudio = '';

    public int $c1SpeakingStep = 1;

    /** @var array<string, mixed> */
    public array $speakingFeedback = [];

    /** @var array<string, mixed> */
    public array $speakingProcessing = [];

    /** @var array<int, array<string, mixed>> */
    public array $speakingHistory = [];

    public bool $showEnrollModal = false;

    public ?int $enrollTemplateId = null;

    public string $enrollModalError = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);

        $this->ensurePresets();

        $template = ExamTemplate::query()->where('exam_provider', $this->providerFilter)->orderBy('level')->first();
        if ($template) {
            $this->loadTemplate($template->id);
        }
    }

    public function render(): View
    {
        return view('livewire.exam-dashboard', [
            'templates' => ExamTemplate::query()->where('exam_provider', $this->providerFilter)->orderBy('level')->get(),
            'presets' => array_keys(TelcTemplateFactory::presets()),
            'elementTypes' => $this->elementTypes(),
            'metrics' => $this->metrics(),
            'performanceRows' => $this->performanceRows(),
            'participants' => $this->selectedTemplateId ? ExamAttempt::query()->where('exam_template_id', $this->selectedTemplateId)->with('user')->latest()->get() : collect(),
            'pendingPayments' => ExamEnrollment::query()->where('status', 'pending')->with(['user', 'examTemplate'])->latest()->get(),
        ]);
    }

    public function approvePayment(int $enrollmentId): void
    {
        $enrollment = ExamEnrollment::findOrFail($enrollmentId);
        $enrollment->update([
            'status' => 'active',
            'payment_status' => 'manual_enrolled',
            'enrolled_at' => now(),
        ]);

        session()->flash('payment_message', "Zahlung für {$enrollment->user->name} freigegeben.");
    }

    public function rejectPayment(int $enrollmentId): void
    {
        $enrollment = ExamEnrollment::findOrFail($enrollmentId);
        // Delete receipt file if exists
        if ($enrollment->receipt_path) {
            Storage::disk('public')->delete($enrollment->receipt_path);
        }

        $enrollment->delete();

        session()->flash('payment_message', 'Zahlung abgelehnt und Antrag gelöscht.');
    }

    public function changeProvider(string $provider): void
    {
        $this->providerFilter = $provider;
        $template = ExamTemplate::query()->where('exam_provider', $this->providerFilter)->orderBy('level')->first();
        if ($template) {
            $this->loadTemplate($template->id);
        } else {
            $this->selectedTemplateId = null;
            $this->template = [];
        }
    }

    public function loadTemplate(int $id): void
    {
        $record = ExamTemplate::findOrFail($id);

        $this->selectedTemplateId = $record->id;
        $this->template = $record->only(['title', 'level', 'source_pdf', 'theme', 'timings', 'sections', 'is_published', 'exam_provider', 'price', 'is_free']);
        $this->providerFilter = $record->exam_provider ?? 'telc';
        $this->activeSection = 0;
        $this->answers = [];
        $this->result = null;
        $this->resetTimer();
    }

    public function createFromPreset(string $level): void
    {
        $preset = TelcTemplateFactory::forLevel($level);
        $preset['title'] .= ' Custom';
        $preset['is_published'] = false;
        $preset['exam_provider'] = $this->providerFilter;

        $record = ExamTemplate::create($preset);

        $this->loadTemplate($record->id);
    }

    public function save(): void
    {
        $this->validate([
            'template.title' => ['required', 'string', 'max:255'],
            'template.level' => ['required', 'string', 'max:8'],
            'template.sections' => ['required', 'array', 'min:1'],
        ]);

        ExamTemplate::updateOrCreate(
            ['id' => $this->selectedTemplateId],
            $this->template,
        );

        $this->dispatch('exam-saved');
    }

    public function togglePublished(): void
    {
        $this->template['is_published'] = ! (bool) ($this->template['is_published'] ?? false);
        $this->save();
    }

    public function addSection(): void
    {
        $this->template['sections'][] = [
            'id' => 'section-'.Str::uuid(),
            'title' => 'Neuer Prüfungsteil',
            'minutes' => 0,
            'elements' => [],
        ];
    }

    public function removeSection(int $sectionIndex): void
    {
        unset($this->template['sections'][$sectionIndex]);
        $this->template['sections'] = array_values($this->template['sections']);
        $this->activeSection = min($this->activeSection, max(count($this->template['sections']) - 1, 0));
    }

    public function moveSection(int $from, int $to): void
    {
        $this->template['sections'] = $this->move($this->template['sections'], $from, $to);
        $this->activeSection = $to;
    }

    public function addElement(int $sectionIndex, string $type): void
    {
        $defaultOptions = match ($type) {
            'multiple_choice', 'true_false' => 'a,b,c',
            'inline_gap' => '[]',
            default => '',
        };

        $this->template['sections'][$sectionIndex]['elements'][] = [
            'id' => 'item-'.Str::uuid(),
            'type' => $type,
            'label' => $this->elementTypes()[$type] ?? 'Element',
            'content' => '',
            'options' => $defaultOptions,
            'answer' => '',
            'points' => in_array($type, ['text', 'image', 'audio', 'writing'], true) ? 0 : 1,
        ];
    }

    public function duplicateElement(int $sectionIndex, int $elementIndex): void
    {
        $element = $this->template['sections'][$sectionIndex]['elements'][$elementIndex];
        $element['id'] = 'item-'.Str::uuid();
        $element['label'] .= ' Kopie';

        array_splice($this->template['sections'][$sectionIndex]['elements'], $elementIndex + 1, 0, [$element]);
    }

    public function removeElement(int $sectionIndex, int $elementIndex): void
    {
        unset($this->template['sections'][$sectionIndex]['elements'][$elementIndex]);
        $this->template['sections'][$sectionIndex]['elements'] = array_values($this->template['sections'][$sectionIndex]['elements']);
    }

    public function moveElement(int $sectionIndex, int $from, int $to): void
    {
        $this->template['sections'][$sectionIndex]['elements'] = $this->move($this->template['sections'][$sectionIndex]['elements'], $from, $to);
    }

    public function uploadAudio(int $sectionIndex, int $elementIndex): void
    {
        $this->validate([
            'audioUpload' => ['required', 'file', 'mimes:mp3,wav,ogg,m4a', 'max:20480'],
        ]);

        $path = $this->audioUpload->store('', 'exam-audio');

        $this->template['sections'][$sectionIndex]['elements'][$elementIndex]['content'] = route('exam.audio', ['path' => $path]);
        $this->audioUpload = null;
    }

    public function syncGapIds(int $sectionIndex, int $elementIndex): void
    {
        $content = $this->template['sections'][$sectionIndex]['elements'][$elementIndex]['content'] ?? '';
        $gaps = $this->template['sections'][$sectionIndex]['elements'][$elementIndex]['gaps'] ?? [];

        preg_match_all('/\[\[gap:(\d+)\]\]/', $content, $matches);
        $foundIds = array_unique($matches[1]);

        $updated = [];
        foreach ($foundIds as $id) {
            if (isset($gaps[$id])) {
                $updated[$id] = $gaps[$id];
            } else {
                $updated[$id] = [
                    'type' => 'dropdown',
                    'options' => 'a:,b:,c:',
                    'answer' => '',
                ];
            }
        }

        $this->template['sections'][$sectionIndex]['elements'][$elementIndex]['gaps'] = $updated;
    }

    public function switchMode(string $mode): void
    {
        $this->mode = $mode;
        if ($mode === 'student' && ! empty($this->template['sections'])) {
            $this->startSection(0);
        }
        $this->resetTimer();
    }

    public function startSection(int $sectionIndex): void
    {
        $this->activeSection = $sectionIndex;
        $this->started = true;
        $minutes = (int) ($this->template['sections'][$sectionIndex]['minutes'] ?? 0);
        $this->remainingSeconds = max($minutes, 1) * 60;
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
        $this->started = false;
        $this->result = app(ExamGrader::class)->grade($this->template['sections'], $this->answers);

        if ($this->selectedTemplateId) {
            ExamAttempt::create([
                'exam_template_id' => $this->selectedTemplateId,
                'user_id' => Auth::id(),
                'answers' => $this->answers,
                'result' => $this->result,
                'started_at' => now(),
                'submitted_at' => now(),
            ]);
        }
    }

    public function resetTimer(): void
    {
        $this->started = false;
        $this->remainingSeconds = max((int) ($this->template['sections'][$this->activeSection]['minutes'] ?? 0), 1) * 60;
    }

    /**
     * @return array<string, string>
     */
    public function elementTypes(): array
    {
        return [
            'text' => 'Text / Lesetext',
            'image' => 'Bildplatzhalter',
            'audio' => 'Audioquelle',
            'multiple_choice' => 'Multiple Choice',
            'matching' => 'Zuordnung',
            'fill_blank' => 'Lückentext',
            'inline_gap' => 'Lückentext (inline)',
            'interactive_text' => 'Interaktiver Text',
            'true_false' => 'Richtig / Falsch',
            'writing' => 'Schreibfeld',
            'speaking' => 'Sprechen (KI)',
        ];
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

    /**
     * @param  array<string, mixed>  $element
     * @return list<string>
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

    private function ensurePresets(): void
    {
        foreach (TelcTemplateFactory::presets() as $preset) {
            ExamTemplate::updateOrCreate(
                ['title' => $preset['title']],
                $preset + ['is_published' => true]
            );
        }
    }

    /**
     * @return array<string, int|float>
     */
    private function metrics(): array
    {
        $today = now()->startOfDay();

        return [
            'students' => User::query()->where('role', 'student')->count(),
            'daily_logged_in' => User::query()->where('role', 'student')->where('last_login_at', '>=', $today)->count(),
            'attempts' => ExamAttempt::query()->count(),
            'average_score' => round((float) ExamAttempt::query()->get()->avg(fn (ExamAttempt $attempt) => $attempt->result['percentage'] ?? 0), 2),
        ];
    }

    /**
     * @return Collection<int, array{student: User, attempts: int<0, max>, average: float, latest_score: float|null, latest_exam: string|null}>
     */
    private function performanceRows(): Collection
    {
        /** @phpstan-ignore return.type (covariant template issue) */
        return User::query()
            ->where('role', 'student')
            ->with(['attempts' => fn ($query) => $query->latest()->limit(20), 'attempts.examTemplate'])
            ->latest('last_login_at')
            ->limit(20)
            ->get()
            ->map(function (User $student): array {
                $attempts = $student->attempts;
                $latest = $attempts->first();

                return [
                    'student' => $student,
                    'attempts' => $attempts->count(),
                    'average' => round((float) $attempts->avg(fn (ExamAttempt $attempt) => $attempt->result['percentage'] ?? 0), 2),
                    'latest_score' => $latest?->result['percentage'] ?? null,
                    'latest_exam' => $latest?->examTemplate?->level,
                ];
            });
    }

    /**
     * @param  list<mixed>  $items
     * @return list<mixed>
     */
    private function move(array $items, int $from, int $to): array
    {
        if (! isset($items[$from]) || $to < 0 || $to >= count($items)) {
            return $items;
        }

        $item = $items[$from];
        array_splice($items, $from, 1);
        array_splice($items, $to, 0, [$item]);

        return $items;
    }
}
