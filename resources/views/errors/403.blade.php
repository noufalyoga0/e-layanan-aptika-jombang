@extends('layouts.app')

@section('title', '403 - Akses Ditolak | E-Layanan APTIKA Jombang')

@section('content')
    <div class="max-w-lg mx-auto py-16 text-center">
        <div class="card border-0 shadow-xl rounded-3xl p-10 bg-white">

            {{-- Icon --}}
            <div class="w-24 h-24 rounded-3xl mx-auto mb-6 flex items-center justify-center text-5xl"
                 style="background: linear-gradient(135deg, #fee2e2, #fca5a5); color: #dc2626;">
                <i class="fa-solid fa-shield-halved"></i>
            </div>

            {{-- Kode --}}
            <h1 class="text-7xl font-extrabold text-rose-600 mb-1" style="font-family:'Outfit',sans-serif;">403</h1>
            <h2 class="text-xl font-bold text-slate-900 mb-3">Akses Ditolak</h2>

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-100 text-amber-800 font-bold text-xs mb-5">
                <i class="fa-solid fa-triangle-exclamation"></i> Hak Akses Tidak Mencukupi
            </div>

            <p class="text-slate-500 text-sm mb-5 leading-relaxed">
                Anda tidak memiliki izin untuk mengakses halaman ini.<br>
                Halaman tersebut hanya dapat diakses oleh role tertentu.
            </p>

            @auth
                <div class="bg-slate-50 rounded-2xl p-4 mb-6 text-xs text-slate-600">
                    <p class="mb-1">Login sebagai: <strong class="text-blue-900">{{ Auth::user()->name }}</strong></p>
                    <p class="mb-0">Role aktif:
                        <span class="font-bold uppercase text-emerald-600">
                            {{ match(Auth::user()->role) {
                                'super_admin'  => 'Super Admin',
                                'admin_aptika' => 'Verifikator APTIKA',
                                'teknisi'      => 'Staf Teknisi',
                                'kabid'        => 'Kepala Bidang',
                                default        => 'Admin OPD'
                            } }}
                        </span>
                    </p>
                </div>
            @endauth

            <div class="flex gap-3 justify-center">
                <a href="{{ url()->previous() }}"
                   class="btn btn-light rounded-xl font-bold text-xs px-5 py-2.5 no-underline text-slate-600">
                    <i class="fa-solid fa-arrow-left me-1"></i> Kembali
                </a>
                <a href="{{ route('home') }}"
                   class="btn rounded-xl font-bold text-xs px-5 py-2.5 no-underline text-white border-0"
                   style="background:#0f2c59;">
                    <i class="fa-solid fa-house me-1"></i> Beranda
                </a>
            </div>
        </div>
    </div>
@endsection
