<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'login_count' => 10,
                'last_login_at' => now(),
            ]
        );

        // Standard student
        User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'name' => 'Student User',
                'password' => Hash::make('password'),
                'role' => 'student',
                'login_count' => 5,
                'last_login_at' => now()->subDays(1),
            ]
        );

        // Dummy students
        User::factory()->count(10)->create([
            'role' => 'student',
            'login_count' => fn () => rand(1, 20),
            'last_login_at' => fn () => now()->subDays(rand(0, 14))->subHours(rand(0, 23)),
        ]);

        User::updateOrCreate(
            ['email' => 'mmaudace@gmail.com'],
            [
                'name' => 'Mmaudace',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ]
        );

        User::updateOrCreate(
            ['email' => 'thasengineza@gmail.com'],
            [
                'name' => 'Owner User',
                'password' => Hash::make('password'),
                'role' => 'owner',
            ]
        );
    }
}
