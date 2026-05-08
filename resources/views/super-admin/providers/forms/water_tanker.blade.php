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
