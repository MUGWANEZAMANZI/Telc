<section class="c1-section telc-teil4-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); width: 100%; display: flex; flex-direction: column;">

        @php
            // Find the text element (which contains title, eyebrow, and example)
            $textEl = collect($section['elements'])->firstWhere(function($el) {
                return ($el['type'] ?? '') === 'text' && str_contains($el['id'] ?? '', 'LV4');
            });
            if (!$textEl) {
                $textEl = collect($section['elements'])->firstWhere('type', 'text');
            }

            $teil4Title = $textEl['content'] ?? ($section['title'] ?? 'Leseverstehen Teil 4');
            $teil4Eyebrow = $textEl['label'] ?? '';
            $instruction = $textEl['instruction'] ?? ($section['instruction'] ?? '');
            
            // Example
            $teil4Example = [
                'number' => 0,
                'statement' => $textEl['instruction'] ?? '',
                'solution' => $textEl['options'] ?? 'a',
            ];

            // Portagers (Text Passages): all elements of type 'goethe_portrait' in this section
            $portraits = collect($section['elements'])->where('type', 'goethe_portrait')->all();

            // Questions (Statements): all elements of type 'matching' in this section
            $questions = collect($section['elements'])->where('type', 'matching')->all();

            $firstQ = collect($questions)->first();
            $teil4Options = $firstQ ? array_filter(array_map('trim', explode(',', $firstQ['options'] ?? ''))) : ['a', 'b', 'c', '0'];
            if (empty($teil4Options)) {
                $teil4Options = ['a', 'b', 'c', '0'];
            }
        @endphp

        <div style="border-bottom: 2px solid #1a1a1a; padding-bottom: 0.75rem; margin-bottom: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap;">
                <h2 style="font-size: 1.8rem; font-weight: 900; color: #111827; margin: 0;">{{ $teil4Title }}</h2>
                <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 1rem;">
                    <span>Teil 4</span>
                    <span style="background: #1a1a1a; color: #ffffff; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">S. {{ $sectionIdx + 6 }}</span>
                </div>
            </div>

            @if ($instruction)
                <div class="c1-instructions" style="margin-top: 1.5rem; margin-bottom: 1.5rem; font-style: italic; color: #555; background: #fafafa; border-left: 3px solid #d8232a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6;">
                    {!! nl2br(e($instruction)) !!}
                </div>
            @endif
        </div>

        {{-- Example & Statement List --}}
        <div style="font-size: 0.95rem; color: #334155; margin-bottom: 2rem;">
            <div style="font-weight: 800; margin-bottom: 0.85rem; color: #1e293b;">Beispiel:</div>
            <div style="display: flex; gap: 1rem; align-items: baseline; margin-bottom: 2rem; padding: 0.5rem 1rem; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; color: #64748b;">
                <strong style="color:#1e293b; width: 1.5rem; flex-shrink:0;">{{ $teil4Example['number'] ?? 0 }}</strong>
                <span style="flex-grow: 1;">{{ $teil4Example['statement'] ?? '' }}</span>
                <strong style="white-space: nowrap; color: #1e293b; margin-left: auto;">Lösung: {{ strtoupper($teil4Example['solution'] ?? 'a') }}</strong>
            </div>

            <div style="font-weight: 800; margin-bottom: 0.85rem; color: #1e293b;">Aussagen:</div>
            @foreach ($questions as $q)
                @php
                    preg_match('/\d+/', $q['label'] ?? '', $m);
                    $num = $m[0] ?? ($loop->index + 24);
                    $elementId = $q['id'];
                    $isSelected = ($answers[$elementId] ?? '') !== '';
                    $selectBorder = '#cbd5e1';
                    $selectBg = '#ffffff';
                    $selectColor = '#1e293b';
                    $isCorrect = false;
                    $expected = '';
                    
                    if ($result) {
                        $detail = collect($result['details'])->firstWhere('id', $elementId);
                        if ($detail) {
                            $isCorrect = $detail['correct'] ?? false;
                            $expected = $detail['expected'] ?? '';
                            $selectBorder = $isCorrect ? '#16a34a' : '#dc2626';
                            $selectBg = $isCorrect ? '#f0fdf4' : '#fdf2f2';
                            $selectColor = $isCorrect ? '#16a34a' : '#dc2626';
                        }
                    }
                @endphp
                <div style="display: flex; gap: 1rem; align-items: baseline; margin-bottom: 1.25rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 0.75rem;">
                    <strong style="color:#1e293b; width: 1.5rem; flex-shrink:0;">{{ $num }}</strong>
                    <span style="line-height: 1.5; color: #334155; flex-grow: 1;">{{ $q['instruction'] ?? ($q['content'] ?? '') }}</span>
                    
                    {{-- Inline select matching options --}}
                    <div style="display: inline-flex; align-items: center; gap: 0.5rem; margin-left: auto;">
                        <span style="font-size: 0.85rem; font-weight: 700; color: #475569; text-transform: uppercase;">Abschnitt:</span>
                        <select wire:model.live="answers.{{ $elementId }}" @disabled($result)
                            style="font-family: inherit; font-size: 0.9rem; font-weight: 700; padding: 0.25rem 0.5rem; border: 2px solid {{ $selectBorder }}; background: {{ $selectBg }}; color: {{ $selectColor }}; border-radius: 6px; outline: none; cursor: pointer; width: 110px;">
                            <option value="">Wählen...</option>
                            @foreach ($teil4Options as $opt)
                                <option value="{{ $opt }}">{{ $opt === '0' ? 'Keiner (0)' : strtoupper($opt) }}</option>
                            @endforeach
                        </select>
                        
                        @if ($result)
                            @php
                                $detail = collect($result['details'])->firstWhere('id', $elementId);
                            @endphp
                            @if ($detail)
                                <span style="font-weight: bold; font-size: 0.85rem; white-space: nowrap; padding-left: 0.5rem;">
                                    @if ($detail['correct'])
                                        <span style="color: #16a34a;">✓ Richtig</span>
                                    @else
                                        <span style="color: #dc2626;">✗ Falsch</span>
                                        <span style="color: #16a34a; margin-left: 0.25rem;">(Lösung: {{ strtoupper($detail['expected']) }})</span>
                                    @endif
                                </span>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Text Passages a, b, c --}}
        <div style="border-top: 1px solid #e5e7eb; padding-top: 1.75rem; margin-bottom: 3rem;">
            @if ($teil4Eyebrow || $teil4Title)
                <div style="text-align: center; margin-bottom: 1.3rem;">
                    @if ($teil4Eyebrow)
                        <div style="font-size: 0.7rem; letter-spacing: 0.08em; text-transform: uppercase; color: #52525b; margin-bottom: 0.45rem;">{{ $teil4Eyebrow }}</div>
                    @endif
                    @if ($teil4Title)
                        <h3 style="font-size: 1.35rem; line-height: 1.2; font-weight: 900; margin: 0; color: #111827;">{{ $teil4Title }}</h3>
                    @endif
                </div>
            @endif

            @foreach ($portraits as $idx => $p)
                @php
                    $labelParts = explode(':', $p['label'] ?? '', 2);
                    if (count($labelParts) === 2 && strlen(trim($labelParts[0])) === 1) {
                        $letter = strtolower(trim($labelParts[0]));
                        $name = trim($labelParts[1]);
                    } else {
                        $letter = chr(ord('a') + $idx);
                        $name = $p['label'] ?? '';
                    }
                @endphp
                <article style="margin-bottom: 2.25rem; font-family: 'Arial Narrow', Arial, sans-serif; font-size: 0.88rem; line-height: 1.42; color: #27272a;">
                    <h4 style="font-size: 0.92rem; font-weight: 900; margin: 0 0 0.25rem; color: #111827;">
                        <span style="background: #1e293b; color: #ffffff; font-weight: 800; font-size: 0.95rem; width: 1.75rem; height: 1.75rem; display: inline-flex; align-items: center; justify-content: center; border-radius: 4px; text-transform: uppercase; margin-right: 0.5rem;">{{ $letter }}</span>
                        {{ $name }}
                    </h4>
                    <p style="margin: 0; text-align: left;">{{ $p['content'] ?? '' }}</p>
                </article>
            @endforeach
        </div>

        {{-- Answer Grid --}}
        <div class="c1-teil4-answer-sheet" style="background: #ffffff; padding: 2rem; border: 1px solid #cbd5e1; border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif; width: 100%; overflow-x: auto;">
            <h3 style="font-size: 1.25rem; font-weight: 800; color: #1a1a1a; margin-top: 0; margin-bottom: 0.5rem; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem;">Antwortbogen: Teil 4</h3>

            <style>
                .teil4-grid-header {
                    display: flex !important;
                    flex-direction: row;
                    align-items: center;
                    gap: 0.5rem;
                    font-weight: 800;
                    font-size: 0.85rem;
                    color: #475569;
                    border-bottom: 2px solid #1e293b;
                    padding-bottom: 0.5rem;
                    margin-bottom: 0.5rem;
                    min-width: max-content;
                }
                .teil4-grid-header span:first-child {
                    width: 5.5rem;
                    flex-shrink: 0;
                }
                .teil4-grid-header span:not(:first-child) {
                    width: 28px;
                    text-align: center;
                    flex-shrink: 0;
                    text-transform: uppercase;
                }
                .teil4-grid-row {
                    display: flex !important;
                    flex-direction: row !important;
                    align-items: center;
                    gap: 0.5rem;
                    padding: 0.5rem 0;
                    border-bottom: 1px solid #f1f5f9;
                    min-width: max-content;
                }
                .teil4-grid-num {
                    width: 5.5rem;
                    flex-shrink: 0;
                    font-weight: 800;
                    font-size: 0.95rem;
                    color: #1e293b;
                }
                .teil4-grid-box {
                    width: 28px;
                    height: 28px;
                    border: 1px solid #cbd5e1;
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    font-weight: 750;
                    font-size: 0.85rem;
                    color: #4b5563;
                    border-radius: 4px;
                    cursor: pointer;
                    position: relative;
                    user-select: none;
                    transition: all 0.15s;
                    background: #ffffff;
                    margin: 0;
                }
                .teil4-grid-box:hover:not(.disabled) {
                    border-color: #1a1a1a;
                    background-color: #f4f4f5;
                }
                .teil4-grid-box.selected::after {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: 
                    linear-gradient(to top right, transparent 46%, #1a1a1a 46%, #1a1a1a 54%, transparent 54%),
                    linear-gradient(to bottom right, transparent 46%, #1a1a1a 46%, #1a1a1a 54%, transparent 54%);
                }
                .teil4-grid-box.selected.correct-answer-highlight::after {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: 
                    linear-gradient(to top right, transparent 46%, #16a34a 46%, #16a34a 54%, transparent 54%),
                    linear-gradient(to bottom right, transparent 46%, #16a34a 46%, #16a34a 54%, transparent 54%);
                }
                .teil4-grid-box.selected.wrong-answer::after,
                .teil4-grid-box.wrong-answer::after {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: 
                    linear-gradient(to top right, transparent 46%, #dc2626 46%, #dc2626 54%, transparent 54%),
                    linear-gradient(to bottom right, transparent 46%, #dc2626 46%, #dc2626 54%, transparent 54%);
                }
                .teil4-grid-box.disabled {
                    cursor: not-allowed;
                }
                
                .teil4-grid-box.bg-green-100 {
                    background-color: #f0fdf4 !important;
                    border: 2px solid #16a34a !important;
                    color: #15803d !important;
                    font-weight: 700 !important;
                }
                .teil4-grid-box.bg-red-100 {
                    background-color: #fdf2f2 !important;
                    border: 2px solid #dc2626 !important;
                    color: #b91c1c !important;
                }
                .teil4-grid-box.bg-green-50,
                .teil4-grid-box.correct-answer-highlight {
                    background-color: #f0fdf4 !important;
                    border: 2px solid #22c55e !important;
                    color: #15803d !important;
                    font-weight: 700 !important;
                }
            </style>
 


            <div class="teil4-grid-header">
                <span>Aufgabe</span>
                @foreach ($teil4Options as $opt)
                    <span>{{ $opt }}</span>
                @endforeach
            </div>
 
            @foreach ($questions as $q)
                @php
                    preg_match('/\d+/', $q['label'] ?? '', $m);
                    $rowNum = $m[0] ?? ($loop->index + 24);
                    $elementId = $q['id'];
                    $currentAnswer = $answers[$elementId] ?? '';
                @endphp
                <div class="teil4-grid-row" wire:key="teil4-row-{{ $rowNum }}">
                    <span class="teil4-grid-num">Aufgabe {{ $rowNum }}</span>
                    @foreach ($teil4Options as $key)
                        @php
                            $isSelected = strtolower($currentAnswer) === strtolower($key);
                            $isCorrectAnswer = false;
                            $expectedAnswer = '';
                            if ($result) {
                                $detail = collect($result['details'])->firstWhere('id', $elementId);
                                if ($detail) {
                                    $isCorrectAnswer = $detail['correct'] ?? false;
                                    $expectedAnswer = $detail['expected'] ?? '';
                                }
                            }
                            $isExpected = strtolower($expectedAnswer) === strtolower($key);
                        @endphp
                        <button type="button"
                            wire:key="teil4-btn-{{ $rowNum }}-{{ $key }}"
                            @if(!$result) wire:click="$set('answers.{{ $elementId }}', '{{ $isSelected ? '' : $key }}')" @endif
                            @class([
                                'teil4-grid-box transition-all duration-150',
                                'selected' => $isSelected,
                                'disabled' => $result,
                                'bg-green-100 text-green-800 border-green-500 font-bold' => $result && $isSelected && $isCorrectAnswer,
                                'bg-red-100 text-red-800 border-red-500' => $result && $isSelected && !$isCorrectAnswer,
                                'bg-green-50 text-green-700 border-green-300' => $result && !$isSelected && $isExpected,
                                'correct-answer-highlight' => $result && $isExpected,
                                'wrong-answer' => $result && $isSelected && !$isExpected,
                            ])
                            title="Aufgabe {{ $rowNum }} - Option {{ strtoupper($key) }}"
                        >
                            {{ $key }}
                        </button>
                    @endforeach
                </div>
            @endforeach
        </div>
    </section>
