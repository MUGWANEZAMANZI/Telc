<?php

namespace App\Livewire;

use App\Models\ExamEnrollment;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class AdminFinance extends Component
{
    public function mount(): void
    {
        abort_unless(Auth::user()?->isOwner(), 403);
    }

    public function render()
    {
        $totalExamRevenue = (float) ExamEnrollment::query()
            ->whereIn('payment_status', ['paid', 'manual_enrolled'])
            ->join('exam_templates', 'exam_enrollments.exam_template_id', '=', 'exam_templates.id')
            ->whereNotNull('exam_templates.price')
            ->sum(DB::raw('exam_templates.price'));

        $activeSubscriptions = Subscription::query()->where('stripe_status', 'active')->count();

        $subscriptionMrr = (float) Subscription::query()
            ->where('stripe_status', 'active')
            ->join('plans', 'subscriptions.plan_id', '=', 'plans.id')
            ->where('plans.interval', 'month')
            ->sum(DB::raw('plans.price'));

        $totalRevenue = $totalExamRevenue + $subscriptionMrr;

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

        $monthlyBreakdown = ExamEnrollment::query()
            ->whereIn('payment_status', ['paid', 'manual_enrolled'])
            ->join('exam_templates', 'exam_enrollments.exam_template_id', '=', 'exam_templates.id')
            ->whereNotNull('exam_templates.price')
            ->select(
                DB::raw("strftime('%Y-%m', enrolled_at) as month"),
                DB::raw('SUM(exam_templates.price) as revenue'),
                DB::raw('COUNT(*) as count')
            )
            ->whereNotNull('enrolled_at')
            ->groupBy(DB::raw("strftime('%Y-%m', enrolled_at)"))
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return view('livewire.admin-finance', [
            'totalExamRevenue' => $totalExamRevenue,
            'activeSubscriptions' => $activeSubscriptions,
            'subscriptionMrr' => $subscriptionMrr,
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
