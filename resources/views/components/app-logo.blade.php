@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="GERMAN SUCCESS" {{ $attributes }}>
        <x-slot name="logo">
            <img src="/logo.jpeg" class="size-8 object-cover rounded-md" alt="GERMAN SUCCESS Logo" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="GERMAN SUCCESS" {{ $attributes }}>
        <x-slot name="logo">
            <img src="/logo.jpeg" class="size-8 object-cover rounded-md" alt="GERMAN SUCCESS Logo" />
        </x-slot>
    </flux:brand>
@endif
