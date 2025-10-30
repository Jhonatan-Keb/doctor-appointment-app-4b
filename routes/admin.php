<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController; // ← ahora apunta al namespace correcto

// Dashboard principal del administrador
Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

// Gestión de roles
Route::resource('roles', RoleController::class);
