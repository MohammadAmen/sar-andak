<!doctype html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Sar Andak') }} - تسجيل دخول السوبر أدمن</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;500;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        :root{
            --brand: #f5b301; /* golden yellow */
            --brand-2: #ffcf3a;
            --ink: #111827;
        }
        html{ font-size: 15px; }
        body{
            font-family: "Cairo", system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
            font-weight: 400;
            line-height: 1.55;
            -webkit-font-smoothing: antialiased;
            color: var(--ink);
            background:
                radial-gradient(900px 500px at 10% 10%, rgba(245,179,1,.18), transparent 60%),
                radial-gradient(900px 500px at 90% 0%, rgba(255,207,58,.18), transparent 55%),
                linear-gradient(180deg, #fff, #f8fafc);
            min-height: 100vh;
        }
        .auth-shell{
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 48px 12px;
        }
        .brand-badge{
            width: 52px;
            height: 52px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--brand), var(--brand-2));
            display: inline-grid;
            place-items: center;
            box-shadow: 0 10px 22px rgba(17,24,39,.10);
        }
        .auth-card{
            border: 1px solid rgba(17,24,39,.08);
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(17,24,39,.10);
            overflow: hidden;
        }
        .auth-topbar{
            background:
                radial-gradient(800px 180px at 10% 50%, rgba(245,179,1,.22), transparent 60%),
                radial-gradient(600px 180px at 90% 10%, rgba(255,207,58,.18), transparent 55%),
                #ffffff;
            border-bottom: 1px solid rgba(17,24,39,.06);
        }
        .btn-brand{
            --bs-btn-bg: var(--brand);
            --bs-btn-border-color: var(--brand);
            --bs-btn-hover-bg: #eaa800;
            --bs-btn-hover-border-color: #eaa800;
            --bs-btn-active-bg: #d99b00;
            --bs-btn-active-border-color: #d99b00;
            --bs-btn-color: #111827;
            font-weight: 600;
            letter-spacing: .2px;
        }
        .form-control:focus{
            border-color: rgba(245,179,1,.65);
            box-shadow: 0 0 0 .25rem rgba(245,179,1,.18);
        }
        .muted{
            color: rgba(17,24,39,.70);
        }
        .helper{
            color: rgba(17,24,39,.55);
        }
    </style>
</head>
<body>
<div class="auth-shell">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="auth-card bg-white">
                    <div class="auth-topbar p-4 p-md-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="brand-badge" aria-hidden="true">
                                {{-- Delivery Arrow Icon --}}
                                <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Delivery">
                                    <path d="M3 12h13.5" stroke="#111827" stroke-width="2" stroke-linecap="round"/>
                                    <path d="M13 6l6 6-6 6" stroke="#111827" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M3 7.5V16.5" stroke="#111827" stroke-width="2" stroke-linecap="round" opacity=".35"/>
                                </svg>
                            </div>
                            <div class="flex-grow-1">
                                <div class="fw-semibold" style="font-size: 1rem;">{{ config('app.name', 'Sar Andak') }}</div>
                                <div class="helper small">تسجيل دخول السوبر أدمن</div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 p-md-5">
                        <h1 class="h4 mb-2">أهلاً بعودتك</h1>
                        <p class="muted mb-4">أدخل رقم الجوال وكلمة المرور للمتابعة.</p>

                        <form method="POST" action="{{ route('super-admin.login.submit') }}" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="phone">رقم الجوال</label>
                                <input
                                    id="phone"
                                    name="phone"
                                    type="text"
                                    inputmode="numeric"
                                    autocomplete="username"
                                    class="form-control form-control-lg @error('phone') is-invalid @enderror"
                                    value="{{ old('phone') }}"
                                    placeholder="0961100101"
                                    required
                                >
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-semibold" for="password">كلمة المرور</label>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    autocomplete="current-password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    placeholder="••••••••"
                                    required
                                >
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button class="btn btn-brand btn-lg w-100" type="submit">تسجيل الدخول</button>

                            @if ($errors->any() && !($errors->has('phone') || $errors->has('password')))
                                <div class="alert alert-danger mt-3 mb-0">
                                    حدث خطأ، يرجى المحاولة مرة أخرى.
                                </div>
                            @endif

                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="small helper">لوحة تحكم السوبر أدمن</div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="text-center helper mt-3 small">
                    © {{ date('Y') }} {{ config('app.name', 'Sar Andak') }}
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
