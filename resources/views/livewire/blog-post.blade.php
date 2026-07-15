@php
    $post = $this->post;
@endphp

<article style="max-width:800px;margin:0 auto;padding:2rem 1rem;">
    {{-- Breadcrumb --}}
    <nav style="margin-bottom:2rem;font-size:0.85rem;">
        <a href="{{ route('blog') }}" style="color:#d8232a;text-decoration:none;font-weight:600;">← Zurück zum Blog</a>
    </nav>

    {{-- Header --}}
    <header style="margin-bottom:2.5rem;">
        @if ($post->category)
            <span style="display:inline-block;font-size:0.7rem;font-weight:700;text-transform:uppercase;letter-spacing:0.15em;color:#d8232a;background:#fdf2f2;padding:0.3rem 0.75rem;border-radius:4px;margin-bottom:1rem;">{{ $post->category }}</span>
        @endif
        <h1 style="font-family:'Playfair Display',serif;font-size:2.5rem;font-weight:700;color:#1a1a1a;line-height:1.2;margin:0 0 1rem;">{{ $post->title }}</h1>
        <div style="display:flex;align-items:center;gap:1rem;color:#999;font-size:0.85rem;">
            <time datetime="{{ $post->published_at->format('Y-m-d') }}">{{ $post->published_at->format('d. M Y') }}</time>
            @if ($post->author)
                <span>·</span>
                <span>{{ $post->author->name }}</span>
            @endif
        </div>
    </header>

    {{-- Featured Image --}}
    @if ($post->featured_image)
        <div style="margin-bottom:2.5rem;border-radius:10px;overflow:hidden;">
            <img src="{{ $post->featured_image }}" alt="{{ $post->title }}" style="width:100%;height:auto;display:block;">
        </div>
    @endif

    {{-- Content --}}
    <div style="font-size:1.05rem;line-height:1.9;color:#333;">
        {!! nl2br(e($post->content)) !!}
    </div>

    {{-- Share --}}
    <div style="margin-top:3rem;padding-top:2rem;border-top:1px solid #e5e5e5;">
        <p style="font-size:0.85rem;font-weight:700;color:#666;margin-bottom:0.75rem;">Artikel teilen:</p>
        <div style="display:flex;gap:0.75rem;">
            <a href="https://twitter.com/intent/tweet?text={{ urlencode($post->title) }}&url={{ urlencode(route('blog.show', $post->slug)) }}" target="_blank" rel="noopener"
               style="padding:0.5rem 1rem;border:1px solid #e5e5e5;border-radius:6px;font-size:0.82rem;color:#666;text-decoration:none;transition:all 0.2s;"
               onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'"
               onmouseout="this.style.borderColor='#e5e5e5';this.style.color='#666'">Twitter</a>
            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $post->slug)) }}" target="_blank" rel="noopener"
               style="padding:0.5rem 1rem;border:1px solid #e5e5e5;border-radius:6px;font-size:0.82rem;color:#666;text-decoration:none;transition:all 0.2s;"
               onmouseover="this.style.borderColor='#1a1a1a';this.style.color='#1a1a1a'"
               onmouseout="this.style.borderColor='#e5e5e5';this.style.color='#666'">Facebook</a>
        </div>
    </div>
</article>
