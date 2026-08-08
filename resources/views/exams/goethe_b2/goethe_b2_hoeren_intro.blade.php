{{-- ==================== GOETHE B2 HÖREN INTRO LAYOUT ==================== --}}
<section class="goethe-section goethe-b2-hoeren-intro-layout" data-section-idx="{{ $sectionIdx }}" style="position: relative; font-family: 'Plus Jakarta Sans', sans-serif; margin-bottom: 3rem; background: #ffffff; padding: 2.5rem; border-radius: 12px; border: 1px solid #e2e8f0; width: 100%; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
    <div style="border-bottom: 3px solid #d8232a; padding-bottom: 0.75rem; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <span style="font-size: 0.85rem; font-weight: 800; text-transform: uppercase; color: #d8232a; letter-spacing: 0.1em; display: block; margin-bottom: 0.25rem;">Kandidatenblätter</span>
            <h2 style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 2.2rem; font-weight: 900; color: #111827; margin: 0; letter-spacing: -0.02em;">Hören</h2>
        </div>
        <div style="text-align: right;">
            <span style="display: block; font-size: 1.1rem; font-weight: 800; color: #1f2937;">circa 40 Minuten</span>
            <span style="font-size: 0.8rem; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em;">Modul Hören</span>
        </div>
    </div>

    @php
        $introEl = collect($section['elements'])->firstWhere('id', 'goethe-b2-hoeren-intro-text');
    @endphp

    @if ($introEl && $introEl['content'])
        <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 2rem; color: #1f2937; line-height: 1.8; font-size: 1.05rem; font-weight: 500; font-family: system-ui, -apple-system, sans-serif;">
            {!! nl2br(e($introEl['content'])) !!}
        </div>
    @endif
</section>
