{{-- ==================== GOETHE B2 TEIL 3 LAYOUT ==================== --}}
<section class="goethe-section goethe-b2-teil3-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $section['title'] ?? 'Lesen Teil 3' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 1rem;">
            <span>vorgeschlagene Arbeitszeit: 12 Minuten</span>
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-l3-instruction');
        $imageEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-l3-image');
        $articleEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-l3-article');
        $mcQuestions = collect($section['elements'])->where('type', 'multiple_choice')->all();
    @endphp

    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2rem; color: #374151; background: #f8fafc; border-left: 4px solid #a3a3a3; padding: 1rem 1.25rem; font-size: 0.95rem; line-height: 1.6; border-radius: 4px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    {{-- Split screen: Left = Article, Right = MC Questions --}}
    <div style="display: flex; gap: 2.5rem; flex-wrap: wrap; width: 100%;">
        {{-- Left column: Article --}}
        <div style="flex: 1.2; min-width: 400px; background: #ffffff; border: 1.5px solid #d1d5db; border-radius: 8px; padding: 2rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
            @if ($articleEl)
                <div style="text-align: center; margin-bottom: 1.5rem;">
                    <h1 style="font-family: Georgia, serif; font-size: 2.2rem; font-weight: 700; color: #111827; margin: 0 0 0.75rem 0;">{{ $articleEl['label'] ?? 'Doktor Google' }}</h1>
                    @if (!empty($articleEl['instruction']))
                        <p style="font-size: 1rem; font-weight: 700; color: #111827; margin: 0; line-height: 1.6; text-align: justify; font-family: Georgia, serif;">{{ $articleEl['instruction'] }}</p>
                    @endif
                </div>
            @endif

            @if ($imageEl && $imageEl['content'])
                <div style="width: 100%; height: 260px; overflow: hidden; border-radius: 6px; margin-bottom: 2rem; border: 1px solid #e5e7eb;">
                    <img src="{{ $imageEl['content'] }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Doktor Google Image">
                </div>
            @endif

            @if ($articleEl)
                <div class="goethe-article-body" style="font-family: Georgia, serif; font-size: 1.05rem; line-height: 1.95; color: #1f2937; text-align: justify;">
                    {!! nl2br(e($articleEl['content'])) !!}
                </div>
            @endif
        </div>

        {{-- Right column: Multiple Choice --}}
        <div style="flex: 1; min-width: 320px; display: flex; flex-direction: column; gap: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; border-bottom: 1.5px solid #e2e8f0; padding-bottom: 0.5rem; margin-bottom: 0.5rem; color: #1f2937; text-transform: uppercase; letter-spacing: 0.05em;">Aufgaben</h3>

            {{-- Example 0 --}}
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem;">
                <div style="display: flex; align-items: flex-start; gap: 0.75rem; margin-bottom: 0.75rem;">
                    <span style="background: #6b7280; color: #ffffff; font-weight: 850; font-size: 0.95rem; width: 1.8rem; height: 1.8rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0;">0</span>
                    <div style="font-size: 0.95rem; font-weight: 700; color: #374151;">Immer mehr Kranke in Deutschland ...</div>
                </div>
                <div style="display: flex; flex-direction: column; gap: 0.5rem; padding-left: 2.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.92rem; color: #6b7280; cursor: not-allowed;">
                        <span style="width: 1.5rem; height: 1.5rem; border: 1.5px solid #cbd5e1; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; font-weight: 800; font-size: 0.8rem;">A</span>
                        <span>lassen sich lieber zu Hause behandeln.</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.92rem; color: #111827; font-weight: 600; cursor: not-allowed;">
                        <span style="width: 1.5rem; height: 1.5rem; border: 1.5px solid #111827; background: #111827; color: #ffffff; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; font-weight: 800; font-size: 0.8rem;">B</span>
                        <span>vertrauen auf Hilfe im Internet. (Lösung)</span>
                    </label>
                    <label style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.92rem; color: #6b7280; cursor: not-allowed;">
                        <span style="width: 1.5rem; height: 1.5rem; border: 1.5px solid #cbd5e1; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; font-weight: 800; font-size: 0.8rem;">C</span>
                        <span>werden in Wartezimmern krank.</span>
                    </label>
                </div>
            </div>

            {{-- Actual Questions --}}
            @foreach ($mcQuestions as $element)
                @php
                    $elId = $element['id'];
                    $qNum = substr($elId, strrpos($elId, '-') + 1);
                    $optionsText = $element['options'] ?? '';
                    $optionsArray = [];
                    if (str_contains($optionsText, '|')) {
                        foreach (explode(',', $optionsText) as $optionPart) {
                            $bits = explode('|', $optionPart, 2);
                            if (count($bits) === 2) {
                                $optionsArray[trim($bits[0])] = trim($bits[1]);
                            }
                        }
                    } else {
                        foreach (explode(',', $optionsText) as $opt) {
                            $optionsArray[trim($opt)] = trim($opt);
                        }
                    }
                    $selected = $answers[$elId] ?? '';
                    $isCorrect = null;
                    $expected = $element['answer'] ?? '';
                    if ($result) {
                        $detail = collect($result['details'])->firstWhere('id', $elId);
                        if ($detail) {
                            $isCorrect = $detail['correct'] ?? null;
                            $expected = $detail['expected'] ?? $expected;
                        }
                    }
                @endphp
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; box-shadow: 0 1px 3px rgba(0,0,0,0.02); display: flex; flex-direction: column; gap: 0.75rem;">
                    <div style="display: flex; align-items: flex-start; gap: 0.75rem;">
                        <span style="background: #111827; color: #ffffff; font-weight: 850; font-size: 0.95rem; width: 1.8rem; height: 1.8rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%; flex-shrink: 0; margin-top: 0.1rem;">{{ $qNum }}</span>
                        <div style="font-size: 0.98rem; font-weight: 700; color: #1f2937; line-height: 1.4;">{{ $element['instruction'] ?? '' }}</div>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 0.65rem; padding-left: 2.5rem; margin-top: 0.25rem;">
                        @foreach ($optionsArray as $key => $text)
                            @php
                                $isThisSelected = strtolower($selected) === strtolower($key);
                                $isThisExpected = strtolower($expected) === strtolower($key);
                                
                                $borderCol = '#cbd5e1';
                                $bgCol = '#ffffff';
                                $textCol = '#374151';
                                $fontW = '550';
                                $boxBg = '#ffffff';
                                $boxBorder = '#cbd5e1';
                                $boxText = strtoupper($key);
                                
                                if ($isThisSelected) {
                                    $borderCol = '#111827';
                                    $boxBg = '#111827';
                                    $boxBorder = '#111827';
                                    $boxText = strtoupper($key);
                                    $fontW = '700';
                                }
                                
                                if ($result) {
                                    if ($isThisExpected) {
                                        $borderCol = '#16a34a';
                                        $boxBg = '#16a34a';
                                        $boxBorder = '#16a34a';
                                        $boxText = '✓';
                                        $fontW = '700';
                                        $textCol = '#15803d';
                                    } elseif ($isThisSelected && !$isCorrect) {
                                        $borderCol = '#dc2626';
                                        $boxBg = '#dc2626';
                                        $boxBorder = '#dc2626';
                                        $boxText = '✗';
                                        $fontW = '700';
                                        $textCol = '#b91c1c';
                                    }
                                }

                                $labelBg = 'transparent';
                                $labelBorder = 'transparent';
                                
                                if ($result) {
                                    if ($isThisExpected) {
                                        $labelBg = '#f0fdf4';
                                        $labelBorder = '#16a34a';
                                    } elseif ($isThisSelected) {
                                        $labelBg = '#fdf2f2';
                                        $labelBorder = '#dc2626';
                                    }
                                } else {
                                    if ($isThisSelected) {
                                        $labelBg = '#f8fafc';
                                        $labelBorder = '#111827';
                                    }
                                }
                            @endphp
                            <label style="display: flex; align-items: center; gap: 0.85rem; font-size: 0.92rem; color: {{ $textCol }}; font-weight: {{ $fontW }}; cursor: {{ $result ? 'not-allowed' : 'pointer' }}; padding: 0.4rem 0.6rem; border-radius: 6px; transition: all 0.15s; margin-left: -0.6rem; background: {{ $labelBg }}; border: 1px solid {{ $labelBorder }};">
                                <input type="radio" name="radio-{{ $elId }}" value="{{ $key }}" wire:model.live="answers.{{ $elId }}" @disabled($result) style="display: none;">
                                <span style="width: 1.6rem; height: 1.6rem; border: 1.5px solid {{ $boxBorder }}; background: {{ $boxBg }}; color: @if($boxBg === '#ffffff') #4b5563 @else #ffffff @endif; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; font-weight: 800; font-size: 0.85rem; transition: all 0.15s; flex-shrink: 0;">
                                    {{ $boxText }}
                                </span>
                                <span>{{ $text }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
