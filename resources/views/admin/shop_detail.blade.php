@extends('layouts.app')

@section('content')
<style>
    /* Tipografi & Kontras Tinggi */
    .detail-container { animation: fadeIn 0.8s ease-out; }
    .back-link { color: #fcd34d; text-decoration: none; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; transition: 0.3s; }
    .back-link:hover { opacity: 0.7; transform: translateX(-5px); }

    .header-title { font-size: 32px; font-weight: 900; color: #ffffff; margin-top: 15px; }
    .header-subtitle { color: #94a3b8; font-size: 14px; margin-bottom: 40px; display: block; }

    /* Luxury Glass Cards */
    .card-detail {
        background: rgba(255, 255, 255, 0.03);
        backdrop-filter: blur(25px);
        -webkit-backdrop-filter: blur(25px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 40px;
        padding: 45px;
        box-shadow: 0 25px 50px rgba(0,0,0,0.3);
        height: 100%;
    }

    /* Container Logo Toko */
    .store-logo-wrapper {
        width: 120px;
        height: 120px;
        background: rgba(252, 211, 77, 0.05);
        border: 2px solid #fcd34d;
        border-radius: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 25px;
        overflow: hidden;
        box-shadow: 0 15px 30px rgba(252, 211, 77, 0.15);
    }

    .store-logo-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .store-icon-placeholder {
        font-size: 50px;
        color: #fcd34d;
    }

    /* Label Kontras (Kuning Emas Sesuai Gambar) */
    .label-emas {
        color: #fcd34d;
        font-size: 10px;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        margin-bottom: 8px;
        display: block;
    }
    .info-value { color: #ffffff; font-size: 15px; font-weight: 600; margin-bottom: 25px; display: block; }

    /* Financial Dashboard */
    .saldo-box { text-align: right; margin-bottom: 40px; }
    .saldo-label { color: #4ade80; font-size: 11px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; }
    .saldo-value { color: #4ade80; font-size: 48px; font-weight: 900; letter-spacing: -1px; }

    /* Table History Luxury */
    .table-history { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .table-history th { text-align: left; padding: 15px; color: #fcd34d; font-size: 10px; font-weight: 900; text-transform: uppercase; border-bottom: 1px solid rgba(255,255,255,0.1); }
    .table-history td { padding: 20px 15px; color: #ffffff; font-size: 13px; border-bottom: 1px solid rgba(255,255,255,0.05); }

    .badge-premium { padding: 5px 15px; border-radius: 50px; font-size: 9px; font-weight: 900; }
    .badge-active { background: rgba(34, 197, 94, 0.1); color: #4ade80; border: 1px solid rgba(34, 197, 94, 0.2); }
    .badge-pending { background: rgba(252, 211, 77, 0.1); color: #fcd34d; border: 1px solid rgba(252, 211, 77, 0.2); }

    @keyframes fadeIn { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="detail-container">
    <div class="container">
        <!-- Header -->
        <a href="{{ route('admin.shops') }}" class="back-link">
            <i class="fa-solid fa-chevron-left"></i> Kembali ke List Tenant
        </a>
        <h1 class="header-title">Profil Eksklusif Tenant</h1>
        <span class="header-subtitle">Detail Transparansi & Performa operasional {{ $shop->name }}</span>

        <div style="display: grid; grid-template-columns: 1fr 1.8fr; gap: 30px; align-items: start;">
            
            <!-- SISI KIRI: PROFIL & LOGO TOKO -->
            <div class="card-detail" style="text-align: center;">
                <div class="store-logo-wrapper">
                    <!-- LOGIKAN UNTUK MENAMPILKAN LOGO ASLI TOKO -->
                    @if($shop->logo)
                        <img src="{{ asset('storage/' . $shop->logo) }}" class="store-logo-img" alt="Logo {{ $shop->name }}">
                    @else
                        <i class="fa-solid fa-store store-icon-placeholder"></i>
                    @endif
                </div>
                
                <h2 style="color: #fff; font-size: 24px; font-weight: 800; margin-bottom: 10px;">{{ $shop->name }}</h2>
                <span class="badge-premium {{ $shop->status == 'active' ? 'badge-active' : 'badge-pending' }}" style="display: inline-block; margin-bottom: 40px;">
                    {{ strtoupper($shop->status == 'active' ? 'Aktif' : 'Non-Aktif') }}
                </span>

                <div style="text-align: left; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 30px;">
                    <span class="label-emas">Pemilik Utama</span>
                    <span class="info-value">{{ $shop->user->name ?? 'N/A' }}</span>

                    <span class="label-emas">Kontak Digital</span>
                    <span class="info-value">{{ $shop->user->email ?? '-' }}</span>

                    <span class="label-emas">Mulai Bergabung</span>
                    <span class="info-value">{{ $shop->created_at->format('d F Y') }}</span>
                </div>
            </div>

            <!-- SISI KANAN: FINANCIAL & HISTORY -->
            <div class="card-detail">
                <div style="display: flex; justify-content: space-between; align-items: start;">
                    <h3 style="color: #fff; font-size: 20px; font-weight: 800;">Informasi Finansial</h3>
                    <div class="saldo-box">
                        <span class="saldo-label">Saldo Saat Ini</span><br>
                        <span class="saldo-value">Rp {{ number_format($shop->balance ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <span class="label-emas" style="border-bottom: 1px solid rgba(252, 211, 77, 0.2); padding-bottom: 10px; margin-bottom: 20px;">Riwayat Penarikan Terbaru</span>
                    
                    <table class="table-history">
                        <thead>
                            <tr>
                                <th>Tanggal Transaksi</th>
                                <th>Nominal</th>
                                <th>Status Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shop->withdrawals as $wd)
                            <tr>
                                <td style="color: #94a3b8;">{{ $wd->created_at->format('d M Y, H:i') }}</td>
                                <td style="font-weight: 800; font-size: 14px;">Rp {{ number_format($wd->amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge-premium {{ $wd->status == 'success' ? 'badge-active' : 'badge-pending' }}">
                                        {{ strtoupper($wd->status) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" style="text-align: center; padding: 50px; color: #475569;">
                                    <i class="fa-solid fa-receipt" style="font-size: 30px; display: block; margin-bottom: 10px; opacity: 0.5;"></i>
                                    Belum ada aktivitas penarikan dana.
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
@endsection