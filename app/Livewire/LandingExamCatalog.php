<?php

namespace App\Livewire;

use App\Models\ExamTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class LandingExamCatalog extends Component
{
    public string $selectedProvider = 'telc';

    public ?string $selectedLevel = null;

    public int $visibleFreeCount = 1;

    public function mount(): void
    {
        $levels = $this->getLevels();
        $this->selectedLevel = $levels[0] ?? null;
    }

    public function selectProvider(string $provider): void
    {
        $this->selectedProvider = $provider;
        $levels = $this->getLevels();
        if (! in_array($this->selectedLevel, $levels)) {
            $this->selectedLevel = $levels[0] ?? null;
        }
    }

    public function selectLevel(string $level): void
    {
        $this->selectedLevel = $level;
    }

    public function startFreeExam(int $templateId): void
    {
        $this->redirect(route('free.exam', ['template' => $templateId]));
    }

    public function promptRegister(): void
    {
        $this->redirect(route('register'));
    }

    /** @return list<string> */
    public function getLevels(): array
    {
        $order = ['B1' => 0, 'B2' => 1, 'C1' => 2];

        return array_values(ExamTemplate::where('is_published', true)
            ->where('exam_provider', $this->selectedProvider)
            ->whereIn('level', ['B1', 'B2', 'C1'])
            ->pluck('level')
            ->unique()
            ->sortBy(fn ($l) => $order[$l] ?? 99)
            ->toArray());
    }

    public function render(): View
    {
        $freeExams = ExamTemplate::where('is_published', true)
            ->where('exam_provider', $this->selectedProvider)
            ->where('level', $this->selectedLevel)
            ->where(fn ($q) => $q->whereNull('price')->orWhere('price', '<=', 0))
            ->limit($this->visibleFreeCount)
            ->get();

        $paidExams = ExamTemplate::where('is_published', true)
            ->where('exam_provider', $this->selectedProvider)
            ->where('level', $this->selectedLevel)
            ->where('price', '>', 0)
            ->orderBy('price')
            ->get();

        $totalFree = ExamTemplate::where('is_published', true)
            ->where('exam_provider', $this->selectedProvider)
            ->where('level', $this->selectedLevel)
            ->where(fn ($q) => $q->whereNull('price')->orWhere('price', '<=', 0))
            ->count();

        return view('livewire.landing-exam-catalog', [
            'levels' => $this->getLevels(),
            'freeExams' => $freeExams,
            'paidExams' => $paidExams,
            'hasMoreFree' => $totalFree > $this->visibleFreeCount,
            'totalFreeCount' => $totalFree,
            'isAuth' => Auth::check(),
        ]);
    }
}
