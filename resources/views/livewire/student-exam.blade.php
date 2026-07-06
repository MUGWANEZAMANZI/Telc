@php
    $section = ($template['sections'] ?? [])[$activeSection] ?? null;
    $theme = $template['theme'] ?? [];
    $minutes = intdiv($remainingSeconds, 60);
    $seconds = $remainingSeconds % 60;
@endphp

<div class="student-catalog-shell">
    <style>
        /* Premium C1 Lined Paper Styles */
        .premium-document {
            max-width: 800px;
            margin: 2rem auto;
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
            position: fixed;
            top: 2rem;
            right: 1.5rem;
            width: 140px;
            background: #ffffff;
            border: 2px solid #1a1a1a;
            border-radius: 6px;
            padding: 0.75rem 1rem;
            z-index: 1000;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
            align-items: center;
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
        @media (max-width: 768px) {
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

            .doc-timer-bar .save-indicator {
                display: none !important; /* Hide save logs on small timer bar */
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
    </style>

    @if (empty($template))
        <section class="exam-store">
            <div class="exam-store-header">
                <p>TELC Exam Store</p>
                <h1>Erwerben Sie eine Prüfungssimulation vor dem Start</h1>
                <span>Wählen Sie unten eine Prüfung aus, um zu beginnen.</span>
            </div>

            <div class="exam-card-grid">
                @forelse ($templates as $record)
                    @php
                        $enrollment = $enrollments->get($record->id);
                    @endphp
                    <article class="exam-buy-card" style="position: relative;">
                        <div>
                            <span class="exam-level">{{ $record->level }}</span>
                            <h2>{{ $record->title }}</h2>
                            <p>Originalgetreue Prüfungssimulation auf C1-Niveau im linearen Dokumenten-Design.</p>
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
                                    Prüfung starten
                                </button>
                            @endif
                        @elseif ($isSubscribed)
                            <button type="button" wire:click="startOrResumeExam({{ $record->id }})" class="telc-button primary wide" style="background-color: #1a1a1a; border-color: #1a1a1a;">
                                Prüfung starten
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
        {{-- Check if this is a Premium C1/B2/B1 exam layout --}}
        @if (in_array($template['level'] ?? '', ['B1', 'B2', 'C1'], true))
            <div class="premium-document" wire:poll.1s="tick">
                <header style="text-align: center; margin-bottom: 5rem;">
                    <div class="c1-badge">
                        {{ ($template['exam_provider'] ?? 'telc') === 'goethe' ? 'Goethe' : 'telc' }} {{ $template['level'] ?? 'C1' }}
                    </div>
                    <h1 class="c1-title">ERFOLG</h1>
                    <p class="c1-subtitle">Deutsches Sprachzertifikat — Offizieller Übungstest</p>

                    @if ($result)
                        <div style="background: #ffffff; border: 2px solid #1a1a1a; border-radius: 8px; padding: 2.5rem; max-width: 600px; margin: 0 auto; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.05); font-family: 'Plus Jakarta Sans', sans-serif;">
                            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.15em; color: #666666; display: block; margin-bottom: 0.5rem;">Offizielles Zertifikatsergebnis</span>
                            <h3 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; color: #1a1a1a; margin-bottom: 1.5rem;">C1 Deutschprüfung</h3>
                            
                            <div style="display: grid; grid-cols: 2; gap: 1.5rem; border-top: 1px solid #e5e5e5; border-bottom: 1px solid #e5e5e5; padding: 1.5rem 0; margin-bottom: 1.5rem;">
                                <div style="text-align: center; border-right: 1px solid #e5e5e5; padding-right: 1rem;">
                                    <span style="font-size: 0.8rem; font-weight: 600; text-transform: uppercase; color: #666666; display: block;">Schriftlicher Teil</span>
                                    <strong style="font-size: 1.8rem; color: #1a1a1a; display: block; margin-top: 0.25rem;">{{ $result['written_earned'] ?? 0 }} / 166</strong>
                                    <span style="font-size: 0.85rem; font-weight: 600; color: {{ ($result['written_earned'] ?? 0) >= 99 ? '#137333' : '#c5221f' }};">
                                        {{ ($result['written_earned'] ?? 0) >= 99 ? 'Bestanden (Min. 99)' : 'Nicht bestanden' }}
                                    </span>
                                </div>
                                <div style="text-align: center; padding-left: 1rem;">
                                    <span style="font-size: 0.85rem; font-weight: 600; text-transform: uppercase; color: #666666; display: block;">Mündlicher Teil</span>
                                    <strong style="font-size: 1.8rem; color: #1a1a1a; display: block; margin-top: 0.25rem;">{{ $result['oral_earned'] ?? 0 }} / 48</strong>
                                    <span style="font-size: 0.85rem; font-weight: 600; color: {{ ($result['oral_earned'] ?? 0) >= 29 ? '#137333' : '#c5221f' }};">
                                        {{ ($result['oral_earned'] ?? 0) >= 29 ? 'Bestanden (Min. 29)' : 'Nicht bestanden' }}
                                    </span>
                                </div>
                            </div>

                            <div style="margin-bottom: 1rem;">
                                <span style="font-size: 0.9rem; color: #666666;">Gesamtpunktzahl:</span>
                                <strong style="font-size: 2.2rem; color: #1a1a1a; display: block; font-family: 'Playfair Display', serif; margin-top: 0.25rem;">{{ $result['earned'] }} / {{ $result['possible'] }}</strong>
                            </div>

                            <div style="background-color: {{ ($result['passed'] ?? false) ? '#e6f4ea' : '#fce8e6' }}; padding: 0.75rem; border-radius: 4px; font-weight: 700; font-size: 1.1rem; color: {{ ($result['passed'] ?? false) ? '#137333' : '#c5221f' }};">
                                Status: {{ $result['grade'] ?? 'Nicht bestanden' }}
                            </div>
                        </div>
                    @endif
                </header>

                @php
                    $c1LueckeOptions = [
                        'a' => 'a (Allerdings war die Atmosphäre niemals so dicht wie bei der Erde.)',
                        'b' => 'b (Auf lange Sicht wären die besten Stromlieferanten aber wohl Fusionsreaktoren.)',
                        'c' => 'c (Aus eigener Erfahrung wissen wir auch, wie man einen kühlen Planeten aufheizt:)',
                        'd' => 'd (Das 1000-Jahres-Projekt beginnt mit einer Reihe von Expeditionen.)',
                        'e' => 'e (Den Rest der Arbeit wird das Leben selber erledigen.)',
                        'f' => 'f (Der Nasa-Experte Chris McKay sieht die Aussichten jedoch nüchterner:)',
                        'g' => 'g (Doch selbst dafür fehlt es an Geld.)',
                        'h' => 'h (Es gibt daher keine Gewässer mit flüssigem Wasser auf der Marsoberfläche.)',
                    ];
                @endphp

                {{-- ==================== LESEVERSTEHEN TEIL 1 ==================== --}}
                <section class="exam-section">
                    <div class="section-label">Leseverstehen, Teil 1</div>
                    <h2 class="section-heading">Textrekonstruktion</h2>
                    <div class="c1-instructions">
                        <strong>Anweisung:</strong> Lesen Sie den folgenden Text. Welche der Sätze a–h gehören in die Lücken 1–6? Es gibt jeweils nur eine richtige Lösung. Zwei Sätze können nicht zugeordnet werden. Lücke (0) ist ein Beispiel.
                    </div>

                    <article class="c1-passage">
                        <strong>Der Mars – bald eine Reise wert?</strong><br><br>
                        Könnten wir den eisigen Mars „terraformen“? Die Antwort lautet: 
                        <span class="cloze-select-wrapper">
                            <select class="cloze-select" disabled><option>z (Wahrscheinlich wäre es zu schaffen)</option></select>
                        </span>. 
                        Wir wissen heute, dass es auf dem Mars einst wärmer war als heute. Es muss sogar Flüsse und Seen gegeben haben. 
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">1</span>
                            <select wire:model.live="answers.C1-LV1-1" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($c1LueckeOptions as $letter => $fullText)
                                    <option value="{{ $letter }}">{{ $fullText }}</option>
                                @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-LV1-1'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>
                        Indem man genug Treibhausgase in seine Atmosphäre bläst. Ein großer Teil des Kohlendioxids (CO2), das den Roten Planeten früher wärmte, ist wohl noch vorhanden – gebunden in den Eiskappen der Pole und im Boden. Gefrorenes Wasser wurde ebenfalls nachgewiesen. Technisch ist es also machbar, dort wieder Pflanzen wachsen zu lassen.<br><br>

                        „Wir müssen nur die Temperatur ein wenig erhöhen und ein paar Samen ausstreuen“, sagt der Nasa-Planetenforscher Chris McKay, „
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">2</span>
                            <select wire:model.live="answers.C1-LV1-2" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($c1LueckeOptions as $letter => $fullText)
                                    <option value="{{ $letter }}">{{ $fullText }}</option>
                                @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-LV1-2'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>
                        .“ Um den Prozess in Gang zu setzen, kann man aus der Marsoberfläche Perfluorkohlenstoffe (PFC) gewinnen. PFC sind wirksame Treibhausgase. Sobald sich die Atmosphäre aufheizt, wird gefrorenes CO2 aus den Böden frei. Ein sich verstärkender Treibhauseffekt entsteht. Der Luftdruck steigt, und irgendwann fließt wieder Wasser. Menschliche Pioniere können die Felsen mit Bakterien und Flechten „impfen“, die auf der Erde gedeihen. Später bringt man Moose aus, dann Bäume. Die Pflanzen reichern die Atmosphäre mit Sauerstoff an, doch bis wir sie atmen können, werden viele tausend Jahre vergehen.<br><br>

                        Robert Zubrin, der Präsident einer Organisation zur Besiedlung des Mars, träumt schon von Städten auf dem Mars. 
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">3</span>
                            <select wire:model.live="answers.C1-LV1-3" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($c1LueckeOptions as $letter => $fullText)
                                    <option value="{{ $letter }}">{{ $fullText }}</option>
                                @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-LV1-3'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>
                        „Wir werden auf dem Mars leben wie die Forscher heute in der Antarktis“ – in kleinen Stationen also, ohne viel Komfort. Doch aus den Erfahrungen bei der Umgestaltung des Mars könnten wir viel lernen, um künftig die Erde besser zu bewahren, meint er. Das sind freilich noch Gedankenspiele. Zunächst will die Nasa wieder auf dem Mond landen oder auf einem Asteroiden. 
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">4</span>
                            <select wire:model.live="answers.C1-LV1-4" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($c1LueckeOptions as $letter => $fullText)
                                    <option value="{{ $letter }}">{{ $fullText }}</option>
                                @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-LV1-4'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>
                        . Was es kosten würde, den Roten Planeten zu begrünen, ist noch nicht einmal geschätzt.<br><br>

                        Der Zeitplan für eine potentielle Besiedelung: 
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">5</span>
                            <select wire:model.live="answers.C1-LV1-5" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($c1LueckeOptions as $letter => $fullText)
                                    <option value="{{ $letter }}">{{ $fullText }}</option>
                                @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-LV1-5'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>
                        . Nach seinem sechs Monate dauernden Flug zum Mars installiert zunächst jedes Team eine kleine Wohneinheit und legt damit den Grundstein für die Besiedlung. 
                        Die ersten 100 Jahre: Die Atmosphäre wird dichter, wenn man das an den Polkappen gebundene CO2 freisetzt. Um das zu erreichen, erzeugen Fabriken Treibhausgase. Auch das Sonnenlicht konzentrierende Spiegel könnten das Eis auftauen. 
                        200-600 Jahre: Regen fällt und Wasser fließt, sobald ausreichend CO2 in der Atmosphäre die mittlere Temperatur über den Gefrierpunkt angehoben hat. Bakterien, Algen und Flechten beginnen, die steinige Wüste zu besiedeln. Später wachsen auch Blütenpflanzen und irdische Bäume aus nördlichen Breiten. 
                        900 Jahre: Die Energie für die Städte wird anfangs von Atomkraftwerken und Windkraftanlagen produziert. 
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">6</span>
                            <select wire:model.live="answers.C1-LV1-6" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($c1LueckeOptions as $letter => $fullText)
                                    <option value="{{ $letter }}">{{ $fullText }}</option>
                                @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-LV1-6'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>
                        1000 Jahre: Marsbewohner können nur mit Atemgeräten ins Freie, weil der Sauerstoffgehalt der Luft sehr langsam steigt. Langfristig wird der Mars seine Atmosphäre auch wieder verlieren und erneut abkühlen.
                    </article>

                    <div style="background-color: #fafafa; border: 1px solid #e5e5e5; padding: 1.5rem; font-size: 0.9rem; line-height: 1.6;">
                        <strong style="display: block; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.05em; font-size: 0.75rem;">Satzoptionen zur Auswahl:</strong>
                        <ul style="list-style: none; padding-left: 0;">
                            <li><strong>a</strong> Allerdings war die Atmosphäre niemals so dicht wie bei der Erde.</li>
                            <li><strong>b</strong> Auf lange Sicht wären die besten Stromlieferanten aber wohl Fusionsreaktoren.</li>
                            <li><strong>c</strong> Aus eigener Erfahrung wissen wir auch, wie man einen kühlen Planeten aufheizt:</li>
                            <li><strong>d</strong> Das 1000-Jahres-Projekt beginnt mit einer Reihe von Expeditionen.</li>
                            <li><strong>e</strong> Den Rest der Arbeit wird das Leben selber erledigen.</li>
                            <li><strong>f</strong> Der Nasa-Experte Chris McKay sieht die Aussichten jedoch nüchterner:</li>
                            <li><strong>g</strong> Doch selbst dafür fehlt es an Geld.</li>
                            <li><strong>h</strong> Es gibt daher keine Gewässer mit flüssigem Wasser auf der Marsoberfläche.</li>
                        </ul>
                    </div>
                </section>

                <hr class="c1-divider">

                {{-- ==================== LESEVERSTEHEN TEIL 2 ==================== --}}
                <section class="exam-section">
                    <div class="section-label">Leseverstehen, Teil 2</div>
                    <h2 class="section-heading">Selektives Verstehen</h2>
                    <div class="c1-instructions">
                        <strong>Anweisung:</strong> In welchem Textabsatz a–e finden Sie die Antworten auf die Fragen 7–12? Es gibt jeweils nur eine richtige Lösung. Jeder Absatz kann Antworten auf mehrere Fragen enthalten.
                    </div>

                    <div style="background-color: #fafafa; padding: 2rem; border-radius: 4px; margin-bottom: 3rem; font-size: 0.95rem; max-height: 350px; overflow-y: auto; border: 1px solid #e5e5e5;">
                        <h4 style="margin-bottom: 1rem; border-bottom: 1px solid #e5e5e5; padding-bottom: 0.5rem;">Ein Selbstversuch als Hobbybäuerin</h4>
                        <p style="margin-bottom: 1.25rem;"><strong>a:</strong> Ich bin stolz auf mich und fühle mich richtig gut, weil ich meine ambitionierten Ziele bis jetzt immer realisieren konnte. Damit meine ich nicht nur die beruflichen Ziele, sondern auch meine privaten... Ich muss mich bis Ende Oktober entscheiden, ob ich mein Stück Gartenland auch im nächsten Jahr wieder bestellen will...</p>
                        <p style="margin-bottom: 1.25rem;"><strong>b:</strong> Bin ich nun eine Heldin oder nicht? Nun kann man über meine Leibesstärke diskutieren... Ich war höchstens eine Heldin der Arbeit, als ich auf dem Acker herumkroch. Menschen, die mich kennen, hätten mich in meinem Outfit nicht wiedererkannt. Ich habe gebuddelt, was das Zeug hielt, und an besonders warmen Tagen konnte ich meinen Körpergeruch selbst kaum ertragen...</p>
                        <p style="margin-bottom: 1.25rem;"><strong>c:</strong> Einige Freundschaften hat es mich auch gekostet: Ein Freund redet nicht mehr mit mir, seit er zum Unkrautrupfen mitkam. Wir gerieten über die Frage, wie weit man Salat von Radieschen fernhalten sollte, in einen lächerlichen Streit... „So ein unzuverlässiges und treuloses Pack“, dachte ich mir ganz oft. Mit diesen Subjekten habe ich mal meine Zeit verbracht; welch eine Verschwendung...</p>
                        <p style="margin-bottom: 1.25rem;"><strong>d:</strong> Man muss auch beim Bewältigen der Erntemengen Ideen sprießen lassen, schließlich wird vieles gleichzeitig reif. Und man muss bestimmte Abstriche machen... Bedenken Sie, dass wir auch im Job gleichzeitig an vielen Projekten arbeiten und uns selten den Luxus gönnen, eine Sache nach der anderen abzuarbeiten... Lernen Sie Kompromisse zu machen und damit zu leben...</p>
                        <p><strong>e:</strong> Im Grunde haben wir uns beide heldenhaft geschlagen, der Acker und ich. Er hat mich einen Sommer lang genauso überlebt wie ich ihn. Wenn ich mich nächstes Jahr wieder auf das Abenteuer „Garten“ einlasse, will ich es tapfer und aufopfernd tun, aber ich will nachsichtig mit mir sein...</p>
                    </div>

                    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                        @foreach ([
                            7 => 'ist die Autorin belehrend?',
                            8 => 'ist die Autorin polemisch?',
                            9 => 'übt die Autorin Selbstironie?',
                            10 => 'übt die Autorin Kritik?',
                            11 => 'zieht die Autorin Lehren für die Zukunft?',
                            12 => 'will sich die Autorin profilieren?'
                        ] as $qNum => $qText)
                            <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed #e5e5e5; padding-bottom: 0.75rem;">
                                <span style="font-size: 0.95rem; font-weight: 500;"><strong>{{ $qNum }}.</strong> In welchem Abschnitt {{ $qText }}</span>
                                <div style="display: flex; align-items: center; gap: 0.5rem;">
                                    <select wire:model.live="answers.C1-LV2-{{ $qNum }}" class="cloze-select" @disabled($result)>
                                        <option value="">—</option>
                                        @foreach (['a','b','c','d','e'] as $opt)
                                            <option value="{{ $opt }}">Abschnitt {{ strtoupper($opt) }}</option>
                                        @endforeach
                                    </select>
                                    @if ($result)
                                        @php $det = collect($result['details'])->firstWhere('id', 'C1-LV2-' . $qNum); @endphp
                                        <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . strtoupper($det['expected'] ?? '') }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                <hr class="c1-divider">

                {{-- ==================== LESEVERSTEHEN TEIL 3 ==================== --}}
                <section class="exam-section">
                    <div class="section-label">Leseverstehen, Teil 3</div>
                    <h2 class="section-heading">Detailverstehen</h2>
                    <div class="c1-instructions">
                        <strong>Anweisung:</strong> Beantworten Sie die Aussagen 13–23. Ist die Aussage richtig (+), falsch (–) oder nicht im Text enthalten (x)?
                    </div>

                    <div style="background-color: #fafafa; padding: 2rem; border-radius: 4px; margin-bottom: 3rem; font-size: 0.95rem; max-height: 350px; overflow-y: auto; border: 1px solid #e5e5e5;">
                        <h4 style="margin-bottom: 1rem; border-bottom: 1px solid #e5e5e5; padding-bottom: 0.5rem;">Genie und Wahnsinn — Stereotypen von Wissenschaftlern im Film</h4>
                        <p style="margin-bottom: 1rem;">Die Wissenschaft und ihre Protagonisten sind inzwischen zu Lieblingen der Filmschaffenden avanciert... Mit Alan Turing und Stephen Hawking wurden innerhalb eines Jahres in „The Imitation Game“ beziehungsweise „The Theory of Everything“ gleich zwei genialen Forschern monumentale filmische Denkmäler gesetzt...</p>
                        <p style="margin-bottom: 1rem;">All das ist insofern bemerkenswert, als alle westlichen Filmkulturen lange Zeit auf wenige, sich ähnelnde Typen von Wissenschaftlern setzten... Die Ambivalenzen einer komplexen Persönlichkeit suchte man meist vergeblich...</p>
                        <p style="margin-bottom: 1rem;">In der überwiegenden Mehrzahl der Fälle ist der Wissenschaftler im Film ein Verrückter, ein „mad scientist“. In seiner bösen, düsteren Ausprägung geht er in geheimen Labors monströsen Forschungsprojekten nach... Bösewichte dieser Art begegnen uns als „Dr. No“ oder „Dr. Seltsam“...</p>
                        <p>Peter Weingart und Petra Pansegrau von der Universität Bielefeld konstatieren: „Im Film gibt es viele schlechte und nur wenige gute Wissenschaftler, aber vor allem nur eine überschaubare Anzahl an Stereotypen“...</p>
                    </div>

                    <table class="tf-option-table">
                        <thead>
                            <tr>
                                <th style="width: 70%;">Aussage</th>
                                <th style="width: 30%; text-align: center;">Bewertung (+ / - / x)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ([
                                13 => 'In den Unterhaltungsfilmen der letzten Jahre werden Wissenschaftler vorwiegend als skrupellose Bösewichte dargestellt.',
                                14 => 'In Science-Fiction-Filmen verbinden sich archaische Erzähltraditionen mit aktuellen Tendenzen der Wissenschaft.',
                                15 => 'In der Filmgeschichte ist die Bandbreite der Wissenschaftler-Typen ausgesprochen vielschichtig.',
                                16 => 'Der „böse“ verrückte Wissenschaftler hat nur ein Ziel: die Weltherrschaft zu erobern.',
                                17 => 'Fiktive Figuren wie Bruce Banner oder Indiana Jones sind in der Öffentlichkeit bekannter als reale Forscherpersönlichkeiten.',
                                18 => 'Die Figur des verrückten Wissenschaftlers hatte bereits im Mittelalter literarische Vorläufer.',
                                19 => 'Reale Wissenschaftlerinnen und Wissenschaftler kritisieren das Rollenbild des „mad scientist“ in Unterhaltungsfilmen.',
                                20 => 'Die Gesellschaft hat auch heute noch ein zwiespältiges Verhältnis zur Wissenschaft und Technik.',
                                21 => 'Der Wandel der Wissenschaftlerfigur ist auf eine generelle Veränderung der Erzählweise im Film zurückzuführen.',
                                22 => 'Heutzutage werden Wissenschaftler im Film zunehmend als sympathische Sonderlinge mit außerordentlicher Fachkompetenz dargestellt.',
                                23 => 'Im Gegensatz zu den „mad scientists“ üben moderne Wissenschaftlerfiguren systematisch Kritik an wissenschaftlicher Forschung.'
                            ] as $idx => $statement)
                                <tr>
                                    <td><strong>{{ $idx }}.</strong> {{ $statement }}</td>
                                    <td style="text-align: center; white-space: nowrap;">
                                        @foreach (['+', '-', 'x'] as $choice)
                                            <label style="margin: 0 0.25rem;">
                                                <input type="radio" 
                                                       wire:model.live="answers.C1-LV3-{{ $idx }}" 
                                                       value="{{ $choice }}" 
                                                       class="tf-choice-input" 
                                                       @disabled($result)>
                                                <span class="tf-choice-label">{{ $choice }}</span>
                                            </label>
                                        @endforeach
                                        @if ($result)
                                            @php $det = collect($result['details'])->firstWhere('id', 'C1-LV3-' . $idx); @endphp
                                            <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div style="margin-top: 3rem;">
                        <p class="mc-question-text"><strong>24.</strong> Welche der Überschriften a, b oder c trifft die Aussage des Textes am besten?</p>
                        <div class="mc-options-group" style="margin-top: 1rem;">
                            @foreach ([
                                'a' => 'a) Fakt verus Fiktion – Wissenschaftler wollen ihr Image im Film verbessern',
                                'b' => 'b) Verrückt und gefährlich – warum Wissenschaftler im Film immer die Bösen sind',
                                'c' => 'c) Zwischen Genie und Wahnsinn – Stereotypen von Wissenschaftlern im Film'
                            ] as $key => $titleOption)
                                <label class="mc-option-item" :class="$wire.answers['C1-LV3-24'] === '{{ $key }}' ? 'selected' : ''">
                                    <input type="radio" wire:model.live="answers.C1-LV3-24" value="{{ $key }}" class="mc-radio-input" @disabled($result)>
                                    <span class="mc-option-bullet"></span>
                                    <span class="mc-option-content">{{ $titleOption }}</span>
                                    @if ($result && $key === 'c')
                                        <span class="feedback-badge ok">Korrekte Antwort</span>
                                    @endif
                                </label>
                            @endforeach
                        </div>
                    </div>
                </section>

                <hr class="c1-divider">

                {{-- ==================== SPRACHBAUSTEINE ==================== --}}
                <section class="exam-section">
                    <div class="section-label">Sprachbausteine</div>
                    <h2 class="section-heading">Grammatik und Lexik</h2>
                    <div class="c1-instructions">
                        <strong>Anweisung:</strong> Lesen Sie den folgenden Text. Welche Option (a, b, c oder d) ist für jede Lücke richtig? Wählen Sie die passende Antwort aus dem jeweiligen Dropdown-Feld.
                    </div>

                    @php
                        $sbOptions = [
                            25 => ['a' => 'dagegen', 'b' => 'doch', 'c' => 'hingegen', 'd' => 'jedoch'],
                            26 => ['a' => 'anderweitig', 'b' => 'artfremd', 'c' => 'auswärtig', 'd' => 'fremdartig'],
                            27 => ['a' => 'durchweg', 'b' => 'extra', 'c' => 'gehörig', 'd' => 'überaus'],
                            28 => ['a' => 'vermeintlich', 'b' => 'vermeintliche', 'c' => 'vermeintlichem', 'd' => 'vermeintlicher'],
                            29 => ['a' => 'dank', 'b' => 'infolge', 'c' => 'kraft', 'd' => 'zwecks'],
                            30 => ['a' => 'hervor', 'b' => 'hinaus', 'c' => 'voraus', 'd' => 'zurück'],
                            31 => ['a' => 'veränderbare', 'b' => 'veränderliche', 'c' => 'verändernde', 'd' => 'veränderte'],
                            32 => ['a' => 'sei', 'b' => 'war', 'c' => 'wäre', 'd' => 'würde'],
                            33 => ['a' => 'für sich hatte', 'b' => 'mit sich zog', 'c' => 'von sich gab', 'd' => 'vor sich ging'],
                            34 => ['a' => 'angeschrieben', 'b' => 'gutgeschrieben', 'c' => 'vorgeschrieben', 'd' => 'zugeschrieben'],
                            35 => ['a' => 'Desto', 'b' => 'So sehr', 'c' => 'So weit', 'd' => 'Weit mehr'],
                            36 => ['a' => 'behandelnde', 'b' => 'behandelnden', 'c' => 'behandelte', 'd' => 'behandelten'],
                            37 => ['a' => 'als', 'b' => 'das', 'c' => 'die', 'd' => 'wie'],
                            38 => ['a' => 'der Art', 'b' => 'Derart', 'c' => 'derart', 'd' => 'der-Art'],
                            39 => ['a' => 'im Blick', 'b' => 'in Sicht', 'c' => 'ins Auge', 'd' => 'zu Gesicht'],
                            40 => ['a' => 'bekannt gewesen war', 'b' => 'bekannt geworden', 'c' => 'bekannt sind', 'd' => 'bekannt wurden'],
                            41 => ['a' => 'Dem ersten Patienten', 'b' => 'Den ersten Patienten', 'c' => 'Der erste Patient', 'd' => 'Des ersten Patienten'],
                            42 => ['a' => 'daraufhin', 'b' => 'indem', 'c' => 'nach', 'd' => 'nachdem'],
                            43 => ['a' => 'auf', 'b' => 'für', 'c' => 'über', 'd' => 'von'],
                            44 => ['a' => 'ist', 'b' => 'soll', 'c' => 'war', 'd' => 'wurde'],
                            45 => ['a' => 'benannt', 'b' => 'benennen', 'c' => 'benennen können', 'd' => 'zu benennen'],
                            46 => ['a' => 'an einen', 'b' => 'auf einen', 'c' => 'mit einem', 'd' => 'zu einem'],
                        ];
                    @endphp

                    <article class="c1-passage" style="line-height: 2.3;">
                        <strong>Ein merkwürdiger Akzent</strong><br><br>
                        Stellen Sie sich vor, Sie wachen eines Morgens auf und wünschen Ihrem Partner einen guten Morgen – 
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">25</span>
                            <select wire:model.live="answers.C1-SB-25" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($sbOptions[25] as $k => $o) <option value="{{ $k }}">{{ $o }}</option> @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-SB-25'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>
                        die Worte aus Ihrem Mund klingen 
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">26</span>
                            <select wire:model.live="answers.C1-SB-26" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($sbOptions[26] as $k => $o) <option value="{{ $k }}">{{ $o }}</option> @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-SB-26'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>. 
                        Dann könnte es sich um eine 
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">27</span>
                            <select wire:model.live="answers.C1-SB-27" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($sbOptions[27] as $k => $o) <option value="{{ $k }}">{{ $o }}</option> @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-SB-27'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>
                        seltene neurologische Störung handeln: das „Foreign Accent Syndrome“, zu Deutsch Fremdsprachenakzentsyndrom.<br><br>

                        Beim Fremdsprachenakzentsyndrom sprechen Patienten plötzlich ihre Muttersprache mit einem 
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">28</span>
                            <select wire:model.live="answers.C1-SB-28" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($sbOptions[28] as $k => $o) <option value="{{ $k }}">{{ $o }}</option> @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-SB-28'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>
                        regionalen oder ausländischen Akzent. Meist geht die veränderte Sprechweise auf Hirnschäden 
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">29</span>
                            <select wire:model.live="answers.C1-SB-29" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($sbOptions[29] as $k => $o) <option value="{{ $k }}">{{ $o }}</option> @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-SB-29'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>
                        eines Schlaganfalls oder einer Verletzung 
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">30</span>
                            <select wire:model.live="answers.C1-SB-30" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($sbOptions[30] as $k => $o) <option value="{{ $k }}">{{ $o }}</option> @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-SB-30'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>. 
                        Die Erkrankung beeinflusst die Sprachmelodie, wobei die 
                        <span class="cloze-select-wrapper">
                            <span class="cloze-gap-num">31</span>
                            <select wire:model.live="answers.C1-SB-31" class="cloze-select" @disabled($result)>
                                <option value="">—</option>
                                @foreach ($sbOptions[31] as $k => $o) <option value="{{ $k }}">{{ $o }}</option> @endforeach
                            </select>
                            @if ($result)
                                @php $det = collect($result['details'])->firstWhere('id', 'C1-SB-31'); @endphp
                                <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                            @endif
                        </span>
                        Lautbildung und Betonung fälschlicherweise den Eindruck erweckt, der Betroffene sei kein Muttersprachler.
                    </article>
                </section>

                <hr class="c1-divider">

                {{-- ==================== HÖRVERSTEHEN ==================== --}}
                <section class="exam-section">
                    <div class="section-label">Hörverstehen</div>
                    <h2 class="section-heading">Audio-Auswertung</h2>
                    <div class="c1-instructions">
                        <strong>Anweisung:</strong> Starten Sie die Audiodatei und bearbeiten Sie die Aufgaben 47-74.
                    </div>

                    <audio controls src="/telc-assets/telc_deutsch_c1_uebungstest_1_format_2016.mp3" style="width: 100%; margin-bottom: 3rem;"></audio>

                    <!-- HV Part 1 -->
                    <div style="margin-bottom: 4rem;">
                        <h4 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem;">Teil 1: Kontroverse Meinungen über den Zoo (Aufgaben 47-54)</h4>
                        <p style="color: #666666; font-size: 0.9rem; margin-bottom: 1.5rem;">Ordnen Sie den Sprechern 1-8 die passende Aussage a-j zu. Zwei Aussagen passen nicht.</p>
                        
                        <div style="display: flex; flex-direction: column; gap: 1rem;">
                            @foreach (range(1, 8) as $speakerNum)
                                @php $elIdx = 46 + $speakerNum; @endphp
                                <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px dashed #e5e5e5; padding-bottom: 0.5rem;">
                                    <span>Sprecher / Sprecherin {{ $speakerNum }}</span>
                                    <div style="display: flex; align-items: center;">
                                        <select wire:model.live="answers.C1-HV1-{{ $elIdx }}" class="cloze-select" @disabled($result)>
                                            <option value="">—</option>
                                            @foreach (['a','b','c','d','e','f','g','h','i','j'] as $let)
                                                <option value="{{ $let }}">Aussage {{ strtoupper($let) }}</option>
                                            @endforeach
                                        </select>
                                        @if ($result)
                                            @php $det = collect($result['details'])->firstWhere('id', 'C1-HV1-' . $elIdx); @endphp
                                            <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . strtoupper($det['expected'] ?? '') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- HV Part 2 -->
                    <div style="margin-bottom: 4rem;">
                        <h4 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1.5rem;">Teil 2: Interview zum Thema Gruppenreisen (Aufgaben 55-64)</h4>
                        
                        <div class="mc-questions-list">
                            @php
                                $hv2Questions = [
                                    55 => 'Herr Rimbach ist für die Reisegruppe leicht erkennbar, obwohl er...',
                                    56 => 'Herr Rimbach setzt Körpersprache bewusst ein, um...',
                                    57 => 'Nörgler und Unruhestifter...',
                                    58 => 'In schwierigen Situationen...',
                                    59 => 'Beschwerden über das Essen sind nachvollziehbar, weil...'
                                ];
                                $hv2Options = [
                                    55 => ['a' => 'keine auffälligen Requisiten dabei hat.', 'b' => 'nicht durch seine Körpergröße auffällt.', 'c' => 'unauffällige Kleidung bevorzugt.'],
                                    56 => ['a' => 'einen positiven ersten Eindruck zu hinterlassen.', 'b' => 'seine eigene Nervosität zu überspielen.', 'c' => 'Urlaubern die Nervosität zu nehmen.'],
                                    57 => ['a' => 'erkennt Herr Rimbach immer auf den ersten Blick.', 'b' => 'fallen oft schon am Anreisetag unangenehm auf.', 'c' => 'reagieren in Krisensituationen besonders panisch.'],
                                    58 => ['a' => 'benötigen ältere Urlauber oft besondere Aufmerksamkeit.', 'b' => 'braucht Herr Rimbach manchmal schauspielerisches Talent.', 'c' => 'kann Herr Rimbach oft schnell eine Lösung finden.'],
                                    59 => ['a' => 'das Angebot oft etwas eintönig ist.', 'b' => 'keine landestypischen Gerichte serviert werden.', 'c' => 'ungewohnte Zutaten verwendet werden.']
                                ];
                            @endphp

                            @foreach ($hv2Questions as $qIdx => $qText)
                                <div class="mc-question-item">
                                    <p class="mc-question-text">{{ $qIdx }}. {{ $qText }}</p>
                                    <div class="mc-options-group">
                                        @foreach ($hv2Options[$qIdx] as $val => $text)
                                            <label class="mc-option-item" :class="$wire.answers['C1-HV2-{{ $qIdx }}'] === '{{ $val }}' ? 'selected' : ''">
                                                <input type="radio" wire:model.live="answers.C1-HV2-{{ $qIdx }}" value="{{ $val }}" class="mc-radio-input" @disabled($result)>
                                                <span class="mc-option-bullet"></span>
                                                <span class="mc-option-content">{{ strtoupper($val) }}) {{ $text }}</span>
                                            </label>
                                        @endforeach
                                        @if ($result)
                                            @php $det = collect($result['details'])->firstWhere('id', 'C1-HV2-' . $qIdx); @endphp
                                            <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . strtoupper($det['expected'] ?? '') }}</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- HV Part 3 -->
                    <div>
                        <h4 style="font-size: 1.1rem; font-weight: 600; margin-bottom: 1.5rem;">Teil 3: Vortragsreihe „Schöner wohnen“ (Aufgaben 65-74)</h4>
                        <p style="color: #666666; font-size: 0.9rem; margin-bottom: 2rem;">Vervollständigen Sie die Notizen stichwortartig.</p>

                        <div style="display: flex; flex-direction: column; gap: 2rem;">
                            @php
                                $hv3Prompts = [
                                    65 => 'Die neue Wandfarbe bewirkt...',
                                    66 => 'Wir unterscheiden beim Auge...',
                                    67 => 'Weiße Wände? Nein, weil...',
                                    68 => 'Alternativen zu reinem Weiß...',
                                    69 => 'Die richtige Farbwahl hängt ab von...'
                                ];
                            @endphp

                            @foreach ($hv3Prompts as $idx => $prompt)
                                <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                    <label style="font-size: 0.95rem; font-weight: 600;"><strong>{{ $idx }}.</strong> {{ $prompt }}</label>
                                    <input type="text" 
                                           wire:model.live="answers.C1-HV3-{{ $idx }}" 
                                           class="hv3-lined-input" 
                                           placeholder="Ihre Notiz schreiben..." 
                                           @disabled($result)>
                                    @if ($result)
                                        @php $det = collect($result['details'])->firstWhere('id', 'C1-HV3-' . $idx); @endphp
                                        <div style="margin-top: 0.25rem;">
                                            <span class="feedback-badge {{ $det && $det['correct'] ? 'ok' : 'fail' }}">{{ $det && $det['correct'] ? '✓' : '✗ ' . ($det['expected'] ?? '') }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>

                <hr class="c1-divider">

                {{-- ==================== SCHRIFTLICHER AUSDRUCK ==================== --}}
                <section class="exam-section" x-data="{ thema: 1 }">
                    <div class="section-label">Schriftlicher Ausdruck</div>
                    <h2 class="section-heading">Stellungnahme</h2>
                    <div class="c1-instructions">
                        <strong>Anweisung:</strong> Wählen Sie eines der beiden Themen aus und verfassen Sie einen zusammenhängenden Text (ca. 350 Wörter).
                    </div>

                    <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
                        <button type="button" @click="thema = 1" class="telc-button" :style="thema === 1 ? 'background: #1a1a1a; color: #ffffff;' : 'background: #f7f7f7; color: #1a1a1a;'">Thema 1: Spendenbereitschaft</button>
                        <button type="button" @click="thema = 2" class="telc-button" :style="thema === 2 ? 'background: #1a1a1a; color: #ffffff;' : 'background: #f7f7f7; color: #1a1a1a;'">Thema 2: Überwachungssoftware</button>
                    </div>

                    <div x-show="thema === 1" class="writing-guidelines">
                        <h4>Thema 1: Soll man Geld spenden?</h4>
                        <p>Diskutieren Sie, ob Spendengelder tatsächlich sinnvoll eingesetzt werden oder primär in der Verwaltung versickern. Nehmen Sie Stellung.</p>
                    </div>

                    <div x-show="thema === 2" class="writing-guidelines">
                        <h4>Thema 2: Überwachungssoftware für Handys der Kinder</h4>
                        <p>Diskutieren Sie, ob Eltern die Handyaktivitäten ihrer Kinder überwachen sollten. Wie verhält es sich mit dem Recht auf Privatsphäre?</p>
                    </div>

                    <textarea wire:model.live="answers.C1-WRITE-1" 
                              class="writing-textarea-lined" 
                              placeholder="Verfassen Sie hier Ihren Aufsatz..." 
                              @disabled($result)></textarea>

                    @if ($result)
                        @php
                            $writingDetail = collect($result['details'])->firstWhere('id', 'C1-WRITE-1');
                        @endphp
                        @if ($writingDetail)
                            <div style="margin-top: 1.5rem; background-color: #f7f9fa; border: 1px solid #d9dde5; border-left: 4px solid #d8232a; padding: 1.5rem; border-radius: 4px; text-align: left;">
                                <h5 style="font-weight: 700; font-size: 0.95rem; text-transform: uppercase; color: #d8232a; margin-bottom: 0.5rem; font-family: 'Plus Jakarta Sans', sans-serif;">AI-Bewertung: Schriftlicher Ausdruck</h5>
                                <strong style="font-size: 1.1rem; color: #1a1a1a; display: block; margin-bottom: 0.5rem;">Erreichte Punkte: {{ $writingDetail['points'] }} / 48</strong>
                                <p style="font-size: 0.95rem; line-height: 1.5; color: #4a4a4a; white-space: pre-wrap;">{{ $writingDetail['explanation'] ?? 'Keine ausführliche Begründung verfügbar.' }}</p>
                            </div>
                        @endif
                    @endif
                </section>

                        <section class="exam-section">
                            <div class="section-label">Mündliche Prüfung</div>
                            <h2 class="section-heading">Mündlicher Ausdruck — Interaktive Prüfungssimulation (48 Punkte)</h2>
                            <div class="c1-instructions">
                                <strong>Prüfungsstruktur:</strong><br>
                                • <strong>Teil 1a: Präsentation</strong> (Ihr Vortrag, max. 6 Punkte)<br>
                                • <strong>Teil 1b: Zusammenfassung & Anschlussfragen</strong> (KI trägt den Vortrag zum anderen Thema vor, Sie antworten darauf, max. 4 Punkte)<br>
                                • <strong>Teil 2: Diskussion</strong> (Diskussion zu einem Zitat, max. 6 Punkte)<br>
                                • <strong>Sprachliche Angemessenheit</strong> (Übergeordnete Bewertung nach Flüssigkeit, Repertoire, Grammatik, Aussprache, max. 32 Punkte)
                            </div>

                            <div style="background-color: #f7f9fa; border: 1px solid #d9dde5; border-left: 4px solid #1a1a1a; padding: 1.25rem 1.5rem; margin-bottom: 2rem; border-radius: 4px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1rem;">
                                <div style="text-align: left;">
                                    <strong style="color: #1a1a1a; font-size: 0.95rem; display: block;">Möchten Sie freies Sprechen im Labor üben?</strong>
                                    <span style="font-size: 0.85rem; color: #666666;">Nutzen Sie das dedizierte Sprechübungs-Labor mit frei wählbaren Timern.</span>
                                </div>
                                <a href="{{ route('voice.practice') }}" target="_blank" class="telc-button" style="padding: 0.5rem 1rem; font-size: 0.85rem; font-weight: 600; text-decoration: none; border: 1px solid #1a1a1a; color: #1a1a1a; background: none;">
                                    Labor im neuen Fenster öffnen ↗
                                </a>
                            </div>

                            @if (!$c1SelectedTopic)
                                <div style="background: #ffffff; border: 1px solid #e5e5e5; padding: 2.5rem; border-radius: 6px; text-align: center; font-family: 'Plus Jakarta Sans', sans-serif;">
                                    <h4 style="font-family: 'Playfair Display', serif; font-size: 1.6rem; color: #1a1a1a; margin-bottom: 1rem;">Wählen Sie ein Präsentationsthema für Teil 1a</h4>
                                    <p style="color: #666666; font-size: 0.95rem; margin-bottom: 2rem;">Das andere Thema wird für die KI-Präsentation in Teil 1b verwendet.</p>
                                    
                                    <div style="display: flex; flex-direction: column; gap: 1rem; max-width: 600px; margin: 0 auto;">
                                        <button type="button" wire:click="chooseC1Topic('Was ist der Nationalsport in einem Land Ihrer Wahl und welche Bedeutung hat er für die Gesellschaft?')" class="telc-button primary" style="background: #ffffff; color: #1a1a1a; border: 1px solid #cbcbcb; padding: 1.25rem; text-align: left; font-size: 0.95rem; line-height: 1.5; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                            Thema A: Was ist der Nationalsport in einem Land Ihrer Wahl und welche Bedeutung hat er für die Gesellschaft? Berichten Sie!
                                        </button>
                                        <button type="button" wire:click="chooseC1Topic('Wer gehört Ihrer Meinung nach zu den einflussreichsten lebenden Personen weltweit? Wodurch zeichnet sich diese Person aus?')" class="telc-button primary" style="background: #ffffff; color: #1a1a1a; border: 1px solid #cbcbcb; padding: 1.25rem; text-align: left; font-size: 0.95rem; line-height: 1.5; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                            Thema B: Wer gehört Ihrer Meinung nach zu den einflussreichsten lebenden Personen weltweit? Wodurch zeichnet sich diese Person aus? Erzählen Sie!
                                        </button>
                                    </div>
                                </div>
                            @else
                                <!-- Step 1: User Presentation -->
                                <div class="speaking-task" style="border: 1px solid #e5e5e5; padding: 2rem; border-radius: 6px; background-color: #ffffff; margin-bottom: 2.5rem;"
                                     x-data="{
                                         isRecording: false,
                                         recognition: null,
                                         transcript: '',
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
                                                 this.transcript = t;
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
                                         sendReply() {
                                             $wire.call('analyzeSpeaking', 'C1-SP-1a', this.transcript);
                                             this.transcript = '';
                                         }
                                     }">
                                    <div style="border-bottom: 1px solid #e5e5e5; padding-bottom: 0.75rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                                        <span style="font-weight: 700; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em; color: #d8232a;">Teil 1a: Präsentation (Max. 6 Punkte)</span>
                                        <span style="background: #f7f7f7; padding: 0.25rem 0.5rem; font-size: 0.8rem; border-radius: 4px;">Status: Step 1 / 3</span>
                                    </div>

                                    <p style="font-size: 0.95rem; line-height: 1.6; color: #4a4a4a; background-color: #fafafa; padding: 1rem; border-left: 3px solid #d8232a; margin-bottom: 1.5rem; text-align: left;">
                                        <strong>Ihr gewähltes Thema:</strong> {{ $c1SelectedTopic }}
                                    </p>

                                    @if (!empty($speakingHistory['C1-SP-1a'] ?? []))
                                        <div style="background-color: #f7f9fa; border: 1px solid #e5e5e5; border-radius: 4px; padding: 1.25rem; margin-bottom: 1.5rem; text-align: left;">
                                            @foreach ($speakingHistory['C1-SP-1a'] as $turn)
                                                <div style="margin-bottom: 0.75rem;">
                                                    <strong style="font-size: 0.8rem; color: #666666;">{{ $turn['role'] === 'user' ? 'Ihr Vortrag' : 'Prüfer-Bewertung' }}:</strong>
                                                    <p style="font-size: 0.95rem; line-height: 1.5; margin-top: 0.25rem; white-space: pre-wrap;">{{ $turn['text'] }}</p>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if (empty($speakingHistory['C1-SP-1a'] ?? []))
                                        <div style="display: flex; gap: 1rem; align-items: center;">
                                            <button type="button" @click="toggleRecording" class="speaking-record-btn" :style="isRecording ? 'background-color: #1a1a1a;' : 'background-color: #d8232a;'" style="color: #ffffff; border: none; padding: 0.85rem 1.5rem; border-radius: 4px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; white-space: nowrap;">
                                                <span x-text="isRecording ? '⏹ Stoppen' : '🎤 Vortrag sprechen'"></span>
                                            </button>
                                            <span style="color: #666666; font-size: 0.9rem;">oder</span>
                                            <input type="text" x-model="transcript" placeholder="Transkription eingeben..." style="flex-grow: 1; border: 1px solid #cbcbcb; padding: 0.85rem 1rem; border-radius: 4px; font-size: 0.95rem; outline: none;" @keydown.enter.prevent="sendReply">
                                            <button type="button" @click="sendReply" class="telc-button primary" :disabled="$wire.speakingProcessing['C1-SP-1a'] || !transcript.trim()" style="padding: 0.85rem 1.5rem; white-space: nowrap;">
                                                Abschicken
                                            </button>
                                        </div>
                                    @else
                                        <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 0.75rem 1rem; border-radius: 4px; font-size: 0.9rem; color: #15803d; font-weight: 600; text-align: left;">
                                            ✓ Teil 1a abgeschlossen und bewertet.
                                        </div>
                                    @endif
                                </div>

                                <!-- Step 2: AI Presentation on Unchosen Topic -->
                                @if ($c1SpeakingStep >= 2)
                                    <div class="speaking-task" style="border: 1px solid #e5e5e5; padding: 2rem; border-radius: 6px; background-color: #ffffff; margin-bottom: 2.5rem;"
                                         x-data="{
                                             isSynthesizing: false,
                                             playAudio() {
                                                 const utterance = new SpeechSynthesisUtterance(@js($c1AiResponseText));
                                                 utterance.lang = 'de-DE';
                                                 utterance.rate = 0.95;
                                                 utterance.onstart = () => { this.isSynthesizing = true; };
                                                 utterance.onend = () => { this.isSynthesizing = false; };
                                                 window.speechSynthesis.speak(utterance);
                                             },
                                             stopAudio() {
                                                 window.speechSynthesis.cancel();
                                                 this.isSynthesizing = false;
                                             }
                                         }"
                                         x-init="setTimeout(() => playAudio(), 500)">
                                        <div style="border-bottom: 1px solid #e5e5e5; padding-bottom: 0.75rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-weight: 700; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em; color: #d8232a;">Teil 1b: KI-Vortrag & Ihre Reaktion (Max. 4 Punkte)</span>
                                            <span style="background: #f7f7f7; padding: 0.25rem 0.5rem; font-size: 0.8rem; border-radius: 4px;">Status: Step 2 / 3</span>
                                        </div>

                                        <p style="font-size: 0.95rem; line-height: 1.5; color: #666666; margin-bottom: 1.5rem; text-align: left;">
                                            Hören Sie sich den C1-Vortrag der KI zum alternativen Thema an und reagieren Sie anschließend stichhaltig darauf.
                                        </p>

                                        <div style="background-color: #fafafa; border: 1px solid #cbcbcb; border-radius: 4px; padding: 1.5rem; margin-bottom: 1.5rem; text-align: left; position: relative;">
                                            <div style="display: flex; gap: 0.75rem; align-items: center; margin-bottom: 1rem;">
                                                <button type="button" @click="isSynthesizing ? stopAudio() : playAudio()" style="background-color: #1a1a1a; color: #ffffff; border: none; padding: 0.5rem 1rem; border-radius: 4px; font-size: 0.85rem; font-weight: 600; cursor: pointer;">
                                                    <span x-show="!isSynthesizing">🔊 Vortrag anhören</span>
                                                    <span x-show="isSynthesizing">⏹ Stoppen</span>
                                                </button>
                                                <span x-show="isSynthesizing" style="font-size: 0.85rem; color: #d8232a; font-weight: 600; animation: pulse 1.5s infinite;">Spielt Audio mit Gemini 2.5 Voice...</span>
                                            </div>
                                            <strong style="display: block; font-size: 0.85rem; color: #1a1a1a; margin-bottom: 0.5rem;">KI-Vortrag (Transkript):</strong>
                                            <div style="font-size: 0.95rem; line-height: 1.6; color: #4a4a4a; white-space: pre-wrap;">{{ $c1AiResponseText }}</div>
                                        </div>

                                        <!-- User response dialogue step -->
                                        <div x-data="{
                                             isRecording: false,
                                             recognition: null,
                                             transcript: '',
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
                                                     this.transcript = t;
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
                                             sendReply() {
                                                 $wire.call('analyzeSpeaking', 'C1-SP-1b', this.transcript);
                                                 this.transcript = '';
                                             }
                                         }">
                                            @if ($c1SpeakingStep === 2)
                                                <div style="border-top: 1px solid #e5e5e5; padding-top: 1.5rem; display: flex; gap: 1rem; align-items: center;">
                                                    <button type="button" @click="toggleRecording" class="speaking-record-btn" :style="isRecording ? 'background-color: #1a1a1a;' : 'background-color: #d8232a;'" style="color: #ffffff; border: none; padding: 0.85rem 1.5rem; border-radius: 4px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; white-space: nowrap;">
                                                        <span x-text="isRecording ? '⏹ Stoppen' : '🎤 Auf Vortrag antworten'"></span>
                                                    </button>
                                                    <span style="color: #666666; font-size: 0.9rem;">oder</span>
                                                    <input type="text" x-model="transcript" placeholder="Ihre Anschlussfragen / Zusammenfassung tippen..." style="flex-grow: 1; border: 1px solid #cbcbcb; padding: 0.85rem 1rem; border-radius: 4px; font-size: 0.95rem; outline: none;" @keydown.enter.prevent="sendReply">
                                                    <button type="button" @click="sendReply" class="telc-button primary" :disabled="$wire.speakingProcessing['C1-SP-1b'] || !transcript.trim()" style="padding: 0.85rem 1.5rem; white-space: nowrap;">
                                                        Abschicken
                                                    </button>
                                                </div>
                                            @else
                                                @if (isset($speakingHistory['C1-SP-1b'][1]))
                                                    <div style="margin-top: 1.5rem; padding: 1rem; background-color: #f7f9fa; border: 1px solid #e5e5e5; border-radius: 4px; text-align: left;">
                                                        <strong style="font-size: 0.85rem; color: #666666;">Ihre Reaktion:</strong>
                                                        <p style="font-size: 0.95rem; margin-top: 0.25rem;">{{ $speakingHistory['C1-SP-1b'][1]['text'] }}</p>
                                                        
                                                        @if (isset($speakingHistory['C1-SP-1b'][2]))
                                                            <div style="margin-top: 1rem; border-top: 1px dashed #e5e5e5; padding-top: 0.75rem;">
                                                                <strong style="font-size: 0.85rem; color: #1a1a1a;">Bewertung der Reaktion:</strong>
                                                                <p style="font-size: 0.95rem; margin-top: 0.25rem; white-space: pre-wrap;">{{ $speakingHistory['C1-SP-1b'][2]['text'] }}</p>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                                
                                                <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 0.75rem 1rem; border-radius: 4px; font-size: 0.9rem; color: #15803d; font-weight: 600; text-align: left; margin-top: 1rem;">
                                                    ✓ Teil 1b abgeschlossen und bewertet.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <!-- Step 3: Discussion -->
                                @if ($c1SpeakingStep >= 3)
                                    <div class="speaking-task" style="border: 1px solid #e5e5e5; padding: 2rem; border-radius: 6px; background-color: #ffffff; margin-bottom: 2.5rem;"
                                         x-data="{
                                             isRecording: false,
                                             recognition: null,
                                             transcript: '',
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
                                                     this.transcript = t;
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
                                             sendReply() {
                                                 $wire.call('analyzeSpeaking', 'C1-SP-2', this.transcript);
                                                 this.transcript = '';
                                             }
                                         }">
                                        <div style="border-bottom: 1px solid #e5e5e5; padding-bottom: 0.75rem; margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
                                            <span style="font-weight: 700; text-transform: uppercase; font-size: 0.85rem; letter-spacing: 0.05em; color: #d8232a;">Teil 2: Diskussion (6 Minuten, Max. 6 Punkte)</span>
                                            <span style="background: #f7f7f7; padding: 0.25rem 0.5rem; font-size: 0.8rem; border-radius: 4px;">Status: Step 3 / 3</span>
                                        </div>

                                        <div style="background-color: #fafafa; border-left: 3px solid #1a1a1a; padding: 1rem; font-size: 0.95rem; color: #4a4a4a; text-align: left; margin-bottom: 1.5rem;">
                                            <strong>Diskussionsthema:</strong> Beziehungen mit großem Altersunterschied sind immer problematisch.
                                        </div>

                                        <div style="background-color: #f7f9fa; border: 1px solid #e5e5e5; border-radius: 4px; padding: 1.5rem; min-height: 120px; max-height: 350px; overflow-y: auto; margin-bottom: 1.5rem; display: flex; flex-direction: column; gap: 1rem;">
                                            @if (empty($speakingHistory['C1-SP-2'] ?? []))
                                                <div style="color: #666666; font-style: italic; font-size: 0.95rem; text-align: center; margin-top: 1.5rem;">
                                                    Keine Beiträge. Starten Sie das Gespräch, indem Sie das Wort ergreifen.
                                                </div>
                                            @else
                                                @foreach ($speakingHistory['C1-SP-2'] as $turn)
                                                    <div style="display: flex; flex-direction: column; align-items: {{ $turn['role'] === 'user' ? 'flex-end' : 'flex-start' }}">
                                                        <span style="font-size: 0.75rem; font-weight: 700; color: #666666; margin-bottom: 0.25rem;">
                                                            {{ $turn['role'] === 'user' ? 'Lernender' : 'Prüfer' }}
                                                        </span>
                                                        <div style="background-color: {{ $turn['role'] === 'user' ? '#1a1a1a' : '#ffffff' }}; color: {{ $turn['role'] === 'user' ? '#ffffff' : '#1a1a1a' }}; border: 1px solid #e5e5e5; padding: 0.75rem 1rem; border-radius: 6px; max-width: 80%; font-size: 0.95rem; line-height: 1.5; text-align: left;">
                                                            {!! nl2br(e($turn['text'])) !!}
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @endif
                                        </div>

                                        @php
                                            $discTurnsCount = collect($speakingHistory['C1-SP-2'] ?? [])->where('role', 'user')->count();
                                            $isDiscCompleted = $discTurnsCount >= 3;
                                        @endphp

                                        @if (!$isDiscCompleted)
                                            <div style="display: flex; gap: 1rem; align-items: center;">
                                                <button type="button" @click="toggleRecording" class="speaking-record-btn" :style="isRecording ? 'background-color: #1a1a1a;' : 'background-color: #d8232a;'" style="color: #ffffff; border: none; padding: 0.85rem 1.5rem; border-radius: 4px; font-weight: 600; cursor: pointer; transition: opacity 0.2s; white-space: nowrap;">
                                                    <span x-text="isRecording ? '⏹ Stoppen' : '🎤 Sprechen'"></span>
                                                </button>
                                                <span style="color: #666666; font-size: 0.9rem;">oder</span>
                                                <input type="text" x-model="transcript" placeholder="Argument tippen..." style="flex-grow: 1; border: 1px solid #cbcbcb; padding: 0.85rem 1rem; border-radius: 4px; font-size: 0.95rem; outline: none;" @keydown.enter.prevent="sendReply">
                                                <button type="button" @click="sendReply" class="telc-button primary" :disabled="$wire.speakingProcessing['C1-SP-2'] || !transcript.trim()" style="padding: 0.85rem 1.5rem; white-space: nowrap;">
                                                    Abschicken
                                                </button>
                                            </div>
                                        @else
                                            <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; padding: 0.75rem 1rem; border-radius: 4px; font-size: 0.9rem; color: #15803d; font-weight: 600; text-align: left;">
                                                ✓ Teil 2: Diskussion erfolgreich abgeschlossen.
                                            </div>
                                        @endif
                                    </div>
                                @endif
                            @endif
                        </section>

                 <!-- Doc Floating Right Timer / Submit widget -->
                 <div class="doc-timer-bar">
                     @if (isset($template['sections'][$activeSection]))
                         <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #666666; border-bottom: 1px solid #e5e5e5; padding-bottom: 0.25rem; width: 100%; text-align: center;">
                             {{ $template['sections'][$activeSection]['title'] }}
                         </div>
                     @endif

                     <div class="doc-timer-text">
                         <svg style="color: #d8232a;" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                             <circle cx="12" cy="12" r="10"></circle>
                             <polyline points="12 6 12 12 16 14"></polyline>
                         </svg>
                         <span>Restzeit:</span>
                         <strong style="color: #d8232a;">{{ sprintf('%02d:%02d', $minutes, $seconds) }} Min.</strong>
                     </div>

                     <div class="save-indicator" style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: #666666;">
                         <div style="display: flex; align-items: center; gap: 0.25rem;">
                             <span class="save-pulse"></span>
                             <span>Auto-Safe aktiv</span>
                         </div>
                         <button type="button" wire:click="exitExam" style="background: none; border: none; color: #666666; font-size: 0.75rem; font-weight: 600; text-transform: uppercase; cursor: pointer; text-decoration: underline;">Menü verlassen</button>
                     </div>

                     @if (!$result)
                         <button type="button" wire:click="submit" class="submit-button" style="width: 100%; white-space: nowrap;">
                             Prüfung abgeben
                         </button>
                     @else
                         <button type="button" wire:click="loadTemplate({{ $selectedTemplateId }})" class="submit-button" style="background-color: #d8232a; width: 100%; white-space: nowrap;">
                             Neustarten
                         </button>
                     @endif
                 </div>
             </div>
        @else
            {{-- Standard Split View for non-C1 levels (B1, B2) --}}
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

                                        {{-- Writing --}}
                                        @foreach ($section['elements'] as $element)
                                            @if (($element['type'] ?? '') === 'writing')
                                                <div class="writing-section">
                                                    <label class="writing-label">{{ $element['label'] }}</label>
                                                    <textarea wire:model.live="answers.{{ $element['id'] }}" class="writing-textarea" placeholder="Schreibe deinen Text hier..."></textarea>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </aside>
                            </div>
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
</script>
@endpush
