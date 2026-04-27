<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Shelter Manahan Premium</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">

    <style>
        /* --- SEMUA CSS DIGABUNG DISINI --- */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            height: 100%;
            margin: 0;
            font-family: 'Inter', sans-serif;
        }

        /* Container Utama dengan Background Makanan */
        .bg-wrapper {
            /* Ganti URL di bawah dengan link gambar makanan kamu */
            background-image: url('https://images.unsplash.com/photo-1555939594-58d7cb561ad1?ixlib=rb-4.0.3&auto=format&fit=crop&w=1374&q=80');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        /* Overlay Gelap Transparan agar Kotak Login Menonjol */
        .bg-wrapper::before {
            content: "";
            position: absolute;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0, 0, 0, 0.65); /* Efek kegelapan (seperti Pinterest) */
            z-index: 1;
        }

        /* Kotak Login Mewah */
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            padding: 50px 40px;
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            width: 100%;
            max-width: 420px;
            text-align: center;
            position: relative;
            z-index: 2;
            backdrop-filter: blur(5px); /* Sedikit efek kaca */
        }

        .brand-name {
            font-size: 30px;
            font-weight: 800;
            color: #2d3436;
            margin-bottom: 8px;
            letter-spacing: -1px;
        }

        .brand-name span {
            color: #d35400; /* Warna Oranye Khas Kuliner */
        }

        .subtitle {
            color: #636e72;
            font-size: 14px;
            margin-bottom: 35px;
        }

        /* Form Styling */
        .form-group {
            text-align: left;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #2d3436;
            margin-bottom: 8px;
            margin-left: 4px;
        }

        input {
            width: 100%;
            padding: 15px 18px;
            border: 2px solid #edf2f7;
            border-radius: 14px;
            font-size: 16px;
            transition: all 0.3s ease;
            outline: none;
            background-color: #f8fafc;
        }

        input:focus {
            border-color: #d35400;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(211, 84, 0, 0.1);
        }

        /* Tombol Login Mewah */
        .btn-submit {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #e67e22 0%, #d35400 100%);
            color: white;
            border: none;
            border-radius: 14px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 10px 15px -3px rgba(211, 84, 0, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-top: 10px;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(211, 84, 0, 0.4);
            opacity: 0.95;
        }

        /* Error Message */
        .error-alert {
            background: #fff5f5;
            color: #c53030;
            padding: 12px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 13px;
            border: 1px solid #feb2b2;
            font-weight: 500;
        }

        .footer-text {
            margin-top: 30px;
            font-size: 12px;
            color: #b2bec3;
        }
    </style>
</head>
<body>

    <div class="bg-wrapper">
        <div class="login-card">
            <div class="brand-name">Shelter<span>Manahan</span></div>
            <p class="subtitle">Silakan masuk untuk mengelola toko Anda</p>

            @if($errors->any())
                <div class="error-alert">
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="/login" method="POST">
                @csrf
                <div class="form-group">
                    <label>Email atau Nama Pengguna</label>
                    <input type="text" 
                        name="login" 
                        class="form-control" 
                        placeholder="Masukkan email atau nama..." 
                        value="{{ old('login') }}" 
                        required autofocus>
                </div>

                <div class="form-group">
                    <label>Kata Sandi</label>
                    <input type="password" name="password" placeholder="••••••••" required>
                </div>

                <button type="submit" class="btn-submit">Masuk</button>
            </form>

            <div class="footer-text">
                &copy; 2026 Pengelola Shelter Manahan <br> Surakarta, Jawa Tengah
            </div>
        </div>
    </div>

</body>
</html>