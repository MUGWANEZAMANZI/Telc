<?php

namespace App\Livewire;

use App\Models\Book;
use Livewire\Component;

class BookReader extends Component
{
    public Book $book;

    public function mount(Book $book): void
    {
        abort_unless($book->is_published || auth()->user()?->isAdmin(), 404);
        $this->book = $book;
    }

    public function render()
    {
        return view('livewire.book-reader', [
            'book' => $this->book,
            'pdfUrl' => asset('storage/' . $this->book->pdf_path),
        ]);
    }
}
