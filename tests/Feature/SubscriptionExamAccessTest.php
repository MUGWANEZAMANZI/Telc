<?php

use App\Livewire\StudentExam;
use App\Models\ExamEnrollment;
use App\Models\ExamTemplate;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use Livewire\Livewire;

test('subscribed users can bypass enrollment payment modal and start exams directly', function () {
    ExamTemplate::query()->delete();
    $template = ExamTemplate::create([
        'title' => 'Premium C1 Exam',
        'level' => 'C1',
        'is_published' => true,
        'theme' => [],
        'timings' => [],
        'sections' => [],
    ]);

    $plan = Plan::create([
        'name' => 'Monthly Plan',
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
        ->set('selectedLevel', 'C1')
        ->assertViewHas('isSubscribed', false)
        ->assertDontSee('Prüfung starten')
        ->assertSee('Kostenpflichtige Prüfung freischalten');

    // Add subscription
    Subscription::create([
        'user_id' => $student->id,
        'plan_id' => $plan->id,
        'provider' => 'paypal',
        'provider_order_id' => 'sub_123',
        'status' => 'active',
        'duration_months' => 1,
        'starts_at' => now(),
        'ends_at' => now()->addMonth(),
    ]);

    // Assert now subscribed and can start
    Livewire::test(StudentExam::class)
        ->set('selectedLevel', 'C1')
        ->assertViewHas('isSubscribed', true)
        ->assertSee('Prüfung starten')
        ->assertDontSee('Kostenpflichtige Prüfung freischalten')
        ->call('startOrResumeExam', $template->id);

    // Verify enrollment was automatically created
    expect(ExamEnrollment::where('user_id', $student->id)->where('exam_template_id', $template->id)->exists())->toBeTrue();
});

test('it calculates pricing for different durations with correct discounts', function () {
    $plan = Plan::create([
        'name' => 'Monthly Plan',
        'price' => 6.98,
        'currency' => 'eur',
        'interval' => 'month',
        'features' => [],
        'is_active' => true,
    ]);

    expect($plan->getPriceForDuration(1))->toBe(6.98);
    expect($plan->getPriceForDuration(3))->toBe(19.47);
    expect($plan->getPriceForDuration(6))->toBe(36.85);
    expect($plan->getPriceForDuration(12))->toBe(59.90);
});
