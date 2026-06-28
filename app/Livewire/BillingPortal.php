<?php

namespace App\Livewire;

use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BillingPortal extends Component
{
    public function render()
    {
        $user = Auth::user();

        $history = collect();
        $expiring = collect();

        if ($user && $user->isAdmin()) {
            $history = Subscription::query()
                ->with(['user', 'plan'])
                ->latest()
                ->get();

            $expiring = Subscription::query()
                ->where('stripe_status', 'active')
                ->whereNotNull('ends_at')
                ->where('ends_at', '>=', now())
                ->where('ends_at', '<=', now()->addDays(7))
                ->with(['user', 'plan'])
                ->get();
        }

        return view('livewire.billing-portal', [
            'plans' => Plan::query()->where('is_active', true)->orderBy('price')->get(),
            'subscription' => Subscription::query()
                ->where('user_id', $user->id)
                ->where('stripe_status', 'active')
                ->with('plan')
                ->latest()
                ->first(),
            'history' => $history,
            'expiring' => $expiring,
            'isAdmin' => $user && $user->isAdmin(),
        ]);
    }
}
