<?php 

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DoctorStatusController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PatientAuthController;
use App\Http\Controllers\PatientDashboardController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WilayahController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// ── GUEST (tanpa login) ──
Route::get('/', [HomeController::class, 'index'])->name('home');

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

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('patient.dashboard')->with('success', 'Email berhasil diverifikasi!');
    })->middleware('signed')->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Link verifikasi telah dikirim ulang ke email Anda.');
    })->middleware('throttle:6,1')->name('verification.send');
});

// ── PASIEN (harus login + verified) ──
Route::middleware('auth.patient')->group(function () {
    Route::get('/booking', [BookingController::class, 'index'])->name('booking.index');
    Route::post('/booking', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/status-dokter', [DoctorStatusController::class, 'index'])->name('status-dokter');
    Route::get('/antrean-saya', [PatientDashboardController::class, 'index'])->name('patient.dashboard');
    Route::get('/review/{booking}', [ReviewController::class, 'create'])->name('review.create');
    Route::post('/review/{booking}', [ReviewController::class, 'store'])->name('review.store');
});

// ── AJAX / JSON (publik) ──
Route::prefix('api')->group(function () {
    Route::get('/doctors', [ApiController::class, 'doctors']);
    Route::get('/schedules/{id}', [ApiController::class, 'schedules']);
    Route::get('/doctor-status', [ApiController::class, 'doctorStatus']);
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
    Route::patch('/bookings/{id}/confirm', [App\Http\Controllers\Admin\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::patch('/bookings/{id}/reject', [App\Http\Controllers\Admin\BookingController::class, 'reject'])->name('bookings.reject');
    Route::patch('/bookings/{id}/done', [App\Http\Controllers\Admin\BookingController::class, 'done'])->name('bookings.done');
    Route::get('/doctor-status', [App\Http\Controllers\Admin\DoctorStatusController::class, 'index'])->name('doctor-status.index');
    Route::patch('/doctor-status/{doctorId}', [App\Http\Controllers\Admin\DoctorStatusController::class, 'update'])->name('doctor-status.update');
    Route::resource('/doctors', DoctorController::class)->names('doctors');
    Route::resource('/specializations', SpecializationsController::class)->names('specializations');
    Route::resource('/schedules', ScheduleController::class)->names('schedules');
});
