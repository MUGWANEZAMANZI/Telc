<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class OwnerDashboard extends Component
{
    public string $userFilter = '';
    public string $roleFilter = '';

    public function mount(): void
    {
        abort_unless(Auth::user()?->isOwner(), 403);
    }

    public function render()
    {
        $query = User::query()->orderBy('name');

        if ($this->roleFilter) {
            $query->where('role', $this->roleFilter);
        }

        if ($this->userFilter) {
            $query->where(function ($q) {
                $q->where('name', 'like', "%{$this->userFilter}%")
                  ->orWhere('email', 'like', "%{$this->userFilter}%");
            });
        }

        return view('livewire.owner-dashboard', [
            'users' => $query->get(),
        ]);
    }

    public function promoteToAdmin(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['role' => 'admin']);
        session()->flash('owner_message', "{$user->name} wurde zum Admin befördert.");
    }

    public function demoteToStudent(int $userId): void
    {
        $user = User::findOrFail($userId);
        $user->update(['role' => 'student']);
        session()->flash('owner_message', "{$user->name} wurde zum Student herabgestuft.");
    }
}
