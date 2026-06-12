<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password — Alia Cookies</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body class="ac-auth-body">

<div class="ac-auth-page ac-auth-page--centered">

    <div class="ac-centered-wrap">

        {{-- Brand --}}
        <a href="{{ route('home') }}" class="ac-centered-brand">
            <div class="ac-panel__logo ac-panel__logo--sm">
                <img src="{{ asset('images/aliacookies.png') }}" alt="Alia Cookies"
                     onerror="this.parentElement.innerHTML='🍪'">
            </div>
            <span>Alia <em>Cookies</em></span>
        </a>

        {{-- Card --}}
        <div class="ac-centered-card">

            {{-- Icon --}}
            <div class="ac-icon-hero ac-icon-hero--key">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4"/>
                </svg>
            </div>

            <div class="ac-centered-header">
                <h1 class="ac-form-title">Lupa Password?</h1>
                <p class="ac-form-sub">
                    Masukkan email akunmu dan kami akan mengirimkan link untuk membuat password baru.
                </p>
            </div>

            @if(session('success'))
                <div class="ac-alert ac-alert--success ac-alert--lg">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="20 6 9 17 4 12"/></svg>
                    <div>
                        <strong>Email terkirim!</strong>
                        <p>{{ session('success') }}</p>
                    </div>
                </div>
            @else

                @error('email')
                    <div class="ac-alert ac-alert--error">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                        {{ $message }}
                    </div>
                @enderror

                <form action="{{ route('password.email') }}" method="POST" class="ac-form" id="forgotForm">
                    @csrf

                    <div class="ac-field @error('email') ac-field--error @enderror">
                        <label for="email">Alamat Email</label>
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
                    </div>

                    <button type="submit" class="ac-btn-submit" id="sendBtn">
                        <span>Kirim Link Reset</span>
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="22" y1="2" x2="11" y2="13"/><polygon points="22 2 15 22 11 13 2 9 22 2"/></svg>
                    </button>
                </form>

            @endif

            <div class="ac-centered-links">
                <a href="{{ route('login') }}" class="ac-back-link ac-back-link--inline">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/></svg>
                    Kembali ke Login
                </a>
            </div>
        </div>

    </div>

    {{-- Background blobs --}}
    <div class="ac-bg-blob ac-bg-blob--1"></div>
    <div class="ac-bg-blob ac-bg-blob--2"></div>
</div>

<script>
document.getElementById('forgotForm')?.addEventListener('submit', function() {
    const btn = document.getElementById('sendBtn');
    btn.innerHTML = '<span>Mengirim...</span>';
    btn.disabled = true;
});
</script>
</body>
</html>
