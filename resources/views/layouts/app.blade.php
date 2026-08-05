<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'E-Layanan APTIKA - Diskominfo Kabupaten Jombang')</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            blue: '#0f2c59',
                            emerald: '#059669',
                            cyan: '#0891b2',
                            gold: '#d97706'
                        }
                    }
                }
            }
        }
    </script>
    
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Google Fonts & FontAwesome -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
        }
        h1, h2, h3, h4 {
            font-family: 'Outfit', sans-serif;
        }
        .hero-bg {
            background: linear-gradient(135deg, #0f2c59 0%, #1e3a8a 50%, #0369a1 100%);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-900 flex flex-col min-h-screen">

    <!-- Top Official Government Header Bar -->
    <div class="bg-slate-900 text-slate-300 text-xs py-2 px-4 border-b border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2">
            <span class="font-semibold text-slate-300 flex items-center gap-2">
                <i class="fa-solid fa-building-columns text-emerald-400"></i>
                <span>Pemerintah Kabupaten Jombang • <strong>Diskominfo Bidang APTIKA</strong></span>
            </span>

            <div class="flex items-center gap-3">
                @auth
                    <span class="text-slate-400 text-3xs font-mono">
                        <i class="fa-solid fa-building me-1 text-slate-500"></i> {{ Auth::user()->opd_name }}
                    </span>
                @endauth

                {{-- Reset Data Demo Link --}}
                <a href="{{ route('reset.demo') }}"
                   onclick="return confirm('Reset semua data tiket database ke kondisi awal demo? All changes will be re-seeded.')"
                   class="px-2.5 py-0.5 rounded-full bg-amber-600/30 hover:bg-amber-600 text-amber-300 hover:text-white transition font-semibold border border-amber-500/50 flex items-center gap-1 text-3xs">
                    <i class="fa-solid fa-rotate-left"></i> Reset DB Demo
                </a>
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar — Custom (no Bootstrap collapse conflict with Tailwind) -->
    <nav style="background:#fff; border-bottom:1px solid #e2e8f0; box-shadow:0 1px 4px rgba(0,0,0,0.05); position:sticky; top:0; z-index:998;">
        <div style="max-width:1280px; margin:0 auto; padding:10px 24px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:8px;">

            {{-- Brand --}}
            <a href="{{ route('home') }}" style="display:flex; align-items:center; gap:10px; text-decoration:none;">
                <div style="width:40px; height:40px; border-radius:10px; background:linear-gradient(135deg,#0f2c59,#1e40af); color:#fff; display:flex; align-items:center; justify-content:center; font-size:1.2rem; box-shadow:0 3px 8px rgba(15,44,89,.25);">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <div>
                    <span style="font-family:'Outfit',sans-serif; font-weight:800; font-size:1.15rem; color:#0f2c59; letter-spacing:-0.02em;">E-LAYANAN <span style="color:#059669;">APTIKA</span></span><br>
                    <span style="font-size:0.7rem; color:#64748b; font-weight:500;">Diskominfo Kabupaten Jombang</span>
                </div>
            </a>

            {{-- Navigation Links (Role-Based) --}}
            <div style="display:flex; align-items:center; gap:4px; flex-wrap:wrap;">

                {{-- Semua role: Beranda & Katalog selalu tampil --}}
                <a href="{{ route('home') }}"
                   style="display:inline-flex; align-items:center; gap:6px; padding:7px 13px; border-radius:8px; font-size:0.85rem; font-weight:600; text-decoration:none; color:{{ request()->routeIs('home') ? '#0f2c59' : '#475569' }}; background:{{ request()->routeIs('home') ? '#eff6ff' : 'transparent' }};">
                    <i class="fa-solid fa-house" style="font-size:0.8rem;"></i> Beranda
                </a>
                <a href="{{ route('katalog') }}"
                   style="display:inline-flex; align-items:center; gap:6px; padding:7px 13px; border-radius:8px; font-size:0.85rem; font-weight:600; text-decoration:none; color:{{ request()->routeIs('katalog') ? '#0f2c59' : '#475569' }}; background:{{ request()->routeIs('katalog') ? '#eff6ff' : 'transparent' }};">
                    <i class="fa-solid fa-layer-group" style="font-size:0.8rem;"></i> Katalog Layanan
                </a>

                @auth
                    @php $role = Auth::user()->role; @endphp

                    {{-- OPD & Admin APTIKA: Buat Pengajuan --}}
                    @if(in_array($role, ['opd', 'admin_aptika']))
                        <a href="{{ route('pengajuan.form') }}"
                           style="display:inline-flex; align-items:center; gap:6px; padding:7px 13px; border-radius:8px; font-size:0.85rem; font-weight:600; text-decoration:none; color:{{ request()->routeIs('pengajuan.form') ? '#065f46' : '#059669' }}; background:{{ request()->routeIs('pengajuan.form') ? '#d1fae5' : 'transparent' }};">
                            <i class="fa-solid fa-plus-circle" style="font-size:0.8rem;"></i> Buat Pengajuan
                        </a>
                    @endif

                    {{-- Semua yang login: Daftar Tiket --}}
                    <a href="{{ route('tickets.index') }}"
                       style="display:inline-flex; align-items:center; gap:6px; padding:7px 13px; border-radius:8px; font-size:0.85rem; font-weight:600; text-decoration:none; color:{{ request()->routeIs('tickets.index') ? '#0f2c59' : '#475569' }}; background:{{ request()->routeIs('tickets.index') ? '#eff6ff' : 'transparent' }};">
                        <i class="fa-solid fa-ticket" style="font-size:0.8rem;"></i> Daftar Tiket
                    </a>

                    {{-- Hanya Verifikator APTIKA --}}
                    @if($role === 'admin_aptika')
                        <a href="{{ route('verifikasi') }}"
                           style="display:inline-flex; align-items:center; gap:6px; padding:7px 13px; border-radius:8px; font-size:0.85rem; font-weight:600; text-decoration:none; color:{{ request()->routeIs('verifikasi') ? '#92400e' : '#b45309' }}; background:{{ request()->routeIs('verifikasi') ? '#fef3c7' : 'transparent' }};">
                            <i class="fa-solid fa-list-check" style="font-size:0.8rem;"></i> Verifikasi APTIKA
                        </a>
                    @endif

                    {{-- Hanya Teknisi & Admin APTIKA --}}
                    @if(in_array($role, ['teknisi', 'admin_aptika']))
                        <a href="{{ route('workspace.tech') }}"
                           style="display:inline-flex; align-items:center; gap:6px; padding:7px 13px; border-radius:8px; font-size:0.85rem; font-weight:600; text-decoration:none; color:{{ request()->routeIs('workspace.tech') ? '#155e75' : '#0891b2' }}; background:{{ request()->routeIs('workspace.tech') ? '#e0f2fe' : 'transparent' }};">
                            <i class="fa-solid fa-sliders" style="font-size:0.8rem;"></i> Workspace Teknisi
                        </a>
                    @endif

                    {{-- Super Admin Access --}}
                    @if($role === 'super_admin')
                        <a href="{{ route('admin.dashboard') }}"
                           style="display:inline-flex; align-items:center; gap:6px; padding:7px 13px; border-radius:8px; font-size:0.85rem; font-weight:700; text-decoration:none; color:#fff; background:linear-gradient(135deg,#581c87,#6b21a8);">
                            <i class="fa-solid fa-crown" style="color:#fde047;"></i> Super Admin Center
                        </a>
                    @endif

                    {{-- Semua yang login: SLA & SPBE --}}
                    <a href="{{ route('analytics') }}"
                       style="display:inline-flex; align-items:center; gap:6px; padding:7px 13px; border-radius:8px; font-size:0.85rem; font-weight:600; text-decoration:none; color:{{ request()->routeIs('analytics') ? '#0f2c59' : '#475569' }}; background:{{ request()->routeIs('analytics') ? '#eff6ff' : 'transparent' }};">
                        <i class="fa-solid fa-chart-line" style="font-size:0.8rem;"></i> SLA & SPBE
                    </a>

                    {{-- Profil & Logout --}}
                    <div style="display:inline-flex; align-items:center; gap:8px; margin-left:8px; padding-left:8px; border-left:1px solid #cbd5e1;">
                        <div style="font-size:0.78rem; line-height:1.2;">
                            <div style="font-weight:700; color:#0f2c59;">
                                <i class="fa-solid fa-circle-user" style="color:#059669;"></i>
                                {{ Auth::user()->name }}
                            </div>
                            <div style="font-size:0.68rem; color:#94a3b8; text-transform:uppercase; font-weight:600; letter-spacing:0.05em;">
                                {{ match(Auth::user()->role) {
                                    'super_admin'  => '🔑 Super Admin (Developer)',
                                    'admin_aptika' => '🛡️ Verifikator APTIKA',
                                    'teknisi'      => '💻 Staf Teknisi',
                                    'kabid'        => '📊 Kepala Bidang',
                                    default        => '🏢 Admin OPD'
                                } }}
                            </div>
                        </div>
                        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" style="background:#fee2e2; color:#991b1b; border:none; padding:5px 11px; border-radius:7px; font-size:0.75rem; font-weight:700; cursor:pointer;">
                                <i class="fa-solid fa-right-from-bracket"></i> Keluar
                            </button>
                        </form>
                    </div>

                @else
                    {{-- Belum login --}}
                    <a href="{{ route('login') }}"
                       style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:8px; font-size:0.8rem; font-weight:700; text-decoration:none; color:#fff; background:#0f2c59; margin-left:8px;">
                        <i class="fa-solid fa-right-to-bracket"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}"
                       style="display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:8px; font-size:0.8rem; font-weight:700; text-decoration:none; color:#059669; background:#d1fae5;">
                        <i class="fa-solid fa-user-plus"></i> Daftar OPD
                    </a>
                @endauth
            </div>
        </div>
    </nav>


    <!-- Success Toast Alert -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 bg-emerald-100 text-emerald-900 font-semibold" role="alert">
                <i class="fa-solid fa-circle-check me-2 text-emerald-600"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif

    <!-- Main Content Area -->
    <main class="flex-1 py-8">
        <div class="max-w-7xl mx-auto px-4">
            @yield('content')
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-8 border-t border-slate-800 text-sm">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center font-bold">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div>
                    <p class="font-bold text-white mb-0">Diskominfo Kabupaten Jombang</p>
                    <p class="text-xs text-slate-500 mb-0">Bidang Aplikasi dan Informatika (APTIKA) • Laravel 11 Edition</p>
                </div>
            </div>
            <p class="text-xs text-slate-500 mb-0">© 2026 Pemkab Jombang. Proyek Magang D3 TI Universitas Brawijaya.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
