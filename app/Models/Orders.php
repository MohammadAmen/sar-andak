<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;

     protected $table = 'orders';

    protected $fillable = [
        'customer_id',
        'driver_id',
        'store_id',
        'order_type',
        'order_text',
        'status',
        'delivery_fee',
        'items_price',
        'total_price',
        'delivery_location_text',
        'delivery_lat',
        'delivery_lng',
        'driver_last_lat',
        'driver_last_lng',
        'verification_code',
        'order_image',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
}