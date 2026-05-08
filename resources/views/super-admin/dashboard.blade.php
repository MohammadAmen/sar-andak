@extends('super-admin.layout')

@section('title', config('app.name', 'Sar Andak').' - لوحة السوبر أدمن')
@section('subtitle', 'لوحة السوبر أدمن')

@section('content')
    <div class="row g-3 g-lg-4 align-items-stretch">
        <div class="col-12 col-lg-7">
            <div class="card-pro bg-white h-100">
                <div class="p-4 p-lg-5">
                    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                        <div>
                            <h1 class="h4 mb-2">مرحباً</h1>
                            <p class="muted mb-0">تم تسجيل دخولك كسوبر أدمن. يمكنك إدارة النظام من هنا.</p>
                        </div>
                        <a class="btn btn-outline-secondary btn-soft" href="{{ route('super-admin.dashboard') }}">
                            تحديث الصفحة
                        </a>
                    </div>

                    <hr class="my-4">

                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="metric p-3 p-lg-4">
                                <div class="helper small mb-1">الحالة</div>
                                <div class="fw-bold">جلسة نشطة</div>
                                <div class="muted small mt-1">تم التحقق من الصلاحية عبر الجلسة.</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="metric p-3 p-lg-4">
                                <div class="helper small mb-1">الدور</div>
                                <div class="fw-bold">Super Admin</div>
                                <div class="muted small mt-1">وصول كامل للوحة الإدارة.</div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <div class="helper small mb-2">اختصارات</div>
                        <div class="d-flex flex-wrap gap-2">
                            <a class="chip text-decoration-none" href="{{ route('super-admin.users.index') }}">المستخدمون</a>
                            @php
                                use App\Support\ProviderStaffScope;
                                $dashNavTypes = ProviderStaffScope::allowedTypesFor($superAdmin ?? null);
                                if ($dashNavTypes === null) {
                                    $dashNavTypes = config('provider_ops.provider_types', []);
                                }
                            @endphp
                            @foreach($dashNavTypes as $pt)
                                @php($dm = config('provider_ops.nav.'.$pt, ['label' => $pt]))
                                <a class="chip text-decoration-none" href="{{ route('super-admin.providers.index', ['type' => $pt]) }}">{{ $dm['label'] }}</a>
                            @endforeach
                        </div>
                        <div class="muted small mt-2">هاي صفحات إدارة جاهزة كبداية. الخطوة التالية: إضافة “إجراءات” (توثيق/تعطيل/تغيير دور) لكل مزوّد.</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card-pro bg-white h-100">
                <div class="p-4 p-lg-5">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="fw-bold">نظرة سريعة</div>
                        <span class="chip">اليوم</span>
                    </div>

                    <div class="mt-4">
                        <div class="metric p-3 p-lg-4 mb-3">
                            <div class="helper small mb-1">آخر دخول</div>
                            <div class="fw-bold">{{ now()->format('Y-m-d H:i') }}</div>
                        </div>

                        <div class="metric p-3 p-lg-4">
                            <div class="helper small mb-2">إحصائيات</div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="chip w-100 justify-content-between">
                                        <span>المستخدمون</span>
                                        <span class="fw-bold">{{ $stats['users_total'] ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="chip w-100 justify-content-between">
                                        <span>نشطون</span>
                                        <span class="fw-bold">{{ $stats['users_active'] ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="chip w-100 justify-content-between">
                                        <span>الطلبات</span>
                                        <span class="fw-bold">{{ $stats['orders_total'] ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="chip w-100 justify-content-between">
                                        <span>معلّقة</span>
                                        <span class="fw-bold">{{ $stats['orders_pending'] ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="chip w-100 justify-content-between">
                                        <span>دليفري</span>
                                        <span class="fw-bold">{{ $stats['providers_delivery'] ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="chip w-100 justify-content-between">
                                        <span>تكسي</span>
                                        <span class="fw-bold">{{ $stats['providers_taxi'] ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="chip w-100 justify-content-between">
                                        <span>صهاريج</span>
                                        <span class="fw-bold">{{ $stats['providers_water_tanker'] ?? 0 }}</span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="chip w-100 justify-content-between">
                                        <span>ورشات</span>
                                        <span class="fw-bold">{{ $stats['providers_workshop'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center helper mt-4 small">
                        © {{ date('Y') }} {{ config('app.name', 'Sar Andak') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
