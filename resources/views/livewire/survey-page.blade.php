<div style="max-width:800px;margin:2rem auto;padding:0 1rem;">
    <div style="background:#ffffff;border:1px solid #e5e5e5;border-radius:12px;padding:3rem;text-align:center;box-shadow:0 4px 20px rgba(0,0,0,0.03);">
        <div style="width:64px;height:64px;background:#eff6ff;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1.5rem;">
            <svg style="width:32px;height:32px;color:#2563eb;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
        </div>
        <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:#1a1a1a;margin-bottom:0.5rem;">Umfrage</h1>
        <p style="color:#666;font-size:1rem;margin-bottom:2rem;">Helfen Sie uns, GERMAN SUCCESS zu verbessern. Ihre Antworten sind anonym und werden vertraulich behandelt.</p>

        @if ($googleFormUrl)
            <div style="border:1px solid #e5e5e5;border-radius:8px;overflow:hidden;margin-bottom:1.5rem;">
                <iframe
                    src="{{ $googleFormUrl }}"
                    width="100%"
                    height="800"
                    frameborder="0"
                    marginheight="0"
                    marginwidth="0"
                    style="border:none;min-height:600px;"
                    loading="lazy"
                    title="GERMAN SUCCESS Umfrage"
                >Wird geladen…</iframe>
            </div>
            <a href="{{ $googleFormUrl }}" target="_blank" rel="noopener noreferrer"
               style="display:inline-flex;align-items:center;gap:0.5rem;color:#2563eb;font-size:0.9rem;font-weight:600;text-decoration:none;">
                <svg style="width:16px;height:16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                </svg>
                In neuem Fenster öffnen
            </a>
        @else
            <div style="background:#f9fafb;border:1px solid #e5e7eb;border-radius:8px;padding:3rem;text-align:center;">
                <p style="color:#6b7280;font-size:0.95rem;">Die Umfrage ist derzeit nicht verfügbar. Bitte schauen Sie später wieder vorbei.</p>
            </div>
        @endif
    </div>
</div>
