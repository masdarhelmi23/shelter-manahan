@extends('layouts.app')

@section('content')
@php
    // Mengambil data jumlah transaksi berhasil selama 6 bulan terakhir
    $bulanLabels = [];
    $dataTransaksi = [];
    
    for ($i = 5; $i >= 0; $i--) {
        $month = \Carbon\Carbon::today()->subMonths($i);
        $bulanLabels[] = $month->translatedFormat('M Y');
        
        // Menghitung jumlah order dengan status 'success' pada bulan dan tahun tersebut
        $dataTransaksi[] = \App\Models\Order::where('status', 'success')
            ->whereMonth('created_at', $month->month)
            ->whereYear('created_at', $month->year)
            ->count();
    }
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- Tambahan Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    .main-dashboard-wrapper {
        width: 100%;
        margin: 0 auto;
        padding: 0 20px;
        box-sizing: border-box;
        overflow-x: hidden;
    }

    .judul-halaman { font-size: 32px; font-weight: 800; color: #ffffff; margin-bottom: 5px; letter-spacing: -1px; text-shadow: 0 2px 10px rgba(0,0,0,0.3); }
    .subjudul-halaman { font-size: 11px; color: #fcd34d; letter-spacing: 2px; text-transform: uppercase; font-weight: 700; margin-bottom: 35px; display: block; }

    .grid-kpi { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
        gap: 20px; 
        width: 100%;
    }

    .kartu-mewah {
        background: rgba(255, 255, 255, 0.05); 
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 25px;
        padding: 25px;
        position: relative;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2); 
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.4s ease;
        width: 100%;
        box-sizing: border-box;
    }
    
    .label-kpi { font-size: 10px; font-weight: 800; color: #94a3b8; letter-spacing: 1px; display: block; margin-bottom: 15px; text-transform: uppercase; }
    .nilai-kpi { font-size: 42px; font-weight: 900; color: #ffffff; line-height: 1; margin-bottom: 10px; }

    .feed-container { 
        margin-top: 30px; 
        display: grid; 
        grid-template-columns: 2fr 1fr; 
        gap: 20px; 
        width: 100%;
    }

    .panel-glass { 
        background: rgba(15, 23, 42, 0.6); 
        backdrop-filter: blur(10px); 
        -webkit-backdrop-filter: blur(10px);
        border-radius: 25px; 
        padding: 25px; 
        border: 1px solid rgba(255, 255, 255, 0.1); 
        box-sizing: border-box;
    }

    .judul-panel { font-size: 13px; font-weight: 800; color: #fcd34d; letter-spacing: 1px; margin-bottom: 20px; text-transform: uppercase; display: flex; align-items: center; gap: 10px; }

    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    .live-indicator { width: 8px; height: 8px; background: #ef4444; border-radius: 50%; animation: pulse 1.5s infinite; }

    @media (max-width: 992px) {
        .feed-container { grid-template-columns: 1fr; }
    }

    @media (max-width: 576px) {
        .main-dashboard-wrapper { padding: 0 15px; }
        .judul-halaman { font-size: 26px; text-align: center; }
        .subjudul-halaman { text-align: center; font-size: 10px; }
        .nilai-kpi { font-size: 36px; }
        body { margin: 0; padding: 0; }
        .header-section { display: flex; flex-direction: column; align-items: center; width: 100%; margin-top: 10px; }
    }
</style>

<div class="main-dashboard-wrapper">
    <div class="header-section">
        <div class="judul-halaman">Ringkasan Eksekutif</div>
        <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 35px;">
            <div class="live-indicator"></div>
            <span class="subjudul-halaman" style="margin-bottom: 0;">Analisis Real-Time Shelter Manahan</span>
        </div>
    </div>

    <!-- Hanya menampilkan 2 KPI utama (Pengguna & Toko Aktif) karena sistem saldo & WD dihapus -->
    <div class="grid-kpi" style="grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));">
        <div class="kartu-mewah">
            <span class="label-kpi">Entitas Pengguna</span>
            <div class="nilai-kpi">{{ $totalUser }}</div>
            <p style="font-size: 11px; color: #94a3b8; font-weight: 600;"><i class="fas fa-users"></i> Akun Terdaftar</p>
        </div>

        <div class="kartu-mewah">
            <span class="label-kpi">Okupansi Komersial</span>
            <div class="nilai-kpi">{{ $totalToko }}</div>
            <p style="font-size: 11px; color: #94a3b8; font-weight: 600;"><i class="fas fa-shop"></i> Unit Toko Aktif</p>
        </div>
    </div>

    <div class="feed-container">
        <!-- Panel Grafik Statistik Pengganti Antrean WD -->
        <div class="panel-glass">
            <h3 class="judul-panel"><i class="fas fa-chart-bar"></i> Statistik Sistem & Transaksi</h3>
            <div style="position: relative; height: 250px; width: 100%;">
                <canvas id="adminStatsChart"></canvas>
            </div>
        </div>

        <div class="panel-glass" style="border-left: 5px solid #38bdf8;">
            <h3 class="judul-panel"><i class="fas fa-microchip"></i> Integritas Sistem</h3>
            <div style="margin-bottom: 20px;">
                <span class="label-kpi" style="margin-bottom: 5px;">Tenant Menunggu Aktivasi</span>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <span style="color: #fcd34d; font-weight: 800; font-size: 16px;">{{ \App\Models\Shop::where('status', 'pending')->count() }} Toko</span>
                </div>
            </div>
            <div style="margin-bottom: 20px;">
                <span class="label-kpi" style="margin-bottom: 5px;">Volume Transaksi (Hari Ini)</span>
                <div style="display: flex; align-items: center; gap: 10px;">
                    <!-- Perbaikan status dari 'paid' menjadi 'success' -->
                    <span style="color: #4ade80; font-weight: 800; font-size: 16px;">Rp {{ number_format(\App\Models\Order::where('status', 'success')->whereDate('created_at', today())->sum('amount'), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Inisialisasi Grafik Analitik di Dashboard Admin dengan Data Real
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('adminStatsChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                // Parse label bulan dari PHP ke JS
                labels: {!! json_encode($bulanLabels) !!},
                datasets: [{
                    label: 'Total Transaksi Berhasil',
                    // Parse data nominal/jumlah dari PHP ke JS
                    data: {!! json_encode($dataTransaksi) !!},
                    borderColor: '#fcd34d',
                    backgroundColor: 'rgba(252, 211, 77, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { labels: { color: '#fff' } }
                },
                scales: {
                    x: { 
                        ticks: { color: '#94a3b8' }, 
                        grid: { color: 'rgba(255,255,255,0.05)' } 
                    },
                    y: { 
                        ticks: { 
                            color: '#94a3b8',
                            stepSize: 1, // Memastikan Y-Axis tidak desimal karena ini jumlah transaksi
                            precision: 0 
                        }, 
                        grid: { color: 'rgba(255,255,255,0.05)' } 
                    }
                }
            }
        });
    });
</script>
@endsection