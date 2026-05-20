@extends('layouts.app')

@section('title', 'Pengaturan Preferensi')

@section('content')
<div class="page-wrapper" style="max-w: 600px; margin: 40px auto; padding: 0 20px;">
    <div class="page-header">
        <div>
            <p class="greeting" id="greeting">Halo, Selamat Pagi 👋</p>
            <h1 class="page-title">Preferensi Tampilan</h1>
            <p class="page-sub">Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Sesuaikan kenyamanan tema dan ukuran huruf panel admin Anda.</p>
        </div>
    </div>

    <div class="card" style="background: white; padding: 30px; border-radius: 16px; box-shadow: 0 4px 20px rgba(167, 119, 97, 0.08); border: 1px solid #f5ebe0;">
        <form id="preferencesForm" style="display: flex; flex-direction: column; gap: 24px;">
            @csrf

            <div class="form-group" style="display: flex; flex-direction: column; gap: 8px;">
                <label for="theme" style="font-weight: 500; font-size: 0.95rem; color: #3d2b1f;">Pilihan Tema Global</label>
                <select name="theme" id="theme" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #f5ebe0; background-color: #faf5ef; font-family: 'DM Sans', sans-serif; color: #3d2b1f; outline: none;">
                    <option value="light" {{ request()->cookie('theme') == 'light' ? 'selected' : '' }}>Terang (Light Mode)</option>
                    <option value="dark" {{ request()->cookie('theme') == 'dark' ? 'selected' : '' }}>Gelap (Dark Mode)</option>
                    <option value="system" {{ request()->cookie('theme') == 'system' || !request()->cookie('theme') ? 'selected' : '' }}>Ikuti Sistem Perangkat</option>
                </select>
            </div>

            <div class="form-group" style="display: flex; flex-direction: column; gap: 8px;">
                <label for="font_size" style="font-weight: 500; font-size: 0.95rem; color: #3d2b1f;">Ukuran Huruf Aplikasi</label>
                <select name="font_size" id="font_size" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #f5ebe0; background-color: #faf5ef; font-family: 'DM Sans', sans-serif; color: #3d2b1f; outline: none;">
                    <option value="sm" {{ request()->cookie('font_size') == 'sm' ? 'selected' : '' }}>Kecil (Small)</option>
                    <option value="md" {{ request()->cookie('font_size') == 'md' || !request()->cookie('font_size') ? 'selected' : '' }}>Normal (Medium)</option>
                    <option value="lg" {{ request()->cookie('font_size') == 'lg' ? 'selected' : '' }}>Besar (Large)</option>
                </select>
            </div>

            <div style="margin-top: 10px;">
                <button type="submit" class="btn-detail" style="width: 100%; padding: 12px; background-color: #a77761; color: white; border: none; border-radius: 8px; font-weight: 500; font-size: 1rem; cursor: pointer; transition: background 0.2s;">
                    Simpan Pengaturan Preferensi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const themeSelect = document.getElementById('theme');
    if (themeSelect) {
        themeSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            const moonIcon = document.getElementById('moon-icon');
            const sunIcon = document.getElementById('sun-icon');
            const themeText = document.getElementById('theme-text');

            // Cek preferensi sistem jika user memilih opsi 'system'
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            // Tentukan apakah harus mengaktifkan mode gelap atau tidak
            let shouldBeDark = false;
            if (selectedValue === 'dark') {
                shouldBeDark = true;
            } else if (selectedValue === 'system') {
                shouldBeDark = systemPrefersDark;
            }

            // 1. Ubah class 'dark' di tag HTML root secara langsung
            if (shouldBeDark) {
                document.documentElement.classList.add('dark');
                if (moonIcon) moonIcon.style.display = 'none';
                if (sunIcon) sunIcon.style.display = 'inline-block';
                if (themeText) themeText.innerText = 'Mode Terang';
            } else {
                document.documentElement.classList.remove('dark');
                if (moonIcon) moonIcon.style.display = 'inline-block';
                if (sunIcon) sunIcon.style.display = 'none';
                if (themeText) themeText.innerText = 'Mode Gelap';
            }
        });
    }

    document.getElementById('preferencesForm').addEventListener('submit', async function(e) {
        // 1. Hentikan aksi bawaan form HTML agar tidak refresh halaman
        e.preventDefault();

        // 2. Ambil semua data inputan dari Form (theme dan font_size)
        const formData = new FormData(this);

        try {
            // 3. Kirim data secara asynchronous (Fetch API) dengan metode POST
            const response = await fetch("{{ route('preferensi.store') }}", {
                method: "POST",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': formData.get('_token') // Ambil token CSRF untuk keamanan Laravel
                },
                body: formData
            });

            // 4. Ubah respon mentah dari Laravel menjadi format JSON digital
            const result = await response.json();

            // 5. Jika response sukses, tampilkan notifikasi dan muat ulang halaman
            if (result.status === 'success') {
                alert(result.message);

                // Reload halaman agar cookie baru langsung dibaca oleh skrip anti-flash di <head>
                window.location.reload();
            }
        } catch (error) {
            console.error("Gagal mengirim preferensi ke server:", error);
            alert("Terjadi kesalahan saat menyimpan pengaturan.");
        }
    });
</script>
@endpush
