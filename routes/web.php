<?php

use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('reports.index'));

Route::get('/reports/index', [ReportController::class, 'create'])->name('reports.index');
Route::post('/reports', [ReportController::class, 'store'])->name('reports.store');
Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
Route::get('/reports/{report}/pdf', [ReportController::class, 'pdf'])->name('reports.pdf');


