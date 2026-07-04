<div class="admin-books-shell" style="max-width: 1000px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; padding: 1rem;">
    <div style="margin-bottom: 2rem;">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 2rem; font-weight: 700; margin: 0 0 0.25rem; color: #111827;">Bücher verwalten</h2>
        <p style="color: #666666; font-size: 0.95rem; margin: 0;">PDF-Bücher für die Leseplattform hochladen und verwalten.</p>
    </div>

    @if (session()->has('books_message'))
        <div style="background: #e6f4ea; border: 1px solid #a7f3d0; border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 1rem; color: #137333; font-weight: 600;">
            {{ session('books_message') }}
        </div>
    @endif

    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 1.5rem; margin-bottom: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02); border-left: 5px solid #d8232a;">
        <h4 style="font-size: 1.1rem; font-weight: 700; margin: 0 0 1rem; color: #111827;">{{ $editingBookId ? 'Buch bearbeiten' : 'Neues Buch' }}</h4>
        <form wire:submit="{{ $editingBookId ? 'update' : 'create' }}">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div style="grid-column: 1 / -1;">
                    <label style="font-size: 0.8rem; color: #6b7280; font-weight: 600; display: block; margin-bottom: 0.25rem;">Titel *</label>
                    <input type="text" wire:model="title" placeholder="Buchtitel..." style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d9dde5; border-radius: 6px; font-size: 0.9rem;">
                    @error('title') <span style="color: #d8232a; font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div style="grid-column: 1 / -1;">
                    <label style="font-size: 0.8rem; color: #6b7280; font-weight: 600; display: block; margin-bottom: 0.25rem;">Beschreibung</label>
                    <textarea wire:model="description" placeholder="Kurze Beschreibung..." rows="2" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d9dde5; border-radius: 6px; font-size: 0.9rem; resize: vertical;"></textarea>
                </div>

                <div>
                    <label style="font-size: 0.8rem; color: #6b7280; font-weight: 600; display: block; margin-bottom: 0.25rem;">Autor</label>
                    <input type="text" wire:model="author" placeholder="Autor(in)..." style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d9dde5; border-radius: 6px; font-size: 0.9rem;">
                </div>

                <div>
                    <label style="font-size: 0.8rem; color: #6b7280; font-weight: 600; display: block; margin-bottom: 0.25rem;">Niveau</label>
                    <select wire:model="level" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d9dde5; border-radius: 6px; font-size: 0.9rem; background: #fff;">
                        <option value="">Alle Stufen</option>
                        <option value="A1">A1</option>
                        <option value="A2">A2</option>
                        <option value="B1">B1</option>
                        <option value="B2">B2</option>
                        <option value="C1">C1</option>
                        <option value="C2">C2</option>
                    </select>
                </div>

                <div>
                    <label style="font-size: 0.8rem; color: #6b7280; font-weight: 600; display: block; margin-bottom: 0.25rem;">PDF-Datei {{ $editingBookId ? '(optional)' : '*' }}</label>
                    <input type="file" wire:model="pdf" accept=".pdf" style="width: 100%; padding: 0.5rem 0; font-size: 0.9rem;">
                    @error('pdf') <span style="color: #d8232a; font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label style="font-size: 0.8rem; color: #6b7280; font-weight: 600; display: block; margin-bottom: 0.25rem;">Cover-Bild (optional)</label>
                    <input type="file" wire:model="cover" accept="image/*" style="width: 100%; padding: 0.5rem 0; font-size: 0.9rem;">
                    @error('cover') <span style="color: #d8232a; font-size: 0.8rem;">{{ $message }}</span> @enderror
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <div>
                        <label style="font-size: 0.8rem; color: #6b7280; font-weight: 600; display: block; margin-bottom: 0.25rem;">Sortierung</label>
                        <input type="number" wire:model="sortOrder" min="0" style="width: 80px; padding: 0.6rem 0.75rem; border: 1px solid #d9dde5; border-radius: 6px; font-size: 0.9rem;">
                    </div>
                    <div style="display: flex; align-items: flex-end; padding-bottom: 0.3rem;">
                        <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.9rem; font-weight: 600; color: #374151; cursor: pointer;">
                            <input type="checkbox" wire:model="isPublished" style="width: 1rem; height: 1rem;">
                            Veröffentlicht
                        </label>
                    </div>
                </div>
            </div>

            <div style="margin-top: 1rem; display: flex; gap: 0.5rem;">
                <button type="submit" style="background: #d8232a; color: #fff; border: none; padding: 0.6rem 1.5rem; border-radius: 6px; font-weight: 700; font-size: 0.9rem; cursor: pointer;">
                    {{ $editingBookId ? 'Speichern' : 'Buch erstellen' }}
                </button>
                @if ($editingBookId)
                    <button type="button" wire:click="cancelEdit" style="background: #6b7280; color: #fff; border: none; padding: 0.6rem 1.5rem; border-radius: 6px; font-weight: 600; font-size: 0.9rem; cursor: pointer;">Abbrechen</button>
                @endif
            </div>
        </form>
    </div>

    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #fafafa; border-bottom: 2px solid #edf0f4;">
                        <th style="padding: 0.75rem 1rem;">Titel</th>
                        <th style="padding: 0.75rem; width: 80px;">Niveau</th>
                        <th style="padding: 0.75rem; width: 80px;">Autor</th>
                        <th style="padding: 0.75rem; width: 100px;">Status</th>
                        <th style="padding: 0.75rem; width: 180px;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($books as $book)
                        <tr style="border-bottom: 1px solid #edf0f4;">
                            <td style="padding: 0.75rem 1rem; font-weight: 600;">{{ $book->title }}</td>
                            <td style="padding: 0.75rem;">
                                <span style="display: inline-block; padding: 0.15rem 0.4rem; background: #f3f4f6; border-radius: 4px; font-size: 0.8rem; font-weight: 700;">{{ $book->level ?: '—' }}</span>
                            </td>
                            <td style="padding: 0.75rem; color: #666;">{{ $book->author ?: '—' }}</td>
                            <td style="padding: 0.75rem;">
                                <button type="button" wire:click="togglePublished({{ $book->id }})" style="background: none; border: none; cursor: pointer; font-size: 1.1rem;">{{ $book->is_published ? '✅' : '❌' }}</button>
                            </td>
                            <td style="padding: 0.75rem;">
                                <button type="button" wire:click="startEdit({{ $book->id }})" style="background: #004085; color: #fff; border: none; padding: 0.35rem 0.75rem; border-radius: 4px; font-weight: 600; font-size: 0.8rem; cursor: pointer; margin-right: 0.25rem;">Bearbeiten</button>
                                <button type="button" wire:click="deleteBook({{ $book->id }})" onclick="return confirm('Wirklich löschen?')" style="background: #d8232a; color: #fff; border: none; padding: 0.35rem 0.75rem; border-radius: 4px; font-weight: 600; font-size: 0.8rem; cursor: pointer;">Löschen</button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 1.5rem; text-align: center; color: #666666; font-style: italic;">Keine Bücher vorhanden. Erstellen Sie oben das erste Buch.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
