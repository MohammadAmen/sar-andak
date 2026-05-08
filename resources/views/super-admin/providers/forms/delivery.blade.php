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
