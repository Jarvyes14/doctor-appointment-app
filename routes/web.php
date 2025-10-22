<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::redirect('/', '/admin/');
//Route::get('/', function () {
//  return view('welcome');
//});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/user/profile', [\Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController::class, 'show'])
        ->name('profile.show');
    Route::post('/admin/roles', [RoleController::class, 'store'])
        ->name('admin.roles.store');
    Route::get('/admin/roles/create', [RoleController::class, 'create'])
        ->name('admin.roles.create');
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
