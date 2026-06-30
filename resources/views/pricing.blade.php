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

                    @auth
                        @if ($plan->interval === 'month')
                            <button type="button"
                                    onclick="openPaymentModal({{ $plan->id }}, '{{ $plan->paypal_plan_id ? 'both' : 'stripe' }}')"
                                    class="telc-button primary">
                                Monatlich abonnieren
                            </button>
                        @else
                            <a href="{{ route('checkout', ['plan_id' => $plan->id]) }}"
                               class="telc-button primary">
                                Jetzt freischalten
                            </a>
                        @endif
                    @else
                        <a href="{{ route('register') }}" class="telc-button primary">Konto erstellen</a>
                    @endauth
                </article>
            @empty
                <div style="background: #ffffff; border: 2px solid #e5e5e5; border-radius: 8px; padding: 3rem; grid-column: 1 / -1;">
                    <p style="color: #666666;">Aktuell sind keine Preispläne verfügbar.</p>
                </div>
            @endforelse
        </div>

        <div class="pricing-footer">
            <p>© {{ date('Y') }} Erfolg. Alle Preise inkl. gesetzlicher MwSt. Zahlungen werden sicher über Stripe und PayPal abgewickelt.</p>
        </div>
    </div>

    {{-- Payment Method Modal --}}
    <div id="paymentModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
        <div style="background: #ffffff; padding: 2.5rem; max-width: 480px; width: 90%; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-left: 5px solid #d8232a; font-family: 'Plus Jakarta Sans', sans-serif; text-align: left;">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin: 0 0 0.5rem; color: #1a1a1a;">Zahlungsmethode wählen</h3>
            <p style="color: #666666; font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6;">Bitte wählen Sie eine Zahlungsmethode für Ihr Abonnement.</p>

            <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 2rem;">
                <a id="stripeBtn" href="#" style="display: flex; align-items: center; justify-content: space-between; border: 2px solid #1a1a1a; padding: 1rem; border-radius: 4px; background: #fafafa; cursor: pointer; transition: all 0.2s; text-align: left; width: 100%; text-decoration: none; color: inherit; box-sizing: border-box;">
                    <strong style="font-size: 0.95rem; color: #1a1a1a;">Mit Kreditkarte (Stripe) zahlen</strong>
                    <span style="font-size: 0.85rem; color: #d8232a; font-weight: 600;">Weiter</span>
                </a>
                <a id="paypalBtn" href="#" style="display: flex; align-items: center; justify-content: space-between; border: 2px solid #1a1a1a; padding: 1rem; border-radius: 4px; background: #fafafa; cursor: pointer; transition: all 0.2s; text-align: left; width: 100%; text-decoration: none; color: inherit; box-sizing: border-box;">
                    <strong style="font-size: 0.95rem; color: #1a1a1a;">Mit PayPal bezahlen</strong>
                    <span style="font-size: 0.85rem; color: #d8232a; font-weight: 600;">Weiter</span>
                </a>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="button" onclick="closePaymentModal()" style="border: none; background: none; padding: 0.5rem 1rem; cursor: pointer; color: #666666; font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">Abbrechen</button>
            </div>
        </div>
    </div>

    <script>
        let selectedPlanId = null;

        function openPaymentModal(planId, available) {
            selectedPlanId = planId;
            document.getElementById('stripeBtn').href = '{{ route('checkout') }}?plan_id=' + planId;

            const paypalBtn = document.getElementById('paypalBtn');
            if (available === 'both') {
                paypalBtn.href = '{{ route('paypal.checkout', '__PLAN_ID__') }}'.replace('__PLAN_ID__', planId);
                paypalBtn.style.display = 'flex';
            } else {
                paypalBtn.style.display = 'none';
            }

            document.getElementById('paymentModal').style.display = 'flex';
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
        }

        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });
    </script>
</body>
</html>
