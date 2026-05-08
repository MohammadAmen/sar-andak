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
