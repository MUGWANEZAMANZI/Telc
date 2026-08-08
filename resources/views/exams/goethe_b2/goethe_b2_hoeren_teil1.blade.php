{{-- ==================== GOETHE B2 HÖREN TEIL 1 LAYOUT ==================== --}}
<section class="goethe-section goethe-b2-hoeren-teil1-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $section['title'] ?? 'Hören Teil 1' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em;">
            <span>vorgeschlagene Arbeitszeit: 18 Minuten</span>
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-h1-instruction');
        $questions = collect($section['elements'])->whereIn('type', ['matching', 'multiple_choice'])->all();
    @endphp

    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2rem; color: #374151; background: #f8fafc; border-left: 4px solid #a3a3a3; padding: 1rem 1.25rem; font-size: 0.95rem; line-height: 1.6; border-radius: 4px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    {{-- Interactive Gespräch Blocks --}}
    <div style="display: flex; flex-direction: column; gap: 2rem; margin-top: 1.5rem;">
        {{-- Example block Gespräch 0 --}}
        <div style="border: 1px solid #cbd5e1; border-radius: 8px; background: #f8fafc; padding: 1.5rem; opacity: 0.85;">
            <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #cbd5e1; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                <span style="font-weight: 800; font-size: 0.85rem; text-transform: uppercase; color: #475569; letter-spacing: 0.05em;">Beispiel (Gespräch 0)</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem;">
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start; max-width: 70%;">
                        <span style="background: #64748b; color: #ffffff; font-weight: 800; font-size: 0.85rem; padding: 0.25rem 0.5/em; border-radius: 4px; flex-shrink: 0;">01</span>
                        <span style="font-size: 0.92rem; font-weight: 600; color: #334155;">Die Frau fragt nach Freizeitaktivitäten im Sommer.</span>
                    </div>
                    <div style="display: flex; gap: 0.5rem;">
                        <span style="padding: 0.35rem 1rem; border-radius: 6px; font-size: 0.85rem; font-weight: 800; background: #ffffff; border: 1.5px solid #cbd5e1; color: #64748b;">Richtig</span>
                        <span style="padding: 0.35rem 1rem; border-radius: 6px; font-size: 0.85rem; font-weight: 800; background: #cbd5e1; border: 1.5px solid #cbd5e1; color: #1e293b; text-decoration: line-through; position: relative;">Falsch<span style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; color: #d8232a; font-weight: 900;">✕</span></span>
                    </div>
                </div>
                <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 0.25rem 0;">
                <div>
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start; margin-bottom: 0.75rem;">
                        <span style="background: #64748b; color: #ffffff; font-weight: 850; font-size: 0.85rem; padding: 0.25rem 0.55rem; border-radius: 4px; flex-shrink: 0;">02</span>
                        <span style="font-size: 0.92rem; font-weight: 600; color: #334155;">Für die Angebote, die etwas kosten, muss man sich ...</span>
                    </div>
                    <div style="display: flex; flex-direction: column; gap: 0.5rem; padding-left: 2.2rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: #475569;">
                            <span style="width: 1.5rem; height: 1.5rem; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; font-weight: 700; background: #fff;">a</span>
                            <span>im Internet anmelden.</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: #475569;">
                            <span style="width: 1.5rem; height: 1.5rem; border: 1px solid #cbd5e1; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; font-weight: 700; background: #fff;">b</span>
                            <span>vor dem 1. Juni anmelden.</span>
                        </div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; color: #1e293b; font-weight: 700; position: relative;">
                            <span style="width: 1.5rem; height: 1.5rem; border: 1.5px solid #cbd5e1; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; font-weight: 700; background: #cbd5e1;">c<span style="position: absolute; top: -2px; left: 0; font-size: 1.25rem; color: #d8232a; font-weight: 900;">✕</span></span>
                            <span>persönlich anmelden.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Dynamic Gespräch Blocks 1 to 5 --}}
        @for ($g = 1; $g <= 5; $g++)
            @php
                $qIdxR = ($g - 1) * 2;
                $qIdxMC = $qIdxR + 1;
                $qR = $questions[$qIdxR] ?? null;
                $qMC = $questions[$qIdxMC] ?? null;
            @endphp

            @if ($qR && $qMC)
                <div style="border: 1.5px solid #cbd5e1; border-radius: 8px; background: #ffffff; padding: 1.5rem; box-shadow: 0 2px 5px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 1.25rem;">
                        <span style="font-weight: 800; font-size: 0.9rem; text-transform: uppercase; color: #1e293b; letter-spacing: 0.05em;">Gespräch {{ $g }}</span>
                    </div>

                    {{-- Odd Question: Richtig/Falsch --}}
                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
                        <div style="display: flex; gap: 0.75rem; align-items: flex-start; max-width: 65%;">
                            <span style="background: #111827; color: #ffffff; font-weight: 850; font-size: 0.9rem; padding: 0.25rem 0.55rem; border-radius: 4px; flex-shrink: 0;">{{ substr($qR['id'], strrpos($qR['id'], '-') + 1) }}</span>
                            <span style="font-size: 0.95rem; font-weight: 700; color: #1f2937; line-height: 1.5;">{{ $qR['instruction'] }}</span>
                        </div>

                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            @foreach (['richtig', 'falsch'] as $optVal)
                                @php
                                    $selected = ($answers[$qR['id']] ?? '') === $optVal;
                                    $btnStyle = 'background: #ffffff; border: 1.5px solid #cbd5e1; color: #475569;';
                                    if ($selected) {
                                        $btnStyle = 'background: #eff6ff; border-color: #3b82f6; color: #1d4ed8; font-weight: 800;';
                                    }
                                    if ($result) {
                                        $detail = collect($result['details'])->firstWhere('id', $qR['id']);
                                        $correct = $detail['correct'] ?? false;
                                        $expected = $detail['expected'] ?? '';
                                        if ($selected) {
                                            $btnStyle = $correct ? 'background: #e6f4ea; border-color: #10b981; color: #137333; font-weight:800;' : 'background: #fdf2f2; border-color: #ef4444; color: #9b1c1c; font-weight:800;';
                                        } elseif ($expected === $optVal) {
                                            $btnStyle = 'background: #e6f4ea; border-color: #10b981; color: #137333; font-weight:800;';
                                        }
                                    }
                                @endphp
                                <button type="button" wire:click="$set('answers.{{ $qR['id'] }}', '{{ $optVal }}')" {{ $result ? 'disabled' : '' }}
                                    style="padding: 0.4rem 1.25rem; border-radius: 6px; font-size: 0.88rem; font-weight: 700; cursor: pointer; transition: all 0.2s; min-width: 90px; text-align: center; outline: none; {{ $btnStyle }}">
                                    {{ ucfirst($optVal) }}
                                </button>
                            @endforeach

                            @if ($result)
                                @php
                                    $detail = collect($result['details'])->firstWhere('id', $qR['id']);
                                    $correct = $detail['correct'] ?? false;
                                    $expected = $detail['expected'] ?? '';
                                	@endphp
                                <span style="font-weight: 800; font-size: 0.9rem; margin-left: 0.5rem;">
                                    @if ($correct)
                                        <span style="color: #10b981;">✓ Richtig</span>
                                    @else
                                        <span style="color: #ef4444;">✗ Falsch</span>
                                        <span style="color: #10b981; margin-left: 0.25rem;">(Lösung: {{ ucfirst($expected) }})</span>
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>

                    <hr style="border: 0; border-top: 1.5px dashed #e2e8f0; margin: 1rem 0;">

                    {{-- Even Question: MC a/b/c --}}
                    <div>
                        <div style="display: flex; gap: 0.75rem; align-items: flex-start; margin-bottom: 0.75rem;">
                            <span style="background: #111827; color: #ffffff; font-weight: 850; font-size: 0.9rem; padding: 0.25rem 0.55rem; border-radius: 4px; flex-shrink: 0;">{{ substr($qMC['id'], strrpos($qMC['id'], '-') + 1) }}</span>
                            <span style="font-size: 0.95rem; font-weight: 700; color: #1f2937; line-height: 1.5;">{{ $qMC['instruction'] }}</span>
                        </div>

                        @php
                            $mcOptions = parseMultipleChoiceOptions($qMC['options'] ?? '');
                        @endphp

                        <div style="display: flex; flex-direction: column; gap: 0.6rem; padding-left: 2.2rem;">
                            @foreach ($mcOptions as $optChar => $optText)
                                @php
                                    $selected = ($answers[$qMC['id']] ?? '') === $optChar;
                                    $optStyle = 'background: #ffffff; border: 1.5px solid #e2e8f0; color: #374151;';
                                    $chipStyle = 'background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569;';
                                    
                                    if ($selected) {
                                        $optStyle = 'background: #eff6ff; border-color: #3b82f6; color: #1e3a8a; font-weight: 700;';
                                        $chipStyle = 'background: #3b82f6; border-color: #3b82f6; color: #ffffff;';
                                    }

                                    if ($result) {
                                        $detail = collect($result['details'])->firstWhere('id', $qMC['id']);
                                        $correct = $detail['correct'] ?? false;
                                        $expected = $detail['expected'] ?? '';
                                        
                                        if ($selected) {
                                            if ($correct) {
                                                $optStyle = 'background: #e6f4ea; border-color: #10b981; color: #137333; font-weight: 700;';
                                                $chipStyle = 'background: #10b981; border-color: #10b981; color: #ffffff;';
                                            } else {
                                                $optStyle = 'background: #fdf2f2; border-color: #ef4444; color: #9b1c1c; font-weight: 700;';
                                                $chipStyle = 'background: #ef4444; border-color: #ef4444; color: #ffffff;';
                                            }
                                        } elseif ($optChar === $expected) {
                                            $optStyle = 'background: #f0fdf4; border-color: #86efac; color: #166534;';
                                            $chipStyle = 'background: #86efac; border-color: #86efac; color: #166534;';
                                        }
                                    }
                                @endphp

                                <button type="button" wire:click="$set('answers.{{ $qMC['id'] }}', '{{ $optChar }}')" {{ $result ? 'disabled' : '' }}
                                    style="display: flex; align-items: center; gap: 0.75rem; width: 100%; text-align: left; padding: 0.6rem 1rem; border-radius: 6px; font-size: 0.92rem; cursor: pointer; transition: all 0.2s; outline: none; {{ $optStyle }}">
                                    <span style="font-size: 0.85rem; font-weight: 800; width: 1.6rem; height: 1.6rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; text-transform: uppercase; flex-shrink: 0; {{ $chipStyle }}">{{ $optChar }}</span>
                                    <span>{{ $optText }}</span>
                                </button>
                            @endforeach

                            @if ($result)
                                @php
                                    $detail = collect($result['details'])->firstWhere('id', $qMC['id']);
                                    $correct = $detail['correct'] ?? false;
                                    $expected = $detail['expected'] ?? '';
                                @endphp
                                <div style="font-weight: 800; font-size: 0.9rem; margin-top: 0.25rem; color: {{ $correct ? '#10b981' : '#ef4444' }};">
                                    {{ $correct ? '✓ Richtig' : '✗ Falsch (Lösung: ' . strtoupper($expected) . ')' }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @endfor
    </div>
</section>
