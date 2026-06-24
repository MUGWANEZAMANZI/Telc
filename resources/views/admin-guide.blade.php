<x-layouts::app :title="__('Admin Guide – Prüfung erstellen')">
    <div class="admin-guide-shell">
        <div class="admin-guide-header">
            <h1>Prüfungen erstellen & verwalten</h1>
            <p>Schritt-für-Schritt-Anleitung für Administratoren</p>
        </div>

        <div class="guide-grid">
            <article class="guide-card">
                <span class="guide-step">1</span>
                <h2>Preset verwenden</h2>
                <p>Im Dashboard unter <strong>Presets</strong> auf "New B2" oder "New C1" klicken. Dadurch wird eine fertige Prüfungsvorlage mit allen Abschnitten und Lösungen erstellt.</p>
            </article>

            <article class="guide-card">
                <span class="guide-step">2</span>
                <h2>Abschnitte bearbeiten</h2>
                <p>Jede Prüfung besteht aus Prüfungsteilen (Lesen, Hören, Schreiben, Sprechen). Titel und Bearbeitungszeit können angepasst werden. Neue Teile können über "Add Part" hinzugefügt werden.</p>
            </article>

            <article class="guide-card">
                <span class="guide-step">3</span>
                <h2>Elemente hinzufügen</h2>
                <p>Pro Abschnitt können verschiedene Aufgabentypen eingefügt werden:</p>
                <ul>
                    <li><strong>Multiple Choice</strong> – Optionen als Komma-getrennte Liste (z.B. <code>a,b,c,d</code>)</li>
                    <li><strong>Zuordnung</strong> – Zum Zuordnen von Überschriften zu Absätzen</li>
                    <li><strong>Lückentext (inline)</strong> – Text mit einblendbaren Auswahlboxen. Lücken mit <code>--0--</code>, <code>--1--</code> markieren, Optionen als JSON definieren</li>
                    <li><strong>Richtig/Falsch</strong> – Mit Optionen <code>+,-,x</code></li>
                    <li><strong>Sprechen (KI)</strong> – Sprachaufnahme mit KI-Bewertung</li>
                </ul>
            </article>

            <article class="guide-card">
                <span class="guide-step">4</span>
                <h2>Lösungen eintragen</h2>
                <p>Bei bewertbaren Elementen das Feld <strong>Correct Answer</strong> ausfüllen. Bei Multiple Choice den Buchstaben (z.B. <code>b</code>), bei True/False <code>+</code> oder <code>-</code>.</p>
            </article>

            <article class="guide-card">
                <span class="guide-step">5</span>
                <h2>Audio-Dateien</h2>
                <p>MP3-Dateien in den Ordner <code>instructions/</code> legen. Im Element die URL <code>/telc-assets/dateiname.mp3</code> angeben. Die Datei wird dann im Hörverstehen-Teil abgespielt.</p>
            </article>

            <article class="guide-card">
                <span class="guide-step">6</span>
                <h2>Veröffentlichen</h2>
                <p>Im Dashboard auf <strong>Publish</strong> klicken. Nur veröffentlichte Prüfungen sind für Studenten im Exam Store sichtbar und können belegt werden.</p>
            </article>

            <article class="guide-card">
                <span class="guide-step">7</span>
                <h2>KI-Sprachbewertung</h2>
                <p>Für die mündliche Prüfung: Der Student nimmt seine Antwort per Mikrofon auf, die Transkription wird an Gemini gesendet. Der API-Key muss in der <code>.env</code> unter <code>GEMINI_API_KEY</code> gesetzt sein.</p>
            </article>

            <article class="guide-card">
                <span class="guide-step">8</span>
                <h2>Studenten verwalten</h2>
                <p>Im Dashboard werden Metriken zu allen Studenten angezeigt: Anmeldedaten, Versuche, Punktzahlen. Neue Studenten können über die Registrierungsseite hinzugefügt werden.</p>
            </article>
        </div>

        <div class="guide-tips">
            <h2>Tipps</h2>
            <ul>
                <li>Die Lückentext-Optionen werden im Format <code>a|Text,b|Text,c|Text</code> angegeben. Der Buchstabe vor dem Pipe-Zeichen ist der Antwortwert.</li>
                <li>Das Theme (Akzentfarbe, Schriftart) kann pro Prüfung angepasst werden.</li>
                <li>Der Studenten-Modus im Dashboard zeigt eine Vorschau, wie die Prüfung für Studenten aussieht.</li>
            </ul>
        </div>
    </div>

    @push('styles')
        <style>
            .admin-guide-shell { max-width: 1200px; margin: 0 auto; padding: 2rem; }
            .admin-guide-header { margin-bottom: 2.5rem; }
            .admin-guide-header h1 { font-size: 2rem; font-weight: 700; color: #111827; margin: 0; }
            .admin-guide-header p { color: #6b7280; margin-top: .5rem; }
            .guide-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.5rem; }
            .guide-card { background: #fff; border: 1px solid #e5e7eb; border-radius: .75rem; padding: 1.5rem; position: relative; }
            .guide-step { position: absolute; top: -0.75rem; left: 1rem; background: #6366f1; color: #fff; width: 2rem; height: 2rem; display: flex; align-items: center; justify-content: center; border-radius: 9999px; font-weight: 700; font-size: .875rem; }
            .guide-card h2 { font-size: 1.125rem; font-weight: 600; margin: 0 0 .75rem; color: #111827; }
            .guide-card p { color: #374151; line-height: 1.6; margin: 0; font-size: .925rem; }
            .guide-card ul { margin: .75rem 0 0; padding-left: 1.25rem; }
            .guide-card li { color: #374151; font-size: .875rem; margin-bottom: .35rem; }
            .guide-card code { background: #f3f4f6; padding: .1rem .3rem; border-radius: .25rem; font-size: .8125rem; }
            .guide-tips { margin-top: 2.5rem; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: .75rem; padding: 1.5rem; }
            .guide-tips h2 { font-size: 1.25rem; font-weight: 600; margin: 0 0 1rem; }
            .guide-tips ul { margin: 0; padding-left: 1.25rem; }
            .guide-tips li { color: #374151; margin-bottom: .5rem; line-height: 1.5; }
        </style>
    @endpush
</x-layouts::app>
