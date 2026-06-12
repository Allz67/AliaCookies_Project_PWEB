<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — Alia Cookies</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="ac-auth-body">

<div class="ac-auth-page">

    {{-- ── Left Panel (Dekoratif) ── --}}
    <div class="ac-auth-panel ac-auth-panel--left">
        <div class="ac-panel__blob ac-panel__blob--1"></div>
        <div class="ac-panel__blob ac-panel__blob--2"></div>
        <div class="ac-panel__blob ac-panel__blob--3"></div>

        <div class="ac-panel__content">
            <a href="{{ route('home') }}" class="ac-panel__brand">
                <div class="ac-panel__logo">
                    <img src="{{ asset('images/aliacookies.png') }}" alt="Alia Cookies"
                         onerror="this.parentElement.innerHTML='🍪'">
                </div>
                <span>Alia <em>Cookies</em></span>
            </a>

            <div class="ac-panel__hero">
                <h2 class="ac-panel__tagline">
                    Selamat<br>
                    <em>Datang</em><br>
                    Kembali!
                </h2>
                <p class="ac-panel__sub">
                    Masuk untuk melanjutkan perjalanan kamu bersama kami — cookie by cookie.
                </p>
            </div>

            <div class="ac-panel__floaters">
                <span class="ac-floater ac-floater--a">🍪</span>
                <span class="ac-floater ac-floater--b">🌟</span>
                <span class="ac-floater ac-floater--c">🎀</span>
                <span class="ac-floater ac-floater--d">✦</span>
            </div>

            <div class="ac-panel__footer-note">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar sekarang →</a>
            </div>
        </div>
    </div>

    {{-- ── Right Panel (Form) ── --}}
    <div class="ac-auth-panel ac-auth-panel--right">
        <div class="ac-form-wrap">

            {{-- Back link --}}
            <a href="{{ route('home') }}" class="ac-back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Beranda
            </a>

            <div class="ac-form-header">
                <h1 class="ac-form-title">Masuk</h1>
                <p class="ac-form-sub">Akses akun Alia Cookies kamu</p>
            </div>

            {{-- Flash messages --}}
            <div id="toast-container" style="position: fixed; top: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none;">
                @if(session('success') || session('status'))
                    <div class="toast-alert" style="background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981; padding: 16px 20px; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.1), 0 4px 6px -2px rgba(16, 185, 129, 0.05); display: flex; align-items: center; gap: 12px; min-width: 300px; transform: translateX(0); transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55); pointer-events: auto;">
                        <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                        <span style="font-weight: 500; font-size: 14px; font-family: ui-sans-serif, system-ui, sans-serif;">{{ session('success') ?? session('status') }}</span>
                    </div>
                @endif
            </div>

            @if($errors->has('email') && !$errors->has('password'))
                <div class="ac-alert ac-alert--error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    {{ $errors->first('email') }}
                </div>
            @endif

            {{-- Google Login --}}
            <a href="{{ route('auth.google') }}" class="ac-btn-google">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Masuk dengan Google
            </a>

            <div class="ac-divider">
                <span>atau masuk dengan email</span>
            </div>

            {{-- Login Form --}}
            <form action="{{ route('login') }}" method="POST" class="ac-form" id="loginForm" novalidate>
                @csrf

                <div class="ac-field @error('email') ac-field--error @enderror">
                    <label for="email">Email</label>
                    <div class="ac-input-wrap">
                        <svg class="ac-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            autocomplete="email"
                            autofocus
                        >
                    </div>
                    @error('email')
                        <span class="ac-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="ac-field @error('password') ac-field--error @enderror">
                    <div class="ac-field-label-row">
                        <label for="password">Password</label>
                    </div>
                    <div class="ac-input-wrap">
                        <svg class="ac-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Password kamu"
                            autocomplete="current-password"
                        >
                        <button type="button" class="ac-toggle-pw" onclick="togglePw('password', this)" aria-label="Lihat password">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    @error('password')
                        <span class="ac-field-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="ac-remember">
                    <label class="ac-checkbox">
                        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <span class="ac-checkbox__box"></span>
                        <span>Ingat saya</span>
                    </label>
                </div>

                <button type="submit" class="ac-btn-submit" id="loginBtn">
                    <span>Masuk Sekarang</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>

            </form>

            <p class="ac-form-switch">
                Belum punya akun?
                <a href="{{ route('register') }}">Daftar gratis →</a>
            </p>

        </div>
    </div>

</div>

<script>
function togglePw(fieldId, btn) {
    const input = document.getElementById(fieldId);
    const isText = input.type === 'text';
    input.type = isText ? 'password' : 'text';
    btn.querySelector('.eye-open').style.display  = isText ? 'block' : 'none';
    btn.querySelector('.eye-closed').style.display = isText ? 'none'  : 'block';
}

// Submit loading state
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<span>Memproses...</span>';
    btn.disabled = true;
});

document.addEventListener('DOMContentLoaded', function() {
    const toasts = document.querySelectorAll('.toast-alert');
    toasts.forEach(toast => {
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => { toast.remove(); }, 500);
        }, 3500);
    });
});
</script>
</body>
</html>
