<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voice_topics', function (Blueprint $table) {
            $table->string('counter_topic')->nullable()->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('voice_topics', function (Blueprint $table) {
            $table->dropColumn('counter_topic');
        });
    }
};
