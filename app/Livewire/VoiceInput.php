<?php

namespace App\Livewire;

use App\Models\VoiceTopic;
use Livewire\Component;

class VoiceInput extends Component
{
    public string $transcribedText = '';

    public function render(): \Illuminate\View\View
    {
        return view('livewire.voice-input', [
            'topics' => VoiceTopic::query()
                ->where('is_active', true)
                ->orderBy('level')
                ->orderBy('title')
                ->get(),
        ]);
    }
}
