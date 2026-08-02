<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GERMAN SUCCESS — Premium C1 Deutsch Prüfung</title>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
    
    <!-- AlpineJS for Interactive Logic -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        :root {
            --bg-color: #fcfcfc;
            --text-color: #1a1a1a;
            --muted-color: #666666;
            --border-color: #e5e5e5;
            --accent-red: #d8232a;
            --accent-yellow: #ffcc00;
            --font-sans: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            --font-serif: 'Playfair Display', Georgia, serif;
        }

        /* Reset & Base */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            font-family: var(--font-sans);
            line-height: 1.85;
            font-size: 1.05rem;
            -webkit-font-smoothing: antialiased;
            overflow-x: hidden;
        }

        /* Sticky Top Navigation Bar */
        .header-nav {
            position: sticky;
            top: 0;
            z-index: 100;
            background-color: rgba(252, 252, 252, 0.96);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            height: 72px;
            display: flex;
            align-items: center;
        }

        .nav-container {
            max-width: 1000px;
            width: 100%;
            margin: 0 auto;
            padding: 0 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-color);
            transition: opacity 0.2s ease;
        }

        .brand:hover {
            opacity: 0.85;
        }

        .logo-img {
            height: 36px;
            width: auto;
            object-fit: contain;
            border-radius: 4px;
        }

        .brand-name {
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }

        @media (max-width: 640px) {
            .brand-name {
                display: none;
            }
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--muted-color);
            font-size: 0.85rem;
            font-weight: 500;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            transition: color 0.25s ease;
            position: relative;
            padding: 0.5rem 0;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 0;
            height: 1px;
            background-color: var(--text-color);
            transition: width 0.25s ease;
        }

        .nav-links a:hover {
            color: var(--text-color);
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* Unified Lined Paper Document Layout */
        .exam-document {
            max-width: 800px;
            margin: 2rem auto;
            background: #ffffff;
            border: 1px solid var(--border-color);
            position: relative;
            padding: 5rem 4rem 10rem 6rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.03);
            border-radius: 8px;
        }

        /* Lined paper margin lines #cbcbcb */
        .exam-document::before {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 4.5rem;
            width: 1px;
            border-left: 1px solid #cbcbcb;
        }

        .exam-document::after {
            content: '';
            position: absolute;
            top: 0;
            bottom: 0;
            left: 4.7rem;
            width: 1px;
            border-left: 1px solid #cbcbcb;
        }

        /* Meta Header */
        .document-header {
            text-align: center;
            margin-bottom: 6rem;
        }

        .level-badge {
            display: inline-block;
            border: 1px solid var(--text-color);
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.15em;
            padding: 0.35rem 1rem;
            margin-bottom: 2rem;
            text-transform: uppercase;
            position: relative;
        }

        .level-badge::before {
            content: '';
            position: absolute;
            left: -4px;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background-color: var(--accent-red);
            border-radius: 50%;
        }

        .document-title {
            font-family: var(--font-serif);
            font-size: 3.5rem;
            font-weight: 400;
            letter-spacing: -0.01em;
            line-height: 1.15;
            margin-bottom: 1rem;
        }

        .document-subtitle {
            font-size: 1.05rem;
            color: var(--muted-color);
            font-weight: 300;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }

        /* Progress Tracker */
        .progress-section {
            margin-top: 4rem;
            max-width: 400px;
            margin-left: auto;
            margin-right: auto;
        }

        .progress-bar-bg {
            width: 100%;
            height: 2px;
            background-color: var(--border-color);
            position: relative;
            overflow: hidden;
        }

        .progress-bar-fill {
            height: 100%;
            background-color: var(--text-color);
            width: 0%;
            transition: width 0.5s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .progress-text {
            font-size: 0.75rem;
            color: var(--muted-color);
            letter-spacing: 0.05em;
            text-transform: uppercase;
            margin-top: 0.5rem;
            display: flex;
            justify-content: space-between;
        }

        /* Document Dividers */
        .document-divider {
            border: 0;
            height: 1px;
            background-color: #cbcbcb;
            margin: 6rem 0;
        }

        /* Section Headings */
        .section-label {
            font-size: 0.75rem;
            font-weight: 600;
            color: var(--accent-red);
            letter-spacing: 0.2em;
            text-transform: uppercase;
            margin-bottom: 0.75rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .section-label::after {
            content: '';
            flex-grow: 1;
            height: 1px;
            background-color: #cbcbcb;
        }

        .section-heading {
            font-family: var(--font-serif);
            font-size: 2.2rem;
            font-weight: 400;
            line-height: 1.2;
            margin-bottom: 2rem;
        }

        .section-instructions {
            font-size: 0.95rem;
            color: var(--muted-color);
            line-height: 1.6;
            margin-bottom: 3.5rem;
            padding-left: 1.25rem;
            border-left: 1px solid var(--text-color);
        }

        /* Section 1: Cloze Passage Styles */
        .cloze-passage {
            font-family: var(--font-sans);
            font-size: 1.1rem;
            line-height: 2.1;
            color: #262626;
            text-align: justify;
        }

        .cloze-select-wrapper {
            position: relative;
            display: inline-block;
            margin: 0 0.35rem;
        }

        .cloze-select {
            border: none;
            border-bottom: 2px solid var(--text-color);
            background-color: transparent;
            font-family: inherit;
            font-size: 1.05rem;
            font-weight: 700;
            color: var(--accent-red);
            padding: 0 0.25rem;
            outline: none;
            cursor: pointer;
            border-radius: 0;
        }

        .cloze-select:focus {
            border-bottom-color: var(--accent-red);
        }

        .cloze-gap-num {
            font-size: 0.75rem;
            font-weight: 800;
            vertical-align: super;
            color: var(--muted-color);
            margin-right: 0.15rem;
        }

        /* Section 2: Paragraph Matching */
        .paragraph-box {
            background-color: #fafafa;
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            margin-bottom: 2rem;
            font-size: 0.95rem;
            max-height: 300px;
            overflow-y: auto;
        }

        .paragraph-box p {
            margin-bottom: 1rem;
        }

        .paragraph-box p:last-child {
            margin-bottom: 0;
        }

        .matching-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px dashed var(--border-color);
            padding: 0.85rem 0;
        }

        .matching-row:last-child {
            border-bottom: 0;
        }

        /* Section 3: T/F/NG table */
        .tf-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2rem;
        }

        .tf-table th, .tf-table td {
            padding: 0.85rem 1rem;
            border-bottom: 1px solid var(--border-color);
            text-align: left;
            font-size: 0.95rem;
        }

        .tf-table th {
            font-weight: 700;
            color: var(--muted-color);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .tf-choice-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border: 1px solid var(--border-color);
            border-radius: 50%;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.2s;
            margin-right: 0.5rem;
            user-select: none;
        }

        .tf-choice-btn:hover {
            border-color: var(--text-color);
            background-color: #fafafa;
        }

        .tf-input-hidden {
            display: none;
        }

        .tf-input-hidden:checked + .tf-choice-btn {
            border-color: var(--text-color);
            background-color: var(--text-color);
            color: var(--bg-color);
        }

        /* Section 4: Lined Textarea for Essay */
        .writing-area-lined {
            width: 100%;
            min-height: 420px;
            padding: 1.5rem;
            font-family: var(--font-sans);
            font-size: 1.05rem;
            line-height: 2.2rem;
            border: 1px solid var(--border-color);
            background-color: transparent;
            background-image: linear-gradient(transparent, transparent 2.15rem, #cbcbcb 2.15rem, #cbcbcb 2.2rem);
            background-size: 100% 2.2rem;
            color: var(--text-color);
            resize: vertical;
            transition: all 0.3s ease;
            outline: none;
        }

        /* Audio Lined input */
        .lined-input {
            border: none;
            border-bottom: 1.5px solid #cbcbcb;
            background: transparent;
            font-family: inherit;
            font-size: 1rem;
            font-weight: 600;
            color: var(--accent-red);
            width: 100%;
            padding: 0.25rem 0;
            outline: none;
        }

        .lined-input:focus {
            border-bottom-color: var(--text-color);
        }

        /* Sticky Footer Controls */
        .footer-controls {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(252, 252, 252, 0.98);
            border-top: 1px solid var(--border-color);
            padding: 1.25rem 2rem;
            z-index: 99;
        }

        .footer-container {
            max-width: 800px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .timer-box {
            font-size: 1.05rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            letter-spacing: 0.02em;
        }

        .timer-accent {
            color: var(--accent-red);
        }

        .save-indicator {
            font-size: 0.85rem;
            color: var(--muted-color);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .save-pulse {
            width: 6px;
            height: 6px;
            background-color: #10b981;
            border-radius: 50%;
            display: inline-block;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { transform: scale(0.95); opacity: 0.5; }
            50% { transform: scale(1.15); opacity: 1; }
            100% { transform: scale(0.95); opacity: 0.5; }
        }

        .submit-button {
            background-color: var(--text-color);
            color: var(--bg-color);
            border: none;
            padding: 0.85rem 2.25rem;
            font-family: var(--font-sans);
            font-size: 0.95rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .submit-button:hover {
            background-color: var(--accent-red);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(216, 35, 42, 0.25);
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
</head>
<body>

    <!-- Sticky Top Navigation -->
    <header class="header-nav">
        <div class="nav-container">
            <a href="/" class="brand">
                <img src="/logo.jpeg" alt="GERMAN SUCCESS Logo" class="logo-img">
                <span class="brand-name">GERMAN SUCCESS</span>
            </a>
            <nav>
                <ul class="nav-links">
                    <li><a href="#">Methodik</a></li>
                    <li><a href="#">Prüfungen</a></li>
                    <li><a href="#">Preise</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Main Exam Container -->
    <main class="exam-document" x-data="examHandler()">
        
        <!-- Meta Header Section -->
        <header class="document-header">
            <div class="level-badge">telc Deutsch C1</div>
            <h1 class="document-title">Übungstest 1</h1>
            <p class="document-subtitle">Modelltest — Vollständige Prüfungssimulation</p>

            <!-- Live Progress Bar -->
            <div class="progress-section">
                <div class="progress-bar-bg">
                    <div class="progress-bar-fill" :style="`width: ${progressPercentage}%`"></div>
                </div>
                <div class="progress-text">
                    <span>Bearbeitungsstand</span>
                    <span x-text="`${answeredCount} von ${totalCount} Aufgaben gelöst`"></span>
                </div>
            </div>
        </header>

        <!-- SECTION 1: Textrekonstruktion -->
        <section class="exam-section">
            <div class="section-label">Leseverstehen, Teil 1</div>
            <h2 class="section-heading">Textrekonstruktion (Der Mars)</h2>
            <div class="section-instructions">
                <strong>Anweisung:</strong> Welche der Sätze a–h gehören in die Lücken 1–6? Wählen Sie den passenden Buchstaben aus. Lücke (0) ist ein Beispiel.
            </div>

            <article class="cloze-passage">
                Könnten wir den eisigen Mars „terraformen“? Die Antwort lautet: 
                <span class="cloze-select-wrapper"><select class="cloze-select" disabled><option>0: z (Wahrscheinlich wäre es zu schaffen.)</option></select></span>. 
                Wir wissen heute, dass es auf dem Mars einst wärmer war als heute. Es muss sogar Flüsse und Seen gegeben haben. 
                <span class="cloze-select-wrapper">
                    <span class="cloze-gap-num">1</span>
                    <select x-model="answers.cloze[0]" @change="updateProgress" class="cloze-select">
                        <option value="">—</option>
                        <option value="a">a</option><option value="b">b</option><option value="c">c</option><option value="d">d</option>
                        <option value="e">e</option><option value="f">f</option><option value="g">g</option><option value="h">h</option>
                    </select>
                </span>
                Indem man genug Treibhausgase in seine Atmosphäre bläst. Ein großer Teil des Kohlendioxids (CO2), das den Roten Planeten früher wärmte, ist wohl noch vorhanden – gebunden in den Eiskappen der Pole und im Boden. Gefrorenes Wasser wurde ebenfalls nachgewiesen. Technisch ist es also machbar, dort wieder Pflanzen wachsen zu lassen.<br><br>

                „Wir müssen nur die Temperatur ein wenig erhöhen und ein paar Samen ausstreuen“, sagt der Nasa-Planetenforscher Chris McKay, „
                <span class="cloze-select-wrapper">
                    <span class="cloze-gap-num">2</span>
                    <select x-model="answers.cloze[1]" @change="updateProgress" class="cloze-select">
                        <option value="">—</option>
                        <option value="a">a</option><option value="b">b</option><option value="c">c</option><option value="d">d</option>
                        <option value="e">e</option><option value="f">f</option><option value="g">g</option><option value="h">h</option>
                    </select>
                </span>
                .“ Um den Prozess in Gang zu setzen, kann man aus der Marsoberfläche Perfluorkohlenstoffe (PFC) gewinnen. PFC sind wirksame Treibhausgase. Sobald sich die Atmosphäre aufheizt, wird gefrorenes CO2 aus den Böden frei. Ein sich verstärkender Treibhauseffekt entsteht. Der Luftdruck steigt, und irgendwann fließt wieder Wasser. Menschliche Pioniere können die Felsen mit Bakterien und Flechten „impfen“, die auf der Erde gedeihen. Später bringt man Moose aus, dann Bäume. Die Pflanzen reichern die Atmosphäre mit Sauerstoff an, doch bis wir sie atmen können, werden viele tausend Jahre vergehen.
            </article>

            <div style="background: #fafafa; border: 1px solid var(--border-color); padding: 1.5rem; font-size: 0.9rem; margin-top: 2rem;">
                <strong>Satzoptionen:</strong>
                <ul style="list-style: none; margin-top: 0.5rem; padding-left: 0;">
                    <li><strong>a</strong> Allerdings war die Atmosphäre niemals so dicht wie bei der Erde.</li>
                    <li><strong>b</strong> Auf lange Sicht wären die besten Stromlieferanten aber wohl Fusionsreaktoren.</li>
                    <li><strong>c</strong> Aus eigener Erfahrung wissen wir auch, wie man einen kühlen Planeten aufheizt:</li>
                    <li><strong>d</strong> Das 1000-Jahres-Projekt beginnt mit einer Reihe von Expeditionen.</li>
                    <li><strong>e</strong> Den Rest der Arbeit wird das Leben selber erledigen.</li>
                </ul>
            </div>
        </section>

        <hr class="document-divider">

        <!-- SECTION 2: Paragraph Matching -->
        <section class="exam-section">
            <div class="section-label">Leseverstehen, Teil 2</div>
            <h2 class="section-heading">Selektives Verstehen (Hobbybäuerin)</h2>
            <div class="section-instructions">
                <strong>Anweisung:</strong> In welchem Textabsatz a–e finden Sie die Antworten auf die Fragen 7–12?
            </div>

            <div class="paragraph-box">
                <p><strong>a)</strong> Ich bin stolz auf mich und fühle mich richtig gut, weil ich meine ambitionierten Ziele bis jetzt immer realisieren konnte...</p>
                <p><strong>b)</strong> Bin ich nun eine Heldin oder nicht? Nun kann man über meine Leibesstärke diskutieren... Ich war höchstens eine Heldin der Arbeit...</p>
                <p><strong>c)</strong> Einige Freundschaften hat es mich auch gekostet: Ein Freund redet nicht mehr mit mir, seit er zum Unkrautrupfen mitkam...</p>
            </div>

            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div class="matching-row">
                    <span>7. In welchem Abschnitt ist die Autorin belehrend?</span>
                    <select x-model="answers.matching[0]" @change="updateProgress" class="cloze-select">
                        <option value="">—</option>
                        <option value="a">a</option><option value="b">b</option><option value="c">c</option><option value="d">d</option><option value="e">e</option>
                    </select>
                </div>
                <div class="matching-row">
                    <span>8. In welchem Abschnitt ist die Autorin polemisch?</span>
                    <select x-model="answers.matching[1]" @change="updateProgress" class="cloze-select">
                        <option value="">—</option>
                        <option value="a">a</option><option value="b">b</option><option value="c">c</option><option value="d">d</option><option value="e">e</option>
                    </select>
                </div>
            </div>
        </section>

        <hr class="document-divider">

        <!-- SECTION 3: T/F/NG table -->
        <section class="exam-section">
            <div class="section-label">Leseverstehen, Teil 3</div>
            <h2 class="section-heading">Detailverstehen (Film-Wissenschaftler)</h2>
            <div class="section-instructions">
                <strong>Anweisung:</strong> Ist die Aussage richtig (+), falsch (–) oder nicht im Text enthalten (x)?
            </div>

            <table class="tf-table">
                <thead>
                    <tr>
                        <th>Aussage</th>
                        <th style="text-align: center;">Optionen (+ / - / x)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>13. In den Unterhaltungsfilmen der letzten Jahre werden Wissenschaftler vorwiegend als skrupellose Bösewichte dargestellt.</td>
                        <td style="text-align: center; white-space: nowrap;">
                            <label><input type="radio" name="q13" value="+" x-model="answers.tf[0]" @change="updateProgress" class="tf-input-hidden"><span class="tf-choice-btn">+</span></label>
                            <label><input type="radio" name="q13" value="-" x-model="answers.tf[0]" @change="updateProgress" class="tf-input-hidden"><span class="tf-choice-btn">-</span></label>
                            <label><input type="radio" name="q13" value="x" x-model="answers.tf[0]" @change="updateProgress" class="tf-input-hidden"><span class="tf-choice-btn">x</span></label>
                        </td>
                    </tr>
                </tbody>
            </table>
        </section>

        <hr class="document-divider">

        <!-- SECTION 4: Sprachbausteine -->
        <section class="exam-section">
            <div class="section-label">Sprachbausteine</div>
            <h2 class="section-heading">Ein merkwürdiger Akzent</h2>
            <div class="section-instructions">
                <strong>Anweisung:</strong> Wählen Sie das passende Wort für jede Lücke aus.
            </div>

            <article class="cloze-passage" style="line-height: 2.3;">
                Stellen Sie sich vor, Sie wachen eines Morgens auf und wünschen Ihrem Partner einen guten Morgen – 
                <span class="cloze-select-wrapper">
                    <span class="cloze-gap-num">25</span>
                    <select x-model="answers.sb[0]" @change="updateProgress" class="cloze-select">
                        <option value="">—</option>
                        <option value="a">a) dagegen</option>
                        <option value="b">b) doch</option>
                        <option value="c">c) hingegen</option>
                        <option value="d">d) jedoch</option>
                    </select>
                </span>
                die Worte aus Ihrem Mund klingen...
            </article>
        </section>

        <hr class="document-divider">

        <!-- SECTION 5: Kandidatenblätter / Hörverstehen -->
        <section class="exam-section">
            <div class="section-label">Kandidatenblätter</div>
            <h2 class="section-heading">Hören</h2>
            <div class="section-instructions" style="font-style: normal; line-height: 1.7; background: #fafafa; border-left: 3px solid #d8232a; padding: 1rem 1.25rem;">
                <strong>circa 40 Minuten</strong><br>
                Das Modul Hören hat vier Teile.<br>
                Sie hören mehrere Texte und lösen Aufgaben dazu.<br>
                Lesen Sie jeweils zuerst die Aufgaben und hören Sie dann den Text dazu.<br>
                Für jede Aufgabe gibt es nur eine richtige Lösung.<br>
                Nach dem Hören haben Sie drei Minuten Zeit, Ihre Lösungen auf den Antwortbogen zu übertragen.<br>
                Es werden nur die Lösungen und Texte auf dem Antwortbogen bewertet.<br>
                Bitte schreiben Sie mit einem blauen oder schwarzen Stift.<br>
                Hilfsmittel (z. B. Wörterbücher, Handys/mobile Endgeräte) sind nicht erlaubt.
            </div>

            <audio controls src="/telc-assets/telc_deutsch_c1_uebungstest_1_format_2016.mp3" style="width: 100%; margin-bottom: 2.5rem;"></audio>

            <div style="display: flex; flex-direction: column; gap: 2rem;">
                <div>
                    <label style="font-size: 0.95rem; font-weight: 600; display: block; margin-bottom: 0.5rem;">65. Die neue Wandfarbe bewirkt bei Frau Müllers Wohnzimmer...</label>
                    <input type="text" x-model="answers.hv3[0]" @input="updateProgress" class="lined-input" placeholder="Stichwort eintragen...">
                </div>
            </div>
        </section>

        <hr class="document-divider">

        <!-- SECTION 6: Written Expression (Schriftlicher Ausdruck) -->
        <section class="exam-section">
            <div class="section-label">Schriftlicher Ausdruck</div>
            <h2 class="section-heading">Stellungnahme</h2>
            
            <div class="writing-guidelines">
                <h4>Thema 1: Soll man Geld spenden?</h4>
                <p>Verfassen Sie eine Stellungnahme von ca. 350 Wörtern, in der Sie die Argumente abwägen.</p>
            </div>

            <textarea x-model="answers.writing" @input="handleWritingInput" class="writing-area-lined" placeholder="Schreiben Sie hier Ihre Stellungnahme auf dem linierten Papier..."></textarea>
            
            <div class="writing-status-row">
                <span>Ziel: ca. 350 Wörter</span>
                <span class="word-count" x-text="`${wordCount} Wörter`"></span>
            </div>
        </section>

        <!-- Sticky Bottom Action Bar -->
        <footer class="footer-controls">
            <div class="footer-container">
                <div class="timer-box">
                    <svg class="timer-accent" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <span>Verbleibende Zeit: </span>
                    <span class="timer-accent" x-text="formattedTime"></span>
                </div>
                
                <div class="save-indicator">
                    <span class="save-pulse"></span>
                    <span>Automatisch gesichert</span>
                </div>

                <button class="submit-button" @click="submitExam">
                    Prüfung abgeben
                </button>
            </div>
        </footer>

    </main>

    <script>
        function examHandler() {
            return {
                answers: {
                    cloze: ['', ''],
                    matching: ['', ''],
                    tf: [''],
                    sb: [''],
                    hv3: [''],
                    writing: ''
                },
                wordCount: 0,
                totalCount: 7,
                answeredCount: 0,
                progressPercentage: 0,
                remainingSeconds: 7200,
                timerInterval: null,

                init() {
                    this.startTimer();
                    this.updateProgress();
                },

                startTimer() {
                    this.timerInterval = setInterval(() => {
                        if (this.remainingSeconds > 0) this.remainingSeconds--;
                    }, 1000);
                },

                get formattedTime() {
                    const minutes = Math.floor(this.remainingSeconds / 60);
                    const seconds = this.remainingSeconds % 60;
                    return `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')} Min.`;
                },

                handleWritingInput() {
                    const text = this.answers.writing.trim();
                    this.wordCount = text === '' ? 0 : text.split(/\s+/).length;
                    this.updateProgress();
                },

                updateProgress() {
                    let filled = 0;
                    this.answers.cloze.forEach(v => { if(v) filled++ });
                    this.answers.matching.forEach(v => { if(v) filled++ });
                    this.answers.tf.forEach(v => { if(v) filled++ });
                    this.answers.sb.forEach(v => { if(v) filled++ });
                    this.answers.hv3.forEach(v => { if(v) filled++ });
                    if (this.wordCount >= 10) filled++;

                    this.answeredCount = filled;
                    this.progressPercentage = Math.round((filled / this.totalCount) * 100);
                },

                submitExam() {
                    if (confirm('Möchten Sie die Prüfung abgeben?')) {
                        alert('Prüfung erfolgreich übermittelt!');
                    }
                }
            }
        }
    </script>
</body>
</html>
