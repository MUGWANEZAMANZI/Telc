<div style="max-width:1200px;margin:0 auto;padding:2rem 1rem;">
    @if (session('blog_message'))
        <div style="background:#ecfdf5;border:1px solid #6ee7b7;color:#065f46;padding:0.75rem 1rem;border-radius:8px;margin-bottom:1.5rem;font-size:0.9rem;">
            {{ session('blog_message') }}
        </div>
    @endif

    @if ($mode === 'list')
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2rem;">
            <div>
                <h1 style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:700;color:#1a1a1a;margin:0;">Blog-Verwaltung</h1>
                <p style="color:#666;font-size:0.9rem;margin:0.25rem 0 0;">Artikel erstellen und verwalten für SEO und Nutzer.</p>
            </div>
            <button wire:click="createNew" style="background:#d8232a;color:#fff;border:none;padding:0.6rem 1.5rem;border-radius:8px;font-weight:700;font-size:0.85rem;cursor:pointer;transition:opacity 0.2s;" onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                + Neuer Artikel
            </button>
        </div>

        <div style="background:#fff;border:1px solid #e5e5e5;border-radius:10px;overflow:hidden;">
            <table style="width:100%;border-collapse:collapse;font-size:0.9rem;">
                <thead>
                    <tr style="background:#fafafa;border-bottom:2px solid #edf0f4;">
                        <th style="padding:0.75rem 1rem;text-align:left;font-weight:700;color:#666;">Titel</th>
                        <th style="padding:0.75rem 1rem;text-align:left;font-weight:700;color:#666;">Kategorie</th>
                        <th style="padding:0.75rem 1rem;text-align:left;font-weight:700;color:#666;">Datum</th>
                        <th style="padding:0.75rem 1rem;text-align:center;font-weight:700;color:#666;">Status</th>
                        <th style="padding:0.75rem 1rem;text-align:right;font-weight:700;color:#666;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($posts as $post)
                        <tr style="border-bottom:1px solid #f3f4f6;">
                            <td style="padding:0.75rem 1rem;">
                                <div style="font-weight:700;color:#1a1a1a;">{{ $post->title }}</div>
                                <div style="font-size:0.78rem;color:#999;">/{{ $post->slug }}</div>
                            </td>
                            <td style="padding:0.75rem 1rem;color:#666;">{{ $post->category ?? '-' }}</td>
                            <td style="padding:0.75rem 1rem;color:#666;">{{ $post->created_at->format('d.m.Y') }}</td>
                            <td style="padding:0.75rem 1rem;text-align:center;">
                                @if ($post->is_published)
                                    <span style="background:#ecfdf5;color:#065f46;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Veröffentlicht</span>
                                @else
                                    <span style="background:#f3f4f6;color:#6b7280;padding:0.2rem 0.6rem;border-radius:999px;font-size:0.75rem;font-weight:700;">Entwurf</span>
                                @endif
                            </td>
                            <td style="padding:0.75rem 1rem;text-align:right;">
                                <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                                    <button wire:click="edit({{ $post->id }})" style="padding:0.3rem 0.7rem;border:1px solid #e5e5e5;border-radius:4px;background:#fff;font-size:0.78rem;cursor:pointer;color:#374151;">Bearbeiten</button>
                                    <button wire:click="togglePublish({{ $post->id }})" style="padding:0.3rem 0.7rem;border:1px solid {{ $post->is_published ? '#f59e0b' : '#10b981' }};border-radius:4px;background:{{ $post->is_published ? '#fffbeb' : '#ecfdf5' }};font-size:0.78rem;cursor:pointer;color:{{ $post->is_published ? '#92400e' : '#065f46' }};">
                                        {{ $post->is_published ? 'Deaktivieren' : 'Veröffentlichen' }}
                                    </button>
                                    <button wire:click="delete({{ $post->id }})" wire:confirm="Wirklich löschen?" style="padding:0.3rem 0.7rem;border:1px solid #fecaca;border-radius:4px;background:#fef2f2;font-size:0.78rem;cursor:pointer;color:#991b1b;">Löschen</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding:3rem;text-align:center;color:#999;">
                                Noch keine Artikel vorhanden. Klicken Sie auf "+ Neuer Artikel" um zu beginnen.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top:1.5rem;text-align:center;">
            {{ $posts->links() }}
        </div>

    @elseif ($mode === 'edit')
        <div style="display:flex;align-items:center;gap:1rem;margin-bottom:2rem;">
            <button wire:click="backToList" style="padding:0.5rem 1rem;border:1px solid #e5e5e5;border-radius:6px;background:#fff;font-size:0.85rem;cursor:pointer;color:#666;">← Zurück</button>
            <h1 style="font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:700;color:#1a1a1a;margin:0;">{{ $editingId ? 'Artikel bearbeiten' : 'Neuer Artikel' }}</h1>
        </div>

        <div style="display:grid;grid-template-columns:2fr 1fr;gap:2rem;">
            {{-- Main Content --}}
            <div style="background:#fff;border:1px solid #e5e5e5;border-radius:10px;padding:2rem;">
                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-weight:700;font-size:0.85rem;color:#374151;margin-bottom:0.4rem;">Titel</label>
                    <input type="text" wire:model.live="title" style="width:100%;padding:0.6rem 0.8rem;border:1px solid #e5e5e5;border-radius:6px;font-size:1rem;outline:none;" placeholder="Artikeltitel">
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-weight:700;font-size:0.85rem;color:#374151;margin-bottom:0.4rem;">URL-Slug</label>
                    <input type="text" wire:model.live="slug" style="width:100%;padding:0.6rem 0.8rem;border:1px solid #e5e5e5;border-radius:6px;font-size:0.9rem;outline:none;font-family:monospace;" placeholder="artikel-titel">
                </div>

                <div style="margin-bottom:1.5rem;">
                    <label style="display:block;font-weight:700;font-size:0.85rem;color:#374151;margin-bottom:0.4rem;">Inhalt (Markdown)</label>
                    <textarea wire:model.live="content" style="width:100%;min-height:400px;padding:1rem;border:1px solid #e5e5e5;border-radius:6px;font-size:0.95rem;outline:none;font-family:monospace;line-height:1.7;resize:vertical;" placeholder="Schreiben Sie Ihren Artikel hier..."></textarea>
                </div>
            </div>

            {{-- Sidebar --}}
            <div>
                {{-- Publish --}}
                <div style="background:#fff;border:1px solid #e5e5e5;border-radius:10px;padding:1.5rem;margin-bottom:1.5rem;">
                    <h3 style="font-weight:700;font-size:0.9rem;color:#374151;margin:0 0 1rem;">Veröffentlichung</h3>
                    <label style="display:flex;align-items:center;gap:0.5rem;margin-bottom:1rem;cursor:pointer;font-size:0.9rem;">
                        <input type="checkbox" wire:model.live="isPublished" style="width:18px;height:18px;accent-color:#d8232a;">
                        Veröffentlicht
                    </label>
                    @if ($isPublished)
                        <div style="margin-bottom:1rem;">
                            <label style="display:block;font-size:0.8rem;color:#666;margin-bottom:0.3rem;">Veröffentlichungsdatum</label>
                            <input type="datetime-local" wire:model.live="publishedAt" style="width:100%;padding:0.4rem;border:1px solid #e5e5e5;border-radius:4px;font-size:0.85rem;">
                        </div>
                    @endif
                    <button wire:click="save" style="width:100%;background:#d8232a;color:#fff;border:none;padding:0.65rem;border-radius:6px;font-weight:700;font-size:0.9rem;cursor:pointer;">Speichern</button>
                </div>

                {{-- SEO --}}
                <div style="background:#fff;border:1px solid #e5e5e5;border-radius:10px;padding:1.5rem;margin-bottom:1.5rem;">
                    <h3 style="font-weight:700;font-size:0.9rem;color:#374151;margin:0 0 1rem;">SEO</h3>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.8rem;color:#666;margin-bottom:0.3rem;">Meta-Titel</label>
                        <input type="text" wire:model.live="metaTitle" style="width:100%;padding:0.4rem;border:1px solid #e5e5e5;border-radius:4px;font-size:0.85rem;" placeholder="SEO Titel">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.8rem;color:#666;margin-bottom:0.3rem;">Meta-Beschreibung</label>
                        <textarea wire:model.live="metaDescription" style="width:100%;min-height:80px;padding:0.4rem;border:1px solid #e5e5e5;border-radius:4px;font-size:0.85rem;resize:vertical;" placeholder="SEO Beschreibung für Suchmaschinen"></textarea>
                    </div>
                </div>

                {{-- Details --}}
                <div style="background:#fff;border:1px solid #e5e5e5;border-radius:10px;padding:1.5rem;">
                    <h3 style="font-weight:700;font-size:0.9rem;color:#374151;margin:0 0 1rem;">Details</h3>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.8rem;color:#666;margin-bottom:0.3rem;">Kategorie</label>
                        <input type="text" wire:model.live="category" style="width:100%;padding:0.4rem;border:1px solid #e5e5e5;border-radius:4px;font-size:0.85rem;" placeholder="z.B. TELC Tipps">
                    </div>
                    <div style="margin-bottom:1rem;">
                        <label style="display:block;font-size:0.8rem;color:#666;margin-bottom:0.3rem;">Auszug</label>
                        <textarea wire:model.live="excerpt" style="width:100%;min-height:80px;padding:0.4rem;border:1px solid #e5e5e5;border-radius:4px;font-size:0.85rem;resize:vertical;" placeholder="Kurze Zusammenfassung"></textarea>
                    </div>
                    <div>
                        <label style="display:block;font-size:0.8rem;color:#666;margin-bottom:0.3rem;">Bild-URL</label>
                        <input type="url" wire:model.live="featuredImage" style="width:100%;padding:0.4rem;border:1px solid #e5e5e5;border-radius:4px;font-size:0.85rem;" placeholder="https://...">
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
