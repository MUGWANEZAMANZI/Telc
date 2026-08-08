{{-- ==================== GOETHE B2 TEIL 2 LAYOUT ==================== --}}
<section class="goethe-section goethe-b2-teil2-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%;">
    <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
        <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $section['title'] ?? 'Lesen Teil 2' }}</h2>
        <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 1rem;">
            <span>vorgeschlagene Arbeitszeit: 12 Minuten</span>
        </div>
    </div>

    @php
        $instrEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-l2-instruction');
        $imageEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-l2-image');
        $inlineGapEl = collect($section['elements'])->firstWhere('type', 'inline_gap');
        $sentencesEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-l2-sentences');
    @endphp

    @if ($instrEl && $instrEl['content'])
        <div class="goethe-instructions" style="margin-bottom: 2rem; color: #374151; background: #f8fafc; border-left: 4px solid #a3a3a3; padding: 1rem 1.25rem; font-size: 0.95rem; line-height: 1.6; border-radius: 4px; font-weight: 500;">
            {!! nl2br(e($instrEl['content'])) !!}
        </div>
    @endif

    {{-- Center Box for the Article --}}
    <div style="max-width: 820px; margin: 0 auto; border: 1.5px solid #d1d5db; border-radius: 8px; padding: 2.5rem; background: #ffffff; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.03);">
        <div style="text-align: center; margin-bottom: 1.5rem;">
            <h1 style="font-family: Georgia, serif; font-size: 2.2rem; font-weight: 700; color: #111827; margin: 0 0 0.5rem 0;">Reisefieber</h1>
            <p style="font-size: 1rem; font-weight: 700; color: #374151; margin: 0;">Heute verreist fast jeder, früher aber kaum jemand.</p>
        </div>

        @if ($imageEl && $imageEl['content'])
            <div style="width: 100%; height: 260px; overflow: hidden; border-radius: 6px; margin-bottom: 2rem; border: 1px solid #e5e7eb;">
                <img src="{{ $imageEl['content'] }}" style="width: 100%; height: 100%; object-fit: cover;" alt="Airport Header">
            </div>
        @endif

        {{-- Render Gapped Passage --}}
        @if ($inlineGapEl)
            @php
                $gaps = $this->gapsFor($inlineGapEl);
                $parts = preg_split('/--(\d+)--/', $inlineGapEl['content'] ?? '', -1, PREG_SPLIT_DELIM_CAPTURE);
            @endphp
            <div class="goethe-article-body" style="font-family: Georgia, serif; font-size: 1.05rem; line-height: 1.95; color: #1f2937; text-align: justify;">
                @foreach ($parts as $i => $part)
                    @if ($i % 2 === 0)
                        <span>{!! nl2br(e($part)) !!}</span>
                    @else
                        @php
                            $gapIdx = (int) $part;
                            $gapDef = $gaps[$gapIdx] ?? null;
                        @endphp
                        @if ($gapIdx === 0)
                            <span style="display: inline-block; background: #f3f4f6; border: 1px solid #d1d5db; border-radius: 4px; padding: 0.1rem 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 0.88rem; font-weight: 800; color: #374151; margin: 0 0.15rem; cursor: not-allowed;" title="Beispiel-Lösung bereits eingesetzt">
                                [ ...0... ]
                            </span>
                        @elseif ($gapDef)
                            @php
                                $optList = array_filter(array_map('trim', explode(',', $gapDef['options'] ?? 'a,b,c,d,e,f,g,h')));
                                $current = $answers[$inlineGapEl['id']][$gapIdx] ?? '';
                                $gapCorrect = null;
                                $gapExpected = $gapDef['answer'] ?? '';
                                if ($result) {
                                    $gapDetail = collect($result['details'])->firstWhere('id', $inlineGapEl['id'].'.gap.'.$gapIdx);
                                    if ($gapDetail) {
                                        $gapCorrect = $gapDetail['correct'] ?? null;
                                        $gapExpected = $gapDetail['expected'] ?? $gapExpected;
                                    }
                                }
                                $gapCls = '';
                                if ($result) {
                                    $gapCls = $gapCorrect ? 'correct' : 'wrong';
                                }
                            @endphp
                            @php
                                $gapBorder = '#cbd5e1';
                                $current = $answers[$inlineGapEl['id']][$gapIdx] ?? '';
                                if ($result) {
                                    $gapBorder = $gapCorrect ? '#16a34a' : '#dc2626';
                                }
                            @endphp
                            <span style="display: inline-block; position: relative; margin: 0 0.2rem; font-family: 'Plus Jakarta Sans', sans-serif; vertical-align: middle;">
                                <span style="font-size: 0.72rem; font-weight: 800; color: #6b7280; vertical-align: super; margin-right: 2px;">[{{ $gapIdx }}]</span>
                                <select wire:model.live="answers.{{ $inlineGapEl['id'] }}.{{ $gapIdx }}" @disabled($result)
                                    @class(['cloze-select', 'correct' => $gapCls === 'correct', 'wrong' => $gapCls === 'wrong'])
                                    style="padding: 0.2rem 0.5rem; border: 1.5px solid {{ $gapBorder }}; border-radius: 4px; font-size: 0.9rem; font-weight: 700; background: #ffffff; cursor: pointer; outline: none; @if($result) pointer-events: none; @endif">
                                    <option value="">Lücke {{ $gapIdx }}</option>
                                    @foreach ($optList as $opt)
                                        <option value="{{ $opt }}"
                                            @if($result && $opt === $gapExpected)
                                                style="color: #16a34a; font-weight: bold; background-color: #f0fdf4;"
                                            @elseif($result && $current === $opt && !$gapCorrect)
                                                style="color: #dc2626; text-decoration: line-through; background-color: #fdf2f2;"
                                            @endif
                                        >{{ strtoupper($opt) }}</option>
                                    @endforeach
                                </select>
                                @if ($result)
                                    @if ($gapCorrect)
                                        <span style="color: #16a34a; font-weight: 700; font-size: 0.95rem; margin-left: 0.25rem;">✓</span>
                                    @else
                                        @if ($current !== '')
                                            <span style="color: #dc2626; font-weight: 700; font-size: 0.95rem; margin-left: 0.25rem;">✗</span>
                                        @endif
                                        <span style="color: #16a34a; font-weight: 700; font-size: 0.85rem; margin-left: 0.25rem; border: 1px solid #16a34a; padding: 0.1rem 0.3rem; border-radius: 3px; background-color: #f0fdf4;">
                                            Lösung: {{ strtoupper($gapExpected) }}
                                        </span>
                                    @endif
                                @endif
                            </span>
                        @endif
                    @endif
                @endforeach
            </div>
        @endif
    </div>

    {{-- Sentences choice list at the bottom --}}
    @if ($sentencesEl && $sentencesEl['content'])
        <div style="max-width: 820px; margin: 2rem auto 0; background: #f8fafc; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 1.5rem;">
            <h3 style="font-size: 1.05rem; font-weight: 800; border-bottom: 1.5px solid #cbd5e1; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #1e293b; text-transform: uppercase; letter-spacing: 0.03em;">Sätze zur Auswahl</h3>
            <div style="display: flex; flex-direction: column; gap: 0.85rem;">
                @php
                    $lines = explode("\n", trim($sentencesEl['content']));
                @endphp
                @foreach ($lines as $line)
                    @php
                        $trimmed = trim($line);
                        if (empty($trimmed)) continue;
                        
                        $firstChar = strtolower(substr($trimmed, 0, 1));
                        $isUsed = false;
                        
                        // Check if this option is chosen in the gaps
                        if ($inlineGapEl && is_numeric($firstChar) === false) {
                            $selectedAnswers = $answers[$inlineGapEl['id']] ?? [];
                            foreach ($selectedAnswers as $ansVal) {
                                if (strtolower($ansVal) === $firstChar) {
                                    $isUsed = true;
                                    break;
                                }
                            }
                        }
                    @endphp
                    <div style="display: flex; align-items: flex-start; gap: 1rem; padding: 0.5rem 0.75rem; background: #ffffff; border: 1px solid #e2e8f0; border-radius: 6px; box-shadow: 0 1px 2px rgba(0,0,0,0.01); @if($isUsed) opacity: 0.45; text-decoration: line-through; @endif">
                        <span style="background: #e2e8f0; color: #1e293b; font-weight: 800; font-size: 0.9rem; width: 1.7rem; height: 1.7rem; display: flex; align-items: center; justify-content: center; border-radius: 4px; text-transform: uppercase; flex-shrink: 0;">{{ substr($trimmed, 0, 1) }}</span>
                        <span style="font-size: 0.92rem; color: #374151; line-height: 1.5; font-weight: 550;">{{ trim(substr($trimmed, 1)) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>
