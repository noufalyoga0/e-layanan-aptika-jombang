@extends('layouts.app')

@section('title', 'Beranda - E-Layanan APTIKA Pemkab Jombang')

@section('content')

    {{-- ================================================================
         HERO SECTION — Glassmorphism + Animated gradient mesh
    ================================================================ --}}
    <div class="hero-section relative rounded-3xl overflow-hidden mb-10 shadow-2xl" style="min-height:420px;">

        {{-- Animated gradient background --}}
        <div class="absolute inset-0 hero-gradient"></div>

        {{-- Decorative orbs --}}
        <div class="absolute top-[-60px] right-[-60px] w-72 h-72 rounded-full opacity-20 orb-1"></div>
        <div class="absolute bottom-[-40px] left-[10%] w-48 h-48 rounded-full opacity-15 orb-2"></div>
        <div class="absolute top-[30%] right-[20%] w-32 h-32 rounded-full opacity-10 orb-3"></div>

        {{-- Grid pattern overlay --}}
        <div class="absolute inset-0 grid-pattern"></div>

        {{-- Content --}}
        <div class="relative z-10 p-8 md:p-14">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glass-badge text-xs font-bold text-cyan-200 mb-6 animate-fade-in-down">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                SPBE Kabupaten Jombang · Indeks 3,91 (Sangat Baik)
            </div>

            {{-- Heading --}}
            <h1 class="text-3xl md:text-5xl font-extrabold tracking-tight mb-4 text-white animate-fade-in-up" style="line-height:1.15;">
                Digitalisasi Layanan
                <span class="gradient-text-cyan block md:inline">APTIKA</span>
                <span class="block text-slate-200">Pemkab Jombang</span>
            </h1>

            <p class="text-slate-300 text-base md:text-lg max-w-2xl mb-8 leading-relaxed animate-fade-in-up" style="animation-delay:.1s;">
                Portal terpadu pengajuan Subdomain, TTE BSRE, Integrasi API, dan Helpdesk IT untuk seluruh OPD Kabupaten Jombang.
            </p>

            {{-- Search Card --}}
            <div class="glass-card p-6 rounded-2xl max-w-2xl animate-fade-in-up" style="animation-delay:.2s;">
                <h3 class="text-sm font-bold text-white mb-3 flex items-center gap-2">
                    <i class="fa-solid fa-magnifying-glass text-cyan-300"></i> Lacak Status Tiket Layanan
                </h3>
                <form action="{{ route('tickets.index', [], false) }}" method="GET" class="flex flex-col sm:flex-row gap-2 mb-2">
                    <input type="text" name="search"
                        class="form-control border-0 text-slate-900 rounded-xl shadow-sm text-sm flex-1"
                        placeholder="Masukkan Nomor Resi Tiket (REQ-JBG-...)">
                    <button type="submit" class="btn px-5 rounded-xl font-bold text-sm flex-shrink-0 btn-glow">
                        <i class="fa-solid fa-magnifying-glass me-1"></i> Lacak
                    </button>
                </form>
                <p class="text-xs text-slate-400">Format: REQ-JBG-202608-001</p>
            </div>
        </div>
    </div>

    {{-- ================================================================
         STATS SECTION — Animated counters
    ================================================================ --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-12">
        @php
            $statsData = [
                ['value' => $stats['total'],                                                                     'label' => 'Total Pengajuan',      'icon' => 'fa-folder-open',       'color' => 'blue'],
                ['value' => $stats['selesai'],                                                                   'label' => 'Selesai & BAST',       'icon' => 'fa-circle-check',      'color' => 'emerald'],
                ['value' => $stats['menunggu'] + $stats['diproses'],                                             'label' => 'Sedang Diproses',      'icon' => 'fa-clock-rotate-left', 'color' => 'amber'],
                ['value' => $stats['total'] > 0 ? round(($stats['selesai'] / $stats['total']) * 100) : 100,    'label' => 'Tingkat Selesai',      'icon' => 'fa-shield-check',      'color' => 'purple', 'suffix' => '%'],
            ];
            $colorMap = [
                'blue'    => ['bg' => 'bg-blue-50',    'icon' => 'text-blue-600',    'border' => 'border-blue-200',    'num' => 'text-blue-700'],
                'emerald' => ['bg' => 'bg-emerald-50', 'icon' => 'text-emerald-600', 'border' => 'border-emerald-200', 'num' => 'text-emerald-700'],
                'amber'   => ['bg' => 'bg-amber-50',   'icon' => 'text-amber-600',   'border' => 'border-amber-200',   'num' => 'text-amber-700'],
                'purple'  => ['bg' => 'bg-purple-50',  'icon' => 'text-purple-600',  'border' => 'border-purple-200',  'num' => 'text-purple-700'],
            ];
        @endphp

        @foreach($statsData as $i => $s)
            @php $c = $colorMap[$s['color']]; @endphp
            <div class="stat-card card border {{ $c['border'] }} {{ $c['bg'] }} rounded-2xl p-5 shadow-sm hover:shadow-md transition-all duration-300 hover:-translate-y-1 reveal-on-scroll"
                 style="animation-delay: {{ $i * 0.08 }}s">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center {{ $c['icon'] }} text-lg">
                        <i class="fa-solid {{ $s['icon'] }}"></i>
                    </div>
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider leading-tight">{{ $s['label'] }}</span>
                </div>
                <div class="text-3xl font-extrabold {{ $c['num'] }} counter" data-target="{{ $s['value'] }}">0</div>
                @if(isset($s['suffix']))
                    <script>document.currentScript.previousElementSibling.dataset.suffix = "{{ $s['suffix'] }}"</script>
                @endif
            </div>
        @endforeach
    </div>

    {{-- ================================================================
         SERVICE CATALOG — Cards with hover effects
    ================================================================ --}}
    <div class="mb-8">
        <div class="flex items-end justify-between mb-6">
            <div>
                <h2 class="text-2xl font-extrabold text-blue-950 mb-1 reveal-on-scroll">
                    Katalog Layanan Digital APTIKA
                </h2>
                <p class="text-slate-500 text-sm reveal-on-scroll">Layanan resmi Diskominfo Kabupaten Jombang untuk seluruh OPD</p>
            </div>
            <a href="{{ route('katalog') }}" class="text-xs font-bold text-blue-800 hover:underline hidden md:block reveal-on-scroll">
                Lihat semua →
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
            @php
                $serviceColors = [
                    ['from' => '#0f2c59', 'to' => '#1e40af', 'light' => 'bg-blue-50',   'icon_color' => 'text-blue-700'],
                    ['from' => '#065f46', 'to' => '#059669', 'light' => 'bg-emerald-50', 'icon_color' => 'text-emerald-700'],
                    ['from' => '#0c4a6e', 'to' => '#0891b2', 'light' => 'bg-cyan-50',   'icon_color' => 'text-cyan-700'],
                    ['from' => '#7c2d12', 'to' => '#ea580c', 'light' => 'bg-orange-50', 'icon_color' => 'text-orange-700'],
                ];
            @endphp

            @foreach($services as $i => $srv)
                @php $sc = $serviceColors[$i % 4]; @endphp
                <div class="service-card group card border-0 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-400 reveal-on-scroll flex flex-col"
                     style="animation-delay: {{ $i * 0.1 }}s">

                    {{-- Card top color strip --}}
                    <div class="h-1.5 w-full" style="background: linear-gradient(90deg, {{ $sc['from'] }}, {{ $sc['to'] }});"></div>

                    <div class="p-6 bg-white flex flex-col flex-1">
                        {{-- Icon --}}
                        <div class="w-14 h-14 rounded-2xl {{ $sc['light'] }} {{ $sc['icon_color'] }} flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform duration-300">
                            <i class="fa-solid {{ $srv['icon'] }}"></i>
                        </div>

                        {{-- Content --}}
                        <h3 class="text-base font-bold text-slate-900 mb-2 group-hover:text-blue-900 transition-colors">{{ $srv['name'] }}</h3>
                        <p class="text-xs text-slate-500 mb-4 leading-relaxed flex-1">{{ $srv['desc'] }}</p>

                        {{-- SLA badge --}}
                        <div class="flex items-center justify-between mb-4">
                            <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500">
                                <i class="fa-regular fa-clock text-slate-400"></i> SLA: {{ $srv['sla'] }}
                            </span>
                        </div>

                        {{-- CTA Button --}}
                        <a href="{{ route('pengajuan.form') }}?service={{ $srv['id'] }}"
                           class="btn w-100 font-bold text-xs py-2.5 rounded-xl border-0 text-white transition-all duration-300 group-hover:shadow-lg"
                           style="background: linear-gradient(135deg, {{ $sc['from'] }}, {{ $sc['to'] }});">
                            <i class="fa-solid fa-paper-plane me-1.5 group-hover:translate-x-1 transition-transform"></i>
                            Ajukan Layanan
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- ================================================================
         ANIMASI & STYLES
    ================================================================ --}}
    <style>
        /* Hero */
        .hero-gradient {
            background: linear-gradient(135deg, #0a1628 0%, #0f2c59 35%, #1e3a8a 65%, #0369a1 100%);
        }
        .orb-1 { background: radial-gradient(circle, #3b82f6, #1d4ed8); }
        .orb-2 { background: radial-gradient(circle, #06b6d4, #0891b2); }
        .orb-3 { background: radial-gradient(circle, #8b5cf6, #6d28d9); }
        .grid-pattern {
            background-image: linear-gradient(rgba(255,255,255,.03) 1px, transparent 1px),
                              linear-gradient(90deg, rgba(255,255,255,.03) 1px, transparent 1px);
            background-size: 40px 40px;
        }
        .glass-badge {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            backdrop-filter: blur(8px);
        }
        .glass-card {
            background: rgba(255,255,255,.08);
            border: 1px solid rgba(255,255,255,.15);
            backdrop-filter: blur(12px);
        }
        .btn-glow {
            background: linear-gradient(135deg, #059669, #0891b2);
            color: #fff;
            box-shadow: 0 0 20px rgba(5,150,105,.4);
            transition: box-shadow .3s, transform .2s;
        }
        .btn-glow:hover {
            box-shadow: 0 0 30px rgba(5,150,105,.6);
            transform: translateY(-1px);
            color: #fff;
        }
        .gradient-text-cyan {
            background: linear-gradient(135deg, #22d3ee, #06b6d4, #67e8f9);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        /* Stat cards */
        .stat-card { cursor: default; }

        /* Service cards */
        .service-card { transition: transform .3s cubic-bezier(.34,1.56,.64,1), box-shadow .3s; }
        .service-card:hover { transform: translateY(-6px); }

        /* Fade-in animations */
        @keyframes fadeInDown {
            from { opacity:0; transform:translateY(-16px); }
            to   { opacity:1; transform:translateY(0); }
        }
        @keyframes fadeInUp {
            from { opacity:0; transform:translateY(20px); }
            to   { opacity:1; transform:translateY(0); }
        }
        .animate-fade-in-down { animation: fadeInDown .6s ease both; }
        .animate-fade-in-up   { animation: fadeInUp .6s ease both; }

        /* Reveal on scroll */
        .reveal-on-scroll {
            opacity: 0;
            transform: translateY(24px);
            transition: opacity .6s ease, transform .6s ease;
        }
        .reveal-on-scroll.revealed {
            opacity: 1;
            transform: translateY(0);
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function () {

        // ── Animated counters ──────────────────────────────────────────
        function animateCounter(el) {
            const target = parseInt(el.dataset.target) || 0;
            const suffix = el.dataset.suffix || '';
            const duration = 1200;
            const start = performance.now();
            function update(now) {
                const elapsed = now - start;
                const progress = Math.min(elapsed / duration, 1);
                const ease = 1 - Math.pow(1 - progress, 3);
                el.textContent = Math.round(ease * target) + suffix;
                if (progress < 1) requestAnimationFrame(update);
            }
            requestAnimationFrame(update);
        }

        // ── Reveal on scroll (IntersectionObserver) ───────────────────
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('revealed');
                    // Trigger counter jika ini stat card
                    const counter = entry.target.querySelector('.counter');
                    if (counter && !counter.dataset.animated) {
                        counter.dataset.animated = '1';
                        animateCounter(counter);
                    }
                    // Trigger counter langsung kalau elemennya sendiri yang punya counter
                    if (entry.target.classList.contains('counter') && !entry.target.dataset.animated) {
                        entry.target.dataset.animated = '1';
                        animateCounter(entry.target);
                    }
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        document.querySelectorAll('.reveal-on-scroll').forEach(el => observer.observe(el));
        document.querySelectorAll('.stat-card').forEach(el => observer.observe(el));
    });
    </script>

@endsection
