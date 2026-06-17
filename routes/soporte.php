<?php

use App\Http\Controllers\Soporte\DashboardController as SoporteDashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('soporte')->name('soporte.')->group(function () {
    Route::get('/dashboard', SoporteDashboardController::class)
        ->middleware('module:soporte_dashboard,view')
        ->name('dashboard');
});
