<?php

use Livewire\Component;
use App\Models\ContactRequest;
use Livewire\Attributes\Validate;

new class extends Component
{
    #[Validate('required|string|max:255')]
    public string $name = '';

    #[Validate('nullable|string|max:255')]
    public string $organization = '';

    #[Validate('required|email|max:255')]
    public string $email = '';

    public string $successMessage = '';

    public function submit(): void
    {
        $this->validate();

        ContactRequest::create([
            'name' => $this->name,
            'organization' => $this->organization,
            'email' => $this->email,
            'password' => '',
            'status' => 'not_responded',
        ]);

        $this->reset(['name', 'organization', 'email']);
        $this->successMessage = 'Vielen Dank! Ihre Kontaktanfrage wurde erfolgreich übermittelt.';
    }
};
?>

<section class="py-16 bg-stone-900 text-white relative overflow-hidden" id="contact-us" style="font-family: 'Plus Jakarta Sans', sans-serif;">
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#80808012_1px,transparent_1px),linear-gradient(to_bottom,#80808012_1px,transparent_1px)] bg-[size:24px_24px] opacity-20"></div>

    <div class="max-w-xl mx-auto px-6 relative z-10">
        <div style="text-align: center; margin-bottom: 2.5rem;">
            <span style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; tracking-widest: 0.1em; color: #eab308; display: block; margin-bottom: 0.5rem;">Kontakt</span>
            <h2 style="font-family: 'Playfair Display', serif; font-size: 2.2rem; font-weight: 700; margin: 0 0 0.5rem; text-transform: uppercase;">Kontaktieren Sie uns</h2>
            <p style="color: #a1a1aa; font-size: 0.95rem; max-w: 360px; margin: 0 auto; line-height: 1.5;">
                Haben Sie Fragen? Senden Sie uns eine Nachricht und wir melden uns in Kürze.
            </p>
        </div>

        @if ($successMessage)
            <div style="background-color: rgba(6, 78, 59, 0.8); border: 1px solid #10b981; color: #a7f3d0; padding: 1rem; border-radius: 8px; font-size: 0.88rem; text-align: center; margin-bottom: 1.5rem;">
                {{ $successMessage }}
            </div>
        @endif

        <form wire:submit="submit" style="background-color: rgba(12, 10, 9, 0.6); border: 1px solid #292524; border-radius: 16px; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5); display: flex; flex-direction: column; gap: 1.25rem;">
            <div>
                <label for="contact-name" style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #a8a29e; margin-bottom: 0.35rem;">Name</label>
                <input type="text" 
                       id="contact-name" 
                       wire:model="name" 
                       style="width: 100%; box-sizing: border-box; background-color: #1c1917; border: 1px solid #44403c; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.88rem; color: #ffffff; outline: none; transition: border-color 0.2s;" 
                       placeholder="Ihr Name" />
                @error('name') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="contact-organization" style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #a8a29e; margin-bottom: 0.35rem;">Organisation</label>
                <input type="text" 
                       id="contact-organization" 
                       wire:model="organization" 
                       style="width: 100%; box-sizing: border-box; background-color: #1c1917; border: 1px solid #44403c; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.88rem; color: #ffffff; outline: none; transition: border-color 0.2s;" 
                       placeholder="Ihre Organisation (optional)" />
                @error('organization') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <div>
                <label for="contact-email" style="display: block; font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: #a8a29e; margin-bottom: 0.35rem;">E-Mail-Adresse</label>
                <input type="email" 
                       id="contact-email" 
                       wire:model="email" 
                       style="width: 100%; box-sizing: border-box; background-color: #1c1917; border: 1px solid #44403c; border-radius: 8px; padding: 0.75rem 1rem; font-size: 0.88rem; color: #ffffff; outline: none; transition: border-color 0.2s;" 
                       placeholder="ihre.email@beispiel.de" />
                @error('email') <span style="color: #ef4444; font-size: 0.75rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
            </div>

            <button type="submit" 
                    style="width: 100%; background-color: #eab308; border: none; border-radius: 8px; padding: 0.9rem; font-weight: 700; text-transform: uppercase; color: #0c0a09; cursor: pointer; transition: background-color 0.2s, transform 0.1s; font-size: 0.88rem;">
                Nachricht absenden
            </button>
        </form>
    </div>
</section>