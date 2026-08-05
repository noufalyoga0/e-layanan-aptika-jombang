/* ==========================================================================
   E-LAYANAN APTIKA DISKOMINFO KABUPATEN JOMBANG - APPLICATION LOGIC (JS)
   ========================================================================== */

// --------------------------------------------------------------------------
// 1. INITIAL SEED DATA & STATE
// --------------------------------------------------------------------------
const INITIAL_SERVICES = [
    {
        id: 'subdomain_hosting',
        name: 'Subdomain & Hosting VPS',
        icon: 'fa-server',
        sla: '2 Hari Kerja',
        desc: 'Permohonan alamat domain resmi (`dinas.jombangkab.go.id`) dan alokasi resource server VPS/Hosting.',
        docs: ['Surat Permohonan Resmi Kadin (PDF)', 'Form Spesifikasi Teknis Web/App']
    },
    {
        id: 'tte_bsre',
        name: 'Sertifikat Elektronik / TTE',
        icon: 'fa-signature',
        sla: '1-2 Hari Kerja',
        desc: 'Penerbitan Tanda Tangan Elektronik terintegrasi Balai Sertifikasi Elektronik (BSRE BSSN) untuk pejabat ASN.',
        docs: ['Surat Rekomendasi OPD (PDF)', 'Scan KTP ASN', 'SK Jabatan Terakhir']
    },
    {
        id: 'integrasi_api',
        name: 'Integrasi API & SPLP Data',
        icon: 'fa-code-branch',
        sla: '3 Hari Kerja',
        desc: 'Permohonan token API Sistem Penghubung Layanan Pemerintah (SPLP) & Interoperabilitas Satu Data Jombang.',
        docs: ['Surat Pengajuan Integrasi Data', 'Dokumen Arsitektur API / Data Schema']
    },
    {
        id: 'helpdesk_it',
        name: 'Helpdesk & Trouble Ticket IT',
        icon: 'fa-headset',
        sla: '1 Hari Kerja (Fast Response)',
        desc: 'Bantuan penanganan masalah teknis website OPD, Server Down, dan kendala Jaringan Intra Pemkab.',
        docs: ['Screenshot Bukti Error / Kendala', 'Form Laporan Gangguan']
    }
];

