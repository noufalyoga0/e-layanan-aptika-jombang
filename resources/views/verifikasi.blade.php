@extends('layouts.app')

@section('title', 'Meja Verifikasi APTIKA - Diskominfo Jombang')

@section('content')
    <div class="mb-6">
        <h1 class="text-3xl font-extrabold text-blue-950 mb-1">Meja Kerja Verifikator APTIKA</h1>
        <p class="text-slate-500 text-sm">Verifikasi kelengkapan berkas OPD & disposisikan ke Staf Teknisi penanggung jawab</p>
    </div>

    {{-- Alert Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-2xl border-0 bg-emerald-100 text-emerald-900 font-semibold mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('warning'))
        <div class="alert alert-warning alert-dismissible fade show rounded-2xl border-0 bg-amber-100 text-amber-900 font-semibold mb-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-xmark me-2"></i> {{ session('warning') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(count($pendingTickets) === 0)
        <div class="card border-0 shadow-sm rounded-3xl p-12 text-center bg-white">
            <i class="fa-solid fa-circle-check text-5xl text-emerald-500 mb-4"></i>
            <h3 class="text-xl font-bold text-slate-900 mb-1">Semua Berkas Terverifikasi!</h3>
            <p class="text-slate-500 text-sm mb-0">Tidak ada antrean permohonan baru dari OPD saat ini.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($pendingTickets as $t)
                <div class="card border-0 shadow-sm rounded-3xl bg-white overflow-hidden">
                    <!-- Card Header -->
                    <div class="bg-amber-50 border-b border-amber-100 px-6 py-4 flex justify-between items-center">
                        <span class="font-extrabold text-blue-950 text-sm font-mono">{{ $t['id'] }}</span>
                        <span class="badge bg-amber-100 text-amber-800 font-bold text-xs px-3 py-1 rounded-full">
                            <i class="fa-solid fa-clock me-1"></i> Menunggu Verifikasi
                        </span>
                    </div>

                    <!-- Card Body -->
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-slate-900 mb-1">{{ $t['service_name'] }}</h3>
                        <p class="text-xs font-semibold text-slate-500 mb-4">
                            <i class="fa-solid fa-building me-1 text-slate-400"></i> {{ $t['opd_name'] }}
                        </p>

                        <div class="bg-slate-50 p-4 rounded-2xl text-xs mb-5 space-y-1.5">
                            <p class="text-slate-700"><span class="font-bold text-slate-500">Tanggal Masuk:</span> {{ $t['created_at'] }}</p>
                            <p class="text-slate-700"><span class="font-bold text-slate-500">Detail Target:</span> {{ $t['detail_target'] }}</p>
                            <p class="text-slate-700"><span class="font-bold text-slate-500">Catatan OPD:</span> {{ $t['notes'] }}</p>
                            <div class="pt-2 border-t border-slate-200 mt-2">
                                <p class="font-bold text-slate-500 mb-2">Dokumen Persyaratan:</p>
                                @if(!empty($t['attachments']))
                                    <div class="space-y-1.5">
                                        @foreach($t['attachments'] as $idx => $doc)
                                            <a href="{{ route('tickets.document', [$t['id'], $idx], false) }}"
                                               target="_blank"
                                               class="inline-flex items-center gap-2 text-emerald-700 hover:text-emerald-900 font-semibold hover:underline">
                                                <i class="fa-solid {{ str_contains($doc['mime'] ?? '', 'pdf') ? 'fa-file-pdf text-rose-500' : 'fa-file-image text-sky-500' }}"></i>
                                                {{ $doc['label'] ?? ('Dokumen ' . ($idx + 1)) }}
                                                <span class="text-3xs text-slate-400 font-normal">({{ $doc['original_name'] ?? 'file' }})</span>
                                                <i class="fa-solid fa-arrow-up-right-from-square text-3xs"></i>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-amber-700 font-semibold mb-0">
                                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                        Tidak ada dokumen tersimpan untuk pengajuan ini.
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <!-- Tombol Setujui → buka modal disposisi -->
                            <button
                                type="button"
                                class="btn btn-success flex-1 font-bold text-sm py-2.5 rounded-xl border-0 bg-emerald-600 hover:bg-emerald-500 text-white shadow-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-disp-{{ $loop->index }}">
                                <i class="fa-solid fa-check me-1"></i> Setujui & Disposisi
                            </button>

                            <!-- Tombol Tolak → buka modal tolak -->
                            <button
                                type="button"
                                class="btn btn-danger font-bold text-sm py-2.5 px-4 rounded-xl border-0 bg-rose-600 hover:bg-rose-500 text-white shadow-sm"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-tolak-{{ $loop->index }}">
                                <i class="fa-solid fa-xmark me-1"></i> Tolak
                            </button>
                        </div>
                    </div>
                </div>

                {{-- ============================================================
                     MODAL 1: Disposisi Teknisi
                ============================================================ --}}
                <div class="modal fade" id="modal-disp-{{ $loop->index }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-3xl border-0 shadow-xl overflow-hidden">
                            <div class="modal-header bg-emerald-50 border-bottom border-emerald-100 px-6 py-4">
                                <h5 class="modal-title font-bold text-emerald-900 text-base">
                                    <i class="fa-solid fa-user-check me-2"></i>Disposisi Tiket: {{ $t['id'] }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="{{ route('verifikasi.approve', $t['id'], false) }}" method="POST">
                                @csrf
                                <div class="modal-body p-6 space-y-4">
                                    <div class="bg-slate-50 rounded-2xl p-3 text-xs text-slate-600 mb-2">
                                        <p class="mb-0"><strong>Layanan:</strong> {{ $t['service_name'] }} — {{ $t['opd_name'] }}</p>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider mb-1.5">
                                            Pilih Staf Teknisi Penanggung Jawab <span class="text-rose-500">*</span>
                                        </label>
                                        <select name="teknisi" class="form-select rounded-xl text-sm py-2.5" required>
                                            <option value="" disabled selected>-- Pilih Teknisi --</option>
                                            <option value="Agus Setiawan (Teknisi Server/Hosting)">Agus Setiawan — Staf Server & Hosting</option>
                                            <option value="Budi Raharjo (Staf TTE & BSRE)">Budi Raharjo — Staf Keamanan & TTE BSRE</option>
                                            <option value="Citra Dewi (Developer Integrasi API)">Citra Dewi — Developer Integrasi API</option>
                                            <option value="Dian Pratama (Helpdesk IT Support)">Dian Pratama — Helpdesk IT Support</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider mb-1.5">
                                            Instruksi & Catatan untuk Teknisi
                                        </label>
                                        <textarea
                                            name="catatan_disp"
                                            rows="3"
                                            class="form-control rounded-xl text-sm"
                                            placeholder="Contoh: Kerjakan sesuai SOP setup subdomain, aktifkan SSL Let's Encrypt, dan laporkan IP server ke pemohon."></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer bg-slate-50 border-top px-6 py-3 gap-2">
                                    <button type="button" class="btn btn-light rounded-xl font-bold text-xs px-4 py-2" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn bg-emerald-600 text-white rounded-xl font-bold text-xs px-5 py-2 border-0 hover:bg-emerald-500">
                                        <i class="fa-solid fa-paper-plane me-1"></i> Kirim Disposisi
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- ============================================================
                     MODAL 2: Tolak Pengajuan
                ============================================================ --}}
                <div class="modal fade" id="modal-tolak-{{ $loop->index }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content rounded-3xl border-0 shadow-xl overflow-hidden">
                            <div class="modal-header bg-rose-50 border-bottom border-rose-100 px-6 py-4">
                                <h5 class="modal-title font-bold text-rose-900 text-base">
                                    <i class="fa-solid fa-circle-xmark me-2"></i>Tolak Permohonan: {{ $t['id'] }}
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>

                            <form action="{{ route('verifikasi.tolak', $t['id'], false) }}" method="POST">
                                @csrf
                                <div class="modal-body p-6">
                                    <div class="bg-rose-50 border border-rose-200 rounded-2xl p-3 text-xs text-rose-800 mb-4 font-semibold">
                                        <i class="fa-solid fa-triangle-exclamation me-1"></i>
                                        Permohonan dari <strong>{{ $t['opd_name'] }}</strong> akan ditolak dan OPD akan diberitahu.
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider mb-1.5">
                                            Alasan Penolakan <span class="text-rose-500">*</span>
                                        </label>
                                        <textarea
                                            name="alasan_tolak"
                                            rows="3"
                                            class="form-control rounded-xl text-sm"
                                            placeholder="Contoh: Surat Permohonan tidak lengkap / tidak ada tanda tangan Kepala Dinas. Silakan lengkapi dan ajukan ulang."
                                            required></textarea>
                                    </div>
                                </div>

                                <div class="modal-footer bg-slate-50 border-top px-6 py-3 gap-2">
                                    <button type="button" class="btn btn-light rounded-xl font-bold text-xs px-4 py-2" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn bg-rose-600 text-white rounded-xl font-bold text-xs px-5 py-2 border-0 hover:bg-rose-500">
                                        <i class="fa-solid fa-xmark me-1"></i> Konfirmasi Tolak
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            @endforeach
        </div>
    @endif
@endsection
