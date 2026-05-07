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
            $table->boolean('taxi_is_metered')->default(true);
            $table->decimal('taxi_base_fare', 10, 2)->default(0);
            $table->decimal('taxi_price_per_km', 10, 2)->default(0);
            $table->decimal('taxi_price_per_minute', 10, 2)->default(0);
            $table->decimal('taxi_min_fare', 10, 2)->default(0);
            $table->string('taxi_coverage_areas')->nullable(); // e.g. "Tafas, ريف درعا"
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'taxi_is_metered',
                'taxi_base_fare',
                'taxi_price_per_km',
                'taxi_price_per_minute',
                'taxi_min_fare',
                'taxi_coverage_areas',
            ]);
        });
    }
};
