<?php

use App\Http\Controllers\ReportController;
use App\Http\Controllers\BapController;
use App\Http\Controllers\UtilizationReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::get('/dashboard', [ReportController::class, 'dashboard'])->name('dashboard');

// Reports
Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create');
Route::get('/reports/riwayat', [ReportController::class, 'riwayat'])->name('reports.riwayat');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
Route::get('/reports/{report}/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');
Route::get('/reports/{report}/word', [ReportController::class, 'word'])->name('reports.word');

// BAP
Route::get('/bap', [BapController::class, 'index'])->name('bap.index');
Route::get('/bap/create', [BapController::class, 'create'])->name('bap.create');
Route::post('/bap', [BapController::class, 'store'])->name('bap.store');
Route::get('/bap/{bap}', [BapController::class, 'show'])->name('bap.show');
Route::get('/bap/{bap}/word', [BapController::class, 'word'])->name('bap.word');

// Utilization Report
Route::get('/utilization', [UtilizationReportController::class, 'index'])->name('utilization.index');
Route::get('/utilization/create', [UtilizationReportController::class, 'create'])->name('utilization.create');
Route::post('/utilization', [UtilizationReportController::class, 'store'])->name('utilization.store');
Route::get('/utilization/{utilization}', [UtilizationReportController::class, 'show'])->name('utilization.show');
Route::get('/utilization/{utilization}/excel', [UtilizationReportController::class, 'excel'])->name('utilization.excel');


