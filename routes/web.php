<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;

// Redirige la raíz al prefijo admin
Route::redirect('/', '/admin');

// Opcional pero útil: que /admin apunte al dashboard
Route::redirect('/admin', '/admin/dashboard');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard de administrador
    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // resources/views/admin/dashboard.blade.php
    })->name('dashboard');

    // CRUD de Roles
    Route::resource('roles', RoleController::class);
    // CRUD de Usuarios
    Route::resource('users', UserController::class);
});
