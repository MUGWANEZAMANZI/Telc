<div class="telc-shell" x-data="{ saved: false }" @exam-saved.window="saved = true; setTimeout(() => saved = false, 1800)">
    <div class="telc-topbar">
        <div>
            <p class="telc-eyebrow">TELC Germany</p>
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                @if ($mode !== 'overview')
                    <button type="button" wire:click="switchMode('overview')" class="telc-button font-bold text-sm" style="min-height: auto; padding: 0.35rem 0.75rem; border-color: #cbd5e1; border-radius: 6px; display: inline-flex; align-items: center; gap: 0.25rem;">
                        ← Overview
                    </button>
                @endif
                <h1>TELC Exam Template Builder</h1>
            </div>
        </div>

        @if ($mode !== 'overview')
            <div class="telc-actions">
                <div class="telc-segment">
                    <button type="button" wire:click="switchMode('builder')" @class(['active' => $mode === 'builder'])>Builder</button>
                    <button type="button" wire:click="switchMode('student')" @class(['active' => $mode === 'student'])>Student</button>
                    <button type="button" wire:click="switchMode('topics')" @class(['active' => $mode === 'topics'])>Topics</button>
                    <button type="button" wire:click="switchMode('contacts')" @class(['active' => $mode === 'contacts'])>Contacts</button>
                    <button type="button" wire:click="switchMode('blog')" @class(['active' => $mode === 'blog'])>Blog</button>
                    @if (auth()->user()?->isOwner())
                        <button type="button" wire:click="switchMode('pricing')" @class(['active' => $mode === 'pricing'])>Pricing</button>
                    @endif
                </div>

                @if ($mode !== 'blog')
                    <button type="button" wire:click="save" class="telc-button primary">Save</button>
                @endif
            </div>
        @else
            <div class="telc-actions">
                <button type="button" wire:click="createNewExam" class="telc-button primary" style="font-weight: 750;">
                    + Create New Exam Template
                </button>
            </div>
        @endif
    </div>

    @if (session()->has('dashboard_message'))
        <div style="background: #e6f4ea; border: 1px solid #a7f3d0; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; color: #137333; font-weight: 600;">
            {{ session('dashboard_message') }}
        </div>
    @endif

    <div class="telc-status" x-show="saved" x-cloak>Template saved.</div>

    @if ($mode === 'overview')
        {{-- Rich System Guide Banner for Admins & Owners --}}
        <div class="telc-banner no-print" style="background: linear-gradient(135deg, #1e293b, #0f172a); border: 1px solid #334155; border-radius: 12px; padding: 1.5rem; margin: 1.5rem 0; color: #f8fafc; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-family: 'Plus Jakarta Sans', sans-serif; text-align: left;">
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <div style="background: rgba(216, 35, 42, 0.15); border: 1.5px solid #d8232a; border-radius: 50%; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: #d8232a; flex-shrink: 0;">🛠️</div>
                <div>
                    <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #f8fafc;">System-Benutzerhandbuch</h3>
                    <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: #94a3b8; line-height: 1.45;">Erhalten Sie detaillierte Anleitungen zur Erstellung von Prüfungen, Upload-Limits (bis zu 100MB Audio), PayPal/Revolut Abrechnungsprozessen und Lückentext-Syntaktik.</p>
                </div>
            </div>
            <a href="{{ route('admin.guide') }}" class="telc-button" style="background: #d8232a; border-color: #d8232a; color: #ffffff; text-decoration: none; padding: 0.6rem 1.2rem; font-size: 0.85rem; font-weight: 700; border-radius: 6px; flex-shrink: 0; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 10px rgba(216, 35, 42, 0.25);">
                <span>Handbuch öffnen</span>
                <span>→</span>
            </a>
        </div>

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

        {{-- Grid of Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" style="margin-top: 1.5rem; display: grid; gap: 1.5rem; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));">
            @forelse ($cards as $card)
                <div class="telc-panel" style="display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid {{ strtolower($card['provider']) === 'telc' ? '#d8232a' : '#1e3a8a' }}; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.05); padding: 1.5rem; background: #fff; transition: transform 0.2s, box-shadow 0.2s;" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 16px rgba(0,0,0,0.08)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 12px rgba(0,0,0,0.05)'">
                    <div>
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                            <span style="font-size: 0.75rem; font-weight: 800; text-transform: uppercase; padding: 0.25rem 0.6rem; border-radius: 9999px; background: {{ strtolower($card['provider']) === 'telc' ? 'rgba(216, 35, 42, 0.1)' : 'rgba(30, 58, 138, 0.1)' }}; color: {{ strtolower($card['provider']) === 'telc' ? '#d8232a' : '#1e3a8a' }};">
                                {{ strtoupper($card['provider']) }} Folder
                            </span>
                            <span style="font-size: 1.25rem; font-weight: 800; color: #111827;">{{ strtoupper($card['level']) }}</span>
                        </div>

                        <div style="display: flex; gap: 1rem; margin-bottom: 1.25rem; background: #f8fafc; padding: 0.75rem; border-radius: 8px; border: 1px solid #edf2f7;">
                            <div style="flex: 1; text-align: center;">
                                <span style="display: block; font-size: 0.75rem; color: #64748b; font-weight: 600;">Published</span>
                                <strong style="font-size: 1.25rem; color: #10b981; font-weight: 800;">{{ $card['published_count'] }}</strong>
                            </div>
                            <div style="flex: 1; text-align: center; border-left: 1px solid #e2e8f0;">
                                <span style="display: block; font-size: 0.75rem; color: #64748b; font-weight: 600;">Draft</span>
                                <strong style="font-size: 1.25rem; color: #64748b; font-weight: 800;">{{ $card['draft_count'] }}</strong>
                            </div>
                        </div>

                        <div style="margin-top: 1rem;">
                            <h4 style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em;">Templates in this level</h4>
                            <ul style="list-style: none; padding: 0; margin: 0; display: flex; flex-direction: column; gap: 0.5rem;">
                                @foreach ($card['exams'] as $exam)
                                    <li style="display: flex; align-items: center; justify-content: space-between; padding: 0.5rem 0.75rem; background: #fdfdfd; border: 1px solid #f1f5f9; border-radius: 6px; font-size: 0.85rem;">
                                        <div style="display: flex; align-items: center; gap: 0.5rem; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 70%;">
                                            <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $exam->is_published ? '#10b981' : '#cbd5e1' }}; flex-shrink: 0;" title="{{ $exam->is_published ? 'Published' : 'Draft' }}"></span>
                                            <span style="color: #334155; font-weight: 600; text-overflow: ellipsis; overflow: hidden;" title="{{ $exam->title }}">{{ $exam->title }}</span>
                                        </div>
                                        <button type="button" wire:click="editExam({{ $exam->id }})" class="telc-button" style="padding: 0.25rem 0.5rem; font-size: 0.75rem; min-height: auto; border-color: #cbd5e1; background: #fff; color: #1e293b; font-weight: 700; transition: all 0.2s;">
                                            Edit
                                        </button>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @empty
                <div class="telc-panel" style="text-align: center; padding: 3rem; border-radius: 12px; margin-top: 1.5rem; grid-column: 1 / -1;">
                    <div style="font-size: 2.5rem; margin-bottom: 1rem;">📁</div>
                    <h3 style="font-size: 1.25rem; font-weight: 700; color: #1f2937; margin-bottom: 0.5rem;">No Exam Templates Found</h3>
                    <p style="color: #6b7280; font-size: 0.9rem; margin-bottom: 1.5rem;">There are currently no exam templates in the database. You can start by creating one.</p>
                    <button type="button" wire:click="createNewExam" class="telc-button primary">
                        Create Template from Presets
                    </button>
                </div>
            @endforelse
        </div>
    @else
        {{-- Rich System Guide Banner for Admins & Owners --}}
        <div class="telc-banner no-print" style="background: linear-gradient(135deg, #1e293b, #0f172a); border: 1px solid #334155; border-radius: 12px; padding: 1.5rem; margin: 1.5rem 0; color: #f8fafc; display: flex; align-items: center; justify-content: space-between; gap: 1.5rem; box-shadow: 0 4px 15px rgba(0,0,0,0.2); font-family: 'Plus Jakarta Sans', sans-serif; text-align: left;">
            <div style="display: flex; align-items: center; gap: 1.25rem;">
                <div style="background: rgba(216, 35, 42, 0.15); border: 1.5px solid #d8232a; border-radius: 50%; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; color: #d8232a; flex-shrink: 0;">🛠️</div>
                <div>
                    <h3 style="margin: 0; font-size: 1.1rem; font-weight: 700; color: #f8fafc;">System-Benutzerhandbuch</h3>
                    <p style="margin: 0.25rem 0 0; font-size: 0.85rem; color: #94a3b8; line-height: 1.45;">Erhalten Sie detaillierte Anleitungen zur Erstellung von Prüfungen, Upload-Limits (bis zu 100MB Audio), PayPal/Revolut Abrechnungsprozessen und Lückentext-Syntaktik.</p>
                </div>
            </div>
            <a href="{{ route('admin.guide') }}" class="telc-button" style="background: #d8232a; border-color: #d8232a; color: #ffffff; text-decoration: none; padding: 0.6rem 1.2rem; font-size: 0.85rem; font-weight: 700; border-radius: 6px; flex-shrink: 0; display: inline-flex; align-items: center; gap: 0.35rem; box-shadow: 0 4px 10px rgba(216, 35, 42, 0.25);">
                <span>Handbuch öffnen</span>
                <span>→</span>
            </a>
        </div>

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
                    <label>Template Category Directory</label>
                    <div class="telc-segment" style="margin-top: 0.5rem; width: 100%;">
                        <button type="button" wire:click="changeProvider('telc')" @class(['active' => $providerFilter === 'telc']) style="flex: 1;">TELC Folder</button>
                        <button type="button" wire:click="changeProvider('goethe')" @class(['active' => $providerFilter === 'goethe']) style="flex: 1;">Goethe Folder</button>
                    </div>
                </div>

                <label>Active Category Template</label>
                <select wire:change="loadTemplate($event.target.value)" class="telc-select" style="margin-bottom: 1.5rem; width: 100%;">
                    <option value="">— Select Template —</option>
                    @foreach ($templates as $record)
                        <option value="{{ $record->id }}" @selected($selectedTemplateId === $record->id)>
                            {{ $record->level }} - {{ $record->title }}
                        </option>
                    @endforeach
                </select>

                <h2>Template Source Settings</h2>

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
                <div style="margin-bottom: 1rem;">
                    <label class="telc-checkbox-label" style="margin-bottom: 0.5rem;">
                        <input type="checkbox" wire:model.live="template.is_free">
                        Free exam (all sections, no payment required)
                    </label>
                    <div style="margin-top: 0.5rem;">
                        <label>Template Price (EUR) — leave 0 for free / subscription only</label>
                        <input type="number" step="0.01" wire:model.live="template.price" class="telc-input" placeholder="e.g. 9.99">
                    </div>
                </div>

                <div class="telc-row" style="margin-top: 1rem; gap: 0.5rem; width: 100%;">
                    <button type="button" wire:click="togglePublished" class="telc-button" style="flex: 1;">
                        {{ $template['is_published'] ?? false ? 'Unpublish' : 'Publish' }}
                    </button>
                    <button type="button"
                            wire:click="deleteExam"
                            wire:confirm="Are you sure you want to permanently delete this exam template and all its attempts?"
                            class="telc-button"
                            style="border-color: #d8232a; color: #d8232a; font-weight: 750; flex: 1;">
                        Delete
                    </button>
                </div>
                <div style="margin-top: 0.5rem; text-align: center;">
                    <span @class(['telc-pill', 'green' => ($template['is_published'] ?? false)]) style="width: 100%; display: block; text-align: center;">
                        {{ $template['is_published'] ?? false ? 'Published' : 'Draft' }}
                    </span>
                </div>

                <h2>Presets</h2>
                <div class="telc-preset-list">
                    @foreach ($presets as $level)
                        <button type="button" wire:click="createFromPreset('{{ $level }}')" class="telc-button">New {{ $level }}</button>
                    @endforeach
                </div>

                @if ($newExamMessage)
                    <div style="margin-top: 0.75rem; padding: 0.6rem 0.75rem; background: #e6f4ea; border: 1px solid #a7f3d0; border-radius: 6px; color: #137333; font-size: 0.8rem; font-weight: 600;">
                        ✓ {{ $newExamMessage }}
                    </div>
                @endif

                <h2>Create Exam from Template</h2>
                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                    <label>New exam name</label>
                    <input type="text" wire:model="newExamTitle" class="telc-input" placeholder="e.g. Goethe Zertifikat C1 – Modellsatz 1">
                    <button type="button" wire:click="duplicateTemplateAsExam" class="telc-button primary"
                        @if (empty($selectedTemplateId)) disabled style="opacity: 0.5;" title="Select a template first" @endif>
                        Create Exam from Template
                    </button>
                </div>

                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #e5e7eb;">
                    <h2>Umfrage-Einstellungen</h2>
                    <label>Google Form URL</label>
                    <input type="url" wire:model.live="googleFormUrl" class="telc-input" placeholder="https://docs.google.com/forms/d/...">
                    <button type="button" wire:click="saveGoogleFormUrl" class="telc-button primary" style="margin-top: 0.5rem; width: 100%;">URL speichern</button>
                    @if (session('google_form_saved'))
                        <span style="color:#16a34a;font-size:0.8rem;margin-top:0.25rem;display:block;">✓ Gespeichert</span>
                    @endif
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
                                <input type="number" wire:model.live="template.sections.{{ $sectionIndex }}.minutes" class="telc-input minutes" style="max-width: 80px;" placeholder="Min">
                                <select wire:model.live="template.sections.{{ $sectionIndex }}.layout_style" class="telc-select" style="max-width: 250px; font-size: 0.85rem; padding: 0.25rem 0.5rem; height: 36px; margin: 0 0.5rem;">
                                    <option value="default">Layout: Automatisch</option>
                                    <option value="teil1">Teil 1: Überschriften oben, Texte unten</option>
                                    <option value="teil2">Teil 2: Artikel oben, MC unten</option>
                                    <option value="teil3">Teil 3: Situationen oben, Info-Texte unten (Grid)</option>
                                </select>
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

                                            <label>Instruction / Question Text</label>
                                            <input type="text" wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.instruction" class="telc-input" placeholder="e.g. Question text or section instruction">

                                            @if ($element['type'] === 'audio')
                                                <label>Audio file</label>
                                                <div class="telc-audio-upload">
                                                    <input type="file" wire:model="audioUpload" accept="audio/*">
                                                    <button type="button" wire:click="uploadAudio({{ $sectionIndex }}, {{ $elementIndex }})" wire:loading.attr="disabled" wire:target="audioUpload, uploadAudio" class="telc-button primary">
                                                        <span wire:loading.remove wire:target="audioUpload, uploadAudio">Upload</span>
                                                        <span wire:loading wire:target="audioUpload">Wird geladen...</span>
                                                        <span wire:loading wire:target="uploadAudio">Wird gespeichert...</span>
                                                    </button>
                                                    <div wire:loading wire:target="audioUpload" style="font-size:0.8rem;color:#d8232a;font-weight:600;margin-top:0.25rem;">
                                                        ⏳ Audio-Datei wird geladen... Klicken Sie nach dem Laden auf Upload.
                                                    </div>
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
                                            @endif

                                            @if (in_array($element['type'], ['multiple_choice', 'matching', 'fill_blank', 'true_false', 'inline_gap', 'interactive_text'], true))
                                                <div style="margin-top: 0.75rem; padding: 0.75rem; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px;">
                                                    <label style="font-weight: 600; color: #374151; display: block; margin-bottom: 0.5rem;">Erklärung (Antwort-Erläuterung)</label>
                                                    <textarea wire:model.live="template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.explanation" class="telc-textarea" style="min-height:4rem" placeholder="Optional: Eigene Erklärung verfassen. Leer lassen für KI-Auto-Generierung."></textarea>
                                                    <div style="display:flex;gap:0.5rem;margin-top:0.5rem;align-items:center;">
                                                        <button type="button" wire:click="generateExplanation({{ $sectionIndex }}, {{ $elementIndex }})" class="telc-link-button" style="font-size:0.8rem;color:#6366f1;" wire:loading.attr="disabled">
                                                            🤖 Mit KI generieren
                                                        </button>
                                                        @if (!empty($element['explanation']))
                                                            <span style="font-size:0.75rem;color:#16a34a;">✓ Erklärung vorhanden</span>
                                                        @endif
                                                    </div>
                                                </div>
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
                                                             $gapData = is_string($element['options'] ?? null) ? (json_decode($element['options'], true) ?: []) : ($element['options'] ?? []);
                                                             // Match gaps count in the text
                                                             preg_match_all('/--(\d+)--/', $element['content'] ?? '', $matches);
                                                             $gKeys = array_unique($matches[1]);
                                                         @endphp

                                                         <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 1rem; max-height: 25rem; overflow-y: auto; width: 100%;">
                                                             <strong style="display: block; margin-bottom: 0.75rem; font-size: 0.85rem; color: #374151;">Gaps Options Manager</strong>
                                                             
                                                             @foreach ($gKeys as $gKey)
                                                                 @php
                                                                     $gapIdx = (int) $gKey;
                                                                     $cur = $gapData[$gapIdx] ?? ['options' => 'a|Option A,b|Option B,c|Option C,d|Option D', 'answer' => 'a'];
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
                                                                     
                                                                     <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.5rem;">
                                                                         @foreach (['a', 'b', 'c', 'd'] as $letter)
                                                                             <div>
                                                                                 <label style="font-size: 0.7rem; color: #4b5563; display: block; text-transform: uppercase; font-weight: 600; margin-bottom: 0.2rem;">Option {{ $letter }}</label>
                                                                                 <input type="text" 
                                                                                        value="{{ $optList[$letter] ?? '' }}" 
                                                                                        x-on:input="
                                                                                            let arr = JSON.parse($wire.get('template.sections.{{ $sectionIndex }}.elements.{{ $elementIndex }}.options') || '[]');
                                                                                            if (!arr[{{ $gapIdx }}]) arr[{{ $gapIdx }}] = {options: 'a|,b|,c|,d|', answer: 'a'};
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
                                                                                            if (!arr[{{ $gapIdx }}]) arr[{{ $gapIdx }}] = {options: 'a|,b|,c|,d|', answer: 'a'};
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
    @elseif ($mode === 'topics')
        <livewire:admin-topics />
    @elseif ($mode === 'pricing')
        <livewire:admin-pricing />
    @elseif ($mode === 'blog')
        <livewire:admin-blog />
    @elseif ($mode === 'contacts')
        <div class="telc-panel" style="background: #ffffff; border: 1px solid #d9dde5; border-radius: 8px; padding: 2rem; text-align: left; font-family: 'Plus Jakarta Sans', sans-serif;">
            <div style="margin-bottom: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin: 0 0 0.25rem; color: #1a1a1a;">Kontaktanfragen</h3>
                    <p style="color: #666666; font-size: 0.9rem; margin: 0;">Alle über das Kontaktformular eingegangenen Anfragen.</p>
                </div>
            </div>

            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.95rem;">
                    <thead>
                        <tr style="background: #fafafa; border-bottom: 2px solid #edf0f4;">
                            <th style="padding: 0.75rem 1rem;">Name</th>
                            <th style="padding: 0.75rem 1rem;">Organisation</th>
                            <th style="padding: 0.75rem 1rem;">E-Mail</th>
                            <th style="padding: 0.75rem 1rem;">Datum</th>
                            <th style="padding: 0.75rem 1rem; text-align: center;">Status</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">Aktionen</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($contactRequests as $req)
                            <tr style="border-bottom: 1px solid #edf0f4;">
                                <td style="padding: 0.75rem 1rem; font-weight: 700; color: #1a1a1a;">{{ $req->name }}</td>
                                <td style="padding: 0.75rem 1rem; color: #444444;">{{ $req->organization ?: '-' }}</td>
                                <td style="padding: 0.75rem 1rem;">
                                    <code style="background: #f1f5f9; padding: 0.2rem 0.4rem; border-radius: 4px; font-size: 0.85rem;">{{ $req->email }}</code>
                                </td>
                                <td style="padding: 0.75rem 1rem; color: #666666;">{{ $req->created_at->format('d.m.Y H:i') }} Uhr</td>
                                <td style="padding: 0.75rem 1rem; text-align: center;">
                                    @if ($req->status === 'responded')
                                        <span style="background: #e6f4ea; color: #137333; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">
                                            Beantwortet
                                        </span>
                                    @else
                                        <span style="background: #fce8e6; color: #c5221f; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">
                                            Offen
                                        </span>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                    @if ($req->status === 'responded')
                                        <button type="button" wire:click="toggleContactStatus({{ $req->id }}, 'not_responded')" class="telc-button" style="padding: 0.35rem 0.75rem; font-size: 0.8rem; min-height: auto; border-color: #6b7280; color: #374151;">
                                            Als offen markieren
                                        </button>
                                    @else
                                        <button type="button" wire:click="toggleContactStatus({{ $req->id }}, 'responded')" class="telc-button primary" style="padding: 0.35rem 0.75rem; font-size: 0.8rem; min-height: auto; background-color: #137333; border-color: #137333;">
                                            Als beantwortet markieren
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="padding: 2rem; text-align: center; color: #666666; font-style: italic;">
                                    Bislang keine Kontaktanfragen eingegangen.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @elseif ($mode === 'student')
        <div class="admin-student-preview" style="max-width: 100%;">
            @if (empty($template))
                <p style="padding: 2rem; text-align: center; color: #6b7280; font-style: italic;">
                    Keine Vorlage geladen. Bitte wählen Sie zuerst eine Vorlage im Builder aus.
                </p>
            @else
                @include('livewire.student-exam')
            @endif
        </div>
    @endif

    @if ($mode !== 'student')
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
    @endif
    @endif
</div>
