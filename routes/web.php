<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('home');

Route::middleware('auth')->get('/dashboard', [\App\Http\Controllers\User\DashboardController::class, 'index'])->name('dashboard');

Route::get('/book/{id}', [\App\Http\Controllers\User\BookDetailController::class, 'show'])->name('book.detail');

Route::get('/reader/{id}', [\App\Http\Controllers\User\PdfReaderController::class, 'show'])->name('reader');

// Admin authentication and dashboard
Route::prefix('admin')->group(function () {
    // Admin login pages (no auth/role middleware)
    Route::get('login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('admin.login.post');

    // Logout: only accessible by authenticated admin users
    Route::post('logout', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])
        ->middleware(['auth', 'role:admin'])
        ->name('admin.logout');

    // Back-compat: beberapa menu kemungkinan masih memakai URL lama /admin/logout
    // Jika route ini tidak dipakai, tidak berdampak.
    Route::post('/logout', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])
        ->middleware(['auth', 'role:admin'])
        ->name('admin.logout.legacy');

    // Protected admin routes (must be auth + role:admin)
    Route::middleware(['auth', 'role:admin'])->group(function () {
        // Dashboard
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        // Books
        Route::resource('books', \App\Http\Controllers\Admin\BookController::class, ['as' => 'admin']);

        // Categories
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class, ['as' => 'admin']);

        // Borrowings
        Route::resource('borrowings', \App\Http\Controllers\Admin\BorrowingController::class, ['as' => 'admin']);

        // Membership
        Route::resource('memberships', \App\Http\Controllers\Admin\MembershipController::class, ['as' => 'admin']);

        // Settings
        Route::resource('settings', \App\Http\Controllers\Admin\SettingController::class, ['as' => 'admin']);

        // Users
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class, ['as' => 'admin']);

        // Reset Password (custom action)
        Route::post('users/{user}/reset-password', [\App\Http\Controllers\Admin\UserController::class, 'resetPassword'])
            ->name('admin.users.reset-password');


        // Reports
        Route::get('laporan', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->name('admin.reports.index');

        Route::get('laporan/export/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])
            ->name('admin.reports.export.pdf');

        Route::get('laporan/export/excel', [\App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])
            ->name('admin.reports.export.excel');
    });
});


Auth::routes(['register' => false]);

// =============================
// Authorization for Login User
// =============================
// Tolak admin untuk akses halaman user /dashboard (juga akan menghalangi login admin via /login).
// Route::middleware(['auth'])->group(function () {
//     Route::get('/dashboard', function () {
//         abort_if(auth()->user()?->role === 'admin', 403, 'Akun Administrator harus login melalui halaman Admin.');
//         return view('dashboard');
//     })->name('dashboard');
// });



