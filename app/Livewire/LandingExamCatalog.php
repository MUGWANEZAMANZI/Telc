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
        $this->selectedLevel = $levels[0] ?? 'B1';
    }

    public function selectProvider(string $provider): void
    {
        $this->selectedProvider = $provider;
        $levels = $this->getLevels();
        if (! in_array($this->selectedLevel, $levels)) {
            $this->selectedLevel = $levels[0] ?? 'B1';
        }
    }

    public function selectLevel(string $level): void
    {
        $this->selectedLevel = $level;
    }

    public function selectExam(int $examId): void
    {
        $exam = ExamTemplate::findOrFail($examId);

        // Find index of this exam to see if it is free/partially free
        $all = ExamTemplate::where('is_published', true)
            ->where('exam_provider', $exam->exam_provider)
            ->where('level', $exam->level)
            ->orderBy('id')
            ->pluck('id')
            ->toArray();

        $index = array_search($exam->id, $all);

        if ($index === 0 || $index === 1) {
            // Free or partially free
            if (Auth::check()) {
                $this->redirect(route('dashboard').'?start_exam='.$exam->id);
            } else {
                $this->redirect(route('free.exam', ['templateId' => $exam->id]));
            }
        } else {
            // Paid
            if (Auth::check()) {
                session()->flash('trigger-enroll', $exam->id);
                $this->redirect(route('dashboard'));
            } else {
                session(['intended_enroll_exam' => $exam->id]);
                $this->redirect(route('register'));
            }
        }
    }

    /** @return list<string> */
    public function getLevels(): array
    {
        $order = ['A1' => 0, 'A2' => 1, 'B1' => 2, 'B2' => 3, 'C1' => 4, 'C2' => 5];

        return array_values(ExamTemplate::where('is_published', true)
            ->where('exam_provider', $this->selectedProvider)
            ->whereIn('level', ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'])
            ->pluck('level')
            ->unique()
            ->sortBy(fn ($l) => $order[$l] ?? 99)
            ->toArray());
    }

    public function render(): View
    {
        $allExams = ExamTemplate::where('is_published', true)
            ->where('exam_provider', $this->selectedProvider)
            ->where('level', $this->selectedLevel)
            ->orderBy('id')
            ->get();

        $freeExams = collect();
        $paidExams = collect();

        foreach ($allExams as $index => $exam) {
            if ($index === 0) {
                // First exam: 100% Free
                $exam->price_label = 'KOSTENLOS';
                $exam->is_partially_free = false;
                $exam->is_actually_free = true;
                $freeExams->push($exam);
            } elseif ($index === 1) {
                // Second exam: 2 sections free
                $exam->price_label = '2 TEILE KOSTENLOS';
                $exam->is_partially_free = true;
                $exam->is_actually_free = true;
                $freeExams->push($exam);
            } else {
                // Paid
                $exam->price_label = '€'.number_format($exam->price ?: 5.00, 2);
                $exam->is_partially_free = false;
                $exam->is_actually_free = false;
                $paidExams->push($exam);
            }
        }

        return view('livewire.landing-exam-catalog', [
            'levels' => $this->getLevels(),
            'freeExams' => $freeExams,
            'paidExams' => $paidExams,
            'isAuth' => Auth::check(),
        ]);
    }
}
