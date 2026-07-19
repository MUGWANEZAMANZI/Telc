<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Erfolg — Prüfungsvorbereitung & Tarife</title>
    <link rel="icon" href="{{ asset('logo.jpeg') }}" type="image/jpeg">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .font-serif {
            font-family: 'Playfair Display', serif;
        }
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #e5e5e5;
            border-top-color: #1a1a1a;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-zinc-50 via-zinc-100 to-zinc-200 text-zinc-900 min-h-screen py-4 px-4 md:px-6">
    <div class="max-w-[1100px] mx-auto">
        
        {{-- Header Section --}}
        <div class="text-center max-w-3xl mx-auto mb-6">
            <span class="text-[0.65rem] font-bold tracking-widest text-[#d8232a] uppercase bg-red-50 px-2 py-0.5 rounded-full border border-red-100 inline-block mb-2">Tarife & Optionen</span>
            <h1 class="font-serif text-2xl md:text-3xl lg:text-4xl text-zinc-900 mb-2 font-bold tracking-tight leading-tight">Einfache, transparente Tarife</h1>
            <p class="text-zinc-600 text-xs md:text-sm leading-relaxed">Schalten Sie originalgetreue Prüfungssimulationen frei und bereiten Sie sich optimal auf Goethe & telc Zertifikate vor.</p>
        </div>

        {{-- Pricing Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 items-stretch mb-6">
            @php $plans = \App\Models\Plan::query()->where('is_active', true)->orderBy('price')->get(); @endphp
            @forelse ($plans as $plan)
                @php
                    $isOneOff = $plan->interval !== 'month';
                @endphp
                @if ($isOneOff)
                    <article class="relative flex flex-col justify-between bg-white border border-zinc-200 shadow-md rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                        <div>
                            <h2 class="font-serif text-lg md:text-xl font-bold mb-1 text-zinc-900">{{ $plan->name }}</h2>
                            <p class="text-zinc-500 text-xs mb-4 min-h-[1.5rem]">{{ $plan->description }}</p>

                            <div class="mb-4">
                                <div class="flex items-baseline text-zinc-900 mb-0.5">
                                    <span class="text-2xl md:text-3xl font-black tracking-tight">
                                        {{ number_format($plan->getPriceForDuration(1), 2, ',', '.') }}
                                    </span>
                                    <span class="text-lg font-bold ml-0.5">€</span>
                                    <span class="text-zinc-500 text-xs font-semibold ml-1.5">/ einmalig</span>
                                </div>
                            </div>

                            @if ($plan->features)
                                <ul class="space-y-2 mb-6 border-t border-zinc-100 pt-4 flex-grow">
                                    @foreach ((array) $plan->features as $feature)
                                        <li class="flex items-start text-xs text-zinc-600 leading-normal">
                                            <svg class="h-4 w-4 text-emerald-600 shrink-0 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                            </svg>
                                            <span>{{ $feature }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        @auth
                            <button type="button"
                                    onclick="openPaymentModal({{ $plan->id }}, 'plan', null)"
                                    class="w-full text-center py-2.5 px-4 rounded-lg font-extrabold text-xs transition-all duration-200 border-2 border-zinc-900 bg-zinc-900 text-white hover:bg-zinc-800 shadow-md cursor-pointer">
                                Jetzt freischalten
                            </button>
                        @else
                            <a href="{{ route('register') }}" class="w-full text-center py-2.5 px-4 rounded-lg font-extrabold text-xs transition-all duration-200 border-2 border-zinc-900 bg-zinc-900 text-white hover:bg-zinc-800 shadow-md block no-underline cursor-pointer">Konto erstellen</a>
                        @endauth
                    </article>
                @else
                    @foreach ([1, 3, 6, 12] as $months)
                        @php
                            $isPremium = $months === 3;
                            $pricePerMonth = $plan->getPriceForDuration($months) / $months;
                            $totalPrice = $plan->getPriceForDuration($months);
                        @endphp
                        <article class="relative flex flex-col justify-between bg-white border {{ $isPremium ? 'border-zinc-950 ring-2 ring-zinc-950 shadow-2xl' : 'border-zinc-200 shadow-md' }} rounded-2xl p-6 transition-all duration-300 hover:-translate-y-1 hover:shadow-xl">
                            @if ($isPremium)
                                <div class="absolute -top-3 left-1/2 -translate-x-1/2 bg-zinc-950 text-white text-[0.65rem] font-extrabold px-3 py-0.5 rounded-full tracking-widest uppercase shadow-md">
                                    Beliebtester Tarif
                                </div>
                            @endif

                            <div>
                                <h2 class="font-serif text-lg md:text-xl font-bold mb-1 text-zinc-900">
                                    @if($months === 1)
                                        1 Monat Training
                                    @else
                                        {{ $months }} Monate Training
                                    @endif
                                </h2>
                                <p class="text-zinc-500 text-xs mb-4 min-h-[1.5rem]">
                                    Voller Zugang für {{ $months }} {{ $months === 1 ? 'Monat' : 'Monate' }}
                                </p>

                                <div class="mb-4">
                                    <div class="flex items-baseline text-zinc-900 mb-0.5">
                                        <span class="text-2xl md:text-3xl font-black tracking-tight">
                                            {{ number_format($pricePerMonth, 2, ',', '.') }}
                                        </span>
                                        <span class="text-lg font-bold ml-0.5">€</span>
                                        <span class="text-zinc-500 text-xs font-semibold ml-1.5">/ Monat</span>
                                    </div>
                                    <div class="text-[0.65rem] text-zinc-400 font-semibold">
                                        Gesamtpreis: {{ number_format($totalPrice, 2, ',', '.') }} € (einmalige Abrechnung)
                                    </div>
                                </div>

                                @if ($plan->features)
                                    <ul class="space-y-2 mb-6 border-t border-zinc-100 pt-4 flex-grow">
                                        @foreach ((array) $plan->features as $feature)
                                            <li class="flex items-start text-xs text-zinc-600 leading-normal">
                                                <svg class="h-4 w-4 text-emerald-600 shrink-0 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                                </svg>
                                                <span>{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>

                            @auth
                                <button type="button"
                                        onclick="openPaymentModal({{ $plan->id }}, 'plan', {{ $months }})"
                                        class="w-full text-center py-2.5 px-4 rounded-lg font-extrabold text-xs transition-all duration-200 border-2 border-zinc-900 bg-zinc-900 text-white hover:bg-zinc-800 shadow-md cursor-pointer">
                                    Jetzt kaufen
                                </button>
                            @else
                                <a href="{{ route('register') }}" class="w-full text-center py-2.5 px-4 rounded-lg font-extrabold text-xs transition-all duration-200 border-2 border-zinc-900 bg-zinc-900 text-white hover:bg-zinc-800 shadow-md block no-underline cursor-pointer">Konto erstellen</a>
                            @endauth
                        </article>
                    @endforeach
                @endif
            @empty
                <div class="bg-white border border-zinc-200 rounded-2xl p-12 text-center col-span-full shadow-md">
                    <p class="text-zinc-500 font-medium">Aktuell sind keine Preispläne verfügbar.</p>
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="mt-6 border-t border-zinc-200/80 pt-4 text-center text-zinc-500 text-[0.65rem] leading-relaxed">
            <p>&copy; {{ date('Y') }} Erfolg. Alle Preise inkl. gesetzlicher MwSt. Zahlungen werden sicher über Revolut und PayPal abgewickelt.</p>
        </div>
    </div>

    {{-- Payment Method Modal --}}
    <div id="paymentModal" class="hidden fixed inset-0 bg-black/60 z-50 items-center justify-center backdrop-blur-md transition-all duration-300">
        <div class="bg-white p-8 md:p-10 max-w-lg w-[90%] rounded-2xl shadow-2xl border-l-4 border-[#d8232a] text-left transform scale-95 opacity-0 transition-all duration-300" id="modalContent">
            <h3 class="font-serif text-2xl md:text-3xl font-bold mb-2 text-zinc-900">Zahlungsmethode wählen</h3>
            <p class="text-zinc-500 text-sm mb-6 leading-relaxed">Bitte wählen Sie eine Zahlungsmethode.</p>

            <div id="revolut-section" class="hidden flex-col gap-3 mb-6">
                <div class="border-2 border-zinc-950 p-4 rounded-xl bg-zinc-50">
                    <strong class="text-sm text-zinc-900 block mb-4">Mit Revolut bezahlen</strong>
                    <div id="revolut-pay-button"></div>
                    <div id="revolut-loading" class="text-center py-4">
                        <div class="loading-spinner"></div>
                        <p class="text-xs text-zinc-500 mt-2">Zahlung wird vorbereitet...</p>
                    </div>
                    <div id="revolut-error" class="hidden text-[#d8232a] text-xs mt-2 font-medium"></div>
                </div>
            </div>

            <div class="flex flex-col gap-3 mb-8">
                <a id="paypalBtn" href="#" class="flex items-center justify-between border-2 border-zinc-200 hover:border-zinc-950 p-4 rounded-xl bg-zinc-50 hover:bg-white transition-all duration-200 text-decoration-none text-inherit box-border">
                    <strong class="text-sm text-zinc-900">Mit PayPal bezahlen</strong>
                    <span class="text-xs text-[#d8232a] font-bold">Weiter →</span>
                </a>
                <button type="button" disabled class="flex items-center justify-between border-2 border-zinc-200 p-4 rounded-xl bg-zinc-50 opacity-50 cursor-not-allowed pointer-events-none text-left box-border font-inherit text-inherit w-full">
                    <div>
                        <strong class="text-sm text-zinc-400">Mit Kreditkarte (Revolut) zahlen</strong>
                        <span class="text-xs text-zinc-400 block font-medium">Coming soon</span>
                    </div>
                    <span class="text-xs text-zinc-400 font-bold">Demnächst</span>
                </button>
            </div>

            <div class="flex justify-end">
                <button type="button" onclick="closePaymentModal()" class="border-none bg-transparent py-2 px-4 cursor-pointer text-zinc-500 hover:text-zinc-900 text-sm font-bold tracking-wide uppercase transition-colors">Abbrechen</button>
            </div>
        </div>
    </div>

    <script>
        let selectedPlanId = null;
        let selectedDuration = 1;
        let selectedItemType = 'plan';

        function selectDuration(el, planId, totalPrice, monthlyPrice, months) {
            // Find all options in the container and reset their classes
            el.parentElement.querySelectorAll('.duration-option').forEach(o => {
                o.classList.remove('bg-white', 'text-zinc-900', 'border', 'border-zinc-200/50', 'shadow-sm', 'selected');
                o.classList.add('bg-transparent', 'text-zinc-600', 'hover:text-zinc-900');
            });
            
            // Mark the selected one
            el.classList.remove('bg-transparent', 'text-zinc-600', 'hover:text-zinc-900');
            el.classList.add('bg-white', 'text-zinc-900', 'border', 'border-zinc-200/50', 'shadow-sm', 'selected');

            // Update top price display
            const priceEl = document.getElementById(`price-display-${planId}`);
            if (priceEl) {
                priceEl.textContent = monthlyPrice.toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
            }

            // Update subtext display
            const subtextEl = document.getElementById(`subtext-display-${planId}`);
            if (subtextEl) {
                if (months === 1) {
                    subtextEl.textContent = 'Monatliche Abrechnung';
                } else {
                    subtextEl.textContent = `Gesamtpreis: ${totalPrice.toLocaleString('de-DE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })} € (alle ${months} Monate abgerechnet)`;
                }
            }
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

            const modal = document.getElementById('paymentModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            
            // Trigger animation frame for transition
            setTimeout(() => {
                const content = document.getElementById('modalContent');
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closePaymentModal() {
            const content = document.getElementById('modalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            
            setTimeout(() => {
                const modal = document.getElementById('paymentModal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
                document.getElementById('revolut-section').style.display = 'none';
            }, 300);
        }

        async function initRevolutPay() {
            const revolutSection = document.getElementById('revolut-section');
            const revolutButton = document.getElementById('revolut-pay-button');
            const revolutLoading = document.getElementById('revolut-loading');
            const revolutError = document.getElementById('revolut-error');

            revolutSection.classList.remove('hidden');
            revolutSection.classList.add('flex');
            revolutLoading.style.display = 'block';
            revolutError.style.display = 'none';
            revolutError.classList.add('hidden');

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
                    revolutError.classList.remove('hidden');
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
                            revolutError.classList.remove('hidden');
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
                        revolutError.classList.remove('hidden');
                    },
                    onCancel() {
                        revolutSection.classList.remove('flex');
                        revolutSection.classList.add('hidden');
                        revolutButton.innerHTML = '';
                    }
                });
            } catch (e) {
                revolutError.textContent = 'Zahlungsdienst nicht verfügbar. Bitte versuchen Sie es später erneut.';
                revolutError.style.display = 'block';
                revolutError.classList.remove('hidden');
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
