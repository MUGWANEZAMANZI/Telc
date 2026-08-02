<section class="c1-section telc-listening-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); width: 100%; display: flex; flex-direction: column;">

    <style>
        .hv-answer-row { display: flex; align-items: center; gap: 0.75rem; padding: 0.45rem 0; border-bottom: 1px solid #f1f5f9; }
        .hv-answer-row:last-child { border-bottom: 0; }
        .hv-qnum { flex-shrink: 0; width: 2.5rem; height: 1.9rem; display: inline-flex; align-items: center; justify-content: center; background: #f3f4f6; border-radius: 4px; font-weight: 800; font-size: 0.85rem; color: #1e293b; }
        .hv-prompt { flex: 1; min-width: 0; font-size: 0.9rem; line-height: 1.5; color: #334155; }
        .hv-select { flex-shrink: 0; min-width: 6rem; }
        .hv-input { flex: 1; min-width: 0; }
        .hv-mc-option-row { display: flex; align-items: center; gap: 0.75rem; cursor: pointer; padding: 0.3rem 0; }
        .hv-mc-box { width: 1.75rem; height: 1.75rem; border: 1px solid #cbd5e1; border-radius: 4px; display: inline-flex; align-items: center; justify-content: center; font-weight: 800; font-size: 0.85rem; color: #4b5563; background: #ffffff; flex-shrink: 0; transition: all 0.15s; }
        .hv-mc-box:hover:not(.hv-disabled) { border-color: #1a1a1a; background-color: #f4f4f5; }
        .hv-mc-option-row.selected .hv-mc-box { border-color: #1a1a1a; background-color: #1a1a1a; color: #ffffff; }
        .hv-mc-option-row.selected .hv-mc-text { color: #1a1a1a; font-weight: 700; }
        .hv-mc-option-row .hv-mc-text { font-size: 0.9rem; line-height: 1.5; color: #4b5563; }
        .hv-feedback { margin-left: auto; font-weight: 700; font-size: 0.85rem; white-space: nowrap; padding-left: 1rem; }
    </style>

    @php
        $els = collect($section['elements'] ?? []);
        $audioEl = $els->firstWhere('id', 'c1-audio');
        $hv1Instr = $els->firstWhere('id', 'C1-HV1-instruction');
        $hv2Instr = $els->firstWhere('id', 'C1-HV2-instruction');
        $hv3Instr = $els->firstWhere('id', 'C1-HV3-instruction');
        $hv4Instr = $els->firstWhere('id', 'C1-HV4-instruction');

        $hv1Tasks = $els->filter(fn ($e) => str_starts_with($e['id'] ?? '', 'C1-HV1-') && ($e['type'] ?? '') === 'matching')->values()->all();
        $hv2Tasks = $els->filter(fn ($e) => str_starts_with($e['id'] ?? '', 'C1-HV2-') && in_array($e['type'] ?? '', ['multiple_choice', 'true_false'], true))->values()->all();
        $hv3Tasks = $els->filter(fn ($e) => str_starts_with($e['id'] ?? '', 'C1-HV3-') && in_array($e['type'] ?? '', ['fill_blank', 'multiple_choice', 'true_false'], true))->values()->all();
        $hv4Tasks = $els->filter(fn ($e) => str_starts_with($e['id'] ?? '', 'C1-HV4-'))->values()->all();

        $pages = $section['paper_pages'] ?? [];
        $pageBadge = 'S. ' . ($sectionIdx + 6);
        if (is_array($pages) && count($pages) > 0 && preg_match('/page-(\d+)/', (string) $pages[0], $pm)) {
            $pageBadge = 'S. ' . (int) $pm[1];
            $lastPage = $pages[count($pages) - 1];
            if (preg_match('/page-(\d+)/', (string) $lastPage, $pm2) && (int) $pm2[1] !== (int) $pm[1]) {
                $pageBadge .= '–' . (int) $pm2[1];
            }
        }

        $teilTitle = function ($el): array {
            $parts = array_map('trim', explode(':', $el['label'] ?? '', 2));
            return [$parts[0] ?? '', $parts[1] ?? ''];
        };

        $splitMc = function (string $instruction): array {
            $stem = [];
            $options = [];
            foreach (preg_split('/\r?\n/', $instruction) as $line) {
                if (preg_match('/^\s*([a-c])\s*\)\s*(.+)$/', $line, $m)) {
                    $options[trim($m[1])] = trim($m[2]);
                } elseif (trim($line) !== '') {
                    $stem[] = trim($line);
                }
            }
            return [implode(' ', $stem), $options];
        };

        $qNum = fn ($el) => (int) preg_replace('/[^0-9]/', '', $el['label'] ?? '');

        $optionsOf = function ($el): array {
            $raw = $el['options'] ?? '';
            if (is_string($raw) && trim($raw) !== '') {
                $opts = array_values(array_filter(array_map('trim', explode(',', $raw))));
                if ($opts !== []) {
                    return $opts;
                }
            }
            if (is_array($raw)) {
                return array_values($raw);
            }
            return match ($el['type'] ?? '') {
                'true_false' => ['+', '-'],
                'multiple_choice' => ['a', 'b', 'c', 'd'],
                'matching' => range('a', 'j'),
                default => [],
            };
        };
    @endphp

    <div style="border-bottom: 2px solid #1a1a1a; padding-bottom: 0.75rem; margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap;">
            <h2 style="font-size: 1.8rem; font-weight: 900; color: #111827; margin: 0;">{{ $section['title'] ?? 'Hörverstehen' }}</h2>
            <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 1rem;">
                <span>circa {{ $section['minutes'] ?? 40 }} Minuten</span>
                <span style="background: #1a1a1a; color: #ffffff; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">{{ $pageBadge }}</span>
            </div>
        </div>

        @if ($audioEl && !$isGuest)
            <div class="audio-player" style="margin-top: 1rem; max-width: 420px;">
                <audio controls src="{{ $audioEl['content'] }}"></audio>
            </div>
        @endif
    </div>

    {{-- ==================== TEIL 1 (Zuordnung) ==================== --}}
    @if ($hv1Instr || count($hv1Tasks) > 0)
        @php [$hv1Head, $hv1Sub] = $teilTitle($hv1Instr); @endphp
        <div style="margin-bottom: 2.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $hv1Head }}</h3>
                @if ($hv1Sub)
                    <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">{{ $hv1Sub }}</span>
                @endif
            </div>
            @if ($hv1Instr && trim($hv1Instr['content'] ?? '') !== '')
                <div style="margin-bottom: 1.25rem; font-style: italic; color: #555; background: #fafafa; border-left: 3px solid #d8232a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6; white-space: pre-line;">
                    {{ $hv1Instr['content'] }}
                </div>
            @elseif ($hv1Instr && trim($hv1Instr['instruction'] ?? '') !== '')
                <div style="margin-bottom: 1.25rem; font-style: italic; color: #555; background: #fafafa; border-left: 3px solid #d8232a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6;">
                    {{ $hv1Instr['instruction'] }}
                </div>
            @endif

            @php
                $hv1Options = [];
                $hv1Example = null;
                $hv1Statements = [];
                foreach (preg_split('/\r?\n/', trim($hv1Instr['instruction'] ?? '')) as $line) {
                    $line = trim($line);
                    if (preg_match('/^([a-c])\s+(.+)$/', $line, $m)) {
                        $hv1Options[$m[1]] = trim($m[2]);
                    } elseif (preg_match('/^(\d+)\s+(.+)$/', $line, $m)) {
                        $num = (int) $m[1];
                        $text = trim($m[2]);
                        if ($num === 0) {
                            $hv1Example = $text;
                        } else {
                            $hv1Statements[$num] = $text;
                        }
                    }
                }
            @endphp
            @if (count($hv1Options) > 0 || count($hv1Statements) > 0 || $hv1Example !== null)
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 1.25rem 1.5rem; margin-bottom: 1.5rem;">
                    @if (count($hv1Options) > 0)
                        <div style="font-weight: 800; margin-bottom: 0.75rem; color: #1e293b;">Bücher zur Auswahl:</div>
                        @foreach ($hv1Options as $letter => $text)
                            <div style="display: flex; gap: 1rem; align-items: baseline; margin-bottom: 0.5rem;">
                                <strong style="color: #1e293b; width: 1.25rem; flex-shrink: 0; text-align: right;">{{ $letter }}</strong>
                                <span style="font-size: 0.92rem; line-height: 1.5; color: #334155;">{{ $text }}</span>
                            </div>
                        @endforeach
                    @endif
                    @if ($hv1Example !== null)
                        <div style="margin-top: 1rem; padding-top: 0.75rem; border-top: 1px dashed #e2e8f0; display: flex; gap: 1rem; align-items: baseline; color: #64748b;">
                            <strong style="width: 1.25rem; flex-shrink: 0; text-align: right; color: #1e293b;">0</strong>
                            <span style="font-size: 0.92rem; line-height: 1.5;">
                                <strong style="color: #1e293b; margin-right: 0.5rem;">(Beispiel)</strong>{{ $hv1Example }}
                            </span>
                        </div>
                    @endif
                </div>
            @endif

            @if (count($hv1Tasks) > 0)
                <div style="background: #ffffff; padding: 1.5rem; border: 1px solid #cbd5e1; border-radius: 8px;">
                    <h4 style="font-size: 1.05rem; font-weight: 800; color: #1a1a1a; margin: 0 0 0.5rem; border-bottom: 2px solid #1a1a1a; padding-bottom: 0.5rem;">Antwortbogen: Teil 1</h4>
                    @foreach ($hv1Tasks as $task)
                        @php
                            $optList = $optionsOf($task);
                            $current = $answers[$task['id']] ?? '';
                            $detail = $result ? collect($result['details'])->firstWhere('id', $task['id']) : null;
                            $selCls = '';
                            if ($result && $current !== '') {
                                $selCls = $detail && $detail['correct'] ? 'correct' : 'wrong';
                            }
                            $hvQ = $qNum($task);
                            $hvStatement = $hv1Statements[$hvQ] ?? '';
                        @endphp
                        <div class="hv-answer-row">
                            <span class="hv-qnum">{{ $hvQ }}</span>
                            <span class="hv-prompt">{{ $hvStatement }}</span>
                            <select wire:model.live="answers.{{ $task['id'] }}" class="matching-select hv-select @if($selCls) {{ $selCls }} @endif" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($optList as $opt)
                                    <option value="{{ $opt }}" @selected($current === $opt)>{{ $opt }}</option>
                                @endforeach
                            </select>
                            @if ($result && $detail)
                                <span class="hv-feedback" style="color: {{ $detail['correct'] ? '#16a34a' : '#dc2626' }};">
                                    {{ $detail['correct'] ? '✓' : '✗ (Lösung: ' . $detail['expected'] . ')' }}
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    @endif

    {{-- ==================== TEIL 2 (Multiple Choice) ==================== --}}
    @if ($hv2Instr || count($hv2Tasks) > 0)
        @php [$hv2Head, $hv2Sub] = $teilTitle($hv2Instr); @endphp
        <div style="margin-bottom: 2.5rem; border-top: 1px solid #e5e7eb; padding-top: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $hv2Head }}</h3>
                @if ($hv2Sub)
                    <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">{{ $hv2Sub }}</span>
                @endif
            </div>
            @if ($hv2Instr && trim($hv2Instr['instruction'] ?? '') !== '')
                <div style="margin-bottom: 1.25rem; font-style: italic; color: #555; background: #fafafa; border-left: 3px solid #d8232a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6;">
                    {{ $hv2Instr['instruction'] }}
                </div>
            @endif

            @foreach ($hv2Tasks as $task)
                @php
                    [$stemText, $mcOptions] = $splitMc($task['instruction'] ?? '');
                    $currentVal = $answers[$task['id']] ?? '';
                    $detail = $result ? collect($result['details'])->firstWhere('id', $task['id']) : null;
                    $expectedAnswer = $detail['expected'] ?? '';
                @endphp
                <div style="margin-bottom: 1.5rem;">
                    <div style="display: flex; gap: 0.75rem; align-items: flex-start; margin-bottom: 0.5rem;">
                        <span style="font-size: 1.05rem; font-weight: 900; color: #1e293b; line-height: 1.4; flex-shrink: 0;">{{ $qNum($task) }}</span>
                        <span style="font-size: 0.95rem; line-height: 1.45; color: #1a1a1a;">{{ $stemText }}</span>
                    </div>
                    @if (count($mcOptions) > 0)
                        <div style="padding-left: 2rem;">
                            @foreach ($mcOptions as $key => $text)
                                @php $isSelected = $currentVal === $key; @endphp
                                <div class="hv-mc-option-row @if($isSelected) selected @endif"
                                     @if(!$result) wire:click="$set('answers.{{ $task['id'] }}', '{{ $isSelected ? '' : $key }}')" @endif>
                                    <span class="hv-mc-box @if($result) hv-disabled @endif"
                                          style="
                                              @if($result && $isSelected && $detail && $detail['correct']) border-color: #16a34a; background-color: #f0fdf4; color: #14532d; @endif
                                              @if($result && $isSelected && $detail && !$detail['correct']) border-color: #dc2626; background-color: #fdf2f2; color: #b91c1c; @endif
                                              @if($result && !$isSelected && $expectedAnswer === $key) border-color: #16a34a; background-color: #f0fdf4; color: #14532d; @endif
                                          ">{{ $key }}</span>
                                    <span class="hv-mc-text"
                                          style="
                                              @if($result && $isSelected && $detail && $detail['correct']) color: #15803d; @endif
                                              @if($result && $isSelected && $detail && !$detail['correct']) color: #b91c1c; text-decoration: line-through; @endif
                                              @if($result && !$isSelected && $expectedAnswer === $key) color: #15803d; font-weight: 700; @endif
                                          ">{{ $text }}</span>
                                </div>
                            @endforeach
                            @if ($result && $detail && !$detail['correct'] && $expectedAnswer !== '')
                                <div style="font-size: 0.85rem; color: #15803d; font-weight: 700; padding-left: 0.25rem;">
                                    Richtige Antwort: {{ strtoupper($expectedAnswer) }}
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- ==================== TEIL 3 (Vervollständigen) ==================== --}}
    @if ($hv3Instr || count($hv3Tasks) > 0)
        @php [$hv3Head, $hv3Sub] = $teilTitle($hv3Instr); @endphp
        <div style="border-top: 1px solid #e5e7eb; padding-top: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $hv3Head }}</h3>
                @if ($hv3Sub)
                    <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">{{ $hv3Sub }}</span>
                @endif
            </div>
            @if ($hv3Instr && trim($hv3Instr['instruction'] ?? '') !== '')
                <div style="margin-bottom: 1.25rem; font-style: italic; color: #555; background: #fafafa; border-left: 3px solid #d8232a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6;">
                    {{ $hv3Instr['instruction'] }}
                </div>
            @endif

            @foreach ($hv3Tasks as $task)
                @php
                    $current = $answers[$task['id']] ?? '';
                    $detail = $result ? collect($result['details'])->firstWhere('id', $task['id']) : null;
                    $type = $task['type'] ?? '';
                @endphp
                <div class="hv-answer-row">
                    <span class="hv-qnum">{{ $qNum($task) }}</span>
                    @if (in_array($type, ['multiple_choice', 'true_false'], true))
                        @php
                            [$stemText, $mcOptions] = $splitMc($task['instruction'] ?? '');
                            $expectedAnswer = $detail['expected'] ?? '';
                        @endphp
                        <div style="display: flex; flex-direction: column; gap: 0.5rem; width: 100%;">
                            <div class="hv-prompt">{{ $stemText }}</div>
                            <div style="padding-left: 0.25rem; display: flex; flex-direction: column; gap: 0.4rem;">
                                @foreach ($mcOptions as $key => $text)
                                    @php $isSelected = $current === $key; @endphp
                                    <div class="hv-mc-option-row @if($isSelected) selected @endif"
                                         @if(!$result) wire:click="$set('answers.{{ $task['id'] }}', '{{ $isSelected ? '' : $key }}')" @endif>
                                        <span class="hv-mc-box @if($result) hv-disabled @endif"
                                              style="
                                                  @if($result && $isSelected && $detail && $detail['correct']) border-color: #16a34a; background-color: #f0fdf4; color: #14532d; @endif
                                                  @if($result && $isSelected && $detail && !$detail['correct']) border-color: #dc2626; background-color: #fdf2f2; color: #b91c1c; @endif
                                                  @if($result && !$isSelected && $expectedAnswer === $key) border-color: #16a34a; background-color: #f0fdf4; color: #14532d; @endif
                                              ">{{ $key }}</span>
                                        <span class="hv-mc-text"
                                              style="
                                                  @if($result && $isSelected && $detail && $detail['correct']) color: #15803d; @endif
                                                  @if($result && $isSelected && $detail && !$detail['correct']) color: #b91c1c; text-decoration: line-through; @endif
                                                  @if($result && !$isSelected && $expectedAnswer === $key) color: #15803d; font-weight: 700; @endif
                                              ">{{ $text }}</span>
                                    </div>
                                @endforeach
                                @if ($result && $detail && !$detail['correct'] && $expectedAnswer !== '')
                                    <div style="font-size: 0.85rem; color: #15803d; font-weight: 700; padding-left: 0.25rem;">
                                        Richtige Antwort: {{ strtoupper($expectedAnswer) }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @else
                        @php
                            $fbCls = '';
                            if ($result && $current !== '') {
                                $fbCls = $detail && $detail['correct'] ? 'correct' : 'wrong';
                            }
                        @endphp
                        <span class="hv-prompt">{{ $task['instruction'] ?? '' }}</span>
                        <input type="text" wire:model.live="answers.{{ $task['id'] }}" class="fill-blank-input hv-input @if($fbCls) {{ $fbCls }} @endif" placeholder="Antwort" @disabled($result)>
                    @endif
                    @if ($result && $detail)
                        <span class="hv-feedback" style="color: {{ $detail['correct'] ? '#16a34a' : '#dc2626' }};">
                            {{ $detail['correct'] ? '✓' : '✗ (Lösung: ' . $detail['expected'] . ')' }}
                        </span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    {{-- ==================== TEIL 4 (dynamisch) ==================== --}}
    @if ($hv4Instr || count($hv4Tasks) > 0)
        @php [$hv4Head, $hv4Sub] = $teilTitle($hv4Instr); @endphp
        <div style="border-top: 1px solid #e5e7eb; padding-top: 1.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: #1a1a1a; margin: 0;">{{ $hv4Head }}</h3>
                @if ($hv4Sub)
                    <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">{{ $hv4Sub }}</span>
                @endif
            </div>
            @if ($hv4Instr && trim($hv4Instr['instruction'] ?? '') !== '')
                <div style="margin-bottom: 1.25rem; font-style: italic; color: #555; background: #fafafa; border-left: 3px solid #d8232a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6;">
                    {{ $hv4Instr['instruction'] }}
                </div>
            @endif

            @foreach ($hv4Tasks as $task)
                @php
                    $type = $task['type'] ?? '';
                @endphp
                @if (in_array($type, ['multiple_choice', 'true_false'], true))
                    @php
                        [$stemText, $mcOptions] = $splitMc($task['instruction'] ?? '');
                        $currentVal = $answers[$task['id']] ?? '';
                        $detail = $result ? collect($result['details'])->firstWhere('id', $task['id']) : null;
                        $expectedAnswer = $detail['expected'] ?? '';
                    @endphp
                    <div style="margin-bottom: 1.5rem;">
                        <div style="display: flex; gap: 0.75rem; align-items: flex-start; margin-bottom: 0.5rem;">
                            <span style="font-size: 1.05rem; font-weight: 900; color: #1e293b; line-height: 1.4; flex-shrink: 0;">{{ $qNum($task) }}</span>
                            <span style="font-size: 0.95rem; line-height: 1.45; color: #1a1a1a;">{{ $stemText }}</span>
                        </div>
                        @if (count($mcOptions) > 0)
                            <div style="padding-left: 2rem;">
                                @foreach ($mcOptions as $key => $text)
                                    @php $isSelected = $currentVal === $key; @endphp
                                    <div class="hv-mc-option-row @if($isSelected) selected @endif"
                                         @if(!$result) wire:click="$set('answers.{{ $task['id'] }}', '{{ $isSelected ? '' : $key }}')" @endif>
                                        <span class="hv-mc-box @if($result) hv-disabled @endif"
                                              style="
                                                  @if($result && $isSelected && $detail && $detail['correct']) border-color: #16a34a; background-color: #f0fdf4; color: #14532d; @endif
                                                  @if($result && $isSelected && $detail && !$detail['correct']) border-color: #dc2626; background-color: #fdf2f2; color: #b91c1c; @endif
                                                  @if($result && !$isSelected && $expectedAnswer === $key) border-color: #16a34a; background-color: #f0fdf4; color: #14532d; @endif
                                              ">{{ $key }}</span>
                                        <span class="hv-mc-text"
                                              style="
                                                  @if($result && $isSelected && $detail && $detail['correct']) color: #15803d; @endif
                                                  @if($result && $isSelected && $detail && !$detail['correct']) color: #b91c1c; text-decoration: line-through; @endif
                                                  @if($result && !$isSelected && $expectedAnswer === $key) color: #15803d; font-weight: 700; @endif
                                              ">{{ $text }}</span>
                                    </div>
                                @endforeach
                                @if ($result && $detail && !$detail['correct'] && $expectedAnswer !== '')
                                    <div style="font-size: 0.85rem; color: #15803d; font-weight: 700; padding-left: 0.25rem;">
                                        Richtige Antwort: {{ strtoupper($expectedAnswer) }}
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                @else
                    @php
                        $current = $answers[$task['id']] ?? '';
                        $detail = $result ? collect($result['details'])->firstWhere('id', $task['id']) : null;
                    @endphp
                    <div class="hv-answer-row">
                        <span class="hv-qnum">{{ $qNum($task) }}</span>
                        <span class="hv-prompt">{{ $task['instruction'] ?? '' }}</span>
                        <input type="text" wire:model.live="answers.{{ $task['id'] }}" class="fill-blank-input hv-input" placeholder="Antwort" @disabled($result)>
                        @if ($result && $detail)
                            <span class="hv-feedback" style="color: {{ $detail['correct'] ? '#16a34a' : '#dc2626' }};">
                                {{ $detail['correct'] ? '✓' : '✗ (Lösung: ' . $detail['expected'] . ')' }}
                            </span>
                        @endif
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</section>
