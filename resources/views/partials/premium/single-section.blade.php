@php
if (!function_exists('parseTeilContent')) {
    function parseTeilContent(string $content): array {
        $lines = explode("\n", $content);
        $instruction = '';
        $options = [];
        $texts = [];
        
        $currentTextNum = null;
        $currentTextContent = '';
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '') {
                if ($currentTextNum !== null) {
                    $currentTextContent .= "\n";
                }
                continue;
            }
            
            if (preg_match('/^[-=*_]{3,}$/', $trimmed)) {
                continue;
            }
            
            if (preg_match('/^(Überschriften|Situationen|Angebote|Info-Texte|Texte|Personen):$/iu', $trimmed)) {
                continue;
            }
            
            if (preg_match('/^([a-l])(\s{2,}|\s*[\)::-]\s*)(.+)$/iu', $trimmed, $matches)) {
                if ($currentTextNum !== null) {
                    $texts[$currentTextNum] = trim($currentTextContent);
                    $currentTextNum = null;
                    $currentTextContent = '';
                }
                $options[strtolower($matches[1])] = trim($matches[3]);
                continue;
            }
            
            if (preg_match('/^([1-9]|1[0-9]|20)$/', $trimmed, $matches)) {
                if ($currentTextNum !== null) {
                    $texts[$currentTextNum] = trim($currentTextContent);
                }
                $currentTextNum = (int)$matches[1];
                $currentTextContent = '';
                continue;
            }
            
            if (preg_match('/^(Person|Situation|Aufgabe)?\s*([1-9]|1[0-9]|20)(\s*[\s::.-]\s*)(.+)$/iu', $trimmed, $matches)) {
                if ($currentTextNum !== null) {
                    $texts[$currentTextNum] = trim($currentTextContent);
                }
                $currentTextNum = (int)$matches[2];
                $currentTextContent = $matches[4];
                continue;
            }
            
            if ($currentTextNum !== null) {
                $currentTextContent .= ($currentTextContent === '' ? '' : "\n") . $trimmed;
            } else {
                if (empty($options) && empty($texts)) {
                    $instruction .= ($instruction === '' ? '' : "\n") . $trimmed;
                }
            }
        }
        
        if ($currentTextNum !== null && trim($currentTextContent) !== '') {
            $texts[$currentTextNum] = trim($currentTextContent);
        }
        
        return [
            'instruction' => trim($instruction),
            'options' => $options,
            'texts' => $texts,
        ];
    }
}
@endphp

@php
    $layoutStyle = $section['layout_style'] ?? 'default';
    if ($layoutStyle === 'default') {
        $sectionTitleLower = strtolower($section['title'] ?? '');
        if (str_contains($sectionTitleLower, 'teil 1')) {
            $layoutStyle = 'teil1';
        } elseif (str_contains($sectionTitleLower, 'teil 2')) {
            $layoutStyle = 'teil2';
        } elseif (str_contains($sectionTitleLower, 'teil 3')) {
            $layoutStyle = 'teil3';
        }
    }
@endphp

