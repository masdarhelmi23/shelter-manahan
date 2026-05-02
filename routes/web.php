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
    Route::get('/login', 'showLogin')->name('login');
    Route::post('/login', 'login');
    Route::get('/register', 'showRegister')->name('register');
    Route::post('/register', 'register');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/customer/login', 'showCustomerLogin')->name('customer.login');
    Route::post('/customer/login', 'customerLogin');
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
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::get('/users/{id}/edit', [AdminController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [AdminController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');
        
        // Fitur Manajemen Toko
        Route::get('/shops', [AdminController::class, 'shops'])->name('shops');
        Route::get('/shops/{id}', [AdminController::class, 'showShop'])->name('shops.show'); // PINDAH KE SINI AGAR PREFIX ADMIN AKTIF
        Route::post('/shops/approve/{id}', [AdminController::class, 'approveShop'])->name('shops.approve');

        // Fitur Penarikan Dana (Withdrawal)
        Route::get('/withdrawals', [AdminController::class, 'withdrawals'])->name('withdrawals');
        Route::post('/withdrawals/{id}/approve', [AdminController::class, 'approveWithdraw'])->name('withdrawals.approve');
        Route::post('/withdrawals/{id}/reject', [AdminController::class, 'rejectWithdraw'])->name('withdrawals.reject');
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
        
        // --- FITUR SALDO & WITHDRAW (HALAMAN BARU) ---
        Route::get('/withdraw', [OwnerController::class, 'indexWithdraw'])->name('withdraw.index');
        Route::post('/withdraw', [OwnerController::class, 'withdraw'])->name('withdraw');

        Route::controller(OwnerController::class)->group(function () {
            Route::get('/produk', 'produk')->name('produk');
            Route::get('/produk/create', 'create')->name('produk.create');
            Route::post('/produk', 'store')->name('produk.store');
            Route::get('/produk/{id}/edit', 'edit')->name('produk.edit');
            Route::put('/produk/{id}', 'update')->name('produk.update');
            Route::delete('/produk/{id}', 'destroy')->name('produk.destroy');
            Route::get('/pengaturan', 'pengaturan')->name('pengaturan');
            Route::put('/pengaturan/update', 'updatePengaturan')->name('pengaturan.update');
            Route::post('/toko/store', 'storeToko')->name('toko.store');
            
            // Pesanan untuk Owner dalam grup prefix owner
            Route::get('/pesanan', 'pesanan')->name('pesanan');
            Route::post('/pesanan/store', 'storePesanan')->name('pesanan.store');
            Route::put('/pesanan/{id}/status', 'updateStatusPesanan')->name('pesanan.status');
            Route::put('/pesanan/{id}/update', 'updatePesanan')->name('pesanan.update');
            Route::delete('/pesanan/{id}/destroy', 'destroyPesanan')->name('pesanan.destroy');
        });

        // Fitur Libur Warung
        Route::post('/set-libur', [OwnerController::class, 'setLibur'])->name('libur.update');
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
    
    // --- KHUSUS ORDER CHECKOUT (FIX 404) ---
    Route::get('/order-checkout/{order_id}', [CustomerController::class, 'orderCheckout'])->name('customer.order_checkout');

    // --- Bagian Checkout Legacy ---
    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/checkout/all', 'index')->name('checkout.all');
        Route::get('/checkout/finish', 'finish')->name('checkout.finish');
        
        // Route detail pesanan / nota
        Route::get('/checkout/{id}', [CustomerController::class, 'orderCheckout'])->name('customer.checkout'); 
        
        Route::get('/checkout/create/{id}', 'create')->name('checkout.create');
        Route::post('/checkout/pay', 'pay')->name('checkout.pay');
    });
});

/*
|--------------------------------------------------------------------------
| 6. GLOBAL & EXTERNAL CALLBACKS
|--------------------------------------------------------------------------
*/
Route::post('/orders/store', [CustomerController::class, 'store'])->name('orders.store');

// Callback Midtrans (API Route)
Route::post('/api/midtrans/callback', [CustomerController::class, 'callback']);