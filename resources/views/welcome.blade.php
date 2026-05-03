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
    background:linear-gradient(180deg,#f1f5f9,#e2e8f0);
    color:#111827;
    overflow-x:hidden;
}

/* HERO PREMIUM */
.hero{
    min-height:75vh;
    position:relative;
    background:
    linear-gradient(rgba(0,0,0,.75),rgba(0,0,0,.75)),
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
    animation:fadeUp 1s ease;
}

@keyframes fadeUp{
    from{opacity:0;transform:translateY(40px);}
    to{opacity:1;transform:translateY(0);}
}

.badge-top{
    display:inline-block;
    background:rgba(255,255,255,.12);
    color:#fff;
    padding:10px 20px;
    border-radius:50px;
    font-size:12px;
    font-weight:800;
    letter-spacing:2px;
    margin-bottom:20px;
    backdrop-filter:blur(12px);
}

.hero h1{
    font-size:clamp(36px,8vw,72px);
    color:#fff;
    font-weight:900;
    margin-bottom:18px;
    letter-spacing:-1px;
}

.hero p{
    color:rgba(255,255,255,.9);
    font-size:18px;
    line-height:1.8;
    max-width:700px;
    margin:auto;
}

/* MAP BOX PREMIUM */
.hero-box{
    background:rgba(255,255,255,0.9);
    backdrop-filter:blur(14px);
    max-width:1100px;
    margin:-70px auto 0;
    border-radius:26px;
    padding:20px;
    box-shadow:0 30px 80px rgba(0,0,0,.15);
    position:relative;
    z-index:5;
}

.map-box{
    overflow:hidden;
    border-radius:20px;
}

.map-box iframe{
    width:100%;
    height:300px;
    border:0;
}

/* CONTAINER */
.container{
    max-width:1300px;
    margin:auto;
    padding:70px 25px;
}

.section-title{
    font-size:36px;
    font-weight:900;
    margin-bottom:10px;
    text-align:center;
}

.section-sub{
    text-align:center;
    color:#64748b;
    margin-bottom:50px;
}

/* GRID */
.grid-warung{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(290px,1fr));
    gap:30px;
}

/* CARD PREMIUM */
.card-warung{
    background:rgba(255,255,255,0.95);
    border-radius:26px;
    padding:30px;
    text-decoration:none;
    color:inherit;
    transition:.4s ease;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
    border:1px solid rgba(255,255,255,0.5);
    position: relative;
    overflow: hidden;
    backdrop-filter:blur(10px);
    display: block; /* Memastikan element a berfungsi penuh */
}

.card-warung::before{
    content:"";
    position:absolute;
    inset:0;
    background:linear-gradient(120deg,transparent,rgba(255,255,255,.4),transparent);
    opacity:0;
    transition:.5s;
}

.card-warung:hover::before{
    opacity:1;
}

.card-warung:hover{
    transform:translateY(-10px) scale(1.02);
    box-shadow:0 30px 60px rgba(0,0,0,.15);
}

/* STATUS BADGE */
.status-tag {
    position: absolute;
    top: 18px;
    right: 18px;
    padding: 6px 12px;
    border-radius: 12px;
    font-size: 10px;
    font-weight: 800;
    text-transform: uppercase;
    display: flex;
    align-items: center;
    gap: 5px;
    z-index: 3;
}
.status-open { background: #ecfdf5; color: #16a34a; }
.status-closed { background: #fef2f2; color: #dc2626; }

.dot-ping {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    animation:ping 1.5s infinite;
}
@keyframes ping{
    0%{transform:scale(1);}
    50%{transform:scale(1.6);opacity:.5;}
    100%{transform:scale(1);}
}
.bg-open { background: #22c55e; }
.bg-closed { background: #ef4444; }

/* ICON */
.icon-box{
    width:95px;
    height:95px;
    border-radius:24px;
    background:#f8fafc;
    overflow:hidden;
    margin:auto auto 18px;
    display:flex;
    align-items:center;
    justify-content:center;
    border: 1px solid #e2e8f0;
}

.icon-box img{
    width:100%;
    height:100%;
    object-fit:cover;
}

/* TEXT */
.shop-name{
    text-align:center;
    font-size:22px;
    font-weight:800;
    margin-bottom:18px;
}

/* PILLS */
.produk-preview{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    justify-content:center;
    margin-bottom:22px;
}

.pill{
    background:#e0f2fe;
    color:#0369a1;
    padding:7px 12px;
    border-radius:50px;
    font-size:12px;
    font-weight:700;
    transition:.2s;
}
.pill:hover{
    transform:scale(1.05);
}

/* BUTTON */
.btn-lihat{
    background:linear-gradient(135deg,var(--primary),var(--primary2));
    color:#fff;
    text-align:center;
    padding:14px;
    border-radius:16px;
    font-size:14px;
    font-weight:800;
    transition:.3s;
}
.btn-lihat:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(0,123,255,.4);
}

/* FOOTER */
footer{
    background:#0f172a;
    color:#94a3b8;
    text-align:center;
    padding:50px 20px;
    margin-top:60px;
}

/* MOBILE */
@media(max-width:768px){
    .hero{ min-height:60vh; }
    .hero h1{ font-size:34px; }
    .hero-box{ margin:-30px 14px 0; }
    .container{ padding:40px 15px; }
    .grid-warung{ grid-template-columns:1fr; gap:18px; }
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

                // FIX: Logika URL Fallback jika slug NULL
                $targetUrl = $shop->slug ? route('customer.warung', $shop->slug) : route('customer.warung', $shop->id);
            @endphp

            <a href="{{ $targetUrl }}" class="card-warung">
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
                        <img src="{{ asset('storage/' . $shop->logo) }}" alt="{{ $shop->name }}">
                    @else
                        <i class="fas fa-store" style="font-size:35px;color:#0ea5e9;"></i>
                    @endif
                </div>

                <div class="shop-name">{{ $shop->name }}</div>

                <div class="produk-preview">
                    @php $count = 0; @endphp
                    {{-- Pastikan relasi products dipanggil dengan benar --}}
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