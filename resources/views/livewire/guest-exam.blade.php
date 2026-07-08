<div class="guest-exam-shell">
    <style>
        .free-left-nav { width: 260px; min-width: 260px; background: #1a1a1a; display: flex; flex-direction: column; border-right: 1px solid #2a2a2a; overflow-y: auto; }
        .free-left-nav .nav-header { padding: 1rem 1.25rem; border-bottom: 1px solid #2a2a2a; }
        .free-left-nav .nav-header h3 { font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.1em; color: #a0a0a0; margin: 0 0 0.25rem; }
        .free-left-nav .nav-header h2 { font-size: 0.95rem; font-weight: 700; color: #ffffff; margin: 0; }
        .free-left-nav .nav-section-list { flex: 1; padding: 0.75rem 0; }
        .free-left-nav .nav-section-item { display: flex; align-items: center; gap: 0.75rem; padding: 0.75rem 1.25rem; cursor: pointer; transition: all 0.15s; border-left: 3px solid transparent; text-align: left; width: 100%; background: none; border-right: none; border-top: none; border-bottom: none; font-family: inherit; }
        .free-left-nav .nav-section-item:hover { background: #252525; border-left-color: #555; }
        .free-left-nav .nav-section-item.active { background: #252525; border-left-color: #eab308; }
        .free-left-nav .nav-section-item .nav-icon { width: 24px; height: 24px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.7rem; font-weight: 700; flex-shrink: 0; }
        .free-left-nav .nav-section-item .nav-icon.new { background: #333; color: #888; }
        .free-left-nav .nav-section-item .nav-icon.completed { background: #166534; color: #fff; }
        .free-left-nav .nav-section-item .nav-icon.locked { background: #7f1d1d; color: #fca5a5; }
        .free-left-nav .nav-section-item .nav-icon.in-progress { background: #a16207; color: #fef08a; }
        .free-left-nav .nav-section-item .nav-label { flex: 1; font-size: 0.82rem; color: #ccc; text-align: left; }
        .free-left-nav .nav-section-item .nav-duration { font-size: 0.7rem; color: #666; }
        .free-left-nav .nav-section-item.locked { opacity: 0.4; cursor: not-allowed; }
        .free-left-nav .nav-footer { padding: 0.75rem 1.25rem; border-top: 1px solid #2a2a2a; }
        .free-left-nav .nav-footer button { width: 100%; padding: 0.6rem; font-size: 0.78rem; font-weight: 600; border-radius: 4px; cursor: pointer; transition: all 0.15s; }

        .free-notification { position: fixed; top: 1rem; right: 1rem; z-index: 1000; background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 0.6rem 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.4); display: flex; align-items: center; gap: 0.5rem; }
        .free-notification svg { width: 16px; height: 16px; color: #eab308; flex-shrink: 0; }
        .free-notification span { font-size: 0.78rem; color: #aaa; }
        .free-notification strong { color: #eab308; }

        .free-exam-layout { display: flex; min-height: 85vh; background: #111; }
        .free-exam-content { flex: 1; display: flex; flex-direction: column; overflow-y: auto; }
        .free-exam-workbench { flex: 1; padding: 2rem; }
        .free-exam-workbench .telc-paper { max-width: 800px; margin: 0 auto; }

        .level-tabs { display: flex; justify-content: center; gap: 0.5rem; padding: 1.5rem 1rem; background: #111; border-bottom: 1px solid #222; }
        .level-tabs button { padding: 0.4rem 1.2rem; border-radius: 999px; font-size: 0.82rem; font-weight: 700; cursor: pointer; transition: all 0.15s; border: none; font-family: inherit; }
        .level-tabs button.active { background: #eab308; color: #1a1a1a; }
        .level-tabs button.inactive { background: #222; color: #666; }
        .level-tabs button.inactive:hover { background: #2a2a2a; color: #999; }

        .free-result-panel { max-width: 600px; margin: 2rem auto; background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 2rem; text-align: center; }
        .free-result-panel .score { font-size: 2.5rem; font-weight: 800; color: #fff; }
        .free-result-panel .score-label { font-size: 0.85rem; color: #888; }

        .free-timer-widget { position: fixed; top: 1rem; right: 1rem; z-index: 1001; background: #1a1a1a; border: 1px solid #333; border-radius: 8px; padding: 0.6rem 1rem; box-shadow: 0 4px 20px rgba(0,0,0,0.4); display: flex; align-items: center; gap: 0.5rem; }
        .free-timer-widget svg { width: 16px; height: 16px; color: #d8232a; flex-shrink: 0; }
        .free-timer-widget .timer-text { font-size: 0.85rem; font-weight: 700; color: #d8232a; font-variant-numeric: tabular-nums; }
        .free-timer-widget .submit-inline { background: #d8232a; color: #fff; border: none; padding: 0.35rem 0.75rem; border-radius: 4px; font-size: 0.72rem; font-weight: 700; cursor: pointer; font-family: inherit; transition: opacity 0.15s; }
        .free-timer-widget .submit-inline:hover { opacity: 0.85; }

        .premium-document {
            max-width: 800px; margin: 0 auto; background: #ffffff; border: 1px solid #d9dde5;
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

        .writing-textarea-lined { width: 100%; min-height: 350px; padding: 1rem; font-family: inherit; font-size: 1rem; line-height: 2rem; border: 1px solid #d9dde5; background: transparent; background-image: linear-gradient(transparent, transparent 1.95rem, #cbcbcb 1.95rem, #cbcbcb 2rem); background-size: 100% 2rem; color: #1a1a1a; resize: vertical; outline: none; }

        .hv3-lined-input { border: none; border-bottom: 1.5px solid #cbcbcb; background: transparent; font-family: inherit; font-size: 0.95rem; font-weight: 600; color: #d8232a; width: 100%; padding: 0.2rem 0; outline: none; }
        .hv3-lined-input:focus { border-bottom-color: #1a1a1a; }

        .feedback-badge { display: inline-block; font-size: 0.7rem; font-weight: 700; padding: 0.1rem 0.4rem; border-radius: 3px; margin-left: 0.25rem; text-transform: uppercase; white-space: nowrap; }
        .feedback-badge.ok { background: #e6f4ea; color: #137333; }
        .feedback-badge.fail { background: #fce8e6; color: #c5221f; }

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

        .exam-layout { display: flex; gap: 0; max-width: 1200px; margin: 0 auto; }
        .exam-paper-column { flex: 1; min-width: 0; }
        .exam-answer-column { width: 300px; min-width: 300px; margin-left: 2rem; }
        .answer-sheet { background: #fff; border: 1px solid #d9dde5; border-radius: 6px; padding: 1.5rem; position: sticky; top: 1rem; }
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
        .answer-mark { font-size: 0.75rem; font-weight: 700; }
        .answer-mark.correct { color: #137333; }
        .answer-mark.wrong { color: #c5221f; }
        .writing-section { margin-top: 1.5rem; }
        .writing-label { font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 0.5rem; }
        .writing-textarea { width: 100%; min-height: 200px; padding: 0.75rem; border: 1px solid #d9dde5; border-radius: 4px; font-family: inherit; font-size: 0.95rem; line-height: 1.6; resize: vertical; outline: none; }

        .speaking-task { border: 1px solid #e5e5e5; padding: 1.5rem; border-radius: 6px; background: #fff; margin-bottom: 2rem; }
        .speaking-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; }
        .speaking-badge { font-weight: 700; font-size: 0.85rem; text-transform: uppercase; color: #d8232a; }
        .speaking-points { font-size: 0.8rem; color: #666; }
        .speaking-prompt { background: #fafafa; border-left: 3px solid #1a1a1a; padding: 0.85rem 1rem; margin-bottom: 1rem; font-size: 0.9rem; line-height: 1.5; color: #4a4a4a; }
        .speaking-controls { margin-bottom: 0.75rem; }
        .speaking-record-btn { background: #d8232a; color: #fff; border: none; padding: 0.7rem 1.2rem; border-radius: 4px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; font-size: 0.85rem; font-family: inherit; }
        .speaking-record-btn:hover { opacity: 0.85; }
        .speaking-record-btn.recording { background: #1a1a1a; }
        .speaking-recording-indicator { color: #d8232a; font-weight: 600; font-size: 0.85rem; margin-left: 0.75rem; }
        .speaking-transcript { margin-bottom: 0.75rem; }
        .speaking-transcript label { font-size: 0.8rem; font-weight: 600; display: block; margin-bottom: 0.35rem; color: #555; }
        .speaking-textarea { width: 100%; min-height: 100px; padding: 0.75rem; border: 1px solid #d9dde5; border-radius: 4px; font-family: inherit; font-size: 0.9rem; line-height: 1.5; resize: vertical; outline: none; }
        .speaking-actions { margin-top: 0.75rem; }
        .speaking-feedback { margin-top: 1rem; padding: 1rem; background: #f7f9fa; border: 1px solid #e5e5e5; border-radius: 4px; }
        .speaking-feedback h4 { font-size: 0.8rem; font-weight: 700; text-transform: uppercase; color: #555; margin-bottom: 0.5rem; }
        .speaking-feedback .feedback-content { font-size: 0.9rem; line-height: 1.5; color: #333; white-space: pre-wrap; }
        .speaking-locked { text-align: center; padding: 3rem 1rem; color: #888; }
        .speaking-locked svg { width: 48px; height: 48px; margin: 0 auto 1rem; color: #555; }

        .audio-player { margin: 1rem 0; }
        .audio-player audio { width: 100%; }

        .inline-gap-text { font-size: 1rem; line-height: 2.2; }
        .inline-gap-pill { display: inline-block; position: relative; margin: 0 0.15rem; }
        .inline-gap-pill select { padding: 0.15rem 0.3rem; border: 1px solid #ccc; border-radius: 3px; font-size: 0.9rem; background: #fff; cursor: pointer; }
        .inline-gap-pill.filled select { border-color: #1a1a1a; font-weight: 600; background: #fafafa; }

        .free-back-btn { display: inline-flex; align-items: center; gap: 0.35rem; color: #888; font-size: 0.8rem; cursor: pointer; background: none; border: none; font-family: inherit; padding: 0.5rem 0; transition: color 0.15s; }
        .free-back-btn:hover { color: #eab308; }
        .free-back-btn svg { width: 14px; height: 14px; }

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
        {{-- Section exam view with left nav --}}
        @php
            $currentSection = $sections[$activeSectionIndex] ?? null;
            $theme = $template['theme'] ?? [];
            $minutes = intdiv($remainingSeconds, 60);
            $seconds = $remainingSeconds % 60;
            $hasInlineGap = $currentSection && collect($currentSection['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'inline_gap');
            $hasInteractiveText = $currentSection && collect($currentSection['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'interactive_text');
            $hasSpeaking = $currentSection && collect($currentSection['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'speaking');
            $hasAudio = $currentSection && collect($currentSection['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'audio');
            $hasWriting = $currentSection && collect($currentSection['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'writing');
            $sectionLocked = $this->isNoRetrySection($activeSectionIndex) && $this->isSectionCompleted($activeSectionIndex);
        @endphp

        <div class="free-exam-layout">
            {{-- Left nav sidebar --}}
            <nav class="free-left-nav">
                <div class="nav-header">
                    <h3>{{ $template['level'] ?? '' }} Exam</h3>
                    <h2>{{ $template['title'] ?? '' }}</h2>
                </div>
                <div class="nav-section-list">
                    @foreach ($sections as $idx => $sec)
                        @php
                            $secStatus = $this->sectionStatus($idx);
                            $secIconType = match($secStatus) {
                                'completed' => 'completed',
                                'locked' => 'locked',
                                'in-progress' => 'in-progress',
                                default => 'new'
                            };
                            $secIconLabel = match($secStatus) {
                                'completed' => '✓',
                                'locked' => '!',
                                'in-progress' => '→',
                                default => (string)($idx + 1)
                            };
                            $isActive = $activeSectionIndex === $idx;
                        @endphp
                        <button type="button"
                            wire:click="startSection({{ $idx }})"
                            @disabled($secStatus === 'locked')
                            @class([
                                'nav-section-item', 'active' => $isActive,
                                'locked' => $secStatus === 'locked'
                            ])>
                            <span class="nav-icon {{ $secIconType }}">{{ $secIconLabel }}</span>
                            <span class="nav-label">{{ $sec['title'] }}</span>
                            <span class="nav-duration">{{ $sec['minutes'] }}min</span>
                        </button>
                    @endforeach
                </div>
                <div class="nav-footer">
                    <button type="button" wire:click="backToChapters" style="background:#333;color:#ccc;border:none;font-family:inherit;">
                        ← Back to chapters
                    </button>
                </div>
            </nav>

            {{-- Main content area --}}
            <div class="free-exam-content">
                {{-- Timer + Submit floating widget --}}
                @if ($currentSection && !$result)
                    <div class="free-timer-widget" wire:poll.1s="tick">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <polyline points="12 6 12 12 16 14"></polyline>
                        </svg>
                        <span class="timer-text">{{ sprintf('%02d:%02d', $minutes, $seconds) }}</span>
                        <button type="button" wire:click="submit" class="submit-inline">Submit</button>
                    </div>
                @elseif ($currentSection && $result)
                    <div class="free-timer-widget">
                        <button type="button" wire:click="backToChapters" style="background:#333;color:#ccc;border:none;padding:0.35rem 0.75rem;border-radius:4px;font-size:0.72rem;font-weight:700;cursor:pointer;font-family:inherit;">← Back</button>
                    </div>
                @endif

                <div class="free-exam-workbench">
                    @if ($sectionLocked)
                        <div class="speaking-locked">
                            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                            <h3 class="text-lg font-bold text-zinc-400 mb-2">Section completed</h3>
                            <p class="text-zinc-600 text-sm">This section cannot be retaken.</p>
                        </div>
                    @elseif ($currentSection)
                        {{-- Render section content matching paid exam styles --}}

                        {{-- === INTERACTIVE TEXT VIEW (like paid) --}}
                        @if ($hasInteractiveText)
                            <div class="exam-layout">
                                <div class="exam-paper-column">
                                    @foreach ($currentSection['elements'] as $element)
                                        @if (($element['type'] ?? '') === 'interactive_text')
                                            <div class="premium-document">
                                                <h3 style="font-size:1.1rem;font-weight:700;margin-bottom:1rem;">{{ $element['label'] }}</h3>
                                                <div class="c1-passage">{!! nl2br(e($element['content'] ?? '')) !!}</div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <aside class="exam-answer-column">
                                    <div class="answer-sheet">
                                        <div class="answer-sheet-header">
                                            <div>
                                                <span class="answer-sheet-title">Antwortbogen</span>
                                                <span class="answer-sheet-section">{{ $currentSection['title'] }}</span>
                                            </div>
                                            @if ($hasAudio)
                                                @foreach ($currentSection['elements'] as $el)
                                                    @if (($el['type'] ?? '') === 'audio')
                                                        <div class="audio-player"><audio controls src="{{ $el['content'] }}"></audio></div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="answer-sheet-grid">
                                            @foreach ($currentSection['elements'] as $element)
                                                @if (in_array($element['type'] ?? '', ['multiple_choice', 'matching', 'fill_blank', 'true_false'], true))
                                                    @php
                                                        $options = $this->optionsFor($element);
                                                        $qNum = preg_replace('/[^0-9]/', '', $element['label']) ?: $loop->iteration;
                                                        $isMatching = ($element['type'] ?? '') === 'matching';
                                                        $detail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null;
                                                        $selectedVal = $answers[$element['id']] ?? '';
                                                    @endphp
                                                    <div class="answer-sheet-row">
                                                        <span class="question-num">{{ $qNum }}</span>
                                                        @if (($element['type'] ?? '') === 'fill_blank' && $options === [])
                                                            @php $fbCorrect = $detail ? $detail['correct'] : null; @endphp
                                                            <input type="text" wire:model.live="answers.{{ $element['id'] }}" class="fill-blank-input {{ $result && $selectedVal !== '' ? ($fbCorrect ? 'correct' : 'wrong') : '' }}" placeholder="Antwort" @disabled($result)>
                                                            @if ($result && $selectedVal !== '')
                                                                <span class="feedback-badge {{ $fbCorrect ? 'ok' : 'fail' }}">{{ $fbCorrect ? '✓' : '✗ ' . $detail['expected'] }}</span>
                                                            @endif
                                                        @elseif ($isMatching && count($options) > 5)
                                                            @php $matCorrect = $detail ? $detail['correct'] : null; @endphp
                                                            <select wire:model.live="answers.{{ $element['id'] }}" class="matching-select {{ $result && $selectedVal !== '' ? ($matCorrect ? 'correct' : 'wrong') : '' }}" @disabled($result)>
                                                                <option value="">—</option>
                                                                @foreach ($options as $opt)
                                                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if ($result && $selectedVal !== '')
                                                                <span class="feedback-badge {{ $matCorrect ? 'ok' : 'fail' }}">{{ $matCorrect ? '✓' : '✗ ' . $detail['expected'] }}</span>
                                                            @endif
                                                        @else
                                                            <div class="bubble-group">
                                                                @foreach ($options as $option)
                                                                    @php
                                                                        $isSelected = $selectedVal === $option;
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
                                                @endif
                                            @endforeach
                                        </div>
                                        @foreach ($currentSection['elements'] as $element)
                                            @if (($element['type'] ?? '') === 'writing')
                                                @php $writeDetail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null; @endphp
                                                <div class="writing-section @if($result) marked @endif">
                                                    <label class="writing-label">{{ $element['label'] }}</label>
                                                    <textarea wire:model.live="answers.{{ $element['id'] }}" class="writing-textarea" placeholder="Schreibe deinen Text hier..." @disabled($result)></textarea>
                                                    @if ($writeDetail)
                                                        <span class="writing-score">{{ $writeDetail['points'] }} / {{ $writeDetail['max_points'] }} Punkte</span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </aside>
                            </div>

                        {{-- === INLINE GAP VIEW (like paid) --}}
                        @elseif ($hasInlineGap)
                            <div class="telc-paper booklet-paper premium-document">
                                <div class="telc-paper-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
                                    <div>
                                        <p style="font-size:0.75rem;color:#666;margin:0;">{{ $template['title'] ?? '' }}</p>
                                        <h2 style="font-size:1.3rem;font-weight:700;margin:0.25rem 0 0;">{{ $currentSection['title'] }}</h2>
                                    </div>
                                    <span style="font-weight:700;color:#888;">{{ $template['level'] ?? '' }}</span>
                                </div>
                                <div class="telc-paper-body">
                                    @foreach ($currentSection['elements'] as $element)
                                        @if (($element['type'] ?? '') === 'inline_gap')
                                            @php
                                                $gaps = $this->gapsFor($element);
                                                $parts = preg_split('/--(\d+)--/', $element['content'] ?? '', -1, PREG_SPLIT_DELIM_CAPTURE);
                                            @endphp
                                            <h3 style="font-size:1rem;font-weight:700;margin-bottom:0.75rem;">{{ $element['label'] }}</h3>
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
                                                             @php
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
                                        @endif
                                    @endforeach
                                </div>
                                @if ($result)
                                    <div style="background:#fff;border:2px solid #1a1a1a;border-radius:8px;padding:2rem;max-width:500px;margin:2rem auto 0;text-align:center;">
                                        <span style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;color:#666;display:block;margin-bottom:0.5rem;">Ergebnis</span>
                                        <div style="display:flex;justify-content:center;gap:1.5rem;margin-bottom:1rem;">
                                            <div><span style="font-size:0.8rem;color:#666;">Erreicht</span><br><strong style="font-size:2rem;">{{ $result['earned'] }}</strong></div>
                                            <div><span style="font-size:0.8rem;color:#666;">Mögliche</span><br><strong style="font-size:2rem;">{{ $result['possible'] }}</strong></div>
                                        </div>
                                        <div style="font-size:1.2rem;font-weight:700;">{{ $result['percentage'] }}%</div>
                                    </div>
                                @endif
                            </div>

                        {{-- === SPEAKING VIEW (like paid) --}}
                        @elseif ($hasSpeaking)
                            <div class="telc-paper booklet-paper premium-document">
                                <div class="telc-paper-header" style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
                                    <div>
                                        <p style="font-size:0.75rem;color:#666;margin:0;">{{ $template['title'] ?? '' }}</p>
                                        <h2 style="font-size:1.3rem;font-weight:700;margin:0.25rem 0 0;">{{ $currentSection['title'] }}</h2>
                                    </div>
                                    <span style="font-weight:700;color:#888;">{{ $template['level'] ?? '' }}</span>
                                </div>
                                <div class="telc-paper-body">
                                    @foreach ($currentSection['elements'] as $element)
                                        @if (($element['type'] ?? '') === 'speaking')
                                            @php
                                                $elId = $element['id'];
                                                $elCompleted = in_array($elId, $this->completedSectionIds, true);
                                            @endphp
                                            @if ($elCompleted)
                                                @php $spDetail = $result ? collect($result['details'])->firstWhere('id', $elId) : null; @endphp
                                                <div class="speaking-locked">
                                                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                    </svg>
                                                    <h3 class="text-lg font-bold text-zinc-600 mb-2">Abgeschlossen</h3>
                                                    <p class="text-zinc-500 text-sm">Dieser Abschnitt kann nicht wiederholt werden.</p>
                                                    @if ($spDetail)
                                                        <div style="margin-top:0.75rem;font-size:1rem;font-weight:700;color:#eab308;">{{ $spDetail['points'] }} / {{ $spDetail['max_points'] }} Punkte</div>
                                                    @endif
                                                </div>
                                            @elseif (!$result)
                                                <div class="speaking-task" x-data="{
                                                    isRecording: false, recognition: null, interimText: '',
                                                    initRecognition() {
                                                        const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                                                        if (!SR) return;
                                                        this.recognition = new SR(); this.recognition.lang = 'de-DE';
                                                        this.recognition.continuous = true; this.recognition.interimResults = true;
                                                        this.recognition.onresult = (e) => { let t = ''; for (let i = e.resultIndex; i < e.results.length; i++) t += e.results[i][0].transcript; this.interimText = t; };
                                                        this.recognition.onerror = () => this.stopRecording();
                                                    },
                                                    startRecording() { if (!this.recognition) this.initRecognition(); if (!this.recognition) return; this.isRecording = true; this.recognition.start(); },
                                                    stopRecording() { this.isRecording = false; if (this.recognition) this.recognition.stop(); },
                                                    toggleRecording() { this.isRecording ? this.stopRecording() : this.startRecording(); },
                                                    sendToAi(id) { $wire.set('answers.' + id, this.interimText); $wire.call('submit'); }
                                                }">
                                                    <div class="speaking-header">
                                                        <span class="speaking-badge">{{ $element['label'] }}</span>
                                                        <span class="speaking-points">{{ $element['points'] ?? 25 }} Punkte</span>
                                                    </div>
                                                    <div class="speaking-prompt"><p>{{ $element['content'] }}</p></div>
                                                    <div class="speaking-controls">
                                                        <button type="button" @click="toggleRecording" class="speaking-record-btn" :class="{ recording: isRecording }">
                                                            <template x-if="!isRecording"><span>🎤 Antwort aufnehmen</span></template>
                                                            <template x-if="isRecording"><span>⏹ Aufnahme stoppen</span></template>
                                                        </button>
                                                        <template x-if="isRecording"><span class="speaking-recording-indicator">● Aufnahme läuft...</span></template>
                                                    </div>
                                                    <div class="speaking-transcript">
                                                        <label>Transkription / Antwort:</label>
                                                        <textarea x-model="interimText" wire:model.live="answers.{{ $elId }}" class="speaking-textarea" placeholder="Deine transkribierte Antwort erscheint hier."></textarea>
                                                    </div>
                                                    <div class="speaking-actions">
                                                        <button type="button" @click="sendToAi('{{ $elId }}')" class="telc-button primary">
                                                            <span>Absenden und bewerten</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            @else
                                                @php $spDetail = $result ? collect($result['details'])->firstWhere('id', $elId) : null; @endphp
                                                <div class="speaking-task" style="opacity:0.8;">
                                                    <div class="speaking-header">
                                                        <span class="speaking-badge">{{ $element['label'] }}</span>
                                                        <span class="speaking-points">{{ $element['points'] ?? 25 }} Punkte</span>
                                                    </div>
                                                    <div class="speaking-prompt"><p>{{ $element['content'] }}</p></div>
                                                    <div class="speaking-transcript">
                                                        <label>Deine Antwort:</label>
                                                        <textarea class="speaking-textarea" disabled>{{ $answers[$elId] ?? '' }}</textarea>
                                                    </div>
                                                    @if ($spDetail)
                                                        <div class="speaking-feedback">
                                                            <h4>Bewertung</h4>
                                                            <div class="feedback-content">{{ $spDetail['points'] }} / {{ $spDetail['max_points'] }} Punkte</div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                        {{-- === DEFAULT VIEW with answer sheet (like paid) --}}
                        @else
                            <div class="exam-layout">
                                <div class="exam-paper-column">
                                    <div class="premium-document">
                                        <div class="c1-badge">{{ $template['level'] ?? '' }}</div>
                                        <h1 class="c1-title">ERFOLG</h1>
                                        <p class="c1-subtitle">Free Exam — {{ $currentSection['title'] }}</p>

                                        @if ($currentSection['elements'] ?? false)
                                            @foreach ($currentSection['elements'] as $element)
                                                @if (($element['type'] ?? '') === 'text')
                                                    <h4 style="font-weight:700;margin-bottom:0.5rem;">{{ $element['label'] }}</h4>
                                                    <p style="margin-bottom:1rem;">{!! nl2br(e($element['content'])) !!}</p>
                                                @elseif (($element['type'] ?? '') === 'image')
                                                    <figure style="margin:1rem 0;"><img src="{{ $element['content'] }}" alt="{{ $element['label'] }}" style="max-width:100%;"></figure>
                                                @endif
                                            @endforeach
                                        @endif

                                        @if ($result)
                                            <div style="background:#fff;border:2px solid #1a1a1a;border-radius:8px;padding:2rem;max-width:500px;margin:2rem auto 0;text-align:center;">
                                                <span style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;color:#666;display:block;margin-bottom:0.5rem;">Ergebnis</span>
                                                <div style="display:flex;justify-content:center;gap:1.5rem;margin-bottom:1rem;">
                                                    <div><span style="font-size:0.8rem;color:#666;">Erreicht</span><br><strong style="font-size:2rem;">{{ $result['earned'] }}</strong></div>
                                                    <div><span style="font-size:0.8rem;color:#666;">Mögliche</span><br><strong style="font-size:2rem;">{{ $result['possible'] }}</strong></div>
                                                </div>
                                                <div style="font-size:1.2rem;font-weight:700;">{{ $result['percentage'] }}%</div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <aside class="exam-answer-column">
                                    <div class="answer-sheet">
                                        <div class="answer-sheet-header">
                                            <div>
                                                <span class="answer-sheet-title">Antwortbogen</span>
                                                <span class="answer-sheet-section">{{ $currentSection['title'] }}</span>
                                            </div>
                                            @if ($hasAudio)
                                                @foreach ($currentSection['elements'] as $el)
                                                    @if (($el['type'] ?? '') === 'audio')
                                                        <div class="audio-player"><audio controls src="{{ $el['content'] }}"></audio></div>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </div>

                                        @php
                                            $sheetItems = collect($currentSection['elements'] ?? [])
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
                                                        $detail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null;
                                                        $selectedVal = $answers[$element['id']] ?? '';
                                                    @endphp
                                                    <div class="answer-sheet-row" wire:key="answer-{{ $element['id'] }}">
                                                        <span class="question-num">{{ $qNum }}</span>
                                                        @if (($element['type'] ?? '') === 'fill_blank' && $options === [])
                                                            @php $fbCorrect = $detail ? $detail['correct'] : null; @endphp
                                                            <input type="text" wire:model.live="answers.{{ $element['id'] }}" class="fill-blank-input {{ $result && $selectedVal !== '' ? ($fbCorrect ? 'correct' : 'wrong') : '' }}" placeholder="Antwort" @disabled($result)>
                                                            @if ($result && $selectedVal !== '')
                                                                <span class="feedback-badge {{ $fbCorrect ? 'ok' : 'fail' }}">{{ $fbCorrect ? '✓' : '✗ ' . $detail['expected'] }}</span>
                                                            @endif
                                                        @elseif ($isMatching && count($options) > 5)
                                                            @php $matCorrect = $detail ? $detail['correct'] : null; @endphp
                                                            <select wire:model.live="answers.{{ $element['id'] }}" class="matching-select {{ $result && $selectedVal !== '' ? ($matCorrect ? 'correct' : 'wrong') : '' }}" @disabled($result)>
                                                                <option value="">—</option>
                                                                @foreach ($options as $opt)
                                                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                                                @endforeach
                                                            </select>
                                                            @if ($result && $selectedVal !== '')
                                                                <span class="feedback-badge {{ $matCorrect ? 'ok' : 'fail' }}">{{ $matCorrect ? '✓' : '✗ ' . $detail['expected'] }}</span>
                                                            @endif
                                                        @else
                                                            <div class="bubble-group">
                                                                @foreach ($options as $option)
                                                                    @php
                                                                        $isSelected = $selectedVal === $option;
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
                                                @endforeach
                                            </div>
                                        @endif

                                        {{-- Writing --}}
                                        @foreach ($currentSection['elements'] as $element)
                                            @if (($element['type'] ?? '') === 'writing')
                                                @php $writeDetail = $result ? collect($result['details'])->firstWhere('id', $element['id']) : null; @endphp
                                                <div class="writing-section @if($result) marked @endif">
                                                    <label class="writing-label">{{ $element['label'] }}</label>
                                                    <textarea wire:model.live="answers.{{ $element['id'] }}" class="writing-textarea" placeholder="Schreibe deinen Text hier..." @disabled($result)></textarea>
                                                    @if ($writeDetail)
                                                        <span class="writing-score">{{ $writeDetail['points'] }} / {{ $writeDetail['max_points'] }} Punkte</span>
                                                    @endif
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </aside>
                            </div>
                        @endif

                        {{-- Result display after submission --}}
                        @if ($result && !$sectionLocked)
                            <div class="free-result-panel">
                                <div class="score-label">Your Score</div>
                                <div class="score">{{ $result['earned'] }} / {{ $result['possible'] }}</div>
                                <div style="font-size:1.1rem;font-weight:700;color:{{ ($result['percentage'] ?? 0) >= 60 ? '#22c55e' : '#ef4444' }};margin-top:0.5rem;">
                                    {{ $result['percentage'] }}%
                                </div>
                                @if (!empty($result['details']))
                                    <div style="margin-top:1.5rem;text-align:left;border-top:1px solid #333;padding-top:1rem;">
                                        @foreach ($result['details'] as $detail)
                                            <div style="display:flex;justify-content:space-between;padding:0.35rem 0;font-size:0.85rem;border-bottom:1px solid #222;">
                                                <span style="color:#aaa;">{{ $detail['label'] }}</span>
                                                <span style="color:{{ ($detail['correct'] ?? false) ? '#22c55e' : '#ef4444' }};">
                                                    {{ $detail['points'] }}/{{ $detail['max_points'] }}
                                                    {{ ($detail['correct'] ?? false) ? '✓' : '✗' }}
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endif

                    @else
                        <div class="telc-panel"><h2>Section not found.</h2></div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Next-section popup (bottom-left) --}}
        @if ($result && $nextSectionIndex !== null)
            @php $nextSec = $sections[$nextSectionIndex] ?? null; @endphp
            @if ($nextSec)
                <div style="position:fixed;bottom:1rem;left:1rem;z-index:1002;background:#1a1a1a;border:1px solid #333;border-radius:8px;padding:0.6rem 1rem;box-shadow:0 4px 20px rgba(0,0,0,0.4);display:flex;align-items:center;gap:0.75rem;animation:slideUp 0.3s ease;">
                    <span style="font-size:0.78rem;color:#aaa;">
                        Nächstes: <strong style="color:#eab308;">{{ $nextSec['title'] }}</strong>
                    </span>
                    <button type="button" wire:click="startSection({{ $nextSectionIndex }})" style="background:#eab308;color:#1a1a1a;border:none;padding:0.35rem 0.75rem;border-radius:4px;font-size:0.72rem;font-weight:700;cursor:pointer;font-family:inherit;transition:opacity 0.15s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                        Starten →
                    </button>
                </div>
            @endif
        @endif
    @endif
</div>
