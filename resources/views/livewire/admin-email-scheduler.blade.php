<div class="space-y-6">
    @if (session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg text-sm">
            {{ session('error') }}
        </div>
    @endif

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold uppercase tracking-tight">E-Mail-Planung</h1>
            <p class="text-sm text-stone-500 mt-1">E-Mails an Benutzer verfassen und planen (max. 90/Tag).</p>
        </div>
        <button wire:click="toggleCompose"
                class="bg-yellow-500 hover:bg-yellow-400 text-stone-950 font-bold px-5 py-2.5 rounded-full text-xs uppercase tracking-wider transition-colors duration-200">
            {{ $showCompose ? 'Schließen' : 'Neue E-Mail' }}
        </button>
    </div>

    {{-- Compose Form --}}
    @if ($showCompose)
        <div class="bg-white border border-stone-200 rounded-xl p-6 space-y-5">
            <h2 class="text-lg font-bold uppercase tracking-wider text-stone-700">
                {{ $editingId ? 'E-Mail bearbeiten' : 'Neue E-Mail verfassen' }}
            </h2>

            <div>
                <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Betreff</label>
                <input type="text" wire:model="subject"
                       class="w-full border border-stone-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500"
                       placeholder="E-Mail Betreff eingeben...">
                @error('subject') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Nachricht (HTML)</label>
                <textarea wire:model="body" rows="10"
                          class="w-full border border-stone-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 font-mono"
                          placeholder="<p>Ihre Nachricht hier...</p>"></textarea>
                @error('body') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Empfänger</label>
                    <select wire:model="recipientFilter"
                            class="w-full border border-stone-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                        <option value="all_users">Alle Benutzer</option>
                        <option value="subscribed">Nur Abonnenten</option>
                        <option value="non_subscribed">Nur Nicht-Abonnenten</option>
                        <option value="specific_role">Nach Rolle</option>
                    </select>
                </div>

                @if ($recipientFilter === 'specific_role')
                    <div>
                        <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Rolle</label>
                        <select wire:model="specificRole"
                                class="w-full border border-stone-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                            <option value="student">Student</option>
                            <option value="admin">Admin</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                @endif

                <div>
                    <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">
                        Empfänger: <span class="text-stone-900">{{ number_format($recipientCount) }}</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Geplanter Datum (optional)</label>
                    <input type="date" wire:model="scheduledDate"
                           class="w-full border border-stone-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-500 uppercase tracking-wider mb-1">Uhrzeit</label>
                    <input type="time" wire:model="scheduledTime" value="09:00"
                           class="w-full border border-stone-200 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500">
                </div>
            </div>

            <div class="flex gap-3 pt-2">
                <button wire:click="saveDraft"
                        class="bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold px-5 py-2.5 rounded-full text-xs uppercase tracking-wider transition-colors duration-200">
                    Entwurf speichern
                </button>
                <button wire:click="scheduleEmail"
                        class="bg-yellow-500 hover:bg-yellow-400 text-stone-950 font-bold px-5 py-2.5 rounded-full text-xs uppercase tracking-wider transition-colors duration-200">
                    Planen &amp; Senden
                </button>
            </div>
        </div>
    @endif

    {{-- Email List --}}
    <div class="bg-white border border-stone-200 rounded-xl overflow-hidden">
        <div class="px-6 py-4 border-b border-stone-200">
            <h2 class="text-sm font-bold uppercase tracking-wider text-stone-700">Alle E-Mails</h2>
        </div>

        @if ($emails->isEmpty())
            <div class="px-6 py-12 text-center text-stone-400 text-sm">
                Noch keine E-Mails geplant.
            </div>
        @else
            <div class="divide-y divide-stone-100">
                @foreach ($emails as $email)
                    <div class="px-6 py-4 flex items-center justify-between gap-4 hover:bg-stone-50 transition-colors">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span class="font-bold text-sm text-stone-900 truncate">{{ $email->subject }}</span>
                                <span class="shrink-0 inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider
                                    @class([
                                        'bg-stone-100 text-stone-500' => $email->status === 'draft',
                                        'bg-yellow-50 text-yellow-700 border border-yellow-200' => $email->status === 'scheduled',
                                        'bg-blue-50 text-blue-700 border border-blue-200' => $email->status === 'sending',
                                        'bg-green-50 text-green-700 border border-green-200' => $email->status === 'sent',
                                        'bg-red-50 text-red-700 border border-red-200' => $email->status === 'failed',
                                    ])">
                                    {{ $email->status }}
                                </span>
                            </div>
                            <div class="flex items-center gap-3 mt-1 text-xs text-stone-400">
                                <span>{{ $email->creator?->name }}</span>
                                <span>&middot;</span>
                                <span>{{ $email->created_at->format('d.m.Y H:i') }}</span>
                                @if ($email->scheduled_at && $email->status === 'scheduled')
                                    <span>&middot;</span>
                                    <span>Geplant: {{ $email->scheduled_at->format('d.m.Y H:i') }}</span>
                                @endif
                                @if ($email->total_count > 0)
                                    <span>&middot;</span>
                                    <span>{{ $email->sent_count }}/{{ $email->total_count }} gesendet</span>
                                @endif
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            @if ($email->status === 'draft')
                                <button wire:click="editEmail({{ $email->id }})"
                                        class="text-stone-400 hover:text-stone-600 transition-colors text-xs font-bold uppercase">
                                    Bearbeiten
                                </button>
                                <button wire:click="sendNow({{ $email->id }})"
                                        class="bg-yellow-500 hover:bg-yellow-400 text-stone-950 px-3 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-wider transition-colors">
                                    Senden
                                </button>
                            @endif
                            @if (in_array($email->status, ['scheduled', 'draft']))
                                <button wire:click="deleteEmail({{ $email->id }})"
                                        wire:confirm="Sind Sie sicher?"
                                        class="text-red-400 hover:text-red-600 transition-colors text-xs font-bold uppercase">
                                    Löschen
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="px-6 py-3 border-t border-stone-200">
                {{ $emails->links() }}
            </div>
        @endif
    </div>
</div>
