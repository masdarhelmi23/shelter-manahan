<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') | Shelter Manahan</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #0284c7;
            --primary-soft: rgba(2, 132, 199, 0.08);
            --bg-navbar: #ffffff;
            --teks-gelap: #1e293b;
            --teks-abu: #64748b;
            --merah-logout: #ef4444;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }
        
        body { background: #f1f5f9; min-height: 100vh; }

        /* NAVBAR MEWAH */
        .navbar {
            height: 80px; background: var(--bg-navbar);
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 5%; position: sticky; top: 0; z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, .05);
        }

        .brand { text-decoration: none; font-size: 20px; font-weight: 800; color: var(--primary); letter-spacing: 1.2px; text-transform: uppercase; }
        .brand span { color: var(--teks-gelap); font-weight: 400; }
        
        .nav-links { display: flex; align-items: center; gap: 10px; }
        .nav-item { 
            text-decoration: none; color: var(--teks-abu); font-size: 11px; font-weight: 700; 
            padding: 10px 15px; border-radius: 10px; text-transform: uppercase; 
            display: flex; align-items: center; gap: 6px; transition: 0.3s;
        }
        .nav-item:hover, .nav-item.active { color: var(--primary); background: var(--primary-soft); }

        /* Keranjang & Badge */
        .cart-link { position: relative; display: flex; align-items: center; }
        .cart-badge { 
            background: #f43f5e; color: #fff; font-size: 9px; 
            padding: 2px 6px; border-radius: 50px; font-weight: 800;
            position: absolute; top: -5px; right: -5px;
            box-shadow: 0 2px 8px rgba(244, 63, 94, 0.4);
        }

        .user-menu { display: flex; align-items: center; gap: 12px; border-left: 2px solid #e2e8f0; padding-left: 15px; margin-left: 10px; }
        .btn-logout { background: var(--merah-logout); color: #fff; border: none; padding: 8px 15px; border-radius: 8px; font-size: 10px; font-weight: 800; cursor: pointer; transition: 0.2s; }
        .btn-logout:hover { filter: brightness(0.9); transform: translateY(-1px); }

        /* Scrollbar Mewah */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f5f9; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--primary); }
    </style>
    @yield('extra-css')
</head>
<body>
    <nav class="navbar">
        <a href="/" class="brand">SHELTER <span>MANAHAN</span></a>
        <div class="nav-links">
            <a href="/" class="nav-item {{ request()->is('/') ? 'active' : '' }}"><i class="fa-solid fa-utensils"></i> Katalog</a>
            
            @if(auth()->check())
                <a href="{{ route('orders.index') }}" class="nav-item {{ request()->routeIs('orders.index') ? 'active' : '' }}"><i class="fa-solid fa-receipt"></i> Pesanan</a>
                
                <a href="{{ route('cart.index') }}" class="nav-item cart-link {{ request()->routeIs('cart.index') ? 'active' : '' }}">
                    <i class="fa-solid fa-shopping-cart" style="font-size: 16px;"></i>
                    <span class="cart-badge" id="cartCount">
                        {{-- Logika dinamis hitung total porsi di keranjang --}}
                        {{ \App\Models\Cart::where('user_id', auth()->id())->sum('quantity') }}
                    </span>
                </a>

                <div class="user-menu">
                    <span style="font-weight: 700; font-size: 13px; color: var(--teks-gelap);">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn-logout">KELUAR</button>
                    </form>
                </div>
            @else
                <a href="{{ route('customer.login') }}" class="nav-item">Masuk</a>
            @endif
        </div>
    </nav>

    @yield('content')

</body>
</html>