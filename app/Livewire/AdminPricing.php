<?php

namespace App\Livewire;

use App\Models\Discount;
use App\Models\Plan;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
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
        'stripe_price_id' => '',
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

    public string $freeExamsPerSession = '2';
    public ?int $editingPlanId = null;
    public ?int $editingDiscountId = null;

    public function mount(): void
    {
        abort_unless(Auth::user()?->isOwner(), 403);
        $this->freeExamsPerSession = (string) Setting::value('free_exams_per_session', '2');
    }

    public function render()
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
        $this->planForm = $plan->toArray() + ['features' => is_array($plan->features) ? implode("\n", $plan->features) : ''];
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
        Setting::query()->updateOrCreate(
            ['key' => 'free_exams_per_session'],
            ['value' => $this->freeExamsPerSession],
        );

        $this->dispatch('pricing-saved');
    }

    private function resetForms(): void
    {
        $this->planForm = ['name' => '', 'description' => '', 'price' => 0, 'currency' => 'eur', 'interval' => 'month', 'stripe_price_id' => '', 'features' => '', 'is_active' => true];
        $this->discountForm = ['code' => '', 'type' => 'percentage', 'value' => 0, 'max_uses' => 0, 'expires_at' => '', 'is_active' => true];
        $this->editingPlanId = null;
        $this->editingDiscountId = null;
    }
}
