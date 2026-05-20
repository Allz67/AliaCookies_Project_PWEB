@props([
    'judul',
    'nilai',
    'warna',
    'perubahan',
    'trend' => 'up',
])

<div class="stat-card stat-{{ $warna }}">
    <div class="stat-icon">
        {{ $slot }}
    </div>
    <div class="stat-info">
        <span class="stat-label">{{ $judul }}</span>
        <span class="stat-value">{{ $nilai }}</span>
        <span class="stat-change {{ $trend }}">
            {{ $trend === 'up' ? '↑' : '↓' }} {{ $perubahan }}
        </span>
    </div>
</div>
