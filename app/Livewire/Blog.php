<?php

namespace App\Livewire;

use App\Models\BlogPost;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class Blog extends Component
{
    use WithPagination;

    public string $category = '';

    public string $search = '';

    public function updatedCategory(): void
    {
        $this->resetPage();
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function getPostsProperty()
    {
        return BlogPost::query()
            ->published()
            ->forCategory($this->category !== '' ? $this->category : null)
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%")
                ->orWhere('content', 'like', "%{$this->search}%"))
            ->orderByDesc('published_at')
            ->paginate(9);
    }

    public function getCategoriesProperty(): array
    {
        return BlogPost::query()
            ->published()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values()
            ->toArray();
    }

    public function render(): View
    {
        return view('livewire.blog', [
            'posts' => $this->posts,
            'categories' => $this->categories,
        ]);
    }
}
