@extends('super-admin.layout')

@section('title', config('app.name', 'Sar Andak').' - ملف مستخدم')
@section('subtitle', 'متابعة المستخدم والطلبات')
@section('main_class', 'container-fluid')

@php
    $roleLabel = match ($user->role) {
        'customer' => 'عميل التطبيق',
        'driver' => 'سائق / مزوّد توصيل',
        'shop_owner' => 'صاحب متجر',
        'admin' => 'مسؤول نظام',
        default => $user->role,
    };
    $statusLabel = static fn (?string $s): string => match ($s) {
        'pending' => 'بانتظار',
        'accepted' => 'مقبول',
        'picking_up' => 'بالاستلام',
        'on_way' => 'في الطريق',
        'delivered' => 'مُسلّم',
        'cancelled' => 'ملغى',
        default => $s ?? '-',
    };
    $typeLabel = static fn (?string $t): string => match ($t) {
        'custom' => 'طلب مخصص',
        'water_tanker' => 'صهريج مياه',
        default => $t ?? '—',
    };
@endphp

@section('content')
    @php
        $f = $orderFilters ?? [];
    @endphp
    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap mb-3">
        <div>
            <h1 class="h4 mb-1">{{ $user->name }}</h1>
            <div class="muted">متابعة النشاط، الطلبات، والتحكم بالوصول للتطبيق.</div>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            <a class="btn btn-outline-secondary btn-soft icon-btn" href="{{ route('super-admin.users.index', array_filter(['q' => request('back_q'), 'role' => request('back_role'), 'status' => request('back_status')])) }}"
               data-bs-toggle="tooltip" title="رجوع" aria-label="رجوع">
                <i class="bi bi-arrow-right"></i>
            </a>
            <form method="POST" action="{{ route('super-admin.users.toggle-active', $user) }}" class="d-inline" data-bs-toggle="tooltip" title="{{ $user->is_active ? 'إيقاف الحساب وقطع جلسات التطبيق' : 'تفعيل الحساب' }}">
                @csrf
                <button type="submit" class="btn btn-outline-{{ $user->is_active ? 'danger' : 'success' }} btn-soft icon-btn" aria-label="{{ $user->is_active ? 'إيقاف' : 'تفعيل' }}">
                    <i class="bi {{ $user->is_active ? 'bi-slash-circle' : 'bi-check-circle' }}"></i>
                </button>
            </form>
        </div>
    </div>

    <div class="row g-3 g-lg-4">
        <div class="col-12">
            <div class="card-pro bg-white">
                <div class="p-4 p-lg-5">
                    <div class="section-title">
                        <h2 class="h6 mb-0">الهوية</h2>
                        <span class="chip">{{ $roleLabel }}</span>
                    </div>
                    <div class="row g-4 align-items-start mt-2">
                        <div class="col-12 col-lg-auto text-center text-lg-start">
                            @if($user->avatar)
                                <img src="{{ asset('storage/'.$user->avatar) }}" alt="" class="rounded-3 border" style="width: 112px; height: 112px; object-fit: cover;">
                            @else
                                <div class="rounded-3 border d-inline-flex align-items-center justify-content-center bg-light text-secondary" style="width: 112px; height: 112px;">
                                    <i class="bi bi-person fs-1"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-12 col-lg">
                            <div class="row g-3 g-lg-4">
                                <div class="col-6 col-md-4 col-xl-3">
                                    <div class="helper small">الاسم</div>
                                    <div class="fw-semibold">{{ $user->name }}</div>
                                </div>
                                <div class="col-6 col-md-4 col-xl-3">
                                    <div class="helper small">الجوال</div>
                                    <div class="fw-semibold" dir="ltr">{{ $user->phone }}</div>
                                </div>
                                <div class="col-12 col-md-4 col-xl-3">
                                    <div class="helper small">الحالة</div>
                                    @if($user->is_active)
                                        <span class="chip" style="border-color: rgba(16,185,129,.25); background: rgba(16,185,129,.10);">نشط — يستطيع استخدام التطبيق</span>
                                    @else
                                        <span class="chip" style="border-color: rgba(239,68,68,.25); background: rgba(239,68,68,.10);">موقوف — لا يمكن تسجيل الدخول</span>
                                    @endif
                                </div>
                                <div class="col-6 col-md-4 col-xl-3">
                                    <div class="helper small">منطقة التوصيل</div>
                                    <div class="fw-semibold">{{ $user->area?->name ?? '—' }}</div>
                                </div>
                                <div class="col-12 col-md-6 col-xl-4">
                                    <div class="helper small">تفاصيل العنوان</div>
                                    <div class="muted small">{{ $user->address_details ?: '—' }}</div>
                                </div>
                                <div class="col-6 col-md-4 col-xl-3">
                                    <div class="helper small">المدينة</div>
                                    <div>{{ $user->city ?? '—' }}</div>
                                </div>
                                <div class="col-6 col-md-4 col-xl-3">
                                    <div class="helper small">تاريخ التسجيل</div>
                                    <div>{{ optional($user->created_at)->format('Y-m-d H:i') ?? '—' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card-pro bg-white">
                <div class="p-4 p-lg-5">
                    <div class="section-title">
                        <h2 class="h6 mb-0">طلبات كعميل</h2>
                        <span class="chip">إجمالي {{ (int) ($customerStats->total ?? 0) }}</span>
                    </div>
                    <div class="row row-cols-2 row-cols-sm-3 row-cols-lg-5 g-2 g-lg-3 mb-4">
                        @foreach([
                            ['key' => 'pending_count', 'label' => 'بانتظار'],
                            ['key' => 'accepted_count', 'label' => 'مقبول'],
                            ['key' => 'in_progress_count', 'label' => 'قيد التنفيذ'],
                            ['key' => 'delivered_count', 'label' => 'مُسلّم'],
                            ['key' => 'cancelled_count', 'label' => 'ملغى'],
                        ] as $cell)
                            <div class="col">
                                <div class="metric p-3 p-lg-3 h-100">
                                    <div class="helper small">{{ $cell['label'] }}</div>
                                    <div class="fw-bold fs-5">{{ (int) ($customerStats->{$cell['key']} ?? 0) }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <form method="get" action="{{ route('super-admin.users.show', $user) }}" class="row g-2 align-items-end mb-3">
                        @if(request()->filled('back_q'))
                            <input type="hidden" name="back_q" value="{{ request('back_q') }}">
                        @endif
                        @if(request()->filled('back_role'))
                            <input type="hidden" name="back_role" value="{{ request('back_role') }}">
                        @endif
                        @if(request()->filled('back_status'))
                            <input type="hidden" name="back_status" value="{{ request('back_status') }}">
                        @endif
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-2">
                            <label class="form-label small fw-semibold mb-1">حالة الطلب</label>
                            <select class="form-select form-select-sm" name="order_status">
                                <option value="">الكل</option>
                                @foreach(['pending' => 'بانتظار', 'accepted' => 'مقبول', 'picking_up' => 'بالاستلام', 'on_way' => 'في الطريق', 'delivered' => 'مُسلّم', 'cancelled' => 'ملغى'] as $k => $lab)
                                    <option value="{{ $k }}" @selected(($f['order_status'] ?? '') === $k)>{{ $lab }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-sm-6 col-md-6 col-lg-3 col-xl-2">
                            <label class="form-label small fw-semibold mb-1">نوع الطلب</label>
                            <select class="form-select form-select-sm" name="order_type">
                                <option value="">الكل</option>
                                <option value="custom" @selected(($f['order_type'] ?? '') === 'custom')>طلب مخصص</option>
                                <option value="water_tanker" @selected(($f['order_type'] ?? '') === 'water_tanker')>صهريج مياه</option>
                            </select>
                        </div>
                        <div class="col-6 col-sm-6 col-md-4 col-lg-2 col-xl-2">
                            <label class="form-label small fw-semibold mb-1">من تاريخ</label>
                            <input type="date" class="form-control form-control-sm" name="date_from" value="{{ $f['date_from'] ?? '' }}">
                        </div>
                        <div class="col-6 col-sm-6 col-md-4 col-lg-2 col-xl-2">
                            <label class="form-label small fw-semibold mb-1">إلى تاريخ</label>
                            <input type="date" class="form-control form-control-sm" name="date_to" value="{{ $f['date_to'] ?? '' }}">
                        </div>
                        <div class="col-12 col-md-8 col-lg-4 col-xl-4 d-flex flex-wrap gap-2 align-items-end">
                            <button type="submit" class="btn btn-outline-secondary btn-sm btn-soft">تصفية</button>
                            <a class="btn btn-light btn-sm btn-soft" href="{{ route('super-admin.users.show', array_filter(['user' => $user, 'back_q' => request('back_q'), 'back_role' => request('back_role'), 'back_status' => request('back_status')])) }}">مسح</a>
                        </div>
                    </form>

                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                        <span class="helper small">التصدير يستخدم نفس التصفية الحالية.</span>
                        <a class="btn btn-outline-secondary btn-sm btn-soft" href="{{ route('super-admin.users.orders-export', array_filter(array_merge(['user' => $user], request()->only(['order_status','order_type','date_from','date_to','back_q','back_role','back_status'])))) }}">
                            <i class="bi bi-download"></i><span class="ms-1">تصدير CSV</span>
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table align-middle table-sm w-100">
                            <thead class="helper small">
                            <tr>
                                <th>#</th>
                                <th>النوع</th>
                                <th>الحالة</th>
                                <th>الإجمالي</th>
                                <th>التاريخ</th>
                                <th>السائق</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($orders as $order)
                                <tr>
                                    <td class="text-secondary">{{ $order->id }}</td>
                                    <td><span class="chip small">{{ $typeLabel($order->order_type) }}</span></td>
                                    <td><span class="chip small">{{ $statusLabel($order->status) }}</span></td>
                                    <td>{{ number_format((float) $order->items_price + (float) $order->delivery_fee, 0) }}</td>
                                    <td class="text-nowrap text-secondary small">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                                    <td class="small">
                                        @if($order->driver)
                                            <span dir="ltr">{{ $order->driver->phone }}</span>
                                        @else
                                            —
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-secondary py-4">لا طلبات كعميل بعد.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        {{ $orders->links() }}
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card-pro bg-white">
                <div class="p-4 p-lg-5">
                    <div class="section-title">
                        <h2 class="h6 mb-0">الجلسات والنشاط</h2>
                        <span class="helper small">توكنات Sanctum</span>
                    </div>
                    <div class="row g-3 g-lg-4 mt-2 align-items-start">
                        <div class="col-12 col-md-4 col-xl-3">
                            <div class="metric p-3 p-lg-4 h-100">
                                <div class="helper small mb-1">عدد الجلسات النشطة</div>
                                <div class="fw-bold fs-4">{{ (int) $tokensTotal }}</div>
                                <div class="muted small mt-2">عند «إيقاف المستخدم» تُلغى جميع الجلسات تلقائياً.</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 col-xl-9">
                            @if($activeTokens->isNotEmpty())
                                <div class="helper small mb-2">آخر الأجهزة / التسميات</div>
                                <div class="row g-2">
                                    @foreach($activeTokens as $tok)
                                        <div class="col-12 col-md-6 col-xl-4">
                                            <div class="metric p-3 h-100">
                                                <div class="fw-semibold">{{ $tok->name }}</div>
                                                <div class="muted small">آخر استخدام: {{ $tok->last_used_at ? $tok->last_used_at->format('Y-m-d H:i') : '—' }}</div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-secondary small">لا توجد جلسات مسجّلة حالياً.</div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card-pro bg-white">
                <div class="p-4 p-lg-5">
                    <div class="section-title">
                        <h2 class="h6 mb-0">ملاحظات المتابعة</h2>
                        <span class="helper small">داخلية — لا تظهر في التطبيق</span>
                    </div>
                    <form method="POST" action="{{ route('super-admin.users.notes', $user) }}" class="mt-3">
                        @csrf
                        @method('PUT')
                        <textarea name="admin_notes" class="form-control @error('admin_notes') is-invalid @enderror" rows="6" placeholder="سبب الإيقاف، تذكرة دعم، ملاحظات للفريق…">{{ old('admin_notes', $user->admin_notes) }}</textarea>
                        @error('admin_notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-outline-secondary btn-soft">
                                <i class="bi bi-save2"></i><span class="ms-1">حفظ الملاحظات</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        @if((int) ($driverStats->total ?? 0) > 0)
            <div class="col-12">
                <div class="card-pro bg-white">
                    <div class="p-4 p-lg-5">
                        <div class="section-title">
                            <h2 class="h6 mb-0">طلبات كسائق / مُنفّذ</h2>
                            <span class="chip">إجمالي {{ (int) ($driverStats->total ?? 0) }}</span>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="metric p-3">
                                    <div class="helper small">مُسلّمة</div>
                                    <div class="fw-bold">{{ (int) ($driverStats->delivered_count ?? 0) }}</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-4 col-lg-3">
                                <div class="metric p-3">
                                    <div class="helper small">ملغاة</div>
                                    <div class="fw-bold">{{ (int) ($driverStats->cancelled_count ?? 0) }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table align-middle table-sm w-100">
                                <thead class="helper small">
                                <tr>
                                    <th>#</th>
                                    <th>النوع</th>
                                    <th>الحالة</th>
                                    <th>التاريخ</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($driverOrders as $order)
                                    <tr>
                                        <td class="text-secondary">{{ $order->id }}</td>
                                        <td><span class="chip small">{{ $typeLabel($order->order_type) }}</span></td>
                                        <td>{{ $statusLabel($order->status) }}</td>
                                        <td class="text-secondary small text-nowrap">{{ $order->created_at?->format('Y-m-d H:i') }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="col-12">
            <div class="card-pro bg-white">
                <div class="p-4 p-lg-5">
                    <div class="section-title">
                        <h2 class="h6 mb-0">سجل التدقيق</h2>
                        <span class="helper small">إجراءات الإدارة على هذا الحساب</span>
                    </div>
                    @php
                        $auditActionLabel = static fn (string $a): string => match ($a) {
                            'user.notes_updated' => 'تحديث ملاحظات المتابعة',
                            'user.account_activated' => 'تفعيل الحساب',
                            'user.account_deactivated' => 'إيقاف الحساب',
                            'user.orders_exported' => 'تصدير طلبات (CSV)',
                            default => $a,
                        };
                    @endphp
                    <div class="table-responsive mt-2">
                        <table class="table align-middle table-sm w-100">
                            <thead class="helper small">
                            <tr>
                                <th>الوقت</th>
                                <th>الإجراء</th>
                                <th>المسؤول</th>
                                <th>تفاصيل</th>
                                <th>IP</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($auditLogs as $log)
                                <tr>
                                    <td class="text-secondary text-nowrap small">{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                                    <td><span class="chip small">{{ $auditActionLabel($log->action) }}</span></td>
                                    <td class="small" dir="ltr">{{ $log->superAdmin?->phone ?? '—' }}</td>
                                    <td class="small text-break muted" style="max-width: 420px;">
                                        @if($log->meta)
                                            <code class="small d-block text-wrap" style="white-space: pre-wrap; font-size: 0.72rem;">{{ json_encode($log->meta, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) }}</code>
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="small text-nowrap" dir="ltr">{{ $log->ip_address ?? '—' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-secondary py-4">لا سجلات بعد.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-2">
                        {{ $auditLogs->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
