<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — Alia Cookies</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body class="login-page">

    <div class="login-wrapper">

        <div class="login-deco">
            <div class="deco-content">
                <div class="deco-badge">Admin Panel</div>

                <h1 class="deco-title">
                    Sweet <em>Moments</em><br>
                    Start Here.
                </h1>

                <p class="deco-sub">
                    Kelola toko kue Anda dengan mudah, cepat, dan elegan.
                </p>

                <div class="deco-cookies">
                    <img src="{{ asset('images/cookies.png') }}"
                         alt="Cookies"
                         class="deco-img"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">

                    <div class="deco-emoji" style="display:none">🍪</div>
                </div>

                <div class="deco-dots">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
        </div>

        <div class="login-form-side">
            <div class="login-box">

                <div class="login-logo">
                    <img src="{{ asset('images/aliacookies.png') }}"
                         alt="Logo"
                         class="login-logo-img"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">

                    <div class="login-logo-fallback" style="display:none">
                        🍪
                    </div>
                </div>

                <h2 class="login-title">Selamat Datang</h2>
                <p class="login-sub">
                    Masuk ke panel admin Alia Cookies
                </p>

                @if(session('error'))
                    <div class="alert-error">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('do.login') }}"
                      method="POST"
                      class="login-form"
                      id="loginForm">

                    @csrf

                    <div class="form-group">
                        <label for="username">Username</label>

                        <div class="input-wrapper">
                            <svg class="input-icon"
                                 viewBox="0 0 24 24"
                                 fill="none"
                                 stroke="currentColor"
                                 stroke-width="2">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                                <circle cx="12" cy="7" r="4"/>
                            </svg>

                            <input
                                type="text"
                                id="username"
                                name="username"
                                placeholder="Masukkan username..."
                                autocomplete="off"
                            >
                        </div>

                        {{-- warning validation dari JS --}}
                        <small id="usernameError" class="error-message"></small>

                        <span class="field-hint">
                            Gunakan nama asli kamu
                        </span>
                    </div>

                    <button type="submit"
                            class="btn-login"
                            id="btnLogin">

                        <span>Masuk</span>

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">
                            <path d="M5 12h14"/>
                            <path d="M12 5l7 7-7 7"/>
                        </svg>
                    </button>
                </form>

                <p class="login-footer-text">
                    © {{ date('Y') }} Alia Cookies — Admin Panel
                </p>

            </div>
        </div>

    </div>

    <script src="{{ asset('js/admin.js') }}"></script>
    <script>
    window.history.forward();
    function noBack() {
        window.history.forward();
    }

    window.onload = noBack;
    window.onpageshow = function(evt) {
        if (evt.persisted) noBack();
    };
    </script>

</body>
</html>
