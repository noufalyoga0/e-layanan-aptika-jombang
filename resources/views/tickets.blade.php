@extends('layouts.app')

@section('title', 'Daftar Tiket - E-Layanan APTIKA Diskominfo Jombang')

@section('content')
    <div class="card border-0 shadow-sm rounded-3xl p-6 bg-white">
        <div class="d-flex justify-between items-center mb-6 flex-wrap gap-3">
            <div>
                <h2 class="text-2xl font-extrabold text-blue-950 mb-1">Daftar Tiket Pengajuan Layanan</h2>
                <p class="text-slate-500 text-xs mb-0">
                    Monitoring progres permohonan dan linimasa penyelesaian layanan
                    @if(count($tickets) > 0)
                        <span class="inline-flex items-center gap-1 ml-2 px-2 py-0.5 rounded-full bg-blue-100 text-blue-800 font-bold text-xs">
                            {{ count($tickets) }} tiket
                        </span>
                    @endif
                </p>
            </div>
            @auth
                @if(Auth::user()->role === 'opd')
                    <a href="{{ route('pengajuan.form') }}" class="btn btn-emerald bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-4 py-2 rounded-xl border-0 shadow">
                        <i class="fa-solid fa-plus me-1"></i> Buat Pengajuan Baru
                    </a>
                @endif
            @endauth
        </div>

        {{-- Search bar --}}
        <form method="GET" action="{{ route('tickets.index', [], false) }}" class="mb-4">
            <div class="flex gap-2">
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    class="form-control rounded-xl text-sm py-2 px-3"
                    placeholder="Cari nomor tiket atau nama instansi...">
                <button type="submit" class="btn bg-blue-900 text-white rounded-xl text-xs font-bold px-4 border-0 flex-shrink-0">
                    <i class="fa-solid fa-magnifying-glass me-1"></i> Cari
                </button>
                @if($search)
                    <a href="{{ route('tickets.index', [], false) }}" class="btn btn-light rounded-xl text-xs font-bold px-3 flex-shrink-0">
                        <i class="fa-solid fa-xmark me-1"></i> Reset
                    </a>
                @endif
            </div>
        </form>

        @if(count($tickets) === 0)
            <div class="text-center py-16">
                <i class="fa-solid fa-ticket-simple text-5xl text-slate-200 mb-4"></i>
                <h3 class="text-lg font-bold text-slate-500 mb-1">
                    @if($search)
                        Tidak ada tiket yang cocok dengan "{{ $search }}"
                    @else
                        Belum ada tiket pengajuan
                    @endif
                </h3>
                <p class="text-slate-400 text-sm mb-4">
                    @if($search)
                        Coba kata kunci lain atau reset pencarian.
                    @else
                        Tiket akan muncul setelah OPD mengajukan permohonan layanan.
                    @endif
                </p>
                @if($search)
                    <a href="{{ route('tickets.index', [], false) }}" class="btn bg-blue-900 text-white rounded-xl text-xs font-bold px-4 py-2 border-0">
                        <i class="fa-solid fa-xmark me-1"></i> Reset Pencarian
                    </a>
                @endif
            </div>
        @else
        <div class="table-responsive">
            <table class="table table-hover align-middle text-sm mb-0">
                <thead class="table-light text-slate-600">
                    <tr>
                        <th class="fw-bold">No. Tiket</th>
                        <th class="fw-bold">Tanggal</th>
                        <th class="fw-bold">Instansi Pemohon</th>
                        <th class="fw-bold">Layanan</th>
                        <th class="fw-bold">Status</th>
                        <th class="fw-bold">Teknisi PIC</th>
                        <th class="fw-bold">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tickets as $t)
                        <tr>
                            <td class="fw-bold text-blue-900">{{ $t['id'] }}</td>
                            <td class="text-slate-500">{{ $t['created_at'] }}</td>
                            <td class="fw-semibold text-slate-800">{{ $t['opd_name'] }}</td>
                            <td>{{ $t['service_name'] }}</td>
                            <td>
                                @if($t['status'] === 'selesai')
                                    <span class="badge bg-emerald-100 text-emerald-800 font-bold px-2.5 py-1 rounded-full text-xs">Selesai & BAST</span>
                                @elseif($t['status'] === 'diproses' || $t['status'] === 'disposisi')
                                    <span class="badge bg-sky-100 text-sky-800 font-bold px-2.5 py-1 rounded-full text-xs">Dalam Pengerjaan</span>
                                @elseif($t['status'] === 'menunggu_verifikasi')
                                    <span class="badge bg-amber-100 text-amber-800 font-bold px-2.5 py-1 rounded-full text-xs">Menunggu Verifikasi</span>
                                @else
                                    <span class="badge bg-rose-100 text-rose-800 font-bold px-2.5 py-1 rounded-full text-xs">Ditolak</span>
                                @endif
                            </td>
                            <td class="text-xs text-slate-600 font-medium">{{ $t['assigned_to'] }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-light font-bold text-xs rounded-lg border" data-bs-toggle="modal" data-bs-target="#modal-{{ Str::slug($t['id']) }}">
                                    <i class="fa-solid fa-eye me-1"></i> Detail
                                </button>
                            </td>
                        </tr>

                        <!-- Modal Detail Tiket -->
                        <div class="modal fade" id="modal-{{ Str::slug($t['id']) }}" tabindex="-1">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content rounded-3xl border-0 shadow-lg">
                                    <div class="modal-header border-bottom bg-slate-50 px-6 py-4">
                                        <h5 class="modal-title font-bold text-blue-950 text-base">Detail Tiket: {{ $t['id'] }}</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body p-6">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl mb-4 text-xs">
                                            <div>
                                                <p class="mb-1 text-slate-500">Instansi Pemohon:</p>
                                                <p class="font-bold text-slate-900 mb-2">{{ $t['opd_name'] }}</p>
                                                <p class="mb-1 text-slate-500">Kategori Layanan:</p>
                                                <p class="font-bold text-slate-900 mb-0">{{ $t['service_name'] }}</p>
                                            </div>
                                            <div>
                                                <p class="mb-1 text-slate-500">Detail / Target Spec:</p>
                                                <p class="font-bold text-blue-900 mb-2">{{ $t['detail_target'] }}</p>
                                                <p class="mb-1 text-slate-500">Teknisi PIC:</p>
                                                <p class="font-bold text-slate-900 mb-0">{{ $t['assigned_to'] }}</p>
                                            </div>
                                        </div>

                                        @if(!empty($t['attachments']))
                                            <div class="bg-amber-50 border border-amber-200 p-4 rounded-2xl mb-4">
                                                <h6 class="font-bold text-amber-900 text-xs mb-2">
                                                    <i class="fa-solid fa-paperclip me-1"></i> Dokumen Persyaratan Terlampir
                                                </h6>
                                                <div class="space-y-1.5">
                                                    @foreach($t['attachments'] as $idx => $doc)
                                                        <a href="{{ route('tickets.document', [$t['id'], $idx], false) }}"
                                                           target="_blank"
                                                           class="inline-flex items-center gap-2 text-xs text-emerald-700 hover:text-emerald-900 font-semibold hover:underline">
                                                            <i class="fa-solid {{ str_contains($doc['mime'] ?? '', 'pdf') ? 'fa-file-pdf text-rose-500' : 'fa-file-image text-sky-500' }}"></i>
                                                            {{ $doc['label'] ?? ('Dokumen ' . ($idx + 1)) }}
                                                        </a>
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif

                                        @if(isset($t['tech_result']) && $t['tech_result'] && $t['tech_result'] !== '-')
                                            <div class="bg-emerald-50 border border-emerald-200 p-4 rounded-2xl mb-4">
                                                <h6 class="font-bold text-emerald-900 text-xs mb-1"><i class="fa-solid fa-screwdriver-wrench me-1"></i> Hasil Pengerjaan Teknisi:</h6>
                                                <p class="text-xs text-emerald-800 mb-0 font-mono">{{ $t['tech_result'] }}</p>
                                            </div>
                                        @endif

                                        <h6 class="font-bold text-slate-900 text-xs mb-3">Linimasa Tracking Tiket:</h6>
                                        <div class="space-y-3">
                                            @foreach($t['logs'] as $log)
                                                <div class="flex items-start gap-3 text-xs border-l-2 border-emerald-500 pl-3 py-1">
                                                    <div class="w-2 h-2 rounded-full bg-emerald-500 mt-1 shrink-0"></div>
                                                    <div>
                                                        <p class="font-bold text-slate-900 mb-0">{{ $log['title'] }}</p>
                                                        <p class="text-slate-600 mb-0">{{ $log['desc'] }}</p>
                                                        <span class="text-slate-400 font-mono text-3xs">{{ $log['time'] }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="modal-footer border-top bg-slate-50 px-6 py-3">
                                        <button type="button" class="btn btn-secondary rounded-xl text-xs font-bold px-4 py-2" data-bs-dismiss="modal">Tutup</button>
                                        @if($t['status'] === 'selesai')
                                            <a href="{{ route('tickets.bast', $t['id']) }}" target="_blank" class="btn btn-primary bg-blue-900 hover:bg-blue-800 border-0 rounded-xl text-xs font-bold px-4 py-2">
                                                <i class="fa-solid fa-print me-1"></i> Cetak Dokumen BAST Resmi (PDF)
                                            </a>
                                        @else
                                            <button type="button" class="btn btn-light text-slate-400 border rounded-xl text-xs font-bold px-4 py-2" disabled>
                                                <i class="fa-solid fa-lock me-1"></i> BAST Terbit Setelah Selesai
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
@endsection
