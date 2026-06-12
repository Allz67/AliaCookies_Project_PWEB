<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password — Alia Cookies</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/storefront.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="ac-auth-body">

<div class="ac-auth-page ac-auth-page--centered">

    <div class="ac-centered-wrap">

        {{-- Brand --}}
        <a href="{{ route('store.home') }}" class="ac-centered-brand">
            <div class="ac-panel__logo ac-panel__logo--sm">
                <img src="{{ asset('images/aliacookies.png') }}" alt="Alia Cookies"
                     onerror="this.parentElement.innerHTML='🍪'">
            </div>
            <span>Alia <em>Cookies</em></span>
        </a>

        {{-- Card --}}
        <div class="ac-centered-card">

            <div class="ac-icon-hero ac-icon-hero--lock">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/>
                    <path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    <circle cx="12" cy="16" r="1" fill="currentColor"/>
                </svg>
            </div>

            <div class="ac-centered-header">
                <h1 class="ac-form-title">Buat Password Baru</h1>
                <p class="ac-form-sub">
                    Password baru kamu harus berbeda dari yang sebelumnya dan minimal 8 karakter.
                </p>
            </div>

            @if($errors->any())
                <div class="ac-alert ac-alert--error">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                    <div>
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form action="{{ route('password.update') }}" method="POST" class="ac-form" id="resetForm" novalidate>
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                {{-- Email (hidden prefill, shown as readonly) --}}
                <div class="ac-field">
                    <label for="email">Email</label>
                    <div class="ac-input-wrap">
                        <svg class="ac-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ $email ?? old('email') }}"
                            readonly
                            class="ac-input-readonly"
                        >
                    </div>
                </div>

                {{-- Password Baru --}}
                <div class="ac-field @error('password') ac-field--error @enderror">
                    <label for="password">Password Baru</label>
                    <div class="ac-input-wrap">
                        <svg class="ac-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimal 8 karakter"
                            autocomplete="new-password"
                            oninput="checkStrength(this.value)"
                            autofocus
                        >
                        <button type="button" class="ac-toggle-pw" onclick="togglePw('password', this)" aria-label="Lihat password">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
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

                {{-- Konfirmasi --}}
                <div class="ac-field">
                    <label for="password_confirmation">Konfirmasi Password Baru</label>
                    <div class="ac-input-wrap">
                        <svg class="ac-input-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Ulangi password baru"
                            autocomplete="new-password"
                        >
                        <button type="button" class="ac-toggle-pw" onclick="togglePw('password_confirmation', this)" aria-label="Lihat password">
                            <svg class="eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                            <svg class="eye-closed" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display:none"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/></svg>
                        </button>
                    </div>
                </div>

                <button type="submit" class="ac-btn-submit" id="resetBtn">
                    <span>Simpan Password Baru</span>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                </button>
            </form>

            <div class="ac-centered-links">
                <a href="{{ route('login') }}" class="ac-back-link ac-back-link--inline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Kembali ke Login
                </a>
            </div>
        </div>

    </div>

    <div class="ac-bg-blob ac-bg-blob--1"></div>
    <div class="ac-bg-blob ac-bg-blob--2"></div>
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
    const wrap  = document.getElementById('pwStrength');
    const label = document.getElementById('pwLabel');
    const bars  = ['bar1','bar2','bar3','bar4'].map(id => document.getElementById(id));
    if (!val) { wrap.style.display = 'none'; return; }
    wrap.style.display = 'flex';
    let score = 0;
    if (val.length >= 8)          score++;
    if (/[A-Z]/.test(val))        score++;
    if (/[0-9]/.test(val))        score++;
    if (/[^A-Za-z0-9]/.test(val)) score++;
    const colors = ['#d97575','#e8b84b','#8fad94','#4a7c51'];
    const labels = ['Lemah','Cukup','Kuat','Sangat Kuat'];
    bars.forEach((bar, i) => { bar.style.background = i < score ? colors[score-1] : 'var(--cream-dark)'; });
    label.textContent = labels[score-1] || 'Terlalu Pendek';
    label.style.color = colors[score-1] || '#d97575';
}

document.getElementById('resetForm').addEventListener('submit', function() {
    const btn = document.getElementById('resetBtn');
    btn.innerHTML = '<span>Menyimpan...</span>';
    btn.disabled = true;
});
</script>
</body>
</html>
