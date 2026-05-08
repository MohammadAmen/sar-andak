@extends('super-admin.layout')

@section('title', config('app.name', 'Sar Andak').' - تفاصيل مزوّد الخدمة')
@section('subtitle', 'تفاصيل مزوّد الخدمة')

@section('content')

    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap mb-3">
        <div>
            <h1 class="h4 mb-1">{{ $typeLabel }}</h1>
            <div class="muted">إدارة الملف، التوثيق، الاشتراك، والمستندات.</div>
        </div>
        <div class="d-flex gap-2 flex-wrap align-items-center">
            <a class="btn btn-outline-secondary btn-soft icon-btn" href="{{ route('super-admin.providers.index', ['type' => $profile->provider_type]) }}"
               data-bs-toggle="tooltip" title="رجوع" aria-label="رجوع">
                <i class="bi bi-arrow-right"></i>
            </a>

            @if($profile->is_verified)
                <form method="POST" action="{{ route('super-admin.providers.unverify', $profile) }}">
                    @csrf
                    <button class="btn btn-outline-warning btn-soft icon-btn" type="submit" data-bs-toggle="tooltip" title="إلغاء توثيق" aria-label="إلغاء توثيق">
                        <i class="bi bi-patch-minus"></i>
                    </button>
                </form>
            @else
                <form method="POST" action="{{ route('super-admin.providers.verify', $profile) }}">
                    @csrf
                    <button class="btn btn-outline-success btn-soft icon-btn" type="submit" data-bs-toggle="tooltip" title="توثيق" aria-label="توثيق">
                        <i class="bi bi-patch-check"></i>
                    </button>
                </form>
            @endif

            <form method="POST" action="{{ route('super-admin.providers.toggle-active', $profile) }}">
                @csrf
                <button class="btn btn-outline-danger btn-soft icon-btn" type="submit"
                        data-bs-toggle="tooltip"
                        title="{{ ($profile->user?->is_active ?? false) ? 'تعطيل' : 'تفعيل' }}"
                        aria-label="{{ ($profile->user?->is_active ?? false) ? 'تعطيل' : 'تفعيل' }}">
                    <i class="bi {{ ($profile->user?->is_active ?? false) ? 'bi-slash-circle' : 'bi-check-circle' }}"></i>
                </button>
            </form>
        </div>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="row g-3 g-lg-4">
        <div class="col-12 col-lg-7">
            <div class="card-pro bg-white">
                <div class="p-4 p-lg-5">
                    <div class="section-title">
                        <h2>المعلومات الأساسية</h2>
                        <span class="chip">
                            #{{ $profile->id }}
                        </span>
                    </div>
                    <div class="row g-3">
                        <div class="col-12 col-md-6">
                            <div class="metric p-3 p-lg-4">
                                <div class="helper small mb-1">الاسم</div>
                                <div class="fw-bold">{{ $profile->user?->name ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="metric p-3 p-lg-4">
                                <div class="helper small mb-1">رقم الجوال</div>
                                <div class="fw-bold" dir="ltr">{{ $profile->user?->phone ?? '-' }}</div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="metric p-3 p-lg-4">
                                <div class="helper small mb-1">التوثيق</div>
                                <div class="fw-bold">
                                    @if($profile->is_verified)
                                        <span class="chip" style="border-color: rgba(16,185,129,.25); background: rgba(16,185,129,.10); color: rgba(17,24,39,.82);">موثّق</span>
                                    @else
                                        <span class="chip" style="border-color: rgba(245,179,1,.25); background: rgba(245,179,1,.10); color: rgba(17,24,39,.82);">بانتظار</span>
                                    @endif
                                </div>
                                <div class="muted small mt-1">
                                    {{ $profile->verified_at ? ('آخر توثيق: '.$profile->verified_at->format('Y-m-d H:i')) : 'بانتظار توثيق' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="metric p-3 p-lg-4">
                                <div class="helper small mb-1">التفعيل</div>
                                <div class="fw-bold">
                                    @if(($profile->user?->is_active ?? false) === true)
                                        <span class="chip" style="border-color: rgba(16,185,129,.25); background: rgba(16,185,129,.10); color: rgba(17,24,39,.82);">مفعّل</span>
                                    @else
                                        <span class="chip" style="border-color: rgba(239,68,68,.25); background: rgba(239,68,68,.10); color: rgba(17,24,39,.82);">معطّل</span>
                                    @endif
                                </div>
                                <div class="muted small mt-1">يتم التحكم بالحالة من لوحة السوبر أدمن (تفعيل/تعطيل).</div>
                            </div>
                        </div>
                    </div>

                    <hr class="soft-divider">

                    @if($profile->provider_type === 'delivery')
                        @include('super-admin.providers.forms.delivery')
                    @elseif($profile->provider_type === 'taxi')
                        @include('super-admin.providers.forms.taxi')
                    @elseif($profile->provider_type === 'water_tanker')
                        @include('super-admin.providers.forms.water_tanker')
                    @elseif($profile->provider_type === 'workshop')
                        @include('super-admin.providers.forms.workshop')
                    @else
                        <div class="helper small mb-2">تفاصيل القطاع</div>
                        <div class="muted small">لا يوجد نموذج مخصّص لهذا النوع بعد.</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-5">
            <div class="card-pro bg-white mb-3">
                <div class="p-4 p-lg-5">
                    <div class="section-title">
                        <h2>العمولة والضمانة</h2>
                        <span class="chip">{{ $subscriptionStatusLabel }}</span>
                    </div>

                    <div class="metric p-3 p-lg-4">
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="helper small">العمولة</div>
                                <div class="fw-bold">{{ number_format((float)($profile->commission_rate_percent ?? 0), 2) }}%</div>
                            </div>
                            <div class="col-6">
                                <div class="helper small">الضمانة الشهرية</div>
                                <div class="fw-bold">{{ number_format((float)($profile->monthly_deposit_amount ?? 0), 2) }}</div>
                            </div>
                            <div class="col-12">
                                <div class="helper small">تغطية الضمانة</div>
                                <div class="muted small">
                                    {{ optional($profile->deposit_starts_at)->format('Y-m-d') ?? '-' }}
                                    →
                                    {{ optional($profile->deposit_ends_at)->format('Y-m-d') ?? '-' }}
                                </div>
                            </div>
                            @if($remainingDays !== null)
                                <div class="col-12">
                                    <div class="helper small">المتبقي</div>
                                    @if($remainingDays >= 0)
                                        <div class="fw-bold">{{ (int) $remainingDays }} يوم</div>
                                    @else
                                        <div class="fw-bold">منتهي</div>
                                    @endif
                                </div>
                            @endif
                        </div>

                        <div class="d-flex gap-2 flex-wrap mt-3">
                            <span data-bs-toggle="tooltip" title="تجديد/تعديل الضمانة">
                                <button class="btn btn-outline-secondary btn-soft icon-btn" type="button" data-bs-toggle="modal" data-bs-target="#renewSubModal" aria-label="تجديد اشتراك">
                                    <i class="bi bi-arrow-repeat"></i>
                                </button>
                            </span>
                            <form method="POST" action="{{ route('super-admin.providers.subscription.toggle-pause', $profile) }}">
                                @csrf
                                <button class="btn btn-outline-warning btn-soft icon-btn" type="submit"
                                        data-bs-toggle="tooltip"
                                        title="{{ ($profile->deposit_status === 'paused') ? 'استئناف' : 'إيقاف مؤقت' }}"
                                        aria-label="{{ ($profile->deposit_status === 'paused') ? 'استئناف' : 'إيقاف مؤقت' }}">
                                    <i class="bi {{ ($profile->deposit_status === 'paused') ? 'bi-play-fill' : 'bi-pause-fill' }}"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-pro bg-white">
                <div class="p-4 p-lg-5">
                    <div class="section-title">
                        <h2>المستندات</h2>
                        <span class="helper small">انقر للتوسيع</span>
                    </div>
                    @php
                        $docs = [
                            ['key' => 'id_document_image', 'label' => 'صورة الهوية', 'url' => $profile->id_document_image ? asset('storage/'.$profile->id_document_image) : null],
                            ['key' => 'license_image', 'label' => 'صورة الرخصة', 'url' => $profile->license_image ? asset('storage/'.$profile->license_image) : null],
                            ['key' => 'vehicle_image', 'label' => 'صورة المركبة', 'url' => $profile->vehicle_image ? asset('storage/'.$profile->vehicle_image) : null],
                        ];
                    @endphp

                    @foreach($docs as $d)
                        <div class="metric p-3 p-lg-4 mb-3">
                            <div class="d-flex justify-content-between align-items-center gap-2 flex-wrap">
                                <div class="fw-semibold">{{ $d['label'] }}</div>
                                <div class="muted small">{{ $d['url'] ? 'موجود' : 'غير مرفوع' }}</div>
                            </div>

                            @if($d['url'])
                                <div class="mt-3 d-flex gap-2 align-items-center flex-wrap">
                                    <img
                                        src="{{ $d['url'] }}"
                                        alt="{{ $d['label'] }}"
                                        style="width: 112px; height: 84px; object-fit: cover; border-radius: 14px; border: 1px solid rgba(17,24,39,.10); cursor: zoom-in;"
                                        data-bs-toggle="modal"
                                        data-bs-target="#imgPreviewModal"
                                        data-img="{{ $d['url'] }}"
                                        data-title="{{ $d['label'] }}"
                                    >
                                    <span data-bs-toggle="tooltip" title="توسيع">
                                        <button
                                            class="btn btn-sm btn-outline-secondary btn-soft icon-btn"
                                            type="button"
                                            data-bs-toggle="modal"
                                            data-bs-target="#imgPreviewModal"
                                            data-img="{{ $d['url'] }}"
                                            data-title="{{ $d['label'] }}"
                                            aria-label="توسيع"
                                        ><i class="bi bi-arrows-fullscreen"></i></button>
                                    </span>
                                    <a class="btn btn-sm btn-light btn-soft icon-btn" target="_blank" href="{{ $d['url'] }}"
                                       data-bs-toggle="tooltip" title="فتح بمتصفح" aria-label="فتح بمتصفح">
                                        <i class="bi bi-box-arrow-up-right"></i>
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <div class="helper small mt-3">لرفع أو تحديث الصور استخدم النموذج في العمود الأيسر حسب نوع المزوّد.</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Image preview modal --}}
    <div class="modal fade" id="imgPreviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imgPreviewTitle">معاينة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="imgPreviewEl" src="" alt="" style="width:100%; height:auto; border-radius: 14px;">
                </div>
            </div>
        </div>
    </div>

    {{-- Renew subscription modal --}}
    <div class="modal fade" id="renewSubModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تجديد الضمانة</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('super-admin.providers.subscription.renew', $profile) }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">إضافة أشهر</label>
                            <input class="form-control" type="number" min="1" max="36" name="add_months" value="{{ old('add_months', 1) }}" required>
                            @error('add_months')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">دفعة الضمانة الشهرية (تحديث/تغيير)</label>
                            <input class="form-control" type="number" step="0.01" min="0" name="monthly_deposit_amount" value="{{ old('monthly_deposit_amount', $profile->monthly_deposit_amount ?? 0) }}" required>
                            @error('monthly_deposit_amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="mb-0">
                            <label class="form-label fw-semibold">نسبة العمولة (%)</label>
                            <input class="form-control" type="number" step="0.01" min="0" max="100" name="commission_rate_percent" value="{{ old('commission_rate_percent', $profile->commission_rate_percent ?? 0) }}" required>
                            @error('commission_rate_percent')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="helper small mt-2">
                            سيتم تمديد تغطية الضمانة من تاريخ النهاية الحالي إن كان مستقبلياً، أو من تاريخ اليوم إن كان منتهي.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light btn-soft" data-bs-dismiss="modal">إلغاء</button>
                        <button type="submit" class="btn btn-outline-secondary btn-soft">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('imgPreviewModal');
            if (!modal) return;

            modal.addEventListener('show.bs.modal', function (event) {
                const trigger = event.relatedTarget;
                if (!trigger) return;
                const img = trigger.getAttribute('data-img');
                const title = trigger.getAttribute('data-title') || 'معاينة';
                const el = document.getElementById('imgPreviewEl');
                const ttl = document.getElementById('imgPreviewTitle');
                if (el) el.src = img || '';
                if (ttl) ttl.textContent = title;
            });
        });
    </script>
@endsection

