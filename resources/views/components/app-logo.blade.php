@props([
    'sidebar' => false,
])

@if($sidebar)
    <flux:sidebar.brand name="ERFOLG" {{ $attributes }}>
        <x-slot name="logo">
            <img src="/logo.jpeg" class="size-8 object-cover rounded-md" alt="ERFOLG Logo" />
        </x-slot>
    </flux:sidebar.brand>
@else
    <flux:brand name="ERFOLG" {{ $attributes }}>
        <x-slot name="logo">
            <img src="/logo.jpeg" class="size-8 object-cover rounded-md" alt="ERFOLG Logo" />
        </x-slot>
    </flux:brand>
@endif
