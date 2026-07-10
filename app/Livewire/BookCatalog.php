<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\View\View;
use Livewire\Component;

class BookCatalog extends Component
{
    public string $selectedLevel = '';

    public string $sortBy = 'sort_order';

    public function selectLevel(string $level): void
    {
        $this->selectedLevel = $level;
    }

    public function selectSort(string $sort): void
    {
        $this->sortBy = $sort;
    }

    public function render(): View
    {
        $query = Book::query()
            ->where('is_published', true);

        if ($this->selectedLevel !== '') {
            $query->where('level', $this->selectedLevel);
        }

        if ($this->sortBy === 'title_asc') {
            $query->orderBy('title', 'asc');
        } elseif ($this->sortBy === 'title_desc') {
            $query->orderBy('title', 'desc');
        } elseif ($this->sortBy === 'level_asc') {
            $query->orderBy('level', 'asc');
        } else {
            $query->orderBy('sort_order')->orderBy('title');
        }

        $books = $query->get();

        return view('livewire.book-catalog', [
            'books' => $books,
            'levels' => ['A1', 'A2', 'B1', 'B2', 'C1', 'C2'],
        ]);
    }
}
