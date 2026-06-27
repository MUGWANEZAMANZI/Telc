<?php

namespace Database\Seeders;

use App\Models\User;
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

        \App\Models\Plan::updateOrCreate(
            ['stripe_price_id' => 'price_premium_monthly'],
            [
                'name' => 'Premium Monatlich',
                'description' => 'Voller Zugang zu allen Prüfungen und Gemini-Sprachtraining.',
                'price' => 19.99,
                'currency' => 'eur',
                'interval' => 'month',
                'features' => ['Unbegrenzte Prüfungen (B1, B2, C1, C2)', 'Gemini-KI-Sprechübung', 'Schreibkorrekturen', 'Offizielle TELC-Zertifikatssimulation'],
                'is_active' => true,
            ]
        );

        \App\Models\Plan::updateOrCreate(
            ['stripe_price_id' => 'price_premium_yearly'],
            [
                'name' => 'Premium Jährlich',
                'description' => 'Sparen Sie über 50% mit unserem Jahresabo.',
                'price' => 99.99,
                'currency' => 'eur',
                'interval' => 'year',
                'features' => ['Unbegrenzte Prüfungen (B1, B2, C1, C2)', 'Gemini-KI-Sprechübung', 'Schreibkorrekturen', 'Offizielle TELC-Zertifikatssimulation', 'Vorrangiger Support'],
                'is_active' => true,
            ]
        );
    }
}
