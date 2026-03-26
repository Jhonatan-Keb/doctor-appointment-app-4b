<?php

use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\TicketController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\InsuranceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admin.dashboard');
})->name('dashboard');

// Gestión de roles
Route::resource('roles', RoleController::class)->names('admin.roles');

// Gestión de usuarios
Route::resource('users', UserController::class)->names('admin.users');

// Gestión de pacientes
Route::resource('patients', PatientController::class)->names('admin.patients');
Route::get('patients-import', [PatientController::class, 'importForm'])->name('admin.patients.import-form');
Route::post('patients-import', [PatientController::class, 'import'])->name('admin.patients.import');
Route::get('patients-import/template', [PatientController::class, 'downloadTemplate'])->name('admin.patients.import-template');

// Gestión de doctores
Route::resource('doctors', DoctorController::class)->names('admin.doctors');

// Gestión de aseguradoras
Route::resource('insurances', InsuranceController::class)->names('admin.insurances');

// Gestión de citas médicas
Route::post('appointments/send-test-email', [AppointmentController::class, 'sendTestEmail'])->name('admin.appointments.send-test-email');
Route::resource('appointments', AppointmentController::class)->names('admin.appointments');
Route::get('appointments/{appointment}/consult', [AppointmentController::class, 'consult'])->name('admin.appointments.consult');
Route::patch('appointments/{appointment}/complete', [AppointmentController::class, 'complete'])->name('admin.appointments.complete');

// Horarios de doctores
Route::get('doctors/{doctor}/schedules', [ScheduleController::class, 'index'])->name('admin.doctors.schedules');

// Calendario Global
Route::get('calendar', [CalendarController::class, 'index'])->name('admin.calendar.index');
Route::get('calendar/events', [CalendarController::class, 'events'])->name('admin.calendar.events');

// Gestión de Tickets
Route::resource('tickets', TicketController::class)->names('admin.tickets');
