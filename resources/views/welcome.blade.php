<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shelter Manahan | Pusat Kuliner Solo</title>

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

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

/* CARD */
.card-warung{
    background:#fff;
    border-radius:24px;
    padding:30px;
    text-decoration:none;
    color:inherit;
    transition:.35s ease;
    box-shadow:0 10px 25px rgba(0,0,0,.06);
    border:1px solid #e5e7eb;
}

.card-warung:hover{
    transform:translateY(-8px);
    box-shadow:0 25px 45px rgba(0,0,0,.12);
}

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

/* MOBILE */
@media(max-width:768px){

.hero{
    min-height:62vh;
}

.hero-box{
    margin-top:-35px;
    padding:14px;
}

.map-box iframe{
    height:220px;
}

.section-title{
    font-size:28px;
}

}
</style>
</head>
<body>

<!-- HERO -->
<section class="hero">
<div class="hero-content">

<div class="badge-top">
THE HEART OF SOLO CULINARY
</div>

<h1>SHELTER MANAHAN</h1>

<p>
Nikmati ratusan menu pilihan dari pedagang terbaik dalam satu sistem modern, cepat, dan terintegrasi.
</p>

</div>
</section>

<!-- MAP BOX -->
<div class="hero-box">
<div class="map-box">
<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3955.1900021619495!2d110.80080787411676!3d-7.554250474589366!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e7a1510d7482e41%3A0x33ad1be7b190dca!2sShelter%20MANAHAN!5e0!3m2!1sid!2sid!4v1776832303823!5m2!1sid!2sid"
allowfullscreen=""
loading="lazy"></iframe>
</div>
</div>

<!-- CONTENT -->
<div class="container">

<h2 class="section-title">Eksplorasi Warung Pilihan</h2>
<p class="section-sub">Temukan makanan favoritmu dari tenant terbaik Shelter Manahan</p>

<div class="grid-warung">

@forelse($shops as $shop)

<a href="{{ $shop->slug ? route('customer.warung', $shop->slug) : '#' }}" class="card-warung">

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
LIHAT SEMUA MENU
<i class="fas fa-arrow-right" style="margin-left:8px;"></i>
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

<a href="{{ route('login') }}" style="color:#38bdf8;text-decoration:none;font-weight:700;">
Managed by Surakarta Digital Team
</a>
</footer>

</body>
</html>