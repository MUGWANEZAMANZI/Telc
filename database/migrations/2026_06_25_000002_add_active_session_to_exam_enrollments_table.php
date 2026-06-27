<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('exam_enrollments', function (Blueprint $table) {
            $table->json('active_answers')->nullable()->after('payment_status');
            $table->integer('remaining_seconds')->nullable()->after('active_answers');
            $table->integer('active_section')->default(0)->after('remaining_seconds');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('exam_enrollments', function (Blueprint $table) {
            $table->dropColumn(['active_answers', 'remaining_seconds', 'active_section']);
        });
    }
};