const INITIAL_TICKETS = [
    {
        id: 'REQ-JBG-202608-001',
        created_at: '2026-08-01 09:30',
        opd_name: 'Dinas Kesehatan Kab. Jombang',
        service_id: 'subdomain_hosting',
        service_name: 'Subdomain & Hosting VPS',
        detail_target: 'posyandu.jombangkab.go.id',
        status: 'selesai', // menunggu_verifikasi, disposisi, diproses, selesai, ditolak
        priority: 'Tinggi',
        notes: 'Dibutuhkan untuk aplikasi pemantauan stunting desa di Kabupaten Jombang.',
        assigned_to: 'Agus Setiawan (Teknisi Server)',
        tech_result: 'Subdomain https://posyandu.jombangkab.go.id aktif. Server IP: 103.14.22.10 (RAM 4GB, SSD 50GB). SSL Active.',
        logs: [
            { time: '2026-08-01 09:30', title: 'Permohonan Dikirim', desc: 'Diajukan oleh Admin Dinas Kesehatan' },
            { time: '2026-08-01 11:15', title: 'Terverifikasi APTIKA', desc: 'Dokumen lengkap oleh Verifikator APTIKA' },
            { time: '2026-08-01 13:00', title: 'Disposisi Teknisi', desc: 'Ditugaskan ke Agus Setiawan (Teknisi Server)' },
            { time: '2026-08-02 10:20', title: 'Selesai & BAST Terbit', desc: 'Konfigurasi server & subdomain berhasil' }
        ]
    },
    {
        id: 'REQ-JBG-202608-002',
        created_at: '2026-08-03 10:15',
        opd_name: 'Dinas Pendidikan & Kebudayaan',
        service_id: 'tte_bsre',
        service_name: 'Sertifikat Elektronik / TTE',
        detail_target: 'NIP: 19780512 200501 1 003 (Kepala Dinas)',
        status: 'diproses',
        priority: 'Sangat Tinggi',
        notes: 'Penerbitan Sertifikat Elektronik BSRE untuk penandatanganan ijazah dan SK Guru.',
        assigned_to: 'Budi Raharjo (Staf TTE & BSRE)',
        tech_result: 'Pendaftaran ke portal BSRE BSSN dalam verifikasi NIK/NIP.',
        logs: [
            { time: '2026-08-03 10:15', title: 'Permohonan Dikirim', desc: 'Diajukan oleh Admin Disdik' },
            { time: '2026-08-03 14:00', title: 'Disposisi Teknisi', desc: 'Ditugaskan ke Budi Raharjo' },
            { time: '2026-08-04 08:30', title: 'Pengerjaan BSRE', desc: 'Verifikasi NIP & NIK di sistem BSRE BSSN' }
        ]
    },
    {
        id: 'REQ-JBG-202608-003',
        created_at: '2026-08-04 08:45',
        opd_name: 'Dinas Koperasi & UMKM',
        service_id: 'integrasi_api',
        service_name: 'Integrasi API & SPLP Data',
        detail_target: 'API Data Pelaku UMKM Jombang',
        status: 'menunggu_verifikasi',
        priority: 'Sedang',
        notes: 'Permohonan integrasi data UMKM dengan Portal Jombang Kita Smart City.',
        assigned_to: 'Belum Didisposisi',
        tech_result: '-',
        logs: [
            { time: '2026-08-04 08:45', title: 'Permohonan Dikirim', desc: 'Menunggu verifikasi berkas oleh Admin APTIKA' }
        ]
    },
    {
        id: 'REQ-JBG-202608-004',
        created_at: '2026-08-04 11:20',
        opd_name: 'Kecamatan Ploso Kabupaten Jombang',
        service_id: 'helpdesk_it',
        service_name: 'Helpdesk & Trouble Ticket IT',
        detail_target: 'Koneksi Jaringan Intra Kantor Camat Down',
        status: 'menunggu_verifikasi',
        priority: 'Tinggi',
        notes: 'Koneksi fiber optic ke aplikasi PATEN Kecamatan RPU terputus sejak tadi pagi.',
        assigned_to: 'Belum Didisposisi',
        tech_result: '-',
        logs: [
            { time: '2026-08-04 11:20', title: 'Permohonan Dikirim', desc: 'Menunggu penanganan cepat tim jaringan' }
        ]
    }
];

// App Global State
let appState = {
    currentRole: 'guest', // guest, opd, admin_aptika, teknisi, kabid
    currentView: 'home',
    tickets: [],
    selectedServiceForForm: 'subdomain_hosting',
    currentWizardStep: 1,
    chartsRendered: false
};

// --------------------------------------------------------------------------
// 2. INITIALIZATION & LOCALSTORAGE MANAGEMENT
// --------------------------------------------------------------------------
document.addEventListener('DOMContentLoaded', () => {
    loadStateFromStorage();
    renderServicesList();
    renderTicketsTable();
    renderVerificationQueue();
    renderTechWorkspace();
    updateBadges();
    updateFormFields();
});

function loadStateFromStorage() {
    const savedTickets = localStorage.getItem('aptika_jombang_tickets');
    if (savedTickets) {
        appState.tickets = JSON.parse(savedTickets);
    } else {
        appState.tickets = INITIAL_TICKETS;
        saveStateToStorage();
    }
}

function saveStateToStorage() {
    localStorage.setItem('aptika_jombang_tickets', JSON.stringify(appState.tickets));
    updateBadges();
}


// --------------------------------------------------------------------------
// 3. ROLE & VIEW SWITCHER LOGIC
// --------------------------------------------------------------------------
function switchRole(role) {
    appState.currentRole = role;
    
    // Update top role buttons UI
    document.querySelectorAll('.role-btn').forEach(btn => {
        btn.classList.toggle('active', btn.dataset.role === role);
    });

    // Update Header Profile Badge & Display Name
    const avatarEl = document.getElementById('user-avatar');
    const nameEl = document.getElementById('user-display-name');
    const roleTagEl = document.getElementById('user-role-tag');

    const roleConfig = {
        guest: { avatar: 'T', name: 'Masyarakat / Tamu', tag: 'Publik (Lacak Resi)', defaultView: 'home' },
        opd: { avatar: 'DK', name: 'Admin Dinas Kesehatan', tag: 'Dinas / OPD Pemkab', defaultView: 'pengajuan-baru' },
        admin_aptika: { avatar: 'VA', name: 'Eko Prasetyo, S.STP', tag: 'Verifikator APTIKA', defaultView: 'verifikasi' },
        teknisi: { avatar: 'AS', name: 'Agus Setiawan', tag: 'Staf Teknisi Server/Subdomain', defaultView: 'workspace-tech' },
        kabid: { avatar: 'KB', name: 'Drs. Bambang H., M.Si', tag: 'Kepala Bidang APTIKA', defaultView: 'analytics' }
    };

    const config = roleConfig[role];
    avatarEl.innerText = config.avatar;
    nameEl.innerText = config.name;
    roleTagEl.innerText = config.tag;

    // Body role class attribute for CSS conditional visibility
    document.body.className = `role-${role}`;

    // Show toast message
    showToast(`Beralih ke Mode: ${config.tag}`, 'info');

    // Switch to appropriate default view for role
    switchView(config.defaultView);
}

function switchView(viewId) {
    appState.currentView = viewId;

    // Toggle navigation link active status
    document.querySelectorAll('.nav-item').forEach(nav => {
        nav.classList.toggle('active', nav.dataset.view === viewId);
    });

    // Toggle active view section
    document.querySelectorAll('.view-section').forEach(sec => {
        sec.classList.toggle('active', sec.id === `view-${viewId}`);
    });

    // Lazy load Analytics Charts if on analytics view
    if (viewId === 'analytics' && !appState.chartsRendered) {
        setTimeout(initCharts, 100);
    }
}


// --------------------------------------------------------------------------
// 4. DYNAMIC RENDER FUNCTIONS (SERVICES & TABLES)
// --------------------------------------------------------------------------
function renderServicesList() {
    const homeContainer = document.getElementById('home-services-list');
    const fullContainer = document.getElementById('full-services-list');

    const servicesHtml = INITIAL_SERVICES.map(srv => `
        <div class="service-card">
            <div class="service-icon">
                <i class="fa-solid ${srv.icon}"></i>
            </div>
            <h3>${srv.name}</h3>
            <p>${srv.desc}</p>
            <div class="service-meta">
                <span><i class="fa-regular fa-clock"></i> SLA: ${srv.sla}</span>
                <span><i class="fa-solid fa-file-shield"></i> Standard SPBE</span>
            </div>
            <button class="btn btn-primary btn-sm" onclick="selectServiceForSubmission('${srv.id}')">
                <i class="fa-solid fa-paper-plane"></i> Ajukan Layanan Ini
            </button>
        </div>
    `).join('');

    if (homeContainer) homeContainer.innerHTML = servicesHtml;
    if (fullContainer) fullContainer.innerHTML = servicesHtml;
}

function selectServiceForSubmission(serviceId) {
    switchRole('opd'); // Auto switch to OPD mode
    switchView('pengajuan-baru');
    
    // Check radio button
    const radio = document.querySelector(`input[name="service_type"][value="${serviceId}"]`);
    if (radio) {
        radio.checked = true;
        updateFormFields();
    }
}

function renderTicketsTable() {
    const tbody = document.getElementById('tickets-table-body');
    const filterStatus = document.getElementById('filter-status').value;

    let filtered = appState.tickets;
    if (filterStatus !== 'all') {
        filtered = appState.tickets.filter(t => t.status === filterStatus);
    }

    if (filtered.length === 0) {
        tbody.innerHTML = `<tr><td colspan="7" style="text-align:center; padding:30px; color:#94a3b8;">Belum ada pengajuan dengan status ini.</td></tr>`;
        return;
    }

    tbody.innerHTML = filtered.map(t => `
        <tr>
            <td><strong style="color:var(--primary);">${t.id}</strong></td>
            <td>${t.created_at}</td>
            <td><strong>${t.opd_name}</strong></td>
            <td>${t.service_name}</td>
            <td><span class="badge-status badge-${t.status}">${getStatusLabel(t.status)}</span></td>
            <td>${t.assigned_to}</td>
            <td>
                <button class="btn btn-secondary btn-sm" onclick="openTicketDetailModal('${t.id}')">
                    <i class="fa-solid fa-eye"></i> Detail
                </button>
            </td>
        </tr>
    `).join('');
}


