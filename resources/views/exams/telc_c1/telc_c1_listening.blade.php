<section class="c1-section telc-listening-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 3rem; border-radius: 12px; border: 1px solid #e2e8f0; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.02); width: 100%; display: flex; flex-direction: column;">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Architects+Daughter&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap');
        
        .listening-title-bar {
            border-bottom: 3px solid #d8232a;
            padding-bottom: 1rem;
            margin-bottom: 2.5rem;
            display: flex;
            justify-content: space-between;
            align-items: baseline;
            flex-wrap: wrap;
        }
        .listening-title {
            font-size: 1.8rem;
            font-weight: 850;
            color: #111827;
            margin: 0;
            text-transform: uppercase;
            letter-spacing: -0.02em;
        }
        .listening-meta {
            font-size: 0.85rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .listening-meta .page-badge {
            background: #d8232a;
            color: #ffffff;
            padding: 0.2rem 0.6rem;
            border-radius: 4px;
            font-size: 0.78rem;
        }
        
        /* Audio Player */
        .audio-player-card {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.25rem;
            margin-bottom: 3rem;
            max-width: 480px;
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .audio-player-card audio {
            width: 100%;
        }

        /* Section Container */
        .hv-part-container {
            margin-bottom: 4rem;
        }
        .hv-part-header {
            margin-bottom: 1.5rem;
        }
        .hv-part-tag {
            background: #fef2f2;
            color: #dc2626;
            font-size: 0.78rem;
            font-weight: 800;
            padding: 0.2rem 0.6rem;
            border-radius: 9999px;
            text-transform: uppercase;
            display: inline-block;
            margin-bottom: 0.5rem;
        }
        .hv-part-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1e293b;
            margin: 0;
            letter-spacing: -0.01em;
        }
        .hv-part-instructions {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.6;
            margin-top: 0.5rem;
            background: #f8fafc;
            border-left: 3px solid #cbd5e1;
            padding: 0.75rem 1rem;
            border-radius: 0 6px 6px 0;
        }

        /* Teil 1 Styles */
        .hv1-statements-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        @media (max-width: 768px) {
            .hv1-statements-grid {
                grid-template-columns: 1fr;
            }
        }
        .hv1-statement-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 1rem;
            font-size: 0.9rem;
            line-height: 1.5;
            color: #334155;
            display: flex;
            gap: 0.75rem;
            transition: border-color 0.15s;
        }
        .hv1-statement-card:hover {
            border-color: #cbd5e1;
        }
        .hv1-statement-letter {
            width: 1.5rem;
            height: 1.5rem;
            background: #f1f5f9;
            border-radius: 4px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            color: #475569;
            flex-shrink: 0;
        }
        .hv1-speakers-list {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            border-top: 1px solid #e2e8f0;
            padding-top: 2rem;
        }
        .hv1-speaker-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            background: #f8fafc;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            gap: 1rem;
            flex-wrap: wrap;
        }
        .hv1-speaker-name {
            font-weight: 700;
            color: #1e293b;
            font-size: 0.95rem;
            min-width: 6.5rem;
        }
        .hv1-speaker-options {
            display: flex;
            gap: 0.35rem;
            flex-wrap: wrap;
        }
        .hv-bubble {
            width: 30px;
            height: 24px;
            border-radius: 12px;
            border: 1.5px solid #64748b;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.72rem;
            font-weight: 800;
            color: #64748b;
            cursor: pointer;
            user-select: none;
            transition: all 0.15s ease-in-out;
            background: #ffffff;
        }
        .hv-bubble:hover:not(.disabled) {
            border-color: #1a1a1a;
            background: #f8fafc;
        }
        .hv-bubble.selected {
            background: #1e293b;
            color: #ffffff;
            border-color: #1e293b;
        }
        .hv-bubble.correct {
            background: #16a34a !important;
            color: #ffffff !important;
            border-color: #16a34a !important;
        }
        .hv-bubble.wrong {
            background: #dc2626 !important;
            color: #ffffff !important;
            border-color: #dc2626 !important;
        }
        .hv-bubble.expected {
            border: 2px dashed #16a34a !important;
            color: #16a34a !important;
            background: #f0fdf4 !important;
        }

        /* Teil 2 Styles */
        .hv2-questions-grid {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }
        .hv2-question-card {
            background: #ffffff;
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 1.5rem;
            transition: border-color 0.15s;
        }
        .hv2-question-card:hover {
            border-color: #cbd5e1;
        }
        .hv2-question-text {
            font-weight: 700;
            color: #1e293b;
            font-size: 1rem;
            line-height: 1.5;
            margin-bottom: 1rem;
            display: flex;
            gap: 0.5rem;
        }
        .hv2-q-num {
            color: #64748b;
            font-weight: 800;
        }
        .hv2-options-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .hv2-option-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.6rem 1rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.15s ease-in-out;
            text-align: left;
            width: 100%;
        }
        .hv2-option-btn:hover:not(:disabled) {
            border-color: #94a3b8;
            background: #f8fafc;
        }
        .hv2-option-btn.selected {
            background: #f1f5f9;
            border-color: #1e293b;
        }
        .hv2-option-btn.correct {
            background: #f0fdf4 !important;
            border-color: #16a34a !important;
        }
        .hv2-option-btn.wrong {
            background: #fef2f2 !important;
            border-color: #dc2626 !important;
        }
        .hv2-option-circle {
            width: 1.25rem;
            height: 1.25rem;
            border-radius: 9999px;
            border: 2px solid #cbd5e1;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 800;
            color: #64748b;
            flex-shrink: 0;
            transition: all 0.15s ease-in-out;
        }
        .hv2-option-btn.selected .hv2-option-circle {
            border-color: #1e293b;
            background: #1e293b;
            color: #ffffff;
        }
        .hv2-option-btn.correct .hv2-option-circle {
            border-color: #16a34a !important;
            background: #16a34a !important;
            color: #ffffff !important;
        }
        .hv2-option-btn.wrong .hv2-option-circle {
            border-color: #dc2626 !important;
            background: #dc2626 !important;
            color: #ffffff !important;
        }
        .hv2-option-text {
            font-size: 0.92rem;
            color: #475569;
            font-weight: 600;
        }
        .hv2-option-btn.selected .hv2-option-text {
            color: #1e293b;
        }

        /* Teil 3 Styles */
        .hv3-split-layout {
            display: grid;
            grid-template-columns: 1.2fr 1fr;
            gap: 2rem;
        }
        @media (max-width: 1024px) {
            .hv3-split-layout {
                grid-template-columns: 1fr;
            }
        }
        .hv3-slide-card {
            background: #1e293b;
            color: #f8fafc;
            border-radius: 8px;
            padding: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid #334155;
            margin-bottom: 1.25rem;
        }
        .hv3-slide-header {
            font-size: 0.8rem;
            text-transform: uppercase;
            font-weight: 800;
            color: #94a3b8;
            border-bottom: 1px solid #334155;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
            display: flex;
            justify-content: space-between;
        }
        .hv3-slide-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 1rem;
        }
        .hv3-slide-body {
            font-size: 0.92rem;
            line-height: 1.6;
            color: #cbd5e1;
        }
        .hv3-slide-body ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
        }
        .hv3-slide-body li {
            margin-bottom: 0.75rem;
            display: flex;
            align-items: baseline;
            gap: 0.5rem;
        }
        .hv3-slide-body li::before {
            content: "•";
            color: #38bdf8;
            font-weight: 800;
        }
        .hv3-gap-highlight {
            background: rgba(56, 189, 248, 0.15);
            color: #38bdf8;
            padding: 0.1rem 0.4rem;
            border-radius: 4px;
            font-weight: 700;
            font-size: 0.82rem;
            border: 1px dashed rgba(56, 189, 248, 0.4);
        }

        /* Handwritten ruled sheet */
        .hv3-ruled-sheet {
            background: #fffdf5;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 2rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            background-image: linear-gradient(#e2e8f0 1px, transparent 1px);
            background-size: 100% 2.2rem;
            line-height: 2.2rem;
        }
        .ruled-title {
            font-size: 1.2rem;
            font-weight: 800;
            color: #475569;
            margin-top: 0;
            margin-bottom: 2rem;
            border-bottom: 2px solid #e2e8f0;
            line-height: 1.5;
            padding-bottom: 0.5rem;
        }
        .ruled-line-item {
            display: flex;
            align-items: flex-end;
            margin-bottom: 0;
            height: 2.2rem;
        }
        .ruled-num {
            font-weight: 800;
            font-size: 0.95rem;
            color: #64748b;
            width: 2.5rem;
            flex-shrink: 0;
        }
        .ruled-input {
            flex: 1;
            border: none !important;
            border-bottom: 1.5px solid #94a3b8 !important;
            background: transparent !important;
            font-family: 'Architects Daughter', cursive;
            font-size: 1.1rem;
            color: #1e3a8a;
            padding: 0 0.5rem !important;
            height: 1.8rem !important;
            line-height: 1.8rem !important;
            outline: none !important;
            box-shadow: none !important;
        }
        .ruled-input:focus {
            border-bottom-color: #d8232a !important;
        }
        .ruled-feedback {
            margin-left: 0.5rem;
            align-self: center;
            line-height: 1;
        }
    </style>

    @php
        $getHvDetail = function($id) use ($result) {
            if (!$result) return null;
            return collect($result['details'])->firstWhere('id', $id);
        };
    @endphp

    <div class="listening-title-bar">
        <h2 class="listening-title">{{ $section['title'] ?? 'Hörverstehen' }}</h2>
        <div class="listening-meta">
            <span>circa 40 Minuten</span>
            <span class="page-badge">S. 18-21</span>
        </div>
    </div>

    @if (!$isGuest)
        <div class="audio-player-card">
            <span style="font-weight: 700; color: #475569; font-size: 0.9rem;">Audio:</span>
            <audio controls src="{{ '/goethe-assets/goethe_zertifikat_c1_uebungstest_1_format_2016.mp3' }}"></audio>
        </div>
    @endif

    {{-- ==================== TEIL 1 ==================== --}}
    <div class="hv-part-container">
        <div class="hv-part-header">
            <span class="hv-part-tag">Teil 1</span>
            <h3 class="hv-part-title">Kontroverse Meinungen über den Zoo</h3>
            <p class="hv-part-instructions">
                Sie hören eine Diskussionsrunde über Zoos. Sie hören den Text einmal. Ordnen Sie den Sprechern 1 bis 8 (Aufgaben 47 bis 54) die passenden Aussagen a bis j zu. Zwei Aussagen passen nicht.
            </p>
        </div>

        {{-- Statements box --}}
        <div class="hv1-statements-grid">
            @php
                $statements = [
                    'a' => 'Aus pädagogischer Sicht ist ein Zoobesuch insbesondere für Kleinkinder eine wertvolle Erfahrung.',
                    'b' => 'Dass Zoos der Arterhaltung dienen, ist ein vorgeschobenes Argument.',
                    'c' => 'Die ökonomischen Interessen der Zoos werden oft vor das Wohl der Tiere gestellt.',
                    'd' => 'Forschung und Zoo sind eng miteinander verknüpft.',
                    'e' => 'Für manche Tierarten ist die Situation im Zoo besonders dramatisch.',
                    'f' => 'Moderne Zoos bieten den Tieren größere Lebensräume.',
                    'g' => 'In Zoos gehaltene Tiere verhalten sich anders als in freier Wildbahn.',
                    'h' => 'Zoos vermitteln kein authentisches Bild vom Leben der Tiere.',
                    'i' => 'Zoos passen nicht mehr in unsere Zeit und sollten deshalb abgeschafft werden.',
                    'j' => 'Zoos sind Erholungs- und Freizeiteinrichtungen für alle Altersklassen.'
                ];
            @endphp
            @foreach ($statements as $letter => $text)
                <div class="hv1-statement-card">
                    <span class="hv1-statement-letter">{{ strtoupper($letter) }}</span>
                    <span>{{ $text }}</span>
                </div>
            @endforeach
        </div>

        {{-- Speakers list --}}
        <div class="hv1-speakers-list">
            @for ($qNum = 47; $qNum <= 54; $qNum++)
                @php
                    $elId = "telc-c1-hv1-{$qNum}";
                    $current = $answers[$elId] ?? '';
                    $detail = $getHvDetail($elId);
                    $expected = $detail['expected'] ?? '';
                    $hasSelection = $current !== '';
                @endphp
                <div class="hv1-speaker-row" wire:key="speaker-q-{{ $qNum }}">
                    <span class="hv1-speaker-name">Sprecher {{ $qNum - 46 }} (Aufg. {{ $qNum }})</span>
                    
                    <div class="hv1-speaker-options">
                        @foreach (range('a', 'j') as $letter)
                            @php
                                $isSelected = $current === $letter;
                                $isExpected = $result && $letter === $expected;
                                
                                $class = '';
                                if ($isSelected) $class .= ' selected';
                                if ($result) {
                                    if ($isExpected) $class .= ' correct';
                                    elseif ($isSelected) $class .= ' wrong';
                                }
                                if ($result && $isExpected && !$isSelected) $class .= ' expected';
                                
                                $style = '';
                                if ($hasSelection && !$isSelected && !$isExpected) {
                                    $style = 'opacity: 0.35;';
                                }
                            @endphp
                            <button type="button" class="hv-bubble{{ $class }} @if($result) disabled @endif"
                                @if(!$result) wire:click="$set('answers.{{ $elId }}', '{{ $isSelected ? '' : $letter }}')" @endif
                                style="{{ $style }}"
                                @if($result) disabled @endif>
                                {{ strtoupper($letter) }}
                            </button>
                        @endforeach
                    </div>

                    @if ($result)
                        <div class="hv-feedback" style="align-self: center;">
                            @if ($detail['correct'] ?? false)
                                <span style="color: #16a34a; font-weight: 800; font-size: 1.1rem;">✓</span>
                            @else
                                <span style="color: #dc2626; font-weight: 800; font-size: 1.1rem;">✗</span>
                                <span style="color: #16a34a; font-size: 0.85rem; margin-left: 0.5rem; font-weight: 700;">Lösung: {{ strtoupper($expected) }}</span>
                            @endif
                        </div>
                    @endif
                </div>
            @endfor
        </div>
    </div>

    {{-- ==================== TEIL 2 ==================== --}}
    <div class="hv-part-container">
        <div class="hv-part-header">
            <span class="hv-part-tag">Teil 2</span>
            <h3 class="hv-part-title">Interview mit Reiseleiter Herr Rimbach</h3>
            <p class="hv-part-instructions">
                Sie hören ein Interview mit dem Reiseleiter Herr Rimbach. Sie hören den Text zweimal. Wählen Sie bei jeder Aufgabe 55 bis 64 die richtige Lösung a, b oder c.
            </p>
        </div>

        <div class="hv2-questions-grid">
            @php
                $mcQuestions = [
                    55 => [
                        'q' => 'Herr Rimbach ist für die Reisegruppe leicht erkennbar, obwohl er...',
                        'opts' => ['a' => 'keine auffälligen Requisiten dabei hat.', 'b' => 'nicht durch seine Körpergröße auffällt.', 'c' => 'unauffällige Kleidung bevorzugt.']
                    ],
                    56 => [
                        'q' => 'Herr Rimbach setzt Körpersprache bewusst ein, um...',
                        'opts' => ['a' => 'einen positiven ersten Eindruck zu hinterlassen.', 'b' => 'seine eigene Nervosität zu überspielen.', 'c' => 'Urlaubern die Nervosität zu nehmen.']
                    ],
                    57 => [
                        'q' => 'Nörgler und Unruhestifter...',
                        'opts' => ['a' => 'erkennt Herr Rimbach immer auf den ersten Blick.', 'b' => 'fallen oft schon am Anreisetag unangenehm auf.', 'c' => 'reagieren in Krisensituationen besonders panisch.']
                    ],
                    58 => [
                        'q' => 'In schwierigen Situationen...',
                        'opts' => ['a' => 'benötigen ältere Urlauber oft besondere Aufmerksamkeit.', 'b' => 'braucht Herr Rimbach manchmal schauspielerisches Talent.', 'c' => 'kann Herr Rimbach oft schnell eine Lösung finden.']
                    ],
                    59 => [
                        'q' => 'Beschwerden über das Essen sind verständlich, weil...',
                        'opts' => ['a' => 'das Angebot oft etwas eintönig ist.', 'b' => 'keine landestypischen Gerichte serviert werden.', 'c' => 'ungewöhnliche Gewürze verwendet werden.']
                    ],
                    60 => [
                        'q' => 'Das Klischee, dass Deutsche für alle Eventualitäten gerüstet sein wollen,...',
                        'opts' => ['a' => 'hält Herr Rimbach inzwischen für überholt.', 'b' => 'kann Herr Rimbach aus eigener Erfahrung bestätigen.', 'c' => 'sieht Herr Rimbach als übertrieben an.']
                    ],
                    61 => [
                        'q' => 'Der deutsche Urlauber...',
                        'opts' => ['a' => 'hat ein ausgeprägtes Bedürfnis nach Struktur.', 'b' => 'hat ein Problem mit Wartezeiten.', 'c' => 'kann auch sehr spontan sein.']
                    ],
                    62 => [
                        'q' => 'Die Verwendung des „Du“ schafft eine Vertrautheit, die...',
                        'opts' => ['a' => 'auch auf Abenteuerreisen nicht gut ankommt.', 'b' => 'auf Studienreisen absolut erwünscht ist.', 'c' => 'zu Irritationen bzw. schwierigen Situationen führen kann.']
                    ],
                    63 => [
                        'q' => 'Besserwissern begegnet Herr Rimbach, indem er...',
                        'opts' => ['a' => 'gar nicht erst auf sie eingeht.', 'b' => 'gelassen und sachlich bleibt.', 'c' => 'humorvolle Bemerkungen macht.']
                    ],
                    64 => [
                        'q' => 'Herr Rimbach...',
                        'opts' => ['a' => 'ärgert sich über zu wenig Trinkgeld.', 'b' => 'findet es unangenehm, Trinkgeld anzunehmen.', 'c' => 'spendet sein Trinkgeld grundsätzlich.']
                    ]
                ];
            @endphp

            @foreach ($mcQuestions as $num => $qData)
                @php
                    $elId = "telc-c1-hv2-{$num}";
                    $current = $answers[$elId] ?? '';
                    $detail = $getHvDetail($elId);
                    $expected = $detail['expected'] ?? '';
                @endphp
                <div class="hv2-question-card" wire:key="mc-q-{{ $num }}">
                    <div class="hv2-question-text">
                        <span class="hv2-q-num">{{ $num }}.</span>
                        <span>{{ $qData['q'] }}</span>
                    </div>

                    <div class="hv2-options-group">
                        @foreach ($qData['opts'] as $letter => $txt)
                            @php
                                $isSelected = $current === $letter;
                                $isExpected = $result && $letter === $expected;
                                
                                $class = '';
                                if ($isSelected) $class .= ' selected';
                                if ($result) {
                                    if ($isExpected) $class .= ' correct';
                                    elseif ($isSelected) $class .= ' wrong';
                                }
                            @endphp
                            <button type="button" class="hv2-option-btn{{ $class }}"
                                @if(!$result) wire:click="$set('answers.{{ $elId }}', '{{ $isSelected ? '' : $letter }}')" @endif
                                @if($result) disabled @endif>
                                <span class="hv2-option-circle">{{ strtoupper($letter) }}</span>
                                <span class="hv2-option-text">{{ $txt }}</span>
                                
                                @if($result && $isExpected && !$isSelected)
                                    <span style="color: #16a34a; font-size: 0.8rem; font-weight: 700; margin-left: auto;">(Korrekte Lösung)</span>
                                @endif
                            </button>
                        @endforeach
                    </div>

                    @if ($result)
                        <div style="margin-top: 1rem; display: flex; align-items: center; justify-content: flex-end;">
                            @if ($detail['correct'] ?? false)
                                <span style="color: #16a34a; font-weight: 800; font-size: 0.9rem;">✓ Richtig (+2 Punkte)</span>
                            @else
                                <span style="color: #dc2626; font-weight: 800; font-size: 0.9rem;">✗ Falsch (0 Punkte)</span>
                            @endif
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- ==================== TEIL 3 ==================== --}}
    <div class="hv-part-container" style="margin-bottom: 1rem;">
        <div class="hv-part-header">
            <span class="hv-part-tag">Teil 3</span>
            <h3 class="hv-part-title">Vortragsreihe „Schöner wohnen“ — Martin Wintermeyer</h3>
            <p class="hv-part-instructions">
                Sie hören einen Vortrag. Sie hören den Text einmal. Schreiben Sie die fehlenden Informationen stichwortartig in die freien Zeilen 65 bis 74 in der rechten Spalte.
            </p>
        </div>

        <div class="hv3-split-layout">
            {{-- Slides Column --}}
            <div>
                <div class="hv3-slide-card">
                    <div class="hv3-slide-header">
                        <span>Folie 1</span>
                        <span>Martin Wintermeyer</span>
                    </div>
                    <div class="hv3-slide-title">Präsentation: Wohnen mit Farbe</div>
                    <div class="hv3-slide-body">
                        <ul>
                            <li>Beispiel: Frau Müllers Wohnzimmer</li>
                            <li>Die neue Wandfarbe bewirkt: <span class="hv3-gap-highlight">(65)</span></li>
                        </ul>
                    </div>
                </div>

                <div class="hv3-slide-card">
                    <div class="hv3-slide-header">
                        <span>Folie 2</span>
                        <span>Martin Wintermeyer</span>
                    </div>
                    <div class="hv3-slide-title">Farbunterschiede</div>
                    <div class="hv3-slide-body">
                        <ul>
                            <li>Farbe ist nicht gleich Farbe. Wir unterscheiden: <span class="hv3-gap-highlight">(66)</span></li>
                            <li>Weiße Wände? Nein, weil: <span class="hv3-gap-highlight">(67)</span></li>
                            <li>Alternativen: <span class="hv3-gap-highlight">(68)</span></li>
                        </ul>
                    </div>
                </div>

                <div class="hv3-slide-card">
                    <div class="hv3-slide-header">
                        <span>Folie 3</span>
                        <span>Martin Wintermeyer</span>
                    </div>
                    <div class="hv3-slide-title">Farbwahl & Akzente</div>
                    <div class="hv3-slide-body">
                        <ul>
                            <li>Die richtige Farbwahl und -menge hängt ab von: <span class="hv3-gap-highlight">(69)</span></li>
                            <li>Farbe und Persönlichkeit. Ein Beispiel: <span class="hv3-gap-highlight">(70)</span></li>
                            <li>Farbakzente. Vorteil von Farbakzenten: <span class="hv3-gap-highlight">(71)</span></li>
                        </ul>
                    </div>
                </div>

                <div class="hv3-slide-card">
                    <div class="hv3-slide-header">
                        <span>Folie 4</span>
                        <span>Martin Wintermeyer</span>
                    </div>
                    <div class="hv3-slide-title">Praktische Tipps & Kritik</div>
                    <div class="hv3-slide-body">
                        <ul>
                            <li>Mehr Mut zur Farbe in: <span class="hv3-gap-highlight">(72)</span></li>
                            <li>Der Faktor Licht. Wohngefühl positiv beeinflussen, indem: <span class="hv3-gap-highlight">(73)</span></li>
                            <li>Kritik an modernen Wohnungen: <span class="hv3-gap-highlight">(74)</span></li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Ruled handwriting sheet --}}
            <div>
                <div class="hv3-ruled-sheet">
                    <h4 class="ruled-title">Ihre Lösungen</h4>
                    
                    @for ($qNum = 65; $qNum <= 74; $qNum++)
                        @php
                            $elId = "telc-c1-hv3-{$qNum}";
                            $current = $answers[$elId] ?? '';
                            $detail = $getHvDetail($elId);
                            $expected = $detail['expected'] ?? '';
                        @endphp
                        <div class="ruled-line-item" wire:key="fill-q-{{ $qNum }}">
                            <span class="ruled-num">{{ $qNum }}.</span>
                            <input type="text" class="ruled-input"
                                wire:model.blur="answers.{{ $elId }}"
                                placeholder="stichwortartig eintragen..."
                                @if($result) disabled @endif>
                            
                            @if ($result)
                                <div class="ruled-feedback">
                                    @if ($detail['correct'] ?? false)
                                        <span style="color: #16a34a; font-weight: 800; font-size: 1rem;">✓</span>
                                    @else
                                        <span style="color: #dc2626; font-weight: 800; font-size: 1rem;">✗</span>
                                        <span style="color: #16a34a; font-size: 0.72rem; font-family: sans-serif; font-weight: 700; margin-left: 0.25rem;">({{ $expected }})</span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

</section>
