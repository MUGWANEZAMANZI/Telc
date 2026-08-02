@php
    $introEl = collect($section['elements'] ?? [])->firstWhere('type', 'text');
    $introLines = preg_split('/\r?\n/', trim($introEl['content'] ?? ''));
    $introTitle = trim($introLines[0] ?? '') ?: ($section['title'] ?? 'Kandidatenblätter');
    $introModule = trim($introLines[1] ?? '');
    $introTime = trim($introLines[2] ?? '');
    $introBody = array_filter(array_slice($introLines, 3), fn ($line) => trim($line) !== '');

    $writingElements = collect($section['elements'] ?? [])
        ->filter(fn ($el) => ($el['type'] ?? '') === 'writing')
        ->values();
@endphp

<section class="c1-section telc-kandidaten-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); width: 100%;">

    <div style="border-bottom: 2px solid #1a1a1a; padding-bottom: 0.75rem; margin-bottom: 1.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: baseline; flex-wrap: wrap;">
            <h2 style="font-size: 1.8rem; font-weight: 900; color: #111827; margin: 0;">{{ $introTitle }}</h2>
            <div style="font-size: 0.85rem; font-weight: 700; color: #71717a; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 1rem;">
                @if ($introModule)
                    <span>{{ $introModule }}</span>
                @endif
                @if ($introTime)
                    <span style="background: #1a1a1a; color: #ffffff; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">{{ $introTime }}</span>
                @endif
                <span style="background: #1a1a1a; color: #ffffff; padding: 0.15rem 0.5rem; border-radius: 4px; font-size: 0.75rem;">S. {{ $sectionIdx + 6 }}</span>
            </div>
        </div>
    </div>

    <div style="background: #fafafa; border: 1px solid #e2e8f0; border-left: 3px solid #d8232a; border-radius: 6px; padding: 1.5rem 1.75rem; font-size: 0.95rem; line-height: 1.7; color: #334155; margin-bottom: 2rem;">
        @foreach ($introBody as $line)
            <p style="margin: 0 0 0.6rem;">{{ $line }}</p>
        @endforeach
    </div>

    @foreach ($writingElements as $element)
        @php
            $elId = $element['id'];
            $writeDetail = $result ? collect($result['details'])->firstWhere('id', $elId) : null;
            $maxPoints = $writeDetail['max_points'] ?? ($element['points'] ?? 48);
        @endphp
        <div class="writing-section @if($result) marked @endif" style="margin-bottom: 2.5rem;">
            <label class="writing-label" style="font-weight: 700; display: block; margin-bottom: 0.75rem; font-size: 1.1rem; color: #1a1a1a;">{{ $element['label'] ?? 'Schreiben' }}</label>
            @if (!empty($element['instruction']))
                <div class="c1-instructions" style="margin-bottom: 1rem; font-style: italic; color: #555; background: #fff; border-left: 3px solid #d8232a; padding: 0.75rem 1rem; font-size: 0.9rem; line-height: 1.6;">
                    {!! nl2br(e($element['instruction'])) !!}
                </div>
            @endif
            <textarea class="writing-textarea-lined" wire:model.debounce.500ms="answers.{{ $elId }}" {{ $result ? 'disabled' : '' }} placeholder="Schreiben Sie hier Ihre Antwort..." style="min-height: 300px;"></textarea>
            @if ($writeDetail)
                <span class="writing-score" style="display: inline-block; background: #1a1a1a; color: #eab308; font-size: 0.8rem; font-weight: 700; padding: 0.2rem 0.6rem; border-radius: 4px; margin-top: 0.5rem;">{{ $writeDetail['points'] }} / {{ $maxPoints }} Punkte</span>
                @if (($writeDetail['explanation'] ?? $writeDetail['feedback'] ?? '') !== '')
                    <div style="margin-top:0.75rem;padding:0.75rem;background:#f9f9f9;border-left:3px solid #d8232a;border-radius:4px;font-size:0.85rem;">
                        <strong>Feedback:</strong> {{ $writeDetail['explanation'] ?? $writeDetail['feedback'] }}
                    </div>
                @endif
                @if (!empty($element['answer']))
                    <div style="margin-top: 1rem; padding: 1rem; background: #f0fdf4; border-left: 4px solid #16a34a; border-radius: 4px;">
                        <strong style="color: #14532d; font-size: 0.85rem; text-transform: uppercase; letter-spacing: 0.05em; display: block; margin-bottom: 0.5rem;">Musterlösung / AI-Musterlösung:</strong>
                        <div style="font-size: 0.95rem; line-height: 1.6; color: #166534; white-space: pre-wrap;">{{ $element['answer'] }}</div>
                    </div>
                @endif
            @endif
        </div>
    @endforeach

</section>
