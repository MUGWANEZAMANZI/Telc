@php
if (!function_exists('renderPremiumPassage')) {
    function renderPremiumPassage(string $content): string {
        $lines = explode("\n", $content);
        $outputHtml = '';
        $inOptionsList = false;
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '') {
                if ($inOptionsList) {
                    $outputHtml .= "</div>";
                    $inOptionsList = false;
                }
                $outputHtml .= "<div style='margin-bottom:0.5rem;'></div>";
                continue;
            }

            // If the line already starts or ends with HTML tags, output it directly
            if (str_starts_with($trimmed, '<') || str_ends_with($trimmed, '>')) {
                if ($inOptionsList) {
                    $outputHtml .= "</div>";
                    $inOptionsList = false;
                }
                $outputHtml .= $trimmed;
                continue;
            }
            
            // Match separator lines like ---
            if (preg_match('/^[-=*_]{3,}$/', $trimmed)) {
                if ($inOptionsList) {
                    $outputHtml .= "</div>";
                    $inOptionsList = false;
                }
                $outputHtml .= "<hr style='border:0; border-top:1px solid #e2e8f0; margin:1.5rem 0;' />";
                continue;
            }
            
            // Match option header like "Überschriften:" or "Info-Texte:" or "Angebote:"
            if (preg_match('/^(Überschriften|Info-Texte|Angebote|Situationen|Personen):$/iu', $trimmed)) {
                if ($inOptionsList) {
                    $outputHtml .= "</div>";
                    $inOptionsList = false;
                }
                $outputHtml .= "<h4 style='font-size:1.1rem; font-weight:700; color:#1a1a1a; margin-top:1.5rem; margin-bottom:0.75rem; text-transform:uppercase; letter-spacing:0.05em;'>" . e($trimmed) . "</h4>";
                continue;
            }
            
            // Match option letters: "a  text" or "a) text" or "a: text"
            if (preg_match('/^([a-l])(\s{2,}|\s*[\)::-]\s*)(.+)$/iu', $trimmed, $matches)) {
                if (!$inOptionsList) {
                    $outputHtml .= "<div class='options-list-container' style='display:flex; flex-direction:column; gap:0.55rem; margin-bottom:1.5rem;'>";
                    $inOptionsList = true;
                }
                $letter = strtolower($matches[1]);
                $text = trim($matches[3]);
                $outputHtml .= "
                    <div class='option-strip-item' style='display:flex; align-items:center; gap:1rem; background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:0.6rem 1rem; font-family:var(--font-sans); box-shadow:0 1px 2px rgba(0,0,0,0.02);'>
                        <span style='background:#e2e8f0; color:#1e293b; font-weight:800; font-size:0.95rem; width:1.75rem; height:1.75rem; display:flex; align-items:center; justify-content:center; border-radius:4px; text-transform:uppercase;'>{$letter}</span>
                        <span style='color:#334155; font-weight:600; font-size:0.95rem;'>{$text}</span>
                    </div>";
                continue;
            }
            
            // Match single letter headers: e.g. line is just "a"
            if (preg_match('/^([a-l])$/i', $trimmed, $matches)) {
                if ($inOptionsList) {
                    $outputHtml .= "</div>";
                    $inOptionsList = false;
                }
                $letter = strtolower($matches[1]);
                $outputHtml .= "<div style='display:flex; align-items:center; gap:0.75rem; margin-top:1.5rem; margin-bottom:0.5rem;'>
                    <span style='background:#1e293b; color:#ffffff; font-weight:800; font-size:1rem; width:2rem; height:2rem; display:flex; align-items:center; justify-content:center; border-radius:4px; text-transform:uppercase;'>{$letter}</span>
                </div>";
                continue;
            }
            
            // Match single number headers: "1", "2", "3" etc. on their own line
            if (preg_match('/^([1-9]|1[0-9]|20)$/', $trimmed, $matches)) {
                if ($inOptionsList) {
                    $outputHtml .= "</div>";
                    $inOptionsList = false;
                }
                $num = $matches[1];
                $outputHtml .= "<div style='display:flex; align-items:center; gap:0.75rem; margin-top:1.5rem; margin-bottom:0.5rem;'>
                    <span style='background:#d8232a; color:#ffffff; font-weight:800; font-size:1.05rem; width:2.2rem; height:2.2rem; display:flex; align-items:center; justify-content:center; border-radius:50%;'>{$num}</span>
                </div>";
                continue;
            }
            
            // Match numbered inline text / situations: "1  text" or "Person 1: text" or "Situation 1: text"
            if (preg_match('/^(Person|Situation|Aufgabe)?\s*([1-9]|1[0-9]|20)(\s*[\s::.-]\s*)(.+)$/iu', $trimmed, $matches)) {
                if ($inOptionsList) {
                    $outputHtml .= "</div>";
                    $inOptionsList = false;
                }
                $prefix = trim($matches[1]);
                $num = $matches[2];
                $text = trim($matches[4]);
                $badgeText = $prefix ? "{$prefix} {$num}" : $num;
                $outputHtml .= "
                    <div class='paragraph-styled-block' style='display:flex; gap:1.25rem; margin-top:1.25rem; margin-bottom:1.25rem;'>
                        <div style='flex-shrink:0;'>
                            <span style='background:#d8232a; color:#ffffff; font-weight:800; font-size:0.9rem; padding:0.35rem 0.75rem; border-radius:4px; display:inline-block; min-width:2.5rem; text-align:center;'>{$badgeText}</span>
                        </div>
                        <div style='color:#1a1a1a; line-height:1.75; font-size:0.95rem; font-weight:450;'>{$text}</div>
                    </div>";
                continue;
            }
            
            // Otherwise, render normal paragraph line
            if ($inOptionsList) {
                $outputHtml .= "</div>";
                $inOptionsList = false;
            }
            $outputHtml .= "<p style='color:#1a1a1a; line-height:1.75; font-size:0.95rem; margin-bottom:1rem; font-weight:450;'>" . e($trimmed) . "</p>";
        }
        
        if ($inOptionsList) {
            $outputHtml .= "</div>";
        }
        
        return $outputHtml;
    }
}

