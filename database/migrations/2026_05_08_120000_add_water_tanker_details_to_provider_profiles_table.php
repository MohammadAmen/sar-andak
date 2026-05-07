<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provider_profiles', function (Blueprint $table) {
            $table->boolean('water_has_pump')->default(false)->after('water_capacity_liters');
            $table->unsignedSmallInteger('water_hose_length_m')->nullable()->after('water_has_pump');
            $table->string('water_tank_material', 32)->nullable()->after('water_hose_length_m');
            $table->string('water_pricing_mode', 24)->default('per_tank')->after('water_tank_material');
            $table->decimal('water_price_per_tank', 12, 2)->nullable()->after('water_pricing_mode');
            $table->decimal('water_price_per_liter', 12, 4)->nullable()->after('water_price_per_tank');
            $table->unsignedInteger('water_min_order_liters')->nullable()->after('water_price_per_liter');
            $table->json('water_service_area_keys')->nullable()->after('water_min_order_liters');
            $table->boolean('water_potable_declared')->default(false)->after('water_service_area_keys');
        });
    }

    public function down(): void
    {
        Schema::table('provider_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'water_has_pump',
                'water_hose_length_m',
                'water_tank_material',
                'water_pricing_mode',
                'water_price_per_tank',
                'water_price_per_liter',
                'water_min_order_liters',
                'water_service_area_keys',
                'water_potable_declared',
            ]);
        });
    }
};
