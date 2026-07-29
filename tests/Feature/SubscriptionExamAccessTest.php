<?php

use App\Livewire\AdminFinance;
use App\Livewire\StudentExam;
use App\Models\ExamEnrollment;
use App\Models\ExamTemplate;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use App\Services\SpeakingPromptService;
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

test('it returns correct dynamic speaking prompts based on provider and level', function () {
    $a1Telc = SpeakingPromptService::getSystemInstruction('telc', 'A1');
    $b2Goethe = SpeakingPromptService::getSystemInstruction('goethe', 'B2');
    $c1Telc = SpeakingPromptService::getSystemInstruction('telc', 'C1');

    expect($a1Telc)->toContain('conduct the actual exam prompts and responses in simple, clear A1 German');
    expect($b2Goethe)->toContain('conducting the official Goethe-Zertifikat B2 "Sprechen" exam');
    expect($c1Telc)->toContain('Die Ökonomisierung des Gesundheitssystems');
});

test('non-subscribed users can access free exams but non-free exams require payment', function () {
    ExamTemplate::query()->delete();
    $freeTemplate = ExamTemplate::create([
        'title' => 'Free B1 Exam',
        'level' => 'B1',
        'exam_provider' => 'telc',
        'is_published' => true,
        'is_free' => true,
        'theme' => [],
        'timings' => [],
        'sections' => [
            ['id' => 'sec1', 'title' => 'Section 1', 'minutes' => 15, 'elements' => []],
            ['id' => 'sec2', 'title' => 'Section 2', 'minutes' => 15, 'elements' => []],
            ['id' => 'sec3', 'title' => 'Section 3', 'minutes' => 15, 'elements' => []],
        ],
    ]);

    $paidTemplate = ExamTemplate::create([
        'title' => 'Paid B1 Exam',
        'level' => 'B1',
        'exam_provider' => 'telc',
        'is_published' => true,
        'is_free' => false,
        'theme' => [],
        'timings' => [],
        'sections' => [
            ['id' => 'secA', 'title' => 'Section A', 'minutes' => 15, 'elements' => []],
            ['id' => 'secB', 'title' => 'Section B', 'minutes' => 15, 'elements' => []],
        ],
    ]);

    $student = User::factory()->create(['role' => 'student']);
    $this->actingAs($student);

    $status1 = Livewire::test(StudentExam::class)->instance()->getTemplateFreeStatus($freeTemplate);
    expect($status1['is_free'])->toBeTrue();
    expect($status1['free_sections'])->toBe(3);

    $status2 = Livewire::test(StudentExam::class)->instance()->getTemplateFreeStatus($paidTemplate);
    expect($status2['is_free'])->toBeFalse();
    expect($status2['free_sections'])->toBe(0);
});

test('admin can create a 30-day manual free trial for a user', function () {
    $plan = Plan::create([
        'name' => 'Trial Target Plan',
        'price' => 19.99,
        'currency' => 'eur',
        'interval' => 'month',
        'features' => [],
        'is_active' => true,
    ]);

    $student = User::factory()->create(['role' => 'student']);
    $owner = User::factory()->create(['role' => 'owner']); // Owner acts as admin

    $this->actingAs($owner);

    // Create an old active subscription to verify it gets deactivated
    $oldSub = Subscription::create([
        'user_id' => $student->id,
        'plan_id' => $plan->id,
        'provider' => 'paypal',
        'provider_order_id' => 'old_sub_123',
        'status' => 'active',
        'duration_months' => 1,
        'starts_at' => now()->subDays(10),
        'ends_at' => now()->addDays(20),
    ]);

    Livewire::test(AdminFinance::class)
        ->set('selectedUserForTrial', $student->id)
        ->set('selectedPlanForTrial', $plan->id)
        ->call('createFreeTrial')
        ->assertHasNoErrors()
        ->assertSet('successMessage', "30-tägiges kostenloses Probeabo (Trial Target Plan) für {$student->name} wurde erfolgreich aktiviert.");

    // Assert new subscription was created with 30 days duration
    $newSub = Subscription::where('user_id', $student->id)
        ->where('provider', 'manual_admin')
        ->first();

    expect($newSub)->not->toBeNull();
    expect($newSub->status)->toBe('active');
    expect((int) abs($newSub->ends_at->diffInDays($newSub->starts_at)))->toBe(30);

    // Assert old subscription status is cancelled
    $oldSub->refresh();
    expect($oldSub->status)->toBe('cancelled');
});

test('investor can view finance statistics but cannot perform write operations', function () {
    $plan = Plan::create([
        'name' => 'Target Plan',
        'price' => 19.99,
        'currency' => 'eur',
        'interval' => 'month',
        'features' => [],
        'is_active' => true,
    ]);

    $student = User::factory()->create(['role' => 'student', 'login_count' => 5]);
    $investor = User::factory()->create(['role' => 'investor']);

    $this->actingAs($investor);

    // Assert access is authorized and variables are set correctly
    Livewire::test(AdminFinance::class)
        ->assertViewHas('totalVisits', 5)
        ->assertViewHas('totalPaymentsCount', 0)
        ->assertViewHas('totalTrialsCount', 0)
        ->assertViewHas('paidUsersCount', 0)
        ->assertDontSee('30 Tage kostenloses Probeabo einrichten')
        ->set('selectedUserForTrial', $student->id)
        ->set('selectedPlanForTrial', $plan->id)
        ->call('createFreeTrial')
        ->assertStatus(403);
});
