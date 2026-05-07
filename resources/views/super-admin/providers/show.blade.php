@extends('super-admin.layout')

@section('title', config('app.name', 'Sar Andak').' - تفاصيل مزوّد الخدمة')
@section('subtitle', 'تفاصيل مزوّد الخدمة')

@section('content')
    <?php
        $doc = function (?string $path): ?string {
            return $path ? asset('storage/'.$path) : null;
        };

        $remainingDays = null;
        if ($profile->deposit_ends_at) {
            $remainingDays = now()->startOfDay()->diffInDays($profile->deposit_ends_at->copy()->startOfDay(), false);
        }

        $subscriptionStatusLabel = match ($profile->deposit_status) {
            'active' => 'نشط',
            'paused' => 'موقوف مؤقتاً',
            'expired' => 'منتهي',
            default => '-',
        };

        $coverageRegionOptions = [
            'tafes' => ['label' => 'طفس', 'icon' => 'bi-geo-alt-fill'],
            'daraa' => ['label' => 'درعا', 'icon' => 'bi-pin-map-fill'],
            'daraa_countryside' => ['label' => 'أرياف درعا', 'icon' => 'bi-tree-fill'],
            'damascus' => ['label' => 'دمشق', 'icon' => 'bi-building'],
            'damascus_airport' => ['label' => 'مطار دمشق', 'icon' => 'bi-airplane'],
            'sy_jo_border' => ['label' => 'الحدود السورية الأردنية', 'icon' => 'bi-signpost-split-fill'],
            'sy_lb_border' => ['label' => 'الحدود السورية اللبنانية', 'icon' => 'bi-signpost-split'],
        ];
        $taxiCoverageSelected = [];
        if (($profile->provider_type ?? null) === 'taxi') {
            $covRaw = old('taxi_coverage_area_keys', $profile->taxi_coverage_area_keys ?? []);
            $taxiCoverageSelected = is_array($covRaw) ? $covRaw : [];
        }
        $waterCoverageRegionOptions = [
            'tafes' => ['label' => 'طفس', 'icon' => 'bi-geo-alt-fill'],
            'tafes_farms' => ['label' => 'المزارع المحيطة بطفس', 'icon' => 'bi-tree-fill'],
        ];
        $waterCoverageSelected = [];
        if (($profile->provider_type ?? null) === 'water_tanker') {
            $wRaw = old('water_service_area_keys', $profile->water_service_area_keys ?? []);
            $waterCoverageSelected = is_array($wRaw) ? $wRaw : [];
        }

        $taxiActivePane = 'taxi-pane-driver';
        if (($profile->provider_type ?? null) === 'taxi' && isset($errors) && $errors->any()) {
            $taxiPaneByField = [
                'user_name' => 'taxi-pane-driver',
                'user_phone' => 'taxi-pane-driver',
                'full_name' => 'taxi-pane-driver',
                'license_no' => 'taxi-pane-driver',
                'license_expiry' => 'taxi-pane-driver',
                'vehicle_type' => 'taxi-pane-vehicle',
                'vehicle_plate' => 'taxi-pane-vehicle',
                'vehicle_color' => 'taxi-pane-vehicle',
                'taxi_car_make' => 'taxi-pane-vehicle',
                'taxi_car_model' => 'taxi-pane-vehicle',
                'taxi_car_year' => 'taxi-pane-vehicle',
                'taxi_seats' => 'taxi-pane-vehicle',
                'taxi_insurance_no' => 'taxi-pane-vehicle',
                'taxi_insurance_expiry' => 'taxi-pane-vehicle',
                'taxi_has_ac' => 'taxi-pane-vehicle',
                'taxi_allows_smoking' => 'taxi-pane-vehicle',
                'taxi_pricing_mode' => 'taxi-pane-pricing',
                'taxi_base_fare' => 'taxi-pane-pricing',
                'taxi_min_fare' => 'taxi-pane-pricing',
                'taxi_price_per_km' => 'taxi-pane-pricing',
                'taxi_price_per_minute' => 'taxi-pane-pricing',
                'taxi_coverage_area_keys' => 'taxi-pane-pricing',
                'notes' => 'taxi-pane-pricing',
                'id_document_image' => 'taxi-pane-docs',
                'license_image' => 'taxi-pane-docs',
                'vehicle_image' => 'taxi-pane-docs',
            ];
            foreach ($errors->keys() as $ek) {
                $base = explode('.', (string) $ek, 2)[0];
                if (isset($taxiPaneByField[$base])) {
                    $taxiActivePane = $taxiPaneByField[$base];
                    break;
                }
            }
        }

        $waterActivePane = 'water-pane-identity';
        if (($profile->provider_type ?? null) === 'water_tanker' && isset($errors) && $errors->any()) {
            $waterPaneByField = [
                'user_name' => 'water-pane-identity',
                'user_phone' => 'water-pane-identity',
                'full_name' => 'water-pane-identity',
                'national_id' => 'water-pane-identity',
                'water_capacity_liters' => 'water-pane-tank',
                'water_has_pump' => 'water-pane-tank',
                'water_hose_length_m' => 'water-pane-tank',
                'water_potable_declared' => 'water-pane-tank',
                'water_pricing_mode' => 'water-pane-service',
                'water_price_per_tank' => 'water-pane-service',
                'water_price_per_liter' => 'water-pane-service',
                'water_min_order_liters' => 'water-pane-service',
                'water_service_area_keys' => 'water-pane-service',
                'notes' => 'water-pane-service',
                'id_document_image' => 'water-pane-docs',
                'license_image' => 'water-pane-docs',
                'vehicle_image' => 'water-pane-docs',
            ];
            foreach ($errors->keys() as $ek) {
                $base = explode('.', (string) $ek, 2)[0];
                if (isset($waterPaneByField[$base])) {
                    $waterActivePane = $waterPaneByField[$base];
                    break;
                }
            }
        }

        $workshopCategoryGroups = \App\Support\WorkshopCategories::groups();
        $workshopCategorySelected = [];
        if (($profile->provider_type ?? null) === 'workshop') {
            $wkRaw = old('workshop_category_keys', $profile->workshop_category_keys ?? []);
            $workshopCategorySelected = is_array($wkRaw) ? $wkRaw : [];
        }

        $workshopActivePane = 'workshop-pane-identity';
        if (($profile->provider_type ?? null) === 'workshop' && isset($errors) && $errors->any()) {
            $workshopPaneByField = [
                'user_name' => 'workshop-pane-identity',
                'user_phone' => 'workshop-pane-identity',
                'full_name' => 'workshop-pane-identity',
                'national_id' => 'workshop-pane-identity',
                'license_no' => 'workshop-pane-identity',
                'license_expiry' => 'workshop-pane-identity',
                'workshop_category_keys' => 'workshop-pane-cats',
                'workshop_skill_other' => 'workshop-pane-cats',
                'workshop_neighborhood' => 'workshop-pane-search',
                'workshop_short_pitch' => 'workshop-pane-search',
                'workshop_years_experience' => 'workshop-pane-search',
                'workshop_home_visit' => 'workshop-pane-search',
                'notes' => 'workshop-pane-search',
                'id_document_image' => 'workshop-pane-docs',
                'license_image' => 'workshop-pane-docs',
                'vehicle_image' => 'workshop-pane-docs',
            ];
            foreach ($errors->keys() as $ek) {
                $base = explode('.', (string) $ek, 2)[0];
                if (isset($workshopPaneByField[$base])) {
                    $workshopActivePane = $workshopPaneByField[$base];
                    break;
                }
            }
        }
    ?>

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
                        <div class="section-title">
                            <h2>بيانات الدليفري</h2>
                            <span class="helper small">أدخل البيانات الأساسية ثم ارفع المستندات</span>
                        </div>

                        <form method="POST" action="{{ route('super-admin.providers.update', $profile) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">اسم المستخدم</label>
                                    <input class="form-control @error('user_name') is-invalid @enderror"
                                           name="user_name"
                                           value="{{ old('user_name', $profile->user?->name) }}"
                                           required>
                                    @error('user_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">رقم الجوال</label>
                                    <input class="form-control @error('user_phone') is-invalid @enderror"
                                           name="user_phone"
                                           value="{{ old('user_phone', $profile->user?->phone) }}"
                                           required
                                           dir="ltr">
                                    @error('user_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">الاسم الكامل (ضمن الدليفري)</label>
                                    <input class="form-control @error('full_name') is-invalid @enderror"
                                           name="full_name"
                                           value="{{ old('full_name', $profile->full_name ?? $profile->user?->name) }}"
                                           required>
                                    @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">الرقم الوطني</label>
                                    <input class="form-control @error('national_id') is-invalid @enderror"
                                           name="national_id"
                                           value="{{ old('national_id', $profile->national_id) }}"
                                           placeholder="اختياري">
                                    @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">رقم الرخصة</label>
                                    <input class="form-control @error('license_no') is-invalid @enderror"
                                           name="license_no"
                                           value="{{ old('license_no', $profile->license_no) }}"
                                           placeholder="اختياري">
                                    @error('license_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">تاريخ انتهاء الرخصة</label>
                                    <input type="date"
                                           class="form-control @error('license_expiry') is-invalid @enderror"
                                           name="license_expiry"
                                           value="{{ old('license_expiry', optional($profile->license_expiry)->format('Y-m-d')) }}">
                                    @error('license_expiry')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">نوع المركبة</label>
                                    <select class="form-select @error('vehicle_type') is-invalid @enderror" name="vehicle_type" required>
                                        @php($vt = old('vehicle_type', $profile->vehicle_type))
                                        <option value="" disabled @selected(empty($vt))>اختر</option>
                                        <option value="motorcycle" @selected($vt==='motorcycle')>موتور</option>
                                        <option value="bicycle" @selected($vt==='bicycle')>بسكلِت</option>
                                        <option value="car" @selected($vt==='car')>سيارة</option>
                                        <option value="van" @selected($vt==='van')>فان</option>
                                        <option value="truck" @selected($vt==='truck')>شاحنة</option>
                                    </select>
                                    @error('vehicle_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">لوحة المركبة</label>
                                    <input class="form-control @error('vehicle_plate') is-invalid @enderror"
                                           name="vehicle_plate"
                                           value="{{ old('vehicle_plate', $profile->vehicle_plate) }}"
                                           placeholder="اختياري">
                                    @error('vehicle_plate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12 col-md-6">
                                    <label class="form-label fw-semibold">لون المركبة</label>
                                    <input class="form-control @error('vehicle_color') is-invalid @enderror"
                                           name="vehicle_color"
                                           value="{{ old('vehicle_color', $profile->vehicle_color) }}"
                                           placeholder="اختياري">
                                    @error('vehicle_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                <div class="col-12">
                                    <label class="form-label fw-semibold">ملاحظات</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3"
                                              placeholder="ملاحظات داخلية للسوبر أدمن">{{ old('notes', $profile->notes) }}</textarea>
                                    @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <hr class="soft-divider">

                            <div class="section-title">
                                <h2>المستندات</h2>
                                <span class="helper small">رفع اختياري (PNG/JPG/WEBP)</span>
                            </div>
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label fw-semibold">صورة الهوية</label>
                                    <input type="file" class="form-control @error('id_document_image') is-invalid @enderror" name="id_document_image" accept="image/*">
                                    @error('id_document_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @if($profile->id_document_image)
                                        <div class="helper small mt-2">تم رفع ملف. يمكنك معاينته من “ملخّص المستندات”.</div>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">صورة الرخصة</label>
                                    <input type="file" class="form-control @error('license_image') is-invalid @enderror" name="license_image" accept="image/*">
                                    @error('license_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @if($profile->license_image)
                                        <div class="helper small mt-2">تم رفع ملف. يمكنك معاينته من “ملخّص المستندات”.</div>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">صورة المركبة</label>
                                    <input type="file" class="form-control @error('vehicle_image') is-invalid @enderror" name="vehicle_image" accept="image/*">
                                    @error('vehicle_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    @if($profile->vehicle_image)
                                        <div class="helper small mt-2">تم رفع ملف. يمكنك معاينته من “ملخّص المستندات”.</div>
                                    @endif
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button class="btn btn-outline-secondary btn-soft" type="submit">
                                    <i class="bi bi-save2"></i>
                                    <span class="ms-1">حفظ</span>
                                </button>
                            </div>
                        </form>
                    @elseif($profile->provider_type === 'taxi')
                        <div class="section-title">
                            <h2>بيانات التكسي</h2>
                            <span class="helper small">خطوات منظّمة: الهوية ← المركبة ← التسعير ← المستندات</span>
                        </div>

                        <form id="taxiProviderForm" method="POST" action="{{ route('super-admin.providers.update', $profile) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <ul class="nav nav-pills taxi-step-nav d-flex mb-3" id="taxiStepNav" role="tablist">
                                <li class="nav-item flex-grow-1" role="presentation">
                                    <button class="nav-link w-100 h-100 @if($taxiActivePane === 'taxi-pane-driver') active @endif" id="taxi-tab-driver" data-bs-toggle="tab" data-bs-target="#taxi-pane-driver" type="button" role="tab" aria-controls="taxi-pane-driver" @if($taxiActivePane === 'taxi-pane-driver') aria-selected="true" @else aria-selected="false" @endif>
                                        <span class="step-idx">1</span>السائق والهوية
                                    </button>
                                </li>
                                <li class="nav-item flex-grow-1" role="presentation">
                                    <button class="nav-link w-100 h-100 @if($taxiActivePane === 'taxi-pane-vehicle') active @endif" id="taxi-tab-vehicle" data-bs-toggle="tab" data-bs-target="#taxi-pane-vehicle" type="button" role="tab" aria-controls="taxi-pane-vehicle" @if($taxiActivePane === 'taxi-pane-vehicle') aria-selected="true" @else aria-selected="false" @endif>
                                        <span class="step-idx">2</span>المركبة والتأمين
                                    </button>
                                </li>
                                <li class="nav-item flex-grow-1" role="presentation">
                                    <button class="nav-link w-100 h-100 @if($taxiActivePane === 'taxi-pane-pricing') active @endif" id="taxi-tab-pricing" data-bs-toggle="tab" data-bs-target="#taxi-pane-pricing" type="button" role="tab" aria-controls="taxi-pane-pricing" @if($taxiActivePane === 'taxi-pane-pricing') aria-selected="true" @else aria-selected="false" @endif>
                                        <span class="step-idx">3</span>التسعير والتغطية
                                    </button>
                                </li>
                                <li class="nav-item flex-grow-1" role="presentation">
                                    <button class="nav-link w-100 h-100 @if($taxiActivePane === 'taxi-pane-docs') active @endif" id="taxi-tab-docs" data-bs-toggle="tab" data-bs-target="#taxi-pane-docs" type="button" role="tab" aria-controls="taxi-pane-docs" @if($taxiActivePane === 'taxi-pane-docs') aria-selected="true" @else aria-selected="false" @endif>
                                        <span class="step-idx">4</span>المستندات
                                    </button>
                                </li>
                            </ul>

                            <div class="taxi-tab-shell">
                                <div class="tab-content" id="taxiStepContent">
                                    <div class="tab-pane fade @if($taxiActivePane === 'taxi-pane-driver') show active @endif" id="taxi-pane-driver" role="tabpanel" tabindex="0" aria-labelledby="taxi-tab-driver">
                                        <div class="section-title mb-3">
                                            <h2 class="h6 mb-0">الخطوة 1</h2>
                                            <span class="helper small">الحساب والهوية والرخصة</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">اسم المستخدم</label>
                                                <input class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old('user_name', $profile->user?->name) }}" required>
                                                @error('user_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">رقم الجوال</label>
                                                <input class="form-control @error('user_phone') is-invalid @enderror" name="user_phone" value="{{ old('user_phone', $profile->user?->phone) }}" required dir="ltr">
                                                @error('user_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">الاسم الكامل (ضمن التكسي)</label>
                                                <input class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{ old('full_name', $profile->full_name ?? $profile->user?->name) }}" required>
                                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">رقم الرخصة</label>
                                                <input class="form-control @error('license_no') is-invalid @enderror" name="license_no" value="{{ old('license_no', $profile->license_no) }}" placeholder="اختياري">
                                                @error('license_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">تاريخ انتهاء الرخصة</label>
                                                <input type="date" class="form-control @error('license_expiry') is-invalid @enderror" name="license_expiry" value="{{ old('license_expiry', optional($profile->license_expiry)->format('Y-m-d')) }}">
                                                @error('license_expiry')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade @if($taxiActivePane === 'taxi-pane-vehicle') show active @endif" id="taxi-pane-vehicle" role="tabpanel" tabindex="0" aria-labelledby="taxi-tab-vehicle">
                                        <div class="section-title mb-3">
                                            <h2 class="h6 mb-0">الخطوة 2</h2>
                                            <span class="helper small">المركبة والتأمين والراحة</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12 col-md-4">
                                                <label class="form-label fw-semibold">نوع المركبة</label>
                                                <select class="form-select @error('vehicle_type') is-invalid @enderror" name="vehicle_type" required>
                                                    @php($vt = old('vehicle_type', $profile->vehicle_type))
                                                    <option value="" disabled @selected(empty($vt))>اختر</option>
                                                    <option value="car" @selected($vt==='car')>سيارة</option>
                                                    <option value="van" @selected($vt==='van')>فان</option>
                                                </select>
                                                @error('vehicle_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label class="form-label fw-semibold">لوحة المركبة</label>
                                                <input class="form-control @error('vehicle_plate') is-invalid @enderror" name="vehicle_plate" value="{{ old('vehicle_plate', $profile->vehicle_plate) }}" required>
                                                @error('vehicle_plate')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label class="form-label fw-semibold">لون المركبة</label>
                                                <input class="form-control @error('vehicle_color') is-invalid @enderror" name="vehicle_color" value="{{ old('vehicle_color', $profile->vehicle_color) }}" placeholder="اختياري">
                                                @error('vehicle_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label class="form-label fw-semibold">الشركة</label>
                                                <input class="form-control @error('taxi_car_make') is-invalid @enderror" name="taxi_car_make" value="{{ old('taxi_car_make', $profile->taxi_car_make) }}" required>
                                                @error('taxi_car_make')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label class="form-label fw-semibold">الموديل</label>
                                                <input class="form-control @error('taxi_car_model') is-invalid @enderror" name="taxi_car_model" value="{{ old('taxi_car_model', $profile->taxi_car_model) }}" required>
                                                @error('taxi_car_model')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <label class="form-label fw-semibold">السنة</label>
                                                <input class="form-control @error('taxi_car_year') is-invalid @enderror" name="taxi_car_year" type="number" min="1990" max="{{ (int) now()->format('Y') }}" value="{{ old('taxi_car_year', $profile->taxi_car_year ?? now()->format('Y')) }}" required>
                                                @error('taxi_car_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-2">
                                                <label class="form-label fw-semibold">المقاعد</label>
                                                <input class="form-control @error('taxi_seats') is-invalid @enderror" name="taxi_seats" type="number" min="1" max="8" value="{{ old('taxi_seats', $profile->taxi_seats) }}" required>
                                                @error('taxi_seats')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">رقم التأمين</label>
                                                <input class="form-control @error('taxi_insurance_no') is-invalid @enderror" name="taxi_insurance_no" value="{{ old('taxi_insurance_no', $profile->taxi_insurance_no) }}" placeholder="اختياري">
                                                @error('taxi_insurance_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">انتهاء التأمين</label>
                                                <input type="date" class="form-control @error('taxi_insurance_expiry') is-invalid @enderror" name="taxi_insurance_expiry" value="{{ old('taxi_insurance_expiry', optional($profile->taxi_insurance_expiry)->format('Y-m-d')) }}">
                                                @error('taxi_insurance_expiry')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1" id="taxi_has_ac" name="taxi_has_ac" @checked(old('taxi_has_ac', $profile->taxi_has_ac) ? true : false)>
                                                    <label class="form-check-label" for="taxi_has_ac">مكيف</label>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1" id="taxi_allows_smoking" name="taxi_allows_smoking" @checked(old('taxi_allows_smoking', $profile->taxi_allows_smoking) ? true : false)>
                                                    <label class="form-check-label" for="taxi_allows_smoking">يسمح بالتدخين</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade @if($taxiActivePane === 'taxi-pane-pricing') show active @endif" id="taxi-pane-pricing" role="tabpanel" tabindex="0" aria-labelledby="taxi-tab-pricing">
                                        <div class="section-title mb-3">
                                            <h2 class="h6 mb-0">الخطوة 3</h2>
                                            <span class="helper small">التعرفة، مناطق التغطية، الملاحظات</span>
                                        </div>
                                        @php($pm = old('taxi_pricing_mode', $profile->taxi_pricing_mode ?? 'simple'))
                                        <div class="row g-3">
                                            <div class="col-12 col-md-4">
                                                <label class="form-label fw-semibold">طريقة التسعير</label>
                                                <select class="form-select @error('taxi_pricing_mode') is-invalid @enderror" name="taxi_pricing_mode" id="taxi_pricing_mode" required>
                                                    <option value="simple" @selected($pm==='simple')>بسيط (فتح + كم + حد أدنى)</option>
                                                    <option value="meter" @selected($pm==='meter')>على العدّاد (إضافة سعر دقيقة)</option>
                                                </select>
                                                @error('taxi_pricing_mode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label class="form-label fw-semibold">سعر بدء (فتح)</label>
                                                <input class="form-control @error('taxi_base_fare') is-invalid @enderror" name="taxi_base_fare" type="number" step="0.01" min="0" value="{{ old('taxi_base_fare', $profile->taxi_base_fare ?? 0) }}" required>
                                                @error('taxi_base_fare')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <label class="form-label fw-semibold">الحد الأدنى</label>
                                                <input class="form-control @error('taxi_min_fare') is-invalid @enderror" name="taxi_min_fare" type="number" step="0.01" min="0" value="{{ old('taxi_min_fare', $profile->taxi_min_fare ?? 0) }}" required>
                                                @error('taxi_min_fare')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">سعر الكيلومتر</label>
                                                <input class="form-control @error('taxi_price_per_km') is-invalid @enderror" name="taxi_price_per_km" type="number" step="0.01" min="0" value="{{ old('taxi_price_per_km', $profile->taxi_price_per_km ?? 0) }}" required>
                                                @error('taxi_price_per_km')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6" id="taxi_price_per_minute_wrap" style="{{ $pm==='meter' ? '' : 'display:none;' }}">
                                                <label class="form-label fw-semibold">سعر الدقيقة</label>
                                                <input class="form-control @error('taxi_price_per_minute') is-invalid @enderror" name="taxi_price_per_minute" type="number" step="0.01" min="0" value="{{ old('taxi_price_per_minute', $profile->taxi_price_per_minute ?? 0) }}">
                                                @error('taxi_price_per_minute')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                                    <label class="form-label fw-semibold mb-0">مناطق التغطية</label>
                                                    <div class="d-flex flex-wrap align-items-center gap-2">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-soft" id="taxi_cov_select_all" data-bs-toggle="tooltip" title="تحديد كل المناطق">
                                                            <i class="bi bi-ui-checks"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-soft" id="taxi_cov_clear" data-bs-toggle="tooltip" title="إلغاء التحديد">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="taxi_coverage_picker" class="coverage-picker @error('taxi_coverage_area_keys') is-invalid-box @enderror">
                                                    <div class="row g-2">
                                                        @foreach($coverageRegionOptions as $areaKey => $meta)
                                                            @php($cid = 'taxi_cov_'.$loop->index)
                                                            <div class="col-12 col-sm-6 col-lg-4">
                                                                <input
                                                                    type="checkbox"
                                                                    class="btn-check taxi-cov-cb"
                                                                    name="taxi_coverage_area_keys[]"
                                                                    id="{{ $cid }}"
                                                                    value="{{ $areaKey }}"
                                                                    autocomplete="off"
                                                                    @checked(in_array($areaKey, $taxiCoverageSelected, true))
                                                                >
                                                                <label class="btn btn-outline-secondary w-100 coverage-tile d-flex align-items-center gap-2" for="{{ $cid }}">
                                                                    <i class="bi {{ $meta['icon'] }} fs-5 opacity-75 flex-shrink-0" aria-hidden="true"></i>
                                                                    <span class="text-start lh-sm">{{ $meta['label'] }}</span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @error('taxi_coverage_area_keys')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                                                <div class="helper small mt-2">اضغط على البطاقات لاختيار منطقة واحدة أو أكثر.</div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">ملاحظات</label>
                                                <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3" placeholder="ملاحظات داخلية للسوبر أدمن">{{ old('notes', $profile->notes) }}</textarea>
                                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade @if($taxiActivePane === 'taxi-pane-docs') show active @endif" id="taxi-pane-docs" role="tabpanel" tabindex="0" aria-labelledby="taxi-tab-docs">
                                        <div class="section-title mb-3">
                                            <h2 class="h6 mb-0">الخطوة 4</h2>
                                            <span class="helper small">رفع اختياري (PNG / JPG / WEBP)</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">صورة الهوية</label>
                                                <input type="file" class="form-control @error('id_document_image') is-invalid @enderror" name="id_document_image" accept="image/*">
                                                @error('id_document_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">صورة الرخصة</label>
                                                <input type="file" class="form-control @error('license_image') is-invalid @enderror" name="license_image" accept="image/*">
                                                @error('license_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">صورة المركبة</label>
                                                <input type="file" class="form-control @error('vehicle_image') is-invalid @enderror" name="vehicle_image" accept="image/*">
                                                @error('vehicle_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="taxi-step-footer mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <button type="button" class="btn btn-light btn-soft" id="taxiStepPrev">
                                    <i class="bi bi-arrow-right ms-1"></i> السابق
                                </button>
                                <div class="d-flex flex-wrap gap-2 ms-md-auto">
                                    <button type="button" class="btn btn-outline-secondary btn-soft" id="taxiStepNext">
                                        التالي <i class="bi bi-arrow-left me-1"></i>
                                    </button>
                                    <button type="submit" class="btn btn-outline-secondary btn-soft d-none" id="taxiStepSave">
                                        <i class="bi bi-save2"></i>
                                        <span class="ms-1">حفظ</span>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const sel = document.getElementById('taxi_pricing_mode');
                                const wrap = document.getElementById('taxi_price_per_minute_wrap');
                                if (sel && wrap) {
                                    const syncPricing = function () {
                                        wrap.style.display = (sel.value === 'meter') ? '' : 'none';
                                    };
                                    sel.addEventListener('change', syncPricing);
                                    syncPricing();
                                }

                                const pickAll = document.getElementById('taxi_cov_select_all');
                                const pickClear = document.getElementById('taxi_cov_clear');
                                const boxes = document.querySelectorAll('#taxi_coverage_picker .taxi-cov-cb');
                                const pickerBox = document.getElementById('taxi_coverage_picker');
                                if (pickAll && pickClear && boxes.length) {
                                    const syncInvalid = function () {
                                        const any = Array.prototype.some.call(boxes, function (x) { return x.checked; });
                                        if (any && pickerBox) pickerBox.classList.remove('is-invalid-box');
                                    };
                                    pickAll.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        boxes.forEach(function (b) { b.checked = true; });
                                        syncInvalid();
                                    });
                                    pickClear.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        boxes.forEach(function (b) { b.checked = false; });
                                    });
                                    boxes.forEach(function (b) {
                                        b.addEventListener('change', syncInvalid);
                                    });
                                }

                                const stepNav = document.getElementById('taxiStepNav');
                                const stepTriggers = stepNav ? Array.prototype.slice.call(stepNav.querySelectorAll('[data-bs-toggle="tab"]')) : [];
                                const btnPrev = document.getElementById('taxiStepPrev');
                                const btnNext = document.getElementById('taxiStepNext');
                                const btnSave = document.getElementById('taxiStepSave');
                                if (stepNav && stepTriggers.length) {
                                    let activeIdx = stepTriggers.findIndex(function (b) { return b.classList.contains('active'); });
                                    if (activeIdx < 0) activeIdx = 0;

                                    const syncSteps = function () {
                                        if (btnPrev) {
                                            btnPrev.classList.toggle('invisible', activeIdx <= 0);
                                            btnPrev.setAttribute('aria-disabled', activeIdx <= 0 ? 'true' : 'false');
                                        }
                                        if (btnNext && btnSave) {
                                            const last = activeIdx >= stepTriggers.length - 1;
                                            btnNext.classList.toggle('d-none', last);
                                            btnSave.classList.toggle('d-none', !last);
                                        }
                                        stepTriggers.forEach(function (b, i) {
                                            b.setAttribute('aria-selected', i === activeIdx ? 'true' : 'false');
                                        });
                                    };

                                    stepNav.addEventListener('shown.bs.tab', function (e) {
                                        activeIdx = stepTriggers.indexOf(e.target);
                                        if (activeIdx < 0) activeIdx = 0;
                                        syncSteps();
                                    });

                                    btnPrev?.addEventListener('click', function () {
                                        if (activeIdx > 0) {
                                            bootstrap.Tab.getOrCreateInstance(stepTriggers[activeIdx - 1]).show();
                                        }
                                    });
                                    btnNext?.addEventListener('click', function () {
                                        if (activeIdx < stepTriggers.length - 1) {
                                            bootstrap.Tab.getOrCreateInstance(stepTriggers[activeIdx + 1]).show();
                                        }
                                    });

                                    syncSteps();
                                }
                            });
                        </script>
                    @elseif($profile->provider_type === 'water_tanker')
                        <div class="section-title">
                            <h2>صهاريج المياه</h2>
                            <span class="helper small"><i class="bi bi-droplet-half" style="color: var(--brand);"></i> توصيل مياه — تغطية طفس والمزارع المحيطة فقط</span>
                        </div>

                        <form id="waterProviderForm" method="POST" action="{{ route('super-admin.providers.update', $profile) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <ul class="nav nav-pills taxi-step-nav d-flex mb-3" id="waterStepNav" role="tablist">
                                <li class="nav-item flex-grow-1" role="presentation">
                                    <button class="nav-link w-100 h-100 @if($waterActivePane === 'water-pane-identity') active @endif" id="water-tab-identity" data-bs-toggle="tab" data-bs-target="#water-pane-identity" type="button" role="tab" aria-controls="water-pane-identity" @if($waterActivePane === 'water-pane-identity') aria-selected="true" @else aria-selected="false" @endif>
                                        <span class="step-idx">1</span>الهوية والحساب
                                    </button>
                                </li>
                                <li class="nav-item flex-grow-1" role="presentation">
                                    <button class="nav-link w-100 h-100 @if($waterActivePane === 'water-pane-tank') active @endif" id="water-tab-tank" data-bs-toggle="tab" data-bs-target="#water-pane-tank" type="button" role="tab" aria-controls="water-pane-tank" @if($waterActivePane === 'water-pane-tank') aria-selected="true" @else aria-selected="false" @endif>
                                        <span class="step-idx">2</span>الصهريج والتجهيزات
                                    </button>
                                </li>
                                <li class="nav-item flex-grow-1" role="presentation">
                                    <button class="nav-link w-100 h-100 @if($waterActivePane === 'water-pane-service') active @endif" id="water-tab-service" data-bs-toggle="tab" data-bs-target="#water-pane-service" type="button" role="tab" aria-controls="water-pane-service" @if($waterActivePane === 'water-pane-service') aria-selected="true" @else aria-selected="false" @endif>
                                        <span class="step-idx">3</span>التسعير والتغطية
                                    </button>
                                </li>
                                <li class="nav-item flex-grow-1" role="presentation">
                                    <button class="nav-link w-100 h-100 @if($waterActivePane === 'water-pane-docs') active @endif" id="water-tab-docs" data-bs-toggle="tab" data-bs-target="#water-pane-docs" type="button" role="tab" aria-controls="water-pane-docs" @if($waterActivePane === 'water-pane-docs') aria-selected="true" @else aria-selected="false" @endif>
                                        <span class="step-idx">4</span>المستندات
                                    </button>
                                </li>
                            </ul>

                            <div class="taxi-tab-shell">
                                <div class="tab-content" id="waterStepContent">
                                    <div class="tab-pane fade @if($waterActivePane === 'water-pane-identity') show active @endif" id="water-pane-identity" role="tabpanel" tabindex="0">
                                        <div class="section-title mb-3">
                                            <h2 class="h6 mb-0">الخطوة 1</h2>
                                            <span class="helper small">ربط الحساب واسم العرض والرقم الوطني (اختياري)</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">اسم المستخدم</label>
                                                <input class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old('user_name', $profile->user?->name) }}" required>
                                                @error('user_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">رقم الجوال</label>
                                                <input class="form-control @error('user_phone') is-invalid @enderror" name="user_phone" value="{{ old('user_phone', $profile->user?->phone) }}" required dir="ltr">
                                                @error('user_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">الاسم الكامل (للعرض في القطاع)</label>
                                                <input class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{ old('full_name', $profile->full_name ?? $profile->user?->name) }}" required>
                                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">الرقم الوطني</label>
                                                <input class="form-control @error('national_id') is-invalid @enderror" name="national_id" value="{{ old('national_id', $profile->national_id) }}" placeholder="اختياري">
                                                @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade @if($waterActivePane === 'water-pane-tank') show active @endif" id="water-pane-tank" role="tabpanel" tabindex="0">
                                        <div class="section-title mb-3">
                                            <h2 class="h6 mb-0">الخطوة 2</h2>
                                            <span class="helper small">سعة الصهريج، الخرطوم، والمضخة إن وُجدت</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">سعة الصهريج (لتر)</label>
                                                <input class="form-control @error('water_capacity_liters') is-invalid @enderror" name="water_capacity_liters" type="number" min="200" max="80000" value="{{ old('water_capacity_liters', $profile->water_capacity_liters) }}" required>
                                                @error('water_capacity_liters')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                <div class="helper small mt-1">مثال: 3000، 5000، 10000…</div>
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">طول الخرطوم (متر)</label>
                                                <input class="form-control @error('water_hose_length_m') is-invalid @enderror" name="water_hose_length_m" type="number" min="0" max="300" value="{{ old('water_hose_length_m', $profile->water_hose_length_m) }}" placeholder="اختياري">
                                                @error('water_hose_length_m')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6 d-flex align-items-end">
                                                <div class="form-check mb-2">
                                                    <input class="form-check-input" type="checkbox" value="1" id="water_has_pump" name="water_has_pump" @checked(old('water_has_pump', $profile->water_has_pump) ? true : false)>
                                                    <label class="form-check-label" for="water_has_pump">مجهّز بمضخة تفريغ</label>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" value="1" id="water_potable_declared" name="water_potable_declared" @checked(old('water_potable_declared', $profile->water_potable_declared) ? true : false)>
                                                    <label class="form-check-label" for="water_potable_declared">يقرّ المزوّد أن المياه مخصّصة للاستخدام المنزلي المعتاد وفق الاشتراطات المحلية</label>
                                                </div>
                                                <div class="helper small mt-1">خانة توثيق إداري؛ لا تغني عن الفحوصات الرسمية عند الحاجة.</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade @if($waterActivePane === 'water-pane-service') show active @endif" id="water-pane-service" role="tabpanel" tabindex="0">
                                        <div class="section-title mb-3">
                                            <h2 class="h6 mb-0">الخطوة 3</h2>
                                            <span class="helper small">سياسة السعر، الحد الأدنى للطلب، ومناطق التوصيل</span>
                                        </div>
                                        @php($wpm = old('water_pricing_mode', $profile->water_pricing_mode ?? 'per_tank'))
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">أسلوب التسعير</label>
                                                <select class="form-select @error('water_pricing_mode') is-invalid @enderror" name="water_pricing_mode" id="water_pricing_mode" required>
                                                    <option value="per_tank" @selected($wpm==='per_tank')>بناءً على تعبئة كاملة (صهريج)</option>
                                                    <option value="per_liter" @selected($wpm==='per_liter')>بناءً على اللتر</option>
                                                </select>
                                                @error('water_pricing_mode')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">حد أدنى للطلب (لتر)</label>
                                                <input class="form-control @error('water_min_order_liters') is-invalid @enderror" name="water_min_order_liters" type="number" min="0" value="{{ old('water_min_order_liters', $profile->water_min_order_liters) }}" placeholder="اختياري">
                                                @error('water_min_order_liters')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6" id="water_price_tank_wrap" style="{{ $wpm==='per_tank' ? '' : 'display:none;' }}">
                                                <label class="form-label fw-semibold">سعر تعبئة الصهريج (كاملة)</label>
                                                <input class="form-control @error('water_price_per_tank') is-invalid @enderror" name="water_price_per_tank" type="number" step="0.01" min="0" value="{{ old('water_price_per_tank', $profile->water_price_per_tank) }}">
                                                @error('water_price_per_tank')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6" id="water_price_liter_wrap" style="{{ $wpm==='per_liter' ? '' : 'display:none;' }}">
                                                <label class="form-label fw-semibold">سعر اللتر</label>
                                                <input class="form-control @error('water_price_per_liter') is-invalid @enderror" name="water_price_per_liter" type="number" step="0.0001" min="0" value="{{ old('water_price_per_liter', $profile->water_price_per_liter) }}">
                                                @error('water_price_per_liter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                                    <label class="form-label fw-semibold mb-0">مناطق التوصيل</label>
                                                    <div class="d-flex flex-wrap gap-2">
                                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-soft" id="water_cov_select_all" data-bs-toggle="tooltip" title="تحديد الكل">
                                                            <i class="bi bi-ui-checks"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-soft" id="water_cov_clear" data-bs-toggle="tooltip" title="مسح التحديد">
                                                            <i class="bi bi-x-lg"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div id="water_coverage_picker" class="coverage-picker @error('water_service_area_keys') is-invalid-box @enderror">
                                                    <div class="row g-2">
                                                        @foreach($waterCoverageRegionOptions as $areaKey => $meta)
                                                            @php($wid = 'water_cov_'.$loop->index)
                                                            <div class="col-12 col-sm-6 col-lg-4">
                                                                <input type="checkbox" class="btn-check water-cov-cb" name="water_service_area_keys[]" id="{{ $wid }}" value="{{ $areaKey }}" autocomplete="off" @checked(in_array($areaKey, $waterCoverageSelected, true))>
                                                                <label class="btn btn-outline-secondary w-100 coverage-tile d-flex align-items-center gap-2" for="{{ $wid }}">
                                                                    <i class="bi {{ $meta['icon'] }} fs-5 opacity-75 flex-shrink-0" aria-hidden="true"></i>
                                                                    <span class="text-start lh-sm">{{ $meta['label'] }}</span>
                                                                </label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                @error('water_service_area_keys')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                                                <div class="helper small mt-2">تغطية العمل ضمن طفس والمزارع المحيطة فقط.</div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">ملاحظات داخلية</label>
                                                <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3" placeholder="مواعيد العمل، قيود الوصول، تعليمات للسائق…">{{ old('notes', $profile->notes) }}</textarea>
                                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade @if($waterActivePane === 'water-pane-docs') show active @endif" id="water-pane-docs" role="tabpanel" tabindex="0">
                                        <div class="section-title mb-3">
                                            <h2 class="h6 mb-0">الخطوة 4</h2>
                                            <span class="helper small">صور للتوثيق (هوية، مستندات، صهريج…)</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">صورة الهوية</label>
                                                <input type="file" class="form-control @error('id_document_image') is-invalid @enderror" name="id_document_image" accept="image/*">
                                                @error('id_document_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">صورة الرخصة</label>
                                                <input type="file" class="form-control @error('license_image') is-invalid @enderror" name="license_image" accept="image/*">
                                                @error('license_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">صورة المركبة أو الصهريج</label>
                                                <input type="file" class="form-control @error('vehicle_image') is-invalid @enderror" name="vehicle_image" accept="image/*">
                                                @error('vehicle_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="taxi-step-footer mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <button type="button" class="btn btn-light btn-soft" id="waterStepPrev"><i class="bi bi-arrow-right ms-1"></i> السابق</button>
                                <div class="d-flex flex-wrap gap-2 ms-md-auto">
                                    <button type="button" class="btn btn-outline-secondary btn-soft" id="waterStepNext">التالي <i class="bi bi-arrow-left me-1"></i></button>
                                    <button type="submit" class="btn btn-outline-secondary btn-soft d-none" id="waterStepSave"><i class="bi bi-save2"></i><span class="ms-1">حفظ</span></button>
                                </div>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const wSel = document.getElementById('water_pricing_mode');
                                const wTank = document.getElementById('water_price_tank_wrap');
                                const wLiter = document.getElementById('water_price_liter_wrap');
                                if (wSel && wTank && wLiter) {
                                    const syncWaterPricing = function () {
                                        const perTank = wSel.value === 'per_tank';
                                        wTank.style.display = perTank ? '' : 'none';
                                        wLiter.style.display = perTank ? 'none' : '';
                                    };
                                    wSel.addEventListener('change', syncWaterPricing);
                                    syncWaterPricing();
                                }

                                const wPickAll = document.getElementById('water_cov_select_all');
                                const wPickClear = document.getElementById('water_cov_clear');
                                const wBoxes = document.querySelectorAll('#water_coverage_picker .water-cov-cb');
                                const wPickerBox = document.getElementById('water_coverage_picker');
                                if (wPickAll && wPickClear && wBoxes.length) {
                                    const wSyncInv = function () {
                                        const any = Array.prototype.some.call(wBoxes, function (x) { return x.checked; });
                                        if (any && wPickerBox) wPickerBox.classList.remove('is-invalid-box');
                                    };
                                    wPickAll.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        wBoxes.forEach(function (b) { b.checked = true; });
                                        wSyncInv();
                                    });
                                    wPickClear.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        wBoxes.forEach(function (b) { b.checked = false; });
                                    });
                                    wBoxes.forEach(function (b) { b.addEventListener('change', wSyncInv); });
                                }

                                const wNav = document.getElementById('waterStepNav');
                                const wTriggers = wNav ? Array.prototype.slice.call(wNav.querySelectorAll('[data-bs-toggle="tab"]')) : [];
                                const wPrev = document.getElementById('waterStepPrev');
                                const wNext = document.getElementById('waterStepNext');
                                const wSave = document.getElementById('waterStepSave');
                                if (wNav && wTriggers.length) {
                                    let wIdx = wTriggers.findIndex(function (b) { return b.classList.contains('active'); });
                                    if (wIdx < 0) wIdx = 0;
                                    const wSyncSteps = function () {
                                        if (wPrev) {
                                            wPrev.classList.toggle('invisible', wIdx <= 0);
                                            wPrev.setAttribute('aria-disabled', wIdx <= 0 ? 'true' : 'false');
                                        }
                                        if (wNext && wSave) {
                                            const last = wIdx >= wTriggers.length - 1;
                                            wNext.classList.toggle('d-none', last);
                                            wSave.classList.toggle('d-none', !last);
                                        }
                                        wTriggers.forEach(function (b, i) {
                                            b.setAttribute('aria-selected', i === wIdx ? 'true' : 'false');
                                        });
                                    };
                                    wNav.addEventListener('shown.bs.tab', function (e) {
                                        wIdx = wTriggers.indexOf(e.target);
                                        if (wIdx < 0) wIdx = 0;
                                        wSyncSteps();
                                    });
                                    wPrev?.addEventListener('click', function () {
                                        if (wIdx > 0) bootstrap.Tab.getOrCreateInstance(wTriggers[wIdx - 1]).show();
                                    });
                                    wNext?.addEventListener('click', function () {
                                        if (wIdx < wTriggers.length - 1) bootstrap.Tab.getOrCreateInstance(wTriggers[wIdx + 1]).show();
                                    });
                                    wSyncSteps();
                                }
                            });
                        </script>
                    @elseif($profile->provider_type === 'workshop')
                        <div class="section-title">
                            <h2>ورش الخدمات</h2>
                            <span class="helper small"><i class="bi bi-tools" style="color: var(--brand);"></i> تصنيفات واسعة لطفس ودرعا والأرياف — تسهّل البحث والفرز لاحقاً في التطبيق</span>
                        </div>

                        <form id="workshopProviderForm" method="POST" action="{{ route('super-admin.providers.update', $profile) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <ul class="nav nav-pills taxi-step-nav d-flex mb-3" id="workshopStepNav" role="tablist">
                                <li class="nav-item flex-grow-1" role="presentation">
                                    <button class="nav-link w-100 h-100 @if($workshopActivePane === 'workshop-pane-identity') active @endif" id="workshop-tab-identity" data-bs-toggle="tab" data-bs-target="#workshop-pane-identity" type="button" role="tab" aria-controls="workshop-pane-identity" @if($workshopActivePane === 'workshop-pane-identity') aria-selected="true" @else aria-selected="false" @endif>
                                        <span class="step-idx">1</span>الهوية والحساب
                                    </button>
                                </li>
                                <li class="nav-item flex-grow-1" role="presentation">
                                    <button class="nav-link w-100 h-100 @if($workshopActivePane === 'workshop-pane-cats') active @endif" id="workshop-tab-cats" data-bs-toggle="tab" data-bs-target="#workshop-pane-cats" type="button" role="tab" aria-controls="workshop-pane-cats" @if($workshopActivePane === 'workshop-pane-cats') aria-selected="true" @else aria-selected="false" @endif>
                                        <span class="step-idx">2</span>التصنيفات
                                    </button>
                                </li>
                                <li class="nav-item flex-grow-1" role="presentation">
                                    <button class="nav-link w-100 h-100 @if($workshopActivePane === 'workshop-pane-search') active @endif" id="workshop-tab-search" data-bs-toggle="tab" data-bs-target="#workshop-pane-search" type="button" role="tab" aria-controls="workshop-pane-search" @if($workshopActivePane === 'workshop-pane-search') aria-selected="true" @else aria-selected="false" @endif>
                                        <span class="step-idx">3</span>الظهور في البحث
                                    </button>
                                </li>
                                <li class="nav-item flex-grow-1" role="presentation">
                                    <button class="nav-link w-100 h-100 @if($workshopActivePane === 'workshop-pane-docs') active @endif" id="workshop-tab-docs" data-bs-toggle="tab" data-bs-target="#workshop-pane-docs" type="button" role="tab" aria-controls="workshop-pane-docs" @if($workshopActivePane === 'workshop-pane-docs') aria-selected="true" @else aria-selected="false" @endif>
                                        <span class="step-idx">4</span>المستندات
                                    </button>
                                </li>
                            </ul>

                            <div class="taxi-tab-shell">
                                <div class="tab-content" id="workshopStepContent">
                                    <div class="tab-pane fade @if($workshopActivePane === 'workshop-pane-identity') show active @endif" id="workshop-pane-identity" role="tabpanel" tabindex="0">
                                        <div class="section-title mb-3">
                                            <h2 class="h6 mb-0">الخطوة 1</h2>
                                            <span class="helper small">ربط الحساب واسم صاحب الورشة</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">اسم المستخدم</label>
                                                <input class="form-control @error('user_name') is-invalid @enderror" name="user_name" value="{{ old('user_name', $profile->user?->name) }}" required>
                                                @error('user_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">رقم الجوال</label>
                                                <input class="form-control @error('user_phone') is-invalid @enderror" name="user_phone" dir="ltr" value="{{ old('user_phone', $profile->user?->phone) }}" required>
                                                @error('user_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">الاسم الكامل لصاحب الورشة</label>
                                                <input class="form-control @error('full_name') is-invalid @enderror" name="full_name" value="{{ old('full_name', $profile->full_name) }}" required placeholder="مثال: ورشة أبو ...">
                                                @error('full_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">الرقم الوطني</label>
                                                <input class="form-control @error('national_id') is-invalid @enderror" name="national_id" value="{{ old('national_id', $profile->national_id) }}">
                                                @error('national_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">رقم رخصة/سجل (إن وُجد)</label>
                                                <input class="form-control @error('license_no') is-invalid @enderror" name="license_no" value="{{ old('license_no', $profile->license_no) }}">
                                                @error('license_no')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">انتهاء الرخصة</label>
                                                <input class="form-control @error('license_expiry') is-invalid @enderror" name="license_expiry" type="date" value="{{ old('license_expiry', optional($profile->license_expiry)->format('Y-m-d')) }}">
                                                @error('license_expiry')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade @if($workshopActivePane === 'workshop-pane-cats') show active @endif" id="workshop-pane-cats" role="tabpanel" tabindex="0">
                                        <div class="section-title mb-3">
                                            <h2 class="h6 mb-0">الخطوة 2</h2>
                                            <span class="helper small">اختَر كل ما تنطبق عليه الورشة — يمكن حتى 15 تصنيفاً</span>
                                        </div>
                                        <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-2">
                                            <label class="form-label fw-semibold mb-0">خدمات وتصنيفات</label>
                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-soft" id="wk_cov_select_all" data-bs-toggle="tooltip" title="تحديد كل البطاقات">
                                                    <i class="bi bi-ui-checks"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-soft" id="wk_cov_clear" data-bs-toggle="tooltip" title="إلغاء التحديد">
                                                    <i class="bi bi-x-lg"></i>
                                                </button>
                                            </div>
                                        </div>
                                        <div id="workshop_category_picker" class="coverage-picker @error('workshop_category_keys') is-invalid-box @enderror">
                                            <div class="accordion" id="workshopCatAccordion">
                                                @foreach($workshopCategoryGroups as $gid => $group)
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header">
                                                            <button class="accordion-button {{ $loop->first ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#wk-acc-{{ $gid }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}" aria-controls="wk-acc-{{ $gid }}">
                                                                <i class="bi {{ $group['icon'] }} ms-2 opacity-75" aria-hidden="true"></i>
                                                                {{ $group['label'] }}
                                                            </button>
                                                        </h2>
                                                        <div id="wk-acc-{{ $gid }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}">
                                                            <div class="accordion-body pt-3">
                                                                <div class="row g-2">
                                                                    @foreach($group['items'] as $key => $item)
                                                                        @php($wkCid = 'wk_cat_'.$gid.'_'.$key)
                                                                        <div class="col-12 col-sm-6 col-lg-4">
                                                                            <input
                                                                                type="checkbox"
                                                                                class="btn-check workshop-cat-cb"
                                                                                name="workshop_category_keys[]"
                                                                                id="{{ $wkCid }}"
                                                                                value="{{ $key }}"
                                                                                autocomplete="off"
                                                                                @checked(in_array($key, $workshopCategorySelected, true))
                                                                            >
                                                                            <label class="btn btn-outline-secondary w-100 coverage-tile d-flex align-items-center gap-2" for="{{ $wkCid }}">
                                                                                <i class="bi {{ $item['icon'] }} fs-5 opacity-75 flex-shrink-0" aria-hidden="true"></i>
                                                                                <span class="text-start lh-sm">{{ $item['label'] }}</span>
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @error('workshop_category_keys')<div class="invalid-feedback d-block mt-2">{{ $message }}</div>@enderror
                                        <div class="helper small mt-2 mb-3">كل مجموعة قابلة للطي — اختر كل ما ينطبق على الورشة.</div>

                                        <label class="form-label fw-semibold">تفاصيل إضافية عند اختيار «تصنيف آخر»</label>
                                        <input class="form-control @error('workshop_skill_other') is-invalid @enderror" name="workshop_skill_other" value="{{ old('workshop_skill_other', $profile->workshop_skill_other) }}" placeholder="مثال: تركيب كاميرات، لحام تخصصي…">
                                        @error('workshop_skill_other')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <div class="helper small mt-1">يُطلب هذا الحقل عند تفعيل بطاقة «تصنيف آخر».</div>
                                    </div>

                                    <div class="tab-pane fade @if($workshopActivePane === 'workshop-pane-search') show active @endif" id="workshop-pane-search" role="tabpanel" tabindex="0">
                                        <div class="section-title mb-3">
                                            <h2 class="h6 mb-0">الخطوة 3</h2>
                                            <span class="helper small">حقول تساعد الزبون على إيجاد الورشة في التطبيق</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">المنطقة / الحي / القرية</label>
                                                <input class="form-control @error('workshop_neighborhood') is-invalid @enderror" name="workshop_neighborhood" value="{{ old('workshop_neighborhood', $profile->workshop_neighborhood) }}" placeholder="مثال: طفس، المزة، نوى، طريق درعا…">
                                                @error('workshop_neighborhood')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12 col-md-6">
                                                <label class="form-label fw-semibold">سنوات الخبرة التقريبية</label>
                                                <input class="form-control @error('workshop_years_experience') is-invalid @enderror" name="workshop_years_experience" type="number" min="0" max="60" value="{{ old('workshop_years_experience', $profile->workshop_years_experience) }}" placeholder="0–60">
                                                @error('workshop_years_experience')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">جملة قصيرة للبحث (اختياري)</label>
                                                <textarea class="form-control @error('workshop_short_pitch') is-invalid @enderror" name="workshop_short_pitch" rows="2" maxlength="280" placeholder="مثال: سباكة منزلية، تركيب سخانات، كشف تسرب — خدمة سريعة في طفس">{{ old('workshop_short_pitch', $profile->workshop_short_pitch) }}</textarea>
                                                @error('workshop_short_pitch')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                                <div class="helper small mt-1">تُستخدم لاحقاً كملخص يظهر مع نتائج البحث.</div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-check">
                                                    <input type="hidden" name="workshop_home_visit" value="0">
                                                    <input class="form-check-input @error('workshop_home_visit') is-invalid @enderror" type="checkbox" name="workshop_home_visit" id="workshop_home_visit" value="1" @checked((string) old('workshop_home_visit', ($profile->workshop_home_visit ?? true) ? '1' : '0') === '1')>
                                                    <label class="form-check-label fw-semibold" for="workshop_home_visit">زيارة منزلية متاحة</label>
                                                    @error('workshop_home_visit')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                                </div>
                                                <div class="helper small mt-1">إن وُجدت مهمة تتطلّب الحضور للمنزل أو المزرعة.</div>
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">ملاحظات داخلية</label>
                                                <textarea class="form-control @error('notes') is-invalid @enderror" name="notes" rows="3" placeholder="مواعيد، طريقة التواصل، تفاصيل للسوبر أدمن…">{{ old('notes', $profile->notes) }}</textarea>
                                                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade @if($workshopActivePane === 'workshop-pane-docs') show active @endif" id="workshop-pane-docs" role="tabpanel" tabindex="0">
                                        <div class="section-title mb-3">
                                            <h2 class="h6 mb-0">الخطوة 4</h2>
                                            <span class="helper small">صور للتوثيق (PNG / JPG / WEBP)</span>
                                        </div>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">صورة الهوية</label>
                                                <input type="file" class="form-control @error('id_document_image') is-invalid @enderror" name="id_document_image" accept="image/*">
                                                @error('id_document_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">صورة الرخصة أو السجل</label>
                                                <input type="file" class="form-control @error('license_image') is-invalid @enderror" name="license_image" accept="image/*">
                                                @error('license_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                            <div class="col-12">
                                                <label class="form-label fw-semibold">صورة للورشة أو المعدات</label>
                                                <input type="file" class="form-control @error('vehicle_image') is-invalid @enderror" name="vehicle_image" accept="image/*">
                                                @error('vehicle_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="taxi-step-footer mt-3 d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <button type="button" class="btn btn-light btn-soft" id="workshopStepPrev"><i class="bi bi-arrow-right ms-1"></i> السابق</button>
                                <div class="d-flex flex-wrap gap-2 ms-md-auto">
                                    <button type="button" class="btn btn-outline-secondary btn-soft" id="workshopStepNext">التالي <i class="bi bi-arrow-left me-1"></i></button>
                                    <button type="submit" class="btn btn-outline-secondary btn-soft d-none" id="workshopStepSave"><i class="bi bi-save2"></i><span class="ms-1">حفظ</span></button>
                                </div>
                            </div>
                        </form>

                        <script>
                            document.addEventListener('DOMContentLoaded', function () {
                                const wkPickAll = document.getElementById('wk_cov_select_all');
                                const wkPickClear = document.getElementById('wk_cov_clear');
                                const wkBoxes = document.querySelectorAll('#workshop_category_picker .workshop-cat-cb');
                                const wkPickerBox = document.getElementById('workshop_category_picker');
                                if (wkPickAll && wkPickClear && wkBoxes.length) {
                                    const wkSyncInv = function () {
                                        const any = Array.prototype.some.call(wkBoxes, function (x) { return x.checked; });
                                        if (any && wkPickerBox) wkPickerBox.classList.remove('is-invalid-box');
                                    };
                                    wkPickAll.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        wkBoxes.forEach(function (b) { b.checked = true; });
                                        wkSyncInv();
                                    });
                                    wkPickClear.addEventListener('click', function (e) {
                                        e.preventDefault();
                                        wkBoxes.forEach(function (b) { b.checked = false; });
                                    });
                                    wkBoxes.forEach(function (b) { b.addEventListener('change', wkSyncInv); });
                                }

                                const wsNav = document.getElementById('workshopStepNav');
                                const wsTriggers = wsNav ? Array.prototype.slice.call(wsNav.querySelectorAll('[data-bs-toggle="tab"]')) : [];
                                const wsPrev = document.getElementById('workshopStepPrev');
                                const wsNext = document.getElementById('workshopStepNext');
                                const wsSave = document.getElementById('workshopStepSave');
                                if (wsNav && wsTriggers.length) {
                                    let wsIdx = wsTriggers.findIndex(function (b) { return b.classList.contains('active'); });
                                    if (wsIdx < 0) wsIdx = 0;
                                    const wsSyncSteps = function () {
                                        if (wsPrev) {
                                            wsPrev.classList.toggle('invisible', wsIdx <= 0);
                                            wsPrev.setAttribute('aria-disabled', wsIdx <= 0 ? 'true' : 'false');
                                        }
                                        if (wsNext && wsSave) {
                                            const last = wsIdx >= wsTriggers.length - 1;
                                            wsNext.classList.toggle('d-none', last);
                                            wsSave.classList.toggle('d-none', !last);
                                        }
                                        wsTriggers.forEach(function (b, i) {
                                            b.setAttribute('aria-selected', i === wsIdx ? 'true' : 'false');
                                        });
                                    };
                                    wsNav.addEventListener('shown.bs.tab', function (e) {
                                        wsIdx = wsTriggers.indexOf(e.target);
                                        if (wsIdx < 0) wsIdx = 0;
                                        wsSyncSteps();
                                    });
                                    wsPrev?.addEventListener('click', function () {
                                        if (wsIdx > 0) bootstrap.Tab.getOrCreateInstance(wsTriggers[wsIdx - 1]).show();
                                    });
                                    wsNext?.addEventListener('click', function () {
                                        if (wsIdx < wsTriggers.length - 1) bootstrap.Tab.getOrCreateInstance(wsTriggers[wsIdx + 1]).show();
                                    });
                                    wsSyncSteps();
                                }
                            });
                        </script>
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
                    <?php
                        $docs = [
                            ['key' => 'id_document_image', 'label' => 'صورة الهوية', 'url' => $doc($profile->id_document_image)],
                            ['key' => 'license_image', 'label' => 'صورة الرخصة', 'url' => $doc($profile->license_image)],
                            ['key' => 'vehicle_image', 'label' => 'صورة المركبة', 'url' => $doc($profile->vehicle_image)],
                        ];
                    ?>

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

