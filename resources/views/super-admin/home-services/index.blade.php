@extends('super-admin.layout')

@section('title', config('app.name', 'Sar Andak').' - بطاقات الصفحة الرئيسية')
@section('subtitle', 'ما يظهر للعميل في التطبيق')
@section('main_class', 'container-fluid')

@section('content')
    @if(session('status'))
        <div class="alert alert-success border-0 shadow-sm mb-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <div class="d-flex align-items-start justify-content-between gap-3 flex-wrap mb-3">
        <div>
            <h1 class="h4 mb-1">بطاقات الخدمات للعميل</h1>
            <p class="muted mb-0">
                التعديلات تنعكس فورًا على تطبيق الجوال عبر الـ API. يمكنك تعطيل خدمة مؤقتًا مع رسالة توضح للعميل السبب، أو إبراز بطاقة ببادج قصير.
            </p>
        </div>
        <code class="small px-3 py-2 rounded-3 bg-light border text-secondary">GET /api/customer/home-services</code>
    </div>

    <div class="row g-3 g-lg-4">
        @foreach($services as $service)
            <div class="col-12 col-xl-6">
                <div class="card-pro bg-white h-100">
                    <div class="p-4 p-lg-5">
                        <div class="d-flex align-items-start justify-content-between gap-2 flex-wrap mb-3">
                            <div>
                                <div class="section-title mb-2">
                                    <h2 class="h6 mb-0">{{ $service->title }}</h2>
                                    <span class="chip small">المعرّف: {{ $service->slug }}</span>
                                </div>
                                <div class="d-flex flex-wrap gap-2">
                                    <form method="POST" action="{{ route('super-admin.home-services.reorder') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $service->id }}">
                                        <input type="hidden" name="direction" value="up">
                                        <button type="submit" class="btn btn-light btn-sm btn-soft" title="أعلى في القائمة" @if($loop->first) disabled @endif>
                                            <i class="bi bi-arrow-up" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('super-admin.home-services.reorder') }}" class="d-inline">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $service->id }}">
                                        <input type="hidden" name="direction" value="down">
                                        <button type="submit" class="btn btn-light btn-sm btn-soft" title="أسفل في القائمة" @if($loop->last) disabled @endif>
                                            <i class="bi bi-arrow-down" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @if($service->is_enabled)
                                <span class="chip" style="border-color: rgba(16,185,129,.25); background: rgba(16,185,129,.10);">مفعّلة</span>
                            @else
                                <span class="chip" style="border-color: rgba(239,68,68,.25); background: rgba(239,68,68,.10);">معطّلة</span>
                            @endif
                        </div>

                        <form method="POST" action="{{ route('super-admin.home-services.update', $service) }}">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="_form_id" value="{{ $service->id }}">
                            @php($editing = (int) old('_form_id') === (int) $service->id)

                            <div class="row g-3">
                                <div class="col-12 col-md-6">
                                    <label class="form-label small helper mb-1">العنوان</label>
                                    <input type="text" name="title" value="{{ $editing ? old('title', $service->title) : $service->title }}" class="form-control form-control-sm @error('title') is-invalid @enderror" required maxlength="120">
                                    @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small helper mb-1">مسار الشاشة في التطبيق</label>
                                    <input type="text" name="route_segment" value="{{ $editing ? old('route_segment', $service->route_segment) : $service->route_segment }}" class="form-control form-control-sm font-monospace @error('route_segment') is-invalid @enderror" required placeholder="/services/…">
                                    @error('route_segment')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label small helper mb-1">الوصف القصير</label>
                                    <textarea name="subtitle" rows="2" class="form-control form-control-sm @error('subtitle') is-invalid @enderror" required maxlength="500">{{ $editing ? old('subtitle', $service->subtitle) : $service->subtitle }}</textarea>
                                    @error('subtitle')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small helper mb-1">الأيقونة</label>
                                    <select name="icon_key" class="form-select form-select-sm @error('icon_key') is-invalid @enderror">
                                        @foreach($iconChoices as $val => $labelAr)
                                            <option value="{{ $val }}" @selected(($editing ? old('icon_key', $service->icon_key) : $service->icon_key) === $val)>{{ $labelAr }} ({{ $val }})</option>
                                        @endforeach
                                    </select>
                                    @error('icon_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small helper mb-1">لون التمييز</label>
                                    <select name="accent_key" class="form-select form-select-sm @error('accent_key') is-invalid @enderror">
                                        @foreach($accentChoices as $val => $labelAr)
                                            <option value="{{ $val }}" @selected(($editing ? old('accent_key', $service->accent_key) : $service->accent_key) === $val)>{{ $labelAr }}</option>
                                        @endforeach
                                    </select>
                                    @error('accent_key')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small helper mb-1">بادج اختياري (مثلاً: جديد)</label>
                                    <input type="text" name="badge_label" value="{{ $editing ? old('badge_label', $service->badge_label) : $service->badge_label }}" class="form-control form-control-sm @error('badge_label') is-invalid @enderror" maxlength="40" placeholder="فارغ = بدون بادج">
                                    @error('badge_label')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12 col-md-6">
                                    <label class="form-label small helper mb-1">تفعيل البطاقة</label>
                                    <div class="form-check form-switch mt-1">
                                        <input type="hidden" name="is_enabled" value="0">
                                        <input class="form-check-input" type="checkbox" name="is_enabled" value="1" id="en-{{ $service->id }}" @checked($editing ? (bool) (int) old('is_enabled', $service->is_enabled ? 1 : 0) : $service->is_enabled)>
                                        <label class="form-check-label small" for="en-{{ $service->id }}">ظهور الخدمة والسماح بالانتقال للشاشة</label>
                                    </div>
                                    @error('is_enabled')<div class="text-danger small">{{ $message }}</div>@enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-label small helper mb-1">رسالة عند التعطيل (تظهر للعميل عند الضغط على بطاقة معطّلة)</label>
                                    <input type="text" name="disabled_message" value="{{ $editing ? old('disabled_message', $service->disabled_message) : $service->disabled_message }}" class="form-control form-control-sm @error('disabled_message') is-invalid @enderror" maxlength="500" placeholder="مثال: الخدمة غير متاحة مؤقتًا — نعود قريبًا">
                                    @error('disabled_message')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="submit" class="btn btn-dark btn-soft px-4">حفظ {{ $service->title }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
