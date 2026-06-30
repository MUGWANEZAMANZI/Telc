<div class="owner-dashboard-shell" style="max-width: 1000px; margin: 0 auto; font-family: 'Plus Jakarta Sans', sans-serif; padding: 1rem;">
    <div style="margin-bottom: 2.5rem;">
        <h2 style="font-family: 'Playfair Display', serif; font-size: 2.4rem; font-weight: 700; margin: 0 0 0.5rem; color: #111827;">{{ __('Owner Panel') }}</h2>
        <p style="color: #666666; font-size: 1.05rem; margin: 0;">Benutzer verwalten und Berechtigungen steuern.</p>
    </div>

    @if (session('owner_message'))
        <div style="background: #e6f4ea; border: 1px solid #a7f3d0; border-radius: 8px; padding: 1rem; margin-bottom: 1.5rem; color: #137333; font-weight: 600;">
            {{ session('owner_message') }}
        </div>
    @endif

    <div style="display: flex; gap: 1rem; flex-wrap: wrap; margin-bottom: 2rem;">
        <a href="{{ route('admin.finance') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; background: #d8232a; color: #ffffff; text-decoration: none; font-weight: 700; font-size: 0.95rem; padding: 0.75rem 1.5rem; border-radius: 6px; transition: background 0.2s;">
            → Finanzübersicht
        </a>
    </div>

    <div style="background: #ffffff; border: 1px solid #e5e7eb; border-left: 5px solid #137333; border-radius: 12px; padding: 2.5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.02);">
        <h3 style="font-family: 'Playfair Display', serif; font-size: 1.5rem; margin: 0 0 0.25rem; color: #111827;">Benutzerverwaltung</h3>
        <p style="color: #666666; font-size: 0.95rem; margin-bottom: 1.5rem;">Alle registrierten Benutzer anzeigen und Rollen verwalten.</p>

        <div style="display: flex; gap: 1rem; margin-bottom: 1.5rem; flex-wrap: wrap;">
            <input type="text" wire:model.live.debounce="userFilter" placeholder="Name oder E-Mail suchen..." style="flex: 1; min-width: 200px; padding: 0.6rem 1rem; border: 1px solid #d9dde5; border-radius: 6px; font-size: 0.9rem;">
            <select wire:model.live="roleFilter" style="padding: 0.6rem 1rem; border: 1px solid #d9dde5; border-radius: 6px; font-size: 0.9rem; background: #fff;">
                <option value="">Alle Rollen</option>
                <option value="owner">Owner</option>
                <option value="admin">Admin</option>
                <option value="student">Student</option>
            </select>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: left; font-size: 0.9rem;">
                <thead>
                    <tr style="background: #fafafa; border-bottom: 2px solid #edf0f4;">
                        <th style="padding: 0.75rem;">Name</th>
                        <th style="padding: 0.75rem;">E-Mail</th>
                        <th style="padding: 0.75rem;">Rolle</th>
                        <th style="padding: 0.75rem;">Letzter Login</th>
                        <th style="padding: 0.75rem;">Aktionen</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr style="border-bottom: 1px solid #edf0f4;">
                            <td style="padding: 0.75rem; font-weight: 600;">{{ $user->name }}</td>
                            <td style="padding: 0.75rem;">{{ $user->email }}</td>
                            <td style="padding: 0.75rem;">
                                <span style="display: inline-block; padding: 0.2rem 0.5rem; border-radius: 4px; font-size: 0.8rem; font-weight: 700; text-transform: uppercase;
                                    {{ $user->role === 'owner' ? 'background: #fef3cd; color: #856404;' : '' }}
                                    {{ $user->role === 'admin' ? 'background: #cce5ff; color: #004085;' : '' }}
                                    {{ $user->role === 'student' ? 'background: #e6f4ea; color: #137333;' : '' }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td style="padding: 0.75rem; color: #666666;">{{ $user->last_login_at?->format('d.m.Y H:i') ?: 'Nie' }}</td>
                            <td style="padding: 0.75rem;">
                                @if ($user->role === 'student')
                                    <button wire:click="promoteToAdmin({{ $user->id }})" style="background: #004085; color: #fff; border: none; padding: 0.4rem 1rem; border-radius: 4px; font-weight: 600; cursor: pointer;">
                                        Zum Admin befördern
                                    </button>
                                @elseif ($user->role === 'admin')
                                    <button wire:click="demoteToStudent({{ $user->id }})" style="background: #d8232a; color: #fff; border: none; padding: 0.4rem 1rem; border-radius: 4px; font-weight: 600; cursor: pointer;">
                                        Zum Student herabstufen
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="padding: 1rem; text-align: center; color: #666666; font-style: italic;">Keine Benutzer gefunden.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
