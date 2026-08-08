<?php

use App\Models\ExamTemplate;
use Illuminate\Contracts\Console\Kernel;

require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Kernel::class)->bootstrap();

$ts = ExamTemplate::orderBy('id')->get(['id', 'title', 'level', 'exam_provider', 'is_template', 'is_free', 'is_published']);
foreach ($ts as $t) {
    echo "#{$t->id} [{$t->level}] {$t->title} provider={$t->exam_provider} is_template={$t->is_template} free={$t->is_free} pub={$t->is_published}\n";
}
