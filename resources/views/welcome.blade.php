@extends('layouts.customer')

@section('title', 'Shelter Manahan | Pusat Kuliner Solo')

@section('extra-css')
<style>
:root{
    --primary:#00a6ff;
    --primary2:#0077ff;
    --dark:#0f172a;
    --white:#ffffff;
    --text:#1e293b;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:'Plus Jakarta Sans',sans-serif;
}

body{
    background:#f1f5f9;
    color:#111827;
    overflow-x:hidden;
}

/* HERO */
.hero{
    min-height:72vh;
    position:relative;
    background:
    linear-gradient(rgba(0,0,0,.65),rgba(0,0,0,.70)),
    url('{{ asset("images/bg-shelter.jpg") }}');
    background-size:cover;
    background-position:center;
    display:flex;
    justify-content:center;
    align-items:center;
    text-align:center;
    padding:30px;
}

.hero-content{
    max-width:850px;
    z-index:2;
}

.badge-top{
    display:inline-block;
    background:rgba(255,255,255,.15);
    color:#fff;
    padding:10px 18px;
    border-radius:50px;
    font-size:12px;
    font-weight:800;
    letter-spacing:2px;
    margin-bottom:20px;
    backdrop-filter:blur(10px);
}

.hero h1{
    font-size:clamp(34px,8vw,68px);
    color:#fff;
    font-weight:800;
    line-height:1.1;
    margin-bottom:18px;
}

.hero p{
    color:rgba(255,255,255,.9);
    font-size:18px;
    line-height:1.8;
    max-width:700px;
    margin:auto;
}

/* SEARCH BOX / MAP BOX */
.hero-box{
    background:#fff;
    max-width:1100px;
    margin:-60px auto 0;
    border-radius:24px;
    padding:20px;
    box-shadow:0 25px 60px rgba(0,0,0,.15);
    position:relative;
    z-index:5;
}

.map-box{
    overflow:hidden;
    border-radius:18px;
}

.map-box iframe{
    width:100%;
    height:280px;
    border:0;
}

/* CONTAINER */
.container{
    max-width:1300px;
    margin:auto;
    padding:60px 25px;
}

.section-title{
    font-size:34px;
    font-weight:800;
    margin-bottom:10px;
    text-align:center;
    color:#0f172a;
}

.section-sub{
    text-align:center;
    color:#64748b;
    margin-bottom:45px;
}

/* GRID */
.grid-warung{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(290px,1fr));
    gap:28px;
}

/* CARD REVISED FOR STATUS BADGE */
.card-warung{
    background:#fff;
    border-radius:24px;
    padding:30px;
    text-decoration:none;
    color:inherit;
    transition:.35s ease;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
    border:1px solid #e5e7eb;
    position: relative; /* Wajib untuk posisi badge */
    overflow: hidden;
}

.card-warung:hover{
    transform:translateY(-8px);
    box-shadow:0 25px 45px rgba(0,0,0,.12);
}

