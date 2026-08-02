<?php

use App\Livewire\ExamDashboard;
use App\Models\ExamTemplate;
use App\Models\User;
use Livewire\Livewire;

test('dump c1 student html', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    $pdo = new PDO('sqlite:'.base_path('database/database.sqlite'));
    $sections = json_decode($pdo->query('SELECT sections FROM exam_templates WHERE id = 70')->fetchColumn(), true);

    $template = ExamTemplate::create([
        'title' => 'telc Deutsch C1',
        'level' => 'C1',
        'is_published' => true,
        'exam_provider' => 'telc',
        'theme' => [],
        'timings' => [],
        'sections' => $sections,
    ]);

    $c = Livewire::test(ExamDashboard::class)
        ->call('loadTemplate', $template->id)
        ->call('switchMode', 'student');

    file_put_contents(sys_get_temp_dir().'/c1_student_dump.html', $c->html());

    expect(file_get_contents(sys_get_temp_dir().'/c1_student_dump.html'))->toContain('lv2-options-container');
});
