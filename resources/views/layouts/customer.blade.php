<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Shelter Manahan - Warung App</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            background: #f3f4f6;
        }

        /* NAVBAR */
        .navbar {
            background: #ffffff;
            color: #1f2937;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 40px;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        /* LOGO STYLE */
        .nav-left {
            font-size: 20px;
            letter-spacing: 1px;
            color: #0369a1; /* Biru Shelter */
            font-weight: 400;
            text-transform: uppercase;
        }

        .nav-left b {
            font-weight: 800;
            color: #111827;
        }

        /* MENU CENTER */
        .nav-menu {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .nav-link {
            color: #4b5563;
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 10px 20px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        /* Style untuk menu aktif ala screenshot */
        .nav-link.active, .nav-link:hover {
            background: #e0f2fe;
            color: #0284c7;
        }

        .badge {
            background: #ef4444;
            color: white;
            font-size: 10px;
            padding: 2px 6px;
            border-radius: 50px;
            font-weight: bold;
            vertical-align: middle;
        }

        /* USER & LOGOUT SECTION */
        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .user-info {
            text-align: right;
            line-height: 1.2;
        }

        .user-role {
            display: block;
            font-size: 10px;
            color: #0284c7;
            font-weight: 800;
            text-transform: uppercase;
        }

        .user-name {
            font-size: 14px;
            color: #1f2937;
            font-weight: 600;
        }

        .logout-btn {
            background: #ef4444; /* Merah sesuai screenshot */
            border: none;
            color: white;
            cursor: pointer;
            font-size: 12px;
            font-weight: 700;
            padding: 10px 20px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 8px;
            text-transform: uppercase;
            transition: background 0.3s;
        }

        .logout-btn:hover {
            background: #dc2626;
        }

        .content {
            padding: 40px;
        }

        @media (max-width: 1024px) {
            .navbar {
                padding: 15px 20px;
            }
            .nav-menu {
                display: none; /* Kamu bisa buat mobile menu nanti */
            }
        }
    </style>
</head>
<body>

<div class="navbar">

    <div class="nav-left">
        SHELTER <b>MANAHAN</b>
    </div>

    <div class="nav-menu">
        <a href="{{ route('customer.warung', ['slug' => 'bakso']) }}" class="nav-link active">
            Ringkasan
        </a>
        <a href="{{ route('cart.index') }}" class="nav-link">
            Keranjang <span class="badge">0</span>
        </a>
        <a href="{{ route('orders.index') }}" class="nav-link">
            Pesanan
        </a>
        <a href="{{ route('profile') }}" class="nav-link">
            Profil
        </a>
    </div>

    <div class="nav-right">
        <div class="user-info">
            <span class="user-role">CUSTOMER</span>
            <span class="user-name">Masdar Helmi</span>
        </div>

        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit" class="logout-btn">
                <i class="fas fa-power-off"></i> Keluar
            </button>
        </form>
    </div>
</div>

<div class="content">
    @yield('content')
</div>

</body>
</html>