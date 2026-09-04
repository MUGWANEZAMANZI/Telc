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
        // Remove dummy users with example.net, example.com
        // User::where('email', 'like', '%example.net')
        //     ->orWhere('email', 'like', '%example.com')
        //     ->delete();

        // Admin
        // User::updateOrCreate(
        //     ['email' => 'admin@telc.de'],
        //     [
        //         'name' => 'Admin User',
        //         'password' => Hash::make('password'),
        //         'role' => 'admin',
        //         'login_count' => 10,
        //         'last_login_at' => now(),
        //     ]
        // );

        // Standard student
        // User::updateOrCreate(
        //     ['email' => 'student@telc.de'],
        //     [
        //         'name' => 'Student User',
        //         'password' => Hash::make('password'),
        //         'role' => 'student',
        //         'login_count' => 5,
        //         'last_login_at' => now()->subDays(1),
        //     ]
        // );

        // Dummy students - using @telc.de domain
        // for ($i = 1; $i <= 10; $i++) {
        //     User::updateOrCreate(
        //         ['email' => "dummy_student{$i}@telc.de"],
        //         [
        //             'name' => fake()->name(),
        //             'password' => Hash::make('password'),
        //             'role' => 'student',
        //             'login_count' => rand(1, 20),
        //             'last_login_at' => now()->subDays(rand(0, 14))->subHours(rand(0, 23)),
        //         ]
        //     );
        // }

        User::updateOrCreate(
            ['email' => 'mmaudace@gmail.com'],
            [
                'name' => 'Mmaudace',
                'password' => Hash::make('password'),
                'role' => 'admin',
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
