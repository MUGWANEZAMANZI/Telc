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
            $table->string('transaction_id')->nullable()->after('payment_status');
            $table->string('receipt_path')->nullable()->after('transaction_id');
        });
    }

    public function down(): void
    {
        Schema::table('exam_enrollments', function (Blueprint $table) {
            $table->dropColumn(['transaction_id', 'receipt_path']);
        });
    }
};
