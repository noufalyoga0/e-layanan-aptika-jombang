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
                <p class="text-slate-500 text-xs">Lengkapi formulir dan unggah dokumen persyaratan sesuai jenis layanan yang dimohon</p>
            </div>

            @if($errors->any())
                <div class="alert alert-danger rounded-2xl text-xs py-3 px-4 mb-6 bg-rose-100 text-rose-900 border-0 font-semibold shadow-sm">
                    <i class="fa-solid fa-circle-exclamation me-1.5 text-rose-600"></i>
                    {{ $errors->first() }}
                </div>
            @endif

            <form action="{{ route('pengajuan.store', [], false) }}" method="POST" enctype="multipart/form-data" id="form-pengajuan" onsubmit="return validateFiles(event)">
                @csrf

                {{-- Instansi Pemohon --}}
                <div class="mb-4">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Instansi Pemohon (OPD)</label>
                    <input type="text" class="form-control rounded-xl py-2.5 px-3 text-sm bg-slate-50 font-semibold"
                           value="{{ Auth::check() ? Auth::user()->opd_name : '' }}" readonly>
                </div>

                {{-- Kategori Layanan --}}
                <div class="mb-4">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Kategori Layanan</label>
                    <select name="service_type" id="service_type" class="form-select rounded-xl py-2.5 px-3 text-sm" required>
                        <option value="subdomain_hosting"   {{ request('service') === 'subdomain_hosting' ? 'selected' : '' }}>Subdomain & Hosting VPS (*.jombangkab.go.id)</option>
                        <option value="tte_bsre"            {{ request('service') === 'tte_bsre'          ? 'selected' : '' }}>Sertifikat Elektronik / TTE (BSRE BSSN)</option>
                        <option value="integrasi_api"       {{ request('service') === 'integrasi_api'     ? 'selected' : '' }}>Integrasi API & SPLP Data Warehouse Jombang</option>
                        <option value="helpdesk_it"         {{ request('service') === 'helpdesk_it'       ? 'selected' : '' }}>Helpdesk & Trouble Ticket IT Support</option>
                    </select>
                </div>

                {{-- Detail Kebutuhan (label berubah sesuai layanan) --}}
                <div class="mb-4">
                    <label id="detail_label" class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Detail Kebutuhan</label>
                    <input type="text" name="subdomain" id="detail_input" class="form-control rounded-xl py-2.5 px-3 text-sm"
                           placeholder="Contoh: posyandu.jombangkab.go.id" required>
                </div>

                {{-- Catatan Tambahan --}}
                <div class="mb-5">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Catatan Tambahan & Latar Belakang</label>
                    <textarea name="notes" rows="3" class="form-control rounded-xl p-3 text-sm"
                              placeholder="Tuliskan latar belakang pengajuan atau rincian kebutuhan spesifik..."></textarea>
                </div>

                {{-- ================================================================
                     DOKUMEN PERSYARATAN WAJIB — Berubah Dinamis Sesuai Layanan
                     ================================================================ --}}
                <div class="mb-6 p-5 rounded-2xl bg-amber-50 border border-amber-200">
                    <h4 class="font-bold text-sm text-amber-900 mb-3 flex items-center gap-2">
                        <i class="fa-solid fa-file-arrow-up text-amber-600"></i>
                        Dokumen Persyaratan Wajib
                        <span id="docs_badge" class="ml-auto text-3xs font-bold px-2 py-0.5 rounded-full bg-amber-200 text-amber-900">Simulasi Upload</span>
                    </h4>
                    <p class="text-xs text-amber-700 mb-4" id="docs_note">Unggah semua dokumen di bawah ini dalam format PDF / JPG / PNG (Max 5MB per file)</p>

                    {{-- Kontainer dokumen — diisi oleh JavaScript --}}
                    <div id="docs_container" class="space-y-3"></div>
                </div>

                <div class="d-flex justify-between items-center pt-3 border-top">
                    <a href="{{ route('katalog') }}" class="btn btn-light rounded-xl font-bold text-xs px-4 py-2">
                        <i class="fa-solid fa-arrow-left me-1"></i> Kembali ke Katalog
                    </a>
                    <button type="submit" class="btn btn-emerald bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs px-6 py-2.5 rounded-xl border-0 shadow">
                        <i class="fa-solid fa-paper-plane me-1"></i> Kirim Pengajuan Resmi
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    // ============================================================
    // DATA DOKUMEN PER LAYANAN (sesuai Katalog)
    // ============================================================
    const layananConfig = {
        subdomain_hosting: {
            detailLabel:  'Usulan URL Subdomain yang Dimohon',
            detailHolder: 'Contoh: posyandu.jombangkab.go.id atau siakad.jombangkab.go.id',
            docs: [
                { label: 'Surat Permohonan Resmi Kadin (PDF)',   icon: 'fa-file-pdf',  hint: 'Ditandatangani & distempel Kepala Dinas' },
                { label: 'Form Spesifikasi Teknis Web / App',     icon: 'fa-file-code', hint: 'Isi nama domain, deskripsi sistem, dan kebutuhan storage/RAM' },
            ]
        },
        tte_bsre: {
            detailLabel:  'NIP Pejabat ASN Pemohon TTE',
            detailHolder: 'Contoh: 19801115 200501 1 002',
            docs: [
                { label: 'Surat Rekomendasi OPD (PDF)',           icon: 'fa-file-pdf',  hint: 'Dari Kepala OPD yang merekomendasikan pejabat tersebut' },
                { label: 'Scan KTP ASN (JPG / PNG)',              icon: 'fa-id-card',   hint: 'Foto KTP yang jelas dan terbaca' },
                { label: 'SK Jabatan Terakhir (PDF)',              icon: 'fa-file-pdf',  hint: 'Surat Keputusan jabatan yang masih berlaku' },
            ]
        },
        integrasi_api: {
            detailLabel:  'Nama Sistem / Aplikasi yang Butuh Integrasi',
            detailHolder: 'Contoh: SIMRS Dinkes ingin integrasi data ke Satu Data Jombang',
            docs: [
                { label: 'Surat Pengajuan Integrasi Data (PDF)',  icon: 'fa-file-pdf',  hint: 'Ditandatangani Kepala Dinas pemohon' },
                { label: 'Dokumen Arsitektur API / Data Schema',  icon: 'fa-file-code', hint: 'ERD, flow data, atau dokumentasi endpoint API yang dibutuhkan' },
            ]
        },
        helpdesk_it: {
            detailLabel:  'Deskripsi Singkat Masalah / Kendala',
            detailHolder: 'Contoh: Website OPD tidak bisa diakses sejak pukul 08.00 WIB',
            docs: [
                { label: 'Screenshot Bukti Error / Kendala (JPG/PNG)', icon: 'fa-image',    hint: 'Tangkap layar menampilkan pesan error atau kondisi gangguan' },
                { label: 'Form Laporan Gangguan (PDF)',                  icon: 'fa-file-pdf', hint: 'Form isian laporan gangguan teknis yang sudah dilengkapi' },
            ]
        }
    };

    // ============================================================
    // FUNGSI RENDER DOKUMEN UPLOAD
    // ============================================================
    function renderDocs(serviceType) {
        const config  = layananConfig[serviceType];
        const container = document.getElementById('docs_container');
        const detailLabel  = document.getElementById('detail_label');
        const detailInput  = document.getElementById('detail_input');

        // Update label & placeholder field detail
        detailLabel.textContent  = config.detailLabel;
        detailInput.placeholder  = config.detailHolder;

        // Render upload field per dokumen
        container.innerHTML = config.docs.map((doc, idx) => `
            <div class="bg-white rounded-xl border border-amber-200 p-3">
                <div class="flex items-center gap-2 mb-1.5">
                    <i class="fa-solid ${doc.icon} text-amber-600 text-sm"></i>
                    <span class="text-xs font-bold text-slate-800">${idx + 1}. ${doc.label}</span>
                    <span class="ms-auto text-3xs text-rose-600 font-bold">WAJIB</span>
                </div>
                <p class="text-3xs text-slate-500 mb-2">${doc.hint}</p>
                <input type="file" name="doc_${idx}" id="doc_input_${idx}" class="form-control form-control-sm rounded-lg text-xs border-slate-200" required>
            </div>
        `).join('');
    }

    // ============================================================
    // VALIDASI INTEGRITAS BERKAS DOKUMEN WAJIB
    // ============================================================
    function validateFiles(e) {
        const fileInputs = document.querySelectorAll('#docs_container input[type="file"]');
        let allFilled = true;
        let missingNames = [];

        fileInputs.forEach((input, idx) => {
            if (!input.files || input.files.length === 0) {
                allFilled = false;
                input.style.border = '2px solid #e11d48';
                missingNames.push(`Dokumen ${idx + 1}`);
            } else {
                input.style.border = '1px solid #cbd5e1';
            }
        });

        if (!allFilled) {
            e.preventDefault();
            alert('⚠️ PENGIRIMAN DITOLAK SYSTEM:\n\n' + missingNames.join(', ') + ' wajib diunggah!\nSilakan pilih file dokumen PDF / JPG terlebih dahulu sebelum mengirim permohonan.');
            return false;
        }
        return true;
    }

    // ============================================================
    // EVENT LISTENER — Jalankan setiap ganti dropdown
    // ============================================================
    const selectEl = document.getElementById('service_type');
    selectEl.addEventListener('change', () => renderDocs(selectEl.value));

    // Jalankan saat halaman pertama kali dimuat
    renderDocs(selectEl.value);
    </script>
@endsection
