<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class KabidController extends Controller
{
    public function dashboard()
    {
        $total    = Ticket::count();
        $selesai  = Ticket::where('status', 'selesai')->count();
        $diproses = Ticket::whereIn('status', ['disposisi', 'diproses'])->count();
        $menunggu = Ticket::where('status', 'menunggu_verifikasi')->count();
        $ditolak  = Ticket::where('status', 'ditolak')->count();

        // SLA — anggap tiket yang selesai tepat waktu
        $slaPercent = $total > 0 ? round(($selesai / $total) * 100, 1) : 100;

        // Jumlah OPD aktif (yang sudah pernah mengajukan)
        $opdAktif = Ticket::distinct('opd_name')->count('opd_name');

        // Distribusi per jenis layanan
        $perLayanan = [
            'Subdomain & Hosting VPS'        => Ticket::where('service_id', 'subdomain_hosting')->count(),
            'Sertifikat Elektronik / TTE'     => Ticket::where('service_id', 'tte_bsre')->count(),
            'Integrasi API & SPLP Data'       => Ticket::where('service_id', 'integrasi_api')->count(),
            'Helpdesk & Trouble Ticket IT'    => Ticket::where('service_id', 'helpdesk_it')->count(),
        ];

        // Kinerja per teknisi
        $teknisiList = User::where('role', 'teknisi')->get();
        $kinerjaTeknisi = $teknisiList->map(function ($tek) {
            $firstName = explode(' ', $tek->name)[0];
            return [
                'nama'     => $tek->name,
                'selesai'  => Ticket::where('assigned_to', 'LIKE', "%{$firstName}%")
                                    ->where('status', 'selesai')->count(),
                'diproses' => Ticket::where('assigned_to', 'LIKE', "%{$firstName}%")
                                    ->whereIn('status', ['disposisi', 'diproses'])->count(),
            ];
        });

        // 5 tiket terbaru
        $tiketTerbaru = Ticket::latest()->take(5)->get();

        // Tiket menunggu terlama (berpotensi melebihi SLA)
        $tiketTerlama = Ticket::whereNotIn('status', ['selesai', 'ditolak'])
            ->oldest()
            ->take(5)
            ->get();

        return view('kabid.dashboard', compact(
            'total', 'selesai', 'diproses', 'menunggu', 'ditolak',
            'slaPercent', 'opdAktif', 'perLayanan',
            'kinerjaTeknisi', 'tiketTerbaru', 'tiketTerlama'
        ));
    }
}
