<?php

namespace App\Livewire;

use App\Models\BlogPost;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

/**
 * @property LengthAwarePaginator<int, BlogPost> $posts
 */
class AdminBlog extends Component
{
    use WithPagination;

    public string $mode = 'list';

    public ?int $editingId = null;

    public string $title = '';

    public string $slug = '';

    public string $content = '';

    public string $excerpt = '';

    public string $category = '';

    public string $metaTitle = '';

    public string $metaDescription = '';

    public string $featuredImage = '';

    public bool $isPublished = false;

    public ?string $publishedAt = null;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);
    }

    /** @return LengthAwarePaginator<int, BlogPost> */
    public function getPostsProperty(): LengthAwarePaginator
    {
        return BlogPost::query()
            ->with('author')
            ->orderByDesc('created_at')
            ->paginate(15);
    }

    public function createNew(): void
    {
        $this->mode = 'edit';
        $this->editingId = null;
        $this->resetForm();
    }

    public function edit(int $id): void
    {
        $post = BlogPost::findOrFail($id);
        $this->mode = 'edit';
        $this->editingId = $post->id;
        $this->title = $post->title;
        $this->slug = $post->slug;
        $this->content = $post->content ?? '';
        $this->excerpt = $post->excerpt ?? '';
        $this->category = $post->category ?? '';
        $this->metaTitle = $post->meta_title ?? '';
        $this->metaDescription = $post->meta_description ?? '';
        $this->featuredImage = $post->featured_image ?? '';
        $this->isPublished = $post->is_published;
        $this->publishedAt = $post->published_at?->format('Y-m-d\TH:i');
    }

    public function updatedTitle(): void
    {
        if ($this->editingId === null) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function save(): void
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:blog_posts,slug,'.($this->editingId ?? '0'),
            'content' => 'nullable|string',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'content' => $this->content,
            'excerpt' => $this->excerpt,
            'category' => $this->category ?: null,
            'meta_title' => $this->metaTitle ?: null,
            'meta_description' => $this->metaDescription ?: null,
            'featured_image' => $this->featuredImage ?: null,
            'is_published' => $this->isPublished,
            'published_at' => $this->isPublished ? ($this->publishedAt ? now()->parse($this->publishedAt) : now()) : null,
            'author_id' => Auth::id(),
        ];

        if ($this->editingId) {
            BlogPost::findOrFail($this->editingId)->update($data);
        } else {
            BlogPost::create($data);
        }

        $this->mode = 'list';
        $this->resetForm();
        session()->flash('blog_message', 'Artikel gespeichert.');
    }

    public function delete(int $id): void
    {
        BlogPost::findOrFail($id)->delete();
        session()->flash('blog_message', 'Artikel gelöscht.');
    }

    public function togglePublish(int $id): void
    {
        $post = BlogPost::findOrFail($id);
        $post->update([
            'is_published' => ! $post->is_published,
            'published_at' => ! $post->is_published ? ($post->published_at ?? now()) : $post->published_at,
        ]);
    }

    public function backToList(): void
    {
        $this->mode = 'list';
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;
        $this->title = '';
        $this->slug = '';
        $this->content = '';
        $this->excerpt = '';
        $this->category = '';
        $this->metaTitle = '';
        $this->metaDescription = '';
        $this->featuredImage = '';
        $this->isPublished = false;
        $this->publishedAt = null;
    }

    public function render(): View
    {
        return view('livewire.admin-blog', [
            'posts' => $this->posts,
        ]);
    }
}
