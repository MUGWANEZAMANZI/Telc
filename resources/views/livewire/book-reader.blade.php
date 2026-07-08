<div>
    {{-- Reader Header --}}
    <header class="bg-white border-b border-stone-200 px-4 py-3 flex items-center justify-between shrink-0">
        <div class="flex items-center gap-4">
            <a href="{{ route('books.index') }}" class="flex items-center gap-1 text-stone-500 hover:text-stone-900 transition-colors">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                </svg>
                <span class="text-sm font-bold">Zurück</span>
            </a>
            <div class="h-4 w-px bg-stone-300"></div>
            <h1 class="text-sm font-bold text-stone-900 truncate max-w-[200px] md:max-w-md">{{ $book->title }}</h1>
            @if ($book->level)
                <span class="text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-600 px-2 py-0.5 rounded shrink-0">{{ $book->level }}</span>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('home') }}" class="text-xs text-stone-500 hover:text-stone-900 transition-colors">ERFOLG</a>
        </div>
    </header>

    {{-- PDF Viewer --}}
    <main class="flex-1 w-full flex flex-col items-center py-6 px-4">
        <div class="w-full max-w-4xl bg-white rounded-xl shadow-lg overflow-hidden" style="height: calc(100vh - 140px);">
            <iframe
                src="{{ $pdfUrl }}#view=FitH"
                class="w-full h-full"
                style="border: none;"
                title="{{ $book->title }}"
            >
                <p>Ihr Browser unterstützt keine PDF-Anzeige. 
                   <a href="{{ $pdfUrl }}" download class="text-yellow-600 underline">PDF herunterladen</a>
                </p>
            </iframe>
        </div>
        <div class="mt-4 text-center">
            <a href="{{ $pdfUrl }}" download class="inline-flex items-center gap-2 text-sm text-stone-600 hover:text-stone-900 transition-colors font-bold">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                PDF herunterladen
            </a>
        </div>
    </main>
</div>
