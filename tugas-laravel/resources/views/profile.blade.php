@extends(auth()->user()->role === 'admin' ? 'layouts.app' : 'layouts.store')

@section('title', 'Profile')

@section('content')

    @if(auth()->user()->role == 'admin')

        {{-- HEADER KHUSUS ADMIN (Sesuai kodemu) --}}
        <div class="page-header" style="margin-bottom: 24px;">
            <div>
                <p class="greeting" id="greeting">Halo, Selamat Pagi 👋</p>
                <h1 class="page-title">Halo, <span class="highlight">{{ auth()->user()->name }}</span>!</h1>
                <p class="page-sub">Berikut informasi profil dan pengaturan akun admin Anda.</p>

            </div>
        </div>

    @else

        {{-- HEADER CUSTOMER (Desain judul sederhana) --}}
        <div class="co-header" style="margin-bottom: 24px; text-align: left;">
            <div class="co-header__eyebrow">PENGATURAN AKUN</div>
            <h1 class="co-header__title" style="font-size: 28px;">Profil <span>Pengguna</span></h1>
            <p style="color: #8a7a63; font-size: 14px; margin-top: 8px;">
                Kelola informasi pribadi, alamat pengiriman, dan keamanan akunmu di sini.
            </p>
        </div>

    @endif

    <div class="profile-grid">

        {{-- =======================================================
             KOLOM KIRI: KARTU PROFIL
             ======================================================= --}}
        <div class="card profile-card">
            <div class="profile-avatar-wrap">
                <div class="profile-avatar-ring">
                    @if(auth()->user()->role === 'admin')
                        <img src="{{ asset('images/aliacookies.png') }}" alt="Logo" class="profile-avatar">
                    @else
                        <div class="profile-avatar-fallback" style="display:flex; width: 100%; height: 100%; align-items: center; justify-content: center; font-size: 2.5rem; font-weight: bold; background: #ebd9c8; color: #8b5e3c; border-radius: 50%;">
                            <span>{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                        </div>
                    @endif
                </div>

                @if(auth()->user()->role === 'admin')
                    <div class="profile-badge-role">Admin</div>
                @endif
            </div>

            <h2 class="profile-name">{{ auth()->user()->name }}</h2>

            <div class="profile-chips">
                <span class="chip-tag chip-active">● Akun Aktif</span>
                @if(auth()->user()->role === 'admin')
                    <span class="chip-tag">Admin System</span>
                @else
                    <span class="chip-tag">Customer</span>
                @endif
            </div>

            <div class="profile-divider"></div>

            <div class="profile-meta">
                <div class="profile-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span>{{ auth()->user()->email }}</span>
                </div>
                <div class="profile-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>
                        @if(auth()->user()->detail_alamat)
                            {{ auth()->user()->detail_alamat }}, {{ auth()->user()->kota_nama }}
                        @else
                            <em style="color: #a0a0a0;">Lokasi belum diisi</em>
                        @endif
                    </span>
                </div>
                <div class="profile-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.37 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 6.53 6.53l1.62-1.81a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>{{ auth()->user()->phone == '0000' || !auth()->user()->phone ? 'Belum diisi' : auth()->user()->phone }}</span>
                </div>
            </div>
        </div>

        {{-- =======================================================
             KOLOM KANAN: INFORMASI & FORM ALAMAT
             ======================================================= --}}
        <div class="profile-detail-col">
            <div class="card">
                <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f4e8df; padding-bottom: 15px; margin-bottom: 15px;">
                    <h2 class="card-title" style="margin: 0;">Informasi Akun</h2>

                    {{-- TOMBOL EDIT --}}
                    <button type="button" id="btnToggleEdit" onclick="toggleEditMode()" style="background-color: #8b5e3c; color: white; border: none; padding: 10px 18px; border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer; display: flex; align-items: center; gap: 6px; box-shadow: 0 4px 12px rgba(139, 94, 60, 0.15); transition: all 0.3s ease;">
                        <span></span> Edit Profil
                    </button>
                </div>

                {{-- ── MODE LIHAT (Default) ── --}}
                <div class="info-list" id="profileViewMode">
                    <div class="info-row">
                        <span class="info-label">Nama Lengkap</span>
                        <span class="info-value">{{ auth()->user()->name }}</span>
                    </div>

                    @if(auth()->user()->role === 'admin')
                        <div class="info-row">
                            <span class="info-label">Role Akses</span>
                            <span class="info-value">
                                <span class="status-badge status-selesai" style="text-transform: uppercase; font-size: 0.75rem;">{{ auth()->user()->role }}</span>
                            </span>
                        </div>
                    @endif

                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ auth()->user()->email }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">No. WhatsApp</span>
                        <span class="info-value">{{ auth()->user()->phone == '0000' || !auth()->user()->phone ? 'Belum diisi' : auth()->user()->phone }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Alamat Rumah</span>
                        @if(auth()->user()->detail_alamat)
                            <span style="text-align: right; max-width: 65%; line-height: 1.5; color: #4a3322;">
                                {{ auth()->user()->detail_alamat }}, Kec. {{ auth()->user()->kecamatan }}, {{ auth()->user()->kota_nama }}, {{ auth()->user()->provinsi_nama }} ({{ auth()->user()->kode_pos }})
                            </span>
                        @else
                            <span style="color: #a0a0a0; font-style: italic;">Belum diisi</span>
                        @endif
                    </div>
                    <div class="info-row">
                        <span class="info-label">Bergabung Sejak</span>
                        <span class="info-value">{{ auth()->user()->created_at->format('d M Y') }}</span>
                    </div>
                </div>

                {{-- ── MODE EDIT ── --}}
                <form id="profileEditMode" action="{{ route('profile.update') }}" method="POST" style="display: none; padding: 10px 5px;">
                    @csrf
                    @method('PATCH')

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #8b5e3c; margin-bottom: 6px;">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required style="width: 100%; padding: 11px; border: 1px solid #ebd9c8; border-radius: 8px; background: #fffaf5; font-size: 0.95rem;">
                        @error('name') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #8b5e3c; margin-bottom: 6px;">Alamat Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" required style="width: 100%; padding: 11px; border: 1px solid #ebd9c8; border-radius: 8px; background: #fffaf5; font-size: 0.95rem;">
                        @error('email') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label style="display: block; font-size: 0.85rem; font-weight: 600; color: #8b5e3c; margin-bottom: 6px;">Nomor WhatsApp</label>
                        <input type="text" name="phone" value="{{ old('phone', auth()->user()->phone == '0000' ? '' : auth()->user()->phone) }}" placeholder="Contoh: 0856xxxxxx" autocomplete="new-password" style="width: 100%; padding: 11px; border: 1px solid #ebd9c8; border-radius: 8px; background: #fffaf5; font-size: 0.95rem;">
                        @error('phone') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                    </div>

                    <div class="address-grid" style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin-top: 15px;">
                        <input type="hidden" name="provinsi_nama" id="provinsi_nama" value="{{ auth()->user()->provinsi_nama }}">
                        <input type="hidden" name="kota_nama" id="kota_nama" value="{{ auth()->user()->kota_nama }}">

                        <div>
                            <label>Provinsi</label>
                            <select name="provinsi_id" id="provinsi" onchange="getKota()" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ebd9c8;">
                                <option value="">-- Pilih Provinsi --</option>
                            </select>
                        </div>

                        <div>
                            <label>Kota / Kabupaten</label>
                            <select name="kota_id" id="kota" onchange="setKotaNama()" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ebd9c8;" disabled>
                                <option value="">-- Pilih Kota --</option>
                            </select>
                        </div>

                        <div>
                            <label>Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ auth()->user()->kecamatan }}" placeholder="Cth: Sumbersari" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ebd9c8;">
                        </div>

                        <div>
                            <label>Kode Pos</label>
                            <input type="text" name="kode_pos" value="{{ auth()->user()->kode_pos }}" placeholder="Cth: 68121" style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ebd9c8;">
                        </div>

                        <div style="grid-column: 1 / -1;">
                            <label>Detail Alamat (Jalan, RT/RW, Patokan)</label>
                            <textarea name="detail_alamat" rows="3" placeholder="Cth: Jl. Kalimantan No. 37, Pagar Hitam depan Indomaret..." style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #ebd9c8;">{{ auth()->user()->detail_alamat }}</textarea>
                        </div>
                    </div>

                    <div style="display: flex; gap: 10px; border-top: 1px solid #f4e8df; padding-top: 15px;">
                        <button type="submit" style="background-color: #4a7c51; color: white; border: none; padding: 11px 22px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.9rem; box-shadow: 0 4px 10px rgba(74,124,81,0.15);">Simpan Alamat</button>
                        <button type="button" onclick="toggleEditMode()" style="background-color: #f4e8df; color: #8b5e3c; border: none; padding: 11px 22px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 0.9rem;">Batal</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Keamanan Akun</h2>
                </div>
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">Status Sesi</span>
                        <span class="info-value"><span class="chip-tag chip-active">● Aktif</span></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Login Terakhir</span>
                        <span class="info-value">{{ auth()->user()->updated_at->format('d M Y, H:i') }}</span>
                    </div>
                </div>

                <div class="profile-actions">
                    <a href="{{ route('logout') }}" onclick="return confirm('Apakah Anda yakin ingin logout?')" class="btn-outline-danger">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                        Logout dari Akun
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        fetchProvinsi();

        // Animasi Notifikasi Toast
        const toasts = document.querySelectorAll('.toast-alert');
        toasts.forEach(toast => {
            // Tunggu 3.5 detik, lalu geser ke kanan sampai hilang
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateX(100%)';

                // Hapus elemen dari memori setelah animasinya selesai
                setTimeout(() => {
                    toast.remove();
                }, 500);
            }, 3500);
        });
    });

    function fetchProvinsi() {
        fetch('/api/provinsi')
            .then(response => response.json())
            .then(data => {
                if (!Array.isArray(data)) return;
                let dropdown = document.getElementById('provinsi');
                let savedProvinsiId = "{{ auth()->user()->provinsi_id }}";

                data.forEach(prov => {
                    let option = document.createElement('option');
                    option.value = prov.id;
                    option.text = prov.name;
                    dropdown.appendChild(option);

                    if(prov.id == savedProvinsiId) {
                        option.selected = true;
                    }
                });

                if (savedProvinsiId) {
                    getKota("{{ auth()->user()->kota_id }}");
                }
            });
    }

    function getKota(autoSelectId = null) {
        let provinsiDropdown = document.getElementById('provinsi');
        let provinsiId = provinsiDropdown.value;
        let kotaDropdown = document.getElementById('kota');
        let kotaNamaInput = document.getElementById('kota_nama');

        if(provinsiDropdown.selectedIndex >= 0) {
            document.getElementById('provinsi_nama').value = provinsiDropdown.options[provinsiDropdown.selectedIndex].text;
        }

        kotaDropdown.innerHTML = '<option value="">Memuat...</option>';
        kotaDropdown.disabled = true;

        if (!autoSelectId) {
            kotaNamaInput.value = '';
        }

        if(provinsiId) {
            fetch('/api/kota/' + provinsiId)
                .then(response => response.json())
                .then(data => {
                    kotaDropdown.innerHTML = '<option value="">-- Pilih Kota --</option>';
                    kotaDropdown.disabled = false;

                    data.forEach(kota => {
                        let option = document.createElement('option');
                        option.value = kota.id;
                        option.text = kota.name;

                        if(autoSelectId && kota.id == autoSelectId) {
                            option.selected = true;
                        }
                        kotaDropdown.appendChild(option);
                    });
                });
        } else {
            kotaDropdown.innerHTML = '<option value="">-- Pilih Kota --</option>';
        }
    }

    function setKotaNama() {
        let kotaDropdown = document.getElementById('kota');
        if(kotaDropdown.selectedIndex >= 0) {
            document.getElementById('kota_nama').value = kotaDropdown.options[kotaDropdown.selectedIndex].text;
        }
    }

    function toggleEditMode() {
        const viewMode = document.getElementById('profileViewMode');
        const editMode = document.getElementById('profileEditMode');
        const btnToggle = document.getElementById('btnToggleEdit');

        if (editMode.style.display === 'none') {
            editMode.style.display = 'block';
            viewMode.style.display = 'none';
            btnToggle.style.display = 'none';
        } else {
            editMode.style.display = 'none';
            viewMode.style.display = 'block';
            btnToggle.style.display = 'flex';
        }
    }

    @if ($errors->any())
        toggleEditMode();
    @endif
</script>

@endsection
