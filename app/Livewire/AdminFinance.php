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

class AdminFinance extends Component
{
    public ?int $selectedUserForTrial = null;

    public ?int $selectedPlanForTrial = null;

    public ?string $successMessage = null;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isOwner() || Auth::user()?->isInvestor(), 403);
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

        $driver = DB::connection()->getDriverName();
        $dateFn = in_array($driver, ['mysql', 'mariadb'], true)
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

        $totalVisits = (int) User::sum('login_count');

        $totalPaymentsCount = ExamEnrollment::whereIn('payment_status', ['paid', 'manual_enrolled'])->count()
            + Subscription::where('status', 'active')->where('price_paid', '>', 0)->count();

        $totalTrialsCount = Subscription::where('provider', 'manual_admin')->count();

        $paidUsersCount = ExamEnrollment::whereIn('payment_status', ['paid', 'manual_enrolled'])->pluck('user_id')
            ->merge(Subscription::where('status', 'active')->where('price_paid', '>', 0)->pluck('user_id'))
            ->unique()
            ->count();

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
            'totalVisits' => $totalVisits,
            'totalPaymentsCount' => $totalPaymentsCount,
            'totalTrialsCount' => $totalTrialsCount,
            'paidUsersCount' => $paidUsersCount,
        ]);
    }

    public function approvePayment(int $enrollmentId): void
    {
        abort_unless(Auth::user()?->isOwner(), 403);
        $this->successMessage = null;
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
        abort_unless(Auth::user()?->isOwner(), 403);
        $this->successMessage = null;
        $enrollment = ExamEnrollment::findOrFail($enrollmentId);
        if ($enrollment->receipt_path) {
            Storage::disk('public')->delete($enrollment->receipt_path);
        }
        $enrollment->delete();
        session()->flash('payment_message', 'Zahlung abgelehnt und Antrag gelöscht.');
    }

    public function createFreeTrial(): void
    {
        abort_unless(Auth::user()?->isOwner(), 403);
        $this->successMessage = null;
        $this->validate([
            'selectedUserForTrial' => 'required|exists:users,id',
            'selectedPlanForTrial' => 'required|exists:plans,id',
        ]);

        // Cancel any existing active subscriptions for this user
        Subscription::query()
            ->where('user_id', $this->selectedUserForTrial)
            ->where('status', 'active')
            ->update(['status' => 'cancelled']);

        // Create the 30-day manual/free-trial subscription
        Subscription::create([
            'user_id' => $this->selectedUserForTrial,
            'plan_id' => $this->selectedPlanForTrial,
            'type' => 'plan',
            'provider' => 'manual_admin',
            'provider_order_id' => 'trial_'.uniqid(),
            'status' => 'active',
            'price_paid' => 0.00,
            'duration_months' => 1,
            'starts_at' => now(),
            'ends_at' => now()->addDays(30),
        ]);

        $user = User::findOrFail($this->selectedUserForTrial);
        $plan = Plan::findOrFail($this->selectedPlanForTrial);

        $this->successMessage = "30-tägiges kostenloses Probeabo ({$plan->name}) für {$user->name} wurde erfolgreich aktiviert.";
        session()->flash('payment_message', $this->successMessage);

        $this->selectedUserForTrial = null;
        $this->selectedPlanForTrial = null;
    }
}