// --------------------------------------------------------------------------
// 5. QUEUES & WORKSPACE (ADMIN & TEKNISI)
// --------------------------------------------------------------------------
function renderVerificationQueue() {
    const container = document.getElementById('verification-cards-container');
    const pendingTickets = appState.tickets.filter(t => t.status === 'menunggu_verifikasi');

    if (pendingTickets.length === 0) {
        container.innerHTML = `<div style="grid-column:1/-1; background:white; padding:40px; text-align:center; border-radius:12px; color:#94a3b8; border:1px solid #e2e8f0;">
            <i class="fa-solid fa-circle-check" style="font-size:2.5rem; color:#10b981; margin-bottom:12px;"></i>
            <h3>Semua Berkas Terverifikasi!</h3>
            <p>Tidak ada antrean permohonan baru dari OPD saat ini.</p>
        </div>`;
        return;
    }

    container.innerHTML = pendingTickets.map(t => `
        <div class="task-card">
            <div class="task-card-header">
                <span class="task-code">${t.id}</span>
                <span class="badge-status badge-${t.status}">Menunggu Verifikasi</span>
            </div>
            <h4>${t.service_name}</h4>
            <div class="task-opd"><i class="fa-solid fa-building"></i> ${t.opd_name}</div>
            
            <div class="task-details-list">
                <p><strong>Target:</strong> ${t.detail_target}</p>
                <p><strong>Catatan:</strong> ${t.notes}</p>
                <p style="color:#059669; font-weight:600;"><i class="fa-solid fa-file-pdf"></i> Surat Permohonan Kadin (Attached)</p>
            </div>

            <div class="task-actions">
                <button class="btn btn-success btn-sm" style="flex:1;" onclick="openDispositionModal('${t.id}')">
                    <i class="fa-solid fa-check"></i> Setujui & Disposisi
                </button>
                <button class="btn btn-danger btn-sm" onclick="rejectTicket('${t.id}')">
                    <i class="fa-solid fa-xmark"></i> Tolak
                </button>
            </div>
        </div>
    `).join('');
}

function renderTechWorkspace() {
    const container = document.getElementById('tech-cards-container');
    const techTickets = appState.tickets.filter(t => t.status === 'disposisi' || t.status === 'diproses');

    if (techTickets.length === 0) {
        container.innerHTML = `<div style="grid-column:1/-1; background:white; padding:40px; text-align:center; border-radius:12px; color:#94a3b8; border:1px solid #e2e8f0;">
            <i class="fa-solid fa-mug-hot" style="font-size:2.5rem; color:#0284c7; margin-bottom:12px;"></i>
            <h3>Workspace Bersih</h3>
            <p>Tidak ada tugas pengerjaan aktif yang didisposisikan ke teknisi saat ini.</p>
        </div>`;
        return;
    }

    container.innerHTML = techTickets.map(t => `
        <div class="task-card">
            <div class="task-card-header">
                <span class="task-code">${t.id}</span>
                <span class="badge-status badge-${t.status}">${getStatusLabel(t.status)}</span>
            </div>
            <h4>${t.service_name}</h4>
            <div class="task-opd"><i class="fa-solid fa-building"></i> ${t.opd_name}</div>
            
            <div class="task-details-list">
                <p><strong>PIC Teknisi:</strong> ${t.assigned_to}</p>
                <p><strong>Instruksi Disposisi:</strong> ${t.disp_notes || 'Pengerjaan sesuai standar SOP APTIKA.'}</p>
                <p><strong>Target Spec:</strong> ${t.detail_target}</p>
            </div>

            <div class="task-actions">
                <button class="btn btn-primary btn-sm" style="flex:1;" onclick="openResolveModal('${t.id}')">
                    <i class="fa-solid fa-circle-check"></i> Selesaikan Pengerjaan
                </button>
            </div>
        </div>
    `).join('');
}


