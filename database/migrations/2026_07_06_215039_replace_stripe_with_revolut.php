<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Add new columns to subscriptions (alongside old ones)
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->string('provider', 20)->nullable()->after('plan_id');
            $table->string('provider_order_id')->nullable()->after('provider');
            $table->integer('duration_months')->nullable()->after('provider_order_id');
            $table->timestamp('starts_at')->nullable()->after('duration_months');
            $table->decimal('price_paid', 10, 2)->nullable()->after('starts_at');
            $table->renameColumn('stripe_status', 'status');
        });

        // 2. Migrate data from old columns to new
        DB::table('subscriptions')
            ->whereNotNull('stripe_id')
            ->update([
                'provider_order_id' => DB::raw('stripe_id'),
                'provider' => DB::raw("CASE WHEN stripe_id LIKE 'paypal_%' THEN 'paypal' ELSE NULL END"),
                'starts_at' => DB::raw('COALESCE(created_at, CURRENT_TIMESTAMP)'),
            ]);

        // 3. Drop unused columns from other tables (safe operations)
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('stripe_price_id');
        });

        Schema::table('exam_templates', function (Blueprint $table) {
            $table->dropColumn('stripe_price_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_stripe_id_index');
            $table->dropColumn(['stripe_id', 'pm_type', 'pm_last_four', 'trial_ends_at', 'stripe_customer_id']);
        });

        // 4. Drop unused stripe_id and stripe_price from subscriptions
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropIndex('subscriptions_stripe_id_unique');
            $table->dropColumn('stripe_id');
            $table->dropColumn('stripe_price');
        });

        Schema::dropIfExists('subscription_items');
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropColumn(['provider', 'provider_order_id', 'duration_months', 'starts_at', 'price_paid']);
            $table->renameColumn('status', 'stripe_status');
        });
    }
};
