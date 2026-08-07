<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    // -----------------------------------------------------------------------
    // VIEWS
    // -----------------------------------------------------------------------

    public function index(Request $request)
    {
        $query = Ticket::with('logs')->latest();
        $user  = Auth::user();

        // OPD hanya melihat tiket milik instansinya sendiri
        if ($user && $user->role === 'opd') {
            $query->where('opd_name', $user->opd_name);
        }

        $search = $request->input('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('ticket_code', 'like', "%{$search}%")
                  ->orWhere('opd_name', 'like', "%{$search}%");
            });
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
            $t['attachments'] = $t['attachments'] ?? [];
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
            $t['attachments'] = $t['attachments'] ?? [];
            return $t;
        }, $pendingTickets);

        // Ambil daftar teknisi dari database secara dinamis
        $teknisiList = \App\Models\User::where('role', 'teknisi')
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('verifikasi', compact('pendingTickets', 'teknisiList'));
    }

    public function workspaceTech()
    {
        $query = Ticket::with('logs')->whereIn('status', ['disposisi', 'diproses']);

        $user = Auth::user();

        // Filter tiket berdasarkan assigned_to yang cocok dengan nama user teknisi
        // Super Admin melihat semua tiket aktif
        if ($user && $user->role === 'teknisi') {
            $query->where('assigned_to', 'LIKE', '%' . $user->name . '%');
        }

        $techTickets = $query->latest()->get()->toArray();

        $techTickets = array_map(function ($t) {
            $t['id'] = $t['ticket_code'];
            $t['created_at'] = date('Y-m-d H:i', strtotime($t['created_at']));
            $t['logs'] = array_map(function ($l) {
                $l['time'] = date('Y-m-d H:i', strtotime($l['created_at']));
                return $l;
            }, $t['logs'] ?? []);
            $t['attachments'] = $t['attachments'] ?? [];
            return $t;
        }, $techTickets);

        return view('workspace_tech', compact('techTickets'));
    }

    // -----------------------------------------------------------------------
    // ACTIONS - Pengajuan Baru (Admin OPD)
    // -----------------------------------------------------------------------

    public function store(Request $request)
    {
        $serviceId = $request->input('service_type');
        $docLabels = $this->docLabelsForService($serviceId);

        if (empty($docLabels)) {
            return back()->withErrors(['service_type' => 'Jenis layanan tidak valid.'])->withInput();
        }

        $rules = [
            'service_type' => 'required|string',
            'subdomain'    => 'required|string|max:255',
            'notes'        => 'nullable|string|max:1000',
        ];

        if ($serviceId === 'tte_bsre') {
            $rules['subdomain'] = 'required|regex:/^\d+$/|max:30';
        }

        foreach (array_keys($docLabels) as $key) {
            $rules[$key] = 'required|file|mimes:pdf,jpg,jpeg,png|max:5120';
        }

        $messages = [
            'subdomain.required' => 'Detail kebutuhan permohonan wajib diisi!',
            'subdomain.regex'    => 'NIP hanya boleh berisi angka, tanpa huruf atau spasi.',
        ];

        foreach ($docLabels as $key => $label) {
            $messages["{$key}.required"] = "{$label} wajib diunggah.";
            $messages["{$key}.file"]     = "{$label} harus berupa file valid.";
            $messages["{$key}.mimes"]    = "{$label} harus berformat PDF, JPG, JPEG, atau PNG.";
            $messages["{$key}.max"]      = "{$label} maksimal 5 MB per file.";
        }

        $request->validate($rules, $messages);

        $count = Ticket::count();
        // Generate kode tiket aman dari duplikat dengan query MAX
        $prefix = 'REQ-JBG-' . date('Ym') . '-';
        $lastCode = Ticket::where('ticket_code', 'like', $prefix . '%')
            ->max('ticket_code');
        $lastNum = $lastCode ? (int) substr($lastCode, strlen($prefix)) : 0;
        $newCode = $prefix . str_pad($lastNum + 1, 3, '0', STR_PAD_LEFT);

        $serviceNameMap = [
            'subdomain_hosting' => 'Subdomain & Hosting VPS',
            'tte_bsre'          => 'Sertifikat Elektronik / TTE',
            'integrasi_api'     => 'Integrasi API & SPLP Data',
            'helpdesk_it'       => 'Helpdesk & Trouble Ticket IT',
        ];

        $opdName = Auth::check()
            ? Auth::user()->opd_name
            : 'Dinas Kesehatan Kab. Jombang';

        $attachments = [];
        foreach ($docLabels as $key => $label) {
            $file = $request->file($key);
            $path = $file->store("ticket-docs/{$newCode}", 'public');
            $attachments[] = [
                'label'         => $label,
                'path'          => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime'          => $file->getMimeType(),
            ];
        }

        $ticket = Ticket::create([
            'ticket_code'   => $newCode,
            'opd_name'      => $opdName,
            'service_id'    => $serviceId,
            'service_name'  => $serviceNameMap[$serviceId] ?? 'Layanan APTIKA',
            'detail_target' => $request->input('subdomain', 'Permohonan Layanan'),
            'status'        => 'menunggu_verifikasi',
            'priority'      => 'Normal',
            'notes'         => $request->input('notes', 'Pengajuan via Portal E-Layanan APTIKA'),
            'attachments'   => $attachments,
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

        // Cari data teknisi yang mengerjakan berdasarkan nama di assigned_to
        $teknisi = null;
        if ($ticket->assigned_to && $ticket->assigned_to !== 'Belum Didisposisi') {
            $teknisi = \App\Models\User::where('role', 'teknisi')
                ->where('name', 'LIKE', '%' . explode(' ', $ticket->assigned_to)[0] . '%')
                ->first();
        }

        return view('bast_print', compact('ticket', 'teknisi'));
    }

    public function viewDocument(string $ticketCode, int $index)
    {
        $ticket = Ticket::where('ticket_code', $ticketCode)->firstOrFail();
        $this->authorizeDocumentAccess($ticket);

        $attachments = $ticket->attachments ?? [];
        if (!isset($attachments[$index])) {
            abort(404, 'Dokumen tidak ditemukan.');
        }

        $doc = $attachments[$index];
        if (!Storage::disk('public')->exists($doc['path'])) {
            abort(404, 'Berkas dokumen tidak ditemukan di server.');
        }

        return Storage::disk('public')->response(
            $doc['path'],
            $doc['original_name'],
            ['Content-Type' => $doc['mime'] ?? 'application/octet-stream']
        );
    }

    private function authorizeDocumentAccess(Ticket $ticket): void
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        if (in_array($user->role, ['admin_aptika', 'teknisi', 'super_admin', 'kabid'], true)) {
            return;
        }

        if ($user->role === 'opd' && $user->opd_name === $ticket->opd_name) {
            return;
        }

        abort(403, 'Anda tidak memiliki akses untuk melihat dokumen ini.');
    }

    private function docLabelsForService(string $serviceId): array
    {
        return match ($serviceId) {
            'subdomain_hosting' => [
                'doc_0' => 'Surat Permohonan Resmi Kadin (PDF)',
                'doc_1' => 'Form Spesifikasi Teknis Web / App',
            ],
            'tte_bsre' => [
                'doc_0' => 'Surat Rekomendasi OPD (PDF)',
                'doc_1' => 'Scan KTP ASN (JPG / PNG)',
                'doc_2' => 'SK Jabatan Terakhir (PDF)',
            ],
            'integrasi_api' => [
                'doc_0' => 'Surat Pengajuan Integrasi Data (PDF)',
                'doc_1' => 'Dokumen Arsitektur API / Data Schema',
            ],
            'helpdesk_it' => [
                'doc_0' => 'Screenshot Bukti Error / Kendala (JPG/PNG)',
                'doc_1' => 'Form Laporan Gangguan (PDF)',
            ],
            default => [],
        };
    }

    // -----------------------------------------------------------------------
    // RESET DATA DEMO - DIHAPUS (tidak dipakai di production)
    // -----------------------------------------------------------------------
}
