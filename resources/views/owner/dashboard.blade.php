@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    *{ box-sizing:border-box; }
    body{ overflow-x:hidden; }

    /* =========================
       TYPOGRAPHY
    ========================== */
    .judul-halaman { 
        font-size: 36px; font-weight: 800; color: #ffffff; margin-bottom: 5px; letter-spacing: -1px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3); word-break: break-word;
    }
    
    .subjudul-halaman { 
        font-size: 13px; color: #fcd34d; letter-spacing: 3px; text-transform: uppercase; 
        font-weight: 700; margin-bottom: 30px; display: block; line-height: 1.6;
    }

    /* =========================
       MODERN TOGGLE SWITCH (SAKLAR GESER)
    ========================== */
    .status-bar {
        background: rgba(255, 255, 255, 0.05); border-radius: 25px; padding: 20px 35px;
        display: flex; justify-content: space-between; align-items: center;
        margin-bottom: 30px; border: 1px solid rgba(255,255,255,0.1);
        backdrop-filter: blur(15px);
    }

    .switch-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 8px;
    }

    .switch {
        position: relative; display: inline-block; width: 60px; height: 32px;
    }

    .switch input { opacity: 0; width: 0; height: 0; }

    .slider {
        position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0;
        background-color: rgba(255,255,255,0.1); transition: .4s; border-radius: 34px;
        border: 1px solid rgba(255,255,255,0.2);
    }

    .slider:before {
        position: absolute; content: ""; height: 24px; width: 24px; left: 4px; bottom: 3px;
        background-color: white; transition: .4s; border-radius: 50%;
        box-shadow: 0 2px 10px rgba(0,0,0,0.3);
    }

    input:checked + .slider { background-color: #22c55e; border-color: #4ade80; }
    input:checked + .slider:before { transform: translateX(26px); }

    .status-label { color: #fff; font-weight: 800; font-size: 14px; text-transform: uppercase; }
    .tiny-status { font-size: 10px; font-weight: 900; text-transform: uppercase; letter-spacing: 1px; }

    /* =========================
       GRID KPI
    ========================== */
    .grid-kpi {
        display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 25px;
    }

    /* =========================
       CARD MEWAH
    ========================== */
    .kartu-mewah {
        background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px); border-radius: 30px; padding: 35px;
        position: relative; box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); 
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275); overflow: hidden;
    }

    .kartu-mewah:hover { transform: translateY(-10px); border-color: #fcd34d; }
    .label-kpi { 
        font-size: 11px; font-weight: 800; color: #fcd34d; letter-spacing: 2px; 
        display: block; margin-bottom: 15px; text-transform: uppercase; line-height: 1.5;
    }

    .nilai-kpi { 
        font-size: 48px; font-weight: 900; color: #ffffff; line-height: 1.1; 
        display: flex; flex-wrap: wrap; align-items: baseline; gap: 10px; word-break: break-word;
    }
    .nilai-kpi span { font-size: 18px; color: #cbd5e1; font-weight: 600; }

    .indikator-jalur { height: 8px; width: 100%; background: rgba(255,255,255,0.1); border-radius: 20px; margin-top: 30px; overflow: hidden; }
    .indikator-isi { height: 100%; background: linear-gradient(90deg, #0284c7, #38bdf8); border-radius: 20px; }

    /* =========================
       BUTTON & BADGE
    ========================== */
    .btn-primer {
        background: #0284c7; color: white; text-decoration: none; padding: 18px 35px;
        border-radius: 18px; font-weight: 800; font-size: 15px; transition: 0.3s;
        border: none; display: inline-flex; align-items: center; justify-content: center;
        gap: 10px; box-shadow: 0 10px 20px rgba(2, 132, 199, 0.3); cursor: pointer;
    }

    .input-mewah { width: 100%; padding: 16px 20px; border-radius: 15px; border: 2px solid rgba(255,255,255,0.1); background: rgba(255,255,255,0.05); color: white; outline: none; margin-bottom: 20px; font-size: 15px; }
    .badge-populer { background: #fcd34d; color: #0f172a; padding: 4px 10px; border-radius: 8px; font-size: 10px; font-weight: 800; text-transform: uppercase; display:inline-block; }

    .aksi-cepat{ margin-top:50px; background: rgba(15, 23, 42, 0.6); padding:35px; border-radius:30px; display:flex; justify-content:space-between; align-items:center; gap:25px; border:1px solid rgba(255,255,255,0.1); flex-wrap:wrap; }

    @media (max-width: 768px){
        .judul-halaman{ font-size:26px; }
        .status-bar { flex-direction: column; gap: 20px; text-align: center; }
        .grid-kpi{ grid-template-columns:1fr; }
        .nilai-kpi { font-size: 34px; }
    }
</style>

{{-- Notifikasi --}}
@if(session('success'))
<div style="background: rgba(22, 163, 74, 0.2); backdrop-filter: blur(10px); color: #4ade80; padding: 20px; border-radius: 20px; margin-bottom: 30px; border: 1px solid rgba(74, 222, 128, 0.3);">
    <i class="fas fa-check-circle"></i> {{ session('success') }}
</div>
@endif

@if(!$shop)
    <div class="kartu-mewah" style="max-width:650px; margin:40px auto; text-align:center; background: rgba(15, 23, 42, 0.8);">
        <div style="font-size:50px; color:#fcd34d; margin-bottom:20px;"><i class="fas fa-store-alt"></i></div>
        <h2 style="font-weight:800; color:#ffffff; font-size:28px; margin-bottom:10px;">Buka Warung Digital</h2>
        <p style="color:#cbd5e1; margin-bottom:30px; line-height:1.6;">Lengkapi data berikut untuk menampilkan menu Anda ke pengunjung Shelter Manahan.</p>
        <form action="{{ route('owner.toko.store') }}" method="POST" enctype="multipart/form-data" style="text-align:left;">
            @csrf
            <label class="label-kpi">Nama Warung</label>
            <input type="text" name="name" class="input-mewah" placeholder="Contoh: Warmindo Penyet" required>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div>
                    <label class="label-kpi">No. WhatsApp</label>
                    <input type="number" name="whatsapp" class="input-mewah" placeholder="62812345xxx" required>
                </div>
                <div>
                    <label class="label-kpi">Username Instagram</label>
                    <input type="text" name="instagram" class="input-mewah" placeholder="username_toko">
                </div>
            </div>
            <label class="label-kpi">Logo / Foto Profil Warung</label>
            <input type="file" name="logo" class="input-mewah" accept="image/*">
            <button type="submit" class="btn-primer" style="width:100%;">Daftarkan Toko Sekarang</button>
        </form>
    </div>
@else

    <div class="judul-halaman">Dashboard {{ $shop->name }}</div>
    <span class="subjudul-halaman">Performa Warung Real-Time</span>

    {{-- SAKLAR STATUS WARUNG (MODERN TOGGLE) --}}
    <div class="status-bar">
        <div style="display:flex; align-items:center; gap:20px;">
            <i class="fas fa-store fa-2x" style="color: {{ $shop->is_active ? '#22c55e' : '#94a3b8' }};"></i>
            <div>
                <div class="status-label">Status Operasional</div>
                <div style="color: {{ $shop->is_active ? '#4ade80' : '#f87171' }}; font-weight: 800; font-size: 18px;">
                    {{ $shop->is_active ? 'WARUNG BUKA' : 'WARUNG TUTUP' }}
                </div>
            </div>
        </div>

        <form id="statusToggleForm" action="{{ route('owner.pengaturan.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="nama_toko" value="{{ $shop->name }}">
            <input type="hidden" name="username" value="{{ Auth::user()->name }}">
            <input type="hidden" name="email" value="{{ Auth::user()->email }}">
            <input type="hidden" name="is_active" value="{{ $shop->is_active ? 0 : 1 }}">
            
            <div class="switch-container">
                <label class="switch">
                    <input type="checkbox" {{ $shop->is_active ? 'checked' : '' }} onchange="document.getElementById('statusToggleForm').submit()">
                    <span class="slider"></span>
                </label>
                <span class="tiny-status" style="color: {{ $shop->is_active ? '#22c55e' : '#f87171' }}">
                    {{ $shop->is_active ? 'Buka' : 'Tutup' }}
                </span>
            </div>
        </form>
    </div>

    <div class="grid-kpi">
        <div class="kartu-mewah">
            <span class="label-kpi">Total Pendapatan (Lunas)</span>
            <div class="nilai-kpi">
                Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
            </div>
            <div class="indikator-jalur">
                <div class="indikator-isi" style="width:100%;"></div>
            </div>
            <div style="margin-top:15px; font-size:12px; color:#94a3b8;">
                <i class="fas fa-chart-line"></i> Akumulasi transaksi status success
            </div>
        </div>

        <div class="kartu-mewah" style="border-color: rgba(252, 211, 77, 0.4);">
            <span class="label-kpi">Menu Paling Laris</span>
            @if(isset($menuPopuler))
                <div style="color:white; font-weight:800; font-size:20px; margin-bottom:5px; word-break:break-word;">
                    {{ $menuPopuler->nama_produk }}
                </div>
                <div style="color:#fcd34d; font-size:14px; font-weight:700;">
                    {{ $menuPopuler->total_sold ?? 0 }} <span style="color:#cbd5e1; font-weight:400;">Porsi Terjual</span>
                </div>
            @else
                <div style="color:#64748b; font-style:italic; font-size:14px;">Belum ada pesanan lunas</div>
            @endif
            <div class="indikator-jalur">
                <div class="indikator-isi" style="width:100%; background:#fcd34d;"></div>
            </div>
            <div style="margin-top:15px;"><span class="badge-populer">BEST SELLER</span></div>
        </div>

        <div class="kartu-mewah">
            <span class="label-kpi">Total Transaksi</span>
            <div class="nilai-kpi" style="color:#38bdf8;">
                {{ $totalPesanan ?? 0 }}
                <span>Order</span>
            </div>
            <div class="indikator-jalur">
                <div class="indikator-isi" style="width:100%; background:#0284c7;"></div>
            </div>
            <div style="margin-top:15px; font-size:12px; color:#38bdf8;">
                <i class="fas fa-shopping-bag"></i> Jumlah pesanan masuk ke sistem
            </div>
        </div>
    </div>

    <div class="aksi-cepat">
        <div style="flex:1; min-width:250px;">
            <h3 style="color:#ffffff; margin-bottom:8px; font-weight:800; font-size:20px;">Ringkasan Toko</h3>
            <p style="color:#94a3b8; font-size:15px; line-height:1.6; margin:0;">
                @if($shop->is_active)
                    Warung Anda sedang <strong>Aktif</strong>. Pelanggan bisa melihat menu dan memesan.
                @else
                    Warung Anda sedang <strong>Tutup</strong>. Pelanggan tidak dapat memesan menu sementara waktu.
                @endif
            </p>
        </div>
        <div style="display:flex; gap:15px;">
            <a href="{{ route('owner.produk') }}" class="btn-primer">
                <i class="fas fa-boxes"></i> Kelola Menu
            </a>
        </div>
    </div>

    <div style="margin-top:50px; border-top:1px solid rgba(255,255,255,0.1); padding-top:25px; color:#64748b; font-size:13px; text-align:center; line-height:1.7;">
        SHELTER MANAHAN DIGITAL &bull; JAM OPERASIONAL: 
        <strong>{{ \Carbon\Carbon::parse($shop->open_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($shop->close_time)->format('H:i') }}</strong>
    </div>
@endif
@endsection