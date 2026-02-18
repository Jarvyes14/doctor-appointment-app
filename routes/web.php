<?php

use App\Http\Controllers\Admin\PatientsController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\DoctorController;
use Illuminate\Support\Facades\Route;

// Ruta raíz: redirige a login si no está autenticado, a /dashboard si lo está
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
})->name('home');

// Rutas públicas (sin autenticación requerida)
Route::get('/doctors', [DoctorController::class, 'index'])->name('doctors.index');
Route::get('/doctors/{doctor}', [DoctorController::class, 'show'])->name('doctors.show');

// Rutas protegidas (requieren autenticación)
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Profile
    Route::get('/user/profile', [\Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController::class, 'show'])
        ->name('profile.show');

    // Dashboard - Redirige a /admin/dashboard
    Route::get('/dashboard', function () {
        return redirect('/admin/dashboard');
    })->name('dashboard');

    // Appointments Routes
    Route::resource('appointments', AppointmentController::class);
    Route::patch('/appointments/{appointment}/status', [AppointmentController::class, 'updateStatus'])
        ->name('appointments.updateStatus');

    // Doctors Edit Routes (solo para doctores autenticados)
    Route::middleware('auth')->group(function () {
        Route::get('/doctors/{doctor}/edit', [DoctorController::class, 'edit'])->name('doctors.edit');
        Route::patch('/doctors/{doctor}', [DoctorController::class, 'update'])->name('doctors.update');
    });

    // Admin Routes - Protected by admin middleware
    Route::middleware('admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // Roles
        Route::resource('roles', RoleController::class);

        // Users
        Route::resource('users', UserController::class);

        // Patients
        Route::resource('patients', PatientsController::class);
    });
});
