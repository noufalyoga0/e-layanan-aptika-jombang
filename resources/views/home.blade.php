@extends('layouts.app')

@section('title', 'Beranda - E-Layanan APTIKA Pemkab Jombang')

@section('content')
    <!-- Hero Banner (Tailwind + Bootstrap Styled) -->
    <div class="hero-bg text-white rounded-3xl p-8 md:p-12 shadow-xl mb-8 relative overflow-hidden">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/10 backdrop-blur border border-white/20 text-xs font-semibold text-cyan-300 mb-4">
            <i class="fa-solid fa-shield-halved"></i> SPBE Kabupaten Jombang Indeks 3,91 (Sangat Baik)
        </div>
        <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-3 text-white">
            Digitalisasi Layanan APTIKA Pemkab Jombang
        </h1>
        <p class="text-slate-300 text-lg max-w-3xl mb-8 leading-relaxed">
            Portal terpadu Laravel untuk pengajuan Subdomain, Hosting VPS, Sertifikat Elektronik TTE BSRE, Integrasi API Data, dan Helpdesk IT OPD Kabupaten Jombang.
        </p>

        <!-- Ticket Quick Search Card -->
        <div class="bg-white/10 backdrop-blur-md border border-white/20 p-6 rounded-2xl max-w-2xl">
            <h3 class="text-base font-bold text-slate-100 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-magnifying-glass text-cyan-300"></i> Lacak Status Tiket Layanan
            </h3>
            <form action="{{ route('tickets.index', [], false) }}" method="GET" class="flex flex-col sm:flex-row gap-2 mb-3">
                <input type="text" name="search" class="form-control border-0 text-slate-900 rounded-xl shadow-sm text-sm" placeholder="Masukkan Nomor Resi Tiket (REQ-JBG-...)" required>
                <button type="submit" class="btn btn-emerald px-4 bg-emerald-600 hover:bg-emerald-500 text-white font-bold rounded-xl shadow flex-shrink-0">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Lacak
                </button>
            </form>
            <div class="text-xs text-slate-300">
                <span class="opacity-70">Masukkan nomor resi tiket untuk melacak status pengajuan Anda.</span>
            </div>
        </div>
    </div>

    <!-- Quick Stats Grid (Realtime MySQL Data) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-10">
        <div class="card border-0 shadow-sm rounded-2xl p-4 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-0">{{ $stats['total'] }}</h3>
                    <p class="text-xs text-slate-500 font-semibold mb-0">Total Pengajuan 2026</p>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-2xl p-4 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-0">{{ $stats['selesai'] }}</h3>
                    <p class="text-xs text-slate-500 font-semibold mb-0">Pengajuan Selesai & BAST</p>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-2xl p-4 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-0">{{ $stats['menunggu'] + $stats['diproses'] }}</h3>
                    <p class="text-xs text-slate-500 font-semibold mb-0">Antrean & Diproses</p>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-2xl p-4 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-xl font-bold">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <div>
                    <h3 class="text-2xl font-extrabold text-slate-900 mb-0">{{ $stats['total'] > 0 ? round(($stats['selesai'] / $stats['total']) * 100) : 100 }}%</h3>
                    <p class="text-xs text-slate-500 font-semibold mb-0">Tingkat Penyelesaian</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Service Catalog Cards -->
    <div class="mb-6">
        <h2 class="text-2xl font-extrabold text-blue-950 mb-1">Katalog Layanan Digital APTIKA</h2>
        <p class="text-slate-500 text-sm mb-6">Pilih jenis layanan digital resmi dari Diskominfo Kabupaten Jombang</p>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($services as $srv)
                <div class="card border-0 shadow-sm hover:shadow-md transition rounded-2xl p-6 bg-white flex flex-col justify-between">
                    <div>
                        <div class="w-14 h-14 rounded-2xl bg-slate-100 text-blue-900 flex items-center justify-center text-2xl mb-4 font-bold">
                            <i class="fa-solid {{ $srv['icon'] }}"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">{{ $srv['name'] }}</h3>
                        <p class="text-xs text-slate-500 mb-4 leading-relaxed">{{ $srv['desc'] }}</p>
                    </div>
                    <div>
                        <div class="border-t border-slate-100 pt-3 mb-4 flex justify-between items-center text-xs text-slate-400 font-medium">
                            <span><i class="fa-regular fa-clock me-1"></i> SLA: {{ $srv['sla'] }}</span>
                        </div>
                        <a href="{{ route('pengajuan.form') }}?service={{ $srv['id'] }}" class="btn btn-primary w-100 bg-blue-900 border-0 hover:bg-blue-800 font-semibold text-xs py-2 rounded-xl">
                            <i class="fa-solid fa-paper-plane me-1"></i> Ajukan Layanan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
