<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorAvailabilityController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.post');

    Route::get('forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');
    Route::get('reset-password/{token}', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::delete('account/delete', [AuthController::class, 'deleteAccount'])->name('account.delete');

    // Ketersediaan dokter — semua user bisa akses
    Route::get('availability', [DoctorAvailabilityController::class, 'index'])->name('availability.index');
    // API endpoint untuk AJAX check availability (booking form)
    Route::get('api/check-availability', [DoctorAvailabilityController::class, 'checkAvailability'])->name('api.check-availability');

    // Email Verification Routes
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        if ($request->user()->isAdmin()) {
            return redirect()->route('dashboard')->with('success', 'Email berhasil diverifikasi.');
        }

        return redirect()->route('bookings.index')->with('success', 'Email berhasil diverifikasi.');
    })->middleware(['signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Email verifikasi telah dikirim ulang!');
    })->middleware(['throttle:6,1'])->name('verification.send');

    // Routes that require verified email
    Route::middleware('verified')->group(function () {
        Route::middleware('admin')->group(function () {
            Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

            Route::resource('clinics', ClinicController::class);
            Route::resource('doctors', DoctorController::class);
            Route::resource('schedules', ScheduleController::class);
        });

        Route::resource('bookings', BookingController::class);
        Route::resource('reviews', ReviewController::class);
    });
});