@if ($layoutStyle === 'teil1')
    {{-- ==================== TEIL 1 LAYOUT ==================== --}}
    <section class="c1-section telc-teil1-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); width: 100%;">
        <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $section['title'] ?? 'Leseverstehen Teil 1' }}</h2>
            <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 1rem;">
                <span>90 Minuten Leseverstehen und Sprachbausteine</span>
                <span style="background: #1a1a1a; color: #ffffff; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">S. {{ $sectionIdx + 6 }}</span>
            </div>
        </div>

        @php
            $textEl = collect($section['elements'])->firstWhere('type', 'text');
            $questions = collect($section['elements'])->filter(fn($el) => $el['type'] !== 'text')->values()->all();
            $parsed = $textEl ? parseTeilContent($textEl['content'] ?? '') : ['instruction' => '', 'options' => [], 'texts' => []];
            $instruction = $parsed['instruction'] ?: ($textEl['instruction'] ?? ($section['instruction'] ?? ''));
        @endphp

        @if ($instruction)
            <div class="c1-instructions" style="margin-bottom: 1.5rem; font-style: italic; color: #555; background: #fafafa; border-left: 3px solid #1a1a1a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6;">
                {!! nl2br(e($instruction)) !!}
            </div>
        @endif

        {{-- TOP SECTION: Selection Options a-j vertical rectangular bars --}}
        @if (!empty($parsed['options']))
            <div class="teil1-options-top" style="display: flex; flex-direction: column; gap: 0.6rem; margin-bottom: 2.5rem; background: #ffffff; padding: 1rem; border-radius: 6px; border: 1px solid #e2e8f0; width: 100%;">
                <h4 style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: #71717a; letter-spacing: 0.05em; margin: 0 0 0.75rem 0;">Überschriften</h4>
                @foreach ($parsed['options'] as $letter => $optText)
                    <div class="option-strip-item" style="display:flex; align-items:center; gap:1.25rem; background:#f4f4f5; border:1px solid #e4e4e7; border-radius:4px; padding:0.5rem 1rem;">
                        <span style="background:#e4e4e7; color:#18181b; font-weight:800; font-size:0.95rem; width:1.85rem; height:1.85rem; display:flex; align-items:center; justify-content:center; border-radius:4px; text-transform:uppercase; border: 1px solid #d4d4d8; flex-shrink: 0;">{{ $letter }}</span>
                        <span style="color:#27272a; font-weight:700; font-size:0.98rem; font-family: var(--font-sans);">{{ $optText }}</span>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- BOTTOM SECTION: Reading Texts 1-5 --}}
        @if (!empty($parsed['texts']))
            <div class="teil1-texts-bottom" style="margin-bottom: 2.5rem;">
                @foreach ($parsed['texts'] as $num => $textBlock)
                    <div class="paragraph-styled-block" style="margin-bottom: 2.5rem; border-bottom: 1px dashed #e2e8f0; padding-bottom: 2rem;">
                        <div style="display:flex; align-items:center; gap:0.75rem; margin-bottom:1rem;">
                            <span style="background:#d8232a; color:#ffffff; font-weight:800; font-size:1.15rem; width:2.4rem; height:2.4rem; display:flex; align-items:center; justify-content:center; border-radius:50%;">{{ $num }}</span>
                        </div>
                        <div style="color:#1a1a1a; line-height:1.8; font-size:0.95rem; column-count: 2; column-gap: 2rem; text-align: justify; font-family: Georgia, serif;">
                            {!! nl2br(e($textBlock)) !!}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- INTERACTIVE MATCH SELECTORS --}}
        @if (!empty($questions))
            <div class="teil1-selectors" style="background: #fafafa; border: 1px solid #e5e5e5; border-radius: 8px; padding: 1.5rem; margin-top: 2rem;">
                <h4 style="font-size: 0.9rem; font-weight: 800; text-transform: uppercase; color: #27272a; margin: 0 0 1rem 0; border-bottom: 2px solid #27272a; padding-bottom: 0.5rem;">Antwortbogen (Zuordnung Aufgaben 1-5)</h4>
                @foreach ($questions as $element)
                    @php
                        $elId = $element['id'];
                        $matchOptions = method_exists($this, 'optionsFor') ? $this->optionsFor($element) : array_map('trim', explode(',', $element['options'] ?? ''));
                    @endphp
                    <div style="display:flex;flex-direction:row;align-items:center;gap:1.5rem;margin-bottom:0.75rem;font-family:'Plus Jakarta Sans',sans-serif; background: #ffffff; padding: 0.75rem 1.25rem; border-radius: 6px; border: 1px solid #e5e5e5; box-shadow: 0 1px 2px rgba(0,0,0,0.01);">
                        <span style="font-weight:700; color: #1a1a1a; min-width: 120px;">{{ $element['label'] ?? '' }}</span>
                        <select class="cloze-select" wire:model.change="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }} style="padding: 0.4rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; background: #ffffff; font-size: 0.9rem;">
                            <option value="">---</option>
                            @foreach ($matchOptions as $opt)
                                <option value="{{ $opt }}">{{ strtoupper($opt) }}</option>
                            @endforeach
                        </select>
                        @if ($result)
                            @php $detail = collect($result['details'])->firstWhere('id', $elId); @endphp
                            @if ($detail)
                                <span style="margin-left: auto; font-weight: bold; color: {{ $detail['correct'] ? '#16a34a' : '#dc2626' }}; font-size: 0.9rem;">
                                    {{ $detail['correct'] ? '✓ Richtig' : '✗ Falsch (Richtig: ' . strtoupper($detail['expected']) . ')' }}
                                </span>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>

