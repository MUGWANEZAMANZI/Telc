<div class="space-y-8">
    {{-- Filter and Sort Controls Bar --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-stone-200 shadow-sm">
        {{-- Level Filters --}}
        <div class="flex flex-wrap gap-2 items-center">
            <span class="text-xs font-extrabold uppercase text-stone-500 mr-2">Klasse / Niveau:</span>
            <button type="button"
                    wire:click="selectLevel('')"
                    class="px-4 py-1.5 rounded-full text-xs font-bold transition-all duration-200"
                    @class([
                        'bg-stone-900 text-white shadow-sm' => $selectedLevel === '',
                        'bg-stone-100 text-stone-600 hover:bg-stone-200' => $selectedLevel !== '',
                    ])>
                Alle
            </button>
            @foreach ($levels as $lvl)
                <button type="button"
                        wire:click="selectLevel('{{ $lvl }}')"
                        class="px-4 py-1.5 rounded-full text-xs font-bold transition-all duration-200"
                        @class([
                            'bg-yellow-500 text-stone-950 shadow-sm' => $selectedLevel === $lvl,
                            'bg-stone-100 text-stone-600 hover:bg-stone-200' => $selectedLevel !== $lvl,
                        ])>
                    {{ $lvl }}
                </button>
            @endforeach
        </div>

        {{-- Sorting Controls --}}
        <div class="flex items-center gap-2">
            <span class="text-xs font-extrabold uppercase text-stone-500">Sortieren nach:</span>
            <select wire:change="selectSort($event.target.value)" class="text-xs font-bold bg-stone-50 border border-stone-200 rounded-lg px-3 py-2 text-stone-700 focus:outline-none focus:border-yellow-500">
                <option value="sort_order" @selected($sortBy === 'sort_order')>Standard-Reihenfolge</option>
                <option value="title_asc" @selected($sortBy === 'title_asc')>Titel (A-Z)</option>
                <option value="title_desc" @selected($sortBy === 'title_desc')>Titel (Z-A)</option>
                <option value="level_asc" @selected($sortBy === 'level_asc')>Niveau (A-Z)</option>
            </select>
        </div>
    </div>

    {{-- Books Grid --}}
    @if ($books->isEmpty())
        <div class="text-center py-20 bg-white rounded-xl border border-stone-200">
            <svg class="w-16 h-16 mx-auto text-stone-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
            </svg>
            <p class="text-stone-500 text-lg">Keine Bücher für dieses Niveau verfügbar. Schau bald wieder vorbei!</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach ($books as $book)
                <a href="{{ route('books.read', $book) }}" class="group block bg-white rounded-xl border border-stone-200 overflow-hidden hover:border-yellow-500 hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                    <div class="aspect-[4/3] bg-stone-100 flex items-center justify-center overflow-hidden">
                        @if ($book->cover_image)
                            <img src="{{ route('books.cover', $book) }}" alt="{{ $book->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                        @else
                            <div class="w-full h-full bg-gradient-to-br from-stone-100 to-stone-200 flex items-center justify-center relative">
                                <svg class="w-16 h-16 text-stone-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                                </svg>
                            </div>
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
</div>
