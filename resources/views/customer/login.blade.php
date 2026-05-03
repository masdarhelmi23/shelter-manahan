<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Customer | Shelter Manahan</title>
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
            min-height: 100vh; display: flex; align-items: center; justify-content: center;
            position: relative; overflow-x: hidden; background: #000;
            padding: 20px; /* Jarak aman untuk mobile */
        }

        /* BACKGROUND WITH OVERLAY */
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

        .login-box {
            width: 100%; max-width: 420px;
            background: rgba(255, 255, 255, 0.98);
            border-radius: 28px; padding: 40px 30px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.5);
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        h2 { text-align: center; color: var(--teks-gelap); font-weight: 800; font-size: 24px; margin-bottom: 8px; letter-spacing: -0.5px; }
        .subtitle { text-align: center; font-size: 14px; color: var(--teks-abu); margin-bottom: 30px; }

        /* FORM STYLING */
        .form-group { margin-bottom: 18px; }
        .form-group label { display: block; font-size: 11px; font-weight: 700; color: var(--teks-gelap); margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        
        input {
            width: 100%; padding: 12px 16px; border-radius: 12px;
            border: 1.5px solid #e5e7eb; outline: none; transition: var(--transition);
            background: #f9fafb; font-size: 15px;
        }
        input:focus { border-color: var(--primary); background: #fff; box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.1); }

        button#btnLogin {
            width: 100%; padding: 14px; border: none; border-radius: 12px;
            background: var(--teks-gelap); color: white; font-weight: 700; font-size: 15px;
            cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 10px;
            margin-top: 10px;
        }
        button#btnLogin:hover { background: #000; transform: translateY(-2px); box-shadow: 0 10px 20px rgba(0,0,0,0.15); }
        button#btnLogin:active { transform: translateY(0); }
        button#btnLogin:disabled { background: #9ca3af; cursor: not-allowed; transform: none; }

        .divider { 
            text-align: center; margin: 25px 0; font-size: 11px; color: #9ca3af; 
            text-transform: uppercase; letter-spacing: 1.5px; position: relative;
        }
        .divider::before, .divider::after {
            content: ""; position: absolute; top: 50%; width: 30%; height: 1px; background: #e5e7eb;
        }
        .divider::before { left: 0; } .divider::after { right: 0; }

        .google-btn {
            display: flex; align-items: center; justify-content: center; gap: 12px;
            padding: 12px; border-radius: 12px; border: 1.5px solid #e5e7eb;
            text-decoration: none; color: #374151; font-weight: 700; font-size: 14px;
            background: #fff; transition: var(--transition);
            box-shadow: 0 1px 2px rgba(0,0,0,0.05);
        }
        .google-btn:hover { background: #fdfdfd; border-color: #d1d5db; transform: translateY(-1px); }
        .google-btn img { width: 18px; height: 18px; }

        .footer { text-align: center; margin-top: 25px; font-size: 13px; color: var(--teks-abu); }
        .footer a { color: var(--primary); font-weight: 700; text-decoration: none; transition: 0.2s; }

        /* RESPONSIVE MOBILE ADJUSTMENTS */
        @media (max-width: 480px) {
            .login-box {
                padding: 30px 20px;
                border-radius: 24px;
            }
            h2 { font-size: 22px; }
            .subtitle { font-size: 13px; }
            input { font-size: 14px; padding: 11px 14px; }
            button#btnLogin { font-size: 14px; }
        }
    </style>
</head>

<body>

<div class="login-box">
    <h2>Login Customer</h2>
    <div class="subtitle">Silakan masuk ke akun Shelter Manahan Anda</div>

    <form id="loginForm">
        @csrf
        <div class="form-group">
            <label>Email atau Username</label>
            <input type="text" name="login" id="login" placeholder="Email atau username" required>
        </div>

        <div class="form-group">
            <label>Password</label>
            <input type="password" name="password" id="password" placeholder="••••••••" required>
        </div>

        <button type="submit" id="btnLogin">
            <i class="fas fa-sign-in-alt"></i> <span>Masuk Sekarang</span>
        </button>
    </form>

    <div class="divider">Atau</div>

    <a href="{{ url('/auth/google') }}" class="google-btn">
        <img src="https://www.gstatic.com/firebasejs/ui/2.0.0/images/auth/google.svg" alt="Google Logo">
        Masuk dengan Google
    </a>

    <div class="footer">
        Belum punya akun? 
        <a href="{{ url('/register') }}">Daftar Sekarang</a>
    </div>
</div>

<script>
    // Inisialisasi Toast SweetAlert2
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

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
                "X-Requested-With": "XMLHttpRequest", 
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            },
            body: formData
        })
        .then(response => {
            const contentType = response.headers.get("content-type");
            if (contentType && contentType.indexOf("application/json") !== -1) {
                return response.json().then(data => ({ status: response.status, body: data }));
            } else {
                throw new Error("Sesi berakhir atau terjadi kesalahan server.");
            }
        })
        .then(({ status, body }) => {
            if (body.success) {
                Toast.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: body.message
                }).then(() => {
                    window.location.href = body.redirect;
                });
            } else {
                throw new Error(body.message || 'Kredensial tidak valid.');
            }
        })
        .catch(error => {
            Toast.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: error.message
            });
            btn.disabled = false;
            btnText.innerText = 'Masuk Sekarang';
        });
    });
</script>

</body>
</html>