<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Alia Cookies') — Admin Panel</title>
    <script>
        // Helper pembaca cookie yang stabil
        function getCookie(name) {
            let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) return decodeURIComponent(match[2]);
            return null;
        }

        // Ambil data cookie 'theme', kalau kosong default ke 'system'
        const savedTheme = getCookie('theme') || 'system';
        const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        // Pasang class ke tag <html> secara dini
        if (savedTheme === 'dark' || (savedTheme === 'system' && systemPrefersDark)) {
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

    @include('partials.navbar')

    <main class="main-content">
        @yield('content')
    </main>

    @include('partials.footer')

    <script src="{{ asset('js/admin.js') }}"></script>

    @stack('scripts')

    <script>
        function setCookie(name, value, days = 30) {
            let expires = "";
            if (days) {
                let date = new Date();
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                expires = "; expires=" + date.toUTCString();
            }
            document.cookie = name + "=" + encodeURIComponent(value) + expires + "; path=/; SameSite=Lax";
        }

        function getCookie(name) {
            let match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
            if (match) return decodeURIComponent(match[2]);
            return null;
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
