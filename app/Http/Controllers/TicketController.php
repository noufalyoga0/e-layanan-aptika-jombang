<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    // -----------------------------------------------------------------------
    // VIEWS
    // -----------------------------------------------------------------------

    public function index(Request $request)
    {
        $query = Ticket::with('logs')->latest();

        $search = $request->input('search');
        if ($search) {
            $query->where('ticket_code', 'like', "%{$search}%")
                  ->orWhere('opd_name', 'like', "%{$search}%");
        }

        $tickets = $query->get()->toArray();

        // Convert key format to match views seamlessly
        $tickets = array_map(function ($t) {
            $t['id'] = $t['ticket_code'];
            $t['created_at'] = date('Y-m-d H:i', strtotime($t['created_at']));
            $t['logs'] = array_map(function ($l) {
                $l['time'] = date('Y-m-d H:i', strtotime($l['created_at']));
                return $l;
            }, $t['logs'] ?? []);
            return $t;
        }, $tickets);

        return view('tickets', compact('tickets', 'search'));
    }

    public function verifikasi()
    {
        $pendingTickets = Ticket::with('logs')
            ->where('status', 'menunggu_verifikasi')
            ->latest()
            ->get()
            ->toArray();

        $pendingTickets = array_map(function ($t) {
            $t['id'] = $t['ticket_code'];
            $t['created_at'] = date('Y-m-d H:i', strtotime($t['created_at']));
            return $t;
        }, $pendingTickets);

        return view('verifikasi', compact('pendingTickets'));
    }

    public function workspaceTech()
    {
        $techTickets = Ticket::with('logs')
            ->whereIn('status', ['disposisi', 'diproses'])
            ->latest()
            ->get()
            ->toArray();

        $techTickets = array_map(function ($t) {
            $t['id'] = $t['ticket_code'];
            $t['created_at'] = date('Y-m-d H:i', strtotime($t['created_at']));
            $t['logs'] = array_map(function ($l) {
                $l['time'] = date('Y-m-d H:i', strtotime($l['created_at']));
                return $l;
            }, $t['logs'] ?? []);
            return $t;
        }, $techTickets);

        return view('workspace_tech', compact('techTickets'));
    }

    // -----------------------------------------------------------------------
    // ACTIONS - Pengajuan Baru (Admin OPD)
    // -----------------------------------------------------------------------

    public function store(Request $request)
    {
        $request->validate([
            'service_type' => 'required|string',
            'subdomain'    => 'nullable|string|max:255',
            'notes'        => 'nullable|string|max:1000',
        ]);

        $count = Ticket::count();
        $newCode = 'REQ-JBG-' . date('Ym') . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);

        $serviceNameMap = [
            'subdomain_hosting' => 'Subdomain & Hosting VPS',
            'tte_bsre'          => 'Sertifikat Elektronik / TTE',
            'integrasi_api'     => 'Integrasi API & SPLP Data',
            'helpdesk_it'       => 'Helpdesk & Trouble Ticket IT',
        ];

        $serviceId = $request->input('service_type');

        $opdName = \Illuminate\Support\Facades\Auth::check()
            ? \Illuminate\Support\Facades\Auth::user()->opd_name
            : 'Dinas Kesehatan Kab. Jombang';

        $ticket = Ticket::create([
            'ticket_code'   => $newCode,
            'opd_name'      => $opdName,
            'service_id'    => $serviceId,
            'service_name'  => $serviceNameMap[$serviceId] ?? 'Layanan APTIKA',
            'detail_target' => $request->input('subdomain', 'Permohonan Layanan'),
            'status'        => 'menunggu_verifikasi',
            'priority'      => 'Normal',
            'notes'         => $request->input('notes', 'Pengajuan via Portal E-Layanan APTIKA'),
            'assigned_to'   => 'Belum Didisposisi',
        ]);

        TicketLog::create([
            'ticket_id'  => $ticket->id,
            'title'      => 'Permohonan Dikirim',
            'desc'       => 'Diajukan via Portal E-Layanan APTIKA Jombang',
            'created_at' => now(),
        ]);

        return redirect()->route('tickets.index')
            ->with('success', "✅ Pengajuan tersimpan di Database! Kode Tiket: {$newCode}");
    }

    // -----------------------------------------------------------------------
    // ACTIONS - Verifikator APTIKA
    // -----------------------------------------------------------------------

    public function approve(Request $request, string $ticketCode)
    {
        $request->validate([
            'teknisi'      => 'required|string',
            'catatan_disp' => 'nullable|string|max:500',
        ]);

        $ticket = Ticket::where('ticket_code', $ticketCode)->firstOrFail();

        $ticket->update([
            'status'      => 'disposisi',
            'assigned_to' => $request->input('teknisi'),
            'disp_notes'  => $request->input('catatan_disp'),
        ]);

        TicketLog::create([
            'ticket_id'  => $ticket->id,
            'title'      => 'Disetujui & Didisposisi',
            'desc'       => 'Berkas disetujui Verifikator APTIKA. Didisposisi ke: ' . $request->input('teknisi') . '. Catatan: ' . ($request->input('catatan_disp') ?: '-'),
            'created_at' => now(),
        ]);

        return redirect()->route('verifikasi')
            ->with('success', "✅ Tiket {$ticketCode} berhasil disetujui di Database & didisposisikan ke " . $request->input('teknisi') . ".");
    }

    public function tolak(Request $request, string $ticketCode)
    {
        $request->validate([
            'alasan_tolak' => 'required|string|max:500',
        ]);

        $ticket = Ticket::where('ticket_code', $ticketCode)->firstOrFail();

        $ticket->update([
            'status' => 'ditolak',
        ]);

        TicketLog::create([
            'ticket_id'  => $ticket->id,
            'title'      => 'Permohonan Ditolak',
            'desc'       => 'Ditolak oleh Verifikator APTIKA. Alasan: ' . $request->input('alasan_tolak'),
            'created_at' => now(),
        ]);

        return redirect()->route('verifikasi')
            ->with('warning', "❌ Tiket {$ticketCode} telah ditolak dengan alasan: " . $request->input('alasan_tolak'));
    }

    // -----------------------------------------------------------------------
    // ACTIONS - Staf Teknisi
    // -----------------------------------------------------------------------

    public function selesai(Request $request, string $ticketCode)
    {
        $request->validate([
            'tech_result' => 'required|string|max:1000',
        ]);

        $ticket = Ticket::where('ticket_code', $ticketCode)->firstOrFail();

        $ticket->update([
            'status'      => 'selesai',
            'tech_result' => $request->input('tech_result'),
        ]);

        TicketLog::create([
            'ticket_id'  => $ticket->id,
            'title'      => 'Selesai & BAST Terbit',
            'desc'       => 'Pengerjaan teknis selesai oleh ' . $ticket->assigned_to . '. Hasil: ' . $request->input('tech_result'),
            'created_at' => now(),
        ]);

        return redirect()->route('workspace.tech')
            ->with('success', "✅ Tiket {$ticketCode} telah ditandai SELESAI di Database! Berita Acara (BAST) siap dicetak.");
    }

    // -----------------------------------------------------------------------
    // CETAK BERITA ACARA (BAST) PDF / PRINT
    // -----------------------------------------------------------------------
    public function printBast(string $ticketCode)
    {
        $ticket = Ticket::where('ticket_code', $ticketCode)->firstOrFail();
        return view('bast_print', compact('ticket'));
    }

    // -----------------------------------------------------------------------
    // RESET DATA DEMO (Re-run Seeder)
    // -----------------------------------------------------------------------

    public function resetDemo()
    {
        \Artisan::call('migrate:fresh', [
            '--seed'  => true,
            '--force' => true,
        ]);
        return redirect()->route('tickets.index')
            ->with('success', '🔄 Database MySQL berhasil di-reset & di-seed ke kondisi awal!');
    }
}
