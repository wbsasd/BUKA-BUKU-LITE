<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('landing');
})->name('home');

Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/book/{id}', function ($id) {
    return view('book-detail');
})->name('book.detail');

Route::get('/reader/{id}', function ($id) {
    return view('pdf-reader');
})->name('reader');

// Admin authentication and dashboard
Route::prefix('admin')->group(function () {
    // Admin login pages
    Route::get('login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('admin.logout');

    // Protected admin routes
    Route::middleware([\App\Http\Middleware\EnsureAdmin::class])->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        // Admin user management
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class, ['as' => 'admin']);

        // Additional admin routes (reports already defined below)
    });
});


Auth::routes(['register' => false]);

// =============================
// Authorization for Login User
// =============================
// Tolak admin untuk akses halaman user /dashboard (juga akan menghalangi login admin via /login).
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        abort_if(auth()->user()?->role === 'admin', 403, 'Akun Administrator harus login melalui halaman Admin.');
        return view('dashboard');
    })->name('dashboard');
});

// Laporan Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/laporan', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
        ->name('admin.reports.index');

    Route::get('/admin/laporan/export/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])
        ->name('admin.reports.export.pdf');

    Route::get('/admin/laporan/export/excel', [\App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])
        ->name('admin.reports.export.excel');
});


