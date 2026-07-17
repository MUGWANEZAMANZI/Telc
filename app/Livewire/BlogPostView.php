<?php

namespace App\Livewire;

use App\Models\BlogPost;
use Illuminate\View\View;
use Livewire\Component;

class BlogPostView extends Component
{
    public ?BlogPost $post = null;

    public function mount(string $slug): void
    {
        $this->post = BlogPost::query()
            ->where('slug', $slug)
            ->published()
            ->firstOrFail();
    }

    public function render(): View
    {
        return view('livewire.blog-post');
    }
}
