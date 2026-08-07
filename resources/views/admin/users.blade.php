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
                                        'super_admin'  => '🔑 Super Admin',
                                        'admin_aptika' => '🛡️ Verifikator APTIKA',
                                        'teknisi'      => '💻 Staf Teknisi',
                                        'kabid'        => '📊 Kepala Bidang',
                                        default        => '🏢 Admin OPD'
                                    } }}
                                </span>
                            </td>
                            <td>
                                @if($u->role !== 'super_admin')
                                    <div class="flex gap-1.5">
                                        {{-- Tombol Reset Password --}}
                                        <button type="button"
                                            class="btn btn-sm btn-warning bg-amber-500 border-0 rounded-lg text-xs font-bold px-3 py-1 text-white"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modal-reset-pw-{{ $u->id }}"
                                            title="Reset Password">
                                            <i class="fa-solid fa-key"></i>
                                        </button>
                                        {{-- Tombol Hapus --}}
                                        <form action="{{ route('admin.users.delete', $u->id, false) }}" method="POST"
                                              onsubmit="return confirm('Yakin hapus akun {{ addslashes($u->name) }}? Tindakan ini tidak bisa dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger bg-rose-600 border-0 rounded-lg text-xs font-bold px-3 py-1">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </form>
                                    </div>
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
                <form action="{{ route('admin.users.store', [], false) }}" method="POST">
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
                            <input type="text" name="nip" class="form-control rounded-xl text-sm py-2" placeholder="Contoh: 198011152005011002" inputmode="numeric" pattern="[0-9]*" data-numeric-only required>
                            <p class="text-3xs text-slate-400 mt-1 mb-0">Hanya angka, tanpa spasi atau huruf.</p>
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
                            <input type="password" name="password" class="form-control rounded-xl text-sm py-2" placeholder="Minimal 6 karakter" required>
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

    {{-- Modal Reset Password (satu per user, di-loop) --}}
    @foreach($users as $u)
        @if($u->role !== 'super_admin')
        <div class="modal fade" id="modal-reset-pw-{{ $u->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-sm">
                <div class="modal-content rounded-3xl border-0 shadow-xl overflow-hidden">
                    <div class="modal-header bg-amber-500 text-white px-6 py-4">
                        <h5 class="modal-title font-bold text-sm">
                            <i class="fa-solid fa-key me-2"></i>Reset Password
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('admin.users.password', $u->id, false) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body p-5 space-y-3">
                            <p class="text-xs text-slate-600 mb-3">
                                Reset password untuk: <strong class="text-slate-900">{{ $u->name }}</strong>
                            </p>
                            <div>
                                <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Password Baru</label>
                                <input type="password" name="new_password" class="form-control rounded-xl text-sm py-2" placeholder="Minimal 6 karakter" required>
                            </div>
                            <div>
                                <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Konfirmasi Password Baru</label>
                                <input type="password" name="new_password_confirmation" class="form-control rounded-xl text-sm py-2" placeholder="Ulangi password baru" required>
                            </div>
                        </div>
                        <div class="modal-footer bg-slate-50 px-5 py-3">
                            <button type="button" class="btn btn-light rounded-xl font-bold text-xs" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn bg-amber-500 text-white rounded-xl font-bold text-xs px-4 border-0">
                                <i class="fa-solid fa-key me-1"></i> Simpan Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif
    @endforeach
@endsection
