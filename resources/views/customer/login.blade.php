<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Customer | Shelter Manahan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }

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
            background: rgba(0,0,0,0.55); z-index: -1;
        }

        .login-box {
            width: 100%; max-width: 400px;
            background: rgba(255,255,255,0.98);
            border-radius: 24px; padding: 40px;
            box-shadow: 0 20px 50px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
        }

        h2 { text-align: center; color: #111827; font-weight: 800; margin-bottom: 8px; }
        .subtitle { text-align: center; font-size: 14px; color: #6b7280; margin-bottom: 30px; }

        .form-group { margin-bottom: 15px; }
        input {
            width: 100%; padding: 14px; border-radius: 12px;
            border: 1px solid #e5e7eb; outline: none; transition: 0.3s;
        }
        input:focus { border-color: #0284c7; box-shadow: 0 0 0 4px rgba(2,132,199,0.1); }

        button {
            width: 100%; padding: 14px; border: none; border-radius: 12px;
            background: #111827; color: white; font-weight: 700;
            cursor: pointer; transition: 0.3s; display: flex; align-items: center; justify-content: center; gap: 10px;
        }
        button:hover { background: #000; transform: translateY(-2px); }
        button:disabled { background: #6b7280; cursor: not-allowed; }

        .divider { text-align: center; margin: 20px 0; font-size: 12px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; }

        .google-btn {
            display: flex; align-items: center; justify-content: center; gap: 10px;
            padding: 14px; border-radius: 12px; border: 1px solid #e5e7eb;
            text-decoration: none; color: #111; font-weight: 600; background: #fff; transition: 0.3s;
        }
        .google-btn:hover { background: #f9fafb; border-color: #d1d5db; }

        .footer { text-align: center; margin-top: 25px; font-size: 14px; color: #6b7280; }
        .footer a { color: #0284c7; font-weight: 700; text-decoration: none; }
    </style>
</head>

<body>

<div class="login-box">
    <h2>Login Customer</h2>
    <div class="subtitle">Masuk untuk lanjut berbelanja di Manahan</div>

    <form id="loginForm">
        @csrf
        <div class="form-group">
            <input type="email" name="email" id="email" placeholder="Alamat Email" required>
        </div>

        <div class="form-group">
            <input type="password" name="password" id="password" placeholder="Password" required>
        </div>

        <button type="submit" id="btnLogin">
            <i class="fas fa-sign-in-alt"></i> <span>Masuk Sekarang</span>
        </button>
    </form>

    <div class="divider">atau masuk dengan</div>

    <a href="{{ url('/auth/google') }}" class="google-btn">
        <i class="fab fa-google" style="color: #DB4437;"></i> Google Account
    </a>

    <div class="footer">
        Belum punya akun? 
        <a href="{{ url('/register') }}">Daftar Gratis</a>
    </div>
</div>

<script>
    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const btn = document.getElementById('btnLogin');
        const btnText = btn.querySelector('span');
        const formData = new FormData(this);

        // Loading State
        btn.disabled = true;
        btnText.innerText = 'Memverifikasi...';

        fetch("{{ url('/login') }}", {
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
                // Notifikasi Sukses
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: data.message,
                    showConfirmButton: false,
                    timer: 1500,
                    timerProgressBar: true,
                    background: '#fff',
                    color: '#111'
                }).then(() => {
                    // Redirect ke halaman terakhir atau home
                    window.location.href = data.redirect;
                });
            } else {
                throw new Error(data.message);
            }
        })
        .catch(error => {
            // Notifikasi Gagal
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: error.message || 'Terjadi kesalahan sistem.',
                confirmButtonColor: '#111827'
            });
            btn.disabled = false;
            btnText.innerText = 'Masuk Sekarang';
        });
    });
</script>

</body>
</html>