@extends('layouts.app')

@section('title', 'Pengelolaan Stok')

@section('content')
<div class="page-wrapper">
    <div class="page-header">
        <div>
            <p class="greeting" id="greeting">Halo, Selamat Pagi 👋</p>
            <h1 class="page-title">Pengelolaan <span class="highlight">Stok Produk</span></h1>
            <p class="page-sub">Selamat datang, <strong>{{ $username }}</strong>. Kelola stok produk Alia Cookies dengan mudah.</p>
        </div>
    </div>

    <div class="stats-grid stats-3">
        <div class="stat-card stat-green">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Stok</span>
                <span class="stat-value">{{ $stokStats['total'] }} unit</span>
                <span class="stat-change">Semua produk</span>
            </div>
        </div>
        <div class="stat-card stat-rose">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10.29 3.86L1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/><line x1="12" y1="9" x2="12" y2="13"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Stok Menipis</span>
                <span class="stat-value">{{ $stokStats['menipis'] }} produk</span>
                <span class="stat-change warn">Perlu restock</span>
            </div>
        </div>
        <div class="stat-card stat-mocha">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Stok Habis</span>
                <span class="stat-value">{{ $stokStats['habis'] }} produk</span>
                <span class="stat-change danger">Segera restock!</span>
            </div>
        </div>
    </div>

    <div class="section-grid">
        <div class="card chart-card">
            <div class="card-header">
                <h2 class="card-title">Perbandingan Stok: Cookies vs Hampers</h2>
            </div>
            <div class="chart-container chart-doughnut">
                <canvas id="stokChart"></canvas>
            </div>
            <div class="chart-legend">
                <div class="legend-item"><span class="legend-dot" style="background:#a77761"></span> Cookies ({{ $chartStok['cookies'] }} unit)</div>
                <div class="legend-item"><span class="legend-dot" style="background:#c9a7b2"></span> Hampers ({{ $chartStok['hampers'] }} unit)</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h2 class="card-title">Status Stok</h2>
            </div>
            <div class="stok-bars">
                @foreach($produk as $p)
                <div class="stok-bar-item">
                    <div class="stok-bar-header">
                        <span class="stok-bar-name">{{ $p['nama'] }}</span>
                        <span class="stok-bar-val">{{ $p['stok'] }}</span>
                    </div>
                    <div class="stok-bar-track">
                        <div class="stok-bar-fill stok-fill-{{ strtolower($p['status']) }}" style="width: {{ min(($p['stok']/50)*100, 100) }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-header">
            <h2 class="card-title">Data Stok Produk</h2>
            <div class="table-controls">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="searchProduk" placeholder="Cari produk..." onkeyup="searchTable('produkTable', this.value)">
                </div>
                <select onchange="filterTableByStatus('produkTable', 4, this.value)" class="filter-select">
                    <option value="">Semua Kategori</option>
                    <option value="Cookies">Cookies</option>
                    <option value="Hampers">Hampers</option>
                </select>
                <select onchange="filterTableByStatus('produkTable', 5, this.value)" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="Tersedia">Tersedia</option>
                    <option value="Menipis">Menipis</option>
                    <option value="Habis">Habis</option>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table" id="produkTable">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Stok</th>
                        <th>Satuan</th>
                        <th>Kategori</th>
                        <th>Status</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($produk as $p)
                    <tr>
                        <td><span class="trx-id">{{ $p['kode'] }}</span></td>
                        <td>{{ $p['nama'] }}</td>
                        <td><strong>{{ $p['stok'] }}</strong></td>
                        <td>{{ $p['satuan'] }}</td>
                        <td>
                            <span class="kategori-badge kategori-{{ strtolower($p['kategori']) }}">
                                {{ $p['kategori'] }}
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $p['status'] === 'Tersedia' ? 'selesai' : ($p['status'] === 'Menipis' ? 'proses' : 'batal') }}">
                                {{ $p['status'] }}
                            </span>
                        </td>
                        <td class="td-money">Rp {{ number_format($p['harga'], 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const ctx2 = document.getElementById('stokChart').getContext('2d');
    new Chart(ctx2, {
        type: 'doughnut',
        data: {
            labels: ['Cookies', 'Hampers'],
            datasets: [{
                data: [{{ $chartStok['cookies'] }}, {{ $chartStok['hampers'] }}],
                backgroundColor: ['rgba(167,119,97,0.85)', 'rgba(201,167,178,0.85)'],
                borderColor: ['#a77761', '#c9a7b2'],
                borderWidth: 2,
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed} unit`
                    }
                }
            }
        }
    });
</script>
@endsection
