<section class="c1-section telc-kandidaten-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2rem; border-radius: 8px; border: 1px solid #e2e8f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02); width: 100%;">

    @php
        $introEl = collect($section['elements'] ?? [])->firstWhere('id', 'C1-HV0-intro');
        $introLines = preg_split('/\r?\n/', trim($introEl['content'] ?? ''));
        $introTitle = trim($introLines[0] ?? '') ?: ($section['title'] ?? 'Kandidatenblätter');
        $introModule = trim($introLines[1] ?? '');
        $introTime = trim($introLines[2] ?? '');
        $introBody = array_filter(array_slice($introLines, 3), fn ($line) => trim($line) !== '');
    @endphp

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

    <div style="background: #fafafa; border: 1px solid #e2e8f0; border-left: 3px solid #d8232a; border-radius: 6px; padding: 1.5rem 1.75rem; font-size: 0.95rem; line-height: 1.7; color: #334155;">
        @foreach ($introBody as $line)
            <p style="margin: 0 0 0.6rem;">{{ $line }}</p>
        @endforeach
    </div>
</section>
