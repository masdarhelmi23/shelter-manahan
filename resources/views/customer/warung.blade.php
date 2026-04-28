@extends('layouts.customer')

@section('title', 'Menu ' . $shop->name)

@section('extra-css')
<style>
    /* =========================================
       CSS KHUSUS HALAMAN WARUNG (REVISED)
    ========================================= */
    .shop-header { 
        position: relative; 
        padding: 60px 20px 40px; 
        text-align: center; /* Memastikan semua konten di header ke tengah */
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .shop-logo {
        width: 140px; 
        height: 140px; 
        border-radius: 35px;
        margin: 0 auto 25px; 
        overflow: hidden; 
        background: #fff;
        border: 4px solid var(--white); 
        box-shadow: 0 25px 50px rgba(0,0,0,0.5);
    }
    .shop-logo img { width: 100%; height: 100%; object-fit: cover; }

    /* NAMA TOKO: Dibuat Full Center */
    .shop-name {
        font-size: 42px; 
        font-weight: 800; 
        color: #fff; 
        text-transform: uppercase;
        letter-spacing: -1px; 
        text-shadow: 0 10px 30px rgba(0,0,0,0.6); 
        margin-bottom: 25px;
        text-align: center;
        width: 100%;
        line-height: 1.2;
    }

    .shop-social-actions { 
        display: flex; 
        justify-content: center; 
        gap: 15px; 
        flex-wrap: wrap;
    }

    .social-btn {
        padding: 14px 28px; 
        border-radius: 12px; 
        font-size: 12px;
        font-weight: 800; 
        display: flex; 
        align-items: center; 
        gap: 10px;
        background: rgba(255, 255, 255, 0.15); 
        color: #fff;
        backdrop-filter: blur(10px); 
        border: 1px solid rgba(255, 255, 255, 0.2);
        transition: 0.3s; 
        text-transform: uppercase; 
        text-decoration: none;
    }
    .social-btn:hover { 
        background: var(--primary); 
        transform: translateY(-3px); 
        box-shadow: 0 10px 20px rgba(2, 132, 199, 0.3);
    }

    /* GRID MENU & CONTAINER */
    .container { width: 90%; max-width: 1400px; margin: 0 auto; padding: 40px 0 100px; }
    .section-title { 
        font-size: 26px; 
        font-weight: 800; 
        color: #fff; 
        margin-bottom: 40px; 
        display: flex; 
        align-items: center; 
        gap: 20px; 
    }
    .section-title::after { content: ''; height: 2px; flex: 1; background: linear-gradient(to right, var(--primary), transparent); }

    .grid-menu { display: grid; grid-template-columns: repeat(4, 1fr); gap: 25px; align-items: start; }
    
    .card-menu {
        position: relative; 
        background: rgba(255, 255, 255, 0.98);
        border-radius: 18px; 
        overflow: hidden; 
        display: flex; 
        flex-direction: column;
        border: 1px solid rgba(255,255,255,0.5); 
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        transition: var(--transition-smooth); 
        z-index: 1;
    }
    .card-menu:hover { 
        transform: translateY(-12px); 
        z-index: 10; 
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3); 
        border-color: var(--primary); 
    }

    .menu-img-wrapper { 
        width: 100%; 
        aspect-ratio: 1 / 1; 
        overflow: hidden; 
        background: #f1f5f9; 
    }
    .menu-img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.6s ease; }
    .card-menu:hover .menu-img { transform: scale(1.1); }

    .menu-body { padding: 22px; display: flex; flex-direction: column; flex: 1; text-align: center; }
    .menu-title { font-size: 18px; font-weight: 800; color: var(--teks-gelap); margin-bottom: 6px; }
    .menu-price { font-size: 20px; font-weight: 800; color: var(--primary); margin-bottom: 20px; }
    
    .btn-order {
        width: 100%; 
        padding: 14px; 
        border-radius: 12px; 
        background: linear-gradient(135deg, #22c55e, #16a34a);
        color: #fff; 
        font-size: 12px; 
        font-weight: 800; 
        display: flex; 
        align-items: center; 
        justify-content: center; 
        gap: 10px;
        transition: 0.3s; 
        text-transform: uppercase; 
        border: none; 
        cursor: pointer; 
        text-decoration: none;
    }
    .btn-order:hover { filter: brightness(1.1); box-shadow: 0 10px 20px rgba(34, 197, 94, 0.3); }

    @media (max-width: 1024px) { .grid-menu { grid-template-columns: repeat(3, 1fr); } }
    @media (max-width: 768px) { 
        .grid-menu { grid-template-columns: repeat(2, 1fr); gap: 15px; } 
        .shop-name { font-size: 30px; }
        .social-btn { padding: 10px 18px; font-size: 10px; }
    }
</style>
@endsection

@section('content')
    <a href="{{ route('welcome') }}" class="btn-back-global">
        <i class="fas fa-chevron-left"></i> Kembali
    </a>

    <header class="shop-header">
        <div class="shop-logo">
            @if($shop->logo)
                <img src="{{ asset('storage/' . $shop->logo) }}" alt="Logo">
            @else
                <div style="height:100%; display:flex; align-items:center; justify-content:center; background:#f8fafc;">
                    <i class="fas fa-store fa-4x" style="color:#cbd5e1;"></i>
                </div>
            @endif
        </div>

        {{-- NAMA TOKO CENTER --}}
        <h1 class="shop-name">{{ $shop->name }}</h1>

        <div class="shop-social-actions">
            @if($shop->whatsapp)
                <a href="https://wa.me/{{ $shop->whatsapp }}" class="social-btn" target="_blank">
                    <i class="fa-brands fa-whatsapp fa-xl"></i> WhatsApp
                </a>
            @endif
            @if($shop->instagram)
                <a href="https://instagram.com/{{ $shop->instagram }}" class="social-btn" target="_blank">
                    <i class="fa-brands fa-instagram fa-xl"></i> Instagram
                </a>
            @endif
        </div>
    </header>

    <div class="container">
        <h2 class="section-title">Menu Andalan Kami</h2>
        <div class="grid-menu">
            @forelse($products as $product)
                <div class="card-menu">
                    <div class="menu-img-wrapper">
                        @if($product->foto)
                            <img src="{{ asset('storage/' . $product->foto) }}" class="menu-img" alt="{{ $product->nama_produk }}">
                        @else
                            <div style="height:100%; background:#f1f5f9; display:flex; align-items:center; justify-content:center; color:#94a3b8;">
                                <i class="fas fa-utensils fa-3x"></i>
                            </div>
                        @endif
                    </div>
                    <div class="menu-body">
                        <h3 class="menu-title">{{ $product->nama_produk }}</h3>
                        <div class="menu-price">Rp {{ number_format($product->harga, 0, ',', '.') }}</div>
                        
                        @if(Auth::check())
                            <a href="{{ route('checkout', $product->id) }}" class="btn-order">
                                <i class="fas fa-shopping-basket"></i> Pesan & Bayar
                            </a>
                        @else
                            <a href="{{ route('customer.login') }}" class="btn-order" style="background: #64748b;">
                                <i class="fas fa-lock"></i> Login Untuk Pesan
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; color: #fff; padding: 50px;">
                    <i class="fas fa-info-circle fa-2x" style="opacity: 0.5;"></i>
                    <p style="margin-top: 10px;">Menu belum tersedia di warung ini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection