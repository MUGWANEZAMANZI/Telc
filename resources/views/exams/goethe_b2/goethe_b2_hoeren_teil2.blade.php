{{-- ==================== GOETHE B2 HÖREN TEIL 2 LAYOUT ==================== --}}
<section class="goethe-section goethe-b2-hoeren-teil2-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $section['title'] ?? 'Hören Teil 2' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em;">
            <span>Wählen Sie bei jeder Aufgabe die richtige Lösung</span>
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-h2-instruction');
        $questions = collect($section['elements'])->where('type', 'multiple_choice')->all();
    @endphp

    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2rem; color: #374151; background: #f8fafc; border-left: 4px solid #a3a3a3; padding: 1rem 1.25rem; font-size: 0.95rem; line-height: 1.6; border-radius: 4px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        @foreach ($questions as $element)
            @php
                $elId = $element['id'];
                $qNum = substr($elId, strrpos($elId, '-') + 1);
                $mcOptions = parseMultipleChoiceOptions($element['options'] ?? '');
            @endphp
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.01);">
                <div style="display: flex; gap: 0.75rem; align-items: flex-start; border-bottom: 1.5px solid #f1f5f9; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                    <span style="background: #111827; color: #ffffff; font-weight: 850; font-size: 0.9rem; padding: 0.25rem 0.55rem; border-radius: 4px; flex-shrink: 0;">{{ $qNum }}</span>
                    <span style="font-size: 1rem; font-weight: 700; color: #1f2937; line-height: 1.5;">{{ $element['instruction'] }}</span>
                </div>

                <div style="display: flex; flex-direction: column; gap: 0.5rem; padding-left: 2rem;">
                    @foreach ($mcOptions as $optChar => $optText)
                        @php
                            $selected = ($answers[$elId] ?? '') === $optChar;
                            $optStyle = 'background: #ffffff; border: 1.5px solid #e2e8f0; color: #374151;';
                            $chipStyle = 'background: #f1f5f9; border: 1px solid #cbd5e1; color: #475569;';
                            
                            if ($selected) {
                                $optStyle = 'background: #eff6ff; border-color: #3b82f6; color: #1e3a8a; font-weight: 700;';
                                $chipStyle = 'background: #3b82f6; border-color: #3b82f6; color: #ffffff;';
                            }

                            if ($result) {
                                $detail = collect($result['details'])->firstWhere('id', $elId);
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

                        <button type="button" wire:click="$set('answers.{{ $elId }}', '{{ $optChar }}')" {{ $result ? 'disabled' : '' }}
                            style="display: flex; align-items: center; gap: 0.75rem; width: 100%; text-align: left; padding: 0.6rem 1rem; border-radius: 6px; font-size: 0.92rem; cursor: pointer; transition: all 0.2s; outline: none; {{ $optStyle }}">
                            <span style="font-size: 0.85rem; font-weight: 800; width: 1.6rem; height: 1.6rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; text-transform: uppercase; flex-shrink: 0; {{ $chipStyle }}">{{ $optChar }}</span>
                            <span>{{ $optText }}</span>
                        </button>
                    @endforeach

                    @if ($result)
                        @php
                            $detail = collect($result['details'])->firstWhere('id', $elId);
                            $correct = $detail['correct'] ?? false;
                            $expected = $detail['expected'] ?? '';
                        @endphp
                        <div style="font-weight: 800; font-size: 0.9rem; margin-top: 0.25rem; color: {{ $correct ? '#10b981' : '#ef4444' }};">
                            {{ $correct ? '✓ Richtig' : '✗ Falsch (Lösung: ' . strtoupper($expected) . ')' }}
                        </div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</section>
