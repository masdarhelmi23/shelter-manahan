@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* FIX: Reset pembungkus agar seimbang di semua sisi */
    .main-dashboard-wrapper {
        width: 100%;
        margin: 0 auto;
        padding: 0 20px; /* Space kiri & kanan identik */
        box-sizing: border-box;
        overflow-x: hidden; /* Mencegah konten meluber ke kanan */
    }

    /* Tipografi Utama */
    .judul-halaman { 
        font-size: 32px; 
        font-weight: 800; 
        color: #ffffff; 
        margin-bottom: 5px; 
        letter-spacing: -1px; 
        text-shadow: 0 2px 10px rgba(0,0,0,0.3); 
    }
    
    .subjudul-halaman { 
        font-size: 11px; 
        color: #fcd34d; 
        letter-spacing: 2px; 
        text-transform: uppercase; 
        font-weight: 700; 
        margin-bottom: 35px; 
        display: block; 
    }

    /* Grid KPI - Fluid & Center */
    .grid-kpi { 
        display: grid; 
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); 
        gap: 20px; 
        width: 100%;
    }

    /* Kartu Mewah - Glassmorphism */
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
    .nilai-finansial { color: #4ade80; font-size: 32px; }

    /* Feed & Panel */
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

    .notif-item {
        display: flex; align-items: center; gap: 15px; padding: 15px; background: rgba(255, 255, 255, 0.03); border-radius: 18px; margin-bottom: 12px; border: 1px solid rgba(255, 255, 255, 0.05);
    }
    
    .icon-notif { width: 35px; height: 35px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; }

    @keyframes pulse { 0% { opacity: 1; } 50% { opacity: 0.5; } 100% { opacity: 1; } }
    .live-indicator { width: 8px; height: 8px; background: #ef4444; border-radius: 50%; animation: pulse 1.5s infinite; }

    /* MOBILE CENTER CORRECTION */
    @media (max-width: 992px) {
        .feed-container { grid-template-columns: 1fr; }
    }

    @media (max-width: 576px) {
        .main-dashboard-wrapper { padding: 0 15px; } /* Jarak kiri-kanan HP disamakan */
        .judul-halaman { font-size: 26px; text-align: center; }
        .subjudul-halaman { text-align: center; font-size: 10px; }
        .nilai-kpi { font-size: 36px; }
        .nilai-finansial { font-size: 26px; }
        
        /* Menghilangkan margin bawaan yang mungkin ada di layout induk */
        body { margin: 0; padding: 0; }
        
        .header-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
            margin-top: 10px;
        }
    }
</style>

<div class="main-dashboard-wrapper">
    <!-- Bagian Header yang dipaksa Center -->
    <div class="header-section">
        <div class="judul-halaman">Ringkasan Eksekutif</div>
        <div style="display: flex; align-items: center; justify-content: center; gap: 8px; margin-bottom: 35px;">
            <div class="live-indicator"></div>
            <span class="subjudul-halaman" style="margin-bottom: 0;">Analisis Real-Time Shelter Manahan</span>
        </div>
    </div>

    <div class="grid-kpi">
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

        <div class="kartu-mewah" style="border-left: 5px solid #4ade80;">
            <span class="label-kpi">Dana Mengendap</span>
            <div class="nilai-kpi nilai-finansial">Rp {{ number_format($totalSaldo, 0, ',', '.') }}</div>
            <p style="font-size: 11px; color: #4ade80; font-weight: 600;"><i class="fas fa-vault"></i> Akumulasi Saldo Tenant</p>
        </div>
    </div>

    <div class="feed-container">
        <div class="panel-glass">
            <h3 class="judul-panel"><i class="fas fa-bell"></i> Antrean Penarikan Dana (WD)</h3>
            @forelse($pendingWithdrawals as $wd)
            <div class="notif-item">
                <div class="icon-notif" style="background: rgba(252, 211, 77, 0.1); color: #fcd34d;">
                    <i class="fas fa-money-bill-transfer"></i>
                </div>
                <div style="flex: 1;">
                    <h4 style="font-size: 13px; color: #fff; margin: 0;">{{ $wd->shop->name }}</h4>
                    <p style="font-size: 11px; color: #4ade80; font-weight: 700; margin: 2px 0;">Rp {{ number_format($wd->amount, 0, ',', '.') }}</p>
                    <p style="font-size: 9px; color: #94a3b8;">{{ $wd->created_at->diffForHumans() }}</p>
                </div>
                <a href="{{ route('admin.withdrawals') }}" style="color: #fcd34d; font-size: 10px; font-weight: 800; text-decoration: none;">PROSES <i class="fas fa-chevron-right"></i></a>
            </div>
            @empty
            <div style="text-align: center; padding: 30px; color: #475569; font-size: 13px;">
                Semua pengajuan telah diproses.
            </div>
            @endforelse
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
                    <span style="color: #4ade80; font-weight: 800; font-size: 16px;">Rp {{ number_format(\App\Models\Order::where('status', 'paid')->whereDate('created_at', today())->sum('amount'), 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection