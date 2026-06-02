<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login — Scholr</title>
    @vite('resources/css/app.css')
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200"
        rel="stylesheet" />
    <style>
        body {
            font-family: 'DM Sans', sans-serif;
        }

        h1, h2, h3, h4, label, p, .font-display {
            font-family: 'Sora', sans-serif;
        }

        .dot-grid {
            background-image: radial-gradient(#306576 1px, transparent 1px);
            background-size: 24px 24px;
            opacity: 0.30;
        }

        /* Tab underline active state */
        .role-tab-active {
            border-bottom: 2px solid #134e4a;
            color: #134e4a;
            font-weight: 700;
        }

        .role-tab-inactive {
            border-bottom: 2px solid transparent;
            color: #94a3b8;
            font-weight: 500;
        }

        /* Input focus */
        .scholr-input:focus {
            outline: none;
            border-color: #0f766e;
            background-color: #f0fdfa;
        }

        /* Eye toggle */
        #togglePassword {
            cursor: pointer;
            user-select: none;
        }
    </style>
</head>

<body class="bg-slate-50 w-full min-h-screen flex flex-col justify-between relative overflow-x-hidden">

    {{-- Dot grid background --}}
    <div class="dot-grid fixed inset-0 pointer-events-none z-0"></div>

    <main class="flex-grow flex flex-col justify-center items-center py-16 z-10 relative">
        <div class="flex flex-col justify-center items-center w-full">

            {{-- Logo --}}
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-teal-900 text-2xl">school</span>
                <span class="font-display text-2xl font-extrabold text-teal-900 tracking-tight">Scholr</span>
            </div>

            {{-- Tagline --}}
            <p class="font-display text-sm text-slate-500 mb-8">Beasiswa untuk Semua Kalangan</p>

            {{-- Card --}}
            <div class="bg-white w-full max-w-sm rounded-3xl border border-slate-200 shadow-sm overflow-hidden">


                {{-- Form --}}
                <form action="{{ route('login.authenticate') }}" method="POST" class="px-8 py-7 space-y-5">
                    @csrf

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">mail</span>
                            <input
                                type="email"
                                id="email"
                                name="email"
                                value="{{ old('email') }}"
                                placeholder="contoh@email.com"
                                required
                                class="scholr-input w-full pl-9 pr-4 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 placeholder:text-slate-400 transition-colors duration-200 @error('email') border-red-400 @enderror">
                        </div>
                        @error('email')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="password" class="text-xs font-semibold text-slate-700">Password</label>
                            <a href="" class="text-xs text-teal-700 hover:underline font-medium">Lupa password?</a>
                        </div>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">lock</span>
                            <input
                                type="password"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                required
                                class="scholr-input w-full pl-9 pr-10 py-2.5 rounded-xl border border-slate-200 bg-white text-sm text-slate-800 placeholder:text-slate-400 transition-colors duration-200 @error('password') border-red-400 @enderror">
                            <span
                                id="togglePassword"
                                class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px] hover:text-teal-700 transition-colors"
                                onclick="togglePwd()">
                                visibility
                            </span>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Submit --}}
                    <button
                        type="submit"
                        class="w-full flex items-center justify-center gap-2 bg-teal-900 hover:bg-teal-700 active:scale-[0.98] text-white font-display font-semibold text-sm py-3 rounded-xl transition-all duration-200 mt-1">
                        Masuk
                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                    </button>

                    {{-- Register link --}}
                    <p class="text-center text-xs text-slate-500 pt-1">
                        Belum punya akun?
                        <a href="{{ route('register') }}" class="text-teal-700 font-semibold hover:underline">Daftar sekarang</a>
                    </p>
                </form>
            </div>

            {{-- Trust badge --}}
            <div class="flex items-center gap-2 mt-6">
                <span class="material-symbols-outlined text-slate-300 text-base">verified</span>
                <p class="text-xs text-slate-400 uppercase tracking-widest font-semibold">Dipercaya oleh 50+ Institusi Pendidikan</p>
            </div>

        </div>
    </main>

    {{-- Footer --}}
    <footer class="bg-teal-900 w-full py-12 border-t border-white/10 z-10 relative">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 px-10 max-w-7xl mx-auto">
            <div class="col-span-1">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-cyan-300 text-2xl">school</span>
                    <span class="font-display text-xl font-bold text-white">Scholr</span>
                </div>
                <p class="text-sm text-white/70 mb-4 leading-relaxed">
                    Demokratisasi pendidikan melalui akses beasiswa digital yang transparan.
                </p>
                <p class="text-xs font-medium text-white/40">© 2026 Scholr - Aspirasi Digital Indonesia.</p>
            </div>

            <div>
                <h4 class="text-white font-semibold text-xs mb-5 uppercase tracking-wider">Navigasi</h4>
                <ul class="space-y-3">
                    <li><a class="text-white/70 hover:text-white transition-colors text-sm" href="#">Beranda</a></li>
                    <li><a class="text-white/70 hover:text-white transition-colors text-sm" href="#">Cara Kerja</a></li>
                    <li><a class="text-white/70 hover:text-white transition-colors text-sm" href="#">Beasiswa</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold text-xs mb-5 uppercase tracking-wider">Perusahaan</h4>
                <ul class="space-y-3">
                    <li><a class="text-white/70 hover:text-white transition-colors text-sm" href="#">Tentang Kami</a></li>
                    <li><a class="text-white/70 hover:text-white transition-colors text-sm" href="#">Kebijakan Privasi</a></li>
                    <li><a class="text-white/70 hover:text-white transition-colors text-sm" href="#">Syarat &amp; Ketentuan</a></li>
                </ul>
            </div>

            <div>
                <h4 class="text-white font-semibold text-xs mb-5 uppercase tracking-wider">Kontak</h4>
                <ul class="space-y-3">
                    <li><a class="text-white/70 hover:text-white transition-colors text-sm" href="mailto:halo@scholr.id">halo@scholr.id</a></li>
                </ul>
                <div class="flex items-center gap-3 mt-5">
                    <a href="#" class="w-8 h-8 rounded-full border border-white/20 flex items-center justify-center hover:border-white/60 transition-colors">
                        <span class="material-symbols-outlined text-white/70 text-base">language</span>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full border border-white/20 flex items-center justify-center hover:border-white/60 transition-colors">
                        <span class="material-symbols-outlined text-white/70 text-base">share</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function togglePwd() {
            const pwd    = document.getElementById('password');
            const icon   = document.getElementById('togglePassword');
            const isText = pwd.type === 'text';
            pwd.type     = isText ? 'password' : 'text';
            icon.textContent = isText ? 'visibility' : 'visibility_off';
        }
    </script>

</body>
</html>
