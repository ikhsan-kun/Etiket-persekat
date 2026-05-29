<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\MatchController as AdminMatchController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\GateController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MyTicketController;
use App\Http\Controllers\WebhookController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Landing Page)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Webhook Routes (No CSRF)
|--------------------------------------------------------------------------
*/
Route::post('/webhook/midtrans', [WebhookController::class, 'midtrans'])->name('webhook.midtrans');

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard redirect
    Route::get('/dashboard', function () {
        if (auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }
        return redirect()->route('matches.index');
    })->name('dashboard');

    // Match catalog
    Route::get('/pertandingan', [HomeController::class, 'matches'])->name('matches.index');
    Route::get('/pertandingan/{match}', [HomeController::class, 'matchDetail'])->name('matches.show');

    // Checkout
    Route::get('/checkout/{match}', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout/{match}', [CheckoutController::class, 'store'])->name('checkout.store');

    // Payment
    Route::get('/payment/{order}', [PaymentController::class, 'show'])->name('payment.show');
    Route::post('/payment/{order}/dummy', [PaymentController::class, 'dummyPay'])->name('payment.dummy');

    // My Tickets
    Route::get('/tiket-saya', [MyTicketController::class, 'index'])->name('my-tickets.index');
    Route::get('/tiket-saya/{order}', [MyTicketController::class, 'show'])->name('my-tickets.show');

    // Profile (from Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Match Management
    Route::resource('matches', AdminMatchController::class)->parameters([
        'matches' => 'match',
    ]);

    // Order Management
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');

    // Reports
    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/export-csv', [AdminReportController::class, 'exportCsv'])->name('reports.export-csv');

    // Gate Validator
    Route::get('/gate', [GateController::class, 'index'])->name('gate.index');
    Route::post('/gate/validate', [GateController::class, 'validateTicket'])->name('gate.validate');
});

require __DIR__.'/auth.php';
