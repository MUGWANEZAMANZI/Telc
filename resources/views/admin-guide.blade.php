<x-layouts::app :title="__('Admin Guide – Systemadministration & Verwaltung')">
    <div class="admin-guide-shell">
        <div class="admin-guide-header">
            <h1>Administratoren-Handbuch</h1>
            <p>Systemverwaltung, Prüfungserstellung & Freigabeprozesse</p>
        </div>

        <div x-data="{ folder: 'telc' }" style="margin-top: 1.5rem;">
            <div style="display: flex; gap: 1rem; border-bottom: 2px solid #e5e7eb; padding-bottom: 0.5rem; margin-bottom: 2rem;">
                <button type="button" @click="folder = 'telc'" :style="folder === 'telc' ? 'border-bottom: 3px solid #d8232a; font-weight: 700; color: #d8232a;' : 'color: #6b7280;'" style="padding: 0.5rem 1rem; background: none; border: none; cursor: pointer; font-size: 1rem;">
                    📂 TELC & Goethe Ordner
                </button>
                <button type="button" @click="folder = 'answering'" :style="folder === 'answering' ? 'border-bottom: 3px solid #d8232a; font-weight: 700; color: #d8232a;' : 'color: #6b7280;'" style="padding: 0.5rem 1rem; background: none; border: none; cursor: pointer; font-size: 1rem;">
                    📝 Lückentexte & Fragetypen
                </button>
                <button type="button" @click="folder = 'pricing'" :style="folder === 'pricing' ? 'border-bottom: 3px solid #d8232a; font-weight: 700; color: #d8232a;' : 'color: #6b7280;'" style="padding: 0.5rem 1rem; background: none; border: none; cursor: pointer; font-size: 1rem;">
                    💳 Tarife & Freigaben
                </button>
                <button type="button" @click="folder = 'teaching'" :style="folder === 'teaching' ? 'border-bottom: 3px solid #d8232a; font-weight: 700; color: #d8232a;' : 'color: #6b7280;'" style="padding: 0.5rem 1rem; background: none; border: none; cursor: pointer; font-size: 1rem;">
                    🤖 KI-Unterrichtsmodell
                </button>
            </div>

            {{-- Tab 1: Ordner verwalten --}}
            <div x-show="folder === 'telc'" class="guide-grid">
                <article class="guide-card">
                    <span class="guide-step">1</span>
                    <h2>Klassifizierung nach Anbieter</h2>
                    <p>Alle erstellten Prüfungsvorlagen werden dem <strong>TELC-Ordner</strong> oder dem <strong>Goethe-Ordner</strong> zugewiesen. Dies steuert die Einordnung in den jeweiligen Tabs des Admin-Dashboards.</p>
                </article>

                <article class="guide-card">
                    <span class="guide-step">2</span>
                    <h2>Audio-Ressourcen verknüpfen</h2>
                    <p>Für Hörverstehensübungen kopieren Sie die MP3-Audiodatei direkt in das Verzeichnis <code>instructions/</code> des Webservers. Geben Sie im Audioelement des Builders als Pfad <code>/telc-assets/dateiname.mp3</code> an.</p>
                </article>
            </div>

            {{-- Tab 2: Lückentexte --}}
            <div x-show="folder === 'answering'" class="guide-grid">
                <article class="guide-card">
                    <span class="guide-step">1</span>
                    <h2>Syntax der Lückentexte</h2>
                    <p>Im Builder wird das Lückentext-Element als <strong>Inline-Gap</strong> angelegt. Schreiben Sie den Fließtext und markieren Sie die Lücken fortlaufend mit <code>--0--</code>, <code>--1--</code>, <code>--2--</code> usw.</p>
                </article>

                <article class="guide-card">
                    <span class="guide-step">2</span>
                    <h2>Antwortoptionen anlegen</h2>
                    <p>Der Lückentext-Manager auf der rechten Seite erlaubt es Ihnen, für jede Lücke drei Optionen (a, b, c) sowie deren jeweiligen Antworttext einzutragen. Markieren Sie die korrekte Option mit dem Haken-Symbol (✓ Correct).</p>
                </article>
            </div>

            {{-- Tab 3: Tarife & Freigaben --}}
            <div x-show="folder === 'pricing'" class="guide-grid">
                <article class="guide-card">
                    <span class="guide-step">1</span>
                    <h2>Zugangstypen festlegen</h2>
                    <p>Im Builder-Panel kann jede Prüfung als <strong>Kostenlos (Free)</strong> oder <strong>Kostenpflichtig (Paid)</strong> konfiguriert werden. Kostenlose Prüfungen stehen allen Benutzern sofort zur Verfügung.</p>
                </article>

                <article class="guide-card">
                    <span class="guide-step">2</span>
                    <h2>Manuelle PayPal-Freigabe</h2>
                    <p>Sobald ein Student einen PayPal-Zahlungsbeleg einreicht, erscheint der Antrag am Ende des Admin-Dashboards. Prüfen Sie die Transaktions-ID und geben Sie die Prüfung per Klick auf <strong>Freigeben</strong> frei.</p>
                </article>
            </div>

            {{-- Tab 4: KI-Modell --}}
            <div x-show="folder === 'teaching'" class="guide-grid">
                <article class="guide-card">
                    <span class="guide-step">1</span>
                    <h2>Aufsatz-Bewertung</h2>
                    <p>Bei der Korrektur schriftlicher Ausarbeitungen wertet das Gemini-Sprachmodell Rechtschreibung, Grammatik, Stil und Kohärenz anhand offizieller C1-Kriterien aus und gibt eine detaillierte Begründung ab.</p>
                </article>

                <article class="guide-card">
                    <span class="guide-step">2</span>
                    <h2>Sprechprüfungs-Agent</h2>
                    <p>Der AI-Prüfer im mündlichen Bereich reagiert dynamisch auf die Antworten der Teilnehmer, stellt passende Anschlussfragen und bewertet den Redefluss nach drei Runden interaktivem Austausch.</p>
                </article>
            </div>
        </div>

        <div class="guide-tips">
            <h2>Wichtige Administrationshinweise</h2>
            <ul>
                <li><strong>Account-Sharing-Schutz:</strong> Das System lässt keine Mehrfachanmeldungen mit denselben Zugangsdaten zu. Meldet sich ein Nutzer auf einem neuen Gerät an, wird die vorherige Sitzung automatisch gelöscht.</li>
                <li><strong>Entwurfsmodus:</strong> Unveröffentlichte Prüfungen (Draft) sind für Studenten im Katalog nicht sichtbar und können gefahrlos bearbeitet werden.</li>
            </ul>
        </div>
    </div>

    @push('styles')
        <style>
            .admin-guide-shell { max-width: 1200px; margin: 0 auto; padding: 2rem; font-family: 'Plus Jakarta Sans', sans-serif; }
            .admin-guide-header { margin-bottom: 2.5rem; }
            .admin-guide-header h1 { font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 700; color: #111827; margin: 0; }
            .admin-guide-header p { color: #6b7280; margin-top: .5rem; font-size: 1.1rem; }
            .guide-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 2rem; margin-top: 1.5rem; }
            .guide-card { background: #fff; border: 1px solid #e5e7eb; border-radius: .75rem; padding: 2rem 1.5rem; position: relative; box-shadow: 0 4px 10px rgba(0,0,0,0.02); }
            .guide-step { position: absolute; top: -0.75rem; left: 1rem; background: #d8232a; color: #fff; width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; border-radius: 9999px; font-weight: 700; font-size: .875rem; }
            .guide-card h2 { font-size: 1.25rem; font-weight: 700; margin: 0 0 .75rem; color: #111827; }
            .guide-card p { color: #4b5563; line-height: 1.7; margin: 0; font-size: .95rem; }
            .guide-card code { background: #f3f4f6; padding: .15rem .35rem; border-radius: .25rem; font-size: .85rem; font-weight: 700; color: #1f2937; }
            .guide-tips { margin-top: 3rem; background: #fafafa; border: 1px solid #e5e7eb; border-radius: .75rem; padding: 2rem; }
            .guide-tips h2 { font-family: 'Playfair Display', serif; font-size: 1.6rem; font-weight: 700; margin: 0 0 1.25rem; color: #111827; }
            .guide-tips ul { margin: 0; padding-left: 1.5rem; }
            .guide-tips li { color: #4b5563; margin-bottom: 0.75rem; line-height: 1.6; font-size: 0.95rem; }
        </style>
    @endpush
</x-layouts::app>
