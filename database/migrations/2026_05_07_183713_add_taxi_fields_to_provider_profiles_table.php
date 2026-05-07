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
            $table->string('taxi_car_make')->nullable();
            $table->string('taxi_car_model')->nullable();
            $table->unsignedSmallInteger('taxi_car_year')->nullable();
            $table->unsignedTinyInteger('taxi_seats')->nullable();

            $table->string('taxi_insurance_no')->nullable();
            $table->date('taxi_insurance_expiry')->nullable();

            $table->boolean('taxi_has_ac')->default(true);
            $table->boolean('taxi_allows_smoking')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('provider_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'taxi_car_make',
                'taxi_car_model',
                'taxi_car_year',
                'taxi_seats',
                'taxi_insurance_no',
                'taxi_insurance_expiry',
                'taxi_has_ac',
                'taxi_allows_smoking',
            ]);
        });
    }
};
