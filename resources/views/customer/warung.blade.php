<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu {{ $shop->nama_toko }} | Shelter Manahan</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary: #0284c7;
            --primary-soft: rgba(2, 132, 199, 0.08);
            --bg-topbar: #f1f5f9;
            --teks-gelap: #1e293b;
            --teks-abu: #64748b;
            --merah-logout: #ef4444;
            --white: #ffffff;
            --transition-smooth: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.4s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background: #000;
            min-height: 100vh;
            overflow-x: hidden;
        }

        .page-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-image: 
                linear-gradient(to bottom, rgba(0, 0, 0, .88), rgba(0, 0, 0, .70)),
                url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        /* NAVBAR */
        .navbar {
            height: 85px;
            background: var(--bg-topbar);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 4%;
            position: sticky;
            top: 0;
            z-index: 1001;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }

        .brand {
            text-decoration: none;
            font-size: 22px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .nav-links { display: flex; align-items: center; gap: 15px; }

        .nav-item {
            text-decoration: none;
            color: var(--teks-abu);
            font-size: 12px;
            font-weight: 700;
            padding: 10px 18px;
            border-radius: 12px;
            text-transform: uppercase;
            transition: 0.3s;
            display: flex; align-items: center; gap: 8px;
        }

        .nav-item:hover, .nav-item.active {
            color: var(--primary);
            background: var(--primary-soft);
        }

        .user-menu {
            display: flex; align-items: center; gap: 15px;
            border-left: 2px solid #e2e8f0; padding-left: 20px;
        }

        .btn-logout {
            background: var(--merah-logout); color: #fff; border: none;
            padding: 10px 18px; border-radius: 10px; font-size: 11px;
            font-weight: 800; cursor: pointer; display: flex; align-items: center; gap: 8px;
        }

        /* FLOATING BACK BUTTON */
        .btn-back {
            position: fixed; top: 105px; left: 30px; z-index: 999;
            padding: 12px 25px; border-radius: 50px; background: rgba(255, 255, 255, 0.95);
            color: var(--teks-gelap); font-size: 12px; font-weight: 800;
            border: 1px solid rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);
            transition: var(--transition-smooth); box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            display: flex; align-items: center; gap: 10px; text-transform: uppercase;
        }
        .btn-back:hover { background: var(--primary); color: #fff; transform: translateY(-5px); }

        /* SHOP HEADER */
        .shop-header { position: relative; padding: 60px 20px 40px; text-align: center; }
        .shop-logo {
            width: 150px; height: 150px; border-radius: 30px;
            margin: 0 auto 20px; overflow: hidden; background: #fff;
            border: 5px solid var(--white); box-shadow: 0 25px 50px rgba(0,0,0,0.5);
        }
        .shop-logo img { width: 100%; height: 100%; object-fit: cover; }
        .shop-name {
            font-size: 48px; font-weight: 800; color: #fff; text-transform: uppercase;
            letter-spacing: -1px; text-shadow: 0 10px 40px rgba(0,0,0,0.6); margin-bottom: 20px;
        }

        .shop-social-actions { display: flex; justify-content: center; gap: 15px; }
        .social-btn {
            padding: 14px 28px; border-radius: 15px; font-size: 12px;
            font-weight: 800; display: flex; align-items: center; gap: 10px;
            background: rgba(255, 255, 255, 0.15); color: #fff;
            backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.2);
            transition: 0.3s; text-transform: uppercase;
        }
        .social-btn:hover { background: var(--primary); transform: translateY(-3px); }

        /* =========================================
           REVISI CONTAINER (Sangat Lebar ke Samping)
        ========================================= */
        .container {
            width: 80%; /* Pakai persentase biar makin lebar */
            max-width: 1400px; /* Tingkatkan batas maksimalnya */
            margin: 0 auto;
            padding: 40px 0 100px;
        }

        .section-title {
            font-size: 32px; font-weight: 800; color: #fff; margin-bottom: 40px;
            display: flex; align-items: center; gap: 20px; padding: 0 2%;
        }
        .section-title::after {
            content: ''; height: 3px; flex: 1; background: linear-gradient(to right, var(--primary), transparent);
        }

        /* GRID 4 KOLOM */
        .grid-menu {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 35px; /* Jarak antar kotak lebih lega */
            align-items: start;
            padding: 0 2%;
        }

        /* CARD PRODUK (Bikin Persegi & Gede) */
        .card-menu {
            position: relative;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px; /* Sedikit lebih kotak */
            overflow: hidden;
            display: flex;
            flex-direction: column;
            border: 1px solid rgba(255,255,255,0.5);
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            transition: var(--transition-smooth);
            z-index: 1;
        }

        .card-menu:hover {
            transform: translateY(-15px);
            z-index: 10;
            box-shadow: 0 30px 60px rgba(0, 0, 0, 0.4);
            border-color: var(--primary);
        }

        /* TRIK PERSEGI SEMPURNA */
        .menu-img-wrapper {
            width: 100%;
            aspect-ratio: 1 / 1; /* WAJIB INI: Biar jadi KOTAK (Square) */
            overflow: hidden;
            background: #f1f5f9;
        }

        .menu-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.6s ease;
        }
        .card-menu:hover .menu-img { transform: scale(1.1); }

        .menu-body {
            padding: 25px;
            display: flex;
            flex-direction: column;
            flex: 1;
            text-align: center; /* Teks tengah biar makin premium */
        }

        .menu-title {
            font-size: 22px;
            font-weight: 800;
            color: var(--teks-gelap);
            margin-bottom: 8px;
            text-transform: capitalize;
        }

        .menu-price {
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            margin-bottom: 25px;
        }

        .btn-order {
            width: 100%; padding: 16px; border-radius: 15px;
            background: linear-gradient(135deg, #22c55e, #16a34a);
            color: #fff; font-size: 13px; font-weight: 800;
            display: flex; align-items: center; justify-content: center; gap: 10px;
            transition: 0.3s; text-transform: uppercase; border: none; cursor: pointer;
        }
        .btn-order:hover { filter: brightness(1.1); box-shadow: 0 10px 20px rgba(34, 197, 94, 0.4); }

        /* RESPONSIVE */
        @media (max-width: 1400px) { .grid-menu { grid-template-columns: repeat(3, 1fr); } }
        @media (max-width: 900px) { .grid-menu { grid-template-columns: repeat(2, 1fr); } }
        @media (max-width: 600px) {
            .grid-menu { grid-template-columns: 1fr; }
            .shop-name { font-size: 32px; }
            .container { width: 100%; padding: 20px 15px; }
        }
    </style>
</head>

<body>
<div class="page-container">

    <nav class="navbar">
        <a href="/" class="brand">SHELTER <span>MANAHAN</span></a>
        <div class="nav-links">
            @if(auth()->check())
                <a href="/" class="nav-item active"><i class="fa-solid fa-utensils"></i> KATALOG</a>
                <a href="{{ route('cart.index') }}" class="nav-item"><i class="fa-solid fa-shopping-cart"></i> KERANJANG</a>
                <div class="user-menu">
                    <span style="font-weight: 700; font-size: 14px; margin-right: 15px; color: var(--teks-gelap);">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-logout"><i class="fa-solid fa-power-off"></i> KELUAR</button>
                    </form>
                </div>
            @else
                <a href="{{ route('customer.login') }}" class="nav-item">MASUK</a>
            @endif
        </div>
    </nav>

    

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
        <h1 class="shop-name">{{ $shop->nama_toko }}</h1>
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
                                <i class="fas fa-utensils fa-4x"></i>
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
                            <a href="{{ route('customer.login') }}" class="btn-order">
                                <i class="fas fa-lock"></i> Login Untuk Pesan
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; color: #fff; padding: 100px;">
                    <i class="fas fa-utensils fa-5x" style="margin-bottom:20px; opacity: 0.3;"></i>
                    <p style="font-size: 20px;">Maaf, menu belum tersedia.</p>
                </div>
            @endforelse
        </div>
    </div>

    <footer style="text-align:center; padding:60px; color:#64748b; font-size:12px; border-top: 1px solid rgba(255,255,255,0.1);">
        &copy; {{ date('Y') }} <b>{{ $shop->nama_toko }}</b> &bull; Shelter Manahan Executive System
    </footer>
</div>
</body>
</html>