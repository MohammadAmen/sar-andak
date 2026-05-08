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

@php
    use App\Support\ProviderStaffScope;
    $navTypes = ProviderStaffScope::allowedTypesFor($superAdmin ?? null);
    if ($navTypes === null) {
        $navTypes = config('provider_ops.provider_types', []);
    }
    $routeProfile = request()->route('providerProfile');
    $activeProviderType = request()->query('type') ?: ($routeProfile?->provider_type);
@endphp
@foreach($navTypes as $pt)
    @php($meta = config('provider_ops.nav.'.$pt, ['label' => $pt, 'icon' => 'bi-grid']))
    <a class="side-link {{ request()->routeIs('super-admin.providers.*') && (string) $activeProviderType === (string) $pt ? 'active' : '' }}"
       href="{{ route('super-admin.providers.index', ['type' => $pt]) }}">
        <i class="bi {{ $meta['icon'] }}" aria-hidden="true"></i>
        <span>{{ $meta['label'] }}</span>
    </a>
@endforeach

<div class="sidebar-footer-hint">
    القائمة الجانبية ثابتة على الشاشات الواسعة، ومنزلقة على الجوال.
</div>
