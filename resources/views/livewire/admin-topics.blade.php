<div class="admin-topics-shell" style="max-width: 900px; margin: 2rem auto; font-family: 'Plus Jakarta Sans', sans-serif; padding: 1rem;">
    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-radius: 12px; padding: 2rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; margin: 0 0 0.25rem; color: #111827;">Sprechprüfungsthemen</h3>
        <p style="color: #666666; font-size: 0.95rem; margin-bottom: 1.5rem;">Themen für die Sprechübung verwalten. Diese werden in der Sprechübungs-Seite angezeigt.</p>

        @if (session()->has('topics_message'))
            <div style="background: #e6f4ea; border: 1px solid #a7f3d0; border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 1rem; color: #137333; font-weight: 600;">
                {{ session('topics_message') }}
            </div>
        @endif

        <div style="background: #fafaf9; border: 1px solid #e5e7eb; border-radius: 8px; padding: 1.25rem; margin-bottom: 1.5rem;">
            <h4 style="font-size: 1rem; font-weight: 700; margin: 0 0 0.75rem; color: #111827;">Neues Thema</h4>
            <div style="display: flex; gap: 0.75rem; flex-wrap: wrap; align-items: flex-start;">
                <div style="flex: 1; min-width: 200px;">
                    <label style="font-size: 0.8rem; color: #6b7280; font-weight: 600; display: block; margin-bottom: 0.25rem;">Titel</label>
                    <input type="text" wire:model="newTopicTitle" placeholder="Thema für die Sprechprüfung..." style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d9dde5; border-radius: 6px; font-size: 0.9rem;">
                </div>
                <div style="min-width: 100px;">
                    <label style="font-size: 0.8rem; color: #6b7280; font-weight: 600; display: block; margin-bottom: 0.25rem;">Niveau</label>
                    <select wire:model="newTopicLevel" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d9dde5; border-radius: 6px; font-size: 0.9rem; background: #fff;">
                        <option value="A1">A1</option>
                        <option value="A2">A2</option>
                        <option value="B1">B1</option>
                        <option value="B2">B2</option>
                        <option value="C1">C1</option>
                    </select>
                </div>
                <div style="min-width: 100px;">
                    <label style="font-size: 0.8rem; color: #6b7280; font-weight: 600; display: block; margin-bottom: 0.25rem;">Anbieter</label>
                    <select wire:model="newTopicProvider" style="width: 100%; padding: 0.6rem 0.75rem; border: 1px solid #d9dde5; border-radius: 6px; font-size: 0.9rem; background: #fff;">
                        <option value="telc">telc</option>
                        <option value="goethe">Goethe</option>
                    </select>
                </div>
                <div style="padding-top: 1.25rem;">
                    <button type="button" wire:click="createTopic" style="background: #d8232a; color: #fff; border: none; padding: 0.6rem 1.25rem; border-radius: 6px; font-weight: 700; font-size: 0.9rem; cursor: pointer;">Hinzufügen</button>
                </div>
            </div>
            @error('newTopicTitle') <span style="color: #d8232a; font-size: 0.8rem; margin-top: 0.25rem; display: block;">{{ $message }}</span> @enderror
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #fafafa; border-bottom: 2px solid #edf0f4;">
                        <th style="padding: 0.75rem;">Thema</th>
                        <th style="padding: 0.75rem; width: 80px;">Niveau</th>
                        <th style="padding: 0.75rem; width: 100px;">Anbieter</th>
                        <th style="padding: 0.75rem; width: 80px;">Aktiv</th>
                        <th style="padding: 0.75rem; width: 160px;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($topics as $topic)
                        <tr style="border-bottom: 1px solid #edf0f4;">
                            @if ($editingTopicId === $topic->id)
                                <td style="padding: 0.5rem 0.75rem;">
                                    <input type="text" wire:model="editTopicTitle" style="width: 100%; padding: 0.4rem 0.5rem; border: 1px solid #d9dde5; border-radius: 4px; font-size: 0.85rem;">
                                    @error('editTopicTitle') <span style="color: #d8232a; font-size: 0.75rem;">{{ $message }}</span> @enderror
                                </td>
                                <td style="padding: 0.5rem 0.75rem;">
                                    <select wire:model="editTopicLevel" style="padding: 0.4rem 0.5rem; border: 1px solid #d9dde5; border-radius: 4px; font-size: 0.85rem; background: #fff;">
                                        <option value="A1">A1</option>
                                        <option value="A2">A2</option>
                                        <option value="B1">B1</option>
                                        <option value="B2">B2</option>
                                        <option value="C1">C1</option>
                                    </select>
                                </td>
                                <td style="padding: 0.5rem 0.75rem;">
                                    <select wire:model="editTopicProvider" style="padding: 0.4rem 0.5rem; border: 1px solid #d9dde5; border-radius: 4px; font-size: 0.85rem; background: #fff;">
                                        <option value="telc">telc</option>
                                        <option value="goethe">Goethe</option>
                                    </select>
                                </td>
                                <td style="padding: 0.5rem 0.75rem;">{{ $topic->is_active ? '✅' : '❌' }}</td>
                                <td style="padding: 0.5rem 0.75rem;">
                                    <button type="button" wire:click="saveEdit" style="background: #137333; color: #fff; border: none; padding: 0.35rem 0.75rem; border-radius: 4px; font-weight: 600; font-size: 0.8rem; cursor: pointer; margin-right: 0.25rem;">Speichern</button>
                                    <button type="button" wire:click="cancelEdit" style="background: #6b7280; color: #fff; border: none; padding: 0.35rem 0.75rem; border-radius: 4px; font-weight: 600; font-size: 0.8rem; cursor: pointer;">Abbrechen</button>
                                </td>
                            @else
                                <td style="padding: 0.75rem; font-weight: 600;">{{ $topic->title }}</td>
                                <td style="padding: 0.75rem;">
                                    <span style="display: inline-block; padding: 0.15rem 0.4rem; background: #f3f4f6; border-radius: 4px; font-size: 0.8rem; font-weight: 700;">{{ $topic->level }}</span>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <span style="display: inline-block; padding: 0.15rem 0.4rem; background: #f3f4f6; border-radius: 4px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase;">{{ $topic->exam_provider ?? 'telc' }}</span>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <button type="button" wire:click="toggleActive({{ $topic->id }})" style="background: none; border: none; cursor: pointer; font-size: 1.1rem;">{{ $topic->is_active ? '✅' : '❌' }}</button>
                                </td>
                                <td style="padding: 0.75rem;">
                                    <button type="button" wire:click="startEdit({{ $topic->id }})" style="background: #004085; color: #fff; border: none; padding: 0.35rem 0.75rem; border-radius: 4px; font-weight: 600; font-size: 0.8rem; cursor: pointer; margin-right: 0.25rem;">Bearbeiten</button>
                                    <button type="button" wire:click="deleteTopic({{ $topic->id }})" onclick="return confirm('Wirklich löschen?')" style="background: #d8232a; color: #fff; border: none; padding: 0.35rem 0.75rem; border-radius: 4px; font-weight: 600; font-size: 0.8rem; cursor: pointer;">Löschen</button>
                                </td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 1.5rem; text-align: center; color: #666666; font-style: italic;">Keine Themen vorhanden. Erstellen Sie oben das erste Thema.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
