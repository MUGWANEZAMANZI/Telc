<?php

namespace Database\Seeders;

use App\Models\ExamTemplate;
use App\Services\TelcTemplateFactory;
use Illuminate\Database\Seeder;

class ExamTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First insert base presets
        foreach (TelcTemplateFactory::presets() as $preset) {
            ExamTemplate::updateOrCreate(
                ['title' => $preset['title']],
                $preset + ['is_published' => true]
            );
        }
    }
}
