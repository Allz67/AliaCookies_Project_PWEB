@extends(Auth::user()->role == 'admin' ? 'layouts.app' : 'layouts.store')

@section('title', 'Pengaturan Preferensi')

@section('content')


<div class="page-wrapper">

    {{-- HEADER KONDISIONAL --}}
    @if(Auth::user()->role == 'admin')
        {{-- HEADER ADMIN (Menggunakan CSS Bawaanmu) --}}
        <div class="page-header" style="margin-bottom: 30px;">
            <div>
                <p class="greeting" id="greeting">Halo, Selamat Pagi 👋</p>
                <h1 class="page-title">Preferensi <span class="highlight">Tampilan</span></h1>
                <p class="page-sub">Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Sesuaikan kenyamanan tema dan ukuran huruf panel admin Anda.</p>
            </div>
            </div>
    @else
        {{-- HEADER CUSTOMER --}}
        <div style="margin-bottom: 40px; text-align: center;">
            <h1 class="pref-title-cust" style="font-family: 'Playfair Display', serif; font-size: 2.4rem; color: #3d2b1f; margin-bottom: 10px;">
                Preferensi <em style="color: #a77761; font-style: italic;">Tampilan</em>
            </h1>
            <p class="pref-sub-cust" style="color: #9c8275; font-size: 1rem;">
                Sesuaikan kenyamanan tema dan ukuran huruf untuk pengalaman belanjamu.
            </p>
        </div>
    @endif

    {{-- KOTAK FORM --}}
    <div class="pref-card">
        <form id="preferencesForm" style="display: flex; flex-direction: column; gap: 24px;">
            @csrf

            <div>
                <label for="theme" class="pref-label">Pilihan Tema Global</label>
                <select name="theme" id="theme" class="pref-select">
                    <option value="light" {{ request()->cookie('theme') == 'light' ? 'selected' : '' }}>Terang (Light Mode)</option>
                    <option value="dark" {{ request()->cookie('theme') == 'dark' ? 'selected' : '' }}>Gelap (Dark Mode)</option>
                    <option value="system" {{ request()->cookie('theme') == 'system' || !request()->cookie('theme') ? 'selected' : '' }}>Ikuti Sistem Perangkat</option>
                </select>
            </div>

            <div>
                <label for="font_size" class="pref-label">Ukuran Huruf Aplikasi</label>
                <select name="font_size" id="font_size" class="pref-select">
                    <option value="sm" {{ request()->cookie('font_size') == 'sm' ? 'selected' : '' }}>Kecil (Small)</option>
                    <option value="md" {{ request()->cookie('font_size') == 'md' || !request()->cookie('font_size') ? 'selected' : '' }}>Normal (Medium)</option>
                    <option value="lg" {{ request()->cookie('font_size') == 'lg' ? 'selected' : '' }}>Besar (Large)</option>
                </select>
            </div>

            <button type="submit" class="pref-btn">
                Simpan Pengaturan Preferensi
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function syncNavbarThemeText() {
        const moonIcon = document.getElementById('moon-icon');
        const sunIcon = document.getElementById('sun-icon');
        const themeText = document.getElementById('theme-text');

        if (document.documentElement.classList.contains('dark')) {
            if (moonIcon) moonIcon.style.display = 'none';
            if (sunIcon) sunIcon.style.display = 'inline-block';
            if (themeText) themeText.innerText = 'Mode Terang';
        } else {
            if (moonIcon) moonIcon.style.display = 'inline-block';
            if (sunIcon) sunIcon.style.display = 'none';
            if (themeText) themeText.innerText = 'Mode Gelap';
        }
    }

    document.addEventListener('DOMContentLoaded', syncNavbarThemeText);

    const themeSelect = document.getElementById('theme');
    if (themeSelect) {
        themeSelect.addEventListener('change', function() {
            const selectedValue = this.value;
            const systemPrefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

            let shouldBeDark = false;
            if (selectedValue === 'dark') {
                shouldBeDark = true;
            } else if (selectedValue === 'system') {
                shouldBeDark = systemPrefersDark;
            }

            if (shouldBeDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }

            syncNavbarThemeText();
        });
    }

    document.getElementById('preferencesForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        try {
            const response = await fetch("{{ route('preferensi.store') }}", {
                method: "POST",
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': formData.get('_token')
                },
                body: formData
            });

            const result = await response.json();
            if (result.status === 'success') {
                alert('Berhasil! Preferensi Anda telah disimpan.');
                window.location.reload();
            }
        } catch (error) {
            console.error("Gagal mengirim preferensi ke server:", error);
            alert("Terjadi kesalahan saat menyimpan pengaturan.");
        }
    });
</script>
@endpush
