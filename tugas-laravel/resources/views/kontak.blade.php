@extends('layouts.app')

@section('title', 'Hubungi Kami')

@section('content')
<div class="page-wrapper">
    <div class="card">
        <h2 class="card-title">Hubungi Tim Kami</h2>
        <p class="page-sub">Kami siap melayani pesanan hampers dan kustomisasi cookies Anda.</p>

        <div class="kontak-grid" style="margin-top: 2rem; display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div class="info-item">
                <strong>Alamat Toko:</strong>
                <p>Jl. Letjen Suprapto IV Garuda 12, Jember, Jawa Timur</p>
            </div>
            <div class="info-item">
                <strong>WhatsApp:</strong>
                <p>+62 856-4856-9562</p>
            </div>
        </div>
    </div>
</div>
@endsection