// --------------------------------------------------------------------------
// 6. FORM WIZARD & SUBMISSION LOGIC
// --------------------------------------------------------------------------
function updateFormFields() {
    const selectedServiceId = document.querySelector('input[name="service_type"]:checked').value;
    appState.selectedServiceForForm = selectedServiceId;
    
    const container = document.getElementById('dynamic-fields-container');
    const reqContainer = document.getElementById('upload-requirements-container');
    const titleEl = document.getElementById('step2-title');

    let fieldsHtml = '';
    let reqHtml = '';

    if (selectedServiceId === 'subdomain_hosting') {
        titleEl.innerText = 'Spesifikasi Subdomain & VPS Hosting';
        fieldsHtml = `
            <div class="form-group">
                <label>Usulan Nama Subdomain (harus *.jombangkab.go.id):</label>
                <input type="text" id="field-subdomain" class="form-control" placeholder="contoh: posyandu.jombangkab.go.id" required>
            </div>
            <div class="form-group">
                <label>Kebutuhan Resource Server (RAM / Storage):</label>
                <select id="field-specs" class="form-control">
                    <option>Standard Web (RAM 2GB, Storage 25GB SSD)</option>
                    <option>Medium Web App (RAM 4GB, Storage 50GB SSD)</option>
                    <option>High Performance DB (RAM 8GB, Storage 100GB SSD)</option>
                </select>
            </div>
        `;
        reqHtml = `
            <div class="upload-box">
                <div class="upload-info">
                    <h5>Surat Permohonan Resmi Kepala Dinas / OPD</h5>
                    <p>Format PDF bertanda tangan resmi & stempel (Max 5MB)</p>
                </div>
                <input type="file" class="form-control" style="width:250px;" required>
            </div>
            <div class="upload-box">
                <div class="upload-info">
                    <h5>Form Arsitektur Web / Aplikasi</h5>
                    <p>File dokumen spesifikasi tech stack web (Max 5MB)</p>
                </div>
                <input type="file" class="form-control" style="width:250px;">
            </div>
        `;
    } else if (selectedServiceId === 'tte_bsre') {
        titleEl.innerText = 'Data Pemohon Sertifikat Elektronik ASN';
        fieldsHtml = `
            <div class="form-group">
                <label>Nama Lengkap ASN & Gelar:</label>
                <input type="text" id="field-asn-name" class="form-control" placeholder="Dr. H. Ahmad Fauzi, M.Si" required>
            </div>
            <div class="form-group">
                <label>NIP (Nomor Induk Pegawai):</label>
                <input type="text" id="field-nip" class="form-control" placeholder="1980xxxx 2005xx x xxx" required>
            </div>
            <div class="form-group">
                <label>NIK KTP:</label>
                <input type="text" id="field-nik" class="form-control" placeholder="3517xxxxxxxxxxxx" required>
            </div>
            <div class="form-group">
                <label>Jabatan Struktural / Fungsional:</label>
                <input type="text" id="field-jabatan" class="form-control" placeholder="Kepala Dinas / Kabid" required>
            </div>
        `;
        reqHtml = `
            <div class="upload-box">
                <div class="upload-info">
                    <h5>Scan KTP Asli ASN</h5>
                    <p>Format JPG/PNG/PDF (Harus Terbaca Jelas)</p>
                </div>
                <input type="file" class="form-control" style="width:250px;" required>
            </div>
            <div class="upload-box">
                <div class="upload-info">
                    <h5>Surat Rekomendasi Penerbitan TTE</h5>
                    <p>Dokumen Resmi bertanda tangan atasan langsung</p>
                </div>
                <input type="file" class="form-control" style="width:250px;" required>
            </div>
        `;
    } else {
        titleEl.innerText = 'Detail Permohonan Integrasi / Helpdesk';
        fieldsHtml = `
            <div class="form-group">
                <label>Judul Permohonan / Masalah Teknis:</label>
                <input type="text" id="field-title" class="form-control" placeholder="Misal: Integrasi Data Stunting dengan Satu Data Jombang" required>
            </div>
            <div class="form-group">
                <label>Tingkat Urgensi (Priority):</label>
                <select id="field-priority" class="form-control">
                    <option>Normal (SLA Standard)</option>
                    <option>Tinggi (Dibutuhkan segara)</option>
                    <option>Sangat Tinggi (Emergency / Down)</option>
                </select>
            </div>
        `;
        reqHtml = `
            <div class="upload-box">
                <div class="upload-info">
                    <h5>Lampiran Surat / Screenshot Bukti</h5>
                    <p>Format PDF/JPG (Max 5MB)</p>
                </div>
                <input type="file" class="form-control" style="width:250px;" required>
            </div>
        `;
    }

    container.innerHTML = fieldsHtml;
    reqContainer.innerHTML = reqHtml;
}

function goToStep(stepNumber) {
    appState.currentWizardStep = stepNumber;

    // Toggle step contents
    for (let i = 1; i <= 4; i++) {
        const stepEl = document.getElementById(`step-${i}`);
        const nodeEl = document.getElementById(`step-node-${i}`);
        
        stepEl.classList.toggle('active', i === stepNumber);
        nodeEl.classList.toggle('active', i <= stepNumber);
    }

    // Render Step 4 Summary Card
    if (stepNumber === 4) {
        renderFormSummary();
    }
}

