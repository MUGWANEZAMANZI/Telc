<div style="max-width:1200px;margin:0 auto;padding:2rem 1rem;">
    {{-- Header --}}
    <div style="text-align:center;margin-bottom:3rem;">
        <span style="font-size:0.75rem;font-weight:700;letter-spacing:0.15em;text-transform:uppercase;color:#d8232a;">Blog</span>
        <h1 style="font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:#1a1a1a;margin:0.5rem 0;">ERFOLG Blog</h1>
        <p style="color:#666;font-size:1.05rem;max-width:600px;margin:0 auto;">Tipps, Strategien und Neuigkeiten zur Vorbereitung auf Ihre TELC-Prüfung.</p>
    </div>

    {{-- Search & Filter --}}
    <div style="display:flex;gap:1rem;margin-bottom:2rem;flex-wrap:wrap;justify-content:center;">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Artikel suchen..."
               style="max-width:320px;width:100%;padding:0.6rem 1rem;border:1px solid #e5e5e5;border-radius:8px;font-size:0.9rem;outline:none;transition:border 0.2s;"
               onfocus="this.style.borderColor='#1a1a1a'" onblur="this.style.borderColor='#e5e5e5'">
        <div style="display:flex;gap:0.5rem;flex-wrap:wrap;">
            <button wire:click="$set('category', '')"
                    style="padding:0.5rem 1rem;border-radius:999px;font-size:0.82rem;font-weight:700;border:1px solid {{ $category === '' ? '#1a1a1a' : '#e5e5e5' }};background:{{ $category === '' ? '#1a1a1a' : '#fff' }};color:{{ $category === '' ? '#fff' : '#666' }};cursor:pointer;transition:all 0.2s;">
                Alle
            </button>
            @foreach ($categories as $cat)
                <button wire:click="$set('category', '{{ $cat }}')"
                        style="padding:0.5rem 1rem;border-radius:999px;font-size:0.82rem;font-weight:700;border:1px solid {{ $category === $cat ? '#1a1a1a' : '#e5e5e5' }};background:{{ $category === $cat ? '#1a1a1a' : '#fff' }};color:{{ $category === $cat ? '#fff' : '#666' }};cursor:pointer;transition:all 0.2s;">
                    {{ $cat }}
                </button>
            @endforeach
        </div>
    </div>

    {{-- Posts Grid --}}
    @if ($posts->count() > 0)
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(340px,1fr));gap:1.5rem;">
            @foreach ($posts as $post)
                <a href="{{ route('blog.show', $post->slug) }}" style="display:block;background:#fff;border:1px solid #e5e5e5;border-radius:10px;overflow:hidden;transition:all 0.2s;text-decoration:none;color:inherit;box-shadow:0 2px 10px rgba(0,0,0,0.03);"
                   onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 25px rgba(0,0,0,0.08)'"
                   onmouseout="this.style.transform='';this.style.boxShadow='0 2px 10px rgba(0,0,0,0.03)'">
                    @if ($post->featured_image)
                        <div style="height:200px;overflow:hidden;">
                            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                    @else
                        <div style="height:120px;background:linear-gradient(135deg,#1a1a1a,#333);display:flex;align-items:center;justify-content:center;">
                            <span style="font-family:'Playfair Display',serif;font-size:2rem;color:#eab308;font-weight:700;">{{ strtoupper(substr($post->title, 0, 1)) }}</span>
                        </div>
                    @endif
                    <div style="padding:1.5rem;">
                        @if ($post->category)
                            <span style="font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.1em;color:#d8232a;">{{ $post->category }}</span>
                        @endif
                        <h2 style="font-family:'Playfair Display',serif;font-size:1.25rem;font-weight:700;color:#1a1a1a;margin:0.5rem 0;line-height:1.3;">{{ $post->title }}</h2>
                        @if ($post->excerpt)
                            <p style="color:#666;font-size:0.88rem;line-height:1.6;margin:0.75rem 0;">{{ Str::limit($post->excerpt, 150) }}</p>
                        @endif
                        <div style="display:flex;align-items:center;gap:0.5rem;margin-top:1rem;font-size:0.78rem;color:#999;">
                            <time datetime="{{ $post->published_at->format('Y-m-d') }}">{{ $post->published_at->format('d. M Y') }}</time>
                            @if ($post->author)
                                <span>·</span>
                                <span>{{ $post->author->name }}</span>
                            @endif
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        <div style="margin-top:2rem;text-align:center;">
            {{ $posts->links() }}
        </div>
    @else
        <div style="text-align:center;padding:4rem 2rem;color:#999;">
            <svg style="width:48px;height:48px;margin:0 auto 1rem;color:#ccc;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
            </svg>
            <p style="font-size:1rem;">Noch keine Artikel veröffentlicht.</p>
        </div>
    @endif
</div>
