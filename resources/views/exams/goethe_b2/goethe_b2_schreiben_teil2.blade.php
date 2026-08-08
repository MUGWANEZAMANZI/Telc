{{-- ==================== GOETHE B2 SCHREIBEN TEIL 2 LAYOUT ==================== --}}
<section class="goethe-section goethe-b2-schreiben-teil2-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2.5rem; border-radius: 12px; border: 1px solid #e2e8f0; width: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <div style="border-bottom: 3px solid #d8232a; padding-bottom: 0.75rem; margin-bottom: 2.25rem; display: flex; justify-content: space-between; align-items: baseline;">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #111827; margin: 0;">Teil 2</h2>
        <div style="font-size: 0.9rem; font-weight: 700; color: #d8232a; text-transform: uppercase;">vorgeschlagene Arbeitszeit: 25 Minuten</div>
    </div>

    <div style="margin-bottom: 2rem;">
        <div style="font-size: 1.05rem; font-weight: 600; color: #1e293b; line-height: 1.6; margin-bottom: 1.5rem;">
            Sie machen gerade ein Praktikum bei einer deutschen Firma. Sie haben derzeit so viel zu tun, dass Sie Ihre Arbeit nicht mehr schaffen. Schreiben Sie eine Nachricht an Ihren Vorgesetzten, Herrn Ebert.
        </div>

        {{-- 4 Staggered rotated cards in 2x2 grid --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem; perspective: 1000px;">
            <div style="background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; box-shadow: 2px 4px 8px rgba(0,0,0,0.04); transform: rotate(-1deg); display: flex; align-items: center; justify-content: center; min-height: 90px; text-align: center;">
                <span style="font-size: 1rem; font-weight: 700; color: #1e293b;">Bitten Sie um Verständnis für Ihre Situation.</span>
            </div>
            <div style="background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; box-shadow: 2px 4px 8px rgba(0,0,0,0.04); transform: rotate(1deg); display: flex; align-items: center; justify-content: center; min-height: 90px; text-align: center;">
                <span style="font-size: 1rem; font-weight: 700; color: #1e293b;">Beschreiben Sie, womit Sie beschäftigt sind.</span>
            </div>
            <div style="background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; box-shadow: 2px 4px 8px rgba(0,0,0,0.04); transform: rotate(1deg); display: flex; align-items: center; justify-content: center; min-height: 90px; text-align: center;">
                <span style="font-size: 1rem; font-weight: 700; color: #1e293b;">Machen Sie einen Vorschlag für die kommenden Tage.</span>
            </div>
            <div style="background: #ffffff; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; box-shadow: 2px 4px 8px rgba(0,0,0,0.04); transform: rotate(-1deg); display: flex; align-items: center; justify-content: center; min-height: 90px; text-align: center;">
                <span style="font-size: 1rem; font-weight: 700; color: #1e293b;">Zeigen Sie Verständnis für die Arbeitssituation in der Firma.</span>
            </div>
        </div>

        <div style="font-size: 0.9rem; line-height: 1.6; color: #475569; font-weight: 500;">
            Überlegen Sie sich eine passende Reihenfolge für die Inhaltspunkte.
            Bei der Bewertung wird darauf geachtet, wie genau die Inhaltspunkte bearbeitet sind, wie korrekt der Text ist und wie gut die Sätze und Abschnitte sprachlich miteinander verknüpft sind. Vergessen Sie nicht Anrede und Gruß.
            <br><strong style="color: #0f172a; font-weight: 700;">Schreiben Sie circa 100 Wörter.</strong>
        </div>
    </div>

    {{-- Lined Writing Area --}}
    @php
        $writeInput2 = collect($section['elements'])->firstWhere('type', 'writing');
    @endphp

    @if ($writeInput2)
        @php
            $elId = $writeInput2['id'];
            $valText = $answers[$elId] ?? '';
            $wordCount = !empty(trim($valText)) ? count(preg_split('/\s+/', trim($valText))) : 0;
        @endphp
        <div style="margin-top: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <label style="font-weight: 800; font-size: 1rem; color: #1e293b;">{{ $writeInput2['label'] }}</label>
                <span style="font-size: 0.85rem; font-weight: 700; color: #64748b; background: #e2e8f0; padding: 0.25rem 0.6rem; border-radius: 20px;">
                    Wortanzahl: <span style="color: #0f172a;">{{ $wordCount }}</span> / 100
                </span>
            </div>

            <textarea 
                wire:model.live="answers.{{ $elId }}"
                @disabled($result)
                placeholder="Schreiben Sie hier Ihre Nachricht auf dem linierten Papier..."
                style="width: 100%; box-sizing: border-box; min-height: 300px; padding: 1.5rem; font-family: inherit; font-size: 1.05rem; line-height: 2.2rem; border: 1px solid #d9dde5; border-radius: 8px; background: transparent; background-image: linear-gradient(transparent, transparent 2.15rem, #cbcbcb 2.15rem, #cbcbcb 2.2rem); background-size: 100% 2.2rem; color: #1a1a1a; resize: vertical; outline: none; box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);"
            ></textarea>

            @if ($result)
                @php
                    $feedback = collect($result['details'])->firstWhere('id', $elId);
                @endphp
                @if ($feedback && (!empty($feedback['explanation']) || !empty($feedback['feedback'])))
                    <div style="background: #fef08a; border: 1px solid #fef08a; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                        <strong style="color: #854d0e;">Feedback vom Lehrer:</strong>
                        <p style="margin: 0.5rem 0 0 0; color: #713f12; font-size: 0.95rem;">{{ $feedback['explanation'] ?? $feedback['feedback'] }}</p>
                    </div>
                @endif
            @endif
        </div>
    @endif
</section>
