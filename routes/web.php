<?php

use App\Http\Controllers\Admin\ArchiveController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\SpecializationController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DoctorStatusController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PatientAuthController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WilayahController;
use Illuminate\Support\Facades\Route;

// ── GUEST (tanpa login) ──
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');

// ── AUTH PASIEN ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [PatientAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [PatientAuthController::class, 'login']);
    Route::get('/register', [PatientAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [PatientAuthController::class, 'register']);
});

Route::post('/logout', [PatientAuthController::class, 'logout'])->name('logout')->middleware('auth');

// ── EMAIL VERIFICATION ──
Route::middleware('auth')->group(function () {
    Route::get('/email/verify', function () {
        return view('auth.verify-email');
    })->name('verification.notice');

    Route::post('/email/verify-code', [PatientAuthController::class, 'verifyCode'])->name('verification.verify-code');
    Route::post('/email/verification-notification', [PatientAuthController::class, 'resendCode'])->middleware('throttle:6,1')->name('verification.send');
});

// ── PASIEN (harus login + verified) ──
Route::middleware('auth.patient')->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/status-dokter', [DoctorStatusController::class, 'index'])->name('status-dokter');
    Route::get('/antrean-saya', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
    Route::get('/review/{booking}', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/review/{booking}', [ReviewController::class, 'store'])->name('review.store');

    // ── Notifikasi ──
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// ── AJAX / JSON (publik) ──
Route::prefix('api')->group(function () {
    Route::get('/doctors', [ApiController::class, 'doctors']);
    Route::get('/schedules/{id}', [ApiController::class, 'schedules']);
    Route::get('/doctor-capacities', [ApiController::class, 'doctorCapacities']);
    Route::get('/doctor-status', [ApiController::class, 'doctorStatus']);
    Route::post('/suggest-doctor', [ApiController::class, 'suggestDoctor'])->name('api.suggest-doctor');
    Route::get('/wilayah/provinces', [WilayahController::class, 'provinces']);
    Route::get('/wilayah/districts', [WilayahController::class, 'districts']);
    Route::get('/wilayah/subdistricts', [WilayahController::class, 'subDistricts']);
    Route::get('/wilayah/villages', [WilayahController::class, 'villages']);
});

// ── AUTH ADMIN ──
Route::get('/admin/login', [AuthController::class, 'showLogin'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login']);
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

// ── ADMIN (middleware: auth.admin) ──
Route::prefix('admin')->middleware('auth.admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/reset-daily', [DashboardController::class, 'resetDaily'])->name('reset-daily');
    Route::get('/bookings', [App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::post('/bookings', [App\Http\Controllers\Admin\BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{id}/confirm', [App\Http\Controllers\Admin\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{id}/examine', [App\Http\Controllers\Admin\BookingController::class, 'examine'])->name('bookings.examine');
    Route::patch('/bookings/{id}/reject', [App\Http\Controllers\Admin\BookingController::class, 'reject'])->name('bookings.reject');
    Route::patch('/bookings/{id}/done', [App\Http\Controllers\Admin\BookingController::class, 'done'])->name('bookings.done');
    Route::get('/doctor-status', [App\Http\Controllers\Admin\DoctorStatusController::class, 'index'])->name('doctor-status.index');
    Route::patch('/doctor-status/{doctorId}', [App\Http\Controllers\Admin\DoctorStatusController::class, 'update'])->name('doctor-status.update');
    Route::resource('/doctors', DoctorController::class)->names('doctors');
    // Specialization management
    Route::get('/specializations', [SpecializationController::class, 'index'])->name('specializations.index');
    Route::post('/specializations', [SpecializationController::class, 'store'])->name('specializations.store');
    Route::put('/specializations/{specialization}', [SpecializationController::class, 'update'])->name('specializations.update');
    Route::delete('/specializations/{specialization}', [SpecializationController::class, 'destroy'])->name('specializations.destroy');
    Route::resource('/schedules', ScheduleController::class)->names('schedules');
    // Archive
    Route::get('/archive', [ArchiveController::class, 'index'])->name('archive.index');
    Route::delete('/archive/{id}', [ArchiveController::class, 'destroy'])->name('archive.destroy');
});
