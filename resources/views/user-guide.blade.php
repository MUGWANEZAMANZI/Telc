<x-layouts::app :title="__('Studenten-Handbuch – Anleitung zur Prüfungssimulation')">
    <div class="user-guide-shell">
        <div class="user-guide-header">
            <h1>Studenten-Handbuch</h1>
            <p>Anleitung zur Durchführung von Prüfungen, Sprechübungen & Abonnement-Details</p>
        </div>

        <div class="guide-section">
            <h2>1. Durchführung einer Prüfungssimulation</h2>
            <p>Die Prüfungsdurchführung wurde speziell dem originalen telc und Goethe Dokumenten-Layout nachempfunden. Folgen Sie diesen Schritten für ein optimales Ergebnis:</p>
            <ol>
                <li>Navigieren Sie zum <strong>Dashboard</strong> und wählen Sie die gewünschte Prüfung aus dem Katalog aus.</li>
                <li>Sind Sie für die Prüfung freigeschaltet, klicken Sie auf <strong>Prüfung starten</strong>.</li>
                <li>Arbeiten Sie die Aufgabenstellungen linear ab. Der Timer am oberen/unteren Rand zeigt Ihnen die verbleibende Restzeit an.</li>
                <li>Bei <strong>Lückentexten</strong> wählen Sie Ihre Antwort per Klick auf die Lücke direkt im Fließtext aus.</li>
                <li>Bei <strong>Schreibaufgaben</strong> nutzen Sie das linierte Textfeld für Ihren Aufsatz.</li>
                <li>Klicken Sie nach Abschluss aller Abschnitte auf <strong>Prüfung abgeben</strong>, um das Prüfungsergebnis einzureichen.</li>
            </ol>
        </div>

        <div class="guide-section">
            <h2>2. Mündliches KI-Sprachtraining (Voice Practice)</h2>
            <p>Das Sprechübungsmodul nutzt Spracherkennung (Web Speech API) und das Gemini-KI-Modell, um Sie interaktiv zu prüfen:</p>
            <ul>
                <li><strong>Themenauswahl:</strong> Wählen Sie ein C1-Prüfungsthema aus den Optionen aus.</li>
                <li><strong>Aufnahme starten:</strong> Klicken Sie auf <strong>Sprechen (🎤)</strong> und antworten Sie laut und deutlich in Ihr Mikrofon. Klicken Sie nach Beendigung auf Stopp.</li>
                <li><strong>KI-Analyse:</strong> Unser virtueller C1-Prüfer wertet Ihre Aussprache, Satzstrukturen, Wortwahl und Grammatik aus und führt die Konversation über 3 Runden fort.</li>
            </ul>
        </div>

        <div class="guide-section">
            <h2>3. Tarife & Abonnement-Struktur</h2>
            <p>Der Zugriff auf kostenpflichtige Prüfungen wird über folgende Optionen gesteuert:</p>
            <div class="pricing-overview">
                <div class="price-box">
                    <h3>Einzelprüfung</h3>
                    <strong>5,00 €</strong>
                    <p>Einmalige Freischaltung einer Prüfung Ihrer Wahl.</p>
                </div>
                <div class="price-box highlighted">
                    <h3>Monatstraining</h3>
                    <strong>19,00 €</strong>
                    <p>Voller Zugriff auf bis zu 30 Prüfungssimulationen pro Monat.</p>
                </div>
                <div class="price-box">
                    <h3>Intensivkurs</h3>
                    <strong>69,00 €</strong>
                    <p>Voller Zugriff auf bis zu 60 Prüfungssimulationen pro Monat.</p>
                </div>
            </div>
            <p style="margin-top: 1.5rem;">Zahlungen werden sicher via PayPal abgewickelt. Nachdem Sie den Betrag überwiesen haben, übermitteln Sie einfach Ihre PayPal-Transaktions-ID im Portal über die Schaltfläche <strong>"Beleg senden"</strong>. Ein Administrator schaltet Ihren Zugang anschließend frei.</p>
        </div>

        <div class="guide-section">
            <h2>4. Schutz vor Account-Sharing</h2>
            <p>Um die missbräuchliche Weitergabe von Zugängen zu verhindern, erlaubt die Plattform nur <strong>eine aktive Anmeldung pro Benutzerkonto zur gleichen Zeit</strong>. Sobald Sie sich auf einem neuen Gerät einloggen, wird die Sitzung auf Ihrem vorherigen Gerät automatisch beendet.</p>
        </div>
    </div>

    @push('styles')
        <style>
            .user-guide-shell { max-width: 900px; margin: 0 auto; padding: 2rem; font-family: 'Plus Jakarta Sans', sans-serif; color: #1f2937; }
            .user-guide-header { margin-bottom: 3rem; text-align: center; }
            .user-guide-header h1 { font-family: 'Playfair Display', serif; font-size: 2.6rem; color: #111827; margin: 0; }
            .user-guide-header p { color: #6b7280; margin-top: 0.5rem; font-size: 1.1rem; }
            .guide-section { background: #ffffff; border: 1px solid #e5e7eb; border-radius: 8px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 4px 6px rgba(0,0,0,0.01); }
            .guide-section h2 { font-family: 'Playfair Display', serif; font-size: 1.5rem; color: #111827; margin-top: 0; margin-bottom: 1rem; border-bottom: 2px solid #f3f4f6; padding-bottom: 0.5rem; }
            .guide-section p { line-height: 1.7; font-size: 0.95rem; color: #4b5563; }
            .guide-section ol, .guide-section ul { padding-left: 1.5rem; margin-bottom: 0; }
            .guide-section li { margin-bottom: 0.75rem; line-height: 1.6; font-size: 0.95rem; color: #4b5563; }
            .pricing-overview { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.25rem; margin-top: 2rem; }
            .price-box { border: 1px solid #e5e7eb; border-radius: 6px; padding: 1.5rem; text-align: center; background: #fafafa; }
            .price-box.highlighted { border-color: #d8232a; background: #fffcfc; }
            .price-box h3 { margin: 0 0 0.5rem; font-size: 1.1rem; color: #111827; }
            .price-box strong { font-size: 1.8rem; color: #d8232a; display: block; margin-bottom: 0.5rem; }
            .price-box p { font-size: 0.85rem; margin: 0; color: #666666; }
        </style>
    @endpush
</x-layouts::app>
