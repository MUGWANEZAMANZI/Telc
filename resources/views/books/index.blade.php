<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bücher — ERFOLG</title>
        <link rel="icon" href="/logo.jpeg" sizes="any">
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Plus Jakarta Sans', sans-serif; }
            .font-serif-display { font-family: 'Playfair Display', serif; }
        </style>
        @livewireStyles
    </head>
    <body class="bg-stone-50 text-stone-900 min-h-screen flex flex-col">
        <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between shrink-0">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <img src="/logo.jpeg" class="h-10 w-auto rounded border border-stone-200" alt="ERFOLG Logo" />
                <span class="text-xl font-extrabold tracking-wider text-stone-900 uppercase">ERFOLG</span>
            </a>
            <nav class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="text-stone-600 hover:text-stone-900 text-sm font-bold transition-colors">Startseite</a>
                @auth
                    <a href="{{ route('dashboard') }}" class="bg-yellow-500 hover:bg-yellow-400 text-stone-950 px-6 py-2 rounded-full font-bold text-sm transition-colors uppercase tracking-wider">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="bg-yellow-500 hover:bg-yellow-400 text-stone-950 px-6 py-2 rounded-full font-bold text-sm transition-colors uppercase tracking-wider">Anmelden</a>
                @endauth
            </nav>
        </header>

        <main class="flex-1 w-full max-w-7xl mx-auto px-6 py-12">
            <div class="text-center mb-12">
                <h1 class="text-4xl md:text-5xl font-extrabold tracking-tight uppercase">Bücher & <span class="text-yellow-600">Lernmaterial</span></h1>
                <p class="text-stone-600 mt-3 max-w-xl mx-auto">Lese PDFs zu deiner Prüfungsvorbereitung – jederzeit, überall.</p>
            </div>

            @if ($books->isEmpty())
                <div class="text-center py-20">
                    <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                    </svg>
                    <p class="text-stone-500 text-lg">Noch keine Bücher verfügbar. Schau bald wieder vorbei!</p>
                </div>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                    @foreach ($books as $book)
                        <a href="{{ route('books.read', $book) }}" class="group block bg-white rounded-xl border border-stone-200 overflow-hidden hover:border-yellow-500 hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                            <div class="aspect-[4/3] bg-stone-100 flex items-center justify-center overflow-hidden">
                                @if ($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <svg class="w-16 h-16 text-stone-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                    </svg>
                                @endif
                            </div>
                            <div class="p-5">
                                <div class="flex items-center gap-2 mb-2">
                                    @if ($book->level)
                                        <span class="text-[10px] font-bold uppercase tracking-wider bg-red-50 text-red-600 px-2 py-0.5 rounded">{{ $book->level }}</span>
                                    @endif
                                </div>
                                <h3 class="font-bold text-lg text-stone-900 group-hover:text-yellow-600 transition-colors">{{ $book->title }}</h3>
                                @if ($book->author)
                                    <p class="text-sm text-stone-500 mt-1">{{ $book->author }}</p>
                                @endif
                                @if ($book->description)
                                    <p class="text-sm text-stone-600 mt-2 line-clamp-2">{{ $book->description }}</p>
                                @endif
                                <span class="inline-flex items-center gap-1 text-sm font-bold text-yellow-600 mt-3 group-hover:gap-2 transition-all">
                                    Lesen
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m9 12.75 3 3m0 0 3-3m-3 3v-7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </span>
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </main>

        @livewireScripts
    </body>
</html>
