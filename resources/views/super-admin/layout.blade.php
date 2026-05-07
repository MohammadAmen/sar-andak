<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Sar Andak'))</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        :root{
            --brand: #f5b301;
            --brand-2: #ffcf3a;
            --ink: #111827;
        }
        html{
            font-size: 15px;
        }
        body{
            font-family: "Cairo", system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
            font-weight: 400;
            line-height: 1.55;
            letter-spacing: 0.01em;
            color: rgba(17,24,39,.88);
            -webkit-font-smoothing: antialiased;
            background:
                radial-gradient(1000px 480px at 12% 0%, rgba(245,179,1,.09), transparent 58%),
                radial-gradient(900px 440px at 88% 8%, rgba(255,207,58,.08), transparent 52%),
                linear-gradient(180deg, #ffffff 0%, #f6f8fc 100%);
            min-height: 100vh;
        }
        .nav-sa{
            background: rgba(255,255,255,.88);
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
            border-bottom: 1px solid rgba(17,24,39,.065);
            box-shadow: 0 1px 0 rgba(255,255,255,.7) inset, 0 6px 28px rgba(17,24,39,.045);
            min-height: 3.35rem;
            padding-top: .45rem;
            padding-bottom: .45rem;
        }
        .nav-sa .navbar-brand-block .nav-title{
            font-size: .95rem;
            font-weight: 600;
            color: rgba(17,24,39,.90);
            line-height: 1.25;
        }
        .nav-sa .navbar-brand-block .nav-sub{
            font-size: .72rem;
            font-weight: 500;
            color: rgba(17,24,39,.48);
            line-height: 1.2;
        }
        .brand-badge{
            width: 36px;
            height: 36px;
            border-radius: 11px;
            background: linear-gradient(145deg, var(--brand), var(--brand-2));
            display: inline-grid;
            place-items: center;
            box-shadow: 0 6px 16px rgba(245,179,1,.28), 0 2px 6px rgba(17,24,39,.06);
            flex-shrink: 0;
        }
        .app-shell{
            display: grid;
            grid-template-columns: minmax(248px, 268px) 1fr;
            gap: 0;
            min-height: calc(100vh - 3.35rem);
            max-width: 100%;
        }
        @media (max-width: 991.98px){
            .app-shell{ grid-template-columns: 1fr; }
        }
        .sidebar{
            background:
                linear-gradient(180deg, rgba(255,255,255,.99) 0%, rgba(249,250,252,.98) 55%, rgba(244,246,250,1) 100%);
            border-inline-start: 1px solid rgba(17,24,39,.055);
            box-shadow: inset 1px 0 0 rgba(255,255,255,.75);
            padding: 1rem .75rem 1.5rem;
            position: relative;
        }
        .sidebar::before{
            content: "";
            position: absolute;
            inset-inline-start: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, var(--brand), var(--brand-2));
            opacity: .45;
            border-radius: 0 3px 3px 0;
            pointer-events: none;
        }
        [dir="rtl"] .sidebar::before{
            border-radius: 3px 0 0 3px;
        }
        .sidebar-brand-mini .sb-app{
            font-size: .9rem;
            font-weight: 600;
            color: rgba(17,24,39,.88);
            line-height: 1.25;
        }
        .sidebar-brand-mini .sb-role{
            font-size: .68rem;
            font-weight: 500;
            color: rgba(17,24,39,.45);
        }
        .sidebar-section-label{
            font-size: .65rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: rgba(17,24,39,.42);
            margin-bottom: .5rem;
            padding-inline-start: .35rem;
        }
        .side-link{
            display: flex;
            align-items: center;
            gap: .55rem;
            padding: .5rem .65rem;
            border-radius: 11px;
            text-decoration: none;
            font-size: .875rem;
            font-weight: 500;
            color: rgba(17,24,39,.70);
            border: 1px solid transparent;
            transition: background .15s ease, color .15s ease, border-color .15s ease, box-shadow .15s ease;
        }
        .side-link i{
            font-size: 1.05rem;
            opacity: .72;
            flex-shrink: 0;
            width: 1.35rem;
            text-align: center;
        }
        .side-link:hover{
            color: rgba(17,24,39,.92);
            background: rgba(245,179,1,.08);
            border-color: rgba(245,179,1,.12);
        }
        .side-link.active{
            color: rgba(15,23,42,.95);
            background: linear-gradient(105deg, rgba(245,179,1,.14), rgba(255,207,58,.08));
            border-color: rgba(245,179,1,.2);
            font-weight: 600;
            box-shadow: 0 4px 14px rgba(245,179,1,.12);
            border-inline-end: 3px solid var(--brand);
            padding-inline-end: calc(.65rem - 3px);
        }
        .side-link.active i{
            opacity: 1;
            color: #b45309;
        }
        .sidebar-footer-hint{
            font-size: .72rem;
            line-height: 1.45;
            color: rgba(17,24,39,.45);
            padding: .65rem .5rem 0;
            border-top: 1px solid rgba(17,24,39,.06);
            margin-top: 1rem;
        }
        .chip{
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .28rem .55rem;
            border-radius: 999px;
            border: 1px solid rgba(17,24,39,.08);
            background: rgba(255,255,255,.92);
            font-size: .78rem;
            font-weight: 500;
            color: rgba(17,24,39,.62);
        }
        .offcanvas-sa .offcanvas-header{
            border-bottom: 1px solid rgba(17,24,39,.06);
            padding-bottom: 1rem;
        }
        .offcanvas-sa .offcanvas-title{
            font-size: .95rem;
            font-weight: 600;
        }
        .offcanvas-sa .offcanvas-body{
            padding-top: 1rem;
            background: linear-gradient(180deg, rgba(255,255,255,.98) 0%, rgba(247,249,253,1) 100%);
        }
        .card-pro{
            border: 1px solid rgba(17,24,39,.08);
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(17,24,39,.08);
        }
        .metric{
            border: 1px solid rgba(17,24,39,.08);
            border-radius: 16px;
            background: #fff;
        }
        .section-title{
            display:flex;
            align-items:center;
            justify-content:space-between;
            gap: 12px;
            margin-bottom: 12px;
        }
        .section-title h2{
            font-size: .9375rem;
            margin: 0;
            font-weight: 600;
            letter-spacing: 0;
            color: rgba(17,24,39,.88);
        }
        .soft-divider{
            border: 0;
            height: 1px;
            background: rgba(17,24,39,.06);
            margin: 18px 0;
        }
        .form-label{
            color: rgba(17,24,39,.78);
        }
        .form-control, .form-select{
            border-radius: 14px;
            border-color: rgba(17,24,39,.12);
        }
        .form-control:focus, .form-select:focus{
            border-color: rgba(245,179,1,.55);
            box-shadow: 0 0 0 .25rem rgba(245,179,1,.14);
        }
        .btn-soft{
            border-radius: 12px;
            font-weight: 600;
        }
        .icon-btn{
            width: 38px;
            height: 38px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            padding: 0;
        }
        .icon-btn.btn-sm{
            width: 34px;
            height: 34px;
            border-radius: 12px;
        }
        .muted{ color: rgba(17,24,39,.65); }
        .helper{ color: rgba(17,24,39,.55); }
        /* Taxi coverage area picker (super-admin provider profile) */
        .coverage-picker {
            border: 1px solid rgba(17, 24, 39, .08);
            border-radius: 16px;
            background: rgba(255, 255, 255, .65);
            padding: 1rem 1rem 1.1rem;
        }
        .coverage-picker .btn-check:focus + .coverage-tile {
            box-shadow: 0 0 0 .25rem rgba(245, 179, 1, .22);
        }
        .coverage-picker .btn-check:checked + .coverage-tile {
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            border-color: rgba(245, 179, 1, .65);
            color: #111827;
            font-weight: 600;
            box-shadow: 0 8px 22px rgba(245, 179, 1, .28);
        }
        .coverage-picker .coverage-tile {
            border-radius: 14px;
            border: 1px solid rgba(17, 24, 39, .12);
            padding: .75rem .85rem;
            min-height: 3.25rem;
            transition: background .15s ease, border-color .15s ease, box-shadow .15s ease;
        }
        .coverage-picker .coverage-tile:hover {
            border-color: rgba(245, 179, 1, .35);
            background: rgba(245, 179, 1, .06);
        }
        .coverage-picker.is-invalid-box {
            border-color: rgba(220, 53, 69, .45);
            box-shadow: 0 0 0 .12rem rgba(220, 53, 69, .12);
        }
        .taxi-step-nav{ gap: .5rem; flex-wrap: wrap; }
        .taxi-step-nav .nav-link{
            border-radius: 14px;
            border: 1px solid rgba(17,24,39,.10);
            color: rgba(17,24,39,.78);
            font-weight: 600;
            font-size: .82rem;
            padding: .65rem .75rem;
            white-space: normal;
            line-height: 1.25;
        }
        .taxi-step-nav .nav-link:hover{
            background: rgba(245,179,1,.08);
            border-color: rgba(245,179,1,.28);
        }
        .taxi-step-nav .nav-link.active{
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            border-color: rgba(245,179,1,.5);
            color: #111827;
        }
        .taxi-step-nav .step-idx{
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 1.45rem;
            height: 1.45rem;
            padding: 0 .25rem;
            border-radius: 999px;
            background: rgba(17,24,39,.08);
            font-size: .72rem;
            margin-inline-end: .4rem;
            flex-shrink: 0;
        }
        .taxi-step-nav .nav-link.active .step-idx{
            background: rgba(255,255,255,.55);
        }
        .taxi-tab-shell{
            border: 1px solid rgba(17,24,39,.08);
            border-radius: 16px;
            background: rgba(255,255,255,.55);
            padding: 1rem 1rem 1.25rem;
        }
        .taxi-step-footer{
            background: rgba(255,255,255,.85);
            border-radius: 14px;
            padding: 1rem 1.1rem;
            border: 1px solid rgba(17,24,39,.07);
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg nav-sa">
    <div class="container">
        <div class="d-flex align-items-center gap-2 gap-sm-3 flex-grow-1 flex-lg-grow-0">
            <button class="btn btn-outline-secondary btn-sm btn-soft d-lg-none d-inline-flex align-items-center gap-1" type="button" data-bs-toggle="offcanvas" data-bs-target="#saSidebar" aria-controls="saSidebar">
                <i class="bi bi-list" aria-hidden="true"></i>
                <span class="d-none d-sm-inline">القائمة</span>
            </button>

            <div class="brand-badge d-none d-lg-inline-grid overflow-hidden" aria-hidden="true">
                <img
                    src="{{ asset('logo.png') }}"
                    alt=""
                    style="width: 100%; height: 100%; object-fit: contain;"
                />
            </div>
            <div class="navbar-brand-block lh-sm min-w-0">
                <div class="nav-title text-truncate">{{ config('app.name', 'Sar Andak') }}</div>
                <div class="nav-sub text-truncate">@yield('subtitle', 'لوحة السوبر أدمن')</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-2 ms-auto flex-shrink-0">
            @isset($superAdmin)
                @if($superAdmin)
                    <span class="chip d-none d-sm-inline-flex" title="رقم الجوال">
                        <i class="bi bi-phone" style="font-size:.85rem;opacity:.65" aria-hidden="true"></i>
                        <span>{{ $superAdmin->phone }}</span>
                    </span>
                @endif
            @endisset
            <form method="POST" action="{{ route('super-admin.logout') }}" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-soft btn-sm">خروج</button>
            </form>
        </div>
    </div>
</nav>

{{-- Mobile sidebar --}}
<div class="offcanvas offcanvas-end offcanvas-sa" tabindex="-1" id="saSidebar" aria-labelledby="saSidebarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="saSidebarLabel">القائمة</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        @include('super-admin.partials.sidebar')
    </div>
</div>

<div class="app-shell">
    <aside class="sidebar d-none d-lg-block">
        @include('super-admin.partials.sidebar')
    </aside>
    <main class="container py-4 py-lg-5">
        @yield('content')
    </main>
</div>

{{-- Toast notifications --}}
<div class="toast-container position-fixed bottom-0 start-0 p-3" style="z-index: 1100">
    @if(session('toast'))
        @php($t = session('toast'))
        <div id="appToast" class="toast align-items-center text-bg-{{ $t['type'] ?? 'success' }} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    {{ $t['message'] ?? '' }}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.forEach(function (el) {
            new bootstrap.Tooltip(el);
        });

        const toastEl = document.getElementById('appToast');
        if (toastEl) {
            const t = new bootstrap.Toast(toastEl, { delay: 3000 });
            t.show();
        }
    });
</script>
</body>
</html>
