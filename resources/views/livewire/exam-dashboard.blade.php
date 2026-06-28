<div class="telc-shell" x-data="{ saved: false }" @exam-saved.window="saved = true; setTimeout(() => saved = false, 1800)">
    <div class="telc-topbar">
        <div>
            <p class="telc-eyebrow">TELC Germany</p>
            <h1>TELC Exam Builder</h1>
        </div>

        <div class="telc-actions">
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
                <div style="margin-bottom: 1.5rem;">
                    <label>Exam Category Directory</label>
                    <div class="telc-segment" style="margin-top: 0.5rem; width: 100%;">
                        <button type="button" wire:click="changeProvider('telc')" @class(['active' => $providerFilter === 'telc']) style="flex: 1;">TELC Folder</button>
                        <button type="button" wire:click="changeProvider('goethe')" @class(['active' => $providerFilter === 'goethe']) style="flex: 1;">Goethe Folder</button>
                    </div>
                </div>

                <label>Active Category Exam</label>
                <select wire:change="loadTemplate($event.target.value)" class="telc-select" style="margin-bottom: 1.5rem; width: 100%;">
                    <option value="">— Select Exam —</option>
                    @foreach ($templates as $record)
                        <option value="{{ $record->id }}" @selected($selectedTemplateId === $record->id)>
                            {{ $record->level }} - {{ $record->title }}
                        </option>
                    @endforeach
                </select>

                <h2>Exam Source Settings</h2>

                <label>Title</label>
                <input type="text" wire:model.live="template.title" class="telc-input">

                <label>Level</label>
                <input type="text" wire:model.live="template.level" class="telc-input">

                <label>Provider Folder</label>
                <select wire:model.live="template.exam_provider" class="telc-select">
                    <option value="telc">TELC</option>
                    <option value="goethe">Goethe</option>
                </select>

                <label>Source PDF Reference</label>
                <input type="text" wire:model.live="template.source_pdf" class="telc-input">

                <label>Pricing Access</label>
                <div x-data="{ isPaid: {{ isset($template['price']) && $template['price'] > 0 ? 'true' : 'false' }} }" style="margin-bottom: 1rem;">
                    <select class="telc-select" x-on:change="isPaid = ($event.target.value === 'paid'); if (!isPaid) $wire.set('template.price', 0)">
                        <option value="free" :selected="!isPaid">Free / Basic Tier</option>
                        <option value="paid" :selected="isPaid">Paid / Premium Subscription Required</option>
                    </select>
                    <div x-show="isPaid" style="margin-top: 0.5rem;" x-cloak>
                        <label>Exam Price (EUR)</label>
                        <input type="number" step="0.01" wire:model.live="template.price" class="telc-input" placeholder="e.g. 9.99">
                    </div>
                </div>

                <div class="telc-row" style="margin-top: 1rem;">
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

                <h2>Theme Colorway</h2>
                <label>Accent</label>
                <input type="color" wire:model.live="template.theme.accent" class="telc-color">
                <label>Ink</label>
                <input type="color" wire:model.live="template.theme.ink" class="telc-color">
                <label>Paper</label>
                <input type="color" wire:model.live="template.theme.paper" class="telc-color">

                <h2>Timings Configurations</h2>
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
                                                 <div style="grid-column: span 2; display: grid; grid-template-columns: 1fr 2.2fr; gap: 2rem; margin-top: 1rem; width: 100%;">
                                                     <div>
                                                         <label>Paragraph (use --0--, --1-- etc. for gaps)</label>
                                                         <textarea wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.content" class="telc-textarea" style="min-height:16rem; width: 100%;" placeholder="Liebe Daniela, ... denn --0-- wollte ich dir ..."></textarea>

                                                         <label style="margin-top: 1rem; display: block;">Points per gap</label>
                                                         <input type="number" wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.points" class="telc-input small">
                                                     </div>

                                                     <div>
                                                         @php
                                                             $gapData = json_decode($element['options'] ?? '[]', true) ?: [];
                                                             // Match gaps count in the text
                                                             preg_match_all('/--(\d+)--/', $element['content'] ?? '', $matches);
                                                             $gKeys = array_unique($matches[1]);
                                                         @endphp

                                                         <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 1rem; max-height: 25rem; overflow-y: auto; width: 100%;">
                                                             <strong style="display: block; margin-bottom: 0.75rem; font-size: 0.85rem; color: #374151;">Gaps Options Manager</strong>
                                                             
                                                             @foreach ($gKeys as $gKey)
                                                                 @php
                                                                     $gapIdx = (int) $gKey;
                                                                     $cur = $gapData[$gapIdx] ?? ['options' => 'a|Option A,b|Option B,c|Option C', 'answer' => 'a'];
                                                                     // Parse options list
                                                                     $parts = explode(',', $cur['options']);
                                                                     $optList = [];
                                                                     foreach ($parts as $p) {
                                                                         $pair = explode('|', $p, 2);
                                                                         $optList[trim($pair[0])] = $pair[1] ?? '';
                                                                     }
                                                                 @endphp
                                                                 <div style="margin-bottom: 1.25rem; padding-bottom: 1.25rem; border-bottom: 1px solid #e5e7eb; font-size: 0.85rem;" wire:key="inline-gap-{{ $sectionIndex }}-{{ $elementIndex }}-{{ $gapIdx }}">
                                                                     <strong style="color: #6366f1; font-size: 0.9rem; display: block; margin-bottom: 0.5rem;">Gap #{{ $gapIdx }}</strong>
                                                                     
                                                                     <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.5rem;">
                                                                         @foreach (['a', 'b', 'c'] as $letter)
                                                                             <div>
                                                                                 <label style="font-size: 0.7rem; color: #4b5563; display: block; text-transform: uppercase; font-weight: 600; margin-bottom: 0.2rem;">Option {{ $letter }}</label>
                                                                                 <input type="text" 
                                                                                        value="{{ $optList[$letter] ?? '' }}" 
                                                                                        x-on:input="
                                                                                            let arr = JSON.parse($wire.get('template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.options') || '[]');
                                                                                            if (!arr[{{ $gapIdx }}]) arr[{{ $gapIdx }}] = {options: 'a|,b|,c|', answer: 'a'};
                                                                                            let opts = (arr[{{ $gapIdx }}].options || '').split(',').map(o => o.split('|'));
                                                                                            let match = opts.find(o => o[0] === '{{ $letter }}');
                                                                                            if (match) match[1] = $event.target.value;
                                                                                            else opts.push(['{{ $letter }}', $event.target.value]);
                                                                                            arr[{{ $gapIdx }}].options = opts.map(o => o.join('|')).join(',');
                                                                                            $wire.set('template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.options', JSON.stringify(arr));
                                                                                        "
                                                                                        class="telc-input" style="padding: 0.35rem 0.5rem; font-size: 0.8rem; width: 100%;">
                                                                                 <button type="button" 
                                                                                         x-on:click="
                                                                                            let arr = JSON.parse($wire.get('template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.options') || '[]');
                                                                                            if (!arr[{{ $gapIdx }}]) arr[{{ $gapIdx }}] = {options: 'a|,b|,c|', answer: 'a'};
                                                                                            arr[{{ $gapIdx }}].answer = '{{ $letter }}';
                                                                                            $wire.set('template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.options', JSON.stringify(arr));
                                                                                         "
                                                                                         style="margin-top: 0.35rem; font-size: 0.75rem; width: 100%; border: 1px solid #ccc; cursor: pointer; border-radius: 4px; padding: 4px 0; background: {{ ($cur['answer'] ?? '') === $letter ? '#4ade80' : '#f3f4f6' }}; font-weight: {{ ($cur['answer'] ?? '') === $letter ? 'bold' : 'normal' }};">
                                                                                     {{ ($cur['answer'] ?? '') === $letter ? '✓ Correct' : 'Set Correct' }}
                                                                                 </button>
                                                                             </div>
                                                                         @endforeach
                                                                     </div>
                                                                 </div>
                                                             @endforeach
                                                         </div>
                                                     </div>
                                                 </div>
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

                <!-- Participant Scores and Attempts Tracker -->
                @if ($selectedTemplateId)
                    <div class="telc-section-editor" style="margin-top: 3rem; background: #fff; padding: 2rem; border-radius: 8px; border: 1px solid #e5e7eb;">
                        <div style="margin-bottom: 1.5rem;">
                            <h3 style="font-size: 1.25rem; font-weight: 700; color: #111827;">Teilnehmer & Ergebnisse</h3>
                            <p style="font-size: 0.85rem; color: #6b7280;">Übersicht aller Benutzer, die diese Prüfung absolviert haben.</p>
                        </div>

                        <div style="overflow-x: auto;">
                            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                                <thead>
                                    <tr style="border-bottom: 1px solid #e5e7eb; color: #4b5563; font-weight: 600;">
                                        <th style="padding: 0.75rem 1rem;">Teilnehmer</th>
                                        <th style="padding: 0.75rem 1rem;">Datum</th>
                                        <th style="padding: 0.75rem 1rem; text-align: center;">Score</th>
                                        <th style="padding: 0.75rem 1rem; text-align: right;">Ergebnis</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($participants as $attempt)
                                        <tr style="border-bottom: 1px solid #f3f4f6; color: #374151;">
                                            <td style="padding: 0.75rem 1rem;">
                                                <strong style="color: #111827; display: block;">{{ $attempt->user->name ?? 'Gast' }}</strong>
                                                <span style="font-size: 0.8rem; color: #6b7280;">{{ $attempt->user->email ?? 'Gast-Zugang' }}</span>
                                            </td>
                                            <td style="padding: 0.75rem 1rem; color: #6b7280;">
                                                {{ $attempt->submitted_at?->format('d.m.Y H:i') ?? $attempt->created_at->format('d.m.Y H:i') }}
                                            </td>
                                            <td style="padding: 0.75rem 1rem; text-align: center; font-weight: 600; color: #d8232a;">
                                                {{ $attempt->result['earned'] ?? 0 }} / {{ $attempt->result['possible'] ?? 0 }}
                                            </td>
                                            <td style="padding: 0.75rem 1rem; text-align: right; font-weight: 700;">
                                                <span style="color: {{ ($attempt->result['passed'] ?? false) ? '#137333' : '#c5221f' }}">
                                                    {{ $attempt->result['percentage'] ?? 0 }}% ({{ $attempt->result['grade'] ?? 'Nicht bestanden' }})
                                                </span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" style="padding: 2rem; text-align: center; color: #9ca3af;">Bislang keine Prüfungsversuche für diese Vorlage aufgezeichnet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
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

    {{-- Manual Payment Approvals List --}}
    <div class="telc-panel" style="margin-top: 2rem; background: #ffffff; border: 1px solid #d9dde5; border-radius: 8px; padding: 1.5rem;">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; margin-bottom: 1.25rem; color: #1a1a1a;">Freizugebende PayPal-Zahlungen</h3>
        
        @if (session()->has('payment_message'))
            <div style="background: #e6f4ea; color: #137333; padding: 0.75rem 1rem; border-radius: 4px; font-weight: 700; margin-bottom: 1rem; font-size: 0.85rem;">
                {{ session('payment_message') }}
            </div>
        @endif

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr style="text-align: left; background: #fafafa; border-bottom: 2px solid #edf0f4;">
                        <th style="padding: 0.75rem;">Teilnehmer</th>
                        <th style="padding: 0.75rem;">Prüfung</th>
                        <th style="padding: 0.75rem;">Transaktions-ID</th>
                        <th style="padding: 0.75rem;">Beleg</th>
                        <th style="padding: 0.75rem; text-align: right;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($pendingPayments as $payment)
                        <tr style="border-bottom: 1px solid #edf0f4;">
                            <td style="padding: 0.75rem;">
                                <strong>{{ $payment->user->name }}</strong><br>
                                <small style="color: #666666;">{{ $payment->user->email }}</small>
                            </td>
                            <td style="padding: 0.75rem;">
                                {{ $payment->examTemplate->title ?? 'TELC' }} ({{ $payment->examTemplate->level }})
                            </td>
                            <td style="padding: 0.75rem;">
                                <code style="background: #f1f5f9; padding: 0.2rem 0.4rem; border-radius: 4px;">{{ $payment->transaction_id }}</code>
                            </td>
                            <td style="padding: 0.75rem;">
                                @if ($payment->receipt_path)
                                    <a href="{{ Storage::url($payment->receipt_path) }}" target="_blank" style="color: #d8232a; font-weight: 700; text-decoration: underline;">
                                        Beleg öffnen
                                    </a>
                                @else
                                    <span style="color: #666666; font-style: italic;">Kein Bild</span>
                                @endif
                            </td>
                            <td style="padding: 0.75rem; text-align: right;">
                                <button type="button" wire:click="approvePayment({{ $payment->id }})" class="telc-button primary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem; min-height: auto; margin-right: 0.25rem;">
                                    Freigeben
                                </button>
                                <button type="button" wire:click="rejectPayment({{ $payment->id }})" class="telc-button" style="padding: 0.35rem 0.75rem; font-size: 0.8rem; min-height: auto; border-color: #d8232a; color: #d8232a;">
                                    Ablehnen
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 1.5rem; text-align: center; color: #666666; font-style: italic;">
                                Keine ausstehenden Freigabeanträge gefunden.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
