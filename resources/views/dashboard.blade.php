<x-layouts::app :title="__('TELC Exam Builder')">
    @if (auth()->user()->isAdmin())
        <livewire:exam-dashboard />
    @else
        <livewire:student-exam />
    @endif
</x-layouts::app>
