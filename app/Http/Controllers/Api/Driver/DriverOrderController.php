<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Orders;
use Illuminate\Http\Request;

class DriverOrderController extends Controller
{
    public function getAvailableOrders(Request $request)
    {
        $query = Orders::where('status', 'pending')
            ->whereNull('driver_id')
            ->orderBy('created_at', 'desc');

        $role = $request->user()->role;

        if ($role === 'delivery_driver') {
            $query->where(function ($q) {
                $q->whereNull('order_type')->orWhere('order_type', 'custom');
            });
        } elseif ($role === 'water_tanker_owner') {
            $query->where('order_type', 'water_tanker');
        }

        return response()->json([
            'success' => true,
            'orders' => $query->get(),
        ]);
    }

    /**
     * لوحة صاحب الصهريج: طلبات بلا سائق + مهامي النشطة.
     */
    public function waterTankerTasks(Request $request)
    {
        if ($request->user()->role !== 'water_tanker_owner') {
            return response()->json(['message' => 'غير مصرح'], 403);
        }

        $uid = $request->user()->id;

        $openOrders = Orders::where('order_type', 'water_tanker')
            ->where('status', 'pending')
            ->whereNull('driver_id')
            ->orderBy('created_at', 'desc')
            ->get();

        $myJobs = Orders::where('order_type', 'water_tanker')
            ->where('driver_id', $uid)
            ->whereIn('status', ['accepted', 'on_way', 'delivered'])
            ->orderBy('updated_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'open_orders' => $openOrders,
            'my_jobs' => $myJobs,
        ]);
    }

    public function acceptOrder(Request $request, $id)
    {
        $order = Orders::findOrFail($id);

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'عذراً، هذا الطلب لم يعد متاحاً'], 400);
        }

        $user = $request->user();

        if ($order->order_type === 'water_tanker') {
            if ($user->role !== 'water_tanker_owner') {
                return response()->json(['message' => 'هذا الطلب مخصص لمالكي الصهاريج'], 403);
            }
            $request->validate([
                'items_price' => 'required|numeric|min:0',
                'delivery_fee' => 'nullable|numeric|min:0',
            ]);
            $items = (float) $request->items_price;
            $fee = (float) ($request->delivery_fee ?? 0);

            $order->update([
                'driver_id' => $user->id,
                'status' => 'accepted',
                'items_price' => $items,
                'delivery_fee' => $fee,
                'total_price' => $items + $fee,
            ]);
        } else {
            if ($user->role !== 'delivery_driver') {
                return response()->json(['message' => 'غير مصرح بقبول هذا النوع من الطلبات'], 403);
            }
            $order->update([
                'driver_id' => $user->id,
                'status' => 'accepted',
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'تم قبول الطلب',
            'order' => $order->fresh(),
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Orders::where('id', $id)
            ->where('driver_id', $request->user()->id)
            ->firstOrFail();

        $request->validate(['status' => 'required|in:on_way,delivered']);

        $order->update(['status' => $request->status]);

        return response()->json(['success' => true, 'message' => 'تم تحديث حالة الطلب']);
    }

    public function updateDriverLocation(Request $request, $id)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $order = Orders::where('id', $id)
            ->where('driver_id', $request->user()->id)
            ->firstOrFail();

        $order->update([
            'driver_last_lat' => (string) $request->latitude,
            'driver_last_lng' => (string) $request->longitude,
        ]);

        return response()->json(['success' => true]);
    }
}