/* BADGE STATUS STYLE */
.status-tag {
    position: absolute;
    top: 20px;
    right: 20px;
    padding: 6px 12px;
    border-radius: 10px;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    display: flex;
    align-items: center;
    gap: 5px;
}
.status-open { background: #f0fdf4; color: #16a34a; border: 1px solid #dcfce7; }
.status-closed { background: #fef2f2; color: #dc2626; border: 1px solid #fee2e2; }
.dot-ping { width: 6px; height: 6px; border-radius: 50%; }
.bg-open { background: #22c55e; box-shadow: 0 0 8px #22c55e; }
.bg-closed { background: #ef4444; }

.icon-box{
    width:95px;
    height:95px;
    border-radius:22px;
    background:#f8fafc;
    overflow:hidden;
    margin:auto auto 18px;
    display:flex;
    align-items:center;
    justify-content:center;
    border:1px solid #e2e8f0;
}

.icon-box img{
    width:100%;
    height:100%;
    object-fit:cover;
}

.shop-name{
    text-align:center;
    font-size:22px;
    font-weight:800;
    margin-bottom:18px;
    color:#0f172a;
}

.produk-preview{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    justify-content:center;
    margin-bottom:22px;
}

.pill{
    background:#eff6ff;
    color:#2563eb;
    padding:7px 12px;
    border-radius:50px;
    font-size:12px;
    font-weight:700;
}

.btn-lihat{
    background:linear-gradient(135deg,var(--primary),var(--primary2));
    color:#fff;
    text-align:center;
    padding:14px;
    border-radius:14px;
    font-size:14px;
    font-weight:800;
}

/* FOOTER */
footer{
    background:#0f172a;
    color:#94a3b8;
    text-align:center;
    padding:40px 20px;
    margin-top:50px;
}

/* ================= MOBILE RESPONSIVE ================= */
@media(max-width:768px){
    .hero{ min-height:58vh; padding:20px 15px; }
    .hero h1{ font-size:34px; }
    .hero-box{ margin:-28px 14px 0; padding:12px; }
    .container{ padding:35px 14px; }
    .section-title{ font-size:26px; }
    .grid-warung{ grid-template-columns:1fr; gap:16px; }
}
</style>
@endsection

@section('content')

<!-- HERO -->
<section class="hero">
    <div class="hero-content">
        <div class="badge-top">THE HEART OF SOLO CULINARY</div>
        <h1>SHELTER MANAHAN</h1>
        <p>Nikmati ratusan menu pilihan dari pedagang terbaik dalam satu sistem modern, cepat, dan terintegrasi.</p>
    </div>
</section>

<!-- MAP BOX -->
<div class="hero-box">
    <div class="map-box">
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.1900021619495!2d110.80080787411676!3d-7.554250474589366!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a1510d7482e41%3A0x33ad1be7b190dca!2sShelter%20MANAHAN!5e0!3m2!1sid!2sid!4v1776832303823!5m2!1sid!2sid" allowfullscreen="" loading="lazy"></iframe>
    </div>
</div>

<!-- CONTENT -->
<div class="container">
    <h2 class="section-title">Eksplorasi Warung Pilihan</h2>
    <p class="section-sub">Temukan makanan favoritmu dari tenant terbaik Shelter Manahan</p>

    <div class="grid-warung">
        @forelse($shops as $shop)
            @php
                // Logika Status Buka/Tutup
                $sekarang = \Carbon\Carbon::now();
                $jamBuka = \Carbon\Carbon::parse($shop->open_time);
                $jamTutup = \Carbon\Carbon::parse($shop->close_time);
                $isJamOperasional = $sekarang->between($jamBuka, $jamTutup);
                $sedangLibur = $shop->closed_until && $sekarang->lessThanOrEqualTo(\Carbon\Carbon::parse($shop->closed_until));
                $isBuka = $isJamOperasional && !$sedangLibur;
            @endphp

            <a href="{{ $shop->slug ? route('customer.warung', $shop->slug) : '#' }}" class="card-warung">
                <!-- BADGE STATUS -->
                @if($isBuka)
                    <div class="status-tag status-open">
                        <div class="dot-ping bg-open"></div> BUKA
                    </div>
                @else
                    <div class="status-tag status-closed">
                        <div class="dot-ping bg-closed"></div> TUTUP
                    </div>
                @endif

                <div class="icon-box">
                    @if($shop->logo)
                        <img src="{{ asset('storage/' . $shop->logo) }}">
                    @else
                        <i class="fas fa-store" style="font-size:35px;color:#0ea5e9;"></i>
                    @endif
                </div>

                <div class="shop-name">{{ $shop->name }}</div>

                <div class="produk-preview">
                    @php $count = 0; @endphp
                    @forelse($shop->products->where('status','aktif') as $prod)
                        @if($count < 4)
                            <span class="pill">{{ $prod->nama_produk }}</span>
                            @php $count++; @endphp
                        @endif
                    @empty
                        <span class="pill">Belum ada menu</span>
                    @endforelse
                </div>

                <div class="btn-lihat">
                    LIHAT SEMUA MENU <i class="fas fa-arrow-right" style="margin-left:8px;"></i>
                </div>
            </a>
        @empty
            <div style="grid-column:1/-1;text-align:center;padding:80px 0;color:#64748b;">
                <i class="fas fa-store-slash fa-4x" style="margin-bottom:20px;"></i>
                <p>Belum ada warung tersedia.</p>
            </div>
        @endforelse
    </div>
</div>

<footer>
    © {{ date('Y') }} Shelter Manahan. All rights reserved. <br><br>
    <a href="{{ route('login') }}" style="color:#38bdf8;text-decoration:none;font-weight:700;">Managed by Surakarta Digital Team</a>
</footer>

@endsection