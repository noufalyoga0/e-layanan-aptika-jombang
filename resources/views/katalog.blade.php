@extends('layouts.app')

@section('title', 'Katalog Layanan - E-Layanan APTIKA Diskominfo Jombang')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-blue-950 mb-2">Katalog Layanan Digital APTIKA</h1>
        <p class="text-slate-500 text-sm">Persyaratan dokumen, mekanisme pengerjaan, dan estimasi Service Level Agreement (SLA)</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($services as $srv)
            <div class="card border-0 shadow-sm rounded-2xl p-6 bg-white">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-900 flex items-center justify-center text-3xl shrink-0">
                        <i class="fa-solid {{ $srv['icon'] }}"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="text-xl font-bold text-slate-900 mb-0">{{ $srv['name'] }}</h3>
                            <span class="badge bg-emerald-100 text-emerald-800 font-bold px-3 py-1 rounded-full text-xs">SLA: {{ $srv['sla'] }}</span>
                        </div>
                        <p class="text-sm text-slate-600 mb-4">{{ $srv['desc'] }}</p>
                        
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Dokumen Persyaratan Wajib:</h4>
                        <ul class="list-unstyled text-xs text-slate-700 mb-4 space-y-1">
                            @foreach($srv['docs'] as $doc)
                                <li class="flex items-center gap-2">
                                    <i class="fa-solid fa-file-pdf text-rose-500"></i> {{ $doc }}
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ route('pengajuan.form') }}?service={{ $srv['id'] }}" class="btn btn-emerald bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-2 rounded-xl border-0">
                            <i class="fa-solid fa-paper-plane me-1"></i> Buat Pengajuan Resmi
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
