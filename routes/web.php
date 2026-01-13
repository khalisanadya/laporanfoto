<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');

Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
Route::get('/reports/riwayat', [ReportController::class, 'riwayat'])->name('reports.riwayat');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
Route::get('/reports/{report}/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');


