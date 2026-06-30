<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('exam_enrollments', function (Blueprint $table) {
            $table->string('paypal_order_id')->nullable()->after('receipt_path');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('paypal_subscription_id')->nullable()->after('stripe_subscription_id');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn('paypal_subscription_id');
        });

        Schema::table('exam_enrollments', function (Blueprint $table) {
            $table->dropColumn('paypal_order_id');
        });
    }
};
