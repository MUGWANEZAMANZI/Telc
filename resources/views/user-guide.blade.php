<x-layouts::app :title="__('Studenten-Handbuch & Benutzeranleitung')">
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
            <span class="handbook-eyebrow">GERMAN SUCCESS BENUTZER-HANDBUCH</span>
            <h1 class="handbook-main-title">Studenten- & Teilnehmer-Leitfaden</h1>
            <p class="handbook-subtitle">Lernplattform-Bedienung, Prüfungssimulationen und KI-Sprechtraining</p>
        </div>

        {{-- SECTION 1: BENUTZERREGISTRIERUNG & TARIFE --}}
        <section class="handbook-section">
            <div class="section-badge student-color">Registrierung & Zugriff</div>
            <h2 class="section-title">1. Erste Schritte auf der Plattform</h2>
            <div class="section-intro">
                So registrieren Sie sich, schalten Übungstests frei und greifen auf PDF-Bibliotheken zu.
            </div>
            
            <div class="guide-sub-grid">
                <div class="guide-item-card">
                    <h3>Registrierung & Login</h3>
                    <p>Erstellen Sie über die Schaltfläche <strong>Registrieren</strong> ein Benutzerkonto mit Ihrer E-Mail-Adresse und einem Passwort. Bestätigen Sie Ihre Anmeldung, um Zugang zum Dashboard zu erhalten.</p>
                </div>
                <div class="guide-item-card">
                    <h3>Zahlungsbeleg einreichen</h3>
                    <p>Haben Sie eine Prüfung manuell erworben? Laden Sie Ihren Überweisungs- oder Transaktionsbeleg einfach im System hoch. Ein Administrator prüft und schaltet Ihren Test kurzfristig frei.</p>
                </div>
                <div class="guide-item-card">
                    <h3>Lernmaterialien (Bücher)</h3>
                    <p>Als registrierter Nutzer haben Sie Zugriff auf unsere E-Book-Bibliothek. Öffnen Sie die PDF-Ansichten der Bücher direkt im Browser, um Lerneinheiten begleitend durchzugehen.</p>
                </div>
            </div>
        </section>

        <div class="page-break"></div>

        {{-- SECTION 2: PRÜFUNGSSIMULATION --}}
        <section class="handbook-section">
            <div class="section-badge owner-color">Prüfungssimulation</div>
            <h2 class="section-title">2. Durchführung von Prüfungssimulationen</h2>
            <div class="section-intro">
                So nutzen Sie das Split-Screen-Interface und beantworten Lückentexte, Zuordnungs- und Freitext-Aufgaben.
            </div>

            <div class="guide-sub-grid" style="grid-template-columns: 1fr;">
                <div class="guide-item-card" style="margin-bottom: 1.5rem;">
                    <h3>2.1 Die Prüfungsoberfläche (Split-Screen)</h3>
                    <p>Wenn Sie eine Prüfung starten, teilt sich Ihr Bildschirm:</p>
                    <ul style="margin: 0.5rem 0 0 1.5rem; padding: 0; font-size: 0.92rem; color: #4a5568;">
                        <li><strong>Linke Seite (Prüfungsheft / Booklet):</strong> Enthält den offiziellen Lesetext, die multiple choice Aufgabenbeschreibungen, Gaps oder die Info-Texte zur Zuordnung. Sie können hier entspannt lesen und scrollen.</li>
                        <li><strong>Rechte Seite (Antwortbogen / Answer Sheet):</strong> Hier befinden sich die Eingabefelder. Wählen Sie Ihre Antworten durch Klicks auf die jeweiligen Radio-Buttons oder Dropdowns (z. B. <i>Aufgaben 1-5</i> oder <i>11-20</i>).</li>
                    </ul>
                </div>

                <div class="guide-item-card" style="margin-bottom: 1.5rem;">
                    <h3>2.2 Zeiteinteilung und Timer</h3>
                    <p>Oben rechts läuft die verbleibende Bearbeitungszeit für den aktuellen Abschnitt ab. Läuft die Zeit ab, wird Ihre aktuelle Antwort automatisch gespeichert und der Abschnitt beendet. Planen Sie Ihre Bearbeitungszeit entsprechend!</p>
                </div>

                <div class="guide-item-card" style="margin-bottom: 1.5rem;">
                    <h3>2.3 Hörverstehen absolvieren</h3>
                    <p>Sobald Sie einen Hörverstehensabschnitt starten, erscheint oben auf Ihrem Antwortbogen ein Audioplayer. Klicken Sie auf Start, um die Hördatei abzuspielen. Tragen Sie Ihre Antworten direkt während des Hörens ein.</p>
                </div>
            </div>
        </section>

        <div class="page-break"></div>

        {{-- SECTION 3: KI LAUTSPRECH-LABOR --}}
        <section class="handbook-section">
            <div class="section-badge admin-color">KI Voice Lab</div>
            <h2 class="section-title">3. Training im KI-Sprechübungs-Labor (Voice Practice)</h2>
            <div class="section-intro">
                So verbessern Sie Ihre Aussprache und Kommunikationskompetenz unter realen Prüfungsbedingungen.
            </div>

            <div class="guide-sub-grid">
                <div class="guide-item-card">
                    <h3>Themenauswahl & Niveaus (A1–C1)</h3>
                    <p>Wählen Sie Ihr gewünschtes Sprachniveau (A1, A2, B1, B2 oder C1). Bei <strong>Goethe-Prüfungen</strong> wählen Sie nach dem Start Ihr Thema manuell aus. Bei <strong>TELC-Prüfungen</strong> erfolgt die Zuweisung des Sprechthemas im Hintergrund vollkommen automatisch, ganz ohne manuelle Vorauswahl.</p>
                </div>
                <div class="guide-item-card">
                    <h3>Dynamischer Ablauf & Timings</h3>
                    <p>Die KI steuert den Übergang zwischen den verschiedenen Prüfungsteilen (z. B. von Teil 1 zu Teil 2) vollautomatisch. Sobald Sie einen Teil abgeschlossen haben, wechselt das System zum nächsten Teil und startet den Timer für diesen Abschnitt.</p>
                </div>
                <div class="guide-item-card">
                    <h3>Spracherkennung & Antworten</h3>
                    <p>Klicken Sie auf das Mikrofon-Symbol, erteilen Sie dem Browser die Berechtigung und sprechen Sie Ihre Antwort ein. Die Plattform transkribiert Ihre gesprochene Sprache in Text. Die KI analysiert diese und antwortet Ihnen mit einer gesprochenen Audiospur.</p>
                </div>
            </div>
        </section>

        <div class="page-break"></div>

        {{-- SECTION 4: FEHLERBEHEBUNG --}}
        <section class="handbook-section">
            <div class="section-badge troubleshooting-color">Hilfe & Support</div>
            <h2 class="section-title">4. Fehlerbehebung & Tipps für Kursteilnehmer</h2>
            <div class="section-intro">
                Lösungen für die häufigsten technischen Anwenderfragen.
            </div>

            <table class="trouble-table">
                <thead>
                    <tr>
                        <th style="width: 30%;">Häufige Frage / Fehler</th>
                        <th style="width: 35%;">Mögliche Ursache</th>
                        <th style="width: 35%;">Lösung / Behebung</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><strong>Das Mikrofon nimmt meine Stimme nicht auf</strong></td>
                        <td>Der Browser hat keinen Mikrofonzugriff oder nutzt ein falsches Aufnahmegerät.</td>
                        <td>Klicken Sie links neben der URL-Leiste auf das Schloss-Symbol und setzen Sie die Berechtigung für "Mikrofon" auf "Zulassen". Nutzen Sie bevorzugt Google Chrome oder Edge.</td>
                    </tr>
                    <tr>
                        <td><strong>Prüfung wurde abgebrochen / "Banned" Meldung</strong></td>
                        <td>Mehrfach-Anmeldung mit demselben Benutzerkonto auf verschiedenen Geräten erkannt.</td>
                        <td>Zum Schutz vor unbefugter Weitergabe schließt das System die alte Sitzung sofort, wenn Sie sich auf einem neuen Gerät anmelden. Loggen Sie sich einfach erneut ein.</td>
                    </tr>
                    <tr>
                        <td><strong>Ich kann die Audio-Spur nicht hören</strong></td>
                        <td>Die Lautsprecherlautstärke ist stummgeschaltet oder der Browsertab ist stummgeschaltet.</td>
                        <td>Prüfen Sie Ihre Systemsounds. Stellen Sie sicher, dass der Tab nicht stummgeschaltet ist und keine Bluetooth-Kopfhörer stummgeschaltet im Hintergrund verbunden sind.</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <div class="page-break"></div>

        {{-- SECTION 5: VISA BERATUNG & FEEDBACK --}}
        <section class="handbook-section no-print">
            <div class="section-badge student-color">VISA & Feedback</div>
            <h2 class="section-title">5. VISA-Beratung & Feedback-Formulare</h2>
            <div class="section-intro">
                Benötigen Sie Hilfe bei Ihrem VISA-Antrag oder möchten Sie uns Feedback geben? Nutzen Sie unsere offiziellen Google-Formulare.
            </div>

            <div class="guide-sub-grid">
                <div class="guide-item-card">
                    <h3>VISA-Beratung (VISA Consultancy)</h3>
                    <p>Wenn Sie Unterstützung bei der Beantragung Ihres deutschen Visums, Behördenangelegenheiten oder Dokumenten benötigen, helfen wir Ihnen gerne weiter.</p>
                    <div style="margin-top: 1.25rem;">
                        <a href="{{ \App\Models\Setting::value('visa_consultancy_url', config('services.google_forms.visa_consultancy')) }}" target="_blank" class="print-btn" style="text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 100%;">
                            Formular ausfüllen
                        </a>
                    </div>
                </div>
                <div class="guide-item-card">
                    <h3>Feedback & Support</h3>
                    <p>Ihre Meinung ist uns wichtig! Teilen Sie uns Ihre Anregungen, Lob oder technische Fehlerberichte mit, damit wir German Success kontinuierlich verbessern können.</p>
                    <div style="margin-top: 1.25rem;">
                        <a href="{{ \App\Models\Setting::value('feedback_url', config('services.google_forms.feedback')) }}" target="_blank" class="print-btn" style="background-color: #4a5568; box-shadow: 0 4px 12px rgba(74, 85, 104, 0.15); text-decoration: none; display: inline-flex; align-items: center; justify-content: center; width: 100%;">
                            Feedback senden
                        </a>
                    </div>
                </div>
            </div>
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
