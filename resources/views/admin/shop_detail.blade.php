@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    /* =========================================
        BASE & LUXURY ANIMATION
    ========================================= */
    .detail-container { animation: fadeIn 0.8s ease-out; padding-bottom: 50px; }
    .back-link { color: #fcd34d; text-decoration: none; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; display: inline-flex; align-items: center; gap: 8px; margin-bottom: 15px; }
    .back-link:hover { opacity: 0.7; transform: translateX(-5px); }

    .header-title { font-size: 32px; font-weight: 900; color: #ffffff; margin-top: 5px; letter-spacing: -1px; }
    .header-subtitle { color: #94a3b8; font-size: 14px; margin-bottom: 35px; display: block; }

    /* Luxury Glass Cards */
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

    /* Layout Grid */
    .detail-grid {
        display: grid; 
        grid-template-columns: 1fr 1.8fr; 
        gap: 25px; 
        align-items: start;
    }

    /* Container Logo Toko */
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

    /* Info Styling */
    .label-emas { color: #fcd34d; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 6px; display: block; }
    .info-value { color: #ffffff; font-size: 15px; font-weight: 600; margin-bottom: 22px; display: block; }

    /* Financial Dashboard */
    .saldo-box { text-align: right; }
    .saldo-label { color: #4ade80; font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1.5px; }
    .saldo-value { color: #4ade80; font-size: clamp(28px, 4vw, 44px); font-weight: 900; letter-spacing: -1px; display: block; margin-top: 5px; }

    /* Table History Luxury */
    .table-history { width: 100%; border-collapse: separate; border-spacing: 0 10px; margin-top: 15px; }
    .table-history th { text-align: left; padding: 12px 15px; color: #fcd34d; font-size: 10px; font-weight: 900; text-transform: uppercase; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .table-history td { padding: 15px; color: #ffffff; font-size: 13px; background: rgba(255,255,255,0.02); }
    .table-history tr td:first-child { border-radius: 15px 0 0 15px; }
    .table-history tr td:last-child { border-radius: 0 15px 15px 0; text-align: right; }

    .badge-premium { padding: 6px 14px; border-radius: 50px; font-size: 9px; font-weight: 900; text-transform: uppercase; border: 1px solid transparent; }
    .badge-active { background: rgba(34, 197, 94, 0.15); color: #4ade80; border-color: rgba(34, 197, 94, 0.3); }
    .badge-pending { background: rgba(252, 211, 77, 0.15); color: #fcd34d; border-color: rgba(252, 211, 77, 0.3); }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* =========================================
        RESPONSIVE MOBILE REVISION
    ========================================= */
    @media (max-width: 992px) {
        .detail-grid { grid-template-columns: 1fr; }
        .card-detail { padding: 30px; border-radius: 30px; }
        .header-title { font-size: 26px; }
        .saldo-box { text-align: center; width: 100%; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.1); }
        
        .financial-header { flex-direction: column; align-items: center; text-align: center; }

        /* Tabel Transformation for Mobile */
        .table-history thead { display: none; }
        .table-history, .table-history tbody, .table-history tr, .table-history td { display: block; width: 100%; }
        .table-history tr { margin-bottom: 15px; border: 1px solid rgba(255,255,255,0.1); border-radius: 20px; overflow: hidden; }
        .table-history td { 
            display: flex; justify-content: space-between; align-items: center; 
            text-align: right; padding: 12px 20px; border-radius: 0 !important;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        .table-history td:last-child { border-bottom: none; }
        .table-history td::before { 
            content: attr(data-label); font-weight: 900; color: #fcd34d; 
            text-transform: uppercase; font-size: 10px; text-align: left; 
        }
    }
</style>

<div class="detail-container">
    <div class="container">
        <!-- Breadcrumb / Back Link -->
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

            <!-- SISI KANAN: FINANCIAL INSIGHT & LOGS -->
            <div class="card-detail">
                <div class="financial-header" style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                    <h3 style="color: #fff; font-size: 20px; font-weight: 800; margin: 0;"><i class="fa-solid fa-chart-line" style="color: #4ade80; margin-right: 10px;"></i>Informasi Finansial</h3>
                    <div class="saldo-box">
                        <span class="saldo-label">Saldo Mengendap Saat Ini</span>
                        <span class="saldo-value">Rp {{ number_format($shop->balance ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div style="margin-top: 30px;">
                    <div style="border-bottom: 1px solid rgba(252, 211, 77, 0.2); padding-bottom: 10px; margin-bottom: 15px; display: flex; align-items: center; gap: 10px;">
                        <span class="label-emas" style="margin-bottom: 0;">Riwayat Penarikan Dana (Withdrawal)</span>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table-history">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Nominal (IDR)</th>
                                    <th style="text-align: right;">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($shop->withdrawals()->latest()->take(10)->get() as $wd)
                                <tr>
                                    <td data-label="Tanggal" style="color: #94a3b8;">{{ $wd->created_at->format('d/m/y, H:i') }}</td>
                                    <td data-label="Nominal" style="font-weight: 800; font-size: 14px; color: #4ade80;">Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                                    <td data-label="Status">
                                        <span class="badge-premium {{ $wd->status == 'success' ? 'badge-active' : 'badge-pending' }}">
                                            {{ strtoupper($wd->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" style="text-align: center; padding: 60px; color: #475569; background: transparent;">
                                        <i class="fa-solid fa-receipt" style="font-size: 40px; display: block; margin-bottom: 15px; opacity: 0.3;"></i>
                                        <span style="font-style: italic;">Belum terdeteksi aktivitas penarikan dana.</span>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection