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
            $table->decimal('commission_rate_percent', 5, 2)->default(0);

            // Monthly deposit (guarantee)
            $table->decimal('monthly_deposit_amount', 10, 2)->default(0);
            $table->unsignedInteger('deposit_period_months')->default(1);
            $table->timestamp('deposit_starts_at')->nullable();
            $table->timestamp('deposit_ends_at')->nullable();
            $table->enum('deposit_status', ['active', 'expired', 'paused'])->default('active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'commission_rate_percent',
                'monthly_deposit_amount',
                'deposit_period_months',
                'deposit_starts_at',
                'deposit_ends_at',
                'deposit_status',
            ]);
        });
    }
};
