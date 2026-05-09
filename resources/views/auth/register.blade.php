<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun | Shelter Manahan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #0284c7;
            --teks-gelap: #111827;
            --teks-abu: #6b7280;
            --white: #ffffff;
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Plus Jakarta Sans', sans-serif; }

        body {
            height: 100vh; display: flex; align-items: center; justify-content: center;
            position: relative; overflow: hidden; background: #000;
        }

        body::before {
            content: ""; position: absolute; inset: 0;
            background: url("{{ asset('images/bg-shelter.jpg') }}") center/cover no-repeat;
            z-index: -2;
        }

        body::after {
            content: ""; position: absolute; inset: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.4), rgba(0,0,0,0.7));
            z-index: -1;
        }

        .register-box {
            width: 100%; max-width: 450px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 28px; padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 { text-align: center; color: var(--teks-gelap); font-weight: 800; font-size: 26px; margin-bottom: 8px; letter-spacing: -0.5px; }
        .subtitle { text-align: center; font-size: 14px; color: var(--teks-abu); margin-bottom: 30px; }

        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; color: var(--teks-gelap); margin-bottom: 8px; text-transform: uppercase; }
        
        input {
            width: 100%; padding: 14px 18px; border-radius: 14px;
            border: 1.5px solid #e5e7eb; outline: none; transition: var(--transition);
            background: #f9fafb; font-size: 15px;
        }
        input:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.1); }

        button#btnRegister {
            width: 100%; padding: 16px; border: none; border-radius: 14px;
            background: var(--teks-gelap); color: white; font-weight: 700; font-size: 15px;
            cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 10px;
            margin-top: 10px;
        }
        button#btnRegister:hover { background: #000; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.15); }
        button#btnRegister:disabled { background: #9ca3af; cursor: not-allowed; }

        .divider { 
            text-align: center; margin: 25px 0; font-size: 12px; color: #9ca3af; 
            text-transform: uppercase; letter-spacing: 1.5px; position: relative;
        }
        .divider::before, .divider::after {
            content: ""; position: absolute; top: 50%; width: 25%; height: 1px; background: #e5e7eb;
        }
        .divider::before { left: 0; } .divider::after { right: 0; }

        .google-btn {
            display: flex; align-items: center; justify-content: center; gap: 12px;
            padding: 14px; border-radius: 14px; border: 1.5px solid #e5e7eb;
            text-decoration: none; color: #374151; font-weight: 700; font-size: 14px;
            background: #fff; transition: var(--transition);
        }
        .google-btn:hover { border-color: #d1d5db; transform: translateY(-1px); }
        .google-btn img { width: 20px; height: 20px; }

        .footer { text-align: center; margin-top: 30px; font-size: 14px; color: var(--teks-abu); }
        .footer a { color: var(--primary); font-weight: 700; text-decoration: none; }
    </style>
</head>

<body>

<div class="register-box">
    <h2>Daftar Akun Baru</h2>
    <div class="subtitle">Gabung sekarang untuk mulai berbelanja di Shelter Manahan</div>

    <form id="registerForm">
        @csrf
        <div class="form-group">
            <label>Nama Lengkap</label>
            <input type="text" name="name" placeholder="Contoh: Fathin" required>
        </div>

        <div class="form-group">
            <label>Alamat Email</label>
            <input type="email" name="email" placeholder="email@contoh.com" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" placeholder="Minimal 8 karakter" required>
        </div>

        <div class="form-group">
            <label>Konfirmasi Password</label>
            <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
        </div>

        <button type="submit" id="btnRegister">
            <i class="fas fa-user-plus"></i> <span>Daftar Sekarang</span>
        </button>
    </form>

    <div class="divider">Atau daftar dengan</div>

    <a href="{{ url('/auth/google') }}" class="google-btn">
        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Logo">
        Google Account
    </a>

    <div class="footer">
        Sudah punya akun? 
        <a href="{{ route('customer.login') }}">Masuk Disini</a>
    </div>
</div>

<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('btnRegister');
        const btnText = btn.querySelector('span');
        const formData = new FormData(this);

        btn.disabled = true;
        btnText.innerText = 'Memproses...';

        fetch("{{ url('/register') }}", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Pendaftaran Berhasil!',
                    text: 'Akun Anda sudah siap. Mengalihkan...',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true
                }).then(() => {
                    window.location.href = data.redirect;
                });
            } else {
                throw new Error(data.message || 'Cek kembali data Anda.');
            }
        })
        .catch(error => {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Daftar',
                text: error.message,
                confirmButtonColor: '#111827'
            });
            btn.disabled = false;
            btnText.innerText = 'Daftar Sekarang';
        });
    });
</script>

</body>
</html>