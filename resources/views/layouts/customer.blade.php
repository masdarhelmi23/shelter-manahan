<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Shelter Manahan</title>

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

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        body { background: #000; min-height: 100vh; overflow-x: hidden; }

        .page-container {
            min-height: 100vh; display: flex; flex-direction: column;
            background-image: linear-gradient(to bottom, rgba(0, 0, 0, .88), rgba(0, 0, 0, .70)),
                url('https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2070&auto=format&fit=crop');
            background-size: cover; background-position: center; background-attachment: fixed;
        }

        /* NAVBAR */
        .navbar {
            height: 85px; background: var(--bg-topbar);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%; position: sticky; top: 0; z-index: 1001;
            box-shadow: 0 4px 20px rgba(0,0,0,.08);
        }

        .brand {
            text-decoration: none; font-size: 20px; font-weight: 800;
            color: var(--primary); letter-spacing: 1.5px; text-transform: uppercase;
        }
        .brand span { color: var(--teks-gelap); font-weight: 400; }

        .nav-links { display: flex; align-items: center; gap: 15px; }
        .nav-item {
            text-decoration: none; color: var(--teks-abu); font-size: 12px;
            font-weight: 700; padding: 10px 15px; border-radius: 12px;
            text-transform: uppercase; transition: 0.3s;
            display: flex; align-items: center; gap: 8px;
        }
        .nav-item:hover, .nav-item.active { color: var(--primary); background: var(--primary-soft); }

        .user-menu { display: flex; align-items: center; gap: 15px; border-left: 2px solid #e2e8f0; padding-left: 20px; }
        .btn-logout {
            background: var(--merah-logout); color: #fff; border: none;
            padding: 10px 18px; border-radius: 10px; font-size: 11px;
            font-weight: 800; cursor: pointer; text-transform: uppercase;
        }

        /* Tombol Kembali Global */
        .btn-back-global {
            position: fixed; top: 105px; left: 25px; z-index: 999;
            padding: 12px 20px; border-radius: 50px; background: rgba(255, 255, 255, 0.95);
            color: var(--teks-gelap); font-size: 11px; font-weight: 800;
            border: 1px solid rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);
            transition: var(--transition-smooth); box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            display: flex; align-items: center; gap: 8px; text-decoration: none;
        }
        .btn-back-global:hover { background: var(--primary); color: #fff; transform: translateY(-5px); }

        @yield('extra-css')
    </style>
</head>
<body>
    <div class="page-container">
        <nav class="navbar">
            <a href="/" class="brand">SHELTER <span>MANAHAN</span></a>
            <div class="nav-links">
                @if(auth()->check())
                    <a href="/" class="nav-item {{ request()->is('/') ? 'active' : '' }}"><i class="fa-solid fa-utensils"></i> <span>Katalog</span></a>
                    <a href="{{ route('cart.index') }}" class="nav-item {{ request()->routeIs('cart.index') ? 'active' : '' }}"><i class="fa-solid fa-shopping-cart"></i> <span>Keranjang</span></a>
                    <div class="user-menu">
                        <span style="font-weight: 700; font-size: 13px; color: var(--teks-gelap);">{{ auth()->user()->name }}</span>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="btn-logout">KELUAR</button>
                        </form>
                    </div>
                @else
                    <a href="{{ route('customer.login') }}" class="nav-item">MASUK</a>
                @endif
            </div>
        </nav>

        @yield('content')

        <footer style="text-align:center; padding:60px; color:#64748b; font-size:11px; border-top: 1px solid rgba(255,255,255,0.1);">
            &copy; {{ date('Y') }} <b>Shelter Manahan</b> &bull; Executive System
        </footer>
    </div>
</body>
</html>