function renderFormSummary() {
    const serviceObj = INITIAL_SERVICES.find(s => s.id === appState.selectedServiceForForm);
    const summaryContainer = document.getElementById('summary-container');
    const notes = document.getElementById('field-notes').value || 'Tidak ada catatan tambahan.';

    let targetDetail = '';
    if (appState.selectedServiceForForm === 'subdomain_hosting') {
        const sub = document.getElementById('field-subdomain')?.value || 'posyandu.jombangkab.go.id';
        const spec = document.getElementById('field-specs')?.value || 'Standard';
        targetDetail = `Subdomain: <strong>${sub}</strong> (${spec})`;
    } else if (appState.selectedServiceForForm === 'tte_bsre') {
        const name = document.getElementById('field-asn-name')?.value || 'ASN Pemkab';
        const nip = document.getElementById('field-nip')?.value || '-';
        targetDetail = `Pemohon TTE: <strong>${name}</strong> (NIP: ${nip})`;
    } else {
        targetDetail = document.getElementById('field-title')?.value || 'Permohonan Layanan IT';
    }

    summaryContainer.innerHTML = `
        <div style="background:#f8fafc; padding:20px; border-radius:12px; border:1px solid #e2e8f0;">
            <p><strong>Instansi Pengaju:</strong> Dinas Kesehatan Kabupaten Jombang</p>
            <p><strong>Kategori Layanan:</strong> ${serviceObj.name}</p>
            <p><strong>Detail Kebutuhan:</strong> ${targetDetail}</p>
            <p><strong>Catatan:</strong> ${notes}</p>
            <p><strong>Status Berkas Upload:</strong> <span style="color:#059669; font-weight:700;"><i class="fa-solid fa-check"></i> Dokumen Siap Ditinjau</span></p>
        </div>
    `;
}

function handleFormSubmit(event) {
    event.preventDefault();

    // Generate New Ticket ID
    const newId = `REQ-JBG-${new Date().getFullYear()}${String(new Date().getMonth() + 1).padStart(2, '0')}-${String(appState.tickets.length + 1).padStart(3, '0')}`;
    const serviceObj = INITIAL_SERVICES.find(s => s.id === appState.selectedServiceForForm);

    let targetDetail = '';
    if (appState.selectedServiceForForm === 'subdomain_hosting') {
        targetDetail = document.getElementById('field-subdomain')?.value || 'baru.jombangkab.go.id';
    } else if (appState.selectedServiceForForm === 'tte_bsre') {
        targetDetail = `ASN: ${document.getElementById('field-asn-name')?.value || 'ASN'} (NIP: ${document.getElementById('field-nip')?.value || '-'})`;
    } else {
        targetDetail = document.getElementById('field-title')?.value || 'Pengajuan Layanan';
    }

    const nowStr = new Date().toISOString().replace('T', ' ').substring(0, 16);

    const newTicket = {
        id: newId,
        created_at: nowStr,
        opd_name: 'Dinas Kesehatan Kab. Jombang',
        service_id: appState.selectedServiceForForm,
        service_name: serviceObj.name,
        detail_target: targetDetail,
        status: 'menunggu_verifikasi',
        priority: 'Normal',
        notes: document.getElementById('field-notes').value || 'Pengajuan resmi via Portal E-Layanan.',
        assigned_to: 'Belum Didisposisi',
        tech_result: '-',
        logs: [
            { time: nowStr, title: 'Permohonan Dikirim', desc: 'Diajukan resmi via Portal E-Layanan APTIKA' }
        ]
    };

    appState.tickets.unshift(newTicket);
    saveStateToStorage();

    showToast(`Pengajuan Berhasil! Kode Tiket: ${newId}`, 'success');

    // Reset Form & Switch to Tickets View
    goToStep(1);
    document.getElementById('submission-form').reset();
    renderTicketsTable();
    renderVerificationQueue();
    switchView('my-tickets');
}


// --------------------------------------------------------------------------
// 7. ACTIONS (DISPOSITION, RESOLVE, TRACKING)
// --------------------------------------------------------------------------
function openDispositionModal(ticketId) {
    document.getElementById('disp-ticket-id').value = ticketId;
    openModal('modal-disposition');
}

