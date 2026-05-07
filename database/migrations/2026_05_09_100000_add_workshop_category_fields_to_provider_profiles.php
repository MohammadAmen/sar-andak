<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('provider_profiles', function (Blueprint $table) {
            $table->json('workshop_category_keys')->nullable()->after('workshop_skill_other');
            $table->string('workshop_neighborhood', 120)->nullable()->after('workshop_category_keys');
            $table->string('workshop_short_pitch', 280)->nullable()->after('workshop_neighborhood');
            $table->unsignedTinyInteger('workshop_years_experience')->nullable()->after('workshop_short_pitch');
            $table->boolean('workshop_home_visit')->default(true)->after('workshop_years_experience');
        });
    }

    public function down(): void
    {
        Schema::table('provider_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'workshop_category_keys',
                'workshop_neighborhood',
                'workshop_short_pitch',
                'workshop_years_experience',
                'workshop_home_visit',
            ]);
        });
    }
};
