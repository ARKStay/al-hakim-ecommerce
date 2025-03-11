<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserIndexController;
use App\Http\Controllers\UserOrdersController;
use App\Http\Controllers\UserHistoryController;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DashboardRatingsController;
use App\Http\Controllers\DashboardSizesController;
use App\Http\Controllers\DashboardUsersController;
use App\Http\Controllers\DashboardBannersController;
use App\Http\Controllers\DashboardPaymentsController;
use App\Http\Controllers\DashboardProductsController;
use App\Http\Controllers\DashboardShippedsController;
use App\Http\Controllers\DashboardCategoriesController;

// Rute untuk pengunjung yang belum login
Route::middleware('guest')->group(function () {
    // Menampilkan halaman utama
    Route::get('/', [IndexController::class, 'index']);

    // Menampilkan detail produk berdasarkan slug
    Route::get('/products/{slug}', [IndexController::class, 'product_detail'])->name('products.detail');

    // Menampilkan daftar produk
    Route::get('/products', [IndexController::class, 'products']);

    // Menampilkan halaman login dan menangani proses login
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

    // Menampilkan halaman register dan menangani proses pendaftaran
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

// Rute untuk logout yang hanya bisa diakses oleh pengguna yang sudah login
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

// Rute untuk pengguna yang sudah login dengan akses admin
Route::middleware(['auth', 'admin'])->group(function () {
    // Menampilkan dashboard admin
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Mengecek slug produk sebelum disimpan
    Route::get('/dashboard/products/checkSlug', [DashboardProductsController::class, 'checkSlug']);

    // Menangani CRUD produk, pengguna, kategori, ukuran, dan banner di dashboard
    Route::resource('/dashboard/products', DashboardProductsController::class);
    Route::resource('/dashboard/users', DashboardUsersController::class);
    Route::resource('/dashboard/categories', DashboardCategoriesController::class)->except('show');
    Route::resource('/dashboard/sizes', DashboardSizesController::class)->except('show');
    Route::resource('/dashboard/banners', DashboardBannersController::class);

    // Menampilkan dan menangani pembayaran di dashboard
    Route::get('/dashboard/payments', [DashboardPaymentsController::class, 'index'])->name('dashboard.payments');
    Route::patch('/dashboard/payments/{id}/approve', [DashboardPaymentsController::class, 'approve'])->name('dashboard.payments.approve');
    Route::patch('/dashboard/payments/{id}/reject', [DashboardPaymentsController::class, 'reject'])->name('dashboard.payments.reject');
    Route::delete('/dashboard/payments/{id}', [DashboardPaymentsController::class, 'delete'])->name('dashboard.payments.delete');

    // Menangani pengiriman produk di dashboard
    Route::get('/dashboard/shippeds', [DashboardShippedsController::class, 'index'])->name('dashboard.shippeds.index');
    Route::patch('/dashboard/shippeds/{id}/complete', [DashboardShippedsController::class, 'complete'])->name('dashboard.shippeds.complete');
    Route::delete('/dashboard/shippeds/{id}', [DashboardShippedsController::class, 'delete'])->name('dashboard.shippeds.delete');

    // Route untuk manajemen rating di dashboard admin
    Route::resource('/dashboard/ratings', DashboardRatingsController::class)->only(['index', 'destroy']);
});

// Rute untuk pengguna yang sudah login dengan akses user
Route::middleware(['auth', 'user'])->group(function () {
    // Menampilkan halaman utama pengguna
    Route::get('/user', [UserIndexController::class, 'index'])->name('user.index');

    // Menampilkan detail produk berdasarkan slug untuk pengguna
    Route::get('user/products/{slug}', [UserIndexController::class, 'detail'])->name('user.products.detail');

    // Menampilkan daftar produk untuk pengguna
    Route::get('user/products', [UserProductController::class, 'index'])->name('user.products');

    // Menangani profil pengguna
    Route::resource('user/profile', UserProfileController::class)->parameters([
        'profile' => 'user',
    ]);

    // Menampilkan dan menangani pesanan pengguna
    Route::get('/user/orders', [UserOrdersController::class, 'index'])->name('user.order');

    // Menampilkan riwayat pengguna dan menangani rating
    Route::get('/user/history', [UserHistoryController::class, 'index'])->name('user.history');
    Route::post('/user/rating', [UserHistoryController::class, 'rateOrder'])->name('user.rating.store');

    // Menangani penambahan produk ke keranjang dan proses checkout
    Route::post('user/detail/{slug}', [UserProductController::class, 'cart']);
    Route::get('/user/cart', [UserProductController::class, 'check_out'])->name('user.cart');
    Route::delete('/user/cart/{id}', [UserProductController::class, 'delete'])->name('user.cart.delete');
    Route::get('confirm_check_out', [UserProductController::class, 'confirm_check_out']);

    // Menangani pembayaran dan pesanan pengguna
    Route::get('/user/payment', [PaymentController::class, 'index']);
    Route::post('/user/payment', [PaymentController::class, 'order'])->name('user.payment');
});
