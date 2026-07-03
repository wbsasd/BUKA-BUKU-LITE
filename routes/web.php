<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Laporan Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/laporan', [\App\Http\Controllers\Admin\ReportController::class, 'index'])
        ->name('admin.reports.index');

    Route::get('/admin/laporan/export/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportPdf'])
        ->name('admin.reports.export.pdf');

    Route::get('/admin/laporan/export/excel', [\App\Http\Controllers\Admin\ReportController::class, 'exportExcel'])
        ->name('admin.reports.export.excel');
});

