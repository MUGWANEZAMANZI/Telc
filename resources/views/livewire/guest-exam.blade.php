<div class="guest-exam-shell">
    @if (empty($template))
        <section class="exam-store">
            <div class="exam-store-header">
                <p>Free Exam</p>
                <h1>Try an exam before subscribing</h1>
                <span>Select an exam below to begin your free trial.</span>
            </div>

            <div class="exam-card-grid">
                @forelse ($templates as $record)
                    <article class="exam-buy-card">
                        <div>
                            <span class="exam-level">{{ $record->level }}</span>
                            <h2>{{ $record->title }}</h2>
                            <p>Original A4 paper simulation as a real Telc exam.</p>
                        </div>
                        @if (! empty($record->timings))
                            <dl>
                                @foreach (array_slice($record->timings, 0, 4) as $timing)
                                    <div><dt>{{ $timing['label'] }}</dt><dd>{{ $timing['minutes'] }} min</dd></div>
                                @endforeach
                            </dl>
                        @endif
                        <button type="button" wire:click="loadTemplate({{ $record->id }})" class="telc-button primary wide">
                            Start Free Exam
                        </button>
                    </article>
                @empty
                    <div class="telc-panel">
                        <h2>No exam is published yet.</h2>
                        <p>An administrator should publish a template first.</p>
                    </div>
                @endforelse
            </div>
        </section>
    @else
        @php
            $section = ($template['sections'] ?? [])[$activeSection] ?? null;
            $theme = $template['theme'] ?? [];
            $minutes = intdiv($remainingSeconds, 60);
            $seconds = $remainingSeconds % 60;
        @endphp
        <div class="paper-exam-shell">
            <aside class="paper-exam-controls">
                <div class="paper-exam-brand">
                    <span>{{ $template['level'] ?? 'C1' }}</span>
                    <div>
                        <strong>{{ $template['title'] ?? 'telc Deutsch C1' }}</strong>
                        <small>Free Exam</small>
                    </div>
                </div>

                <div class="student-sections">
                    @foreach (($template['sections'] ?? []) as $index => $part)
                        <button type="button" wire:click="startSection({{ $index }})" @class(['student-section-button', 'active' => $activeSection === $index])>
                            <span>{{ $part['title'] }}</span>
                            <small>{{ $part['minutes'] }} min</small>
                        </button>
                    @endforeach
                </div>

                <div class="student-clock" wire:poll.1s="tick">
                    {{ sprintf('%02d:%02d', $minutes, $seconds) }}
                </div>

                <button type="button" wire:click="submit" class="telc-button primary wide">
                    Submit & Mark
                </button>

                @if ($result)
                    <div class="student-score">
                        <strong>{{ $result['earned'] }} / {{ $result['possible'] }}</strong>
                        <span>{{ $result['percentage'] }}%</span>
                    </div>
                @endif
            </aside>

            <main class="paper-workbench" style="--telc-accent: {{ $theme['accent'] ?? '#8f8f8f' }}; --telc-ink: {{ $theme['ink'] ?? '#202020' }};">
                @if ($section)
                    <div class="telc-paper booklet-paper">
                        <div class="telc-paper-header">
                            <div>
                                <p>{{ $template['title'] ?? 'telc Deutsch C1' }}</p>
                                <h2>{{ $section['title'] }}</h2>
                            </div>
                            <span>{{ $template['level'] ?? '' }}</span>
                        </div>
                        <div class="telc-paper-body">
                            @foreach ($section['elements'] as $element)
                                @if (($element['type'] ?? '') === 'text')
                                    <h4>{{ $element['label'] }}</h4>
                                    <p>{!! nl2br(e($element['content'])) !!}</p>
                                @elseif (($element['type'] ?? '') === 'inline_gap')
                                    <h3>{{ $element['label'] }}</h3>
                                    <div class="inline-gap-text">
                                        @php
                                            $gaps = $this->gapsFor($element);
                                            $parts = preg_split('/--(\d+)--/', $element['content'] ?? '', -1, PREG_SPLIT_DELIM_CAPTURE);
                                        @endphp
                                        @foreach ($parts as $i => $part)
                                            @if ($i % 2 === 0)
                                                <span>{!! nl2br(e($part)) !!}</span>
                                            @else
                                                @php $gapIdx = (int) $part; $gapDef = $gaps[$gapIdx] ?? null; @endphp
                                                @if ($gapDef)
                                                    @php
                                                        $optList = array_filter(array_map('trim', explode(',', $gapDef['options'] ?? '')));
                                                        $current = $answers[$element['id']][$gapIdx] ?? '';
                                                    @endphp
                                                    <span class="inline-gap-pill @if($current) filled @endif">
                                                        <select wire:model.live="answers.{{ $element['id'] }}.{{ $gapIdx }}">
                                                            <option value="">--{{ $gapIdx }}--</option>
                                                            @foreach ($optList as $opt)
                                                                @php $optParts = explode('|', $opt, 2); $optKey = trim($optParts[0]); $optLabel = trim($optParts[1] ?? $optKey); @endphp
                                                                <option value="{{ $optKey }}">{{ $optLabel }}</option>
                                                            @endforeach
                                                        </select>
                                                    </span>
                                                @endif
                                            @endif
                                        @endforeach
                                    </div>
                                @elseif (in_array($element['type'] ?? '', ['multiple_choice', 'true_false', 'matching'], true))
                                    @php $options = $this->optionsFor($element); @endphp
                                    <label>{{ $element['label'] }}</label>
                                    <select wire:model.live="answers.{{ $element['id'] }}" class="telc-answer">
                                        <option value="">Antwort wählen</option>
                                        @foreach ($options as $option)
                                            <option value="{{ $option }}">{{ $option }}</option>
                                        @endforeach
                                    </select>
                                @elseif (($element['type'] ?? '') === 'writing')
                                    <label>{{ $element['label'] }}</label>
                                    <textarea wire:model.live="answers.{{ $element['id'] }}" class="telc-answer-textarea"></textarea>
                                @endif
                            @endforeach
                        </div>
                    </div>
                @endif
            </main>
        </div>
    @endif
</div>
