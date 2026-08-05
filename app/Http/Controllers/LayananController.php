<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    public function index()
    {
        $services = [
            [
                'id' => 'subdomain_hosting',
                'name' => 'Subdomain & Hosting VPS',
                'icon' => 'fa-server',
                'sla' => '2 Hari Kerja',
                'desc' => 'Permohonan alamat domain resmi (dinas.jombangkab.go.id) dan alokasi resource server VPS/Hosting.',
                'docs' => ['Surat Permohonan Resmi Kadin (PDF)', 'Form Spesifikasi Teknis Web/App']
            ],
            [
                'id' => 'tte_bsre',
                'name' => 'Sertifikat Elektronik / TTE',
                'icon' => 'fa-signature',
                'sla' => '1-2 Hari Kerja',
                'desc' => 'Penerbitan Tanda Tangan Elektronik terintegrasi Balai Sertifikasi Elektronik (BSRE BSSN) untuk pejabat ASN.',
                'docs' => ['Surat Rekomendasi OPD (PDF)', 'Scan KTP ASN', 'SK Jabatan Terakhir']
            ],
            [
                'id' => 'integrasi_api',
                'name' => 'Integrasi API & SPLP Data',
                'icon' => 'fa-code-branch',
                'sla' => '3 Hari Kerja',
                'desc' => 'Permohonan token API Sistem Penghubung Layanan Pemerintah (SPLP) & Interoperabilitas Satu Data Jombang.',
                'docs' => ['Surat Pengajuan Integrasi Data', 'Dokumen Arsitektur API / Data Schema']
            ],
            [
                'id' => 'helpdesk_it',
                'name' => 'Helpdesk & Trouble Ticket IT',
                'icon' => 'fa-headset',
                'sla' => '1 Hari Kerja (Fast Response)',
                'desc' => 'Bantuan penanganan masalah teknis website OPD, Server Down, dan kendala Jaringan Intra Pemkab.',
                'docs' => ['Screenshot Bukti Error / Kendala', 'Form Laporan Gangguan']
            ]
        ];

        // REALTIME STATS FROM DATABASE
        $stats = [
            'total'    => Ticket::count(),
            'selesai'  => Ticket::where('status', 'selesai')->count(),
            'diproses' => Ticket::whereIn('status', ['disposisi', 'diproses'])->count(),
            'menunggu' => Ticket::where('status', 'menunggu_verifikasi')->count(),
        ];

        return view('home', compact('services', 'stats'));
    }

    public function katalog()
    {
        $services = [
            [
                'id' => 'subdomain_hosting',
                'name' => 'Subdomain & Hosting VPS',
                'icon' => 'fa-server',
                'sla' => '2 Hari Kerja',
                'desc' => 'Permohonan alamat domain resmi (dinas.jombangkab.go.id) dan alokasi resource server VPS/Hosting.',
                'docs' => ['Surat Permohonan Resmi Kadin (PDF)', 'Form Spesifikasi Teknis Web/App']
            ],
            [
                'id' => 'tte_bsre',
                'name' => 'Sertifikat Elektronik / TTE',
                'icon' => 'fa-signature',
                'sla' => '1-2 Hari Kerja',
                'desc' => 'Penerbitan Tanda Tangan Elektronik terintegrasi Balai Sertifikasi Elektronik (BSRE BSSN) untuk pejabat ASN.',
                'docs' => ['Surat Rekomendasi OPD (PDF)', 'Scan KTP ASN', 'SK Jabatan Terakhir']
            ],
            [
                'id' => 'integrasi_api',
                'name' => 'Integrasi API & SPLP Data',
                'icon' => 'fa-code-branch',
                'sla' => '3 Hari Kerja',
                'desc' => 'Permohonan token API Sistem Penghubung Layanan Pemerintah (SPLP) & Interoperabilitas Satu Data Jombang.',
                'docs' => ['Surat Pengajuan Integrasi Data', 'Dokumen Arsitektur API / Data Schema']
            ],
            [
                'id' => 'helpdesk_it',
                'name' => 'Helpdesk & Trouble Ticket IT',
                'icon' => 'fa-headset',
                'sla' => '1 Hari Kerja (Fast Response)',
                'desc' => 'Bantuan penanganan masalah teknis website OPD, Server Down, dan kendala Jaringan Intra Pemkab.',
                'docs' => ['Screenshot Bukti Error / Kendala', 'Form Laporan Gangguan']
            ]
        ];

        return view('katalog', compact('services'));
    }

    public function pengajuanForm()
    {
        return view('pengajuan');
    }

    public function analytics()
    {
        $total = Ticket::count();
        $selesai = Ticket::where('status', 'selesai')->count();
        $diproses = Ticket::whereIn('status', ['disposisi', 'diproses'])->count();
        $menunggu = Ticket::where('status', 'menunggu_verifikasi')->count();
        $ditolak = Ticket::where('status', 'ditolak')->count();

        $slaPercent = $total > 0 ? round(($selesai / $total) * 100, 1) : 100;
        $totalOpd = User::where('role', 'opd')->count();

        $categoryCounts = [
            'subdomain_hosting' => Ticket::where('service_id', 'subdomain_hosting')->count(),
            'tte_bsre'          => Ticket::where('service_id', 'tte_bsre')->count(),
            'integrasi_api'     => Ticket::where('service_id', 'integrasi_api')->count(),
            'helpdesk_it'       => Ticket::where('service_id', 'helpdesk_it')->count(),
        ];

        return view('analytics', compact('total', 'selesai', 'diproses', 'menunggu', 'ditolak', 'slaPercent', 'totalOpd', 'categoryCounts'));
    }
}
