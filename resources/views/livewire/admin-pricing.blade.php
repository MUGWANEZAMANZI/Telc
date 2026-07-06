<div>
    <div class="telc-builder-head">
        <div>
            <h2>Pricing & Settings</h2>
            <p>Manage subscription plans, discount codes, and platform settings.</p>
        </div>
    </div>

    <div class="telc-segment" style="margin-bottom:1.5rem">
        <button type="button" wire:click="switchTab('plans')" @class(['active' => $tab === 'plans'])>Plans</button>
        <button type="button" wire:click="switchTab('discounts')" @class(['active' => $tab === 'discounts'])>Discounts</button>
        <button type="button" wire:click="switchTab('settings')" @class(['active' => $tab === 'settings'])>Settings</button>
    </div>

    @if ($tab === 'plans')
        <div class="telc-admin-grid">
            <aside class="telc-panel">
                <h2>{{ $editingPlanId ? 'Edit Plan' : 'New Plan' }}</h2>

                <label>Name</label>
                <input type="text" wire:model.live="planForm.name" class="telc-input">

                <label>Description</label>
                <textarea wire:model.live="planForm.description" class="telc-textarea" style="min-height:4rem"></textarea>

                <label>Price</label>
                <input type="number" wire:model.live="planForm.price" class="telc-input" step="0.01">

                <label>Currency</label>
                <select wire:model.live="planForm.currency" class="telc-select">
                    <option value="eur">EUR</option>
                    <option value="usd">USD</option>
                </select>

                <label>Interval</label>
                <select wire:model.live="planForm.interval" class="telc-select">
                    <option value="month">Monthly</option>
                    <option value="year">Yearly</option>
                </select>

                <label>Features (one per line)</label>
                <textarea wire:model.live="planForm.features" class="telc-textarea" style="min-height:6rem;font-family:monospace" placeholder="Unlimited exams&#10;AI feedback&#10;Priority support"></textarea>

                <label class="telc-checkbox-label">
                    <input type="checkbox" wire:model.live="planForm.is_active">
                    Active
                </label>

                <div class="telc-row">
                    <button type="button" wire:click="savePlan" class="telc-button primary">Save Plan</button>
                    @if ($editingPlanId)
                        <button type="button" wire:click="deletePlan({{ $editingPlanId }})" class="telc-button danger">Delete</button>
                    @endif
                </div>
            </aside>

            <main class="telc-panel">
                <h2>Existing Plans</h2>
                @forelse ($plans as $plan)
                    <article class="telc-plan-card" wire:key="plan-{{ $plan->id }}">
                        <div>
                            <strong>{{ $plan->name }}</strong>
                            <span>{{ number_format($plan->price, 2) }} {{ strtoupper($plan->currency) }} / {{ $plan->interval }}</span>
                            @if ($plan->description)
                                <p>{{ $plan->description }}</p>
                            @endif
                        </div>
                        <div class="telc-row">
                            <span @class(['telc-pill', 'green' => $plan->is_active])>{{ $plan->is_active ? 'Active' : 'Inactive' }}</span>
                            <button type="button" wire:click="editPlan({{ $plan->id }})" class="telc-link-button">Edit</button>
                        </div>
                    </article>
                @empty
                    <p>No plans created yet.</p>
                @endforelse
            </main>
        </div>

    @elseif ($tab === 'discounts')
        <div class="telc-admin-grid">
            <aside class="telc-panel">
                <h2>{{ $editingDiscountId ? 'Edit Discount' : 'New Discount' }}</h2>

                <label>Code</label>
                <input type="text" wire:model.live="discountForm.code" class="telc-input">

                <label>Type</label>
                <select wire:model.live="discountForm.type" class="telc-select">
                    <option value="percentage">Percentage</option>
                    <option value="fixed">Fixed</option>
                </select>

                <label>Value</label>
                <input type="number" wire:model.live="discountForm.value" class="telc-input" step="0.01">

                <label>Max Uses (0 = unlimited)</label>
                <input type="number" wire:model.live="discountForm.max_uses" class="telc-input">

                <label>Expires At</label>
                <input type="datetime-local" wire:model.live="discountForm.expires_at" class="telc-input">

                <label class="telc-checkbox-label">
                    <input type="checkbox" wire:model.live="discountForm.is_active">
                    Active
                </label>

                <div class="telc-row">
                    <button type="button" wire:click="saveDiscount" class="telc-button primary">Save Discount</button>
                    @if ($editingDiscountId)
                        <button type="button" wire:click="deleteDiscount({{ $editingDiscountId }})" class="telc-button danger">Delete</button>
                    @endif
                </div>
            </aside>

            <main class="telc-panel">
                <h2>Existing Discounts</h2>
                @forelse ($discounts as $discount)
                    <article class="telc-plan-card" wire:key="discount-{{ $discount->id }}">
                        <div>
                            <strong>{{ $discount->code }}</strong>
                            <span>{{ $discount->type === 'percentage' ? $discount->value.'%' : number_format($discount->value, 2).' EUR' }}</span>
                            <p>Used {{ $discount->used_count }}/{{ $discount->max_uses ?: '∞' }}</p>
                        </div>
                        <div class="telc-row">
                            <span @class(['telc-pill', 'green' => $discount->is_active])>{{ $discount->is_active ? 'Active' : 'Inactive' }}</span>
                            <button type="button" wire:click="editDiscount({{ $discount->id }})" class="telc-link-button">Edit</button>
                        </div>
                    </article>
                @empty
                    <p>No discounts created yet.</p>
                @endforelse
            </main>
        </div>

    @elseif ($tab === 'settings')
        <div class="telc-panel" style="max-width:480px">
            <h2>Platform Settings</h2>

            <label>Free section completions limit</label>
            <input type="number" wire:model.live="freeSectionLimit" class="telc-input" min="0">
            <p style="color:#667085;font-size:.78rem;margin:0 0 1rem">Number of free section completions a user gets before needing to subscribe. Set to 0 to disable free access.</p>

            <button type="button" wire:click="saveSettings" class="telc-button primary">Save Settings</button>
        </div>
    @endif
</div>
