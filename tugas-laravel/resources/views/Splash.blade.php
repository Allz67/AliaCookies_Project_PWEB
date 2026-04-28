<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alia Cookies</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --cream:       #fdf6ef;
            --cream-dark:  #f5ebe0;
            --mocha:       #a77761;
            --mocha-light: #c9a48a;
            --mocha-deep:  #7a5540;
            --rose:        #c9a7b2;
            --text-dark:   #3d2b1f;
            --text-light:  #9c8275;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .blob {
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            animation: drift 8s ease-in-out infinite;
        }
        .blob-1 {
            width: 500px; height: 500px;
            background: rgba(167,119,97,0.14);
            top: -100px; right: -80px;
            animation-delay: 0s;
        }
        .blob-2 {
            width: 420px; height: 420px;
            background: rgba(201,167,178,0.18);
            bottom: -80px; left: -60px;
            animation-delay: -3s;
        }
        .blob-3 {
            width: 280px; height: 280px;
            background: rgba(181,170,199,0.14);
            top: 40%; left: 55%;
            animation-delay: -5s;
        }
        @keyframes drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(20px, -15px) scale(1.04); }
            66%       { transform: translate(-12px, 20px) scale(0.97); }
        }

        .splash {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0;
            opacity: 0;
            animation: fadeIn 0.6s ease 0.1s forwards;
        }

        @keyframes fadeIn {
            to { opacity: 1; }
        }

        .logo-wrap {
            position: relative;
            width: 130px;
            height: 130px;
            margin-bottom: 2rem;
        }

        .logo-ring-outer {
            position: absolute;
            inset: -8px;
            border-radius: 50%;
            border: 1.5px dashed rgba(167,119,97,0.35);
            animation: spin-slow 12s linear infinite;
        }

        .logo-ring-inner {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            border: 2px solid transparent;
            border-top-color: var(--mocha);
            border-right-color: var(--mocha-light);
            animation: spin 1.6s ease-in-out infinite;
        }

        .logo-circle {
            position: absolute;
            inset: 8px;
            border-radius: 50%;
            background: white;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 32px rgba(167,119,97,0.2), 0 0 0 1px rgba(167,119,97,0.1);
            overflow: hidden;
        }

        .logo-circle img {
            width: 80%;
            height: 80%;
            object-fit: cover;
            border-radius: 50%;
        }

        .logo-fallback {
            font-size: 3.5rem;
            line-height: 1;
        }

        @keyframes spin-slow {
            to { transform: rotate(360deg); }
        }
        @keyframes spin {
            0%   { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .brand {
            font-family: 'Cormorant Garamond', serif;
            font-size: 3.2rem;
            font-weight: 600;
            color: var(--text-dark);
            letter-spacing: 0.01em;
            line-height: 1;
            margin-bottom: 0.4rem;
            opacity: 0;
            transform: translateY(12px);
            animation: slideUp 0.5s ease 0.5s forwards;
        }

        .brand em {
            color: var(--mocha);
            font-style: italic;
        }

        .tagline {
            font-size: 0.85rem;
            color: var(--text-light);
            letter-spacing: 0.12em;
            text-transform: uppercase;
            margin-bottom: 3rem;
            opacity: 0;
            animation: slideUp 0.5s ease 0.65s forwards;
        }

        @keyframes slideUp {
            to { opacity: 1; transform: translateY(0); }
        }

        .progress-wrap {
            width: 220px;
            opacity: 0;
            animation: slideUp 0.5s ease 0.8s forwards;
        }

        .progress-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.72rem;
            color: var(--text-light);
            letter-spacing: 0.04em;
        }

        .progress-track {
            height: 3px;
            background: var(--cream-dark);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, var(--mocha-deep), var(--mocha-light));
            border-radius: 10px;
            transition: width 0.12s ease;
        }

        .status {
            margin-top: 1rem;
            font-size: 0.8rem;
            color: var(--mocha);
            font-weight: 500;
            min-height: 1.2em;
            opacity: 0;
            animation: slideUp 0.5s ease 0.9s forwards;
            transition: opacity 0.25s ease;
        }

        .dots {
            display: flex;
            gap: 0.4rem;
            margin-top: 2.5rem;
            opacity: 0;
            animation: slideUp 0.5s ease 1s forwards;
        }

        .dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--cream-dark);
            animation: dot-pulse 1.4s ease-in-out infinite;
        }

        .dot:nth-child(1) { animation-delay: 0s;    background: var(--mocha); }
        .dot:nth-child(2) { animation-delay: 0.2s; }
        .dot:nth-child(3) { animation-delay: 0.4s; }

        @keyframes dot-pulse {
            0%, 100% { transform: scale(1);   opacity: 0.4; }
            50%       { transform: scale(1.5); opacity: 1;   background: var(--mocha); }
        }

        .corner-text {
            position: fixed;
            bottom: 2rem;
            font-size: 0.72rem;
            color: var(--text-light);
            letter-spacing: 0.06em;
            opacity: 0;
            animation: fadeIn 0.5s ease 1.2s forwards;
        }
    </style>
