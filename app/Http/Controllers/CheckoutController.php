<?php

namespace App\Http\Controllers;

use App\Models\ExamTemplate;
use App\Models\Plan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function checkout(Request $request): RedirectResponse
    {
        $plan = Plan::query()->findOrFail($request->query('plan_id'));

        if ($plan->interval === 'month') {
            $checkout = $request->user()
                ->newSubscription('default', $plan->stripe_price_id)
                ->checkout([
                    'success_url' => route('billing'),
                    'cancel_url' => route('pricing'),
                    'metadata' => [
                        'plan_id' => $plan->id,
                    ],
                ]);

            return redirect()->away($checkout->url);
        }

        $checkout = $request->user()->checkout(
            [$plan->stripe_price_id => 1],
            [
                'success_url' => route('billing'),
                'cancel_url' => route('pricing'),
                'metadata' => [
                    'plan_id' => $plan->id,
                ],
            ],
        );

        return redirect()->away($checkout->url);
    }

    public function examCheckout(Request $request, ExamTemplate $examTemplate): RedirectResponse
    {
        $checkout = $request->user()->checkout(
            [$examTemplate->stripe_price_id => 1],
            [
                'success_url' => route('billing'),
                'cancel_url' => route('dashboard'),
                'metadata' => [
                    'exam_template_id' => $examTemplate->id,
                ],
            ],
        );

        return redirect()->away($checkout->url);
    }
}
