<?php

namespace App\Livewire;

use App\Models\Discount;
use App\Models\Plan;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Livewire\Component;

class AdminPricing extends Component
{
    public string $tab = 'plans';

    /** @var array<string, mixed> */
    public array $planForm = [
        'name' => '',
        'description' => '',
        'price' => 0,
        'currency' => 'eur',
        'interval' => 'month',
        'features' => '',
        'is_active' => true,
    ];

    /** @var array<string, mixed> */
    public array $discountForm = [
        'code' => '',
        'type' => 'percentage',
        'value' => 0,
        'max_uses' => 0,
        'expires_at' => '',
        'is_active' => true,
    ];

    public string $freeSectionLimit = '3';

    public bool $freeTrialPublished = false;

    public string $freeTrialDays = '7';

    public string $visaConsultancyUrl = '';

    public string $feedbackUrl = '';

    public ?int $editingPlanId = null;

    public ?int $editingDiscountId = null;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isOwner(), 403);
        $this->freeSectionLimit = (string) Setting::value('free_section_limit', '3');
        $this->freeTrialPublished = (bool) Setting::value('free_trial_published', false);
        $this->freeTrialDays = (string) Setting::value('free_trial_days', '7');
        $this->visaConsultancyUrl = (string) Setting::value('visa_consultancy_url', config('services.google_forms.visa_consultancy'));
        $this->feedbackUrl = (string) Setting::value('feedback_url', config('services.google_forms.feedback'));
    }

    public function render(): View
    {
        return view('livewire.admin-pricing', [
            'plans' => Plan::query()->orderBy('price')->get(),
            'discounts' => Discount::query()->orderBy('code')->get(),
        ]);
    }

    public function switchTab(string $tab): void
    {
        $this->tab = $tab;
        $this->resetForms();
    }

    public function editPlan(int $id): void
    {
        $plan = Plan::findOrFail($id);
        $this->editingPlanId = $plan->id;
        $this->planForm = $plan->toArray() + ['features' => implode("\n", $plan->features ?? [])];
    }

    public function savePlan(): void
    {
        $this->validate([
            'planForm.name' => ['required', 'string', 'max:255'],
            'planForm.price' => ['required', 'numeric', 'min:0'],
            'planForm.currency' => ['required', 'string', 'size:3'],
            'planForm.interval' => ['required', 'string', 'in:month,year'],
        ]);

        $data = $this->planForm;
        $data['features'] = array_filter(array_map('trim', explode("\n", $data['features'] ?? '')));

        if ($this->editingPlanId) {
            Plan::findOrFail($this->editingPlanId)->update($data);
        } else {
            Plan::create($data);
        }

        $this->resetForms();
        $this->dispatch('pricing-saved');
    }

    public function deletePlan(int $id): void
    {
        Plan::findOrFail($id)->delete();
        $this->resetForms();
    }

    public function editDiscount(int $id): void
    {
        $discount = Discount::findOrFail($id);
        $this->editingDiscountId = $discount->id;
        $this->discountForm = $discount->toArray();
        $this->discountForm['expires_at'] = $discount->expires_at?->format('Y-m-d\TH:i') ?? '';
    }

    public function saveDiscount(): void
    {
        $this->validate([
            'discountForm.code' => ['required', 'string', 'max:50'],
            'discountForm.type' => ['required', 'in:percentage,fixed'],
            'discountForm.value' => ['required', 'numeric', 'min:0'],
        ]);

        $data = $this->discountForm;
        $data['expires_at'] = $data['expires_at'] ?: null;

        if ($this->editingDiscountId) {
            Discount::findOrFail($this->editingDiscountId)->update($data);
        } else {
            Discount::create($data);
        }

        $this->resetForms();
        $this->dispatch('pricing-saved');
    }

    public function deleteDiscount(int $id): void
    {
        Discount::findOrFail($id)->delete();
        $this->resetForms();
    }

    public function saveSettings(): void
    {
        $this->validate([
            'freeTrialDays' => ['required', 'integer', 'min:1'],
            'freeSectionLimit' => ['required', 'integer', 'min:0'],
            'visaConsultancyUrl' => ['nullable', 'url', 'max:2048'],
            'feedbackUrl' => ['nullable', 'url', 'max:2048'],
        ]);

        Setting::query()->updateOrCreate(
            ['key' => 'free_section_limit'],
            ['value' => $this->freeSectionLimit],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'free_trial_published'],
            ['value' => $this->freeTrialPublished ? '1' : '0'],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'free_trial_days'],
            ['value' => $this->freeTrialDays],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'visa_consultancy_url'],
            ['value' => $this->visaConsultancyUrl],
        );

        Setting::query()->updateOrCreate(
            ['key' => 'feedback_url'],
            ['value' => $this->feedbackUrl],
        );

        $this->dispatch('pricing-saved');
    }

    private function resetForms(): void
    {
        $this->planForm = ['name' => '', 'description' => '', 'price' => 0, 'currency' => 'eur', 'interval' => 'month', 'features' => '', 'is_active' => true];
        $this->discountForm = ['code' => '', 'type' => 'percentage', 'value' => 0, 'max_uses' => 0, 'expires_at' => '', 'is_active' => true];
        $this->editingPlanId = null;
        $this->editingDiscountId = null;
    }
}
