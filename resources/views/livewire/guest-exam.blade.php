<div class="guest-exam-shell">
    @if ($hasReachedLimit && !$result)
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
            <div class="bg-zinc-900 border border-zinc-800/80 p-8 rounded-2xl max-w-lg shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 via-yellow-500 to-red-600"></div>
                
                <svg class="w-16 h-16 text-yellow-500 mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                
                <h1 class="text-2xl font-bold text-zinc-100 font-serif mb-4">Tägliches Limit erreicht</h1>
                <p class="text-zinc-400 text-sm leading-relaxed mb-6">
                    Sie haben heute bereits das Limit von <strong>2 kostenlosen Prüfungen</strong> ausgeschöpft. 
                    Bitte kommen Sie morgen wieder oder registrieren Sie sich, um vollen Zugang zu allen Funktionen zu erhalten.
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-semibold rounded-lg bg-yellow-500 text-neutral-950 hover:bg-yellow-400 transition duration-150 ease-in-out font-mono tracking-wide uppercase">
                        Jetzt Registrieren
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-zinc-700 text-sm font-semibold rounded-lg text-zinc-300 hover:bg-zinc-800 transition duration-150 ease-in-out font-mono tracking-wide uppercase">
                        Anmelden
                    </a>
                </div>
            </div>
        </div>
    @elseif (empty($template))
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
