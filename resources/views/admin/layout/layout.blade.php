<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Dashboard') — Scholr Admin</title>
    @vite('resources/css/app.css')
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- Datatables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.8/css/dataTables.dataTables.css" />
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

        .dot-grid {
            background-image: radial-gradient(#306576 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.10;
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Sidebar active nav item */
        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.1);
            border-left: 3px solid #34d399;
        }

        .nav-item.active .nav-icon,
        .nav-item.active .nav-label {
            color: #ffffff;
        }

        .nav-item:not(.active) {
            border-left: 3px solid transparent;
        }

        /* Sidebar collapse transition */
        #sidebar {
            transition: width 0.25s ease;
        }

        #sidebar.collapsed {
            width: 64px;
        }

        #sidebar.collapsed .nav-label,
        #sidebar.collapsed .sidebar-section-label,
        #sidebar.collapsed .brand-name,
        #sidebar.collapsed .user-info {
            display: none;
        }

        #sidebar.collapsed .nav-item {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }

        #sidebar.collapsed .nav-badge {
            display: none;
        }

        /* Scrollbar for main content */
        #main-content::-webkit-scrollbar {
            width: 4px;
        }

        #main-content::-webkit-scrollbar-track {
            background: transparent;
        }

        #main-content::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
    </style>
</head>

