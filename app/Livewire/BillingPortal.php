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

        return view('livewire.billing-portal', [
            'plans' => Plan::query()->where('is_active', true)->orderBy('price')->get(),
            'subscription' => Subscription::query()
                ->where('user_id', $user->id)
                ->where('stripe_status', 'active')
                ->with('plan')
                ->latest()
                ->first(),
        ]);
    }
}
