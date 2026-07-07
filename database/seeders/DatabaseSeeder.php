<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ExamTemplateSeeder::class,
            LearningModuleSeeder::class,
        ]);

        Plan::updateOrCreate(
            ['name' => 'Einmalige Prüfung'],
            [
                'description' => 'Zugang zu einer vollständigen Prüfung Ihrer Wahl.',
                'price' => 5.00,
                'currency' => 'eur',
                'interval' => 'one-off',
                'features' => ['1 vollständige Prüfung (B1, B2, oder C1)', 'Gemini-KI-Sprechübung', 'Schreibkorrekturen', 'Auto-Safe & detaillierte Auswertung'],
                'is_active' => true,
            ]
        );

        Plan::updateOrCreate(
            ['name' => 'Monatliches Training (Standard)'],
            [
                'description' => 'Perfekt für die kontinuierliche Prüfungsvorbereitung.',
                'price' => 19.00,
                'currency' => 'eur',
                'interval' => 'month',
                'features' => ['Bis zu 30 Prüfungen pro Monat', 'Voller Gemini-KI-Support', 'Auswertung & Fehleranalysen', 'Schreibübungen-Feedback'],
                'is_active' => true,
            ]
        );

        Plan::updateOrCreate(
            ['name' => 'Monatliches Training (Intensiv)'],
            [
                'description' => 'Maximale Vorbereitung für den schnellen Erfolg.',
                'price' => 69.00,
                'currency' => 'eur',
                'interval' => 'month',
                'features' => ['Bis zu 60 Prüfungen pro Monat', 'Gemini-KI-Sprechübung unbegrenzt', 'Priorisierter Support', 'Individuelle Lernmodule'],
                'is_active' => true,
            ]
        );
    }
}
