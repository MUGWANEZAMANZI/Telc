<?php

use App\Models\ExamTemplate;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$t = ExamTemplate::where('title', 'Goethe Zertifikat C1')->first();
$sections = collect($t->sections);
$reading = $sections->where('id', 'c1-reading-language')->first();
foreach ($reading['elements'] as $el) {
    if (str_starts_with($el['id'], 'C1-LV2-')) {
        echo 'Element: '.$el['id']."\n";
        echo '  Type: '.$el['type']."\n";
        echo '  Answer: '.($el['answer'] ?? 'none')."\n";
        echo '  Options: '.($el['options'] ?? 'none')."\n";
    }
}
