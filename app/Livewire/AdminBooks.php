<?php

namespace App\Livewire;

use App\Models\Book;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class AdminBooks extends Component
{
    use WithFileUploads;

    public string $title = '';

    public string $description = '';

    public string $author = '';

    public string $level = '';

    public $pdf = null;

    public $cover = null;

    public bool $isPublished = false;

    public int $sortOrder = 0;

    public ?int $editingBookId = null;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isAdmin(), 403);
    }

    public function render()
    {
        return view('livewire.admin-books', [
            'books' => Book::query()->orderBy('sort_order')->orderBy('title')->get(),
        ]);
    }

    public function create(): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'pdf' => ['required', 'file', 'mimes:pdf', 'max:51200'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ]);

        $pdfPath = $this->pdf->store('books', 'public');
        $coverPath = $this->cover?->store('books/covers', 'public');

        Book::create([
            'title' => $this->title,
            'description' => $this->description,
            'author' => $this->author,
            'level' => $this->level,
            'pdf_path' => $pdfPath,
            'cover_image' => $coverPath,
            'is_published' => $this->isPublished,
            'sort_order' => $this->sortOrder,
        ]);

        $this->resetForm();
        $this->dispatch('books-saved');
    }

    public function startEdit(int $id): void
    {
        $book = Book::findOrFail($id);
        $this->editingBookId = $book->id;
        $this->title = $book->title;
        $this->description = $book->description ?? '';
        $this->author = $book->author ?? '';
        $this->level = $book->level ?? '';
        $this->isPublished = $book->is_published;
        $this->sortOrder = $book->sort_order;
        $this->pdf = null;
        $this->cover = null;
    }

    public function update(): void
    {
        $this->validate([
            'title' => ['required', 'string', 'max:255'],
            'pdf' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'cover' => ['nullable', 'image', 'max:2048'],
        ]);

        $book = Book::findOrFail($this->editingBookId);
        $data = [
            'title' => $this->title,
            'description' => $this->description,
            'author' => $this->author,
            'level' => $this->level,
            'is_published' => $this->isPublished,
            'sort_order' => $this->sortOrder,
        ];

        if ($this->pdf) {
            Storage::disk('public')->delete($book->pdf_path);
            $data['pdf_path'] = $this->pdf->store('books', 'public');
        }

        if ($this->cover) {
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $data['cover_image'] = $this->cover->store('books/covers', 'public');
        }

        $book->update($data);
        $this->cancelEdit();
        $this->dispatch('books-saved');
    }

    public function cancelEdit(): void
    {
        $this->editingBookId = null;
        $this->resetForm();
    }

    public function togglePublished(int $id): void
    {
        $book = Book::findOrFail($id);
        $book->update(['is_published' => ! $book->is_published]);
    }

    public function deleteBook(int $id): void
    {
        $book = Book::findOrFail($id);
        Storage::disk('public')->delete($book->pdf_path);
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }
        $book->delete();
        $this->dispatch('books-saved');
    }

    private function resetForm(): void
    {
        $this->title = '';
        $this->description = '';
        $this->author = '';
        $this->level = '';
        $this->pdf = null;
        $this->cover = null;
        $this->isPublished = false;
        $this->sortOrder = 0;
    }
}
