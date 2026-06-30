<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('plan_id')->nullable()->constrained()->nullOnDelete()->after('user_id');
            $table->string('stripe_subscription_id')->nullable()->after('stripe_id');
        });

        Schema::table('exam_templates', function (Blueprint $table) {
            $table->string('stripe_price_id')->nullable()->after('price');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropForeign(['plan_id']);
            $table->dropColumn(['plan_id', 'stripe_subscription_id']);
        });

        Schema::table('exam_templates', function (Blueprint $table) {
            $table->dropColumn('stripe_price_id');
        });
    }
};
