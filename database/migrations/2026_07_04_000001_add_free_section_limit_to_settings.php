<?php

use App\Models\Setting;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Setting::query()->updateOrCreate(
            ['key' => 'free_section_limit'],
            ['value' => '4'],
        );
    }

    public function down(): void
    {
        Setting::where('key', 'free_section_limit')->delete();
    }
};
