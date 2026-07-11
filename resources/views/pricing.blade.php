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

        .duration-selector {
            display: flex;
            gap: 0.5rem;
            margin-bottom: 1.5rem;
        }

        .duration-option {
            flex: 1;
            padding: 0.6rem 0.5rem;
            border: 2px solid #e5e5e5;
            border-radius: 6px;
            background: #fafafa;
            cursor: pointer;
            text-align: center;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .duration-option.selected {
            border-color: var(--telc-ink);
            background: var(--telc-ink);
            color: #fff;
        }

        .duration-option .price-tag {
            display: block;
            font-size: 0.75rem;
            font-weight: 400;
            margin-top: 0.15rem;
            opacity: 0.7;
        }

        .duration-option.selected .price-tag {
            opacity: 0.9;
        }

        .pricing-footer {
            margin-top: 5rem;
            border-top: 1px solid #e5e5e5;
            padding-top: 3rem;
            color: #666666;
            font-size: 0.85rem;
        }

        #revolut-pay-button {
            min-height: 48px;
        }

        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #e5e5e5;
            border-top-color: var(--telc-ink);
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
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
                    $isPremium = $plan->name === 'Monatliches Training (Standard)';
                    $isOneOff = $plan->interval !== 'month';
                @endphp
                <article class="pricing-card {{ $isPremium ? 'premium-tier' : '' }}">
                    <div>
                        <h2>{{ $plan->name }}</h2>
                        <p class="pricing-desc">{{ $plan->description }}</p>

                        <div class="pricing-amount">
                            <span class="price">
                                @if($plan->interval === 'month')
                                    6,98
                                @else
                                    {{ number_format($plan->price, 0) }}
                                @endif
                            </span>
                            <span class="currency">€</span>
                            <span class="interval">
                                @if($isOneOff)
                                    / einmalig
                                @else
                                    / Monat
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
                        @if ($isOneOff)
                            <button type="button"
                                    onclick="openPaymentModal({{ $plan->id }}, 'plan', null)"
                                    class="telc-button primary">
                                Jetzt freischalten
                            </button>
                        @else
                            <div class="duration-selector" data-plan-id="{{ $plan->id }}">
                                <div class="duration-option selected" data-months="1" onclick="selectDuration(this, {{ $plan->id }}, 6.98)">
                                    1 Monat
                                    <span class="price-tag">6,98 €</span>
                                </div>
                                <div class="duration-option" data-months="3" onclick="selectDuration(this, {{ $plan->id }}, 19.47)">
                                    3 Monate
                                    <span class="price-tag">19,47 € <small style="font-size:0.65rem; color:#137333;">(-7%)</small></span>
                                </div>
                                <div class="duration-option" data-months="6" onclick="selectDuration(this, {{ $plan->id }}, 36.85)">
                                    6 Monate
                                    <span class="price-tag">36,85 € <small style="font-size:0.65rem; color:#137333;">(-12%)</small></span>
                                </div>
                                <div class="duration-option" data-months="12" onclick="selectDuration(this, {{ $plan->id }}, 59.90)">
                                    12 Monate
                                    <span class="price-tag">59,90 € <small style="font-size:0.65rem; color:#137333;">(Festpreis)</small></span>
                                </div>
                            </div>
                            <button type="button"
                                    onclick="openPaymentModal({{ $plan->id }}, 'plan', getSelectedDuration({{ $plan->id }}))"
                                    class="telc-button primary">
                                Jetzt kaufen
                            </button>
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
            <p>&copy; {{ date('Y') }} Erfolg. Alle Preise inkl. gesetzlicher MwSt. Zahlungen werden sicher über Revolut und PayPal abgewickelt.</p>
        </div>
    </div>

    {{-- Payment Method Modal --}}
    <div id="paymentModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
        <div style="background: #ffffff; padding: 2.5rem; max-width: 480px; width: 90%; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-left: 5px solid #d8232a; font-family: 'Plus Jakarta Sans', sans-serif; text-align: left;">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin: 0 0 0.5rem; color: #1a1a1a;">Zahlungsmethode wählen</h3>
            <p style="color: #666666; font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6;">Bitte wählen Sie eine Zahlungsmethode.</p>

            <div id="revolut-section" style="display: none; flex-direction: column; gap: 0.75rem; margin-bottom: 2rem;">
                <div style="border: 2px solid #1a1a1a; padding: 1rem; border-radius: 4px; background: #fafafa;">
                    <strong style="font-size: 0.95rem; color: #1a1a1a; display: block; margin-bottom: 1rem;">Mit Revolut bezahlen</strong>
                    <div id="revolut-pay-button"></div>
                    <div id="revolut-loading" style="text-align: center; padding: 1rem;">
                        <div class="loading-spinner"></div>
                        <p style="font-size: 0.85rem; color: #666; margin-top: 0.5rem;">Zahlung wird vorbereitet...</p>
                    </div>
                    <div id="revolut-error" style="display: none; color: #d8232a; font-size: 0.85rem; margin-top: 0.5rem;"></div>
                </div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 2rem;">
                <a id="paypalBtn" href="#" style="display: flex; align-items: center; justify-content: space-between; border: 2px solid #1a1a1a; padding: 1rem; border-radius: 4px; background: #fafafa; cursor: pointer; transition: all 0.2s; text-align: left; width: 100%; text-decoration: none; color: inherit; box-sizing: border-box;">
                    <strong style="font-size: 0.95rem; color: #1a1a1a;">Mit PayPal bezahlen</strong>
                    <span style="font-size: 0.85rem; color: #d8232a; font-weight: 600;">Weiter</span>
                </a>
                <button type="button" onclick="initRevolutPay()" style="display: flex; align-items: center; justify-content: space-between; border: 2px solid #1a1a1a; padding: 1rem; border-radius: 4px; background: #fafafa; cursor: pointer; transition: all 0.2s; text-align: left; width: 100%; text-decoration: none; color: inherit; box-sizing: border-box; font-family: inherit; font-size: inherit;">
                    <strong style="font-size: 0.95rem; color: #1a1a1a;">Mit Kreditkarte (Revolut) zahlen</strong>
                    <span style="font-size: 0.85rem; color: #d8232a; font-weight: 600;">Jetzt bezahlen</span>
                </button>
            </div>

            <div style="display: flex; justify-content: flex-end;">
                <button type="button" onclick="closePaymentModal()" style="border: none; background: none; padding: 0.5rem 1rem; cursor: pointer; color: #666666; font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">Abbrechen</button>
            </div>
        </div>
    </div>

    <script>
        let selectedPlanId = null;
        let selectedDuration = 1;
        let selectedItemType = 'plan';

        function selectDuration(el, planId, monthlyPrice) {
            el.parentElement.querySelectorAll('.duration-option').forEach(o => o.classList.remove('selected'));
            el.classList.add('selected');
        }

        function getSelectedDuration(planId) {
            const selector = document.querySelector(`.duration-selector[data-plan-id="${planId}"]`);
            if (! selector) return 1;
            const selected = selector.querySelector('.duration-option.selected');
            return selected ? parseInt(selected.dataset.months) : 1;
        }

        function openPaymentModal(planId, type, duration) {
            selectedPlanId = planId;
            selectedItemType = type || 'plan';
            selectedDuration = duration || getSelectedDuration(planId);

            const paypalUrl = type === 'plan'
                ? '{{ route('paypal.plan.checkout') }}?plan_id=' + planId + '&duration=' + selectedDuration
                : null;

            const paypalBtn = document.getElementById('paypalBtn');
            if (paypalUrl) {
                paypalBtn.href = paypalUrl;
                paypalBtn.style.display = 'flex';
            } else {
                paypalBtn.style.display = 'none';
            }

            document.getElementById('revolut-section').style.display = 'none';
            document.getElementById('revolut-pay-button').innerHTML = '';
            document.getElementById('revolut-error').style.display = 'none';

            document.getElementById('paymentModal').style.display = 'flex';
        }

        function closePaymentModal() {
            document.getElementById('paymentModal').style.display = 'none';
            document.getElementById('revolut-section').style.display = 'none';
        }

        async function initRevolutPay() {
            const revolutSection = document.getElementById('revolut-section');
            const revolutButton = document.getElementById('revolut-pay-button');
            const revolutLoading = document.getElementById('revolut-loading');
            const revolutError = document.getElementById('revolut-error');

            revolutSection.style.display = 'flex';
            revolutLoading.style.display = 'block';
            revolutError.style.display = 'none';

            try {
                const response = await fetch('{{ route('revolut.create') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        type: selectedItemType,
                        id: selectedPlanId,
                        duration_months: selectedDuration,
                    })
                });

                const data = await response.json();

                if (data.error) {
                    revolutError.textContent = data.error;
                    revolutError.style.display = 'block';
                    revolutLoading.style.display = 'none';
                    return;
                }

                revolutLoading.style.display = 'none';

                // Load Revolut SDK and mount the button
                if (typeof RevolutCheckout === 'undefined') {
                    await new Promise((resolve, reject) => {
                        const script = document.createElement('script');
                        script.src = 'https://merchant.revolut.com/embed.js';
                        script.onload = resolve;
                        script.onerror = () => {
                            revolutError.textContent = 'Revolut SDK konnte nicht geladen werden.';
                            revolutError.style.display = 'block';
                            reject();
                        };
                        document.head.appendChild(script);
                    });
                }

                const rc = await RevolutCheckout(data.public_id, data.mode);
                const revolutPay = rc.revolutPay({
                    target: revolutButton,
                    onSuccess() {
                        window.location.href = '{{ route('revolut.success') }}';
                    },
                    onError(error) {
                        revolutError.textContent = 'Zahlung fehlgeschlagen: ' + (error.message || 'Unbekannter Fehler');
                        revolutError.style.display = 'block';
                    },
                    onCancel() {
                        revolutSection.style.display = 'none';
                        revolutButton.innerHTML = '';
                    }
                });
            } catch (e) {
                revolutError.textContent = 'Zahlungsdienst nicht verfügbar. Bitte versuchen Sie es später erneut.';
                revolutError.style.display = 'block';
                revolutLoading.style.display = 'none';
            }
        }

        document.getElementById('paymentModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePaymentModal();
            }
        });
    </script>
</body>
</html>
