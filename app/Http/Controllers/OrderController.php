<?php

namespace App\Http\Controllers;

use App\Models\Orders;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    public function index(Request $request)
    {
        // جلب طلبات المستخدم الحالي مرتبة من الأحدث للأقدم
        $orders = Orders::where('customer_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'orders' => $orders
        ]);
    }

   public function storeCustomOrder(Request $request)
{
    $request->validate([
        'order_text' => 'required|string|min:10',
        'order_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120', // التحقق من الصورة (حد أقصى 5 ميجا)
    ]);

    $order = new Orders();
    $order->customer_id = $request->user()->id;
    $order->order_type = 'custom';
    $order->order_text = $request->order_text;
    $order->status = 'pending';

    if ($request->hasFile('order_image')) {
        $path = $request->file('order_image')->store('orders', 'public');
        $order->order_image = $path;
    }

    $budget = $request->input('items_price', $request->input('estimated_budget', 0));
    $order->items_price = is_numeric($budget) ? (float) $budget : 0;

    if ($request->filled('delivery_location_text')) {
        $order->delivery_location_text = $request->delivery_location_text;
    } else {
        $order->delivery_location_text = $request->user()->address_details ?? 'طفس - عنوان غير محدد';
    }
    if ($request->filled('delivery_latitude')) {
        $order->delivery_lat = (string) $request->delivery_latitude;
    }
    if ($request->filled('delivery_longitude')) {
        $order->delivery_lng = (string) $request->delivery_longitude;
    }

     $order->verification_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

    $order->save();

    return response()->json([
        'message' => 'تم استلام طلبك بنجاح',
        'order_id' => $order->id,
        'image_url' => $order->order_image ? asset('storage/' . $order->order_image) : null
    ], 201);
}

    public function storeWaterTankerOrder(Request $request)
    {
        $request->validate([
            'capacity' => 'required|string|max:100',
            'delivery_location_text' => 'required|string|min:3',
            'delivery_latitude' => 'required|numeric',
            'delivery_longitude' => 'required|numeric',
        ]);

        $order = new Orders();
        $order->customer_id = $request->user()->id;
        $order->order_type = 'water_tanker';
        $order->order_text = 'طلب صهريج مياه — السعة: '.$request->capacity;
        $order->status = 'pending';
        $order->items_price = 0;
        $order->delivery_location_text = $request->delivery_location_text;
        $order->delivery_lat = (string) $request->delivery_latitude;
        $order->delivery_lng = (string) $request->delivery_longitude;
        $order->verification_code = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        $order->save();

        return response()->json([
            'message' => 'تم استلام طلب الصهريج بنجاح',
            'order_id' => $order->id,
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $order = Orders::with('driver')->find($id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'الطلب غير موجود',
            ], 404);
        }

        $uid = $request->user()->id;
        $allowed = $order->customer_id === $uid || $order->driver_id === $uid;

        if (!$allowed) {
            return response()->json([
                'success' => false,
                'message' => 'الطلب غير موجود',
            ], 404);
        }

        $toFloat = static fn (?string $v): ?float => $v !== null && $v !== '' ? (float) $v : null;

        return response()->json([
            'success' => true,
            'order' => [
                'id' => $order->id,
                'driver_id' => $order->driver_id,
                'order_type' => $order->order_type ?? 'custom',
                'order_text' => $order->order_text,
                'status' => $order->status,
                'items_price' => number_format($order->items_price, 0, '.', ','),
                'order_image' => $order->order_image ? $order->order_image : null,
                'delivery_fee' => number_format($order->delivery_fee, 0, '.', ','),
                'total_price' => number_format($order->items_price + $order->delivery_fee, 0, '.', ','),
                'delivery_location_text' => $order->delivery_location_text,
                'delivery_lat' => $toFloat($order->delivery_lat),
                'delivery_lng' => $toFloat($order->delivery_lng),
                'driver_last_lat' => $toFloat($order->driver_last_lat),
                'driver_last_lng' => $toFloat($order->driver_last_lng),
                'created_at' => $order->created_at,
                'driver' => $order->driver ? [
                    'id' => $order->driver->id,
                    'name' => $order->driver->name,
                    'phone' => $order->driver->phone,
                ] : null,
            ],
        ]);
    }
}