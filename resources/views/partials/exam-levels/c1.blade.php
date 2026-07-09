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
                @endphp

<style>
    .markdown-content h1, .markdown-content h2, .markdown-content h3, .markdown-content h4 {
        color: #1a1a1a;
        font-weight: 700;
        margin-top: 1rem;
        margin-bottom: 0.5rem;
    }
    .markdown-content h3 {
        font-size: 1.05rem;
        border-bottom: 1px solid #eaeaea;
        padding-bottom: 0.25rem;
        margin-top: 1.25rem;
    }
    .markdown-content p {
        margin-top: 0;
        margin-bottom: 0.5rem;
        line-height: 1.6;
    }
    .markdown-content ul, .markdown-content ol {
        margin-top: 0;
        margin-bottom: 0.75rem;
        padding-left: 1.25rem;
    }
    .markdown-content li {
        margin-bottom: 0.25rem;
    }
    .markdown-content strong {
        color: #111;
        font-weight: 600;
    }
    .markdown-content hr {
        border: 0;
        border-top: 1px solid #e5e5e5;
        margin: 1.25rem 0;
    }
</style>

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

                    @if (!$selectedTopic)
                        <div style="background: #ffffff; border: 1px solid #e5e5e5; padding: 2.5rem; border-radius: 6px; text-align: center; font-family: 'Plus Jakarta Sans', sans-serif;">
                            <h4 style="font-family: 'Playfair Display', serif; font-size: 1.6rem; color: #1a1a1a; margin-bottom: 1rem;">Wählen Sie ein Präsentationsthema für Teil 1a</h4>
                            <p style="color: #666666; font-size: 0.95rem; margin-bottom: 2rem;">Das andere Thema wird für die KI-Präsentation in Teil 1b verwendet.</p>
                            
                            <div style="display: flex; flex-direction: column; gap: 1rem; max-width: 600px; margin: 0 auto;">
                                <button type="button" wire:click="chooseTopic('Was ist der Nationalsport in einem Land Ihrer Wahl und welche Bedeutung hat er für die Gesellschaft?','C1-SP-1a')" class="telc-button primary" style="background: #ffffff; color: #1a1a1a; border: 1px solid #cbcbcb; padding: 1.25rem; text-align: left; font-size: 0.95rem; line-height: 1.5; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                                    Thema A: Was ist der Nationalsport in einem Land Ihrer Wahl und welche Bedeutung hat er für die Gesellschaft? Berichten Sie!
                                </button>
                                <button type="button" wire:click="chooseTopic('Wer gehört Ihrer Meinung nach zu den einflussreichsten lebenden Personen weltweit? Wodurch zeichnet sich diese Person aus?','C1-SP-1a')" class="telc-button primary" style="background: #ffffff; color: #1a1a1a; border: 1px solid #cbcbcb; padding: 1.25rem; text-align: left; font-size: 0.95rem; line-height: 1.5; font-weight: 600; cursor: pointer; transition: all 0.2s;">
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
                                <strong>Ihr gewähltes Thema:</strong> {{ $selectedTopic }}
                            </p>

                            @if (!empty($speakingHistory['C1-SP-1a'] ?? []))
                                <div style="background-color: #f7f9fa; border: 1px solid #e5e5e5; border-radius: 4px; padding: 1.25rem; margin-bottom: 1.5rem; text-align: left;">
                                    @foreach ($speakingHistory['C1-SP-1a'] as $turn)
                                        <div style="margin-bottom: 0.75rem;">
                                            <strong style="font-size: 0.8rem; color: #666666;">{{ $turn['role'] === 'user' ? 'Ihr Vortrag' : 'Prüfer-Bewertung' }}:</strong>
                                            @if ($turn['role'] === 'user')
                                                <p style="font-size: 0.95rem; line-height: 1.5; margin-top: 0.25rem; white-space: pre-wrap;">{{ $turn['text'] }}</p>
                                            @else
                                                <div class="markdown-content" style="font-size: 0.95rem; line-height: 1.6; margin-top: 0.25rem;">
                                                    {!! \Illuminate\Support\Str::markdown($turn['text']) !!}
                                                </div>
                                            @endif
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
                        @if ($speakingStep >= 2)
                            @if (empty($aiPresentationText))
                                <div style="background:#fff;border:1px solid #e5e5e5;padding:2rem;border-radius:6px;text-align:center;margin-bottom:2rem;"
                                     x-init="$wire.loadAiPresentation()">
                                    <h4 style="font-size:1.1rem;margin-bottom:0.75rem;">KI-Vortrag wird generiert...</h4>
                                    <p style="font-size:0.9rem;color:#666;margin-bottom:1.5rem;">
                                        Das KI-System erstellt einen Vortrag zum anderen Thema. Bitte warten Sie einen Moment.
                                    </p>
                                    <div style="display:inline-block;width:2rem;height:2rem;border:3px solid #e5e5e5;border-top-color:#d8232a;border-radius:50%;animation:spin 0.8s linear infinite;"></div>
                                    <style>@keyframes spin{to{transform:rotate(360deg)}}</style>
                                </div>
                            @else
                            <div class="speaking-task" style="border: 1px solid #e5e5e5; padding: 2rem; border-radius: 6px; background-color: #ffffff; margin-bottom: 2.5rem;"
                                 x-data="{
                                     isSynthesizing: false,
                                     playAudio() {
                                         const utterance = new SpeechSynthesisUtterance(@js($aiPresentationText));
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
                                    <div class="markdown-content" style="font-size: 0.95rem; line-height: 1.6; color: #4a4a4a;">
                                        {!! \Illuminate\Support\Str::markdown($aiPresentationText) !!}
                                    </div>
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
                                    @if ($speakingStep === 2)
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
                                                        <div class="markdown-content" style="font-size: 0.95rem; line-height: 1.6; margin-top: 0.25rem;">
                                                            {!! \Illuminate\Support\Str::markdown($speakingHistory['C1-SP-1b'][2]['text']) !!}
                                                        </div>
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
                        @endif

                        <!-- Step 3: Discussion -->
                        @if ($speakingStep >= 3)
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
                                                    @if ($turn['role'] === 'user')
                                                        {!! nl2br(e($turn['text'])) !!}
                                                    @else
                                                        <div class="markdown-content">
                                                            {!! \Illuminate\Support\Str::markdown($turn['text']) !!}
                                                        </div>
                                                    @endif
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
