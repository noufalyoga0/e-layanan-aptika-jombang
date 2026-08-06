<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController;

// ─── PUBLIC (tidak perlu login) ─────────────────────────────────────────────
Route::get('/', [LayananController::class, 'index'])->name('home');
Route::get('/katalog', [LayananController::class, 'katalog'])->name('katalog');

// ─── AUTH (hanya untuk yang belum login) ────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// Logout (perlu login)
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── SEMUA ROLE (harus login) ────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Lacak tiket & cetak BAST (semua role boleh lihat)
    Route::get('/tiket', [TicketController::class, 'index'])->name('tickets.index');
    Route::get('/tiket/{ticketCode}/bast', [TicketController::class, 'printBast'])->name('tickets.bast');
    Route::get('/tiket/{ticketCode}/dokumen/{index}', [TicketController::class, 'viewDocument'])->name('tickets.document');
    Route::get('/analytics', [LayananController::class, 'analytics'])->name('analytics');

    // ── Admin OPD: Buat Pengajuan (verifikator tidak boleh mengajukan) ───
    Route::middleware('role:opd,super_admin')->group(function () {
        Route::get('/pengajuan', [LayananController::class, 'pengajuanForm'])->name('pengajuan.form');
        Route::post('/pengajuan', [TicketController::class, 'store'])->name('pengajuan.store');
    });

    // ── Verifikator APTIKA ────────────────────────────────────────────────
    Route::middleware('role:admin_aptika')->group(function () {
        Route::get('/verifikasi', [TicketController::class, 'verifikasi'])->name('verifikasi');
        Route::post('/verifikasi/{ticketId}/approve', [TicketController::class, 'approve'])->name('verifikasi.approve');
        Route::post('/verifikasi/{ticketId}/tolak', [TicketController::class, 'tolak'])->name('verifikasi.tolak');
    });

    // ── Staf Teknisi ──────────────────────────────────────────────────────
    Route::middleware('role:teknisi,super_admin')->group(function () {
        Route::get('/workspace-tech', [TicketController::class, 'workspaceTech'])->name('workspace.tech');
    });
    Route::middleware('role:teknisi')->group(function () {
        Route::post('/workspace-tech/{ticketId}/selesai', [TicketController::class, 'selesai'])->name('workspace.selesai');
    });

    // ── Super Admin ───────────────────────────────────────────────────────
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
        Route::post('/admin/users', [\App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::delete('/admin/users/{user}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');
    });
});
