@extends('super-admin.layout')

@section('title', config('app.name', 'Sar Andak').' - '.$typeLabel)
@section('subtitle', $typeLabel)

@section('content')
    <div class="card-pro bg-white">
        <div class="p-4 p-lg-5">
            <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap">
                <div>
                    <h1 class="h4 mb-2">{{ $typeLabel }}</h1>
                    <p class="muted mb-0">إدارة مزوّدي الخدمة: إنشاء ملفات، توثيق، ومتابعة الجاهزية للعمل.</p>
                </div>
                <span data-bs-toggle="tooltip" title="إضافة مزوّد">
                    <button
                        class="btn btn-outline-secondary btn-soft icon-btn"
                        type="button"
                        data-bs-toggle="modal"
                        data-bs-target="#addProviderModal"
                        aria-label="إضافة مزوّد"
                    >
                        <i class="bi bi-person-plus"></i>
                    </button>
                </span>
            </div>

            {{-- toast handled globally --}}

            <form class="row g-2 g-lg-3 mt-3" method="GET" action="{{ route('super-admin.providers.index') }}">
                <input type="hidden" name="type" value="{{ $type }}">
                <div class="col-12 col-lg-6">
                    <input class="form-control" name="q" value="{{ $q }}" placeholder="ابحث بالاسم أو رقم الجوال">
                </div>
                <div class="col-6 col-lg-3">
                    <select class="form-select" name="verified">
                        <option value="">الكل (توثيق)</option>
                        <option value="yes" @selected($verified==='yes')>موثّق</option>
                        <option value="no" @selected($verified==='no')>غير موثّق</option>
                    </select>
                </div>
                <div class="col-6 col-lg-3 d-grid">
                    <button class="btn btn-outline-secondary btn-soft" type="submit">
                        <i class="bi bi-funnel"></i>
                        <span class="ms-1">تطبيق</span>
                    </button>
                </div>
            </form>

            <hr class="my-4">

            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                    <tr class="helper">
                        <th>#</th>
                        <th>المستخدم</th>
                        <th>رقم الجوال</th>
                        <th>{{ $type === 'workshop' ? 'التصنيفات' : 'المركبة/المهارة' }}</th>
                        <th>التوثيق</th>
                        <th>التفعيل</th>
                        <th>إجراءات</th>
                        <th>تاريخ الإنشاء</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($profiles as $p)
                        <tr>
                            <td class="text-secondary">{{ $p->id }}</td>
                            <td class="fw-semibold">{{ $p->user?->name ?? '-' }}</td>
                            <td dir="ltr" class="text-nowrap">{{ $p->user?->phone ?? '-' }}</td>
                            <td class="text-nowrap">
                                @if($type === 'workshop')
                                    @php
                                        $wkKeys = is_array($p->workshop_category_keys) ? $p->workshop_category_keys : [];
                                        $wkLabels = collect($wkKeys)
                                            ->map(fn ($k) => \App\Support\WorkshopCategories::label($k))
                                            ->filter()
                                            ->values();
                                    @endphp
                                    @if($wkLabels->isNotEmpty())
                                        <span class="d-inline-flex flex-wrap align-items-center gap-1">
                                            @foreach($wkLabels->take(3) as $lbl)
                                                <span class="chip">{{ $lbl }}</span>
                                            @endforeach
                                            @if($wkLabels->count() > 3)
                                                <span class="text-secondary small">+{{ $wkLabels->count() - 3 }}</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="chip text-secondary">{{ $p->workshop_skill ?? '—' }}</span>
                                    @endif
                                @elseif($type === 'water_tanker')
                                    <span class="chip">{{ $p->water_capacity_liters ? ($p->water_capacity_liters.'L') : '-' }}</span>
                                @else
                                    <span class="chip">{{ $p->vehicle_type ?? '-' }}</span>
                                @endif
                            </td>
                            <td>
                                @if($p->is_verified)
                                    <span class="chip" style="border-color: rgba(16,185,129,.25); background: rgba(16,185,129,.10); color: rgba(17,24,39,.82);">موثّق</span>
                                @else
                                    <span class="chip" style="border-color: rgba(245,179,1,.25); background: rgba(245,179,1,.10); color: rgba(17,24,39,.82);">بانتظار التوثيق</span>
                                @endif
                            </td>
                            <td>
                                @if(($p->user?->is_active ?? false) === true)
                                    <span class="chip" style="border-color: rgba(16,185,129,.25); background: rgba(16,185,129,.10); color: rgba(17,24,39,.82);">مفعّل</span>
                                @else
                                    <span class="chip" style="border-color: rgba(239,68,68,.25); background: rgba(239,68,68,.10); color: rgba(17,24,39,.82);">معطّل</span>
                                @endif
                            </td>
                            <td class="text-nowrap">
                                <a
                                    class="btn btn-sm btn-outline-secondary btn-soft icon-btn"
                                    href="{{ route('super-admin.providers.show', $p) }}"
                                    data-bs-toggle="tooltip"
                                    title="عرض التفاصيل"
                                    aria-label="عرض التفاصيل"
                                ><i class="bi bi-eye"></i></a>
                                @if($p->is_verified)
                                    <form class="d-inline" method="POST" action="{{ route('super-admin.providers.unverify', $p) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-warning btn-soft icon-btn" type="submit" data-bs-toggle="tooltip" title="إلغاء توثيق" aria-label="إلغاء توثيق">
                                            <i class="bi bi-patch-minus"></i>
                                        </button>
                                    </form>
                                @else
                                    <form class="d-inline" method="POST" action="{{ route('super-admin.providers.verify', $p) }}">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-success btn-soft icon-btn" type="submit" data-bs-toggle="tooltip" title="توثيق" aria-label="توثيق">
                                            <i class="bi bi-patch-check"></i>
                                        </button>
                                    </form>
                                @endif
                                <form class="d-inline" method="POST" action="{{ route('super-admin.providers.toggle-active', $p) }}">
                                    @csrf
                                    <button
                                        class="btn btn-sm btn-outline-danger btn-soft icon-btn"
                                        type="submit"
                                        data-bs-toggle="tooltip"
                                        title="{{ ($p->user?->is_active ?? false) ? 'تعطيل' : 'تفعيل' }}"
                                        aria-label="{{ ($p->user?->is_active ?? false) ? 'تعطيل' : 'تفعيل' }}"
                                    >
                                        <i class="bi {{ ($p->user?->is_active ?? false) ? 'bi-slash-circle' : 'bi-check-circle' }}"></i>
                                    </button>
                                </form>
                            </td>
                            <td class="text-secondary text-nowrap">{{ optional($p->created_at)->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-secondary py-5">لا يوجد ملفات مزوّدي خدمة حتى الآن.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                <div class="helper small">
                    إجمالي النتائج: <span class="fw-semibold">{{ $profiles->total() }}</span>
                </div>
                <div>
                    {{ $profiles->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Create provider profile modal --}}
    <div class="modal fade" id="addProviderModal" tabindex="-1" aria-labelledby="addProviderModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProviderModalLabel">إنشاء ملف مزوّد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('super-admin.providers.store') }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="provider_type" value="{{ $type }}">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">نوع الإضافة</label>
                            <select class="form-select" name="mode" required>
                                <option value="existing" @selected(old('mode','existing')==='existing')>مستخدم موجود (ربط بالهاتف)</option>
                                <option value="new" @selected(old('mode')==='new')>مزوّد جديد (إنشاء مستخدم جديد)</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">رقم الجوال</label>
                            <input class="form-control" name="phone" value="{{ old('phone') }}" placeholder="مثال: 0961100101" required>
                            @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">الاسم (للمزوّد الجديد)</label>
                                <input class="form-control" name="name" value="{{ old('name') }}" placeholder="اختياري">
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">كلمة المرور (اختياري)</label>
                                <input class="form-control" name="password" value="{{ old('password') }}" placeholder="إن تركتها فارغة سيتم توليدها">
                                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="row g-2">
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">نسبة العمولة على الطلب (%)</label>
                                <input class="form-control" name="commission_rate_percent" value="{{ old('commission_rate_percent', 0) }}" type="number" step="0.01" min="0" max="100" required>
                                @error('commission_rate_percent')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">دفعة الضمانة الشهرية</label>
                                <input class="form-control" name="monthly_deposit_amount" value="{{ old('monthly_deposit_amount', 0) }}" type="number" step="0.01" min="0" required>
                                @error('monthly_deposit_amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <div class="mt-2">
                            <label class="form-label fw-semibold">مدة تغطية الضمانة (بالأشهر)</label>
                            <input class="form-control" name="deposit_period_months" value="{{ old('deposit_period_months', 1) }}" type="number" min="1" max="36" required>
                            @error('deposit_period_months')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>

                        <div class="helper small mt-2">
                            بعد الإنشاء سيتم تحويلك لصفحة الملف لإكمال نموذج الدليفري (مركبة/مستندات…).
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-soft" data-bs-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-outline-secondary btn-soft">إنشاء</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

