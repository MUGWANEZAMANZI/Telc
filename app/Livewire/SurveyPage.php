<?php

namespace App\Livewire;

use App\Models\Setting;
use Illuminate\View\View;
use Livewire\Component;

class SurveyPage extends Component
{
    public string $googleFormUrl = '';

    public function mount(): void
    {
        $this->googleFormUrl = Setting::value('google_form_url', '');
    }

    public function render(): View
    {
        return view('livewire.survey-page');
    }
}
