<div class="space-y-8 p-6 bg-zinc-950 min-h-screen text-zinc-100">
    <!-- Metrics Section -->
    <div class="telc-builder-head mb-6">
        <div>
            <h1 class="text-3xl font-bold font-serif text-zinc-100 mb-2">Student Performance & Guest Activity</h1>
            <p class="text-sm text-zinc-400">Verfolgen Sie die Aktivität von registrierten Studenten und anonymen Gästen, die kostenlose Prüfungen absolvieren.</p>
        </div>
    </div> 

    <section class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <article class="bg-zinc-900 border border-zinc-800 p-6 rounded-2xl shadow-xl relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-yellow-500"></div>
            <span class="text-xs text-zinc-400 font-mono uppercase tracking-wider block mb-2">Registrierte Studenten</span>
            <strong class="text-4xl font-bold text-zinc-100">{{ $metrics['students'] }}</strong>
        </article>
        <article class="bg-zinc-900 border border-zinc-800 p-6 rounded-2xl shadow-xl relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-red-600"></div>
            <span class="text-xs text-zinc-400 font-mono uppercase tracking-wider block mb-2">Studenten-Versuche</span>
            <strong class="text-4xl font-bold text-zinc-100">{{ $metrics['student_attempts'] }}</strong>
        </article>
        <article class="bg-zinc-900 border border-zinc-800 p-6 rounded-2xl shadow-xl relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-zinc-500"></div>
            <span class="text-xs text-zinc-400 font-mono uppercase tracking-wider block mb-2">Gast-Versuche</span>
            <strong class="text-4xl font-bold text-zinc-100">{{ $metrics['guest_attempts'] }}</strong>
        </article>
        <article class="bg-zinc-900 border border-zinc-800 p-6 rounded-2xl shadow-xl relative overflow-hidden">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-600 via-yellow-500 to-red-600"></div>
            <span class="text-xs text-zinc-400 font-mono uppercase tracking-wider block mb-2">Gesamt-Versuche</span>
            <strong class="text-4xl font-bold text-zinc-100">{{ $metrics['total_attempts'] }}</strong>
        </article>
    </section>

    <!-- Student Performance -->
    <section class="telc-panel admin-performance bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-zinc-100 font-serif">Aktivität Registrierter Studenten</h2>
            <p class="text-xs text-zinc-400">Letzte Anmeldungen, Anzahl der Versuche und Notenschnitt von registrierten Benutzern.</p>
        </div> 

        <div class="admin-table-wrap overflow-x-auto rounded-xl border border-zinc-800">
            <table class="w-full text-left border-collapse text-zinc-300">
                <thead>
                    <tr class="bg-zinc-900/80 border-b border-zinc-800 text-zinc-400 font-mono text-xs uppercase">
                        <th class="py-4 px-5">Student</th>
                        <th class="py-4 px-5">Letzter Login</th>
                        <th class="py-4 px-5 text-center">Versuche</th>
                        <th class="py-4 px-5">Letzte Prüfung</th>
                        <th class="py-4 px-5">Letztes Ergebnis</th>
                        <th class="py-4 px-5">Durchschnitt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/50">
                    @forelse ($performanceRows as $row)
                        <tr class="hover:bg-zinc-800/30 text-sm transition">
                            <td class="py-4 px-5">
                                <strong class="text-zinc-200 block font-semibold">{{ $row['student']->name }}</strong>
                                <span class="text-xs text-zinc-500 font-mono">{{ $row['student']->email }}</span>
                            </td>
                            <td class="py-4 px-5 text-zinc-400">{{ $row['student']->last_login_at?->diffForHumans() ?? 'Nie' }}</td>
                            <td class="py-4 px-5 text-center font-mono font-semibold text-yellow-500">{{ $row['attempts'] }}</td>
                            <td class="py-4 px-5"><span class="bg-zinc-800/80 text-zinc-300 text-xs px-2.5 py-1 rounded font-mono font-bold">{{ $row['latest_exam'] ?? '-' }}</span></td>
                            <td class="py-4 px-5 font-mono @if($row['latest_score'] !== null) {{ $row['latest_score'] >= 60 ? 'text-green-400' : 'text-red-400' }} @endif">
                                {{ $row['latest_score'] !== null ? $row['latest_score'].'%' : '-' }}
                            </td>
                            <td class="py-4 px-5 font-mono text-zinc-300 font-bold">{{ $row['average'] }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-zinc-500 text-sm font-medium">Keine studentischen Aktivitäten aufgezeichnet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

    <!-- Guest Performance -->
    <section class="telc-panel admin-performance bg-zinc-900 border border-zinc-800 rounded-2xl p-6 shadow-xl">
        <div class="mb-6">
            <h2 class="text-xl font-bold text-zinc-100 font-serif">Gast-Prüfungsversuche (Nicht angemeldet)</h2>
            <p class="text-xs text-zinc-400">Verfolgen Sie Prüfungen von anonymen Besuchern. Diese Benutzer werden über Cookies identifiziert.</p>
        </div> 

        <div class="admin-table-wrap overflow-x-auto rounded-xl border border-zinc-800">
            <table class="w-full text-left border-collapse text-zinc-300">
                <thead>
                    <tr class="bg-zinc-900/80 border-b border-zinc-800 text-zinc-400 font-mono text-xs uppercase">
                        <th class="py-4 px-5">Datum / Zeit</th>
                        <th class="py-4 px-5">Prüfungstitel</th>
                        <th class="py-4 px-5 text-center">Niveau</th>
                        <th class="py-4 px-5">Punkte</th>
                        <th class="py-4 px-5">Prozent</th>
                        <th class="py-4 px-5 text-center">Antworten</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/50">
                    @forelse ($guestAttempts as $attempt)
                        <tr class="hover:bg-zinc-800/30 text-sm transition">
                            <td class="py-4 px-5 text-zinc-400">
                                {{ $attempt->submitted_at?->setTimezone(config('app.timezone', 'Europe/Berlin'))->format('d.m.Y H:i') ?? $attempt->created_at->setTimezone(config('app.timezone', 'Europe/Berlin'))->format('d.m.Y H:i') }}
                            </td>
                            <td class="py-4 px-5">
                                <strong class="text-zinc-200 block font-semibold">{{ $attempt->examTemplate?->title ?? 'Gelöschte Prüfung' }}</strong>
                            </td>
                            <td class="py-4 px-5 text-center">
                                <span class="bg-zinc-800/80 text-zinc-300 text-xs px-2.5 py-1 rounded font-mono font-bold">
                                    {{ $attempt->examTemplate?->level ?? '-' }}
                                </span>
                            </td>
                            <td class="py-4 px-5 font-mono">
                                {{ $attempt->result['earned'] ?? 0 }} / {{ $attempt->result['possible'] ?? 0 }}
                            </td>
                            <td class="py-4 px-5 font-mono @if(isset($attempt->result['percentage'])) {{ $attempt->result['percentage'] >= 60 ? 'text-green-400' : 'text-red-400' }} @endif">
                                {{ $attempt->result['percentage'] ?? 0 }}%
                            </td>
                            <td class="py-4 px-5 text-center">
                                <span class="bg-zinc-800 text-zinc-400 text-xs px-3 py-1 rounded font-mono">
                                    {{ count($attempt->answers) }} Beantwortet
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-zinc-500 text-sm font-medium">Keine Gast-Prüfungsversuche verzeichnet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
