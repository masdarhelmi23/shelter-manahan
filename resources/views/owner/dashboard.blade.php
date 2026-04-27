@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

<style>
    *{
        box-sizing:border-box;
    }

    body{
        overflow-x:hidden;
    }

    /* =========================
       TYPOGRAPHY
    ========================== */
    .judul-halaman { 
        font-size: 36px; 
        font-weight: 800; 
        color: #ffffff; 
        margin-bottom: 5px; 
        letter-spacing: -1px;
        text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        word-break: break-word;
    }
    
    .subjudul-halaman { 
        font-size: 13px; 
        color: #fcd34d; 
        letter-spacing: 3px; 
        text-transform: uppercase; 
        font-weight: 700;
        margin-bottom: 40px; 
        display: block;
        line-height: 1.6;
    }

    /* =========================
       GRID KPI
    ========================== */
    .grid-kpi {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 25px;
    }

    /* =========================
       CARD
    ========================== */
    .kartu-mewah {
        background: rgba(255, 255, 255, 0.1); 
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border-radius: 30px;
        padding: 35px;
        position: relative;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2); 
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        overflow: hidden;
    }

    .kartu-mewah:hover {
        transform: translateY(-10px);
        border-color: #fcd34d;
    }

    .label-kpi { 
        font-size: 11px; 
        font-weight: 800; 
        color: #fcd34d; 
        letter-spacing: 2px; 
        display: block; 
        margin-bottom: 15px; 
        text-transform: uppercase;
        line-height: 1.5;
    }

    .nilai-kpi { 
        font-size: 52px; 
        font-weight: 900; 
        color: #ffffff; 
        line-height: 1.1; 
        display: flex;
        flex-wrap: wrap;
        align-items: baseline;
        gap: 10px;
        word-break: break-word;
    }

    .nilai-kpi span { 
        font-size: 18px; 
        color: #cbd5e1; 
        font-weight: 600; 
    }

    .indikator-jalur { 
        height: 8px; 
        width: 100%; 
        background: rgba(255,255,255,0.1); 
        border-radius: 20px; 
        margin-top: 30px; 
        overflow: hidden;
    }
    
    .indikator-isi { 
        height: 100%; 
        background: linear-gradient(90deg, #0284c7, #38bdf8); 
        border-radius: 20px; 
    }

    /* =========================
       BUTTON
    ========================== */
    .btn-primer {
        background: #0284c7;
        color: white;
        text-decoration: none;
        padding: 18px 35px;
        border-radius: 18px;
        font-weight: 800;
        font-size: 15px;
        transition: 0.3s;
        border: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 10px 20px rgba(2, 132, 199, 0.3);
        cursor: pointer;
        text-align: center;
        width: 100%;
    }

    .btn-primer:hover {
        background: #0ea5e9;
        transform: scale(1.03);
        color:white;
    }

    /* =========================
       INPUT
    ========================== */
    .input-mewah {
        width: 100%;
        padding: 16px 20px;
        border-radius: 15px;
        border: 2px solid rgba(255,255,255,0.1);
        background: rgba(255,255,255,0.05);
        color: white;
        outline: none;
        margin-bottom: 20px;
        font-size: 15px;
    }

    .input-mewah:focus { 
        border-color: #0284c7; 
        background: rgba(255,255,255,0.1); 
    }

    .badge-populer {
        background: #fcd34d;
        color: #0f172a;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 10px;
        font-weight: 800;
        text-transform: uppercase;
        display:inline-block;
    }

    /* =========================
       FORM GRID
    ========================== */
    .form-grid-2{
        display:grid;
        grid-template-columns:1fr 1fr;
        gap:20px;
    }

    /* =========================
       ACTION SECTION
    ========================== */
    .aksi-cepat{
        margin-top:50px;
        background: rgba(15, 23, 42, 0.6);
        padding:35px;
        border-radius:30px;
        display:flex;
        justify-content:space-between;
        align-items:center;
        gap:25px;
        border:1px solid rgba(255,255,255,0.1);
        flex-wrap:wrap;
    }

    /* =========================
       MOBILE RESPONSIVE
    ========================== */
    @media (max-width: 992px){
        .judul-halaman{
            font-size:30px;
        }

        .nilai-kpi{
            font-size:42px;
        }
    }

    @media (max-width: 768px){

        .judul-halaman{
            font-size:26px;
            line-height:1.3;
        }

        .subjudul-halaman{
            font-size:11px;
            letter-spacing:2px;
            margin-bottom:25px;
        }

        .grid-kpi{
            grid-template-columns:1fr;
            gap:18px;
        }

        .kartu-mewah{
            padding:24px;
            border-radius:24px;
        }

        .nilai-kpi{
            font-size:34px;
        }

        .nilai-kpi span{
            font-size:15px;
        }

        .form-grid-2{
            grid-template-columns:1fr;
            gap:0;
        }

        .aksi-cepat{
            flex-direction:column;
            align-items:stretch;
            padding:25px;
        }

        .btn-primer{
            padding:16px 20px;
            font-size:14px;
        }
    }

    @media (max-width: 480px){

        .judul-halaman{
            font-size:22px;
        }

        .kartu-mewah{
            padding:18px;
            border-radius:20px;
        }

        .nilai-kpi{
            font-size:28px;
        }

        .label-kpi{
            font-size:10px;
            letter-spacing:1px;
        }

        .subjudul-halaman{
            letter-spacing:1px;
        }

        .input-mewah{
            padding:14px 16px;
            font-size:14px;
        }

        .btn-primer{
            font-size:13px;
            border-radius:14px;
        }
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
    
    <div style="font-size:50px; color:#fcd34d; margin-bottom:20px;">
        <i class="fas fa-store-alt"></i>
    </div>

    <h2 style="font-weight:800; color:#ffffff; font-size:28px; margin-bottom:10px;">
        Buka Warung Digital
    </h2>

    <p style="color:#cbd5e1; margin-bottom:30px; line-height:1.6;">
        Lengkapi data berikut untuk menampilkan menu Anda ke pengunjung Shelter Manahan.
    </p>

    <form action="{{ route('owner.toko.store') }}" method="POST" enctype="multipart/form-data" style="text-align:left;">
        @csrf

        <label class="label-kpi">Nama Warung</label>
        <input type="text" name="name" class="input-mewah" placeholder="Contoh: Warmindo Penyet" required>

        <div class="form-grid-2">

            <div>
                <label class="label-kpi">No. WhatsApp (Gunakan 62)</label>
                <input type="number" name="whatsapp" class="input-mewah" placeholder="62812345xxx" required>
            </div>

            <div>
                <label class="label-kpi">Username Instagram</label>
                <input type="text" name="instagram" class="input-mewah" placeholder="username_toko">
            </div>

        </div>

        <label class="label-kpi">Logo / Foto Profil Warung</label>
        <input type="file" name="logo" class="input-mewah" accept="image/*">

        <button type="submit" class="btn-primer">
            Daftarkan Toko Sekarang
        </button>
    </form>
</div>

@else

<div class="judul-halaman">Warung {{ $shop->name }}</div>
<span class="subjudul-halaman">Analisis Performa Shelter Manahan</span>

<div class="grid-kpi">

    {{-- KPI 1 --}}
    <div class="kartu-mewah">
        <span class="label-kpi">Kunjungan Katalog</span>

        <div class="nilai-kpi">
            {{ number_format($kunjungan ?? 0) }}
            <span>Scan</span>
        </div>

        <div class="indikator-jalur">
            <div class="indikator-isi" style="width:100%;"></div>
        </div>

        <div style="margin-top:15px; font-size:12px; color:#94a3b8;">
            <i class="fas fa-eye"></i> Total pengunjung scan QR Warung
        </div>
    </div>

    {{-- KPI 2 --}}
    <div class="kartu-mewah" style="border-color: rgba(252, 211, 77, 0.4);">
        <span class="label-kpi">Popularitas Menu</span>

        @if(isset($menuPopuler))
            <div style="color:white; font-weight:800; font-size:20px; margin-bottom:5px; word-break:break-word;">
                {{ $menuPopuler->nama_produk }}
            </div>

            <div style="color:#fcd34d; font-size:14px; font-weight:700;">
                {{ $menuPopuler->clicks ?? 0 }}
                <span style="color:#cbd5e1; font-weight:400;">Peminat</span>
            </div>
        @else
            <div style="color:#64748b; font-style:italic; font-size:14px;">
                Belum ada data menu
            </div>
        @endif

        <div class="indikator-jalur">
            <div class="indikator-isi" style="width:100%; background:#fcd34d;"></div>
        </div>

        <div style="margin-top:15px;">
            <span class="badge-populer">BEST SELLER</span>
        </div>
    </div>

    {{-- KPI 3 --}}
    <div class="kartu-mewah">
        <span class="label-kpi">Klik Order WA</span>

        <div class="nilai-kpi" style="color:#4ade80;">
            {{ $totalOrderWA ?? 0 }}
            <span>Chat</span>
        </div>

        <div class="indikator-jalur">
            <div class="indikator-isi" style="width:100%; background:#22c55e;"></div>
        </div>

        <div style="margin-top:15px; font-size:12px; color:#4ade80;">
            <i class="fab fa-whatsapp"></i> Minat beli via sistem digital
        </div>
    </div>

</div>

<div class="aksi-cepat">

    <div style="flex:1; min-width:250px;">
        <h3 style="color:#ffffff; margin-bottom:8px; font-weight:800; font-size:20px;">
            Insight Operasional
        </h3>

        <p style="color:#94a3b8; font-size:15px; line-height:1.6; margin:0;">
            @if(isset($menuPopuler) && ($menuPopuler->clicks ?? 0) > 0)
                Menu <strong>{{ $menuPopuler->nama_produk }}</strong> sedang tren! Pastikan stok bahan selalu siap di jam sibuk Shelter.
            @else
                Ayo mulai kelola produk Anda untuk melihat menu mana yang paling disukai pembeli.
            @endif
        </p>
    </div>

    <a href="{{ route('owner.produk') }}" class="btn-primer" style="width:auto;">
        <i class="fas fa-boxes"></i> Kelola Katalog Menu
    </a>

</div>

<div style="margin-top:50px; border-top:1px solid rgba(255,255,255,0.1); padding-top:25px; color:#64748b; font-size:13px; text-align:center; line-height:1.7;">
    SHELTER MANAHAN DIGITAL DASHBOARD &bull; TERHUBUNG DENGAN WHATSAPP:
    <strong>{{ $shop->whatsapp }}</strong>
</div>

@endif
@endsection