<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HubController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ScheduleController;

// ─── Auth ─────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Google OAuth
    Route::get('/auth/google', [AuthController::class, 'redirectToGoogle'])->name('auth.google');
    Route::get('/auth/google/callback', [AuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Hub & Workspace ──────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/hub', [HubController::class, 'index'])->name('hub');
    Route::post('/workspace/create', [HubController::class, 'createWorkspace'])->name('workspace.create');
    Route::post('/workspace/join', [HubController::class, 'joinWorkspace'])->name('workspace.join');
    Route::get('/workspace/{id}', [DashboardController::class, 'enterWorkspace'])->name('workspace.enter');
    Route::get('/workspace/member/{id}', [DashboardController::class, 'enterMemberWorkspace'])->name('workspace.member');
});

// ─── Dashboard ────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

// ─── Products (Stock) CRUD ────────────────────────────────
Route::middleware('auth')->prefix('products')->group(function () {
    Route::post('/', [ProductController::class, 'store'])->name('products.store');
    Route::post('/{id}/update', [ProductController::class, 'update'])->name('products.update');
    Route::post('/{id}/delete', [ProductController::class, 'destroy'])->name('products.destroy');
});

// ─── Schedules (Planting) CRUD ────────────────────────────
Route::middleware('auth')->prefix('schedules')->group(function () {
    Route::post('/', [ScheduleController::class, 'store'])->name('schedules.store');
    Route::post('/{id}/update', [ScheduleController::class, 'update'])->name('schedules.update');
    Route::post('/{id}/delete', [ScheduleController::class, 'destroy'])->name('schedules.destroy');
});

// ─── Profile ──────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

// ─── Root ─────────────────────────────────────────────────
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});
