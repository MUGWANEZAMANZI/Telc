<?php

namespace App\Livewire;

use Livewire\Component;

class VoiceInput extends Component
{
    public string $transcribedText = '';

    public function render()
    {
        return view('livewire.voice-input');
    }
}