if (!function_exists('parseTeilContent')) {
    function parseTeilContent(string $content): array {
        $lines = explode("\n", $content);
        $instruction = '';
        $options = [];
        $texts = [];
        
        $currentTextNum = null;
        $currentTextContent = '';
        
        foreach ($lines as $line) {
            $trimmed = trim($line);
            if ($trimmed === '') {
                if ($currentTextNum !== null) {
                    $currentTextContent .= "\n";
                }
                continue;
            }
            
            if (preg_match('/^[-=*_]{3,}$/', $trimmed)) {
                continue;
            }
            
            if (preg_match('/^(Überschriften|Situationen|Angebote|Info-Texte|Texte|Personen):$/iu', $trimmed)) {
                continue;
            }
            
            if (preg_match('/^([a-l])(\s{2,}|\s*[\)::-]\s*)(.+)$/iu', $trimmed, $matches)) {
                if ($currentTextNum !== null) {
                    $texts[$currentTextNum] = trim($currentTextContent);
                    $currentTextNum = null;
                    $currentTextContent = '';
                }
                $options[strtolower($matches[1])] = trim($matches[3]);
                continue;
            }
            
            if (preg_match('/^([1-9]|1[0-9]|20)$/', $trimmed, $matches)) {
                if ($currentTextNum !== null) {
                    $texts[$currentTextNum] = trim($currentTextContent);
                }
                $currentTextNum = (int)$matches[1];
                $currentTextContent = '';
                continue;
            }
            
            if (preg_match('/^(Person|Situation|Aufgabe)?\s*([1-9]|1[0-9]|20)(\s*[\s::.-]\s*)(.+)$/iu', $trimmed, $matches)) {
                if ($currentTextNum !== null) {
                    $texts[$currentTextNum] = trim($currentTextContent);
                }
                $currentTextNum = (int)$matches[2];
                $currentTextContent = $matches[4];
                continue;
            }
            
            if ($currentTextNum !== null) {
                $currentTextContent .= ($currentTextContent === '' ? '' : "\n") . $trimmed;
            } else {
                if (empty($options) && empty($texts)) {
                    $instruction .= ($instruction === '' ? '' : "\n") . $trimmed;
                }
            }
        }
        
        if ($currentTextNum !== null && trim($currentTextContent) !== '') {
            $texts[$currentTextNum] = trim($currentTextContent);
        }
        
        return [
            'instruction' => trim($instruction),
            'options' => $options,
            'texts' => $texts,
        ];
    }
}
@endphp

{{-- Dynamic section/element loop for templated levels (B1, B2, C2, etc.) --}}
<div class="telc-section-stack" style="display: flex; flex-direction: column; gap: 2rem; width: 100%; min-width: 0; box-sizing: border-box;">
    @foreach ($template['sections'] as $sectionIdx => $section)
        @php
            $isSpeaking = collect($section['elements'] ?? [])->contains(fn ($el) => ($el['type'] ?? '') === 'speaking');
            $isHiddenC1 = in_array($section['id'] ?? '', ['c1-speaking']);
            $record = \App\Models\ExamTemplate::find($selectedTemplateId);
            $status = $record ? $this->getTemplateFreeStatus($record) : ['is_free' => false, 'free_sections' => 0];
            $isSubscribed = $this->hasActiveSubscription();
            $isFullyEnrolled = $this->isFullyEnrolled($selectedTemplateId);
            $isLocked = !$isSubscribed && !$isFullyEnrolled && ($sectionIdx >= $status['free_sections']);
        @endphp

        @if ($isLocked)
            <section class="c1-section" style="background: #ffffff; border: 2px dashed #cbcbcb; border-radius: 8px; padding: 3rem 2rem; margin: 2rem 0; text-align: center; font-family: 'Plus Jakarta Sans', sans-serif;">
                <div style="font-size: 2.5rem; margin-bottom: 1rem;">🔒</div>
                <h3 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; margin: 0 0 0.5rem; color: #1a1a1a;">{{ $section['title'] ?? 'Abschnitt gesperrt' }}</h3>
                <p style="color: #666666; font-size: 0.95rem; margin-bottom: 1.5rem; max-width: 400px; margin-left: auto; margin-right: auto;">
                    Dieser Abschnitt ist Teil der Premium-Funktionen. Bitte erwerben Sie ein Abonnement, um vollen Zugang zu allen Abschnitten zu erhalten.
                </p>
                <a href="{{ route('pricing') }}" class="telc-button primary" style="display: inline-block; text-decoration: none;">
                    Jetzt Abonnement ansehen
                </a>
            </section>
        @elseif ($isSpeaking && !$isHiddenC1)
            @include('partials.premium.speaking-flow')
        @elseif (!$isHiddenC1)
            @include('partials.premium.single-section', [
                'section' => $section,
                'sectionIdx' => $sectionIdx,
                'result' => $result,
                'answers' => $answers,
                'isGuest' => false
            ])
        @endif
    @endforeach
</div>
