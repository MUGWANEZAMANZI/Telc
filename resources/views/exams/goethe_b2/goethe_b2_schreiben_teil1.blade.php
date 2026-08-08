{{-- ==================== GOETHE B2 SCHREIBEN TEIL 1 LAYOUT ==================== --}}
<section class="goethe-section goethe-b2-schreiben-teil1-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2.5rem; border-radius: 12px; border: 1px solid #e2e8f0; width: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <div style="border-bottom: 3px solid #d8232a; padding-bottom: 0.75rem; margin-bottom: 2.25rem; display: flex; justify-content: space-between; align-items: baseline;">
        <h2 style="font-size: 1.8rem; font-weight: 800; color: #111827; margin: 0;">Teil 1</h2>
        <div style="font-size: 0.9rem; font-weight: 700; color: #d8232a; text-transform: uppercase;">vorgeschlagene Arbeitszeit: 50 Minuten</div>
    </div>

    <div style="display: grid; grid-template-columns: 1.2fr 0.8fr; gap: 2rem; margin-bottom: 2rem;">
        {{-- Left column: Task Description & Guidelines --}}
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div style="font-size: 1.1rem; font-weight: 800; color: #1e293b;">
                Sie schreiben einen Forumsbeitrag zur Verschmutzung der Umwelt.
            </div>

            <div style="background: #f8fafc; border-left: 4px solid #cbd5e1; padding: 1.25rem; border-radius: 0 8px 8px 0;">
                <ul style="margin: 0; padding-left: 1.2rem; display: flex; flex-direction: column; gap: 0.75rem; font-size: 0.95rem; font-weight: 600; color: #334155; list-style-type: disc;">
                    <li>Äußern Sie Ihre Meinung zu Plastikverpackungen im Alltag.</li>
                    <li>Nennen Sie Gründe, warum Plastikverpackungen so verbreitet sind.</li>
                    <li>Nennen Sie andere Möglichkeiten, Dinge im Alltag zu verpacken.</li>
                    <li>Nennen Sie Vorteile der anderen Verpackungen.</li>
                </ul>
            </div>

            <div style="font-size: 0.9rem; line-height: 1.6; color: #475569; font-weight: 500;">
                Denken Sie an eine Einleitung und einen Schluss. Bei der Bewertung wird darauf geachtet, wie genau die Inhaltspunkte bearbeitet sind, wie korrekt der Text ist und wie gut die Sätze und Abschnitte sprachlich miteinander verknüpft sind.
                <br><strong style="color: #0f172a; font-weight: 700;">Schreiben Sie circa 150 Wörter.</strong>
            </div>
        </div>

        {{-- Right column: Visual Prompt Illustration --}}
        <div style="display: flex; justify-content: center; align-items: center; background: #f1f5f9; border-radius: 12px; overflow: hidden; border: 1px solid #e2e8f0; max-height: 320px;">
            <img src="/images/plastic_waste_beach.png" alt="Plastikverpackungen im Alltag" style="width: 100%; height: 100%; object-fit: cover;">
        </div>
    </div>

    {{-- Lined Writing Area --}}
    @php
        $writeInput = collect($section['elements'])->firstWhere('type', 'writing');
    @endphp

    @if ($writeInput)
        @php
            $elId = $writeInput['id'];
            $valText = $answers[$elId] ?? '';
            $wordCount = !empty(trim($valText)) ? count(preg_split('/\s+/', trim($valText))) : 0;
        @endphp
        <div style="margin-top: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                <label style="font-weight: 800; font-size: 1rem; color: #1e293b;">{{ $writeInput['label'] }}</label>
                <span style="font-size: 0.85rem; font-weight: 700; color: #64748b; background: #e2e8f0; padding: 0.25rem 0.6rem; border-radius: 20px;">
                    Wortanzahl: <span style="color: #0f172a;">{{ $wordCount }}</span> / 150
                </span>
            </div>

            <textarea 
                wire:model.live="answers.{{ $elId }}"
                @disabled($result)
                placeholder="Schreiben Sie hier Ihren Forumsbeitrag auf dem linierten Papier..."
                style="width: 100%; box-sizing: border-box; min-height: 380px; padding: 1.5rem; font-family: inherit; font-size: 1.05rem; line-height: 2.2rem; border: 1px solid #d9dde5; border-radius: 8px; background: transparent; background-image: linear-gradient(transparent, transparent 2.15rem, #cbcbcb 2.15rem, #cbcbcb 2.2rem); background-size: 100% 2.2rem; color: #1a1a1a; resize: vertical; outline: none; box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);"
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
