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

        $levels = ['B1', 'B2', 'C1'];

        // Seed 20 TELC exams dynamically
        for ($i = 1; $i <= 20; $i++) {
            $lvl = $levels[$i % count($levels)];
            $preset = TelcTemplateFactory::forLevel($lvl);
            
            ExamTemplate::updateOrCreate(
                ['title' => "telc Deutsch $lvl - Modellsatz #$i"],
                [
                    'level' => $lvl,
                    'exam_provider' => 'telc',
                    'source_pdf' => $preset['source_pdf'] ?? null,
                    'theme' => $preset['theme'],
                    'timings' => $preset['timings'],
                    'sections' => $preset['sections'],
                    'is_published' => true,
                ]
            );
        }

        // Seed 20 Goethe exams dynamically
        for ($j = 1; $j <= 20; $j++) {
            $lvl = $levels[$j % count($levels)];
            $preset = TelcTemplateFactory::forLevel($lvl);

            ExamTemplate::updateOrCreate(
                ['title' => "Goethe Zertifikat $lvl - Übungssatz #$j"],
                [
                    'level' => $lvl,
                    'exam_provider' => 'goethe',
                    'source_pdf' => $preset['source_pdf'] ?? null,
                    'theme' => $preset['theme'],
                    'timings' => $preset['timings'],
                    'sections' => $preset['sections'],
                    'is_published' => true,
                ]
            );
        }
    }
}
