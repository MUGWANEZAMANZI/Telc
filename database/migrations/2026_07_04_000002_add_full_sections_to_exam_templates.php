<?php

use App\Models\ExamTemplate;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $levels = [
            'B1' => $this->b1Sections(),
            'B2' => $this->b2Sections(),
            'C2' => $this->c2Sections(),
        ];

        foreach ($levels as $level => $newSections) {
            ExamTemplate::where('is_published', true)
                ->where('level', $level)
                ->eachById(function (ExamTemplate $template) use ($newSections) {
                    $existing = $template->sections ?? [];
                    $existingIds = collect($existing)->pluck('id')->values()->all();

                    $toAdd = array_values(array_filter($newSections, fn ($s) => ! in_array($s['id'], $existingIds, true)));

                    if ($toAdd !== []) {
                        $template->sections = array_merge($existing, $toAdd);
                        $template->save();
                    }
                });
        }
    }

    public function down(): void
    {
        $sectionIds = [
            'b1-listening', 'b1-writing', 'b1-speaking',
            'b2-listening', 'b2-writing', 'b2-speaking',
            'c2-listening', 'c2-writing', 'c2-speaking',
        ];

        ExamTemplate::where('is_published', true)
            ->whereIn('level', ['B1', 'B2', 'C2'])
            ->eachById(function (ExamTemplate $template) use ($sectionIds) {
                $existing = $template->sections ?? [];
                $template->sections = array_values(
                    array_filter($existing, fn ($s) => ! in_array($s['id'] ?? '', $sectionIds, true))
                );
                $template->save();
            });
    }

    /** @return array<int, array<string, mixed>> */
    private function b1Sections(): array
    {
        return [
            [
                'id' => 'b1-listening',
                'title' => 'Hörverstehen',
                'minutes' => 30,
                'elements' => [
                    ['id' => 'b1-audio', 'type' => 'audio', 'label' => 'Hörverstehen Audio', 'content' => '/goethe-assets/placeholder.mp3', 'answer' => '', 'points' => 0, 'options' => ''],
                    ['id' => 'B1-HV1-1', 'type' => 'matching', 'label' => 'Aufgabe 1', 'answer' => 'c', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h', 'content' => ''],
                    ['id' => 'B1-HV1-2', 'type' => 'matching', 'label' => 'Aufgabe 2', 'answer' => 'a', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h', 'content' => ''],
                    ['id' => 'B1-HV1-3', 'type' => 'matching', 'label' => 'Aufgabe 3', 'answer' => 'f', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h', 'content' => ''],
                    ['id' => 'B1-HV1-4', 'type' => 'matching', 'label' => 'Aufgabe 4', 'answer' => 'b', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h', 'content' => ''],
                    ['id' => 'B1-HV1-5', 'type' => 'matching', 'label' => 'Aufgabe 5', 'answer' => 'e', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h', 'content' => ''],
                    ['id' => 'B1-HV2-6', 'type' => 'multiple_choice', 'label' => 'Aufgabe 6', 'answer' => 'b', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'B1-HV2-7', 'type' => 'multiple_choice', 'label' => 'Aufgabe 7', 'answer' => 'a', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'B1-HV2-8', 'type' => 'multiple_choice', 'label' => 'Aufgabe 8', 'answer' => 'c', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'B1-HV2-9', 'type' => 'multiple_choice', 'label' => 'Aufgabe 9', 'answer' => 'b', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'B1-HV2-10', 'type' => 'multiple_choice', 'label' => 'Aufgabe 10', 'answer' => 'c', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'B1-HV3-11', 'type' => 'fill_blank', 'label' => 'Aufgabe 11', 'answer' => 'Montag', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'B1-HV3-12', 'type' => 'fill_blank', 'label' => 'Aufgabe 12', 'answer' => '14 Uhr', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'B1-HV3-13', 'type' => 'fill_blank', 'label' => 'Aufgabe 13', 'answer' => 'Bibliothek', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'B1-HV3-14', 'type' => 'fill_blank', 'label' => 'Aufgabe 14', 'answer' => 'Anmeldung', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'B1-HV3-15', 'type' => 'fill_blank', 'label' => 'Aufgabe 15', 'answer' => '30 Euro', 'points' => 1, 'options' => '', 'content' => ''],
                ],
                'paper_pages' => [],
            ],
            [
                'id' => 'b1-writing',
                'title' => 'Schriftlicher Ausdruck',
                'minutes' => 30,
                'elements' => [
                    ['id' => 'B1-WRITE-1', 'type' => 'writing', 'label' => 'Aufgabe Schriftlicher Ausdruck', 'answer' => '', 'points' => 24, 'options' => '', 'content' => 'Schreiben Sie einen Beitrag für ein Internetforum. Wählen Sie eines der beiden Themen: Thema 1: Sollte man in der Freizeit Sport treiben? Oder Thema 2: Vor- und Nachteile von sozialen Medien.'],
                ],
                'paper_pages' => [],
            ],
            [
                'id' => 'b1-speaking',
                'title' => 'Mündliche Prüfung',
                'minutes' => 15,
                'elements' => [
                    ['id' => 'B1-SP-1a', 'type' => 'speaking', 'label' => 'Teil 1: Präsentation', 'content' => 'Halten Sie eine kurze Präsentation (ca. 2 Minuten). Wählen Sie eines der Themen: 1. Mein Lieblingsort in meiner Stadt oder 2. Ein wichtiges Fest in meiner Familie.', 'options' => '', 'answer' => '', 'points' => 8],
                    ['id' => 'B1-SP-1b', 'type' => 'speaking', 'label' => 'Teil 2: Diskussion', 'content' => 'Diskutieren Sie mit Ihrem Gesprächspartner über folgendes Thema: Sollten Kinder ein eigenes Handy bekommen? Nennen Sie Vor- und Nachteile.', 'options' => '', 'answer' => '', 'points' => 8],
                    ['id' => 'B1-SP-2', 'type' => 'speaking', 'label' => 'Teil 3: Gemeinsame Planung', 'content' => 'Planen Sie gemeinsam mit Ihrem Partner etwas: Sie möchten mit Ihrem Kurs einen Ausflug machen. Besprechen Sie Ziel, Termin und Aktivitäten.', 'options' => '', 'answer' => '', 'points' => 8],
                ],
                'paper_pages' => [],
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function b2Sections(): array
    {
        return [
            [
                'id' => 'b2-listening',
                'title' => 'Hörverstehen',
                'minutes' => 35,
                'elements' => [
                    ['id' => 'b2-audio', 'type' => 'audio', 'label' => 'Hörverstehen Audio', 'content' => '/goethe-assets/placeholder.mp3', 'answer' => '', 'points' => 0, 'options' => ''],
                    ['id' => 'B2-HV1-1', 'type' => 'matching', 'label' => 'Aufgabe 1', 'answer' => 'd', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'B2-HV1-2', 'type' => 'matching', 'label' => 'Aufgabe 2', 'answer' => 'g', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'B2-HV1-3', 'type' => 'matching', 'label' => 'Aufgabe 3', 'answer' => 'a', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'B2-HV1-4', 'type' => 'matching', 'label' => 'Aufgabe 4', 'answer' => 'i', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'B2-HV1-5', 'type' => 'matching', 'label' => 'Aufgabe 5', 'answer' => 'c', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'B2-HV1-6', 'type' => 'matching', 'label' => 'Aufgabe 6', 'answer' => 'f', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'B2-HV1-7', 'type' => 'matching', 'label' => 'Aufgabe 7', 'answer' => 'b', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'B2-HV2-8', 'type' => 'multiple_choice', 'label' => 'Aufgabe 8', 'answer' => 'b', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'B2-HV2-9', 'type' => 'multiple_choice', 'label' => 'Aufgabe 9', 'answer' => 'c', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'B2-HV2-10', 'type' => 'multiple_choice', 'label' => 'Aufgabe 10', 'answer' => 'a', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'B2-HV2-11', 'type' => 'multiple_choice', 'label' => 'Aufgabe 11', 'answer' => 'c', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'B2-HV2-12', 'type' => 'multiple_choice', 'label' => 'Aufgabe 12', 'answer' => 'b', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'B2-HV2-13', 'type' => 'multiple_choice', 'label' => 'Aufgabe 13', 'answer' => 'a', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'B2-HV2-14', 'type' => 'multiple_choice', 'label' => 'Aufgabe 14', 'answer' => 'c', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'B2-HV3-15', 'type' => 'fill_blank', 'label' => 'Aufgabe 15', 'answer' => 'Projektmanagement', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'B2-HV3-16', 'type' => 'fill_blank', 'label' => 'Aufgabe 16', 'answer' => '3 Jahren', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'B2-HV3-17', 'type' => 'fill_blank', 'label' => 'Aufgabe 17', 'answer' => 'Referenzen', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'B2-HV3-18', 'type' => 'fill_blank', 'label' => 'Aufgabe 18', 'answer' => 'Teamarbeit', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'B2-HV3-19', 'type' => 'fill_blank', 'label' => 'Aufgabe 19', 'answer' => 'Weiterbildung', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'B2-HV3-20', 'type' => 'fill_blank', 'label' => 'Aufgabe 20', 'answer' => 'Gehaltsvorstellung', 'points' => 1, 'options' => '', 'content' => ''],
                ],
                'paper_pages' => [],
            ],
            [
                'id' => 'b2-writing',
                'title' => 'Schriftlicher Ausdruck',
                'minutes' => 45,
                'elements' => [
                    ['id' => 'B2-WRITE-1', 'type' => 'writing', 'label' => 'Aufgabe Schriftlicher Ausdruck', 'answer' => '', 'points' => 32, 'options' => '', 'content' => 'Verfassen Sie einen Brief an die Redaktion einer Zeitung. Nehmen Sie Stellung zu einem der folgenden Themen: Thema 1: Sollte der öffentliche Nahverkehr kostenlos sein? Oder Thema 2: Brauchen wir mehr Grünflächen in unseren Städten?'],
                ],
                'paper_pages' => [],
            ],
            [
                'id' => 'b2-speaking',
                'title' => 'Mündliche Prüfung',
                'minutes' => 15,
                'elements' => [
                    ['id' => 'B2-SP-1a', 'type' => 'speaking', 'label' => 'Teil 1a: Präsentation', 'content' => 'Halten Sie eine Präsentation (ca. 3 Minuten). Wählen Sie eines der Themen: 1. Die Bedeutung von lebenslangem Lernen oder 2. Chancen und Risiken der Digitalisierung im Berufsleben.', 'options' => '', 'answer' => '', 'points' => 8],
                    ['id' => 'B2-SP-1b', 'type' => 'speaking', 'label' => 'Teil 1b: Zusammenfassung', 'content' => 'Fassen Sie die Präsentation Ihres Partners zusammen und stellen Sie eine Anschlussfrage.', 'options' => '', 'answer' => '', 'points' => 6],
                    ['id' => 'B2-SP-2', 'type' => 'speaking', 'label' => 'Teil 2: Diskussion', 'content' => 'Diskutieren Sie mit Ihrem Partner über folgendes Thema: Sollten Unternehmen Homeoffice verpflichtend anbieten? Argumentieren Sie pro und contra.', 'options' => '', 'answer' => '', 'points' => 8],
                    ['id' => 'B2-SP-3', 'type' => 'speaking', 'label' => 'Teil 3: Problemlösung', 'content' => 'Sie und Ihr Partner möchten in Ihrer Stadt ein neues Kulturzentrum einrichten. Überlegen Sie gemeinsam: Welche Räume soll es geben? Welche Veranstaltungen? Wie wird es finanziert?', 'options' => '', 'answer' => '', 'points' => 8],
                ],
                'paper_pages' => [],
            ],
        ];
    }

    /** @return array<int, array<string, mixed>> */
    private function c2Sections(): array
    {
        return [
            [
                'id' => 'c2-listening',
                'title' => 'Hörverstehen',
                'minutes' => 35,
                'elements' => [
                    ['id' => 'c2-audio', 'type' => 'audio', 'label' => 'Hörverstehen Audio', 'content' => '/goethe-assets/placeholder.mp3', 'answer' => '', 'points' => 0, 'options' => ''],
                    ['id' => 'C2-HV1-1', 'type' => 'matching', 'label' => 'Aufgabe 1', 'answer' => 'e', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'C2-HV1-2', 'type' => 'matching', 'label' => 'Aufgabe 2', 'answer' => 'h', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'C2-HV1-3', 'type' => 'matching', 'label' => 'Aufgabe 3', 'answer' => 'b', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'C2-HV1-4', 'type' => 'matching', 'label' => 'Aufgabe 4', 'answer' => 'j', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'C2-HV1-5', 'type' => 'matching', 'label' => 'Aufgabe 5', 'answer' => 'd', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'C2-HV1-6', 'type' => 'matching', 'label' => 'Aufgabe 6', 'answer' => 'a', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'C2-HV1-7', 'type' => 'matching', 'label' => 'Aufgabe 7', 'answer' => 'g', 'points' => 2, 'options' => 'a,b,c,d,e,f,g,h,i,j', 'content' => ''],
                    ['id' => 'C2-HV2-8', 'type' => 'multiple_choice', 'label' => 'Aufgabe 8', 'answer' => 'c', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'C2-HV2-9', 'type' => 'multiple_choice', 'label' => 'Aufgabe 9', 'answer' => 'a', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'C2-HV2-10', 'type' => 'multiple_choice', 'label' => 'Aufgabe 10', 'answer' => 'b', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'C2-HV2-11', 'type' => 'multiple_choice', 'label' => 'Aufgabe 11', 'answer' => 'c', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'C2-HV2-12', 'type' => 'multiple_choice', 'label' => 'Aufgabe 12', 'answer' => 'a', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'C2-HV2-13', 'type' => 'multiple_choice', 'label' => 'Aufgabe 13', 'answer' => 'b', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'C2-HV2-14', 'type' => 'multiple_choice', 'label' => 'Aufgabe 14', 'answer' => 'c', 'points' => 2, 'options' => 'a,b,c', 'content' => ''],
                    ['id' => 'C2-HV3-15', 'type' => 'fill_blank', 'label' => 'Aufgabe 15', 'answer' => 'demografischen Wandel', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'C2-HV3-16', 'type' => 'fill_blank', 'label' => 'Aufgabe 16', 'answer' => 'Nachhaltigkeit', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'C2-HV3-17', 'type' => 'fill_blank', 'label' => 'Aufgabe 17', 'answer' => 'Bildungssystem', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'C2-HV3-18', 'type' => 'fill_blank', 'label' => 'Aufgabe 18', 'answer' => 'Integration', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'C2-HV3-19', 'type' => 'fill_blank', 'label' => 'Aufgabe 19', 'answer' => 'Ressourcen', 'points' => 1, 'options' => '', 'content' => ''],
                    ['id' => 'C2-HV3-20', 'type' => 'fill_blank', 'label' => 'Aufgabe 20', 'answer' => 'Innovation', 'points' => 1, 'options' => '', 'content' => ''],
                ],
                'paper_pages' => [],
            ],
            [
                'id' => 'c2-writing',
                'title' => 'Schriftlicher Ausdruck',
                'minutes' => 60,
                'elements' => [
                    ['id' => 'C2-WRITE-1', 'type' => 'writing', 'label' => 'Aufgabe Schriftlicher Ausdruck', 'answer' => '', 'points' => 40, 'options' => '', 'content' => 'Verfassen Sie einen wissenschaftlich orientierten Essay zu einem der folgenden Themen: Thema 1: Die Auswirkungen der Künstlichen Intelligenz auf den Arbeitsmarkt — Chancen und Risiken. Oder Thema 2: Inwiefern beeinflusst die Globalisierung die kulturelle Identität von Gesellschaften?'],
                ],
                'paper_pages' => [],
            ],
            [
                'id' => 'c2-speaking',
                'title' => 'Mündliche Prüfung',
                'minutes' => 18,
                'elements' => [
                    ['id' => 'C2-SP-1a', 'type' => 'speaking', 'label' => 'Teil 1a: Vortrag', 'content' => 'Halten Sie einen strukturierten Vortrag (ca. 4 Minuten). Wählen Sie eines der Themen: 1. Die Rolle der Medien in der Demokratie oder 2. Technologischer Fortschritt und ethische Verantwortung.', 'options' => '', 'answer' => '', 'points' => 10],
                    ['id' => 'C2-SP-1b', 'type' => 'speaking', 'label' => 'Teil 1b: Stellungnahme', 'content' => 'Nehmen Sie Stellung zum Vortrag Ihres Partners. Gehen Sie auf die Hauptargumente ein und ergänzen Sie eine eigene Perspektive.', 'options' => '', 'answer' => '', 'points' => 8],
                    ['id' => 'C2-SP-2', 'type' => 'speaking', 'label' => 'Teil 2: Diskussion', 'content' => 'Führen Sie eine differenzierte Diskussion zu folgendem Zitat: "Fortschritt ist ohne Risiko nicht möglich." Erläutern Sie Ihre Position und gehen Sie auf Gegenargumente ein.', 'options' => '', 'answer' => '', 'points' => 10],
                    ['id' => 'C2-SP-3', 'type' => 'speaking', 'label' => 'Teil 3: Problemerörterung', 'content' => 'Erörtern Sie mit Ihrem Partner ein komplexes Problem: Ihre Kommune plant die Umgestaltung des Stadtzentrums. Entwickeln Sie ein Konzept, das sowohl wirtschaftliche Interessen als auch Umweltaspekte und soziale Bedürfnisse berücksichtigt.', 'options' => '', 'answer' => '', 'points' => 10],
                ],
                'paper_pages' => [],
            ],
        ];
    }
};
