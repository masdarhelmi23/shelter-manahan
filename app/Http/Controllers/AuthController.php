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

    // Menampilkan halaman Register (Biar route di web.php tidak error)
    public function showRegister()
    {
        return view('auth.register');
    }

    // Proses Login Utama (Email/Username + AJAX Support)
    public function login(Request $request)
    {
        // Gunakan try-catch agar jika ada error server, tetap kembali sebagai JSON
        try {
            $request->validate([
                'login' => 'required|string',
                'password' => 'required',
            ]);

            $loginType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'name';
            $credentials = [
                $loginType => $request->login,
                'password' => $request->password,
            ];

            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();
                
                if ($user->role === 'admin') {
                    $defaultUrl = route('admin.dashboard');
                } elseif ($user->role === 'owner') {
                    $defaultUrl = route('owner.dashboard');
                } else {
                    $defaultUrl = route('customer.home');
                }

                $redirectUrl = session()->get('url.intended', $defaultUrl);

                // Memastikan selalu mengirim JSON jika dipanggil via fetch/ajax
                if ($request->expectsJson() || $request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Selamat datang kembali, ' . $user->name,
                        'redirect' => $redirectUrl
                    ]);
                }

                return redirect()->intended($defaultUrl);
            }

            // Respon jika kredensial salah
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Username/Email atau Password salah.'
                ], 401);
            }

            return back()->withErrors(['login' => 'Kredensial tidak cocok.']);

        } catch (\Exception $e) {
            // Jika ada error koding/database, kirim pesan error sebagai JSON (bukan HTML)
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
    // Proses logout: REVISI REDIRECT KE WELCOME
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Diarahkan ke halaman menu awal (Welcome)
        return redirect()->route('welcome');
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
    // GOOGLE CALLBACK (SINKRON ROLE)
    // =========================
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::updateOrCreate(
            ['email' => $googleUser->getEmail()],
            [
                'name' => $googleUser->getName(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt('google_login'),
                'role' => 'customer', // Default Google login adalah customer
            ]
        );

        Auth::login($user);

        // Langsung arahkan ke intended URL (halaman terakhir dibuka) atau ke welcome
        return redirect()->intended(route('welcome'));
    }

    // =========================
    // CUSTOMER LOGIN (FIX ROLE REDIRECT)
    // =========================
    public function customerLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();
            
            // Pastikan customer kembali ke halaman terakhir (misal halaman warung tadi)
            return redirect()->intended(route('welcome'));
        }

        return back()->withErrors([
            'email' => 'Login gagal, periksa kembali email dan password anda.',
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => 'customer', // Default sebagai customer
        ]);

        Auth::login($user);

        return response()->json([
            'success' => true,
            'redirect' => route('welcome')
        ]);
    }
}