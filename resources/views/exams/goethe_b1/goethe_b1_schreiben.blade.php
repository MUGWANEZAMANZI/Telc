{{-- ==================== GOETHE B1 SCHREIBEN LAYOUT ==================== --}}
<section class="goethe-section goethe-b1-schreiben-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2.5rem; border-radius: 12px; border: 1px solid #e2e8f0; width: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    
    {{-- Main Header --}}
    <div style="border-bottom: 3px solid #0f766e; padding-bottom: 0.75rem; margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <span style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; color: #0f766e; letter-spacing: 0.1em; display: block; margin-bottom: 0.25rem;">Kandidatenblätter</span>
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2.2rem; font-weight: 900; color: #111827; margin: 0; letter-spacing: -0.02em;">Schreiben</h2>
        </div>
        <div style="text-align: right;">
            <span style="display: block; font-size: 1.1rem; font-weight: 800; color: #1f2937;">60 Minuten</span>
            <span style="font-size: 0.8rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Modul Schreiben</span>
        </div>
    </div>

    @php
        $elements = collect($section['elements']);
        $a1Instruction = $elements->firstWhere('id', 'goethe-b1-write-a1-instruction');
        $a1Input = $elements->firstWhere('id', 'goethe-b1-write-a1-input');
        
        $a2Instruction = $elements->firstWhere('id', 'goethe-b1-write-a2-instruction');
        $a2Input = $elements->firstWhere('id', 'goethe-b1-write-a2-input');
        
        $a3Instruction = $elements->firstWhere('id', 'goethe-b1-write-a3-instruction');
        $a3Input = $elements->firstWhere('id', 'goethe-b1-write-a3-input');
    @endphp

    {{-- ==================== AUFGABE 1 ==================== --}}
    @if ($a1Instruction && $a1Input)
        <div style="margin-bottom: 4rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 3rem;">
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.25rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
                <h3 style="font-size: 1.4rem; font-weight: 800; color: #0f766e; margin: 0;">Aufgabe 1</h3>
                <span style="font-size: 0.85rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Arbeitszeit: 20 Minuten</span>
            </div>

            <div style="font-size: 1.05rem; line-height: 1.7; color: #1e293b; font-weight: 500; white-space: pre-line; margin-bottom: 1.5rem; background: #f8fafc; border-left: 4px solid #0f766e; padding: 1.25rem; border-radius: 0 8px 8px 0;">
                {!! e($a1Instruction['content']) !!}
            </div>

            @php
                $a1Val = $answers[$a1Input['id']] ?? '';
                $a1WordCount = !empty(trim($a1Val)) ? count(preg_split('/\s+/', trim($a1Val))) : 0;
            @endphp

            <div style="margin-top: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label style="font-weight: 800; font-size: 0.95rem; color: #1e293b;">{{ $a1Input['label'] }}</label>
                    <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; background: #e2e8f0; padding: 0.2rem 0.6rem; border-radius: 20px;">
                        Wortanzahl: <span style="color: #0f766e; font-weight: 800;">{{ $a1WordCount }}</span> / ca. 80
                    </span>
                </div>
                <textarea 
                    wire:model.live="answers.{{ $a1Input['id'] }}"
                    @disabled($result)
                    placeholder="Schreiben Sie hier Ihre E-Mail..."
                    style="width: 100%; box-sizing: border-box; min-height: 250px; padding: 1.25rem; font-family: inherit; font-size: 1.02rem; line-height: 2.2rem; border: 1px solid #cbd5e1; border-radius: 8px; background: transparent; background-image: linear-gradient(transparent, transparent 2.15rem, #e2e8f0 2.15rem, #e2e8f0 2.2rem); background-size: 100% 2.2rem; color: #1e293b; resize: vertical; outline: none;"
                ></textarea>

                @if ($result)
                    @php
                        $feedback = collect($result['details'])->firstWhere('id', $a1Input['id']);
                    @endphp
                    @if ($feedback && (!empty($feedback['explanation']) || !empty($feedback['feedback'])))
                        <div style="background: #fef08a; border: 1px solid #fde047; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                            <strong style="color: #854d0e;">Feedback:</strong>
                            <p style="margin: 0.5rem 0 0 0; color: #713f12; font-size: 0.95rem;">{{ $feedback['explanation'] ?? $feedback['feedback'] }}</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif

    {{-- ==================== AUFGABE 2 ==================== --}}
    @if ($a2Instruction && $a2Input)
        <div style="margin-bottom: 4rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 3rem;">
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.25rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
                <h3 style="font-size: 1.4rem; font-weight: 800; color: #0f766e; margin: 0;">Aufgabe 2</h3>
                <span style="font-size: 0.85rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Arbeitszeit: 25 Minuten</span>
            </div>

            <div style="font-size: 1.05rem; line-height: 1.7; color: #1e293b; font-weight: 500; margin-bottom: 1.5rem; background: #f8fafc; border-left: 4px solid #0f766e; padding: 1.25rem; border-radius: 0 8px 8px 0;">
                Sie haben im Fernsehen eine Diskussionssendung zum Thema „Persönliche Kontakte und Internet“ gesehen.
                Im Online-Gästebuch der Sendung finden Sie folgende Meinung:
            </div>

            {{-- Gästebuch browser mockup simulation --}}
            <div style="margin: 1.5rem 0; border: 1.5px solid #cbd5e1; border-radius: 8px; overflow: hidden; background: #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.03); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
                {{-- Browser bar --}}
                <div style="background: #f1f5f9; padding: 0.5rem 1rem; display: flex; align-items: center; gap: 0.5rem; border-bottom: 1.5px solid #cbd5e1;">
                    <div style="display: flex; gap: 0.35rem;">
                        <span style="width: 9px; height: 9px; border-radius: 50%; background: #ef4444; display: inline-block;"></span>
                        <span style="width: 9px; height: 9px; border-radius: 50%; background: #f59e0b; display: inline-block;"></span>
                        <span style="width: 9px; height: 9px; border-radius: 50%; background: #10b981; display: inline-block;"></span>
                    </div>
                    <div style="flex-grow: 1; margin-left: 1rem; background: #ffffff; border: 1px solid #cbd5e1; border-radius: 4px; padding: 0.2rem 0.75rem; font-size: 0.8rem; color: #64748b; font-family: monospace; display: flex; align-items: center; justify-content: space-between;">
                        <span>www.diskussion-aktuell.de</span>
                        <span style="font-size: 0.75rem; color: #94a3b8;">🔒 Verbindung sicher</span>
                    </div>
                </div>
                {{-- Page header --}}
                <div style="background: #475569; padding: 0.6rem 1.25rem; border-bottom: 2px solid #334155; color: #ffffff;">
                    <h4 style="margin: 0; font-size: 1.1rem; font-weight: 700; letter-spacing: 0.05em; text-transform: uppercase;">Gästebuch</h4>
                </div>
                {{-- Comment item --}}
                <div style="padding: 1.25rem; background: #f8fafc;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #e2e8f0; padding-bottom: 0.4rem; margin-bottom: 0.75rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <span style="background: #0f766e; color: #ffffff; width: 1.4rem; height: 1.4rem; border-radius: 50%; font-weight: 800; font-size: 0.75rem; display: inline-flex; align-items: center; justify-content: center;">T</span>
                            <span style="font-weight: 700; font-size: 0.92rem; color: #1e293b;">Tanja</span>
                        </div>
                        <span style="font-size: 0.78rem; color: #64748b; font-weight: 600;">15.01. 16:55 Uhr</span>
                    </div>
                    <div style="font-size: 0.95rem; color: #334155; line-height: 1.6; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1rem;">
                        Ich finde es schlimm, dass persönliche Treffen immer seltener werden. Freunde wohnen oft sehr weit auseinander. Und da ist man dann schon froh über das Internet. Aber Kontakte im Internet können doch persönliche Treffen nicht ersetzen!
                    </div>
                    <div style="margin-top: 0.6rem; font-size: 0.78rem; color: #94a3b8; display: flex; align-items: center; gap: 0.25rem; font-weight: 600;">
                        <span>▶ 15.01. 17:02 Uhr</span>
                    </div>
                </div>
            </div>

            <div style="font-size: 1.05rem; line-height: 1.7; color: #1e293b; font-weight: 500; margin-bottom: 1.5rem; background: #f8fafc; border-left: 4px solid #0f766e; padding: 1.25rem; border-radius: 0 8px 8px 0;">
                Schreiben Sie nun Ihre Meinung zum Thema (circa 80 Wörter).
            </div>

            @php
                $a2Val = $answers[$a2Input['id']] ?? '';
                $a2WordCount = !empty(trim($a2Val)) ? count(preg_split('/\s+/', trim($a2Val))) : 0;
            @endphp

            <div style="margin-top: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label style="font-weight: 800; font-size: 0.95rem; color: #1e293b;">{{ $a2Input['label'] }}</label>
                    <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; background: #e2e8f0; padding: 0.2rem 0.6rem; border-radius: 20px;">
                        Wortanzahl: <span style="color: #0f766e; font-weight: 800;">{{ $a2WordCount }}</span> / ca. 80
                    </span>
                </div>
                <textarea 
                    wire:model.live="answers.{{ $a2Input['id'] }}"
                    @disabled($result)
                    placeholder="Schreiben Sie hier Ihre Meinung..."
                    style="width: 100%; box-sizing: border-box; min-height: 250px; padding: 1.25rem; font-family: inherit; font-size: 1.02rem; line-height: 2.2rem; border: 1px solid #cbd5e1; border-radius: 8px; background: transparent; background-image: linear-gradient(transparent, transparent 2.15rem, #e2e8f0 2.15rem, #e2e8f0 2.2rem); background-size: 100% 2.2rem; color: #1e293b; resize: vertical; outline: none;"
                ></textarea>

                @if ($result)
                    @php
                        $feedback = collect($result['details'])->firstWhere('id', $a2Input['id']);
                    @endphp
                    @if ($feedback && (!empty($feedback['explanation']) || !empty($feedback['feedback'])))
                        <div style="background: #fef08a; border: 1px solid #fde047; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                            <strong style="color: #854d0e;">Feedback:</strong>
                            <p style="margin: 0.5rem 0 0 0; color: #713f12; font-size: 0.95rem;">{{ $feedback['explanation'] ?? $feedback['feedback'] }}</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif

    {{-- ==================== AUFGABE 3 ==================== --}}
    @if ($a3Instruction && $a3Input)
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.25rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
                <h3 style="font-size: 1.4rem; font-weight: 800; color: #0f766e; margin: 0;">Aufgabe 3</h3>
                <span style="font-size: 0.85rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Arbeitszeit: 15 Minuten</span>
            </div>

            <div style="font-size: 1.05rem; line-height: 1.7; color: #1e293b; font-weight: 500; white-space: pre-line; margin-bottom: 1.5rem; background: #f8fafc; border-left: 4px solid #0f766e; padding: 1.25rem; border-radius: 0 8px 8px 0;">
                {!! e($a3Instruction['content']) !!}
            </div>

            @php
                $a3Val = $answers[$a3Input['id']] ?? '';
                $a3WordCount = !empty(trim($a3Val)) ? count(preg_split('/\s+/', trim($a3Val))) : 0;
            @endphp

            <div style="margin-top: 1.5rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                    <label style="font-weight: 800; font-size: 0.95rem; color: #1e293b;">{{ $a3Input['label'] }}</label>
                    <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; background: #e2e8f0; padding: 0.2rem 0.6rem; border-radius: 20px;">
                        Wortanzahl: <span style="color: #0f766e; font-weight: 800;">{{ $a3WordCount }}</span> / ca. 40
                    </span>
                </div>
                <textarea 
                    wire:model.live="answers.{{ $a3Input['id'] }}"
                    @disabled($result)
                    placeholder="Schreiben Sie hier Ihre Entschuldigungs-E-Mail..."
                    style="width: 100%; box-sizing: border-box; min-height: 200px; padding: 1.25rem; font-family: inherit; font-size: 1.02rem; line-height: 2.2rem; border: 1px solid #cbd5e1; border-radius: 8px; background: transparent; background-image: linear-gradient(transparent, transparent 2.15rem, #e2e8f0 2.15rem, #e2e8f0 2.2rem); background-size: 100% 2.2rem; color: #1e293b; resize: vertical; outline: none;"
                ></textarea>

                @if ($result)
                    @php
                        $feedback = collect($result['details'])->firstWhere('id', $a3Input['id']);
                    @endphp
                    @if ($feedback && (!empty($feedback['explanation']) || !empty($feedback['feedback'])))
                        <div style="background: #fef08a; border: 1px solid #fde047; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                            <strong style="color: #854d0e;">Feedback:</strong>
                            <p style="margin: 0.5rem 0 0 0; color: #713f12; font-size: 0.95rem;">{{ $feedback['explanation'] ?? $feedback['feedback'] }}</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    @endif

</section>