function handleSaveDisposition(event) {
    event.preventDefault();
    const ticketId = document.getElementById('disp-ticket-id').value;
    const techName = document.getElementById('disp-tech-select').value;
    const notes = document.getElementById('disp-notes').value;

    const ticket = appState.tickets.find(t => t.id === ticketId);
    if (ticket) {
        ticket.status = 'disposisi';
        ticket.assigned_to = techName;
        ticket.disp_notes = notes;
        ticket.logs.push({
            time: new Date().toISOString().replace('T', ' ').substring(0, 16),
            title: 'Disposisi Terbit',
            desc: `Ditugaskan ke ${techName}. Instruksi: ${notes}`
        });

        saveStateToStorage();
        renderTicketsTable();
        renderVerificationQueue();
        renderTechWorkspace();
        closeModal('modal-disposition');
        showToast(`Disposisi Tiket ${ticketId} berhasil dikirim ke teknisi!`, 'success');
    }
}

function openResolveModal(ticketId) {
    document.getElementById('resolve-ticket-id').value = ticketId;
    openModal('modal-tech-resolve');
}

function handleSaveTechResolve(event) {
    event.preventDefault();
    const ticketId = document.getElementById('resolve-ticket-id').value;
    const resolveNotes = document.getElementById('resolve-notes').value;

    const ticket = appState.tickets.find(t => t.id === ticketId);
    if (ticket) {
        ticket.status = 'selesai';
        ticket.tech_result = resolveNotes;
        ticket.logs.push({
            time: new Date().toISOString().replace('T', ' ').substring(0, 16),
            title: 'Selesai & BAST Terbit',
            desc: `Pengerjaan teknis selesai. Catatan: ${resolveNotes}`
        });

        saveStateToStorage();
        renderTicketsTable();
        renderTechWorkspace();
        closeModal('modal-tech-resolve');
        showToast(`Tiket ${ticketId} telah ditandai SELESAI!`, 'success');
    }
}

function rejectTicket(ticketId) {
    if (confirm(`Apakah Anda yakin ingin menolak permohonan ${ticketId}?`)) {
        const ticket = appState.tickets.find(t => t.id === ticketId);
        if (ticket) {
            ticket.status = 'ditolak';
            ticket.logs.push({
                time: new Date().toISOString().replace('T', ' ').substring(0, 16),
                title: 'Permohonan Ditolak',
                desc: 'Berkas tidak lengkap / tidak memenuhi syarat teknis APTIKA.'
            });

            saveStateToStorage();
            renderTicketsTable();
            renderVerificationQueue();
            showToast(`Tiket ${ticketId} telah ditolak.`, 'warning');
        }
    }
}


// --------------------------------------------------------------------------
// 8. TICKET DETAIL MODAL & TRACKING SEARCH
// --------------------------------------------------------------------------
function openTicketDetailModal(ticketId) {
    const ticket = appState.tickets.find(t => t.id === ticketId);
    if (!ticket) return;

    document.getElementById('modal-ticket-code').innerText = ticket.id;
    const body = document.getElementById('modal-ticket-body');

    const logsHtml = ticket.logs.map((l, index) => `
        <div class="timeline-step ${index === ticket.logs.length - 1 ? 'active' : 'completed'}">
            <div class="timeline-icon"><i class="fa-solid fa-check"></i></div>
            <div class="timeline-content">
                <h5>${l.title}</h5>
                <p>${l.desc}</p>
                <span class="timeline-time"><i class="fa-regular fa-clock"></i> ${l.time}</span>
            </div>
        </div>
    `).join('');

    body.innerHTML = `
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px; margin-bottom:20px; background:#f8fafc; padding:16px; border-radius:8px;">
            <div>
                <p><strong>Instansi OPD:</strong> ${ticket.opd_name}</p>
                <p><strong>Kategori:</strong> ${ticket.service_name}</p>
                <p><strong>Spesifikasi:</strong> ${ticket.detail_target}</p>
            </div>
            <div>
                <p><strong>Tanggal Masuk:</strong> ${ticket.created_at}</p>
                <p><strong>Status Tiket:</strong> <span class="badge-status badge-${ticket.status}">${getStatusLabel(ticket.status)}</span></p>
                <p><strong>Teknisi PIC:</strong> ${ticket.assigned_to}</p>
            </div>
        </div>

        ${ticket.tech_result !== '-' ? `
            <div style="background:#d1fae5; border:1px solid #6ee7b7; padding:14px; border-radius:8px; margin-bottom:20px;">
                <h5 style="color:#065f46; margin-bottom:4px;"><i class="fa-solid fa-key"></i> Hasil Pengerjaan Teknisi:</h5>
                <p style="color:#047857; font-size:0.9rem;">${ticket.tech_result}</p>
            </div>
        ` : ''}

        <h4 style="margin-bottom:12px; color:var(--primary);"><i class="fa-solid fa-route"></i> Linimasa Tracking Status Tiket</h4>
        <div class="timeline-list">
            ${logsHtml}
        </div>
    `;

    openModal('modal-ticket-detail');
}

