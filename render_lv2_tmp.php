<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

function renderPremiumPassage(string $content): string {
    $lines = explode("\n", $content);
    $outputHtml = '';
    $inOptionsList = false;
    foreach ($lines as $line) {
        $trimmed = trim($line);
        if ($trimmed === '') {
            if ($inOptionsList) { $outputHtml .= "</div>"; $inOptionsList = false; }
            $outputHtml .= "<div style='margin-bottom:0.5rem;'></div>";
            continue;
        }
        if (str_starts_with($trimmed, '<') || str_ends_with($trimmed, '>')) {
            if ($inOptionsList) { $outputHtml .= "</div>"; $inOptionsList = false; }
            $outputHtml .= $trimmed;
            continue;
        }
        if (preg_match('/^[-=*_]{3,}$/', $trimmed)) {
            if ($inOptionsList) { $outputHtml .= "</div>"; $inOptionsList = false; }
            $outputHtml .= "<hr style='border:0; border-top:1px solid #e2e8f0; margin:1.5rem 0;' />";
            continue;
        }
        if (preg_match('/^(Überschriften|Info-Texte|Angebote|Situationen|Personen):$/iu', $trimmed)) {
            if ($inOptionsList) { $outputHtml .= "</div>"; $inOptionsList = false; }
            $outputHtml .= "<h4 style='font-size:1.1rem; font-weight:700; color:#1a1a1a; margin-top:1.5rem; margin-bottom:0.75rem; text-transform:uppercase; letter-spacing:0.05em;'>" . e($trimmed) . "</h4>";
            continue;
        }
        if (preg_match('/^([a-l])(\s{2,}|\s*[\)::-]\s*)(.+)$/iu', $trimmed, $matches)) {
            if (!$inOptionsList) {
                $outputHtml .= "<div class='options-list-container' style='display:flex; flex-direction:column; gap:0.55rem; margin-bottom:1.5rem;'>";
                $inOptionsList = true;
            }
            $letter = strtolower($matches[1]);
            $text = trim($matches[3]);
            $outputHtml .= "<div class='option-strip-item' style='display:flex; align-items:center; gap:1rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:0.6rem 1rem;'><span style='background:#e2e8f0; color:#1e293b; font-weight:800; font-size:0.95rem; width:1.75rem; height:1.75rem; display:flex; align-items:center; justify-content:center; border-radius:4px; text-transform:uppercase;'>{$letter}</span><span style='color:#334155; font-weight:600; font-size:0.95rem;'>{$text}</span></div>";
            continue;
        }
        if (preg_match('/^([a-l])$/i', $trimmed, $matches)) {
            if ($inOptionsList) { $outputHtml .= "</div>"; $inOptionsList = false; }
            $letter = strtolower($matches[1]);
            $outputHtml .= "<div style='display:flex; align-items:center; gap:0.75rem; margin-top:1.5rem; margin-bottom:0.5rem;'><span style='background:#1e293b; color:#ffffff; font-weight:800; font-size:1rem; width:2rem; height:2rem; display:flex; align-items:center; justify-content:center; border-radius:4px; text-transform:uppercase;'>{$letter}</span></div>";
            continue;
        }
        if (preg_match('/^([1-9]|1[0-9]|20)$/', $trimmed, $matches)) {
            if ($inOptionsList) { $outputHtml .= "</div>"; $inOptionsList = false; }
            $num = $matches[1];
            $outputHtml .= "<div style='display:flex; align-items:center; gap:0.75rem; margin-top:1.5rem; margin-bottom:0.5rem;'><span style='background:#d8232a; color:#ffffff; font-weight:800; font-size:1.05rem; width:2.2rem; height:2.2rem; display:flex; align-items:center; justify-content:center; border-radius:50%;'>{$num}</span></div>";
            continue;
        }
        if (preg_match('/^(Person|Situation|Aufgabe)?\s*([1-9]|1[0-9]|20)(\s*[\s::.-]\s*)(.+)$/iu', $trimmed, $matches)) {
            if ($inOptionsList) { $outputHtml .= "</div>"; $inOptionsList = false; }
            $prefix = trim($matches[1]);
            $num = $matches[2];
            $text = trim($matches[4]);
            $badgeText = $prefix ? "{$prefix} {$num}" : $num;
            $outputHtml .= "<div class='paragraph-styled-block' style='display:flex; gap:1.25rem; margin-top:1.25rem; margin-bottom:1.25rem;'><div style='flex-shrink:0;'><span style='background:#d8232a; color:#ffffff; font-weight:800; font-size:0.9rem; padding:0.35rem 0.75rem; border-radius:4px; display:inline-block; min-width:2.5rem; text-align:center;'>{$badgeText}</span></div><div style='color:#1a1a1a; line-height:1.75; font-size:0.95rem; font-weight:450;'>{$text}</div></div>";
            continue;
        }
        if ($inOptionsList) { $outputHtml .= "</div>"; $inOptionsList = false; }
        $outputHtml .= "<p style='color:#1a1a1a; line-height:1.75; font-size:0.95rem; margin-bottom:1rem; font-weight:450;'>" . e($trimmed) . "</p>";
    }
    if ($inOptionsList) { $outputHtml .= "</div>"; }
    return $outputHtml;
}

class StubComponent
{
    public function optionsFor(array $element): array
    {
        $raw = $element['options'] ?? '[]';
        $decoded = is_string($raw) ? json_decode($raw, true) : $raw;
        if (is_array($decoded) && isset($decoded[0]['key'])) {
            return array_column($decoded, 'key');
        }
        if (is_string($raw)) {
            return array_map('trim', explode(',', $raw));
        }
        return [];
    }

    public function gapsFor(array $element): array
    {
        return [];
    }

    public function __get($name)
    {
        return null;
    }

    public function __call($name, $args)
    {
        return null;
    }
}

$sections = json_decode(DB::table('exam_templates')->where('id', 70)->value('sections'), true);
$section = $sections[0];
$section['elements'] = array_values(array_filter($section['elements'], fn ($el) => str_starts_with($el['id'] ?? '', 'C1-LV2-')));

$html = view('partials.premium.single-section', [
    'section' => $section,
    'sectionIdx' => 0,
    'result' => null,
    'answers' => [],
    'isGuest' => false,
])->render();

// Print just the LV2 region
$marker = 'C1-LV2-9';
$pos = strpos($html, $marker);
echo substr($html, max(0, $pos - 2000), 6500);
