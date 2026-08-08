<div class="answer-sheet" data-answer-sheet>
    <div class="answer-sheet-header">
        <div>
            <span class="answer-sheet-title">Antwortbogen</span>
            <span class="answer-sheet-section">{{ $sectionTitle ?? '' }}</span>
        </div>
        @if ($showAudio ?? false)
            @foreach (($audioElements ?? []) as $audioEl)
                <audio controls src="{{ $audioEl['content'] }}" class="answer-sheet-audio"></audio>
            @endforeach
        @endif
    </div>

    @php
        $gapItems = app(\App\Services\GapParser::class)->allGaps($sections ?? []);
        $sheetGaps = collect($gapItems)->filter(fn ($item) => $item['type'] !== 'text');
    @endphp

    @if ($sheetGaps->isNotEmpty())
        <div class="answer-sheet-grid">
            @foreach ($sheetGaps as $gap)
                @php
                    $wireKey = $gap['elementId'].'.'.$gap['gapId'];
                    $current = $answers[$gap['elementId']][$gap['gapId']] ?? '';
                    $isDropdown = $gap['type'] === 'dropdown' || $gap['type'] === 'matching';
                    
                    $fieldBorder = '#cbd5e1';
                    $detail = null;
                    $expected = '';
                    if ($result ?? false) {
                        $detail = collect($result['details'] ?? [])->firstWhere('id', $gap['elementId'].'.gap.'.$gap['gapId']);
                        if ($detail) {
                            $fieldBorder = $detail['correct'] ? '#16a34a' : '#dc2626';
                            $expected = $detail['expected'] ?? '';
                        }
                    }
                @endphp

                <div class="answer-sheet-row" wire:key="sheet-{{ $wireKey }}">
                    <span class="question-num">{{ $gap['label'] }}</span>

                    @if ($isDropdown)
                        <select wire:model.live="answers.{{ $wireKey }}" @disabled($result ?? false) class="bubble-select" style="border: 1.5px solid {{ $fieldBorder }}; outline: none; cursor: pointer;">
                            <option value="">—</option>
                            @foreach ($gap['options'] as $opt)
                                <option value="{{ $opt['value'] }}"
                                    @if($result && $detail && $opt['value'] === $expected)
                                        style="color: #16a34a; font-weight: bold; background-color: #f0fdf4;"
                                    @elseif($result && $detail && $current === $opt['value'] && !$detail['correct'])
                                        style="color: #dc2626; text-decoration: line-through; background-color: #fdf2f2;"
                                    @endif
                                >{{ $opt['label'] }}</option>
                            @endforeach
                        </select>
                    @else
                        <input type="text" wire:model.live="answers.{{ $wireKey }}" @disabled($result ?? false) class="bubble-input" placeholder="..." style="border: 1.5px solid {{ $fieldBorder }}; outline: none;">
                    @endif

                    @if ($result ?? false)
                        @if ($detail)
                            @if ($detail['correct'])
                                <span style="font-weight: 700; font-size: 0.9rem; margin-left: 0.5rem; color: #16a34a;">
                                    ✓ Richtig
                                </span>
                            @else
                                <span style="display: inline-flex; align-items: center; gap: 0.25rem;">
                                    @if ($current !== '')
                                        <span style="font-weight: 700; font-size: 0.9rem; color: #dc2626;">✗ Falsch</span>
                                    @endif
                                    <span style="font-weight: 700; font-size: 0.8rem; color: #16a34a; border: 1px solid #16a34a; padding: 0.05rem 0.25rem; border-radius: 4px; background-color: #f0fdf4; white-space: nowrap;">
                                        Lös: {{ strtoupper($expected) }}
                                    </span>
                                </span>
                            @endif
                        @endif
                    @endif
                </div>
            @endforeach
        </div>
    @else
        <div class="answer-sheet-empty">
            <p>No answerable items in this section.</p>
        </div>
    @endif
</div>

@once
    <style>
        .answer-sheet { background: #fff; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem; }
        .answer-sheet-header { display: flex; align-items: flex-start; justify-content: space-between; gap: 1rem; margin-bottom: 1rem; }
        .answer-sheet-title { font-weight: 700; font-size: 0.95rem; color: #1e293b; display: block; }
        .answer-sheet-section { font-size: 0.8rem; color: #64748b; }
        .answer-sheet-audio { height: 36px; width: 200px; }
        .answer-sheet-grid { display: flex; flex-direction: column; gap: 0.5rem; }
        .answer-sheet-row { display: flex; align-items: center; gap: 0.5rem; padding: 0.35rem 0; border-bottom: 1px solid #f1f5f9; }
        .answer-sheet-row:last-child { border-bottom: none; }
        .question-num { width: 2rem; font-weight: 700; font-size: 0.85rem; color: #334155; text-align: center; flex-shrink: 0; }
        .bubble-select { flex: 1; padding: 0.3rem 0.5rem; border: 1.5px solid #cbd5e1; border-radius: 5px; font-size: 0.82rem; background: #fff; cursor: pointer; }
        .bubble-select:focus { border-color: #6366f1; outline: none; box-shadow: 0 0 0 2px rgba(99,102,241,0.15); }
        .bubble-input { flex: 1; padding: 0.3rem 0.5rem; border: 1.5px solid #cbd5e1; border-radius: 5px; font-size: 0.82rem; }
        .bubble-input:focus { border-color: #6366f1; outline: none; box-shadow: 0 0 0 2px rgba(99,102,241,0.15); }
        .answer-mark { font-size: 0.78rem; font-weight: 600; flex-shrink: 0; width: 2rem; text-align: center; }
        .answer-mark.correct { color: #16a34a; }
        .answer-mark:not(.correct) { color: #dc2626; }
        .answer-sheet-empty { text-align: center; color: #94a3b8; font-size: 0.85rem; padding: 1rem 0; }

        /* bubble group style for dropdown items */
        .bubble-group { display: flex; gap: 0.25rem; flex-wrap: wrap; }
        .bubble { display: inline-flex; align-items: center; justify-content: center; width: 1.8rem; height: 1.8rem; border: 1.5px solid #cbd5e1; border-radius: 50%; font-size: 0.75rem; font-weight: 600; color: #64748b; cursor: pointer; transition: all 0.12s; }
        .bubble:hover { border-color: #6366f1; background: #eef2ff; }
        .bubble.selected { border-color: #6366f1; background: #6366f1; color: #fff; }
        .bubble input[type="radio"] { display: none; }
    </style>
@endonce
