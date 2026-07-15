<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sprechübung - ERFOLG</title>
    <link rel="icon" href="/logo.jpeg" sizes="any">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200..800&display=swap" rel="stylesheet">
    <style>body { font-family: 'Plus Jakarta Sans', sans-serif; }</style>
    @livewireStyles
</head>
<body class="bg-stone-50 min-h-screen">
    <header class="bg-white border-b border-stone-200 px-6 py-4">
        <div class="max-w-4xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <img src="/logo.jpeg" class="h-8 w-auto rounded" alt="ERFOLG" />
                <span class="text-lg font-extrabold tracking-wider text-stone-900 uppercase">ERFOLG</span>
            </div>
            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}" class="text-stone-600 hover:text-stone-900 text-sm font-bold">Einloggen</a>
                <a href="{{ route('register') }}" class="bg-yellow-500 hover:bg-yellow-400 text-stone-950 px-5 py-2 rounded-full font-bold text-sm transition-colors">Konto erstellen</a>
            </div>
        </div>
    </header>
    <main class="max-w-4xl mx-auto px-4 py-6">
        <livewire:voice-input />
    </main>
    @livewireScripts
</body>
</html>
