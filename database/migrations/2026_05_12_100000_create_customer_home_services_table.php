<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_home_services', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 40)->unique();
            $table->string('title', 120);
            $table->string('subtitle', 500);
            $table->string('icon_key', 24);
            $table->string('accent_key', 24);
            $table->string('route_segment', 120);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->boolean('is_enabled')->default(true);
            $table->string('badge_label', 40)->nullable();
            $table->string('disabled_message', 500)->nullable();
            $table->timestamps();
        });

        $now = now();

        DB::table('customer_home_services')->insert([
            [
                'slug' => 'custom',
                'title' => 'جيبلي معك',
                'subtitle' => 'وصف · صورة · ميزانية · عنوان نصي + خريطة',
                'icon_key' => 'send',
                'accent_key' => 'blue',
                'route_segment' => '/services/custom-order',
                'sort_order' => 10,
                'is_enabled' => true,
                'badge_label' => null,
                'disabled_message' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'taxi',
                'title' => 'تكسي',
                'subtitle' => 'انطلاق ووصول — نص وخريطة لكل نقطة',
                'icon_key' => 'car',
                'accent_key' => 'rose',
                'route_segment' => '/services/taxi-order',
                'sort_order' => 20,
                'is_enabled' => true,
                'badge_label' => null,
                'disabled_message' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'water_tanker',
                'title' => 'صهريج مياه',
                'subtitle' => 'سعة الصهريج + موقع التعبئة بدقة',
                'icon_key' => 'droplets',
                'accent_key' => 'cyan',
                'route_segment' => '/services/water-tanker-order',
                'sort_order' => 30,
                'is_enabled' => true,
                'badge_label' => null,
                'disabled_message' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'workshop',
                'title' => 'ورشات',
                'subtitle' => 'فني حسب التخصص + وصف العطل + الموقع',
                'icon_key' => 'hammer',
                'accent_key' => 'emerald',
                'route_segment' => '/services/workshop-order',
                'sort_order' => 40,
                'is_enabled' => true,
                'badge_label' => null,
                'disabled_message' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_home_services');
    }
};
