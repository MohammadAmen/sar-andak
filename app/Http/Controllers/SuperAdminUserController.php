<?php

namespace App\Http\Controllers;

use App\Models\AdminUserAuditLog;
use App\Models\Orders;
use App\Models\User;
use App\Support\AdminUserAudit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SuperAdminUserController extends Controller
{
    private const ORDER_STATUSES = ['pending', 'accepted', 'picking_up', 'on_way', 'delivered', 'cancelled'];

    private const ORDER_TYPES = ['custom', 'water_tanker'];

    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $role = trim((string) $request->query('role', ''));
        $status = trim((string) $request->query('status', ''));

        $users = User::query()
            ->withCount([
                'customerOrders',
                'driverOrders',
            ])
            ->when($q !== '', function ($query) use ($q) {
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            })
            ->when($role !== '', fn ($query) => $query->where('role', $role))
            ->when($status !== '', function ($query) use ($status) {
                if ($status === 'active') {
                    $query->where('is_active', true);
                } elseif ($status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('super-admin.users.index', [
            'users' => $users,
            'q' => $q,
            'role' => $role,
            'status' => $status,
        ]);
    }

    public function show(Request $request, User $user)
    {
        $filters = $this->validatedOrderFilters($request);

        $user->load('area');

        $activeTokens = $user->tokens()->orderByDesc('created_at')->limit(5)->get(['id', 'name', 'last_used_at', 'created_at']);

        $customerStats = Orders::query()
            ->where('customer_id', $user->id)
            ->selectRaw('count(*) as total')
            ->selectRaw("sum(case when status = 'pending' then 1 else 0 end) as pending_count")
            ->selectRaw("sum(case when status = 'accepted' then 1 else 0 end) as accepted_count")
            ->selectRaw("sum(case when status in ('picking_up','on_way') then 1 else 0 end) as in_progress_count")
            ->selectRaw("sum(case when status = 'delivered' then 1 else 0 end) as delivered_count")
            ->selectRaw("sum(case when status = 'cancelled' then 1 else 0 end) as cancelled_count")
            ->first();

        $driverStats = Orders::query()
            ->where('driver_id', $user->id)
            ->selectRaw('count(*) as total')
            ->selectRaw("sum(case when status = 'delivered' then 1 else 0 end) as delivered_count")
            ->selectRaw("sum(case when status = 'cancelled' then 1 else 0 end) as cancelled_count")
            ->first();

        $ordersQuery = Orders::query()
            ->where('customer_id', $user->id)
            ->with(['driver:id,name,phone']);

        $this->applyCustomerOrderFilters($ordersQuery, $filters);

        $appendKeys = array_merge(
            array_keys($filters),
            ['back_q', 'back_role', 'back_status']
        );

        $orders = $ordersQuery
            ->orderByDesc('id')
            ->paginate(15)
            ->appends($request->only($appendKeys));

        $driverOrders = Orders::query()
            ->where('driver_id', $user->id)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        $auditLogs = AdminUserAuditLog::query()
            ->where('target_user_id', $user->id)
            ->with('superAdmin:id,phone')
            ->orderByDesc('id')
            ->paginate(10)
            ->appends($request->only($appendKeys));

        return view('super-admin.users.show', [
            'user' => $user,
            'customerStats' => $customerStats,
            'driverStats' => $driverStats,
            'orders' => $orders,
            'driverOrders' => $driverOrders,
            'activeTokens' => $activeTokens,
            'tokensTotal' => $user->tokens()->count(),
            'orderFilters' => $filters,
            'auditLogs' => $auditLogs,
        ]);
    }

    public function exportCustomerOrders(Request $request, User $user): StreamedResponse
    {
        $filters = $this->validatedOrderFilters($request);

        AdminUserAudit::log($user->id, 'user.orders_exported', [
            'filters' => array_filter($filters),
        ], $request);

        $query = Orders::query()
            ->where('customer_id', $user->id);

        $this->applyCustomerOrderFilters($query, $filters);

        $filename = sprintf('user-%d-orders-%s.csv', $user->id, now()->format('Y-m-d_His'));

        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, [
                'id',
                'order_type',
                'status',
                'items_price',
                'delivery_fee',
                'total',
                'delivery_location_text',
                'created_at',
                'driver_id',
                'driver_phone',
            ]);

            foreach ($query->clone()->with('driver:id,phone')->orderByDesc('id')->cursor() as $order) {
                fputcsv($out, [
                    $order->id,
                    $order->order_type,
                    $order->status,
                    $order->items_price,
                    $order->delivery_fee,
                    (float) $order->items_price + (float) $order->delivery_fee,
                    $order->delivery_location_text,
                    optional($order->created_at)->format('Y-m-d H:i:s'),
                    $order->driver_id,
                    $order->driver?->phone,
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function updateNotes(Request $request, User $user)
    {
        $validated = $request->validate([
            'admin_notes' => ['nullable', 'string', 'max:10000'],
        ]);

        $before = $user->admin_notes;

        $user->update([
            'admin_notes' => $validated['admin_notes'] ?? null,
        ]);

        AdminUserAudit::log($user->id, 'user.notes_updated', [
            'changed' => ($before !== ($validated['admin_notes'] ?? null)),
            'length_after' => strlen($validated['admin_notes'] ?? ''),
        ], $request);

        return back()->with('toast', ['type' => 'success', 'message' => 'تم حفظ ملاحظات المتابعة']);
    }

    public function toggleActive(Request $request, User $user)
    {
        $wasActive = (bool) $user->is_active;

        DB::transaction(function () use ($user) {
            $user->is_active = ! (bool) $user->is_active;
            $user->save();
            if (! $user->is_active) {
                $user->tokens()->delete();
            }
        });

        $user->refresh();

        AdminUserAudit::log($user->id, $user->is_active ? 'user.account_activated' : 'user.account_deactivated', [
            'was_active' => $wasActive,
            'is_active' => (bool) $user->is_active,
        ], $request);

        $msg = $user->is_active ? 'تم تفعيل المستخدم. يمكنه تسجيل الدخول من التطبيق من جديد.' : 'تم إيقاف المستخدم وإلغاء جلسات التطبيق (التوكنات).';

        return back()->with('toast', ['type' => 'success', 'message' => $msg]);
    }

    /**
     * @return array{order_status: ?string, order_type: ?string, date_from: ?string, date_to: ?string}
     */
    private function validatedOrderFilters(Request $request): array
    {
        $validated = $request->validate([
            'order_status' => ['nullable', 'string', Rule::in(self::ORDER_STATUSES)],
            'order_type' => ['nullable', 'string', Rule::in(self::ORDER_TYPES)],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date'],
        ]);

        if (! empty($validated['date_from']) && ! empty($validated['date_to'])
            && $validated['date_to'] < $validated['date_from']) {
            throw ValidationException::withMessages([
                'date_to' => 'تاريخ «إلى» يجب أن يكون بعد «من» أو مساوياً له.',
            ]);
        }

        return [
            'order_status' => $validated['order_status'] ?? null,
            'order_type' => $validated['order_type'] ?? null,
            'date_from' => $validated['date_from'] ?? null,
            'date_to' => $validated['date_to'] ?? null,
        ];
    }

    /**
     * @param  array{order_status: ?string, order_type: ?string, date_from: ?string, date_to: ?string}  $filters
     */
    private function applyCustomerOrderFilters($query, array $filters): void
    {
        if (! empty($filters['order_status'])) {
            $query->where('status', $filters['order_status']);
        }
        if (! empty($filters['order_type'])) {
            $query->where('order_type', $filters['order_type']);
        }
        if (! empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }
        if (! empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }
    }
}