</head>
<body>

    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>

    <div class="splash">
        <div class="logo-wrap">
            <div class="logo-ring-outer"></div>
            <div class="logo-ring-inner"></div>
            <div class="logo-circle">
                <img src="{{ asset('images/aliacookies.png') }}" alt="Alia Cookies"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='block'">
                <span class="logo-fallback" style="display:none">🍪</span>
            </div>
        </div>

        <h1 class="brand">Alia <em>Cookies</em></h1>
        <p class="tagline">Handmade · Premium · Homemade</p>

        <div class="progress-wrap">
            <div class="progress-label">
                <span id="statusMsg">Memuat...</span>
                <span id="pctText">0%</span>
            </div>
            <div class="progress-track">
                <div class="progress-fill" id="progressFill"></div>
            </div>
        </div>

        <p class="status" id="subStatus">Menyiapkan sistem admin...</p>

        <div class="dots">
            <div class="dot"></div>
            <div class="dot"></div>
            <div class="dot"></div>
        </div>
    </div>

    <p class="corner-text">© {{ date('Y') }} Alia Cookies — Admin Panel</p>

    <script>
        const fill      = document.getElementById('progressFill');
        const pctText   = document.getElementById('pctText');
        const statusMsg = document.getElementById('statusMsg');
        const subStatus = document.getElementById('subStatus');

        const steps = [
            { pct: 20,  label: 'Memuat aset...',         sub: 'Menyiapkan tampilan...' },
            { pct: 45,  label: 'Menginisialisasi...',     sub: 'Memeriksa konfigurasi sistem...' },
            { pct: 70,  label: 'Hampir selesai...',       sub: 'Menyiapkan panel admin...' },
            { pct: 90,  label: 'Menyelesaikan...',        sub: 'Sebentar lagi masuk!' },
            { pct: 100, label: 'Selesai!',                sub: 'Selamat datang di Alia Cookies' },
        ];

        let current = 0;
        let pct = 0;

        function tick() {
            if (current >= steps.length) return;

            const target = steps[current].pct;
            const speed  = pct < 60 ? 1.8 : pct < 85 ? 0.9 : 1.5;

            pct = Math.min(pct + speed, target);
            fill.style.width = pct + '%';
            pctText.textContent = Math.floor(pct) + '%';

            if (pct >= target) {
                statusMsg.style.opacity = '0';
                setTimeout(() => {
                    statusMsg.textContent = steps[current].label;
                    subStatus.textContent = steps[current].sub;
                    statusMsg.style.opacity = '1';
                }, 150);
                current++;
            }

            if (pct < 100) {
                requestAnimationFrame(tick);
            } else {
                setTimeout(() => {
                    window.location.href = "{{ route('login') }}";
                }, 800);
            }
        }

        setTimeout(tick, 1000);
    </script>

</body>
</html>
