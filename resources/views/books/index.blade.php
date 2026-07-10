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

            <livewire:book-catalog />
        </main>

        @livewireScripts
    </body>
</html>
