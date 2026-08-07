@extends('layouts.app')

@section('title', 'Ganti Password - E-Layanan APTIKA')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="card border-0 shadow-xl rounded-3xl p-8 bg-white">

            {{-- Icon + Header --}}
            <div class="text-center mb-6">
                <div class="w-16 h-16 rounded-2xl bg-amber-100 text-amber-600 mx-auto flex items-center justify-center text-3xl mb-3 shadow-sm">
                    <i class="fa-solid fa-key"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-blue-950 mb-1">Ganti Password</h2>
                <p class="text-slate-500 text-xs leading-relaxed">
                    Password Anda telah direset oleh Admin.<br>
                    Buat password baru sebelum melanjutkan.
                </p>
            </div>

            {{-- Info Banner --}}
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 mb-6 text-xs text-amber-800 flex items-start gap-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 mt-0.5 flex-shrink-0"></i>
                <span>Demi keamanan akun, Anda <strong>wajib mengganti password</strong> sekarang. Anda tidak dapat mengakses sistem sebelum melakukan ini.</span>
            </div>

            {{-- Form --}}
            <form action="{{ route('password.change.update', [], false) }}" method="POST">
                @csrf

                <div class="mb-4">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">
                        Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="new_password"
                        class="form-control rounded-xl py-2.5 px-3 text-sm @error('new_password') is-invalid @enderror"
                        placeholder="Minimal 8 karakter" required autofocus>
                    @error('new_password')
                        <div class="invalid-feedback text-xs">{{ $message }}</div>
                    @enderror
                    <p class="text-3xs text-slate-400 mt-1">Minimal 8 karakter. Gunakan kombinasi huruf dan angka.</p>
                </div>

                <div class="mb-6">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">
                        Konfirmasi Password Baru <span class="text-rose-500">*</span>
                    </label>
                    <input type="password" name="new_password_confirmation"
                        class="form-control rounded-xl py-2.5 px-3 text-sm"
                        placeholder="Ulangi password baru" required>
                </div>

                <button type="submit"
                    class="btn w-100 bg-blue-900 hover:bg-blue-800 text-white font-bold text-sm py-2.5 rounded-xl border-0 shadow">
                    <i class="fa-solid fa-shield-check me-1"></i> Simpan & Lanjutkan
                </button>
            </form>

            {{-- Info akun --}}
            <div class="mt-5 pt-4 border-top text-center text-xs text-slate-400">
                <i class="fa-solid fa-circle-user me-1 text-emerald-500"></i>
                Login sebagai: <strong class="text-slate-600">{{ Auth::user()->name }}</strong>
                <span class="mx-1">·</span>
                {{ Auth::user()->opd_name }}
            </div>
        </div>
    </div>
@endsection
