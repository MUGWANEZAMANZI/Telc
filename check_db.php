<?php

use App\Models\ExamTemplate;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$t = ExamTemplate::where('title', 'telc Deutsch C1')->first();
$sections = collect($t->sections);
$writingSection = $sections->where('id', 'c1-writing')->first();
echo json_encode($writingSection, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
