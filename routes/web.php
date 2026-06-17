<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImpersonationController;
use App\Http\Controllers\PermisosController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth')->name('logout');

Route::middleware(['auth', 'audit'])->group(function () {
    Route::get('/', DashboardController::class)->name('dashboard');
    Route::get('/dashboard', DashboardController::class);

    Route::post('/ver-como', [ImpersonationController::class, 'start'])->name('impersonation.start');
    Route::delete('/ver-como', [ImpersonationController::class, 'stop'])->name('impersonation.stop');

    Route::get('/permisos', [PermisosController::class, 'index'])->name('permisos.index');
    Route::put('/permisos', [PermisosController::class, 'update'])->name('permisos.update');

    require __DIR__.'/clinica.php';
    require __DIR__.'/soporte.php';
    require __DIR__.'/administracion.php';
});
