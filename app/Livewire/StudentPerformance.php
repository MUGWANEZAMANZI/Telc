<?php

namespace App\Livewire;

use App\Models\ExamAttempt;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Student Performance')]
class StudentPerformance extends Component
{
    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);
    }

    public function render(): \Illuminate\View\View
    {
        $today = now()->startOfDay();

        // 1. Registered students aggregation
        $performanceRows = User::query()
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

        // 2. Guest/Anonymous attempts (unauthenticated users)
        $guestAttempts = ExamAttempt::query()
            ->whereNull('user_id')
            ->with('examTemplate')
            ->latest()
            ->limit(30)
            ->get();

        return view('livewire.student-performance', [
            'performanceRows' => $performanceRows,
            'guestAttempts' => $guestAttempts,
            'metrics' => [
                'students' => User::query()->where('role', 'student')->count(),
                'guest_attempts' => ExamAttempt::query()->whereNull('user_id')->count(),
                'student_attempts' => ExamAttempt::query()->whereNotNull('user_id')->count(),
                'total_attempts' => ExamAttempt::query()->count(),
            ],
        ]);
    }
}
