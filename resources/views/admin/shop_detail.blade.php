@extends('layouts.app')

@section('content')
@php
    // Mengambil data jumlah pesanan berhasil 7 hari terakhir khusus untuk toko ini
    $labels = [];
    $dataPesanan = [];
    
    for ($i = 6; $i >= 0; $i--) {
        $date = \Carbon\Carbon::today()->subDays($i);
        $labels[] = $date->translatedFormat('d M Y');
        
        $dataPesanan[] = \App\Models\Order::where('shop_id', $shop->id)
            ->where('status', 'success')
            ->whereDate('created_at', $date)
            ->count();
    }
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- Tambahan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .detail-container { animation: fadeIn 0.8s ease-out; padding-bottom: 50px; }
    .back-link { color: #fcd34d; text-decoration: none; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 15px; }
    .back-link:hover { opacity: 0.7; transform: translateX(-5px); }

    .header-title { font-size: 32px; font-weight: 900; color: #ffffff; margin-top: 5px; letter-spacing: -1px; }
    .header-subtitle { color: #94a3b8; font-size: 14px; margin-bottom: 35px; display: block; }

    .card-detail {
        background: rgba(255, 255, 255, 0.04);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 40px;
        padding: 40px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        height: 100%;
        box-sizing: border-box;
    }

    .detail-grid {
        display: grid; 
        grid-template-columns: 1fr 1.8fr; 
        gap: 25px; 
        align-items: start;
    }

    .store-logo-wrapper {
        width: 120px; height: 120px;
        background: rgba(0, 0, 0, 0.2);
        border: 2px solid #fcd34d;
        border-radius: 35px;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 25px;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(252, 211, 77, 0.15);
    }
    .store-logo-img { width: 100%; height: 100%; object-fit: cover; }
    .store-icon-placeholder { font-size: 50px; color: #fcd34d; }

    .label-emas { color: #fcd34d; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 6px; display: block; }
    .info-value { color: #ffffff; font-size: 15px; font-weight: 600; margin-bottom: 22px; display: block; }

    .badge-premium { padding: 6px 14px; border-radius: 50px; font-size: 9px; font-weight: 900; text-transform: uppercase; border: 1px solid transparent; }
    .badge-active { background: rgba(34, 197, 94, 0.15); color: #4ade80; border-color: rgba(34, 197, 94, 0.3); }
    .badge-pending { background: rgba(252, 211, 77, 0.15); color: #fcd34d; border-color: rgba(252, 211, 77, 0.3); }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    @media (max-width: 992px) {
        .detail-grid { grid-template-columns: 1fr; }
        .card-detail { padding: 30px; border-radius: 30px; }
        .header-title { font-size: 26px; }
    }
</style>

<div class="detail-container">
    <div class="container">
        <a href="{{ route('admin.shops') }}" class="back-link">
            <i class="fa-solid fa-chevron-left"></i> Kembali ke List Tenant
        </a>
        
        <h1 class="header-title">Profil Eksklusif Tenant</h1>
        <span class="header-subtitle">Data transparansi operasional untuk unit {{ $shop->name }}</span>

        <div class="detail-grid">
            
            <!-- SISI KIRI: IDENTITAS & BRANDING -->
            <div class="card-detail" style="text-align: center;">
                <div class="store-logo-wrapper">
                    @if($shop->logo)
                        <img src="{{ asset('storage/' . $shop->logo) }}" class="store-logo-img" alt="Logo {{ $shop->name }}">
                    @else
                        <i class="fa-solid fa-store store-icon-placeholder"></i>
                    @endif
                </div>
                
                <h2 style="color: #fff; font-size: 24px; font-weight: 800; margin-bottom: 8px;">{{ $shop->name }}</h2>
                <div style="margin-bottom: 35px;">
                    <span class="badge-premium {{ $shop->status == 'active' ? 'badge-active' : 'badge-pending' }}">
                        Status: {{ $shop->status == 'active' ? 'Aktif' : 'Non-Aktif' }}
                    </span>
                </div>

                <div style="text-align: left; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 30px;">
                    <span class="label-emas">Pemilik Utama</span>
                    <span class="info-value">{{ $shop->user->name ?? 'N/A' }}</span>

                    <span class="label-emas">Kontak Digital</span>
                    <span class="info-value">{{ $shop->user->email ?? '-' }}</span>

                    <span class="label-emas">Mulai Bergabung</span>
                    <span class="info-value">{{ $shop->created_at->translatedFormat('d F Y') }}</span>
                </div>
            </div>

            <!-- SISI KANAN: GRAFIK KINERJA / ANALISIS TENANT -->
            <div class="card-detail">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 20px;">
                    <h3 style="color: #fff; font-size: 20px; font-weight: 800; margin: 0;">
                        <i class="fa-solid fa-chart-area" style="color: #38bdf8; margin-right: 10px;"></i>Grafik Performa Tenant
                    </h3>
                </div>

                <p style="color: #94a3b8; font-size: 13px; margin-bottom: 25px;">
                    Visualisasi tren pesanan dan aktivitas operasional toko selama 7 hari terakhir.
                </p>

                <!-- Area Grafik Detail Penjualan -->
                <div style="position: relative; height: 280px; width: 100%; background: rgba(0, 0, 0, 0.2); border-radius: 20px; padding: 15px; border: 1px solid rgba(255,255,255,0.05);">
                    <canvas id="tenantPerformanceChart"></canvas>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Inisialisasi Grafik Kinerja Tenant di Halaman Detail Menggunakan Data Real
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('tenantPerformanceChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'bar',
            data: {
                // Parsing array label tanggal dari PHP ke Javascript
                labels: {!! json_encode($labels) !!},
                datasets: [{
                    label: 'Jumlah Pesanan Berhasil',
                    // Parsing array total pesanan dari PHP ke Javascript
                    data: {!! json_encode($dataPesanan) !!},
                    backgroundColor: '#38bdf8',
                    borderRadius: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#fff', font: { size: 11 } } }
                },
                scales: {
                    x: { 
                        ticks: { color: '#94a3b8' }, 
                        grid: { display: false } 
                    },
                    y: { 
                        ticks: { 
                            color: '#94a3b8', 
                            stepSize: 1, // Pastikan interval angka sumbu Y berupa bilangan bulat
                            precision: 0 // Menghindari angka desimal (karena pesanan tidak mungkin berbentuk desimal)
                        }, 
                        grid: { color: 'rgba(255,255,255,0.05)' } 
                    }
                }
            }
        });
    });
</script>
@endsection