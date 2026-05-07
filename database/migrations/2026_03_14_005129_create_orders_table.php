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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users');
            $table->foreignId('driver_id')->nullable()->constrained('users');
            $table->foreignId('store_id')->nullable()->constrained();

            $table->text('order_text')->nullable();
            $table->enum('status', ['pending', 'accepted', 'picking_up', 'on_way', 'delivered', 'cancelled'])->default('pending');

            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('items_price', 10, 2)->default(0);
            $table->decimal('total_price', 10, 2)->default(0);

            $table->text('delivery_location_text');
            $table->string('delivery_lat')->nullable();
            $table->string('delivery_lng')->nullable();

            $table->string('verification_code', 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
