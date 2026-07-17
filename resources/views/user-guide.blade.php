<x-layouts::app :title="__('System-Handbuch & Benutzeranleitung')">
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
                Als PDF exportieren / Drucken
            </button>
        </div>

        {{-- Handbook Main Title --}}
        <div class="handbook-title-block">
            <span class="handbook-eyebrow">GERMAN SUCCESS DEUTSCHLERN- & PRÜFUNGSSYSTEM</span>
            <h1 class="handbook-main-title">Offizielles System-Handbuch</h1>
            <p class="handbook-subtitle">Umfassende Dokumentation für Eigentümer, Administratoren und Kursteilnehmer</p>
        </div>

        {{-- SECTION 1: EIGENTÜMER-HANDBUCH (OWNER) --}}
        @if (auth()->user()?->isAdmin() || auth()->user()?->isOwner())
            <section class="handbook-section">
                <div class="section-badge owner-color">OWNER ROLE</div>
                <h2 class="section-title">1. Leitfaden für System-Eigentümer (Owner)</h2>
                <div class="section-intro">
                    Eigentümer besitzen die höchste administrative Kontrolle über die Finanzen, Tarifmodelle und die allgemeine Auslastung der Plattform.
                </div>
                
                <div class="guide-sub-grid">
                    <div class="guide-item-card">
                        <h3>Tarifstrukturen & Preisgestaltung</h3>
                        <p>Über das <strong>Pricing-Panel</strong> auf dem Dashboard können Sie die Abonnementpläne definieren und Preise anpassen. Sie können Einmalkäufe für spezifische Prüfungen (z. B. 5,00 €) oder wiederkehrende Monatsabonnements (z. B. 19,00 €) anbieten.</p>
                    </div>
                    <div class="guide-item-card">
                        <h3>Finanzübersicht & Zahlungsströme</h3>
                        <p>Im Bereich <strong>Finanzen</strong> sehen Sie die Gesamteinnahmen, die Verteilung auf PayPal und Kreditkartenzahlungen sowie die Anzahl der aktiven Abonnements. Ein monatliches Säulendiagramm zeigt die finanzielle Entwicklung der letzten 12 Monate.</p>
                    </div>
                    <div class="guide-item-card">
                        <h3>Umsatzkontrolle auf Produktion</h3>
                        <p>Die Berechnungen laufen datenbankgestützt und sind vollständig mit MySQL/MariaDB kompatibel. Alle getätigten Umsätze werden in Echtzeit aggregiert, sobald eine Zahlung freigegeben wird.</p>
                    </div>
                </div>
            </section>

            <div class="page-break"></div>
        @endif

        {{-- SECTION 2: ADMINISTRATOREN-HANDBUCH (ADMIN) --}}
        @if (auth()->user()?->isAdmin())
            <section class="handbook-section">
                <div class="section-badge admin-color">ADMIN ROLE</div>
                <h2 class="section-title">2. Leitfaden für Administratoren (Admin)</h2>
                <div class="section-intro">
                    Administratoren verwalten die Lerninhalte, laden Übungstests hoch, steuern Freigaben und überwachen die Ergebnisse der Kursteilnehmer.
                </div>

                <div class="guide-sub-grid">
                    <div class="guide-item-card">
                        <h3>Prüfungsvorlagen (Templates) erstellen</h3>
                        <p>Mit dem <strong>Visual Template Builder</strong> können Sie neue Prüfungen von Grund auf erstellen oder fertige Presets laden. Tragen Sie Titel, Niveau (B1, B2, C1, C2) und Prüfungsanbieter (telc / Goethe) ein. Setzen Sie die Prüfung auf "Published", damit sie im Kurskatalog erscheint.</p>
                    </div>
                    <div class="guide-item-card">
                        <h3>Audio-Ressourcen für Hörverstehen</h3>
                        <p>Im Builder können Sie direkt MP3- oder WAV-Audiodateien bis zu einer Dateigröße von 100MB hochladen. Die Datei wird sicher auf dem Server abgelegt und über eine geschützte Route (<code>/exam-audio/...</code>) ausschließlich für angemeldete und verifizierte Studenten bereitgestellt.</p>
                    </div>
                    <div class="guide-item-card">
                        <h3>Lückentexte (Inline Gaps & Dropdowns)</h3>
                        <p>Fügen Sie dem Prüfungsteil ein <strong>Inline-Gap</strong> hinzu. Im Fließtext markieren Sie die Lücken mit <code>--0--</code>, <code>--1--</code> etc. Im rechten Optionsfeld tragen Sie die Antwortkombinationen getrennt mit Kommas und Pipes ein (z. B. <code>a|ich,b|mir,c|mich</code>) und definieren die richtige Lösung.</p>
                    </div>
                    <div class="guide-item-card">
                        <h3>Zahlungsfreigabe (Automatisch)</h3>
                        <p>Die Freischaltung von erworbenen Prüfungen oder abgeschlossenen Abonnements erfolgt vollautomatisch über die Revolut- und PayPal-APIs.</p>
                    </div>
                    <div class="guide-item-card">
                        <h3>Überwachung der Studentenleistung</h3>
                        <p>Auf der Seite <strong>Studenten-Leistung</strong> haben Sie Einblick in alle Prüfungsversuche. Sie sehen die genaue prozentuale Verteilung, das erreichte KI-Feedback und können auch Gast-Versuche nachverfolgen.</p>
                    </div>
                </div>
            </section>

            <div class="page-break"></div>
        @endif

        {{-- SECTION 3: STUDENTEN-HANDBUCH (STUDENT) --}}
        <section class="handbook-section">
            <div class="section-badge student-color">STUDENT ROLE</div>
            <h2 class="section-title">3. Leitfaden für Studenten (Kursteilnehmer)</h2>
            <div class="section-intro">
                Kursteilnehmer nutzen das System zur Prüfungsvorbereitung, zum Schreiben von Aufsätzen und zum interaktiven Üben des mündlichen Ausdrucks.
            </div>

            <div class="guide-sub-grid">
                <div class="guide-item-card">
                    <h3>Prüfungssimulationen durchführen</h3>
                    <p>Die Prüfungsschnittstelle im lizenzierten linierten Design vermittelt echte Prüfungsatmosphäre. Ein Timer läuft rückwärts. Wählen Sie Antworten bei Lückentexten und Multiple-Choice-Fragen direkt auf der Seite und tippen Sie Ihre Stellungnahme in das linierte Schreibfeld.</p>
                </div>
                <div class="guide-item-card">
                    <h3>Kostenlose Prüfungs-Limits (Free Exam)</h3>
                    <p>Nicht registrierte Gäste können eine kostenlose Probesitzung starten. Es gilt ein Limit von maximal <strong>3 Abschnitten</strong>. Nach Erreichen des Limits wird der Zugriff gesperrt und der Student aufgefordert, sich zu registrieren oder ein Abonnement abzuschließen.</p>
                </div>
                <div class="guide-item-card">
                    <h3>KI-Sprechübungs-Labor (Voice Practice)</h3>
                    <p>Das Sprechübungs-Labor erlaubt es Ihnen, freies Sprechen interaktiv zu trainieren. Nach Auswahl eines Themas sprechen Sie in Ihr Mikrofon. Die KI transkribiert Ihre Worte und antwortet Ihnen. Es findet eine Unterhaltung über 3 bis 4 Runden statt.</p>
                </div>
                <div class="guide-item-card">
                    <h3>Automatische KI-Bewertung & Notenschlüssel</h3>
                    <p>Nach Abgabe einer Prüfung analysiert die Gemini-KI Ihren schriftlichen Aufsatz und Ihre gesprochene Leistung. Sie erhalten detailliertes Feedback und Punkte. Die Gesamtbewertung der sprachlichen Angemessenheit (max. 32 Punkte) bewertet Wortschatz, Grammatik, Aussprache und Flüssigkeit über das gesamte Gespräch hinweg.</p>
                </div>
            </div>
        </section>

        <div class="page-break"></div>

        {{-- SECTION 4: FEHLERBEHEBUNG (TROUBLESHOOTING) --}}
        <section class="handbook-section">
            <div class="section-badge troubleshooting-color">SYSTEM DIAGNOSTIC</div>
            <h2 class="section-title">4. Fehlerbehebung (Troubleshooting)</h2>
            <div class="section-intro">
                Lösungen für häufig auftretende technische Probleme bei Benutzern und Administratoren.
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
                        <td><strong>Mikrofon wird nicht erkannt / Spracherkennung startet nicht</strong></td>
                        <td>Browser blockiert den Zugriff auf das Mikrofon oder unterstützt die Web Speech API nicht.</td>
                        <td>Nutzen Sie Google Chrome oder Microsoft Edge. Stellen Sie sicher, dass in den Browsereinstellungen der Zugriff auf das Mikrofon für diese Domain freigegeben ist.</td>
                    </tr>
                    <tr>
                        <td><strong>PDF-Lernmaterialien laden nicht</strong></td>
                        <td>
                            @if(auth()->user()?->isAdmin())
                                Fehlender oder fehlerhafter symbolischer Link (storage symlink) auf dem Produktionsserver oder Datei fehlt.
                            @else
                                Verbindungsproblem oder temporärer Ladefehler des Dokuments.
                            @endif
                        </td>
                        <td>
                            @if(auth()->user()?->isAdmin())
                                Die PDF-Datei wird direkt über Laravel ausgeliefert (Serving-Route <code>/books-pdf/...</code>). Ein Symlink ist nicht mehr erforderlich. Falls der Fehler fortbesteht, prüfen Sie, ob die Datei unter <code>storage/app/public/books/</code> existiert.
                            @else
                                Laden Sie die Seite neu oder versuchen Sie, das Dokument erneut zu öffnen. Sollte das Problem bestehen bleiben, wenden Sie sich bitte an den Support.
                            @endif
                        </td>
                    </tr>
                    @if (auth()->user()?->isAdmin())
                        <tr>
                            <td><strong>Audio-Upload schlägt fehl / Validierungsfehler im Dashboard</strong></td>
                            <td>Audiodatei ist zu groß (standardmäßig über 20MB) oder besitzt ein ungültiges Format.</td>
                            <td>Die Upload-Grenze wurde auf <strong>100MB</strong> angehoben. Unterstützt werden die Dateiformate MP3, WAV, OGG und M4A. Verringern Sie ggf. die Samplerate der MP3-Datei.</td>
                        </tr>
                    @endif
                    <tr>
                        <td><strong>Banned / Sitzung abgelaufen (Sicherheitshinweis)</strong></td>
                        <td>Ein anderer Benutzer hat sich mit denselben Zugangsdaten angemeldet (Schutz vor Account-Sharing).</td>
                        <td>Teilen Sie Ihre Zugangsdaten nicht. Bei einer Neuanmeldung auf einem anderen Gerät wird die alte Sitzung zur Sicherheit sofort ungültig. Melden Sie sich erneut an.</td>
                    </tr>
                    <tr>
                        <td><strong>Zahlung getätigt, aber Prüfung bleibt gesperrt</strong></td>
                        <td>Die PayPal-Zahlungsbestätigung wurde noch nicht vom Administrator freigegeben.</td>
                        <td>Laden Sie Ihren Transaktionsbeleg im Portal hoch. Der Administrator sieht Ihren Beleg im Dashboard und schaltet die Prüfung mit einem Klick manuell für Sie frei.</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </div>

    {{-- Custom Page Styles --}}
    @push('styles')
        <style>
            /* General Styles */
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

            /* Title Block */
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
                font-size: 3.2rem;
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

            /* Sections */
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

            /* Cards Layout */
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

            .guide-item-card code {
                background-color: #edf2f7;
                padding: 0.15rem 0.35rem;
                border-radius: 4px;
                font-size: 0.85rem;
                font-weight: 700;
            }

            /* Troubleshooting Table */
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

            .trouble-table td strong {
                color: #1a202c;
            }

            .trouble-table td code {
                background-color: #edf2f7;
                padding: 0.15rem 0.35rem;
                border-radius: 4px;
                font-size: 0.85rem;
                font-weight: 700;
            }

            .page-break {
                display: none;
            }

            /* PDF Print Specific Optimization Styles */
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

                .section-badge {
                    border: 1px solid #4a5568 !important;
                    color: #4a5568 !important;
                    background: transparent !important;
                }
            }
        </style>
    @endpush
</x-layouts::app>
