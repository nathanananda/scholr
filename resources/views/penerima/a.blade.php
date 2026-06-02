<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Scholr — Dashboard Penerima</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['Sora', 'sans-serif'],
                        body: ['DM Sans', 'sans-serif'],
                    },
                    colors: {
                        teal: {
                            50: '#E1F5EE',
                            100: '#9FE1CB',
                            200: '#5DCAA5',
                            300: '#1D9E75',
                            400: '#0F6E56',
                            500: '#0a5c48',
                            600: '#085041',
                            700: '#06453a',
                            800: '#043b30',
                            900: '#0d3b36',
                        },
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        h1,
        h2,
        h3,
        h4,
        .font-display {
            font-family: 'Sora', sans-serif;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .dot-bg {
            background-image: radial-gradient(#1D9E75 1px, transparent 1px);
            background-size: 22px 22px;
            opacity: 0.12;
        }

        .timeline-line-done {
            background: #1D9E75;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 overflow-hidden h-screen">

    <div class="flex h-screen overflow-hidden">

        <!-- ===== SIDEBAR ===== -->
        <aside class="w-60 min-w-[240px] bg-teal-900 flex flex-col overflow-hidden">

            <!-- Logo -->
            <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10">
                <span class="material-symbols-outlined text-cyan-300 text-2xl">school</span>
                <span class="font-display text-xl font-extrabold text-white tracking-tight">Scholr</span>
            </div>

            <!-- User -->
            <div class="flex items-center gap-3 px-5 py-4 border-b border-white/10">
                <div
                    class="w-9 h-9 rounded-full bg-teal-300 flex items-center justify-center font-display font-bold text-teal-900 text-sm shrink-0">
                    SA</div>
                <div>
                    <p class="text-white font-semibold text-sm leading-tight">Siti Aminah</p>
                    <p class="text-white/50 text-xs">Penerima Beasiswa</p>
                </div>
            </div>

            <!-- Nav -->
            <nav class="flex-1 px-3 py-3 flex flex-col gap-0.5 overflow-y-auto no-scrollbar">
                <p class="text-white/35 text-[10px] font-semibold uppercase tracking-widest px-2 pt-2 pb-1">Menu Utama
                </p>

                <button onclick="showPage('dashboard',this)" data-nav
                    class="nav-item w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-white/60 text-sm font-medium transition-all hover:bg-white/10 hover:text-white active-nav">
                    <span class="material-symbols-outlined text-[18px]">dashboard</span> Dashboard
                </button>

                <button onclick="showPage('beasiswa',this)" data-nav
                    class="nav-item w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-white/60 text-sm font-medium transition-all hover:bg-white/10 hover:text-white">
                    <span class="material-symbols-outlined text-[18px]">search</span> Cari Beasiswa
                    <span
                        class="ml-auto bg-cyan-300 text-teal-900 text-[10px] font-bold px-2 py-0.5 rounded-full">15k</span>
                </button>

                <button onclick="showPage('persyaratan',this)" data-nav
                    class="nav-item w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-white/60 text-sm font-medium transition-all hover:bg-white/10 hover:text-white">
                    <span class="material-symbols-outlined text-[18px]">task</span> Persyaratan
                    <span
                        class="ml-auto bg-cyan-300 text-teal-900 text-[10px] font-bold px-2 py-0.5 rounded-full">2</span>
                </button>

                <p class="text-white/35 text-[10px] font-semibold uppercase tracking-widest px-2 pt-4 pb-1">Akun</p>

                <button onclick="showPage('status',this)" data-nav
                    class="nav-item w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-white/60 text-sm font-medium transition-all hover:bg-white/10 hover:text-white">
                    <span class="material-symbols-outlined text-[18px]">timeline</span> Status Lamaran
                </button>

                <button onclick="showPage('profil',this)" data-nav
                    class="nav-item w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-white/60 text-sm font-medium transition-all hover:bg-white/10 hover:text-white">
                    <span class="material-symbols-outlined text-[18px]">person</span> Profil Saya
                </button>
            </nav>

            <!-- Logout -->
            <div class="px-3 py-3 border-t border-white/10">
                <button
                    class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-white/40 text-sm font-medium transition-all hover:bg-white/10 hover:text-white/70">
                    <span class="material-symbols-outlined text-[18px]">logout</span> Keluar
                </button>
            </div>
        </aside>

        <!-- ===== MAIN ===== -->
        <div class="flex-1 flex flex-col overflow-hidden">

            <!-- Topbar -->
            <header class="bg-white border-b border-slate-200 px-7 h-[60px] flex items-center justify-between shrink-0">
                <h1 id="page-title" class="font-display text-base font-bold text-teal-900">Dashboard</h1>
                <div class="flex items-center gap-3">
                    <div
                        class="flex items-center gap-2 bg-slate-100 border border-slate-200 rounded-lg px-3 py-1.5 text-slate-400 text-sm cursor-text">
                        <span class="material-symbols-outlined text-[16px]">search</span>
                        Cari beasiswa...
                    </div>
                    <div
                        class="relative w-9 h-9 flex items-center justify-center border border-slate-200 rounded-lg cursor-pointer hover:bg-slate-50">
                        <span class="material-symbols-outlined text-[18px] text-slate-500">notifications</span>
                        <span
                            class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                    </div>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-y-auto px-7 py-6 no-scrollbar">

                <!-- ===== PAGE: DASHBOARD ===== -->
                <section id="page-dashboard" class="page block">

                    <!-- Welcome card -->
                    <div class="relative bg-teal-900 rounded-2xl p-6 mb-6 overflow-hidden">
                        <div class="dot-bg absolute inset-0"></div>
                        <div class="absolute -right-8 -top-8 w-40 h-40 rounded-full bg-white/5"></div>
                        <div class="absolute right-12 -bottom-10 w-28 h-28 rounded-full bg-white/5"></div>
                        <div class="relative z-10">
                            <h2 class="font-display text-xl font-bold text-white mb-1">Selamat datang, Siti! 👋</h2>
                            <p class="text-white/60 text-sm mb-4">Lengkapi profilmu untuk meningkatkan peluang mendapat
                                beasiswa.</p>
                            <button onclick="showPage('beasiswa', document.querySelectorAll('[data-nav]')[1])"
                                class="inline-flex items-center gap-1.5 bg-cyan-300 text-teal-900 px-4 py-2 rounded-lg text-xs font-bold transition hover:bg-cyan-200">
                                <span class="material-symbols-outlined text-[14px]">search</span> Jelajahi Beasiswa
                            </button>
                            <div class="flex items-center gap-3 mt-4">
                                <div class="flex-1 bg-white/20 rounded-full h-1.5 overflow-hidden">
                                    <div class="bg-cyan-300 h-full rounded-full w-[65%]"></div>
                                </div>
                                <span class="text-white/70 text-xs whitespace-nowrap">65% profil lengkap</span>
                            </div>
                        </div>
                    </div>

                    <!-- Stats -->
                    <div class="grid grid-cols-3 gap-3.5 mb-6">
                        <div class="bg-white border border-slate-200 rounded-xl p-4">
                            <p
                                class="text-slate-500 text-[11px] font-semibold uppercase tracking-wide flex items-center gap-1.5 mb-2">
                                <span class="material-symbols-outlined text-[15px]">assignment</span> Lamaran Aktif
                            </p>
                            <p class="font-display text-3xl font-bold text-teal-900">3</p>
                            <p class="text-slate-400 text-[11px] mt-1">2 sedang diproses</p>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-xl p-4">
                            <p
                                class="text-slate-500 text-[11px] font-semibold uppercase tracking-wide flex items-center gap-1.5 mb-2">
                                <span class="material-symbols-outlined text-[15px]">emoji_events</span> Diterima
                            </p>
                            <p class="font-display text-3xl font-bold text-teal-900">1</p>
                            <p class="text-slate-400 text-[11px] mt-1">Djarum Plus 2024</p>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-xl p-4">
                            <p
                                class="text-slate-500 text-[11px] font-semibold uppercase tracking-wide flex items-center gap-1.5 mb-2">
                                <span class="material-symbols-outlined text-[15px]">star</span> Direkomendasikan
                            </p>
                            <p class="font-display text-3xl font-bold text-teal-900">8</p>
                            <p class="text-slate-400 text-[11px] mt-1">Cocok dengan profilmu</p>
                        </div>
                    </div>

                    <!-- Recent applications -->
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-display text-sm font-bold text-teal-900">Lamaran Terakhir</h3>
                        <button onclick="showPage('status', document.querySelectorAll('[data-nav]')[3])"
                            class="text-teal-400 text-xs font-semibold flex items-center gap-0.5 hover:text-teal-300">
                            Lihat semua <span class="material-symbols-outlined text-[13px]">chevron_right</span>
                        </button>
                    </div>

                    <div class="flex flex-col gap-2.5">
                        <!-- item -->
                        <div
                            class="bg-white border border-slate-200 rounded-xl px-4 py-3.5 flex items-center gap-3.5 hover:shadow-md transition-shadow cursor-pointer">
                            <div
                                class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-display font-bold text-teal-900 text-[11px] shrink-0">
                                DJ</div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-teal-900 truncate">Djarum Plus 2024</p>
                                <p class="text-slate-400 text-xs mt-0.5">Djarum Foundation</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span
                                    class="inline-flex items-center gap-1 bg-green-100 text-green-800 text-[11px] font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-600 inline-block"></span> Diterima
                                </span>
                                <p class="font-display font-bold text-xs text-teal-700 mt-1">Rp 12jt/Tahun</p>
                            </div>
                        </div>
                        <div
                            class="bg-white border border-slate-200 rounded-xl px-4 py-3.5 flex items-center gap-3.5 hover:shadow-md transition-shadow cursor-pointer">
                            <div
                                class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-display font-bold text-teal-900 text-[10px] shrink-0">
                                BUMN</div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-teal-900 truncate">Beasiswa Bakti BUMN</p>
                                <p class="text-slate-400 text-xs mt-0.5">Kementerian BUMN</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span
                                    class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 text-[11px] font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span> Seleksi
                                    Dokumen
                                </span>
                                <p class="font-display font-bold text-xs text-teal-700 mt-1">Full Coverage</p>
                            </div>
                        </div>
                        <div
                            class="bg-white border border-slate-200 rounded-xl px-4 py-3.5 flex items-center gap-3.5 hover:shadow-md transition-shadow cursor-pointer">
                            <div
                                class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-display font-bold text-teal-900 text-[11px] shrink-0">
                                FE</div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-teal-900 truncate">Foundation Excellence</p>
                                <p class="text-slate-400 text-xs mt-0.5">Excellence Foundation</p>
                            </div>
                            <div class="text-right shrink-0">
                                <span
                                    class="inline-flex items-center gap-1 bg-cyan-50 text-cyan-800 text-[11px] font-semibold px-2.5 py-1 rounded-full">
                                    <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 inline-block"></span> Draft
                                </span>
                                <p class="font-display font-bold text-xs text-teal-700 mt-1">USD 10,000</p>
                            </div>
                        </div>
                    </div>

                </section>

                <!-- ===== PAGE: BEASISWA ===== -->
                <section id="page-beasiswa" class="page hidden">

                    <!-- Filters -->
                    <div class="flex flex-wrap gap-2 mb-5">
                        <button
                            class="filter-btn bg-teal-900 text-white border-teal-900 border text-xs font-medium px-4 py-1.5 rounded-full transition-all">Semua</button>
                        <button
                            class="filter-btn bg-white text-slate-600 border border-slate-200 text-xs font-medium px-4 py-1.5 rounded-full hover:bg-teal-900 hover:text-white hover:border-teal-900 transition-all">S1</button>
                        <button
                            class="filter-btn bg-white text-slate-600 border border-slate-200 text-xs font-medium px-4 py-1.5 rounded-full hover:bg-teal-900 hover:text-white hover:border-teal-900 transition-all">S2
                            / S3</button>
                        <button
                            class="filter-btn bg-white text-slate-600 border border-slate-200 text-xs font-medium px-4 py-1.5 rounded-full hover:bg-teal-900 hover:text-white hover:border-teal-900 transition-all">Internasional</button>
                        <button
                            class="filter-btn bg-white text-slate-600 border border-slate-200 text-xs font-medium px-4 py-1.5 rounded-full hover:bg-teal-900 hover:text-white hover:border-teal-900 transition-all">Full
                            Coverage</button>
                        <button
                            class="filter-btn bg-white text-slate-600 border border-slate-200 text-xs font-medium px-4 py-1.5 rounded-full hover:bg-teal-900 hover:text-white hover:border-teal-900 transition-all">Sedang
                            Dibuka</button>
                    </div>

                    <!-- Grid -->
                    <div class="grid grid-cols-2 gap-4">

                        <!-- Card 1 -->
                        <div
                            class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:-translate-y-0.5 hover:shadow-lg transition-all cursor-pointer">
                            <div class="h-28 relative overflow-hidden">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuD1OliciBxnIhT2inzBJeaVEuOJa1DwVH6JcZi1wTI8zAzOu283B_KpckxDdfG1xJbUDIwuMLBXYObmj2eIokHa9wHCdqi-LdI7P96rEqe_xPjUoD45PIbC8LIZxv7ZHFbzY-AGk4TDyi-u_Wwp-JWkGg5QqbEEfkOygUftUbTCMoeqJvfBaxv2sSmFdwFKxWS0xlW5C4WbBgzJ_7T5CeYeNXQ_fEpaK0b-2MZbMLxfloN3DF0Gr53vuoo4iRc_VBwOSIdhiLPtd3s"
                                    alt="BUMN" class="w-full h-full object-cover" />
                                <span
                                    class="absolute top-2.5 right-2.5 bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Dibuka</span>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2.5">
                                    <div
                                        class="w-7 h-7 rounded-md bg-slate-100 flex items-center justify-center font-display font-bold text-teal-900 text-[9px]">
                                        BUMN</div>
                                    <span class="text-slate-400 text-[11px]">Kementerian BUMN</span>
                                </div>
                                <h3 class="font-display text-sm font-bold text-teal-900 mb-3 leading-snug">Beasiswa
                                    Bakti BUMN</h3>
                                <div class="flex justify-between pt-2.5 border-t border-slate-100">
                                    <div>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wide">Dana</p>
                                        <p class="text-xs font-bold text-teal-700">Full Coverage</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wide">Deadline</p>
                                        <p class="text-xs font-bold text-red-500">12 Okt 2024</p>
                                    </div>
                                </div>
                                <button
                                    class="w-full mt-3 py-2 bg-green-100 text-green-800 rounded-lg text-xs font-bold flex items-center justify-center gap-1.5">
                                    <span class="material-symbols-outlined text-[13px]">check</span> Sudah Mendaftar
                                </button>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div
                            class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:-translate-y-0.5 hover:shadow-lg transition-all cursor-pointer">
                            <div class="h-28 relative overflow-hidden">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuB1Plrn98w393Mcj7uatsMQcORahlFY2G3o1ixcTFcUeKpl1c-p7JP7Xwr5new5Oa76O0Cv5HLIQMxNlCUq1W8CXFVgmmAYgUW0ewQN4EZdgN4buqmuWVwcBQFIZyrkVhSSjR0nvprzKOmAnLYBS7IRCEnhbjz8ZiVuUfauFti3Tau6m4WkuU3a4dtjdzOpJuF2E5NjoDwz6OP9LaaJdLOEzWB8mg2O9T70jxAN3-caRvjUOfjwHUnctJklkGR46Uy7d5qp-LZDUqk"
                                    alt="Djarum" class="w-full h-full object-cover" />
                                <span
                                    class="absolute top-2.5 right-2.5 bg-cyan-50 text-cyan-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Populer</span>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2.5">
                                    <div
                                        class="w-7 h-7 rounded-md bg-slate-100 flex items-center justify-center font-display font-bold text-teal-900 text-[9px]">
                                        DJ</div>
                                    <span class="text-slate-400 text-[11px]">Djarum Foundation</span>
                                </div>
                                <h3 class="font-display text-sm font-bold text-teal-900 mb-3 leading-snug">Djarum Plus
                                    2024</h3>
                                <div class="flex justify-between pt-2.5 border-t border-slate-100">
                                    <div>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wide">Dana</p>
                                        <p class="text-xs font-bold text-teal-700">Rp 12jt/Tahun</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wide">Deadline</p>
                                        <p class="text-xs font-bold text-slate-700">30 Des 2024</p>
                                    </div>
                                </div>
                                <button
                                    class="w-full mt-3 py-2 bg-green-100 text-green-800 rounded-lg text-xs font-bold flex items-center justify-center gap-1.5">
                                    <span class="material-symbols-outlined text-[13px]">check</span> Sudah Mendaftar
                                </button>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div
                            class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:-translate-y-0.5 hover:shadow-lg transition-all cursor-pointer">
                            <div class="h-28 relative overflow-hidden">
                                <img src="https://lh3.googleusercontent.com/aida-public/AB6AXuC22QsorRSAHYjbtpRNWnsISFU2OeS5_AyNlUtwgt1oCd3Gp5jqbGwiUJnmGKAdoIv4P4t_cW-D0A4egWAyJ-PP_Cf4Xl0VzQAV4I3rhFfi2h_FuTYKrFw059OePB-FEyfqFFywWvopaF6ghANfodGA5KG9H5O3t7xPncMFIW6Pt8FBRKGAWhQqffbstFsO099X2zQIU3lnkR9DNueewrnzmrDmIKrbZ9s7r6jvOEL8L230f7gutuA1WaqpwGR7RP7L0YBbg0LUdAo"
                                    alt="Excellence" class="w-full h-full object-cover" />
                                <span
                                    class="absolute top-2.5 right-2.5 bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Global</span>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2.5">
                                    <div
                                        class="w-7 h-7 rounded-md bg-slate-100 flex items-center justify-center font-display font-bold text-teal-900 text-[9px]">
                                        FE</div>
                                    <span class="text-slate-400 text-[11px]">Excellence Foundation</span>
                                </div>
                                <h3 class="font-display text-sm font-bold text-teal-900 mb-3 leading-snug">Foundation
                                    Excellence Award</h3>
                                <div class="flex justify-between pt-2.5 border-t border-slate-100">
                                    <div>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wide">Dana</p>
                                        <p class="text-xs font-bold text-teal-700">USD 10,000</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wide">Deadline</p>
                                        <p class="text-xs font-bold text-slate-700">15 Jan 2025</p>
                                    </div>
                                </div>
                                <button
                                    class="sc-btn w-full mt-3 py-2 bg-teal-900 text-white rounded-lg text-xs font-bold flex items-center justify-center gap-1.5 hover:bg-teal-600 transition-colors"
                                    onclick="applyNow(this)">
                                    <span class="material-symbols-outlined text-[13px]">add</span> Daftar Sekarang
                                </button>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div
                            class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:-translate-y-0.5 hover:shadow-lg transition-all cursor-pointer">
                            <div class="h-28 relative overflow-hidden bg-teal-900 flex items-center justify-center">
                                <span class="material-symbols-outlined text-white/20 text-6xl">account_balance</span>
                                <span
                                    class="absolute top-2.5 right-2.5 bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-0.5 rounded-full">Baru</span>
                            </div>
                            <div class="p-4">
                                <div class="flex items-center gap-2 mb-2.5">
                                    <div
                                        class="w-7 h-7 rounded-md bg-slate-100 flex items-center justify-center font-display font-bold text-teal-900 text-[9px]">
                                        BRI</div>
                                    <span class="text-slate-400 text-[11px]">Bank BRI</span>
                                </div>
                                <h3 class="font-display text-sm font-bold text-teal-900 mb-3 leading-snug">Beasiswa
                                    BRIlian 2025</h3>
                                <div class="flex justify-between pt-2.5 border-t border-slate-100">
                                    <div>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wide">Dana</p>
                                        <p class="text-xs font-bold text-teal-700">Rp 8jt/Semester</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wide">Deadline</p>
                                        <p class="text-xs font-bold text-slate-700">28 Feb 2025</p>
                                    </div>
                                </div>
                                <button
                                    class="sc-btn w-full mt-3 py-2 bg-teal-900 text-white rounded-lg text-xs font-bold flex items-center justify-center gap-1.5 hover:bg-teal-600 transition-colors"
                                    onclick="applyNow(this)">
                                    <span class="material-symbols-outlined text-[13px]">add</span> Daftar Sekarang
                                </button>
                            </div>
                        </div>

                    </div>
                </section>

                <!-- ===== PAGE: PERSYARATAN ===== -->
                <section id="page-persyaratan" class="page hidden">

                    <!-- Tabs -->
                    <div class="flex border-b border-slate-200 mb-5">
                        <button onclick="switchReqTab(0,this)" data-req-tab
                            class="req-tab px-5 py-2.5 text-sm font-semibold text-teal-800 border-b-2 border-teal-800 -mb-px transition-all">
                            Beasiswa Bakti BUMN
                        </button>
                        <button onclick="switchReqTab(1,this)" data-req-tab
                            class="req-tab px-5 py-2.5 text-sm font-medium text-slate-400 border-b-2 border-transparent -mb-px transition-all hover:text-teal-700">
                            Foundation Excellence
                        </button>
                    </div>

                    <!-- Tab 0: BUMN -->
                    <div id="req-0">
                        <!-- Header -->
                        <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-4 mb-4">
                            <div
                                class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center font-display font-bold text-teal-900 text-xs shrink-0">
                                BUMN</div>
                            <div class="flex-1">
                                <p class="font-display font-bold text-teal-900 text-sm">Beasiswa Bakti BUMN</p>
                                <p class="text-slate-400 text-xs mt-0.5">Kementerian BUMN · Deadline: 12 Okt 2024</p>
                            </div>
                            <span
                                class="inline-flex items-center gap-1 bg-amber-100 text-amber-800 text-[11px] font-semibold px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 inline-block"></span> Seleksi
                                Dokumen
                            </span>
                        </div>
                        <!-- Progress -->
                        <div class="bg-white border border-slate-200 rounded-xl p-4 mb-4">
                            <div class="flex justify-between text-xs mb-2">
                                <span class="text-slate-500">Kelengkapan Dokumen</span>
                                <span class="font-bold text-teal-700">4 / 6</span>
                            </div>
                            <div class="bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-teal-400 h-full rounded-full w-[66%]"></div>
                            </div>
                        </div>
                        <!-- Docs -->
                        <div class="flex flex-col gap-2.5">
                            <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-lg bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">task_alt</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-teal-900">Kartu Tanda Mahasiswa (KTM)</p>
                                    <p class="text-xs text-slate-400 mt-0.5">ktm_siti.pdf · Diunggah 3 hari lalu</p>
                                </div>
                                <span
                                    class="bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-1 rounded-full">Terverifikasi</span>
                            </div>
                            <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-lg bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">task_alt</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-teal-900">Transkrip Nilai (IPK min. 3.5)</p>
                                    <p class="text-xs text-slate-400 mt-0.5">transkrip_sem6.pdf · Diunggah 3 hari lalu
                                    </p>
                                </div>
                                <span
                                    class="bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-1 rounded-full">Terverifikasi</span>
                            </div>
                            <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">upload_file</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-teal-900">Surat Rekomendasi Dosen</p>
                                    <p class="text-xs text-slate-400 mt-0.5">rekomendasi_pak_budi.pdf · Diunggah
                                        kemarin</p>
                                </div>
                                <span
                                    class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-1 rounded-full">Menunggu
                                    Review</span>
                            </div>
                            <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-lg bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">upload_file</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-teal-900">Esai Motivasi (min. 500 kata)</p>
                                    <p class="text-xs text-slate-400 mt-0.5">esai_motivasi.docx · Diunggah 1 jam lalu
                                    </p>
                                </div>
                                <span
                                    class="bg-amber-100 text-amber-800 text-[10px] font-bold px-2.5 py-1 rounded-full">Menunggu
                                    Review</span>
                            </div>
                            <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-lg bg-slate-100 text-slate-400 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">upload</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-teal-900">Surat Keterangan Tidak Mampu</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Belum diunggah · Format PDF, maks 2MB</p>
                                </div>
                                <button
                                    class="flex items-center gap-1.5 border border-teal-700 text-teal-700 text-[11px] font-semibold px-3 py-1.5 rounded-lg hover:bg-teal-50 transition-colors">
                                    <span class="material-symbols-outlined text-[13px]">upload</span> Unggah
                                </button>
                            </div>
                            <div class="bg-white border border-red-200 rounded-xl px-4 py-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-lg bg-red-100 text-red-500 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">error</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-teal-900">Foto Formal (3×4 cm)</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Wajib · Format JPG/PNG, maks 500KB</p>
                                </div>
                                <button
                                    class="flex items-center gap-1.5 border border-teal-700 text-teal-700 text-[11px] font-semibold px-3 py-1.5 rounded-lg hover:bg-teal-50 transition-colors">
                                    <span class="material-symbols-outlined text-[13px]">upload</span> Unggah
                                </button>
                            </div>
                        </div>
                        <button
                            class="w-full mt-5 py-3 bg-slate-300 text-slate-500 rounded-xl font-display font-bold text-sm flex items-center justify-center gap-2 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[16px]">send</span> Lengkapi semua dokumen
                            untuk submit
                        </button>
                    </div>

                    <!-- Tab 1: Foundation Excellence -->
                    <div id="req-1" class="hidden">
                        <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-4 mb-4">
                            <div
                                class="w-11 h-11 rounded-xl bg-teal-50 flex items-center justify-center font-display font-bold text-teal-900 text-xs shrink-0">
                                FE</div>
                            <div class="flex-1">
                                <p class="font-display font-bold text-teal-900 text-sm">Foundation Excellence Award</p>
                                <p class="text-slate-400 text-xs mt-0.5">Excellence Foundation · Deadline: 15 Jan 2025
                                </p>
                            </div>
                            <span
                                class="inline-flex items-center gap-1 bg-cyan-50 text-cyan-800 text-[11px] font-semibold px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 inline-block"></span> Draft
                            </span>
                        </div>
                        <div class="bg-white border border-slate-200 rounded-xl p-4 mb-4">
                            <div class="flex justify-between text-xs mb-2">
                                <span class="text-slate-500">Kelengkapan Dokumen</span>
                                <span class="font-bold text-teal-700">1 / 5</span>
                            </div>
                            <div class="bg-slate-100 rounded-full h-2 overflow-hidden">
                                <div class="bg-teal-400 h-full rounded-full w-[20%]"></div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-2.5">
                            <div class="bg-white border border-slate-200 rounded-xl px-4 py-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-lg bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">task_alt</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-teal-900">Transkrip Nilai (IPK min. 3.7)</p>
                                    <p class="text-xs text-slate-400 mt-0.5">transkrip_sem6.pdf · Diunggah 3 hari lalu
                                    </p>
                                </div>
                                <span
                                    class="bg-green-100 text-green-800 text-[10px] font-bold px-2.5 py-1 rounded-full">Terverifikasi</span>
                            </div>
                            <div class="bg-white border border-red-200 rounded-xl px-4 py-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-lg bg-red-100 text-red-500 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">error</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-teal-900">Sertifikat Bahasa Inggris
                                        (IELTS/TOEFL)</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Min. IELTS 6.5 / TOEFL 90</p>
                                </div>
                                <button
                                    class="flex items-center gap-1.5 border border-teal-700 text-teal-700 text-[11px] font-semibold px-3 py-1.5 rounded-lg hover:bg-teal-50 transition-colors">
                                    <span class="material-symbols-outlined text-[13px]">upload</span> Unggah
                                </button>
                            </div>
                            <div class="bg-white border border-red-200 rounded-xl px-4 py-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-lg bg-red-100 text-red-500 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">error</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-teal-900">Proposal Riset / Research Plan</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Min. 1000 kata, PDF</p>
                                </div>
                                <button
                                    class="flex items-center gap-1.5 border border-teal-700 text-teal-700 text-[11px] font-semibold px-3 py-1.5 rounded-lg hover:bg-teal-50 transition-colors">
                                    <span class="material-symbols-outlined text-[13px]">upload</span> Unggah
                                </button>
                            </div>
                            <div class="bg-white border border-red-200 rounded-xl px-4 py-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-lg bg-red-100 text-red-500 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">error</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-teal-900">2 Surat Rekomendasi Akademik</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Format resmi berkop surat institusi</p>
                                </div>
                                <button
                                    class="flex items-center gap-1.5 border border-teal-700 text-teal-700 text-[11px] font-semibold px-3 py-1.5 rounded-lg hover:bg-teal-50 transition-colors">
                                    <span class="material-symbols-outlined text-[13px]">upload</span> Unggah
                                </button>
                            </div>
                            <div class="bg-white border border-red-200 rounded-xl px-4 py-3 flex items-center gap-3">
                                <div
                                    class="w-9 h-9 rounded-lg bg-red-100 text-red-500 flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">error</span>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-semibold text-teal-900">CV / Riwayat Hidup (English)</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Format ATS-friendly, maks 2 halaman</p>
                                </div>
                                <button
                                    class="flex items-center gap-1.5 border border-teal-700 text-teal-700 text-[11px] font-semibold px-3 py-1.5 rounded-lg hover:bg-teal-50 transition-colors">
                                    <span class="material-symbols-outlined text-[13px]">upload</span> Unggah
                                </button>
                            </div>
                        </div>
                        <button
                            class="w-full mt-5 py-3 bg-slate-300 text-slate-500 rounded-xl font-display font-bold text-sm flex items-center justify-center gap-2 cursor-not-allowed">
                            <span class="material-symbols-outlined text-[16px]">send</span> Lengkapi semua dokumen
                            untuk submit
                        </button>
                    </div>

                </section>

                <!-- ===== PAGE: STATUS ===== -->
                <section id="page-status" class="page hidden">

                    <!-- Accepted banner -->
                    <div class="flex flex-col gap-2.5 mb-6">
                        <div
                            class="bg-white border-l-4 border-green-500 border border-slate-200 rounded-xl px-4 py-3.5 flex items-center gap-3.5 cursor-pointer hover:shadow-md transition-shadow">
                            <div
                                class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-display font-bold text-teal-900 text-[11px] shrink-0">
                                DJ</div>
                            <div class="flex-1">
                                <p class="font-semibold text-sm text-teal-900">Djarum Plus 2024</p>
                                <p class="text-slate-400 text-xs mt-0.5">Djarum Foundation</p>
                            </div>
                            <span
                                class="bg-green-100 text-green-800 text-[11px] font-bold px-2.5 py-1 rounded-full">Diterima</span>
                        </div>
                        <div
                            class="bg-white border-l-4 border-amber-400 border border-slate-200 rounded-xl px-4 py-3.5 flex items-center gap-3.5 cursor-pointer hover:shadow-md transition-shadow">
                            <div
                                class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-display font-bold text-teal-900 text-[10px] shrink-0">
                                BUMN</div>
                            <div class="flex-1">
                                <p class="font-semibold text-sm text-teal-900">Beasiswa Bakti BUMN</p>
                                <p class="text-slate-400 text-xs mt-0.5">Kementerian BUMN</p>
                            </div>
                            <span
                                class="bg-amber-100 text-amber-800 text-[11px] font-bold px-2.5 py-1 rounded-full">Seleksi
                                Dokumen</span>
                        </div>
                        <div
                            class="bg-white border-l-4 border-cyan-400 border border-slate-200 rounded-xl px-4 py-3.5 flex items-center gap-3.5 cursor-pointer hover:shadow-md transition-shadow">
                            <div
                                class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-display font-bold text-teal-900 text-[11px] shrink-0">
                                FE</div>
                            <div class="flex-1">
                                <p class="font-semibold text-sm text-teal-900">Foundation Excellence</p>
                                <p class="text-slate-400 text-xs mt-0.5">Excellence Foundation</p>
                            </div>
                            <span
                                class="bg-cyan-50 text-cyan-800 text-[11px] font-bold px-2.5 py-1 rounded-full">Draft</span>
                        </div>
                    </div>

                    <!-- Timeline detail -->
                    <div class="bg-white border border-slate-200 rounded-xl p-5">
                        <h3 class="font-display text-sm font-bold text-teal-900 mb-5">Djarum Plus 2024 — Detail Tahapan
                        </h3>
                        <div class="flex flex-col">

                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-7 h-7 rounded-full bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[14px]">check</span>
                                    </div>
                                    <div class="w-0.5 bg-teal-400 flex-1 my-1 min-h-[20px]"></div>
                                </div>
                                <div class="pb-5">
                                    <p class="text-sm font-semibold text-teal-900">Pendaftaran Diterima</p>
                                    <p class="text-xs text-slate-400 mt-0.5">10 November 2024</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-7 h-7 rounded-full bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[14px]">check</span>
                                    </div>
                                    <div class="w-0.5 bg-teal-400 flex-1 my-1 min-h-[20px]"></div>
                                </div>
                                <div class="pb-5">
                                    <p class="text-sm font-semibold text-teal-900">Verifikasi Dokumen</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Selesai · 15 November 2024</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-7 h-7 rounded-full bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[14px]">check</span>
                                    </div>
                                    <div class="w-0.5 bg-teal-400 flex-1 my-1 min-h-[20px]"></div>
                                </div>
                                <div class="pb-5">
                                    <p class="text-sm font-semibold text-teal-900">Seleksi Administrasi</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Lulus · 22 November 2024</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-7 h-7 rounded-full bg-green-100 text-green-700 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[14px]">check</span>
                                    </div>
                                    <div class="w-0.5 bg-teal-400 flex-1 my-1 min-h-[20px]"></div>
                                </div>
                                <div class="pb-5">
                                    <p class="text-sm font-semibold text-teal-900">Wawancara</p>
                                    <p class="text-xs text-slate-400 mt-0.5">Selesai · 2 Desember 2024</p>
                                </div>
                            </div>

                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-7 h-7 rounded-full bg-teal-50 text-teal-700 border-2 border-teal-500 flex items-center justify-center shrink-0">
                                        <span class="material-symbols-outlined text-[14px]">emoji_events</span>
                                    </div>
                                </div>
                                <div class="pb-2">
                                    <p class="text-sm font-bold text-teal-700">🎉 Selamat! Kamu Diterima</p>
                                    <p class="text-xs text-slate-400 mt-0.5">10 Desember 2024 · Dana disalurkan mulai
                                        Januari 2025</p>
                                </div>
                            </div>

                        </div>
                    </div>

                </section>

                <!-- ===== PAGE: PROFIL ===== -->
                <section id="page-profil" class="page hidden">

                    <!-- Profile header card -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-5 mb-5">
                        <div
                            class="w-16 h-16 rounded-full bg-teal-400 flex items-center justify-center font-display font-bold text-white text-xl shrink-0">
                            SA</div>
                        <div class="flex-1">
                            <p class="font-display text-lg font-bold text-teal-900">Siti Aminah</p>
                            <p class="text-slate-400 text-sm mt-0.5">siti.aminah@ugm.ac.id</p>
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                <span
                                    class="text-[11px] font-medium px-2.5 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-100">Universitas
                                    Gadjah Mada</span>
                                <span
                                    class="text-[11px] font-medium px-2.5 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-100">Teknik
                                    Informatika</span>
                                <span
                                    class="text-[11px] font-medium px-2.5 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-100">Semester
                                    7</span>
                                <span
                                    class="text-[11px] font-medium px-2.5 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-100">IPK
                                    3.82</span>
                            </div>
                        </div>
                        <div class="text-center shrink-0">
                            <p class="font-display text-3xl font-extrabold text-teal-600">65%</p>
                            <p class="text-slate-400 text-xs mt-1">Profil lengkap</p>
                        </div>
                    </div>

                    <!-- Form card -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-5">
                        <!-- Data Pribadi -->
                        <p class="font-display text-sm font-bold text-teal-900 mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-teal-600">person</span> Data
                            Pribadi
                        </p>
                        <div class="grid grid-cols-2 gap-3 mb-5">
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Nama
                                    Lengkap</label>
                                <input type="text" value="Siti Aminah"
                                    class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Nomor
                                    HP</label>
                                <input type="text" value="0812-3456-7890"
                                    class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Tempat
                                    Lahir</label>
                                <input type="text" value="Yogyakarta"
                                    class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Tanggal
                                    Lahir</label>
                                <input type="date" value="2003-05-14"
                                    class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                            </div>
                        </div>

                        <div class="h-px bg-slate-100 mb-5"></div>

                        <!-- Data Akademik -->
                        <p class="font-display text-sm font-bold text-teal-900 mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-teal-600">school</span> Data
                            Akademik
                        </p>
                        <div class="grid grid-cols-2 gap-3 mb-5">
                            <div class="flex flex-col gap-1.5">
                                <label
                                    class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Universitas</label>
                                <input type="text" value="Universitas Gadjah Mada"
                                    class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Program
                                    Studi</label>
                                <input type="text" value="Teknik Informatika"
                                    class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label
                                    class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Semester</label>
                                <select
                                    class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors">
                                    <option>7</option>
                                    <option>8</option>
                                </select>
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label
                                    class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">IPK</label>
                                <input type="text" value="3.82"
                                    class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                            </div>
                        </div>

                        <div class="h-px bg-slate-100 mb-5"></div>

                        <!-- Informasi Tambahan -->
                        <p class="font-display text-sm font-bold text-teal-900 mb-4 flex items-center gap-2">
                            <span class="material-symbols-outlined text-[16px] text-teal-600">info</span> Informasi
                            Tambahan
                        </p>
                        <div class="grid grid-cols-2 gap-3 mb-5">
                            <div class="flex flex-col gap-1.5">
                                <label
                                    class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Penghasilan
                                    Orang Tua/Bulan</label>
                                <input type="text" placeholder="Rp ..."
                                    class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-300 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                            </div>
                            <div class="flex flex-col gap-1.5">
                                <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Jumlah
                                    Tanggungan</label>
                                <input type="text" placeholder="Contoh: 3 orang"
                                    class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 placeholder-slate-300 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                            </div>
                        </div>

                        <button
                            class="flex items-center gap-2 bg-teal-900 text-white px-5 py-2.5 rounded-xl font-display font-bold text-sm hover:bg-teal-600 transition-colors">
                            <span class="material-symbols-outlined text-[16px]">save</span> Simpan Perubahan
                        </button>
                    </div>

                </section>

            </main>
        </div>
    </div>

    <script>
        const pageTitles = {
            dashboard: 'Dashboard',
            beasiswa: 'Cari Beasiswa',
            persyaratan: 'Persyaratan',
            status: 'Status Lamaran',
            profil: 'Profil Saya'
        };

        function showPage(name, el) {
            document.querySelectorAll('.page').forEach(p => {
                p.classList.add('hidden');
                p.classList.remove('block');
            });
            document.getElementById('page-' + name).classList.remove('hidden');
            document.getElementById('page-' + name).classList.add('block');
            document.querySelectorAll('[data-nav]').forEach(n => n.classList.remove('bg-white/10', 'text-white',
                'active-nav'));
            if (el) {
                el.classList.add('bg-white/10', 'text-white');
            }
            document.getElementById('page-title').textContent = pageTitles[name] || '';
        }

        function switchReqTab(idx, el) {
            document.querySelectorAll('[data-req-tab]').forEach(t => {
                t.classList.remove('text-teal-800', 'border-teal-800', 'font-semibold');
                t.classList.add('text-slate-400', 'border-transparent', 'font-medium');
            });
            el.classList.add('text-teal-800', 'border-teal-800', 'font-semibold');
            el.classList.remove('text-slate-400', 'border-transparent', 'font-medium');
            document.getElementById('req-0').classList.toggle('hidden', idx !== 0);
            document.getElementById('req-1').classList.toggle('hidden', idx !== 1);
        }

        function applyNow(btn) {
            btn.classList.remove('bg-teal-900', 'hover:bg-teal-600');
            btn.classList.add('bg-green-100', 'text-green-800', 'cursor-default');
            btn.innerHTML = '<span class="material-symbols-outlined text-[13px]">check</span> Sudah Mendaftar';
            btn.onclick = null;
        }

        // Set dashboard as active on load
        document.querySelectorAll('[data-nav]')[0].classList.add('bg-white/10', 'text-white');

        // Filter button active state
        document.querySelectorAll('.filter-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                document.querySelectorAll('.filter-btn').forEach(b => {
                    b.classList.remove('bg-teal-900', 'text-white', 'border-teal-900');
                    b.classList.add('bg-white', 'text-slate-600', 'border-slate-200');
                });
                btn.classList.add('bg-teal-900', 'text-white', 'border-teal-900');
                btn.classList.remove('bg-white', 'text-slate-600', 'border-slate-200');
            });
        });
    </script>
</body>

</html>
