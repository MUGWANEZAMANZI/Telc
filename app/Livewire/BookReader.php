<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\View\View;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.reader')]
class BookReader extends Component
{
    public Book $book;

    public function mount(Book $book): void
    {
        abort_unless($book->is_published || auth()->user()?->isAdmin(), 404);
        $this->book = $book;
    }

    public function render(): View
    {
        return view('livewire.book-reader', [
            'book' => $this->book,
            'pdfUrl' => route('books.pdf', $this->book),
        ]);
    }
}
