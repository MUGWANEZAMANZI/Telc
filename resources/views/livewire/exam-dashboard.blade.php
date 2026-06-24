<div class="telc-shell" x-data="{ saved: false }" @exam-saved.window="saved = true; setTimeout(() => saved = false, 1800)">
    <div class="telc-topbar">
        <div>
            <p class="telc-eyebrow">TELC Germany</p>
            <h1>TELC Exam Builder</h1>
        </div>

        <div class="telc-actions">
            <select wire:change="loadTemplate($event.target.value)" class="telc-select">
                @foreach ($templates as $record)
                    <option value="{{ $record->id }}" @selected($selectedTemplateId === $record->id)>
                        {{ $record->level }} - {{ $record->title }}
                    </option>
                @endforeach
            </select>

            <div class="telc-segment">
                <button type="button" wire:click="switchMode('builder')" @class(['active' => $mode === 'builder'])>Builder</button>
                <button type="button" wire:click="switchMode('student')" @class(['active' => $mode === 'student'])>Student</button>
                <button type="button" wire:click="switchMode('pricing')" @class(['active' => $mode === 'pricing'])>Pricing</button>
            </div>

            <button type="button" wire:click="save" class="telc-button primary">Save</button>
        </div>
    </div>

    <div class="telc-status" x-show="saved" x-cloak>Template saved.</div>

    <section class="admin-metrics">
        <article>
            <span>Registered Students</span>
            <strong>{{ $metrics['students'] }}</strong>
        </article>
        <article>
            <span>Logged In Today</span>
            <strong>{{ $metrics['daily_logged_in'] }}</strong>
        </article>
        <article>
            <span>Total Attempts</span>
            <strong>{{ $metrics['attempts'] }}</strong>
        </article>
        <article>
            <span>Average Score</span>
            <strong>{{ $metrics['average_score'] }}%</strong>
        </article>
    </section>

    @if ($mode === 'builder')
        <div class="telc-admin-grid">
            <aside class="telc-panel">
                <h2>Exam Source</h2>

                <label>Title</label>
                <input type="text" wire:model.live="template.title" class="telc-input">

                <label>Level</label>
                <input type="text" wire:model.live="template.level" class="telc-input">

                <label>Source PDF</label>
                <input type="text" wire:model.live="template.source_pdf" class="telc-input">

                <div class="telc-row">
                    <button type="button" wire:click="togglePublished" class="telc-button">
                        {{ $template['is_published'] ?? false ? 'Unpublish' : 'Publish' }}
                    </button>
                    <span @class(['telc-pill', 'green' => ($template['is_published'] ?? false)])>
                        {{ $template['is_published'] ?? false ? 'Published' : 'Draft' }}
                    </span>
                </div>

                <h2>Presets</h2>
                <div class="telc-preset-list">
                    @foreach ($presets as $level)
                        <button type="button" wire:click="createFromPreset('{{ $level }}')" class="telc-button">New {{ $level }}</button>
                    @endforeach
                </div>

                <h2>Theme From Booklet</h2>
                <label>Accent</label>
                <input type="color" wire:model.live="template.theme.accent" class="telc-color">
                <label>Ink</label>
                <input type="color" wire:model.live="template.theme.ink" class="telc-color">
                <label>Paper</label>
                <input type="color" wire:model.live="template.theme.paper" class="telc-color">

                <h2>Exact Timings</h2>
                @foreach (($template['timings'] ?? []) as $index => $timing)
                    <div class="telc-timing-row">
                        <input type="text" wire:model.live="template.timings.{{ $index }}.label" class="telc-input">
                        <input type="number" wire:model.live="template.timings.{{ $index }}.minutes" class="telc-input small">
                    </div>
                @endforeach
            </aside>

            <main class="telc-panel">
                <div class="telc-builder-head">
                    <div>
                        <h2>Visual Template Builder</h2>
                        <p>Digital test begins at the PDF Testformat structure; answer sheets and administrative pages are not included.</p>
                    </div>
                    <button type="button" wire:click="addSection" class="telc-button primary">Add Part</button>
                </div>

                <div class="telc-sections">
                    @foreach (($template['sections'] ?? []) as $sectionIndex => $section)
                        <section class="telc-section-editor">
                            <div class="telc-section-title">
                                <button type="button" wire:click="moveSection({{ $sectionIndex }}, {{ max($sectionIndex - 1, 0) }})" class="telc-icon-button" title="Move up">↑</button>
                                <button type="button" wire:click="moveSection({{ $sectionIndex }}, {{ min($sectionIndex + 1, count($template['sections']) - 1) }})" class="telc-icon-button" title="Move down">↓</button>
                                <input type="text" wire:model.live="template.sections.{{ $sectionIndex }}.title" class="telc-input title">
                                <input type="number" wire:model.live="template.sections.{{ $sectionIndex }}.minutes" class="telc-input minutes">
                                <button type="button" wire:click="removeSection({{ $sectionIndex }})" class="telc-icon-button danger" title="Remove">×</button>
                            </div>

                            <div class="telc-element-palette">
                                @foreach ($elementTypes as $type => $label)
                                    <button type="button" wire:click="addElement({{ $sectionIndex }}, '{{ $type }}')" class="telc-chip">{{ $label }}</button>
                                @endforeach
                            </div>

                            <div class="telc-element-list">
                                @foreach (($section['elements'] ?? []) as $elementIndex => $element)
                                    <article
                                        class="telc-element-editor"
                                        draggable="true"
                                        x-on:dragstart="$event.dataTransfer.setData('text/plain', '{{ $elementIndex }}')"
                                        x-on:dragover.prevent
                                        x-on:drop.prevent="$wire.moveElement({{ $sectionIndex }}, parseInt($event.dataTransfer.getData('text/plain')), {{ $elementIndex }})"
                                    >
                                        <div class="telc-element-toolbar">
                                            <span class="telc-drag">⋮⋮</span>
                                            <strong>{{ $elementTypes[$element['type']] ?? $element['type'] }}</strong>
                                            <button type="button" wire:click="duplicateElement({{ $sectionIndex }}, {{ $elementIndex }})" class="telc-link-button">Duplicate</button>
                                            <button type="button" wire:click="removeElement({{ $sectionIndex }}, {{ $elementIndex }})" class="telc-link-button danger">Remove</button>
                                        </div>

                                        <div class="telc-element-fields">
                                            <label>Label</label>
                                            <input type="text" wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.label" class="telc-input">

                                            @if ($element['type'] === 'audio')
                                                <label>Audio file</label>
                                                <div class="telc-audio-upload">
                                                    <input type="file" wire:model.live="audioUpload" accept="audio/*">
                                                    <button type="button" wire:click="uploadAudio({{ $sectionIndex }}, {{ $elementIndex }})" class="telc-button primary">Upload</button>
                                                    @error('audioUpload') <span class="telc-error">{{ $message }}</span> @enderror
                                                    @if (! empty($element['content']))
                                                        <audio controls src="{{ $element['content'] }}" class="telc-audio-preview"></audio>
                                                    @endif
                                                </div>
                                            @else
                                                <label>Content, image path, or audio URL</label>
                                                <textarea wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.content" class="telc-textarea"></textarea>
                                            @endif

                                            @if (in_array($element['type'], ['multiple_choice', 'matching', 'fill_blank', 'true_false'], true))
                                                <label>Options</label>
                                                <input type="text" wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.options" class="telc-input">

                                                <label>Correct Answer</label>
                                                <input type="text" wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.answer" class="telc-input">

                                                <label>Points</label>
                                                <input type="number" wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.points" class="telc-input small">

                                                <label>Explanation (leave empty for AI auto-generate)</label>
                                                <textarea wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.explanation" class="telc-textarea" style="min-height:4rem" placeholder="Optional: Write a custom explanation for this answer."></textarea>
                                            @endif

                                            @if ($element['type'] === 'inline_gap')
                                                <label>Paragraph (use --0--, --1-- etc. for gaps)</label>
                                                <textarea wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.content" class="telc-textarea" style="min-height:8rem" placeholder="Liebe Daniela, ... denn --0-- wollte ich dir ..."></textarea>

                                                <label>Gap definitions (JSON)</label>
                                                <textarea wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.options" class="telc-textarea" style="min-height:6rem;font-family:monospace;font-size:.82rem" placeholder='[{"options":"a|außerdem,b|eigentlich,c|überhaupt","answer":"b"}]'></textarea>
                                                <p style="color:#667085;font-size:.78rem;margin:0">JSON array. Each gap: <code>{"options":"a|label,b|label,c|label","answer":"a"}</code></p>

                                                <label>Points per gap</label>
                                                <input type="number" wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.points" class="telc-input small">
                                            @endif

                                            @if ($element['type'] === 'interactive_text')
                                                <label>Text with [[gap:1]] placeholders</label>
                                                <textarea wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.content" class="telc-textarea" style="min-height:8rem" placeholder="Heute ist ein schöner [[gap:1]]. Wir gehen in den [[gap:2]]."></textarea>

                                                <label>Points</label>
                                                <input type="number" wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.points" class="telc-input small">

                                                <div class="telc-gap-manager">
                                                    <label style="display:flex;align-items:center;justify-content:space-between">
                                                        <span>Gap definitions</span>
                                                        <button type="button" wire:click="syncGapIds({{ $sectionIndex }}, {{ $elementIndex }})" class="telc-link-button" style="font-size:.8rem">Sync gaps from text</button>
                                                    </label>

                                                    @php $gaps = $element['gaps'] ?? []; @endphp
                                                    @foreach ($gaps as $gId => $gDef)
                                                        <div class="telc-gap-editor" wire:key="gap-{{ $sectionIndex }}-{{ $elementIndex }}-{{ $gId }}">
                                                            <div class="telc-gap-header">
                                                                <strong>Gap {{ $gId }}</strong>
                                                            </div>

                                                            <label>Type</label>
                                                            <select wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.gaps.{{ $gId }}.type" class="telc-select">
                                                                <option value="dropdown">Dropdown</option>
                                                                <option value="text">Text input</option>
                                                            </select>

                                                            <label>Options (comma-separated, e.g. a:Tag,b:Baum,c:Hund)</label>
                                                            <input type="text"
                                                                wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.gaps.{{ $gId }}.options"
                                                                class="telc-input"
                                                                placeholder="a:Tag,b:Baum,c:Hund">

                                                            <label>Correct answer</label>
                                                            <input type="text" wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.gaps.{{ $gId }}.answer" class="telc-input" placeholder="a">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>
            </main>
        </div>
    @elseif ($mode === 'pricing')
        <livewire:admin-pricing />

    @else
        @php
            $section = ($template['sections'] ?? [])[$activeSection] ?? null;
            $theme = $template['theme'] ?? [];
            $minutes = intdiv($remainingSeconds, 60);
            $seconds = $remainingSeconds % 60;
        @endphp

        <div class="telc-student-grid">
            <aside class="telc-panel">
                <h2>Exam Parts</h2>
                @foreach (($template['sections'] ?? []) as $index => $part)
                    <button type="button" wire:click="startSection({{ $index }})" @class(['telc-part-button', 'active' => $activeSection === $index])>
                        <span>{{ $part['title'] }}</span>
                        <small>{{ $part['minutes'] }} min</small>
                    </button>
                @endforeach

                <div class="telc-timer" wire:poll.1s="tick">
                    {{ sprintf('%02d:%02d', $minutes, $seconds) }}
                </div>

                <button type="button" wire:click="submit" class="telc-button primary wide">Submit & Mark</button>

                @if ($result)
                    <div class="telc-result">
                        <strong>{{ $result['earned'] }} / {{ $result['possible'] }}</strong>
                        <span>{{ $result['percentage'] }}%</span>
                    </div>
                @endif
            </aside>

            <main class="telc-exam-frame">
                @if ($section)
                    <div class="telc-a4" style="--telc-accent: {{ $theme['accent'] ?? '#d8232a' }}; --telc-ink: {{ $theme['ink'] ?? '#111827' }}; --telc-paper: {{ $theme['paper'] ?? '#ffffff' }}; font-family: {{ $theme['font'] ?? 'Instrument Sans' }}, sans-serif;">
                        <header class="telc-paper-header">
                            <div>
                                <p>Übungstest</p>
                                <h2>{{ $template['title'] ?? 'TELC Exam' }}</h2>
                            </div>
                            <span>{{ $template['level'] ?? '' }}</span>
                        </header>

                        <div class="telc-paper-scroll">
                            <h3>{{ $section['title'] }}</h3>

                            @foreach (($section['elements'] ?? []) as $element)
                                <div class="telc-paper-element">
                                    @if ($element['type'] === 'text')
                                        <h4>{{ $element['label'] }}</h4>
                                        <p>{!! nl2br(e($element['content'])) !!}</p>
                                    @elseif ($element['type'] === 'image')
                                        <figure class="telc-image-placeholder">
                                            @if (! empty($element['content']))
                                                <img src="{{ $element['content'] }}" alt="{{ $element['label'] }}">
                                            @else
                                                <span>{{ $element['label'] }}</span>
                                            @endif
                                        </figure>
                                    @elseif ($element['type'] === 'audio')
                                        <label>{{ $element['label'] }}</label>
                                        <audio controls src="{{ $element['content'] }}"></audio>
                                    @elseif ($element['type'] === 'writing')
                                        <label>{{ $element['label'] }}</label>
                                        <textarea wire:model.live="studentAnswers.{{ $element['id'] }}" class="telc-answer-textarea"></textarea>
                                    @elseif ($element['type'] === 'inline_gap')
                                        <div class="admin-gap-preview">
                                            <label>{{ $element['label'] }}</label>
                                            <div style="line-height:2;font-size:.92rem">
                                                @php
                                                    $gaps = json_decode($element['options'] ?? '[]', true) ?: [];
                                                    $parts = preg_split('/--(\d+)--/', $element['content'] ?? '', -1, PREG_SPLIT_DELIM_CAPTURE);
                                                @endphp
                                                @foreach ($parts as $i => $part)
                                                    @if ($i % 2 === 0)
                                                        <span>{{ $part }}</span>
                                                    @else
                                                        @php $gapIdx = (int) $part; @endphp
                                                        <span style="display:inline-flex;align-items:center;border:1.7px solid #111827;border-radius:4px;padding:.1rem .35rem;font-weight:900;font-size:.82rem;background:#f7f8fa;margin:0 .1rem">--{{ $gapIdx }}--</span>
                                                    @endif
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        @php $options = array_filter(array_map('trim', explode(',', $element['options'] ?? ''))); @endphp
                                        <label>{{ $element['label'] }}</label>

                                        @if (in_array($element['type'], ['multiple_choice', 'true_false', 'matching'], true) && $options)
                                            <select wire:model.live="studentAnswers.{{ $element['id'] }}" class="telc-answer">
                                                <option value="">Antwort wählen</option>
                                                @foreach ($options as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <input type="text" wire:model.live="studentAnswers.{{ $element['id'] }}" class="telc-answer">
                                        @endif

                                        @if ($result)
                                            @php $detail = collect($result['details'])->firstWhere('id', $element['id']); @endphp
                                            @if ($detail)
                                                <span @class(['telc-mark', 'ok' => $detail['correct']])>
                                                    {{ $detail['correct'] ? 'Correct' : 'Expected: '.$detail['expected'] }}
                                                </span>
                                            @endif
                                        @endif
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </main>
        </div>
    @endif
</div>
