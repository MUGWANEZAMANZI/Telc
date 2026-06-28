<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_templates', function (Blueprint $table) {
            $table->string('exam_provider', 32)->default('telc');
        });
    }

    public function down(): void
    {
        Schema::table('exam_templates', function (Blueprint $table) {
            $table->dropColumn('exam_provider');
        });
    }
};
