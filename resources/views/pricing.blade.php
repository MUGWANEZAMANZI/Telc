<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erfolg — Prüfungsvorbereitung & Tarife</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --telc-ink: #1a1a1a;
            --telc-accent: #d8232a;
            --telc-bg: #f9f9fb;
            --telc-paper: #ffffff;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--telc-bg);
            color: var(--telc-ink);
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        .pricing-landing {
            max-width: 1100px;
            margin: 0 auto;
            padding: 5rem 1.5rem;
            text-align: center;
        }

        .pricing-header h1 {
            font-family: 'Playfair Display', serif;
            font-size: 3.2rem;
            color: var(--telc-ink);
            margin: 0 0 1rem;
            line-height: 1.15;
        }

        .pricing-header p {
            font-size: 1.15rem;
            color: #666666;
            max-width: 600px;
            margin: 0 auto 4rem;
        }

        .pricing-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 2.2rem;
            align-items: stretch;
            margin-bottom: 4rem;
        }

        .pricing-card {
            background: var(--telc-paper);
            border: 2px solid #e5e5e5;
            border-radius: 12px;
            padding: 3rem 2rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.3s ease;
            position: relative;
            text-align: left;
        }

        .pricing-card.premium-tier {
            border-color: var(--telc-ink);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .pricing-card.premium-tier::before {
            content: "BELIEBTESTER TARIF";
            position: absolute;
            top: -12px;
            left: 50%;
            transform: translateX(-50%);
            background: var(--telc-ink);
            color: var(--telc-paper);
            font-size: 0.7rem;
            font-weight: 800;
            padding: 0.35rem 0.85rem;
            border-radius: 99px;
            letter-spacing: 0.1em;
        }

        .pricing-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            margin: 0 0 0.5rem;
            color: var(--telc-ink);
        }

        .pricing-desc {
            font-size: 0.9rem;
            color: #666666;
            margin: 0 0 2rem;
            min-height: 2.5rem;
        }

        .pricing-amount {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--telc-ink);
            margin-bottom: 2rem;
            display: flex;
            align-items: baseline;
            gap: 0.25rem;
        }

        .pricing-amount span.currency {
            font-size: 1.5rem;
            font-weight: 600;
        }

        .pricing-amount span.interval {
            font-size: 0.95rem;
            color: #666666;
            font-weight: 500;
        }

        .pricing-features {
            list-style: none;
            padding: 0;
            margin: 0 0 2.5rem;
            border-top: 1px solid #eeeeee;
            padding-top: 1.5rem;
            flex-grow: 1;
        }

        .pricing-features li {
            font-size: 0.92rem;
            color: #444444;
            margin-bottom: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .pricing-features li::before {
            content: "✓";
            color: #137333;
            font-weight: 800;
        }

        .telc-button {
            display: block;
            text-align: center;
            text-decoration: none;
            background: #ffffff;
            border: 2px solid var(--telc-ink);
            color: var(--telc-ink);
            padding: 0.9rem 1.5rem;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .telc-button.primary {
            background: var(--telc-ink);
            color: var(--telc-paper);
        }

        .telc-button:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }

        .coming-soon-badge {
            background: #f2f4f7;
            color: #475467;
            border: 1px solid #d0d5dd;
            pointer-events: none;
            opacity: 0.7;
            text-align: center;
            text-decoration: none;
            padding: 0.9rem 1.5rem;
            border-radius: 6px;
            font-weight: 700;
            font-size: 0.95rem;
        }

        .pricing-footer {
            margin-top: 5rem;
            border-top: 1px solid #e5e5e5;
            padding-top: 3rem;
            color: #666666;
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
    <div class="pricing-landing">
        <div class="pricing-header">
            <h1>Einfache, transparente Tarife</h1>
            <p>Schalten Sie originalgetreue Prüfungssimulationen frei und bereiten Sie sich optimal auf Goethe & telc Zertifikate vor.</p>
        </div>

        <div class="pricing-grid">
            @php $plans = \App\Models\Plan::query()->where('is_active', true)->orderBy('price')->get(); @endphp
            @forelse ($plans as $plan)
                @php
                    $isPremium = $plan->stripe_price_id === 'price_premium_monthly';
                    $isStripe = $plan->stripe_price_id === 'price_premium_unlimited';
                @endphp
                <article class="pricing-card {{ $isPremium ? 'premium-tier' : '' }}">
                    <div>
                        <h2>{{ $plan->name }}</h2>
                        <p class="pricing-desc">{{ $plan->description }}</p>
                        
                        <div class="pricing-amount">
                            <span class="price">{{ number_format($plan->price, 0) }}</span>
                            <span class="currency">€</span>
                            <span class="interval">
                                @if($plan->interval === 'month')
                                    / Monat
                                @else
                                    / einmalig
                                @endif
                            </span>
                        </div>

                        @if ($plan->features)
                            <ul class="pricing-features">
                                @foreach ((array) $plan->features as $feature)
                                    <li>{{ $feature }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    @if ($isStripe)
                        <button type="button" class="coming-soon-badge">Stripe (In Kürze)</button>
                    @else
                        @auth
                            <a href="{{ route('checkout', ['plan_id' => $plan->id]) }}" class="telc-button primary">Mit PayPal bezahlen</a>
                        @else
                            <a href="{{ route('register') }}" class="telc-button primary">Konto erstellen</a>
                        @endauth
                    @endif
                </article>
            @empty
                <div style="background: #ffffff; border: 2px solid #e5e5e5; border-radius: 8px; padding: 3rem; grid-column: 1 / -1;">
                    <p style="color: #666666;">Aktuell sind keine Preispläne verfügbar.</p>
                </div>
            @endforelse
        </div>

        <div class="pricing-footer">
            <p>© {{ date('Y') }} Erfolg. Alle Preise inkl. gesetzlicher MwSt. PayPal-Zahlungen werden direkt über unsere sichere PayPal.Me-Integration abgewickelt.</p>
        </div>
    </div>
</body>
</html>
