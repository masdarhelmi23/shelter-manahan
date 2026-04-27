<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class AuthController extends Controller
{
    // Menampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
{
    // 1. Validasi Input
    $request->validate([
        'login' => 'required|string',
        'password' => 'required',
    ]);

    // 2. Cek apakah login menggunakan Email atau Username
    $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';

    $credentials = [
        $loginType => $request->login,
        'password' => $request->password,
    ];

    // 3. Proses Attempt Login
    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();

        $user = Auth::user();
        
        // Tentukan Redirect URL berdasarkan Role
        if ($user->role === 'admin') {
            $redirectUrl = route('admin.dashboard');
        } elseif ($user->role === 'owner') {
            $redirectUrl = route('owner.dashboard');
        } else {
            // Untuk Customer: Ambil halaman terakhir yang ingin dibuka, 
            // jika tidak ada, arahkan ke welcome/home.
            $redirectUrl = session()->pull('url.intended', route('welcome'));
        }

        // RESPON UNTUK AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Selamat datang kembali, ' . $user->name,
                'redirect' => $redirectUrl
            ]);
        }

        // RESPON UNTUK REQUEST BIASA (Non-AJAX)
        return redirect()->intended($redirectUrl);
    }

    // 4. JIKA GAGAL
    if ($request->ajax()) {
        return response()->json([
            'success' => false,
            'message' => 'Kredensial yang diberikan tidak cocok dengan data kami.'
        ], 401);
    }

    return back()->withErrors([
        'login' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
    ])->onlyInput('login');
}
    // Proses logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    // =========================
    // CUSTOMER LOGIN VIEW
    // =========================
    public function showCustomerLogin()
    {
        return view('customer.login');
    }

    // =========================
    // GOOGLE LOGIN REDIRECT
    // =========================
    public function redirectGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    // =========================
    // GOOGLE CALLBACK
    // =========================
    public function handleGoogleCallback()
{
    $googleUser = Socialite::driver('google')->stateless()->user();

    $user = User::updateOrCreate(
        [
            'email' => $googleUser->getEmail(),
        ],
        [
            'name' => $googleUser->getName(),
            'google_id' => $googleUser->getId(),
            'password' => bcrypt('google_login'),
            'role' => 'customer',
        ]
    );

    Auth::login($user);

    // 🔥 FIX: langsung ke warung
    return redirect('/warung/bakso');
}

// =========================
// CUSTOMER LOGIN (FIX ERROR ROUTE)
// =========================
public function customerLogin(Request $request)
{
    $request->validate([
        'email' => 'required',
        'password' => 'required',
    ]);

    if (Auth::attempt($request->only('email', 'password'))) {
        $request->session()->regenerate();
        return redirect('/warung/bakso');
    }

    return back()->withErrors([
        'email' => 'Login gagal',
    ]);
}
}