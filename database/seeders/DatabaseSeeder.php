<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        if (User::query()->exists()) {
            return;
        }

        // 1. Seed Users (Roles Pemkab Jombang)

        // 🔑 SUPER ADMIN — Mahasiswa Magang UB
        User::create([
            'name'     => 'Noufal Yoga Salsabila',
            'email'    => 'noufalyoga0@student.ub.ac.id',
            'password' => Hash::make('password'),
            'role'     => 'super_admin',
            'opd_name' => 'Diskominfo Kab. Jombang (Mahasiswa Magang UB)',
            'nip'      => '243140701111010',
        ]);

        // OPD Accounts (Dinas & Kecamatan Pemkab Jombang)
        User::create([
            'name'     => 'Admin Dinkes Jombang',
            'email'    => 'dinkes@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'opd',
            'opd_name' => 'Dinas Kesehatan Kab. Jombang',
            'nip'      => '198203152006041002',
        ]);

        User::create([
            'name'     => 'Admin Disdikbud Jombang',
            'email'    => 'disdik@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'opd',
            'opd_name' => 'Dinas Pendidikan & Kebudayaan',
            'nip'      => '197805122005011003',
        ]);

        User::create([
            'name'     => 'Admin Diskop UMKM',
            'email'    => 'umkm@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'opd',
            'opd_name' => 'Dinas Koperasi & UMKM',
            'nip'      => '198504102008022001',
        ]);

        User::create([
            'name'     => 'Admin Dishub Jombang',
            'email'    => 'dishub@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'opd',
            'opd_name' => 'Dinas Perhubungan Kab. Jombang',
            'nip'      => '198309112007011005',
        ]);

        User::create([
            'name'     => 'Admin Kec. Ploso',
            'email'    => 'ploso@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'opd',
            'opd_name' => 'Kecamatan Ploso Kab. Jombang',
            'nip'      => '198006152006041004',
        ]);

        User::create([
            'name'     => 'Verifikator APTIKA',
            'email'    => 'aptika@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'admin_aptika',
            'opd_name' => 'Diskominfo Kab. Jombang',
            'nip'      => '197908202005011004',
        ]);

        User::create([
            'name'     => 'Agus Setiawan (Teknisi Server/Hosting)',
            'email'    => 'agus.teknisi@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'teknisi',
            'opd_name' => 'Diskominfo Kab. Jombang',
            'nip'      => '199001122015031001',
        ]);

        User::create([
            'name'     => 'Budi Raharjo (Staf TTE & BSRE)',
            'email'    => 'budi.tte@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'teknisi',
            'opd_name' => 'Diskominfo Kab. Jombang',
            'nip'      => '199104152016021002',
        ]);

        User::create([
            'name'     => 'Citra Dewi (Developer Integrasi API)',
            'email'    => 'citra.api@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'teknisi',
            'opd_name' => 'Diskominfo Kab. Jombang',
            'nip'      => '199308222017042003',
        ]);

        User::create([
            'name'     => 'Dian Pratama (Helpdesk IT Support)',
            'email'    => 'dian.helpdesk@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'teknisi',
            'opd_name' => 'Diskominfo Kab. Jombang',
            'nip'      => '199511102019031004',
        ]);

        User::create([
            'name'     => 'Drs. Bambang H., M.Si (Kabid APTIKA)',
            'email'    => 'kabid.aptika@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'kabid',
            'opd_name' => 'Diskominfo Kab. Jombang',
            'nip'      => '196811051994031003',
        ]);

        // 2. Seed Initial Tickets (1 Tiket Spesifik untuk Masing-masing dari 4 Teknisi)
        
        // Tiket 1: Agus Setiawan (Teknisi Server/Hosting)
        $t1 = Ticket::create([
            'ticket_code'   => 'REQ-JBG-202608-001',
            'opd_name'      => 'Dinas Kesehatan Kab. Jombang',
            'service_id'    => 'subdomain_hosting',
            'service_name'  => 'Subdomain & Hosting VPS',
            'detail_target' => 'posyandu.jombangkab.go.id',
            'status'        => 'diproses',
            'priority'      => 'Tinggi',
            'notes'         => 'Dibutuhkan untuk aplikasi pemantauan stunting desa di Kabupaten Jombang.',
            'assigned_to'   => 'Agus Setiawan (Teknisi Server/Hosting)',
            'disp_notes'    => 'Lakukan setup VPS RAM 4GB dan SSL Let\'s Encrypt.',
            'tech_result'   => 'Proses alokasi VPS IP 103.14.22.10 dan instalasi OS Ubuntu Server 22.04 LTS.',
            'created_at'    => '2026-08-01 09:30:00',
        ]);
        TicketLog::create(['ticket_id' => $t1->id, 'title' => 'Permohonan Dikirim', 'desc' => 'Diajukan oleh Admin Dinas Kesehatan', 'created_at' => '2026-08-01 09:30:00']);
        TicketLog::create(['ticket_id' => $t1->id, 'title' => 'Disposisi Teknisi', 'desc' => 'Ditugaskan ke Agus Setiawan (Teknisi Server/Hosting)', 'created_at' => '2026-08-01 13:00:00']);

        // Tiket 2: Budi Raharjo (Staf TTE & BSRE)
        $t2 = Ticket::create([
            'ticket_code'   => 'REQ-JBG-202608-002',
            'opd_name'      => 'Dinas Pendidikan & Kebudayaan',
            'service_id'    => 'tte_bsre',
            'service_name'  => 'Sertifikat Elektronik / TTE',
            'detail_target' => 'NIP: 19780512 200501 1 003 (Kepala Dinas)',
            'status'        => 'diproses',
            'priority'      => 'Sangat Tinggi',
            'notes'         => 'Penerbitan Sertifikat Elektronik BSRE untuk penandatanganan ijazah dan SK Guru.',
            'assigned_to'   => 'Budi Raharjo (Staf TTE & BSRE)',
            'disp_notes'    => 'Proses pendaftaran ke BSSN.',
            'tech_result'   => 'Pendaftaran ke portal BSRE BSSN dalam verifikasi NIK/NIP.',
            'created_at'    => '2026-08-03 10:15:00',
        ]);
        TicketLog::create(['ticket_id' => $t2->id, 'title' => 'Permohonan Dikirim', 'desc' => 'Diajukan oleh Admin Disdik', 'created_at' => '2026-08-03 10:15:00']);
        TicketLog::create(['ticket_id' => $t2->id, 'title' => 'Disposisi Teknisi', 'desc' => 'Ditugaskan ke Budi Raharjo (Staf TTE & BSRE)', 'created_at' => '2026-08-03 14:00:00']);

        // Tiket 3: Citra Dewi (Developer Integrasi API)
        $t3 = Ticket::create([
            'ticket_code'   => 'REQ-JBG-202608-003',
            'opd_name'      => 'Dinas Koperasi & UMKM',
            'service_id'    => 'integrasi_api',
            'service_name'  => 'Integrasi API & SPLP Data',
            'detail_target' => 'API Data Pelaku UMKM Jombang',
            'status'        => 'diproses',
            'priority'      => 'Sedang',
            'notes'         => 'Permohonan integrasi data UMKM dengan Portal Jombang Kita Smart City.',
            'assigned_to'   => 'Citra Dewi (Developer Integrasi API)',
            'disp_notes'    => 'Buat API Endpoint JSON & JWT Authentication.',
            'tech_result'   => 'API Endpoint /api/v1/umkm sedang dikembangkan di staging environment.',
            'created_at'    => '2026-08-04 08:45:00',
        ]);
        TicketLog::create(['ticket_id' => $t3->id, 'title' => 'Permohonan Dikirim', 'desc' => 'Diajukan oleh Admin Diskop UMKM', 'created_at' => '2026-08-04 08:45:00']);
        TicketLog::create(['ticket_id' => $t3->id, 'title' => 'Disposisi Teknisi', 'desc' => 'Ditugaskan ke Citra Dewi (Developer Integrasi API)', 'created_at' => '2026-08-04 10:00:00']);

        // Tiket 4: Dian Pratama (Helpdesk IT Support)
        $t4 = Ticket::create([
            'ticket_code'   => 'REQ-JBG-202608-004',
            'opd_name'      => 'Kecamatan Ploso Kabupaten Jombang',
            'service_id'    => 'helpdesk_it',
            'service_name'  => 'Helpdesk & Trouble Ticket IT',
            'detail_target' => 'Koneksi Jaringan Intra Kantor Camat Down',
            'status'        => 'diproses',
            'priority'      => 'Tinggi',
            'notes'         => 'Koneksi fiber optic ke aplikasi PATEN Kecamatan RPU terputus sejak tadi pagi.',
            'assigned_to'   => 'Dian Pratama (Helpdesk IT Support)',
            'disp_notes'    => 'Pengecekan fisik Mikrotik Router & kabel FO Kantor Camat Ploso.',
            'tech_result'   => 'Teknisi dilapangan sedang melakukan replacement Optical Power Meter & patching SFP Module.',
            'created_at'    => '2026-08-04 11:20:00',
        ]);
        TicketLog::create(['ticket_id' => $t4->id, 'title' => 'Permohonan Dikirim', 'desc' => 'Diajukan oleh Admin Kec. Ploso', 'created_at' => '2026-08-04 11:20:00']);
        TicketLog::create(['ticket_id' => $t4->id, 'title' => 'Disposisi Teknisi', 'desc' => 'Ditugaskan ke Dian Pratama (Helpdesk IT Support)', 'created_at' => '2026-08-04 13:10:00']);

        // Tiket 5: Menunggu Verifikasi APTIKA (untuk demo Verifikator)
        $t5 = Ticket::create([
            'ticket_code'   => 'REQ-JBG-202608-005',
            'opd_name'      => 'Dinas Perhubungan Kab. Jombang',
            'service_id'    => 'subdomain_hosting',
            'service_name'  => 'Subdomain & Hosting VPS',
            'detail_target' => 'dishub-atcs.jombangkab.go.id',
            'status'        => 'menunggu_verifikasi',
            'priority'      => 'Tinggi',
            'notes'         => 'Permohonan hosting VPS untuk server integrasi kamera CCTV ATCS lalu lintas.',
            'assigned_to'   => 'Belum Didisposisi',
            'created_at'    => '2026-08-05 08:00:00',
        ]);
        TicketLog::create(['ticket_id' => $t5->id, 'title' => 'Permohonan Dikirim', 'desc' => 'Diajukan oleh Admin Dishub Jombang', 'created_at' => '2026-08-05 08:00:00']);
    }
}
