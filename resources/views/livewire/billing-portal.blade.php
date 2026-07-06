<div class="billing-portal-shell" style="max-width: 1000px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; padding: 1rem;">
    <div style="margin-bottom: 2.5rem;">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 2.4rem; font-weight: 700; margin: 0 0 0.5rem; color: #111827;">{{ __('Billing') }}</h2>
        <p style="color: #666666; font-size: 1.05rem; margin: 0;">Abonnements verwalten und Abrechnungsinformationen einsehen.</p>
    </div>

    @if ($isAdmin)
        {{-- Admin Subscription Management Panel --}}
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-left: 5px solid #d8232a; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 2.5rem;">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin: 0 0 0.5rem; color: #111827;">Ablaufende Abonnements (Nächste 7 Tage)</h3>
            <p style="color: #666666; font-size: 1rem; margin-bottom: 1.5rem;">Teilnehmer mit bald endenden Laufzeiten. Kopieren Sie die E-Mails für Erinnerungen.</p>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
                    <thead>
                        <tr style="background: #fafafa; border-bottom: 2px solid #edf0f4;">
                            <th style="padding: 0.75rem;">Name</th>
                            <th style="padding: 0.75rem;">E-Mail (Kopieren)</th>
                            <th style="padding: 0.75rem;">Tarif</th>
                            <th style="padding: 0.75rem;">Endet am</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($expiring as $sub)
                            <tr style="border-bottom: 1px solid #edf0f4;">
                                <td style="padding: 0.75rem; font-weight: 700;">{{ $sub->user->name }}</td>
                                <td style="padding: 0.75rem;">
                                    <code style="background: #f1f5f9; padding: 0.2rem 0.4rem; border-radius: 4px; font-size: 0.85rem;">{{ $sub->user->email }}</code>
                                </td>
                                <td style="padding: 0.75rem;">{{ $sub->plan->name }}</td>
                                <td style="padding: 0.75rem; color: #d8232a; font-weight: 700;">{{ $sub->ends_at->format('d.m.Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 1rem; text-align: center; color: #666666; font-style: italic;">Keine Abonnements laufen in den nächsten 7 Tagen ab.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
            <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin: 0 0 1rem; color: #111827;">Abonnement-Historie</h3>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
                    <thead>
                        <tr style="background: #fafafa; border-bottom: 2px solid #edf0f4;">
                            <th style="padding: 0.75rem;">Teilnehmer</th>
                            <th style="padding: 0.75rem;">Tarif</th>
                            <th style="padding: 0.75rem;">Status</th>
                            <th style="padding: 0.75rem;">Enddatum / Zyklus</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($history as $sub)
                            <tr style="border-bottom: 1px solid #edf0f4;">
                                <td style="padding: 0.75rem;">
                                    <strong>{{ $sub->user->name }}</strong><br>
                                    <small style="color: #666666;">{{ $sub->user->email }}</small>
                                </td>
                                <td style="padding: 0.75rem;">{{ $sub->plan->name }} ({{ number_format($sub->price_paid, 2) }} €)</td>
                                <td style="padding: 0.75rem;">
                                    <span style="display: inline-block; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; {{ $sub->status === 'active' ? 'background: #e6f4ea; color: #137333;' : 'background: #fce8e6; color: #c5221f;' }}">
                                        {{ $sub->status }}
                                    </span>
                                </td>
                                <td style="padding: 0.75rem;">
                                    @if ($sub->ends_at)
                                        {{ $sub->ends_at->format('d.m.Y H:i') }} ({{ $sub->duration_months }} Monat(e))
                                    @else
                                        Unbegrenzt
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" style="padding: 1rem; text-align: center; color: #666666; font-style: italic;">Keine Abonnements gefunden.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @else
        {{-- Student Subscription Status Panel --}}
        @if ($subscription)
            <div style="background: #ffffff; border: 1px solid #e5e7eb; border-left: 5px solid #137333; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 2.5rem;">
                <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin: 0 0 1.5rem; color: #111827;">Aktives Abonnement</h3>
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; background: #fafafa; padding: 1.5rem; border-radius: 8px; border: 1px solid #f0f0f0;">
                    <div>
                            <strong style="font-size: 1.35rem; color: #111827; display: block; margin-bottom: 0.25rem;">{{ $subscription->plan->name }}</strong>
                        <span style="font-size: 0.95rem; color: #666666;">
                            {{ number_format($subscription->price_paid, 2) }} {{ strtoupper($subscription->plan->currency) }} für {{ $subscription->duration_months }} Monat(e)
                        </span>
                        @if ($subscription->ends_at)
                            <div style="margin-top: 0.75rem; font-size: 0.9rem; color: #d8232a; font-weight: 700;">
                                📅 Zugang endet am: {{ $subscription->ends_at->format('d.m.Y H:i') }} Uhr
                            </div>
                            <div style="margin-top: 0.5rem; font-size: 0.85rem; color: #666;">
                                Bezahlt via {{ $subscription->provider === 'revolut' ? 'Revolut' : 'PayPal' }} · {{ number_format($subscription->price_paid, 2) }} € für {{ $subscription->duration_months }} Monat(e)
                            </div>
                        @endif
                    </div>
                    <span style="background: #e6f4ea; color: #137333; padding: 0.5rem 1.25rem; border-radius: 20px; font-weight: 700; font-size: 0.85rem; text-transform: uppercase; border: 1px solid #a7f3d0;">
                        Aktiv
                    </span>
                </div>
            </div>
        @else
            <div style="background: #ffffff; border: 1px solid #e5e7eb; border-left: 5px solid #d8232a; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 2.5rem;">
                <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin: 0 0 0.5rem; color: #111827;">Kein aktives Abonnement</h3>
                <p style="color: #666666; font-size: 1rem; margin-bottom: 2rem;">Sie haben derzeit kein aktives Abonnement. Schalten Sie den vollen Funktionsumfang frei, um Ihre Vorbereitung zu maximieren:</p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div style="background: #fafafa; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; display: flex; gap: 1rem; align-items: flex-start;">
                        <span style="font-size: 1.5rem; color: #d8232a;">🚀</span>
                        <div>
                            <h4 style="font-size: 1.05rem; font-weight: 700; margin: 0 0 0.25rem; color: #111827;">Unbegrenzte Prüfungssimulationen</h4>
                            <p style="font-size: 0.85rem; color: #666666; margin: 0; line-height: 1.5;">Simulieren Sie beliebig viele B1, B2 oder C1 Prüfungen unter echten Prüfungsbedingungen.</p>
                        </div>
                    </div>

                    <div style="background: #fafafa; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; display: flex; gap: 1rem; align-items: flex-start;">
                        <span style="font-size: 1.5rem; color: #d8232a;">🎙️</span>
                        <div>
                            <h4 style="font-size: 1.05rem; font-weight: 700; margin: 0 0 0.25rem; color: #111827;">KI-Sprechprüfungs-Agent</h4>
                            <p style="font-size: 0.85rem; color: #666666; margin: 0; line-height: 1.5;">Erhalten Sie direktes, detailliertes Audio-Feedback und simulieren Sie mündliche Prüfungen.</p>
                        </div>
                    </div>

                    <div style="background: #fafafa; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.5rem; display: flex; gap: 1rem; align-items: flex-start;">
                        <span style="font-size: 1.5rem; color: #d8232a;">📝</span>
                        <div>
                            <h4 style="font-size: 1.05rem; font-weight: 700; margin: 0 0 0.25rem; color: #111827;">KI-Aufsatzbewertungen</h4>
                            <p style="font-size: 0.85rem; color: #666666; margin: 0; line-height: 1.5;">Vollautomatische, detaillierte Bewertungen Ihrer schriftlichen C1-Texte.</p>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                    <a href="{{ route('pricing') }}" style="display: inline-block; background: #d8232a; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 0.95rem; padding: 0.75rem 2rem; border-radius: 6px; transition: background 0.2s;">
                        Tarife ansehen & freischalten
                    </a>
                    <a href="{{ route('free.exam') }}" style="display: inline-block; background: #ffffff; border: 1px solid #d9dde5; color: #1a1a1a; text-decoration: none; font-weight: 700; font-size: 0.95rem; padding: 0.75rem 2rem; border-radius: 6px;">
                        Kostenlose Übung machen
                    </a>
                </div>
            </div>
        @endif
    @endif
</div>
</div>
