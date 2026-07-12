<?php

namespace App\Livewire;

use App\Models\VoiceTopic;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class AdminTopics extends Component
{
    public string $newTopicTitle = '';

    public string $newTopicLevel = 'C1';

    public string $newTopicProvider = 'telc';

    public ?int $editingTopicId = null;

    public string $editTopicTitle = '';

    public string $editTopicLevel = 'C1';

    public string $editTopicProvider = 'telc';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);
    }

    public function render(): View
    {
        return view('livewire.admin-topics', [
            'topics' => VoiceTopic::query()->orderBy('level')->orderBy('title')->get(),
        ]);
    }

    public function createTopic(): void
    {
        $this->validate([
            'newTopicTitle' => ['required', 'string', 'max:500'],
            'newTopicLevel' => ['required', 'string', 'max:8'],
            'newTopicProvider' => ['required', 'string', 'in:telc,goethe'],
        ]);

        VoiceTopic::create([
            'title' => $this->newTopicTitle,
            'level' => $this->newTopicLevel,
            'exam_provider' => $this->newTopicProvider,
        ]);

        $this->newTopicTitle = '';
        $this->newTopicLevel = 'C1';
        $this->newTopicProvider = 'telc';
        $this->dispatch('topics-saved');
    }

    public function startEdit(int $id): void
    {
        $topic = VoiceTopic::findOrFail($id);
        $this->editingTopicId = $topic->id;
        $this->editTopicTitle = $topic->title;
        $this->editTopicLevel = $topic->level;
        $this->editTopicProvider = $topic->exam_provider ?: 'telc';
    }

    public function saveEdit(): void
    {
        $this->validate([
            'editTopicTitle' => ['required', 'string', 'max:500'],
            'editTopicLevel' => ['required', 'string', 'max:8'],
            'editTopicProvider' => ['required', 'string', 'in:telc,goethe'],
        ]);

        VoiceTopic::findOrFail($this->editingTopicId)->update([
            'title' => $this->editTopicTitle,
            'level' => $this->editTopicLevel,
            'exam_provider' => $this->editTopicProvider,
        ]);

        $this->cancelEdit();
        $this->dispatch('topics-saved');
    }

    public function cancelEdit(): void
    {
        $this->editingTopicId = null;
        $this->editTopicTitle = '';
        $this->editTopicLevel = 'C1';
        $this->editTopicProvider = 'telc';
    }

    public function toggleActive(int $id): void
    {
        $topic = VoiceTopic::findOrFail($id);
        $topic->update(['is_active' => ! $topic->is_active]);
    }

    public function deleteTopic(int $id): void
    {
        VoiceTopic::findOrFail($id)->delete();
        $this->dispatch('topics-saved');
    }
}
