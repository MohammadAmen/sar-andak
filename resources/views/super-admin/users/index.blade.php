@extends('super-admin.layout')

@section('title', config('app.name', 'Sar Andak').' - إدارة المستخدمين')
@section('subtitle', 'إدارة المستخدمين')

@section('content')
    <div class="card-pro bg-white">
        <div class="p-4 p-lg-5">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div>
                    <h1 class="h4 mb-2">إدارة المستخدمين</h1>
                    <p class="muted mb-0">عملاء التطبيق، السائقون، والفريق — بحث، إحصاءات سريعة، وملف متابعة لكل مستخدم.</p>
                </div>
            </div>

            <form class="row g-2 g-lg-3 mt-3" method="GET" action="{{ route('super-admin.users.index') }}">
                <div class="col-12 col-lg-5">
                    <input class="form-control" name="q" value="{{ $q }}" placeholder="ابحث بالاسم أو رقم الجوال">
                </div>
                <div class="col-6 col-lg-3">
                    <select class="form-select" name="role">
                        <option value="">كل الأدوار</option>
                        <option value="customer" @selected($role==='customer')>عميل التطبيق</option>
                        <option value="driver" @selected($role==='driver')>سائق / مزوّد توصيل</option>
                        <option value="shop_owner" @selected($role==='shop_owner')>صاحب متجر</option>
                        <option value="admin" @selected($role==='admin')>مسؤول نظام</option>
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <select class="form-select" name="status">
                        <option value="">كل الحالات</option>
                        <option value="active" @selected($status==='active')>نشط</option>
                        <option value="inactive" @selected($status==='inactive')>موقوف</option>
                    </select>
                </div>
                <div class="col-12 col-lg-2 d-grid">
                    <button class="btn btn-outline-secondary btn-soft" type="submit">تطبيق</button>
                </div>
            </form>

            <hr class="my-4">

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr class="helper">
                        <th>#</th>
                        <th>الاسم</th>
                        <th>رقم الجوال</th>
                        <th>الدور</th>
                        <th>طلبات (عميل)</th>
                        <th>مهام (سائق)</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                        <th>إجراءات</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        @php
                            $roleLabel = match ($user->role) {
                                'customer' => 'عميل',
                                'driver' => 'سائق',
                                'shop_owner' => 'متجر',
                                'admin' => 'مسؤول',
                                default => $user->role,
                            };
                        @endphp
                        <tr>
                            <td class="text-secondary">{{ $user->id }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td dir="ltr" class="text-nowrap">{{ $user->phone }}</td>
                            <td><span class="chip">{{ $roleLabel }}</span></td>
                            <td><span class="chip">{{ (int) $user->customer_orders_count }}</span></td>
                            <td><span class="chip">{{ (int) $user->driver_orders_count }}</span></td>
                            <td>
                                @if($user->is_active)
                                    <span class="chip" style="border-color: rgba(16,185,129,.25); background: rgba(16,185,129,.10); color: rgba(17,24,39,.82);">نشط</span>
                                @else
                                    <span class="chip" style="border-color: rgba(239,68,68,.25); background: rgba(239,68,68,.10); color: rgba(17,24,39,.82);">موقوف</span>
                                @endif
                            </td>
                            <td class="text-secondary text-nowrap">{{ optional($user->created_at)->format('Y-m-d') }}</td>
                            <td class="text-nowrap">
                                <a class="btn btn-sm btn-outline-secondary btn-soft icon-btn"
                                   href="{{ route('super-admin.users.show', array_merge(['user' => $user], array_filter(['back_q' => $q, 'back_role' => $role, 'back_status' => $status]))) }}"
                                   data-bs-toggle="tooltip" title="ملف المتابعة" aria-label="ملف المتابعة">
                                    <i class="bi bi-person-lines-fill"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center text-secondary py-5">لا يوجد نتائج.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                <div class="helper small">
                    إجمالي النتائج: <span class="fw-semibold">{{ $users->total() }}</span>
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
