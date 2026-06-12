<nav class="navbar">
    <div class="navbar-brand">
        <div class="brand-logo">
            <img src="{{ asset('images/aliacookies.png') }}" alt="Alia Cookies Logo" class="logo-img"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
            <div class="logo-fallback" style="display:none">🍪</div>
        </div>
        <span class="brand-name">Alia <em>Cookies</em></span>
    </div>

    <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">
        <span></span><span></span><span></span>
    </button>

    <ul class="nav-links" id="navLinks">
        {{-- MENU BERANDA --}}
        <li>
            <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                Beranda
            </a>
        </li>

        {{-- MENU PRODUK (Arahkan ke bagian id produk di beranda) --}}
        <li>
            <a href="{{ route('home') }}#produk-cookies">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><line x1="3" y1="9" x2="21" y2="9"/><line x1="9" y1="21" x2="9" y2="9"/></svg>
                Katalog Produk
            </a>
        </li>

        {{-- MENU TRANSAKSI (Hanya muncul kalau sudah login) --}}
        @auth
        <li>
            <a href="{{ route('transaksi.index') }}" class="{{ request()->routeIs('transaksi.index') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                Transaksi
            </a>
        </li>
        @endauth

        {{-- MENU TENTANG KAMI --}}
        <li>
            <a href="{{ route('tentang') }}" class="{{ request()->routeIs('tentang') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
                Tentang Kami
            </a>
        </li>

        {{-- MODE GELAP (Tetap dipertahankan biar konsisten dengan admin) --}}
        <li>
            <a href="javascript:void(0)" onclick="toggleDarkMode()" style="display: flex; align-items: center; gap: 8px;">
                <svg id="moon-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                <svg id="sun-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 18px; height: 18px; display: none;"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                <span id="theme-text">Mode Gelap</span>
            </a>
        </li>

        {{-- MENU PREFERENSI --}}
        <li>
            <a href="{{ route('preferensi.index') }}" class="{{ request()->routeIs('preferensi.index') ? 'active' : '' }}">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                Preferensi
            </a>
        </li>
    </ul>

    {{-- BAGIAN KANAN (Keranjang, Profil, Logout / Tombol Login) --}}
    <div style="display: flex; align-items: center; gap: 1rem;">
        @auth
            @php
                // Menghitung jumlah jenis produk di keranjang (Opsi 1)
                $jumlahKeranjang = \App\Models\Cart::where('user_id', auth()->id())->count();
            @endphp

            {{-- IKON KERANJANG BELANJA --}}
            <a href="{{ route('cart.index') }}" style="color: var(--text-dark); position: relative; margin-right: 15px;">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width: 22px; height: 22px;">
                    <circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                </svg>

                {{-- Lingkaran notifikasi HANYA muncul jika ada barang di keranjang --}}
                @if($jumlahKeranjang > 0)
                    <span style="position: absolute; top: -5px; right: -8px; background: var(--mocha); color: white; font-size: 0.65rem; padding: 2px 5px; border-radius: 50%; font-weight: bold;">
                        {{ $jumlahKeranjang }}
                    </span>
                @endif
            </a>

            {{-- PROFIL YANG BISA DIKLIK --}}
            <a href="{{ route('profile') }}" style="text-decoration: none; color: inherit;">
                <li class="nav-user" style="list-style: none; margin: 0; padding: 0;">
                    <div class="user-profile-nav" style="cursor: pointer; padding: 5px 10px; border-radius: 50px;">
                        <div class="user-avatar">
                            {{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                        <div class="user-details">
                            <span class="user-name">{{ auth()->user()->name }}</span>
                            <span class="user-status">Customer</span>
                        </div>
                    </div>
                </li>
            </a>

            {{-- TOMBOL KELUAR --}}
            <li style="list-style: none;">
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn-logout-nav">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="width:18px; margin-right:5px;">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4" />
                            <polyline points="16 17 21 12 16 7" />
                            <line x1="21" y1="12" x2="9" y2="12" />
                        </svg>
                        Keluar
                    </button>
                </form>
            </li>
        @else
            {{-- JIKA BELUM LOGIN --}}
            <li class="nav-item" style="list-style: none;">
                <a href="{{ route('login') }}" class="sc-btn sc-btn--primary" style="padding: 0.6rem 1.4rem; font-size: 0.85rem; border-radius: 50px; text-decoration: none;">
                    Masuk / Daftar
                </a>
            </li>
        @endauth
    </div>
</nav>
