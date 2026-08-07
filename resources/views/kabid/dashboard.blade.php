@extends('layouts.app')

@section('title', 'Dashboard Eksekutif Kabid APTIKA - Diskominfo Jombang')

@section('content')

    {{-- Header --}}
    <div class="bg-gradient-to-r from-indigo-950 via-blue-950 to-slate-900 rounded-3xl p-8 text-white shadow-xl mb-8 relative overflow-hidden">
        <div class="absolute right-0 top-0 bottom-0 opacity-10 flex items-center pr-10 text-9xl pointer-events-none">
            <i class="fa-solid fa-gauge-high"></i>
        </div>
        <div class="relative z-10">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-bold mb-3">
                <i class="fa-solid fa-chart-pie"></i> Executive Dashboard — Kepala Bidang APTIKA
            </div>
            <h1 class="text-3xl font-extrabold tracking-tight mb-1">Selamat Datang, {{ Auth::user()->name }}</h1>
            <p class="text-slate-300 text-sm">Ringkasan kinerja pelayanan digital APTIKA Diskominfo Kabupaten Jombang</p>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="card border-0 shadow-sm rounded-2xl p-5 bg-white border-t-4 border-blue-600">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Total Tiket</span>
            </div>
            <span class="text-4xl font-extrabold text-blue-950">{{ $total }}</span>
            <span class="text-xs text-slate-400 block mt-1">Seluruh pengajuan masuk</span>
        </div>
        <div class="card border-0 shadow-sm rounded-2xl p-5 bg-white border-t-4 border-emerald-500">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Selesai & BAST</span>
            </div>
            <span class="text-4xl font-extrabold text-emerald-700">{{ $selesai }}</span>
            <span class="text-xs text-slate-400 block mt-1">BAST diterbitkan</span>
        </div>
        <div class="card border-0 shadow-sm rounded-2xl p-5 bg-white border-t-4 border-amber-500">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Pending</span>
            </div>
            <span class="text-4xl font-extrabold text-amber-600">{{ $menunggu + $diproses }}</span>
            <span class="text-xs text-slate-400 block mt-1">Verifikasi + dikerjakan</span>
        </div>
        <div class="card border-0 shadow-sm rounded-2xl p-5 bg-white border-t-4 border-purple-500">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-10 h-10 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center text-lg">
                    <i class="fa-solid fa-shield-check"></i>
                </div>
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Capaian SLA</span>
            </div>
            <span class="text-4xl font-extrabold {{ $slaPercent >= 80 ? 'text-emerald-600' : ($slaPercent >= 60 ? 'text-amber-600' : 'text-rose-600') }}">
                {{ $slaPercent }}%
            </span>
            <span class="text-xs text-slate-400 block mt-1">Penyelesaian tepat waktu</span>
        </div>
    </div>

    {{-- Row 2: Charts + Kinerja Teknisi --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- Grafik distribusi layanan --}}
        <div class="card border-0 shadow-sm rounded-3xl p-6 bg-white">
            <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-indigo-600"></i> Distribusi per Layanan
            </h3>
            <div style="height:220px;">
                <canvas id="chartLayanan"></canvas>
            </div>
        </div>

        {{-- Grafik status --}}
        <div class="card border-0 shadow-sm rounded-3xl p-6 bg-white">
            <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-bar text-blue-600"></i> Status Pengajuan
            </h3>
            <div style="height:220px;">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>

        {{-- Ringkasan indikator --}}
        <div class="card border-0 shadow-sm rounded-3xl p-6 bg-slate-900 text-white">
            <h3 class="text-base font-bold text-white mb-4 flex items-center gap-2">
                <i class="fa-solid fa-bullseye text-emerald-400"></i> Indikator Kinerja
            </h3>
            <div class="space-y-4">
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-400">Capaian SLA</span>
                        <span class="font-bold {{ $slaPercent >= 80 ? 'text-emerald-400' : 'text-amber-400' }}">{{ $slaPercent }}%</span>
                    </div>
                    <div class="w-full bg-slate-700 rounded-full h-2">
                        <div class="h-2 rounded-full {{ $slaPercent >= 80 ? 'bg-emerald-500' : ($slaPercent >= 60 ? 'bg-amber-500' : 'bg-rose-500') }}" style="width:{{ $slaPercent }}%"></div>
                    </div>
                </div>
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-slate-400">Tingkat Penyelesaian</span>
                        <span class="font-bold text-sky-400">{{ $total > 0 ? round(($selesai/$total)*100) : 0 }}%</span>
                    </div>
                    <div class="w-full bg-slate-700 rounded-full h-2">
                        <div class="h-2 rounded-full bg-sky-500" style="width:{{ $total > 0 ? round(($selesai/$total)*100) : 0 }}%"></div>
                    </div>
                </div>
                <div class="pt-2 border-t border-slate-700 space-y-2 text-xs">
                    <div class="flex justify-between">
                        <span class="text-slate-400">OPD Aktif Mengajukan</span>
                        <span class="font-bold text-white">{{ $opdAktif }} instansi</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Ditolak</span>
                        <span class="font-bold text-rose-400">{{ $ditolak }} tiket</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Menunggu Verifikasi</span>
                        <span class="font-bold text-amber-400">{{ $menunggu }} tiket</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Row 3: Kinerja Teknisi + Tiket Terlama --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

        {{-- Kinerja per teknisi --}}
        <div class="card border-0 shadow-sm rounded-3xl p-6 bg-white">
            <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-users-gear text-sky-600"></i> Kinerja Staf Teknisi
            </h3>
            @if($kinerjaTeknisi->isEmpty())
                <p class="text-slate-400 text-sm text-center py-6">Belum ada data teknisi.</p>
            @else
                <div class="space-y-3">
                    @foreach($kinerjaTeknisi as $tek)
                        <div class="flex items-center gap-3 p-3 rounded-2xl bg-slate-50">
                            <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                {{ strtoupper(substr($tek['nama'], 0, 1)) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-900 text-sm mb-0 truncate">{{ $tek['nama'] }}</p>
                                <div class="flex gap-3 text-xs mt-0.5">
                                    <span class="text-emerald-600 font-semibold"><i class="fa-solid fa-check me-1"></i>{{ $tek['selesai'] }} selesai</span>
                                    <span class="text-sky-600 font-semibold"><i class="fa-solid fa-gears me-1"></i>{{ $tek['diproses'] }} proses</span>
                                </div>
                            </div>
                            <div class="text-right">
                                @php $total_tek = $tek['selesai'] + $tek['diproses']; @endphp
                                <span class="text-xs font-bold text-slate-500">{{ $total_tek }} total</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Tiket berpotensi lewat SLA --}}
        <div class="card border-0 shadow-sm rounded-3xl p-6 bg-white">
            <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-triangle-exclamation text-amber-500"></i> Tiket Menunggu Terlama
            </h3>
            @if($tiketTerlama->isEmpty())
                <div class="text-center py-8">
                    <i class="fa-solid fa-circle-check text-4xl text-emerald-400 mb-2"></i>
                    <p class="text-slate-500 text-sm font-semibold">Semua tiket sudah ditangani!</p>
                </div>
            @else
                <div class="space-y-2">
                    @foreach($tiketTerlama as $t)
                        @php
                            $hari = now()->diffInDays($t->created_at);
                            $warna = $hari >= 3 ? 'text-rose-600' : ($hari >= 2 ? 'text-amber-600' : 'text-slate-600');
                        @endphp
                        <div class="flex items-center gap-3 p-3 rounded-2xl border border-slate-100 bg-slate-50">
                            <div class="flex-1 min-w-0">
                                <p class="font-bold text-slate-900 text-xs mb-0">{{ $t->ticket_code }}</p>
                                <p class="text-slate-500 text-xs truncate mb-0">{{ $t->opd_name }} — {{ $t->service_name }}</p>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-xs font-bold {{ $warna }}">
                                    {{ $hari }} hari
                                </span>
                                <span class="block text-3xs text-slate-400 mt-0.5">
                                    {{ match($t->status) {
                                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                                        'diproses'            => 'Dikerjakan',
                                        'disposisi'           => 'Disposisi',
                                        default               => $t->status
                                    } }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Tiket Terbaru --}}
    <div class="card border-0 shadow-sm rounded-3xl p-6 bg-white">
        <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-clock-rotate-left text-blue-600"></i> 5 Pengajuan Terbaru
        </h3>
        <div class="table-responsive">
            <table class="table table-hover align-middle text-xs mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Tiket</th>
                        <th>Instansi</th>
                        <th>Layanan</th>
                        <th>Teknisi PIC</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tiketTerbaru as $t)
                        <tr>
                            <td class="font-bold text-blue-900">{{ $t->ticket_code }}</td>
                            <td class="font-semibold text-slate-700">{{ $t->opd_name }}</td>
                            <td>{{ $t->service_name }}</td>
                            <td class="text-slate-500">{{ $t->assigned_to }}</td>
                            <td>
                                @php
                                    $badge = match($t->status) {
                                        'selesai'             => ['bg-emerald-100 text-emerald-800', 'Selesai & BAST'],
                                        'diproses','disposisi'=> ['bg-sky-100 text-sky-800', 'Dikerjakan'],
                                        'menunggu_verifikasi' => ['bg-amber-100 text-amber-800', 'Menunggu Verifikasi'],
                                        'ditolak'             => ['bg-rose-100 text-rose-800', 'Ditolak'],
                                        default               => ['bg-slate-100 text-slate-800', $t->status],
                                    };
                                @endphp
                                <span class="badge {{ $badge[0] }} font-bold px-2.5 py-1 rounded-full text-xs">{{ $badge[1] }}</span>
                            </td>
                            <td class="text-slate-400 font-mono">{{ $t->created_at->format('d/m/Y') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3 text-right">
            <a href="{{ route('tickets.index') }}" class="text-xs font-bold text-blue-900 hover:underline">
                Lihat semua tiket →
            </a>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Chart distribusi layanan
        new Chart(document.getElementById('chartLayanan'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode(array_keys($perLayanan)) !!},
                datasets: [{
                    data: {!! json_encode(array_values($perLayanan)) !!},
                    backgroundColor: ['#0f2c59','#059669','#0891b2','#d97706'],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { position: 'bottom', labels: { font: { size: 10 } } } } }
        });

        // Chart status
        new Chart(document.getElementById('chartStatus'), {
            type: 'bar',
            data: {
                labels: ['Selesai', 'Dikerjakan', 'Menunggu', 'Ditolak'],
                datasets: [{
                    label: 'Jumlah Tiket',
                    data: [{{ $selesai }}, {{ $diproses }}, {{ $menunggu }}, {{ $ditolak }}],
                    backgroundColor: ['#059669','#0284c7','#d97706','#dc2626'],
                    borderRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    });
    </script>
@endsection
