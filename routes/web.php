<?php

use Illuminate\Support\Facades\Route;

Route::get('/', [\App\Http\Controllers\LandingController::class, 'index'])->name('home');

Route::middleware(['auth', 'membership.active'])->get('/dashboard', function () {
    abort_if(auth()->user()?->role === 'admin', 403, 'Akun Administrator harus login melalui halaman Admin.');
    return app(\App\Http\Controllers\User\DashboardController::class)->index(request());
})->name('dashboard');

// Reader dibuka untuk guest/user agar trial dapat berjalan sesuai flow.
Route::get('/reader/{id}', [\App\Http\Controllers\User\PdfReaderController::class, 'show'])->name('reader');
Route::get('/reader/{id}/document', [\App\Http\Controllers\User\PdfReaderController::class, 'document'])
    ->name('reader.document');

// Detail buku
Route::get('/book/{id}', [\App\Http\Controllers\User\BookDetailController::class, 'show'])->name('book.detail');

// Admin authentication and dashboard

// Membership registration
Route::get('/membership/register', [\App\Http\Controllers\MembershipRegistrationController::class, 'create'])
    ->name('membership.register');

Route::post('/membership/register', [\App\Http\Controllers\MembershipRegistrationController::class, 'store'])
    ->name('membership.store');

Route::prefix('admin')->group(function () {
    // Admin login pages (no auth/role middleware)
    Route::get('login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])
        ->middleware('throttle:10,1')
        ->name('admin.login.post');

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
        // Membership registration requests
        Route::get('membership-requests', [\App\Http\Controllers\Admin\MembershipApprovalController::class, 'index'])
            ->name('admin.membership-requests.index');
        Route::post('membership-requests/{user}/approve', [\App\Http\Controllers\Admin\MembershipApprovalController::class, 'approve'])
            ->name('admin.membership-requests.approve');
        Route::post('membership-requests/{user}/reject', [\App\Http\Controllers\Admin\MembershipApprovalController::class, 'reject'])
            ->name('admin.membership-requests.reject');

        // Dashboard
        // Dashboard
        Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');

        // Books
        Route::resource('books', \App\Http\Controllers\Admin\BookController::class, ['as' => 'admin']);

        // Categories
        Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class, ['as' => 'admin']);

        // Borrowings
        Route::resource('borrowings', \App\Http\Controllers\Admin\BorrowingController::class, ['as' => 'admin']);
        Route::post('borrowings/{borrowing}/warning', [\App\Http\Controllers\Admin\BorrowingController::class, 'sendWarning'])->name('admin.borrowings.warning');

        // Membership Upgrade (New - source of truth: membership_upgrades)
        Route::resource('memberships', \App\Http\Controllers\Admin\AdminMembershipController::class, ['as' => 'admin'])->only([
            'index',
            'show',
        ]);

        // Approve/Reject (New)
        Route::post('memberships/{membership}/approve', [\App\Http\Controllers\Admin\AdminMembershipController::class, 'approve'])
            ->name('admin.memberships.approve');

        Route::post('memberships/{membership}/reject', [\App\Http\Controllers\Admin\AdminMembershipController::class, 'reject'])
            ->name('admin.memberships.reject');


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
// Membership Upgrade (User flow)
// =============================
Route::middleware(['auth'])->group(function () {
    Route::get('/membership/upgrade', [\App\Http\Controllers\MembershipUpgradeController::class, 'plans'])
        ->name('membership.upgrade.plans');

    // Dashboard borrowings count (for realtime card)
    Route::get('/dashboard/borrowings/count', \App\Http\Controllers\User\DashboardBorrowingCountController::class)
        ->name('dashboard.borrowings.count');

    // Dashboard realtime stats (membership + denda + books read)
    Route::get('/dashboard/stats/realtime', \App\Http\Controllers\User\DashboardStatsRealtimeController::class)
        ->name('dashboard.stats.realtime');

    Route::post('/membership/upgrade/review', [\App\Http\Controllers\MembershipUpgradeController::class, 'review'])
        ->name('membership.upgrade.review');



    Route::get('/membership/upgrade/{upgrade}/payment', [\App\Http\Controllers\MembershipUpgradeController::class, 'payment'])
        ->name('membership.upgrade.payment');

    Route::post('/membership/upgrade/{upgrade}/pay', [\App\Http\Controllers\MembershipUpgradeController::class, 'pay'])
        ->name('membership.upgrade.pay');

    Route::get('/membership/upgrade/{upgrade}/finish', [\App\Http\Controllers\MembershipUpgradeController::class, 'finish'])
        ->name('membership.upgrade.finish');
});

// Book reviews (rating & ulasan)
Route::middleware(['auth'])->group(function () {
    Route::post('/books/{book}/reviews', [\App\Http\Controllers\User\ReviewController::class, 'store'])
        ->name('book.reviews.store');

    Route::put('/book-reviews/{review}', [\App\Http\Controllers\User\ReviewController::class, 'update'])
        ->name('book.reviews.update');

    Route::delete('/book-reviews/{review}', [\App\Http\Controllers\User\ReviewController::class, 'destroy'])
        ->name('book.reviews.destroy');
});

// Borrowing user flow
Route::middleware(['auth', 'membership.active'])->group(function () {
    Route::get('/book/{book}/booking', [\App\Http\Controllers\BorrowController::class, 'booking'])->name('borrow.booking');
    Route::post('/book/{book}/booking', [\App\Http\Controllers\BorrowController::class, 'storeBooking'])->name('borrow.store');


    Route::get('/borrow/{borrowing}/payment', [\App\Http\Controllers\BorrowController::class, 'payment'])->name('borrow.payment');
    Route::post('/borrow/{borrowing}/pay', [\App\Http\Controllers\BorrowController::class, 'pay'])->name('borrow.pay');
    Route::get('/borrow/{borrowing}/finish', [\App\Http\Controllers\BorrowController::class, 'finish'])->name('borrow.finish');
    Route::post('/borrow/{borrowing}/return', [\App\Http\Controllers\BorrowController::class, 'returnBook'])->name('borrow.return');
    Route::post('/borrow/{borrowing}/extend', [\App\Http\Controllers\BorrowController::class, 'extendBook'])->name('borrow.extend');

    Route::get('/borrowings/history', [\App\Http\Controllers\BorrowController::class, 'history'])->name('borrow.history');
});


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



