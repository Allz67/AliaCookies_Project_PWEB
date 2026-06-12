<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun — Alia Cookies</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/storefront.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="ac-auth-body">

<div class="ac-auth-page ac-auth-page--register">

    {{-- ── Left Panel (Form) ── --}}
    <div class="ac-auth-panel ac-auth-panel--right ac-auth-panel--form-left">
        <div class="ac-form-wrap">

            <a href="{{ route('home') }}" class="ac-back-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                Kembali ke Beranda
            </a>

            <div class="ac-form-header">
                <h1 class="ac-form-title">Buat Akun</h1>
                <p class="ac-form-sub">Bergabung dan nikmati cookies terbaik kami</p>
            </div>

            {{-- Google Register --}}
            <a href="{{ route('auth.google') }}" class="ac-btn-google">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" fill="#4285F4"/>
                    <path d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" fill="#34A853"/>
                    <path d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" fill="#FBBC05"/>
                    <path d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" fill="#EA4335"/>
                </svg>
                Daftar dengan Google
            </a>

            <div class="ac-divider">
                <span>atau daftar dengan email</span>
            </div>

            {{-- Errors --}}
            @if($errors->any())
                <div class="ac-alert ac-alert--error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        <strong>Periksa kembali isian kamu:</strong>
                        <ul style="margin: 0.3rem 0 0 1rem; font-size: 0.85rem;">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('register') }}" method="POST" class="ac-form" id="registerForm" novalidate>
                @csrf

                {{-- Nama --}}
                <div class="ac-field @error('name') ac-field--error @enderror">
                    <label for="name">Nama Lengkap</label>
                    <div class="ac-input-wrap">
                        <svg class="ac-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Nama lengkap kamu"
                            autocomplete="name"
                            autofocus
                        >
                    </div>
                    @error('name')
                        <span class="ac-field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
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
                        >
                    </div>
                    @error('email')
                        <span class="ac-field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Nomor HP (opsional) --}}
                <div class="ac-field @error('phone') ac-field--error @enderror">
                    <label for="phone">
                        Nomor WhatsApp
                        <span class="ac-label-opt">(opsional)</span>
                    </label>
                    <div class="ac-input-wrap">
                        <svg class="ac-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07A19.5 19.5 0 013 7.18a2 2 0 012-2.18h3a2 2 0 012 1.72c.127.96.361 1.903.7 2.81a2 2 0 01-.45 2.11L9.91 12a16 16 0 006.09 6.09l1.27-1.27a2 2 0 012.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0122 18.92z"/></svg>
                        <input
                            type="tel"
                            id="phone"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="08xxxxxxxxxx"
                            autocomplete="tel"
                        >
                    </div>
                    @error('phone')
                        <span class="ac-field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="ac-field @error('password') ac-field--error @enderror">
                    <label for="password">Password</label>
                    <div class="ac-input-wrap">
                        <svg class="ac-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            autocomplete="new-password"
                            oninput="checkStrength(this.value)"
                        >
                        <button type="button" class="ac-toggle-pw" onclick="togglePw('password', this)" aria-label="Lihat password">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                    {{-- Password strength --}}
                    <div class="ac-pw-strength" id="pwStrength" style="display:none">
                        <div class="ac-pw-bars">
                            <div class="ac-pw-bar" id="bar1"></div>
                            <div class="ac-pw-bar" id="bar2"></div>
                            <div class="ac-pw-bar" id="bar3"></div>
                            <div class="ac-pw-bar" id="bar4"></div>
                        </div>
                        <span class="ac-pw-label" id="pwLabel">Lemah</span>
                    </div>
                    @error('password')
                        <span class="ac-field-error">{{ $message }}</span>
                    @enderror
                </div>

                {{-- Konfirmasi Password --}}
                <div class="ac-field">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <div class="ac-input-wrap">
                        <svg class="ac-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password kamu"
                            autocomplete="new-password"
                        >
                        <button type="button" class="ac-toggle-pw" onclick="togglePw('password_confirmation', this)" aria-label="Lihat password">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="ac-btn-submit" id="registerBtn">
                    <span>Buat Akun Sekarang</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="5" y1="12" x2="19" y2="12"/><polyline points="12 5 19 12 12 19"/></svg>
                </button>

                <p class="ac-terms">
                    Dengan mendaftar, kamu menyetujui
                    <a href="#">Syarat & Ketentuan</a> kami.
                </p>
            </form>

            <p class="ac-form-switch">
                Sudah punya akun?
                <a href="{{ route('login') }}">Masuk di sini →</a>
            </p>

        </div>
    </div>

    {{-- ── Right Panel (Dekoratif) ── --}}
    <div class="ac-auth-panel ac-auth-panel--left ac-auth-panel--deco-right">
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
                    Gabung<br>
                    <em>Keluarga</em><br>
                    Cookie Kami!
                </h2>
                <p class="ac-panel__sub">
                    Daftar sekarang dan dapatkan pengalaman belanja cookies handmade premium yang menyenangkan.
                </p>
            </div>

            <div class="ac-panel__perks">
                <div class="ac-panel__perk">
                    <span>🎉</span>
                    <div>
                        <strong>Daftar Gratis</strong>
                        <small>Tidak ada biaya pendaftaran</small>
                    </div>
                </div>
                <div class="ac-panel__perk">
                    <span>🚀</span>
                    <div>
                        <strong>Pemesanan Mudah</strong>
                        <small>Pesan kapan saja & di mana saja</small>
                    </div>
                </div>
                <div class="ac-panel__perk">
                    <span>📦</span>
                    <div>
                        <strong>Lacak Pesanan</strong>
                        <small>Pantau status pengiriman real-time</small>
                    </div>
                </div>
            </div>

            <div class="ac-panel__floaters">
                <span class="ac-floater ac-floater--a">🍪</span>
                <span class="ac-floater ac-floater--b">🌟</span>
                <span class="ac-floater ac-floater--c">🎀</span>
                <span class="ac-floater ac-floater--d">✦</span>
            </div>
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

function checkStrength(val) {
    const wrap = document.getElementById('pwStrength');
    const label = document.getElementById('pwLabel');
    const bars = [document.getElementById('bar1'), document.getElementById('bar2'),
                  document.getElementById('bar3'), document.getElementById('bar4')];

    if (!val) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'flex';

    let score = 0;
    if (val.length >= 8)  score++;
    if (/[A-Z]/.test(val)) score++;
    if (/[0-9]/.test(val)) score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;

    const colors = ['#d97575', '#e8b84b', '#8fad94', '#4a7c51'];
    const labels = ['Lemah', 'Cukup', 'Kuat', 'Sangat Kuat'];

    bars.forEach((bar, i) => {
        bar.style.background = i < score ? colors[score - 1] : 'var(--cream-dark)';
    });
    label.textContent = labels[score - 1] || 'Terlalu Pendek';
    label.style.color = colors[score - 1] || '#d97575';
}

document.getElementById('registerForm').addEventListener('submit', function() {
    const btn = document.getElementById('registerBtn');
    btn.innerHTML = '<span>Membuat akun...</span>';
    btn.disabled = true;
});
</script>
</body>
</html>
