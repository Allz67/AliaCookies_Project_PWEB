@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="page-wrapper">

    <div class="page-header">
        <div>
            <p class="greeting" id="greeting">Halo, Selamat Pagi 👋</p>
            <h1 class="page-title">Halo, <span class="highlight">{{ $username }}</span>!</h1>
            <h1>Dashboard Alia Cookies</h1>
            <p class="page-sub">Berikut ringkasan penjualan toko Alia Cookies hari ini.</p>
        </div>
        <div class="page-header-img">
            <img src="{{ asset('images/cookies.png') }}" alt="Cookies" onerror="this.style.display='none'">
        </div>
    </div>

    <div class="stats-grid">
        <div class="stat-card stat-green">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Pendapatan</span>
                <span class="stat-value">{{ $stats['pendapatan'] }}</span>
                <span class="stat-change up">↑ 12% dari bulan lalu</span>
            </div>
        </div>
        <div class="stat-card stat-rose">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Total Pesanan</span>
                <span class="stat-value">{{ $stats['pesanan'] }}</span>
                <span class="stat-change up">↑ 8% dari bulan lalu</span>
            </div>
        </div>
        <div class="stat-card stat-mocha">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Produk Terjual</span>
                <span class="stat-value">{{ $stats['terjual'] }}</span>
                <span class="stat-change up">↑ 5% dari bulan lalu</span>
            </div>
        </div>
        <div class="stat-card stat-lavender">
            <div class="stat-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            </div>
            <div class="stat-info">
                <span class="stat-label">Pelanggan Aktif</span>
                <span class="stat-value">{{ $stats['pelanggan'] }}</span>
                <span class="stat-change up">↑ 3 pelanggan baru</span>
            </div>
        </div>
    </div>


    <div class="section-grid">
        <div class="card chart-card">
            <div class="card-header">
                <h2 class="card-title">Grafik Penjualan Mingguan</h2>
                <div class="chart-filter">
                    <button class="chip active" onclick="switchChart('mingguan', this)">Mingguan</button>
                    <button class="chip" onclick="switchChart('harian', this)">Harian</button>
                </div>
            </div>
            <div class="chart-container">
                <canvas id="salesChart"></canvas>
            </div>
        </div>

        <div class="card quick-info-card">
            <div class="card-header">
                <h2 class="card-title">Info Cepat</h2>
            </div>
            <div class="quick-list">
                <div class="quick-item">
                    <div class="quick-dot dot-green"></div>
                    <div>
                        <span class="quick-label">Pesanan Selesai</span>
                        <span class="quick-val">5 transaksi</span>
                    </div>
                </div>
                <div class="quick-item">
                    <div class="quick-dot dot-yellow"></div>
                    <div>
                        <span class="quick-label">Sedang Diproses</span>
                        <span class="quick-val">2 transaksi</span>
                    </div>
                </div>
                <div class="quick-item">
                    <div class="quick-dot dot-red"></div>
                    <div>
                        <span class="quick-label">Dibatalkan</span>
                        <span class="quick-val">1 transaksi</span>
                    </div>
                </div>
                <div class="quick-item">
                    <div class="quick-dot dot-mocha"></div>
                    <div>
                        <span class="quick-label">Produk Terlaris</span>
                        <span class="quick-val">Choco Chip Cookies</span>
                    </div>
                </div>
                <div class="quick-item">
                    <div class="quick-dot dot-lavender"></div>
                    <div>
                        <span class="quick-label">Pendapatan Hari Ini</span>
                        <span class="quick-val">Rp 1.850.000</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card table-card">
        <div class="card-header">
            <h2 class="card-title">Transaksi Terakhir</h2>
            <div class="table-controls">
                <div class="search-box">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                    <input type="text" id="searchTrx" placeholder="Cari transaksi..." onkeyup="searchTable('trxTable', this.value)">
                </div>
                <select id="filterStatus" onchange="filterTableByStatus('trxTable', 3, this.value)" class="filter-select">
                    <option value="">Semua Status</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Proses">Proses</option>
                    <option value="Batal">Batal</option>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="data-table" id="trxTable">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Pelanggan</th>
                        <th>Produk</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($transactions as $trx)
                    <tr>
                        <td><span class="trx-id">{{ $trx['id'] }}</span></td>
                        <td>{{ $trx['customer'] }}</td>
                        <td>{{ $trx['produk'] }} <span class="qty-badge">x{{ $trx['qty'] }}</span></td>
                        <td>
                            <span class="status-badge status-{{ strtolower($trx['status']) }}">
                                {{ $trx['status'] }}
                            </span>
                        </td>
                        <td class="td-money">Rp {{ number_format($trx['total'], 0, ',', '.') }}</td>
                        <td class="td-date">{{ $trx['tanggal'] }}</td>
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
    const grafikLabels = @json($grafik['labels']);
    const grafikData   = @json($grafik['data']);

    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: grafikLabels,
            datasets: [{
                label: 'Pendapatan (Rp)',
                data: grafikData,
                backgroundColor: 'rgba(167, 119, 97, 0.18)',
                borderColor: '#a77761',
                borderWidth: 2,
                borderRadius: 10,
                borderSkipped: false,
                tension: 0.4,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(167,119,97,0.08)' },
                    ticks: {
                        font: { family: 'DM Sans', size: 11 },
                        color: '#9c8275',
                        callback: v => 'Rp ' + (v/1000) + 'K'
                    }
                },
                x: {
                    grid: { display: false },
                    ticks: { font: { family: 'DM Sans', size: 11 }, color: '#9c8275' }
                }
            }
        }
    });

    function switchChart(type, btn) {
        document.querySelectorAll('.chip').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        if (type === 'harian') {
            salesChart.data.labels = ['08:00','10:00','12:00','14:00','16:00','18:00','20:00'];
            salesChart.data.datasets[0].data = [120000,340000,580000,420000,760000,890000,640000];
        } else {
            salesChart.data.labels = grafikLabels;
            salesChart.data.datasets[0].data = grafikData;
        }
        salesChart.update();
    }
</script>
@endsection
