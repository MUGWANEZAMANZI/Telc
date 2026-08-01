<?php

namespace App\Services;

class TelcTemplateFactory
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function presets(): array
    {
        return [
            'A1' => self::a1() + ['exam_provider' => 'telc'],
            'A2' => self::a2() + ['exam_provider' => 'telc'],
            'B1' => self::b1() + ['exam_provider' => 'telc'],
            'B2' => self::b2() + ['exam_provider' => 'telc'],
            'C1' => self::c1() + ['exam_provider' => 'telc'],
            'C2' => self::c2() + ['exam_provider' => 'telc'],
            'Goethe-A1' => self::a1() + ['title' => 'Goethe Zertifikat A1', 'level' => 'A1', 'exam_provider' => 'goethe'],
            'Goethe-A2' => self::a2() + ['title' => 'Goethe Zertifikat A2', 'level' => 'A2', 'exam_provider' => 'goethe'],
            'Goethe-B1' => self::b1() + ['title' => 'Goethe Zertifikat B1', 'level' => 'B1', 'exam_provider' => 'goethe'],
            'Goethe-C1' => self::c1() + ['title' => 'Goethe Zertifikat C1', 'level' => 'C1', 'exam_provider' => 'goethe'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function forLevel(string $level): array
    {
        return self::presets()[$level] ?? self::c1();
    }

    /**
     * @return array<string, mixed>
     */
    private static function a1(): array
    {
        return [
            'title' => 'telc Deutsch A1 (Start Deutsch 1)',
            'level' => 'A1',
            'source_pdf' => null,
            'theme' => self::theme('#f59e0b', '#202020', 'Instrument Sans'),
            'timings' => [
                ['label' => 'Leseverstehen und Sprachbausteine', 'minutes' => 25],
                ['label' => 'Hoerverstehen', 'minutes' => 20],
                ['label' => 'Schriftlicher Ausdruck', 'minutes' => 20],
                ['label' => 'Muendliche Pruefung', 'minutes' => 15],
            ],
            'sections' => [
                self::section('a1-reading-language', 'Leseverstehen und Sprachbausteine', 25, [
                    self::text('A1-LV1-text', 'Leseverstehen Teil 1', 'Lesen Sie die Texte und entscheiden Sie: Ist die Aussage Richtig (+) oder Falsch (-)?'),
                    ...self::answerElements('A1-LV1-', 1, ['+', '-', '+', '-', '+'], 'true_false', 2, '+,-'),
                ], self::pages(1, 3)),
                self::section('a1-listening', 'Hörverstehen', 20, [
                    self::audio('A1-audio', 'Hörverstehen Audio', ''),
                    self::text('A1-HV1-text', 'Hörverstehen Teil 1', 'Sie hören fünf kurze Gespräche. Wählen Sie die richtige Antwort (a, b oder c).'),
                    ...self::answerElements('A1-HV1-', 6, ['a', 'c', 'b', 'a', 'b'], 'multiple_choice', 2, 'a,b,c'),
                ], self::pages(4, 5)),
                self::section('a1-writing', 'Schriftlicher Ausdruck', 20, [
                    self::input('A1-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', "Betreff: Einladung zu meiner Geburtstagsparty\n\nHallo Sarah,\nich lade dich herzlich zu meiner Geburtstagsparty ein. Die Party findet am nächsten Samstag um 18:00 Uhr bei mir zu Hause statt. Ich würde mich freuen, wenn du kommen kannst. Bitte gib mir bis Donnerstag Bescheid, ob du Zeit hast.\n\nViele Grüße\nMax", 15),
                ], self::pages(6, 6)),
                self::section('a1-speaking', 'Mündliche Prüfung', 15, [
                    self::speakingElement('A1-SP-1', 'Teil 1: Sich vorstellen', 'Stellen Sie sich vor (Name, Alter, Land, Wohnort, Sprachen, Beruf, Hobby) sowie Buchstabieren & Zahlen.', 5),
                    self::speakingElement('A1-SP-2', 'Teil 2: Um Informationen bitten', 'Formulieren Sie Fragen zu einem Thema/Wort und antworten Sie darauf.', 5),
                    self::speakingElement('A1-SP-3', 'Teil 3: Bitten formulieren', 'Formulieren Sie eine Bitte anhand einer Bildkarte und reagieren Sie auf eine Bitte.', 5),
                ], self::pages(7, 8)),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function a2(): array
    {
        return [
            'title' => 'telc Deutsch A2',
            'level' => 'A2',
            'source_pdf' => null,
            'theme' => self::theme('#3b82f6', '#202020', 'Instrument Sans'),
            'timings' => [
                ['label' => 'Leseverstehen und Sprachbausteine', 'minutes' => 40],
                ['label' => 'Hoerverstehen', 'minutes' => 20],
                ['label' => 'Schriftlicher Ausdruck', 'minutes' => 30],
                ['label' => 'Muendliche Pruefung', 'minutes' => 15],
            ],
            'sections' => [
                self::section('a2-reading-language', 'Leseverstehen und Sprachbausteine', 40, [
                    self::text('A2-LV1-text', 'Leseverstehen Teil 1', 'Lesen Sie die Texte und ordnen Sie zu.'),
                    ...self::answerElements('A2-LV1-', 1, ['a', 'b', 'c', 'a', 'b'], 'multiple_choice', 2, 'a,b,c'),
                ], self::pages(1, 4)),
                self::section('a2-listening', 'Hörverstehen', 20, [
                    self::audio('A2-audio', 'Hörverstehen Audio', ''),
                    ...self::answerElements('A2-HV1-', 6, ['a', 'b', 'c', 'a', 'b'], 'multiple_choice', 2, 'a,b,c'),
                ], self::pages(5, 6)),
                self::section('a2-writing', 'Schriftlicher Ausdruck', 30, [
                    self::input('A2-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', "Betreff: Entschuldigung für das Verpassen des Termins\n\nSehr geehrte Frau Müller,\nich möchte mich aufrichtig dafür entschuldigen, dass ich unseren Termin am Donnerstag verpasst habe. Ich war krank und konnte nicht kommen. Wäre es möglich, einen neuen Termin für nächste Woche zu vereinbaren? Ich bin von Montag bis Mittwoch flexibel.\n\nMit freundlichen Grüßen\nMax Mustermann", 15),
                ], self::pages(7, 7)),
                self::section('a2-speaking', 'Mündliche Prüfung', 15, [
                    self::speakingElement('A2-SP-1', 'Teil 1: Präsentation', 'Sprechen Sie über ein gewähltes Thema.', 5),
                    self::speakingElement('A2-SP-2', 'Teil 2: Ein Gespräch führen', 'Führen Sie ein alltägliches Gespräch über ein Thema.', 5),
                    self::speakingElement('A2-SP-3', 'Teil 3: Etwas aushandeln', 'Planen Sie eine gemeinsame Aktivität mit dem KI-Partner.', 5),
                ], self::pages(8, 9)),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function b1(): array
    {
        return [
            'title' => 'telc Deutsch B1 (Zertifikat Deutsch)',
            'level' => 'B1',
            'source_pdf' => 'telc_deutsch_b1_zd_uebungstest_1.pdf',
            'theme' => self::theme('#ffcc00', '#202020', 'Instrument Sans'),
            'timings' => [
                ['label' => 'Leseverstehen und Sprachbausteine', 'minutes' => 90],
                ['label' => 'Hoerverstehen', 'minutes' => 30],
                ['label' => 'Schriftlicher Ausdruck', 'minutes' => 30],
                ['label' => 'Muendliche Pruefung', 'minutes' => 15],
            ],
            'sections' => [
                self::section('b1-reading-language', 'Leseverstehen und Sprachbausteine', 90, [
                    self::text('B1-LV1-text', 'Leseverstehen Teil 1', "Person 1: Frau Meier ist 45 Jahre alt und arbeitet als Sekretärin. Sie möchte sich beruflich verändern und sucht einen Kurs, in dem sie neue Computerkenntnisse lernen kann.\n\nPerson 2: Herr Schmidt, 28 Jahre, ist Student und sucht einen Nebenjob für die Semesterferien. Er hat Erfahrung in der Gastronomie.\n\nPerson 3: Familie Wagner sucht eine geeignete Wohnung in der Stadtmitte. Die Wohnung soll drei Zimmer haben und nicht mehr als 800 Euro kosten.\n\nPerson 4: Die 19-jährige Lisa sucht ein günstiges Fitnessstudio in ihrer Nähe. Sie möchte am liebsten Kurse besuchen.\n\nPerson 5: Herr Braun, 60 Jahre, geht bald in Rente und sucht ein Hobby. Er interessiert sich für Kunst und Kultur.", 'Lesen Sie die Überschriften a–j und die Texte 1–5. Finden Sie für jeden Text die passende Überschrift. Sie können jede Überschrift nur einmal benutzen. Markieren Sie Ihre Lösungen für die Aufgaben 1–5 auf dem Antwortbogen.'),
                    ...self::answerElements('B1-LV1-', 1, ['a', 'b', 'b', 'a', 'a'], 'matching', 2, 'a,b,c'),
                    self::text('B1-LV2-text', 'Leseverstehen Teil 2', 'Immer mehr Menschen in Deutschland entscheiden sich für ein Leben ohne Auto. Besonders in großen Städten wie Berlin, München oder Hamburg steigt die Zahl der Haushalte, die bewusst auf ein eigenes Auto verzichten. Die Gründe dafür sind vielfältig: Hohe Parkgebühren, Staus im Berufsverkehr und nicht zuletzt der Wunsch, einen Beitrag zum Umweltschutz zu leisten. Viele Menschen nutzen stattdessen öffentliche Verkehrsmittel, Fahrräder oder Carsharing-Angebote. Eine aktuelle Studie zeigt, dass fast 30 Prozent der Stadtbewohner ihr Auto höchstens einmal pro Woche nutzen. Besonders junge Menschen zwischen 18 und 35 Jahren sehen das Auto nicht mehr als Statussymbol an. Für sie ist es wichtiger, flexibel und umweltbewusst zu sein.', 'Lesen Sie den Text und die Aufgaben 6–10. Welche Lösung (a, b oder c) ist jeweils richtig? Markieren Sie Ihre Lösungen für die Aufgaben 6–10 auf dem Antwortbogen.'),
                    ...self::answerElements('B1-LV2-', 6, ['b', 'c', 'a', 'b', 'c'], 'multiple_choice', 2, 'a,b,c'),
                    self::text('B1-LV3-text', 'Leseverstehen Teil 3', "Immer mehr Menschen in Deutschland achten auf gesunde Ernährung. Sie kaufen Bioprodukte und verzichten auf Fertiggerichte. In vielen Kantinen gibt es inzwischen auch vegetarische Gerichte. Eine Studie zeigt, dass junge Menschen mehr Obst essen als früher.\n\nAussage 11: Die meisten Menschen essen jeden Tag Bio-Produkte.\nAussage 12: In Kantinen gibt es auch vegetarische Gerichte.\nAussage 13: Fertiggerichte sind billiger als frische Produkte.\nAussage 14: Die Deutschen essen mehr Fleisch als früher.\nAussage 15: Eine Studie zeigt, dass junge Leute mehr Obst essen.\nAussage 16: In vielen Supermärkten gibt es eine Biotheke.\nAussage 17: Restaurants bieten spezielle Diätgerichte an.\nAussage 18: Gemüse ist teurer als Fleisch.\nAussage 19: Viele Menschen kaufen auf dem Wochenmarkt ein.\nAussage 20: Schulkinder bekommen kostenloses Obst.", 'Lesen Sie die Aussagen (Aufgaben 11–20) zum Thema »Gesundes Essen«. Markieren Sie: + (richtig), – (falsch) oder x (nicht im Text).'),
                    ...self::answerElements('B1-LV3-', 11, ['x', '+', 'x', '-', '+', 'x', 'x', '-', 'x', 'x'], 'true_false', 2, '+,-,x'),
                    self::input('B1-LV3-21', 'Aufgabe 21 — Zusammenfassung', 'multiple_choice', 'b', 2, 'a,b,c'),
                    self::inlineGap('B1-SB-inline', 'Sprachbausteine Teil 1', "Sehr geehrter Herr Meyer,\nich schreibe Ihnen, weil --0-- am letzten Donnerstag mein Arbeitsvertrag verlängert wurde. Ich möchte mich herzlich für das --1-- Vertrauen bedanken. Für die Zukunft --2-- ich mir eine weiterhin gute Zusammenarbeit.\nMit freundlichen Grüßen\nMax Mustermann", [
                        ['options' => 'a|ich,b|mir,c|mich', 'answer' => 'a'],
                        ['options' => 'a|gezeigte,b|gezeigtem,c|gezeigten', 'answer' => 'a'],
                        ['options' => 'a|wünsche,b|hoffe,c|denke', 'answer' => 'a'],
                    ], 1, 'Lesen Sie den Text und schließen Sie die Lücken 21–30. Welche Lösung (a, b oder c) ist jeweils richtig? Markieren Sie Ihre Lösungen für die Aufgaben 21–30 auf dem Antwortbogen.'),
                ], self::pages(1, 5)),
                self::section('b1-listening', 'Hörverstehen', 30, [
                    self::audio('B1-audio', 'Hörverstehen Audio', ''),
                    self::text('B1-HV1-text', 'Hörverstehen Teil 1', 'Sie hören sechs kurze Texte. Ordnen Sie jeden Text einer Aussage (a–h) zu. Sie hören jeden Text zweimal.', 'Sie hören nun fünf kurze Texte. Dazu sollen Sie fünf Aufgaben lösen. Sie hören diese Texte nur einmal. Entscheiden Sie beim Hören, ob die Aussagen 41-45 richtig oder falsch sind. Markieren Sie Ihre Lösungen auf dem Antwortbogen bei den Aufgaben 41-45. Markieren Sie PLUS (+) gleich richtig und MINUS (–) gleich falsch auf dem Antwortbogen.'),
                    ...self::answerElements('B1-HV1-', 22, ['c', 'e', 'a', 'h', 'f', 'b'], 'matching', 1, 'a,b,c,d,e,f,g,h'),
                    self::text('B1-HV2-text', 'Hörverstehen Teil 2', 'Sie hören ein Gespräch. Wählen Sie die richtige Antwort (a, b oder c). Sie hören den Text zweimal.', 'Sie hören nun ein Gespräch. Dazu sollen Sie zehn Aufgaben lösen. Sie hören das Gespräch zweimal. Entscheiden Sie beim Hören, ob die Aussagen 46-55 richtig oder falsch sind.'),
                    ...self::answerElements('B1-HV2-', 28, ['a', 'c', 'b', 'a', 'c', 'b', 'a', 'c'], 'multiple_choice', 2, 'a,b,c'),
                    self::text('B1-HV3-text', 'Hörverstehen Teil 3', 'Sie hören eine Durchsage. Ergänzen Sie die fehlenden Informationen. Sie hören den Text zweimal.', 'Sie hören eine Ankündigung. Ergänzen Sie die fehlenden Informationen.'),
                    ...self::answerElements('B1-HV3-', 36, ['Montag', '9 Uhr', 'Bücherei', 'Mappe', 'Formular', 'Passfoto', 'Unterschrift', 'bar'], 'fill_blank', 2),
                ], self::pages(6, 8)),
                self::section('b1-writing', 'Schriftlicher Ausdruck', 30, [
                    self::input('B1-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', "Sehr geehrte Damen und Herren,\nich schreibe Ihnen, um mich über den Laptop zu beschweren, den ich letzte Woche in Ihrem Online-Shop gekauft habe. Leider funktioniert der Bildschirm nicht richtig und flackert ständig. Da ich das Gerät dringend für meine Arbeit benötige, bitte ich Sie um einen schnellen Umtausch oder eine Rückerstattung des Kaufpreises.\nIch erwarte Ihre baldige Rückmeldung.\n\nMit freundlichen Grüßen\nJohannes Schmidt", 48),
                ], self::pages(9, 9)),
                self::section('b1-speaking', 'Mündliche Prüfung', 15, [
                    self::speakingElement('B1-SP-1a', '1a: Präsentation', 'Präsentation (ca. 3 Minuten). Wählen Sie ein Thema: 1. Mein Lieblingshobby / 2. Meine letzte Reise', 6),
                    self::speakingElement('B1-SP-1b', '1b: Zusammenfassung und Reaktion', 'Hören Sie die KI-Präsentation zum anderen Thema und antworten Sie darauf (ca. 2 Minuten).', 4),
                    self::speakingElement('B1-SP-2', '2: Diskussion', 'Diskussion (ca. 5 Minuten). Thema: Sollten Kinder in der Schule ein Smartphone benutzen dürfen?', 6),
                    self::speakingElement('B1-SP-GLOBAL', 'Punkte für sprachliche Angemessenheit', 'Gesamtbewertung: Flüssigkeit, Wortschatz, Grammatik, Aussprache', 32),
                ], self::pages(10, 11)),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function c2(): array
    {
        return [
            'title' => 'telc Deutsch C2',
            'level' => 'C2',
            'source_pdf' => null,
            'theme' => self::theme('#000000', '#202020', 'Instrument Sans'),
            'timings' => [
                ['label' => 'Leseverstehen', 'minutes' => 80],
                ['label' => 'Hoerverstehen', 'minutes' => 60],
                ['label' => 'Schriftlicher Ausdruck', 'minutes' => 80],
                ['label' => 'Muendliche Pruefung', 'minutes' => 15],
            ],
            'sections' => [
                self::section('c2-reading', 'Leseverstehen', 80, [
                    self::text('C2-LV1-text', 'Leseverstehen Teil 1 — Multiple Choice', "Lesen Sie den folgenden wissenschaftlichen Text und beantworten Sie die Fragen (Aufgaben 1–8).\n\nDie globale Erwärmung stellt die Menschheit vor beispiellose Herausforderungen. In den letzten Jahrzehnten hat die wissenschaftliche Gemeinschaft unmissverständliche Beweise dafür vorgelegt, dass der anthropogene Klimawandel durch die Emission von Treibhausgasen beschleunigt wird. Besonders besorgniserregend ist die Tatsache, dass die durchschnittlichen globalen Temperaturen seit Beginn der Industrialisierung um etwa 1,2 Grad Celsius gestiegen sind. Die Folgen sind vielfältig und betreffen nahezu alle Ökosysteme der Erde.\n\nEin besonders gravierendes Problem stellt der Anstieg des Meeresspiegels dar. Durch das Abschmelzen der polaren Eismassen und die thermische Ausdehnung der Ozeane drohen Küstenregionen weltweit langfristig unbewohnbar zu werden. Schätzungen zufolge könnten bis zum Jahr 2100 mehr als 200 Millionen Menschen von direkten Folgen des Meeresspiegelanstiegs betroffen sein.\n\nGleichzeitig nimmt die Häufigkeit und Intensität von Extremwetterereignissen zu. Hitzewellen, Dürreperioden und Starkregenereignisse treten immer häufiger auf und verursachen erhebliche wirtschaftliche Schäden. Die Landwirtschaft steht vor der Herausforderung, sich an veränderte klimatische Bedingungen anzupassen. In vielen Regionen der Welt führen langanhaltende Dürren zu Ernteausfällen und Nahrungsmittelknappheit.\n\nDie internationale Staatengemeinschaft hat auf diese Entwicklungen mit einer Reihe von Abkommen reagiert, darunter das Pariser Klimaabkommen von 2015, das die Begrenzung der Erderwärmung auf deutlich unter 2 Grad Celsius zum Ziel hat. Obwohl dieses Abkommen einen wichtigen Meilenstein darstellt, bleibt die Umsetzung der vereinbarten Maßnahmen hinter den Notwendigkeiten zurück. Kritiker bemängeln insbesondere die mangelnde Verbindlichkeit der nationalen Klimaschutzpläne.\n\nEine Schlüsselrolle bei der... (line truncated to 2000 chars)"),
                    ...self::answerElements('C2-LV1-', 1, ['c', 'd', 'b', 'a', 'd', 'c', 'b', 'a'], 'multiple_choice', 2, 'a,b,c,d'),
                    self::text('C2-LV2-text', 'Leseverstehen Teil 2 — Zuordnung', 'Lesen Sie die folgenden Kurztexte (Aufgaben 9–14). Ordnen Sie jedem Text die passende Überschrift (a–h) zu. Jede Überschrift kann nur einmal verwendet werden.'),
                    ...self::answerElements('C2-LV2-', 9, ['e', 'a', 'g', 'c', 'h', 'f'], 'matching', 2, 'a,b,c,d,e,f,g,h'),
                    self::text('C2-LV3-text', 'Leseverstehen Teil 3 — Richtig/Falsch', 'Lesen Sie die Aussagen (Aufgaben 15–26). Entscheiden Sie, ob die Aussagen + (richtig), – (falsch) oder x (nicht im Text) sind, basierend auf dem Text aus Teil 1.'),
                    ...self::answerElements('C2-LV3-', 15, ['+', '-', 'x', '+', 'x', '-', '+', '+', 'x', '-', '-', '+'], 'true_false', 2, '+,-,x'),
                    self::input('C2-LV3-27', 'Aufgabe 27 — Textverständnis', 'multiple_choice', 'b', 2, 'a,b,c,d'),
                    self::inlineGap('C2-SB-inline', 'Sprachbausteine (Wissenschaftssprache)', 'In der heutigen Debatte um die Nachhaltigkeit --0-- es von entscheidender Bedeutung, alternative Energiequellen umfassend zu erforschen. Zwar existieren bereits zahlreiche Ansätze, doch --1-- viele davon an der mangelnden Skalierbarkeit. Um einen substanziellen Beitrag leisten zu --2--, bedarf es einer verstärkten Kooperation zwischen Wissenschaft und Wirtschaft. Darüber hinaus --3-- die politischen Rahmenbedingungen geschaffen werden, um --4-- Investitionen in grüne Technologien zu fördern. Nur --5-- kann langfristig eine nachhaltige Entwicklung --6-- werden.', [
                        ['options' => 'a|ist,b|wäre,c|wird', 'answer' => 'a'],
                        ['options' => 'a|scheitern,b|gelingen,c|verzögern', 'answer' => 'a'],
                        ['options' => 'a|können,b|dürfen,c|sollen', 'answer' => 'c'],
                        ['options' => 'a|müssen,b|dürften,c|könnten', 'answer' => 'a'],
                        ['options' => 'a|gezielte,b|willkürliche,c|spontane', 'answer' => 'a'],
                        ['options' => 'a|dadurch,b|hierfür,c|hierdurch', 'answer' => 'b'],
                        ['options' => 'a|gewährleistet,b|verhindert,c|ausgeschlossen', 'answer' => 'a'],
                    ], 2),
                ], self::pages(1, 8)),
                self::section('c2-listening', 'Hoerverstehen', 60, [
                    self::audio('C2-audio', 'Hoerverstehen Audio', ''),
                    self::text('C2-HV1-text', 'Hoerverstehen Teil 1 — Zuordnung', 'Sie hören zehn kurze Beiträge von verschiedenen Wissenschaftlern. Ordnen Sie jedem Beitrag das passende Thema (a–l) zu. Sie hören den Text einmal.'),
                    ...self::answerElements('C2-HV1-', 28, ['j', 'c', 'h', 'a', 'l', 'e', 'g', 'b', 'k', 'd'], 'matching', 1, 'a,b,c,d,e,f,g,h,i,j,k,l'),
                    self::text('C2-HV2-text', 'Hoerverstehen Teil 2 — Multiple Choice', 'Sie hören eine Diskussionsrunde zum Thema „Künstliche Intelligenz und Arbeitsmarkt". Wählen Sie die richtige Antwort (a, b oder c). Sie hören den Text zweimal.'),
                    ...self::answerElements('C2-HV2-', 38, ['b', 'c', 'a', 'b', 'c', 'a', 'c', 'b', 'a', 'c', 'b', 'a'], 'multiple_choice', 2, 'a,b,c'),
                    self::text('C2-HV3-text', 'Hoerverstehen Teil 3 — Notizen ergänzen', 'Sie hören einen Vortrag über nachhaltige Stadtentwicklung. Ergänzen Sie die fehlenden Informationen. Sie hören den Text zweimal.'),
                    ...self::answerElements('C2-HV3-', 50, ['Urban Gardening', 'CO2-neutral', 'Schwammstadt', 'Dachbegrünung', 'Photovoltaik', 'Wärmepumpe', 'Carsharing', 'Nahverkehr', 'Bürgerbeteiligung', 'Leitbild'], 'fill_blank', 2),
                ], self::pages(9, 12)),
                self::section('c2-writing', 'Schriftlicher Ausdruck', 80, [
                    self::input('C2-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', "Kritische Reflexion der Digitalisierung im Bildungssystem\n\nDie Digitalisierung des Schulwesens schreitet unaufhaltsam voran. Während Befürworter eine Demokratisierung des Wissenszugangs und die Förderung digitaler Kompetenzen betonen, warnen Kritiker vor einer zunehmenden sozialen Disparität und dem Verlust grundlegender kognitiver Fertigkeiten.\nEine differenzierte Betrachtung offenbart, dass die bloße Bereitstellung digitaler Endgeräte noch keinen pädagogischen Mehrwert generiert. Vielmehr bedarf es didaktisch fundierter Konzepte und einer kontinuierlichen Fortbildung des Lehrpersonals, um Medienkompetenz zielführend zu vermitteln. Andernfalls droht eine oberflächliche Beschäftigung mit Lerninhalten zu Lasten von Tiefenverständnis und Konzentrationsfähigkeit.\nSchlussfolgernd ist haftzuhalten, dass digitale Medien als Ergänzung, nicht jedoch als Ersatz für den analogen, zwischenmenschlichen Diskurs im Klassenzimmer fungieren sollten.", 48),
                ], self::pages(13, 13)),
                self::section('c2-speaking', 'Mündliche Prüfung', 15, [
                    self::speakingElement('C2-SP-1a', '1a: Präsentation', 'Präsentation (ca. 4 Minuten). Wählen Sie ein Thema: 1. Die Globalisierung und ihre Auswirkungen auf kulturelle Identität / 2. Wissenschaftlicher Fortschritt und ethische Verantwortung', 6),
                    self::speakingElement('C2-SP-1b', '1b: Zusammenfassung und Reaktion', 'Hören Sie die KI-Präsentation zum anderen Thema und nehmen Sie dazu Stellung (ca. 2 Minuten).', 4),
                    self::speakingElement('C2-SP-2', '2: Diskussion', 'Diskussion (ca. 6 Minuten). Thema: Inwieweit sollte die Meinungsfreiheit in demokratischen Gesellschaften eingeschränkt werden dürfen?', 6),
                    self::speakingElement('C2-SP-GLOBAL', 'Punkte für sprachliche Angemessenheit', 'Gesamtbewertung: Flüssigkeit, Idiomatik, grammatische Korrektheit, Aussprache/Intonation', 32),
                ], self::pages(14, 15)),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function b2(): array
    {
        return [
            'title' => 'telc Deutsch B2 - Modellsatz #1',
            'level' => 'B2',
            'source_pdf' => 'telc_deutsch_b2_uebungstest_1.pdf',
            'theme' => self::theme('#d8232a', '#202020', 'Instrument Sans'),
            'timings' => [
                ['label' => 'Leseverstehen und Sprachbausteine', 'minutes' => 90],
                ['label' => 'Hörverstehen', 'minutes' => 30],
                ['label' => 'Schriftlicher Ausdruck', 'minutes' => 30],
                ['label' => 'Vorbereitungszeit Mündliche Prüfung', 'minutes' => 20],
                ['label' => 'Mündlicher Ausdruck', 'minutes' => 16],
            ],
            'sections' => [
                self::section('b2-reading-language', 'Leseverstehen und Sprachbausteine', 90, [
                    self::text('B2-LV1-text', 'Leseverstehen Teil 1',
                        "Überschriften:\n".
                        "a  Am Strand im Dienst - mehr Sicherheit für Urlauber\n".
                        "b  Bäder, Seen und Natur - im hessischen Paradies\n".
                        "c  Freiheit und Natur - nach sechs Wochen harter Arbeit\n".
                        "d  Jugendliche arbeiten für Jugendliche\n".
                        "e  Kinderarbeit in Deutschland: Jugendliche werden zur Arbeit gezwungen\n".
                        "f  Nach harter Arbeit durch nordische Gewässer\n".
                        "g  Schaden an Kreuzfahrtschiff verhindert Weiterfahrt\n".
                        "h  Urlaub an deutschen Seen immer gefährlicher\n".
                        "i  Wegen Niedrigwasser: vom Fluss auf die Straße\n".
                        "j  Zu Gast bei den Fürsten\n\n".
                        "--------------------------------------------------\n\n".
                        "1\n".
                        "Entdecken Sie interessante Städte und Regionen. Im Herzen Deutschlands liegen wunderbare Landschaften, mit einem für deutsche Verhältnisse sehr milden Klima - und keine typischen \"Touristenziele\". Von der Stadt Gießen ausgehend kann man in den hessischen Kreisen Bergstraße und Waldeck-Frankenberg viele Orte entdecken, die noch ein Geheimtipp sind.\n".
                        "Vor allem gilt dies für den Kreis Waldeck-Frankenberg. Wer nicht gerade in Hessen wohnt, wird kaum wissen, wo diese Region eigentlich liegt. Es ist ein herrliches Stück Deutschland ohne besonders große Städte, eine Gegend, die Natur pur bietet. Daher wundert es nicht, dass man hier einige Kurorte findet wie Bad Arolsen oder Bad Wildungen oder den Luftkurort Edertal-Kleinern. Apropos Edertal: Der zwölf Quadratkilometer große Edersee gehört zu den vier schönen \"blauen Augen\" des Kreises.\n".
                        "Der Landkreis Waldeck-Frankenberg ist Hessens attraktivstes Umland. In der Region der Berge und Seen spürt man auch heute noch einen Hauch von Fürstlichkeit: Majestätisch erhebt sich über dem Edersee das Schloss Waldeck. Auch in Bad Arolsen, einer ehemaligen Residenzstadt, ist vieles noch vom früheren Adel geprägt. Unbedingt besuchen sollte man darüber hinaus das über 1000 Jahre alte Korbach wie auch die Fachwerkstadt Frankenberg.\n\n".
                        "2\n".
                        "Pferde waren schon immer Melanie Schilles Leidenschaft. \"Und jetzt kann ich Hobby und Beruf miteinander verbinden\", freut sich die Beamtin aus Hannover. In diesem Jahr verstärkt sie die Strandwache an der Nordseeküste. Ihr Arbeitsplatz ist der Strand: Mit \"Magnus\", einem 11-jährigen Pferd, patrouilliert sie dort, wo die Kleinen Sandburgen bauen, Urlauber bei einem Buch entspannen oder sich in die kühlen Fluten stürzen. Melanie Schille und ihr Hannoveraner sind eine Attraktion in dem Ferienort. Immer wieder wollen Gäste das Tier streicheln, von der Polizistin wissen, was sie hier macht. \"Wir sorgen für mehr Sicherheit am Strand\", erklären Melanie Schille und Rüdiger Teichmann (42). Sie suchen im watt nach Vermissten, klären über Gefahren auf, verhindern Diebstähle und Sachbeschädigungen. Nachweislich gingen die Delikte zurück, seit es die Streife hoch zu Ross gibt. Die Polizisten: \"Wir sind in dem unwegsamen Gelände oft schneller am Einsatzort als die Kollegen per Fahrrad oder mit dem Auto. Außerdem schonen wir die Natur.\"\n".
                        "Für sich persönlich sieht Melanie Schille noch einen großen Vorteil: \"Es ist schön, mal keine Demonstration sichern zu müssen, stattdessen genieße ich die frische Luft mit fröhlichen Urlaubern.\" Nur eins vermisst die 22-Jährige, die mit Polizeipferd \"Magnus\" auf einem Bauernhof Quartier bezogen hat, während ihres sechswöchigen Einsatzes: Freund Robert (23). Er fährt als Polizist in Hannover Streife - und wartet auf sie.\n\n".
                        "3\n".
                        "Ein neuer Urlaubstrend setzt sich durch: Statt faul am Strand zu liegen, wird man aktiv. Ein besonders beliebtes Ziel ist Schweden: So kann man am Ufer des Flusses Klarälven in der Provinz Värmland selbst ein Floß bauen. Das dauert drei bis sechs Stunden, anschließend macht man darauf Urlaub. \"Das ist Abenteuerurlaub pur\", schwärmt Johan Bengtson, der mit seiner Frau und den drei Kindern zum zweiten Mal Floßferien macht. \"Sich auf dem Fluss treiben lassen und in der Wildnis leben - dieses Gefühl ist super!\" Seit zehn Jahren veranstaltet Marie Junler von der Agentur Vildmark i Värmland die Holzfloßtrips.\n".
                        "Wir haben die Bengtsons an ihrem ersten Urlaubstag begleitet. Mit dabei ist auch Veranstalterin Marie Junler, die der Familie hilft, das Floß zu bauen. Es ist ein herrlicher Sonntag. In einer sanften Kurve des Flusses Klarälven steht Marie bis zu den Hüften im tiefblauen Wasser. Mit dicken grünen Seilen schnürt sie Holzstämme zusammen und gibt Kommandos: \"Einer hält den Stamm, der andere knotet - den Seemannsknoten, wie wir ihn vorhin an Land geübt haben.\" Ohne einen Nagel werden 96 Baumstämme verzurrt - im Wasser, sonst wäre das Holz zu schwer. Mindestens zwei Erwachsene sind nötig, um ein Floß zu bauen, aber allein packt das nicht. Nach drei Stunden ist es geschafft: Das Urlaubsparadies der Bengtsons - es misst übrigens 6 mal 3 Meter und wiegt 2 Tonnen - treibt am Ufer. Noch schnell das Sonnenzelt befestigen, darunter Vorratskasten, Frischwassertank, Chemie-Klo, Küchenausrüstung, Zelt, Rettungsring, Schwimmwesten und das Paddel zum Steuern und Manövrieren verstauen - und ab geht's.\n\n".
                        "4\n".
                        "Von 9 bis 15 Uhr arbeitet Sebastian Keller (18) in einem Altenwohnheim in Hamburg-Altona: Er kümmert sich um die Essensausgabe, putzt die Küche und dann ist noch Zeit, um den Senioren vorzulesen oder mit ihnen Karten zu spielen. Zur selben Zeit putzen Rebecca (12) und Christiane (13) den Eingang des Hamburger \"Michels\", der wohl bekanntesten Kirche der Stadt, und Friderike (17) füttert schon frühmorgens Kühe, Schweine und Hühner auf einem Bio-Bauernhof bei Wedel.\n".
                        "\"Endlich mal ein sinnvoller Job\", sagen die fünf übereinstimmend. Sie stehen stellvertretend für etwa 100.000 Jugendliche, die beim \"Sozialen Tag\" mitgemacht haben. Hut ab! Und was mindestens ebenso beeindruckend ist: Der Verein \"Hamburgs Schüler helfen\" (HSH) wurde von Jugendlichen im Jahr 2004 gegründet - und seitdem findet jedes Jahr im August der \"Soziale Tag\" statt. Mit Behörden und Firmen haben Schüler aus Hamburg Verträge für einen Tag abgeschlossen. Die Schülerinnen und Schüler verdienen dann am \"Sozialen Tag\" zwischen 6 und 8 Euro pro Stunde - aber nicht für sich selbst, sondern für andere. Denn der Verdienst wird gespendet. Die Jugendlichen wählen selbst ein Projekt aus, an das sie die Gelder spenden wollen. Einzige Bedingung: Es muss ein Projekt sein, von dem Jugendliche profitieren. Im letzten Jahr zum Beispiel wurde die Gesamtsumme von 1,2 Millionen Euro an das Projekt \"Frieden für alle\" gespendet. Dessen Ziel ist es, Jugendliche in Kriegs- und Krisenregionen zu unterstützen, den Dialog unter Jugendlichen aus verschiedenen Ländern zu fördern und auch das Kennenlernen anderer Kulturen zu ermöglichen. Für Ralf Waldner (20) vom HSH steht fest: \"Wir können und werden anderen auch in Zukunft helfen, das Engagement der Schülerinnen und Schüler in Hamburg ist in den letzten Jahren schließlich immer weiter gestiegen.\"\n\n".
                        "5\n".
                        'Die Windjacken waren schon eingepackt, die Koffer geschlossen. Thomas Meurer (64) und Wiebke Fuchs (62) aus Hannover freuten sich auf ihre Flusskreuzfahrt mit der "MS Eurostar" von Potsdam nach Prag. Stattliche 2500 Euro kostete die Reise pro Person, und beide hatten lange gespart, um sich das leisten zu können. Doch aus der Kreuzfahrt wurde eine Bustour. Meurer berichtet, was er erlebt hat: "Wir waren am Abend auf das Schiff gegangen und hatten unsere Kabinen bezogen. Am nächsten Morgen ging es los. Aber schon bald machte das Schiff wieder fest und alle Gäste mussten von Bord." Wiebke Fuchs ergänzt: "Der Fluss hatte einfach zu wenig Wasser, da konnten wir mit dem großen Kreuzfahrtschiff nicht weiterfahren!" Per Bus ging es nach Prag. Beide wollen nun einen Teil des Reisepreises zurück, aber der Veranstalter Hapag-Lloyd wehrt ab: "Das war höhere Gewalt, da kann man nichts machen."',
                        'Lesen Sie zuerst die zehn Überschriften. Lesen Sie dann die fünf Texte und entscheiden Sie, welche Überschrift (a–j) am besten zu welchem Text (1–5) passt. Tragen Sie Ihre Lösungen in den Antwortbogen bei den Aufgaben 1–5 ein.'
                    ),
                    ...self::answerElements('B2-LV1-', 1, ['b', 'a', 'f', 'd', 'i'], 'matching', 2, 'a,b,c,d,e,f,g,h,i,j'),
                    self::text('B2-LV2-text', 'Leseverstehen Teil 2',
                        "Freizeitbegriff\n\n".
                        "Das Freizeitverständnis hat sich grundlegend gewandelt. Quantitativ und qualitativ unterscheidet sich die Freizeit heute von früheren Freizeitformen. Auch gegenwärtig findet Erholung von der Arbeit in der Freizeit statt, aber diese Freizeit ist nicht mehr nur - wie in den fünfziger Jahren - Erholungszeit. Für die überwiegende Mehrheit der Bevölkerung hat Freizeit einen eigenständigen Wert bekommen. So vertreten 70% der Bevölkerung die Auffassung, dass Freizeit in erster Linie eine Zeit ist, in der man tun und lassen kann, was einem Spaß macht. Aus einem arbeitsabhängigen Zeitbegriff, der Freizeit negativ als Abwesenheit von Arbeit definierte, hat sich heute ein positives Freizeitverständnis entwickelt: Freizeit ist eine Zeit, in der man frei ist.\n".
                        "Über vierzig Jahre Arbeitszeitverkürzungen sind an den Menschen und ihrer Einstellung zum arbeitsfreien Teil des Lebens nicht spurlos vorübergegangen. Freizeit ist mehr als eine Pause, in der man sich für den nächsten Arbeitstag wieder erholt. Freizeit koppelt sich von der Arbeit ab: Nur für eine Minderheit der Bevölkerung ist Freizeit eine Zeit, die nicht mit Arbeit und Geldverdienen ausgefüllt ist (26%). Dies gilt für Arbeiter (38%) genauso wie für Angestellte (34%) oder Selbständige (30%). Bei Freizeit denken die meisten erst einmal an den eigenen Spaß. Freizeit ist daher mehr ein positives Lebensgefühl als eine arbeitsabhängige Zeitkategorie. Mit dem Wandel der Menschen sind \"arbeitsfreie Zeit\" und \"Freizeit\" nicht mehr dasselbe. Mit dem Wandel des subjektiven Freizeitverständisses ist auch eine gesellschaftliche Neubewertung der Freizeit notwendiger denn je.\n".
                        "Vor dem Hintergrund des wachsenden Anteils Nichterwerbstätiger lässt sich für die Zukunft unschwer prognostizieren: Freizeit verliert zunehmend ihre Bedeutung als arbeitsfreie Regenerationszeit. Umso mehr richten sich dann die Hoffnungen auf die Freizeit als Synonym für Lebensqualität und Wohlbefinden. Dies aber heißt: sich wohlfühlen, das tun und lassen können, was Spaß und Freude macht, und das Leben in eigener Regie gestalten sowie viel mit Familie und Freunden unternehmen.\n\n".
                        "==================================================\n\n".
                        "Freizeitrituale\n\n".
                        "Alles hat seine Regeln und Rituale, auch die Freizeit. Die Deutschen haben ihre Freizeit bestens organisiert: samstags auf die Piste und sonntags mit der Familie. Fast jeder dritte Bundesbürger reserviert regelmäßig einen Wochentag für Familie oder Ausgehen, für Sport oder Faulenzen. Mehr als 20% kennen ebenfalls einen festen Termin für Hobby, Verein oder Besuche. Und selbst der Badetag ist für jeden vierten Deutschen nach wie vor ein Begriff.\n".
                        "Trotz kürzerer Arbeitszeiten konzentrieren sich die meisten Freizeittermine auf das lange Wochenende. Ausgehen und Besuche machen stehen ebenso auf dem Programm wie faulenzen, lesen oder sich dem Hobby widmen. So hat der Samstag für jeden dritten Bundesbürger seinen festen Platz als Hauptausgehtag. Den sonntäglichen Terminkalender bestimmen dann bei der Mehrheit der Befragten die beiden \"F\": Faulenzen und Familie. Für viele wird damit der Sonntag zum Balanceakt zwischen eigener und gemeinsamer Freizeitgestaltung. Denn auch die Zweisamkeit darf nicht zu kurz kommen. Für sechs Prozent der Deutschen ist der siebte Tag der Woche ein \"Schmusetag\".\n".
                        "Freizeitrituale, bisher kaum erforscht, hatten im Alltagsleben der Bevölkerung schon immer ihren festen Platz. Was als liebe Gewohnheit oder Alltagsroutine beginnt, kann zur eingeübten Handlung und starren Ordnung werden: von den Begrüßungs-, Wasch- und Umziehritualen nach Feierabend bis zum gemeinsamen Frühstück und Familienspaziergang am Sonntag. Solche eingespielten und liebgewordenen Gewohnheiten geben den Menschen im Alltag Struktur und Halt. Gerade im Zusammenleben von Familienmitgliedern haben Rituale eine wichtige Stabilisierungsfunktion. Dahinter verbirgt sich das Bedürfnis nach Sicherheit und Geborgenheit, der Wunsch nach einem harmonischen Feierabend oder schönen Wochenende.\n\n".
                        "==================================================\n\n".
                        "Aufgaben 6–10\n\n".
                        "6  Siebzig Prozent der Bevölkerung meinen, dass Freizeit\n".
                        "   a  nach den eigenen Vorlieben gestaltet werden soll.\n".
                        "   b  nicht unbedingt positiv besetzt ist.\n".
                        "   c  nur dem Ausruhen und Schlafen dienen sollte.\n\n".
                        "7  Die Mehrheit der Leute nutzt ihre Freizeit\n".
                        "   a  für die eigenen Interessen.\n".
                        "   b  zur Aufbesserung des Einkommens.\n".
                        "   c  zur Regeneration für den nächsten Arbeitstag.\n\n".
                        "8  Die Deutschen\n".
                        "   a  gehen nur an Wochenenden ihren Hobbys nach.\n".
                        "   b  organisieren ihre Freizeit gar nicht.\n".
                        "   c  organisieren ihre Freizeit meistens gut und reservieren für ihre Aktivitäten bestimmte Wochentage.\n\n".
                        "9  Der Sonntag ist bei vielen reserviert für\n".
                        "   a  das Ausgehen.\n".
                        "   b  die Freizeitgestaltung mit Freunden oder Kollegen.\n".
                        "   c  Familie und Entspannung.\n\n".
                        "10  Freizeitrituale\n".
                        "   a  schwächen den Gruppenzusammenhalt.\n".
                        "   b  sind für die moderne Familie nicht mehr von Bedeutung.\n".
                        '   c  waren bislang noch nicht oft Gegenstand wissenschaftlicher Untersuchungen.',
                        'Lesen Sie zuerst die beiden Artikel und lösen Sie dann die Aufgaben 6–10 zu den Texten. Entscheiden Sie, welche Lösung (a, b oder c) richtig ist, und tragen Sie Ihre Lösung in den Antwortbogen bei den Aufgaben 6–10 ein.'
                    ),
                    ...self::answerElements('B2-LV2-', 6, ['a', 'a', 'c', 'c', 'c'], 'multiple_choice', 2, 'a,b,c'),
                    self::text('B2-LV3-text', 'Leseverstehen Teil 3',
                        "Situationen:\n".
                        "11  Ein Bekannter möchte Schweden per Schiff kennenlernen.\n".
                        "12  Ein Freund möchte sich im Inline-Skaten perfektionieren.\n".
                        "13  Ein Kollege möchte sich über Gesundheitsrisiken in Ägypten informieren.\n".
                        "14  Eine Bekannte möchte einen Kurs über Naturkosmetik besuchen.\n".
                        "15  Eine 17-jährige Freundin würde gerne armen Menschen in anderen Ländern helfen.\n".
                        "16  Ihr Nachbar möchte sich im Sommerurlaub sportlich betätigen.\n".
                        "17  Ihre Freundin möchte gerne bei der Organisation einer Inline-Skate-Veranstaltung mitwirken.\n".
                        "18  Sie möchten das Inline-Skaten erlernen und suchen Informationen.\n".
                        "19  Sie möchten herausfinden, wo es in Deutschland Skate-Veranstaltungen gibt.\n".
                        "20  Sie müssen kurzfristig für Ihren Chef Reisepapiere für Ägypten besorgen.\n\n".
                        "--------------------------------------------------\n\n".
                        "Info-Texte:\n\n".
                        "a\n".
                        "Auf dem Göta-Kanal sind drei historische Schiffe unterwegs: „Juno“, „Wilhelm Tham“ und „Diana“. Die um die Jahrhundertwende gebauten Dampfer sind restauriert und umgerüstet.\n".
                        "Unterkunft an Bord: in Doppelkabinen mit Etagenbetten, Dusche und WC.\n".
                        "Reiseverlauf: Reiseantritt in Stockholm oder Göteborg. Aktivitäten: tägliche Ausflüge zu verschiedenen Sehenswürdigkeiten, Spaziergänge auf dem Treidelweg, baden, lesen, Karten spielen, die Stimmung an Deck genießen.\n".
                        "Reisetermine: von Mai bis September.\n".
                        "Wer in Göteborg oder Stockholm bleiben will, kann weitere Übernachtungen buchen.\n".
                        "Weitere Infos: Schweden-Werbung, Lilienstr. 19, D-20095 Hamburg, Tel. 040 / 32 55 13 55. Web: www.gotacanal.se (Reederei).\n\n".
                        "b\n".
                        "Skating für Fortgeschrittene\n".
                        "Können Sie bremsen? Oder nur wenn ein Verkehrsschild oder eine Ampel in der Nähe ist? Vielleicht ist ein Wochenend-Kurs beim Starnberger Rollschuh-Club genau das Richtige für Sie.\n".
                        "Trainer vermitteln effektives Gleiten, Bremsen, Kurvenfahren, Fallen – alles für fortgeschrittene Skater, die ihre Technik verbessern wollen.\n".
                        "Dazu: neueste Skate-Modelle zum Üben: Fr 17 Uhr bis So 19 Uhr, Ü/F, ab 160 Euro, Infos: 0800 / 100 88 00 (Ortstarif).\n\n".
                        "c\n".
                        "Skater gelten verkehrsrechtlich als Fußgänger und dürfen eigentlich nur auf Gehwegen oder in verkehrsberuhigten Zonen fahren. Gar nicht einfach, eine schöne, freie Piste auf glattem Asphalt zu finden! In unseren Reiseführern für Skater stellen wir ausgewählte Strecken für Touren vor: in verschiedenen Großstädten und Regionen in Deutschland, von der kleinen Abend- bis zur großen Tagestour. (Klartext Verlag Essen, ab ca. 7 Euro im Buchhandel).\n".
                        "Wichtige Infos zu Skate-Hallen und -Bahnen, Veranstaltungen, zu regelmäßigen Inline-Treffpunkten von Flensburg bis Passau enthält der „ActionGuide Inline-Skating“ (BLV Verlag, 14,80 Euro).\n\n".
                        "d\n".
                        "LAUFEN FÜR DIE FORSCHUNG\n".
                        "Straßenlauf in Frankfurt am Main: Alle Teilnehmer haben ihren Sponsor; das Geld stiften sie für Aufklärung und Forschung zum Thema Brustkrebs. Gestartet wird am 13. August um zehn Uhr morgens am Frankfurter Römer. Die Idee für diese Aktion stammt aus den USA: Dort organisiert die „Breast Cancer Foundation“ seit fast 20 Jahren solche gesponserten Läufe. Wer mitlaufen und/oder sponsern will, kann sich per E-Mail anmelden.\n".
                        "Info: Race for the Cure\n".
                        "E-Mail: raceforthecure@arcormail.de\n".
                        "www.raceforthecure.de\n\n".
                        "e\n".
                        "Russland, China, Kamerun, Venezuela – wer binnen weniger Tage in ein Land jenseits der EU-Grenzen aufbrechen muss, verliert eine Menge Zeit bei der Beschaffung von Einreisepapieren. Inzwischen holen Agenturen die Visa kurzfristig ein, überbringen sie persönlich oder hinterlegen sie am Flughafen. So geht’s: Sie lassen sich vom Visumservice beraten, füllen einen Antrag aus, die Unterlagen werden überprüft und beim zuständigen Konsulat eingereicht.\n".
                        "Kosten für ein Visum innerhalb von fünf Arbeitstagen: 85 Euro; 24-Stunden-Service: 140 Euro.\n".
                        "Infos: Tel. 069/69 07 22 32, www.visumservice.de\n\n".
                        "f\n".
                        "MÖWE E. V. – Kinder- und Jugendfreizeiten\n".
                        "Wir bieten: Campen, Kanufahren, Wandern, Klettern, Geländespiele usw. • Für Kinder und Jugendliche zwischen 7 und 18 Jahren. Alle Aktivitäten werden von erfahrenen Pädagogen betreut und begleitet! Elternbegleitung möglich. • Integrativer Ansatz: auch für Kinder und Jugendliche mit erhöhtem Betreuungsbedarf. • Jetzt neu: Auslandsreisen zu Hilfsprojekten in anderen Ländern (mit sozialer Tätigkeit, z. B. Hilfe in Schulen o. Ä.) – Reisen, Lernen, Gutes tun! Für Jugendliche ab 15 Jahren.\n".
                        "Weitere Infos: Möwe e. V., Esslinger str. 7, D-72649 Wolfschlugen. Telefon: 0800 / 5959219 E-Mail: Petra.Schrei@moewe.eu\n\n".
                        "g\n".
                        "Sie wollen bald zu Ihrer ersten Inliner-Tour starten? Anfänger sollten die wichtigsten Techniken (beim Gleiten, Bremsen und Fallen) vorher üben und Körperhaltung und Balance trainieren. Infos zu Kursen gibt es beim Deutschen Inline-Skate-Verband. Auch Sportartikelfirmen bieten Kurse an, z. B. das mobile „K2 Skate College“ in ca. 100 deutschen Städten (Tel. 0190 / 57 55 55; 2 Stunden 25 Euro).\n".
                        "In kleinen Gruppen lernen Sie die richtige Technik beim Gleiten, Bremsen und Fallen, trainieren Körperhaltung und Balance. Skate-Unterricht für Einsteiger gibt es auch per Video (z. B. GoInline!; ca. 15 Euro, in Buch- und Sportgeschäften) oder als Buch (z. B. Richtig Inline-Skaten, BLV Verlag, 9,90 Euro).\n\n".
                        "h\n".
                        "MAINZ. Für die Skate Nights vom 5. bis 9. August sucht die Stadt Mainz Helfer. Engagierte Skater werden benötigt, die Skating-Regeln erklären oder bei der Verkehrsregelung helfen. An allen Tagen dürfen Skater von 20.30 bis 22.30 Uhr durch die Innenstadt fahren – auf einem Rundkurs über Hauptstraßen und den Markt.\n".
                        "Anmeldung für die Helfer und Inline-Skater beim Sportamt der Stadt Mainz, Tel.: 06131-12 43 21.\n\n".
                        "i\n".
                        "Bäche, Seen, Wälder und Wiesen\n".
                        "Für Feriengenießer haben wir viel zu bieten: herrliche Natur, ein bisschen Luxus, komfortable Zimmer und Suiten, gemütliche Restaurants mit dem Besten aus Küche und Keller. Und dazu ein großes Angebot für alle, die im Urlaub aktiv werden wollen: geführte Wanderungen im Tannheimer Tal, Radeln, Mountainbiken, Surfen, Bootsfahrten und Baden am klaren Haldensee, Tennisspielen und dann Relaxen im Wellness-Bereich – Entspannung pur! Schöner kann Urlaub nicht sein!\n".
                        "Hotel Waldruhe, A-6673 Haldensee, Bergweg 12, Tel.: 0043-56 75 31 62; E-Mail: hoteltyrol@netway.at\n\n".
                        "j\n".
                        "Reisetipps\n".
                        "Wichtige Hinweise für Ihre Reise nach Ägypten. Sicherheit: Die Hauptattraktionen des Landes werden bewacht. Verhalten Sie sich respektvoll dem kulturellen Erbe und den Einheimischen gegenüber. Passen Sie Ihre Kleidung wie bei jeder Reise den Gepflogenheiten des Landes an. Gesundheit: Wasser, auch zum Zähneputzen, sollten Sie nur aus verschlossenen Flaschen benutzen. Welche Impfung nötig ist, erfahren Sie am Impftelefon: 0 40 / 42 81 88 00. Beste Reisezeit: Oktober bis April. Touren: Alle großen deutschen Veranstalter bieten auch Rundreisen sowie Kreuzfahrten auf dem Nil an. Weitere Informationen finden Sie im Internet unter www.reise-tipps.eu.\n\n".
                        "k\n".
                        "LAGO DI CHIUSI: EINFACH HERRLICH\n".
                        "Früher schnitt man hier das Rohr für die Florentinerhüte, heute ist der winzige, von grünen Wiesen umgebene See an der Grenze zu Latium ein Geheimtipp für Liebhaber der Gemächlichkeit. Zeitloses Italien: ein windschiefer Holzsteg, morsche bunte Boote, ein paar Angler, ein paar Enten auf sattem Grün und der würzige Geruch von Seefischen, die „alla etrusca“ über Schilf gebraten werden. Zum Beispiel bei Gino: Filets von Forelle, Felchen und Hecht, ein paar Spritzer Olivenöl und Zitronensaft. Buon appetito!\n\n".
                        "l\n".
                        "KRÄUTERCREME UND ÖKO-SEIFE\n".
                        'Viele Beauty-Produkte schmücken sich mit dem Begriff „Naturkosmetik“. Bisher gab es dafür keine festen Prüfkriterien, doch jetzt kommt das Gütezeichen „Kontrollierte Natur-Kosmetik BDHI“, das vom Bundesverband Deutscher Industrie- und Handelsunternehmen (BDHI) vergeben wird. Damit können Sie sicher sein: Wo Natur draufsteht, ist auch Natur drin. Infos gibt’s beim BDHI unter: Telefon 06 21/1 29 43 30.',
                        'Lesen Sie zuerst die zehn Situationen (11–20) und dann die zwölf Info-Texte (a–l). Welcher Info-Text passt zu welcher Situation? Sie können jeden Info-Text nur einmal verwenden. Markieren Sie Ihre Lösungen auf dem Antwortbogen bei den Aufgaben 11–20. Manchmal gibt es keine Lösung. Markieren Sie dann x.'
                    ),
                    ...self::answerElements('B2-LV3-', 11, ['a', 'b', 'j', 'x', 'f', 'i', 'h', 'g', 'c', 'e'], 'matching', 2, 'a,b,c,d,e,f,g,h,i,j,k,l,x'),
                    self::inlineGap('B2-SB-inline', 'Sprachbausteine Teil 1', "Liebe Daniela,\nich habe schon ein ganz schlechtes Gewissen, denn --0-- wollte ich dir schon vor zwei Monaten schreiben. Aber du weißt ja, wie das ist: Wenn man sich auf eine Prüfung vorbereitet, hat --1-- überhaupt keine Zeit mehr für seine Hobbys – alles dreht sich nur noch ums Lernen.\nNun habe ich es aber geschafft: Gestern war die Prüfung und ich bin zuversichtlich, dass ich sie bestanden habe. Mein Freund, mit --2-- Hilfe es mir überhaupt nur möglich war, diese ganze Zeit zu --3--, hat mich für heute Abend in ein tolles Restaurant eingeladen. Danach gehen wir auch noch tanzen.\nIn deinem letzten Brief hast du mich gefragt, --4-- ich Lust hätte, mit dir zusammen ein Wochenende in London zu verbringen. Natürlich habe ich Lust! Nach dem ganzen Stress der letzten Wochen fände ich es super, mal ein paar Tage lang mit einer Freundin etwas Tolles zu --5--. London ist eine wunderbare Stadt, ich habe schon viele Berichte darüber gelesen. Ich würde mich --6-- besonders --7-- die Tate Gallery und das Filmmuseum interessieren.\nMach --8-- einfach ein paar Vorschläge, wann du Zeit hast. Ich bin sicher, dass wir --9-- auf ein Wochenende einigen können. In der Zwischenzeit drucke ich schon mal ein paar Angebote aus dem Internet aus: preiswerte Flüge, ein schönes und günstiges Hotel für uns usw. Ich freue mich schon auf deinen nächsten Brief.\nHerzliche Grüße\nAndrea", [
                        ['options' => 'a|außerdem,b|eigentlich,c|überhaupt', 'answer' => 'b'],
                        ['options' => 'a|er,b|es,c|man', 'answer' => 'c'],
                        ['options' => 'a|der,b|dessen,c|seiner', 'answer' => 'b'],
                        ['options' => 'a|übersetzen,b|überstehen,c|übertragen', 'answer' => 'b'],
                        ['options' => 'a|dass,b|falls,c|ob', 'answer' => 'c'],
                        ['options' => 'a|unternehmen,b|verbringen,c|verplanen', 'answer' => 'a'],
                        ['options' => 'a|ganz,b|recht,c|zwar', 'answer' => 'a'],
                        ['options' => 'a|auf,b|für,c|in', 'answer' => 'b'],
                        ['options' => 'a|bestimmt,b|doch,c|sicher', 'answer' => 'b'],
                        ['options' => 'a|euch,b|sich,c|uns', 'answer' => 'c'],
                    ], 1, 'Lesen Sie den folgenden Text und entscheiden Sie, welches Wort (a, b oder c) in die jeweilige Lücke passt. Markieren Sie Ihre Lösungen auf dem Antwortbogen bei den Aufgaben 21–30.'),
                ], self::pages(6, 12)),
                self::section('b2-listening', 'Hörverstehen', 30, [
                    self::audio('B2-audio', 'Hörverstehen Audio', ''),
                    self::text('B2-HV1-text', 'Hörverstehen Teil 1', 'Sie hören acht kurze Texte. Ordnen Sie jeden Text einer Aussage (a–j) zu. Sie hören jeden Text zweimal.', 'Sie hören acht kurze Texte. Ordnen Sie jeden Text einer Aussage (a–j) zu. Sie hören jeden Text zweimal.'),
                    ...self::answerElements('B2-HV1-', 24, ['f', 'h', 'a', 'j', 'c', 'e', 'i', 'b'], 'matching', 1, 'a,b,c,d,e,f,g,h,i,j'),
                    self::text('B2-HV2-text', 'Hörverstehen Teil 2', 'Sie hören ein längeres Gespräch. Wählen Sie die richtige Antwort (a, b oder c). Sie hören den Text zweimal.', 'Sie hören ein längeres Gespräch. Wählen Sie die richtige Antwort (a, b oder c). Sie hören den Text zweimal.'),
                    ...self::answerElements('B2-HV2-', 32, ['c', 'a', 'b', 'c', 'a', 'b', 'c', 'a', 'b', 'c'], 'multiple_choice', 2, 'a,b,c'),
                    self::text('B2-HV3-text', 'Hörverstehen Teil 3', 'Sie hören eine Ankündigung. Ergänzen Sie die fehlenden Informationen in ganzen Worten. Sie hören den Text zweimal.', 'Sie hören eine Ankündigung. Ergänzen Sie die fehlenden Informationen in ganzen Worten. Sie hören den Text zweimal.'),
                    ...self::answerElements('B2-HV3-', 42, ['Vortragssaal', '19.30', 'Professor Dr. Wagner', 'Klimawandel', 'Eintritt frei', 'Anmeldung', 'Handout', 'Diskussion'], 'fill_blank', 2),
                ], self::pages(13, 15)),
                self::section('b2-writing', 'Schriftlicher Ausdruck', 30, [
                    self::input('B2-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', "Sehr geehrte Damen und Herren,\nmit großem Interesse habe ich Ihre Anzeige für den Deutsch-Intensivkurs im Sommer gelesen. Da ich meine Sprachkenntnisse für mein bevorstehendes Studium verbessern möchte, interessiere ich mich sehr für eine Teilnahme.\nKönnten Sie mir bitte weitere Informationen bezüglich der Kursgebühren, der genauen Unterrichtszeiten und der maximalen Teilnehmerzahl zusenden? Gibt es zudem Unterkünfte, die Sie für ausländische Studenten empfehlen können?\nIch bedanke mich im Voraus für Ihre Bemühungen und freue mich auf Ihre Antwort.\n\nMit freundlichen Grüßen\nMaria Lopez", 48),
                ], self::pages(16, 16)),
                self::section('b2-speaking', 'Mündliche Prüfung', 16, [
                    self::speakingElement('B2-SP-1a', '1a: Präsentation', 'Präsentation (ca. 3 Minuten). Wählen Sie ein Thema: 1. Die Bedeutung von Social Media für junge Menschen / 2. Mein Traumberuf und wie ich ihn erreichen will', 6),
                    self::speakingElement('B2-SP-1b', '1b: Zusammenfassung und Reaktion', 'Hören Sie die KI-Präsentation zum anderen Thema und antworten Sie darauf (ca. 2 Minuten).', 4),
                    self::speakingElement('B2-SP-2', '2: Diskussion', 'Diskussion (ca. 6 Minuten). Thema: Sollten Unternehmen Homeoffice für alle Mitarbeiter verpflichtend machen?', 6),
                    self::speakingElement('B2-SP-GLOBAL', 'Punkte für sprachliche Angemessenheit', 'Gesamtbewertung: Flüssigkeit, Wortschatz, Grammatische Richtigkeit, Aussprache/Intonation', 32),
                ], self::pages(17, 18)),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function text(string $id, string $label, string $content, ?string $instruction = null): array
    {
        return [
            'id' => $id,
            'type' => 'text',
            'label' => $label,
            'content' => $content,
            'instruction' => $instruction,
            'answer' => '',
            'points' => 0,
            'options' => '',
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $gaps
     * @return array<string, mixed>
     */
    private static function inlineGap(string $id, string $label, string $content, array $gaps, int $points = 1, ?string $instruction = null): array
    {
        return [
            'id' => $id,
            'type' => 'inline_gap',
            'label' => $label,
            'content' => $content,
            'options' => json_encode($gaps),
            'instruction' => $instruction,
            'answer' => '',
            'points' => $points,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function c1(): array
    {
        return [
            'title' => 'telc Deutsch C1',
            'level' => 'C1',
            'source_pdf' => 'telc_deutsch_c1_uebungstest_1_format_2016.pdf',
            'theme' => self::theme('#8f8f8f', '#202020', 'Instrument Sans'),
            'timings' => [
                ['label' => 'Leseverstehen und Sprachbausteine', 'minutes' => 90],
                ['label' => 'Pause', 'minutes' => 20],
                ['label' => 'Hoerverstehen', 'minutes' => 40],
                ['label' => 'Schriftlicher Ausdruck', 'minutes' => 70],
                ['label' => 'Vorbereitungszeit Muendliche Pruefung', 'minutes' => 20],
                ['label' => 'Muendlicher Ausdruck', 'minutes' => 16],
            ],
            'sections' => [
                self::section('c1-reading-language', 'Leseverstehen und Sprachbausteine', 90, [
                    ...self::answerElements('C1-LV1-', 1, ['c', 'e', 'f', 'g', 'd', 'b'], 'matching', 2, 'a,b,c,d,e,f,g,h'),
                    ...self::answerElements('C1-LV2-', 7, ['d', 'c', 'b', 'c', 'e', 'a'], 'matching', 2, 'a,b,c,d,e'),
                    ...self::answerElements('C1-LV3-', 13, ['-', 'x', '-', '-', 'x', '+', 'x', '+', '+', '+', '-'], 'true_false', 2, '+,-,x'),
                    self::input('C1-LV3-24', 'Aufgabe 24', 'multiple_choice', 'c', 2, 'a,b,c'),
                    ...self::answerElements('C1-SB-', 25, ['b', 'd', 'd', 'a', 'b', 'd', 'd', 'c', 'd', 'd', 'b', 'b', 'd', 'c', 'd', 'd', 'b', 'a', 'b', 'b', 'd', 'a'], 'multiple_choice', 1, 'a,b,c,d'),
                ], self::pages(8, 16)),
                self::section('c1-listening', 'Hoerverstehen', 40, [
                    self::audio('c1-audio', 'Hoerverstehen Audio', '/telc-assets/telc_deutsch_c1_uebungstest_1_format_2016.mp3'),
                    ...self::answerElements('C1-HV1-', 47, ['h', 'c', 'e', 'f', 'g', 'j', 'b', 'd'], 'matching', 1, 'a,b,c,d,e,f,g,h,i,j'),
                    ...self::answerElements('C1-HV2-', 55, ['a', 'a', 'c', 'b', 'a', 'b', 'a', 'c', 'b', 'a'], 'multiple_choice', 2, 'a,b,c'),
                    ...self::answerElements('C1-HV3-', 65, ['fließenden Übergang', 'mehr als 9 Millionen', 'Emotionen nicht an', 'Off-White', 'Dimensionen', 'kräftigen Farben', 'Kontrast', 'kurz aufhalten', 'Beleuchtungen', 'steril'], 'fill_blank', 2),
                ], self::pages(17, 19)),
                self::section('c1-writing', 'Schriftlicher Ausdruck', 70, [
                    self::input('C1-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', "Stellungnahme zum Thema: Soll man Geld spenden?\n\nDie Frage, ob private Geldspenden eine sinnvolle Möglichkeit zur Hilfeleistung darstellen oder ob Gelder primär in bürokratischen Strukturen versickern, wird kontrovers diskutiert. \nEinerseits ermöglichen Spenden die Finanzierung von Hilfsprojekten weltweit, die ohne private Mittel nicht existieren könnten. Insbesondere bei Naturkatastrophen ist schnelle finanzielle Hilfe überlebenswichtig. \nAndererseits ist die Kritik an den hohen Verwaltungskosten großer Organisationen nicht unberechtigt. Dennoch überwiegt der Nutzen, da seriöse Organisationen durch Transparenzberichte nachweisen, dass der Großteil der Gelder die Betroffenen erreicht. \nZusammenfassend lässt sich sagen, dass gezieltes und informiertes Spenden an zertifizierte Organisationen einen unverzichtbaren Beitrag zur globalen Solidarität leistet.", 48),
                ], self::pages(20, 20)),
                self::section('c1-speaking', 'Mündliche Prüfung', 16, [
                    self::speakingElement('C1-SP-1a', '1a: Präsentation', 'Präsentation (ca. 3 Minuten). Wählen Sie eines der Themen: 1. Was ist der Nationalsport in Ihrem Land und welche Bedeutung hat er für die Gesellschaft? oder 2. Wer gehört Ihrer Meinung nach zu den einflussreichsten lebenden Personen weltweit?', 6),
                    self::speakingElement('C1-SP-1b', '1b: Zusammenfassung und Anschlussfragen', 'Zusammenfassung/Anschlussfragen (ca. 2 Minuten). Hören Sie sich die KI-Präsentation zum anderen Thema an und antworten Sie darauf.', 4),
                    self::speakingElement('C1-SP-2', '2: Diskussion', 'Diskussion (6 Minuten). Diskutieren Sie das folgende Thema: Beziehungen mit großem Altersunterschied sind immer problematisch.', 6),
                    self::speakingElement('C1-SP-GLOBAL', 'Punkte für sprachliche Angemessenheit', 'Gesamtbewertung für sprachliche Angemessenheit (Flüssigkeit, Repertoire, Grammatische Richtigkeit, Aussprache/Intonation)', 32),
                ], self::pages(21, 22)),
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function theme(string $accent, string $ink, string $font): array
    {
        return [
            'accent' => $accent,
            'ink' => $ink,
            'paper' => '#ffffff',
            'muted' => '#6b7280',
            'font' => $font,
        ];
    }

    /**
     * Paper pages are rendered images from the PDF. The answer elements below are
     * only the scan-sheet fields used for automated marking.
     *
     * @param  array<int, array<string, mixed>>  $elements
     * @param  array<int, string>  $paperPages
     * @return array<string, mixed>
     */
    private static function section(string $id, string $title, int $minutes, array $elements, array $paperPages): array
    {
        return [
            'id' => $id,
            'title' => $title,
            'minutes' => $minutes,
            'elements' => $elements,
            'paper_pages' => $paperPages,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function audio(string $id, string $label, string $source): array
    {
        return ['id' => $id, 'type' => 'audio', 'label' => $label, 'content' => $source, 'answer' => '', 'points' => 0, 'options' => ''];
    }

    /**
     * @return array<string, mixed>
     */
    private static function input(string $id, string $label, string $type, string $answer, int $points = 1, ?string $options = null, ?string $instruction = null): array
    {
        $options ??= match ($type) {
            'multiple_choice' => 'a,b,c,d',
            'true_false' => '+,-',
            default => '',
        };

        return compact('id', 'label', 'type', 'answer', 'points', 'options') + ['content' => '', 'instruction' => $instruction];
    }

    /**
     * @param  array<int, string>  $answers
     * @return array<int, array<string, mixed>>
     */
    private static function answerElements(string $prefix, int $start, array $answers, string $type, int $points = 1, ?string $options = null): array
    {
        return array_map(
            fn (string $answer, int $offset): array => self::input($prefix.($start + $offset), 'Aufgabe '.($start + $offset), $type, $answer, $points, $options),
            $answers,
            array_keys($answers),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private static function speakingElement(string $id, string $label, string $prompt, int $points = 25, ?string $instruction = null): array
    {
        return [
            'id' => $id,
            'type' => 'speaking',
            'label' => $label,
            'content' => $prompt,
            'options' => '',
            'answer' => '',
            'points' => $points,
            'instruction' => $instruction,
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function pages(int $first, int $last): array
    {
        return array_map(
            fn (int $page): string => sprintf('/telc/c1/page-%02d.png', $page),
            range($first, $last),
        );
    }
}
