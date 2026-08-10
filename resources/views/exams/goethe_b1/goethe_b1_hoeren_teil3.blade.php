{{-- ==================== GOETHE B1 HÖREN TEIL 3 LAYOUT ==================== --}}
<section class="goethe-section goethe-b1-hoeren-teil3-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $section['title'] ?? 'Hören Teil 3' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em;">
            <span>Wählen Sie: Sind die Aussagen Richtig oder Falsch?</span>
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b1-h3-instruction');
        $questions = collect($section['elements'])->where('type', 'matching')->all();
    @endphp

    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2rem; color: #374151; background: #f8fafc; border-left: 4px solid #a3a3a3; padding: 1rem 1.25rem; font-size: 0.95rem; line-height: 1.6; border-radius: 4px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    <div style="display: flex; flex-direction: column; gap: 1.25rem;">
        @foreach ($questions as $element)
            @php
                $elId = $element['id'];
                $qNum = substr($elId, strrpos($elId, '-') + 1);
                $selected = $answers[$elId] ?? '';
            @endphp
            <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.01);">
                <div style="display: flex; gap: 0.75rem; align-items: flex-start; max-width: 65%;">
                    <span style="background: #111827; color: #ffffff; font-weight: 850; font-size: 0.9rem; padding: 0.25rem 0.55rem; border-radius: 4px; flex-shrink: 0;">{{ $qNum }}</span>
                    <span style="font-size: 0.95rem; font-weight: 700; color: #1f2937; line-height: 1.5;">{{ $element['instruction'] }}</span>
                </div>

                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    @foreach (['richtig', 'falsch'] as $optVal)
                        @php
                            $optSelected = $selected === $optVal;
                            $btnStyle = 'background: #ffffff; border: 1.5px solid #cbd5e1; color: #475569;';
                            if ($optSelected) {
                                $btnStyle = 'background: #eff6ff; border-color: #3b82f6; color: #1d4ed8; font-weight: 800;';
                            }
                            if ($result) {
                                $detail = collect($result['details'])->firstWhere('id', $elId);
                                $correct = $detail['correct'] ?? false;
                                $expected = $detail['expected'] ?? '';
                                if ($optSelected) {
                                    $btnStyle = $correct ? 'background: #e6f4ea; border-color: #10b981; color: #137333; font-weight:800;' : 'background: #fdf2f2; border-color: #ef4444; color: #9b1c1c; font-weight:800;';
                                } elseif ($expected === $optVal) {
                                    $btnStyle = 'background: #e6f4ea; border-color: #10b981; color: #137333; font-weight:800;';
                                }
                            }
                        @endphp
                        <button type="button" wire:click="$set('answers.{{ $elId }}', '{{ $optVal }}')" {{ $result ? 'disabled' : '' }}
                            style="padding: 0.4rem 1.25rem; border-radius: 6px; font-size: 0.88rem; font-weight: 700; cursor: pointer; transition: all 0.2s; min-width: 90px; text-align: center; outline: none; {{ $btnStyle }}">
                            {{ ucfirst($optVal) }}
                        </button>
                    @endforeach

                    @if ($result)
                        @php
                            $detail = collect($result['details'])->firstWhere('id', $elId);
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
        @endforeach
    </div>
</section>
