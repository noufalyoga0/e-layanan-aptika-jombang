@extends('layouts.app')

@section('title', 'Login - E-Layanan APTIKA Pemkab Jombang')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="card border-0 shadow-xl rounded-3xl p-8 bg-white overflow-hidden">
            <div class="text-center mb-6">
                <div class="w-14 h-14 rounded-2xl bg-blue-900 text-white mx-auto flex items-center justify-center text-2xl font-bold mb-3 shadow">
                    <i class="fa-solid fa-lock"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-blue-950 mb-1">Masuk ke Portal</h2>
                <p class="text-slate-500 text-xs">Sistem Informasi Pelayanan APTIKA Diskominfo Kabupaten Jombang</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger rounded-2xl text-xs py-2 px-3 mb-4 bg-rose-100 text-rose-900 border-0 font-semibold">
                    <i class="fa-solid fa-circle-exclamation me-1"></i> {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('login.post', [], false) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Alamat Email Resmi</label>
                    <input type="email" name="email" id="login-email" class="form-control rounded-xl py-2.5 px-3 text-sm" placeholder="nama@jombangkab.go.id" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                    <input type="password" name="password" id="login-password" class="form-control rounded-xl py-2.5 px-3 text-sm" placeholder="••••••••" required>
                </div>

                <div class="d-flex justify-between items-center mb-6 text-xs">
                    <label class="form-check-label text-slate-600 font-medium">
                        <input type="checkbox" name="remember" class="form-check-input me-1 rounded"> Ingat Saya
                    </label>
                    <a href="#" class="text-blue-900 font-bold hover:underline">Lupa Password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 bg-blue-900 hover:bg-blue-800 text-white font-bold text-sm py-2.5 rounded-xl border-0 shadow">
                    <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk Sekarang
                </button>
            </form>

            <div class="mt-6 pt-6 border-top text-center text-xs text-slate-500">
                Belum punya akun OPD? <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:underline">Daftar Akun Dinas Baru</a>
            </div>
        </div>
    </div>
@endsection
