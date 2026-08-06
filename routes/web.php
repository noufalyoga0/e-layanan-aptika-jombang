<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\AuthController;

// ─── DEBUG ROUTE UNTUK DIAGNOSA RAILWAY ──────────────────────────────────
Route::get('/test-db', function() {
    try {
        $userCount = \App\Models\User::count();
        $users = \App\Models\User::all(['id', 'name', 'email', 'role']);
        $tickets = \App\Models\Ticket::all(['id', 'ticket_code', 'service_name', 'status', 'assigned_to']);
        $auth = \Illuminate\Support\Facades\Auth::user();
        return response()->json([
            'status' => '✅ DB DIAGNOSTIC',
            'auth_user' => $auth,
            'user_count' => $userCount,
            'users' => $users,
            'tickets' => $tickets
        ]);
    } catch (\Throwable $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/reset-db-now', function() {
    try {
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        \App\Models\TicketLog::truncate();
        \App\Models\Ticket::truncate();
        \App\Models\User::truncate();
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        $seeder = new \Database\Seeders\DatabaseSeeder();
        $seeder->run();

        \Artisan::call('view:clear');
        \Artisan::call('cache:clear');
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }

        return response()->json([
            'status' => '✅ DATABASE DEMO BERHASIL DI-RESET DAN SEEDER DIPASANG ULANG 100%!',
            'users' => \App\Models\User::all(['name', 'email', 'role']),
            'tickets' => \App\Models\Ticket::all(['ticket_code', 'service_name', 'status', 'assigned_to'])
        ]);
    } catch (\Throwable $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

Route::get('/clear-all-cache', function() {
    try {
        \Artisan::call('view:clear');
        \Artisan::call('cache:clear');
        \Artisan::call('route:clear');
        \Artisan::call('config:clear');
        if (function_exists('opcache_reset')) {
            @opcache_reset();
        }
        return response()->json([
            'status' => '✅ SEMUA CACHE TAMPILAN (VIEW), ROUTE & OPCACHE BERHASIL DIBERSIHKAN TOTAL!'
        ]);
    } catch (\Throwable $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
});

// ─── TAMBAH AKUN TEKNISI YANG HILANG (SATU KLIK) ─────────────────────────
Route::get('/add-teknisi', function() {
    $added = [];
    $exists = [];

    $teknisi = [
        ['name' => 'Budi Raharjo (Staf TTE & BSRE)',       'email' => 'budi.tte@jombangkab.go.id',       'nip' => '199104152016021002'],
        ['name' => 'Citra Dewi (Developer Integrasi API)',  'email' => 'citra.api@jombangkab.go.id',      'nip' => '199308222017042003'],
        ['name' => 'Dian Pratama (Helpdesk IT Support)',    'email' => 'dian.helpdesk@jombangkab.go.id',  'nip' => '199511102019031004'],
    ];

    foreach ($teknisi as $t) {
        $existing = \App\Models\User::where('email', $t['email'])->first();
        if ($existing) {
            $existing->update(['role' => 'teknisi', 'name' => $t['name']]);
            $exists[] = $t['email'];
        } else {
            \App\Models\User::create([
                'name'     => $t['name'],
                'email'    => $t['email'],
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role'     => 'teknisi',
                'opd_name' => 'Diskominfo Kab. Jombang',
                'nip'      => $t['nip'],
            ]);
            $added[] = $t['email'];
        }
    }

    return response()->json([
        'status'       => '✅ BERHASIL!',
        'akun_dibuat'  => $added,
        'sudah_ada'    => $exists,
        'total_users'  => \App\Models\User::count(),
        'semua_users'  => \App\Models\User::all(['name', 'email', 'role']),
    ]);
});

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

    // Reset data demo
    Route::get('/reset-demo', [TicketController::class, 'resetDemo'])->name('reset.demo');

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

    // ── Super Admin (Mahasiswa Magang UB / System Developer) ─────────────
    Route::middleware('role:super_admin')->group(function () {
        Route::get('/admin/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('admin.users');
        Route::post('/admin/users', [\App\Http\Controllers\AdminController::class, 'storeUser'])->name('admin.users.store');
        Route::delete('/admin/users/{user}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('admin.users.delete');
    });
});
