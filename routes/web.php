<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\Admin\ {
    TableAdminController,
    CategoryAdminController,
    PaymentAdminController,
    ProductAdminController,
    UserAdminController,
};

use App\Http\Controllers\User\ {
    ReservationUserController,
};

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Auth;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         // 'laravelVersion' => Application::VERSION,
//         // 'phpVersion' => PHP_VERSION,
//     ]);
// })->name('welcome');

Route::get('/', [WelcomeController::class, 'index'])->name('welcome');
Route::get('/welcome/{product}', [WelcomeController::class, 'show'])->name('welcome.show');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // Route::get('/dashboard/{product}', [DashboardController::class, 'show'])->name('dashboard.show');
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'name'      => Auth::user()->name,
        ]);
    })->name('dashboard');

    // Admin
    Route::resource('tables', TableAdminController::class);
    Route::resource('categories', CategoryAdminController::class);
    Route::resource('products', ProductAdminController::class);
    Route::resource('user', UserAdminController::class);
    Route::resource('payments', PaymentAdminController::class);

    // User
    Route::resource('reservations', ReservationUserController::class);
    Route::post('reservations/reqpay/{reservation}', [ReservationUserController::class, 'reqPay'])->name('reservations.reqpay');
    
});
