{{-- ==================== TELC C1 LESESTAND LAYOUT ==================== --}}
<section class="telc-section telc-c1-l12-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2.5rem; border-radius: 12px; border: 1px solid #e2e8f0; width: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    
    {{-- Main Header --}}
    <div style="border-bottom: 3px solid #d8232a; padding-bottom: 0.75rem; margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <span style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; color: #d8232a; letter-spacing: 0.1em; display: block; margin-bottom: 0.25rem;">Leseverstehen und Sprachbausteine</span>
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2.2rem; font-weight: 900; color: #111827; margin: 0; letter-spacing: -0.02em;">telc Deutsch C1 Hochschule</h2>
        </div>
        <div style="text-align: right;">
            <span style="display: block; font-size: 1.1rem; font-weight: 800; color: #1f2937;">90 Minuten</span>
            <span style="font-size: 0.8rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Modul 1</span>
        </div>
    </div>

    @php
        $elements = collect($section['elements']);
        
        // Teil 1
        $t1Instruction = $elements->firstWhere('id', 'telc-c1-lv1-instruction');
        $t1Cloze = $elements->firstWhere('id', 'telc-c1-lv1-cloze');
        $t1Sentences = $elements->firstWhere('id', 'telc-c1-lv1-sentences');
        
        // Teil 2
        $t2Instruction = $elements->firstWhere('id', 'telc-c1-lv2-instruction');
        $t2Text = $elements->firstWhere('id', 'telc-c1-lv2-text');
        $t2Questions = $elements->where('type', 'matching')->filter(fn($item) => str_starts_with($item['id'] ?? '', 'telc-c1-lv2-'))->all();
        
        // Teil 3
        $t3Instruction = $elements->firstWhere('id', 'telc-c1-lv3-instruction');
        $t3Text = $elements->firstWhere('id', 'telc-c1-lv3-text');
        $t3Questions = $elements->where('type', 'true_false')->filter(fn($item) => str_starts_with($item['id'] ?? '', 'telc-c1-lv3-'))->all();
        $t3_24 = $elements->firstWhere('id', 'telc-c1-lv3-24');
        
        // Sprachbausteine
        $sbInstruction = $elements->firstWhere('id', 'telc-c1-sb-instruction');
        $sbCloze = $elements->firstWhere('id', 'telc-c1-sb-inline');
    @endphp

    {{-- ==================== TEIL 1 (MARS) ==================== --}}
    @if ($t1Cloze)
        <div style="margin-bottom: 4rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 3rem;">
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.25rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
                <h3 style="font-size: 1.4rem; font-weight: 800; color: #d8232a; margin: 0;">Leseverstehen Teil 1</h3>
                <span style="font-size: 0.85rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Textrekonstruktion</span>
            </div>

            @if ($t1Instruction)
                <div style="font-size: 0.95rem; line-height: 1.6; color: #475569; font-weight: 600; background: #fdf2f2; border-left: 4px solid #d8232a; padding: 1rem 1.25rem; border-radius: 4px; margin-bottom: 1.5rem;">
                    {!! nl2br(e($t1Instruction['content'])) !!}
                </div>
            @endif

            {{-- Cloze Text Box --}}
            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.75rem; line-height: 2.2rem; font-size: 1.02rem; color: #1e293b; text-align: justify; margin-bottom: 2rem; box-shadow: 0 1px 3px rgba(0,0,0,0.01);">
                @php
                    $content = $t1Cloze['content'] ?? '';
                    $parts = preg_split('/(--\d+--)/', $content, -1, PREG_SPLIT_DELIM_CAPTURE);
                    $gapsArray = is_array($t1Cloze['options'] ?? null) ? $t1Cloze['options'] : json_decode($t1Cloze['options'] ?? '[]', true);
                @endphp
                @foreach ($parts as $part)
                    @if (preg_match('/^--(\d+)--$/', $part, $matches))
                        @php
                            $gapIdx = (int)$matches[1];
                            $optionIdx = $gapIdx - 1;
                            $gapDef = $gapsArray[$optionIdx] ?? null;
                            
                            $current = $answers[$t1Cloze['id']][$optionIdx] ?? '';
                            $gapExpected = $gapDef['answer'] ?? '';
                            
                            $selectBorder = '#cbd5e1';
                            $selectBg = '#ffffff';
                            $selectColor = '#1e293b';
                            $gapCorrect = false;
                            
                            if ($result) {
                                $detail = collect($result['details'])->firstWhere('id', "{$t1Cloze['id']}.gap.{$optionIdx}");
                                $gapCorrect = $detail['correct'] ?? false;
                                $selectBorder = $gapCorrect ? '#16a34a' : '#dc2626';
                                $selectBg = $gapCorrect ? '#f0fdf4' : '#fdf2f2';
                                $selectColor = $gapCorrect ? '#16a34a' : '#dc2626';
                            }
                        @endphp
                        <span style="display: inline-flex; align-items: center; margin: 0 0.25rem; vertical-align: middle;">
                            <span style="background: #475569; color: #ffffff; font-size: 0.75rem; font-weight: 800; padding: 0.2rem 0.45rem; border-radius: 4px 0 0 4px; height: 32px; display: inline-flex; align-items: center;">{{ $gapIdx }}</span>
                            <select wire:model.change="answers.{{ $t1Cloze['id'] }}.{{ $optionIdx }}" {{ $result ? 'disabled' : '' }}
                                style="font-family: inherit; font-size: 0.9rem; font-weight: 700; padding: 0.2rem 0.5rem; border: 2.5px solid {{ $selectBorder }}; background: {{ $selectBg }}; color: {{ $selectColor }}; border-radius: 0 4px 4px 0; outline: none; cursor: pointer; height: 32px; width: 90px; text-align: center;">
                                <option value="">Wählen</option>
                                @foreach (range('a', 'h') as $letter)
                                    <option value="{{ $letter }}">{{ strtoupper($letter) }}</option>
                                @endforeach
                            </select>
                            @if ($result)
                                @if ($gapCorrect)
                                    <span style="color: #16a34a; font-weight: 900; margin-left: 0.25rem; font-size: 1.1rem;">✓</span>
                                @else
                                    <span style="color: #dc2626; font-weight: 900; margin-left: 0.25rem; font-size: 1.1rem;">✗</span>
                                    <span style="color: #16a34a; font-weight: 800; font-size: 0.82rem; margin-left: 0.25rem; border: 1.5px solid #16a34a; padding: 0.15rem 0.4rem; border-radius: 4px; background: #f0fdf4; height: 28px; display: inline-flex; align-items: center;">
                                        Lösung: {{ strtoupper($gapExpected) }}
                                    </span>
                                @endif
                            @endif
                        </span>
                    @else
                        {!! nl2br(e($part)) !!}
                    @endif
                @endforeach
            </div>

            {{-- Sentence Options Box --}}
            @if ($t1Sentences)
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.5rem;">
                    <h4 style="font-size: 1rem; font-weight: 800; color: #1e293b; margin-top: 0; margin-bottom: 1rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 1px solid #cbd5e1; padding-bottom: 0.4rem;">{{ $t1Sentences['label'] }}</h4>
                    <div style="display: flex; flex-direction: column; gap: 0.65rem;">
                        @php
                            $lines = explode("\n", $t1Sentences['content']);
                        @endphp
                        @foreach ($lines as $line)
                            @php
                                $line = trim($line);
                                if ($line === '') continue;
                                $isExample = str_starts_with($line, 'Beispiel:') || str_starts_with($line, 'z  ');
                                $bg = $isExample ? '#f1f5f9' : '#ffffff';
                                $border = $isExample ? '#cbd5e1' : '#e2e8f0';
                                
                                // Split key and text
                                $keyLetter = '';
                                $restText = $line;
                                if (preg_match('/^([a-z])\s+(.+)$/i', $line, $lm)) {
                                    $keyLetter = $lm[1];
                                    $restText = $lm[2];
                                }
                            @endphp
                            <div style="background: {{ $bg }}; border: 1px solid {{ $border }}; border-radius: 6px; padding: 0.5rem 1rem; display: flex; align-items: center; gap: 1rem; @if($isExample) opacity: 0.85; @endif">
                                @if ($keyLetter)
                                    <span style="background: {{ $isExample ? '#64748b' : '#d8232a' }}; color: #ffffff; font-weight: 850; font-size: 0.85rem; width: 1.5rem; height: 1.5rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; text-transform: uppercase;">{{ $keyLetter }}</span>
                                    <span style="font-size: 0.92rem; font-weight: 600; color: #334155;">{{ $restText }}</span>
                                @else
                                    <span style="font-size: 0.9rem; font-weight: 700; color: #475569;">{{ $line }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- ==================== TEIL 2 (HOBBYBÄUERIN) ==================== --}}
    @if ($t2Text && count($t2Questions) > 0)
        <div style="margin-bottom: 4rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 3rem;">
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.25rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
                <h3 style="font-size: 1.4rem; font-weight: 800; color: #d8232a; margin: 0;">Leseverstehen Teil 2</h3>
                <span style="font-size: 0.85rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Selektives Verstehen</span>
            </div>

            @if ($t2Instruction)
                <div style="font-size: 0.95rem; line-height: 1.6; color: #475569; font-weight: 600; background: #fdf2f2; border-left: 4px solid #d8232a; padding: 1rem 1.25rem; border-radius: 4px; margin-bottom: 1.5rem;">
                    {!! nl2br(e($t2Instruction['content'])) !!}
                </div>
            @endif

            {{-- Parse Paragraphs (a-e) --}}
            @php
                $paragraphs = [];
                $parts = preg_split('/^([a-e])\n/m', $t2Text['content'] ?? '', -1, PREG_SPLIT_DELIM_CAPTURE);
                $currentLetter = '';
                foreach ($parts as $pPart) {
                    $pPart = trim($pPart);
                    if ($pPart === '') continue;
                    if (in_array($pPart, ['a', 'b', 'c', 'd', 'e'])) {
                        $currentLetter = $pPart;
                    } else {
                        $paragraphs[$currentLetter] = $pPart;
                    }
                }
            @endphp

            <div style="display: flex; flex-direction: column; gap: 2rem; margin-bottom: 2.5rem;">
                {{-- Example Box C1 B2 --}}
                <div style="background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; opacity: 0.85;">
                    <div style="display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid #cbd5e1; padding-bottom: 0.5rem; margin-bottom: 1rem;">
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <span style="background: #64748b; color: #ffffff; font-weight: 850; font-size: 0.8rem; padding: 0.2rem 0.5rem; border-radius: 4px;">Beispiel (0)</span>
                            <span style="font-size: 0.95rem; font-weight: 700; color: #1e293b;">In welchem Abschnitt möchte die Autorin die Leser unterhalten?</span>
                        </div>
                        <span style="background: #e2e8f0; border: 1.5px solid #cbd5e1; color: #475569; font-weight: 800; font-size: 0.88rem; width: 2.2rem; height: 2.2rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 50%;">B</span>
                    </div>
                </div>

                {{-- Actual matching questions grid --}}
                <div class="lv2-options-container" style="display: flex; flex-direction: column; gap: 1rem;">
                    @foreach ($t2Questions as $q)
                        @php
                            $qNum = substr($q['id'], strrpos($q['id'], '-') + 1);
                            $selected = $answers[$q['id']] ?? '';
                            
                            $selectBorder = '#cbd5e1';
                            $selectBg = '#ffffff';
                            $selectColor = '#1e293b';
                            $correct = false;
                            $expected = $q['answer'] ?? '';
                            
                            if ($result) {
                                $detail = collect($result['details'])->firstWhere('id', $q['id']);
                                $correct = $detail['correct'] ?? false;
                                $selectBorder = $correct ? '#16a34a' : '#dc2626';
                                $selectBg = $correct ? '#f0fdf4' : '#fdf2f2';
                                $selectColor = $correct ? '#16a34a' : '#dc2626';
                            }
                        @endphp
                        <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; box-shadow: 0 1px 3px rgba(0,0,0,0.01);">
                            <div style="display: flex; gap: 0.75rem; align-items: flex-start; max-width: 70%;">
                                <span style="background: #d8232a; color: #ffffff; font-weight: 850; font-size: 0.9rem; padding: 0.25rem 0.55rem; border-radius: 4px; flex-shrink: 0;">{{ $qNum }}</span>
                                <span style="font-size: 0.95rem; font-weight: 700; color: #1f2937; line-height: 1.5;">In welchem Abschnitt {{ $q['instruction'] }}</span>
                            </div>

                            <div style="display: flex; align-items: center; gap: 0.75rem;">
                                <span style="font-size: 0.85rem; font-weight: 700; color: #475569; text-transform: uppercase;">Abschnitt:</span>
                                <select wire:model.change="answers.{{ $q['id'] }}" {{ $result ? 'disabled' : '' }}
                                    style="font-family: inherit; font-size: 0.9rem; font-weight: 700; padding: 0.35rem 0.75rem; border: 2.5px solid {{ $selectBorder }}; background: {{ $selectBg }}; color: {{ $selectColor }}; border-radius: 8px; outline: none; cursor: pointer; width: 100px;">
                                    <option value="">Wählen...</option>
                                    @foreach (['a', 'b', 'c', 'd', 'e'] as $l)
                                        <option value="{{ $l }}">{{ strtoupper($l) }}</option>
                                    @endforeach
                                </select>
                                @if ($result)
                                    @if ($correct)
                                        <span style="color: #16a34a; font-weight: 800; font-size: 0.9rem;">✓ Richtig</span>
                                    @else
                                        <span style="color: #dc2626; font-weight: 800; font-size: 0.9rem;">✗ Falsch</span>
                                        <span style="color: #16a34a; font-weight: 700; font-size: 0.88rem; border: 1.5px solid #16a34a; padding: 0.2rem 0.5rem; border-radius: 6px; background: #f0fdf4; margin-left: 0.25rem; display: inline-flex; align-items: center;">
                                            Lösung: {{ strtoupper($expected) }}
                                        </span>
                                    @endif
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Render Text Paragraphs (a-e) --}}
                <div style="margin-top: 2rem;">
                    <h4 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; border-bottom: 2px solid #cbd5e1; padding-bottom: 0.5rem; margin-bottom: 1.5rem; text-transform: uppercase; letter-spacing: 0.05em;">{{ $t2Text['label'] }}</h4>
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        @foreach ($paragraphs as $letter => $bodyText)
                            <div style="background: #ffffff; border: 1.5px solid #e2e8f0; border-radius: 8px; padding: 1.5rem; position: relative; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.01);">
                                <span style="position: absolute; top: -11px; left: 1.25rem; background: #d8232a; color: #ffffff; width: 1.5rem; height: 1.5rem; border-radius: 50%; font-weight: 850; font-size: 0.85rem; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.1); text-transform: uppercase;">
                                    {{ $letter }}
                                </span>
                                <div style="font-size: 0.95rem; color: #374151; line-height: 1.7; text-align: justify; padding-top: 0.25rem;">
                                    {!! nl2br(e($bodyText)) !!}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- ==================== TEIL 3 (WISSENSCHAFTLER IM FILM) ==================== --}}
    @if ($t3Text)
        <div style="margin-bottom: 4rem; border-bottom: 1px solid #e2e8f0; padding-bottom: 3rem;">
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.25rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
                <h3 style="font-size: 1.4rem; font-weight: 800; color: #d8232a; margin: 0;">Leseverstehen Teil 3</h3>
                <span style="font-size: 0.85rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Aussagenvergleich</span>
            </div>

            @if ($t3Instruction)
                <div style="font-size: 0.95rem; line-height: 1.6; color: #475569; font-weight: 600; background: #fdf2f2; border-left: 4px solid #d8232a; padding: 1rem 1.25rem; border-radius: 4px; margin-bottom: 1.5rem;">
                    {!! nl2br(e($t3Instruction['content'])) !!}
                </div>
            @endif

            {{-- Text Paragraphs (1-13) --}}
            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.75rem; margin-bottom: 2.5rem; max-height: 500px; overflow-y: auto; box-shadow: inset 0 1px 3px rgba(0,0,0,0.02);">
                <h4 style="font-size: 1.1rem; font-weight: 800; color: #1e293b; margin-top: 0; margin-bottom: 1.25rem; text-transform: uppercase; letter-spacing: 0.05em; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.4rem;">{{ $t3Text['label'] }}</h4>
                @php
                    $t3Paragraphs = [];
                    $t3Parts = preg_split('/^(\d+)\s+/m', $t3Text['content'] ?? '', -1, PREG_SPLIT_DELIM_CAPTURE);
                    $currentNum = '';
                    foreach ($t3Parts as $pPart) {
                        $pPart = trim($pPart);
                        if ($pPart === '') continue;
                        if (is_numeric($pPart)) {
                            $currentNum = $pPart;
                        } else {
                            $t3Paragraphs[$currentNum] = $pPart;
                        }
                    }
                @endphp
                <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                    @foreach ($t3Paragraphs as $num => $bodyText)
                        <div style="display: flex; gap: 1rem; align-items: flex-start; line-height: 1.75; font-size: 0.98rem; color: #374151;">
                            <span style="background: #f1f5f9; color: #475569; font-weight: 800; font-size: 0.8rem; width: 1.5rem; height: 1.5rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; flex-shrink: 0;">{{ $num }}</span>
                            <div style="text-align: justify;">{{ $bodyText }}</div>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Questions Grid (13-23) --}}
            <div style="display: flex; flex-direction: column; gap: 1.25rem;">
                @foreach ($t3Questions as $q)
                    @php
                        $qNum = substr($q['id'], strrpos($q['id'], '-') + 1);
                        $current = $answers[$q['id']] ?? '';
                        $expected = $q['answer'] ?? '';
                        $qCorrect = false;
                        
                        if ($result) {
                            $detail = collect($result['details'])->firstWhere('id', $q['id']);
                            $qCorrect = $detail['correct'] ?? false;
                        }
                    @endphp
                    <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.25rem; display: flex; flex-direction: column; gap: 0.75rem; box-shadow: 0 1px 3px rgba(0,0,0,0.01);">
                        <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                            <span style="background: #d8232a; color: #ffffff; font-weight: 850; font-size: 0.9rem; padding: 0.25rem 0.55rem; border-radius: 4px; flex-shrink: 0;">{{ $qNum }}</span>
                            <span style="font-size: 0.95rem; font-weight: 700; color: #1f2937; line-height: 1.5;">{{ $q['question'] ?: $q['label'] }}</span>
                        </div>

                        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem; border-top: 1px dashed #e2e8f0; padding-top: 0.75rem;">
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                @foreach (['+' => '+ Richtig', '-' => '- Falsch', 'x' => 'x Nicht im Text'] as $optVal => $optLabel)
                                    @php
                                        $isSelected = $current === $optVal;
                                        $btnBg = '#ffffff';
                                        $btnBorder = '#cbd5e1';
                                        $btnColor = '#475569';
                                        
                                        if ($isSelected) {
                                            $btnBg = '#fdf2f2';
                                            $btnBorder = '#d8232a';
                                            $btnColor = '#d8232a';
                                        }
                                        
                                        if ($result) {
                                            $isThisCorrect = $optVal === $expected;
                                            $isUserSelection = $isSelected;
                                            
                                            if ($isThisCorrect) {
                                                $btnBg = '#f0fdf4';
                                                $btnBorder = '#16a34a';
                                                $btnColor = '#16a34a';
                                            } elseif ($isUserSelection && !$isThisCorrect) {
                                                $btnBg = '#fdf2f2';
                                                $btnBorder = '#dc2626';
                                                $btnColor = '#dc2626';
                                            }
                                        }
                                    @endphp
                                    <button type="button" 
                                        @if(!$result) wire:click="$set('answers.{{ $q['id'] }}', '{{ $optVal }}')" @endif
                                        style="font-family: inherit; font-size: 0.85rem; font-weight: 700; padding: 0.35rem 0.85rem; border: 2px solid {{ $btnBorder }}; background: {{ $btnBg }}; color: {{ $btnColor }}; border-radius: 6px; cursor: @if($result) default @else pointer @endif; transition: all 0.2s;"
                                        @if($result) disabled @endif>
                                        {{ $optLabel }}
                                    </button>
                                @endforeach
                            </div>

                            @if ($result)
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    @if ($qCorrect)
                                        <span style="color: #16a34a; font-weight: 800; font-size: 0.9rem;">✓ Richtig</span>
                                    @else
                                        <span style="color: #dc2626; font-weight: 800; font-size: 0.9rem;">✗ Falsch</span>
                                        <span style="color: #16a34a; font-weight: 700; font-size: 0.82rem; border: 1.5px solid #16a34a; padding: 0.2rem 0.5rem; border-radius: 6px; background: #f0fdf4; display: inline-flex; align-items: center;">
                                            Lösung: @if($expected==='+') Richtig (+) @elseif($expected==='-') Falsch (-) @else Nicht im Text (x) @endif
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Question 24 (MC Title selection) --}}
            @if ($t3_24)
                @php
                    $ansVal = $answers[$t3_24['id']] ?? '';
                    $expected24 = $t3_24['answer'] ?? '';
                    $correct24 = $result && $ansVal === $expected24;
                @endphp
                <div style="margin-top: 2.5rem; background: #f8fafc; border: 1.5px solid #cbd5e1; border-radius: 8px; padding: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1rem;">
                        <div style="display: flex; gap: 0.75rem; align-items: flex-start;">
                            <span style="background: #d8232a; color: #ffffff; font-weight: 850; font-size: 0.9rem; padding: 0.25rem 0.55rem; border-radius: 4px; flex-shrink: 0;">24</span>
                            <h4 style="font-size: 0.98rem; font-weight: 800; color: #1f2937; margin: 0; padding-top: 0.2rem;">{{ $t3_24['instruction'] }}</h4>
                        </div>
                        @if ($result)
                            @if ($correct24)
                                <span style="color: #16a34a; font-weight: 800; font-size: 0.9rem;">✓ Richtig</span>
                            @else
                                <span style="color: #dc2626; font-weight: 800; font-size: 0.9rem;">✗ Falsch</span>
                            @endif
                        @endif
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach (['a' => 'Fakt versus Fiktion – Wissenschaftler wollen ihr Image im Film verbessern', 'b' => 'Verrückt und gefährlich – warum Wissenschaftler im Film immer die Bösen sind', 'c' => 'Zwischen Genie und Wahnsinn – Stereotypen von Wissenschaftlern im Film'] as $k => $txt)
                            @php
                                $isSelected = $ansVal === $k;
                                $bg = '#ffffff';
                                $border = '#cbd5e1';
                                $color = '#374151';
                                
                                if ($isSelected) {
                                    $bg = '#fdf2f2';
                                    $border = '#d8232a';
                                    $color = '#d8232a';
                                }
                                
                                if ($result) {
                                    $isThisCorrect = $k === $expected24;
                                    if ($isThisCorrect) {
                                        $bg = '#f0fdf4';
                                        $border = '#16a34a';
                                        $color = '#16a34a';
                                    } elseif ($isSelected && !$isThisCorrect) {
                                        $bg = '#fdf2f2';
                                        $border = '#dc2626';
                                        $color = '#dc2626';
                                    }
                                }
                            @endphp
                            <div @if(!$result) wire:click="$set('answers.{{ $t3_24['id'] }}', '{{ $k }}')" @endif
                                style="border: 2px solid {{ $border }}; background: {{ $bg }}; color: {{ $color }}; padding: 0.85rem 1.25rem; border-radius: 8px; cursor: @if($result) default @else pointer @endif; display: flex; align-items: center; gap: 0.75rem; transition: all 0.2s; font-weight: 700; box-shadow: 0 1px 2px rgba(0,0,0,0.01);">
                                <span style="font-weight: 850; font-size: 0.95rem; text-transform: uppercase;">{{ $k }})</span>
                                <span style="font-size: 0.95rem;">{{ $txt }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- ==================== SPRACHBAUSTEINE ==================== --}}
    @if ($sbCloze)
        <div style="margin-bottom: 2rem;">
            <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 1.25rem; border-bottom: 2px solid #e2e8f0; padding-bottom: 0.5rem;">
                <h3 style="font-size: 1.4rem; font-weight: 800; color: #d8232a; margin: 0;">Sprachbausteine</h3>
                <span style="font-size: 0.85rem; font-weight: 700; color: #6b7280; text-transform: uppercase;">Wortlücken</span>
            </div>

            @if ($sbInstruction)
                <div style="font-size: 0.95rem; line-height: 1.6; color: #475569; font-weight: 600; background: #fdf2f2; border-left: 4px solid #d8232a; padding: 1rem 1.25rem; border-radius: 4px; margin-bottom: 1.5rem;">
                    {!! nl2br(e($sbInstruction['content'])) !!}
                </div>
            @endif

            {{-- Cloze Text with inline MC selects --}}
            <div style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 8px; padding: 1.75rem; line-height: 2.3rem; font-size: 1.02rem; color: #1e293b; text-align: justify; box-shadow: 0 1px 3px rgba(0,0,0,0.01);">
                @php
                    $sbContent = $sbCloze['content'] ?? '';
                    $sbParts = preg_split('/(--\d+--)/', $sbContent, -1, PREG_SPLIT_DELIM_CAPTURE);
                    $sbGapsArray = is_array($sbCloze['options'] ?? null) ? $sbCloze['options'] : json_decode($sbCloze['options'] ?? '[]', true);
                @endphp
                @foreach ($sbParts as $part)
                    @if (preg_match('/^--(\d+)--$/', $part, $matches))
                        @php
                            $gapIdx = (int)$matches[1];
                            $gapDef = $sbGapsArray[$gapIdx] ?? null;
                            
                            $current = $answers[$sbCloze['id']][$gapIdx] ?? '';
                            $gapExpected = $gapDef['answer'] ?? '';
                            
                            $selectBorder = '#cbd5e1';
                            $selectBg = '#ffffff';
                            $selectColor = '#1e293b';
                            $gapCorrect = false;
                            
                            if ($result) {
                                $detail = collect($result['details'])->firstWhere('id', "{$sbCloze['id']}.gap.{$gapIdx}");
                                $gapCorrect = $detail['correct'] ?? false;
                                $selectBorder = $gapCorrect ? '#16a34a' : '#dc2626';
                                $selectBg = $gapCorrect ? '#f0fdf4' : '#fdf2f2';
                                $selectColor = $gapCorrect ? '#16a34a' : '#dc2626';
                            }
                            
                            $optList = parseMultipleChoiceOptions($gapDef['options'] ?? '');
                        @endphp
                        <span style="display: inline-flex; align-items: center; margin: 0 0.25rem; vertical-align: middle;">
                            <span style="background: #475569; color: #ffffff; font-size: 0.75rem; font-weight: 800; padding: 0.2rem 0.45rem; border-radius: 4px 0 0 4px; height: 32px; display: inline-flex; align-items: center;">{{ $gapIdx + 25 }}</span>
                            <select wire:model.change="answers.{{ $sbCloze['id'] }}.{{ $gapIdx }}" {{ $result ? 'disabled' : '' }}
                                style="font-family: inherit; font-size: 0.9rem; font-weight: 700; padding: 0.2rem 0.5rem; border: 2.5px solid {{ $selectBorder }}; background: {{ $selectBg }}; color: {{ $selectColor }}; border-radius: 0 4px 4px 0; outline: none; cursor: pointer; height: 32px; width: 140px;">
                                <option value="">Wählen...</option>
                                @foreach ($optList as $key => $txt)
                                    <option value="{{ $key }}">{{ strtoupper($key) }}) {{ $txt }}</option>
                                @endforeach
                            </select>
                            @if ($result)
                                @if ($gapCorrect)
                                    <span style="color: #16a34a; font-weight: 900; margin-left: 0.25rem; font-size: 1.1rem;">✓</span>
                                @else
                                    <span style="color: #dc2626; font-weight: 900; margin-left: 0.25rem; font-size: 1.1rem;">✗</span>
                                    <span style="color: #16a34a; font-weight: 800; font-size: 0.82rem; margin-left: 0.25rem; border: 1.5px solid #16a34a; padding: 0.15rem 0.4rem; border-radius: 4px; background: #f0fdf4; height: 28px; display: inline-flex; align-items: center;">
                                        Lösung: {{ strtoupper($gapExpected) }}) {{ $optList[$gapExpected] ?? '' }}
                                    </span>
                                @endif
                            @endif
                        </span>
                    @else
                        {!! nl2br(e($part)) !!}
                    @endif
                @endforeach
            </div>
        </div>
    @endif

</section>