<body class="bg-slate-100 h-screen overflow-hidden flex">

    {{-- ===================== SIDEBAR ===================== --}}
    <aside id="sidebar" class="w-60 bg-white flex flex-col h-screen flex-shrink-0 relative z-20">

        {{-- Dot grid overlay --}}
        <div class="dot-grid absolute inset-0 pointer-events-none"></div>

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-5 py-5 border-b border-white/10 relative z-10">
            <div class="w-8 h-8 bg-teal-700 rounded-lg flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-graduation-cap text-white text-[18px]"></i>
            </div>
            <span class="brand-name font-display font-bold text-teal-700 text-lg tracking-tight">Scholr</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 overflow-y-auto no-scrollbar py-4 space-y-1 relative z-10">

            <p
                class="sidebar-section-label text-teal-800 text-[10px] font-semibold uppercase tracking-widest px-5 mb-2">
                Menu Utama
            </p>

            <a href="{{ route('admin.dashboard') }}"
                class="group nav-item flex items-center gap-3 rounded-xl px-5 py-3 cursor-pointer
    transition-all duration-200 ease-out
    hover:bg-teal-600 hover:shadow-md hover:shadow-teal-100
    hover:-translate-y-[1px]
    {{ request()->routeIs('admin.dashboard') ? 'bg-teal-600 shadow-md shadow-teal-100 -translate-y-[1px]' : '' }}">


                <i
                    class="fa-solid fa-chart-pie transition-colors duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-teal-700 group-hover:text-white' }}"></i>
                <span
                    class="nav-label text-sm font-medium
        transition-colors duration-200
        {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-teal-800 group-hover:text-white' }}">
                    Dashboard
                </span>
            </a>

            <a href="{{ route('admin.verifikasi-penyalur.index') }}"
                class="group nav-item flex items-center gap-3 rounded-xl px-5 py-3 cursor-pointer
    transition-all duration-200 ease-out
    hover:bg-teal-600 hover:shadow-md hover:shadow-teal-100
    hover:-translate-y-[1px]
    {{ request()->routeIs('admin.verifikasi-penyalur.index') ? 'bg-teal-600 shadow-md shadow-teal-100 -translate-y-[1px]' : '' }}">


                <i
                    class="fa-solid fa-check-double transition-colors duration-200 {{ request()->routeIs('admin.verifikasi-penyalur.index') ? 'text-white' : 'text-teal-700 group-hover:text-white' }}"></i>
                <span
                    class="nav-label text-sm font-medium
        transition-colors duration-200
        {{ request()->routeIs('admin.verifikasi-penyalur.index') ? 'text-white' : 'text-teal-800 group-hover:text-white' }}">
                    Verifikasi Penyalur
                </span>
            </a>

            <p
                class="sidebar-section-label text-teal-800 text-[10px] font-semibold uppercase tracking-widest px-5 pt-5 mb-2">
                Account
            </p>


            <a href="{{ route('penerima.profile') }}"
                class="group nav-item flex items-center gap-3 rounded-xl px-5 py-3 cursor-pointer
    transition-all duration-200 ease-out
    hover:bg-teal-600 hover:shadow-md hover:shadow-teal-100
    hover:-translate-y-[1px]
    {{ request()->routeIs('penerima.profile') ? 'bg-teal-600 shadow-md shadow-teal-100 -translate-y-[1px]' : '' }}">
                <i
                    class="fa-solid fa-id-badge text-teal-700 group-hover:text-white {{ request()->routeIs('penerima.profile') ? 'text-white' : 'text-teal-700 group-hover:text-white' }}"></i>
                <span
                    class="nav-label text-sm font-medium
        transition-colors duration-200
        {{ request()->routeIs('penerima.profile') ? 'text-white' : 'text-teal-800 group-hover:text-white' }}">Profile</span>
            </a>



        </nav>


        {{-- User profile --}}
        <div class="border-t border-white/10 px-4 py-4 relative z-10">
            <div class="flex items-center gap-3">
                <div
                    class="w-8 h-8 rounded-full bg-teal-700 border border-teal-600 flex items-center justify-center flex-shrink-0">
                    <span class="font-display text-xs font-bold text-white uppercase">
                        {{ substr(auth()->user()->name ?? 'A', 0, 2) }}
                    </span>
                </div>
                <div class="user-info flex-1 min-w-0">
                    <p class="text-teal-700 text-xs font-semibold truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-teal-700 text-[11px] truncate">{{ auth()->user()->email ?? 'admin@scholr.id' }}</p>
                </div>
                <form method="POST" action="">
                    @csrf
                    <button type="submit" title="Keluar"
                        class="material-symbols-outlined text-white/40 hover:text-white transition-colors text-[18px]">
                        logout
                    </button>
                </form>
            </div>
        </div>
    </aside>

    {{-- ===================== MAIN AREA ===================== --}}
    <div class="flex-1 flex flex-col min-w-0 h-screen">

        {{-- ===== HEADER ===== --}}
        <header class="bg-white border-b border-slate-200 px-6 py-3.5 flex items-center gap-4 flex-shrink-0 z-10">

            {{-- Sidebar toggle button --}}
            <button onclick="toggleSidebar()" title="Toggle sidebar"
                class="w-8 h-8 rounded-lg flex items-center justify-center text-slate-500 hover:bg-slate-100 hover:text-teal-800 transition-all">
                <i class="fa-solid fa-bars text-[20px]"></i>
            </button>

            {{-- Page title --}}
            <div class="flex items-center gap-2 text-sm text-slate-400">
                <i class="fa-solid fa-house text-[16px]"></i>
            </div>

            {{-- Spacer --}}
            <div class="flex-1"></div>




            {{-- Avatar dropdown --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open"
                    class="flex items-center gap-2.5 pl-2 pr-3 py-1.5 rounded-xl hover:bg-slate-100 transition-all">
                    <div class="w-7 h-7 rounded-full bg-teal-900 flex items-center justify-center flex-shrink-0">
                        <span class="font-display text-[11px] font-bold text-white uppercase">
                            {{ substr(auth()->user()->name ?? 'A', 0, 2) }}
                        </span>
                    </div>
                    <span class="text-sm font-medium text-slate-700 hidden sm:block">
                        {{ auth()->user()->name ?? 'Admin' }}
                    </span>
                    <i class="fa-solid fa-angle-down text-slate-400 text-[16px]"></i>
                </button>

                {{-- Dropdown --}}
                <div x-show="open" @click.outside="open = false"
                    class="absolute right-0 top-full mt-2 w-44 bg-white border border-slate-200 rounded-2xl shadow-lg py-1.5 z-50">
                    <a href="{{ route('penerima.profile') }}"
                        class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-teal-800 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">manage_accounts</span>
                        Profil Saya
                    </a>
                    <a href=""
                        class="flex items-center gap-2.5 px-4 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-teal-800 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">settings</span>
                        Pengaturan
                    </a>
                    <div class="my-1 border-t border-slate-100"></div>
                    <a href="{{ route('logout') }}"
                        class="w-full flex items-center gap-2.5 px-4 py-2 text-sm text-red-500 hover:bg-red-50 transition-colors">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        Logout
                    </a>
                </div>
            </div>

        </header>

        {{-- ===== MAIN CONTENT ===== --}}
        <main id="main-content" class="flex-1 overflow-y-auto bg-slate-100">



            {{-- Slot konten halaman --}}
            <div class="p-5">
                @yield('content')
            </div>

        </main>

    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/min.js"></script>
    {{-- Datatables --}}
    <script src="https://cdn.datatables.net/2.3.8/js/dataTables.js"></script>
    {{-- ===================== SCRIPTS ===================== --}}
    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        }
    </script>

    @yield('script')

    {{-- Alpine.js untuk dropdown (opsional, skip jika tidak pakai Alpine) --}}
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>



</body>

</html>
