<div>
    <div class="telc-builder-head">
        <div>
            <h2>{{ __('Billing') }}</h2>
            <p>Manage your subscription and billing information.</p>
        </div>
    </div>

    @if ($subscription)
        <div class="telc-panel">
            <h2>Current Plan</h2>
            <div class="telc-plan-card">
                <div>
                    <strong>{{ $subscription->plan->name }}</strong>
                    <span>{{ number_format($subscription->plan->price, 2) }} {{ strtoupper($subscription->plan->currency) }} / {{ $subscription->plan->interval }}</span>
                    @if ($subscription->plan->features)
                        <ul>
                            @foreach ((array) $subscription->plan->features as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <span @class(['telc-pill', 'green' => $subscription->isActive()])>{{ $subscription->stripe_status }}</span>
            </div>
        </div>
    @else
        <div class="telc-panel">
            <h2>{{ __('Subscribe') }}</h2>
            <p>Choose a plan to get started.</p>
            <div class="telc-plan-grid">
                @foreach ($plans as $plan)
                    <article class="telc-plan-card">
                        <h3>{{ $plan->name }}</h3>
                        <div class="telc-plan-price">{{ number_format($plan->price, 2) }} {{ strtoupper($plan->currency) }}</div>
                        <div class="telc-plan-interval">/ {{ $plan->interval }}</div>
                        @if ($plan->description)
                            <p>{{ $plan->description }}</p>
                        @endif
                        @if ($plan->features)
                            <ul>
                                @foreach ((array) $plan->features as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        @endif
                        <a href="{{ route('checkout', ['plan_id' => $plan->id]) }}" class="telc-button primary">{{ __('Subscribe') }}</a>
                    </article>
                @endforeach
            </div>
        </div>
    @endif
</div>
