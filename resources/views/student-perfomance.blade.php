<section class="telc-panel admin-performance">
        <div class="telc-builder-head">
            <div>
                <h2>Student Performance</h2>
                <p>Latest registered students, login activity, attempts, and score progress.</p>
            </div>
        </div> 

        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Last Login</th>
                        <th>Attempts</th>
                        <th>Latest Exam</th>
                        <th>Latest Score</th>
                        <th>Average</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($performanceRows as $row)
                        <tr>
                            <td>
                                <strong>{{ $row['student']->name }}</strong>
                                <span>{{ $row['student']->email }}</span>
                            </td>
                            <td>{{ $row['student']->last_login_at?->diffForHumans() ?? 'Never' }}</td>
                            <td>{{ $row['attempts'] }}</td>
                            <td>{{ $row['latest_exam'] ?? '-' }}</td>
                            <td>{{ $row['latest_score'] !== null ? $row['latest_score'].'%' : '-' }}</td>
                            <td>{{ $row['average'] }}%</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">No student activity has been recorded yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section> 