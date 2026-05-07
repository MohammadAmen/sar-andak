@extends('super-admin.layout')

@section('title', config('app.name', 'Sar Andak').' - إدارة المستخدمين')
@section('subtitle', 'إدارة المستخدمين')

@section('content')
    <div class="card-pro bg-white">
        <div class="p-4 p-lg-5">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div>
                    <h1 class="h4 mb-2">إدارة المستخدمين</h1>
                    <p class="muted mb-0">بحث، فلترة، واستعراض المستخدمين المسجلين.</p>
                </div>
            </div>

            <form class="row g-2 g-lg-3 mt-3" method="GET" action="{{ route('super-admin.users.index') }}">
                <div class="col-12 col-lg-5">
                    <input class="form-control" name="q" value="{{ $q }}" placeholder="ابحث بالاسم أو رقم الجوال">
                </div>
                <div class="col-6 col-lg-3">
                    <select class="form-select" name="role">
                        <option value="">كل الأدوار</option>
                        <option value="customer" @selected($role==='customer')>Customer</option>
                        <option value="driver" @selected($role==='driver')>Driver</option>
                        <option value="shop_owner" @selected($role==='shop_owner')>Shop Owner</option>
                        <option value="admin" @selected($role==='admin')>Admin</option>
                    </select>
                </div>
                <div class="col-6 col-lg-2">
                    <select class="form-select" name="status">
                        <option value="">كل الحالات</option>
                        <option value="active" @selected($status==='active')>نشط</option>
                        <option value="inactive" @selected($status==='inactive')>غير نشط</option>
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
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="text-secondary">{{ $user->id }}</td>
                            <td class="fw-semibold">{{ $user->name }}</td>
                            <td dir="ltr" class="text-nowrap">{{ $user->phone }}</td>
                            <td><span class="chip">{{ $user->role }}</span></td>
                            <td>
                                @if($user->is_active)
                                    <span class="chip" style="border-color: rgba(16,185,129,.25); background: rgba(16,185,129,.10); color: rgba(17,24,39,.82);">نشط</span>
                                @else
                                    <span class="chip" style="border-color: rgba(239,68,68,.25); background: rgba(239,68,68,.10); color: rgba(17,24,39,.82);">غير نشط</span>
                                @endif
                            </td>
                            <td class="text-secondary text-nowrap">{{ optional($user->created_at)->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-secondary py-5">لا يوجد نتائج.</td>
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

