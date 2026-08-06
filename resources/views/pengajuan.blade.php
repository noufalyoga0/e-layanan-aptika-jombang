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
                    @if($errors->has('doc_0') || $errors->has('doc_1') || $errors->has('doc_2'))
                        <span class="block mt-1 font-normal text-rose-800">Data formulir tetap tersimpan. File dokumen perlu dipilih ulang karena batasan keamanan browser.</span>
                    @endif
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
                    @php
                        $selectedService = old('service_type', request('service', 'subdomain_hosting'));
                    @endphp
                    <select name="service_type" id="service_type" class="form-select rounded-xl py-2.5 px-3 text-sm" required>
                        <option value="subdomain_hosting"   {{ $selectedService === 'subdomain_hosting' ? 'selected' : '' }}>Subdomain & Hosting VPS (*.jombangkab.go.id)</option>
                        <option value="tte_bsre"            {{ $selectedService === 'tte_bsre'          ? 'selected' : '' }}>Sertifikat Elektronik / TTE (BSRE BSSN)</option>
                        <option value="integrasi_api"       {{ $selectedService === 'integrasi_api'     ? 'selected' : '' }}>Integrasi API & SPLP Data Warehouse Jombang</option>
                        <option value="helpdesk_it"         {{ $selectedService === 'helpdesk_it'       ? 'selected' : '' }}>Helpdesk & Trouble Ticket IT Support</option>
                    </select>
                </div>

                {{-- Detail Kebutuhan (label berubah sesuai layanan) --}}
                <div class="mb-4">
                    <label id="detail_label" class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Detail Kebutuhan</label>
                    <input type="text" name="subdomain" id="detail_input" class="form-control rounded-xl py-2.5 px-3 text-sm"
                           value="{{ old('subdomain') }}"
                           placeholder="Contoh: posyandu.jombangkab.go.id" required>
                </div>

                {{-- Catatan Tambahan --}}
                <div class="mb-5">
                    <label class="form-label font-bold text-xs text-slate-700 uppercase tracking-wider">Catatan Tambahan & Latar Belakang</label>
                    <textarea name="notes" rows="3" class="form-control rounded-xl p-3 text-sm"
                              placeholder="Tuliskan latar belakang pengajuan atau rincian kebutuhan spesifik...">{{ old('notes') }}</textarea>
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
    const ALLOWED_EXTENSIONS = ['pdf', 'jpg', 'jpeg', 'png'];
    const MAX_FILE_SIZE = 5 * 1024 * 1024; // 5 MB
    const FILE_ACCEPT = '.pdf,.jpg,.jpeg,.png,application/pdf,image/jpeg,image/png';

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
            detailHolder: 'Contoh: 198011152005011002',
            numericOnly: true,
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

    function getFileExtension(filename) {
        const parts = filename.split('.');
        return parts.length > 1 ? parts.pop().toLowerCase() : '';
    }

    function formatFileSize(bytes) {
        if (bytes >= 1024 * 1024) {
            return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
        }
        return Math.max(1, Math.round(bytes / 1024)) + ' KB';
    }

    function setFileInputState(input, state, message = '') {
        const box = input.closest('.doc-upload-box');
        const errorEl = box.querySelector('.file-error');
        const okEl = box.querySelector('.file-ok');

        input.classList.remove('border-rose-500', 'border-emerald-500', 'border-slate-200');
        errorEl.classList.add('hidden');
        okEl.classList.add('hidden');
        errorEl.textContent = '';

        if (state === 'error') {
            input.classList.add('border-rose-500');
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        } else if (state === 'ok') {
            input.classList.add('border-emerald-500');
            okEl.textContent = message;
            okEl.classList.remove('hidden');
        } else {
            input.classList.add('border-slate-200');
        }
    }

    function validateSingleFile(input, showSuccess = true) {
        if (!input.files || input.files.length === 0) {
            setFileInputState(input, 'neutral');
            return { valid: false, empty: true };
        }

        const file = input.files[0];
        const ext = getFileExtension(file.name);

        if (!ALLOWED_EXTENSIONS.includes(ext)) {
            input.value = '';
            setFileInputState(
                input,
                'error',
                `Format "${ext || 'tidak dikenal'}" tidak didukung. Gunakan PDF, JPG, JPEG, atau PNG.`
            );
            return { valid: false, empty: true };
        }

        if (file.size > MAX_FILE_SIZE) {
            input.value = '';
            setFileInputState(
                input,
                'error',
                `Ukuran file ${formatFileSize(file.size)} melebihi batas maksimal 5 MB.`
            );
            return { valid: false, empty: true };
        }

        if (showSuccess) {
            setFileInputState(
                input,
                'ok',
                `✓ ${file.name} (${formatFileSize(file.size)}) siap diunggah.`
            );
        } else {
            setFileInputState(input, 'neutral');
        }

        return { valid: true, empty: false, file };
    }

    function attachFileValidators() {
        document.querySelectorAll('#docs_container input[type="file"]').forEach(input => {
            input.addEventListener('change', () => validateSingleFile(input));
        });
    }

    // ============================================================
    // FUNGSI RENDER DOKUMEN UPLOAD
    // ============================================================
    function renderDocs(serviceType, resetDetail = false) {
        const config  = layananConfig[serviceType];
        const container = document.getElementById('docs_container');
        const detailLabel  = document.getElementById('detail_label');
        const detailInput  = document.getElementById('detail_input');

        detailLabel.textContent  = config.detailLabel;
        detailInput.placeholder  = config.detailHolder;
        if (resetDetail) {
            detailInput.value = '';
        }

        if (config.numericOnly) {
            if (typeof setNumericMode === 'function') {
                setNumericMode(detailInput, true);
            } else {
                detailInput.setAttribute('data-numeric-only', '');
                detailInput.setAttribute('inputmode', 'numeric');
                detailInput.setAttribute('pattern', '[0-9]*');
            }
        } else if (typeof setNumericMode === 'function') {
            setNumericMode(detailInput, false);
        } else {
            detailInput.removeAttribute('data-numeric-only');
            detailInput.removeAttribute('inputmode');
            detailInput.removeAttribute('pattern');
        }

        container.innerHTML = config.docs.map((doc, idx) => `
            <div class="doc-upload-box bg-white rounded-xl border border-amber-200 p-3">
                <div class="flex items-center gap-2 mb-1.5">
                    <i class="fa-solid ${doc.icon} text-amber-600 text-sm"></i>
                    <span class="text-xs font-bold text-slate-800">${idx + 1}. ${doc.label}</span>
                    <span class="ms-auto text-3xs text-rose-600 font-bold">WAJIB</span>
                </div>
                <p class="text-3xs text-slate-500 mb-2">${doc.hint}</p>
                <input type="file"
                       name="doc_${idx}"
                       id="doc_input_${idx}"
                       class="form-control form-control-sm rounded-lg text-xs border-slate-200"
                       accept="${FILE_ACCEPT}"
                       required>
                <p class="file-error hidden text-3xs text-rose-600 font-semibold mt-2"></p>
                <p class="file-ok hidden text-3xs text-emerald-700 font-semibold mt-2"></p>
            </div>
        `).join('');

        attachFileValidators();
    }

    // ============================================================
    // VALIDASI INTEGRITAS BERKAS DOKUMEN WAJIB
    // ============================================================
    function validateFiles(e) {
        const fileInputs = document.querySelectorAll('#docs_container input[type="file"]');
        let canSubmit = true;
        let firstErrorBox = null;

        fileInputs.forEach((input, idx) => {
            const result = validateSingleFile(input, true);
            const docLabel = layananConfig[selectEl.value]?.docs[idx]?.label || `Dokumen ${idx + 1}`;

            if (result.empty) {
                canSubmit = false;
                if (!firstErrorBox) {
                    firstErrorBox = input.closest('.doc-upload-box');
                }
                if (!input.files || input.files.length === 0) {
                    setFileInputState(input, 'error', `"${docLabel}" wajib diunggah.`);
                }
            } else if (!result.valid) {
                canSubmit = false;
                if (!firstErrorBox) {
                    firstErrorBox = input.closest('.doc-upload-box');
                }
            }
        });

        if (!canSubmit) {
            e.preventDefault();
            firstErrorBox?.scrollIntoView({ behavior: 'smooth', block: 'center' });
            return false;
        }

        return true;
    }

    // ============================================================
    // EVENT LISTENER — Jalankan setiap ganti dropdown
    // ============================================================
    const selectEl = document.getElementById('service_type');

    selectEl.addEventListener('change', () => renderDocs(selectEl.value, true));

    document.addEventListener('DOMContentLoaded', () => renderDocs(selectEl.value, false));
    </script>
@endsection
