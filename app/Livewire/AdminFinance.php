<?php

namespace App\Livewire;

use App\Models\ExamEnrollment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Livewire\Component;
use Illuminate\Support\Facades\Config;

class AdminFinance extends Component
{
    public function mount(): void
    {
        abort_unless(Auth::user()?->isOwner(), 403);
    }

    public function render(): View
    {
        $totalExamRevenue = (float) ExamEnrollment::query()
            ->whereIn('payment_status', ['paid', 'manual_enrolled'])
            ->join('exam_templates', 'exam_enrollments.exam_template_id', '=', 'exam_templates.id')
            ->whereNotNull('exam_templates.price')
            ->sum(DB::raw('exam_templates.price'));

        $activeSubscriptions = Subscription::query()
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->count();

        $subscriptionRevenue = (float) Subscription::query()
            ->where('status', 'active')
            ->where(function ($q) {
                $q->whereNull('ends_at')->orWhere('ends_at', '>', now());
            })
            ->sum(DB::raw('COALESCE(price_paid, 0)'));

        $totalRevenue = $totalExamRevenue + $subscriptionRevenue;

        $pendingPayments = ExamEnrollment::query()
            ->where('status', 'pending')
            ->with(['user', 'examTemplate'])
            ->latest()
            ->get();

        $transactions = ExamEnrollment::query()
            ->whereIn('payment_status', ['paid', 'manual_enrolled', 'subscribed'])
            ->with(['user', 'examTemplate'])
            ->latest()
            ->get();

        $driver = Config::get('database.default');
        $dateFn = $driver === 'mysql'
            ? "DATE_FORMAT(enrolled_at, '%Y-%m')"
            : "strftime('%Y-%m', enrolled_at)";

        $monthlyBreakdown = ExamEnrollment::query()
            ->whereIn('payment_status', ['paid', 'manual_enrolled'])
            ->join('exam_templates', 'exam_enrollments.exam_template_id', '=', 'exam_templates.id')
            ->whereNotNull('exam_templates.price')
            ->select(
                DB::raw("{$dateFn} as month"),
                DB::raw('SUM(exam_templates.price) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->whereNotNull('enrolled_at')
            ->groupBy(DB::raw("{$dateFn}"))
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('livewire.admin-finance', [
            'totalExamRevenue' => $totalExamRevenue,
            'activeSubscriptions' => $activeSubscriptions,
            'subscriptionRevenue' => $subscriptionRevenue,
            'totalRevenue' => $totalRevenue,
            'pendingPayments' => $pendingPayments,
            'transactions' => $transactions,
            'monthlyBreakdown' => $monthlyBreakdown,
            'allPlans' => Plan::query()->orderBy('price')->get(),
            'allUsers' => User::query()->orderBy('name')->get(),
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
        if ($enrollment->receipt_path) {
            Storage::disk('public')->delete($enrollment->receipt_path);
        }
        $enrollment->delete();
        session()->flash('payment_message', 'Zahlung abgelehnt und Antrag gelöscht.');
    }
}
