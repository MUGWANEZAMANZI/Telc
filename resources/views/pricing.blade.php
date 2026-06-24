<x-app-layout>
    <div class="pricing-page">
        <div class="pricing-header">
            <h1>{{ __('Pricing') }}</h1>
            <p>{{ __('Try 2 exams free, then subscribe') }}</p>
        </div>

        <div class="pricing-grid">
            @php $plans = \App\Models\Plan::query()->where('is_active', true)->orderBy('price')->get(); @endphp
            @forelse ($plans as $plan)
                <article class="pricing-card">
                    <h2>{{ $plan->name }}</h2>
                    <div class="pricing-amount">
                        <span class="price">{{ number_format($plan->price, 2) }}</span>
                        <span class="currency">{{ strtoupper($plan->currency) }}</span>
                        <span class="interval">/ {{ $plan->interval }}</span>
                    </div>
                    @if ($plan->description)
                        <p class="pricing-desc">{{ $plan->description }}</p>
                    @endif
                    @if ($plan->features)
                        <ul class="pricing-features">
                            @foreach ((array) $plan->features as $feature)
                                <li>{{ $feature }}</li>
                            @endforeach
                        </ul>
                    @endif
                    @auth
                        <a href="{{ route('checkout', ['plan_id' => $plan->id]) }}" class="telc-button primary wide">{{ __('Subscribe') }}</a>
                    @else
                        <a href="{{ route('register') }}" class="telc-button primary wide">{{ __('Create account') }}</a>
                    @endauth
                </article>
            @empty
                <div class="telc-panel">
                    <p>No pricing plans are available yet.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
