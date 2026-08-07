@extends('layouts.app')

@section('title', 'SLA & SPBE Analytics - Diskominfo Jombang')

@section('content')
    <!-- Executive Banner -->
    <div class="bg-slate-900 text-white rounded-3xl p-8 shadow-xl mb-8 flex flex-col md:flex-row justify-between items-center gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-white mb-1">Executive Dashboard SPBE & SLA APTIKA</h1>
            <p class="text-slate-400 text-xs mb-0">Statistik Kinerja Pelayanan & Indikator Sistem Pemerintahan Berbasis Elektronik Kabupaten Jombang</p>
        </div>
        <a href="{{ route('analytics.export') }}" class="btn btn-light rounded-xl font-bold text-xs px-4 py-2 bg-white text-slate-900 hover:bg-slate-100 no-underline">
            <i class="fa-solid fa-file-csv text-emerald-600 me-1"></i> Export Rekap CSV
        </a>
    </div>

    <!-- Stats Overview Cards (Realtime Database Data) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="card border-0 border-t-4 border-blue-600 shadow-sm rounded-2xl p-4 bg-white">
            <h3 class="text-2xl font-extrabold text-slate-900 mb-0">{{ $slaPercent }}%</h3>
            <p class="text-xs text-slate-500 font-semibold mb-0">Tingkat Penyelesaian SLA</p>
        </div>
        <div class="card border-0 border-t-4 border-emerald-600 shadow-sm rounded-2xl p-4 bg-white">
            <h3 class="text-2xl font-extrabold text-slate-900 mb-0">{{ $selesai }} Tiket</h3>
            <p class="text-xs text-slate-500 font-semibold mb-0">Selesai & BAST Terbit</p>
        </div>
        <div class="card border-0 border-t-4 border-purple-600 shadow-sm rounded-2xl p-4 bg-white">
            <h3 class="text-2xl font-extrabold text-slate-900 mb-0">{{ $totalOpd }} Akun OPD</h3>
            <p class="text-xs text-slate-500 font-semibold mb-0">Terdaftar di Sistem</p>
        </div>
        <div class="card border-0 border-t-4 border-amber-600 shadow-sm rounded-2xl p-4 bg-white">
            <h3 class="text-2xl font-extrabold text-slate-900 mb-0">{{ $menunggu + $diproses }} Tiket</h3>
            <p class="text-xs text-slate-500 font-semibold mb-0">Aktif Diproses/Verifikasi</p>
        </div>
    </div>

    <!-- Chart Canvas Containers -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="card border-0 shadow-sm rounded-3xl p-6 bg-white">
            <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-column text-blue-900"></i> Status Realtime Pengajuan 2026
            </h3>
            <div style="height: 260px;">
                <canvas id="laravelStatusChart"></canvas>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-3xl p-6 bg-white">
            <h3 class="text-base font-bold text-slate-900 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-chart-pie text-emerald-600"></i> Distribusi Realtime Kategori Layanan
            </h3>
            <div style="height: 260px;">
                <canvas id="laravelCategoryChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx1 = document.getElementById('laravelStatusChart').getContext('2d');
            new Chart(ctx1, {
                type: 'bar',
                data: {
                    labels: ['Selesai & BAST', 'Dalam Pengerjaan', 'Menunggu Verifikasi', 'Ditolak'],
                    datasets: [
                        {
                            label: 'Jumlah Tiket Realtime',
                            data: [{{ $selesai }}, {{ $diproses }}, {{ $menunggu }}, {{ $ditolak }}],
                            backgroundColor: ['#059669', '#0284c7', '#d97706', '#dc2626'],
                            borderRadius: 8
                        }
                    ]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });

            const ctx2 = document.getElementById('laravelCategoryChart').getContext('2d');
            new Chart(ctx2, {
                type: 'doughnut',
                data: {
                    labels: ['Subdomain & Hosting', 'TTE BSRE BSSN', 'Integrasi API', 'Helpdesk IT'],
                    datasets: [{
                        data: [
                            {{ $categoryCounts['subdomain_hosting'] }},
                            {{ $categoryCounts['tte_bsre'] }},
                            {{ $categoryCounts['integrasi_api'] }},
                            {{ $categoryCounts['helpdesk_it'] }}
                        ],
                        backgroundColor: ['#0f2c59', '#059669', '#0891b2', '#d97706']
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false }
            });
        });
    </script>
@endsection
