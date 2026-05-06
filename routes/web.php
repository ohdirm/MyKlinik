<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorAvailabilityController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');

    // Ketersediaan dokter — semua user bisa akses
    Route::get('availability', [DoctorAvailabilityController::class, 'index'])->name('availability.index');
    // API endpoint untuk AJAX check availability (booking form)
    Route::get('api/check-availability', [DoctorAvailabilityController::class, 'checkAvailability'])->name('api.check-availability');

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::resource('clinics', ClinicController::class);
        Route::resource('doctors', DoctorController::class);
        Route::resource('schedules', ScheduleController::class);
    });

    Route::resource('bookings', BookingController::class);
    Route::resource('reviews', ReviewController::class);
});
