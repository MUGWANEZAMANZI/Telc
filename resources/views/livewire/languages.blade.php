<div class="relative w-40 max-w-xs" x-data="{ open: false }">
    <button @click="open = !open" type="button" class="flex w-full items-center justify-between rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-left shadow-sm focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500">
        <span class="flex items-center gap-2">
            <span class="block truncate text-sm font-medium text-gray-700">{{ strtoupper($currentLocale) }}</span>
        </span>
        <svg class="h-5 w-5 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
        </svg>
    </button>

    <div x-show="open"
         @click.outside="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         class="absolute z-50 mt-1 w-full overflow-auto rounded-lg bg-white py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm" style="display: none;">

        @foreach($languages as $lang)
            <button type="button"
                    wire:click="selectLang('{{ $lang['locale'] }}')"
                    @click="open = false"
                    class="flex w-full items-center gap-3 px-4 py-2.5 text-left text-gray-700 hover:bg-gray-50 transition colors {{ $currentLocale === $lang['locale'] ? 'bg-indigo-50/50 text-indigo-600 font-semibold' : '' }}">
                <span class="block truncate text-sm">{{ $lang['name'] }}</span>
            </button>
        @endforeach
    </div>
</div>
