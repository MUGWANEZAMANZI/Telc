<div class="guest-exam-shell">
    <style>
        .guest-exam-shell { font-family: 'Plus Jakarta Sans', sans-serif; }

        .free-notification { position: fixed; top: 1rem; right: 1rem; z-index: 1000; background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 0.6rem 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.4); display: flex; align-items: center; gap: 0.5rem; }
        .free-notification svg { width: 16px; height: 16px; color: #eab308; flex-shrink: 0; }
        .free-notification span { font-size: 0.78rem; color: #aaa; }
        .free-notification strong { color: #eab308; }

        .free-exam-body { min-height: 100vh; background: #f7f7f7; padding: 0 1rem; }

        .doc-timer-bar { position: fixed; top: 1rem; right: 1rem; z-index: 1001; background: #ffffff; border: 1px solid #e5e5e5; border-radius: 8px; padding: 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.08); width: 200px; display: flex; flex-direction: column; align-items: center; gap: 0.75rem; font-family: 'Plus Jakarta Sans', sans-serif; }
        .doc-timer-text { display: flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; }
        .save-indicator { display: flex; align-items: center; gap: 0.25rem; font-size: 0.75rem; color: #666; }
        .save-pulse { width: 6px; height: 6px; border-radius: 50%; background: #166534; animation: pulse 2s infinite; }
        @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: 0.4; } }
        .submit-button { background: #1a1a1a; color: #ffffff; border: none; padding: 0.6rem 1.5rem; border-radius: 4px; font-weight: 700; font-size: 0.85rem; cursor: pointer; transition: all 0.2s; font-family: inherit; width: 100%; white-space: nowrap; }
        .submit-button:hover { opacity: 0.85; }

        .level-tabs { display: flex; justify-content: center; gap: 0.5rem; padding: 1.5rem 1rem; background: #111; border-bottom: 1px solid #222; }
        .level-tabs button { padding: 0.4rem 1.2rem; border-radius: 999px; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all 0.15s; border: none; font-family: inherit; }
        .level-tabs button.active { background: #eab308; color: #1a1a1a; }
        .level-tabs button.inactive { background: #222; color: #666; }
        .level-tabs button.inactive:hover { background: #2a2a2a; color: #999; }

        .free-result-panel { max-width: 600px; margin: 2rem auto; background: #ffffff; border: 1px solid #d9dde5; border-radius: 8px; padding: 2rem; text-align: center; box-shadow: 0 4px 20px rgba(0,0,0,0.03); }
        .free-result-panel .score { font-size: 2.5rem; font-weight: 800; color: #1a1a1a; }
        .free-result-panel .score-label { font-size: 0.85rem; color: #666; text-transform: uppercase; letter-spacing: 0.05em; font-weight: 700; }

        .premium-document {
            max-width: 800px; margin: 2rem auto; background: #ffffff; border: 1px solid #d9dde5;
            position: relative; padding: 4rem 3.5rem 8rem 5.5rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border-radius: 8px; font-family: 'Plus Jakarta Sans', sans-serif; color: #1a1a1a; line-height: 1.85;
        }
        .premium-document::before { content: ''; position: absolute; top: 0; bottom: 0; left: 4.5rem; width: 1px; border-left: 1px solid #cbcbcb; }
        .premium-document::after { content: ''; position: absolute; top: 0; bottom: 0; left: 4.7rem; width: 1px; border-left: 1px solid #cbcbcb; }

        .c1-badge { display: inline-block; border: 1px solid #1a1a1a; font-size: 0.7rem; font-weight: 700; letter-spacing: 0.15em; padding: 0.3rem 0.8rem; margin-bottom: 1.5rem; text-transform: uppercase; }
        .c1-title { font-family: 'Playfair Display', Georgia, serif; font-size: 2.5rem; font-weight: 400; line-height: 1.15; margin-bottom: 0.25rem; }
        .c1-subtitle { color: #666; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2rem; }
        .c1-instructions { background: #fafafa; border-left: 3px solid #1a1a1a; padding: 1rem 1.25rem; margin-bottom: 2rem; font-size: 0.9rem; line-height: 1.6; color: #4a4a4a; }
        .c1-passage { font-size: 1rem; line-height: 2; text-align: justify; margin-bottom: 2rem; }
        .section-label { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #d8232a; margin-bottom: 0.25rem; }
        .section-heading { font-family: 'Playfair Display', Georgia, serif; font-size: 1.5rem; font-weight: 400; margin-bottom: 1rem; }
        .c1-divider { border: none; border-top: 1px solid #e5e5e5; margin: 2rem 0; }
        .cloze-select-wrapper { position: relative; display: inline; margin: 0 0.25rem; }
        .cloze-select { border: none; border-bottom: 2px solid #1a1a1a; background: transparent; font-family: inherit; font-size: 1rem; font-weight: 700; color: #d8232a; padding: 0 0.25rem; outline: none; cursor: pointer; border-radius: 0; max-width: 100%; white-space: normal; }
        .cloze-select:focus { border-bottom-color: #d8232a; }
        .cloze-gap-num { font-size: 0.7rem; font-weight: 800; vertical-align: super; color: #666; margin-right: 0.1rem; }

        .mc-question-row { margin-bottom: 2rem; }
        .mc-question-header { font-weight: 600; font-size: 1rem; margin-bottom: 0.75rem; }
        .mc-options-list { display: flex; flex-direction: column; gap: 0.5rem; }
        .mc-option-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.6rem 0.85rem; border: 1px solid #e5e5e5; border-radius: 4px; cursor: pointer; transition: all 0.2s; user-select: none; }
        .mc-option-item:hover { border-color: #1a1a1a; background: #fafafa; }
        .mc-option-item.selected { border-color: #1a1a1a; background: #f7f7f7; }
        .mc-option-bullet { width: 14px; height: 14px; border: 1px solid #666; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; transition: all 0.2s; }
        .mc-option-item:hover .mc-option-bullet { border-color: #1a1a1a; }
        .mc-radio-input { display: none; }
        .mc-radio-input:checked + .mc-option-bullet { border-color: #1a1a1a; background: #1a1a1a; }
        .mc-radio-input:checked + .mc-option-bullet::after { content: ''; width: 6px; height: 6px; background: #fff; border-radius: 50%; }

        .tf-option-table { width: 100%; border-collapse: collapse; margin-top: 1.5rem; }
        .tf-option-table th, .tf-option-table td { padding: 0.7rem 0.85rem; border-bottom: 1px solid #e5e5e5; text-align: left; font-size: 0.9rem; }
        .tf-option-table th { font-weight: 700; color: #666; text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.05em; }
        .tf-choice-label { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border: 1px solid #e5e5e5; border-radius: 50%; cursor: pointer; font-weight: 600; transition: all 0.2s; margin-right: 0.35rem; user-select: none; }
        .tf-choice-label:hover { border-color: #1a1a1a; background: #fafafa; }
        .tf-choice-input { display: none; }
        .tf-choice-input:checked + .tf-choice-label { border-color: #1a1a1a; background: #1a1a1a; color: #fff; }

        .bubble.selected.correct { background: #166534; color: #fff; border-color: #166534; }
        .bubble.selected.wrong { background: #991b1b; color: #fff; border-color: #991b1b; }
        .bubble.correct-key:not(.selected) { border-color: #166534; background: #e6f4ea; }
        .bubble.wrong-key { border-color: #991b1b; background: #fce8e6; }

        .cloze-select.correct { border-bottom-color: #166534 !important; color: #166534 !important; }
        .cloze-select.wrong { border-bottom-color: #991b1b !important; color: #991b1b !important; }

        .fill-blank-input.correct { border-bottom-color: #166534; color: #166534; font-weight: 700; }
        .fill-blank-input.wrong { border-bottom-color: #991b1b; color: #991b1b; font-weight: 700; }

        .matching-select.correct { border-color: #166534; color: #166534; font-weight: 700; }
        .matching-select.wrong { border-color: #991b1b; color: #991b1b; font-weight: 700; }

        .writing-section.marked textarea { opacity: 0.7; }
        .writing-section .writing-score { display: inline-block; background: #1a1a1a; color: #eab308; font-size: 0.8rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 4px; margin-top: 0.5rem; }

        .answer-sheet { background: #fff; border: 1px solid #d9dde5; border-radius: 6px; padding: 1.5rem; margin-top: 2rem; }
        .answer-sheet-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.25rem; padding-bottom: 0.75rem; border-bottom: 1px solid #e5e5e5; }
        .answer-sheet-title { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #1a1a1a; display: block; }
        .answer-sheet-section { font-size: 0.75rem; color: #666; display: block; }
        .answer-sheet-grid { display: flex; flex-direction: column; gap: 0.75rem; }
        .answer-sheet-row { display: flex; align-items: center; gap: 0.5rem; padding: 0.5rem 0; border-bottom: 1px solid #f5f5f5; }
        .question-num { font-size: 0.8rem; font-weight: 700; color: #666; min-width: 1.5rem; }
        .bubble-group { display: flex; gap: 0.35rem; }
        .bubble { display: inline-flex; align-items: center; justify-content: center; width: 28px; height: 28px; border: 1px solid #ccc; border-radius: 50%; cursor: pointer; font-size: 0.75rem; font-weight: 600; transition: all 0.15s; }
        .bubble:hover { border-color: #1a1a1a; }
        .bubble.selected { background: #1a1a1a; color: #fff; border-color: #1a1a1a; }
        .bubble input { display: none; }
        .matching-select { padding: 0.3rem 0.5rem; border: 1px solid #ccc; border-radius: 4px; font-size: 0.85rem; }
        .fill-blank-input { border: none; border-bottom: 1.5px solid #cbcbcb; padding: 0.2rem; font-size: 0.9rem; outline: none; width: 160px; }

        .audio-player { margin: 1rem 0; }
        .audio-player audio { width: 100%; }

        .inline-gap-text { font-size: 1rem; line-height: 2.2; }
        .inline-gap-pill { display: inline-block; position: relative; margin: 0 0.15rem; }
        .inline-gap-pill select { padding: 0.15rem 0.3rem; border: 1px solid #ccc; border-radius: 3px; font-size: 0.9rem; background: #fff; cursor: pointer; }
        .inline-gap-pill.filled select { border-color: #1a1a1a; font-weight: 600; background: #fafafa; }

        .writing-textarea-lined { width: 100%; min-height: 350px; padding: 1rem; font-family: inherit; font-size: 1rem; line-height: 2rem; border: 1px solid #d9dde5; background: transparent; background-image: linear-gradient(transparent, transparent 1.95rem, #cbcbcb 1.95rem, #cbcbcb 2rem); background-size: 100% 2rem; color: #1a1a1a; resize: vertical; outline: none; }

        .free-back-btn { display: inline-flex; align-items: center; gap: 0.35rem; color: #888; font-size: 0.8rem; cursor: pointer; background: none; border: none; font-family: inherit; padding: 0.5rem 0; transition: color 0.15s; }
        .free-back-btn:hover { color: #eab308; }
        .free-back-btn svg { width: 14px; height: 14px; }

        .feedback-badge { display: inline-block; font-size: 0.7rem; font-weight: 700; padding: 0.1rem 0.4rem; border-radius: 3px; margin-left: 0.25rem; text-transform: uppercase; white-space: nowrap; }
        .feedback-badge.ok { background: #e6f4ea; color: #137333; }
        .feedback-badge.fail { background: #fce8e6; color: #c5221f; }

        .speaking-locked { text-align: center; padding: 3rem 1rem; color: #888; }
        .speaking-locked svg { width: 48px; height: 48px; margin: 0 auto 1rem; color: #555; }

        @keyframes slideUp { from { opacity:0;transform:translateY(1rem); } to { opacity:1;transform:translateY(0); } }
    </style>

    {{-- Free sections notification (always visible during exam) --}}
    @if ($activeSectionIndex !== null)
        <div class="free-notification">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Free sections: <strong>{{ $remainingSections }}</strong> / {{ $freeSectionLimit }}</span>
        </div>
    @endif

    @if ($showLoginPrompt)
        <div class="fixed inset-0 z-[1050] flex items-center justify-center bg-black/85 backdrop-blur-md p-4 transition-all duration-300">
            <div class="bg-zinc-900 border border-zinc-800 p-8 rounded-2xl max-w-md w-full shadow-2xl relative text-center overflow-hidden animate-[slideUp_0.3s_ease-out]">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-[#d8232a] via-yellow-500 to-[#d8232a]"></div>
                <div class="w-16 h-16 bg-[#d8232a]/10 border border-[#d8232a]/20 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-8 h-8 text-[#d8232a]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-zinc-100 font-serif mb-4">Ergebnisse anzeigen und speichern</h2>
                <p class="text-zinc-400 text-sm leading-relaxed mb-8">
                    Bitte loggen Sie sich ein oder registrieren Sie sich, um Ihre Testergebnisse zu sehen und dauerhaft in Ihrem Profil zu speichern.
                </p>
                <div class="flex flex-col gap-3">
                    <a href="{{ route('register') }}" class="w-full inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-semibold rounded-lg bg-[#d8232a] text-white hover:bg-[#c11f25] transition duration-150 ease-in-out uppercase tracking-wide">
                        Konto erstellen
                    </a>
                    <a href="{{ route('login') }}" class="w-full inline-flex items-center justify-center px-6 py-3 border border-zinc-700 text-sm font-semibold rounded-lg text-zinc-300 hover:bg-zinc-800 transition duration-150 ease-in-out uppercase tracking-wide">
                        Einloggen
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if ($hasReachedLimit && $activeSectionIndex === null)
        {{-- Limit reached --}}
        <div class="flex flex-col items-center justify-center min-h-[60vh] text-center px-4">
            <div class="bg-zinc-900 border border-zinc-800/80 p-8 rounded-2xl max-w-lg shadow-2xl relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 via-yellow-500 to-red-600"></div>
                <svg class="w-16 h-16 text-yellow-500 mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <h1 class="text-2xl font-bold text-zinc-100 font-serif mb-4">Alle kostenlosen Abschnitte abgeschlossen</h1>
                <p class="text-zinc-400 text-sm leading-relaxed mb-6">
                    Sie haben alle <strong>{{ $freeSectionLimit }} kostenlosen Abschnitte</strong> genutzt.
                    Bitte registrieren Sie sich oder schließen Sie ein Abonnement ab, um vollen Zugang zu erhalten.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-semibold rounded-lg bg-yellow-500 text-neutral-950 hover:bg-yellow-400 transition duration-150 ease-in-out font-mono tracking-wide uppercase">Jetzt Registrieren</a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-6 py-3 border border-zinc-700 text-sm font-semibold rounded-lg text-zinc-300 hover:bg-zinc-800 transition duration-150 ease-in-out font-mono tracking-wide uppercase">Anmelden</a>
                </div>
                <div class="mt-4">
                    <a href="{{ route('pricing') }}" class="inline-flex items-center justify-center px-6 py-3 border border-yellow-500/50 text-sm font-semibold rounded-lg text-yellow-400 hover:bg-yellow-500/10 transition duration-150 ease-in-out font-mono tracking-wide uppercase">Abonnement ansehen</a>
                </div>
            </div>
        </div>

    @elseif ($activeSectionIndex === null)
        {{-- Chapter list view --}}
        <section class="exam-store">
            <div class="exam-store-header">
                <p>Free Exam</p>
                <h1>Try an exam before subscribing</h1>
                <span>Select a chapter below to begin your free trial.</span>
            </div>

            @if ($levels)
                <div class="level-tabs">
                    @foreach ($levels as $level)
                        <button type="button" wire:click="selectLevel('{{ $level }}')"
                            @class(['active' => $selectedLevel === $level, 'inactive' => $selectedLevel !== $level])>
                            {{ $level }}
                        </button>
                    @endforeach
                </div>
            @endif

            @if (empty($template))
                <div class="telc-panel">
                    <h2>No exam is published for this level yet.</h2>
                    <p>An administrator should publish a template first.</p>
                </div>
            @else
                <div class="max-w-2xl mx-auto mb-16">
                    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <span class="text-xs font-bold uppercase tracking-widest text-yellow-500">{{ $template['level'] ?? '' }}</span>
                                <h2 class="text-xl font-bold text-zinc-100 mt-1">{{ $template['title'] ?? '' }}</h2>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-zinc-500">Free sections</div>
                                <div class="text-lg font-bold text-yellow-500">{{ $remainingSections }} / {{ $freeSectionLimit }}</div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            @foreach ($sections as $index => $section)
                                @php
                                    $status = $this->sectionStatus($index);
                                    $statusIcon = match($status) {
                                        'completed' => '✓',
                                        'locked' => '🔒',
                                        'in-progress' => '→',
                                        default => ''
                                    };
                                    $statusClass = match($status) {
                                        'completed' => 'border-green-800/50 bg-zinc-800/30',
                                        'locked' => 'border-red-900/30 bg-zinc-800/20 opacity-50',
                                        'in-progress' => 'border-yellow-600/50 bg-zinc-800/50',
                                        default => 'border-zinc-700 bg-zinc-800/50 hover:bg-zinc-800 hover:border-yellow-500/50'
                                    };
                                    $canStart = match($status) {
                                        'locked' => false,
                                        'completed' => true,
                                        default => true
                                    };
                                @endphp
                                <button type="button"
                                    wire:click="startSection({{ $index }})"
                                    @disabled(!$canStart)
                                    class="w-full text-left p-4 rounded-xl border transition-all duration-200 flex items-center justify-between {{ $statusClass }}">
                                    <div class="flex items-center gap-3">
                                        <div @class([
                                            'w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0',
                                            'bg-zinc-700 text-zinc-300' => $status === 'new',
                                            'bg-green-700 text-white' => $status === 'completed',
                                            'bg-red-800 text-red-200' => $status === 'locked',
                                            'bg-yellow-700 text-yellow-200' => $status === 'in-progress',
                                        ])>
                                            @if ($status === 'completed')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" /></svg>
                                            @elseif ($status === 'locked')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                                            @elseif ($status === 'in-progress')
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /></svg>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </div>
                                        <div>
                                            <span class="text-sm font-semibold text-zinc-200">{{ $section['title'] ?? 'Section ' . ($index + 1) }}</span>
                                            <span class="text-xs text-zinc-500 block">{{ $section['minutes'] ?? '?' }} min · {{ ucfirst($this->sectionType($index)) }}</span>
                                        </div>
                                    </div>
                                    <span @class([
                                        'text-xs font-medium px-2 py-0.5 rounded-full',
                                        'text-zinc-500 bg-zinc-800' => $status === 'new',
                                        'text-green-400 bg-green-900/30' => $status === 'completed',
                                        'text-red-400 bg-red-900/30' => $status === 'locked',
                                        'text-yellow-400 bg-yellow-900/30' => $status === 'in-progress',
                                    ])>
                                        {{ match($status) { 'completed' => 'Completed', 'locked' => 'Locked', 'in-progress' => 'In Progress', default => 'Start' } }}
                                    </span>
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </section>

    @else
        {{-- Exam-taking view — single-column premium-document layout --}}
        @php
            $currentSection = $sections[$activeSectionIndex] ?? null;
            $theme = $template['theme'] ?? [];
            $minutes = intdiv($remainingSeconds, 60);
            $seconds = $remainingSeconds % 60;
            $hasInlineGap = $currentSection && collect($currentSection['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'inline_gap');
            $hasInteractiveText = $currentSection && collect($currentSection['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'interactive_text');
            $hasAudio = $currentSection && collect($currentSection['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'audio');
            $hasWriting = $currentSection && collect($currentSection['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'writing');
            $sectionLocked = $this->isNoRetrySection($activeSectionIndex) && $this->isSectionCompleted($activeSectionIndex);
        @endphp

        <div class="free-exam-body">
            {{-- Floating timer widget --}}
            @if ($currentSection && !$result)
                <div class="doc-timer-bar" wire:poll.1s="tick">
                    <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #666; border-bottom: 1px solid #e5e5e5; padding-bottom: 0.25rem; width: 100%; text-align: center;">
                        {{ $currentSection['title'] }}
                    </div>
                    <div class="doc-timer-text">
                        <svg style="color: #d8232a;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span>Restzeit:</span>
                        <strong style="color: #d8232a;">{{ sprintf('%02d:%02d', $minutes, $seconds) }} Min.</strong>
                    </div>
                    <div class="save-indicator">
                        <span class="save-pulse"></span>
                        <span>Auto-Safe aktiv</span>
                    </div>
                    <button type="button" wire:click="backToChapters" style="background: none; border: none; color: #666; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; cursor: pointer; text-decoration: underline;">Menü verlassen</button>
                    <button type="button" wire:click="submit" class="submit-button">Prüfung abgeben</button>
                </div>
            @elseif ($currentSection && $result)
                <div class="doc-timer-bar">
                    <button type="button" wire:click="backToChapters" style="background: none; border: none; color: #666; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; cursor: pointer; text-decoration: underline;">← Zurück</button>
                </div>
            @endif

            {{-- Premium document column --}}
            @if ($sectionLocked)
                <div class="premium-document">
                    <div style="text-align: center; padding: 3rem 1rem; color: #888;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="width: 48px; height: 48px; margin: 0 auto 1rem; color: #555;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        <h3 style="font-size: 1.2rem; font-weight: 700; color: #333; margin-bottom: 0.5rem;">Section completed</h3>
                        <p style="color: #666; font-size: 0.9rem;">This section cannot be retaken.</p>
                    </div>
                </div>

            @elseif ($currentSection)
                <div class="premium-document">
                    <div class="c1-badge">{{ $template['level'] ?? '' }}</div>
                    <div class="section-label">{{ $currentSection['title'] }}</div>
                    <p class="c1-subtitle">{{ $template['title'] ?? '' }}</p>

                    {{-- Audio player --}}
                    @if ($hasAudio)
                        @foreach ($currentSection['elements'] as $el)
                            @if (($el['type'] ?? '') === 'audio')
                                <div class="audio-player"><audio controls src="{{ $el['content'] }}"></audio></div>
                            @endif
                        @endforeach
                    @endif

                    {{-- Section content --}}
                    @foreach ($currentSection['elements'] as $element)
                        @php $elId = $element['id'] ?? ('el_' . $loop->index); @endphp

                        {{-- TEXT --}}
                        @if (($element['type'] ?? '') === 'text')
                            @if ($element['label'] ?? null)
                                <h4 style="font-weight:700;margin-bottom:0.5rem;">{{ $element['label'] }}</h4>
                            @endif
                            <article class="c1-passage">{!! nl2br(e($element['content'] ?? '')) !!}</article>

                        {{-- IMAGE --}}
                        @elseif (($element['type'] ?? '') === 'image')
                            <figure style="margin:1rem 0;"><img src="{{ $element['content'] }}" alt="{{ $element['label'] }}" style="max-width:100%;"></figure>

                        {{-- INLINE GAP --}}
                        @elseif (($element['type'] ?? '') === 'inline_gap')
                            @php
                                $gaps = $this->gapsFor($element);
                                $parts = preg_split('/--(\d+)--/', $element['content'] ?? '', -1, PREG_SPLIT_DELIM_CAPTURE);
                            @endphp
                            @if ($element['label'] ?? null)
                                <h4 style="font-weight:700;margin-bottom:0.5rem;">{{ $element['label'] }}</h4>
                            @endif
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
                                                $gapCorrect = null;
                                                $gapExpected = $gapDef['answer'] ?? '';
                                                if ($result) {
                                                    $gapDetail = collect($result['details'])->firstWhere('id', $element['id'].'.gap.'.$gapIdx);
                                                    if ($gapDetail) {
                                                        $gapCorrect = $gapDetail['correct'] ?? null;
                                                        $gapExpected = $gapDetail['expected'] ?? $gapExpected;
                                                    }
                                                }
                                                $gapCls = '';
                                                if ($result && $current !== '') {
                                                    $gapCls = $gapCorrect ? 'correct' : 'wrong';
                                                }
                                            @endphp
                                            <span class="inline-gap-pill @if($current) filled @endif">
                                                <select wire:model.live="answers.{{ $element['id'] }}.{{ $gapIdx }}" @disabled($result) @class(['cloze-select', 'correct' => $gapCls === 'correct', 'wrong' => $gapCls === 'wrong'])>
                                                    <option value="">--{{ $gapIdx }}--</option>
                                                    @foreach ($optList as $opt)
                                                        @php $optParts = explode('|', $opt, 2); $optKey = trim($optParts[0]); $optLabel = trim($optParts[1] ?? $optKey); @endphp
                                                        <option value="{{ $optKey }}">{{ $optLabel }}</option>
                                                    @endforeach
                                                </select>
                                                @if ($result && $current !== '')
                                                    <span class="feedback-badge {{ $gapCorrect ? 'ok' : 'fail' }}">{{ $gapCorrect ? '✓' : '✗ ' . $gapExpected }}</span>
                                                @endif
                                            </span>
                                        @endif
                                    @endif
                                @endforeach
                            </div>

                        {{-- INTERACTIVE TEXT --}}
                        @elseif (($element['type'] ?? '') === 'interactive_text')
                            @php
                                $parser = app(\App\Services\GapParser::class);
                                $segments = $parser->parse($element['content'] ?? '', $element['gaps'] ?? []);
                            @endphp
                            @if ($element['label'] ?? null)
                                <h4 style="font-weight:700;margin-bottom:0.5rem;">{{ $element['label'] }}</h4>
                            @endif
                            <div class="c1-passage" style="line-height: 2.2;">
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
                                        <span class="inline-gap-pill">
                                            @if ($isDropdown && count($optList) > 0)
                                                <select wire:model.live="answers.{{ $wireKey }}" class="cloze-select">
                                                    <option value="">{{ $current ?: '___' }}</option>
                                                    @foreach ($optList as $opt)
                                                        <option value="{{ $opt['value'] }}" @selected($current === $opt['value'])>{{ $opt['label'] }}</option>
                                                    @endforeach
                                                </select>
                                            @else
                                                <input type="text" wire:model.live="answers.{{ $wireKey }}" class="cloze-select" style="width:80px;" placeholder="___" value="{{ $current }}">
                                            @endif
                                        </span>
                                    @endif
                                @endforeach
                            </div>

                        {{-- MULTIPLE CHOICE --}}
                        @elseif (in_array($element['type'] ?? '', ['multiple_choice', 'true_false'], true))
                            @php $rawOptions = $this->optionsFor($element); @endphp
                            <div class="mc-question-row">
                                @if ($element['label'] ?? null)
                                    <div class="mc-question-header">{{ $element['label'] }}</div>
                                @endif
                                @if ($element['instruction'] ?? null)
                                    <p style="font-size:0.9rem;color:#666;margin-bottom:0.75rem;">{{ $element['instruction'] }}</p>
                                @endif
                                <div class="mc-options-list">
                                    @foreach ($rawOptions as $opt)
                                        @php
                                            $isSelected = ($answers[$element['id']] ?? '') === $opt;
                                            $detail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null;
                                            $optionCls = 'mc-option-item';
                                            if ($result && $isSelected) {
                                                $optionCls .= $detail && $detail['correct'] ? ' selected' : ' selected';
                                            } elseif ($isSelected) {
                                                $optionCls .= ' selected';
                                            }
                                        @endphp
                                        <label class="{{ $optionCls }}">
                                            <input type="radio" wire:model.live="answers.{{ $element['id'] }}" value="{{ $opt }}" @disabled($result) class="mc-radio-input">
                                            <span class="mc-option-bullet"></span>
                                            <span>{{ $opt }}</span>
                                            @if ($result)
                                                @php $det = collect($result['details'])->firstWhere('id', $element['id']); @endphp
                                                @if ($det && $opt === ($det['expected'] ?? '') && !$det['correct'])
                                                    <span class="feedback-badge fail">Richtige Antwort: {{ $det['expected'] }}</span>
                                                @endif
                                                @if ($det && $isSelected && $det['correct'])
                                                    <span class="feedback-badge ok">✓</span>
                                                @endif
                                            @endif
                                        </label>
                                    @endforeach
                                </div>
                            </div>

                        {{-- MATCHING --}}
                        @elseif (($element['type'] ?? '') === 'matching')
                            @php $matchOptions = $this->optionsFor($element); @endphp
                            <div class="mc-question-row">
                                @if ($element['label'] ?? null)
                                    <div class="mc-question-header">{{ $element['label'] }}</div>
                                @endif
                                @if (($element['type'] ?? '') === 'matching' && count($matchOptions) > 5)
                                    <div class="answer-sheet-row">
                                        <span class="question-num">{{ preg_replace('/[^0-9]/', '', $element['label']) ?: $loop->iteration }}</span>
                                        <select wire:model.live="answers.{{ $element['id'] }}" class="matching-select" @disabled($result)>
                                            <option value="">—</option>
                                            @foreach ($matchOptions as $opt)
                                                <option value="{{ $opt }}">{{ $opt }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                @else
                                    <div class="bubble-group">
                                        @foreach ($matchOptions as $option)
                                            @php
                                                $isSelected = ($answers[$element['id']] ?? '') === $option;
                                                $detail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null;
                                                $bubbleCls = 'bubble';
                                                if ($result) {
                                                    if ($isSelected) {
                                                        $bubbleCls .= $detail && $detail['correct'] ? ' selected correct' : ' selected wrong';
                                                    } elseif ($detail && $option === $detail['expected']) {
                                                        $bubbleCls .= ' correct-key';
                                                    }
                                                } elseif ($isSelected) {
                                                    $bubbleCls .= ' selected';
                                                }
                                            @endphp
                                            <label @class([$bubbleCls])>
                                                <input type="radio" wire:model.live="answers.{{ $element['id'] }}" value="{{ $option }}" @disabled($result)>
                                                <span>{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                        {{-- FILL BLANK --}}
                        @elseif (($element['type'] ?? '') === 'fill_blank')
                            @php $fbOptions = $this->optionsFor($element); @endphp
                            <div class="mc-question-row">
                                @if ($element['label'] ?? null)
                                    <div class="mc-question-header">{{ $element['label'] }}</div>
                                @endif
                                @if ($fbOptions === [])
                                    @php
                                        $selectedVal = $answers[$element['id']] ?? '';
                                        $detail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null;
                                        $fbCorrect = $detail ? $detail['correct'] : null;
                                    @endphp
                                    <div class="answer-sheet-row">
                                        <input type="text" wire:model.live="answers.{{ $element['id'] }}" class="fill-blank-input {{ $result && $selectedVal !== '' ? ($fbCorrect ? 'correct' : 'wrong') : '' }}" placeholder="Antwort" @disabled($result) style="width: 100%;">
                                        @if ($result && $selectedVal !== '')
                                            <span class="feedback-badge {{ $fbCorrect ? 'ok' : 'fail' }}">{{ $fbCorrect ? '✓' : '✗ ' . ($detail['expected'] ?? '') }}</span>
                                        @endif
                                    </div>
                                @else
                                    <div class="bubble-group">
                                        @foreach ($fbOptions as $option)
                                            @php
                                                $isSelected = ($answers[$element['id']] ?? '') === $option;
                                                $detail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null;
                                                $bubbleCls = 'bubble';
                                                if ($result) {
                                                    if ($isSelected) {
                                                        $bubbleCls .= $detail && $detail['correct'] ? ' selected correct' : ' selected wrong';
                                                    } elseif ($detail && $option === $detail['expected']) {
                                                        $bubbleCls .= ' correct-key';
                                                    }
                                                } elseif ($isSelected) {
                                                    $bubbleCls .= ' selected';
                                                }
                                            @endphp
                                            <label @class([$bubbleCls])>
                                                <input type="radio" wire:model.live="answers.{{ $element['id'] }}" value="{{ $option }}" @disabled($result)>
                                                <span>{{ $option }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                        {{-- WRITING --}}
                        @elseif (($element['type'] ?? '') === 'writing')
                            @php $writeDetail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null; @endphp
                            <div class="writing-section @if($result) marked @endif" style="margin-top: 2rem;">
                                <label class="writing-label" style="font-weight: 700; display: block; margin-bottom: 0.5rem;">{{ $element['label'] ?? $element['instruction'] ?? 'Schreiben' }}</label>
                                <div class="writing-textarea-lined">
                                    <textarea wire:model.live="answers.{{ $element['id'] }}" placeholder="Ihre Antwort..." @disabled($result) rows="6"></textarea>
                                </div>
                                @if ($writeDetail)
                                    <span class="writing-score">{{ $writeDetail['points'] }} / {{ $writeDetail['max_points'] }} Punkte</span>
                                    @if ($writeDetail['feedback'] ?? '')
                                        <div style="margin-top:0.75rem;padding:0.75rem;background:#f9f9f9;border-left:3px solid #d8232a;border-radius:4px;font-size:0.85rem;">
                                            <strong>Feedback:</strong> {{ $writeDetail['feedback'] }}
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endif
                    @endforeach

                    {{-- Result display --}}
                    @if ($result && !$sectionLocked)
                        <hr class="c1-divider">
                        <div style="background: #ffffff; border: 2px solid #1a1a1a; border-radius: 8px; padding: 2.5rem; max-width: 600px; margin: 2rem auto 0; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #666; display: block; margin-bottom: 0.5rem;">Ergebnis</span>
                            <div style="display: flex; justify-content: center; gap: 1.5rem; margin-bottom: 1rem;">
                                <div><span style="font-size: 0.8rem; color: #666;">Erreicht</span><br><strong style="font-size: 2rem;">{{ $result['earned'] }}</strong></div>
                                <div><span style="font-size: 0.8rem; color: #666;">Mögliche</span><br><strong style="font-size: 2rem;">{{ $result['possible'] }}</strong></div>
                            </div>
                            <div style="font-size: 1.2rem; font-weight: 700; margin-bottom: 1.5rem;">{{ $result['percentage'] }}%</div>

                            <div style="background-color: {{ ($result['percentage'] ?? 0) >= 60 ? '#e6f4ea' : '#fce8e6' }}; padding: 0.75rem; border-radius: 4px; font-weight: 700; font-size: 1.1rem; color: {{ ($result['percentage'] ?? 0) >= 60 ? '#137333' : '#c5221f' }}; margin-bottom: 1.5rem;">
                                Status: {{ ($result['percentage'] ?? 0) >= 60 ? 'Bestanden' : 'Nicht bestanden' }}
                            </div>

                            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                                <button type="button" wire:click="repeat" style="background-color: #137333; color: #ffffff; border: none; padding: 0.6rem 1.5rem; border-radius: 9999px; font-weight: 700; font-size: 0.85rem; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em;">
                                    Prüfung wiederholen
                                </button>
                                <button type="button" wire:click="backToChapters" style="background-color: #1a1a1a; color: #ffffff; border: none; padding: 0.6rem 1.5rem; border-radius: 9999px; font-weight: 700; font-size: 0.85rem; cursor: pointer; text-transform: uppercase; letter-spacing: 0.05em;">
                                    Zurück zur Übersicht
                                </button>
                            </div>

                            @if (!empty($result['details']))
                                <div style="margin-top: 1.5rem; text-align: left; border-top: 1px solid #e5e5e5; padding-top: 1rem;">
                                    @foreach ($result['details'] as $detail)
                                        <div style="display: flex; justify-content: space-between; padding: 0.35rem 0; font-size: 0.85rem; border-bottom: 1px solid #f5f5f5;">
                                            <span style="color: #666;">{{ $detail['label'] }}</span>
                                            <span style="color: {{ ($detail['correct'] ?? false) ? '#137333' : '#c5221f' }};">
                                                {{ $detail['points'] }}/{{ $detail['max_points'] }}
                                                {{ ($detail['correct'] ?? false) ? '✓' : '✗' }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @else
                <div class="premium-document">
                    <h2>Section not found.</h2>
                </div>
            @endif
        </div>

        {{-- Next-section popup --}}
        @if ($result && $nextSectionIndex !== null)
            @php $nextSec = $sections[$nextSectionIndex] ?? null; @endphp
            @if ($nextSec)
                <div style="position:fixed;bottom:1rem;left:1rem;z-index:1002;background:#ffffff;border:1px solid #e5e5e5;border-radius:8px;padding:0.6rem 1rem;box-shadow:0 4px 20px rgba(0,0,0,0.08);display:flex;align-items:center;gap:0.75rem;animation:slideUp 0.3s ease;">
                    <span style="font-size:0.78rem;color:#666;">
                        Nächstes: <strong style="color:#1a1a1a;">{{ $nextSec['title'] }}</strong>
                    </span>
                    <button type="button" wire:click="startSection({{ $nextSectionIndex }})" style="background:#1a1a1a;color:#fff;border:none;padding:0.35rem 0.75rem;border-radius:4px;font-size:0.72rem;font-weight:700;cursor:pointer;font-family:inherit;transition:opacity 0.15s;">
                        Starten →
                    </button>
                </div>
            @endif
        @endif
    @endif

    <script>
        window.examResultDetails = @json($result ? $result['details'] : null);

        function applyExplanations() {
            if (!window.examResultDetails) return;

            window.examResultDetails.forEach(detail => {
                const elId = detail.id;

                let targetEl = document.querySelector(`[wire\\:model\\.live="answers.${elId}"], [wire\\:model="answers.${elId}"], [name="${elId}"], [wire\\:model\\.change="answers.${elId}"]`);

                if (!targetEl) {
                    const allInputs = document.querySelectorAll('input, select, textarea');
                    for (let input of allInputs) {
                        const wireModel = input.getAttribute('wire:model') || input.getAttribute('wire:model.live') || input.getAttribute('wire:model.change');
                        if (wireModel && (wireModel === `answers.${elId}` || wireModel.startsWith(`answers.${elId}.`))) {
                            targetEl = input;
                            break;
                        }
                    }
                }

                if (targetEl) {
                    let wrapper = targetEl.closest('.cloze-select-wrapper') || targetEl.closest('.mc-option-item') || targetEl.closest('.mc-question-row') || targetEl.closest('tr') || targetEl.parentElement;

                    if (wrapper && !wrapper.dataset.explanationAttached) {
                        wrapper.dataset.explanationAttached = "true";
                        wrapper.style.cursor = "pointer";

                        wrapper.classList.add('hover:bg-yellow-50/50', 'transition-colors');

                        const explDiv = document.createElement('div');
                        explDiv.className = 'explanation-box mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-500 text-xs text-stone-750 rounded shadow-sm hidden text-left';
                        explDiv.style.fontFamily = "'Plus Jakarta Sans', sans-serif";

                        let explanationText = detail.explanation || '';
                        if (!explanationText) {
                            explanationText = detail.correct ? 'Richtig! Gut gemacht.' : 'Leider falsch.';
                        }

                        explDiv.innerHTML = `
                            <div class="font-bold text-yellow-800 mb-1" style="font-weight: 700; color: #854d0e;">Erklärung & Feedback:</div>
                            <p class="leading-relaxed" style="margin: 0; color: #451a03;">${explanationText}</p>
                            ${!detail.correct && detail.expected ? `<div class="mt-1 text-green-700 font-bold" style="margin-top: 0.25rem; color: #15803d; font-weight: 700;">Richtige Antwort: ${detail.expected}</div>` : ''}
                        `;

                        if (wrapper.tagName === 'TR') {
                            const newTr = document.createElement('tr');
                            newTr.className = 'hidden';
                            const newTd = document.createElement('td');
                            newTd.colSpan = wrapper.cells.length;
                            newTd.appendChild(explDiv);
                            newTr.appendChild(newTd);

                            wrapper.after(newTr);
                            explDiv.classList.remove('hidden');

                            wrapper.addEventListener('click', () => {
                                newTr.classList.toggle('hidden');
                            });
                        } else {
                            wrapper.appendChild(explDiv);
                            wrapper.addEventListener('click', (e) => {
                                if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.tagName === 'SELECT' || e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') return;
                                explDiv.classList.toggle('hidden');
                            });
                        }
                    }
                }
            });
        }

        document.addEventListener('DOMContentLoaded', applyExplanations);
        document.addEventListener('livewire:navigated', applyExplanations);

        if (typeof Livewire !== 'undefined') {
            Livewire.hook('request', ({ respond }) => {
                respond(() => {
                    setTimeout(applyExplanations, 250);
                });
            });
        }
    </script>
</div>
