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
        Schema::create('provider_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Which sector / provider category
            $table->enum('provider_type', ['delivery', 'taxi', 'water_tanker', 'workshop']);

            // Basic verification / activation controls for admin dashboard
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();

            // Shared “professional” fields (expand later per sector)
            $table->string('full_name')->nullable();
            $table->string('national_id')->nullable();
            $table->string('license_no')->nullable();
            $table->date('license_expiry')->nullable();

            // Delivery / Taxi vehicles, etc.
            $table->enum('vehicle_type', ['motorcycle', 'bicycle', 'car', 'van', 'truck'])->nullable();
            $table->string('vehicle_plate')->nullable();
            $table->string('vehicle_color')->nullable();

            // Workshop specialization
            $table->enum('workshop_skill', ['electricity', 'plumbing', 'carpentry', 'other'])->nullable();
            $table->string('workshop_skill_other')->nullable();

            // Water tanker specifics
            $table->integer('water_capacity_liters')->nullable();

            $table->text('notes')->nullable();
            $table->unique(['user_id', 'provider_type']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_profiles');
    }
};
