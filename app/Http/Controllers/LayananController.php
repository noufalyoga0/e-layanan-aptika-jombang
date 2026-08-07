<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class LayananController extends Controller
{
    private function getServices(): array
    {
        return [
            [
                'id'   => 'subdomain_hosting',
                'name' => 'Subdomain & Hosting VPS',
                'icon' => 'fa-server',
                'sla'  => '2 Hari Kerja',
                'desc' => 'Permohonan alamat domain resmi (dinas.jombangkab.go.id) dan alokasi resource server VPS/Hosting.',
                'docs' => ['Surat Permohonan Resmi Kadin (PDF)', 'Form Spesifikasi Teknis Web/App'],
            ],
            [
                'id'   => 'tte_bsre',
                'name' => 'Sertifikat Elektronik / TTE',
                'icon' => 'fa-signature',
                'sla'  => '1-2 Hari Kerja',
                'desc' => 'Penerbitan Tanda Tangan Elektronik terintegrasi Balai Sertifikasi Elektronik (BSRE BSSN) untuk pejabat ASN.',
                'docs' => ['Surat Rekomendasi OPD (PDF)', 'Scan KTP ASN', 'SK Jabatan Terakhir'],
            ],
            [
                'id'   => 'integrasi_api',
                'name' => 'Integrasi API & SPLP Data',
                'icon' => 'fa-code-branch',
                'sla'  => '3 Hari Kerja',
                'desc' => 'Permohonan token API Sistem Penghubung Layanan Pemerintah (SPLP) & Interoperabilitas Satu Data Jombang.',
                'docs' => ['Surat Pengajuan Integrasi Data', 'Dokumen Arsitektur API / Data Schema'],
            ],
            [
                'id'   => 'helpdesk_it',
                'name' => 'Helpdesk & Trouble Ticket IT',
                'icon' => 'fa-headset',
                'sla'  => '1 Hari Kerja (Fast Response)',
                'desc' => 'Bantuan penanganan masalah teknis website OPD, Server Down, dan kendala Jaringan Intra Pemkab.',
                'docs' => ['Screenshot Bukti Error / Kendala', 'Form Laporan Gangguan'],
            ],
        ];
    }

    public function index()
    {
        $services = $this->getServices();

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
        $services = $this->getServices();
        return view('katalog', compact('services'));
    }

    public function pengajuanForm()
    {
        return response()
            ->view('pengajuan')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', 'Sat, 26 Jul 1997 05:00:00 GMT');
    }

    public function analytics()
    {
        $total   = Ticket::count();
        $selesai = Ticket::where('status', 'selesai')->count();
        $diproses = Ticket::whereIn('status', ['disposisi', 'diproses'])->count();
        $menunggu = Ticket::where('status', 'menunggu_verifikasi')->count();
        $ditolak  = Ticket::where('status', 'ditolak')->count();

        $slaPercent = $total > 0 ? round(($selesai / $total) * 100, 1) : 100;
        $totalOpd   = User::where('role', 'opd')->count();

        $categoryCounts = [
            'subdomain_hosting' => Ticket::where('service_id', 'subdomain_hosting')->count(),
            'tte_bsre'          => Ticket::where('service_id', 'tte_bsre')->count(),
            'integrasi_api'     => Ticket::where('service_id', 'integrasi_api')->count(),
            'helpdesk_it'       => Ticket::where('service_id', 'helpdesk_it')->count(),
        ];

        return view('analytics', compact(
            'total', 'selesai', 'diproses', 'menunggu', 'ditolak',
            'slaPercent', 'totalOpd', 'categoryCounts'
        ));
    }

    public function exportCsv()
    {
        $tickets  = Ticket::all();
        $filename = 'rekap-tiket-aptika-' . date('Ymd-His') . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($tickets) {
            $handle = fopen('php://output', 'w');
            // BOM agar Excel membaca UTF-8 dengan benar
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($handle, [
                'No. Tiket', 'Instansi Pemohon', 'Jenis Layanan',
                'Detail/Target', 'Status', 'Prioritas',
                'Teknisi PIC', 'Catatan OPD', 'Hasil Pengerjaan',
                'Tanggal Pengajuan', 'Tanggal Update',
            ], ';');

            foreach ($tickets as $t) {
                fputcsv($handle, [
                    $t->ticket_code,
                    $t->opd_name,
                    $t->service_name,
                    $t->detail_target,
                    match ($t->status) {
                        'menunggu_verifikasi' => 'Menunggu Verifikasi',
                        'disposisi'           => 'Disposisi Teknisi',
                        'diproses'            => 'Dalam Pengerjaan',
                        'selesai'             => 'Selesai & BAST',
                        'ditolak'             => 'Ditolak',
                        default               => $t->status,
                    },
                    $t->priority,
                    $t->assigned_to,
                    $t->notes,
                    $t->tech_result ?? '-',
                    $t->created_at->format('d/m/Y H:i'),
                    $t->updated_at->format('d/m/Y H:i'),
                ], ';');
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
