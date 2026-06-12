@extends('layouts.app')

@section('title', 'Edit Tentang Kami - Admin')

@section('content')
<style>
    /* Tema Mocha Pastel Khusus Ruang Admin */
    .admin-wrapper { max-width: 900px; margin: 0 auto; padding: 20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
    .admin-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
    .admin-title { color: #5a3e2b; font-size: 24px; font-weight: 700; }

    .panel-card { background: #fffaf5; border: 1px solid #ebd9c8; border-radius: 12px; padding: 25px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(90, 62, 43, 0.05); }
    .panel-title { color: #8b5e3c; font-size: 18px; font-weight: 600; margin-bottom: 15px; border-bottom: 2px dashed #ebd9c8; padding-bottom: 10px; }

    .form-group { margin-bottom: 15px; }
    .form-label { display: block; font-weight: 600; color: #5a3e2b; margin-bottom: 8px; font-size: 14px; }
    .form-control { width: 100%; padding: 10px 15px; border: 1px solid #d4b89f; border-radius: 8px; background: #fff; color: #4a3322; transition: all 0.3s; }
    .form-control:focus { outline: none; border-color: #8b5e3c; box-shadow: 0 0 0 3px rgba(139, 94, 60, 0.2); }

    /* Dynamic Rows Styling */
    .dynamic-row { background: #fff; border: 1px solid #ebd9c8; padding: 15px; border-radius: 8px; margin-bottom: 15px; position: relative; }
    .btn-hapus-row { position: absolute; top: 15px; right: 15px; background: #ff4d4d; color: white; border: none; padding: 5px 10px; border-radius: 6px; cursor: pointer; font-size: 12px; }

    /* Tombol-tombol */
    .btn-tambah { background: #ebd9c8; color: #5a3e2b; border: none; padding: 8px 15px; border-radius: 8px; cursor: pointer; font-weight: 600; font-size: 13px; transition: 0.3s; }
    .btn-tambah:hover { background: #d4b89f; }
    .btn-simpan { background: #8b5e3c; color: white; border: none; padding: 12px 25px; border-radius: 8px; font-weight: bold; font-size: 16px; cursor: pointer; width: 100%; transition: 0.3s; }
    .btn-simpan:hover { background: #5a3e2b; }

    .alert-success { background: #d4edda; color: #155724; padding: 15px; border-radius: 8px; margin-bottom: 20px; border: 1px solid #c3e6cb; }
</style>

<div class="page-wrapper">
    <div class="page-header">
        <div>
            <p class="greeting" id="greeting">Halo, Selamat Pagi 👋</p>
            <h1 class="page-title">Halaman Tentang <span class="highlight">Kami</span></h1>
            <p class="page-sub">Selamat datang, <strong>{{ auth()->user()->name }}</strong>. Kelola informasi tentang perusahaan Alia Cookies.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.tentang.update') }}" method="POST">
        @csrf

        {{-- BAGIAN 1: CERITA UTAMA --}}
        <div class="panel-card">
            <h2 class="panel-title">Cerita Alia Cookies</h2>
            <div class="form-group">
                <label class="form-label">Teks</label>
                <textarea name="cerita" class="form-control" rows="6" required>{{ $cerita }}</textarea>
            </div>
        </div>

        {{-- BAGIAN 2: PERJALANAN / MILESTONES --}}
        <div class="panel-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 2px dashed #ebd9c8; padding-bottom: 10px;">
                <h2 class="panel-title" style="border:none; margin:0; padding:0;">Linimasa Perjalanan</h2>
                <button type="button" class="btn-tambah" onclick="tambahMilestone()">+ Tambah Tahun Baru</button>
            </div>

            <div id="milestone-container">
                @foreach($milestones as $index => $m)
                <div class="dynamic-row" id="ms-row-{{ $index }}">
                    <button type="button" class="btn-hapus-row" onclick="hapusRow('ms-row-{{ $index }}')">Hapus</button>
                    <div style="display: flex; gap: 15px; margin-bottom: 10px;">
                        <div style="flex: 1;">
                            <label class="form-label">Tahun</label>
                            <input type="text" name="milestones[{{ $index }}][tahun]" class="form-control" value="{{ $m['tahun'] }}" placeholder="Contoh: 2026">
                        </div>
                        <div style="flex: 3;">
                            <label class="form-label">Judul Pencapaian</label>
                            <input type="text" name="milestones[{{ $index }}][judul]" class="form-control" value="{{ $m['judul'] }}" placeholder="Contoh: Cabang Baru">
                        </div>
                    </div>
                    <div class="form-group" style="margin-bottom: 0;">
                        <label class="form-label">Deskripsi Singkat</label>
                        <input type="text" name="milestones[{{ $index }}][desc]" class="form-control" value="{{ $m['desc'] }}" placeholder="Penjelasan singkat...">
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- BAGIAN 3: KONTAK --}}
        <div class="panel-card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 2px dashed #ebd9c8; padding-bottom: 10px;">
                <h2 class="panel-title" style="border:none; margin:0; padding:0;">Informasi Kontak</h2>
                <button type="button" class="btn-tambah" onclick="tambahKontak()">+ Tambah Kontak</button>
            </div>

            <div id="kontak-container">
                @foreach($kontaks as $index => $k)
                <div class="dynamic-row" id="kt-row-{{ $index }}">
                    <button type="button" class="btn-hapus-row" onclick="hapusRow('kt-row-{{ $index }}')">Hapus</button>
                    <div style="display: flex; gap: 15px; margin-bottom: 10px;">
                        <div style="flex: 1;">
                            <label class="form-label">Ikon (Emoji)</label>
                            <input type="text" name="kontaks[{{ $index }}][icon]" class="form-control" value="{{ $k['icon'] }}">
                        </div>
                        <div style="flex: 2;">
                            <label class="form-label">Label</label>
                            <input type="text" name="kontaks[{{ $index }}][label]" class="form-control" value="{{ $k['label'] }}">
                        </div>
                    </div>
                    <div style="display: flex; gap: 15px; margin-bottom: 0;">
                        <div style="flex: 2;">
                            <label class="form-label">Isi Utama (Nilai)</label>
                            <input type="text" name="kontaks[{{ $index }}][val]" class="form-control" value="{{ $k['val'] }}">
                        </div>
                        <div style="flex: 2;">
                            <label class="form-label">Teks Kecil Tambahan</label>
                            <input type="text" name="kontaks[{{ $index }}][sub]" class="form-control" value="{{ $k['sub'] }}">
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <button type="submit" class="btn-simpan">Simpan Semua Perubahan</button>
    </form>
</div>

<script>
    // Script cerdas untuk bikin kotak input baru tanpa harus di-refresh!
    let msCount = {{ count($milestones) }};
    let ktCount = {{ count($kontaks) }};

    function tambahMilestone() {
        const container = document.getElementById('milestone-container');
        const html = `
            <div class="dynamic-row" id="ms-row-${msCount}">
                <button type="button" class="btn-hapus-row" onclick="hapusRow('ms-row-${msCount}')">Hapus</button>
                <div style="display: flex; gap: 15px; margin-bottom: 10px;">
                    <div style="flex: 1;"><label class="form-label">Tahun</label><input type="text" name="milestones[${msCount}][tahun]" class="form-control" required></div>
                    <div style="flex: 3;"><label class="form-label">Judul Pencapaian</label><input type="text" name="milestones[${msCount}][judul]" class="form-control" required></div>
                </div>
                <div class="form-group" style="margin-bottom: 0;">
                    <label class="form-label">Deskripsi Singkat</label><input type="text" name="milestones[${msCount}][desc]" class="form-control" required>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        msCount++;
    }

    function tambahKontak() {
        const container = document.getElementById('kontak-container');
        const html = `
            <div class="dynamic-row" id="kt-row-${ktCount}">
                <button type="button" class="btn-hapus-row" onclick="hapusRow('kt-row-${ktCount}')">Hapus</button>
                <div style="display: flex; gap: 15px; margin-bottom: 10px;">
                    <div style="flex: 1;"><label class="form-label">Ikon</label><input type="text" name="kontaks[${ktCount}][icon]" class="form-control" value="📌" required></div>
                    <div style="flex: 2;"><label class="form-label">Label</label><input type="text" name="kontaks[${ktCount}][label]" class="form-control" required></div>
                </div>
                <div style="display: flex; gap: 15px; margin-bottom: 0;">
                    <div style="flex: 2;"><label class="form-label">Isi Utama</label><input type="text" name="kontaks[${ktCount}][val]" class="form-control" required></div>
                    <div style="flex: 2;"><label class="form-label">Teks Tambahan</label><input type="text" name="kontaks[${ktCount}][sub]" class="form-control"></div>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
        ktCount++;
    }

    function hapusRow(rowId) {
        if(confirm("Yakin ingin menghapus baris ini?")) {
            document.getElementById(rowId).remove();
        }
    }
</script>
@endsection
