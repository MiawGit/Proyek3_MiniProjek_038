<?php

use App\Http\Controllers\AreaController;
use App\Http\Controllers\AreaSummaryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RekapController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ReportProgressController;
use App\Http\Controllers\UserAdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/create', [ReportController::class, 'create'])->name('reports.create')->middleware('role:pelapor');
    Route::post('/reports', [ReportController::class, 'store'])->name('reports.store')->middleware('role:pelapor');
    Route::get('/reports/{report}/evidence', [ReportController::class, 'evidence'])->name('reports.evidence');
    Route::post('/reports/{report}/notes', [ReportProgressController::class, 'storeNote'])->name('reports.notes.store')->middleware('role:relawan,koordinator');
    Route::post('/reports/{report}/status', [ReportProgressController::class, 'updateStatus'])->name('reports.status.update')->middleware('role:relawan,koordinator');
    Route::get('/reports/{report}', [ReportController::class, 'show'])->name('reports.show');
    Route::get('/reports/{report}/edit', [ReportController::class, 'edit'])->name('reports.edit');
    Route::put('/reports/{report}', [ReportController::class, 'update'])->name('reports.update');

    Route::get('/areas/{area}/summary', [AreaSummaryController::class, 'show'])->name('areas.summary');

    Route::middleware('role:relawan,koordinator')->group(function () {
        Route::get('/rekap', [RekapController::class, 'index'])->name('rekap.index');
    });

    Route::middleware('role:koordinator')->group(function () {
        Route::get('/areas', [AreaController::class, 'index'])->name('areas.index');
        Route::post('/areas', [AreaController::class, 'store'])->name('areas.store');
        Route::get('/areas/{area}/edit', [AreaController::class, 'edit'])->name('areas.edit');
        Route::put('/areas/{area}', [AreaController::class, 'update'])->name('areas.update');
        Route::delete('/areas/{area}', [AreaController::class, 'destroy'])->name('areas.destroy');

        Route::get('/users', [UserAdminController::class, 'index'])->name('users.index');
        Route::post('/users', [UserAdminController::class, 'store'])->name('users.store');
        Route::delete('/users/{user}', [UserAdminController::class, 'destroy'])->name('users.destroy');
    });
});
