@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="page-wrapper">
    <div class="page-header">
        <div>
            <p class="greeting" id="greeting">Halo, Selamat Pagi 👋</p>
            <h1 class="page-title">Profil <span class="highlight">{{ $username }}</span></h1>
            <p class="page-sub">Informasi akun dan detail administrator Alia Cookies.</p>
        </div>
    </div>

    <div class="profile-grid">
        <div class="card profile-card">
            <div class="profile-avatar-wrap">
                <div class="profile-avatar-ring">
                    <img src="{{ asset('images/aliacookies.png') }}" alt="Logo" class="profile-avatar" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                    <div class="profile-avatar-fallback" style="display:none">
                        <span>{{ strtoupper(substr($username, 0, 1)) }}</span>
                    </div>
                </div>
                <div class="profile-badge-role">{{ $user['role'] }}</div>
            </div>
            <h2 class="profile-name">{{ $user['nama'] }}</h2>
            <p class="profile-store">{{ $user['toko'] }}</p>
            <div class="profile-chips">
                <span class="chip-tag chip-active">● Aktif</span>
                <span class="chip-tag">Admin</span>
            </div>

            <div class="profile-divider"></div>

            <div class="profile-meta">
                <div class="profile-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                    <span>{{ $user['email'] }}</span>
                </div>
                <div class="profile-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>
                    <span>{{ $user['lokasi'] }}</span>
                </div>
                <div class="profile-meta-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07A19.5 19.5 0 0 1 4.69 12 19.79 19.79 0 0 1 1.61 3.37 2 2 0 0 1 3.58 1h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L7.91 8.56a16 16 0 0 0 6.53 6.53l1.62-1.81a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                    <span>{{ $user['no_hp'] }}</span>
                </div>
            </div>
        </div>

        <div class="profile-detail-col">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Informasi Akun</h2>
                </div>
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">Username</span>
                        <span class="info-value">{{ $user['nama'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Role</span>
                        <span class="info-value">
                            <span class="status-badge status-selesai">{{ $user['role'] }}</span>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Email</span>
                        <span class="info-value">{{ $user['email'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">No. HP</span>
                        <span class="info-value">{{ $user['no_hp'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Lokasi</span>
                        <span class="info-value">{{ $user['lokasi'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Bergabung</span>
                        <span class="info-value">{{ $user['bergabung'] }}</span>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Sesi Login</h2>
                </div>
                <div class="info-list">
                    <div class="info-row">
                        <span class="info-label">Status Sesi</span>
                        <span class="info-value"><span class="chip-tag chip-active">● Aktif</span></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Login Terakhir</span>
                        <span class="info-value">{{ $user['tanggal_login'] }}</span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Toko</span>
                        <span class="info-value">{{ $user['toko'] }}</span>
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
@endsection
