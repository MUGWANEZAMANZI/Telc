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
            'B1' => array_merge(self::b1(), ['exam_provider' => 'telc']),
            'B2' => array_merge(self::b2(), ['exam_provider' => 'telc']),
            'C1' => array_merge(self::telcC1(), ['exam_provider' => 'telc']),
            'Goethe-B1' => array_merge(self::goetheB1(), ['exam_provider' => 'goethe']),
            'Goethe-B2' => array_merge(self::goetheB2(), ['exam_provider' => 'goethe']),
            'Goethe-C1' => array_merge(self::c1(), ['title' => 'Goethe Zertifikat C1', 'level' => 'C1', 'exam_provider' => 'goethe']),
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
    private static function c1(): array
    {
        return [
            'title' => 'Goethe Zertifikat C1',
            'level' => 'C1',
            'source_pdf' => 'goethe_zertifikat_c1_uebungstest_1_format_2016.pdf',
            'theme' => self::theme('#8f8f8f', '#202020', 'Instrument Sans'),
            'timings' => [
                ['label' => 'Leseverstehen und Sprachbausteine', 'minutes' => 90],
                ['label' => 'Pause', 'minutes' => 20],
                ['label' => 'Hören', 'minutes' => 40],
                ['label' => 'Schriftlicher Ausdruck', 'minutes' => 70],
                ['label' => 'Vorbereitungszeit Muendliche Pruefung', 'minutes' => 20],
                ['label' => 'Muendlicher Ausdruck', 'minutes' => 16],
            ],
            'sections' => [
                self::section('c1-reading-language', 'Leseverstehen und Sprachbausteine', 90, [
                    self::text('C1-LV1-instruction', 'Teil 1 — vorgeschlagene Arbeitszeit: 10 Minuten', 'Sie lesen in einer Zeitschrift einen Artikel über ein Unternehmen in der Tourismusbranche. Wählen Sie für jede Lücke die richtige Lösung.'),
                    self::inlineGap('C1-LV1-inline', 'JUNGE UNTERNEHMEN DER TOURISMUSBRANCHE: StadtTours', "In Deutschland werden jeden Tag neue Start-ups gegründet. Eines von ihnen wollen wir heute vorstellen: StadtTours.\n\nDer Name ist Programm – das Unternehmen hat sich auf Reisen spezialisiert, für die man nicht lange in die Ferne schweifen muss. 20 Teams in ganz Deutschland zeigen ausgewählte Städte aus ungewohnten, spannenden Perspektiven. Die Reisenden werden **dazu** (Beispiel 0) eingeladen, die besuchte Stadt und ihre Umgebung zu „entziffern“, und kommen darüber ins Gespräch. Auf ihren Entdeckungsreisen besichtigen sie nicht nur touristische Attraktionen, sondern --0-- auch Geschichten aus dem Alltagsleben der jeweiligen Stadt. Die teilnehmenden Besucherinnen und Besucher erhalten Einblicke in kulturelle, politische und wirtschaftliche Zusammenhänge und bekommen so ein facettenreiches Bild von historischen Entwicklungen und Bräuchen der Stadt, die z. B. auch --1-- Fotos oder Anekdoten veranschaulicht werden. Dabei werden die Interessen der Gäste berücksichtigt: Ob nun in Millionenstädten wie Berlin, Hamburg und München oder in einer der anderen 17 Städte – die Programme passen sich den Wünschen der Gäste an, nicht --2--.\n\nDas Start-up hat Standards entwickelt, --3-- in allen angebotenen Städten eine hohe Qualität der Programme gewährleisten sollen. Ob Historiker*innen, Geograf*innen, Journalist*innen, Schauspieler*innen oder Schriftsteller*innen: All diese Expertinnen und Experten bringen nicht nur Fachkompetenz, sondern darüber hinaus auch Erfahrung --4-- Umgang mit Gruppen mit. Von Anfang an praktiziert das junge Unternehmen die Idee des sanften Tourismus. Seine Gründer waren --5-- Wegbereiter für umwelt- und sozialverträgliches Reisen sowie für zeitgemäßen und nachhaltigen Tourismus.\n\nDie Angebote der einzelnen Reise-Teams --6-- städtetouristische Ansprüche mit Niveau: Stadtspaziergänge mit App zum selbstständigen Erkunden, Stadtspiele als Wettbewerbe für größere Gruppen, lebendige Lesungen zur Stadtgeschichte, Rundfahrten mit E-Bikes und E-Rollern oder mit dem „normalen“ Fahrrad – im vielfältigen Angebot ist alles, was Spaß verspricht. Jede Stadt hat ihren ganz eigenen Charme. --7-- hat jedes StadtTours-Team überall einzigartige Programme für seine Besucherinnen und Besucher vorbereitet. Die Reisen eignen sich für Gruppen jeden Alters, besonders jedoch für Schulklassen.", [
                        ['options' => 'a|bekommen,b|erfahren,c|lernen,d|wissen', 'answer' => 'b'],
                        ['options' => 'a|als Anlage zu,b|in Einklang mit,c|im Einverständnis mit,d|mithilfe von', 'answer' => 'd'],
                        ['options' => 'a|umsonst,b|ungefähr,c|ungewöhnlich,d|umgekehrt', 'answer' => 'd'],
                        ['options' => 'a|das,b|die,c|für die,d|mit denen', 'answer' => 'b'],
                        ['options' => 'a|bei,b|im,c|in,d|von', 'answer' => 'b'],
                        ['options' => 'a|daraus,b|davon,c|gegenüber,d|somit', 'answer' => 'd'],
                        ['options' => 'a|erfüllen,b|ergänzen,c|erledigen,d|erzeugen', 'answer' => 'a'],
                        ['options' => 'a|Dennoch,b|Entsprechend,c|Nachdem,d|Zunehmend', 'answer' => 'b'],
                    ], 2, 'Wählen Sie die richtige Option (a, b, c oder d) für jede Lücke aus dem jeweiligen Dropdown-Feld.'),
                    self::text('C1-LV2-instruction', 'Teil 2 — vorgeschlagene Arbeitszeit: 20 Minuten', 'Sie lesen im Internet einen Artikel über eine Studie zur Handynutzung in Familien. Wählen Sie bei jeder Aufgabe die richtige Lösung.'),
                    self::text('C1-LV2-text', 'FAMILIEN IM FOKUS: Der elterliche Handykonsum und seine Folgen',
                        "<div style=\"font-family: 'Plus Jakarta Sans', sans-serif; text-align: justify; line-height: 1.6;\">\n".
                        "    <h4 style=\"text-align: center; letter-spacing: 0.1em; color: #64748b; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.25rem; text-transform: uppercase;\">FAMILIEN IM FOKUS</h4>\n".
                        "    <h2 style=\"text-align: center; color: #1e293b; font-size: 1.6rem; font-weight: 800; margin-top: 0; margin-bottom: 1.5rem;\">Der elterliche Handykonsum und seine Folgen</h2>\n".
                        "    \n".
                        "    <p style=\"margin-bottom: 1rem; color: #334155;\">Laut einer jüngst in einer englischsprachigen Zeitschrift veröffentlichten Studie verursacht die häufige, intensive Beschäftigung vieler Eltern mit dem Handy bei ihren Kindern Frust, Wut, Stress sowie Verhaltensauffälligkeiten. Dennoch ist es nach wie vor die Internetnutzung der Sprösslinge, die ihre Zeit am Handy oder Tablet mit Spielen und Serien verbringen, die zu Diskussionen im Familienkreis führt. Wenn Eltern dasselbe tun, scheint ihnen das jedoch meist kein Grund zur Besorgnis zu sein, sondern gilt als Teil ihrer individuellen Freiheit. Als Erwachsene wissen sie schließlich selbst am besten, was sie gerade brauchen, um glücklich und gesund zu bleiben, so argumentieren sie zumindest oft selbst.</p>\n".
                        "    \n".
                        "    <p style=\"margin-bottom: 1rem; color: #334155;\">Es besteht allerdings kein Zweifel daran, dass in Familien mit Eltern, die ihr Smartphone nur schwer zur Seite legen können, die Kommunikation unter den Familienmitgliedern leidet: Das Handy wird zum Kommunikationsmittel Nummer eins, der ausführliche Eltern-Kind-Austausch fehlt. Diese ungesunden Auswirkungen auf die sozialen Kontakte in der Familie haben in vielen Fällen eine gestörte Sprachentwicklung der Heranwachsenden zur Folge, die sich auch in schlechteren Noten widerspiegelt. Die Unterstützung bei den Hausaufgaben bleibt notgedrungen unzureichend, wenn die Eltern währenddessen mit dem Handy spielen. Fordern die Kinder dann Beachtung ein, reagieren zahlreiche Eltern eher verärgert oder gereizt.</p>\n".
                        "    \n".
                        "    <p style=\"margin-bottom: 1rem; color: #334155;\">Das Forschungsteam der Studie hat in einem Langzeitexperiment untersucht, ob Kinder in Konkurrenz mit den Smartphones ihrer Eltern stehen. Dafür baten die Wissenschaftler 195 Elternpaare mit mindestens einem Kind unter fünf Jahren darum, sechs Monate lang Auskunft über ihre tägliche Smartphone-Nutzung zu geben. Zugleich wurden sie befragt, wie sich das Verhalten der Kinder während des Beobachtungszeitraums entwickelt hat. Lenkten sich Eltern wiederholt mit dem Handy ab, verhielten sich ihre Kinder häufiger auffällig, das heißt, sie quengelten und jammerten viel oder bekamen unverhältnismäßig starke Wutausbrüche. Sobald die Unterfünfjährigen jedoch das Handy selbst als Spielzeug erhielten, wurden sie ruhig und machten einen zufriedenen Eindruck.</p>\n".
                        "    \n".
                        "    <p style=\"margin-bottom: 1rem; color: #334155;\">Die Dynamik solcher Situationen gleiche einem Teufelskreis, argumentieren die Autorinnen und Autoren der Studie. Einige an der Untersuchung beteiligte Eltern räumten ein, dass sie sich besonders in Situationen nach dem Smartphone griffen, in denen sie sich von ihren Kindern gestresst fühlten. Das Handy biete ihnen eine Art Flucht aus dem Chaos, in das die Betreuung von Kindern ausarten kann. Sogar die Beschäftigung mit Problemen am Arbeitsplatz wurde in diesem Kontext als entspannende Tätigkeit empfunden.</p>\n".
                        "    \n".
                        "    <p style=\"margin-bottom: 1rem; color: #334155;\">Kinder, auch das hat die Studie gezeigt, reagieren sensibel auf den Verlust von Aufmerksamkeit seitens ihrer Eltern. Die Expertinnen und Experten schlussfolgern: Wer sich weniger auf seine Kinder einlässt, bekommt auch schwerer Zugang zu ihnen. Jedes Familienmitglied lebt dann letztendlich in seiner eigenen Welt und nimmt nur noch seine eigene Realität wahr.</p>\n".
                        "    \n".
                        "    <p style=\"margin-bottom: 1rem; color: #334155;\">Die Expertinnen und Experten geben Empfehlungen, wie sich wirksam Abhilfe schaffen lässt. Natürlich dürfen sich Eltern auch mal ausklinken, bevor sie sich zwischen den Ansprüchen der Familie zerreiben. Auch Mütter und Väter sind nur Menschen, und das natürliche Bedürfnis nach Zerstreuung verschwindet nicht mit dem Kinderglück. Hier kann das Telefon durchaus helfen. Doch darf nicht vergessen werden, im Familienalltag handyfreie Zeiten und Rituale zu schaffen. Bei gemeinsamen Mahlzeiten, beim Spieleabend oder dem Zubettgehen hat das Handy nichts zu suchen.</p>\n".
                        "    \n".
                        "    <p style=\"margin-bottom: 0; color: #334155;\">Aktuell liegt auch das Handy-Fasten im Trend, das sich gemeinsam mit den Kindern anwenden lässt. Auf diese Weise hat die ganze Familie etwas davon: Ob einen Monat, eine Woche oder drei Tage – der freiwillige Verzicht bringt die Menschen wieder ohne digitale Ablenkung an einen Tisch zusammen. Und wo vorher Schweigen war, könnte schon bald ein reger Gedankenaustausch entstehen. Ohne Handy können Eltern auch ihre Freizeit wieder bewusster mit den Kindern verbringen und gemeinsame Unternehmungen planen. Je nach den Vorlieben sollte eine Aktivität gewählt werden, die alle Familienmitglieder einbindet. Falls dennoch Kompromisse notwendig sind, sollte jede und jeder zugestimmt haben. Um eine passende Aktivität zu finden, können auch der Freundes- oder Bekanntenkreis oder entsprechende Portale im Internet befragt werden. Die einen entscheiden sich dann für das Anlegen eines Gemüsebeets im Gemeinschaftsgarten, die anderen für eine Schnitzeljagd durch die Stadt – alles ist erlaubt, Hauptsache, es macht Spaß und das Handy wird nur für Fotos aus der Tasche genommen.</p>\n".
                        '</div>'
                    ),
                    self::input('C1-LV2-9', 'Aufgabe 9', 'multiple_choice', 'a', 2, self::encode([
                        ['key' => 'a', 'text' => 'ob sich Kinder zu viel online beschäftigen.'],
                        ['key' => 'b', 'text' => 'wie Kinder gefördert werden können.'],
                        ['key' => 'c', 'text' => 'wie stressig die Telefonnutzung der Eltern für Kinder ist.'],
                    ]), 'Innerhalb der Familien wird oft thematisiert, ..'),
                    self::input('C1-LV2-10', 'Aufgabe 10', 'multiple_choice', 'b', 2, self::encode([
                        ['key' => 'a', 'text' => 'Aggressives Benehmen in der Familie.'],
                        ['key' => 'b', 'text' => 'Nachlassen der schulischen Leistung.'],
                        ['key' => 'c', 'text' => 'Unkonzentrierte Erledigung von Hausaufgaben.'],
                    ]), 'Welche Folgen hat der Handykonsum der Eltern bei den Kindern?'),
                    self::input('C1-LV2-11', 'Aufgabe 11', 'multiple_choice', 'a', 2, self::encode([
                        ['key' => 'a', 'text' => 'Auswirkungen des Handykonsums von Eltern.'],
                        ['key' => 'b', 'text' => 'Folgen der regelmäßigen Handynutzung von Kindern.'],
                        ['key' => 'c', 'text' => 'Verhaltensauffälligkeit bei Kindern.'],
                    ]), 'Was wurde in einem Experiment mit Elternpaaren untersucht?'),
                    self::input('C1-LV2-12', 'Aufgabe 12', 'multiple_choice', 'a', 2, self::encode([
                        ['key' => 'a', 'text' => 'Anspannung abzubauen.'],
                        ['key' => 'b', 'text' => 'chaotischen Zuständen vorzubeugen.'],
                        ['key' => 'c', 'text' => 'Probleme im Beruf zu lösen.'],
                    ]), 'Laut den Wissenschaftlerinnen und Wissenschaftlern hilft das Handy den Eltern ..'),
                    self::input('C1-LV2-13', 'Aufgabe 13', 'multiple_choice', 'c', 2, self::encode([
                        ['key' => 'a', 'text' => 'dem Realitätsverlust der Eltern.'],
                        ['key' => 'b', 'text' => 'der kindlichen Sensibilität.'],
                        ['key' => 'c', 'text' => 'verminderter elterlicher Zuneigung.'],
                    ]), 'Die Beziehung zwischen Eltern und Kindern leidet unter ..'),
                    self::input('C1-LV2-14', 'Aufgabe 14', 'multiple_choice', 'b', 2, self::encode([
                        ['key' => 'a', 'text' => 'in der Wohnung handyfreie Zonen einzurichten.'],
                        ['key' => 'b', 'text' => 'die Handynutzung bewusst zu reglementieren.'],
                        ['key' => 'c', 'text' => 'mit ihren Kindern regelmäßig aufs Handy zu verzichten.'],
                    ]), 'Den Eltern wird empfohlen, ..'),
                    self::input('C1-LV2-15', 'Aufgabe 15', 'multiple_choice', 'b', 2, self::encode([
                        ['key' => 'a', 'text' => 'Aktivitäten frühzeitig geplant werden.'],
                        ['key' => 'b', 'text' => 'alle Mitspracherecht bei der Entscheidung haben.'],
                        ['key' => 'c', 'text' => 'sie leichter fällt, wenn Freunde daran teilnehmen.'],
                    ]), 'Bei der Freizeitgestaltung muss berücksichtigt werden, dass ..'),
                    self::inlineGap('C1-LV3-matching', 'Leseverstehen Teil 3', "„Manager-Müdigkeit” - eine bedrohliche Entwicklung in Führungsetagen?\n\nLandauf, landab werden derzeit zahlreiche Unternehmen mit einem neuen Phänomen konfrontiert: „Manager-Müdigkeit”. Das mag recht harmlos klingen, bezeichnet jedoch für viele Unternehmen einen bedenklichen Trend. **[0]** Konkret bedeutet es, dass weniger als 10 % der Beschäftigten auf einer Führungsposition arbeiten möchten.\n\nDas Problem ist weit größer, als erste Umfragen erwarten ließen. In den Chefetagen fehlt nicht nur der Nachwuchs. Zunehmendes Desinteresse an Führungsposten ist auch bei jenen festzustellen, die bereits im Management tätig sind. --0-- Mehr als die Hälfte von ihnen fühlt sich beispielsweise gestresst oder empfindet die Anforderungen der Berufswelt als stetig wachsend. Ein Drittel der Managerinnen und Manager ist von den Aufgaben überfordert und würde es sogar bevorzugen, nicht mehr Teil der Arbeitswelt zu sein.\n\nAbstrahiert man vom Einzelschicksal und stellt sich die Folgen dieser neuen Einstellung für Unternehmen – egal welcher Größe – vor, dann kann man ermessen, was für ein eklatantes Problem da auf unsere Wirtschaft zukommt. Denn ohne Führungskräfte sind Unternehmen im wahrsten Sinne des Wortes „führungslos“. --1-- Wer plant, wer konzipiert, wer übernimmt die Verantwortung? Wenn diese Positionen nicht mehr besetzt werden können, wird nicht nur der Profit der Firma zurückgehen, sondern alle Mitarbeitenden werden unter den Folgen zu leiden haben.\n\nDie interessante Frage lautet: Wo liegen die Ursachen für diese Entwicklung? Früher träumten zahlreiche zielstrebige Jura- oder BWL-Studierende von einer steilen Karriere in Unternehmen. --2-- Sicherlich sind die Ursachen für diese tiefgreifende Veränderung vielschichtig, jedoch war der Prozess kontinuierlich und absehbar. Ein wichtiger Grund könnte der moderne Erziehungsstil sein. Obwohl die Eltern der heutigen Jugendlichen lange nach den entbehrreichen Kriegsjahren aufgewachsen sind, konnten sie der Versuchung nicht widerstehen, ihre Kinder zu verwöhnen. Die Kinder erhalten viel und das meist ohne große Gegenleistung. --3-- Warum sollten sie Tätigkeiten auf sich nehmen, die sie unangenehm oder anstrengend finden? Diese Einstellung der jungen Generation hat großen Einfluss auf ihren beruflichen Werdegang.\n\nIm Berufsleben der Kinder treten Eltern in den Hintergrund. Ganz im Gegensatz dazu steht die Schullaufbahn, in deren Verlauf sie ihre Söhne und Töchter durch zahlreiche Lehrergespräche und die Finanzierung von Hausaufgabenhilfe oder Nachhilfestunden unterstützen können. --4-- Jedoch bleibt die Frage offen, ob die Kinder nicht auch ohne ein solches „Rundum-sorglos-Paket“ erfolgreich den Schulabschluss schaffen und später davon profitieren würden. Übertriebene Förderung in der Schule kann im Arbeitsleben nämlich ebenfalls als Karrierehindernis wirken. So setzen einige der Geförderten andere Prioritäten im Leben. --5-- Denn Freizeit geht für sie vor Arbeitsverpflichtungen – man kümmert sich lieber um sich selbst als um das Firmenwohl.\n\nFür jeden individuell mag dies eine kluge Entscheidung sein, als genereller Trend es jedoch problematisch. Verschärft wird die negative Entwicklung durch die schon ermüdeten Managerinnen und Manager, die aktuell Führungspositionen innehaben. --6-- So äußern sie mehr oder weniger unverhohlen: Sollen doch die Jungen übernehmen und die aktuellen Herausforderungen wie Globalisierung und Digitalisierung meistern! Damit liegen sie jedoch falsch – wir benötigen eine gemeinsame Strategie und den Einsatz aller Altersklassen. Es liegt doch auf der Hand, dass die Probleme des 21. Jahrhunderts mit viel Energie und neuen Methoden gelöst werden müssen. --7-- Da dies keine Option sein kann, benötigen wir auch einen neuen Führungsstil, der wieder Spaß macht – dann findet sich auch das Personal dafür.", [
                        ['options' => 'a,b,c,d,e,f,g,h,i,j,x', 'answer' => 'e'],
                        ['options' => 'a,b,c,d,e,f,g,h,i,j,x', 'answer' => 'i'],
                        ['options' => 'a,b,c,d,e,f,g,h,i,j,x', 'answer' => 'a'],
                        ['options' => 'a,b,c,d,e,f,g,h,i,j,x', 'answer' => 'b'],
                        ['options' => 'a,b,c,d,e,f,g,h,i,j,x', 'answer' => 'c'],
                        ['options' => 'a,b,c,d,e,f,g,h,i,j,x', 'answer' => 'g'],
                        ['options' => 'a,b,c,d,e,f,g,h,i,j,x', 'answer' => 'h'],
                        ['options' => 'a,b,c,d,e,f,g,h,i,j,x', 'answer' => 'j'],
                    ], 2, 'Entscheiden Sie, welcher Satz a–j in die Lücken 16–23 passt. Zwei Sätze passen nicht.'),
                    ...self::c1Teil4Elements(),
                    self::inlineGap('C1-SB-inline', 'Sprachbausteine', "Ein merkwürdiger Akzent\n\nStellen Sie sich vor, Sie wachen eines Morgens auf und wünschen Ihrem Partner einen guten Morgen – --0-- die Worte aus Ihrem Mund klingen --1--. Dann könnte es sich um eine --2-- seltene neurologische Störung handeln: das „Foreign Accent Syndrome“, zu Deutsch Fremdsprachenakzentsyndrom.\n\nBeim Fremdsprachenakzentsyndrom sprechen Patienten plötzlich ihre Muttersprache mit einem --3-- regionalen oder ausländischen Akzent. Meist geht die veränderte Sprechweise auf Hirnschäden --4-- eines Schlaganfalls oder einer Verletzung --5--. Die Erkrankung beeinflusst die Sprachmelodie, wobei die --6-- Lautbildung und Betonung fälschlicherweise den Eindruck erweckt, der Betroffene sei kein Muttersprachler.\n\nEin in der Fachwelt bekanntes Beispiel ist der Fall von Sabine Kindschuh. Hätte die Patientin ein paar hundert Jahre früher gelebt, --7-- sie eine Kandidatin für den Scheiterhaufen gewesen. Ihr Heimatdorf Oberalba in Thüringen war im 17. Jahrhundert Schauplatz mehrerer Hexenprozesse, und was mit der 57-Jährigen --8--, hätte man einst wohl übernatürlichen Mächten --9--: Sie sprach nach einem Schlaganfall plötzlich mit einem Tonfall, der an den Schweizer Dialekt erinnerte. --10-- sie auch versuchte, wieder normal zu sprechen, es gelang ihr einfach nicht.\n\nDie --11-- Ärzte vermuteten psychische Ursachen dahinter, --12-- eine regionale Tageszeitung berichtete. Erst ein Sprachtherapeut erkannte: Es handelte sich um das „Foreign Accent Syndrome“, eine neurologische Erkrankung, die --13-- selten ist, dass ein durchschnittlicher Mediziner in seinem gesamten Leben keinen einzigen Betroffenen --14-- bekommt. Experten sprechen von weltweit rund 60, maximal 100 Fällen, die überhaupt jemals --15--.\n\n--16-- beschrieb der französische Neurologe Pierre Marie 1907: Eine Pariserin, die einen Schlaganfall erlitten hatte und danach rechtsseitig gelähmt war, begann --17-- mit einem elsässisch klingenden Akzent zu sprechen. Mehr Details sind von einer anderen historischen Patientin überliefert: Der Experte --18-- Sprachstörungen Georg Herman Monrad-Krohn dokumentierte 1943 die Geschichte der Norwegerin Astrid L., die bei einem Unfall am linken Stirnhirn verletzt wurde. Sie --19-- mehrere Tage bewusstlos und danach rechtsseitig gelähmt gewesen sein. Zunächst hatte sie enorme Schwierigkeiten, einfache Gegenstände wie eine Streichholzschachtel --20--, und machte zahlreiche Grammatikfehler. Mit der Zeit lernte sie zwar wieder, korrekt und flüssig zu sprechen, allerdings mit einem Tonfall, der ihre Landsleute offenbar --21-- deutschen Akzent erinnerte.", [
                        ['options' => 'a|dagegen,b|doch,c|hingegen,d|jedoch', 'answer' => 'b'],
                        ['options' => 'a|anderweitig,b|artfremd,c|auswärtig,d|fremdartig', 'answer' => 'd'],
                        ['options' => 'a|durchweg,b|extra,c|gehörig,d|überaus', 'answer' => 'd'],
                        ['options' => 'a|vermeintlich,b|vermeintliche,c|vermeintlichem,d|vermeintlicher', 'answer' => 'a'],
                        ['options' => 'a|dank,b|infolge,c|kraft,d|zwecks', 'answer' => 'b'],
                        ['options' => 'a|hervor,b|hinaus,c|voraus,d|zurück', 'answer' => 'd'],
                        ['options' => 'a|veränderbare,b|veränderliche,c|verändernde,d|veränderte', 'answer' => 'd'],
                        ['options' => 'a|sei,b|war,c|wäre,d|würde', 'answer' => 'c'],
                        ['options' => 'a|für sich hatte,b|mit sich zog,c|von sich gab,d|vor sich ging', 'answer' => 'd'],
                        ['options' => 'a|angeschrieben,b|gutgeschrieben,c|vorgeschrieben,d|zugeschrieben', 'answer' => 'd'],
                        ['options' => 'a|Desto,b|So sehr,c|So weit,d|Weit mehr', 'answer' => 'b'],
                        ['options' => 'a|behandelnde,b|behandelnden,c|behandelte,d|behandelten', 'answer' => 'b'],
                        ['options' => 'a|als,b|das,c|die,d|wie', 'answer' => 'a'],
                        ['options' => 'a|der Art,b|Derart,c|derart,d|der-Art', 'answer' => 'c'],
                        ['options' => 'a|im Blick,b|in Sicht,c|ins Auge,d|zu Gesicht', 'answer' => 'd'],
                        ['options' => 'a|bekannt gewesen war,b|bekannt geworden,c|bekannt sind,d|bekannt wurden', 'answer' => 'd'],
                        ['options' => 'a|Dem ersten Patienten,b|Den ersten Patienten,c|Der erste Patient,d|Des ersten Patienten', 'answer' => 'b'],
                        ['options' => 'a|daraufhin,b|indem,c|nach,d|nachdem', 'answer' => 'a'],
                        ['options' => 'a|auf,b|für,c|über,d|von', 'answer' => 'b'],
                        ['options' => 'a|ist,b|soll,c|war,d|wurde', 'answer' => 'b'],
                        ['options' => 'a|benannt,b|benennen,c|benennen können,d|zu benennen', 'answer' => 'd'],
                        ['options' => 'a|an einen,b|auf einen,c|mit einem,d|zu einem', 'answer' => 'a'],
                    ], 1, 'Lesen Sie den folgenden Text. Welche Option (a, b, c oder d) ist für jede Lücke richtig? Wählen Sie die passende Antwort aus dem jeweiligen Dropdown-Feld.'),
                ], self::pages(8, 16)),
                self::section('c1-listening-intro', 'Kandidatenblätter', 40, [
                    self::text('C1-HV0-intro', 'Kandidatenblätter Hören', "Kandidatenblätter\nHören\ncirca 40 Minuten\n\nDas Modul Hören hat vier Teile.\nSie hören mehrere Texte und lösen Aufgaben dazu.\nLesen Sie jeweils zuerst die Aufgaben\nund hören Sie dann den Text dazu.\nFür jede Aufgabe gibt es nur eine\nrichtige Lösung.\nNach dem Hören haben Sie drei\nMinuten Zeit, Ihre Lösungen auf den\nAntwortbogen zu übertragen.\nEs werden nur die Lösungen und Texte\nauf dem Antwortbogen bewertet.\nBitte schreiben Sie mit einem blauen\noder schwarzen Stift.\nHilfsmittel (z. B. Wörterbücher, Handys/\nmobile Endgeräte) sind nicht erlaubt."),
                ], self::pages(17, 17)),
                self::section('c1-listening', 'Hörverstehen', 40, [
                    self::audio('c1-audio', 'Hörverstehen Audio', '/goethe-assets/goethe_zertifikat_c1_uebungstest_1_format_2016.mp3'),
                    self::text('C1-HV1-instruction', 'Hörverstehen Teil 1: Podcasts über neue Bücher', "Sie hören einen Podcast über neue Bücher.\nSie hören den Text einmal. Wählen Sie bei jeder Aufgabe, zu welchem Buch die Aussage passt.\nLesen Sie jetzt die Aufgaben 1 bis 6. Dazu haben Sie 60 Sekunden Zeit.", "Aussagen:\nBeispiel:\n0 Das Buch ist ein fiktionales Werk.\na Buch 1 - Polarsturm\nb Buch 2 - Faszination Meer\nc Buch 3 - Die Natur und das Meer\n\n1 Es werden Analysen über Flora und Fauna angeführt.\n2 Im Buch wird die Erforschung des Klimas vergangener Epochen behandelt.\n3 Man erfährt etwas über Bedrohungen, die uns beschäftigen werden.\n4 Die Veränderungen der Machtverhältnisse auf den Schifffahrtsrouten werden beschrieben.\n5 Die Wechselwirkungen zwischen menschlichem Handeln und dem Zustand der Meere werden gezeigt.\n6 Neben wissenschaftlichen werden auch zwischenmenschliche Fragestellungen behandelt."),
                    ...self::answerElements('C1-HV1-', 1, ['c', 'a', 'c', 'b', 'c', 'a'], 'matching', 1, 'Buch 1,Buch 2,Buch 3'),
                    self::text('C1-HV2-instruction', 'Hörverstehen Teil 2: Radiointerview mit Tobias Maurer', '', "Sie hören ein Radiointerview mit einer Persönlichkeit aus der Wissenschaft.\nSie hören den Text zweimal. Wählen Sie bei jeder Aufgabe die richtige Lösung.\nLesen Sie jetzt die Aufgaben 7 bis 15. Dazu haben Sie 60 Sekunden Zeit."),
                    self::input('C1-HV2-7', 'Aufgabe 7', 'multiple_choice', 'b', 2, 'a,b,c', "Handschrift und Rechtschreibung haben sich bei Schulkindern gleichermaßen verschlechtert.\na) stimmt\nb) stimmt nicht\nc) dazu wird nichts gesagt"),
                    self::input('C1-HV2-8', 'Aufgabe 8', 'multiple_choice', 'a', 2, 'a,b,c', "Das Schreiben mit der Hand wirkt sich positiv auf kognitive Fähigkeiten aus.\na) stimmt\nb) stimmt nicht\nc) dazu wird nichts gesagt"),
                    self::input('C1-HV2-9', 'Aufgabe 9', 'multiple_choice', 'b', 2, 'a,b,c', "Herr Maurer führt die USA als Beispiel für eine gelungene Digitalisierung von Schulen an.\na) stimmt\nb) stimmt nicht\nc) dazu wird nichts gesagt"),
                    self::input('C1-HV2-10', 'Aufgabe 10', 'multiple_choice', 'c', 2, 'a,b,c', "Herr Maurer wollte schon immer ein mit der Hand geschriebenes Buch herausbringen.\na) stimmt\nb) stimmt nicht\nc) dazu wird nichts gesagt"),
                    self::input('C1-HV2-11', 'Aufgabe 11', 'multiple_choice', 'b', 2, 'a,b,c', "Für Herrn Maurer gehört das Tippen zum Konzipieren.\na) stimmt\nb) stimmt nicht\nc) dazu wird nichts gesagt"),
                    self::input('C1-HV2-12', 'Aufgabe 12', 'multiple_choice', 'a', 2, 'a,b,c', "Schreiben am Computer stellt laut Tobias Maurer eine wichtige Kompetenz dar.\na) stimmt\nb) stimmt nicht\nc) dazu wird nichts gesagt"),
                    self::input('C1-HV2-13', 'Aufgabe 13', 'multiple_choice', 'a', 2, 'a,b,c', "Beim Tippen wird das Gehörte ungefiltert übernommen.\na) stimmt\nb) stimmt nicht\nc) dazu wird nichts gesagt"),
                    self::input('C1-HV2-14', 'Aufgabe 14', 'multiple_choice', 'a', 2, 'a,b,c', "Herr Maurer betont, dass handschriftlich notierte Inhalte besser verarbeitet werden.\na) stimmt\nb) stimmt nicht\nc) dazu wird nichts gesagt"),
                    self::input('C1-HV2-15', 'Aufgabe 15', 'multiple_choice', 'b', 2, 'a,b,c', "Herr Maurer glaubt, dass das allmähliche Aussterben der Handschrift unvermeidlich ist.\na) stimmt\nb) stimmt nicht\nc) dazu wird nichts gesagt"),
                    self::text('C1-HV3-instruction', 'Hörverstehen Teil 3: Wohnen der Zukunft', '', "Sie hören ein Gespräch mit mehreren Personen über das Wohnen der Zukunft.\nSie hören den Text in vier Abschnitten jeweils einmal. Zu jedem Abschnitt gibt es zwei Aufgaben. Wählen Sie bei jeder Aufgabe die richtige Lösung.\nVor dem Hören eines Abschnitts haben Sie 30 Sekunden Zeit, um die zwei Aufgaben zu lesen."),
                    self::input('C1-HV3-16', 'Aufgabe 16', 'multiple_choice', 'a', 2, 'a,b,c', "Der Bau eines Eigenheims ...\na) hat negative Folgen für die Umwelt.\nb) ist für viele Familien unerschwinglich.\nc) sollte vom Staat stärker unterstützt werden."),
                    self::input('C1-HV3-17', 'Aufgabe 17', 'multiple_choice', 'b', 2, 'a,b,c', "Die Soziologin plädiert für ...\na) die Förderung von Wohnprojekten außerhalb der Stadtzentren.\nb) Wohnmodelle, die Gemeinschafts- und Privaträume berücksichtigen.\nc) mehr Wohnraum für individualisiertes Wohnen."),
                    self::input('C1-HV3-18', 'Aufgabe 18', 'multiple_choice', 'c', 2, 'a,b,c', "Alternative Wohnkonzepte wie die hier vorgestellten ...\na) eignen sich kaum für die ältere Bevölkerung.\nb) sind in Deutschland bisher weitgehend unbekannt.\nc) stoßen bei den meisten Menschen auf wenig Interesse."),
                    self::input('C1-HV3-19', 'Aufgabe 19', 'multiple_choice', 'c', 2, 'a,b,c', "Der Städteplaner fordert, ...\na) mehr bezahlbare Etagenwohnungen zur Verfügung zu stellen.\nb) neue Räume für gemeinschaftliches Wohnen zu bauen.\nc) urbanen Wohnraum multifunktional zu nutzen."),
                    self::input('C1-HV3-20', 'Aufgabe 20', 'multiple_choice', 'a', 2, 'a,b,c', "Die Schaffung von mehr Wohnraum in den Städten ...\na) ist angesichts der demografischen Entwicklung erforderlich.\nb) setzt den Ausbau der Infrastruktur voraus.\nc) trägt zur Beschleunigung des Klimawandels bei."),
                    self::input('C1-HV3-21', 'Aufgabe 21', 'multiple_choice', 'b', 2, 'a,b,c', "Das Konzept der Kleinsthäuser ...\na) ist aufgrund der dafür notwendigen Einschränkungen gescheitert.\nb) könnte für große Teile der Bevölkerung interessant sein.\nc) zeigt den Willen zu einem neuen Wohnstil."),
                    self::input('C1-HV3-22', 'Aufgabe 22', 'multiple_choice', 'b', 2, 'a,b,c', "Mit der Verkleinerung der Wohnfläche ...\na) muss man auf viele Gegenstände verzichten.\nb) verringert sich der Aufwand im Haushalt.\nc) wächst der Bedarf an modernster Technik."),
                    self::input('C1-HV3-23', 'Aufgabe 23', 'multiple_choice', 'b', 2, 'a,b,c', "Die Soziologin tritt dafür ein, dass ...\na) der Staat bei der Wohnungssuche behilflich ist.\nb) ein Wohnungstausch staatlich gefördert wird.\nc) Familien die Kosten für ihren Umzug erstattet werden."),
                    self::text('C1-HV4-instruction', 'Hörverstehen Teil 4: Maßnahmen in der Europäischen Union', '', "Sie hören einen Vortrag über Maßnahmen in der Europäischen Union.\nSie hören den Text zweimal. Wählen Sie bei jeder Aufgabe die richtige Lösung.\nLesen Sie jetzt die Aufgaben 24 bis 30. Dazu haben Sie 90 Sekunden Zeit."),
                    self::input('C1-HV4-24', 'Aufgabe 24', 'multiple_choice', 'b', 2, 'a,b,c', "Für die Europäische Union (EU) ist es ein wichtiges Ziel, ...\na) ihre Bürgerinnen und Bürger in beruflicher Hinsicht zu fördern.\nb) bessere Bedingungen für Familien zu schaffen.\nc) Projekte berufstätiger Paare finanziell zu unterstützen."),
                    self::input('C1-HV4-25', 'Aufgabe 25', 'multiple_choice', 'b', 2, 'a,b,c', "Wie hat sich die Beschäftigungsquote in der EU entwickelt?\na) Das Ziel wurde sowohl für Männer als auch für Frauen erreicht.\nb) Die Quote bei den Frauen steigt seit einem Jahrzehnt kontinuierlich an.\nc) Die Zahl der erwerbstätigen Männer sank zwischenzeitlich leicht."),
                    self::input('C1-HV4-26', 'Aufgabe 26', 'multiple_choice', 'c', 2, 'a,b,c', "Wie erfolgreich ist die Gleichstellungspolitik für Männer und Frauen bislang?\na) Die Lohnunterschiede werden nach wie vor größer.\nb) Der Anteil von Frauen auf Leitungsebene ist gesunken.\nc) Bezüglich gleicher Bezahlung ist der Trend positiv."),
                    self::input('C1-HV4-27', 'Aufgabe 27', 'multiple_choice', 'c', 2, 'a,b,c', "Welcher weitere Unterschied zwischen Männern und Frauen wird angesprochen?\na) Frauen mit Kindern lassen sich öfter beurlauben.\nb) Der Großteil der Männer hat einen sicheren Arbeitsplatz.\nc) Frauen sind häufiger in minder honorierten Berufen tätig."),
                    self::input('C1-HV4-28', 'Aufgabe 28', 'multiple_choice', 'b', 2, 'a,b,c', "Was müssen werdende Väter für eine staatliche Unterstützung beachten?\na) Für Elterngeld müssen sie in einem Arbeitsverhältnis stehen.\nb) Bei der Anmeldung der Elternzeit gibt es besondere Fristen.\nc) Vorgesetzte können den Zeitraum der Elternzeit mitbestimmen."),
                    self::input('C1-HV4-29', 'Aufgabe 29', 'multiple_choice', 'a', 2, 'a,b,c', "Welche Bedingung muss ein Kindergarten erfüllen? Er muss ...\na) für die Eltern bezahlbar sein.\nb) ausreichend Personal haben.\nc) leicht zu erreichen sein."),
                    self::input('C1-HV4-30', 'Aufgabe 30', 'multiple_choice', 'b', 2, 'a,b,c', "Die geplanten Maßnahmen der EU-Kommission sollen ...\na) dem Bevölkerungswachstum entgegenwirken.\nb) zur Betreuung von Angehörigen ermuntern.\nc) bewirken, dass mehr Kinder geboren werden."),
                ], self::pages(18, 21)),
                self::section('c1-writing', 'Schriftlicher Ausdruck', 70, [
                    self::text('C1-WRITE-0', 'Kandidatenblätter Schreiben', "Kandidatenblätter\nSchreiben\n75 Minuten\n\nDas Modul Schreiben hat zwei Teile.\nIn Teil 1 schreiben Sie einen Diskussionsbeitrag für ein Forum.\nIn Teil 2 schreiben Sie eine Nachricht.\nSie können mit jeder Aufgabe beginnen.\nEs werden nur die Lösungen und Texte\nauf dem Antwortbogen bewertet.\nBitte schreiben Sie mit einem blauen\noder schwarzen Stift.\nHilfsmittel (z. B. Wörterbücher, Handys/\nmobile Endgeräte) sind nicht erlaubt."),
                    self::input('C1-WRITE-1', 'Teil 1 — Diskussionsbeitrag für ein Forum', 'writing', "In der Diskussion im Kurs hat Maria argumentiert, dass ein Pflichtfach für digitale Kompetenzen in der Schule wichtig ist, damit alle Schülerinnen und Schüler gleichzeitig mit dem digitalen Wandel Schritt halten können. Sie schrieb: ″Ohne digitale Grundbildung verlieren junge Menschen den Anschluss an die Arbeitswelt von morgen. Das Problem ist nicht das Internet selbst, sondern die fehlende Unterstützung durch Lehrkräfte, die selbst noch nicht ausreichend geschult wurden.″\n\nAntwort des Diskussionspartners: ″Ein Pflichtfach ist problematisch, weil viele Schülerinnen und Schüler ohnehin schon durch andere Fächer wie Politik oder Geografie kritisch mit digitalen Medien konfrontiert werden. Zudem fehlt es an ausreichend qualifiziertem Personal, um ein weiteres Fach anzubieten.″", 48, null, "Schreiben Sie einen Diskussionsbeitrag für ein Internet-Forum (ca. 120–150 Wörter).\n\nIn einem Kurs wird diskutiert, ob Schülerinnen und Schüler in der Schule ein Pflichtfach ‚Digitale Kompetenzen‘ einführen sollen. Nehmen Sie Bezug auf die Beiträge von Maria und einem anderen Kursteilnehmer. Formulieren Sie Ihre Position und nennen Sie mindestens zwei Argumente."),
                    self::input('C1-WRITE-2', 'Teil 2 — Nachricht', 'writing', '', 48, null, "Schreiben Sie eine Nachricht (ca. 50–80 Wörter) an einen Mitbewohner im Studentenwohnheim.\n\nIhr Mitbewohner hat Ihre Teekanne und Tassen immer wieder benutzt, ohne sie danach zu spülen. Bitten Sie höflich, aber bestimmt um Rücksichtnahme und erklären Sie kurz, warum Ihnen das wichtig ist."),
                ], self::pages(22, 22)),
                self::section('c1-speaking', 'Mündliche Prüfung', 16, [
                    self::speakingElement('C1-SP-1a', '1a: Präsentation', 'Präsentation (ca. 3 Minuten). Wählen Sie eines der Themen:\n1. Was ist der Nationalsport in einem Land Ihrer Wahl und welche Bedeutung hat er für die Gesellschaft?\noder\n2. Wer gehört Ihrer Meinung nach zu den einflussreichsten lebenden Personen weltweit? Wodurch zeichnet sich diese Person aus?', 6),
                    self::speakingElement('C1-SP-1b', '1b: Zusammenfassung und Anschlussfragen', 'Zusammenfassung/Anschlussfragen (ca. 2 Minuten). Hören Sie sich die KI-Präsentation zum anderen Thema an und antworten Sie darauf.', 4),
                    self::speakingElement('C1-SP-2', '2: Diskussion', 'Diskussion (6 Minuten). Diskutieren Sie das folgende Thema: Beziehungen mit großem Altersunterschied sind immer problematisch.', 6),
                    self::speakingElement('C1-SP-GLOBAL', 'Punkte für sprachliche Angemessenheit', 'Gesamtbewertung für sprachliche Angemessenheit (Flüssigkeit, Repertoire, Grammatische Richtigkeit, Aussprache/Intonation)', 32),
                ], self::pages(23, 24)),
            ],
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private static function c1Teil4Elements(): array
    {
        return [
            self::text('C1-LV4-text', 'Teil 4 — vorgeschlagene Arbeitszeit: 15 Minuten', self::encode([
                'title' => 'Privatheit in Zeiten des Internets',
                'eyebrow' => 'Zukunftsfragen unserer Gesellschaft:',
                'example' => [
                    'number' => 0,
                    'statement' => 'Das Internet bietet viele Vorteile, die gern genutzt werden.',
                    'solution' => 'a',
                ],
                'statements' => [
                    24 => 'Angriffe auf sensible Daten verursachen zunehmend finanzielle Verluste.',
                    25 => 'Reflektiertes Nutzerverhalten kann dazu beitragen, die Verbreitung von Daten einzuschränken.',
                    26 => 'Unternehmen, die nicht ausreichend für Datenschutz sorgen, werden Kundinnen und Kunden verlieren.',
                    27 => 'Dank immenser Datenmengen können Unternehmen individuelle Kundenangebote erstellen.',
                    28 => 'Inhaber von Internetseiten wollen effizient zum Schutz der Bürgerinnen und Bürger beitragen.',
                    29 => 'Trotz gegenteiliger Äußerungen gehen Nutzerinnen und Nutzer sorglos mit persönlichen Angaben um.',
                    30 => 'Kostenlose Dienstleistungen zielen auf die Gewinnung persönlicher Daten.',
                ],
                'texts' => [
                    'a' => [
                        'name' => 'Hella Brückner, Professorin für Wirtschaftspsychologie',
                        'body' => 'Wer ist denn nicht froh, dass es das Internet gibt? Es ist doch eine große Erleichterung, mit einem Klick Tickets oder Waren kaufen oder die Lieblingsserie jederzeit und ohne lästige Werbung sehen zu können. Messenger-Dienste sind aus dem Alltag nicht mehr wegzudenken, über eine Musik-App wird die aktuelle Lieblingsplaylist geteilt und dank einer lustigen Spiele-App kann man sich auch die Wartezeit an der Bushaltestelle angenehm vertreiben. Aber das Ganze hat natürlich seine Schattenseiten. Bei der Nutzung des Internets entsteht, von den meisten völlig unbemerkt, eine Zweckgemeinschaft zwischen dem privaten Nutzer oder der Nutzerin und diversen Firmen. Man erhält nichts umsonst, auch wenn es so aussieht, sondern bezahlt mit seinen Daten. Die digitalen Medien können unsere Lebensqualität fördern, das steht außer Frage. Doch nur der bewusste Umgang damit vermag den Transfer persönlicher Daten in Grenzen zu halten. Denn je selbstverständlicher das Datensammeln im Netzalltag ist, desto mehr verschwimmen die Grenzen des Privaten und desto mehr schwindet auch das Bewusstsein für einen möglichen Verlust.',
                    ],
                    'b' => [
                        'name' => 'Benedikt Janssen, Direktor des Instituts für Wirtschaftsinformatik',
                        'body' => 'Das Internet hat nicht nur Vorteile, darüber sollte man sich im Klaren sein, wenn man online einkauft oder den nächsten Urlaub über eine Webseite bucht. Täglich werden kaum vorstellbare Massen an Daten gesammelt, analysiert und für personalisierte Vertriebsaktionen verwertet. Das geht auf Kosten der Kundinnen und Kunden, denn deren Gewohnheiten, Vorlieben, Träume und Ängste sind im ökonomischen Sinne transparent und dadurch quantifizierbar geworden. Erstaunlicherweise ist das den meisten Konsumentinnen und Konsumenten ziemlich egal. Es gibt sogar einen deutlichen Unterschied zwischen Intention und realem Handeln. Obgleich sie in Umfragen beteuern, zur Preisgabe ihrer Daten nicht bereit zu sein, sieht die Sache anders aus, sobald man ihnen beim Einkauf ein paar Bonuspunkte anbietet. Meiner Ansicht nach sollten die Konsumentinnen und Konsumenten darüber aufgeklärt werden, welchen Zwecken sie ungewollt durch die Teilnahme beispielsweise an Rabattprogrammen dienen.',
                    ],
                    'c' => [
                        'name' => 'Leopold Nowak, Professor für Öffentliches Recht',
                        'body' => 'Die Lebenswirklichkeit vieler Menschen verlagert sich mehr und mehr in die digitale Welt. Unsere Persönlichkeitsrechte müssen daher besser gesetzlich verankert werden. Hier besteht ein massiver Reformbedarf. Es geht darum, Daten und damit auch die Identität der Bürgerinnen und Bürger besser zu schützen. Denn man muss sich klar machen: Viele Menschen geben überhaupt nicht Acht darauf, wohin ihre Daten schließlich wandern, und ignorieren das Risiko, dass diese missbraucht werden könnten. Das Bundeskriminalamt veröffentlichte jüngst eine Studie, die zeigt, dass Kriminelle mit falschen Identitäten immer öfter an das Geld argloser Internetnutzerinnen und -nutzer gelangen. Den Kopf in den Sand zu stecken, ist also für Konsumentinnen und Konsumenten keine Lösung. Die Politik wird langsam aktiv und drängt Unternehmen dazu, bei ihrem Online-Angebot effektive Sicherheitsvorkehrungen zu treffen. Das ist dringend nötig. Und endlich ein wichtiger Schritt in die richtige Richtung.',
                    ],
                ],
            ], JSON_UNESCAPED_UNICODE), 'Sie lesen in einer Fachzeitschrift Beiträge von Wissenschaftlerinnen und Wissenschaftlern. Wählen Sie bei jeder Aussage: Wer äußert das? Zwei Aussagen passen nicht. Markieren Sie in diesem Fall 0.'),
            self::input('C1-LV4-24', 'Aufgabe 24', 'matching', 'c', 2, 'a,b,c,0'),
            self::input('C1-LV4-25', 'Aufgabe 25', 'matching', 'a', 2, 'a,b,c,0'),
            self::input('C1-LV4-26', 'Aufgabe 26', 'matching', 'c', 2, 'a,b,c,0'),
            self::input('C1-LV4-27', 'Aufgabe 27', 'matching', 'b', 2, 'a,b,c,0'),
            self::input('C1-LV4-28', 'Aufgabe 28', 'matching', '0', 2, 'a,b,c,0'),
            self::input('C1-LV4-29', 'Aufgabe 29', 'matching', 'b', 2, 'a,b,c,0'),
            self::input('C1-LV4-30', 'Aufgabe 30', 'matching', 'a', 2, 'a,b,c,0'),
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
     * @param  array<mixed>  $data
     */
    private static function encode(array $data, int $flags = 0): string
    {
        $json = json_encode($data, $flags);

        if ($json === false) {
            throw new \RuntimeException('Failed to encode JSON: '.json_last_error_msg());
        }

        return $json;
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
            fn (int $page): string => sprintf('/goethe/c1/page-%02d.png', $page),
            range($first, $last),
        );
    }

    /**
     * @return array<string, mixed>
     */
    private static function goetheB2(): array
    {
        return [
            'title' => 'Goethe Zertifikat B2',
            'level' => 'B2',
            'source_pdf' => 'goethe_b2_lesen.pdf',
            'theme' => self::theme('#a3a3a3', '#1c1917', 'Instrument Sans'),
            'timings' => [
                ['label' => 'Lesen', 'minutes' => 65],
            ],
            'sections' => [
                [
                    'id' => 'goethe-b2-lesen-intro',
                    'title' => 'Kandidatenblätter - Lesen',
                    'minutes' => 65,
                    'layout_style' => 'default',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-lesen-intro-text',
                            'type' => 'text',
                            'label' => 'Kandidatenblätter Lesen',
                            'content' => "Das Modul Lesen hat fünf Teile.\nSie lesen mehrere Texte und lösen Aufgaben dazu. Sie können mit jeder Aufgabe beginnen. Für jede Aufgabe gibt es nur eine richtige Lösung.\n\nEs werden nur die Lösungen auf dem Antwortbogen bewertet.\n\nBitte schreiben Sie mit einem blauen oder schwarzen Stift.\n\nHilfsmittel (z. B. Wörterbücher, Handys/mobile Endgeräte) sind nicht erlaubt.",
                            'instruction' => '65 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b2-lesen-teil1',
                    'title' => 'Lesen Teil 1',
                    'minutes' => 18,
                    'layout_style' => 'goethe_b2_teil1',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-l1-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung und Aufgaben',
                            'content' => "Sie lesen in einem Forum, wie Menschen über Minimalismus im täglichen Leben denken.\nAuf welche der vier Personen treffen die einzelnen Aussagen zu? Die Personen können mehrmals gewählt werden.\n\nBeispiel:\n0 Wer will nur die wirklich notwendigen Dinge haben? Lösung: a",
                            'instruction' => 'vorgeschlagene Arbeitszeit: 18 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l1-person-a',
                            'type' => 'goethe_portrait',
                            'label' => 'a Erik',
                            'content' => 'In der heutigen Gesellschaft wird leider nur noch konsumiert. Es zählen Besitz und Leistung und das im Überfluss; das ist den Menschen wichtig. Ich sehe das anders, denn ich habe nur Sachen, die ich wirklich brauche. Ein Autokauf käme mir zum Beispiel nicht in den Sinn, ich bevorzuge das Rad oder gehe zu Fuß. Eine größere Wohnung? Warum? - Meine Einzimmerwohnung ist fast leer: Bett, Tisch, zwei Stühle, Garderobe. Einen Kühlschrank brauche ich nicht. Auch als Veganer kann man genussvoll essen. Erdbeeren und Salat pflanze ich auf dem Balkon an. Wenn ich reise, dann mit Rucksack und Zelt, ohne Kamera und Schnickschnack. Bei Freunden daheim mit tollen Fotos angeben gibt mir nichts. Das hat nichts mit Geiz zu tun - das einfache und entspannte Leben ist, was ich will.',
                            'image_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&q=80&w=256&h=256',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l1-person-b',
                            'type' => 'goethe_portrait',
                            'label' => 'b Katharina',
                            'content' => 'Die gesamte Debatte über die richtige Lebensweise nervt mich: Warum auf etwas verzichten? Dinge können die schönste Nebensache der Welt sein! Am Abend kehre ich gern in meine Wohnung heim und am Wochenende lade ich lieber Freunde ein, um ihnen Bilder von meinem letzten Urlaub zu zeigen, als mich beim Sport abzumühen. Viel brauche ich nicht, um mich in den Großstädten der Welt wohlzufühlen: ein bisschen Luxus im Hotel, interessante Ausstellungen und schick essen gehen. Viel nehme ich schon deswegen nicht mit, damit im Koffer genug Platz für die Einkäufe ist. Zu einem guten Steak sage ich nie nein. Gesund kann, muss Essen aber nicht sein - man lebt schließlich nur einmal. Mein Auto, ein Cabriolet, nutze ich jeden Tag beruflich, im Sommer am liebsten mit dem Dach offen. Bescheidenheit ist doch nur etwas für die Leute, die sich nichts leisten können.',
                            'image_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&q=80&w=256&h=256',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l1-person-c',
                            'type' => 'goethe_portrait',
                            'label' => 'c Franzi',
                            'content' => 'Für mich ist es wichtig, sowohl gut zu mir als auch zu meiner Umwelt zu sein. Im Alltag versuche ich auf Fleisch zu verzichten und baue Kräuter am Fenster meines kleinen, aber feinen Appartements an. Ich genieße es, am Wochenende mit Freunden gut essen zu gehen, und wenn es mal nichts Vegetarisches gibt, esse ich ab und zu auch Fleisch. Zur Arbeit nehme ich am liebsten das Rad - das hält mich fit und ist nebenbei auch noch umweltfreundlich; bei Schnee und Regen sind dann aber doch die Öffentlichen angenehmer. Vielleicht mache ich irgendwann mal Carsharing, also Autos am Straßenrand mieten. Mit Geld hat das alles aber nichts zu tun. Es besteht für mich ein großer Unterschied zwischen Urlaub und Reisen: Im Urlaub gönne ich mir gerne etwas: Ein Sterne-Hotel oder einen Einkaufstrip. Auf Reisen jedoch will ich Neues entdecken. Da erkunde ich Länder am liebsten auf Wanderungen.',
                            'image_url' => 'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?auto=format&fit=crop&q=80&w=256&h=256',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l1-person-d',
                            'type' => 'goethe_portrait',
                            'label' => 'd Nils',
                            'content' => 'Ich halte das für eine ziemlich deutsche Debatte! In keinem anderen Land wird so viel über das Thema „Richtig leben“ diskutiert. Geld ist nicht alles, aber kann nicht schaden; z. B. für Bio-Produkte: Ich selbst kaufe Fleisch und Wurst auf dem Bauernhof ein, nicht nur der Natur und der Gesundheit zuliebe, es schmeckt einfach besser, und viel Fleisch esse ich sowieso nicht. Der Versuch, einfach und natürlich zu leben, ist bei der Ernährung leider nicht immer kostengünstig. Das Thema „Auto“ sehe ich eher nicht ideologisch: Für mich ist es in erster Linie ein Fortbewegungsmittel, das manchmal notwendig, manchmal auch einfach nur bequem. Wegen des Verkehrs benutze ich oft die Öffentlichen oder das Rad. Ich habe eine Zwei-Zimmer-Wohnung in der Innenstadt und zwar in Flussnähe, der Blick aufs Wasser ist mir wichtig. Für meine Reisen habe ich ein Wohnmobil mit allem Luxus. Da ist man völlig frei und hat doch immer alles Nötige dabei!',
                            'image_url' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&q=80&w=256&h=256',
                            'answer' => '',
                            'points' => 0,
                        ],
                        ['id' => 'goethe-b2-l1-1', 'type' => 'matching', 'label' => 'Aufgabe 1', 'instruction' => 'Wer sieht im Auto einen Gebrauchsgegenstand?', 'answer' => 'd', 'points' => 1, 'options' => 'a,b,c,d'],
                        ['id' => 'goethe-b2-l1-2', 'type' => 'matching', 'label' => 'Aufgabe 2', 'instruction' => 'Wer empfindet Besitz als unwichtig?', 'answer' => 'a', 'points' => 1, 'options' => 'a,b,c,d'],
                        ['id' => 'goethe-b2-l1-3', 'type' => 'matching', 'label' => 'Aufgabe 3', 'instruction' => 'Wer verzichtet auf Fleisch?', 'answer' => 'a', 'points' => 1, 'options' => 'a,b,c,d'],
                        ['id' => 'goethe-b2-l1-4', 'type' => 'matching', 'label' => 'Aufgabe 4', 'instruction' => 'Für wen spielt die Lage der Wohnung eine große Rolle?', 'answer' => 'd', 'points' => 1, 'options' => 'a,b,c,d'],
                        ['id' => 'goethe-b2-l1-5', 'type' => 'matching', 'label' => 'Aufgabe 5', 'instruction' => 'Für wen ist der Naturschutz bei Entscheidungen wichtig?', 'answer' => 'c', 'points' => 1, 'options' => 'a,b,c,d'],
                        ['id' => 'goethe-b2-l1-6', 'type' => 'matching', 'label' => 'Aufgabe 6', 'instruction' => 'Wer denkt, einfach lebende Personen hätten zu wenig Geld?', 'answer' => 'b', 'points' => 1, 'options' => 'a,b,c,d'],
                        ['id' => 'goethe-b2-l1-7', 'type' => 'matching', 'label' => 'Aufgabe 7', 'instruction' => 'Wer nimmt auf Reisen nichts Überflüssiges mit?', 'answer' => 'a', 'points' => 1, 'options' => 'a,b,c,d'],
                        ['id' => 'goethe-b2-l1-8', 'type' => 'matching', 'label' => 'Aufgabe 8', 'instruction' => 'Für wen zählt beim Essen allein, etwas zu genießen?', 'answer' => 'b', 'points' => 1, 'options' => 'a,b,c,d'],
                        ['id' => 'goethe-b2-l1-9', 'type' => 'matching', 'label' => 'Aufgabe 9', 'instruction' => 'Wer mag sowohl Luxus als auch das einfache Leben?', 'answer' => 'c', 'points' => 1, 'options' => 'a,b,c,d'],
                    ],
                ],
                [
                    'id' => 'goethe-b2-lesen-teil2',
                    'title' => 'Lesen Teil 2',
                    'minutes' => 12,
                    'layout_style' => 'goethe_b2_teil2',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-l2-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Sie lesen in einer Zeitschrift einen Artikel über die Geschichte des Reisens.\nWelche Sätze passen in die Lücken? Zwei Sätze passen nicht.",
                            'instruction' => 'vorgeschlagene Arbeitszeit: 12 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l2-image',
                            'type' => 'image',
                            'label' => 'Artikel Bild',
                            'content' => 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&q=80&w=1200&h=400',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l2-inline',
                            'type' => 'inline_gap',
                            'label' => 'Reisefieber Text',
                            'content' => "Über eine Milliarde Menschen reisen jährlich in ein anderes Land. Ob von Deutschland nach Italien, von Kanada nach Mexiko oder von Japan nach Australien. Noch nie waren so viele Touristinnen und Touristen unterwegs. Kaum ein Wirtschaftszweig wächst so schnell und kontinuierlich wie der Tourismus. --0--\n\nSchon in der Antike gingen Menschen an andere Orte. Oft zu Fuß oder mit dem Pferd. Das dauerte jedoch lange und war sehr mühsam. Nur wenige konnten es sich leisten, in einer Kutsche zu reisen. Besonders schnell war die Kutsche allerdings auch nicht. --10-- Erst als die Eisenbahn und das Dampfschiff erfunden wurden, änderte sich das.\n\nDie Reisen, wie wir sie heute kennen, verdanken wir vor allem dem Briten Thomas Cook. Er organisierte 1841 eine Zugfahrt mit Blasmusik, Tee und belegten Broten in eine 20 km entfernte Kleinstadt. --11-- Damit erfand der Brite die erste Pauschalreise der Welt – ein großer Erfolg.\n\nDie meisten solcher Reisen blieben jedoch lange ein Luxus, den sich die meisten Menschen nicht leisten konnten. An sechs Tagen der Woche und zehn Stunden am Tag zu arbeiten, war um 1900 normal. --12-- Dies änderte sich im Laufe des 20. Jahrhunderts, als die Arbeiter mehr Freizeit bekamen, die Löhne stiegen und die Reisekosten sanken. Seitdem steigt die Anzahl der Touristen stetig.\n\nDie derzeit beliebtesten Reiseziele weltweit sind Frankreich, die USA und Spanien. Aber auch Deutschland zieht immer mehr Reisende an. Insbesondere die 15- bis 24-jährigen Europäerinnen und Europäer kommen gerne nach Deutschland. Am liebsten fahren sie in die großen Städte wie Berlin oder München. --13-- Ein Grund dafür, dass Städtereisen insgesamt immer beliebter werden.\n\nDer Tourismus hat das Aussehen vieler Orte verändert. --14-- So entstand das typische Bild, das man heute an vielen Orten sieht, die touristisch geprägt sind: blaues Meer, Sandstrand und Hochhäuser. Auch die Städte verändern sich. Cafés und Restaurants werden immer teurer. --15-- Vor allem Anwohnerinnen und Anwohner ärgern sich darüber. Aber auch Touristinnen und Touristen suchen immer häufiger nach Orten, die weniger besucht werden.",
                            'options' => json_encode([
                                0 => ['options' => '0|0', 'answer' => '0'],
                                10 => ['options' => 'a|a,b|b,c|c,d|d,e|e,f|f,g|g,h|h', 'answer' => 'c'],
                                11 => ['options' => 'a|a,b|b,c|c,d|d,e|e,f|f,g|g,h|h', 'answer' => 'd'],
                                12 => ['options' => 'a|a,b|b,c|c,d|d,e|e,f|f,g|g,h|h', 'answer' => 'f'],
                                13 => ['options' => 'a|a,b|b,c|c,d|d,e|e,f|f,g|g,h|h', 'answer' => 'a'],
                                14 => ['options' => 'a|a,b|b,c|c,d|d,e|e,f|f,g|g,h|h', 'answer' => 'e'],
                                15 => ['options' => 'a|a,b|b,c|c,d|d,e|e,f|f,g|g,h|h', 'answer' => 'g'],
                            ]),
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b2-l2-sentences',
                            'type' => 'text',
                            'label' => 'Sätze zur Auswahl',
                            'content' => "0 Denn immer mehr Menschen können es sich leisten zu verreisen.\n\na Dort gibt es neben den vielen Bars und Clubs auch viele gute Kulturangebote.\nb Dennoch fahren immer mehr Touristinnen und Touristen aufs Land.\nc Noch Ende des 18. Jahrhunderts war man von München nach Frankfurt 74 Stunden unterwegs.\nd Alle Leistungen gab es zu einem geringen Gesamtpreis.\ne Hotels und Häuserblocks mit Urlaubswohnungen prägen heute vielerorts das Bild.\nf Es gab nur drei Tage Urlaub im Jahr.\ng Diese Veränderungen gefallen natürlich nicht jedem.\nh Trotzdem hatten die Arbeiter nur wenig Freizeit.",
                            'answer' => '',
                            'points' => 0,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b2-lesen-teil3',
                    'title' => 'Lesen Teil 3',
                    'minutes' => 12,
                    'layout_style' => 'goethe_b2_teil3',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-l3-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Sie lesen in einer Zeitung einen Artikel über das Verhalten von Kranken in Zeiten des Internets.\nWählen Sie bei jeder Aufgabe die richtige Lösung.",
                            'instruction' => 'vorgeschlagene Arbeitszeit: 12 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l3-image',
                            'type' => 'image',
                            'label' => 'Artikel Bild',
                            'content' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&q=80&w=1200&h=400',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l3-article',
                            'type' => 'text',
                            'label' => 'Doktor Google',
                            'instruction' => 'Das Wartezimmer in Arztpraxen ist voll mit Menschen, Bakterien und verbrauchter Luft. Bevor sich jemand zusätzliche Viren ins Gesicht husten lässt, bleibt er manchmal lieber zu Hause und sucht Rat beim Doktor im Netz.',
                            'content' => "Von den Deutschen suchen 38 Prozent bei Gesundheitsfragen Rat im Internet und klicken Gesundheitsportale an, fand man kürzlich heraus. Innerhalb Europas war das Interesse nur in Schweden, Norwegen und in Finnland noch größer. Laut einer Studie gehört zu den meistgesuchten Krankheiten Diabetes, woran so viele Europäerinnen und Europäer leiden. Ebenfalls oft gesucht: Leiden, über die viele nicht gerne sprechen, auch nicht mit dem Arzt.\n\nJe mehr das Googeln von Krankheiten zum Volkssport wird, desto stärker stellt sich für Politiker und Mediziner die Frage nach der Qualität des Angebots. Kann sein, dass dort Hilfe wartet. Kann aber auch sein, dass die Beschwerden sich nach der Lektüre schlimmer anfühlen als vorher.\n\nDenn die Qualität ist in vielen Fällen richtig schlecht: Es gibt häufig lückenhafte Informationen und Widersprüche. Manchmal dienen die Texte und Bilder vor allem dazu, für ein Medikament oder eine Heilmethode zu werben. Wer sich stundenlang durch die Ergebnislisten klickt, landet auf Seiten von Krankenkassen, Vereinen, Pharmaunternehmen, Verlagen, Medizinern, Hobbyratgebern.\n\nDie organisierte Ärzteschaft ist keineswegs grundsätzlich dagegen, wenn sich Patienten online schlau machen. „Die Frage ist nur, wann sie es tun und wo sie suchen“, sagt Claudia Becker vom Institut „Medizinisches Zentrum für therapeutische Qualität“. Dort hält man es für falsch, Krankheitssymptome, die man bei sich entdeckt hat, per Suchmaschine selbst zu diagnostizieren. „Wenn Patienten richtige und gute Informationen haben, vereinfacht das vieles.“ Ziel ist dabei nicht das Verständnis fachlicher Details. „Aber Patienten können Ärzte später informiert fragen – und genau darum geht es.“\n\nWer Informationen unkritisch aufnimmt, für den bringt das eher Gefahren statt Orientierung mit sich: Nicht wenige Versprechen auf Heilung sind Betrug. Auch Internetseiten und -foren, die Patienten über Selbsthilfe informieren, sollten mit Vorsicht gelesen werden. „Es ist natürlich vorteilhaft, wenn Ratsuchende anonym von den Erfahrungen ebenfalls Betroffener profitieren können. Andererseits wissen sie nie, mit wem sie sich da gerade über hochsensible Dinge unterhalten.“\n\nEine gewisse Gefahr bestehe vor allem dann, wenn in Foren persönliche Daten wie E-Mail-Adressen, Telefonnummern oder Krankengeschichten abgefragt würden: „Immer wieder kommt es in Patientenforen vor, dass das Gespräch mit anderen Kranken insgeheim von Dritten benutzt wird. Oft wird man danach pausenlos mit unerwünschter Werbung der Gesundheits-Industrie bombardiert.“",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l3-16',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 16',
                            'instruction' => 'Das Interesse richtet sich auf Krankheiten, ..',
                            'answer' => 'c',
                            'options' => 'a|die auch Ärzte überfordern.,b|die besonders schmerzhaft sind.,c|für die sich die Betroffenen schämen.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b2-l3-17',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 17',
                            'instruction' => 'Viele Mediziner und Politiker zweifeln daran, dass ..',
                            'answer' => 'a',
                            'options' => 'a|das Internet brauchbare Hilfe liefert.,b|die berichteten Beschwerden echt sind.,c|sich die Gesundheit der Kranken verbessert.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b2-l3-18',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 18',
                            'instruction' => 'Was ist am Internet-Angebot oft so schlecht?',
                            'answer' => 'c',
                            'options' => 'a|Das Angebot ist zu groß.,b|Es gibt falsche Informationen.,c|Viele Informationen sind unvollständig.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b2-l3-19',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 19',
                            'instruction' => 'Claudia Becker findet, Patienten sollten sich im Internet ..',
                            'answer' => 'a',
                            'options' => 'a|auf das Arztgespräch vorbereiten.,b|detailliertes Fachwissen anlesen.,c|nach einem Arztgespräch informieren.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b2-l3-20',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 20',
                            'instruction' => 'Claudia Becker warnt Patienten vor ..',
                            'answer' => 'a',
                            'options' => 'a|gefährlichen Versprechen im Internet.,b|Anleitungen zur Selbsttherapie.,c|Kontakt mit anonymen Ratsuchenden.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b2-l3-21',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 21',
                            'instruction' => 'Ratsuchende sollten vorsichtig sein, in Foren ..',
                            'answer' => 'b',
                            'options' => 'a|ihre Krankheit zu beschreiben.,b|Informationen über sich weiterzugeben.,c|auf Werbung der Gesundheitsindustrie zu reagieren.',
                            'points' => 1,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b2-lesen-teil4',
                    'title' => 'Lesen Teil 4',
                    'minutes' => 12,
                    'layout_style' => 'goethe_b2_teil4',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-l4-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Sie lesen in einer Zeitschrift Meinungsäußerungen zu dem Lebensmodell „digitale Nomaden“.\nWelche Äußerung passt zu welcher Überschrift? Eine Äußerung passt nicht. Die Äußerung a ist das Beispiel und kann nicht noch einmal verwendet werden.",
                            'instruction' => 'vorgeschlagene Arbeitszeit: 12 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l4-opinion-a',
                            'type' => 'goethe_portrait',
                            'label' => 'a Amelie, Bonn',
                            'content' => 'Es hört sich im ersten Moment vielleicht etwas überraschend an - aber gerade für berufstätige Eltern hat das digitale Nomadentum Pluspunkte: Man kann seinen Kindern die Welt zeigen und sich tagsüber die Zeit für sie nehmen, die sie brauchen; gearbeitet wird dann eben nachts.',
                            'image_url' => '',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l4-opinion-b',
                            'type' => 'goethe_portrait',
                            'label' => 'b Eva, Berlin',
                            'content' => 'Die Gefahr, dass die eigene Leistung absinkt, ist einfach zu groß: Jede Arbeit braucht Struktur und das Gespräch mit Experten, um herauszufinden, ob man richtig liegt. Wenn jeder immer nur allein vor sich hin arbeitet, fehlt der Vergleich.',
                            'image_url' => '',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l4-opinion-c',
                            'type' => 'goethe_portrait',
                            'label' => 'c Inga, Hannover',
                            'content' => 'Auch wenn es unter den Jüngeren heutzutage Mode ist, sich als digitale Nomaden zu verstehen - wieso möchte keiner mehr fest an einem Ort verankert sein? Woher weiß man denn sonst, wohin man gehört?',
                            'image_url' => '',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l4-opinion-d',
                            'type' => 'goethe_portrait',
                            'label' => 'd Steven, Greifswald',
                            'content' => 'Kaum jemand kann und will ständig allein am Computer vor sich hinarbeiten. Das Arbeiten in flexiblen Büros wäre eine Möglichkeit, einen ständigen Austausch möglich zu machen, fachlich wie menschlich. Aber man würde dabei hoffentlich nicht die Freiheit verlieren, weiterzuziehen, wenn und wann man will.',
                            'image_url' => '',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l4-opinion-e',
                            'type' => 'goethe_portrait',
                            'label' => 'e Jan, Chemnitz',
                            'content' => 'Diese Arbeitsform funktioniert nur ohne Familie und Verantwortung. Wer hat schon einen Partner, der ständig umziehen kann und will, weil er selbst digitaler Nomade ist? Und sobald eine gute Schulbildung für den Nachwuchs gesichert werden muss, hat dieses Arbeitsmodell mehr Nachteile als Vorteile.',
                            'image_url' => '',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l4-opinion-f',
                            'type' => 'goethe_portrait',
                            'label' => 'f Sarah, München',
                            'content' => 'Es darf nicht vergessen werden, dass auch das Berufsleben aus mehr als nur Arbeit besteht: Mit wem wird die Mittagspause verbracht? Wer hat schon Lust, immer allein zu sein? Wer hat schon die Kraft, sich alle paar Wochen einen neuen Freundeskreis aufzubauen?',
                            'image_url' => '',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l4-opinion-g',
                            'type' => 'goethe_portrait',
                            'label' => 'g Janice, Magdeburg',
                            'content' => 'Zeit- und raumflexibel zu arbeiten, wo und wann auch immer man will - vom Laptop, Smartphone oder Tablet aus -, nennt man digitales Nomadentum. Das mag für manche zwar etwas traurig klingen, nach Verlust der Heimat, aber es ist das einzige dem 21. Jahrhundert angemessene Arbeitsmodell.',
                            'image_url' => '',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l4-opinion-h',
                            'type' => 'goethe_portrait',
                            'label' => 'h Katharina, Stuttgart',
                            'content' => 'Bei allen Vorzügen besteht doch die ständige Angst, zu viel zu reisen und zu wenig zu schaffen. Es ist letztlich alles eine Frage des Charakters: Um sein eigener Chef zu sein, ist auf jeden Fall ein hohes Maß an Selbstorganisation und Selbstdisziplin notwendig.',
                            'image_url' => '',
                            'answer' => '',
                            'points' => 0,
                        ],
                        ['id' => 'goethe-b2-l4-22', 'type' => 'matching', 'label' => 'Aufgabe 22', 'instruction' => 'Sich zu Hause zu fühlen, ist wichtig', 'answer' => 'c', 'points' => 1, 'options' => 'a,b,c,d,e,f,g,h'],
                        ['id' => 'goethe-b2-l4-23', 'type' => 'matching', 'label' => 'Aufgabe 23', 'instruction' => 'Moderne Arbeitsgeräte ermöglichen neue Arbeitsformen und Lebensweisen', 'answer' => 'g', 'points' => 1, 'options' => 'a,b,c,d,e,f,g,h'],
                        ['id' => 'goethe-b2-l4-24', 'type' => 'matching', 'label' => 'Aufgabe 24', 'instruction' => 'Arbeiten ohne einen Vorgesetzten bringt Herausforderungen', 'answer' => 'h', 'points' => 1, 'options' => 'a,b,c,d,e,f,g,h'],
                        ['id' => 'goethe-b2-l4-25', 'type' => 'matching', 'label' => 'Aufgabe 25', 'instruction' => 'Leben als digitaler Nomade eignet sich besonders für Singles', 'answer' => 'e', 'points' => 1, 'options' => 'a,b,c,d,e,f,g,h'],
                        ['id' => 'goethe-b2-l4-26', 'type' => 'matching', 'label' => 'Aufgabe 26', 'instruction' => 'Wichtig ist der Austausch mit Kollegen auch außerhalb der Arbeit', 'answer' => 'f', 'points' => 1, 'options' => 'a,b,c,d,e,f,g,h'],
                        ['id' => 'goethe-b2-l4-27', 'type' => 'matching', 'label' => 'Aufgabe 27', 'instruction' => 'Qualitätssteigerung durch den Austausch unter Kollegen', 'answer' => 'b', 'points' => 1, 'options' => 'a,b,c,d,e,f,g,h'],
                    ],
                ],
                [
                    'id' => 'goethe-b2-lesen-teil5',
                    'title' => 'Lesen Teil 5',
                    'minutes' => 6,
                    'layout_style' => 'goethe_b2_teil5',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-l5-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Sie möchten an der Universität Bremen studieren und lesen die Studienordnung.\nWelche der Überschriften aus dem Inhaltsverzeichnis passen zu den Paragrafen? Vier Überschriften werden nicht gebraucht.",
                            'instruction' => 'vorgeschlagene Arbeitszeit: 6 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l5-table-of-contents',
                            'type' => 'text',
                            'label' => 'Inhaltsverzeichnis',
                            'content' => "a Abschluss des Bachelorstudiums\nb Aufbau und Inhalte des Studiums\nc Studienbeginn\nd Studiendauer und Studienvolumen\ne Vermittlungsformen\nf Zugangsvoraussetzungen\ng Studienziele\nh Auslandsaufenthalt",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-l5-28',
                            'type' => 'matching',
                            'label' => 'Aufgabe 28',
                            'instruction' => '§ 28',
                            'content' => "Die allgemeine Qualifikation für das Studium wird durch ein Zeugnis der Hochschulzugangsberechtigung gemäß § 17 BremHG (insbesondere allgemeine Hochschulreife, fachgebundene Hochschulreife, Fachhochschulreife) oder ein durch Rechtsvorschrift oder von der zuständigen staatlichen Stelle als gleichwertig anerkanntes Zeugnis nachgewiesen.\n\nFachspezifisch:\n- der Nachweis von Kenntnissen in Englisch (Gemeinsamer europäischer Referenzrahmen, Stufe B2) und einer weiteren modernen Fremdsprache (Gemeinsamer europäischer Referenzrahmen, Stufe B1) oder\n- der Nachweis von Kenntnissen in Englisch (Gemeinsamer europäischer Referenzrahmen, Stufe B2) und Lateinkenntnissen jeweils vor Studienbeginn",
                            'answer' => 'f',
                            'options' => 'a,b,c,d,e,f,g,h',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b2-l5-29',
                            'type' => 'matching',
                            'label' => 'Aufgabe 29',
                            'instruction' => '§ 29',
                            'content' => "(1) Die Regelstudienzeit umfasst sechs Semester. Der Gesamtumfang des studentischen Arbeitsaufwandes für das Bachelorstudium Theaterwissenschaft beträgt 180 Leistungspunkte.\n(2) Das Studium kann auch als Teilzeitstudium betrieben werden. Im Falle eines Teilzeitstudiums verringert sich der studentische Arbeitsaufwand pro Jahr entsprechend dem Anteil des Teilzeitstudiums. Die Regelstudienzeit verlängert sich entsprechend. Der Prüfungsausschuss entscheidet auf Antrag der/des Studierenden über den Anteil des Teilzeitstudiums.",
                            'answer' => 'd',
                            'options' => 'a,b,c,d,e,f,g,h',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b2-l5-30',
                            'type' => 'matching',
                            'label' => 'Aufgabe 30',
                            'instruction' => '§ 30',
                            'content' => "- Vorlesungen (V)\n- Übungen (Ü)\n- Tutorien (Tut)\n- Seminare (S)\n- Praktika (P)\nDie Modulverantwortlichen können festlegen, dass eine Lernplattform begleitend zum Präsenzstudium für die Vermittlung von Lehrinhalten eingesetzt wird.",
                            'answer' => 'e',
                            'options' => 'a,b,c,d,e,f,g,h',
                            'points' => 1,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b2-hoeren-intro',
                    'title' => 'Kandidatenblätter - Hören',
                    'minutes' => 40,
                    'layout_style' => 'goethe_b2_hoeren_intro',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-hoeren-intro-text',
                            'type' => 'text',
                            'label' => 'Kandidatenblätter Hören',
                            'content' => "Das Modul Hören hat vier Teile.\nSie hören mehrere Texte und lösen Aufgaben dazu. Lesen Sie jeweils zuerst die Aufgaben und hören Sie dann den Text dazu. Für jede Aufgabe gibt es nur eine richtige Lösung.\n\nNach dem Hören haben Sie fünf Minuten Zeit, Ihre Lösungen auf den Antwortbogen zu übertragen. Es werden nur die Lösungen auf dem Antwortbogen bewertet.\n\nBitte schreiben Sie mit einem blauen oder schwarzen Stift.\n\nHilfsmittel (z. B. Wörterbücher, Handys/mobile Endgeräte) sind nicht erlaubt.",
                            'instruction' => 'circa 40 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b2-hoeren-teil1',
                    'title' => 'Hören Teil 1',
                    'minutes' => 10,
                    'layout_style' => 'goethe_b2_hoeren_teil1',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-h1-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Sie hören fünf Gespräche und Äußerungen. Sie hören jeden Text einmal. Zu jedem Text lösen Sie zwei Aufgaben. Wählen Sie bei jeder Aufgabe die richtige Lösung.\nLesen Sie jetzt das Beispiel. Dazu haben Sie 15 Sekunden Zeit.",
                            'answer' => '',
                            'points' => 0,
                        ],
                        ['id' => 'goethe-b2-h1-1', 'type' => 'matching', 'label' => 'Aufgabe 1', 'instruction' => 'Die Frau spricht darüber, warum sie sich für Literatur interessiert.', 'answer' => 'richtig', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b2-h1-2', 'type' => 'multiple_choice', 'label' => 'Aufgabe 2', 'instruction' => 'Welche Meinung hat die Frau über das Lesen?', 'answer' => 'a', 'options' => 'a|Zum Romanlesen braucht sie ihre Fantasie.,b|Sie hat oft zu wenig Zeit, sich eigene Gedanken zu machen.,c|Was sie gelesen hat, bleibt nicht lang im Gedächtnis.', 'points' => 1],
                        ['id' => 'goethe-b2-h1-3', 'type' => 'matching', 'label' => 'Aufgabe 3', 'instruction' => 'Die Frau berichtet über die Zahlungsmoral in vielen Ländern.', 'answer' => 'falsch', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b2-h1-4', 'type' => 'multiple_choice', 'label' => 'Aufgabe 4', 'instruction' => 'Was wird immer seltener gemacht?', 'answer' => 'c', 'options' => 'a|Im Internet eingekauft.,b|Geld gewechselt.,c|Mit Bargeld bezahlt.', 'points' => 1],
                        ['id' => 'goethe-b2-h1-5', 'type' => 'matching', 'label' => 'Aufgabe 5', 'instruction' => 'Die Frau hat ein Praktikum bei einem Gericht gemacht.', 'answer' => 'falsch', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b2-h1-6', 'type' => 'multiple_choice', 'label' => 'Aufgabe 6', 'instruction' => 'Die Frau fand ihre Aufgaben ..', 'answer' => 'b', 'options' => 'a|wie zum Beispiel Kaffee kochen unangenehm.,b|nützlich für ihr Studium.,c|für ihre berufliche Tätigkeit sehr hilfreich.', 'points' => 1],
                        ['id' => 'goethe-b2-h1-7', 'type' => 'matching', 'label' => 'Aufgabe 7', 'instruction' => 'Der Mann berichtet über Sicherheit im Straßenverkehr.', 'answer' => 'richtig', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b2-h1-8', 'type' => 'multiple_choice', 'label' => 'Aufgabe 8', 'instruction' => 'Nummernschilder bei Fahrrädern ...', 'answer' => 'a', 'options' => 'a|lehnen Experten ab.,b|halten Experten für eine gute Idee.,c|möchten Experten ausprobieren.', 'points' => 1],
                        ['id' => 'goethe-b2-h1-9', 'type' => 'matching', 'label' => 'Aufgabe 9', 'instruction' => 'Die beiden Freunde unterhalten sich über einen Professor.', 'answer' => 'falsch', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b2-h1-10', 'type' => 'multiple_choice', 'label' => 'Aufgabe 10', 'instruction' => 'Die beiden Freunde brauchen noch ein Thema für ...', 'answer' => 'a', 'options' => 'a|eine Präsentation.,b|einen Aufsatz.,c|eine Seminararbeit.', 'points' => 1],
                    ],
                ],
                [
                    'id' => 'goethe-b2-hoeren-teil2',
                    'title' => 'Hören Teil 2',
                    'minutes' => 10,
                    'layout_style' => 'goethe_b2_hoeren_teil2',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-h2-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Sie hören im Radio ein Interview mit einer Persönlichkeit aus der Wissenschaft. Sie hören den Text zweimal. Wählen Sie bei jeder Aufgabe die richtige Lösung.\nLesen Sie jetzt die Aufgaben 11 bis 16. Dazu haben Sie 90 Sekunden Zeit.",
                            'answer' => '',
                            'points' => 0,
                        ],
                        ['id' => 'goethe-b2-h2-11', 'type' => 'multiple_choice', 'label' => 'Aufgabe 11', 'instruction' => 'Frau Neuhaus findet es für unsere zukünftige Ernährung wichtig, ..', 'answer' => 'a', 'options' => 'a|an die Konsequenzen unserer Entscheidung zu denken.,b|uns mit Fleisch und Fisch zu ernähren.,c|mehr Erbsen und Sojabohnen zu essen.', 'points' => 1],
                        ['id' => 'goethe-b2-h2-12', 'type' => 'multiple_choice', 'label' => 'Aufgabe 12', 'instruction' => 'Was ist die Ursache für wachsenden Fleischkonsum?', 'answer' => 'c', 'options' => 'a|Die Fleischproduktion ist gestiegen.,b|Die Weltbevölkerung ist gewachsen.,c|Mehr Menschen haben genug Geld für Fleisch.', 'points' => 1],
                        ['id' => 'goethe-b2-h2-13', 'type' => 'multiple_choice', 'label' => 'Aufgabe 13', 'instruction' => 'Warum sehen viele in Insekten eine Alternative zu Fleisch?', 'answer' => 'b', 'options' => 'a|Sie lassen sich gut zu Schokolade verarbeiten.,b|Es gibt viele Zubereitungsarten.,c|Sie schmecken wie Hähnchenfleisch.', 'points' => 1],
                        ['id' => 'goethe-b2-h2-14', 'type' => 'multiple_choice', 'label' => 'Aufgabe 14', 'instruction' => 'In einigen Ländern isst man heutzutage Insekten, weil ..', 'answer' => 'b', 'options' => 'a|dort Viehbetriebe nicht rentabel sind.,b|es dort schon länger üblich ist.,c|es dort viele verschiedene Insekten gibt.', 'points' => 1],
                        ['id' => 'goethe-b2-h2-15', 'type' => 'multiple_choice', 'label' => 'Aufgabe 15', 'instruction' => 'Was ist der Vorteil einer Landwirtschaft in städtischen Gebieten?', 'answer' => 'a', 'options' => 'a|Der Wasserverbrauch sinkt.,b|Man spart Strom.,c|Sie ist für alle Nutzpflanzen geeignet.', 'points' => 1],
                        ['id' => 'goethe-b2-h2-16', 'type' => 'multiple_choice', 'label' => 'Aufgabe 16', 'instruction' => 'Was bedeutet „individualisierte Landwirtschaft“?', 'answer' => 'a', 'options' => 'a|Die Produktion passt sich an das einzelne Tier an.,b|Computer zeigen an, wie viel Milch eine Kuh gegeben hat.,c|Die Qualität der Milchprodukte steigt.', 'points' => 1],
                    ],
                ],
                [
                    'id' => 'goethe-b2-hoeren-teil3',
                    'title' => 'Hören Teil 3',
                    'minutes' => 10,
                    'layout_style' => 'goethe_b2_hoeren_teil3',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-h3-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Sie hören im Radio ein Gespräch mit mehreren Personen. Die Personen sprechen über alternative Wohnformen. Sie hören den Text einmal. Wählen Sie bei jeder Aufgabe: Wer sagt das?\nLesen Sie jetzt die Aufgaben 17 bis 22. Dazu haben Sie 60 Sekunden Zeit.",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-h3-profiles',
                            'type' => 'text',
                            'label' => 'Sprecherprofile',
                            'content' => 'a Moderator|b Frau Gerster, Studentin|c Frau Lücke, Seniorin',
                            'answer' => '',
                            'points' => 0,
                        ],
                        ['id' => 'goethe-b2-h3-17', 'type' => 'matching', 'label' => 'Aufgabe 17', 'instruction' => 'Er/Sie lebt aus finanziellen Gründen mit anderen zusammen.', 'answer' => 'b', 'options' => 'a,b,c', 'points' => 1],
                        ['id' => 'goethe-b2-h3-18', 'type' => 'matching', 'label' => 'Aufgabe 18', 'instruction' => 'Eine WG-Wohnung sollte auch Raum zum Alleinsein bieten.', 'answer' => 'b', 'options' => 'a,b,c', 'points' => 1],
                        ['id' => 'goethe-b2-h3-19', 'type' => 'matching', 'label' => 'Aufgabe 19', 'instruction' => 'Es ist schön, Gesprächspartner in der Nähe zu haben.', 'answer' => 'c', 'options' => 'a,b,c', 'points' => 1],
                        ['id' => 'goethe-b2-h3-20', 'type' => 'matching', 'label' => 'Aufgabe 20', 'instruction' => 'Wohngemeinschaften für Menschen verschiedenen Alters sind neu.', 'answer' => 'a', 'options' => 'a,b,c', 'points' => 1],
                        ['id' => 'goethe-b2-h3-21', 'type' => 'matching', 'label' => 'Aufgabe 21', 'instruction' => 'Wohngemeinschaften können für die Bewohner nützlich sein.', 'answer' => 'c', 'options' => 'a,b,c', 'points' => 1],
                        ['id' => 'goethe-b2-h3-22', 'type' => 'matching', 'label' => 'Aufgabe 22', 'instruction' => 'Wohngemeinschaften sind für Senioren passender als für Studenten.', 'answer' => 'b', 'options' => 'a,b,c', 'points' => 1],
                    ],
                ],
                [
                    'id' => 'goethe-b2-hoeren-teil4',
                    'title' => 'Hören Teil 4',
                    'minutes' => 10,
                    'layout_style' => 'goethe_b2_hoeren_teil4',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-h4-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Sie hören einen kurzen Vortrag. Der Redner spricht über das Thema „Bessere Arbeitstechniken“. Sie hören den Text zweimal. Wählen Sie bei jeder Aufgabe die richtige Lösung.\nLesen Sie jetzt die Aufgaben 23 bis 30. Dazu haben Sie 90 Sekunden Zeit.",
                            'answer' => '',
                            'points' => 0,
                        ],
                        ['id' => 'goethe-b2-h4-23', 'type' => 'multiple_choice', 'label' => 'Aufgabe 23', 'instruction' => 'Es dauert mehr als 20 Minuten, ...', 'answer' => 'c', 'options' => 'a|nach einer Störung umzuschalten.,b|eine Aufgabe zu erledigen.,c|sich in eine neue Aufgabe einzuarbeiten.', 'points' => 1],
                        ['id' => 'goethe-b2-h4-24', 'type' => 'multiple_choice', 'label' => 'Aufgabe 24', 'instruction' => 'Was meint Herr Kinigard mit „leeren Kalorien“?', 'answer' => 'b', 'options' => 'a|Inhaltslose Dinge.,b|Informationen, die nicht relevant sind.,c|Dinge, die sehr komplex sind.', 'points' => 1],
                        ['id' => 'goethe-b2-h4-25', 'type' => 'multiple_choice', 'label' => 'Aufgabe 25', 'instruction' => 'Der Versuch, konzentriert zu arbeiten, ...', 'answer' => 'c', 'options' => 'a|ist erfolgversprechend.,b|macht glücklich.,c|strengt an.', 'points' => 1],
                        ['id' => 'goethe-b2-h4-26', 'type' => 'multiple_choice', 'label' => 'Aufgabe 26', 'instruction' => 'Mehrere Dinge gleichzeitig zu tun, ..', 'answer' => 'c', 'options' => 'a|ändert nichts am Ergebnis.,b|verbessert das Ergebnis.,c|verschlechtert das Ergebnis.', 'points' => 1],
                        ['id' => 'goethe-b2-h4-27', 'type' => 'multiple_choice', 'label' => 'Aufgabe 27', 'instruction' => 'Ein externes Gedächtnis wird geschaffen, indem man ..', 'answer' => 'a', 'options' => 'a|Dinge aufschreibt.,b|sich auf andere Dinge konzentriert.,c|den Kopf frei macht.', 'points' => 1],
                        ['id' => 'goethe-b2-h4-28', 'type' => 'multiple_choice', 'label' => 'Aufgabe 28', 'instruction' => 'Laut Herrn Kinigard sind gesetzliche Ruhepausen ..', 'answer' => 'b', 'options' => 'a|unnötig.,b|wichtig.,c|zu stark reguliert.', 'points' => 1],
                        ['id' => 'goethe-b2-h4-29', 'type' => 'multiple_choice', 'label' => 'Aufgabe 29', 'instruction' => 'Wozu kann Zeitdruck führen? Zu ...', 'answer' => 'a', 'options' => 'a|hervorragenden Ergebnissen.,b|langen Blockaden.,c|negativem Stress.', 'points' => 1],
                        ['id' => 'goethe-b2-h4-30', 'type' => 'multiple_choice', 'label' => 'Aufgabe 30', 'instruction' => 'Herr Kinigard rät dazu, ..', 'answer' => 'a', 'options' => 'a|Ablenkungen zu vermeiden.,b|auf Einflüsse von außen nicht zu reagieren.,c|E-Mail-Korrespondenz zu reduzieren.', 'points' => 1],
                    ],
                ],
                [
                    'id' => 'goethe-b2-schreiben-intro',
                    'title' => 'Kandidatenblätter Schreiben',
                    'minutes' => 75,
                    'layout_style' => 'goethe_b2_schreiben_intro',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-schreiben-intro-text',
                            'type' => 'text',
                            'label' => 'Modul Schreiben',
                            'content' => "Kandidatenblätter\nSchreiben\n75 Minuten\n\nDas Modul Schreiben hat zwei Teile.\nIn Teil 1 schreiben Sie einen Forumsbeitrag.\nIn Teil 2 schreiben Sie eine Nachricht.\nSie können mit jeder Aufgabe beginnen.\nEs werden nur die Lösungen und Texte auf dem Antwortbogen bewertet.\nBitte schreiben Sie mit einem blauen oder schwarzen Stift.\nHilfsmittel (z. B. Wörterbücher, Handys/mobile Endgeräte) sind nicht erlaubt.",
                            'answer' => '',
                            'points' => 0,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b2-schreiben-teil1',
                    'title' => 'Schreiben Teil 1',
                    'minutes' => 50,
                    'layout_style' => 'goethe_b2_schreiben_teil1',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-schreiben-t1-text',
                            'type' => 'text',
                            'label' => 'Teil 1',
                            'content' => "vorgeschlagene Arbeitszeit: 50 Minuten\n\nSie schreiben einen Forumsbeitrag zur Verschmutzung der Umwelt.\n\n• Äußern Sie Ihre Meinung zu Plastikverpackungen im Alltag.\n• Nennen Sie Gründe, warum Plastikverpackungen so verbreitet sind.\n• Nennen Sie andere Möglichkeiten, Dinge im Alltag zu verpacken.\n• Nennen Sie Vorteile der anderen Verpackungen.\n\nDenken Sie an eine Einleitung und einen Schluss. Bei der Bewertung wird darauf geachtet, wie genau die Inhaltspunkte bearbeitet sind, wie korrekt der Text ist und wie gut die Sätze und Abschnitte sprachlich miteinander verknüpft sind. Schreiben Sie circa 150 Wörter.",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-schreiben-t1-input',
                            'type' => 'writing',
                            'label' => 'Ihre Antwort zu Teil 1',
                            'instruction' => 'Forumsbeitrag zur Verschmutzung der Umwelt (ca. 150 Wörter)',
                            'answer' => '',
                            'points' => 15,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b2-schreiben-teil2',
                    'title' => 'Schreiben Teil 2',
                    'minutes' => 25,
                    'layout_style' => 'goethe_b2_schreiben_teil2',
                    'elements' => [
                        [
                            'id' => 'goethe-b2-schreiben-t2-text',
                            'type' => 'text',
                            'label' => 'Teil 2',
                            'content' => "vorgeschlagene Arbeitszeit: 25 Minuten\n\nSie machen gerade ein Praktikum bei einer deutschen Firma. Sie haben derzeit so viel zu tun, dass Sie Ihre Arbeit nicht mehr schaffen. Schreiben Sie eine Nachricht an Ihren Vorgesetzten, Herrn Ebert.\n\n• Bitten Sie um Verständnis für Ihre Situation.\n• Beschreiben Sie, womit Sie beschäftigt sind.\n• Machen Sie einen Vorschlag für die kommenden Tage.\n• Zeigen Sie Verständnis für die Arbeitssituation in der Firma.\n\nÜberlegen Sie sich eine passende Reihenfolge für die Inhaltspunkte. Bei der Bewertung wird darauf geachtet, wie genau die Inhaltspunkte bearbeitet sind, wie korrekt der Text ist und wie gut die Sätze und Abschnitte sprachlich miteinander verknüpft sind. Vergessen Sie nicht Anrede und Gruß. Schreiben Sie circa 100 Wörter.",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b2-schreiben-t2-input',
                            'type' => 'writing',
                            'label' => 'Ihre Antwort zu Teil 2',
                            'instruction' => 'Nachricht an Ihren Vorgesetzten, Herrn Ebert (ca. 100 Wörter)',
                            'answer' => '',
                            'points' => 10,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function goetheB1(): array
    {
        return [
            'title' => 'Goethe Zertifikat B1',
            'level' => 'B1',
            'source_pdf' => 'goethe_b1_lesen.pdf',
            'theme' => self::theme('#0f766e', '#1f2937', 'Instrument Sans'),
            'timings' => [
                ['label' => 'Lesen', 'minutes' => 65],
                ['label' => 'Hören', 'minutes' => 40],
                ['label' => 'Schreiben', 'minutes' => 60],
            ],
            'sections' => [
                [
                    'id' => 'goethe-b1-lesen-intro',
                    'title' => 'Kandidatenblätter - Lesen',
                    'minutes' => 65,
                    'layout_style' => 'default',
                    'elements' => [
                        [
                            'id' => 'goethe-b1-lesen-intro-text',
                            'type' => 'text',
                            'label' => 'Kandidatenblätter Lesen',
                            'content' => "Das Modul Lesen hat fünf Teile.\nSie lesen mehrere Texte und lösen Aufgaben dazu. Sie können mit jeder Aufgabe beginnen. Für jede Aufgabe gibt es nur eine richtige Lösung.\n\nEs werden nur die Lösungen auf dem Antwortbogen bewertet.\n\nBitte schreiben Sie mit einem blauen oder schwarzen Stift.\n\nHilfsmittel (z. B. Wörterbücher, Handys/mobile Endgeräte) sind nicht erlaubt.",
                            'instruction' => '65 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b1-lesen-teil1',
                    'title' => 'Lesen Teil 1',
                    'minutes' => 10,
                    'layout_style' => 'goethe_b1_teil1',
                    'elements' => [
                        [
                            'id' => 'goethe-b1-l1-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Lesen Sie den Text und die Aufgaben 1 bis 6 dazu.\nWählen Sie: Sind die Aussagen Richtig oder Falsch?",
                            'instruction' => 'Arbeitszeit: 10 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l1-blog',
                            'type' => 'text',
                            'label' => 'Susannes Alltags-Blog',
                            'content' => 'Was mir heute passiert ist, das glaubt mir keiner: Als ich zu Mittag nichts ahnend in der Küche beim Kochen stand, läutete mein Handy. Eine Frauenstimme erklärte mir, dass meine Brieftasche in der Bankfiliale abgegeben worden war und ich sie dort abholen könnte. Mir wurde ganz heiß – mir war noch gar nicht aufgefallen, dass sie fehlte. Und ich hatte ja auch noch relativ viel Bargeld eingesteckt! Schnell holte ich meine Handtasche hervor und suchte nach der Brieftasche. Es stimmte! Auch nach längerem Kramen in der Tasche konnte ich sie nicht finden. Mein Geld war tatsächlich verschwunden! Ich machte mich also auf den Weg zur Bank und überlegte, wo ich meine Brieftasche liegen gelassen hatte: Sicherlich im Supermarkt an der Kasse. Jedenfalls kam ich bei der Bank an und war schon gespannt darauf zu erfahren, wo meine Brieftasche gefunden worden war und natürlich, ob etwas fehlte. Die Bankangestellte teilte mir mit, dass ein junger Mann die Brieftasche abgegeben hatte. Er hatte sie auf dem Parkplatz vor dem Supermarkt gefunden und wollte sie eigentlich ins Fundbüro bringen – wie man es in so einem Fall eben macht. Der Weg dorthin war für ihn zu weit und so suchte er nach einer anderen Möglichkeit, die Brieftasche zurückzugeben. Das muss man sich einmal vorstellen: Er war so clever, dass er auf der Bankomatkarte nach meinem und dem Namen meiner Bank suchte ... Die Bank würde ja die Kontaktdaten zu meinem Namen haben und könnte mich so anrufen. Er fuhr in die nächste Filiale meiner Bank und dank der Computervernetzung der Filialen konnte meine Telefonnummer schnell herausgefunden werden. Da stand ich nun mit meiner Brieftasche, die mir beim Verlassen des Supermarktes aus der Handtasche gerutscht sein muss. Zum Glück war alles noch da! Ich bin sooo froh, dass diese Episode so gut ausgegangen ist. Nun weiß ich leider gar nicht, wie ich dem ehrlichen Finder danken kann. Vielleicht liest er ja diesen Blogeintrag oder es liest ihn jemand, dem er die Geschichte erzählt hat: „Vielen Dank, lieber Finder!“',
                            'instruction' => 'Donnerstag, den 23. Juni',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l1-1',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 1',
                            'instruction' => 'Erst durch den Anruf bemerkte Susanne das Fehlen ihrer Brieftasche.',
                            'answer' => 'richtig',
                            'options' => 'richtig,falsch',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l1-2',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 2',
                            'instruction' => 'Susanne glaubte, die Brieftasche beim Bezahlen vergessen zu haben.',
                            'answer' => 'richtig',
                            'options' => 'richtig,falsch',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l1-3',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 3',
                            'instruction' => 'Der Finder hatte die Brieftasche ins Fundbüro gebracht.',
                            'answer' => 'falsch',
                            'options' => 'richtig,falsch',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l1-4',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 4',
                            'instruction' => 'Die Telefonnummer der Bank war in der Brieftasche.',
                            'answer' => 'falsch',
                            'options' => 'richtig,falsch',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l1-5',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 5',
                            'instruction' => 'In Susannes Brieftasche fehlte nichts.',
                            'answer' => 'richtig',
                            'options' => 'richtig,falsch',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l1-6',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 6',
                            'instruction' => 'Susanne konnte dem Finder persönlich für seine Ehrlichkeit danken.',
                            'answer' => 'falsch',
                            'options' => 'richtig,falsch',
                            'points' => 1,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b1-lesen-teil2',
                    'title' => 'Lesen Teil 2',
                    'minutes' => 20,
                    'layout_style' => 'goethe_b1_teil2',
                    'elements' => [
                        [
                            'id' => 'goethe-b1-l2-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => 'Lesen Sie die Texte aus der Presse und die Aufgaben 7 bis 12 dazu. Wählen Sie bei jeder Aufgabe die richtige Lösung a, b oder c.',
                            'instruction' => 'Arbeitszeit: 20 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l2-article1',
                            'type' => 'text',
                            'label' => 'Artikel 1: Ein Dorf für grüne Energie',
                            'content' => "Seit Kurzem deckt das Dorf seinen kompletten Strombedarf und drei Viertel des Wärmebedarfs durch moderne Energien. „Das funktioniert mithilfe einer modernen Anlage für Bio-Gas“, erklärt der Diplom-Physiker Eckhard Meier. „Da kommen Abfall von den Tieren, Getreide und Holz rein und werden erwärmt. Ein Motor verbrennt das Gas und erzeugt dabei Wärme. Der Motor treibt dann einen Generator an, der Strom produziert.“\n\nEntstanden ist die Idee des „Bio-Energiedorfs“ an der Universität Göttingen. Ziel der Wissenschaftler war es zu zeigen, dass es möglich ist, ein Dorf komplett mit erneuerbaren Energien zu versorgen und damit einen Beitrag zum Klimaschutz zu leisten. Tatsächlich: Die Bio-Gasanlage erzeugt jährlich doppelt so viel Strom wie die Gemeinde verbraucht. Der Rest wird in das Stromnetz abgegeben und kostenlos anderen Dörfern zur Verfügung gestellt. Passt das Konzept auch für andere Dörfer? „Im Prinzip schon“, meint Eckhard Meier. Die technischen Anlagen könnten an anderen Orten genauso aufgebaut werden – der Raumbedarf is gering. Man benötigt allerdings vor allem eines: aktive und begeisterte Einwohner!",
                            'instruction' => 'Ein Dorf für grüne Energie — Das Dorf Feldheim in Brandenburg macht sich unabhängig von Öl und Kohle. (aus einer deutschen Zeitung)',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l2-7',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 7',
                            'instruction' => 'In diesem Text geht es um ...',
                            'answer' => 'b',
                            'options' => 'a|die neue Technologie von Eckhard Meier.,b|die umweltfreundliche Stromproduktion in Feldheim.,c|einen Studiengang an der Universität Göttingen.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l2-8',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 8',
                            'instruction' => 'Die Wissenschaftler wollten zeigen, dass ...',
                            'answer' => 'a',
                            'options' => 'a|ein ganzes Dorf von modernen Energien leben kann.,b|eine Bio-Gasanlage mehr Strom produziert, als ein Dorf braucht.,c|man größere Mengen Strom sparen kann.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l2-9',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 9',
                            'instruction' => 'Damit die Idee auch in anderen Dörfern funktioniert, ...',
                            'answer' => 'c',
                            'options' => 'a|benötigt man viel Geld.,b|braucht man genug Platz für die Technik.,c|muss die Bevölkerung dafür sein.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l2-article2',
                            'type' => 'text',
                            'label' => 'Artikel 2: Tour durch Murtens Geschichte',
                            'content' => "Mit der Rundfahrt „Zeitreise per Velo*“ können Touristen das Städtchen Murten und seine Geschichte sportlich neu entdecken. Die Tour startet am Bahnhof von Murten, wo die sportlichen Teilnehmer auf das eigene oder ein gemietetes Velo steigen.\n\nDie weniger sportlichen und jene, die es schon immer ausprobieren wollten, steigen aufs Elektro-Velo. Dieses kann ebenfalls am Bahnhof gemietet werden.\n\nVom Bahnhof führt der Weg auf den historischen Hügel, wo Karl der Kühne sein Hauptquartier aufbaute, bevor sein Heer im Jahr 1476 besiegt wurde. Die Sportlichen kommen bei der Fahrt auf den Hügel ins Schwitzen, während die E-Biker ganz einfach den Elektromotor nutzen.\n\nOben angekommen kann man die wunderbare Aussicht auf den Murtensee genießen. Nach einer kurzen Pause geht es weiter nach Merlach. Dort steht ein Denkmal für Soldaten, die in der Schlacht bei Murten 1476 umgekommen sind. Danach geht die Fahrt zum Hafen und in die Altstadt. Unterwegs erfahren die Velofahrer vieles über die Region. „Mit der Velorundfahrt für Gruppen wollen wir unser Angebot für aktive Radfahrer erweitern“, sagt der Geschäftsführer von Murten Tourismus. Damit soll sowohl das Gebiet für Velo-Touristen interessant gemacht als auch der Trend zum E-Bike unterstützt werden.\n\n*Velo = Schweizer Standard für „Fahrrad“",
                            'instruction' => 'Tour durch Murtens Geschichte (aus einer Schweizer Broschüre)',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l2-10',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 10',
                            'instruction' => 'In diesem Text geht es darum, dass ...',
                            'answer' => 'b',
                            'options' => 'a|die Geschichte von Murten neu erzählt wird.,b|es ein neues Tourismus-Angebot gibt.,c|man in Murten neue Velo-Wege bauen will.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l2-11',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 11',
                            'instruction' => 'Für die Rundfahrt ...',
                            'answer' => 'b',
                            'options' => 'a|braucht man ein eigenes Velo.,b|muss man nicht sportlich sein.,c|sollte man mit der Bahn anreisen.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l2-12',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 12',
                            'instruction' => 'Der Geschäftsführer von Murten Tourismus will, dass ...',
                            'answer' => 'c',
                            'options' => 'a|es in Murten mehr Stadtführungen für Gruppen gibt.,b|die Leute normale Velos statt Elektro-Velos benutzen.,c|mehr Velo-Touristen in die Region kommen.',
                            'points' => 1,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b1-lesen-teil3',
                    'title' => 'Lesen Teil 3',
                    'minutes' => 10,
                    'layout_style' => 'goethe_b1_teil3',
                    'elements' => [
                        [
                            'id' => 'goethe-b1-l3-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => 'Lesen Sie die Situationen 13 bis 19 und die Anzeigen A bis J aus verschiedenen deutschsprachigen Medien. Wählen Sie: Welche Anzeige passt zu welcher Situation? Sie können jede Anzeige nur einmal verwenden. Die Anzeige aus dem Beispiel können Sie nicht mehr verwenden. Für eine Situation gibt es keine passende Anzeige. In diesem Fall schreiben Sie 0.',
                            'instruction' => 'Arbeitszeit: 10 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l3-13',
                            'type' => 'matching',
                            'label' => 'Aufgabe 13',
                            'instruction' => 'Leon möchte im Sommer im Tourismus-Bereich arbeiten, um sein Deutsch zu verbessern.',
                            'answer' => 'd',
                            'options' => 'a,b,c,d,e,f,g,h,i,j,0',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l3-14',
                            'type' => 'matching',
                            'label' => 'Aufgabe 14',
                            'instruction' => 'Giovanna sucht deutsche Hörbücher, damit sie unterwegs Deutsch lernen kann.',
                            'answer' => '0',
                            'options' => 'a,b,c,d,e,f,g,h,i,j,0',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l3-15',
                            'type' => 'matching',
                            'label' => 'Aufgabe 15',
                            'instruction' => 'Mirjeta hat keine Zeit für einen Kurs, möchte sich aber regelmäßig über Neuigkeiten aus Deutschland informieren.',
                            'answer' => 'f',
                            'options' => 'a,b,c,d,e,f,g,h,i,j,0',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l3-16',
                            'type' => 'matching',
                            'label' => 'Aufgabe 16',
                            'instruction' => 'Maria möchte am Computer Deutsch lernen.',
                            'answer' => 'b',
                            'options' => 'a,b,c,d,e,f,g,h,i,j,0',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l3-17',
                            'type' => 'matching',
                            'label' => 'Aufgabe 17',
                            'instruction' => 'Susan liest am liebsten Literatur, wenn die Texte nicht zu schwierig sind.',
                            'answer' => 'a',
                            'options' => 'a,b,c,d,e,f,g,h,i,j,0',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l3-18',
                            'type' => 'matching',
                            'label' => 'Aufgabe 18',
                            'instruction' => 'Miroslav will den schriftlichen Ausdruck verbessern, weil er im Studium viel schreiben muss.',
                            'answer' => 'c',
                            'options' => 'a,b,c,d,e,f,g,h,i,j,0',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l3-19',
                            'type' => 'matching',
                            'label' => 'Aufgabe 19',
                            'instruction' => 'Juan kann nur am Abend einen Kurs besuchen.',
                            'answer' => 'h',
                            'options' => 'a,b,c,d,e,f,g,h,i,j,0',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l3-ad-a',
                            'type' => 'ad_box',
                            'label' => 'a',
                            'content' => "Neu im Verlagsprogramm:\nSchweizer Autoren, leicht gemacht\nNach 100 Lernstunden schon literarische Kurzgeschichten, Romane und Gedichte lesen? Kein Problem!\nDie Reihe „Schweizer Autoren, leicht gemacht“ bietet Deutschlernern vereinfachte Originalversionen für uneingeschränktes Lesevergnügen.\nwww.schweizer-leseverlag.ch",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l3-ad-b',
                            'type' => 'ad_box',
                            'label' => 'b',
                            'content' => "Trainingsprogramm Deutsch\nSie wollen Ihre Sprachkenntnisse verbessern, haben aber keine Zeit für Kurse?\nDann lernen Sie Deutsch im Internet!\nUnser Lernportal bietet Ihnen gratis:\n– 10 Kurslektionen für Anfänger und Fortgeschrittene\n– Erklärungen zur Grammatik\n– alle Übungen online verfügbar\nwww.sprachenlernen.de",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l3-ad-c',
                            'type' => 'ad_box',
                            'label' => 'c',
                            'content' => "DEUTSCH IN DER SCHWEIZ\nUnser Angebot:\n– Intensivkurse mit 20-30 Wochenstunden\n– Schreibkurse (auch als Fernstudium!)\n– Sommerkurse für Jugendliche und Erwachsene (mit Freizeitprogramm)\n– Kurs: Deutsch im Hotel\nWir bieten nur Tageskurse an!\nwww.deutschinderschweiz.ch",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l3-ad-d',
                            'type' => 'ad_box',
                            'label' => 'd',
                            'content' => "Job & Sprache-Net\nWir bieten Jobs für Deutschlernende in Deutschland, Österreich und in der Schweiz. Perfektionieren Sie Ihre Sprachkenntnisse und sammeln Sie Erfahrungen in den Arbeitsbereichen Hotel und Restaurant.\n• Dauer: bis zu 3 Monate (Juni – August)\n• Kosten für Unterkunft und Verpflegung werden übernommen\nInformationen: www.jobundsprache-net.com",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l3-ad-e',
                            'type' => 'ad_box',
                            'label' => 'e',
                            'content' => "Sprachschule ORION sucht engagierte Trainer und Trainerinnen (Vollzeit)\n• Kurszeiten von 8:00 – 17:00 h\n• Niveaus A1 – C1\n• allgemeine und berufsbezogene Sprachkurse (z. B. Deutsch für den Tourismus)\nBewerbungen an: office@deutschintensiv.de",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l3-ad-f',
                            'type' => 'ad_box',
                            'label' => 'f',
                            'content' => "Deutsch erLesen\nDas Magazin Deutsch erLesen richtet sich an Deutschinteressierte im In- und Ausland.\nEs erscheint einmal im Monat und enthält aktuelle Originalartikel aus der deutschen Presse. Deutschland erfahren & Deutsch lernen!\nBestellen Sie noch heute Ihr Probeexemplar: info@deutsch-erlesen.de",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l3-ad-g',
                            'type' => 'ad_box',
                            'label' => 'g',
                            'content' => "Verlag für deutsche Literatur sucht Lektor/Lektorin\nfür die Auswahl und Korrektur von Werken junger deutscher Autoren.\nUnser Verlagsprogramm umfasst Romane, Gedichtbände und Hörbücher.\nSchicken Sie Ihre Bewerbung an: junge-literatur@berlin.de",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l3-ad-h',
                            'type' => 'ad_box',
                            'label' => 'h',
                            'content' => "Deutsch in Linz\nDeutsch-Intensivkurse\nMo bis Fr von 9:30 – 13 h und von 14:00 – 17 h\nKurse für Berufstätige\nFür Berufstätige und Vielbeschäftigte bieten wir flexible Kurszeiten an (Termine nach Wunsch).\nOnline-Einstufungstest auf www.deutschinlinz-schule.at",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l3-ad-i',
                            'type' => 'ad_box',
                            'label' => 'i',
                            'content' => "Sprache und Kultur in Wien\n\nDeutschkurse ganzjährig!\nSpezialangebote für den Sommer.\nInfos unter:\nwww.sprache-kultur@aon.at",
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l3-ad-j',
                            'type' => 'ad_box',
                            'label' => 'j',
                            'content' => "Neues Computerprogramm von DIGITAL LEARNING\nFür Büromanagement und Buchhaltung in englischer und deutscher Sprache.\nAb sofort im Buchhandel erhältlich\nInfos: software@digital-learning.net",
                            'answer' => '',
                            'points' => 0,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b1-lesen-teil4',
                    'title' => 'Lesen Teil 4',
                    'minutes' => 15,
                    'layout_style' => 'goethe_b1_teil4',
                    'elements' => [
                        [
                            'id' => 'goethe-b1-l4-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Lesen Sie die Texte 20 bis 26. Wählen Sie: Ist die Person für ein Verbot?\nIn einer Zeitschrift lesen Sie Kommentare zu einem Artikel über das Verbot von Videospielen, in denen viel Gewalt vorkommt (sogenannte „Killerspiele“).",
                            'instruction' => 'Arbeitszeit: 15 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l4-opinion-niko',
                            'type' => 'goethe_portrait',
                            'label' => 'Niko, 52, Saarbrücken',
                            'content' => 'Man hat bis jetzt nicht wissenschaftlich gezeigt, dass sogenannte Gewaltspiele einen Einfluss auf das Verhalten von Jugendlichen haben? So ein Blödsinn! Ist doch logisch, dass so massive Bilder die Gedanken beeinflussen! Für mich ist klar: Durch solche Spiele kann viel Unglück und Schaden entstehen, die müssen weg!',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l4-opinion-stefan',
                            'type' => 'goethe_portrait',
                            'label' => 'Stefan, 19, Graz',
                            'content' => 'Ich könnte mir vorstellen, dass ein Verbot die gegenteilige Wirkung hätte, denn ein verbotenes Spiel ist doch noch interessanter als ein nicht verbotenes! Außerdem ist es gar nicht möglich, alle Killerspiele abzuschaffen, weil es davon schon viel zu viele gibt. Mein Fazit: Warum „Killerspiele“ verbieten, wenn es im Endeffekt sowieso alle spielen und das Ganze gerade durch ein Verbot noch interessanter wird?',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l4-20',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 20 (Stefan)',
                            'instruction' => 'Ist Stefan für ein Verbot von Gewaltspielen?',
                            'answer' => 'nein',
                            'options' => 'ja,nein',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l4-opinion-dagmar',
                            'type' => 'goethe_portrait',
                            'label' => 'Dagmar, 23, Leipzig',
                            'content' => 'Wer entscheidet letztlich darüber, welche Spiele man nicht braucht? Dürfen diese Menschen dann auch darüber entscheiden, welche Bücher, Filme oder Musik wir nicht brauchen? Viel wichtiger ist es doch, dass Kinder und Jugendliche lernen, selbst zwischen virtueller und realer Gewalt zu unterscheiden!',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l4-21',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 21 (Dagmar)',
                            'instruction' => 'Ist Dagmar für ein Verbot von Gewaltspielen?',
                            'answer' => 'nein',
                            'options' => 'ja,nein',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l4-opinion-kathleen',
                            'type' => 'goethe_portrait',
                            'label' => 'Kathleen, 49, Cuxhaven',
                            'content' => '„Töten auf Probe“ soll erlaubt sein? Das bedeutet: Mal schnell zu üben, wie man jemanden umbringt, ist eine Freizeitbeschäftigung. Wie zynisch kann man sein? Nicht jeder wird zum Glück zum Monster, der sich mit so viel Gewalt und Zerstörung beschäftigt. Die Einstellung dahinter ist aber Ausdruck einer unglaublichen Gleichgültigkeit. Das muss man stoppen, und zwar schnell.',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l4-22',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 22 (Kathleen)',
                            'instruction' => 'Ist Kathleen für ein Verbot von Gewaltspielen?',
                            'answer' => 'ja',
                            'options' => 'ja,nein',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l4-opinion-marius',
                            'type' => 'goethe_portrait',
                            'label' => 'Marius, 34, St. Gallen',
                            'content' => 'Ich spiele sogenannte Killerspiele wie CaDu seit bald drei Jahren regelmäßig. Ich habe eine kleine Tochter, eine Frau und einen Job und spiele für den Ausgleich. Nur weil es mal dazu kommt, dass einer auf dieser Welt das Spiel als Realität sieht und durchdreht, müssen dann all die anderen ein Verbot hinnehmen? Es wäre besser, die Altersbeschränkung auf 18 Jahre festzulegen und sie auch strikt einzuhalten.',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l4-23',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 23 (Marius)',
                            'instruction' => 'Ist Marius für ein Verbot von Gewaltspielen?',
                            'answer' => 'nein',
                            'options' => 'ja,nein',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l4-opinion-jonny',
                            'type' => 'goethe_portrait',
                            'label' => 'Jonny, 21, Berlin',
                            'content' => '„Killerspiele“ machen schnell aggressiv und man wird davon abhängig. Außerdem besteht die Gefahr, dass jemand nicht mit solchen Spielen umgehen kann und zum Nachahmungstäter wird. Das sind nur zwei Gründe, warum man gegen diese Spiele endlich etwas tun sollte.',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l4-24',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 24 (Jonny)',
                            'instruction' => 'Ist Jonny für ein Verbot von Gewaltspielen?',
                            'answer' => 'ja',
                            'options' => 'ja,nein',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l4-opinion-robert',
                            'type' => 'goethe_portrait',
                            'label' => 'Robert, 18, Winterthur',
                            'content' => 'In dieser Diskussion fehlt immer die genaue Kenntnis! Meistens ist es bei sogenannten „Killerspielen“ nämlich so, dass man in einem Team spielt. Ein solches Spiel stärkt also den Teamgeist. Außerdem steht die Taktik im Vordergrund und nicht eine bestimmte Methode, jemanden umzubringen. So wird das taktische bzw. logische Denken gefördert!',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l4-25',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 25 (Robert)',
                            'instruction' => 'Ist Robert für ein Verbot von Gewaltspielen?',
                            'answer' => 'nein',
                            'options' => 'ja,nein',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l4-opinion-marinette',
                            'type' => 'goethe_portrait',
                            'label' => 'Marinette, 38, Frankfurt',
                            'content' => 'Ich denke, dass gewisse Situationen oder Dinge einen Menschen dazu bringen können, etwas zu tun, das er sonst nicht tun würde. Das kann gerade bei sogenannten „Killerspielen“ der Fall sein. Deshalb scheint mir ein Verbot sinnvoll zu sein, auch wenn so ein Verbot allein wahrscheinlich nicht viel nützt, denn Killerspiele sind ja nur eine ‚Inspirationsquelle‘ für Gewalt.',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l4-26',
                            'type' => 'true_false',
                            'label' => 'Aufgabe 26 (Marinette)',
                            'instruction' => 'Ist Marinette für ein Verbot von Gewaltspielen?',
                            'answer' => 'ja',
                            'options' => 'ja,nein',
                            'points' => 1,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b1-lesen-teil5',
                    'title' => 'Lesen Teil 5',
                    'minutes' => 10,
                    'layout_style' => 'goethe_b1_teil5',
                    'elements' => [
                        [
                            'id' => 'goethe-b1-l5-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Lesen Sie die Aufgaben 27 bis 30 und den Text dazu. Wählen Sie bei jeder Aufgabe die richtige Lösung a, b oder c.\nSie informieren sich über die Hausordnung des Dresdner Berufsbildungszentrums BZW, in dem Sie einen Kurs gebucht haben.",
                            'instruction' => 'Arbeitszeit: 10 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l5-hausordnung',
                            'type' => 'text',
                            'label' => 'HAUSORDNUNG',
                            'content' => "Unterrichtszeiten: Die vereinbarten Unterrichtszeiten sind verbindlich. Ist die Lehrperson zehn Minuten nach Unterrichtsbeginn nicht da, informiert die Klassenvertretung das Sekretariat.\n\nOrdnung: In sämtlichen Räumen und Anlagen unserer Schule ist auf Ordnung und Sauberkeit zu achten. Schulräume, Einrichtungen und Anlagen sind sorgfältig zu benützen. Außerhalb der Unterrichtszeiten dürfen sich Lernende nicht in den Klassenräumen aufhalten. Es ist untersagt, in den Klassenräumen etwas an die Wände zu kleben oder zu schreiben und Schulmöbel in andere Räume zu bringen. Mitarbeitende und Lernende, die Schäden feststellen, melden diese dem Sekretariat.\n\nStörungen: Mitarbeitende und Lernende sorgen dafür, dass der Schulbetrieb nicht gestört wird.\n\nAlkohol- und Drogenkonsum: Der Konsum von Alkohol, illegalen Drogen sowie anderen psychoaktiven Substanzen ist auf dem gesamten Schulareal und während schulischer Veranstaltungen (einschließlich aller Pausen) verboten. In Ausnahmefällen kann die Schulleitung den Konsum von Alkohol erlauben.\n\nRauchen: Rauchen ist nur im Freien beziehungsweise in den dafür vorgesehenen Zonen gestattet. Wir bitten darum, die aufgestellten Aschenbecher zu benutzen.\n\nDiebstahl: Es empfiehlt sich, Wertsachen und Bargeld sorgfältig aufzubewahren. Die Schule stellt den Lernenden und Mitarbeitenden kostenlos Schließfächer zur Verfügung. Für verlorene Schlüssel wird eine Gebühr von Euro 50,- erhoben. Die Schule übernimmt für Diebstähle keine Haftung.\n\nFundgegenstände: Fundgegenstände bitte im Sekretariat abgeben.\n\nParkplätze: Auf dem Schulareal stehen keine Gratis-Autoparkplätze zur Verfügung. Fahrräder müssen in den dafür vorgesehenen Fahrradkeller gebracht und abgeschlossen werden. Mopeds und Motorräder sind auf dem Schulareal nicht erlaubt.",
                            'instruction' => 'Hausordnung des Dresdner Berufsbildungszentrums BZW',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-l5-27',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 27',
                            'instruction' => 'Schüler ...',
                            'answer' => 'c',
                            'options' => 'a|dürfen keine Fahrräder mit zur Schule bringen.,b|dürfen ihre Fahrräder auf den Schulhof stellen.,c|müssen ihre Fahrräder in einen speziellen Raum stellen.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l5-28',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 28',
                            'instruction' => 'Für die Klassenräume des BZW gilt:',
                            'answer' => 'a',
                            'options' => 'a|Schüler dürfen keine Poster aufhängen.,b|Schüler müssen dort selber aufräumen.,c|Schüler können dort nach dem Unterricht lernen.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l5-29',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 29',
                            'instruction' => 'Um die verschließbaren Fächer benutzen zu können, muss man ...',
                            'answer' => 'c',
                            'options' => 'a|einen Schlüssel im Sekretariat verlangen.,b|einmalig 50,- Euro zahlen.,c|Schüler sein oder im BZW arbeiten.',
                            'points' => 1,
                        ],
                        [
                            'id' => 'goethe-b1-l5-30',
                            'type' => 'multiple_choice',
                            'label' => 'Aufgabe 30',
                            'instruction' => 'Das Trinken von Alkohol ...',
                            'answer' => 'a',
                            'options' => 'a|kann von der Schulleitung genehmigt werden.,b|muss der Lehrperson gemeldet werden.,c|ist ohne Ausnahme verboten.',
                            'points' => 1,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b1-hoeren-intro',
                    'title' => 'Kandidatenblätter - Hören',
                    'minutes' => 40,
                    'layout_style' => 'goethe_b1_hoeren_intro',
                    'elements' => [
                        [
                            'id' => 'goethe-b1-hoeren-intro-text',
                            'type' => 'text',
                            'label' => 'Kandidatenblätter Hören',
                            'content' => "Das Modul Hören hat vier Teile.\nSie hören mehrere Texte und lösen Aufgaben dazu. Lesen Sie jeweils zuerst die Aufgaben und hören Sie dann den Text dazu. Für jede Aufgabe gibt es nur eine richtige Lösung.\n\nNach dem Hören haben Sie fünf Minuten Zeit, Ihre Lösungen auf den Antwortbogen zu übertragen. Es werden nur die Lösungen auf dem Antwortbogen bewertet.\n\nBitte schreiben Sie mit einem blauen oder schwarzen Stift.\n\nHilfsmittel (z. B. Wörterbücher, Handys/mobile Endgeräte) sind nicht erlaubt.",
                            'instruction' => 'circa 40 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                    ],
                ],
                [
                    'id' => 'goethe-b1-hoeren-teil1',
                    'title' => 'Hören Teil 1',
                    'minutes' => 10,
                    'layout_style' => 'goethe_b1_hoeren_teil1',
                    'elements' => [
                        [
                            'id' => 'goethe-b1-h1-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Sie hören nun fünf kurze Texte. Sie hören jeden Text zweimal. Zu jedem Text lösen Sie zwei Aufgaben. Wählen Sie bei jeder Aufgabe die richtige Lösung.\nLesen Sie zuerst das Beispiel. Dazu haben Sie 10 Sekunden Zeit.",
                            'answer' => '',
                            'points' => 0,
                        ],
                        ['id' => 'goethe-b1-h1-1', 'type' => 'matching', 'label' => 'Aufgabe 1', 'instruction' => 'Der Termin von Frau Stein wird verschoben.', 'answer' => 'richtig', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b1-h1-2', 'type' => 'multiple_choice', 'label' => 'Aufgabe 2', 'instruction' => 'Frau Stein soll ...', 'answer' => 'c', 'options' => 'a|die Chipkarte mitbringen.,b|zehn Euro bezahlen.,c|zurückrufen.', 'points' => 1],
                        ['id' => 'goethe-b1-h1-3', 'type' => 'matching', 'label' => 'Aufgabe 3', 'instruction' => 'Herr Thomas informiert Frau Brahms über neue Versicherungstarife.', 'answer' => 'falsch', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b1-h1-4', 'type' => 'multiple_choice', 'label' => 'Aufgabe 4', 'instruction' => 'Herr Thomas ...', 'answer' => 'b', 'options' => 'a|möchte, dass Frau Brahms einen neuen Vertrag abschließt.,b|braucht Zeugnisse von Frau Brahms.,c|ruft später noch einmal an.', 'points' => 1],
                        ['id' => 'goethe-b1-h1-5', 'type' => 'matching', 'label' => 'Aufgabe 5', 'instruction' => 'Sie hören Veranstaltungstipps für München.', 'answer' => 'falsch', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b1-h1-6', 'type' => 'multiple_choice', 'label' => 'Aufgabe 6', 'instruction' => 'Auf der Autobahn gibt es Stau wegen ...', 'answer' => 'c', 'options' => 'a|einer Baustelle.,b|des Berufsverkehrs.,c|eines Unfalls.', 'points' => 1],
                        ['id' => 'goethe-b1-h1-7', 'type' => 'matching', 'label' => 'Aufgabe 7', 'instruction' => 'Sie hören eine Information für eine Reisegruppe.', 'answer' => 'falsch', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b1-h1-8', 'type' => 'multiple_choice', 'label' => 'Aufgabe 8', 'instruction' => 'Welcher Zug fällt aus? Der Zug nach ..', 'answer' => 'b', 'options' => 'a|Bern.,b|Genf.,c|Lausanne.', 'points' => 1],
                        ['id' => 'goethe-b1-h1-9', 'type' => 'matching', 'label' => 'Aufgabe 9', 'instruction' => 'Das Wetter wird im Osten Deutschlands besser.', 'answer' => 'falsch', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b1-h1-10', 'type' => 'multiple_choice', 'label' => 'Aufgabe 10', 'instruction' => 'Vorausgesagt werden ...', 'answer' => 'a', 'options' => 'a|Gewitter an der Elbe.,b|Temperaturen unter 10 Grad.,c|starke Regenfälle im Westen.', 'points' => 1],
                    ],
                ],
                [
                    'id' => 'goethe-b1-hoeren-teil2',
                    'title' => 'Hören Teil 2',
                    'minutes' => 10,
                    'layout_style' => 'goethe_b1_hoeren_teil2',
                    'elements' => [
                        [
                            'id' => 'goethe-b1-h2-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Sie hören nun einen Text. Sie hören den Text einmal. Dazu lösen Sie fünf Aufgaben. Wählen Sie bei jeder Aufgabe die richtige Lösung a, b oder c.\nLesen Sie jetzt die Aufgaben 11 bis 15. Dazu haben Sie 60 Sekunden Zeit.\n\nSie nehmen an einer Führung durch das Münchner Stadtmuseum teil.",
                            'answer' => '',
                            'points' => 0,
                        ],
                        ['id' => 'goethe-b1-h2-11', 'type' => 'multiple_choice', 'label' => 'Aufgabe 11', 'instruction' => 'Das Museum ist ...', 'answer' => 'c', 'options' => 'a|sehr voll.,b|teilweise geschlossen.,c|ziemlich leer.', 'points' => 1],
                        ['id' => 'goethe-b1-h2-12', 'type' => 'multiple_choice', 'label' => 'Aufgabe 12', 'instruction' => 'Was zeigt der Museumsführer den Touristen?', 'answer' => 'b', 'options' => 'a|alle Ausstellungen,b|die Hauptausstellung,c|die Sonderausstellungen', 'points' => 1],
                        ['id' => 'goethe-b1-h2-13', 'type' => 'multiple_choice', 'label' => 'Aufgabe 13', 'instruction' => 'Wo ist der Treffpunkt am Nachmittag?', 'answer' => 'a', 'options' => 'a|am Eingang,b|an der Garderobe,c|im Café', 'points' => 1],
                        ['id' => 'goethe-b1-h2-14', 'type' => 'multiple_choice', 'label' => 'Aufgabe 14', 'instruction' => 'Die Ausstellung beschäftigt sich mit ...', 'answer' => 'c', 'options' => 'a|dem Oktoberfest.,b|der bayerischen Küche.,c|der Geschichte Münchens.', 'points' => 1],
                        ['id' => 'goethe-b1-h2-15', 'type' => 'multiple_choice', 'label' => 'Aufgabe 15', 'instruction' => 'Der Museumsführer empfiehlt den Teilnehmern einen ...', 'answer' => 'c', 'options' => 'a|Restaurantbesuch.,b|Cafébesuch.,c|Biergartenbesuch.', 'points' => 1],
                    ],
                ],
                [
                    'id' => 'goethe-b1-hoeren-teil3',
                    'title' => 'Hören Teil 3',
                    'minutes' => 10,
                    'layout_style' => 'goethe_b1_hoeren_teil3',
                    'elements' => [
                        [
                            'id' => 'goethe-b1-h3-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Sie hören nun ein Gespräch. Sie hören das Gespräch einmal. Dazu lösen Sie sieben Aufgaben. Wählen Sie: Sind die Aussagen Richtig oder Falsch?\nLesen Sie jetzt die Aufgaben 16 bis 22. Dazu haben Sie 60 Sekunden Zeit.\n\nSie sind an einer Bushaltestelle und hören, wie sich ein Mann und eine Frau über ein Fest unterhalten.",
                            'answer' => '',
                            'points' => 0,
                        ],
                        ['id' => 'goethe-b1-h3-16', 'type' => 'matching', 'label' => 'Aufgabe 16', 'instruction' => 'Bei dem Fest wurde der Geburtstag von Annas Mann gefeiert.', 'answer' => 'richtig', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b1-h3-17', 'type' => 'matching', 'label' => 'Aufgabe 17', 'instruction' => 'Nadia ist vom Haus der Gastgeber begeistert.', 'answer' => 'falsch', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b1-h3-18', 'type' => 'matching', 'label' => 'Aufgabe 18', 'instruction' => 'Nadia arbeitet beim Fernsehen.', 'answer' => 'falsch', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b1-h3-19', 'type' => 'matching', 'label' => 'Aufgabe 19', 'instruction' => 'Das Essen war ausgezeichnet.', 'answer' => 'richtig', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b1-h3-20', 'type' => 'matching', 'label' => 'Aufgabe 20', 'instruction' => 'Nadia hat zusammen mit dem Musiker gespielt.', 'answer' => 'richtig', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b1-h3-21', 'type' => 'matching', 'label' => 'Aufgabe 21', 'instruction' => 'Nadia hat auch Jazz gespielt.', 'answer' => 'falsch', 'options' => 'richtig,falsch', 'points' => 1],
                        ['id' => 'goethe-b1-h3-22', 'type' => 'matching', 'label' => 'Aufgabe 22', 'instruction' => 'Das Fest dauerte bis nach 12 Uhr nachts.', 'answer' => 'richtig', 'options' => 'richtig,falsch', 'points' => 1],
                    ],
                ],
                [
                    'id' => 'goethe-b1-hoeren-teil4',
                    'title' => 'Hören Teil 4',
                    'minutes' => 10,
                    'layout_style' => 'goethe_b1_hoeren_teil4',
                    'elements' => [
                        [
                            'id' => 'goethe-b1-h4-instruction',
                            'type' => 'text',
                            'label' => 'Anleitung',
                            'content' => "Sie hören nun eine Diskussion. Sie hören die Diskussion zweimal. Dazu lösen Sie acht Aufgaben. Ordnen Sie die Aussagen zu: Wer sagt was?\nLesen Sie jetzt die Aussagen 23 bis 30. Dazu haben Sie 60 Sekunden Zeit.\n\nDer Moderator der Radiosendung „Diskussion am Abend“ diskutiert mit den Eltern Dana Schneider und Florian Bader zum Thema „Sollen kleine Kinder in die Kinderkrippe gehen?“.",
                            'answer' => '',
                            'points' => 0,
                        ],
                        ['id' => 'goethe-b1-h4-23', 'type' => 'matching', 'label' => 'Aufgabe 23', 'instruction' => 'Kinder lernen soziales Verhalten erst ab einem bestimmten Alter.', 'answer' => 'b', 'options' => 'a,b,c', 'points' => 1],
                        ['id' => 'goethe-b1-h4-24', 'type' => 'matching', 'label' => 'Aufgabe 24', 'instruction' => 'Für den Erfolg im Beruf ist es wichtig, immer zu arbeiten.', 'answer' => 'c', 'options' => 'a,b,c', 'points' => 1],
                        ['id' => 'goethe-b1-h4-25', 'type' => 'matching', 'label' => 'Aufgabe 25', 'instruction' => 'Es ist möglich, Kinder zu haben und auch zu arbeiten.', 'answer' => 'c', 'options' => 'a,b,c', 'points' => 1],
                        ['id' => 'goethe-b1-h4-26', 'type' => 'matching', 'label' => 'Aufgabe 26', 'instruction' => 'In der Krippe lernen Kinder andere Dinge als zu Hause.', 'answer' => 'a', 'options' => 'a,b,c', 'points' => 1],
                        ['id' => 'goethe-b1-h4-27', 'type' => 'matching', 'label' => 'Aufgabe 27', 'instruction' => 'In Krippen müssen Erzieherinnen viele Kinder gleichzeitig betreuen.', 'answer' => 'b', 'options' => 'a,b,c', 'points' => 1],
                        ['id' => 'goethe-b1-h4-28', 'type' => 'matching', 'label' => 'Aufgabe 28', 'instruction' => 'Kinder sollen lernen, sich auch mal alleine zu beschäftigen.', 'answer' => 'c', 'options' => 'a,b,c', 'points' => 1],
                        ['id' => 'goethe-b1-h4-29', 'type' => 'matching', 'label' => 'Aufgabe 29', 'instruction' => 'Manche Kindertagesstätten haben zu wenig Geld.', 'answer' => 'c', 'options' => 'a,b,c', 'points' => 1],
                        ['id' => 'goethe-b1-h4-30', 'type' => 'matching', 'label' => 'Aufgabe 30', 'instruction' => 'Auch Familien mit wenig Geld sollen Kinder haben können.', 'answer' => 'b', 'options' => 'a,b,c', 'points' => 1],
                    ],
                ],
                [
                    'id' => 'goethe-b1-schreiben',
                    'title' => 'Schreiben',
                    'minutes' => 60,
                    'layout_style' => 'goethe_b1_schreiben',
                    'elements' => [
                        [
                            'id' => 'goethe-b1-write-a1-instruction',
                            'type' => 'text',
                            'label' => 'Aufgabe 1',
                            'content' => "Sie haben vor einer Woche Ihren Geburtstag gefeiert.\nEin Freund/Eine Freundin von Ihnen konnte nicht zu Ihrer Feier kommen, weil er/sie krank war.\n- Beschreiben Sie: Wie war die Feier?\n- Begründen Sie: Welches Geschenk finden Sie besonders toll und warum?\n- Machen Sie einen Vorschlag für ein Treffen.\n\nSchreiben Sie eine E-Mail (circa 80 Wörter).\nSchreiben Sie etwas zu allen drei Punkten.\nAchten Sie auf den Textaufbau (Anrede, Einleitung, Reihenfolge der Inhaltspunkte, Schluss).",
                            'instruction' => 'Arbeitszeit: 20 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-write-a1-input',
                            'type' => 'writing',
                            'label' => 'Ihre Antwort zu Aufgabe 1',
                            'instruction' => 'E-Mail zu Aufgabe 1 (ca. 80 Wörter)',
                            'answer' => '',
                            'points' => 40,
                        ],
                        [
                            'id' => 'goethe-b1-write-a2-instruction',
                            'type' => 'text',
                            'label' => 'Aufgabe 2',
                            'content' => "Sie haben im Fernsehen eine Diskussionssendung zum Thema „Persönliche Kontakte und Internet“ gesehen.\nIm Online-Gästebuch der Sendung finden Sie folgende Meinung:\n\nSchreiben Sie nun Ihre Meinung zum Thema (circa 80 Wörter).",
                            'instruction' => 'Arbeitszeit: 25 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-write-a2-input',
                            'type' => 'writing',
                            'label' => 'Ihre Antwort zu Aufgabe 2',
                            'instruction' => 'Forumsbeitrag zu Aufgabe 2 (ca. 80 Wörter)',
                            'answer' => '',
                            'points' => 40,
                        ],
                        [
                            'id' => 'goethe-b1-write-a3-instruction',
                            'type' => 'text',
                            'label' => 'Aufgabe 3',
                            'content' => "Ihre Kursleiterin, Frau Müller, hat Sie zu einem Gespräch über Ihre persönlichen Lernziele eingeladen. Zu dem Termin können Sie aber nicht kommen.\n\nSchreiben Sie an Frau Müller. Entschuldigen Sie sich höflich und berichten Sie, warum Sie nicht kommen können.\n\nSchreiben Sie eine E-Mail (circa 40 Wörter).\nVergessen Sie nicht die Anrede und den Gruß am Schluss.",
                            'instruction' => 'Arbeitszeit: 15 Minuten',
                            'answer' => '',
                            'points' => 0,
                        ],
                        [
                            'id' => 'goethe-b1-write-a3-input',
                            'type' => 'writing',
                            'label' => 'Ihre Antwort zu Aufgabe 3',
                            'instruction' => 'Entschuldigungsschreiben zu Aufgabe 3 (ca. 40 Wörter)',
                            'answer' => '',
                            'points' => 20,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private static function telcC1(): array
    {
        return [
            'title' => 'telc Deutsch C1 Hochschule',
            'level' => 'C1',
            'source_pdf' => 'telc_deutsch_c1_uebungstest_1.pdf',
            'theme' => self::theme('#d8232a', '#202020', 'Instrument Sans'),
            'timings' => [
                ['label' => 'Leseverstehen und Sprachbausteine', 'minutes' => 90],
                ['label' => 'Hoerverstehen', 'minutes' => 40],
                ['label' => 'Schriftlicher Ausdruck', 'minutes' => 70],
                ['label' => 'Mündliche Prüfung', 'minutes' => 16],
            ],
            'sections' => [
                array_merge(self::section('telc-c1-reading-language', 'Leseverstehen und Sprachbausteine', 90, [
                    [
                        'id' => 'telc-c1-lv1-instruction',
                        'type' => 'text',
                        'label' => 'Leseverstehen Teil 1',
                        'content' => "Lesen Sie den folgenden Text. Welche der Sätze a–h gehören in die Lücken 1–6? Es gibt jeweils nur eine richtige Lösung. Zwei Sätze können nicht zugeordnet werden. Markieren Sie Ihre Lösungen für die Aufgaben 1–6 auf dem Antwortbogen.\nLücke (0) ist ein Beispiel.\n\nSie lesen den folgenden Artikel in einer Zeitschrift:",
                        'instruction' => '',
                        'answer' => '',
                        'points' => 0,
                    ],
                    self::inlineGap('telc-c1-lv1-cloze', 'Der Mars – bald eine Reise wert?', "Könnten wir den eisigen Mars „terraformen“? Die Antwort lautet: z. Wir wissen heute, dass es auf dem Mars einst wärmer war als heute. Es muss sogar Flüsse und Seen gegeben haben. --1-- Indem man genug Treibhausgase in seine Atmosphäre bläst. Ein großer Teil des Kohlendioxids (CO2), das den Roten Planeten früher wärmte, ist wohl noch vorhanden – gebunden in den Eiskappen der Pole und im Boden. Gefrorenes Wasser wurde ebenfalls nachgewiesen. Technisch ist es also machbar, dort wieder Pflanzen wachsen zu lassen.\n\n„Wir müssen nur die Temperatur ein wenig erhöhen und ein paar Samen ausstreuen“, sagt der Nasa-Planetenforscher Chris McKay, „--2--.“ Um den Prozess in Gang zu setzen, kann man aus der Marsoberfläche Perfluorkohlenstoffe (PFC) gewinnen. PFC sind wirksame Treibhausgase. Sobald sich die Atmosphäre aufheizt, wird gefrorenes CO2 aus den Böden frei. Ein sich verstärkender Treibhauseffekt entsteht. Der Luftdruck steigt, und irgendwann fließt wieder Wasser. Menschliche Pioniere können die Felsen mit Bakterien und Flechten „impfen“, die auf der Erde gedeihen. Später bringt man Moose aus, dann Bäume. Die Pflanzen reichern die Atmosphäre mit Sauerstoff an, doch bis wir sie atmen können, werden viele tausend Jahre vergehen.\n\nRobert Zubrin, der Präsident einer Organisation zur Besiedlung des Mars, träumt schon von Städten auf dem Mars. --3-- „Wir werden auf dem Mars leben wie die Forscher heute in der Antarktis“ – in kleinen Stationen also, ohne viel Komfort. Doch aus den Erfahrungen bei der Umgestaltung des Mars könnten wir viel lernen, um künftig die Erde besser zu bewahren, meint er. Das sind freilich noch Gedankenspiele. Zunächst will die Nasa wieder auf dem Mond landen oder auf einem Asteroiden. --4--. Was es kosten würde, den Roten Planeten zu begrünen, ist noch nicht einmal geschätzt.\n\nDer Zeitplan für eine potentielle Besiedlung: --5--. Nach seinem sechs Monate dauernden Flug zum Mars installiert zunächst jedes Team eine kleine Wohneinheit und legt damit den Grundstein für die Besiedlung. Die ersten 100 Jahre: Die Atmosphäre wird dichter, wenn man das an den Polkappen gebundene CO2 freisetzt. Um das zu erreichen, erzeugen Fabriken Treibhausgase. Auch das Sonnenlicht konzentrierende Spiegel könnten das Eis auftauen. 200-600 Jahre: Regen fällt und Wasser fließt, sobald ausreichend CO2 in der Atmosphäre die mittlere Temperatur über den Gefrierpunkt angehoben hat. Bakterien, Algen und Flechten beginnen, die steinige Wüste zu besiedeln. Später wachsen auch Blütenpflanzen und irdische Bäume aus nördlichen Breiten. 900 Jahre: Die Energie für die Städte wird anfangs von Atomkraftwerken und Windkraftanlagen produziert. --6-- 1000 Jahre: Marsbewohner können nur mit Atemgeräten ins Freie, weil der Sauerstoffgehalt der Luft sehr langsam steigt. Langfristig wird der Mars seine Atmosphäre auch wieder verlieren und erneut abkühlen.", [
                        ['options' => 'a|a,b|b,c|c,d|d,e|e,f|f,g|g,h|h', 'answer' => 'c'],
                        ['options' => 'a|a,b|b,c|c,d|d,e|e,f|f,g|g,h|h', 'answer' => 'e'],
                        ['options' => 'a|a,b|b,c|c,d|d,e|e,f|f,g|g,h|h', 'answer' => 'f'],
                        ['options' => 'a|a,b|b,c|c,d|d,e|e,f|f,g|g,h|h', 'answer' => 'g'],
                        ['options' => 'a|a,b|b,c|c,d|d,e|e,f|f,g|g,h|h', 'answer' => 'd'],
                        ['options' => 'a|a,b|b,c|c,d|d,e|e,f|f,g|g,h|h', 'answer' => 'b'],
                    ], 2),
                    [
                        'id' => 'telc-c1-lv1-sentences',
                        'type' => 'text',
                        'label' => 'Satzoptionen',
                        'content' => "Beispiel:\nz  Wahrscheinlich wäre es zu schaffen.\n\na  Allerdings war die Atmosphäre niemals so dicht wie bei der Erde.\nb  Auf lange Sicht wären die besten Stromlieferanten aber wohl Fusionsreaktoren.\nc  Aus eigener Erfahrung wissen wir auch, wie man einen kühlen Planeten aufheizt:\nd  Das 1000-Jahres-Projekt beginnt mit einer Reihe von Expeditionen.\ne  Den Rest der Arbeit wird das Leben selber erledigen.\nf  Der Nasa-Experte Chris McKay sieht die Aussichten jedoch nüchtern:\ng  Doch selbst dafür fehlt es an Geld.\nh  Es gibt daher keine Gewässer mit flüssigem Wasser auf der Marsoberfläche.",
                        'instruction' => '',
                        'answer' => '',
                        'points' => 0,
                    ],
                    [
                        'id' => 'telc-c1-lv2-instruction',
                        'type' => 'text',
                        'label' => 'Leseverstehen Teil 2',
                        'content' => 'Lesen Sie den folgenden Text. In welchem Textabsatz a–e finden Sie die Antworten auf die Fragen 7–12? Es gibt jeweils nur eine richtige Lösung. Jeder Absatz kann Antworten auf mehrere Fragen enthalten. Markieren Sie Ihre Lösungen für die Aufgaben 7–12 auf dem Antwortbogen.',
                        'instruction' => '',
                        'answer' => '',
                        'points' => 0,
                    ],
                    [
                        'id' => 'telc-c1-lv2-text',
                        'type' => 'text',
                        'label' => 'Ein Selbstversuch als Hobbybäuerin',
                        'content' => "a\nIch bin stolz auf mich und fühle mich richtig gut, weil ich meine ambitionierten Ziele bis jetzt immer realisieren konnte. Damit meine ich nicht nur die beruflichen Ziele, sondern auch meine privaten, denn diese können ja bekanntlich gleichermaßen anspruchsvoll sein. Wenn ich jetzt nach vielen Jahren Bilanz ziehe, macht mich das Ergebnis sehr zufrieden. Bin ich vielleicht sogar eine Heldin? Diese Frage habe ich mir nun eine Woche lang gestellt. Nicht, weil ich unbedingt mit einer Großtat in die Geschichtsbücher eingehen wollte, sondern vor einer weiteren Herausforderung stehe. Eine selbstauferlegte Aufgabe, die es zu meistern gilt. Ich muss mich bis Ende Oktober entscheiden, ob ich mein Stück Gartenland auch im nächsten Jahr wieder bestellen will. Wenn ja, wird es für mich reserviert, sobald der Frühling kommt. Es liegt vor den Toren Münchens, und ich habe darauf nun einen Sommer lang Gemüse angebaut. Wenn ich Hobbybäuerin bleiben will, muss ich mich bereits jetzt als Ackerheldin für das kommende Jahr anmelden.\n\nb\nBin ich nun eine Heldin oder nicht? Nun kann man über meine Leibesstärke diskutieren; ich würde jedenfalls behaupten, meine geistige Stärke ist ausgeprägter. Und besondere Tapferkeit kann ich mir auch nicht wirklich zusprechen. Ich war höchstens eine Heldin der Arbeit, als ich auf dem Acker herumkroch. Menschen, die mich kennen, hätten mich in meinem Outfit nicht wiedererkannt. Ich habe gebuddelt, was das Zeug hielt, und an besonders warmen Tagen konnte ich meinen Körpergeruch selbst kaum ertragen. Mit schwarzen Fingernägeln und zerzaustem Haar fuhr ich dann nach getaner Arbeit nach Hause und war froh, dass ich zumindest dazu noch in der Lage war. Ich hatte also die Schlacht gewonnen. Mein Leben habe ich dabei nicht riskiert, höchstens ab und zu meine körperliche Unversehrtheit, wenn die Mücken mal wieder zubissen oder der Muskelkater schmerzte. Immerhin stellte ich mich allwöchentlich dem Kampf gegen einen unerbittlichen Kontrahenten: das Unkraut. So gesehen bin ich zumindest eine Ackerheldin, die den Kampf für sich entscheiden konnte. Häufig siegte aber auch der Acker – über meine Kraft (mehr als zehn Gießkannen Wasser konnte ich bei 30 Grad einfach nicht schleppen) und vor allem über meinen inneren Schweinehund. Wenn ich mich mal nicht aufraffte, zum Feld zu fahren, plagte mich das schlechte Gewissen.\n\nc\nEinige Freundschaften hat es mich auch gekostet: Ein Freund redet nicht mehr mit mir, seit er zum Unkrautrupfen mitkam. Wir gerieten über die Frage, wie weit man Salat von Radieschen fernhalten sollte, in einen lächerlichen Streit. Eine Freundin, die sich an einem Nachmittag als Erntehelferin beteiligt hatte, wurde von einer Wespe ins Ohr gestochen und musste sich in ärztliche Behandlung begeben. „Du und dein Acker!“, schnaubte sie später ins Telefon, „wir sehen uns wieder, wenn die Saison vorbei ist.“ Andere Freunde riefen mich immer seltener an, weil sie befürchteten, ich könnte sie um Hilfe bitten. In dieser Zeit begann ich immer häufiger über meine Freunde nachzudenken und erkannte den Wert meiner Freundschaften. Sie konnten nicht einmal banalen Dingen standhalten. Nun wusste ich, wie meine sogenannten Freunde tickten. „So ein unzuverlässiges und treuloses Pack“, dachte ich mir ganz oft. Mit diesen Subjekten habe ich mal meine Zeit verbracht; welch eine Verschwendung.\n\nd\nMan muss auch beim Bewältigen der Erntemengen Ideen sprießen lassen, schließlich wird vieles gleichzeitig reif. Und man muss bestimmte Abstriche machen, wenn man nur einmal pro Woche vor Ort sein kann. Das ist wie im wirklichen Leben: Bedenken Sie, dass wir auch im Job gleichzeitig an vielen Projekten arbeiten und uns selten den Luxus gönnen, eine Sache nach der anderen abzuarbeiten. Das gilt natürlich auch für unser Privatleben, wo wir meist mehrere Baustellen haben und oft gar nicht wissen, wo wir anfangen sollen. Was die Abstriche angeht, so verhält es sich sich nicht anders. Man hat in der Regel immer Zeitdruck und ein begrenztes Budget. Das ist einfach die Reality, ob es uns nun gefällt oder nicht. Ich kann jedem, der sich in solchen Situationen überfordert fühlt, nur raten, nicht zu perfektionistisch an eine Sache heranzugehen. Lernen Sie Kompromisse zu machen und damit zu leben. Mieten Sie sich notfalls einen Garten, so wie ich es gemacht habe. Ich garantiere Ihnen: Sie lernen dabei nicht nur etwas über Gemüseanbau!\n\ne\nIm Grunde haben wir uns beide heldenhaft geschlagen, der Acker und ich. Er hat mich einen Sommer lang genauso überlebt wie ich ihn. Wenn ich mich nächstes Jahr wieder auf das Abenteuer „Garten“ einlasse, will ich es tapfer und aufopfernd tun, aber ich will nachsichtig mit mir sein. Superman hatte ebenfalls einen Bürojob, und er rettete die Welt schließlich auch nicht in eineinhalb Stunden.",
                        'instruction' => '',
                        'answer' => '',
                        'points' => 0,
                    ],
                    ['id' => 'telc-c1-lv2-7', 'type' => 'matching', 'label' => 'Aufgabe 7', 'instruction' => 'ist die Autorin belehrend?', 'answer' => 'd', 'options' => 'a,b,c,d,e', 'points' => 2],
                    ['id' => 'telc-c1-lv2-8', 'type' => 'matching', 'label' => 'Aufgabe 8', 'instruction' => 'ist die Autorin polemisch?', 'answer' => 'c', 'options' => 'a,b,c,d,e', 'points' => 2],
                    ['id' => 'telc-c1-lv2-9', 'type' => 'matching', 'label' => 'Aufgabe 9', 'instruction' => 'übt die Autorin Selbstironie?', 'answer' => 'b', 'options' => 'a,b,c,d,e', 'points' => 2],
                    ['id' => 'telc-c1-lv2-10', 'type' => 'matching', 'label' => 'Aufgabe 10', 'instruction' => 'übt die Autorin Kritik?', 'answer' => 'd', 'options' => 'a,b,c,d,e', 'points' => 2],
                    ['id' => 'telc-c1-lv2-11', 'type' => 'matching', 'label' => 'Aufgabe 11', 'instruction' => 'zieht die Autorin Lehren für die Zukunft?', 'answer' => 'e', 'options' => 'a,b,c,d,e', 'points' => 2],
                    ['id' => 'telc-c1-lv2-12', 'type' => 'matching', 'label' => 'Aufgabe 12', 'instruction' => 'will sich die Autorin profilieren?', 'answer' => 'a', 'options' => 'a,b,c,d,e', 'points' => 2],

                    // Teil 3
                    [
                        'id' => 'telc-c1-lv3-instruction',
                        'type' => 'text',
                        'label' => 'Leseverstehen Teil 3',
                        'content' => 'Lesen Sie den folgenden Text und die Aussagen 13–23. Welche der Aussagen sind richtig (+), falsch (-) oder gar nicht im Text enthalten (x)? Es gibt jeweils nur eine richtige Lösung. Markieren Sie Ihre Lösungen für die Aufgaben 13–23 auf dem Antwortbogen.',
                        'instruction' => '',
                        'answer' => '',
                        'points' => 0,
                    ],
                    [
                        'id' => 'telc-c1-lv3-text',
                        'type' => 'text',
                        'label' => 'Die Wissenschaft und ihre Protagonisten im Film',
                        'content' => "1 Die Wissenschaft und ihre Protagonisten sind inzwischen zu Lieblingen der Filmschaffenden avanciert – diesen Eindruck jedenfalls bekommt schnell, wer einmal die großen Kinofilme Revue passieren lässt: Mit Alan Turing und Stephen Hawking wurden innerhalb eines Jahres in „The Imitation Game“ beziehungsweise „The Theory of Everything“ gleich zwei genialen Forschern monumentale filmische Denkmäler gesetzt, während ein sarkastisch-sympathischer Botaniker und dessen Erfindergeist ins Zentrum des Geschehens auf dem Roten Planeten rückte. Nur wenig früher hatten in „Interstellar“ und „Contagion“ Wissenschaftler heldenhaft die Menschheit vor der sicheren Auslöschung durch Umweltkatastrophen und Killerviren bewahrt. Und in „Gravity“ kämpft die Filmheldin Dr. Ryan Stone nicht nur gegen die Unwirtlichkeit des Weltraums an, sondern auch gegen ihre eigenen Schwächen.\n\n2 All das ist insofern bemerkenswert, als alle westlichen Filmkulturen lange Zeit auf wenige, sich ähnelnde Typen von Wissenschaftlern setzten, die sich von den Anfängen des filmischen Erzählens bis in die Gegenwart hinein kaum verändert haben. Das schmale Spektrum ihrer Charaktereigenschaften reichte vom machtbesessenen Bösewicht bis zur Lachnummer mit wirren Haaren und weißem Kittel. Die Ambivalenzen einer komplexen Persönlichkeit suchte man meist vergeblich.\n\n3 In der überwiegenden Mehrzahl der Fälle ist der Wissenschaftler im Film ein Verrückter, ein „mad scientist“. In seiner bösen, düsteren Ausprägung geht er in geheimen Labors monströsen Forschungsprojekten nach, losgelöst von der Welt und der Kontrolle einer Akademie. Oft experimentiert er an Tieren oder Menschen, züchtet künstliches Leben oder arbeitet auf die Vernichtung der ganzen Menschheit hin. Bösewichte dieser Art begegnen uns als „Dr. No“, James Bonds allererstem Gegenspieler, in Stanley Kubricks „Dr. Seltsam“, in der Figur des Rotwang in Fritz Langs „Metropolis“, der mit einem Maschinenmenschen die Massen aufzuwiegeln versucht, sowie in den zahlreichen Frankenstein-Verfilmungen.\n\n4 Führt der verrückte Wissenschaftler einmal nichts Böses im Schilde, zeitigt sein Forschergeist im Unterhaltungsfilm eben unbeabsichtigt schlimme Folgen – etwa weil ein Experiment schiefgeht oder die Ergebnisse seiner harten Arbeit für einen anderen als den vorgesehenen Zweck verwendet werden. So wird John Hammond in „Jurassic Park“ in jenem Moment zum „mad scientist“ wider Willen, in dem die von ihm erschaffenen Dinosaurier den Freizeitpark samt Personal und Besuchern verschlingen. Wenig besser ergeht es Dr. Bruce Banner in „The Incredible Hulk“, der sich infolge radioaktiver Bestrahlung unversehens in ein gemeingefährliches grünes Monster verwandelt. Ein anderes berühmtes Beispiel ist der Wissenschaftler aus „Die Fliege“. Er ist mit einem Experiment zur Auflösung und Wiederzusammensetzung von Materie beschäftigt. Bei einem Selbstversuch gelangt eine Fliege in die zu diesem Zweck erbaute Maschine und sorgt für eine Vermischung der Körper. Der Mann hat plötzlich Kopf und Arme einer Fliege und muss sich von seiner Frau, die ihn aber nur verhüllt sehen darf, versorgen lassen.\n\n5 Kaum schmeichelhafter ist die Rolle, die dem Wissenschaftler in der Komödie zufällt: Dort tritt er, wie beispielsweise Doc Brown in „Back to the Future“, typischerweise als schrulliger Professor mit wirrem Haar und weißem Kittel in Erscheinung, der bei aller Tollpatschigkeit aber immerhin eine harmlose, zuweilen liebenswerte Gestalt ist. Nur als Abenteurer dürfen Wissenschaftler im Actionfilm auch einmal in die Rolle des Helden schlüpfen. Die Indiana-Jones-Filme sind ein eindrückliches Beispiel dafür – wenngleich Jones’ Ehrgeiz offensichtlich weniger der Archäologie als dem Erbeuten von wertvollen Artefakten gilt.\n\n6 „Im Film gibt es viele schlechte und nur wenige gute Wissenschaftler, aber vor allem nur eine überschaubare Anzahl von Stereotypen“, konstatieren deshalb Peter Weingart und Petra Pansegrau von der Universität Bielefeld, die die Darstellung von Wissenschaftlern im Film einer systematischen Untersuchung unterzogen. Tatsächlich sind die Rollenklischees, die Wissenschaftler im Film ausfüllen, aber sehr viel älter als die Filmgeschichte. Sie lassen sich bis in die Literatur des Mittelalters zurückverfolgen, wo der „böse Alchemist“ als Prototyp des skrupellosen „mad scientist“ in Erscheinung tritt. In beiden spiegelt sich laut Weingart jene Ambivalenz, mit der Wissenschaft und Technik seit je wahrgenommen werden: Forschung weckt Hoffnungen, aber auch tiefgreifende Ängste; sie verspricht Fortschritt und Wohlstand, birgt aber in der öffentlichen Wahrnehmung zugleich große Zerstörungspotenziale.\n\n7 Diese extremen Gegensätze verdichten Literatur und Film in der Figur des Wissenschaftlers. In diesem Sinne manifestiert sich im Stereotyp des „mad scientist“ ein gesellschaftlich weitverbreitetes, fundamentales Misstrauen wissenschaftlichen Tätigkeiten gegenüber, das auch damit begründet wird, dass, was einmal gedacht ist, nicht mehr rückgängig gemacht werden kann. Das erklärt auch, warum im Film auch vor allem Mediziner, Physiker und Chemiker als Bösewichte herhalten müssen: Ihre Disziplinen vermögen die Welt zum Guten wie zum Bösen zu verändern und bieten entsprechend viel Raum für Schreckensszenarien. Astronomen und Archäologen hingegen, deren wissenschaftliche Arbeit sich auf das Beobachten beschränkt, fällt im Film die Rolle des Abenteurers zu.\n\n8 Nun kann die Wissenschaft von heute kaum als weniger komplex oder weniger konfliktgeladen gelten als jene vergangener Zeiten. Nach wie vor taugt sie als Lieferant für Szenarien globaler Bedrohung, bieten sich doch Kernkraft, Killerviren, Gen- und Nanotechnologie oder künstliche Intelligenz als Kulisse für filmische Dystopien nach den alten Schemata an. Woran also liegt es, dass Filmschaffende neuerdings einen anderen Umgang mit ihren Wissenschaftlerfiguren pflegen?\n\n9 Nur vordergründig seien die alten Klischees verschwunden, sagt dazu Sven Stollfuss, Medienwissenschaftler an der Universität Bayreuth. Doch sei das Bild bunter geworden, weil stereotype Grundmuster von Filmcharakteren heute stärker vermischt, untereinander „hybridisiert“ würden, um die Figuren plastischer erscheinen zu lassen. Dieser Wandel ist laut Stollfuss nicht nur auf Wissenschaftler beschränkt, sondern lässt sich genauso bei anderen Filmfiguren beobachten: Auch Verbrecher sind im Film nicht mehr nur böse, Ärzte geben sich gelegentlich zynisch statt empathisch, und Polizisten müssen nicht mehr laufend den Helden spielen.\n\n10 Diese Entwicklung hänge mit einem allgemeinen Bestreben hin zu einer vielschichtiger angelegten Erzählweise und Storyentwicklung zusammen, sagt Stollfuss. Filmschaffende bemühten sich heute generell um authentischere Charaktere, recherchierten genauer. Aus holzschnittartigen Stereotypen würden dadurch vielschichtige Protagonisten mit jeweils individuellen Zügen.\n\n11 Das heute vorherrschende Stereotyp vom professionellen Wissenschaftler ist demnach das eines Nerds, bei dem fachliche Brillanz mit mehr oder weniger schwerwiegenden sozialen Handicaps und einem gewissen Außenseitertum einhergeht – dabei aber Raum für Coolness, Liebenswürdigkeit, ausgeprägten Sarkasmus oder eine Frohnatur lässt. Am deutlichsten zeigt sich das derzeit in aktuellen US-Serien, etwa in den Protagonisten von „CSI – Crime Scene Investigation“, die dank wissenschaftlicher Kompetenz in der Verbrechensbekämpfung brillieren. Und „The Big Bang Theory“ verleiht gleich einer ganzen Wohngemeinschaft voller Wissenschafts-Nerds Kultstatus.\n\n12 Ganz passé sind die alten Stereotype also nicht. Doch transportieren Wissenschaftler im Film heute keine gezielte, grundsätzliche Kritik am Wissenschaftsbetrieb mehr, wie das bei den „mad scientists“ der Fall war. Das mag auch daran liegen, dass die erzählten Handlungen komplexer geworden sind. Drohszenarien gehen kaum noch monokausal von einem Bösewicht aus, sondern werden eher als strukturell bedingt dargestellt: Beispielsweise gelangen die üblen Viren in „Contagion“ ganz banal durch Mutation in die Welt, obwohl zum Zeitpunkt der Entstehung des Films das Horrorszenario eines irren Biologen, der ein Killervirus in die Welt setzt, in Wissenschaft und Medien breit diskutiert wurde.\n\n13 Derart befreit von der Rolle des Übeltäters können sich die neuen Wissenschaftler im Film nun ganz auf ihre Laborarbeit konzentrieren – und nebenbei mal eben die Welt retten.",
                        'instruction' => '',
                        'answer' => '',
                        'points' => 0,
                    ],
                    ...self::answerElements('telc-c1-lv3-', 13, ['-', '+', '-', '-', 'x', '+', 'x', '+', '+', '+', '-'], 'true_false', 2, '+,-,x'),
                    [
                        'id' => 'telc-c1-lv3-24',
                        'type' => 'multiple_choice',
                        'label' => 'Aufgabe 24',
                        'content' => '',
                        'instruction' => 'Welche der Überschriften a, b oder c trifft die Aussage des Textes am besten?',
                        'answer' => 'c',
                        'points' => 2,
                        'options' => 'a,b,c',
                    ],

                    // Sprachbausteine
                    [
                        'id' => 'telc-c1-sb-instruction',
                        'type' => 'text',
                        'label' => 'Sprachbausteine',
                        'content' => 'Wählen Sie das passende Wort für jede Lücke aus den Optionen a, b, c oder d.',
                        'instruction' => '',
                        'answer' => '',
                        'points' => 0,
                    ],
                    self::inlineGap('telc-c1-sb-inline', 'Ein merkwürdiger Akzent', "Ein merkwürdiger Akzent\n\nStellen Sie sich vor, Sie wachen eines Morgens auf und wünschen Ihrem Partner einen guten Morgen – --0-- die Worte aus Ihrem Mund klingen --1--. Dann könnte es sich um eine --2-- seltene neurologische Störung handeln: das „Foreign Accent Syndrome“, zu Deutsch Fremdsprachenakzentsyndrom.\n\nBeim Fremdsprachenakzentsyndrom sprechen Patienten plötzlich ihre Muttersprache mit einem --3-- regionalen oder ausländischen Akzent. Meist geht die veränderte Sprechweise auf Hirnschäden --4-- eines Schlaganfalls oder einer Verletzung --5--. Die Erkrankung beeinflusst die Sprachmelodie, wobei die --6-- Lautbildung und Betonung fälschlicherweise den Eindruck erweckt, der Betroffene sei kein Muttersprachler.\n\nEin in der Fachwelt bekanntes Beispiel ist der Fall von Sabine Kindschuh. Hätte die Patientin ein paar hundert Jahre früher gelebt, --7-- sie eine Kandidatin für den Scheiterhaufen gewesen. Ihr Heimatdorf Oberalba in Thüringen war im 17. Jahrhundert Schauplatz mehrerer Hexenprozesse, und was mit der 57-Jährigen --8--, hätte man einst wohl übernatürlichen Mächten --9--: Sie sprach nach einem Schlaganfall plötzlich mit einem Tonfall, der an den Schweizer Dialekt erinnerte. --10-- sie auch versuchte, wieder normal zu sprechen, es gelang ihr einfach nicht.\n\nDie --11-- Ärzte vermuteten psychische Ursachen dahinter, --12-- eine regionale Tageszeitung berichtete. Erst ein Sprachtherapeut erkannte: Es handelte sich um das „Foreign Accent Syndrome“, eine neurologische Erkrankung, die --13-- selten ist, dass ein durchschnittlicher Mediziner in seinem gesamten Leben keinen einzigen Betroffenen --14-- bekommt. Experten sprechen von weltweit rund 60, maximal 100 Fällen, die überhaupt jemals --15--.\n\n--16-- beschrieb der französische Neurologe Pierre Marie 1907: Eine Pariserin, die einen Schlaganfall erlitten hatte und danach rechtsseitig gelähmt war, begann --17-- mit einem elsässisch klingenden Akzent zu sprechen. Mehr Details sind von einer anderen historischen Patientin überliefert: Der Experte --18-- Sprachstörungen Georg Herman Monrad-Krohn dokumentierte 1943 die Geschichte der Norwegerin Astrid L., die bei einem Unfall am linken Stirnhirn verletzt wurde. Sie --19-- mehrere Tage bewusstlos und danach rechtsseitig gelähmt gewesen sein. Zunächst hatte sie enorme Schwierigkeiten, einfache Gegenstände wie eine Streichholzschachtel --20--, und machte zahlreiche Grammatikfehler. Mit der Zeit lernte sie zwar wieder, korrekt und flüssig zu sprechen, allerdings mit einem Tonfall, der ihre Landsleute offenbar --21-- deutschen Akzent erinnerte.", [
                        ['options' => 'a|dagegen,b|doch,c|hingegen,d|jedoch', 'answer' => 'b'],
                        ['options' => 'a|anderweitig,b|artfremd,c|auswärtig,d|fremdartig', 'answer' => 'd'],
                        ['options' => 'a|durchweg,b|extra,c|gehörig,d|überaus', 'answer' => 'd'],
                        ['options' => 'a|vermeintlich,b|vermeintliche,c|vermeintlichem,d|vermeintlicher', 'answer' => 'a'],
                        ['options' => 'a|dank,b|infolge,c|kraft,d|zwecks', 'answer' => 'b'],
                        ['options' => 'a|hervor,b|hinaus,c|voraus,d|zurück', 'answer' => 'd'],
                        ['options' => 'a|vererbbare,b|veränderbare,c|veränderliche,d|veränderte', 'answer' => 'd'],
                        ['options' => 'a|sei,b|war,c|wäre,d|würde', 'answer' => 'c'],
                        ['options' => 'a|für sich hatte,b|mit sich zog,c|von sich gab,d|vor sich ging', 'answer' => 'd'],
                        ['options' => 'a|angeschrieben,b|gutgeschrieben,c|vorgeschrieben,d|zugeschrieben', 'answer' => 'd'],
                        ['options' => 'a|Desto,b|So sehr,c|So weit,d|Weit mehr', 'answer' => 'b'],
                        ['options' => 'a|behandelnde,b|behandelnden,c|behandelte,d|behandelten', 'answer' => 'b'],
                        ['options' => 'a|als,b|das,c|die,d|wie', 'answer' => 'a'],
                        ['options' => 'a|der Art,b|Derart,c|derart,d|der-Art', 'answer' => 'c'],
                        ['options' => 'a|im Blick,b|in Sicht,c|ins Auge,d|zu Gesicht', 'answer' => 'd'],
                        ['options' => 'a|bekannt gewesen war,b|bekannt geworden,c|bekannt sind,d|bekannt wurden', 'answer' => 'd'],
                        ['options' => 'a|Dem ersten Patienten,b|Den ersten Patienten,c|Der erste Patient,d|Des ersten Patienten', 'answer' => 'b'],
                        ['options' => 'a|daraufhin,b|indem,c|nach,d|nachdem', 'answer' => 'a'],
                        ['options' => 'a|auf,b|für,c|über,d|von', 'answer' => 'b'],
                        ['options' => 'a|ist,b|soll,c|war,d|wurde', 'answer' => 'b'],
                        ['options' => 'a|benannt,b|benennen,c|benennen können,d|zu benennen', 'answer' => 'd'],
                        ['options' => 'a|an einen,b|auf einen,c|mit einem,d|zu einem', 'answer' => 'a'],
                    ], 1),
                ], self::pages(8, 16)), ['layout_style' => 'telc_c1_l12']),
                self::section('telc-c1-listening-intro', 'Kandidatenblätter', 40, [
                    self::text('telc-c1-hv0-intro', 'Kandidatenblätter Hören', "Kandidatenblätter\nHören\ncirca 40 Minuten\n\nDas Modul Hören hat vier Teile.\nSie hören mehrere Texte und lösen Aufgaben dazu.\nLesen Sie jeweils zuerst die Aufgaben\nund hören Sie dann den Text dazu.\nFür jede Aufgabe gibt es nur eine\nrichtige Lösung.\nNach dem Hören haben Sie drei\nMinuten Zeit, Ihre Lösungen auf den\nAntwortbogen zu übertragen.\nEs werden nur die Lösungen und Texte\nauf dem Antwortbogen bewertet.\nBitte schreiben Sie mit einem blauen\noder schwarzen Stift.\nHilfsmittel (z. B. Wörterbücher, Handys/\nmobile Endgeräte) sind nicht erlaubt."),
                ], self::pages(17, 17)),                array_merge(self::section('telc-c1-listening', 'Hörverstehen', 40, [
                    self::audio('telc-c1-audio', 'Hörverstehen Audio', '/goethe-assets/goethe_zertifikat_c1_uebungstest_1_format_2016.mp3'),

                    self::text('telc-c1-hv1-instruction', 'Hörverstehen Teil 1: Kontroverse Meinungen über den Zoo', 'Sie hören eine Diskussionsrunde über Zoos. Sie hören den Text einmal. Ordnen Sie den Sprechern 1 bis 8 (Aufgaben 47 bis 54) die passenden Aussagen a bis j zu. Zwei Aussagen passen nicht. Markieren Sie Ihre Lösungen für die Aufgaben 47-54 auf dem Antwortbogen.'),
                    self::input('telc-c1-hv1-47', 'Aufgabe 47', 'matching', 'h', 1, 'a,b,c,d,e,f,g,h,i,j', 'Sprecher 1'),
                    self::input('telc-c1-hv1-48', 'Aufgabe 48', 'matching', 'c', 1, 'a,b,c,d,e,f,g,h,i,j', 'Sprecher 2'),
                    self::input('telc-c1-hv1-49', 'Aufgabe 49', 'matching', 'e', 1, 'a,b,c,d,e,f,g,h,i,j', 'Sprecher 3'),
                    self::input('telc-c1-hv1-50', 'Aufgabe 50', 'matching', 'f', 1, 'a,b,c,d,e,f,g,h,i,j', 'Sprecher 4'),
                    self::input('telc-c1-hv1-51', 'Aufgabe 51', 'matching', 'g', 1, 'a,b,c,d,e,f,g,h,i,j', 'Sprecher 5'),
                    self::input('telc-c1-hv1-52', 'Aufgabe 52', 'matching', 'j', 1, 'a,b,c,d,e,f,g,h,i,j', 'Sprecher 6'),
                    self::input('telc-c1-hv1-53', 'Aufgabe 53', 'matching', 'b', 1, 'a,b,c,d,e,f,g,h,i,j', 'Sprecher 7'),
                    self::input('telc-c1-hv1-54', 'Aufgabe 54', 'matching', 'd', 1, 'a,b,c,d,e,f,g,h,i,j', 'Sprecher 8'),

                    self::text('telc-c1-hv2-instruction', 'Hörverstehen Teil 2: Interview mit Reiseleiter Herr Rimbach', 'Sie hören ein Interview mit dem Reiseleiter Herr Rimbach. Sie hören den Text zweimal. Wählen Sie bei jeder Aufgabe 55 bis 64 die richtige Lösung a, b oder c. Markieren Sie Ihre Lösungen auf dem Antwortbogen.'),
                    self::input('telc-c1-hv2-55', 'Aufgabe 55', 'multiple_choice', 'b', 2, 'a,b,c', 'Herr Rimbach ist für die Reisegruppe leicht erkennbar, obwohl er...
a) keine auffälligen Requisiten dabei hat.
b) nicht durch seine Körpergröße auffällt.
c) unauffällige Kleidung bevorzugt.'),
                    self::input('telc-c1-hv2-56', 'Aufgabe 56', 'multiple_choice', 'a', 2, 'a,b,c', 'Herr Rimbach setzt Körpersprache bewusst ein, um...
a) einen positiven ersten Eindruck zu hinterlassen.
b) seine eigene Nervosität zu überspielen.
c) Urlaubern die Nervosität zu nehmen.'),
                    self::input('telc-c1-hv2-57', 'Aufgabe 57', 'multiple_choice', 'b', 2, 'a,b,c', 'Nörgler und Unruhestifter...
a) erkennt Herr Rimbach immer auf den ersten Blick.
b) fallen oft schon am Anreisetag unangenehm auf.
c) reagieren in Krisensituationen besonders panisch.'),
                    self::input('telc-c1-hv2-58', 'Aufgabe 58', 'multiple_choice', 'c', 2, 'a,b,c', 'In schwierigen Situationen...
a) benötigen ältere Urlauber oft besondere Aufmerksamkeit.
b) braucht Herr Rimbach manchmal schauspielerisches Talent.
c) kann Herr Rimbach oft schnell eine Lösung finden.'),
                    self::input('telc-c1-hv2-59', 'Aufgabe 59', 'multiple_choice', 'a', 2, 'a,b,c', 'Beschwerden über das Essen sind verständlich, weil...
a) das Angebot oft etwas eintönig ist.
b) keine landestypischen Gerichte serviert werden.
c) ungewöhnliche Gewürze verwendet werden.'),
                    self::input('telc-c1-hv2-60', 'Aufgabe 60', 'multiple_choice', 'b', 2, 'a,b,c', 'Das Klischee, dass Deutsche für alle Eventualitäten gerüstet sein wollen,...
a) hält Herr Rimbach inzwischen für überholt.
b) kann Herr Rimbach aus eigener Erfahrung bestätigen.
c) sieht Herr Rimbach als übertrieben an.'),
                    self::input('telc-c1-hv2-61', 'Aufgabe 61', 'multiple_choice', 'b', 2, 'a,b,c', 'Der deutsche Urlauber...
a) hat ein ausgeprägtes Bedürfnis nach Struktur.
b) hat ein Problem mit Wartezeiten.
c) kann auch sehr spontan sein.'),
                    self::input('telc-c1-hv2-62', 'Aufgabe 62', 'multiple_choice', 'c', 2, 'a,b,c', 'Die Verwendung des „Du“ schafft eine Vertrautheit, die...
a) auch auf Abenteuerreisen nicht gut ankommt.
b) auf Studienreisen absolut erwünscht ist.
c) zu Irritationen bzw. schwierigen Situationen führen kann.'),
                    self::input('telc-c1-hv2-63', 'Aufgabe 63', 'multiple_choice', 'c', 2, 'a,b,c', 'Besserwissern begegnet Herr Rimbach, indem er...
a) gar nicht erst auf sie eingeht.
b) gelassen und sachlich bleibt.
c) humorvolle Bemerkungen macht.'),
                    self::input('telc-c1-hv2-64', 'Aufgabe 64', 'multiple_choice', 'a', 2, 'a,b,c', 'Herr Rimbach...
a) ärgert sich über zu wenig Trinkgeld.
b) findet es unangenehm, Trinkgeld anzunehmen.
c) spendet sein Trinkgeld grundsätzlich.'),

                    self::text('telc-c1-hv3-instruction', 'Hörverstehen Teil 3: Vortragsreihe „Schöner wohnen“ - Martin Wintermeyer', 'Sie hören einen Vortrag. Sie hören den Text einmal. Schreiben Sie die fehlenden Informationen stichwortartig in die freien Zeilen 65 bis 74 in der rechten Spalte.'),
                    self::input('telc-c1-hv3-65', 'Aufgabe 65', 'fill_blank', 'gemütlich', 2, '', 'Die neue Wandfarbe bewirkt:'),
                    self::input('telc-c1-hv3-66', 'Aufgabe 66', 'fill_blank', 'warme und kalte', 2, '', 'Farbe ist nicht gleich Farbe. Wir unterscheiden'),
                    self::input('telc-c1-hv3-67', 'Aufgabe 67', 'fill_blank', 'kalt wirken', 2, '', 'Weiße Wände? Nein, weil:'),
                    self::input('telc-c1-hv3-68', 'Aufgabe 68', 'fill_blank', 'pastelltöne', 2, '', 'Alternativen:'),
                    self::input('telc-c1-hv3-69', 'Aufgabe 69', 'fill_blank', 'raumgröße', 2, '', 'Die richtige Farbwahl und -menge hängt ab von'),
                    self::input('telc-c1-hv3-70', 'Aufgabe 70', 'fill_blank', 'rot für dynamik', 2, '', 'Farbe und Persönlichkeit. Ein Beispiel:'),
                    self::input('telc-c1-hv3-71', 'Aufgabe 71', 'fill_blank', 'belebung', 2, '', 'Farbakzente. Vorteil von Farbakzenten:'),
                    self::input('telc-c1-hv3-72', 'Aufgabe 72', 'fill_blank', 'kleinen räumen', 2, '', 'Mehr Mut zur Farbe in'),
                    self::input('telc-c1-hv3-73', 'Aufgabe 73', 'fill_blank', 'tageslicht nutzen', 2, '', 'Der Faktor Licht. Wohngefühl positiv beeinflussen, indem'),
                    self::input('telc-c1-hv3-74', 'Aufgabe 74', 'fill_blank', 'steriles design', 2, '', 'Kritik an modernen Wohnungen'),
                ], self::pages(18, 21)), ['layout_style' => 'telc_c1_listening']),
                self::section('telc-c1-writing', 'Schriftlicher Ausdruck', 70, [
                    self::text('telc-c1-write-0', 'Kandidatenblätter Schreiben', "Kandidatenblätter\nSchreiben\n75 Minuten\n\nDas Modul Schreiben hat zwei Teile.\nIn Teil 1 schreiben Sie einen Diskussionsbeitrag für ein Forum.\nIn Teil 2 schreiben Sie eine Nachricht.\nSie können mit jeder Aufgabe beginnen.\nEs werden nur die Lösungen und Texte\nauf dem Antwortbogen bewertet.\nBitte schreiben Sie mit einem blauen\noder schwarzen Stift.\nHilfsmittel (z. B. Wörterbücher, Handys/\nmobile Endgeräte) sind nicht erlaubt."),
                    self::input('telc-c1-write-1', 'Teil 1 — Diskussionsbeitrag für ein Forum', 'writing', "In der Diskussion im Kurs hat Maria argumentiert, dass ein Pflichtfach für digitale Kompetenzen in der Schule wichtig ist, damit alle Schülerinnen und Schüler gleichzeitig mit dem digitalen Wandel Schritt halten können. Sie schrieb: ″Ohne digitale Grundbildung verlieren junge Menschen den Anschluss an die Arbeitswelt von morgen. Das Problem ist nicht das Internet selbst, sondern die fehlende Unterstützung durch Lehrkräfte, die selbst noch nicht ausreichend geschult wurden.″\n\nAntwort des Diskussionspartners: ″Ein Pflichtfach ist problematisch, weil viele Schülerinnen und Schüler ohnehin schon durch andere Fächer wie Politik oder Geografie kritisch mit digitalen Medien konfrontiert werden. Zudem fehlt es an ausreichend qualifiziertem Personal, um ein weiteres Fach anzubieten.″", 48, null, "Schreiben Sie einen Diskussionsbeitrag für ein Internet-Forum (ca. 120–150 Wörter).\n\nIn einem Kurs wird diskutiert, ob Schülerinnen und Schüler in der Schule ein Pflichtfach ‚Digitale Kompetenzen‘ einführen sollen. Nehmen Sie Bezug auf die Beiträge von Maria und einem anderen Kursteilnehmer. Formulieren Sie Ihre Position und nennen Sie mindestens zwei Argumente."),
                    self::input('telc-c1-write-2', 'Teil 2 — Nachricht', 'writing', '', 48, null, "Schreiben Sie eine Nachricht (ca. 50–80 Wörter) an einen Mitbewohner im Studentenwohnheim.\n\nIhr Mitbewohner hat Ihre Teekanne und Tassen immer wieder benutzt, ohne sie danach zu spülen. Bitten Sie höflich, aber bestimmt um Rücksichtnahme und erklären Sie kurz, warum Ihnen das wichtig ist."),
                ], self::pages(22, 22)),
                self::section('telc-c1-speaking', 'Mündliche Prüfung', 16, [
                    self::speakingElement('telc-c1-sp-1a', '1a: Präsentation', 'Präsentation (ca. 3 Minuten). Wählen Sie eines der Themen:\n1. Was ist der Nationalsport in einem Land Ihrer Wahl und welche Bedeutung hat er für die Gesellschaft?\noder\n2. Wer gehört Ihrer Meinung nach zu den einflussreichsten lebenden Personen weltweit? Wodurch zeichnet sich diese Person aus?', 6),
                    self::speakingElement('telc-c1-sp-1b', '1b: Zusammenfassung und Anschlussfragen', 'Zusammenfassung/Anschlussfragen (ca. 2 Minuten). Hören Sie sich die KI-Präsentation zum anderen Thema an und antworten Sie darauf.', 4),
                    self::speakingElement('telc-c1-sp-2', '2: Diskussion', 'Diskussion (6 Minuten). Diskutieren Sie das folgende Thema: Beziehungen mit großem Altersunterschied sind immer problematisch.', 6),
                    self::speakingElement('telc-c1-sp-global', 'Punkte für sprachliche Angemessenheit', 'Gesamtbewertung für sprachliche Angemessenheit (Flüssigkeit, Repertoire, Grammatische Richtigkeit, Aussprache/Intonation)', 32),
                ], self::pages(23, 24)),
            ],
        ];
    }
}
