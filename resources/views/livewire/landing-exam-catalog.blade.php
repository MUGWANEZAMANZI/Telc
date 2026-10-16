<div class="space-y-6 scroll-mt-24" id="exam-catalog" x-data="{ showAllFree: false, showAllPaid: false }">
    <div class="text-center space-y-2">
        <span class="text-xs font-bold uppercase tracking-widest text-red-600">Prüfungskatalog</span>
        <h2 class="text-3xl md:text-3xl font-extrabold tracking-tight uppercase">
            Bestehe deine TELC oder Goethe <span class="text-yellow-600">Prüfung beim ersten Versuch</span>
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
            {{-- Free Exams + Voice Practice Side by Side --}}
            <div class="flex flex-col md:flex-row gap-6">
                {{-- Free Exams --}}
                @if ($freeExams->isNotEmpty())
                    <div class="flex-1">
                        <h3 class="text-lg font-bold uppercase tracking-wider text-stone-700 mb-4 flex items-center gap-2">
                            Kostenlose Übungen
                            <span class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-1.5 text-xs font-extrabold bg-yellow-100 text-yellow-700 rounded-full border border-yellow-200">
                                {{ $freeExams->count() }}
                            </span>
                        </h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            @foreach ($freeExams as $index => $exam)
                                <article class="bg-white border border-stone-200 rounded-xl p-6 flex flex-col justify-between transition-all duration-200 hover:border-yellow-500 hover:shadow-md"
                                         :class="showAllFree ? '' : '{{ $index >= 3 ? 'hidden' : ($index === 2 ? 'hidden sm:block' : '') }}'">
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

                        {{-- See More Toggle --}}
                        @if ($freeExams->count() > 2)
                            <div class="mt-5 flex flex-col items-center gap-2.5">
                                <div x-show="!showAllFree" class="flex items-center gap-1.5" aria-hidden="true">
                                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-400 inline-block"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-yellow-300 inline-block"></span>
                                    <span class="w-2.5 h-2.5 rounded-full bg-stone-200 inline-block"></span>
                                </div>
                                <button type="button"
                                        @click="showAllFree = !showAllFree"
                                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-sm font-bold uppercase tracking-wider border-2 transition-all duration-200 hover:shadow-md"
                                        :class="showAllFree ? 'border-stone-200 bg-white text-stone-500 hover:border-stone-300' : 'border-yellow-500 bg-yellow-50 text-yellow-700 hover:bg-yellow-100'">
                                    <span x-show="!showAllFree" class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                        {{ $freeExams->count() - 2 }} weitere kostenlose Übungen
                                    </span>
                                    <span x-show="showAllFree" class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                                        </svg>
                                        Weniger anzeigen
                                    </span>
                                </button>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            {{-- Paid Exams --}}
            @if ($paidExams->isNotEmpty())
                <div>
                    <h3 class="text-lg font-bold uppercase tracking-wider text-stone-700 mb-4 flex items-center gap-2">
                        Premium-Prüfungen
                        <span class="inline-flex items-center justify-center min-w-[1.5rem] h-6 px-1.5 text-xs font-extrabold bg-stone-100 text-stone-600 rounded-full border border-stone-200">
                            {{ $paidExams->count() }}
                        </span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($paidExams as $index => $exam)
                            <article class="bg-white border-2 border-stone-300 rounded-xl p-6 flex flex-col justify-between transition-all duration-200 hover:border-stone-900 hover:shadow-lg"
                                     :class="showAllPaid ? '' : '{{ $index >= 6 ? 'hidden' : ($index >= 3 ? 'hidden sm:block' : '') }}'">
                                <div class="space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold uppercase tracking-widest text-stone-400 border border-stone-200 rounded-full px-3 py-1">
                                            {{ $exam->exam_provider }} {{ $exam->level }}
                                        </span>
                                        {{-- Note: €5.00 price badge is removed per user request --}}
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
                                        Abonnieren, um diese Prüfung zu machen
                                    </button>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- See More Toggle for Paid Exams --}}
                    @if ($paidExams->count() > 3)
                        <div class="mt-6 flex flex-col items-center gap-2.5">
                            <div x-show="!showAllPaid" class="flex items-center gap-1.5" aria-hidden="true">
                                <span class="w-2.5 h-2.5 rounded-full bg-stone-500 inline-block"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-stone-400 inline-block"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-stone-200 inline-block"></span>
                            </div>
                            <button type="button"
                                    @click="showAllPaid = !showAllPaid"
                                    class="inline-flex items-center gap-2 px-6 py-2.5 rounded-full text-sm font-bold uppercase tracking-wider border-2 transition-all duration-200 hover:shadow-md"
                                    :class="showAllPaid ? 'border-stone-200 bg-white text-stone-500 hover:border-stone-300' : 'border-stone-900 bg-stone-900 text-white hover:bg-stone-700'">
                                <span x-show="!showAllPaid" class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                    {{ $paidExams->count() - 3 }} weitere Premium-Prüfungen
                                </span>
                                <span x-show="showAllPaid" class="flex items-center gap-1.5">
                                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"/>
                                    </svg>
                                    Weniger anzeigen
                                </span>
                            </button>
                        </div>
                    @endif
                </div>
            @endif

            {{-- Empty State --}}
            @if ($freeExams->isEmpty() && $paidExams->isEmpty())
                <div class="text-center py-12">
                    <p class="text-stone-500 text-sm">Derzeit sind keine Prüfungen für diese Auswahl verfügbar.</p>
                </div>
            @endif

            {{-- Voice Practice Card --}}
            <div class="flex-1">
                <h3 class="text-lg font-bold uppercase tracking-wider text-stone-700 mb-4">Mündliche Sprechübung</h3>
                <a href="{{ route('voice.practice') }}" class="block bg-white border-2 border-stone-900 rounded-xl p-6 transition-all duration-200 hover:shadow-lg hover:-translate-y-1 group">
                    <div class="space-y-4">
                        <div class="h-16 w-16 rounded-xl bg-yellow-50 flex items-center justify-center text-yellow-600 group-hover:scale-110 transition-transform">
                            <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"></path>
                                <path d="M19 10v1a7 7 0 0 1-14 0v-1"></path>
                                <line x1="12" y1="19" x2="12" y2="22"></line>
                            </svg>
                        </div>
                        <h4 class="font-bold text-stone-900 uppercase text-lg leading-tight">KI-Sprechprüfung</h4>
                        <p class="text-sm text-stone-600 leading-relaxed">
                            Simulieren Sie den mündlichen Teil der TELC oder Goethe Prüfung. Sprechen Sie per Mikrofon mit unserem KI-Prüfer und erhalten Sie sofortiges Feedback.
                        </p>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <span class="text-xs text-stone-500 bg-stone-50 rounded-full px-3 py-1 border border-stone-200">TELC</span>
                            <span class="text-xs text-stone-500 bg-stone-50 rounded-full px-3 py-1 border border-stone-200">GOETHE</span>
                            <span class="text-xs text-stone-500 bg-stone-50 rounded-full px-3 py-1 border border-stone-200">A1 — C2</span>
                        </div>
                    </div>
                    <div class="mt-6">
                        <span class="w-full block text-center bg-stone-900 hover:bg-stone-800 text-white font-bold px-4 py-2.5 rounded-full text-xs uppercase tracking-wider transition-colors duration-200">
                            Sprechübung starten
                        </span>
                    </div>
                </a>
            </div>
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-stone-500 text-sm">Keine Prüfungsstufen gefunden.</p>
        </div>
    @endif
</div>
