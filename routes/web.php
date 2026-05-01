<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| 1. PUBLIC ROUTES (Pengunjung Umum)
|--------------------------------------------------------------------------
*/
Route::get('/', [AdminController::class, 'index'])->name('welcome');
Route::get('/warung/{slug}', [CustomerController::class, 'showWarung'])->name('customer.warung');

/*
|--------------------------------------------------------------------------
| 2. AUTHENTICATION ROUTES (Login, Register, Logout)
|--------------------------------------------------------------------------
*/
Route::controller(AuthController::class)->group(function () {
    // Login & Register
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');

    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');

    Route::post('/logout', 'logout')->name('logout');

    // Customer Login Khusus
    Route::get('/customer/login', 'showCustomerLogin')->name('customer.login');
    Route::post('/customer/login', 'customerLogin');

    // Google Auth
    Route::get('/auth/google', 'redirectGoogle')->name('google.redirect');
    Route::get('/auth/google/callback', 'handleGoogleCallback')->name('google.callback');
});

/*
|--------------------------------------------------------------------------
| 3. ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // Users
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [AdminController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

        // Shops
        Route::get('/shops', [AdminController::class, 'shops'])->name('shops');
        Route::post('/shops/approve/{id}', [AdminController::class, 'approveShop'])->name('shops.approve');
});

/*
|--------------------------------------------------------------------------
| 4. OWNER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:owner'])
    ->prefix('owner')
    ->name('owner.')
    ->group(function () {
        Route::get('/dashboard', [OwnerController::class, 'dashboard'])->name('dashboard');

        Route::controller(OwnerController::class)->group(function () {
            // Produk
            Route::get('/produk', 'produk')->name('produk');
            Route::get('/produk/create', 'create')->name('produk.create');
            Route::post('/produk', 'store')->name('produk.store');
            Route::get('/produk/{id}/edit', 'edit')->name('produk.edit');
            Route::put('/produk/{id}', 'update')->name('produk.update');
            Route::delete('/produk/{id}', 'destroy')->name('produk.destroy');

            // Pengaturan
            Route::get('/pengaturan', 'pengaturan')->name('pengaturan');
            Route::put('/pengaturan/update', 'updatePengaturan')->name('pengaturan.update');

            // Toko
            Route::post('/toko/store', 'storeToko')->name('toko.store');
        });
});

/*
|--------------------------------------------------------------------------
| 5. CUSTOMER ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:customer'])->group(function () {

    Route::get('/home', [CustomerController::class, 'index'])->name('customer.home');
    Route::get('/profile', [CustomerController::class, 'profile'])->name('profile');

    // --- Bagian Cart ---
    Route::get('/cart', [CustomerController::class, 'cart'])->name('cart.index');
    Route::post('/cart/add/{id}', [CustomerController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CustomerController::class, 'removeFromCart'])->name('cart.remove');

    // --- Bagian Orders ---
    Route::get('/orders', [CustomerController::class, 'orders'])->name('orders.index');

    // --- Bagian Checkout (FIX 404 DI SINI) ---
    Route::controller(CheckoutController::class)->group(function () {
        // 1. Rute Statis Wajib di Paling Atas (all & finish)
        Route::get('/checkout/all', 'index')->name('checkout.all');
        Route::get('/checkout/finish', 'finish')->name('checkout.finish');
        
        // 2. Baru Rute Dinamis yang pakai {id} di bawahnya
        Route::get('/checkout/{id}', 'create')->name('checkout');
        Route::get('/checkout/create/{id}', 'create')->name('checkout.create');
        
        // 3. Proses Pembayaran
        Route::post('/checkout/pay', 'pay')->name('checkout.pay');
    });
});

Route::get('/owner/pesanan', [OwnerController::class, 'pesanan'])->name('owner.pesanan');
Route::put('/owner/pesanan/{id}/status', [OwnerController::class, 'updateStatusPesanan'])->name('owner.pesanan.status');

// Rincian Route Pesanan Owner
Route::prefix('owner')->name('owner.')->middleware(['auth', 'role:owner'])->group(function () {
    Route::get('/pesanan', [OwnerController::class, 'pesanan'])->name('pesanan');
    Route::post('/pesanan/store', [OwnerController::class, 'storePesanan'])->name('pesanan.store');
    Route::put('/pesanan/{id}/status', [OwnerController::class, 'updateStatusPesanan'])->name('pesanan.status');
    Route::put('/pesanan/{id}/update', [OwnerController::class, 'updatePesanan'])->name('pesanan.update');
    Route::delete('/pesanan/{id}/destroy', [OwnerController::class, 'destroyPesanan'])->name('pesanan.destroy');
});