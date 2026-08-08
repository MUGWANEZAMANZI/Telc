{{-- ==================== GOETHE B2 TEIL 4 LAYOUT ==================== --}}
<section class="goethe-section goethe-b2-teil4-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $section['title'] ?? 'Lesen Teil 4' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 1rem;">
            <span>vorgeschlagene Arbeitszeit: 12 Minuten</span>
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-l4-instruction');
        $opinions = collect($section['elements'])->where('type', 'goethe_portrait')->all();
        $questions = collect($section['elements'])->where('type', 'matching')->all();
    @endphp

    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2rem; color: #374151; background: #f8fafc; border-left: 4px solid #a3a3a3; padding: 1rem 1.25rem; font-size: 0.95rem; line-height: 1.6; border-radius: 4px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    <div style="display: flex; gap: 2rem; flex-wrap: wrap; width: 100%;">
        {{-- Left column: Headlines (Questions) --}}
        <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 1rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 0.5rem; color: #1f2937; text-transform: uppercase; letter-spacing: 0.05em;">Überschriften</h3>

            {{-- Example 0 --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; display: flex; flex-direction: column; gap: 0.5rem;">
                <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                    <span style="background: #6b7280; color: #ffffff; font-weight: 850; font-size: 0.95rem; width: 1.8rem; height: 1.8rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0; margin-top: 0.1rem;">0</span>
                    <div style="font-size: 0.95rem; font-weight: 650; color: #4b5563; line-height: 1.5;">Familie und Beruf kann man vereinbaren</div>
                </div>
                <div style="display: flex; align-items: center; gap: 0.75rem; padding-left: 2.5rem;">
                    <span style="font-size: 0.82rem; font-weight: 700; color: #4b5563; text-transform: uppercase; letter-spacing: 0.03em;">Lösung:</span>
                    <span style="background: #e2e8f0; color: #111827; font-weight: 800; font-size: 0.88rem; padding: 0.2rem 0.5rem; border-radius: 4px;">A</span>
                </div>
            </div>

            @foreach ($questions as $element)
                @php
                    $elId = $element['id'];
                    $qNum = substr($elId, strrpos($elId, '-') + 1);
                    $matchOptions = method_exists($this, 'optionsFor') ? $this->optionsFor($element) : array_map('trim', explode(',', $element['options'] ?? 'a,b,c,d,e,f,g,h'));
                @endphp
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                        <span style="background: #111827; color: #ffffff; font-weight: 850; font-size: 0.95rem; width: 1.8rem; height: 1.8rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0; margin-top: 0.1rem;">{{ $qNum }}</span>
                        <div style="font-size: 0.95rem; font-weight: 550; color: #1f2937; line-height: 1.5;">{{ $element['instruction'] ?? '' }}</div>
                    </div>
                    
                    <div style="display: flex; align-items: center; gap: 0.75rem; padding-left: 2.5rem; margin-top: 0.25rem;">
                        <span style="font-size: 0.82rem; font-weight: 700; color: #4b5563; text-transform: uppercase; letter-spacing: 0.03em;">Lösung:</span>
                        @php
                            $selectBorder = '#cbd5e1';
                            $detail = $result ? collect($result['details'])->firstWhere('id', $elId) : null;
                            $current = $answers[$elId] ?? '';
                            $expected = $element['answer'] ?? '';
                            if ($result && $detail) {
                                $selectBorder = $detail['correct'] ? '#16a34a' : '#dc2626';
                                $expected = $detail['expected'] ?? $expected;
                            }
                        @endphp
                        <select class="cloze-select" wire:model.change="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }} style="padding: 0.35rem 0.65rem; border: 1.5px solid {{ $selectBorder }}; border-radius: 6px; background: #ffffff; font-size: 0.88rem; font-weight: 700; color: #1f2937; min-width: 80px; transition: border-color 0.15s; outline: none; cursor: pointer;">
                            <option value="">---</option>
                            @foreach ($matchOptions as $opt)
                                <option value="{{ $opt }}"
                                    @if($result && $opt === $expected)
                                        style="color: #16a34a; font-weight: bold; background-color: #f0fdf4;"
                                    @elseif($result && $current === $opt && !$detail['correct'])
                                        style="color: #dc2626; text-decoration: line-through; background-color: #fdf2f2;"
                                    @endif
                                >{{ strtoupper($opt) }}</option>
                            @endforeach
                        </select>
                        @if ($result && $detail)
                            @if ($detail['correct'])
                                <span style="font-weight: 700; font-size: 0.9rem; margin-left: 0.5rem; color: #16a34a;">
                                    ✓ Richtig
                                </span>
                            @else
                                @if ($current !== '')
                                    <span style="font-weight: 700; font-size: 0.9rem; margin-left: 0.5rem; color: #dc2626;">
                                        ✗ Falsch
                                    </span>
                                @endif
                                <span style="font-weight: 700; font-size: 0.9rem; margin-left: 0.5rem; color: #16a34a; border: 1px solid #16a34a; padding: 0.1rem 0.4rem; border-radius: 4px; background-color: #f0fdf4;">
                                    Lösung: {{ strtoupper($expected) }}
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Right column: Opinions list --}}
        <div style="flex: 1.3; min-width: 400px; display: flex; flex-direction: column; gap: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 0.5rem; color: #1f2937; text-transform: uppercase; letter-spacing: 0.05em;">Meinungen</h3>
            
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @foreach ($opinions as $opinion)
                    @php
                        $charLetter = strtolower(substr($opinion['label'], 0, 1));
                        $isUsed = false;
                        
                        $selectedAnswers = $answers ?? [];
                        foreach ($questions as $q) {
                            if (isset($selectedAnswers[$q['id']]) && strtolower($selectedAnswers[$q['id']]) === $charLetter) {
                                $isUsed = true;
                                break;
                            }
                        }
                    @endphp
                    <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; gap: 1.25rem; align-items: flex-start; @if($isUsed) opacity: 0.45; text-decoration: line-through; @endif">
                        <span style="background: #111827; color: #ffffff; font-weight: 850; font-size: 0.95rem; width: 1.8rem; height: 1.8rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; flex-shrink: 0; text-transform: uppercase;">{{ $charLetter }}</span>
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; flex: 1;">
                            <p style="font-size: 0.88rem; color: #374151; line-height: 1.65; text-align: justify; margin: 0; font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;">{{ $opinion['content'] }}</p>
                            <span style="font-size: 0.78rem; font-style: italic; color: #6b7280; text-align: right; display: block; font-weight: 600;">{{ substr($opinion['label'], 2) }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
