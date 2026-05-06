<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
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

    Route::middleware('admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        // Modules placeholders
        Route::resource('clinics', \App\Http\Controllers\ClinicController::class);
        Route::resource('doctors', \App\Http\Controllers\DoctorController::class);
        Route::resource('schedules', \App\Http\Controllers\ScheduleController::class);
    });

    Route::resource('bookings', \App\Http\Controllers\BookingController::class);
    Route::resource('reviews', \App\Http\Controllers\ReviewController::class);
});
