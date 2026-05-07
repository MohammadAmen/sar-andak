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
        Schema::table('provider_profiles', function (Blueprint $table) {
            $table->decimal('subscription_amount', 10, 2)->default(0);
            $table->unsignedInteger('subscription_period_months')->default(1);
            $table->timestamp('subscription_starts_at')->nullable();
            $table->timestamp('subscription_ends_at')->nullable();
            $table->enum('subscription_status', ['active', 'expired', 'paused'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'subscription_amount',
                'subscription_period_months',
                'subscription_starts_at',
                'subscription_ends_at',
                'subscription_status',
            ]);
        });
    }
};
