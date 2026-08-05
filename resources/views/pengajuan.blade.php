@extends('layouts.app')

@section('title', 'Buat Pengajuan - E-Layanan APTIKA Diskominfo Jombang')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="card border-0 shadow-md rounded-3xl p-8 bg-white">
            <div class="text-center mb-8">
                <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-900 mx-auto flex items-center justify-center text-xl font-bold mb-3">
                    <i class="fa-solid fa-file-pen"></i>
                </div>
                <h2 class="text-2xl font-extrabold text-blue-950 mb-1">Form Pengajuan Layanan APTIKA</h2>
                <p class="text-slate-500 text-xs">Lengkapi formulir di bawah ini beserta Surat Permohonan Resmi bertanda tangan Kepala Dinas</p>
            </div>

            <form action="{{ route('pengajuan.store') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Instansi Pemohon (OPD)</label>
                    <input type="text" class="form-control rounded-xl py-2.5 px-3 text-sm bg-slate-50 font-semibold" value="{{ Auth::check() ? Auth::user()->opd_name : 'Dinas Kesehatan Kabupaten Jombang' }}" readonly>
                </div>

                <div class="mb-4">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Kategori Layanan</label>
                    <select name="service_type" class="form-select rounded-xl py-2.5 px-3 text-sm" required>
                        <option value="subdomain_hosting">Subdomain & Hosting VPS (*.jombangkab.go.id)</option>
                        <option value="tte_bsre">Sertifikat Elektronik / TTE (BSRE BSSN)</option>
                        <option value="integrasi_api">Integrasi API & SPLP Data Warehouse Jombang</option>
                        <option value="helpdesk_it">Helpdesk & Trouble Ticket IT Support</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Detail Kebutuhan / Usulan URL / NIP Pemohon</label>
                    <input type="text" name="subdomain" class="form-control rounded-xl py-2.5 px-3 text-sm" placeholder="Contoh: posyandu.jombangkab.go.id atau NIP: 1980xxxx..." required>
                </div>

                <div class="mb-4">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Catatan Tambahan & Latar Belakang</label>
                    <textarea name="notes" rows="3" class="form-control rounded-xl p-3 text-sm" placeholder="Tuliskan latar belakang pengajuan atau rincian kebutuhan spesifik..."></textarea>
                </div>

                <div class="mb-6 p-4 rounded-2xl bg-amber-50 border border-amber-200">
                    <div class="flex items-start gap-3">
                        <i class="fa-solid fa-file-arrow-up text-amber-600 text-lg mt-0.5"></i>
                        <div>
                            <h4 class="font-bold text-xs text-amber-900 mb-1">Unggah Surat Permohonan Kadin (Simulasi Upload)</h4>
                            <p class="text-xs text-amber-700 mb-2">Format PDF bertanda tangan resmi Kepala Dinas (Max 5MB)</p>
                            <input type="file" class="form-control form-control-sm rounded-lg text-xs">
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-between items-center pt-3 border-top">
                    <a href="{{ route('home') }}" class="btn btn-light rounded-xl font-bold text-xs px-4 py-2">Batal</a>
                    <button type="submit" class="btn btn-emerald bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-6 py-2.5 rounded-xl border-0 shadow">
                        <i class="fa-solid fa-paper-plane me-1"></i> Kirim Pengajuan Resmi (Laravel)
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
