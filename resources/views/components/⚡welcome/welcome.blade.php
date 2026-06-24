<div class="flex w-full flex-col lg:flex-row items-start justify-between gap-8 opacity-100 transition-opacity duration-750 lg:grow starting:opacity-0 max-w-7xl mx-auto p-4 font-sans">
    

     <!-- Sidebar Navigation / Course Levels -->
    <aside class="w-full lg:w-1/4 bg-white border border-gray-100 rounded-2xl p-6 shadow-md">
        <h3 class="text-xs font-bold uppercase tracking-widest text-gray-400 mb-4">Verfügbare Kurse</h3>
        
        <ul class="space-y-2.5">
            <!-- Active/Highlighted Exam -->
            <li class="bg-gradient-to-r from-emerald-50 to-transparent border-l-4 border-emerald-500 p-3 rounded-r-xl">
                <span class="block text-xs text-emerald-700 font-bold uppercase tracking-wider">Top-Kurs</span>
                <span class="font-bold text-gray-900 block text-sm">Telc C1 Prüfungserfolg</span>
            </li>
            
            <!-- Other levels designed as clean, clickable items -->
            <li>
                <a href="#b1" class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 text-gray-600 hover:text-gray-900 font-medium text-sm transition">
                    <span>Deutsch B1</span>
                    <span class="text-xs text-gray-400">Mittelstufe</span>
                </a>
            </li>
            <li>
                <a href="#b2" class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 text-gray-600 hover:text-gray-900 font-medium text-sm transition">
                    <span>Deutsch B2</span>
                    <span class="text-xs text-gray-400">Beruf</span>
                </a>
            </li>
            <li>
                <a href="#c1" class="flex items-center justify-between p-3 bg-gray-50 rounded-xl text-emerald-600 font-semibold text-sm transition">
                    <span>Deutsch C1</span>
                    <span class="text-xs bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-md font-bold">Aktiv</span>
                </a>
            </li>
            <li>
                <a href="#c2" class="flex items-center justify-between p-3 rounded-xl hover:bg-gray-50 text-gray-600 hover:text-gray-900 font-medium text-sm transition">
                    <span>Deutsch C2</span>
                    <span class="text-xs text-gray-400">Experte</span>
                </a>
            </li>
        </ul>
    </aside>
    <!-- Main Course/Hero Section -->
    <div class="w-full lg:w-3/4">
        <main class="relative flex w-full flex-col overflow-hidden rounded-2xl shadow-xl border border-gray-100 group">
            <!-- Image Overlay Gradient for a premium feel -->
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent z-10"></div>
            
            <img src="a_hero.png" class="w-full h-[450px] object-cover transform group-hover:scale-105 transition duration-700 ease-out" alt="Telc C1 Vorbereitungskurs" />

            <!-- Core Value Proposition Text Overlay -->
            <div class="absolute bottom-24 left-8 right-8 z-20 text-white hidden md:block">
                <span class="bg-amber-500 text-gray-900 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full">Erfolgsgarantie</span>
                <h2 class="text-3xl font-extrabold mt-2 tracking-tight drop-shadow-sm">Meistern Sie die Telc C1 Prüfung</h2>
                <p class="text-gray-200 mt-1 text-sm max-w-md">Der direkte Weg zu Ihrem akademischen und beruflichen Erfolg in Deutschland.</p>
            </div>

            <!-- Premium Call-to-Action Button -->
            <a href="c1" 
               class="absolute bottom-6 left-1/2 -translate-x-1/2 md:left-8 md:translate-x-0 z-20 bg-emerald-600 text-white font-semibold px-8 py-3.5 rounded-xl shadow-lg shadow-emerald-600/30 hover:bg-emerald-500 hover:shadow-emerald-500/40 hover:-translate-y-0.5 transition-all duration-200 text-center min-w-[200px]">
                Jetzt C1 Starten
            </a>
        </main>
    </div>

</div>