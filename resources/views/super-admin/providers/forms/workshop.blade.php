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
