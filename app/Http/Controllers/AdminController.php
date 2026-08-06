<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_tickets'   => Ticket::count(),
            'menunggu'        => Ticket::where('status', 'menunggu_verifikasi')->count(),
            'diproses'        => Ticket::whereIn('status', ['disposisi', 'diproses'])->count(),
            'selesai'         => Ticket::where('status', 'selesai')->count(),
            'ditolak'         => Ticket::where('status', 'ditolak')->count(),
            'total_users'     => User::count(),
            'total_opd'       => User::where('role', 'opd')->count(),
        ];

        $recentTickets = Ticket::latest()->take(5)->get();
        $allUsers      = User::orderBy('role')->get();

        return view('admin.dashboard', compact('stats', 'recentTickets', 'allUsers'));
    }

    public function users()
    {
        $users = User::orderBy('role')->get();
        return view('admin.users', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'opd_name' => 'required|string|max:255',
            'nip'      => 'required|regex:/^\d+$/|max:30',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required|in:opd,admin_aptika,teknisi,kabid',
        ], [
            'nip.regex' => 'NIP hanya boleh berisi angka, tanpa huruf atau spasi.',
        ]);

        User::create([
            'name'     => $validated['name'],
            'opd_name' => $validated['opd_name'],
            'nip'      => $validated['nip'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => $validated['role'],
        ]);

        return redirect()->route('admin.users')
            ->with('success', "✅ Akun baru '{$validated['name']}' berhasil ditambahkan ke sistem!");
    }

    public function deleteUser(User $user)
    {
        if ($user->role === 'super_admin') {
            return back()->with('error', '❌ Akun Super Admin tidak bisa dihapus!');
        }
        $name = $user->name;
        $user->delete();
        return redirect()->route('admin.users')
            ->with('warning', "🗑️ Akun '{$name}' berhasil dihapus dari sistem.");
    }
}
