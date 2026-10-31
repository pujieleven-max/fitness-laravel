<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\PosController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SupplementController;
use App\Http\Controllers\TrainerController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Public Promotional Frontend & Online Services
Route::get('/', [PublicController::class, 'index'])->name('landing');
Route::get('/promo', [PublicController::class, 'index'])->name('promo');
Route::get('/public/check-status', [PublicController::class, 'checkStatus'])->name('public.checkStatus');
Route::post('/public/booking', [PublicController::class, 'storeBooking'])->name('public.booking.store');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // POS Routes
    Route::get('/pos', [PosController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [PosController::class, 'storeOrder'])->name('pos.checkout');
    Route::get('/pos/receipt/{order}', [PosController::class, 'receipt'])->name('pos.receipt');
    Route::get('/pos/orders', [PosController::class, 'ordersHistory'])->name('pos.orders');

    // Supplement & Stock Routes
    Route::get('/supplements', [SupplementController::class, 'index'])->name('supplements.index');
    Route::get('/supplements/create', [SupplementController::class, 'create'])->name('supplements.create');
    Route::post('/supplements', [SupplementController::class, 'store'])->name('supplements.store');
    Route::get('/supplements/history', [SupplementController::class, 'stockHistory'])->name('supplements.history');
    Route::get('/supplements/{supplement}/edit', [SupplementController::class, 'edit'])->name('supplements.edit');
    Route::put('/supplements/{supplement}', [SupplementController::class, 'update'])->name('supplements.update');
    Route::post('/supplements/{supplement}/restock', [SupplementController::class, 'restock'])->name('supplements.restock');
    Route::post('/supplements/{supplement}/adjust-stock', [SupplementController::class, 'adjustStock'])->name('supplements.adjustStock');

    // Booking Routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    Route::get('/bookings/calendar', [BookingController::class, 'calendar'])->name('bookings.calendar');

    // Members
    Route::get('/members', [MemberController::class, 'index'])->name('members.index');
    Route::post('/members', [MemberController::class, 'store'])->name('members.store');
    Route::put('/members/{member}', [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{member}', [MemberController::class, 'destroy'])->name('members.destroy');

    // Tiket Harian & Paket Langganan Gym (Gym Packages)
    Route::get('/packages', [\App\Http\Controllers\GymPackageController::class, 'index'])->name('packages.index');
    Route::post('/packages', [\App\Http\Controllers\GymPackageController::class, 'store'])->name('packages.store');
    Route::put('/packages/{gymPackage}', [\App\Http\Controllers\GymPackageController::class, 'update'])->name('packages.update');
    Route::patch('/packages/{gymPackage}/toggle', [\App\Http\Controllers\GymPackageController::class, 'toggle'])->name('packages.toggle');

    // Trainers & Classes
    Route::get('/trainers', [TrainerController::class, 'index'])->name('trainers.index');
    Route::post('/trainers', [TrainerController::class, 'storeTrainer'])->name('trainers.store');
    Route::put('/trainers/{trainer}', [TrainerController::class, 'updateTrainer'])->name('trainers.update');
    Route::delete('/trainers/{trainer}', [TrainerController::class, 'destroyTrainer'])->name('trainers.destroy');
    Route::post('/classes', [TrainerController::class, 'storeClass'])->name('classes.store');
    Route::put('/classes/{fitnessClass}', [TrainerController::class, 'updateClass'])->name('classes.update');
    Route::delete('/classes/{fitnessClass}', [TrainerController::class, 'destroyClass'])->name('classes.destroy');

    // Member Fitness & Body Measurement Assessments (Progres Kebugaran & Asesmen Trainer)
    Route::get('/assessments', [\App\Http\Controllers\MemberAssessmentController::class, 'index'])->name('assessments.index');
    Route::get('/assessments/create', [\App\Http\Controllers\MemberAssessmentController::class, 'create'])->name('assessments.create');
    Route::post('/assessments', [\App\Http\Controllers\MemberAssessmentController::class, 'store'])->name('assessments.store');
    Route::get('/assessments/{assessment}', [\App\Http\Controllers\MemberAssessmentController::class, 'show'])->name('assessments.show');
    Route::get('/assessments/{assessment}/edit', [\App\Http\Controllers\MemberAssessmentController::class, 'edit'])->name('assessments.edit');
    Route::put('/assessments/{assessment}', [\App\Http\Controllers\MemberAssessmentController::class, 'update'])->name('assessments.update');
    Route::delete('/assessments/{assessment}', [\App\Http\Controllers\MemberAssessmentController::class, 'destroy'])->name('assessments.destroy');
    Route::get('/assessments/member/{member}', [\App\Http\Controllers\MemberAssessmentController::class, 'memberHistory'])->name('assessments.member');
    Route::get('/assessments/{assessment}/print', [\App\Http\Controllers\MemberAssessmentController::class, 'printCard'])->name('assessments.print');

    // Owner Only Routes
    Route::middleware('role:owner')->group(function () {
        Route::delete('/supplements/{supplement}', [SupplementController::class, 'destroy'])->name('supplements.destroy');
        Route::delete('/packages/{gymPackage}', [\App\Http\Controllers\GymPackageController::class, 'destroy'])->name('packages.destroy');
        Route::post('/pos/orders/{order}/cancel', [PosController::class, 'cancelOrder'])->name('pos.orders.cancel');

        // User Management (Kasir & Owner)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/toggle', [UserController::class, 'toggleActive'])->name('users.toggle');

        // Financial & Profit Reports
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');

        // Activity Logs & Audit Trail (INSERT, UPDATE, DELETE & User Tracking)
        Route::get('/activities', [\App\Http\Controllers\ActivityLogController::class, 'index'])->name('activities.index');
        Route::get('/activities/{activity}', [\App\Http\Controllers\ActivityLogController::class, 'show'])->name('activities.show');

        // Application Settings (Gym Identity, Logo, Address, Contact)
        Route::get('/settings', [\App\Http\Controllers\SettingController::class, 'index'])->name('settings.index');
        Route::put('/settings', [\App\Http\Controllers\SettingController::class, 'update'])->name('settings.update');
    });
});
