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
        foreach (TelcTemplateFactory::presets() as $preset) {
            ExamTemplate::updateOrCreate(
                ['level' => $preset['level'], 'title' => $preset['title']],
                $preset + ['is_published' => true]
            );
        }
    }
}
