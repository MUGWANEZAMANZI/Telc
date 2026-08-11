<!DOCTYPE html>
<html lang="de" translate="no" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? '' }} — GERMAN SUCCESS</title>
    <script defer src="https://analytics.germansuccess.com/script.js" data-website-id="9365fe70-e29e-4370-bc7f-f3b96ee73200"></script>
    <link rel="icon" href="/logo.jpeg" sizes="any">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .font-serif-display { font-family: 'Playfair Display', serif; }
    </style>
    @livewireStyles
</head>
<body class="bg-stone-100 text-stone-900 min-h-screen flex flex-col">
    {{ $slot }}
    @livewireScripts
</body>
</html>
