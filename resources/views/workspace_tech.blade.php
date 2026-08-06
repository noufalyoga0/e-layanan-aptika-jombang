@extends('layouts.app')

@section('title', 'Workspace Teknisi - Diskominfo Jombang')

@section('content')
@php
    $u = Auth::user();
    if ($u && !in_array($u->role, ['super_admin', 'admin_aptika', 'kabid'])) {
        $nameKey = strtolower(explode(' ', trim($u->name))[0]);
        $techTickets = array_filter($techTickets, function($t) use ($nameKey) {
            $assigned = strtolower($t['assigned_to'] ?? '');
            return str_contains($assigned, $nameKey);
        });
    }
@endphp

    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-blue-950 mb-1">Workspace Eksekusi Teknisi APTIKA</h1>
        <p class="text-slate-500 text-sm">Daftar tugas yang didisposisikan kepadamu. Kerjakan dan tandai selesai beserta hasil pengerjaan teknis.</p>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-2xl border-0 bg-emerald-100 text-emerald-900 font-semibold mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(count($techTickets) === 0)
        <div class="card border-0 shadow-sm rounded-3xl p-12 text-center bg-white">
            <i class="fa-solid fa-mug-hot text-5xl text-sky-400 mb-4"></i>
            <h3 class="text-xl font-bold text-slate-900 mb-1">Workspace Bersih!</h3>
            <p class="text-slate-500 text-sm mb-0">Tidak ada tugas pengerjaan aktif yang didisposisikan ke teknisi saat ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($techTickets as $t)
                <div class="card border-0 shadow-sm rounded-3xl bg-white overflow-hidden">
                    <!-- Card Header -->
                    <div class="bg-sky-50 border-b border-sky-100 px-6 py-4 flex justify-between items-center">
                        <span class="font-extrabold text-blue-950 text-sm font-mono">{{ $t['id'] }}</span>
                        <span class="badge bg-sky-100 text-sky-800 font-bold text-xs px-3 py-1 rounded-full">
                            <i class="fa-solid fa-gears me-1"></i> Dalam Pengerjaan
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $t['service_name'] }}</h3>
                        <p class="text-xs font-semibold text-slate-500 mb-4">
                            <i class="fa-solid fa-building me-1 text-slate-400"></i> {{ $t['opd_name'] }}
                        </p>

                        <div class="bg-slate-50 p-4 rounded-2xl text-xs mb-5 space-y-1.5">
                            <p class="text-slate-700"><span class="font-bold text-slate-500">PIC Teknisi:</span>
                                <span class="font-semibold text-sky-700">{{ $t['assigned_to'] }}</span>
                            </p>
                            <p class="text-slate-700"><span class="font-bold text-slate-500">Spesifikasi / Target:</span> {{ $t['detail_target'] }}</p>
                            <p class="text-slate-700"><span class="font-bold text-slate-500">Catatan OPD:</span> {{ $t['notes'] }}</p>
                            @if(!empty($t['disp_notes']) && $t['disp_notes'] !== '-')
                                <p class="text-blue-800 font-semibold pt-1">
                                    <i class="fa-solid fa-clipboard-list me-1"></i>
                                    <strong>Instruksi Verifikator:</strong> {{ $t['disp_notes'] }}
                                </p>
                            @endif
                            @if(!empty($t['attachments']))
                                <div class="pt-2 border-t border-slate-200 mt-2">
                                    <p class="font-bold text-slate-500 mb-1.5">Dokumen Persyaratan:</p>
                                    @foreach($t['attachments'] as $idx => $doc)
                                        <a href="{{ route('tickets.document', [$t['id'], $idx], false) }}"
                                           target="_blank"
                                           class="inline-flex items-center gap-1.5 text-emerald-700 hover:text-emerald-900 font-semibold hover:underline mb-1">
                                            <i class="fa-solid {{ str_contains($doc['mime'] ?? '', 'pdf') ? 'fa-file-pdf text-rose-500' : 'fa-file-image text-sky-500' }}"></i>
                                            {{ $doc['label'] ?? ('Dokumen ' . ($idx + 1)) }}
                                        </a><br>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <!-- Progress log mini -->
                        @if(!empty($t['logs']))
                            <div class="text-xs text-slate-500 mb-5 bg-slate-50 rounded-xl p-3">
                                <p class="font-bold text-slate-700 mb-2"><i class="fa-solid fa-route me-1"></i> Log Terakhir:</p>
                                @php $lastLog = end($t['logs']); @endphp
                                <p class="mb-0 font-semibold text-slate-700">{{ $lastLog['title'] }}</p>
                                <p class="mb-0 text-slate-500">{{ $lastLog['desc'] }}</p>
                                <span class="font-mono text-slate-400 text-3xs">{{ $lastLog['time'] }}</span>
                            </div>
                        @endif

                        <!-- Tombol Selesai → hanya untuk teknisi -->
                        @if(Auth::user()->role === 'teknisi' || Auth::user()->role === 'super_admin')
                        <button
                            type="button"
                            class="btn w-100 font-bold text-sm py-2.5 rounded-xl border-0 bg-blue-900 hover:bg-blue-800 text-white shadow"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-selesai-{{ $loop->index }}">
                            <i class="fa-solid fa-circle-check me-1"></i> Tandai Selesai & Terbitkan BAST
                        </button>
                        @else
                        <div class="text-center text-xs text-slate-400 py-2 bg-slate-50 rounded-xl">
                            <i class="fa-solid fa-eye me-1"></i> Hanya teknisi yang dapat menandai selesai
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ============================================================
                     MODAL: Isi Hasil Pengerjaan Teknisi (hanya untuk teknisi)
                ============================================================ --}}
                @if(Auth::user()->role === 'teknisi' || Auth::user()->role === 'super_admin')
                <div class="modal fade" id="modal-selesai-{{ $loop->index }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-3xl border-0 shadow-xl overflow-hidden">
                            <div class="modal-header bg-blue-50 border-bottom border-blue-100 px-6 py-4">
                                <h5 class="modal-title font-bold text-blue-900 text-base">
                                    <i class="fa-solid fa-check-double me-2"></i>Selesaikan Tiket: {{ $t['id'] }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="{{ route('workspace.selesai', $t['id'], false) }}" method="POST">
                                @csrf
                                <div class="modal-body p-6">
                                    <div class="bg-slate-50 rounded-2xl p-3 text-xs text-slate-600 mb-4">
                                        <p class="mb-0"><strong>Layanan:</strong> {{ $t['service_name'] }}</p>
                                        <p class="mb-0"><strong>Pemohon:</strong> {{ $t['opd_name'] }}</p>
                                        <p class="mb-0"><strong>Target:</strong> {{ $t['detail_target'] }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider mb-1.5">
                                            Laporan Hasil Pengerjaan Teknis <span class="text-rose-500">*</span>
                                        </label>
                                        <textarea
                                            name="tech_result"
                                            rows="4"
                                            class="form-control rounded-xl text-sm"
                                            placeholder="Contoh: Subdomain https://posyandu.jombangkab.go.id berhasil dikonfigurasi. Server IP: 103.14.22.10 (RAM 4GB, SSD 50GB). Sertifikat SSL Let's Encrypt aktif hingga 2027-08-04."
                                            required></textarea>
                                        <p class="text-xs text-slate-400 mt-1">Isi dengan detail teknis hasil pengerjaan (IP, URL, kode TTE, dsb.) untuk dicantumkan dalam Berita Acara (BAST).</p>
                                    </div>

                                    <div class="mt-3">
                                        <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider mb-1.5">
                                            Upload Bukti/Dokumentasi
                                        </label>
                                        <input type="file" class="form-control form-control-sm rounded-xl text-xs">
                                    </div>
                                </div>

                                <div class="modal-footer bg-slate-50 border-top px-6 py-3 gap-2">
                                    <button type="button" class="btn btn-light rounded-xl font-bold text-xs px-4 py-2" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn bg-blue-900 text-white rounded-xl font-bold text-xs px-5 py-2 border-0 hover:bg-blue-800">
                                        <i class="fa-solid fa-circle-check me-1"></i> Konfirmasi Selesai & Terbitkan BAST
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                @endif

            @endforeach
        </div>
    @endif
@endsection
