<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

Route::resource('roles', RoleController::class);
Route::resource('users', UserController::class);
Route::resource('patients', PatientController::class);
