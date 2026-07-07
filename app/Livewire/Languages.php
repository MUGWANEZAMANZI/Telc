<?php

namespace App\Livewire;

use Illuminate\Support\Facades\App;
use Illuminate\View\View;
use Livewire\Component;

class Languages extends Component
{
    /** @var list<array{locale: string, name: string, flag: string}> */
    public array $languages = [
        ['locale' => 'en', 'name' => 'English', 'flag' => 'gb'],
        ['locale' => 'fr', 'name' => 'Français', 'flag' => 'fr'],
        ['locale' => 'es', 'name' => 'Español', 'flag' => 'es'],
    ];

    public string $currentLocale = 'en';

    public function mount(): void
    {
        $this->currentLocale = App::getLocale();
    }

    public function selectLang(string $locale): void
    {
        if (! in_array($locale, ['en', 'fr', 'es'], true)) {
            return;
        }

        App::setLocale($locale);

        if ($user = auth()->user()) {
            $user->update(['locale' => $locale]);
        }

        session()->put('locale', $locale);
        $this->currentLocale = $locale;

        $this->dispatch('locale-changed', locale: $locale);
    }

    public function render(): View
    {
        return view('livewire.languages');
    }
}
