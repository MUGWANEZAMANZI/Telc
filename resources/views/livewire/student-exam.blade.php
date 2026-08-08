@php
    $section = ($template['sections'] ?? [])[$activeSection] ?? null;
    $theme = $template['theme'] ?? [];
    $minutes = intdiv($remainingSeconds, 60);
    $seconds = $remainingSeconds % 60;
@endphp

<div class="student-catalog-shell">
    <div wire:loading.flex wire:target="submitSection, submit" style="display: none; position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,0.6);flex-direction:column;align-items:center;justify-content:center;pointer-events:all;">
        <img src="{{ asset('Spinner@1x-1.7s-199px-199px.svg') }}" alt="Laden..." style="width:120px;height:120px;" />
        <p style="color:#fff;font-weight:700;font-size:1rem;margin-top:1rem;font-family:'Plus Jakarta Sans',sans-serif;">Prüfung wird ausgewertet...</p>
    </div>
    <style>
        .premium-document-shell {
            position: relative;
            max-width: 1120px;
            margin: 0 auto;
            display: flex;
            justify-content: center;
            align-items: flex-start;
            gap: 1.5rem;
        }

        /* Premium C1 Lined Paper Styles */
        .premium-document {
            max-width: 800px;
            width: 100%;
            flex: 0 0 min(100%, 800px);
            margin: 2rem 0;
            background: #ffffff;
            border: 1px solid #d9dde5;
            position: relative;
            padding: 5rem 4rem 10rem 6rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border-radius: 8px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: #1a1a1a;
            line-height: 1.85;
        }

        .premium-document,
        .premium-document > header,
        .premium-document .telc-section-stack,
        .premium-document .telc-section-stack > *,
        .premium-document .c1-section,
        .premium-document .speaking-flow-container,
        .premium-document .c1-passage,
        .premium-document .cloze-text,
        .premium-document .c1-answer-sheet,
        .premium-document .c1-teil3-sentences,
        .premium-document .c1-teil3-answer-sheet,
        .premium-document .writing-section,
        .premium-document fieldset,
        .premium-document article,
        .premium-document figure {
            display: block !important;
            float: none !important;
            clear: both !important;
            width: 100% !important;
            max-width: 100% !important;
            min-width: 0 !important;
            box-sizing: border-box !important;
        }

        .premium-document .telc-section-stack {
            margin-top: 2rem !important;
        }

        .premium-document .c1-section,
        .premium-document .speaking-flow-container {
            position: relative !important;
            margin: 0 0 3rem 0 !important;
        }

        .premium-document .c1-section::after,
        .premium-document .speaking-flow-container::after {
            content: "";
            display: block;
            clear: both;
        }

        .premium-document .document-section-tab {
            position: static !important;
            right: auto !important;
            top: auto !important;
            width: fit-content !important;
            margin: 0 0 0.75rem 0 !important;
            padding: 0.5rem 0.75rem !important;
            border: 1px solid #d1d5db !important;
            border-radius: 6px !important;
            flex-direction: row !important;
        }

        .premium-document .document-section-tab .tab-text {
            writing-mode: horizontal-tb !important;
            text-orientation: mixed !important;
        }

        .premium-document .section-label {
            display: block !important;
            width: 100% !important;
            margin: 0 0 1rem 0 !important;
            color: #d8232a;
            font-size: 0.78rem;
            font-weight: 800;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        .premium-document .paper-workbench,
        .premium-document .exam-layout {
            display: block !important;
            width: 100% !important;
            min-height: auto !important;
            overflow: visible !important;
        }

        .premium-document .exam-paper-column,
        .premium-document .exam-answer-column {
            display: block !important;
            width: 100% !important;
            min-width: 0 !important;
            flex: none !important;
            overflow: visible !important;
        }

        .premium-document .exam-answer-column {
            margin-top: 2rem !important;
        }

        .premium-document::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 4.5rem;
            width: 1px;
            border-left: 1px solid #cbcbcb;
        }

        .premium-document::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 4.7rem;
            width: 1px;
            border-left: 1px solid #cbcbcb;
        }

        .c1-badge {
            display: inline-block;
            border: 1px solid #1a1a1a;
            font-size: 0.75rem;
            font-weight: 700;
            letter-spacing: 0.15em;
            padding: 0.35rem 1rem;
            margin-bottom: 2rem;
            text-transform: uppercase;
        }

        .c1-title {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 3.2rem;
            font-weight: 400;
            line-height: 1.15;
            margin-bottom: 0.5rem;
        }

        .c1-subtitle {
            color: #666666;
            font-size: 1rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 4rem;
        }

        .c1-instructions {
            background-color: #fafafa;
            border-left: 3px solid #1a1a1a;
            padding: 1.25rem 1.5rem;
            margin-bottom: 3rem;
            font-size: 0.95rem;
            line-height: 1.6;
            color: #4a4a4a;
        }

        .c1-passage {
            font-size: 1.08rem;
            line-height: 2.1;
            text-align: justify;
            margin-bottom: 3rem;
        }

        .cloze-select-wrapper {
            position: relative;
            display: inline;
            margin: 0 0.35rem;
        }

        .cloze-select {
            border: none;
            border-bottom: 2px solid #1a1a1a;
            background: transparent;
            font-family: inherit;
            font-size: 1.05rem;
            font-weight: 700;
            color: #d8232a;
            padding: 0 0.25rem;
            outline: none;
            cursor: pointer;
            border-radius: 0;
            max-width: 100%;
            white-space: normal;
        }

        .cloze-select:focus {
            border-bottom-color: #d8232a;
        }

        .cloze-select.correct {
            border-bottom-color: #166534 !important;
            color: #166534 !important;
        }

        .cloze-select.wrong {
            border-bottom-color: #991b1b !important;
            color: #991b1b !important;
        }

        .cloze-gap-num {
            font-size: 0.75rem;
            font-weight: 800;
            vertical-align: super;
            color: #666666;
            margin-right: 0.15rem;
        }

        .mc-question-row {
            margin-bottom: 2.5rem;
        }

        .mc-question-header {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .mc-options-list {
            display: flex;
            flex-direction: column;
            gap: 0.65rem;
        }

        .mc-option-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.75rem 1rem;
            border: 1px solid #e5e5e5;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }

        .mc-option-item:hover {
            border-color: #1a1a1a;
            background-color: #fafafa;
        }

        .mc-option-item.selected {
            border-color: #1a1a1a;
            background-color: #f7f7f7;
        }

        .mc-option-bullet {
            width: 16px;
            height: 16px;
            border: 1px solid #666666;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
            flex-shrink: 0;
        }

        .mc-option-item:hover .mc-option-bullet {
            border-color: #1a1a1a;
        }

        .mc-radio-input {
            display: none;
        }

        .mc-radio-input:checked + .mc-option-bullet {
            border-color: #1a1a1a;
            background-color: #1a1a1a;
        }

        .mc-radio-input:checked + .mc-option-bullet::after {
            content: '';
            width: 6px;
            height: 6px;
            background-color: #ffffff;
            border-radius: 50%;
        }

        .tf-option-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }

        .tf-option-table th, .tf-option-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid #e5e5e5;
            text-align: left;
            font-size: 0.95rem;
        }

        .tf-option-table th {
            font-weight: 700;
            color: #666666;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }

        .tf-choice-label {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: 1px solid #e5e5e5;
            border-radius: 50%;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            margin-right: 0.5rem;
            user-select: none;
        }

        .tf-choice-label:hover {
            border-color: #1a1a1a;
            background-color: #fafafa;
        }

        .tf-choice-input {
            display: none;
        }

        .tf-choice-input:checked + .tf-choice-label {
            border-color: #1a1a1a;
            background-color: #1a1a1a;
            color: #ffffff;
        }

        .writing-textarea-lined {
            width: 100%;
            box-sizing: border-box;
            min-height: 400px;
            padding: 1.5rem;
            font-family: inherit;
            font-size: 1.05rem;
            line-height: 2.2rem;
            border: 1px solid #d9dde5;
            background-color: transparent;
            background-image: linear-gradient(transparent, transparent 2.15rem, #cbcbcb 2.15rem, #cbcbcb 2.2rem);
            background-size: 100% 2.2rem;
            color: #1a1a1a;
            resize: vertical;
            transition: all 0.3s ease;
            outline: none;
        }

        .writing-textarea-lined textarea {
            width: 100%;
            height: 100%;
            min-height: 380px;
            border: none;
            background: transparent;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit;
            color: inherit;
            outline: none;
            resize: vertical;
            box-sizing: border-box;
        }

        /* Lined inputs for HV3 */
        .hv3-lined-input {
            border: none;
            border-bottom: 1.5px solid #cbcbcb;
            background: transparent;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            color: #d8232a;
            width: 100%;
            padding: 0.25rem 0;
            outline: none;
        }

        .hv3-lined-input:focus {
            border-bottom-color: #1a1a1a;
        }

        /* Feedback Marks */
        .feedback-badge {
            display: inline-block;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 0.15rem 0.5rem;
            border-radius: 2px;
            margin-left: 0.5rem;
            text-transform: uppercase;
        }

        .feedback-badge.ok {
            background-color: #e6f4ea;
            color: #137333;
        }

        .feedback-badge.fail {
            background-color: #fce8e6;
            color: #c5221f;
        }

        /* Floating Right Timer Widget */
        .doc-timer-bar {
            position: sticky;
            top: 2rem;
            margin-top: 2rem;
            width: 180px;
            background: #ffffff;
            border: 2px solid #1a1a1a;
            border-radius: 8px;
            padding: 1rem;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            align-items: center;
            flex-shrink: 0;
        }

        .doc-timer-text {
            font-size: 0.95rem;
            font-weight: 700;
            color: #d8232a;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }

        /* Responsive Mobile Styles */
        @media (max-width: 1080px) {
            .premium-document-shell {
                flex-direction: column;
                align-items: center;
            }

            .premium-document {
                margin: 1rem auto;
                padding: 3rem 1.5rem 6rem 1.5rem;
                border-radius: 4px;
            }

            .premium-document::before,
            .premium-document::after {
                display: none; /* Hide margins line guides on mobile */
            }

            .c1-title {
                font-size: 2rem;
            }

            .c1-subtitle {
                margin-bottom: 2rem;
            }

            .doc-timer-bar {
                position: fixed;
                top: auto;
                bottom: 0;
                left: 0;
                right: 0;
                width: 100%;
                border-radius: 0;
                border: none;
                border-top: 2px solid #1a1a1a;
                padding: 0.6rem 1rem;
                flex-direction: row;
                justify-content: space-between;
                box-shadow: 0 -4px 15px rgba(0, 0, 0, 0.1);
                box-sizing: border-box;
            }


            .doc-timer-bar .submit-button {
                width: auto !important;
                padding: 0.4rem 1rem !important;
                font-size: 0.85rem !important;
            }

            /* Adjust flex tables */
            .tf-option-table th, .tf-option-table td {
                padding: 0.6rem 0.5rem;
                font-size: 0.85rem;
            }

            .tf-choice-label {
                width: 28px;
                height: 28px;
                font-size: 0.8rem;
            }

            .mc-option-item {
                padding: 0.6rem 0.85rem;
                font-size: 0.9rem;
            }
        }

        /* Vertical sidebar tab on the document */
        .document-section-tab {
            position: absolute;
            right: -3.2rem;
            top: 5rem;
            background: #e5e7eb;
            border: 1px solid #d1d5db;
            border-left: none;
            border-radius: 0 8px 8px 0;
            padding: 1.25rem 0.45rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
            user-select: none;
            box-shadow: 2px 2px 10px rgba(0,0,0,0.04);
            z-index: 10;
        }

        .document-section-tab .icon-grid {
            display: grid;
            grid-template-columns: repeat(2, 6px);
            gap: 2px;
            opacity: 0.6;
        }

        .document-section-tab .icon-grid div {
            width: 6px;
            height: 6px;
            background: #1a1a1a;
            border-radius: 1px;
        }

        .document-section-tab .icon-grid div:nth-child(4) {
            background: transparent;
        }

        .document-section-tab .tab-text {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            font-size: 0.78rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #374151;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        @media (max-width: 1024px) {
            .document-section-tab {
                display: none !important;
            }
        }
    </style>

    @if (empty($template))
        <section class="exam-store">
            <div class="exam-store-header">
                <p>{{ $filterFree ? 'Kostenlose Übungen' : 'TELC Exam Store' }}</p>
                <h1>{{ $filterFree ? 'Üben Sie kostenlos mit unseren Freiübungen' : 'Erwerben Sie eine Prüfungssimulation vor dem Start' }}</h1>
                <span>{{ $filterFree ? 'Wählen Sie unten eine kostenlose Übung aus, um zu beginnen.' : 'Wählen Sie unten eine Prüfung aus, um zu beginnen.' }}</span>
            </div>

            {{-- Rich Helper Banner for Students --}}
            <div class="student-helper-banner" style="background: linear-gradient(135deg, #18181b, #09090b); border: 1px solid #27272a; border-radius: 12px; padding: 1.75rem; margin-bottom: 2.5rem; color: #f4f4f5; display: flex; flex-direction: column; gap: 1.25rem; box-shadow: 0 10px 25px rgba(0,0,0,0.25); text-align: left; font-family: 'Plus Jakarta Sans', sans-serif;">
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <span style="font-size: 1.75rem;">🎓</span>
                    <div>
                        <h3 style="margin: 0; font-size: 1.2rem; font-weight: 700; color: #ffffff;">Willkommen im Lernportal!</h3>
                        <p style="margin: 0.25rem 0 0; font-size: 0.88rem; color: #a1a1aa; line-height: 1.5;">Hier können Sie originalgetreue Prüfungssimulationen durchführen und interaktiv per Mikrofon üben.</p>
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; border-top: 1px solid #27272a; padding-top: 1.25rem;">
                    <div style="font-size: 0.82rem; color: #a1a1aa; line-height: 1.5;">
                        <strong style="color: #ffffff; display: block; margin-bottom: 0.25rem;">🎤 KI-Sprechübungen</strong>
                        Nutzen Sie Ihr Mikrofon im Sprechprüfungs-Labor. Die KI transkribiert Ihre Sätze und antwortet Ihnen interaktiv in 3 Runden.
                    </div>
                    <div style="font-size: 0.82rem; color: #a1a1aa; line-height: 1.5;">
                        <strong style="color: #ffffff; display: block; margin-bottom: 0.25rem;">📝 Liniertes Papier-Design</strong>
                        Unsere Oberfläche ahmt echte Prüfungsformulare auf liniertem Papier nach, um Ihnen die authentischste Vorbereitung zu bieten.
                    </div>
                    <div style="font-size: 0.82rem; color: #a1a1aa; line-height: 1.5;">
                        <strong style="color: #ffffff; display: block; margin-bottom: 0.25rem;">💳 Freischaltung</strong>
                        Bezahlen Sie sicher via PayPal oder Revolut. Die Freischaltung erfolgt vollautomatisch und sofort nach dem Kauf.
                    </div>
                </div>
                <div style="text-align: right; border-top: 1px solid #27272a; padding-top: 1rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.5rem;">
                    <span style="font-size: 0.8rem; color: #71717a;">Fragen? Unser Handbuch hilft Ihnen bei Mikrofon- oder PDF-Problemen.</span>
                    <a href="{{ route('user.guide') }}" style="display: inline-flex; align-items: center; gap: 0.35rem; color: #eab308; font-size: 0.85rem; font-weight: 700; text-decoration: none; border: 1.5px solid #eab308; padding: 0.5rem 1rem; border-radius: 6px; transition: all 0.2s;">
                        <span>Vollständiges Handbuch anzeigen (PDF-Export)</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            {{-- VISA Consultancy & Feedback Card --}}
            <div style="background: #ffffff; border: 1px solid #d9dde5; border-radius: 8px; padding: 1.75rem; margin-bottom: 2rem; text-align: left; font-family: 'Plus Jakarta Sans', sans-serif;">
                <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem;">
                    <div style="flex: 1; min-width: 300px;">
                        <h4 style="font-family: 'Playfair Display', Georgia, serif; font-size: 1.35rem; font-weight: 700; margin: 0 0 0.5rem; color: #1a1a1a;">Benötigen Sie Hilfe bei Ihrem VISA-Antrag?</h4>
                        <p style="color: #4a5568; font-size: 0.95rem; margin: 0 0 1rem; line-height: 1.6;">Für Unterstützung bei Bewerbungen, Visa-Fragen oder Behördengängen füllen Sie einfach unser Google-Formular aus. Wenn Sie uns Feedback senden möchten, können Sie auch das entsprechende Formular nutzen.</p>
                        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                            <a href="{{ \App\Models\Setting::value('visa_consultancy_url', config('services.google_forms.visa_consultancy')) }}" target="_blank" class="telc-button primary" style="text-decoration: none; padding: 0.55rem 1.25rem; font-size: 0.85rem; background-color: #1a1a1a; color: #ffffff; border-radius: 6px;">
                                VISA-Beratung anfordern (Google Formular)
                            </a>
                            <a href="{{ \App\Models\Setting::value('feedback_url', config('services.google_forms.feedback')) }}" target="_blank" class="telc-button" style="text-decoration: none; padding: 0.55rem 1.25rem; font-size: 0.85rem; border: 1.5px solid #4a5568; color: #4a5568; border-radius: 6px;">
                                Feedback geben (Google Formular)
                            </a>
                        </div>
                    </div>
                </div>
            </div>

             {{-- Level Filters and Sorting Controls --}}
            <div style="display: flex; flex-direction: column; gap: 1rem; background: #ffffff; border: 1px solid #d9dde5; border-radius: 8px; padding: 1.25rem; margin-bottom: 2rem; text-align: left; font-family: 'Plus Jakarta Sans', sans-serif;">
                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem;">
                    <strong style="font-size: 0.8rem; text-transform: uppercase; color: #666666; letter-spacing: 0.05em; margin-right: 0.5rem;">Prüfungsniveau filtern:</strong>
                    <button type="button" wire:click="selectLevel('')" style="border: none; padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s; background-color: {{ $selectedLevel === '' ? '#1a1a1a' : '#f3f4f6' }}; color: {{ $selectedLevel === '' ? '#ffffff' : '#4b5563' }};">Alle</button>
                    @foreach (['B1', 'B2', 'C1'] as $lvl)
                        <button type="button" wire:click="selectLevel('{{ $lvl }}')" style="border: none; padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s; background-color: {{ $selectedLevel === $lvl ? '#eab308' : '#f3f4f6' }}; color: {{ $selectedLevel === $lvl ? '#09090b' : '#4b5563' }};">{{ $lvl }}</button>
                    @endforeach
                </div>
                <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem;">
                    <strong style="font-size: 0.8rem; text-transform: uppercase; color: #666666; letter-spacing: 0.05em; margin-right: 0.5rem;">Anbieter filtern:</strong>
                    <button type="button" wire:click="selectProvider('')" style="border: none; padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s; background-color: {{ $selectedProvider === '' ? '#1a1a1a' : '#f3f4f6' }}; color: {{ $selectedProvider === '' ? '#ffffff' : '#4b5563' }};">Alle</button>
                    <button type="button" wire:click="selectProvider('telc')" style="border: none; padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s; background-color: {{ $selectedProvider === 'telc' ? '#eab308' : '#f3f4f6' }}; color: {{ $selectedProvider === 'telc' ? '#09090b' : '#4b5563' }};">telc</button>
                    <button type="button" wire:click="selectProvider('goethe')" style="border: none; padding: 0.4rem 1rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 700; cursor: pointer; transition: all 0.2s; background-color: {{ $selectedProvider === 'goethe' ? '#eab308' : '#f3f4f6' }}; color: {{ $selectedProvider === 'goethe' ? '#09090b' : '#4b5563' }};">Goethe</button>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <strong style="font-size: 0.8rem; text-transform: uppercase; color: #666666; letter-spacing: 0.05em; margin-right: 0.5rem;">Sortieren nach:</strong>
                    <select wire:change="selectSort($event.target.value)" style="border: 1px solid #d9dde5; padding: 0.4rem 0.75rem; border-radius: 6px; font-size: 0.75rem; font-weight: 700; background: #ffffff; color: #374151; outline: none; cursor: pointer;">
                        <option value="title_asc" @selected($sortBy === 'title_asc')>Titel (A-Z)</option>
                        <option value="title_desc" @selected($sortBy === 'title_desc')>Titel (Z-A)</option>
                        <option value="price_asc" @selected($sortBy === 'price_asc')>Preis (günstig zuerst)</option>
                        <option value="price_desc" @selected($sortBy === 'price_desc')>Preis (teuer zuerst)</option>
                    </select>
                </div>
            </div>

            <div class="exam-card-grid">
                @forelse ($templates as $record)
                    @php
                        $enrollment = $enrollments->get($record->id);
                        $status = $this->getTemplateFreeStatus($record);
                        $isActuallyFree = $status['is_free'];
                    @endphp
                    <article class="exam-buy-card" style="position: relative;">
                        <div>
                            <span class="exam-level">{{ $record->level }}</span>
                            <h2>{{ $record->title }}</h2>
                            <p>Originalgetreue Prüfungssimulation auf {{ $record->level }}-Niveau im linearen Dokumenten-Design.</p>
                        </div>

                        @if (! empty($record->timings))
                            <dl>
                                @foreach (array_slice($record->timings, 0, 4) as $timing)
                                    <div><dt>{{ $timing['label'] }}</dt><dd>{{ $timing['minutes'] }} Min</dd></div>
                                @endforeach
                            </dl>
                        @endif

                        @if ($enrollment && $enrollment->status === 'active')
                            @if ($enrollment->active_answers !== null)
                                <button type="button" wire:click="startOrResumeExam({{ $record->id }})" class="telc-button primary wide" style="background-color: #137333; border-color: #137333;">
                                    Sitzung fortsetzen
                                </button>
                            @else
                                <button type="button" wire:click="startOrResumeExam({{ $record->id }})" class="telc-button primary wide" style="background-color: #1a1a1a; border-color: #1a1a1a;">
                                    Prüfung starten {{ $isActuallyFree ? '(Kostenlos)' : '' }}
                                </button>
                            @endif
                        @elseif ($isSubscribed || $isActuallyFree)
                            <button type="button" wire:click="startOrResumeExam({{ $record->id }})" class="telc-button primary wide" style="background-color: #1a1a1a; border-color: #1a1a1a;">
                                Prüfung starten {{ $isActuallyFree ? '(Kostenlos)' : '' }}
                            </button>
                        @else
                            <button type="button" wire:click="startEnroll({{ $record->id }})" class="telc-button primary wide">
                                Kostenpflichtige Prüfung freischalten
                            </button>
                        @endif
                    </article>
                @empty
                    <div class="telc-panel">
                        <h2>Bislang sind keine Prüfungen veröffentlicht.</h2>
                        <p>Ein Administrator muss zuerst Vorlagen hochladen und veröffentlichen.</p>
                    </div>
                @endforelse
            </div>

            {{-- Purchase Modal --}}
            @if ($showEnrollModal)
                <div class="store-modal-overlay" style="position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; display: flex; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
                    <div class="store-modal-body" style="background: #ffffff; padding: 2.5rem; max-width: 500px; width: 90%; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-left: 5px solid #d8232a; font-family: 'Plus Jakarta Sans', sans-serif;">
                        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 0.5rem; color: #1a1a1a;">Premium-Prüfung erwerben</h3>
                        <p style="color: #666666; font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6;">Sie schalten hiermit die vollständige, zeitgesteuerte C1-Prüfung frei. Bitte wählen Sie eine Zahlungsmethode:</p>

                        @if ($enrollModalError)
                            <div style="background: #fdf2f2; border: 1px solid #fbd5d5; color: #9b1c1c; padding: 0.75rem 1rem; border-radius: 4px; font-size: 0.85rem; margin-bottom: 1.25rem; line-height: 1.5;">
                                {{ $enrollModalError }}
                            </div>
                        @endif

                        <div style="display: flex; flex-direction: column; gap: 0.75rem; margin-bottom: 2rem;">
                            <button type="button" wire:click="selectPayment('paypal')" style="display: flex; align-items: center; justify-content: space-between; border: 2px solid #1a1a1a; padding: 1rem; border-radius: 4px; background: #fafafa; cursor: pointer; transition: all 0.2s; text-align: left; width: 100%;">
                                <strong style="font-size: 0.95rem; color: #1a1a1a;">Mit PayPal bezahlen (Einmalig 5,00 €)</strong>
                                <span style="font-size: 0.85rem; color: #d8232a; font-weight: 600;">Jetzt freischalten</span>
                            </button>
                            <button type="button" wire:click="selectPayment('revolut')" style="display: flex; align-items: center; justify-content: space-between; border: 2px solid #1a1a1a; padding: 1rem; border-radius: 4px; background: #fafafa; cursor: pointer; transition: all 0.2s; text-align: left; width: 100%;">
                                <strong style="font-size: 0.95rem; color: #1a1a1a;">Mit Kreditkarte (Revolut) zahlen</strong>
                                <span style="font-size: 0.85rem; color: #d8232a; font-weight: 600;">Jetzt bezahlen</span>
                            </button>
                        </div>

                        <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                            <button type="button" wire:click="cancelEnroll" style="border: none; background: none; padding: 0.5rem 1rem; cursor: pointer; color: #666666; font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">Abbrechen</button>
                        </div>
                    </div>
                </div>
            @endif

            @if ($attempts->isNotEmpty())
                <div class="telc-panel" style="margin-top: 3rem; background: #ffffff; border: 1px solid #d9dde5; border-radius: 8px; padding: 2rem;">
                    <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin-bottom: 1.5rem; color: #1a1a1a;">Meine letzten Prüfungsergebnisse</h3>
                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        @foreach ($attempts as $attempt)
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f0f0f0; padding-bottom: 1rem;">
                                <div style="text-align: left;">
                                    <strong style="font-size: 1.05rem; color: #1a1a1a; display: block;">{{ $attempt->examTemplate->title ?? 'TELC Prüfung' }}</strong>
                                    <span style="font-size: 0.85rem; color: #666666;">Abgegeben am {{ $attempt->submitted_at->format('d.m.Y H:i') }}</span>
                                </div>
                                <div style="display: flex; align-items: center; gap: 1.5rem;">
                                    <div style="text-align: right;">
                                        <strong style="font-size: 1.25rem; color: #1a1a1a; display: block;">{{ $attempt->result['earned'] ?? 0 }} / {{ $attempt->result['possible'] ?? 0 }}</strong>
                                        <span style="font-size: 0.9rem; font-weight: 700; color: #d8232a;">{{ $attempt->result['percentage'] ?? 0 }}%</span>
                                    </div>
                                    <button type="button" wire:click="viewAttempt({{ $attempt->id }})" class="telc-button" style="padding: 0.5rem 1rem; font-size: 0.85rem;">Details ansehen</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </section>
    @else
        @php
            $isPaidExam = $this->hasActiveSubscription() || ($selectedTemplateId !== null && $this->isFullyEnrolled($selectedTemplateId));
        @endphp

        {{-- Premium Document Layout for Paid C1 / B1 / B2 or dynamic templates without PDF --}}
        @if (empty($template['source_pdf']) || ($isPaidExam && in_array($template['level'] ?? '', ['B1', 'B2', 'C1', 'C2'], true)))
            <div class="premium-document-shell" wire:poll.1s="tick">
                <div class="premium-document">
                    @include('partials.premium.header')
                    @if (view()->exists('partials.exam-levels.' . strtolower($template['level'])))
                        @include('partials.exam-levels.' . strtolower($template['level']))
                    @else
                        @include('partials.premium.dynamic-sections')
                    @endif
                </div>
                @include('partials.premium.timer')
            </div>
        @else
            {{-- Standard Split View for free exams or non-premium levels --}}
            <div class="paper-exam-shell">
                <aside class="paper-exam-controls">
                    <div class="paper-exam-brand">
                        <span>{{ $template['level'] ?? 'B2' }}</span>
                        <div>
                            <strong>{{ $template['title'] ?? 'telc Deutsch B2' }}</strong>
                            <small>Prüfungssimulation</small>
                        </div>
                    </div>

                    <div class="student-sections">
                        @foreach (($template['sections'] ?? []) as $index => $part)
                            @php
                                $isLocked = false;
                                if (isset($answers['_locked_limit_reached']) && $index >= 2) {
                                    $isLocked = true;
                                }
                            @endphp
                            <button type="button" wire:click="startSection({{ $index }})" @class(['student-section-button', 'active' => $activeSection === $index]) @disabled($isLocked)>
                                <span>{{ $part['title'] }} {!! $isLocked ? '🔒' : '' !!}</span>
                                <small>{{ $part['minutes'] }} min</small>
                            </button>
                        @endforeach
                    </div>

                    <div class="student-clock" wire:poll.1s="tick">
                        {{ sprintf('%02d:%02d', $minutes, $seconds) }}
                    </div>

                    @if ($isPaidExam)
                        <button type="button" wire:click="submit" class="telc-button primary wide">
                            Antwortbogen abgeben
                        </button>
                    @else
                        @if (!$result)
                            <button type="button" wire:click="submitSection" class="telc-button primary wide">
                                Abschnitt abgeben
                            </button>
                        @else
                            @if (isset($template['sections'][$activeSection + 1]))
                                @php
                                    $nextLocked = isset($answers['_locked_limit_reached']) && ($activeSection + 1) >= 2;
                                @endphp
                                @if ($nextLocked)
                                    <div style="background:#fdf2f2;border:1px solid #fbd5d5;color:#9b1c1c;padding:0.5rem;border-radius:4px;font-size:0.75rem;margin-bottom:0.75rem;">
                                        Bitte abonnieren Sie, um weitere Abschnitte freizuschalten.
                                    </div>
                                    <a href="{{ route('pricing') }}" class="telc-button primary wide" style="background-color: #d8232a; border-color: #d8232a; text-align: center; display: block; text-decoration: none; color: #fff;">
                                        Jetzt abonnieren
                                    </a>
                                @else
                                    <button type="button" wire:click="startSection({{ $activeSection + 1 }})" class="telc-button primary wide" style="background-color: #137333; border-color: #137333;">
                                        Nächster Abschnitt →
                                    </button>
                                @endif
                            @else
                                <button type="button" wire:click="exitExam" class="telc-button primary wide" style="background-color: #1a1a1a; border-color: #1a1a1a;">
                                    Prüfung beenden
                                </button>
                            @endif
                        @endif
                    @endif

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

                    @if (isset($attempts) && $attempts->isNotEmpty())
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

                        {{-- === INTERACTIVE TEXT VIEW === --}}
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

                        {{-- === INLINE GAP VIEW === --}}
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
                                                id: '{{ $element['id'] }}',
                                                isRecording: false,
                                                recognition: null,
                                                interimText: '',
                                                init() {
                                                    this.interimText = $wire.get('answers.' + this.id) || '';
                                                },
                                                initRecognition() {
                                                    const SR = window.SpeechRecognition || window.webkitSpeechRecognition;
                                                    if (!SR) return;
                                                    this.recognition = new SR();
                                                    this.recognition.lang = 'de-DE';
                                                    this.recognition.continuous = true;
                                                    this.recognition.onresult = (e) => {
                                                        let t = '';
                                                        let lastChunk = null;
                                                        for (let i = 0; i < e.results.length; i++) {
                                                            let chunk = e.results[i][0].transcript.trim();
                                                            if (chunk === '') continue;
                                                            if (chunk === lastChunk) continue;
                                                            lastChunk = chunk;
                                                            t += e.results[i][0].transcript + ' ';
                                                        }
                                                        this.interimText = t.trim();
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
                                                sendToAi() {
                                                    $wire.set('answers.' + this.id, this.interimText);
                                                    $wire.call('analyzeSpeaking', this.id, this.interimText);
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
                                                    <button type="button" @click="sendToAi()"
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

                        {{-- === DEFAULT PDF BOOKLET VIEW === --}}
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

                                    </div>
                                </aside>
                            </div>

                            {{-- Writing (full-width below the paper layout) --}}
                            @foreach ($section['elements'] as $element)
                                @if (($element['type'] ?? '') === 'writing')
                                    <div class="writing-section" style="max-width: 794px; margin: 1rem auto;">
                                        <label class="writing-label">{{ $element['label'] }}</label>
                                        <textarea wire:model.live="answers.{{ $element['id'] }}" class="writing-textarea" placeholder="Schreibe deinen Text hier..." style="min-height: 600px;"></textarea>
                                    </div>
                                @endif
                            @endforeach
                        @endif
                    @endif
                </main>
            </div>
        @endif
    @endif

    {{-- Revolut Payment Modal --}}
<div id="revolutModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1001; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: #ffffff; padding: 2.5rem; max-width: 480px; width: 90%; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); border-left: 5px solid #d8232a; font-family: 'Plus Jakarta Sans', sans-serif; text-align: left;">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.8rem; margin: 0 0 0.5rem; color: #1a1a1a;">Revolut Bezahlung</h3>
        <p style="color: #666666; font-size: 0.95rem; margin-bottom: 1.5rem; line-height: 1.6;">Schliessen Sie die Zahlung mit Revolut ab.</p>
        <div id="revolut-exam-button"></div>
        <div id="revolut-exam-loading" style="text-align: center; padding: 1rem;">
            <div class="loading-spinner" style="display: inline-block; width: 20px; height: 20px; border: 2px solid #e5e5e5; border-top-color: #1a1a1a; border-radius: 50%; animation: spin 0.6s linear infinite;"></div>
            <p style="font-size: 0.85rem; color: #666; margin-top: 0.5rem;">Zahlung wird vorbereitet...</p>
        </div>
        <div id="revolut-exam-error" style="display: none; color: #d8232a; font-size: 0.85rem; margin-top: 0.5rem;"></div>
        <div style="display: flex; justify-content: flex-end; margin-top: 1rem;">
            <button type="button" onclick="closeRevolutModal()" style="border: none; background: none; padding: 0.5rem 1rem; cursor: pointer; color: #666666; font-size: 0.9rem; font-weight: 600; text-transform: uppercase;">Abbrechen</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let revolutExamTemplateId = null;

    document.addEventListener('open-revolut-modal', function(event) {
        revolutExamTemplateId = event.detail.templateId;
        document.getElementById('revolutModal').style.display = 'flex';
        initRevolutExamPay();
    });

    async function initRevolutExamPay() {
        const button = document.getElementById('revolut-exam-button');
        const loading = document.getElementById('revolut-exam-loading');
        const error = document.getElementById('revolut-exam-error');

        button.innerHTML = '';
        loading.style.display = 'block';
        error.style.display = 'none';

        try {
            const response = await fetch('{{ route('revolut.create') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    type: 'exam',
                    id: revolutExamTemplateId
                })
            });

            const data = await response.json();

            if (data.error) {
                error.textContent = data.error;
                error.style.display = 'block';
                loading.style.display = 'none';
                return;
            }

            loading.style.display = 'none';

            if (typeof RevolutCheckout === 'undefined') {
                await new Promise((resolve, reject) => {
                    const script = document.createElement('script');
                    script.src = 'https://merchant.revolut.com/embed.js';
                    script.onload = resolve;
                    script.onerror = () => {
                        error.textContent = 'Revolut SDK konnte nicht geladen werden.';
                        error.style.display = 'block';
                        reject();
                    };
                    document.head.appendChild(script);
                });
            }

            const rc = await RevolutCheckout(data.public_id, data.mode);
            rc.revolutPay({
                target: button,
                onSuccess() {
                    window.location.href = '{{ route('revolut.success') }}';
                },
                onError(e) {
                    error.textContent = 'Zahlung fehlgeschlagen: ' + (e.message || 'Unbekannter Fehler');
                    error.style.display = 'block';
                },
                onCancel() {
                    closeRevolutModal();
                }
            });
        } catch (e) {
            error.textContent = 'Zahlungsdienst nicht verfügbar. Bitte versuchen Sie es später erneut.';
            error.style.display = 'block';
            loading.style.display = 'none';
        }
    }

    function closeRevolutModal() {
        document.getElementById('revolutModal').style.display = 'none';
        document.getElementById('revolut-exam-button').innerHTML = '';
    }

    // Interactive Explanations Display
    window.examResultDetails = @json($result ? $result['details'] : null);

    function applyExplanations() {
        if (!window.examResultDetails) return;

        window.examResultDetails.forEach(detail => {
            const elId = detail.id;
            let selectors = [
                `[wire\\:model\\.live="answers.${elId}"]`,
                `[wire\\:model="answers.${elId}"]`,
                `[name="${elId}"]`,
                `[wire\\:model\\.change="answers.${elId}"]`,
                `[wire\\:model\\.live\\.change="answers.${elId}"]`
            ];
            
            if (elId.includes('.gap.')) {
                let normalizedId = elId.replace('.gap.', '.');
                selectors.push(`[wire\\:model\\.live="answers.${normalizedId}"]`);
                selectors.push(`[wire\\:model="answers.${normalizedId}"]`);
                selectors.push(`[wire\\:model\\.change="answers.${normalizedId}"]`);
                selectors.push(`[wire\\:model\\.live\\.change="answers.${normalizedId}"]`);
            }
            
            let targetEl = document.querySelector(selectors.join(', '));
            
            if (targetEl) {
                let wrapper = targetEl.closest('.cloze-select-wrapper') || targetEl.closest('.mc-option-item') || targetEl.closest('.mc-question-row') || targetEl.closest('tr') || targetEl.parentElement;

                if (wrapper && !wrapper.dataset.explanationAttached) {
                    wrapper.dataset.explanationAttached = "true";
                    wrapper.style.cursor = "pointer";
                    wrapper.classList.add('hover:bg-yellow-50/50', 'transition-colors');

                    if (wrapper.classList.contains('cloze-select-wrapper')) {
                        wrapper.style.display = 'inline-flex';
                        wrapper.style.flexWrap = 'wrap';
                        wrapper.style.alignItems = 'center';
                        wrapper.style.gap = '0.25rem';
                    } else {
                        const style = window.getComputedStyle(wrapper);
                        if (style.display === 'flex' || style.display === 'inline-flex') {
                            wrapper.style.flexWrap = 'wrap';
                        }
                    }

                    const explDiv = document.createElement('div');
                    explDiv.className = 'explanation-box mt-2 p-3 bg-yellow-50 border-l-4 border-yellow-500 text-xs text-stone-750 rounded shadow-sm hidden text-left';
                    
                    let explanationText = detail.explanation || (detail.correct ? 'Richtig! Gut gemacht.' : 'Leider falsch.');
                    let questionHtml = detail.question?.trim() ? `<div class="font-bold text-slate-800 mb-1">Frage / Aufgabe:</div><p class="text-slate-600 mb-3">${detail.question}</p>` : '';
                    
                    explDiv.innerHTML = `${questionHtml}<div class="font-bold text-yellow-800 mb-1">Erklärung & Feedback:</div><p>${explanationText}</p>${!detail.correct && detail.expected ? `<div class="mt-1 text-green-700 font-bold">Richtige Antwort: ${detail.expected}</div>` : ''}`;
                    
                    if (wrapper.tagName === 'TR') {
                        const newTr = document.createElement('tr');
                        newTr.className = 'hidden';
                        const newTd = document.createElement('td');
                        newTd.colSpan = wrapper.cells.length;
                        newTd.appendChild(explDiv);
                        newTr.appendChild(newTd);
                        wrapper.after(newTr);
                        wrapper.addEventListener('click', () => newTr.classList.toggle('hidden'));
                    } else {
                        wrapper.appendChild(explDiv);
                        wrapper.addEventListener('click', (e) => {
                            if (!['A', 'BUTTON', 'SELECT', 'INPUT', 'TEXTAREA'].includes(e.target.tagName)) {
                                explDiv.classList.toggle('hidden');
                            }
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
@endpush
