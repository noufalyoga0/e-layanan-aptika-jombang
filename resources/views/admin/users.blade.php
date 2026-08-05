@extends('layouts.app')

@section('title', 'Manajemen User & Akun OPD - Control Center Super Admin')

@section('content')
    <div class="mb-6 flex justify-between items-center flex-wrap gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-blue-950 mb-1">Manajemen Pengguna System</h1>
            <p class="text-slate-500 text-sm">Kelola akun Dinas/OPD, Verifikator APTIKA, Staf Teknisi Server, dan Kepala Bidang</p>
        </div>
        <button type="button" class="btn btn-emerald bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-2.5 rounded-xl border-0 shadow" data-bs-toggle="modal" data-bs-target="#modal-tambah-user">
            <i class="fa-solid fa-user-plus me-1"></i> Tambah Akun Baru
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-2xl border-0 bg-emerald-100 text-emerald-900 font-semibold mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show rounded-2xl border-0 bg-amber-100 text-amber-900 font-semibold mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-3xl p-6 bg-white">
        <div class="table-responsive">
            <table class="table table-hover align-middle text-sm mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama & NIP Pegawai</th>
                        <th>Instansi / Dinas (OPD)</th>
                        <th>Email Login</th>
                        <th>Role / Hak Akses</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $u)
                        <tr>
                            <td class="font-bold text-slate-400 text-xs">{{ $loop->iteration }}</td>
                            <td>
                                <span class="font-bold text-slate-900 block">{{ $u->name }}</span>
                                <span class="text-slate-400 font-mono text-3xs">{{ $u->nip ?? '-' }}</span>
                            </td>
                            <td class="font-semibold text-slate-700">{{ $u->opd_name }}</td>
                            <td class="font-mono text-xs text-blue-900">{{ $u->email }}</td>
                            <td>
                                <span class="badge px-3 py-1 rounded-full font-bold text-xs 
                                    {{ match($u->role) {
                                        'super_admin'  => 'bg-purple-100 text-purple-900 border border-purple-300',
                                        'admin_aptika' => 'bg-amber-100 text-amber-900',
                                        'teknisi'      => 'bg-cyan-100 text-cyan-900',
                                        'kabid'        => 'bg-indigo-100 text-indigo-900',
                                        default        => 'bg-emerald-100 text-emerald-900'
                                    } }}">
                                    {{ match($u->role) {
                                        'super_admin'  => '🔑 Super Admin (Developer)',
                                        'admin_aptika' => '🛡️ Verifikator APTIKA',
                                        'teknisi'      => '💻 Staf Teknisi',
                                        'kabid'        => '📊 Kepala Bidang',
                                        default        => '🏢 Admin OPD'
                                    } }}
                                </span>
                            </td>
                            <td>
                                @if($u->role !== 'super_admin')
                                    <form action="{{ route('admin.users.delete', $u->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus akun {{ $u->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger bg-rose-600 border-0 rounded-lg text-xs font-bold px-3 py-1">
                                            <i class="fa-solid fa-trash-can"></i> Hapus
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 text-xs italic font-semibold">Protected</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah User Baru -->
    <div class="modal fade" id="modal-tambah-user" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-3xl border-0 shadow-xl overflow-hidden">
                <div class="modal-header bg-slate-900 text-white px-6 py-4">
                    <h5 class="modal-title font-bold text-sm">
                        <i class="fa-solid fa-user-plus me-2 text-emerald-400"></i>Tambah Akun Pengguna Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="modal-body p-6 space-y-3">
                        <div>
                            <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Nama Lengkap Pegawai</label>
                            <input type="text" name="name" class="form-control rounded-xl text-sm py-2" placeholder="Contoh: Rina Wijaya, S.STP" required>
                        </div>
                        <div>
                            <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Instansi / Dinas (OPD)</label>
                            <input type="text" name="opd_name" class="form-control rounded-xl text-sm py-2" placeholder="Contoh: Dinas Perhubungan Kab. Jombang" required>
                        </div>
                        <div>
                            <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">NIP Pegawai</label>
                            <input type="text" name="nip" class="form-control rounded-xl text-sm py-2" placeholder="1985xxxx 2010xx x xxx" required>
                        </div>
                        <div>
                            <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Role / Hak Akses</label>
                            <select name="role" class="form-select rounded-xl text-sm py-2" required>
                                <option value="opd">🏢 Admin OPD (Dinas / Kecamatan)</option>
                                <option value="admin_aptika">🛡️ Verifikator APTIKA (Diskominfo)</option>
                                <option value="teknisi">💻 Staf Teknisi Server & Jaringan</option>
                                <option value="kabid">📊 Kepala Bidang APTIKA (Executive)</option>
                            </select>
                        </div>
                        <div>
                            <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Email Kedinasan Login</label>
                            <input type="email" name="email" class="form-control rounded-xl text-sm py-2" placeholder="nama@jombangkab.go.id" required>
                        </div>
                        <div>
                            <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Password</label>
                            <input type="password" name="password" class="form-control rounded-xl text-sm py-2" placeholder="Minimal 6 karakter" value="password" required>
                        </div>
                    </div>
                    <div class="modal-footer bg-slate-50 px-6 py-3">
                        <button type="button" class="btn btn-light rounded-xl font-bold text-xs" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-emerald bg-emerald-600 text-white rounded-xl font-bold text-xs px-5 border-0">Simpan Akun</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
