@extends('layouts.app')

@section('title', 'Registrasi OPD - E-Layanan APTIKA Pemkab Jombang')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="card border-0 shadow-xl rounded-3xl p-8 bg-white">
            <div class="text-center mb-6">
                <div class="w-14 h-14 rounded-2xl bg-emerald-600 text-white mx-auto flex items-center justify-center text-2xl font-bold mb-3 shadow">
                    <i class="fa-solid fa-user-plus"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-blue-950 mb-1">Daftar Akun OPD Baru</h2>
                <p class="text-slate-500 text-xs">Registrasi khusus penanggung jawab IT Dinas / Kecamatan Jombang</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger rounded-2xl text-xs py-2 px-3 mb-4 bg-rose-100 text-rose-900 border-0 font-semibold">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Nama Lengkap Petugas</label>
                    <input type="text" name="name" class="form-control rounded-xl py-2 px-3 text-sm" placeholder="Contoh: Rina Wijaya, S.STP" value="{{ old('name') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Nama Organisasi Perangkat Daerah (OPD)</label>
                    <input type="text" name="opd_name" class="form-control rounded-xl py-2 px-3 text-sm" placeholder="Contoh: Dinas Ketahanan Pangan Kab. Jombang" value="{{ old('opd_name') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">NIP Pegawai</label>
                    <input type="text" name="nip" class="form-control rounded-xl py-2 px-3 text-sm" placeholder="1985xxxx 2010xx x xxx" value="{{ old('nip') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Email Kedinasan</label>
                    <input type="email" name="email" class="form-control rounded-xl py-2 px-3 text-sm" placeholder="dinas@jombangkab.go.id" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                    <input type="password" name="password" class="form-control rounded-xl py-2 px-3 text-sm" placeholder="Minimal 6 karakter" required>
                </div>

                <div class="mb-5">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Konfirmasi Kata Sandi</label>
                    <input type="password" name="password_confirmation" class="form-control rounded-xl py-2 px-3 text-sm" placeholder="Ulangi kata sandi" required>
                </div>

                <button type="submit" class="btn btn-emerald w-100 bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm py-2.5 rounded-xl border-0 shadow">
                    <i class="fa-solid fa-user-check me-1"></i> Buat Akun Dinas Baru
                </button>
            </form>

            <div class="mt-6 pt-4 border-top text-center text-xs text-slate-500">
                Sudah punya akun? <a href="{{ route('login') }}" class="font-bold text-blue-900 hover:underline">Masuk Sekarang</a>
            </div>
        </div>
    </div>
@endsection
