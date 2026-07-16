<?php

namespace App\Livewire;

use App\Models\BlogPost;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property LengthAwarePaginator<int, BlogPost> $posts
 * @property list<string> $categories
 */
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

    /** @return LengthAwarePaginator<int, BlogPost> */
    public function getPostsProperty(): LengthAwarePaginator
    {
        return BlogPost::query()
            ->published()
            ->forCategory($this->category !== '' ? $this->category : null)
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%")
                ->orWhere('content', 'like', "%{$this->search}%"))
            ->orderByDesc('published_at')
            ->paginate(9);
    }

    /** @return list<string> */
    public function getCategoriesProperty(): array
    {
        /** @var list<string> */
        return BlogPost::query()
            ->published()
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->sort()
            ->values()
            ->all();
    }

    public function render(): View
    {
        return view('livewire.blog', [
            'posts' => $this->posts,
            'categories' => $this->categories,
        ]);
    }
}
