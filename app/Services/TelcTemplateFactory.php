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
            'B1' => self::b1(),
            'B2' => self::b2(),
            'C1' => self::c1(),
            'C2' => self::c2(),
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
                    ...self::answerElements('B1-LV1-', 1, ['a', 'b', 'b', 'a', 'a'], 'matching', 2, 'a,b,c'),
                    ...self::answerElements('B1-LV2-', 6, ['b', 'c', 'a', 'b', 'c'], 'multiple_choice', 2, 'a,b,c'),
                    self::inlineGap('B1-SB-inline', 'Sprachbausteine Teil 1', "Sehr geehrter Herr Meyer,\nich schreibe Ihnen, weil --0-- am letzten Donnerstag mein Arbeitsvertrag verlängert wurde. Ich möchte mich herzlich für das --1-- Vertrauen bedanken. Für die Zukunft --2-- ich mir eine weiterhin gute Zusammenarbeit.\nMit freundlichen Grüßen\nMax Mustermann", [
                        ['options' => 'a|ich,b|mir,c|mich', 'answer' => 'a'],
                        ['options' => 'a|gezeigte,b|gezeigtem,c|gezeigten', 'answer' => 'a'],
                        ['options' => 'a|wünsche,b|hoffe,c|denke', 'answer' => 'a'],
                    ], 1),
                ], self::pages(1, 5)),
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
                    ...self::answerElements('C2-LV1-', 1, ['c', 'd', 'b', 'a', 'd', 'c'], 'multiple_choice', 2, 'a,b,c,d'),
                    self::inlineGap('C2-SB-inline', 'Sprachbausteine (Wissenschaftssprache)', "In der heutigen Debatte um die Nachhaltigkeit --0-- es von entscheidender Bedeutung, alternative Energiequellen umfassend zu erforschen. Zwar existieren bereits zahlreiche Ansätze, doch --1-- viele davon an der mangelnden Skalierbarkeit. Um einen substanziellen Beitrag leisten zu --2--, bedarf es einer verstärkten Kooperation zwischen Wissenschaft und Wirtschaft.", [
                        ['options' => 'a|ist,b|wäre,c|wird', 'answer' => 'a'],
                        ['options' => 'a|scheitern,b|gelingen,c|verzögern', 'answer' => 'a'],
                        ['options' => 'a|können,b|dürfen,c|sollen', 'answer' => 'a'],
                    ], 2),
                ], self::pages(1, 6)),
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
                ['label' => 'Hoerverstehen', 'minutes' => 30],
                ['label' => 'Schriftlicher Ausdruck', 'minutes' => 30],
                ['label' => 'Vorbereitungszeit Muendliche Pruefung', 'minutes' => 20],
                ['label' => 'Muendlicher Ausdruck', 'minutes' => 16],
            ],
            'sections' => [
                self::section('b2-reading-language', 'Leseverstehen und Sprachbausteine', 90, [
                    ...self::answerElements('B2-LV1-', 1, ['h', 'a', 'f', 'd', 'i'], 'matching', 2, 'a,b,c,d,e,f,g,h,i,j'),
                    ...self::answerElements('B2-LV2-', 6, ['a', 'a', 'c', 'c', 'c'], 'multiple_choice', 2, 'a,b,c'),
                    ...self::answerElements('B2-LV3-', 11, ['a', 'b', 'j', 'x', 'f', 'i', 'h', 'g', 'c', 'e'], 'matching', 2, 'a,b,c,d,e,f,g,h,i,j,k,l'),
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
                    ], 1),
                ], self::pages(6, 12)),
            ],
        ];
    }

    /**
     * @param  array<int, array<string, mixed>>  $gaps
     * @return array<string, mixed>
     */
    private static function inlineGap(string $id, string $label, string $content, array $gaps, int $points = 1): array
    {
        return [
            'id' => $id,
            'type' => 'inline_gap',
            'label' => $label,
            'content' => $content,
            'options' => json_encode($gaps),
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
                self::section('c1-speaking', 'Mündliche Prüfung', 36, [
                    self::speakingElement('C1-SP-1', 'Vortrag', 'Stellen Sie Ihrem Gesprächspartner ein aktuelles gesellschaftliches Thema Ihrer Wahl vor. Gehen Sie dabei auf Ursachen, Auswirkungen und Lösungsmöglichkeiten ein.', 24),
                    self::speakingElement('C1-SP-2', 'Diskussion', 'Diskutieren Sie mit Ihrem Gesprächspartner über die Vor- und Nachteile von Homeoffice. Vertreten Sie Ihre Meinung und gehen Sie auf Gegenargumente ein.', 24),
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
    private static function input(string $id, string $label, string $type, string $answer, int $points = 1, ?string $options = null): array
    {
        $options ??= match ($type) {
            'multiple_choice' => 'a,b,c,d',
            'true_false' => '+,-',
            default => '',
        };

        return compact('id', 'label', 'type', 'answer', 'points', 'options') + ['content' => ''];
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
    private static function speakingElement(string $id, string $label, string $prompt, int $points = 25): array
    {
        return [
            'id' => $id,
            'type' => 'speaking',
            'label' => $label,
            'content' => $prompt,
            'options' => '',
            'answer' => '',
            'points' => $points,
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
