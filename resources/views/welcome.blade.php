<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ERFOLG - KI-gestütztes C1 Deutschzertifikat-Portal</title>

        <link rel="icon" href="/logo.jpeg" sizes="any">
        <script src="https://cdn.tailwindcss.com"></script>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap" rel="stylesheet">

        <style>
            body {
                font-family: 'Plus Jakarta Sans', sans-serif;
            }
            .font-serif-display {
                font-family: 'Playfair Display', serif;
            }
            @keyframes bookPulse {
                0%, 100% {
                    transform: scale(1);
                }
                50% {
                    transform: scale(1.1);
                }
            }
            .animate-book-pulse {
                animation: bookPulse 0.5s ease-in-out infinite;
            }
        </style>
        @livewireStyles
    </head>
    <body class="bg-stone-50 text-stone-900 min-h-screen flex flex-col justify-between overflow-x-hidden relative" x-data="{ showSticky: false }" @scroll.window="showSticky = window.pageYOffset > 500">

        <!-- AI Background Orbs -->
        <div class="absolute inset-0 overflow-hidden -z-10 pointer-events-none">
            <div class="absolute -top-40 -left-40 w-96 h-96 bg-red-100/40 rounded-full blur-3xl animate-[pulse_6s_infinite_ease-in-out]"></div>
            <div class="absolute top-1/4 right-0 w-[500px] h-[500px] bg-yellow-100/30 rounded-full blur-3xl animate-[pulse_10s_infinite_ease-in-out]"></div>
            <div class="absolute bottom-10 left-10 w-[600px] h-[600px] bg-red-50/40 rounded-full blur-3xl animate-[pulse_8s_infinite_ease-in-out]"></div>
        </div>

        <!-- Navigation -->
        <header class="w-full max-w-7xl mx-auto px-6 py-6 flex items-center justify-between z-10 shrink-0">
            <div class="flex items-center gap-3">
                <img src="logo.jpeg" class="h-10 w-auto rounded border border-stone-200" alt="ERFOLG Logo" />
                <span class="text-xl font-extrabold tracking-wider text-stone-900 uppercase">ERFOLG</span>
            </div>
            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="bg-yellow-500 hover:bg-yellow-400 text-stone-950 px-6 py-2 rounded-full font-bold text-sm transition-colors duration-200 uppercase tracking-wider">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-stone-600 hover:text-stone-900 text-sm font-bold transition-colors duration-200">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="bg-white hover:bg-stone-100 border border-stone-200 px-6 py-2 rounded-full font-bold text-sm text-stone-900 transition-all duration-200 uppercase tracking-wider">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <!-- Main Content -->
        <main class="flex-1 w-full max-w-7xl mx-auto px-6 flex flex-col justify-center space-y-24 py-6">
            
            <!-- 1. Exam Catalog -->
            <livewire:landing-exam-catalog />

            <!-- 2. Hero Section -->
            <section class="text-center relative flex flex-col items-center justify-center min-h-[70vh]" 
                     x-data="{ show: false }" 
                     x-init="setTimeout(() => show = true, 50)">
                
                <div x-show="show" 
                     x-transition:enter="transition ease-out duration-1000" 
                     x-transition:enter-start="opacity-0 translate-y-12" 
                     x-transition:enter-end="opacity-100 translate-y-0"
                     class="max-w-4xl space-y-6">
                     
                     <div class="inline-flex items-center gap-2 bg-red-50 border border-red-200 px-4 py-1.5 rounded-full text-xs font-bold text-red-600 uppercase tracking-widest mb-4">
                         <span class="h-2 w-2 rounded-full bg-red-600 animate-ping"></span>
                         Next-Gen C1 Vorbereitung
                     </div>

                     <h1 class="text-5xl md:text-8xl font-extrabold tracking-tight text-stone-900 leading-none uppercase">
                         ERHALTE DEINEN <br>
                         <span class="text-yellow-600 font-serif-display italic lowercase font-normal">erfolg.</span>
                     </h1>

                     <p class="text-lg md:text-xl text-stone-600 max-w-2xl mx-auto leading-relaxed">
                         Bestehen Sie die Goethe / TELC C1 Deutschprüfung im ersten Versuch. 
                         Interaktive KI-Sprechprüfungen, echtes Papier-Design und Live-Zertifikatsbewertung.
                     </p>

                     <div class="flex flex-col sm:flex-row gap-4 justify-center items-center pt-6">
                         @auth
                             <a href="{{ route('dashboard') }}" class="w-full sm:w-auto bg-yellow-500 hover:bg-yellow-400 text-stone-950 font-extrabold px-8 py-4 rounded-full uppercase tracking-wider transition-colors duration-200 shadow-[0_0_30px_rgba(234,179,8,0.15)] text-sm text-center">
                                 Prüfungssimulation starten
                             </a>
                         @else
                             <a href="{{ route('register') }}" class="w-full sm:w-auto bg-yellow-500 hover:bg-yellow-400 text-stone-950 font-extrabold px-8 py-4 rounded-full uppercase tracking-wider transition-colors duration-200 shadow-[0_0_30px_rgba(234,179,8,0.15)] text-sm text-center">
                                 Jetzt kostenlos starten
                             </a>
                             <a href="#vorteile" class="w-full sm:w-auto bg-white hover:bg-stone-100 border border-stone-200 text-stone-700 px-8 py-4 rounded-full font-bold uppercase tracking-wider transition-colors duration-200 text-sm text-center">
                                 Mehr erfahren
                             </a>
                         @endauth
                     </div>
                </div>
            </section>

            <!-- 3. Value Proposition Cards -->
            <section id="vorteile" class="space-y-12 scroll-mt-24">
                <div class="text-center space-y-4">
                    <span class="text-xs font-bold uppercase tracking-widest text-red-600">Das Konzept</span>
                    <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight uppercase">Warum <span class="text-yellow-600">ERFOLG</span> funktioniert</h2>
                    <p class="text-stone-600 max-w-xl mx-auto text-sm md:text-base">Unsere Lernplattform wurde mit nur einem Fokus entwickelt: Maximale Effizienz am Prüfungstag.</p>
                </div>/div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Card 1 -->
                    <article class="bg-white p-8 rounded-xl border transition-all duration-300 flex flex-col justify-between"
                             x-data="{ hover: false }"
                             @mouseenter="hover = true"
                             @mouseleave="hover = false"
                             :class="hover ? 'border-yellow-500 -translate-y-2 shadow-[0_10px_30px_rgba(250,204,21,0.05)]' : 'border-stone-200 translate-y-0'">
                        <div class="space-y-4 text-left">
                            <div class="h-12 w-12 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path>
                                    <path d="M19 10v1a7 7 0 0 1-14 0v-1"></path>
                                    <line x1="12" y1="19" x2="12" y2="22"></line>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold uppercase text-stone-900">KI-Sprechprüfung</h3>
                            <p class="text-sm text-stone-600 leading-relaxed">
                                Simulieren Sie den mündlichen Ausdruck C1. Sprechen Sie per Mikrofon mit unserem AI-Prüfer. Erhalten Sie sofortiges Feedback nach echten TELC-Richtlinien.
                            </p>
                        </div>
                    </article>

                    <!-- Card 2 -->
                    <article class="bg-white p-8 rounded-xl border transition-all duration-300 flex flex-col justify-between"
                             x-data="{ hover: false }"
                             @mouseenter="hover = true"
                             @mouseleave="hover = false"
                             :class="hover ? 'border-red-500 -translate-y-2 shadow-[0_10px_30px_rgba(220,38,38,0.05)]' : 'border-stone-200 translate-y-0'">
                        <div class="space-y-4 text-left">
                            <div class="h-12 w-12 rounded-lg bg-red-50 flex items-center justify-center text-red-600">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold uppercase text-stone-900">Fokussiertes Papier-Design</h3>
                            <p class="text-sm text-stone-600 leading-relaxed">
                                Lernen Sie im originalen telc Prüfungsheft-Stil. Lineare Ein-Spalten-Dokumente mit liniertem Konzeptpapier helfen Ihnen, sich ganz auf die Fragen zu fokussieren.
                            </p>
                        </div>
                    </article>

                    <!-- Card 3 -->
                    <article class="bg-white p-8 rounded-xl border transition-all duration-300 flex flex-col justify-between"
                             x-data="{ hover: false }"
                             @mouseenter="hover = true"
                             @mouseleave="hover = false"
                             :class="hover ? 'border-yellow-500 -translate-y-2 shadow-[0_10px_30px_rgba(250,204,21,0.05)]' : 'border-stone-200 translate-y-0'">
                        <div class="space-y-4 text-left">
                            <div class="h-12 w-12 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="8" r="7"></circle>
                                    <polyline points="8.21 13.89 7 23 12 20 17 23 15.79 13.88"></polyline>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold uppercase text-stone-900">Intelligentes AI-Zertifikat</h3>
                            <p class="text-sm text-stone-600 leading-relaxed">
                                Vollautomatische Bewertung des schriftlichen Aufsatzes und Ihrer Redemittel. Erhalten Sie eine exakte Notenbegründung und detailliertes C1-Sprachfeedback.
                            </p>
                        </div>
                    </article>
                </div>
            </section>

            <!-- 3. Social Proof / Statistics -->
            <section class="bg-white border border-stone-200 rounded-2xl p-8 md:p-16 space-y-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-12 text-center">
                    <div class="space-y-2">
                        <span class="block text-5xl md:text-7xl font-extrabold text-red-600">94%</span>
                        <strong class="block text-sm uppercase tracking-wider text-stone-900">Bestehensquote</strong>
                        <p class="text-xs text-stone-500 max-w-[200px] mx-auto">unserer aktiven Abonnenten in der echten telc C1 Prüfung.</p>
                    </div>
                    <div class="space-y-2">
                        <span class="block text-5xl md:text-7xl font-extrabold text-red-600">3.2k+</span>
                        <strong class="block text-sm uppercase tracking-wider text-stone-900">Sprechprüfungen</strong>
                        <p class="text-xs text-stone-500 max-w-[200px] mx-auto">durchgeführte interaktive Konversationen mit unserem KI-Prüfer.</p>
                    </div>
                    <div class="space-y-2">
                        <span class="block text-5xl md:text-7xl font-extrabold text-red-600">&lt; 2s</span>
                        <strong class="block text-sm uppercase tracking-wider text-stone-900">KI-Latenz</strong>
                        <p class="text-xs text-stone-500 max-w-[200px] mx-auto">für direkte Rückfragen und flüssige Gesprächsführung im mündlichen Teil.</p>
                    </div>
                </div>

                <!-- Testimonial card -->
                <div class="max-w-3xl mx-auto border-t border-stone-200 pt-12 text-center">
                    <p class="text-lg md:text-xl font-serif-display italic text-stone-800 leading-relaxed">
                        "Dank der realistischen Simulation der Sprechprüfung im Browser konnte ich meine Nervosität komplett ablegen. Das direkte Feedback zur Grammatik war der Schlüssel zu meiner C1-Note 'Sehr gut'!"
                    </p>
                    <span class="block text-sm font-bold uppercase text-yellow-600 mt-4 tracking-wider">— Maxim G., Erfolgreicher TELC-Absolvent</span>
                </div>
            </section>

        </main>

        <!-- Footer -->
        <footer class="w-full max-w-7xl mx-auto px-6 py-12 border-t border-stone-200 flex flex-col md:flex-row justify-between items-center gap-6 text-sm text-stone-500 shrink-0">
            <div>
                <span>&copy; {{ date('Y') }} ERFOLG. Alle Rechte vorbehalten.</span>
            </div>
            <div class="flex gap-6">
                <a href="{{ route('pricing') }}" class="hover:text-stone-900 transition-colors">Preise</a>
                <a href="{{ route('free.exam') }}" class="hover:text-stone-900 transition-colors">Kostenlose Übung</a>
            </div>
        </footer>

        <!-- 4. Floating/Sticky CTA -->
        <div x-show="showSticky"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-10"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-10"
             class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-white/90 backdrop-blur border border-stone-200 px-6 py-4 rounded-full shadow-2xl z-50 flex items-center gap-6 min-w-[320px] md:min-w-[450px] justify-between pointer-events-auto">
             <div class="text-left">
                 <span class="text-[10px] text-stone-500 block uppercase font-bold tracking-wider leading-none mb-1">Bereit für den Erfolg?</span>
                 <span class="text-xs text-stone-900 font-bold block leading-none">Jetzt C1-Zertifikat sichern</span>
             </div>
             @auth
                 <a href="{{ route('dashboard') }}" class="bg-yellow-500 hover:bg-yellow-400 text-stone-950 font-extrabold px-5 py-2.5 rounded-full text-xs uppercase tracking-wider transition-colors duration-200">
                     Prüfung starten
                 </a>
             @else
                 <a href="{{ route('register') }}" class="bg-yellow-500 hover:bg-yellow-400 text-stone-950 font-extrabold px-5 py-2.5 rounded-full text-xs uppercase tracking-wider transition-colors duration-200">
                     Kostenlos registrieren
                 </a>
             @endauth
        </div>

        <!-- Floating Bookshelf Button (bottom-right corner with pulse/scale animation, visible immediately) -->
        <div class="fixed bottom-6 right-6 z-50 animate-book-pulse">
            <a href="{{ route('books.index') }}"
               class="flex items-center justify-center w-14 h-14 bg-yellow-500 border-2 border-yellow-600 rounded-full shadow-2xl hover:bg-yellow-400 text-stone-950 transition-all duration-200 group"
               title="Bücher & Lernmaterial">
                <svg class="w-6 h-6 text-stone-950 group-hover:scale-110 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                </svg>
            </a>
        </div>

        <livewire:contact-form />

    @livewireScripts
    </body>
</html>