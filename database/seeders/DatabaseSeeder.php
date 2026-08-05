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
            'name'     => 'Agus Setiawan (Teknisi Server)',
            'email'    => 'agus.teknisi@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'teknisi',
            'opd_name' => 'Diskominfo Kab. Jombang',
            'nip'      => '199001122015031001',
        ]);

        User::create([
            'name'     => 'Drs. Bambang H., M.Si (Kabid APTIKA)',
            'email'    => 'kabid.aptika@jombangkab.go.id',
            'password' => Hash::make('password'),
            'role'     => 'kabid',
            'opd_name' => 'Diskominfo Kab. Jombang',
            'nip'      => '196811051994031003',
        ]);

        // 2. Seed Initial Tickets
        $t1 = Ticket::create([
            'ticket_code'   => 'REQ-JBG-202608-001',
            'opd_name'      => 'Dinas Kesehatan Kab. Jombang',
            'service_id'    => 'subdomain_hosting',
            'service_name'  => 'Subdomain & Hosting VPS',
            'detail_target' => 'posyandu.jombangkab.go.id',
            'status'        => 'selesai',
            'priority'      => 'Tinggi',
            'notes'         => 'Dibutuhkan untuk aplikasi pemantauan stunting desa di Kabupaten Jombang.',
            'assigned_to'   => 'Agus Setiawan (Teknisi Server)',
            'disp_notes'    => 'Lakukan setup VPS RAM 4GB dan SSL Let\'s Encrypt.',
            'tech_result'   => 'Subdomain https://posyandu.jombangkab.go.id aktif. Server IP: 103.14.22.10 (RAM 4GB, SSD 50GB). SSL Active.',
            'created_at'    => '2026-08-01 09:30:00',
        ]);
        TicketLog::create(['ticket_id' => $t1->id, 'title' => 'Permohonan Dikirim', 'desc' => 'Diajukan oleh Admin Dinas Kesehatan', 'created_at' => '2026-08-01 09:30:00']);
        TicketLog::create(['ticket_id' => $t1->id, 'title' => 'Terverifikasi APTIKA', 'desc' => 'Dokumen lengkap, disetujui Verifikator APTIKA', 'created_at' => '2026-08-01 11:15:00']);
        TicketLog::create(['ticket_id' => $t1->id, 'title' => 'Disposisi Teknisi', 'desc' => 'Ditugaskan ke Agus Setiawan (Teknisi Server)', 'created_at' => '2026-08-01 13:00:00']);
        TicketLog::create(['ticket_id' => $t1->id, 'title' => 'Selesai & BAST Terbit', 'desc' => 'Konfigurasi server & subdomain berhasil', 'created_at' => '2026-08-02 10:20:00']);

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
        TicketLog::create(['ticket_id' => $t2->id, 'title' => 'Disposisi Teknisi', 'desc' => 'Ditugaskan ke Budi Raharjo', 'created_at' => '2026-08-03 14:00:00']);
        TicketLog::create(['ticket_id' => $t2->id, 'title' => 'Pengerjaan BSRE', 'desc' => 'Verifikasi NIP & NIK di sistem BSRE BSSN', 'created_at' => '2026-08-04 08:30:00']);

        $t3 = Ticket::create([
            'ticket_code'   => 'REQ-JBG-202608-003',
            'opd_name'      => 'Dinas Koperasi & UMKM',
            'service_id'    => 'integrasi_api',
            'service_name'  => 'Integrasi API & SPLP Data',
            'detail_target' => 'API Data Pelaku UMKM Jombang',
            'status'        => 'menunggu_verifikasi',
            'priority'      => 'Sedang',
            'notes'         => 'Permohonan integrasi data UMKM dengan Portal Jombang Kita Smart City.',
            'assigned_to'   => 'Belum Didisposisi',
            'created_at'    => '2026-08-04 08:45:00',
        ]);
        TicketLog::create(['ticket_id' => $t3->id, 'title' => 'Permohonan Dikirim', 'desc' => 'Menunggu verifikasi berkas oleh Admin APTIKA', 'created_at' => '2026-08-04 08:45:00']);

        $t4 = Ticket::create([
            'ticket_code'   => 'REQ-JBG-202608-004',
            'opd_name'      => 'Kecamatan Ploso Kabupaten Jombang',
            'service_id'    => 'helpdesk_it',
            'service_name'  => 'Helpdesk & Trouble Ticket IT',
            'detail_target' => 'Koneksi Jaringan Intra Kantor Camat Down',
            'status'        => 'menunggu_verifikasi',
            'priority'      => 'Tinggi',
            'notes'         => 'Koneksi fiber optic ke aplikasi PATEN Kecamatan RPU terputus sejak tadi pagi.',
            'assigned_to'   => 'Belum Didisposisi',
            'created_at'    => '2026-08-04 11:20:00',
        ]);
        TicketLog::create(['ticket_id' => $t4->id, 'title' => 'Permohonan Dikirim', 'desc' => 'Menunggu penanganan cepat tim jaringan', 'created_at' => '2026-08-04 11:20:00']);
    }
}