@elseif ($layoutStyle === 'teil2')
    {{-- ==================== TEIL 2 LAYOUT ==================== --}}
    <section class="c1-section telc-teil2-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); width: 100%;">
        <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $section['title'] ?? 'Leseverstehen Teil 2' }}</h2>
            <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 1rem;">
                <span>90 Minuten Leseverstehen und Sprachbausteine</span>
                <span style="background: #1a1a1a; color: #ffffff; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">S. {{ $sectionIdx + 6 }}</span>
            </div>
        </div>

        @php
            $textEl = collect($section['elements'])->firstWhere('type', 'text');
            $questions = collect($section['elements'])->filter(fn($el) => $el['type'] !== 'text')->values()->all();
            $instruction = $textEl['instruction'] ?? ($section['instruction'] ?? '');
        @endphp

        @if ($instruction)
            <div class="c1-instructions" style="margin-bottom: 1.5rem; font-style: italic; color: #555; background: #fafafa; border-left: 3px solid #1a1a1a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6;">
                {!! nl2br(e($instruction)) !!}
            </div>
        @endif

        {{-- TOP SECTION: Reading Articles in clean container with subtle border/drop shadow --}}
        @if ($textEl)
            <article class="c1-passage" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 2rem; margin-bottom: 2.5rem; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03); font-family: Georgia, serif; line-height: 1.8; color: #27272a; text-align: justify;">
                {!! renderPremiumPassage($textEl['content'] ?? '') !!}
            </article>
        @endif

        {{-- BOTTOM SECTION: MC Questions 6-10 --}}
        @if (!empty($questions))
            <div class="teil2-questions" style="display: flex; flex-direction: column; gap: 1.5rem; width: 100%;">
                @foreach ($questions as $element)
                    @php
                        $elId = $element['id'];
                        $rawOptions = method_exists($this, 'optionsFor') ? $this->optionsFor($element) : array_map('trim', explode(',', $element['options'] ?? ''));
                        
                        // Parse instruction/question text
                        $lines = explode("\n", $element['instruction'] ?? '');
                        $questionTitle = trim($lines[0] ?? '');
                        $optionLines = array_slice($lines, 1);
                        $parsedOptions = [];
                        foreach ($optionLines as $oLine) {
                            $oLine = trim($oLine);
                            if (preg_match('/^([a-c])[\s\):.-]+(.*)$/iu', $oLine, $m)) {
                                $parsedOptions[strtolower($m[1])] = trim($m[2]);
                            }
                        }
                    @endphp
                    <fieldset style="border:1px solid #e5e5e5; padding: 1.25rem 1.5rem; border-radius: 8px; background: #fafafa; margin: 0; box-shadow: 0 1px 2px rgba(0,0,0,0.02); width: 100%;">
                        <legend style="font-family:'Plus Jakarta Sans',sans-serif; font-size:1.1rem; font-weight:800; color:#1a1a1a; padding: 0 0.5rem; margin-bottom: 0.5rem;">
                            {{ $element['label'] ?? 'Aufgabe' }}
                        </legend>
                        
                        <p style="font-weight: 600; font-size: 0.98rem; margin: 0 0 1rem 0; color: #27272a;">
                            {{ $questionTitle ?: ($element['instruction'] ?? '') }}
                        </p>

                        <div style="display:flex; flex-direction: column; gap:0.75rem; padding-left: 1.5rem;">
                            @foreach ($rawOptions as $opt)
                                <label class="mc-option-item" style="display:inline-flex; align-items:baseline; gap:0.75rem; cursor:pointer; font-size: 0.95rem; color: #3f3f46; font-weight: 500;">
                                    <input type="radio" name="{{ $elId }}" value="{{ $opt }}" wire:model.change="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }} style="margin-top: 0.2rem; flex-shrink: 0;">
                                    <span style="font-weight: 700; text-transform: uppercase; color: #18181b; min-width: 1rem;">{{ $opt }})</span>
                                    <span>
                                        {{ $parsedOptions[strtolower($opt)] ?? '' }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        @if ($result)
                            @php $detail = collect($result['details'])->firstWhere('id', $elId); @endphp
                            @if ($detail)
                                <div style="margin-top:1rem; border-top: 1px dashed #e5e5e5; padding-top: 0.5rem; font-size:0.9rem; font-weight: bold; color: {{ $detail['correct'] ? '#16a34a' : '#dc2626' }};">
                                    {{ $detail['correct'] ? '✓ Richtig' : '✗ Falsch (Richtig: ' . strtoupper($detail['expected']) . ')' }}
                                </div>
                            @endif
                        @endif
                    </fieldset>
                @endforeach
            </div>
        @endif
    </section>

@elseif ($layoutStyle === 'teil3')
    {{-- ==================== TEIL 3 LAYOUT ==================== --}}
    <section class="c1-section telc-teil3-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); width: 100%;">
        <div style="display: flex; justify-content: space-between; align-items: baseline; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem; margin-bottom: 1.5rem;">
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 1.8rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $section['title'] ?? 'Leseverstehen Teil 3' }}</h2>
            <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 1rem;">
                <span>90 Minuten Leseverstehen und Sprachbausteine</span>
                <span style="background: #1a1a1a; color: #ffffff; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">S. {{ $sectionIdx + 6 }}</span>
            </div>
        </div>

        @php
            $textEl = collect($section['elements'])->firstWhere('type', 'text');
            $questions = collect($section['elements'])->filter(fn($el) => $el['type'] !== 'text')->values()->all();
            $parsed = $textEl ? parseTeilContent($textEl['content'] ?? '') : ['instruction' => '', 'options' => [], 'texts' => []];
            $instruction = $parsed['instruction'] ?: ($textEl['instruction'] ?? ($section['instruction'] ?? ''));
        @endphp

        @if ($instruction)
            <div class="c1-instructions" style="margin-bottom: 1.5rem; font-style: italic; color: #555; background: #fafafa; border-left: 3px solid #1a1a1a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6;">
                {!! nl2br(e($instruction)) !!}
            </div>
        @endif

        {{-- TOP SECTION: Numbered Situations List (11-20 / 1-10) --}}
        @if (!empty($parsed['texts']))
            <div class="teil3-situations-top" style="margin-bottom: 2.5rem; background: #ffffff; padding: 1.5rem; border-radius: 8px; border: 1px solid #e2e8f0; width: 100%;">
                <h4 style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: #71717a; letter-spacing: 0.05em; margin: 0 0 1rem 0; border-bottom: 1px solid #f4f4f5; padding-bottom: 0.5rem;">Situationen</h4>
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    @foreach ($parsed['texts'] as $num => $situationText)
                        <div style="display: flex; gap: 1.25rem; align-items: flex-start;">
                            <span style="background:#d8232a; color:#ffffff; font-weight:800; font-size:0.9rem; padding:0.3rem 0.65rem; border-radius:4px; display:inline-block; min-width:2.2rem; text-align:center; flex-shrink: 0;">{{ $num }}</span>
                            <div style="color: #27272a; font-size: 0.96rem; font-weight: 600; line-height: 1.6;">{!! nl2br(e($situationText)) !!}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- BOTTOM SECTION: Info-Texts Grid of bordered Cards (classified ads clippings) --}}
        @if (!empty($parsed['options']))
            <div class="teil3-ads-bottom" style="margin-bottom: 2.5rem; width: 100%;">
                <h4 style="font-size: 0.8rem; font-weight: 800; text-transform: uppercase; color: #71717a; letter-spacing: 0.05em; margin: 0 0 1.25rem 0;">Informations-Angebote (Info-Texte)</h4>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; width: 100%;">
                    @php
                        $cardStyles = [
                            0 => ['border' => '1.5px solid #27272a', 'bg' => '#fcfcfc', 'align' => 'left', 'weight' => 'normal', 'headerBg' => '#f4f4f5'],
                            1 => ['border' => '1.5px dashed #3f3f46', 'bg' => '#fafafa', 'align' => 'center', 'weight' => 'italic', 'headerBg' => '#e4e4e7'],
                            2 => ['border' => '2.5px double #18181b', 'bg' => '#ffffff', 'align' => 'justify', 'weight' => 'normal', 'headerBg' => '#d4d4d8'],
                            3 => ['border' => '1.5px solid #71717a', 'bg' => '#f8fafc', 'align' => 'left', 'weight' => 'normal', 'headerBg' => '#f1f5f9'],
                        ];
                    @endphp
                    @foreach ($parsed['options'] as $letter => $adText)
                        @php
                            $styleIdx = ($loop->index) % 4;
                            $style = $cardStyles[$styleIdx];
                            
                            $adParts = preg_split('/\n|:/', $adText, 2);
                            $adTitle = count($adParts) > 1 ? trim($adParts[0]) : '';
                            $adBody = count($adParts) > 1 ? trim($adParts[1]) : $adText;
                        @endphp
                        <div class="ad-clipping-card" style="background: {{ $style['bg'] }}; border: {{ $style['border'] }}; border-radius: 6px; padding: 1.25rem; display: flex; flex-direction: column; justify-content: space-between; box-shadow: 0 2px 4px rgba(0,0,0,0.02); min-height: 250px;">
                            <div>
                                <div style="display: flex; justify-content: space-between; align-items: center; background: {{ $style['headerBg'] }}; padding: 0.35rem 0.6rem; border-radius: 4px; margin-bottom: 0.75rem; border-bottom: 1px solid #cbd5e1;">
                                    <span style="font-weight: 800; text-transform: uppercase; font-size: 1rem; color: #09090b; width: 1.75rem; height: 1.75rem; display: flex; align-items: center; justify-content: center; border-radius: 50%; background: #ffffff; border: 1px solid #a1a1aa;">{{ $letter }}</span>
                                    @if ($adTitle)
                                        <span style="font-weight: 800; font-size: 0.8rem; text-transform: uppercase; color: #1e293b; letter-spacing: 0.05em; max-width: 80%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $adTitle }}</span>
                                    @endif
                                </div>
                                <div style="color: #27272a; font-size: 0.88rem; line-height: 1.6; text-align: {{ $style['align'] }}; font-style: {{ $style['weight'] === 'italic' ? 'italic' : 'normal' }}; font-family: Georgia, serif;">
                                    {!! nl2br(e($adBody)) !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- INTERACTIVE MATCH SELECTORS --}}
        @if (!empty($questions))
            <div class="teil3-selectors" style="background: #fafafa; border: 1px solid #e5e5e5; border-radius: 8px; padding: 1.5rem; margin-top: 2rem; width: 100%;">
                <h4 style="font-size: 0.9rem; font-weight: 800; text-transform: uppercase; color: #27272a; margin: 0 0 1rem 0; border-bottom: 2px solid #27272a; padding-bottom: 0.5rem;">Antwortbogen (Zuordnung Aufgaben 11-20)</h4>
                @foreach ($questions as $element)
                    @php
                        $elId = $element['id'];
                        $matchOptions = method_exists($this, 'optionsFor') ? $this->optionsFor($element) : array_map('trim', explode(',', $element['options'] ?? ''));
                    @endphp
                    <div style="display:flex;flex-direction:row;align-items:center;gap:1.5rem;margin-bottom:0.75rem;font-family:'Plus Jakarta Sans',sans-serif; background: #ffffff; padding: 0.75rem 1.25rem; border-radius: 6px; border: 1px solid #e5e5e5; box-shadow: 0 1px 2px rgba(0,0,0,0.01);">
                        <span style="font-weight:700; color: #1a1a1a; min-width: 120px;">{{ $element['label'] ?? '' }}</span>
                        <select class="cloze-select" wire:model.change="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }} style="padding: 0.4rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; background: #ffffff; font-size: 0.9rem;">
                            <option value="">---</option>
                            @foreach ($matchOptions as $opt)
                                <option value="{{ $opt }}">{{ strtoupper($opt) }}</option>
                            @endforeach
                        </select>
                        @if ($result)
                            @php $detail = collect($result['details'])->firstWhere('id', $elId); @endphp
                            @if ($detail)
                                <span style="margin-left: auto; font-weight: bold; color: {{ $detail['correct'] ? '#16a34a' : '#dc2626' }}; font-size: 0.9rem;">
                                    {{ $detail['correct'] ? '✓ Richtig' : '✗ Falsch (Richtig: ' . strtoupper($detail['expected']) . ')' }}
                                </span>
                            @endif
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </section>

