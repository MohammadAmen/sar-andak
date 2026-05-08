<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('super_admins', function (Blueprint $table) {
            $table->json('provider_scope')->nullable()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('super_admins', function (Blueprint $table) {
            $table->dropColumn('provider_scope');
        });
    }
};