function handleTrackTicket(event) {
    event.preventDefault();
    const query = document.getElementById('track-input').value.trim();
    quickTrack(query);
}

function quickTrack(ticketId) {
    const ticket = appState.tickets.find(t => t.id.toLowerCase() === ticketId.toLowerCase());
    if (ticket) {
        openTicketDetailModal(ticket.id);
    } else {
        showToast(`Nomor Tiket ${ticketId} tidak ditemukan. Periksa kembali resi Anda.`, 'warning');
    }
}

function printBAST() {
    alert("Simulasi Cetak Dokumen PDF Berita Acara Serah Terima (BAST) Layanan APTIKA Pemkab Jombang siap diunduh.");
}


// --------------------------------------------------------------------------
// 9. SLA & SPBE EXECUTIVE CHARTS (CHART.JS)
// --------------------------------------------------------------------------
function initCharts() {
    appState.chartsRendered = true;

    // 1. Monthly Trend Bar Chart
    const ctxTrend = document.getElementById('chart-trend')?.getContext('2d');
    if (ctxTrend) {
        new Chart(ctxTrend, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt'],
                datasets: [
                    {
                        label: 'Pengajuan Selesai',
                        data: [18, 22, 25, 19, 24, 28, 31, 14],
                        backgroundColor: '#059669',
                        borderRadius: 6
                    },
                    {
                        label: 'Sedang Diproses',
                        data: [2, 1, 3, 2, 1, 4, 3, 4],
                        backgroundColor: '#f59e0b',
                        borderRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }

    // 2. Category Doughnut Chart
    const ctxCategory = document.getElementById('chart-category')?.getContext('2d');
    if (ctxCategory) {
        new Chart(ctxCategory, {
            type: 'doughnut',
            data: {
                labels: ['Subdomain & Hosting', 'TTE BSRE BSSN', 'Integrasi API/Data', 'Helpdesk IT'],
                datasets: [{
                    data: [45, 35, 12, 8],
                    backgroundColor: ['#0f2c59', '#059669', '#0891b2', '#d97706']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    }
}

function exportReportExcel() {
    showToast('Mengunduh Laporan Rekapitulasi SPBE APTIKA 2026 (.xlsx)...', 'success');
}


// --------------------------------------------------------------------------
// 10. UTILITIES (MODALS, TOASTS, BADGES)
// --------------------------------------------------------------------------
function openModal(modalId) {
    document.getElementById(modalId)?.classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId)?.classList.remove('active');
}

function updateBadges() {
    const totalCount = appState.tickets.length;
    const pendingCount = appState.tickets.filter(t => t.status === 'menunggu_verifikasi').length;
    const techCount = appState.tickets.filter(t => t.status === 'disposisi' || t.status === 'diproses').length;

    const bTicket = document.getElementById('ticket-badge');
    const bPending = document.getElementById('pending-badge');
    const bTech = document.getElementById('tech-badge');

    if (bTicket) bTicket.innerText = totalCount;
    if (bPending) bPending.innerText = pendingCount;
    if (bTech) bTech.innerText = techCount;
}

function getStatusLabel(status) {
    const labels = {
        menunggu_verifikasi: 'Menunggu Verifikasi',
        disposisi: 'Disposisi Teknisi',
        diproses: 'Dalam Pengerjaan',
        selesai: 'Selesai & BAST',
        ditolak: 'Ditolak'
    };
    return labels[status] || status;
}

function showToast(message, type = 'info') {
    const container = document.getElementById('toast-container');
    if (!container) return;

    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `<i class="fa-solid fa-circle-info"></i> <span>${message}</span>`;
    
    container.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 4000);
}
