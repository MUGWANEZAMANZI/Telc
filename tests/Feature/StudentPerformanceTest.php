<?php

use App\Models\ExamAttempt;
use App\Models\ExamTemplate;
use App\Models\User;
use App\Livewire\StudentPerformance;
use Livewire\Livewire;

test('guests and non-admins are blocked from student performance page', function () {
    $this->get(route('student.performance'))->assertRedirect(route('login'));

    $student = User::factory()->create(['role' => 'student']);
    $this->actingAs($student);
    $this->get(route('student.performance'))->assertStatus(403);
});

test('admins can view the student performance page and guest attempts', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $this->actingAs($admin);

    ExamTemplate::query()->delete();
    $template = ExamTemplate::create([
        'title' => 'Sample Exam C1',
        'level' => 'C1',
        'is_published' => true,
        'theme' => [],
        'timings' => [],
        'sections' => []
    ]);

    // Create a student attempt
    $student = User::factory()->create(['role' => 'student']);
    ExamAttempt::create([
        'exam_template_id' => $template->id,
        'user_id' => $student->id,
        'answers' => [],
        'result' => ['earned' => 10, 'possible' => 20, 'percentage' => 50],
        'started_at' => now(),
        'submitted_at' => now(),
    ]);

    // Create a guest attempt
    ExamAttempt::create([
        'exam_template_id' => $template->id,
        'user_id' => null,
        'answers' => [],
        'result' => ['earned' => 15, 'possible' => 20, 'percentage' => 75],
        'started_at' => now(),
        'submitted_at' => now(),
    ]);

    $response = $this->get(route('student.performance'));
    $response->assertOk();

    Livewire::test(StudentPerformance::class)
        ->assertSee('Aktivität Registrierter Studenten')
        ->assertSee('Gast-Prüfungsversuche (Nicht angemeldet)')
        ->assertSee($student->name)
        ->assertSee('Sample Exam C1')
        ->assertSee('75%');
});
