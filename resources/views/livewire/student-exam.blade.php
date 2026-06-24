@php
    $section = ($template['sections'] ?? [])[$activeSection] ?? null;
    $theme = $template['theme'] ?? [];
    $minutes = intdiv($remainingSeconds, 60);
    $seconds = $remainingSeconds % 60;
@endphp

<div class="student-catalog-shell">
    @if (empty($template))
        <section class="exam-store">
            <div class="exam-store-header">
                <p>TELC Exam Store</p>
                <h1>Enroll before opening the test booklet</h1>
                <span>Select an exam below to begin.</span>
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

                        <button type="button" wire:click="enroll({{ $record->id }})" class="telc-button primary wide">
                            Buy Exam / Enroll
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
        <div class="paper-exam-shell">
            <aside class="paper-exam-controls">
                <div class="paper-exam-brand">
                    <span>{{ $template['level'] ?? 'C1' }}</span>
                    <div>
                        <strong>{{ $template['title'] ?? 'telc Deutsch C1' }}</strong>
                        <small>Prüfungssimulation</small>
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
                    Antwortbogen abgeben
                </button>

                @if ($result)
                    <div class="student-score">
                        <strong>{{ $result['earned'] }} / {{ $result['possible'] }}</strong>
                        <span>{{ $result['percentage'] }}%</span>
                    </div>

                    @php $explanations = collect($result['details'] ?? [])->filter(fn ($d) => !$d['correct'] && !empty($d['explanation'])); @endphp
                    @if ($explanations->isNotEmpty())
                        <div class="student-explanations">
                            <h2>Erklärungen</h2>
                            @foreach ($explanations as $detail)
                                <div class="explanation-item">
                                    <strong>{{ $detail['label'] }}</strong>
                                    <p>{{ $detail['explanation'] }}</p>
                                </div>
                            @endforeach
                        </div>
                    @endif
                @endif

                @if ($attempts->isNotEmpty())
                    <h2>Letzte Noten</h2>
                    @foreach ($attempts as $attempt)
                        <div class="student-history-row">
                            <span>{{ $attempt->created_at->format('M d') }}</span>
                            <strong>{{ $attempt->result['percentage'] ?? 0 }}%</strong>
                        </div>
                    @endforeach
                @endif
            </aside>

            <main class="paper-workbench" style="--telc-accent: {{ $theme['accent'] ?? '#8f8f8f' }}; --telc-ink: {{ $theme['ink'] ?? '#202020' }};">
                @if ($section)
                    @php
                        $hasInlineGap = collect($section['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'inline_gap');
                        $hasInteractiveText = collect($section['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'interactive_text');
                        $hasSpeaking = collect($section['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'speaking');
                        $hasAudio = collect($section['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'audio');
                    @endphp

                    {{-- === INTERACTIVE TEXT VIEW (fillable paper with [[gap:X]]) === --}}
                    @if ($hasInteractiveText)
                        <div class="exam-layout">
                            <div class="exam-paper-column">
                                <section class="interactive-paper">
                                    @foreach ($section['elements'] as $element)
                                        @if (($element['type'] ?? '') === 'interactive_text')
                                            @php
                                                $parser = app(\App\Services\GapParser::class);
                                                $segments = $parser->parse($element['content'] ?? '', $element['gaps'] ?? []);
                                            @endphp
                                            <div class="interactive-text-section">
                                                <h3>{{ $element['label'] }}</h3>
                                                <div class="interactive-text-body">
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
                                                            <span class="inline-gap-wrapper">
                                                                @if ($isDropdown && count($optList) > 0)
                                                                    <select wire:model.live="answers.{{ $wireKey }}" class="interactive-select">
                                                                        <option value="">{{ $current ?: '___' }}</option>
                                                                        @foreach ($optList as $opt)
                                                                            <option value="{{ $opt['value'] }}" @selected($current === $opt['value'])>{{ $opt['label'] }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                @else
                                                                    <input type="text"
                                                                        wire:model.live="answers.{{ $wireKey }}"
                                                                        class="interactive-input"
                                                                        placeholder="___"
                                                                        value="{{ $current }}">
                                                                @endif
                                                            </span>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </section>
                            </div>

                            <aside class="exam-answer-column">
                                <x-answer-sheet
                                    :sections="[$section]"
                                    :answers="$answers"
                                    :result="$result ?? null"
                                    :section-title="$section['title'] ?? ''"
                                    :show-audio="$hasAudio"
                                    :audio-elements="collect($section['elements'] ?? [])->where('type', 'audio')->values()->all()"
                                />
                            </aside>
                        </div>

                    {{-- === INLINE GAP VIEW (Sprachbausteine) === --}}
                    @elseif ($hasInlineGap)
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
                                    @if (($element['type'] ?? '') === 'inline_gap')
                                        @php
                                            $gaps = $this->gapsFor($element);
                                            $parts = preg_split('/--(\d+)--/', $element['content'] ?? '', -1, PREG_SPLIT_DELIM_CAPTURE);
                                        @endphp
                                        <div class="inline-gap-section">
                                            <h3>{{ $element['label'] }}</h3>
                                            <div class="inline-gap-text">
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
                                                            <span class="inline-gap-pill @if($current) filled @endif" tabindex="0">
                                                                <button type="button" class="gap-trigger">
                                                                    @if ($current)
                                                                        <span class="gap-value">{{ $current }}</span>
                                                                    @else
                                                                        <span class="gap-placeholder">--{{ $gapIdx }}--</span>
                                                                    @endif
                                                                    <svg class="gap-chevron" viewBox="0 0 20 20" fill="currentColor" width="14" height="14"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd"/></svg>
                                                                </button>
                                                                <div class="gap-dropdown">
                                                                    @foreach ($optList as $opt)
                                                                        @php $optParts = explode('|', $opt, 2); $optKey = trim($optParts[0]); $optLabel = trim($optParts[1] ?? $optKey); @endphp
                                                                        <button type="button" class="gap-option @if($current === $optKey) selected @endif" wire:click="setGapAnswer('{{ $element['id'] }}', {{ $gapIdx }}, '{{ $optKey }}')">
                                                                            <span class="gap-option-key">{{ $optKey }}</span>
                                                                            <span class="gap-option-label">{{ $optLabel }}</span>
                                                                        </button>
                                                                    @endforeach
                                                                </div>
                                                            </span>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    {{-- === SPEAKING VIEW === --}}
                    @elseif ($hasSpeaking)
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
                                    @if (($element['type'] ?? '') === 'speaking')
                                        <div class="speaking-task" x-data="{
                                            isRecording: false,
                                            recognition: null,
                                            interimText: '',
                                            initRecognition() {
                                                const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                                                if (!SR) return;
                                                this.recognition = new SR();
                                                this.recognition.lang = 'de-DE';
                                                this.recognition.continuous = true;
                                                this.recognition.interimResults = true;
                                                this.recognition.onresult = (e) => {
                                                    let t = '';
                                                    for (let i = e.resultIndex; i < e.results.length; i++) t += e.results[i][0].transcript;
                                                    this.interimText = t;
                                                };
                                                this.recognition.onerror = () => this.stopRecording();
                                            },
                                            startRecording() {
                                                if (!this.recognition) this.initRecognition();
                                                if (!this.recognition) return;
                                                this.isRecording = true;
                                                this.recognition.start();
                                            },
                                            stopRecording() {
                                                this.isRecording = false;
                                                if (this.recognition) this.recognition.stop();
                                            },
                                            toggleRecording() { this.isRecording ? this.stopRecording() : this.startRecording(); },
                                            sendToAi(id) {
                                                $wire.set('answers.' + id, this.interimText);
                                                $wire.call('analyzeSpeaking', id, this.interimText);
                                            }
                                        }">
                                            <div class="speaking-header">
                                                <span class="speaking-badge">{{ $element['label'] }}</span>
                                                <span class="speaking-points">{{ $element['points'] ?? 25 }} Punkte</span>
                                            </div>
                                            <div class="speaking-prompt">
                                                <p>{{ $element['content'] }}</p>
                                            </div>

                                            <div class="speaking-controls">
                                                <button type="button" @click="toggleRecording"
                                                    class="speaking-record-btn" :class="{ recording: isRecording }">
                                                    <template x-if="!isRecording">
                                                        <span>🎤 Antwort aufnehmen</span>
                                                    </template>
                                                    <template x-if="isRecording">
                                                        <span>⏹ Aufnahme stoppen</span>
                                                    </template>
                                                </button>
                                                <template x-if="isRecording">
                                                    <span class="speaking-recording-indicator">● Aufnahme läuft...</span>
                                                </template>
                                            </div>

                                            <div class="speaking-transcript">
                                                <label>Transkription / Antwort:</label>
                                                <textarea x-model="interimText"
                                                    wire:model.live="answers.{{ $element['id'] }}"
                                                    class="speaking-textarea"
                                                    placeholder="Deine transkribierte Antwort erscheint hier. Du kannst sie bearbeiten."></textarea>
                                            </div>

                                            <div class="speaking-actions">
                                                <button type="button" @click="sendToAi('{{ $element['id'] }}')"
                                                    class="telc-button primary"
                                                    :disabled="$wire.speakingProcessing['{{ $element['id'] }}'] || !interimText.trim()">
                                                    <template x-if="!$wire.speakingProcessing['{{ $element['id'] }}']">
                                                        <span>Mit KI bewerten lassen</span>
                                                    </template>
                                                    <template x-if="$wire.speakingProcessing['{{ $element['id'] }}']">
                                                        <span>Wird analysiert...</span>
                                                    </template>
                                                </button>
                                            </div>

                                            @if (!empty($speakingFeedback[$element['id']] ?? ''))
                                                <div class="speaking-feedback">
                                                    <h4>KI-Feedback</h4>
                                                    <div class="feedback-content">{!! nl2br(e($speakingFeedback[$element['id']])) !!}</div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                    {{-- === PDF BOOKLET VIEW (default: shows paper pages + answer sheet) === --}}
                    @else
                        <div class="exam-layout">
                            <div class="exam-paper-column">
                                <section class="pdf-booklet">
                                    @foreach (($section['paper_pages'] ?? []) as $page)
                                        <figure class="pdf-page">
                                            <img src="{{ $page }}" alt="{{ $section['title'] }} page {{ $loop->iteration }}">
                                        </figure>
                                    @endforeach
                                </section>
                            </div>

                            <aside class="exam-answer-column">
                                <div class="answer-sheet">
                                    <div class="answer-sheet-header">
                                        <div>
                                            <span class="answer-sheet-title">Antwortbogen</span>
                                            <span class="answer-sheet-section">{{ $section['title'] }}</span>
                                        </div>
                                        @if ($hasAudio)
                                            @foreach ($section['elements'] as $element)
                                                @if (($element['type'] ?? '') === 'audio')
                                                    <audio controls src="{{ $element['content'] }}" class="answer-sheet-audio"></audio>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>

                                    @php
                                        $sheetItems = collect($section['elements'] ?? [])
                                            ->filter(fn ($el) => in_array($el['type'] ?? '', ['multiple_choice', 'matching', 'fill_blank', 'true_false'], true))
                                            ->values();
                                    @endphp

                                    @if ($sheetItems->isNotEmpty())
                                        <div class="answer-sheet-grid">
                                            @foreach ($sheetItems as $element)
                                                @php
                                                    $options = $this->optionsFor($element);
                                                    $qNum = preg_replace('/[^0-9]/', '', $element['label']) ?: $loop->iteration;
                                                    $isMatching = ($element['type'] ?? '') === 'matching';
                                                @endphp

                                                <div class="answer-sheet-row" wire:key="answer-{{ $element['id'] }}">
                                                    <span class="question-num">{{ $qNum }}</span>

                                                    @if (($element['type'] ?? '') === 'fill_blank' && $options === [])
                                                        <input type="text" wire:model.live="answers.{{ $element['id'] }}" class="fill-blank-input" placeholder="Antwort">
                                                    @elseif ($isMatching && count($options) > 5)
                                                        <select wire:model.live="answers.{{ $element['id'] }}" class="matching-select">
                                                            <option value="">—</option>
                                                            @foreach ($options as $opt)
                                                                <option value="{{ $opt }}">{{ $opt }}</option>
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <div class="bubble-group">
                                                            @foreach ($options as $option)
                                                                <label @class(['bubble', 'selected' => (($answers[$element['id']] ?? '') === $option)])>
                                                                    <input type="radio" wire:model.live="answers.{{ $element['id'] }}" value="{{ $option }}">
                                                                    <span>{{ $option }}</span>
                                                                </label>
                                                            @endforeach
                                                        </div>
                                                    @endif

                                                    @if ($result)
                                                        @php $detail = collect($result['details'])->firstWhere('id', $element['id']); @endphp
                                                        @if ($detail)
                                                            <em @class(['answer-mark', 'correct' => $detail['correct']])>
                                                                {{ $detail['correct'] ? '✓' : '✗ ' . $detail['expected'] }}
                                                            </em>
                                                        @endif
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    {{-- Writing section --}}
                                    @foreach ($section['elements'] as $element)
                                        @if (($element['type'] ?? '') === 'writing')
                                            <div class="writing-section">
                                                <label class="writing-label">{{ $element['label'] }}</label>
                                                <textarea wire:model.live="answers.{{ $element['id'] }}" class="writing-textarea" placeholder="Schreibe deinen Text hier..."></textarea>
                                            </div>
                                        @endif
                                    @endforeach

                                    {{-- Matching tasks shown inline in the answer sheet --}}
                                    @php
                                        $matchingElements = collect($section['elements'] ?? [])
                                            ->filter(fn ($el) => ($el['type'] ?? '') === 'matching')
                                            ->values();
                                    @endphp
                                    @if ($matchingElements->isNotEmpty())
                                        <div class="matching-inline-section">
                                            <h4>Zuordnungsaufgaben</h4>
                                            @foreach ($matchingElements as $mEl)
                                                @php
                                                    $mOptions = $this->optionsFor($mEl);
                                                    $mNum = preg_replace('/[^0-9]/', '', $mEl['label']) ?: $loop->iteration;
                                                @endphp
                                                <div class="matching-row" wire:key="match-{{ $mEl['id'] }}">
                                                    <span class="question-num">{{ $mNum }}</span>
                                                    <select wire:model.live="answers.{{ $mEl['id'] }}" class="matching-select wide">
                                                        <option value="">Bitte wählen</option>
                                                        @foreach ($mOptions as $opt)
                                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </aside>
                        </div>
                    @endif
                @endif
            </main>
        </div>
    @endif
</div>
