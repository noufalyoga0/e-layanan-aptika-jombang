@extends('layouts.app')

@section('title', 'Dashboard Super Admin - E-Layanan APTIKA')

@section('content')
    <div class="mb-6">
        <div class="bg-gradient-to-r from-blue-950 via-slate-900 to-indigo-950 rounded-3xl p-8 text-white shadow-xl relative overflow-hidden">
            <div class="absolute right-0 top-0 bottom-0 opacity-10 flex items-center pr-10 text-9xl pointer-events-none">
                <i class="fa-solid fa-code"></i>
            </div>
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 border border-emerald-500/30 text-xs font-bold mb-3">
                    <i class="fa-solid fa-shield-check"></i> Super Admin Access
                </div>
                <h1 class="text-3xl font-extrabold tracking-tight mb-2">Dashboard Super Admin</h1>
                <p class="text-slate-300 text-sm max-w-2xl mb-4">
                    Panel kontrol penuh untuk manajemen sistem E-Layanan APTIKA Diskominfo Kabupaten Jombang.
                </p>
                <div class="flex flex-wrap gap-2 text-xs font-semibold">
                    <a href="{{ route('admin.users') }}" class="btn btn-emerald bg-emerald-600 hover:bg-emerald-500 border-0 text-white px-4 py-2 rounded-xl">
                        <i class="fa-solid fa-users-gear me-1"></i> Kelola User & Akun OPD
                    </a>
                    <a href="{{ route('verifikasi') }}" class="btn btn-light bg-white/10 hover:bg-white/20 border-0 text-white px-4 py-2 rounded-xl">
                        <i class="fa-solid fa-list-check me-1"></i> Buka Meja Verifikasi
                    </a>
                    <a href="{{ route('workspace.tech') }}" class="btn btn-light bg-white/10 hover:bg-white/20 border-0 text-white px-4 py-2 rounded-xl">
                        <i class="fa-solid fa-laptop-code me-1"></i> Buka Workspace Teknisi
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Ringkasan Statistik Sistem -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="card border-0 shadow-sm rounded-2xl p-5 bg-white border-l-4 border-blue-900">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Total Permohonan</span>
            <span class="text-3xl font-extrabold text-blue-950">{{ $stats['total_tickets'] }}</span>
            <span class="text-3xs text-slate-400 block mt-1">Seluruh tiket di DB</span>
        </div>
        <div class="card border-0 shadow-sm rounded-2xl p-5 bg-white border-l-4 border-amber-500">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Menunggu Verifikasi</span>
            <span class="text-3xl font-extrabold text-amber-600">{{ $stats['menunggu'] }}</span>
            <span class="text-3xs text-slate-400 block mt-1">Antrean APTIKA</span>
        </div>
        <div class="card border-0 shadow-sm rounded-2xl p-5 bg-white border-l-4 border-sky-500">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Pengerjaan Teknisi</span>
            <span class="text-3xl font-extrabold text-sky-600">{{ $stats['diproses'] }}</span>
            <span class="text-3xs text-slate-400 block mt-1">Diproses teknisi</span>
        </div>
        <div class="card border-0 shadow-sm rounded-2xl p-5 bg-white border-l-4 border-emerald-500">
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block mb-1">Selesai & BAST</span>
            <span class="text-3xl font-extrabold text-emerald-600">{{ $stats['selesai'] }}</span>
            <span class="text-3xs text-slate-400 block mt-1">Dokumen BAST aktif</span>
        </div>
    </div>

    <!-- Tabel Pengguna Sistem (Users Summary) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 card border-0 shadow-sm rounded-3xl p-6 bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-slate-900">Daftar Akun Pengguna Sistem</h3>
                <a href="{{ route('admin.users') }}" class="text-xs font-bold text-blue-900 hover:underline">Lihat Semua / Tambah User →</a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle text-xs mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama & NIP</th>
                            <th>Instansi / Dinas</th>
                            <th>Role / Hak Akses</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($allUsers as $u)
                            <tr>
                                <td>
                                    <span class="font-bold text-slate-900 block">{{ $u->name }}</span>
                                    <span class="text-slate-400 font-mono text-3xs">{{ $u->nip ?? '-' }}</span>
                                </td>
                                <td>{{ $u->opd_name }}</td>
                                <td>
                                    <span class="badge px-2 py-1 rounded-full font-bold text-3xs 
                                        {{ match($u->role) {
                                            'super_admin'  => 'bg-purple-100 text-purple-900',
                                            'admin_aptika' => 'bg-amber-100 text-amber-900',
                                            'teknisi'      => 'bg-cyan-100 text-cyan-900',
                                            'kabid'        => 'bg-indigo-100 text-indigo-900',
                                            default        => 'bg-emerald-100 text-emerald-900'
                                        } }}">
                                        {{ strtoupper(str_replace('_', ' ', $u->role)) }}
                                    </span>
                                </td>
                                <td class="font-mono text-slate-600">{{ $u->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- System Information Widget -->
        <div class="card border-0 shadow-sm rounded-3xl p-6 bg-slate-900 text-white">
            <h3 class="text-base font-bold text-white mb-4"><i class="fa-solid fa-server text-emerald-400 me-2"></i>Status System Environment</h3>
            <div class="space-y-3 text-xs">
                <div class="flex justify-between pb-2 border-b border-slate-800">
                    <span class="text-slate-400">Framework Engine:</span>
                    <span class="font-bold text-emerald-400">Laravel 11.x</span>
                </div>
                <div class="flex justify-between pb-2 border-b border-slate-800">
                    <span class="text-slate-400">Database Connection:</span>
                    <span class="font-bold text-sky-400">MySQL (Railway Production)</span>
                </div>
                <div class="flex justify-between pb-2 border-b border-slate-800">
                    <span class="text-slate-400">PHP Version:</span>
                    <span class="font-bold text-slate-200">PHP 8.3</span>
                </div>
                <div class="flex justify-between pb-2 border-b border-slate-800">
                    <span class="text-slate-400">Auth System:</span>
                    <span class="font-bold text-amber-400">Multi-Role Middleware</span>
                </div>
                <div class="flex justify-between pb-2 border-b border-slate-800">
                    <span class="text-slate-400">Environment:</span>
                    <span class="font-bold text-emerald-300">Production</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Instansi:</span>
                    <span class="font-bold text-slate-200">Diskominfo Kab. Jombang</span>
                </div>
            </div>
        </div>
    </div>
@endsection
