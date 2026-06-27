<?php

use App\Models\ExamEnrollment;
use App\Models\ExamTemplate;
use App\Models\Subscription;
use App\Models\Plan;
use App\Models\User;
use App\Livewire\StudentExam;
use Livewire\Livewire;

test('subscribed users can bypass enrollment payment modal and start exams directly', function () {
    ExamTemplate::query()->delete();
    $template = ExamTemplate::create([
        'title' => 'Premium C1 Exam',
        'level' => 'C1',
        'is_published' => true,
        'theme' => [],
        'timings' => [],
        'sections' => []
    ]);

    $plan = Plan::create([
        'name' => 'Monthly Plan',
        'stripe_price_id' => 'price_test_123',
        'price' => 19.99,
        'currency' => 'eur',
        'interval' => 'month',
        'features' => [],
        'is_active' => true,
    ]);

    $student = User::factory()->create(['role' => 'student']);
    $this->actingAs($student);

    // Assert initially not enrolled and not subscribed
    Livewire::test(StudentExam::class)
        ->assertViewHas('isSubscribed', false)
        ->assertDontSee('Prüfung starten')
        ->assertSee('Kostenpflichtige Prüfung freischalten');

    // Add subscription
    Subscription::create([
        'user_id' => $student->id,
        'plan_id' => $plan->id,
        'stripe_subscription_id' => 'sub_123',
        'stripe_status' => 'active',
    ]);

    // Assert now subscribed and can start
    Livewire::test(StudentExam::class)
        ->assertViewHas('isSubscribed', true)
        ->assertSee('Prüfung starten')
        ->assertDontSee('Kostenpflichtige Prüfung freischalten')
        ->call('startOrResumeExam', $template->id);

    // Verify enrollment was automatically created
    expect(ExamEnrollment::where('user_id', $student->id)->where('exam_template_id', $template->id)->exists())->toBeTrue();
});
