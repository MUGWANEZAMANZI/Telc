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
                    self::input('A1-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', '', 15),
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
                    self::input('A2-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', '', 15),
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
                    self::input('B1-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', '', 48),
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
                    self::input('C2-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', '', 48),
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
            'title' => 'telc Deutsch B2',
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
                    self::text('B2-LV1-text', 'Leseverstehen Teil 1', "Person 1: Susanne Klein ist gelernte Krankenschwester und möchte nach ihrer Elternzeit wieder in den Beruf einsteigen. Sie sucht eine Teilzeitstelle mit flexiblen Arbeitszeiten.\n\nPerson 2: Peter Neumann studiert Maschinenbau im 5. Semester. Er sucht ein Praktikum bei einem internationalen Unternehmen, um praktische Erfahrungen zu sammeln.\n\nPerson 3: Die Rentnerin Renate Wolf sucht eine seniorengerechte Wohnung in ruhiger Lage. Die Miete sollte nicht über 600 Euro liegen.\n\nPerson 4: Markus Becker ist IT-Spezialist und sucht eine neue berufliche Herausforderung als Projektleiter in der Softwareentwicklung.\n\nPerson 5: Julia und Tom Sommer erwarten ihr erstes Kind und suchen einen geeigneten Geburtsvorbereitungskurs in ihrer Stadt.", 'Lesen Sie die Situationen. Für jede Person (Aufgaben 1–5) gibt es mehrere Angebote (a–j). Wählen Sie das passende Angebot.'),
                    ...self::answerElements('B2-LV1-', 1, ['h', 'a', 'f', 'd', 'i'], 'matching', 2, 'a,b,c,d,e,f,g,h,i,j'),
                    self::text('B2-LV2-text', 'Leseverstehen Teil 2', 'Homeoffice wird in Deutschland immer beliebter. Während vor der Pandemie nur wenige Unternehmen ihren Mitarbeitern die Arbeit von zu Hause aus ermöglichten, hat sich dies in den letzten Jahren grundlegend geändert. Eine repräsentative Umfrage des Instituts für Arbeitsmarkt und Berufsforschung zeigt: Mehr als 60 Prozent der Beschäftigten in Deutschland wünschen sich mindestens zwei Tage pro Woche im Homeoffice. Die Vorteile liegen auf der Hand: Lange Anfahrtszeiten entfallen, die Vereinbarkeit von Familie und Beruf verbessert sich, und viele Arbeitnehmer berichten von einer höheren Produktivität. Allerdings gibt es auch Nachteile: Die Grenzen zwischen Arbeit und Freizeit verschwimmen, der Austausch mit Kollegen wird schwieriger, und nicht jeder hat zu Hause geeignete Arbeitsbedingungen. Experten empfehlen daher eine Mischung aus Präsenz- und Homeoffice-Tagen, die sowohl die Bedürfnisse der Unternehmen als auch der Mitarbeiter berücksichtigt.', 'Lesen Sie den folgenden Text und beantworten Sie die Fragen (Aufgaben 6–10).'),
                    ...self::answerElements('B2-LV2-', 6, ['a', 'a', 'c', 'c', 'c'], 'multiple_choice', 2, 'a,b,c'),
                    self::text('B2-LV3-text', 'Leseverstehen Teil 3', "a) Modernes Einfamilienhaus mit Garten in ruhiger Wohnlage zu vermieten. 120 qm, 4 Zimmer, Einbauküche. Kaltmiete: 1.200 Euro.\n\nb) Japanisch-Sprachkurs für Anfänger. Start: 1. März. 10 Termine, jeweils dienstags von 18–20 Uhr. Kosten: 180 Euro.\n\nc) Suche zuverlässige Studentin für Nachhilfe in Mathematik und Englisch, Klassen 5–8. Zweimal pro Woche. 20 Euro pro Stunde.\n\nd) Gebrauchtes E-Bike der Marke Kalkhoff, 2 Jahre alt, wenig gefahren, Neupreis 2.400 Euro, jetzt 1.400 Euro. Nur Selbstabholer.\n\ne) Yoga am Morgen! Steigern Sie Ihr Wohlbefinden mit sanften Übungen. Kurs läuft über 8 Wochen, immer montags und mittwochs um 7 Uhr.\n\nf) Reisebüro sucht erfahrene Reiseverkehrskauffrau/-mann in Vollzeit. Schichtdienst möglich. Bewerbung mit Lebenslauf an bewerbung@reisebuero.de.\n\ng) 3-Zimmer-Wohnung in zentraler Lage zu verkaufen. 78 qm, Balkon, Kellerabteil. Provisionsfrei. Besichtigung nach Vereinbarung.\n\nh) Französisch-Konversationskurs für Fortgeschrittene. Diskutieren Sie aktuelle Themen auf Französisch. Jeden Donnerstagabend.\n\ni) Studentisches Wohnen: Schönes WG-Zimmer in beliebter Lage, 18 qm, 450 Euro warm. WG mit zwei netten Mitbewohnern. Einzug ab 1. April.\n\nj) Intensiv-Spanischkurs für den Beruf. Kompakte 4-Wochen-Kurse, die speziell auf geschäftliche Kommunikation vorbereiten. Inklusive Zertifikat.\n\nk) Regionaler Bio-Hofladen sucht Verstärkung: Verkäufer/in in Teilzeit (20 Std./Woche). Arbeitsbeginn ab sofort. Freude an Lebensmitteln erwünscht.\n\nl) Tanzkurse für Paare: Walzer, Foxtrott, Tango. Anfänger willkommen. Probestunde kostenlos! Jeden Samstagabend im Tanzstudio Lindner.", 'Lesen Sie die Aussagen (Aufgaben 11–22) und markieren Sie: + (richtig), – (falsch) oder x (nicht im Text).'),
                    ...self::answerElements('B2-LV3-', 11, ['+', 'x', '-', 'x', '+', '+', 'x', '-', 'x', '+', 'x', '-'], 'true_false', 2, '+,-,x'),
                    self::input('B2-LV3-23', 'Aufgabe 23 — Zusammenfassung', 'multiple_choice', 'c', 2, 'a,b,c'),
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
                    self::input('B2-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', '', 48),
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
                    self::input('C1-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', '', 48),
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
