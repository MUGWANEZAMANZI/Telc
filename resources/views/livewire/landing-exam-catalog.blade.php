<div class="space-y-6 scroll-mt-24" id="exam-catalog">
    <div class="text-center space-y-2">
        <span class="text-xs font-bold uppercase tracking-widest text-red-600">Prüfungskatalog</span>
        <h2 class="text-3xl md:text-5xl font-extrabold tracking-tight uppercase">
            Wähle deine <span class="text-yellow-600">Prüfung</span>
        </h2>
        <p class="text-stone-600 max-w-xl mx-auto text-sm md:text-base">
            Bereite dich auf telc- und Goethe-Zertifikate vor – kostenlos oder mit vollem Zugang.
        </p>
    </div>

    {{-- Provider Tabs --}}
    <div class="flex justify-center gap-4">
        <button type="button"
                wire:click="selectProvider('telc')"
                class="px-10 py-1 rounded-full font-extrabold uppercase tracking-wider text-sm transition-all duration-200 border-2"
                @class([
                    'bg-stone-900 text-white border-stone-900 shadow-lg' => $selectedProvider === 'telc',
                    'bg-white text-stone-700 border-stone-200 hover:border-stone-400' => $selectedProvider !== 'telc',
                ])>
            TELC
        </button>
        <button type="button"
                wire:click="selectProvider('goethe')"
                class="px-10 py-1 rounded-full font-extrabold uppercase tracking-wider text-sm transition-all duration-200 border-2"
                @class([
                    'bg-stone-900 text-white border-stone-900 shadow-lg' => $selectedProvider === 'goethe',
                    'bg-white text-stone-700 border-stone-200 hover:border-stone-400' => $selectedProvider !== 'goethe',
                ])>
            GOETHE
        </button>
    </div>

    {{-- Level Pills --}}
    @if (count($levels) > 0)
        <div class="flex justify-center gap-3">
            @foreach ($levels as $level)
                <button type="button"
                        wire:click="selectLevel('{{ $level }}')"
                        class="px-6 py-1 rounded-full font-bold text-sm transition-all duration-200"
                        @class([
                            'bg-yellow-500 text-stone-950' => $selectedLevel === $level,
                            'bg-stone-100 text-stone-600 hover:bg-stone-200' => $selectedLevel !== $level,
                        ])>
                    {{ $level }}
                </button>
            @endforeach
        </div>
    @endif
     @if ($selectedLevel)
         <div class="space-y-8">
             {{-- Free Exams --}}
             @if ($freeExams->isNotEmpty())
                 <div>
                     <h3 class="text-lg font-bold uppercase tracking-wider text-stone-700 mb-4">Kostenlose Übungen</h3>
                     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                         @foreach ($freeExams as $exam)
                             <article class="bg-white border border-stone-200 rounded-xl p-6 flex flex-col justify-between transition-all duration-200 hover:border-yellow-500 hover:shadow-md">
                                 <div class="space-y-3">
                                     <div class="flex items-center justify-between">
                                         <span class="text-xs font-bold uppercase tracking-widest text-stone-400 border border-stone-200 rounded-full px-3 py-1">
                                             {{ $exam->exam_provider }} {{ $exam->level }}
                                         </span>
                                         <span class="text-xs font-bold text-green-600 bg-green-50 border border-green-200 rounded-full px-3 py-1">
                                             {{ $exam->price_label }}
                                         </span>
                                     </div>
                                     <h4 class="font-bold text-stone-900 text-sm leading-tight">{{ $exam->title }}</h4>
                                     @if (! empty($exam->timings))
                                         <div class="flex flex-wrap gap-2">
                                             @foreach (array_slice($exam->timings, 0, 3) as $timing)
                                                 <span class="text-xs text-stone-500 bg-stone-50 rounded px-2 py-0.5">
                                                     {{ $timing['label'] }} {{ $timing['minutes'] }}min
                                                 </span>
                                             @endforeach
                                         </div>
                                     @endif
                                 </div>
                                 <div class="mt-4">
                                     <button type="button"
                                             wire:click="selectExam({{ $exam->id }})"
                                             class="w-full bg-yellow-500 hover:bg-yellow-400 text-stone-950 font-bold px-4 py-2.5 rounded-full text-xs uppercase tracking-wider transition-colors duration-200">
                                         Prüfung starten
                                     </button>
                                 </div>
                             </article>
                         @endforeach
                     </div>

                     {{-- See More --}}
                     <div class="mt-6 text-center">
                         @if ($isAuth)
                             <a href="{{ route('dashboard') }}"
                                class="inline-block text-sm font-bold text-yellow-600 hover:text-yellow-700 underline underline-offset-4 transition-colors">
                                 Alle Übungen im Dashboard anzeigen &rarr;
                             </a>
                         @else
                             <a href="{{ route('register') }}"
                                class="inline-block text-sm font-bold text-yellow-600 hover:text-yellow-700 underline underline-offset-4 transition-colors">
                                 Registrieren, um alle Übungen anzuzeigen &rarr;
                             </a>
                         @endif
                     </div>
                 </div>
             @endif

             {{-- Paid Exams --}}
             @if ($paidExams->isNotEmpty())
                 <div>
                     <h3 class="text-lg font-bold uppercase tracking-wider text-stone-700 mb-4">Premium-Prüfungen</h3>
                     <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                         @foreach ($paidExams as $exam)
                             <article class="bg-white border-2 border-stone-300 rounded-xl p-6 flex flex-col justify-between transition-all duration-200 hover:border-stone-900 hover:shadow-lg">
                                 <div class="space-y-3">
                                     <div class="flex items-center justify-between">
                                         <span class="text-xs font-bold uppercase tracking-widest text-stone-400 border border-stone-200 rounded-full px-3 py-1">
                                             {{ $exam->exam_provider }} {{ $exam->level }}
                                         </span>
                                         <span class="text-xs font-bold text-stone-900 bg-stone-100 border border-stone-300 rounded-full px-3 py-1">
                                             {{ $exam->price_label }}
                                         </span>
                                     </div>
                                     <h4 class="font-bold text-stone-900 text-sm leading-tight">{{ $exam->title }}</h4>
                                     @if (! empty($exam->timings))
                                         <div class="flex flex-wrap gap-2">
                                             @foreach (array_slice($exam->timings, 0, 3) as $timing)
                                                 <span class="text-xs text-stone-500 bg-stone-50 rounded px-2 py-0.5">
                                                     {{ $timing['label'] }} {{ $timing['minutes'] }}min
                                                 </span>
                                             @endforeach
                                         </div>
                                     @endif
                                 </div>
                                 <div class="mt-4">
                                     <button type="button"
                                             wire:click="selectExam({{ $exam->id }})"
                                             class="w-full bg-stone-900 hover:bg-stone-800 text-white font-bold px-4 py-2.5 rounded-full text-xs uppercase tracking-wider transition-colors duration-200">
                                         Freischalten
                                     </button>
                                 </div>
                             </article>
                         @endforeach
                     </div>
                 </div>
             @endif

             {{-- Empty State --}}
             @if ($freeExams->isEmpty() && $paidExams->isEmpty())
                 <div class="text-center py-12">
                     <p class="text-stone-500 text-sm">Derzeit sind keine Prüfungen für diese Auswahl verfügbar.</p>
                 </div>
             @endif
         </div>
     @else
         <div class="text-center py-12">
             <p class="text-stone-500 text-sm">Keine Prüfungsstufen gefunden.</p>
         </div>
     @endif
</div>
