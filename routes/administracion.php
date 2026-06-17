<?php

use App\Http\Controllers\AuditoriaController;
use App\Http\Controllers\UsuariosController;
use Illuminate\Support\Facades\Route;

Route::get('/auditoria', [AuditoriaController::class, 'index'])->middleware('module:auditoria,view')->name('auditoria.index');
Route::get('/auditoria/exportar', [AuditoriaController::class, 'export'])->middleware(['module:auditoria,view', 'admin'])->name('auditoria.export');
Route::post('/auditoria', [AuditoriaController::class, 'store'])->middleware('module:auditoria,create')->name('auditoria.store');
Route::get('/auditoria/{log}', [AuditoriaController::class, 'show'])->middleware('module:auditoria,view')->name('auditoria.show');
Route::put('/auditoria/{log}', [AuditoriaController::class, 'update'])->middleware('module:auditoria,edit')->name('auditoria.update');
Route::delete('/auditoria/{log}', [AuditoriaController::class, 'destroy'])->middleware('module:auditoria,delete')->name('auditoria.destroy');

Route::middleware('admin')->group(function () {
    Route::get('/usuarios', [UsuariosController::class, 'index'])->middleware('module:usuarios,view')->name('usuarios.index');
    Route::get('/usuarios/create', [UsuariosController::class, 'create'])->middleware('module:usuarios,create')->name('usuarios.create');
    Route::post('/usuarios', [UsuariosController::class, 'store'])->middleware('module:usuarios,create')->name('usuarios.store');
    Route::get('/usuarios/{usuario}', [UsuariosController::class, 'show'])->middleware('module:usuarios,view')->name('usuarios.show');
    Route::get('/usuarios/{usuario}/edit', [UsuariosController::class, 'edit'])->middleware('module:usuarios,edit')->name('usuarios.edit');
    Route::put('/usuarios/{usuario}', [UsuariosController::class, 'update'])->middleware('module:usuarios,edit')->name('usuarios.update');
    Route::delete('/usuarios/{usuario}', [UsuariosController::class, 'destroy'])->middleware('module:usuarios,delete')->name('usuarios.destroy');
});
