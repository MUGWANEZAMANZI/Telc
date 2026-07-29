<x-layouts::app :title="__('System-Handbuch & Administrator-Anleitung')">
    <div class="user-guide-shell no-print-padding">
        {{-- Header Controls (Not visible in printed PDF) --}}
        <div class="guide-controls no-print">
            <a href="{{ route('dashboard') }}" class="back-link">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="19" y1="12" x2="5" y2="12"></line>
                    <polyline points="12 19 5 12 12 5"></polyline>
                </svg>
                Zurück zum Dashboard
            </a>
            <button type="button" onclick="window.print()" class="print-btn">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Handbuch drucken / PDF exportieren
            </button>
        </div>

        {{-- Handbook Main Title --}}
        <div class="handbook-title-block">
            <span class="handbook-eyebrow">GERMAN SUCCESS SYSTEM-ADMINISTRATION</span>
            <h1 class="handbook-main-title">Administratoren- & Schulungshandbuch</h1>
            <p class="handbook-subtitle">Komplette Anleitung zur Prüfungsverwaltung, Inhaltspflege und Systemdiagnostik</p>
        </div>

        {{-- SECTION 1: SYSTEM STACK & PRESETS --}}
        <section class="handbook-section">
            <div class="section-badge owner-color">Architektur & Presets</div>
            <h2 class="section-title">1. System-Grundlagen & Presets</h2>
            <div class="section-intro">
                Übersicht über die Prüfungsvorlagen und das Zusammenspiel zwischen Backend-Seeding und Admin-Builder.
            </div>
            
            <div class="guide-sub-grid">
                <div class="guide-item-card">
                    <h3>Prüfungsdaten-Seeding</h3>
                    <p>Standardprüfungen werden über <code>TelcTemplateFactory.php</code> geladen und mit dem Seeder in die SQLite-Datenbank übertragen. Admins können jederzeit benutzerdefinierte Kopien dieser Prüfungen erstellen oder bestehende ändern.</p>
                </div>
                <div class="guide-item-card">
                    <h3>Theme-Anpassungen</h3>
                    <p>Jeder Modellsatz kann über eigene Farbwerte (Primär-/Hintergrundfarben) und Schriftarten (z. B. <i>Instrument Sans</i>, <i>Plus Jakarta Sans</i>) verfügen. Diese können direkt im Dashboard angepasst und live geschaltet werden.</p>
                </div>
            </div>
        </section>

        <div class="page-break"></div>

        {{-- SECTION 2: EXAM BUILDER SCHULUNG --}}
        <section class="handbook-section">
            <div class="section-badge admin-color">Visual Exam Builder</div>
            <h2 class="section-title">2. Schritt-für-Schritt Schulung: Prüfungen erstellen & pflegen</h2>
            <div class="section-intro">
                So steuern Sie Abschnitte, laden Audiodateien hoch und verknüpfen Frageelemente nahtlos mit dem Antwortbogen der Studenten.
            </div>

            <div class="guide-sub-grid" style="grid-template-columns: 1fr;">
                <div class="guide-item-card" style="margin-bottom: 1.5rem;">
                    <h3>2.1 Einen neuen Prüfungsteil (Section) hinzufügen</h3>
                    <p>Klicken Sie ganz unten im Editor auf <strong>"Neuer Prüfungsteil"</strong>. Legen Sie den Titel des Abschnitts (z. B. <i>Leseverstehen und Sprachbausteine</i>) und die Bearbeitungszeit in Minuten fest. Der Timer im Studentensystem rechnet diese Minuten automatisch in Sekunden um und schließt den Abschnitt bei Ablauf.</p>
                </div>

                <div class="guide-item-card" style="margin-bottom: 1.5rem;">
                    <h3>2.2 Elemente hinzufügen und konfigurieren</h3>
                    <p>Innerhalb eines Prüfungsteils können Sie beliebig viele Elemente erstellen. Wählen Sie den passenden Elementtyp:</p>
                    <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0; font-size: 0.92rem; color: #4a5568;">
                        <li><strong>Text / Lesetext:</strong> Nimmt den Haupt-Lesetext auf. Verwenden Sie Zeilenumbrüche, Trennlinien und übersichtliche Gliederungen.</li>
                        <li><strong>Audioquelle:</strong> Erlaubt Uploads bis 100MB. Wählen Sie die MP3/WAV-Datei aus und klicken Sie auf "Upload". Der Pfad wird verschlüsselt hinterlegt.</li>
                        <li><strong>Zuordnung (Matching):</strong> Wird für Übungen wie <i>Leseverstehen Teil 1 & Teil 3</i> genutzt. Setzen Sie als Optionen z. B. <code>a,b,c,d,e,f,g,h,i,j,x</code>.</li>
                        <li><strong>Multiple Choice & Richtig/Falsch:</strong> Radio-Buttons für A/B/C oder +/-/X Optionen.</li>
                        <li><strong>Lückentext (Inline):</strong> Verwenden Sie <code>[[gap:0]]</code>, <code>[[gap:1]]</code> im Text. Konfigurieren Sie unten die Dropdown-Werte (z. B. <code>a|Option A,b|Option B</code>).</li>
                    </ul>
                </div>

                <div class="guide-item-card" style="margin-bottom: 1.5rem;">
                    <h3>2.3 Die goldene Regel: Bindung von Label und Frage-Texten</h3>
                    <p>Damit das System die Aufgaben korrekt nummeriert und anzeigt, befolgen Sie diese Richtlinien:</p>
                    <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0; font-size: 0.92rem; color: #4a5568;">
                        <li><strong>Label (z. B. "Aufgabe 11"):</strong> Tragen Sie immer die offizielle Aufgabennummer ein. Das System filtert alle Ziffern heraus (z. B. "11") und verwendet diese Nummer als Index auf dem Antwortbogen sowie in der Punkteauswertung.</li>
                        <li><strong>Instruction / Question Text:</strong> Tragen Sie hier den konkreten Aufgabentext ein (z. B. <i>"Statement 11: Most people eat organic products every day."</i>). Dieser Text wird im Studenten-Dashboard direkt über den Auswahloptionen gerendert.</li>
                        <li><strong>Correct Answer:</strong> Die richtige Lösung (z. B. <code>a</code>, <code>b</code>, <code>+</code>, <code>x</code>) muss immer kleingeschrieben eingetragen werden.</li>
                        <li><strong>Explanation (KI-Erläuterung):</strong> Tragen Sie eine kurze Erklärung ein, warum diese Antwort richtig ist. Klicken Sie auf 🤖 <i>"Mit KI generieren"</i>, um automatisch über Gemini eine präzise, pädagogische Erklärung erstellen zu lassen.</li>
                    </ul>
                </div>
            </div>
        </section>

        <div class="page-break"></div>

        {{-- SECTION 3: RECHNUNGEN & FREIGABEN --}}
        <section class="handbook-section">
            <div class="section-badge owner-color">Zahlungsprüfung & Finanzen</div>
            <h2 class="section-title">3. Zahlungsfreigabe & Benutzerverwaltung</h2>
            <div class="section-intro">
                Freischalten von Kursen und Überprüfung manueller Bankbelege.
            </div>

            <div class="guide-sub-grid">
                <div class="guide-item-card">
                    <h3>Manuelle Freigaben</h3>
                    <p>Unter <strong>"Zahlungen ausstehend"</strong> listet das Dashboard alle Studenten, die einen Bankbeleg oder Screenshot hochgeladen haben. Klicken Sie auf das Beleg-Symbol, um den Upload zu prüfen. Mit einem Klick auf <strong>"Freigeben"</strong> wird die Prüfung für den Studenten sofort aktiv geschaltet.</p>
                </div>
                <div class="guide-item-card">
                    <h3>Studentenergebnisse</h3>
                    <p>Im Reiter <strong>"Teilnehmer & Ergebnisse"</strong> sehen Sie tabellarisch, welcher Benutzer wann einen Test absolviert hat, wie viele Punkte erzielt wurden und wie hoch das KI-Feedback für das Schreiben/Sprechen ausgefallen ist.</p>
                </div>
            </div>
        </section>

        <div class="page-break"></div>

        {{-- SECTION 4: FEHLERBEHEBUNG (TROUBLESHOOTING) --}}
        <section class="handbook-section">
            <div class="section-badge troubleshooting-color">SYSTEM DIAGNOSTIC</div>
            <h2 class="section-title">4. Fehlerbehebung & Diagnostik (Troubleshooting)</h2>
            <div class="section-intro">
                Häufige Systemfragen und administrative Diagnoseschritte.
            </div>

            <table class="trouble-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Problem / Fehlermeldung</th>
                        <th style="width: 35%;">Mögliche Ursache</th>
                        <th style="width: 35%;">Lösung / Behebung</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Fehlermeldung: "Ungültiges Audio-Format"</strong></td>
                        <td>Die Audiodatei entspricht nicht den unterstützten Formaten oder hat eine falsche Dateiendung.</td>
                        <td>Das System unterstützt MP3, WAV, OGG, M4A und AAC. Konvertieren Sie die Datei vor dem Upload in eine Standard-MP3-Datei (max. 100MB).</td>
                    </tr>
                    <tr>
                        <td><strong>Änderungen im Builder werden nicht gespeichert</strong></td>
                        <td>Die Validierung ist fehlgeschlagen (z. B. leere Titel, fehlende Level-Angabe oder ungültiges JSON).</td>
                        <td>Prüfen Sie die Fehlermeldungen oben rechts. Stellen Sie sicher, dass "Title" und "Level" ausgefüllt sind, bevor Sie speichern.</td>
                    </tr>
                    <tr>
                        <td><strong>Doppelte Einträge nach Datenbank-Seeding</strong></td>
                        <td>Der Seeder sucht nach dem exakten Titel. Wird der Titel im Code geändert, entsteht ein neuer Tabelleneintrag.</td>
                        <td>Löschen Sie alte Testeinträge per Laravel Tinker über die Konsole: <code>App\Models\ExamTemplate::where('title', 'alter_titel')->delete();</code>.</td>
                    </tr>
                    <tr>
                        <td><strong>KI-Erklärungsgenerierung schlägt fehl</strong></td>
                        <td>Der Gemini-API-Key in der <code>.env</code> Datei fehlt oder das Limit der API-Aufrufe wurde überschritten.</td>
                        <td>Überprüfen Sie <code>GEMINI_API_KEY</code>. Stellen Sie sicher, dass eine aktive Internetverbindung besteht und das API-Dashboard keine Abrechnungsfehler aufweist.</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>

    {{-- Custom Page Styles --}}
    @push('styles')
        <style>
            .user-guide-shell {
                max-width: 1000px;
                margin: 0 auto;
                padding: 2.5rem 2rem;
                font-family: 'Plus Jakarta Sans', sans-serif;
                color: #2d3748;
                background-color: #ffffff;
                line-height: 1.7;
            }

            .guide-controls {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-bottom: 3rem;
                padding-bottom: 1rem;
                border-bottom: 1px solid #e2e8f0;
            }

            .back-link {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                color: #4a5568;
                text-decoration: none;
                font-weight: 700;
                font-size: 0.9rem;
                transition: color 0.15s ease;
            }

            .back-link:hover {
                color: #d8232a;
            }

            .print-btn {
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                background-color: #d8232a;
                color: #ffffff;
                border: none;
                padding: 0.75rem 1.25rem;
                border-radius: 6px;
                font-weight: 700;
                font-size: 0.9rem;
                cursor: pointer;
                box-shadow: 0 4px 12px rgba(216, 35, 42, 0.15);
                transition: all 0.15s ease;
            }

            .print-btn:hover {
                background-color: #b71a20;
                transform: translateY(-1px);
            }

            .handbook-title-block {
                text-align: center;
                margin-bottom: 4rem;
            }

            .handbook-eyebrow {
                font-size: 0.75rem;
                font-weight: 800;
                letter-spacing: 0.2em;
                color: #d8232a;
                text-transform: uppercase;
                display: block;
                margin-bottom: 0.75rem;
            }

            .handbook-main-title {
                font-family: 'Playfair Display', Georgia, serif;
                font-size: 2.8rem;
                font-weight: 700;
                color: #1a202c;
                line-height: 1.15;
                margin: 0;
            }

            .handbook-subtitle {
                font-size: 1.15rem;
                color: #718096;
                max-width: 700px;
                margin: 1rem auto 0;
            }

            .handbook-section {
                margin-bottom: 4rem;
                position: relative;
            }

            .section-badge {
                display: inline-block;
                font-size: 0.7rem;
                font-weight: 800;
                letter-spacing: 0.1em;
                padding: 0.25rem 0.75rem;
                border-radius: 4px;
                margin-bottom: 1rem;
                color: #ffffff;
                text-transform: uppercase;
            }

            .owner-color { background-color: #2b6cb0; }
            .admin-color { background-color: #319795; }
            .student-color { background-color: #d69e2e; }
            .troubleshooting-color { background-color: #718096; }

            .section-title {
                font-family: 'Playfair Display', Georgia, serif;
                font-size: 1.85rem;
                color: #1a202c;
                margin: 0 0 1rem 0;
            }

            .section-intro {
                font-size: 1.05rem;
                color: #4a5568;
                margin-bottom: 2rem;
                font-style: italic;
            }

            .guide-sub-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
                gap: 1.5rem;
            }

            .guide-item-card {
                background: #f7fafc;
                border: 1px solid #e2e8f0;
                border-radius: 8px;
                padding: 1.75rem;
                transition: border-color 0.2s;
            }

            .guide-item-card:hover {
                border-color: #cbd5e0;
            }

            .guide-item-card h3 {
                font-size: 1.1rem;
                font-weight: 700;
                color: #2d3748;
                margin: 0 0 0.75rem;
            }

            .guide-item-card p {
                font-size: 0.92rem;
                color: #4a5568;
                margin: 0;
                line-height: 1.6;
            }

            .trouble-table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 1.5rem;
                font-size: 0.92rem;
            }

            .trouble-table th, .trouble-table td {
                padding: 1rem 1.25rem;
                border: 1px solid #e2e8f0;
                text-align: left;
                vertical-align: top;
            }

            .trouble-table th {
                background-color: #f7fafc;
                font-weight: 700;
                color: #2d3748;
                text-transform: uppercase;
                font-size: 0.75rem;
                letter-spacing: 0.05em;
            }

            .page-break {
                display: none;
            }

            @media print {
                body {
                    background-color: #ffffff;
                    color: #000000;
                    font-size: 11pt;
                }

                .no-print, .no-print * {
                    display: none !important;
                }

                .no-print-padding {
                    padding: 0 !important;
                }

                .user-guide-shell {
                    max-width: 100%;
                    padding: 0 !important;
                    margin: 0 !important;
                }

                .handbook-main-title {
                    font-size: 26pt;
                }

                .section-title {
                    font-size: 18pt;
                    page-break-after: avoid;
                }

                .page-break {
                    display: block;
                    page-break-before: always;
                    height: 0;
                    margin: 0;
                    border: none;
                }

                .guide-sub-grid {
                    display: block !important;
                }

                .guide-item-card {
                    background: transparent !important;
                    border: none !important;
                    border-bottom: 1px dashed #cbd5e0 !important;
                    border-radius: 0 !important;
                    padding: 1.25rem 0 !important;
                    page-break-inside: avoid;
                }

                .trouble-table {
                    page-break-inside: auto;
                }

                .trouble-table tr {
                    page-break-inside: avoid;
                    page-break-after: auto;
                }
            }
        </style>
    @endpush
</x-layouts::app>
