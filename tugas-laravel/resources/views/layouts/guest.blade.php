<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Alia Cookies') }}</title>
        <link rel="icon" type="image/png" href="{{ asset('images/aliacookies.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">

        {{-- =======================================================
             GLOBAL TOAST NOTIFICATION (Melayang Kanan Atas)
             ======================================================= --}}
        <div id="toast-container" style="position: fixed; top: 24px; right: 24px; z-index: 9999; display: flex; flex-direction: column; gap: 12px; pointer-events: none;">
            @if(session('error'))
                <div class="toast-alert" style="background: #fff1f2; color: #9f1239; border-left: 4px solid #e11d48; padding: 16px 20px; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(225, 29, 72, 0.1), 0 4px 6px -2px rgba(225, 29, 72, 0.05); display: flex; align-items: center; gap: 12px; min-width: 300px; transform: translateX(0); transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55); pointer-events: auto;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>
                    <span style="font-weight: 500; font-size: 14px; font-family: ui-sans-serif, system-ui, sans-serif;">{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success') || session('status'))
                <div class="toast-alert" style="background: #ecfdf5; color: #065f46; border-left: 4px solid #10b981; padding: 16px 20px; border-radius: 8px; box-shadow: 0 10px 15px -3px rgba(16, 185, 129, 0.1), 0 4px 6px -2px rgba(16, 185, 129, 0.05); display: flex; align-items: center; gap: 12px; min-width: 300px; transform: translateX(0); transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55); pointer-events: auto;">
                    <svg viewBox="0 0 24 24" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
                    <span style="font-weight: 500; font-size: 14px; font-family: ui-sans-serif, system-ui, sans-serif;">
                        {{ session('success') ?? session('status') }}
                    </span>
                </div>
            @endif
        </div>

        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
            <div>
                <a href="/">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
            </div>

            <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>

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
        </script>
    </body>
</html>
