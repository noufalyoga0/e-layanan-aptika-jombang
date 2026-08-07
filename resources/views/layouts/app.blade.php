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
        .nav-link-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 0.83rem;
            font-weight: 600;
            text-decoration: none;
            color: #475569;
            transition: background 0.15s;
            white-space: nowrap;
        }
        .nav-link-item:hover { background: #f1f5f9; color: #0f2c59; }
        .nav-link-item.active { background: #eff6ff; color: #0f2c59; }
        .mobile-nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 600;
            text-decoration: none;
            color: #475569;
            transition: background 0.15s;
        }
        .mobile-nav-item:hover { background: #f1f5f9; color: #0f2c59; }
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
            </div>
        </div>
    </div>

    <!-- Main Navigation Bar — Responsive with Hamburger -->
    <nav class="bg-white border-b border-slate-200 sticky top-0 z-50" style="box-shadow:0 1px 4px rgba(0,0,0,0.05);">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between h-16">

            {{-- Brand --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 no-underline flex-shrink-0">
                <div style="width:38px;height:38px;border-radius:10px;background:linear-gradient(135deg,#0f2c59,#1e40af);color:#fff;display:flex;align-items:center;justify-content:center;font-size:1.1rem;box-shadow:0 3px 8px rgba(15,44,89,.25);">
                    <i class="fa-solid fa-network-wired"></i>
                </div>
                <div class="leading-tight">
                    <span style="font-family:'Outfit',sans-serif;font-weight:800;font-size:1.05rem;color:#0f2c59;letter-spacing:-0.02em;">E-LAYANAN <span style="color:#059669;">APTIKA</span></span><br>
                    <span class="text-slate-500 hidden sm:inline" style="font-size:0.65rem;font-weight:500;">Diskominfo Kabupaten Jombang</span>
                </div>
            </a>

            {{-- Desktop Nav Links --}}
            <div class="hidden lg:flex items-center gap-1">
                <a href="{{ route('home') }}" class="nav-link-item {{ request()->routeIs('home') ? 'active' : '' }}">
                    <i class="fa-solid fa-house"></i> Beranda
                </a>
                <a href="{{ route('katalog') }}" class="nav-link-item {{ request()->routeIs('katalog') ? 'active' : '' }}">
                    <i class="fa-solid fa-layer-group"></i> Katalog
                </a>

                @auth
                    @php $role = Auth::user()->role; @endphp

                    {{-- OPD: Buat Pengajuan --}}
                    @if($role === 'opd')
                        <a href="{{ route('pengajuan.form') }}" class="nav-link-item text-emerald-700 {{ request()->routeIs('pengajuan.form') ? 'bg-emerald-50' : '' }}">
                            <i class="fa-solid fa-plus-circle"></i> Buat Pengajuan
                        </a>
                    @endif

                    {{-- Semua login kecuali super_admin: Daftar Tiket --}}
                    @if($role !== 'super_admin')
                        <a href="{{ route('tickets.index') }}" class="nav-link-item {{ request()->routeIs('tickets.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-ticket"></i> Daftar Tiket
                        </a>
                    @endif

                    {{-- Verifikator APTIKA --}}
                    @if($role === 'admin_aptika')
                        <a href="{{ route('verifikasi') }}" class="nav-link-item text-amber-700 {{ request()->routeIs('verifikasi') ? 'bg-amber-50' : '' }}">
                            <i class="fa-solid fa-list-check"></i> Verifikasi
                        </a>
                    @endif

                    {{-- Teknisi --}}
                    @if($role === 'teknisi')
                        <a href="{{ route('workspace.tech') }}" class="nav-link-item text-sky-700 {{ request()->routeIs('workspace.tech') ? 'bg-sky-50' : '' }}">
                            <i class="fa-solid fa-sliders"></i> Workspace
                        </a>
                    @endif

                    {{-- Kabid: Dashboard Eksekutif --}}
                    @if($role === 'kabid')
                        <a href="{{ route('kabid.dashboard') }}" class="nav-link-item text-indigo-700 {{ request()->routeIs('kabid.dashboard') ? 'bg-indigo-50' : '' }}">
                            <i class="fa-solid fa-gauge-high"></i> Dashboard Kabid
                        </a>
                    @endif

                    {{-- SLA & SPBE: semua kecuali super_admin --}}
                    @if($role !== 'super_admin')
                        <a href="{{ route('analytics') }}" class="nav-link-item {{ request()->routeIs('analytics') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-line"></i> SLA & SPBE
                        </a>
                    @endif

                    {{-- Super Admin: hanya panel admin --}}
                    @if($role === 'super_admin')
                        <a href="{{ route('admin.dashboard') }}" class="nav-link-item text-white font-bold" style="background:linear-gradient(135deg,#581c87,#6b21a8);">
                            <i class="fa-solid fa-crown" style="color:#fde047;"></i> Admin Panel
                        </a>
                        <a href="{{ route('tickets.index') }}" class="nav-link-item {{ request()->routeIs('tickets.index') ? 'active' : '' }}">
                            <i class="fa-solid fa-ticket"></i> Daftar Tiket
                        </a>
                        <a href="{{ route('analytics') }}" class="nav-link-item {{ request()->routeIs('analytics') ? 'active' : '' }}">
                            <i class="fa-solid fa-chart-line"></i> SLA & SPBE
                        </a>
                    @endif
                @endauth
            </div>

            {{-- Desktop: User Info + Logout --}}
            <div class="hidden lg:flex items-center gap-3 border-l border-slate-200 pl-3 ml-1">
                @auth
                    <div class="text-right leading-tight">
                        <div class="font-bold text-blue-950 text-sm">
                            <i class="fa-solid fa-circle-user text-emerald-500 me-1"></i>{{ Auth::user()->name }}
                        </div>
                        <div class="text-slate-400 font-semibold uppercase tracking-wider" style="font-size:0.62rem;">
                            {{ match(Auth::user()->role) {
                                'super_admin'  => '🔑 Super Admin',
                                'admin_aptika' => '🛡️ Verifikator APTIKA',
                                'teknisi'      => '💻 Staf Teknisi',
                                'kabid'        => '📊 Kepala Bidang',
                                default        => '🏢 Admin OPD'
                            } }}
                        </div>
                    </div>
                    <form action="{{ route('logout', [], false) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-lg border-0 cursor-pointer" style="background:#fee2e2;color:#991b1b;">
                            <i class="fa-solid fa-right-from-bracket"></i> Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold px-4 py-2 rounded-lg text-white no-underline" style="background:#0f2c59;">
                        <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk
                    </a>
                    <a href="{{ route('register') }}" class="text-sm font-bold px-4 py-2 rounded-lg no-underline" style="color:#059669;background:#d1fae5;">
                        <i class="fa-solid fa-user-plus me-1"></i> Daftar
                    </a>
                @endauth
            </div>

            {{-- Hamburger Button (mobile only) --}}
            <button id="nav-hamburger" class="lg:hidden flex items-center justify-center w-10 h-10 rounded-xl text-slate-600 hover:bg-slate-100 transition border-0 bg-transparent" aria-label="Menu">
                <i class="fa-solid fa-bars text-lg" id="hamburger-icon"></i>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div id="mobile-menu" class="hidden lg:hidden border-t border-slate-100 bg-white px-4 pb-4 pt-2">
            <div class="flex flex-col gap-1">
                <a href="{{ route('home') }}" class="mobile-nav-item {{ request()->routeIs('home') ? 'bg-blue-50 text-blue-900' : '' }}">
                    <i class="fa-solid fa-house w-5 text-center"></i> Beranda
                </a>
                <a href="{{ route('katalog') }}" class="mobile-nav-item {{ request()->routeIs('katalog') ? 'bg-blue-50 text-blue-900' : '' }}">
                    <i class="fa-solid fa-layer-group w-5 text-center"></i> Katalog Layanan
                </a>

                @auth
                    @php $role = Auth::user()->role; @endphp

                    @if($role === 'opd')
                        <a href="{{ route('pengajuan.form') }}" class="mobile-nav-item text-emerald-700 {{ request()->routeIs('pengajuan.form') ? 'bg-emerald-50' : '' }}">
                            <i class="fa-solid fa-plus-circle w-5 text-center"></i> Buat Pengajuan
                        </a>
                    @endif

                    @if($role !== 'super_admin')
                        <a href="{{ route('tickets.index') }}" class="mobile-nav-item {{ request()->routeIs('tickets.index') ? 'bg-blue-50 text-blue-900' : '' }}">
                            <i class="fa-solid fa-ticket w-5 text-center"></i> Daftar Tiket
                        </a>
                    @endif

                    @if($role === 'admin_aptika')
                        <a href="{{ route('verifikasi') }}" class="mobile-nav-item text-amber-700 {{ request()->routeIs('verifikasi') ? 'bg-amber-50' : '' }}">
                            <i class="fa-solid fa-list-check w-5 text-center"></i> Verifikasi APTIKA
                        </a>
                    @endif

                    @if($role === 'teknisi')
                        <a href="{{ route('workspace.tech') }}" class="mobile-nav-item text-sky-700 {{ request()->routeIs('workspace.tech') ? 'bg-sky-50' : '' }}">
                            <i class="fa-solid fa-sliders w-5 text-center"></i> Workspace Teknisi
                        </a>
                    @endif

                    @if($role === 'kabid')
                        <a href="{{ route('kabid.dashboard') }}" class="mobile-nav-item text-indigo-700 {{ request()->routeIs('kabid.dashboard') ? 'bg-indigo-50' : '' }}">
                            <i class="fa-solid fa-gauge-high w-5 text-center"></i> Dashboard Kabid
                        </a>
                    @endif

                    @if($role !== 'super_admin')
                        <a href="{{ route('analytics') }}" class="mobile-nav-item {{ request()->routeIs('analytics') ? 'bg-blue-50 text-blue-900' : '' }}">
                            <i class="fa-solid fa-chart-line w-5 text-center"></i> SLA & SPBE
                        </a>
                    @endif

                    @if($role === 'super_admin')
                        <a href="{{ route('admin.dashboard') }}" class="mobile-nav-item font-bold" style="color:#7c3aed;">
                            <i class="fa-solid fa-crown w-5 text-center" style="color:#d97706;"></i> Admin Panel
                        </a>
                        <a href="{{ route('tickets.index') }}" class="mobile-nav-item {{ request()->routeIs('tickets.index') ? 'bg-blue-50 text-blue-900' : '' }}">
                            <i class="fa-solid fa-ticket w-5 text-center"></i> Daftar Tiket
                        </a>
                        <a href="{{ route('analytics') }}" class="mobile-nav-item {{ request()->routeIs('analytics') ? 'bg-blue-50 text-blue-900' : '' }}">
                            <i class="fa-solid fa-chart-line w-5 text-center"></i> SLA & SPBE
                        </a>
                    @endif

                    <div class="mt-2 pt-2 border-t border-slate-100 flex items-center justify-between">
                        <div class="text-xs font-bold text-slate-700">
                            <i class="fa-solid fa-circle-user text-emerald-500 me-1"></i>
                            {{ Auth::user()->name }}
                            <span class="block text-slate-400 font-normal text-3xs mt-0.5">{{ Auth::user()->opd_name }}</span>
                        </div>
                        <form action="{{ route('logout', [], false) }}" method="POST">
                            @csrf
                            <button type="submit" class="text-xs font-bold px-3 py-1.5 rounded-lg border-0 cursor-pointer" style="background:#fee2e2;color:#991b1b;">
                                <i class="fa-solid fa-right-from-bracket me-1"></i> Keluar
                            </button>
                        </form>
                    </div>

                @else
                    <div class="flex gap-2 mt-2">
                        <a href="{{ route('login') }}" class="flex-1 text-center text-sm font-bold px-4 py-2 rounded-xl text-white no-underline" style="background:#0f2c59;">
                            <i class="fa-solid fa-right-to-bracket me-1"></i> Masuk
                        </a>
                        <a href="{{ route('register') }}" class="flex-1 text-center text-sm font-bold px-4 py-2 rounded-xl no-underline" style="color:#059669;background:#d1fae5;">
                            <i class="fa-solid fa-user-plus me-1"></i> Daftar OPD
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </nav>


    <!-- Flash Alerts (semua halaman) -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 bg-emerald-100 text-emerald-900 font-semibold rounded-2xl" role="alert">
                <i class="fa-solid fa-circle-check me-2 text-emerald-600"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('warning'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="alert alert-warning alert-dismissible fade show shadow-sm border-0 bg-amber-100 text-amber-900 font-semibold rounded-2xl" role="alert">
                <i class="fa-solid fa-triangle-exclamation me-2 text-amber-600"></i> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if(session('error'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 bg-rose-100 text-rose-900 font-semibold rounded-2xl" role="alert">
                <i class="fa-solid fa-circle-xmark me-2 text-rose-600"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    @endif
    @if($errors->any())
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="alert alert-danger alert-dismissible fade show shadow-sm border-0 bg-rose-100 text-rose-900 font-semibold rounded-2xl" role="alert">
                <i class="fa-solid fa-circle-xmark me-2 text-rose-600"></i> {{ $errors->first() }}
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
                    <p class="text-xs text-slate-500 mb-0">Bidang Aplikasi dan Informatika (APTIKA) • Diskominfo Kabupaten Jombang</p>
                </div>
            </div>
            <p class="text-xs text-slate-500 mb-0">© {{ date('Y') }} Pemerintah Kabupaten Jombang. Hak cipta dilindungi.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Hamburger menu toggle
        document.addEventListener('DOMContentLoaded', function () {
            const btn = document.getElementById('nav-hamburger');
            const menu = document.getElementById('mobile-menu');
            const icon = document.getElementById('hamburger-icon');
            if (btn && menu) {
                btn.addEventListener('click', function () {
                    const isOpen = !menu.classList.contains('hidden');
                    menu.classList.toggle('hidden', isOpen);
                    icon.className = isOpen ? 'fa-solid fa-bars text-lg' : 'fa-solid fa-xmark text-lg';
                });
            }
            // Aktifkan semua Bootstrap tooltips
            document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(function (el) {
                new bootstrap.Tooltip(el);
            });
        });
    </script>

    <script>
    (function () {
        function bindNumericOnly(input) {
            if (input.dataset.numericBound) return;
            input.dataset.numericBound = '1';

            input.addEventListener('input', function () {
                if (!this.hasAttribute('data-numeric-only')) return;
                this.value = this.value.replace(/\D/g, '');
            });

            input.addEventListener('paste', function (e) {
                if (!this.hasAttribute('data-numeric-only')) return;
                e.preventDefault();
                var text = (e.clipboardData || window.clipboardData).getData('text') || '';
                this.value = (this.value + text).replace(/\D/g, '');
            });
        }

        function setNumericMode(input, enabled) {
            if (enabled) {
                input.setAttribute('data-numeric-only', '');
                input.setAttribute('inputmode', 'numeric');
                input.setAttribute('pattern', '[0-9]*');
                bindNumericOnly(input);
            } else {
                input.removeAttribute('data-numeric-only');
                input.removeAttribute('inputmode');
                input.removeAttribute('pattern');
            }
        }

        function initNumericInputs(root) {
            (root || document).querySelectorAll('[data-numeric-only]').forEach(bindNumericOnly);
        }

        document.addEventListener('DOMContentLoaded', function () {
            initNumericInputs();
        });

        window.bindNumericOnly = bindNumericOnly;
        window.setNumericMode = setNumericMode;
        window.initNumericInputs = initNumericInputs;
    })();
    </script>
</body>
</html>
