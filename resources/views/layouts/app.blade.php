<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Eksekutif | Shelter Manahan</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <style>
        :root {
            --primary: #0284c7;
            --primary-soft: rgba(2, 132, 199, 0.08);
            --aksen-emas: #fcd34d;
            --bg-topbar: #f1f5f9;
            --teks-gelap: #1e293b;
            --teks-abu: #64748b;
            --merah-logout: #ef4444;
            --merah-hover: #b91c1c;
            --shadow-soft: 0 4px 20px rgba(0, 0, 0, .08);
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        html, body {
            width: 100%;
            overflow-x: hidden;
            scroll-behavior: smooth;
            background: #000;
        }

        /* =========================================
            PAGE CONTAINER & LOCKED BACKGROUND
        ========================================= */
        .page-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            /* TAMBAHKAN PADDING TOP SEBESAR TINGGI NAVBAR (85px) */
            padding-top: 85px; 
            background-image: 
                linear-gradient(to bottom, rgba(0, 0, 0, .85), rgba(0, 0, 0, .65)),
                url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2070&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-attachment: fixed; 
            background-repeat: no-no-repeat;
        }

        /* =========================================
            NAVBAR STYLE (DIATUR FIXED AGAR TIDAK KEGESER)
        ========================================= */
        .navbar {
            height: 85px;
            background: var(--bg-topbar);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 5%;
            /* UBAH KE FIXED AGAR TERKUNCI DI ATAS LAYAR */
            position: fixed; 
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: var(--shadow-soft);
        }

        .brand {
            text-decoration: none;
            font-size: 24px;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: 2px;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .brand span {
            color: var(--teks-gelap);
            font-weight: 400;
        }

        /* =========================================
            NAVIGATION LINKS
        ========================================= */
        .nav-links {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-item {
            text-decoration: none;
            color: var(--teks-abu);
            font-size: 13px;
            font-weight: 700;
            padding: 12px 20px;
            border-radius: 12px;
            letter-spacing: .5px;
            white-space: nowrap;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
        }

        .nav-item i {
            font-size: 15px;
        }

        .nav-item:hover,
        .nav-item.active {
            color: var(--primary);
            background: var(--primary-soft);
        }

        .cart-badge {
            background: var(--merah-logout);
            color: #fff;
            font-size: 10px;
            font-weight: 800;
            padding: 2px 6px;
            border-radius: 50px;
            margin-left: 2px;
            box-shadow: 0 2px 5px rgba(239, 68, 68, 0.4);
        }

        /* =========================================
            USER MENU & PROFILE
        ========================================= */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
            border-left: 2px solid #e2e8f0;
            padding-left: 20px;
            margin-left: 10px;
        }

        .user-info {
            text-align: right;
        }

        .user-name {
            font-size: 14px;
            font-weight: 700;
            color: var(--teks-gelap);
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .btn-logout {
            background: var(--merah-logout);
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: var(--transition);
            text-transform: uppercase;
        }

        .btn-logout:hover {
            background: var(--merah-hover);
            box-shadow: 0 4px 15px rgba(239, 68, 68, .35);
            transform: translateY(-1px);
        }

        /* =========================================
            MAIN CONTENT AREA (FLOATING CARD EFFECT)
        ========================================= */
        .main-content {
            flex: 1;
            padding: 40px 5%;
            width: 100%;
            max-width: 1600px;
            margin: 20px auto 40px; 
            animation: fadeIn 0.8s ease;
            
            background: rgba(255, 255, 255, 0.03); 
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border-radius: 40px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            min-height: calc(100vh - 165px);
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .menu-toggle {
            display: none;
            font-size: 24px;
            color: var(--primary);
            cursor: pointer;
            width: 45px;
            height: 45px;
            border-radius: 12px;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .menu-toggle:hover {
            background: var(--primary-soft);
        }

        /* =========================================
            RESPONSIVE DESIGN
        ========================================= */
        @media (max-width: 1150px) {
            .navbar { padding: 0 3%; }
            .nav-item { padding: 10px 14px; font-size: 12px; }
        }

        @media (max-width: 1024px) {
            .menu-toggle { display: flex; }
            .nav-links {
                position: fixed;
                top: 85px;
                left: 0;
                right: 0;
                background: #ffffff;
                box-shadow: 0 15px 35px rgba(0,0,0,.15);
                padding: 25px;
                display: flex;
                flex-direction: column;
                align-items: stretch;
                gap: 12px;
                opacity: 0;
                visibility: hidden;
                transform: translateY(-20px);
                transition: 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            }

            .nav-links.show {
                opacity: 1;
                visibility: visible;
                transform: translateY(0);
            }

            .nav-item {
                width: 100%;
                padding: 16px 20px;
                border-radius: 14px;
                font-size: 14px;
                background: #f8fafc;
            }

            .user-menu {
                border-left: none;
                border-top: 2px solid #f1f5f9;
                padding-left: 0;
                margin-left: 0;
                padding-top: 20px;
                margin-top: 10px;
                width: 100%;
                justify-content: space-between;
                flex-direction: row;
            }

            .btn-logout { width: auto; padding: 14px 25px; }
            .main-content { margin: 15px; border-radius: 25px; }
        }

        @media (max-width: 768px) {
            .navbar { height: 75px; }
            .nav-links { top: 75px; }
            /* KOMPENSASI PADDING UNTUK MOBILE NAVBAR */
            .page-container { padding-top: 75px; } 
            .brand { font-size: 19px; }
            .user-info { text-align: left; }
            .user-menu { flex-direction: column; align-items: flex-start; gap: 15px; }
            .btn-logout { width: 100%; justify-content: center; }
            .main-content { padding: 30px 15px; }
        }
    </style>
</head>
<body>
    <div class="page-container">
        <nav class="navbar">
            <a href="/" class="brand">
                SHELTER <span>MANAHAN</span>
            </a>
            <div class="nav-links" id="mobileMenu">
                @if(auth()->check())
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-pie"></i> RINGKASAN
                        </a>
                        <a href="{{ route('admin.users') }}" class="nav-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <i class="fa-solid fa-users-gear"></i> PENGGUNA
                        </a>
                        <a href="{{ route('admin.shops') }}" class="nav-item {{ request()->routeIs('admin.shops') ? 'active' : '' }}">
                            <i class="fa-solid fa-shop"></i> KONTROL TOKO
                        </a>
                    @elseif(auth()->user()->role === 'owner')
                        <a href="{{ route('owner.dashboard') }}" class="nav-item {{ request()->routeIs('owner.dashboard') ? 'active' : '' }}">
                            <i class="fa-solid fa-gauge-high"></i> DASHBOARD
                        </a>
                        <a href="{{ route('owner.produk') }}" class="nav-item {{ request()->routeIs('owner.produk') ? 'active' : '' }}">
                            <i class="fa-solid fa-bowl-food"></i> PRODUK SAYA
                        </a>
                        <a href="{{ route('owner.pesanan') }}" class="nav-item {{ request()->routeIs('owner.pesanan') ? 'active' : '' }}">
                            <i class="fa-solid fa-clipboard-list"></i> PESANAN
                        </a>
                        <a href="{{ route('owner.pengaturan') }}" class="nav-item {{ request()->routeIs('owner.pengaturan') ? 'active' : '' }}">
                            <i class="fa-solid fa-gears"></i> PENGATURAN
                        </a>
                    @elseif(auth()->user()->role === 'customer')
                        <a href="{{ url('/') }}" class="nav-item {{ request()->is('/') ? 'active' : '' }}">
                            <i class="fa-solid fa-utensils"></i> KATALOG
                        </a>
                        <a href="{{ route('cart.index') }}" class="nav-item {{ request()->routeIs('cart.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-shopping-cart"></i> KERANJANG 
                            <span class="cart-badge" id="cartCount">0</span>
                        </a>
                        <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-receipt"></i> PESANAN SAYA
                        </a>
                    @endif
                    <div class="user-menu">
                        <div class="user-info">
                            <span class="user-name">{{ auth()->user()->name }}</span>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" style="margin:0; display: flex;">
                            @csrf
                            <button type="submit" class="btn-logout">
                                <i class="fa-solid fa-power-off"></i> KELUAR
                            </button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('login') }}" class="nav-item">
                        <i class="fa-solid fa-right-to-bracket"></i> MASUK
                    </a>
                    <a href="{{ route('register') }}" class="nav-item">
                        <i class="fa-solid fa-user-plus"></i> DAFTAR
                    </a>
                @endif
            </div>
            <div class="menu-toggle" id="menuToggle">
                <i class="fa-solid fa-bars"></i>
            </div>
        </nav>

        <main class="main-content">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('menuToggle');
            const mobileMenu = document.getElementById('mobileMenu');
            const toggleIcon = toggleBtn.querySelector('i');

            toggleBtn.addEventListener('click', function () {
                mobileMenu.classList.toggle('show');
                if (mobileMenu.classList.contains('show')) {
                    toggleIcon.classList.replace('fa-bars', 'fa-xmark');
                } else {
                    toggleIcon.classList.replace('fa-xmark', 'fa-bars');
                }
            });

            document.querySelectorAll('.nav-item').forEach(item => {
                item.addEventListener('click', () => {
                    if (window.innerWidth <= 1024) {
                        mobileMenu.classList.remove('show');
                        toggleIcon.classList.replace('fa-xmark', 'fa-bars');
                    }
                });
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth > 1024) {
                    mobileMenu.classList.remove('show');
                    toggleIcon.classList.replace('fa-xmark', 'fa-bars');
                }
            });
        });
    </script>
</body>
</html>