<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserIndexController;
use App\Http\Controllers\UserOrdersController;
use App\Http\Controllers\DestinationController;
use App\Http\Controllers\UserHistoryController;
use App\Http\Controllers\UserProductController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\DashboardCrmController;
use App\Http\Controllers\DashboardUsersController;
use App\Http\Controllers\DashboardBannersController;
use App\Http\Controllers\DashboardRatingsController;
use App\Http\Controllers\DashboardProductsController;
use App\Http\Controllers\DashboardShippedsController;

/*
|--------------------------------------------------------------------------
| Routes for guests (not logged in)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    // Homepage
    Route::get('/', [IndexController::class, 'index']);

    // Product detail by slug
    Route::get('/products/{slug}', [IndexController::class, 'product_detail'])->name('products.detail');

    // Product list
    Route::get('/products', [IndexController::class, 'products']);

    // Login
    Route::get('/login', [LoginController::class, 'index'])->name('login');
    Route::post('/login', [LoginController::class, 'authenticate']);

    // Register
    Route::get('/register', [RegisterController::class, 'index'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);

    // Reset Password
    Route::get('/forgot-password', [LoginController::class, 'forgotPassword'])->name('password.request');
    Route::post('/forgot-password', [LoginController::class, 'sendResetLink'])->name('password.email');
    Route::get('/reset-password/{token}', [LoginController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [LoginController::class, 'resetPassword'])->name('password.update');
});

/*
|--------------------------------------------------------------------------
| Logout route (only for logged in users)
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');

/*
|--------------------------------------------------------------------------
| Admin routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/dashboard/print-report', [DashboardController::class, 'printReport'])->name('dashboard.print');

    // Products management
    Route::get('/dashboard/products/checkSlug', [DashboardProductsController::class, 'checkSlug']);
    Route::resource('/dashboard/products', DashboardProductsController::class);

    // Users management
    Route::resource('/dashboard/users', DashboardUsersController::class);
    Route::patch('/dashboard/users/{user}/toggle-status', [DashboardUsersController::class, 'toggleStatus'])->name('dashboard.users.toggle-status');
    Route::patch('/dashboard/users/{user}/activate', [DashboardUsersController::class, 'activate'])->name('dashboard.users.activate');
    Route::patch('/dashboard/users/{user}/deactivate', [DashboardUsersController::class, 'deactivate'])->name('dashboard.users.deactivate');

    // Banners management
    Route::resource('/dashboard/banners', DashboardBannersController::class);

    // Shipped management
    Route::get('/dashboard/shippeds', [DashboardShippedsController::class, 'index'])->name('dashboard.shippeds.index');
    Route::patch('/dashboard/shippeds/{id}/shipped', [DashboardShippedsController::class, 'shipped'])->name('dashboard.shippeds.shipped');
    Route::delete('/dashboard/shippeds/{id}', [DashboardShippedsController::class, 'delete'])->name('dashboard.shippeds.delete');
    Route::get('/dashboard/shippeds/{id}', [DashboardShippedsController::class, 'show'])->name('dashboard.shippeds.show');

    // Ratings management
    Route::resource('/dashboard/ratings', DashboardRatingsController::class)->only(['index', 'destroy']);

    // CRM
    Route::resource('/dashboard/crm', DashboardCrmController::class);
});

/*
|--------------------------------------------------------------------------
| User routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'user'])->group(function () {
    // Homepage for user
    Route::get('/user', [UserIndexController::class, 'index'])->name('user.index');

    // Product detail for user
    Route::get('user/products/{slug}', [UserIndexController::class, 'detail'])->name('user.products.detail');

    // Product list for user
    Route::get('user/products', [UserProductController::class, 'index'])->name('user.products');

    // User profile
    Route::resource('user/profile', UserProfileController::class)->parameters(['profile' => 'user']);

    // Orders
    Route::get('/user/orders', [UserOrdersController::class, 'index'])->name('user.order');
    Route::patch('/orders/{order}/mark-received', [UserOrdersController::class, 'markReceived'])->name('orders.markReceived');
    Route::get('/orders/{order}/review', [UserOrdersController::class, 'review'])->name('orders.review');
    Route::post('/orders/{order}/review', [UserOrdersController::class, 'storeReview'])->name('orders.review.store');

    // History & ratings
    Route::get('/user/history', [UserHistoryController::class, 'index'])->name('user.history');
    Route::post('/user/rating', [UserHistoryController::class, 'rateOrder'])->name('user.rating.store');

    // Cart & checkout
    Route::post('user/detail/{slug}', [UserProductController::class, 'cart']);
    Route::get('/user/cart', [UserProductController::class, 'check_out'])->name('user.cart');
    Route::delete('/user/cart/{id}', [UserProductController::class, 'delete'])->name('user.cart.delete');
    Route::get('confirm_check_out', [UserProductController::class, 'confirm_check_out']);

    // Payment
    Route::get('/user/payment', [PaymentController::class, 'index']);
    Route::post('/user/payment', [PaymentController::class, 'order'])->name('user.payment');

    // Destination (province, city, district)
    Route::get('/provinces', [DestinationController::class, 'getProvinces']);
    Route::get('/cities/{provinceId}', [DestinationController::class, 'getCities']);
    Route::get('/districts/{cityId}', [DestinationController::class, 'getDistricts']);

    // Shipping cost
    Route::post('/check-ongkir', [ShippingController::class, 'checkOngkir'])->name('check-ongkir');

    // Midtrans Snap token
    Route::post('/payment/snap-token', [PaymentController::class, 'getSnapToken'])->name('payment.snap.token');
});
