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
            'C1' => array_merge(self::c1(), ['exam_provider' => 'telc']),
            'Goethe-B1' => array_merge(self::b1(), ['title' => 'Goethe Zertifikat B1', 'level' => 'B1', 'exam_provider' => 'goethe']),
            'Goethe-B2' => array_merge(self::b2(), ['title' => 'Goethe Zertifikat B2', 'level' => 'B2', 'exam_provider' => 'goethe']),
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
                        "</div>"
                    ),
                    self::input('C1-LV2-9', 'Aufgabe 9', 'multiple_choice', 'a', 2, json_encode([
                        ['key' => 'a', 'text' => 'ob sich Kinder zu viel online beschäftigen.'],
                        ['key' => 'b', 'text' => 'wie Kinder gefördert werden können.'],
                        ['key' => 'c', 'text' => 'wie stressig die Telefonnutzung der Eltern für Kinder ist.']
                    ]), 'Innerhalb der Familien wird oft thematisiert, ..'),
                    self::input('C1-LV2-10', 'Aufgabe 10', 'multiple_choice', 'b', 2, json_encode([
                        ['key' => 'a', 'text' => 'Aggressives Benehmen in der Familie.'],
                        ['key' => 'b', 'text' => 'Nachlassen der schulischen Leistung.'],
                        ['key' => 'c', 'text' => 'Unkonzentrierte Erledigung von Hausaufgaben.']
                    ]), 'Welche Folgen hat der Handykonsum der Eltern bei den Kindern?'),
                    self::input('C1-LV2-11', 'Aufgabe 11', 'multiple_choice', 'a', 2, json_encode([
                        ['key' => 'a', 'text' => 'Auswirkungen des Handykonsums von Eltern.'],
                        ['key' => 'b', 'text' => 'Folgen der regelmäßigen Handynutzung von Kindern.'],
                        ['key' => 'c', 'text' => 'Verhaltensauffälligkeit bei Kindern.']
                    ]), 'Was wurde in einem Experiment mit Elternpaaren untersucht?'),
                    self::input('C1-LV2-12', 'Aufgabe 12', 'multiple_choice', 'a', 2, json_encode([
                        ['key' => 'a', 'text' => 'Anspannung abzubauen.'],
                        ['key' => 'b', 'text' => 'chaotischen Zuständen vorzubeugen.'],
                        ['key' => 'c', 'text' => 'Probleme im Beruf zu lösen.']
                    ]), 'Laut den Wissenschaftlerinnen und Wissenschaftlern hilft das Handy den Eltern ..'),
                    self::input('C1-LV2-13', 'Aufgabe 13', 'multiple_choice', 'c', 2, json_encode([
                        ['key' => 'a', 'text' => 'dem Realitätsverlust der Eltern.'],
                        ['key' => 'b', 'text' => 'der kindlichen Sensibilität.'],
                        ['key' => 'c', 'text' => 'verminderter elterlicher Zuneigung.']
                    ]), 'Die Beziehung zwischen Eltern und Kindern leidet unter ..'),
                    self::input('C1-LV2-14', 'Aufgabe 14', 'multiple_choice', 'b', 2, json_encode([
                        ['key' => 'a', 'text' => 'in der Wohnung handyfreie Zonen einzurichten.'],
                        ['key' => 'b', 'text' => 'die Handynutzung bewusst zu reglementieren.'],
                        ['key' => 'c', 'text' => 'mit ihren Kindern regelmäßig aufs Handy zu verzichten.']
                    ]), 'Den Eltern wird empfohlen, ..'),
                    self::input('C1-LV2-15', 'Aufgabe 15', 'multiple_choice', 'b', 2, json_encode([
                        ['key' => 'a', 'text' => 'Aktivitäten frühzeitig geplant werden.'],
                        ['key' => 'b', 'text' => 'alle Mitspracherecht bei der Entscheidung haben.'],
                        ['key' => 'c', 'text' => 'sie leichter fällt, wenn Freunde daran teilnehmen.']
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
                self::section('c1-listening', 'Hoerverstehen', 40, [
                    self::audio('c1-audio', 'Hoerverstehen Audio', '/telc-assets/telc_deutsch_c1_uebungstest_1_format_2016.mp3'),
                    self::text('C1-HV1-instruction', 'Hörverstehen Teil 1: Kontroverse Meinungen über den Zoo', "Aussagen:\na Tiere im Zoo leiden unter Platzmangel und unnatürlichen Bedingungen.\nb Zoologische Gärten leisten einen wichtigen Beitrag zum Artenschutz.\nc Für Kinder ist der Zoobesuch eine unersetzliche Erfahrung.\nd Moderne Zoos bemühen sich um artgerechte Tierhaltung.\ne Die Kritik an Zoos ist oft einseitig und unreflektiert.\nf Zoos dienen primär der Unterhaltung und dem Profit.\ng Bildungsangebote in Zoos sollten ausgebaut werden.\nh Zoos vermitteln ein falsches Bild von der Natur.\ni Forschung im Zoo nutzt auch den Tieren in freier Wildbahn.\nj Zoobesuche fördern das Umweltbewusstsein der Besucher.", 'Ordnen Sie den Sprechern 1-8 die passende Aussage a-j zu. Zwei Aussagen passen nicht. Sie hören den Text einmal.'),
                    ...self::answerElements('C1-HV1-', 47, ['h', 'c', 'e', 'f', 'g', 'j', 'b', 'd'], 'matching', 1, 'a,b,c,d,e,f,g,h,i,j'),
                    self::text('C1-HV2-instruction', 'Hörverstehen Teil 2: Interview zum Thema Gruppenreisen', '', 'Wählen Sie die richtige Option (a, b oder c) für die Aufgaben 55-64. Sie hören den Text zweimal.'),
                    self::input('C1-HV2-55', 'Aufgabe 55', 'multiple_choice', 'a', 2, 'a,b,c', "Herr Rimbach ist für die Reisegruppe leicht erkennbar, obwohl er...\na) keine auffälligen Requisiten dabei hat.\nb) nicht durch seine Körpergröße auffällt.\nc) unauffällige Kleidung bevorzugt."),
                    self::input('C1-HV2-56', 'Aufgabe 56', 'multiple_choice', 'a', 2, 'a,b,c', "Herr Rimbach setzt Körpersprache bewusst ein, um...\na) einen positiven ersten Eindruck zu hinterlassen.\nb) seine eigene Nervosität zu überspielen.\nc) Urlaubern die Nervosität zu nehmen."),
                    self::input('C1-HV2-57', 'Aufgabe 57', 'multiple_choice', 'c', 2, 'a,b,c', "Nörgler und Unruhestifter...\na) erkennt Herr Rimbach immer auf den ersten Blick.\nb) fallen oft schon am Anreisetag unangenehm auf.\nc) reagieren in Krisensituationen besonders panisch."),
                    self::input('C1-HV2-58', 'Aufgabe 58', 'multiple_choice', 'b', 2, 'a,b,c', "In schwierigen Situationen...\na) benötigen ältere Urlauber oft besondere Aufmerksamkeit.\nb) braucht Herr Rimbach manchmal schauspielerisches Talent.\nc) kann Herr Rimbach oft schnell eine Lösung finden."),
                    self::input('C1-HV2-59', 'Aufgabe 59', 'multiple_choice', 'a', 2, 'a,b,c', "Beschwerden über das Essen sind nachvollziehbar, weil...\na) das Angebot oft etwas eintönig ist.\nb) keine landestypischen Gerichte serviert werden.\nc) ungewohnte Zutaten verwendet werden."),
                    ...self::answerElements('C1-HV2-', 60, ['b', 'a', 'c', 'b', 'a'], 'multiple_choice', 2, 'a,b,c'),
                    self::text('C1-HV3-instruction', 'Hörverstehen Teil 3: Vortragsreihe „Schöner wohnen“', '', 'Vervollständigen Sie die Notizen stichwortartig.'),
                    self::input('C1-HV3-65', 'Aufgabe 65', 'fill_blank', 'fließenden Übergang', 2, null, 'Die neue Wandfarbe bewirkt...'),
                    self::input('C1-HV3-66', 'Aufgabe 66', 'fill_blank', 'mehr als 9 Millionen', 2, null, 'Wir unterscheiden beim Auge...'),
                    self::input('C1-HV3-67', 'Aufgabe 67', 'fill_blank', 'Emotionen nicht an', 2, null, 'Weiße Wände? Nein, weil...'),
                    self::input('C1-HV3-68', 'Aufgabe 68', 'fill_blank', 'Off-White', 2, null, 'Alternativen zu reinem Weiß...'),
                    self::input('C1-HV3-69', 'Aufgabe 69', 'fill_blank', 'Dimensionen', 2, null, 'Die richtige Farbwahl hängt ab von...'),
                    ...self::answerElements('C1-HV3-', 70, ['kräftigen Farben', 'Kontrast', 'kurz aufhalten', 'Beleuchtungen', 'steril'], 'fill_blank', 2),
                ], self::pages(17, 19)),
                self::section('c1-writing', 'Schriftlicher Ausdruck', 70, [
                    self::input('C1-WRITE-1', 'Aufgabe Schriftlicher Ausdruck', 'writing', "Stellungnahme zum Thema 1: Soll man Geld spenden?\n\nDie Frage, ob private Geldspenden eine sinnvolle Möglichkeit zur Hilfeleistung darstellen oder ob Gelder primär in bürokratischen Strukturen versickern, wird kontrovers diskutiert. \nEinerseits ermöglichen Spenden die Finanzierung von Hilfsprojekten weltweit, die ohne private Mittel nicht existieren könnten. Insbesondere bei Naturkatastrophen ist schnelle finanzielle Hilfe überlebenswichtig. \nAndererseits ist die Kritik an den hohen Verwaltungskosten großer Organisationen nicht unberechtigt. Dennoch überwiegt der Nutzen, da seriöse Organisationen durch Transparenzberichte nachweisen, dass der Großteil der Gelder die Betroffenen erreicht. \nZusammenfassend lässt sich sagen, dass gezieltes und informiertes Spenden an zertifizierte Organisationen einen unverzichtbaren Beitrag zur globalen Solidarität leistet.", 48, null, "Wählen Sie eines der beiden Themen aus und verfassen Sie einen zusammenhängenden Text (ca. 350 Wörter).\n\nThema 1: Soll man Geld spenden?\nDiskutieren Sie, ob Spendengelder tatsächlich sinnvoll eingesetzt werden oder primär in der Verwaltung versickern. Nehmen Sie Stellung.\n\nThema 2: Überwachungssoftware für Handys der Kinder\nDiskutieren Sie, ob Eltern die Handyaktivitäten ihrer Kinder überwachen sollten. Wie verhält es sich mit dem Recht auf Privatsphäre?"),
                ], self::pages(20, 20)),
                self::section('c1-speaking', 'Mündliche Prüfung', 16, [
                    self::speakingElement('C1-SP-1a', '1a: Präsentation', 'Präsentation (ca. 3 Minuten). Wählen Sie eines der Themen:\n1. Was ist der Nationalsport in einem Land Ihrer Wahl und welche Bedeutung hat er für die Gesellschaft?\noder\n2. Wer gehört Ihrer Meinung nach zu den einflussreichsten lebenden Personen weltweit? Wodurch zeichnet sich diese Person aus?', 6),
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
