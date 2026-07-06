<div class="admin-finance-shell" style="max-width: 1200px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; padding: 1rem;">
    <div style="margin-bottom: 2.5rem;">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 2.4rem; font-weight: 700; margin: 0 0 0.5rem; color: #111827;">{{ __('Finance') }}</h2>
        <p style="color: #666666; font-size: 1.05rem; margin: 0;">Umsätze, Transaktionen und ausstehende Zahlungen im Überblick.</p>
    </div>

    @if (session('payment_message'))
        <div style="background: #e6f4ea; border: 1px solid #a7f3d0; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; color: #137333; font-weight: 600;">
            {{ session('payment_message') }}
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-bottom: 2.5rem;">
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.75rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
            <div style="font-size: 0.85rem; color: #666666; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Gesamtumsatz</div>
            <div style="font-size: 2.2rem; font-weight: 800; color: #111827;">{{ number_format($totalRevenue, 2) }} €</div>
        </div>
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.75rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
            <div style="font-size: 0.85rem; color: #666666; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Prüfungsgebühren</div>
            <div style="font-size: 2.2rem; font-weight: 800; color: #111827;">{{ number_format($totalExamRevenue, 2) }} €</div>
        </div>
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.75rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
            <div style="font-size: 0.85rem; color: #666666; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Abo-Umsatz</div>
            <div style="font-size: 2.2rem; font-weight: 800; color: #111827;">{{ number_format($subscriptionRevenue, 2) }} €</div>
        </div>
        <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.75rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
            <div style="font-size: 0.85rem; color: #666666; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">Aktive Abos</div>
            <div style="font-size: 2.2rem; font-weight: 800; color: #111827;">{{ $activeSubscriptions }}</div>
        </div>
    </div>

    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-left: 5px solid #d8232a; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 2.5rem;">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; margin: 0 0 0.25rem; color: #111827;">Ausstehende Zahlungen ({{ $pendingPayments->count() }})</h3>
        <p style="color: #666666; font-size: 0.95rem; margin-bottom: 1.5rem;">Ausstehende Zahlungen (Legacy PayPal-Einzahlungen / manuelle Freigabe).</p>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #fafafa; border-bottom: 2px solid #edf0f4;">
                        <th style="padding: 0.75rem;">Teilnehmer</th>
                        <th style="padding: 0.75rem;">Prüfung</th>
                        <th style="padding: 0.75rem;">Transaktions-ID</th>
                        <th style="padding: 0.75rem;">Beleg</th>
                        <th style="padding: 0.75rem;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingPayments as $payment)
                        <tr style="border-bottom: 1px solid #edf0f4;">
                            <td style="padding: 0.75rem;">
                                <strong>{{ $payment->user->name }}</strong><br>
                                <small style="color: #666666;">{{ $payment->user->email }}</small>
                            </td>
                            <td style="padding: 0.75rem;">{{ $payment->examTemplate->title }} ({{ $payment->examTemplate->level }})</td>
                            <td style="padding: 0.75rem;">
                                <code style="background: #f1f5f9; padding: 0.2rem 0.4rem; border-radius: 4px; font-size: 0.8rem;">{{ $payment->transaction_id ?: '—' }}</code>
                            </td>
                            <td style="padding: 0.75rem;">
                                @if ($payment->receipt_path)
                                    <a href="{{ Storage::url($payment->receipt_path) }}" target="_blank" style="color: #d8232a; font-weight: 600;">Öffnen</a>
                                @else
                                    <span style="color: #999;">Kein Bild</span>
                                @endif
                            </td>
                            <td style="padding: 0.75rem;">
                                <button wire:click="approvePayment({{ $payment->id }})" style="background: #137333; color: #fff; border: none; padding: 0.4rem 1rem; border-radius: 4px; font-weight: 600; cursor: pointer; margin-right: 0.5rem;">Freigeben</button>
                                <button wire:click="rejectPayment({{ $payment->id }})" style="background: #d8232a; color: #fff; border: none; padding: 0.4rem 1rem; border-radius: 4px; font-weight: 600; cursor: pointer;">Ablehnen</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 1rem; text-align: center; color: #666666; font-style: italic;">Keine ausstehenden Zahlungen.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02); margin-bottom: 2.5rem;">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; margin: 0 0 1rem; color: #111827;">Transaktionshistorie</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #fafafa; border-bottom: 2px solid #edf0f4;">
                        <th style="padding: 0.75rem;">Datum</th>
                        <th style="padding: 0.75rem;">Teilnehmer</th>
                        <th style="padding: 0.75rem;">Prüfung</th>
                        <th style="padding: 0.75rem;">Betrag</th>
                        <th style="padding: 0.75rem;">Zahlungsart</th>
                        <th style="padding: 0.75rem;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transactions as $tx)
                        <tr style="border-bottom: 1px solid #edf0f4;">
                            <td style="padding: 0.75rem;">{{ $tx->enrolled_at?->format('d.m.Y') ?: '—' }}</td>
                            <td style="padding: 0.75rem;">
                                <strong>{{ $tx->user->name }}</strong><br>
                                <small style="color: #666666;">{{ $tx->user->email }}</small>
                            </td>
                            <td style="padding: 0.75rem;">{{ $tx->examTemplate->title }} ({{ $tx->examTemplate->level }})</td>
                            <td style="padding: 0.75rem; font-weight: 700;">{{ number_format($tx->examTemplate->price ?? 0, 2) }} €</td>
                            <td style="padding: 0.75rem;">
                                @if ($tx->payment_status === 'paid')
                                    @if ($tx->paypal_order_id)
                                        PayPal
                                    @elseif ($tx->payment_status === 'paid')
                                        Revolut
                                    @else
                                        {{ $tx->payment_status }}
                                    @endif
                                @elseif ($tx->payment_status === 'manual_enrolled')
                                    PayPal (Legacy)
                                @elseif ($tx->payment_status === 'subscribed')
                                    Abonnement
                                @else
                                    {{ $tx->payment_status }}
                                @endif
                            </td>
                            <td style="padding: 0.75rem;">
                                <span style="display: inline-block; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase; {{ $tx->status === 'active' ? 'background: #e6f4ea; color: #137333;' : 'background: #fce8e6; color: #c5221f;' }}">
                                    {{ $tx->status }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="padding: 1rem; text-align: center; color: #666666; font-style: italic;">Keine Transaktionen gefunden.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; margin: 0 0 1rem; color: #111827;">Monatliche Übersicht (letzte 12 Monate)</h3>
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #fafafa; border-bottom: 2px solid #edf0f4;">
                        <th style="padding: 0.75rem;">Monat</th>
                        <th style="padding: 0.75rem;">Transaktionen</th>
                        <th style="padding: 0.75rem;">Umsatz</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($monthlyBreakdown as $row)
                        <tr style="border-bottom: 1px solid #edf0f4;">
                            <td style="padding: 0.75rem; font-weight: 600;">{{ $row->month }}</td>
                            <td style="padding: 0.75rem;">{{ $row->count }}</td>
                            <td style="padding: 0.75rem; font-weight: 700;">{{ number_format($row->revenue, 2) }} €</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="padding: 1rem; text-align: center; color: #666666; font-style: italic;">Noch keine monatlichen Daten vorhanden.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
