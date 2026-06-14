<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Alia Cookies') — Home</title>
    <link rel="icon" type="image/png" href="{{ asset('images/aliacookies.png') }}">
    <script>
        function getCookie(name) {
            let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) return decodeURIComponent(match[2]);
            return null;
        }

        // 1. Ubah bawaan (default) dari 'system' menjadi 'light'
        const savedTheme = getCookie('theme') || 'light';

        // 2. Detektif OS (systemPrefersDark) kita hapus/abaikan.
        // 3. Hanya gunakan mode gelap JIKA memori cookie benar-benar mencatat 'dark'
        if (savedTheme === 'dark') {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

    {{-- =======================================================
         GLOBAL TOAST NOTIFICATION (Melayang Kanan Atas)
         ======================================================= --}}
    <div id="toast-container" style="position: fixed; top: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none;">
        @if(session('error'))
            <div class="toast-alert" style="background: #fff1f2; color: #9f1239; border-left: 4px solid #e11d48; padding: 16px 20px; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(225, 29, 72, 0.1), 0 4px 6px -2px rgba(225, 29, 72, 0.05); display: flex; align-items: center; gap: 12px; min-width: 300px; transform: translateX(0); transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55); pointer-events: auto;">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                <span style="font-weight: 500; font-size: 14px;">{{ session('error') }}</span>
            </div>
        @endif

        @if(session('success'))
            <div class="toast-alert" style="background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981; padding: 16px 20px; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.1), 0 4px 6px -2px rgba(16, 185, 129, 0.05); display: flex; align-items: center; gap: 12px; min-width: 300px; transform: translateX(0); transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55); pointer-events: auto;">
                <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                <span style="font-weight: 500; font-size: 14px;">{{ session('success') }}</span>
            </div>
        @endif
    </div>

    @include('partials.navbar-store')

    <main class="main-content">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('js/admin.js') }}"></script>

    @stack('scripts')

    <script>
        // Script Global Toast Animation
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

        // Script Cookies bawaanmu
        function setCookie(name, value, days = 30) {
            let expires = "";
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/; SameSite=Lax";
        }
        function deleteCookie(name) {
            document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        }
        function toggleDarkMode() {
            const isDarkNow = document.documentElement.classList.toggle('dark');
            setCookie('theme', isDarkNow ? 'dark' : 'light', 30);
            const moonIcon = document.getElementById('moon-icon');
            const sunIcon = document.getElementById('sun-icon');
            const themeText = document.getElementById('theme-text');
            if (isDarkNow) {
                if(moonIcon) moonIcon.style.display = 'none';
                if(sunIcon) sunIcon.style.display = 'inline-block';
                if(themeText) themeText.innerText = 'Mode Terang';
            } else {
                if(moonIcon) moonIcon.style.display = 'inline-block';
                if(sunIcon) sunIcon.style.display = 'none';
                if(themeText) themeText.innerText = 'Mode Gelap';
            }
            const themeSelectForm = document.getElementById('theme');
            if (themeSelectForm) {
                themeSelectForm.value = isDarkNow ? 'dark' : 'light';
            }
        }
        const savedFont = getCookie('font_size') || 'md';
        document.documentElement.classList.remove('font-sm', 'font-md', 'font-lg');
        document.documentElement.classList.add('font-' + savedFont);
    </script>
</body>
</html>
