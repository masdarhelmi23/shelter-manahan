@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* Tipografi Utama */
    .judul-halaman { font-size: 36px; font-weight: 800; color: #ffffff; margin-bottom: 5px; letter-spacing: -1px; text-shadow: 0 2px 10px rgba(0,0,0,0.3); }
    .subjudul-halaman { font-size: 13px; color: #fcd34d; letter-spacing: 3px; text-transform: uppercase; font-weight: 700; margin-bottom: 40px; display: block; }

    .grid-kpi { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 25px; }

    /* Kartu Mewah - Glassmorphism */
    .kartu-mewah {
        background: rgba(255, 255, 255, 0.05); 
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 30px;
        padding: 30px;
        position: relative;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); 
        border: 1px solid rgba(255, 255, 255, 0.1);
        transition: all 0.4s ease;
    }
    .kartu-mewah:hover { transform: translateY(-10px); border-color: #fcd34d; background: rgba(255, 255, 255, 0.1); }

    .label-kpi { font-size: 11px; font-weight: 800; color: #94a3b8; letter-spacing: 1.5px; display: block; margin-bottom: 15px; text-transform: uppercase; }
    .nilai-kpi { font-size: 48px; font-weight: 900; color: #ffffff; line-height: 1; margin-bottom: 10px; }
    .nilai-finansial { color: #4ade80; font-size: 36px; }

    /* Notifikasi Feed */
    .feed-container { margin-top: 40px; display: grid; grid-template-columns: 2fr 1fr; gap: 30px; }
    .panel-glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(10px); border-radius: 30px; padding: 30px; border: 1px solid rgba(255, 255, 255, 0.1); }
    .judul-panel { font-size: 14px; font-weight: 800; color: #fcd34d; letter-spacing: 2px; margin-bottom: 25px; text-transform: uppercase; display: flex; align-items: center; gap: 10px; }

    .notif-item {
        display: flex; align-items: center; gap: 15px; padding: 15px; background: rgba(255, 255, 255, 0.03); border-radius: 20px; margin-bottom: 12px; border: 1px solid rgba(255, 255, 255, 0.05); transition: 0.3s;
    }
    .notif-item:hover { background: rgba(255, 255, 255, 0.08); transform: scale(1.02); }
    .icon-notif { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 18px; }
    .notif-info h4 { font-size: 14px; color: #fff; margin: 0; }
    .notif-info p { font-size: 11px; color: #94a3b8; margin: 2px 0 0 0; }

    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    .live-indicator { width: 8px; height: 8px; background: #ef4444; border-radius: 50%; animation: pulse 1.5s infinite; }
</style>

<div class="judul-halaman">Ringkasan Eksekutif</div>
<div style="display: flex; align-items: center; gap: 10px; margin-bottom: 40px;">
    <div class="live-indicator"></div>
    <span class="subjudul-halaman" style="margin-bottom: 0;">Analisis Real-Time Shelter Manahan</span>
</div>

<div class="grid-kpi">
    <div class="kartu-mewah">
        <span class="label-kpi">Entitas Pengguna</span>
        <div class="nilai-kpi">{{ $totalUser }}</div>
        <p style="font-size: 12px; color: #94a3b8; font-weight: 600;"><i class="fas fa-users"></i> Akun Terdaftar</p>
    </div>

    <div class="kartu-mewah">
        <span class="label-kpi">Okupansi Komersial</span>
        <div class="nilai-kpi">{{ $totalToko }}</div>
        <p style="font-size: 12px; color: #94a3b8; font-weight: 600;"><i class="fas fa-shop"></i> Unit Toko Aktif</p>
    </div>

    <div class="kartu-mewah" style="border-left: 5px solid #4ade80;">
        <span class="label-kpi">Dana Mengendap</span>
        <div class="nilai-kpi nilai-finansial">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</div>
        <p style="font-size: 12px; color: #4ade80; font-weight: 600;"><i class="fas fa-vault"></i> Akumulasi Saldo Tenant</p>
    </div>
</div>

<div class="feed-container">
    <!-- PANEL PENGAJUAN WD -->
    <div class="panel-glass">
        <h3 class="judul-panel"><i class="fas fa-bell"></i> Antrean Penarikan Dana (WD)</h3>
        @forelse($pendingWithdrawals as $wd)
        <div class="notif-item">
            <div class="icon-notif" style="background: rgba(252, 211, 77, 0.1); color: #fcd34d;">
                <i class="fas fa-money-bill-transfer"></i>
            </div>
            <div class="notif-info" style="flex: 1;">
                <h4>{{ $wd->shop->name }} mengajukan Rp {{ number_format($wd->amount, 0, ',', '.') }}</h4>
                <p>Diajukan {{ $wd->created_at->diffForHumans() }}</p>
            </div>
            <a href="{{ route('admin.withdrawals') }}" style="color: #fcd34d; font-size: 12px; font-weight: 800; text-decoration: none;">PROSES <i class="fas fa-chevron-right"></i></a>
        </div>
        @empty
        <div style="text-align: center; padding: 40px; color: #475569;">
            <i class="fas fa-check-circle fa-2x" style="margin-bottom: 10px; display: block;"></i>
            Semua pengajuan telah diproses.
        </div>
        @endforelse
    </div>

    <!-- PANEL INTEGRITAS SISTEM -->
    <div class="panel-glass" style="border-left: 5px solid #38bdf8;">
        <h3 class="judul-panel"><i class="fas fa-microchip"></i> Integritas Sistem</h3>
        
        <div style="margin-bottom: 25px;">
            <span style="display: block; color: #94a3b8; font-size: 10px; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Tenant Menunggu Aktivasi</span>
            <div style="display: flex; align-items: center; gap: 12px; margin-top: 5px;">
                <div style="width: 35px; height: 35px; background: rgba(252, 211, 77, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #fcd34d;">
                    <i class="fas fa-store-slash"></i>
                </div>
                <span style="color: #fcd34d; font-weight: 800; font-size: 18px;">
                    {{ \App\Models\Shop::where('status', 'pending')->count() }} <small style="font-size: 11px; font-weight: 600; color: #94a3b8;">Toko</small>
                </span>
            </div>
        </div>

        <div style="margin-bottom: 25px;">
            <span style="display: block; color: #94a3b8; font-size: 10px; text-transform: uppercase; font-weight: 800; letter-spacing: 1px;">Volume Transaksi (Hari Ini)</span>
            <div style="display: flex; align-items: center; gap: 12px; margin-top: 5px;">
                <div style="width: 35px; height: 35px; background: rgba(34, 197, 94, 0.1); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: #4ade80;">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span style="color: #4ade80; font-weight: 800; font-size: 18px;">
                    {{-- FIXED: Menggunakan kolom 'amount' sesuai gambar phpMyAdmin --}}
                    Rp {{ number_format(\App\Models\Order::where('status', 'paid')->whereDate('created_at', today())->sum('amount'), 0, ',', '.') }}
                </span>
            </div>
        </div>

        <hr style="border: 0; border-top: 1px solid rgba(255,255,255,0.1); margin: 25px 0;">
        
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div>
                <span style="display: block; color: #64748b; font-size: 9px; text-transform: uppercase; font-weight: 800;">Waktu Server</span>
                <span style="color: #fff; font-weight: 700; font-size: 12px;">{{ now()->format('H:i') }} WIB</span>
            </div>
            <div style="text-align: right;">
                <span style="display: block; color: #64748b; font-size: 9px; text-transform: uppercase; font-weight: 800;">Status API</span>
                <span style="color: #4ade80; font-weight: 700; font-size: 12px;"><i class="fas fa-check-circle" style="font-size: 10px;"></i> Berjalan</span>
            </div>
        </div>
    </div>
</div>
@endsection