@else
    {{-- ==================== STANDARD FALLBACK LAYOUT ==================== --}}
    @if ($isGuest)
        {{-- GUEST SPECIFIC LOOP --}}
        <section class="c1-section" style="position: relative; width: 100%;">
            <div class="section-label">{{ $section['title'] ?? ('Teil ' . ($sectionIdx + 1)) }}</div>
            @foreach ($section['elements'] ?? [] as $element)
                @php
                    $elId = $element['id'] ?? ('el_' . $loop->index);
                    $elementLabel = $element['label'] ?? '';
                    $tabLabel = '';
                    if (str_contains(strtolower($elementLabel), 'leseverstehen')) {
                        $tabLabel = 'Leseverstehen';
                    } elseif (str_contains(strtolower($elementLabel), 'sprachbausteine')) {
                        $tabLabel = 'Sprachbausteine';
                    } elseif (str_contains(strtolower($elementLabel), 'hörverstehen') || str_contains(strtolower($elementLabel), 'hoerverstehen')) {
                        $tabLabel = 'Hörverstehen';
                    } elseif (str_contains(strtolower($elementLabel), 'schriftlicher') || str_contains(strtolower($elementLabel), 'schreiben')) {
                        $tabLabel = 'Schreiben';
                    } elseif (str_contains(strtolower($elementLabel), 'mündliche') || str_contains(strtolower($elementLabel), 'sprechen')) {
                        $tabLabel = 'Sprechen';
                    }
                    $isTaskInput = preg_match('/^Aufgabe\s+\d+/i', $elementLabel);
                @endphp

                @if ($elementLabel && !$isTaskInput)
                    <div style="position: relative; margin-top: 2rem; margin-bottom: 1.5rem;">
                        @if ($tabLabel)
                            <div class="document-section-tab" style="top: 0.5rem;">
                                <div class="icon-grid">
                                    <div></div><div></div>
                                    <div></div><div></div>
                                </div>
                                <div class="tab-text">{{ $tabLabel }}</div>
                            </div>
                        @endif

                        <h3 class="section-heading" style="margin-bottom: 0.5rem; font-family: var(--font-serif); font-size: 1.6rem; font-weight: 600; color: #1a1a1a;">{{ $elementLabel }}</h3>
                        @if ($element['instruction'] ?? null)
                            <div class="c1-instructions" style="margin-bottom: 1.5rem; font-style: italic; color: #555; background: #fafafa; border-left: 3px solid #1a1a1a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6;">
                                {!! nl2br(e($element['instruction'])) !!}
                            </div>
                        @endif
                    </div>
                @endif

                {{-- TEXT --}}
                @if (($element['type'] ?? '') === 'text')
                    <article class="c1-passage" style="background: #fdfdfd; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);">{!! nl2br(e($element['content'] ?? '')) !!}</article>

                {{-- IMAGE --}}
                @elseif (($element['type'] ?? '') === 'image')
                    <figure style="margin:1rem 0;"><img src="{{ $element['content'] }}" alt="{{ $element['label'] }}" style="max-width:100%;"></figure>

                {{-- INLINE GAP --}}
                @elseif (($element['type'] ?? '') === 'inline_gap')
                    @php
                        $gaps = $this->gapsFor($element);
                        $parts = preg_split('/--(\d+)--/', $element['content'] ?? '', -1, PREG_SPLIT_DELIM_CAPTURE);
                    @endphp
                    <div class="inline-gap-text" style="background: #fafafa; border: 1px solid #e5e5e5; border-radius: 6px; padding: 1.5rem; margin-bottom: 1.5rem; line-height: 2.2;">
                        @foreach ($parts as $i => $part)
                            @if ($i % 2 === 0)
                                <span>{!! nl2br(e($part)) !!}</span>
                            @else
                                @php $gapIdx = (int) $part; $gapDef = $gaps[$gapIdx] ?? null; @endphp
                                @if ($gapDef)
                                    @php
                                        $optList = array_filter(array_map('trim', explode(',', $gapDef['options'] ?? '')));
                                        $current = $answers[$element['id']][$gapIdx] ?? '';
                                        $gapCorrect = null;
                                        $gapExpected = $gapDef['answer'] ?? '';
                                        if ($result) {
                                            $gapDetail = collect($result['details'])->firstWhere('id', $element['id'].'.gap.'.$gapIdx);
                                            if ($gapDetail) {
                                                $gapCorrect = $gapDetail['correct'] ?? null;
                                                $gapExpected = $gapDetail['expected'] ?? $gapExpected;
                                            }
                                        }
                                        $gapCls = '';
                                        if ($result && $current !== '') {
                                            $gapCls = $gapCorrect ? 'correct' : 'wrong';
                                        }
                                    @endphp
                                    <span class="inline-gap-pill @if($current) filled @endif">
                                        <select wire:model.live="answers.{{ $element['id'] }}.{{ $gapIdx }}" @disabled($result) @class(['cloze-select', 'correct' => $gapCls === 'correct', 'wrong' => $gapCls === 'wrong'])>
                                            <option value="">--{{ $gapIdx }}--</option>
                                            @foreach ($optList as $opt)
                                                @php $optParts = explode('|', $opt, 2); $optKey = trim($optParts[0]); $optLabel = trim($optParts[1] ?? $optKey); @endphp
                                                <option value="{{ $optKey }}">{{ $optLabel }}</option>
                                            @endforeach
                                        </select>
                                        @if ($result && $current !== '')
                                            <span class="feedback-badge {{ $gapCorrect ? 'ok' : 'fail' }}">{{ $gapCorrect ? '✓' : '✗ ' . $gapExpected }}</span>
                                        @endif
                                    </span>
                                @endif
                            @endif
                        @endforeach
                    </div>

                {{-- INTERACTIVE TEXT --}}
                @elseif (($element['type'] ?? '') === 'interactive_text')
                    @php
                        $parser = app(\App\Services\GapParser::class);
                        $segments = $parser->parse($element['content'] ?? '', $element['gaps'] ?? []);
                    @endphp
                    @if ($element['label'] ?? null)
                        <h4 style="font-weight:700;margin-bottom:0.5rem;">{{ $element['label'] }}</h4>
                    @endif
                    <div class="c1-passage" style="line-height: 2.2;">
                        @foreach ($segments as $seg)
                            @if ($seg['type'] === 'text')
                                <span>{!! nl2br(e($seg['content'])) !!}</span>
                            @elseif ($seg['type'] === 'gap')
                                @php
                                    $wireKey = $element['id'].'.'.$seg['gapId'];
                                    $current = $answers[$element['id']][$seg['gapId']] ?? '';
                                    $isDropdown = ($seg['gapDef']['type'] ?? '') === 'dropdown';
                                    $optList = $parser->optionList($seg['gapDef']);
                                @endphp
                                <span class="inline-gap-pill">
                                    @if ($isDropdown && count($optList) > 0)
                                        <select wire:model.live="answers.{{ $wireKey }}" class="cloze-select">
                                            <option value="">{{ $current ?: '___' }}</option>
                                            @foreach ($optList as $opt)
                                                <option value="{{ $opt['value'] }}" @selected($current === $opt['value'])>{{ $opt['label'] }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <input type="text" wire:model.live="answers.{{ $wireKey }}" class="cloze-select" style="width:80px;" placeholder="___" value="{{ $current }}">
                                    @endif
                                </span>
                            @endif
                        @endforeach
                    </div>

                {{-- MULTIPLE CHOICE --}}
                @elseif (in_array($element['type'] ?? '', ['multiple_choice', 'true_false'], true))
                    @php $rawOptions = $this->optionsFor($element); @endphp
                    <div class="mc-question-row">
                        @if ($element['label'] ?? null)
                            <div class="mc-question-header">{{ $element['label'] }}</div>
                        @endif
                        @if ($element['instruction'] ?? null)
                            <p style="font-size:0.9rem;color:#666;margin-bottom:0.75rem;">{{ $element['instruction'] }}</p>
                        @endif
                        <div class="mc-options-list">
                            @foreach ($rawOptions as $opt)
                                @php
                                    $isSelected = ($answers[$element['id']] ?? '') === $opt;
                                    $detail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null;
                                    $optionCls = 'mc-option-item';
                                    if ($result && $isSelected) {
                                        $optionCls .= $detail && $detail['correct'] ? ' selected' : ' selected';
                                    } elseif ($isSelected) {
                                        $optionCls .= ' selected';
                                    }
                                @endphp
                                <label class="{{ $optionCls }}">
                                    <input type="radio" wire:model.live="answers.{{ $element['id'] }}" value="{{ $opt }}" @disabled($result) class="mc-radio-input">
                                    <span class="mc-option-bullet"></span>
                                    <span>{{ $opt }}</span>
                                    @if ($result)
                                        @php $det = collect($result['details'])->firstWhere('id', $element['id']); @endphp
                                        @if ($det && $opt === ($det['expected'] ?? '') && !$det['correct'])
                                            <span class="feedback-badge fail">Richtige Antwort: {{ $det['expected'] }}</span>
                                        @endif
                                        @if ($det && $isSelected && $det['correct'])
                                            <span class="feedback-badge ok">✓</span>
                                        @endif
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>

                {{-- MATCHING --}}
                @elseif (($element['type'] ?? '') === 'matching')
                    @php $matchOptions = $this->optionsFor($element); @endphp
                    <div class="mc-question-row">
                        @if ($element['label'] ?? null)
                            <div class="mc-question-header">{{ $element['label'] }}</div>
                        @endif
                        @if (($element['type'] ?? '') === 'matching' && count($matchOptions) > 5)
                            <div class="answer-sheet-row">
                                <span class="question-num">{{ preg_replace('/[^0-9]/', '', $element['label']) ?: $loop->iteration }}</span>
                                <select wire:model.live="answers.{{ $element['id'] }}" class="matching-select" @disabled($result)>
                                    <option value="">—</option>
                                    @foreach ($matchOptions as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <div class="bubble-group">
                                @foreach ($matchOptions as $option)
                                    @php
                                        $isSelected = ($answers[$element['id']] ?? '') === $option;
                                        $detail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null;
                                        $bubbleCls = 'bubble';
                                        if ($result) {
                                            if ($isSelected) {
                                                $bubbleCls .= $detail && $detail['correct'] ? ' selected correct' : ' selected wrong';
                                            } elseif ($detail && $option === $detail['expected']) {
                                                $bubbleCls .= ' correct-key';
                                            }
                                        } elseif ($isSelected) {
                                            $bubbleCls .= ' selected';
                                        }
                                    @endphp
                                    <label @class([$bubbleCls])>
                                        <input type="radio" wire:model.live="answers.{{ $element['id'] }}" value="{{ $option }}" @disabled($result)>
                                        <span>{{ $option }}</span>
                                    </label>
                                @endforeach
                            </div>
                        @endif
                    </div>

                {{-- WRITING --}}
                @elseif (($element['type'] ?? '') === 'writing')
                    @php $writeDetail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null; @endphp
                    <div class="writing-section @if($result) marked @endif" style="margin-top: 2rem;">
                        <label class="writing-label" style="font-weight: 700; display: block; margin-bottom: 0.5rem;">{{ $element['label'] ?? $element['instruction'] ?? 'Schreiben' }}</label>
                        <textarea class="writing-textarea-lined" wire:model.live="answers.{{ $element['id'] }}" placeholder="Ihre Antwort..." @disabled($result)></textarea>
                        @if ($writeDetail)
                            <span class="writing-score">{{ $writeDetail['points'] }} / {{ $writeDetail['max_points'] }} Punkte</span>
                            @if (($writeDetail['explanation'] ?? $writeDetail['feedback'] ?? '') !== '')
                                <div style="margin-top:0.75rem;padding:0.75rem;background:#f9f9f9;border-left:3px solid #d8232a;border-radius:4px;font-size:0.85rem;">
                                    <strong>Feedback:</strong> {{ $writeDetail['explanation'] ?? $writeDetail['feedback'] }}
                                </div>
                            @endif
                            @if (!empty($element['answer']))
                                <div style="margin-top: 1rem; padding: 1rem; background: #f0fdf4; border-left: 4px solid #16a34a; border-radius: 4px;">
                                    <strong style="color: #14532d; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">Musterlösung / AI-Musterlösung:</strong>
                                    <div style="font-size: 0.95rem; line-height: 1.6; color: #166534; white-space: pre-wrap;">{{ $element['answer'] }}</div>
                                </div>
                            @endif
                        @endif
                    </div>
                @endif
            @endforeach
        </section>
    @else
        {{-- STANDARD STUDENT/ADMIN FALLBACK LOOP --}}
        <section class="c1-section" data-section-idx="{{ $sectionIdx }}" style="position: relative; width: 100%;">
            <div class="section-label">{{ $section['title'] ?? ('Teil ' . ($sectionIdx + 1)) }}</div>
            @foreach ($section['elements'] ?? [] as $element)
                @php
                    $elId = $element['id'] ?? ('el_' . $sectionIdx . '_' . $loop->index);
                    $elementLabel = $element['label'] ?? '';
                    $tabLabel = '';
                    if (str_contains(strtolower($elementLabel), 'leseverstehen')) {
                        $tabLabel = 'Leseverstehen';
                    } elseif (str_contains(strtolower($elementLabel), 'sprachbausteine')) {
                        $tabLabel = 'Sprachbausteine';
                    } elseif (str_contains(strtolower($elementLabel), 'hörverstehen') || str_contains(strtolower($elementLabel), 'hoerverstehen')) {
                        $tabLabel = 'Hörverstehen';
                    } elseif (str_contains(strtolower($elementLabel), 'schriftlicher') || str_contains(strtolower($elementLabel), 'schreiben')) {
                        $tabLabel = 'Schreiben';
                    } elseif (str_contains(strtolower($elementLabel), 'mündliche') || str_contains(strtolower($elementLabel), 'sprechen')) {
                        $tabLabel = 'Sprechen';
                    }
                    $isTaskInput = preg_match('/^Aufgabe\s+\d+/i', $elementLabel);
                @endphp

                @if ($elementLabel && !$isTaskInput)
                    <div style="position: relative; margin-top: 2rem; margin-bottom: 1.5rem;">
                        @if ($tabLabel)
                            <div class="document-section-tab" style="top: 0.5rem;">
                                <div class="icon-grid">
                                    <div></div><div></div>
                                    <div></div><div></div>
                                </div>
                                <div class="tab-text">{{ $tabLabel }}</div>
                            </div>
                        @endif

                        <h3 class="section-heading" style="margin-bottom: 0.5rem; font-family: var(--font-serif); font-size: 1.6rem; font-weight: 600; color: #1a1a1a;">{{ $elementLabel }}</h3>
                        @if ($element['instruction'] ?? null)
                            <div class="c1-instructions" style="margin-bottom: 1.5rem; font-style: italic; color: #555; background: #fafafa; border-left: 3px solid #1a1a1a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6;">
                                {!! nl2br(e($element['instruction'])) !!}
                            </div>
                        @endif
                    </div>
                @endif

                @if (($element['type'] ?? '') === 'text')
                    <article class="c1-passage" style="background: #fdfdfd; border: 1px solid #e2e8f0; border-radius: 6px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);">
                        {!! renderPremiumPassage($element['content'] ?? '') !!}
                    </article>
                @elseif (in_array($element['type'] ?? '', ['inline_gap', 'fill_blank']))
                    @php
                        $gaps = method_exists($this, 'gapsFor') ? $this->gapsFor($element) : (json_decode($element['options'] ?? '[]', true) ?: []);
                    @endphp
                    @if (preg_match_all('/--(\d+)--/', $element['content'] ?? '', $gapMatches))
                        @php $gapCount = count($gapMatches[0]); @endphp
                        @php $parts = preg_split('/--\d+--/', $element['content'] ?? ''); @endphp
                        @if (count($parts) > 0)
                            <p class="cloze-text" style="background: #fafafa; border: 1px solid #e5e5e5; border-radius: 6px; padding: 1.5rem; margin-bottom: 1.5rem; line-height: 2.2;">
                                @foreach ($parts as $partIdx => $part)
                                    {!! nl2br(e($part)) !!}
                                    @if ($partIdx < $gapCount)
                                        @php
                                            $gapIdx = (int)($gapMatches[1][$partIdx] ?? 0);
                                            $gapDef = $gaps[$gapIdx] ?? null;
                                            $gapOptionList = [];
                                            if ($gapDef) {
                                                $parts2 = array_map('trim', explode(',', $gapDef['options'] ?? ''));
                                                foreach ($parts2 as $p) {
                                                    $kv = explode('|', $p, 2);
                                                    $gapOptionList[] = ['key' => trim($kv[0]), 'text' => trim($kv[1] ?? $kv[0])];
                                                }
                                            }
                                        @endphp
                                        <span class="cloze-select-wrapper">
                                            <span class="cloze-gap-num">{{ $gapIdx }}</span>
                                            <select class="cloze-select" wire:model.change="answers.{{ $elId }}.{{ $gapIdx }}" {{ $result ? 'disabled' : '' }}>
                                                <option value="">---</option>
                                                @foreach ($gapOptionList as $opt)
                                                    <option value="{{ $opt['key'] }}">{{ $opt['text'] }}</option>
                                                @endforeach
                                            </select>
                                        </span>
                                    @endif
                                @endforeach
                            </p>
                        @endif
                    @endif
                @elseif (in_array($element['type'] ?? '', ['matching']))
                    @php $matchOptions = method_exists($this, 'optionsFor') ? $this->optionsFor($element) : array_map('trim', explode(',', $element['options'] ?? '')); @endphp
                    @if (isset($element['pairs']) && count($element['pairs']) > 0)
                        @foreach ($element['pairs'] as $pair)
                            <div style="display:flex;flex-direction:row;align-items:center;gap:1rem;margin-bottom:0.75rem;font-family:'Plus Jakarta Sans',sans-serif;">
                                <span style="min-width:150px;font-weight:600;">{{ $pair['left'] ?? '' }}</span>
                                <select class="cloze-select" wire:model.change="answers.{{ $elId }}.{{ $loop->index }}" {{ $result ? 'disabled' : '' }}>
                                    <option value="">---</option>
                                    @foreach ($matchOptions as $opt)
                                        <option value="{{ $opt }}">{{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    @else
                        <div style="display:flex;flex-direction:row;align-items:center;gap:1.5rem;margin-bottom:0.75rem;font-family:'Plus Jakarta Sans',sans-serif; background: #fafafa; padding: 0.5rem 1rem; border-radius: 6px; border: 1px solid #e5e5e5;">
                            <span style="font-weight:600; color: #1a1a1a; min-width: 100px;">{{ $element['label'] ?? '' }}</span>
                            <select class="cloze-select" wire:model.change="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }} style="padding: 0.35rem 0.75rem; border: 1px solid #cbd5e1; border-radius: 4px; background: #ffffff;">
                                <option value="">---</option>
                                @foreach ($matchOptions as $opt)
                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                @endforeach
                            </select>
                            @if ($result)
                                @php $detail = collect($result['details'])->firstWhere('id', $elId); @endphp
                                @if ($detail)
                                    <span style="margin-left: auto; font-weight: bold; color: {{ $detail['correct'] ? '#16a34a' : '#dc2626' }}; font-size: 0.85rem;">
                                        {{ $detail['correct'] ? '✓ Richtig' : '✗ Falsch (Richtig: ' . strtoupper($detail['expected']) . ')' }}
                                    </span>
                                @endif
                            @endif
                        </div>
                    @endif
                @elseif (in_array($element['type'] ?? '', ['multiple_choice', 'true_false']))
                    @php
                        $rawOptions = method_exists($this, 'optionsFor') ? $this->optionsFor($element) : array_map('trim', explode(',', $element['options'] ?? ''));
                    @endphp
                    <fieldset style="border:none;padding:0;margin:0 0 1.25rem 0; font-family:'Plus Jakarta Sans',sans-serif; background:#fafafa; border:1px solid #e5e5e5; padding: 0.75rem 1rem; border-radius: 6px;">
                        <legend style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;margin-bottom:0.5rem; color:#1a1a1a; padding: 0 0.5rem;">
                            {{ $element['label'] ?? 'Aufgabe' }}@if(!empty($element['instruction'])): {{ $element['instruction'] }}@endif
                        </legend>
                        <div style="display:flex;flex-wrap:wrap;gap:0.75rem;">
                            @foreach ($rawOptions as $opt)
                                <label class="mc-option-item" style="display:inline-flex; align-items:center; gap:0.35rem; cursor:pointer;">
                                    <input type="radio" name="{{ $elId }}" value="{{ $opt }}" wire:model.change="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }}>
                                    <span style="font-weight: 500; text-transform: uppercase;">{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>
                        @if ($result)
                            @php $detail = collect($result['details'])->firstWhere('id', $elId); @endphp
                            @if ($detail)
                                <div style="margin-top:0.5rem; font-size:0.85rem; font-weight: bold; color: {{ $detail['correct'] ? '#16a34a' : '#dc2626' }};">
                                    {{ $detail['correct'] ? '✓ Richtig' : '✗ Falsch (Richtig: ' . strtoupper($detail['expected']) . ')' }}
                                </div>
                            @endif
                        @endif
                    </fieldset>
                @elseif (($element['type'] ?? '') === 'writing')
                    @php
                        $writingDetail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null;
                    @endphp
                    <div style="margin-bottom:2rem;">
                        <label style="font-family:'Playfair Display',serif;font-size:1rem;font-weight:700;display:block;margin-bottom:0.5rem;">{{ $element['instruction'] ?? 'Schreiben Sie einen Text' }} (max. {{ $writingDetail['max_points'] ?? '—' }} Punkte)</label>
                        <textarea class="writing-textarea-lined" wire:model.debounce.500ms="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }} placeholder="Ihre Antwort..."></textarea>
                        @if ($result && (($writingDetail['explanation'] ?? $writingDetail['feedback'] ?? '') !== ''))
                            <div style="margin-top:0.75rem;padding:0.75rem;background:#f9f9f9;border-left:3px solid #d8232a;border-radius:4px;font-size:0.85rem;">
                                <strong>Feedback:</strong> {{ $writingDetail['explanation'] ?? $writingDetail['feedback'] }}
                            </div>
                        @endif
                        @if ($result && !empty($element['answer']))
                            <div style="margin-top: 1rem; padding: 1rem; background: #f0fdf4; border-left: 4px solid #16a34a; border-radius: 4px;">
                                <strong style="color: #14532d; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">Musterlösung / AI-Musterlösung:</strong>
                                <div style="font-size: 0.95rem; line-height: 1.6; color: #166534; white-space: pre-wrap;">{{ $element['answer'] }}</div>
                            </div>
                        @endif
                    </div>
                @endif
            @endforeach
        </section>
    @endif
@endif
