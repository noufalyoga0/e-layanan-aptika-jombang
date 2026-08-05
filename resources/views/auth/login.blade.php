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

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Alamat Email Resmi</label>
                    <input type="email" name="email" id="login-email" class="form-control rounded-xl py-2.5 px-3 text-sm" placeholder="nama@jombangkab.go.id" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-4">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Kata Sandi</label>
                    <input type="password" name="password" id="login-password" class="form-control rounded-xl py-2.5 px-3 text-sm" placeholder="••••••••" value="password" required>
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

            <!-- Quick Demo Login Presets -->
            <div class="mt-6 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                <p class="text-xs font-bold text-slate-700 mb-2 text-center"><i class="fa-solid fa-bolt text-amber-500 me-1"></i> Quick Demo Auto-Fill Login:</p>
                <div class="mb-2">
                    <button type="button" onclick="fillLogin('noufalyoga0@student.ub.ac.id')" class="btn btn-sm btn-dark w-100 bg-purple-950 text-purple-200 border-purple-800 rounded-lg text-xs py-1.5 font-bold">
                        <i class="fa-solid fa-crown text-amber-400 me-1"></i> Noufal Yoga Salsabila (Super Admin Developer)
                    </button>
                </div>
                <div class="grid grid-cols-2 gap-1.5 text-3xs font-semibold">
                    <button type="button" onclick="fillLogin('dinkes@jombangkab.go.id')" class="btn btn-sm btn-light border rounded-lg text-xs py-1">
                        <i class="fa-solid fa-building me-1 text-blue-700"></i> Admin Dinkes
                    </button>
                    <button type="button" onclick="fillLogin('aptika@jombangkab.go.id')" class="btn btn-sm btn-light border rounded-lg text-xs py-1">
                        <i class="fa-solid fa-user-shield me-1 text-amber-600"></i> Verifikator
                    </button>
                    <button type="button" onclick="fillLogin('agus.teknisi@jombangkab.go.id')" class="btn btn-sm btn-light border rounded-lg text-xs py-1">
                        <i class="fa-solid fa-laptop-code me-1 text-cyan-600"></i> Staf Teknisi
                    </button>
                    <button type="button" onclick="fillLogin('kabid.aptika@jombangkab.go.id')" class="btn btn-sm btn-light border rounded-lg text-xs py-1">
                        <i class="fa-solid fa-chart-pie me-1 text-purple-600"></i> Kabid APTIKA
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillLogin(email) {
            document.getElementById('login-email').value = email;
            document.getElementById('login-password').value = 'password';
        }
    </script>
@endsection
