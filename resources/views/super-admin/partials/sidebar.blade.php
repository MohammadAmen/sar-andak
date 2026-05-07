@php
    $active = function(string $routeName): string {
        return request()->routeIs($routeName) ? 'active' : '';
    };
@endphp

<div class="d-flex align-items-center gap-2 mb-4 pb-1 sidebar-brand-mini">
    <div class="brand-badge overflow-hidden flex-shrink-0" style="width:34px;height:34px;border-radius:10px;" aria-hidden="true">
        <img
            src="{{ asset('logo.png') }}"
            alt=""
            style="width: 100%; height: 100%; object-fit: contain;"
        />
    </div>
    <div class="lh-sm min-w-0">
        <div class="sb-app text-truncate">{{ config('app.name', 'Sar Andak') }}</div>
        <div class="sb-role">كل طلباتك بلمح البصر</div>
    </div>
</div>

<div class="sidebar-section-label">التنقل</div>

<a class="side-link {{ $active('super-admin.dashboard') }}" href="{{ route('super-admin.dashboard') }}">
    <i class="bi bi-speedometer2" aria-hidden="true"></i>
    <span>لوحة التحكم</span>
</a>

<a class="side-link {{ $active('super-admin.users.index') }}" href="{{ route('super-admin.users.index') }}">
    <i class="bi bi-people" aria-hidden="true"></i>
    <span>إدارة المستخدمين</span>
</a>

<div class="sidebar-section-label mt-4">مزوّدو الخدمة</div>

<a class="side-link {{ request()->routeIs('super-admin.providers.*') && request('type')==='delivery' ? 'active' : '' }}"
   href="{{ route('super-admin.providers.index', ['type' => 'delivery']) }}">
    <i class="bi bi-truck" aria-hidden="true"></i>
    <span>الدليفري</span>
</a>

<a class="side-link {{ request()->routeIs('super-admin.providers.*') && request('type')==='taxi' ? 'active' : '' }}"
   href="{{ route('super-admin.providers.index', ['type' => 'taxi']) }}">
    <i class="bi bi-car-front" aria-hidden="true"></i>
    <span>تكسي</span>
</a>

<a class="side-link {{ request()->routeIs('super-admin.providers.*') && request('type')==='water_tanker' ? 'active' : '' }}"
   href="{{ route('super-admin.providers.index', ['type' => 'water_tanker']) }}">
    <i class="bi bi-droplet-half" aria-hidden="true"></i>
    <span>صهاريج مياه</span>
</a>

<a class="side-link {{ request()->routeIs('super-admin.providers.*') && request('type')==='workshop' ? 'active' : '' }}"
   href="{{ route('super-admin.providers.index', ['type' => 'workshop']) }}">
    <i class="bi bi-tools" aria-hidden="true"></i>
    <span>ورشات</span>
</a>

<div class="sidebar-footer-hint">
    القائمة الجانبية ثابتة على الشاشات الواسعة، ومنزلقة على الجوال.
</div>
