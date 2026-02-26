<?php

use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController; // <-- 1. AÑADIR ESTA LÍNEA
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DoctorController;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

//Gestion de roles
Route::resource('roles', RoleController::class)->names('admin.roles');

//Gestion de usuarios
// <-- 2. AÑADIR ESTA LÍNEA
Route::resource('users', UserController::class)->names('admin.users');

//Gestion de pacientes
Route::resource('patients', PatientController::class)->names('admin.patients');

// Gestión de doctores
Route::resource('doctors', DoctorController::class)->names('admin.doctors');