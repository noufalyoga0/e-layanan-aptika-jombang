<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            $redirectRoute = match ($user->role) {
                'super_admin'  => 'admin.dashboard',
                'admin_aptika' => 'verifikasi',
                'teknisi'      => 'workspace.tech',
                'kabid'        => 'kabid.dashboard',
                default        => 'tickets.index',
            };

            return redirect()->route($redirectRoute)
                ->with('success', "Selamat datang kembali, {$user->name} ({$user->opd_name})!");
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'opd_name' => 'required|string|max:255',
            'nip'      => 'required|regex:/^\d+$/|max:30',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'nip.regex' => 'NIP hanya boleh berisi angka, tanpa huruf atau spasi.',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'opd_name' => $validated['opd_name'],
            'nip'      => $validated['nip'],
            'email'    => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role'     => 'opd',
        ]);

        Auth::login($user);

        return redirect()->route('pengajuan.form')
            ->with('success', "Registrasi berhasil! Akun OPD {$user->opd_name} aktif.");
    }

    public function showChangePasswordForm()
    {
        return view('auth.change_password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ], [
            'new_password.min'       => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        Auth::user()->update([
            'password'             => Hash::make($request->new_password),
            'must_change_password' => false,
        ]);

        return redirect()->intended(
            match (Auth::user()->role) {
                'super_admin'  => route('admin.dashboard'),
                'admin_aptika' => route('verifikasi'),
                'teknisi'      => route('workspace.tech'),
                'kabid'        => route('kabid.dashboard'),
                default        => route('tickets.index'),
            }
        )->with('success', '✅ Password berhasil diperbarui. Selamat datang!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }
}
