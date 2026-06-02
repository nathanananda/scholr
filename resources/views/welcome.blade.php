<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Scholr - Beasiswa untuk Semua Kalangan</title>
    @vite('resources/css/app.css')
    <link
        href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@400;500;600&display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap"
        rel="stylesheet" />
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
            opacity: 0.30;
        }

        .typewriter::after {
            content: '|';
            animation: blink 1s infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0;
            }
        }

        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 overflow-x-hidden">

    <!-- Navbar -->
    <header class="fixed top-0 w-full z-50 bg-white/80 backdrop-blur-md shadow-sm border-b border-slate-200/60">
        <div class="flex items-center justify-between px-10 h-20 max-w-7xl mx-auto">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-teal-800 text-2xl">school</span>
                <span class="font-display text-2xl font-extrabold text-teal-900 tracking-tight">Scholr</span>
            </div>
            <nav class="hidden md:flex items-center gap-8">
                <a class="text-cyan-700 font-semibold border-b-2 border-cyan-600 text-sm" href="#">Beranda</a>
                <a class="text-slate-500 hover:text-teal-800 font-medium text-sm transition-colors duration-200"
                    href="#cara-kerja">Cara Kerja</a>
                <a class="text-slate-500 hover:text-teal-800 font-medium text-sm transition-colors duration-200"
                    href="#beasiswa">Beasiswa</a>
                <a class="text-slate-500 hover:text-teal-800 font-medium text-sm transition-colors duration-200"
                    href="#tentang-kami">Tentang Kami</a>
            </nav>
            <a href="{{ route('login') }}" class="bg-teal-900 text-white px-6 py-2.5 rounded-full font-semibold text-sm hover:bg-teal-700 transition-colors duration-200 active:scale-95">
                Login
            </a>
        </div>
    </header>

    <main>
        <!-- Hero Section -->
        <section
            class="relative min-h-screen pt-32 pb-20 flex flex-col items-center justify-center overflow-hidden bg-slate-50">
            <div class="absolute inset-0 dot-grid z-0"></div>
            <div class="max-w-7xl mx-auto px-10 relative z-10 text-center">
                <div
                    class="inline-flex items-center gap-2 bg-cyan-100 text-cyan-700 px-4 py-1.5 rounded-full mb-8 border border-cyan-200 text-xs font-semibold">
                    🎓 10.000+ Penerima Beasiswa
                </div>
                <h1
                    class="font-display text-5xl md:text-6xl font-extrabold text-teal-900 mb-6 max-w-4xl mx-auto leading-tight tracking-tight">
                    Beasiswa untuk Semua Kalangan
                </h1>
                <div class="h-10 mb-10">
                    <span class="text-cyan-600 font-display text-2xl font-bold typewriter" id="typewriter"></span>
                </div>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                    <button
                        class="bg-teal-900 text-white px-8 py-4 rounded-xl font-semibold text-sm flex items-center gap-2 hover:shadow-lg hover:shadow-teal-900/20 transition-all">
                        Daftar sebagai Penerima
                        <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
                    </button>
                    <button
                        class="border-2 border-teal-900 text-teal-900 px-8 py-4 rounded-xl font-semibold text-sm hover:bg-teal-50 transition-all">
                        Jadilah Penyalur
                    </button>
                </div>
            </div>

            <!-- Hero Image -->
            <div class="mt-20 w-full max-w-5xl mx-auto px-10">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border border-slate-200/60">
                    <img alt="Education Scene" class="w-full h-[400px] object-cover"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBc0R2m_oUDdtAfvzKMcFO9H1cFj88X3Y56-qSiuKsppWU3xlAYlXPCq8UAuiR6DR8gB1ZZubMOruFiGRxVDi0RvOg22ljyHGPRcJrLVyeygy24hksogdpzQ9HrJWeRHe9Qu88MF0mlSgQF5-yIK2KPUZ4CsI9SAIOJNMy6R00ChbidGJZrmsv0RtjZ6tN4xIN7Ub-rroGE-ERBLWzN2pROeqEsQsjkO0CjnFOyQ6PmlRfAXv9yHgsln_BOd9hfEZ21xHJZhIvZWVw" />
                    <div class="absolute inset-0 bg-gradient-to-t from-teal-900/60 to-transparent"></div>
                </div>
            </div>
        </section>

        <!-- Stats Bar -->
        <section class="bg-teal-900 py-12">
            <div class="max-w-7xl mx-auto px-10 grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div>
                    <p class="text-cyan-300 text-2xl font-display font-bold">15k+</p>
                    <p class="text-white/70 text-sm font-medium">Beasiswa</p>
                </div>
                <div>
                    <p class="text-cyan-300 text-2xl font-display font-bold">200+</p>
                    <p class="text-white/70 text-sm font-medium">Penyalur</p>
                </div>
                <div>
                    <p class="text-cyan-300 text-2xl font-display font-bold">10k+</p>
                    <p class="text-white/70 text-sm font-medium">Penerima</p>
                </div>
                <div>
                    <p class="text-cyan-300 text-2xl font-display font-bold">Rp 500M+</p>
                    <p class="text-white/70 text-sm font-medium">Total</p>
                </div>
            </div>
        </section>

        <!-- How It Works -->
        <section id="cara-kerja" class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-10">
                <div class="text-center mb-16">
                    <h2 class="font-display text-4xl font-bold text-teal-900 mb-4">Cara Kerja Scholr</h2>
                    <p class="text-lg text-slate-500">Proses digital yang transparan dan memudahkan semua pihak.</p>
                </div>
                <div class="grid md:grid-cols-2 gap-12">

                    <!-- Penyalur -->
                    <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm">
                        <h3 class="font-display text-2xl font-bold text-teal-900 mb-8 flex items-center gap-3">
                            <span class="material-symbols-outlined text-cyan-600">volunteer_activism</span>
                            Untuk Penyalur
                        </h3>
                        <div class="space-y-8">
                            <div class="flex gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center shrink-0 font-bold text-sm">
                                    1</div>
                                <div>
                                    <p class="font-semibold text-sm mb-1">Buat Program</p>
                                    <p class="text-base text-slate-500">Tentukan kriteria dan total pendanaan beasiswa
                                        Anda.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center shrink-0 font-bold text-sm">
                                    2</div>
                                <div>
                                    <p class="font-semibold text-sm mb-1">Seleksi Otomatis</p>
                                    <p class="text-base text-slate-500">Sistem AI kami membantu memverifikasi dokumen
                                        pelamar.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center shrink-0 font-bold text-sm">
                                    3</div>
                                <div>
                                    <p class="font-semibold text-sm mb-1">Penyaluran Transparan</p>
                                    <p class="text-base text-slate-500">Dana dikirim langsung ke penerima dengan laporan
                                        lengkap.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Penerima -->
                    <div class="bg-white p-10 rounded-3xl border border-slate-200 shadow-sm">
                        <h3 class="font-display text-2xl font-bold text-teal-900 mb-8 flex items-center gap-3">
                            <span class="material-symbols-outlined text-cyan-600">school</span>
                            Untuk Penerima
                        </h3>
                        <div class="space-y-8">
                            <div class="flex gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center shrink-0 font-bold text-sm">
                                    1</div>
                                <div>
                                    <p class="font-semibold text-sm mb-1">Lengkapi Profil</p>
                                    <p class="text-base text-slate-500">Satu profil untuk melamar ke berbagai program
                                        beasiswa.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center shrink-0 font-bold text-sm">
                                    2</div>
                                <div>
                                    <p class="font-semibold text-sm mb-1">Temukan Kecocokan</p>
                                    <p class="text-base text-slate-500">Rekomendasi beasiswa berdasarkan prestasi dan
                                        minat Anda.</p>
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <div
                                    class="w-10 h-10 rounded-full bg-cyan-100 text-cyan-700 flex items-center justify-center shrink-0 font-bold text-sm">
                                    3</div>
                                <div>
                                    <p class="font-semibold text-sm mb-1">Mulai Perjalanan</p>
                                    <p class="text-base text-slate-500">Terima pendanaan dan fokus pada pendidikan
                                        impian Anda.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Featured Scholarships -->
        <section id="beasiswa" class="py-24 bg-slate-100 overflow-hidden">
            <div class="max-w-7xl mx-auto px-10">
                <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6">
                    <div>
                        <h2 class="font-display text-4xl font-bold text-teal-900 mb-4">Beasiswa Pilihan</h2>
                        <p class="text-lg text-slate-500">Kesempatan terbatas yang sedang dibuka saat ini.</p>
                    </div>
                    <button class="text-cyan-600 font-semibold text-sm flex items-center gap-2 group">
                        Lihat Semua
                        <span
                            class="material-symbols-outlined group-hover:translate-x-1 transition-transform">chevron_right</span>
                    </button>
                </div>

                <div class="flex gap-8 overflow-x-auto pb-8 no-scrollbar -mx-10 px-10">

                    <!-- Card 1 -->
                    <div
                        class="min-w-[320px] md:min-w-[400px] bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-all group">
                        <div class="h-48 relative">
                            <img alt="BUMN scholarship"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuD1OliciBxnIhT2inzBJeaVEuOJa1DwVH6JcZi1wTI8zAzOu283B_KpckxDdfG1xJbUDIwuMLBXYObmj2eIokHa9wHCdqi-LdI7P96rEqe_xPjUoD45PIbC8LIZxv7ZHFbzY-AGk4TDyi-u_Wwp-JWkGg5QqbEEfkOygUftUbTCMoeqJvfBaxv2sSmFdwFKxWS0xlW5C4WbBgzJ_7T5CeYeNXQ_fEpaK0b-2MZbMLxfloN3DF0Gr53vuoo4iRc_VBwOSIdhiLPtd3s" />
                            <div
                                class="absolute top-4 right-4 bg-cyan-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                Dibuka</div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div
                                    class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-teal-900 text-xs">
                                    BUMN</div>
                                <span class="font-medium text-sm text-slate-500">Kementerian BUMN</span>
                            </div>
                            <h3 class="font-display text-xl font-bold text-teal-900 mb-4">Beasiswa Bakti BUMN</h3>
                            <div class="flex justify-between items-center py-4 border-t border-slate-200">
                                <div>
                                    <p class="text-xs text-slate-400 uppercase tracking-wider">Dana</p>
                                    <p class="font-semibold text-sm text-cyan-600">Full Coverage</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-400 uppercase tracking-wider">Deadline</p>
                                    <p class="font-semibold text-sm text-red-500">12 Okt 2024</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 2 -->
                    <div
                        class="min-w-[320px] md:min-w-[400px] bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-all group">
                        <div class="h-48 relative">
                            <img alt="Djarum Scholarship"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuB1Plrn98w393Mcj7uatsMQcORahlFY2G3o1ixcTFcUeKpl1c-p7JP7Xwr5new5Oa76O0Cv5HLIQMxNlCUq1W8CXFVgmmAYgUW0ewQN4EZdgN4buqmuWVwcBQFIZyrkVhSSjR0nvprzKOmAnLYBS7IRCEnhbjz8ZiVuUfauFti3Tau6m4WkuU3a4dtjdzOpJuF2E5NjoDwz6OP9LaaJdLOEzWB8mg2O9T70jxAN3-caRvjUOfjwHUnctJklkGR46Uy7d5qp-LZDUqk" />
                            <div
                                class="absolute top-4 right-4 bg-cyan-600 text-white px-3 py-1 rounded-full text-xs font-semibold">
                                Populer</div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div
                                    class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-teal-900 text-xs">
                                    DJ</div>
                                <span class="font-medium text-sm text-slate-500">Djarum Foundation</span>
                            </div>
                            <h3 class="font-display text-xl font-bold text-teal-900 mb-4">Djarum Plus 2024</h3>
                            <div class="flex justify-between items-center py-4 border-t border-slate-200">
                                <div>
                                    <p class="text-xs text-slate-400 uppercase tracking-wider">Dana</p>
                                    <p class="font-semibold text-sm text-cyan-600">Rp 12jt/Tahun</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-400 uppercase tracking-wider">Deadline</p>
                                    <p class="font-semibold text-sm text-slate-700">30 Des 2024</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card 3 -->
                    <div
                        class="min-w-[320px] md:min-w-[400px] bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl transition-all group">
                        <div class="h-48 relative">
                            <img alt="Excellence Foundation"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuC22QsorRSAHYjbtpRNWnsISFU2OeS5_AyNlUtwgt1oCd3Gp5jqbGwiUJnmGKAdoIv4P4t_cW-D0A4egWAyJ-PP_Cf4Xl0VzQAV4I3rhFfi2h_FuTYKrFw059OePB-FEyfqFFywWvopaF6ghANfodGA5KG9H5O3t7xPncMFIW6Pt8FBRKGAWhQqffbstFsO099X2zQIU3lnkR9DNueewrnzmrDmIKrbZ9s7r6jvOEL8L230f7gutuA1WaqpwGR7RP7L0YBbg0LUdAo" />
                            <div
                                class="absolute top-4 right-4 bg-cyan-100 text-cyan-800 px-3 py-1 rounded-full text-xs font-semibold">
                                Global</div>
                        </div>
                        <div class="p-6">
                            <div class="flex items-center gap-3 mb-4">
                                <div
                                    class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center font-bold text-teal-900 text-xs">
                                    FE</div>
                                <span class="font-medium text-sm text-slate-500">Excellence Foundation</span>
                            </div>
                            <h3 class="font-display text-xl font-bold text-teal-900 mb-4">Foundation Excellence</h3>
                            <div class="flex justify-between items-center py-4 border-t border-slate-200">
                                <div>
                                    <p class="text-xs text-slate-400 uppercase tracking-wider">Dana</p>
                                    <p class="font-semibold text-sm text-cyan-600">USD 10,000</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-slate-400 uppercase tracking-wider">Deadline</p>
                                    <p class="font-semibold text-sm text-slate-700">15 Jan 2025</p>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- Testimonials -->
        <section id="tentang-kami" class="py-24 bg-white">
            <div class="max-w-7xl mx-auto px-10">
                <div class="text-center mb-16">
                    <h2 class="font-display text-4xl font-bold text-teal-900 mb-4">Kata Mereka</h2>
                </div>
                <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">

                    <!-- Testimonial 1 -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm relative">
                        <span
                            class="material-symbols-outlined text-cyan-200 absolute top-6 right-8 text-6xl">format_quote</span>
                        <p class="text-lg text-slate-700 italic mb-8 relative z-10">
                            "Proses penyaluran jadi sangat transparan. Kami bisa melacak setiap rupiah yang dialokasikan
                            untuk pendidikan anak bangsa."
                        </p>
                        <div class="flex items-center gap-4">
                            <img alt="CSR Director" class="w-12 h-12 rounded-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBrzWecGnT_8-Stee5T1JGkhtXaxueN7Y4nOKwIQjX_qmvHJH3F9UYzS1f0OjWYNBM6bPGaw7fcfb-hw6mH1eSs8vuHoI62TCrimPn-EOyCSYqqCM5i8tyFJCtpcQbG-MqaI23JcosaQIiZ2D9oRm9qrh9g2cmO1DW3ZEBlQfuqToVgeBfogCp8_Fdx1jd25gxX1FPGE-xo-5-oCiNpOiI-9SKy5TmW9theS8FeOyZYkHr3QSHTKhS5i1JAWX9Qm_Ga1Us6zV8Hez8" />
                            <div>
                                <p class="font-semibold text-sm text-teal-900">Andi Pratama</p>
                                <p class="text-xs text-slate-500">CSR Director, TechCorp Indonesia</p>
                            </div>
                        </div>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="bg-white p-8 rounded-3xl border border-slate-200 shadow-sm relative">
                        <span
                            class="material-symbols-outlined text-cyan-200 absolute top-6 right-8 text-6xl">format_quote</span>
                        <p class="text-lg text-slate-700 italic mb-8 relative z-10">
                            "Akhirnya saya bisa fokus kuliah tanpa harus memikirkan biaya semester. Scholr sangat
                            memudahkan proses pendaftaran."
                        </p>
                        <div class="flex items-center gap-4">
                            <img alt="Scholarship Recipient" class="w-12 h-12 rounded-full object-cover"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuBmJadZTityJ47pWUVYVA8jlUFi8hCsq1tDpLpDpTslgQI1Dnl_MwiVgSSIC3VJ2FDqIwbyPnJ9bvWzYmowBitSUvuRybA-PGG2Pu_6SJz0KSENNTfN_Dynur8jZKCYs9voOY4LMI2E6KgyBGJMWV2uLxx6Kk120jwd7I1E_mgnl-0gDcpTnYWUNEBSVR7f-9yM3zAEDzuskfskVbBYSk3WdYerYG2qKSICLxZlmq4UTCiaEUZx5uIqLQtH7sr3ARHrcvsQ-huC2Xk" />
                            <div>
                                <p class="font-semibold text-sm text-teal-900">Siti Aminah</p>
                                <p class="text-xs text-slate-500">Mahasiswa, Universitas Gadjah Mada</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- CTA Banner -->
        <section class="py-20 px-10">
            <div
                class="max-w-7xl mx-auto bg-teal-800 rounded-[40px] p-12 md:p-20 relative overflow-hidden text-center shadow-2xl">
                <div class="absolute inset-0 dot-grid opacity-10"></div>
                <div class="relative z-10">
                    <h2 class="font-display text-5xl font-extrabold text-white mb-8 tracking-tight">Mulai Perjalananmu
                        Hari Ini</h2>
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-6">
                        <button
                            class="bg-cyan-300 text-teal-900 px-10 py-4 rounded-2xl font-semibold text-sm hover:shadow-xl hover:shadow-cyan-300/20 transition-all">
                            Cari Beasiswa
                        </button>
                        <button
                            class="border border-white/30 text-white hover:bg-white/10 px-10 py-4 rounded-2xl font-semibold text-sm transition-all">
                            Daftar Penyalur
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-teal-900 w-full py-12 border-t border-white/10">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 px-10 max-w-7xl mx-auto">
            <div class="col-span-1">
                <div class="flex items-center gap-2 mb-6">
                    <span class="material-symbols-outlined text-cyan-300 text-2xl">school</span>
                    <span class="font-display text-2xl font-bold text-white">Scholr</span>
                </div>
                <p class="text-base text-white/80 mb-6">Platform beasiswa digital pertama di Indonesia dengan
                    transparansi berbasis data.</p>
                <p class="text-sm font-medium text-white/50">© 2026 Scholr - Aspirasi Digital Indonesia.</p>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-6 uppercase tracking-wider">Perusahaan</h4>
                <ul class="space-y-4">
                    <li><a class="text-white/80 hover:text-white hover:underline transition-all text-sm"
                            href="#">Beranda</a></li>
                    <li><a class="text-white/80 hover:text-white hover:underline transition-all text-sm"
                            href="#">Tentang Kami</a></li>
                    <li><a class="text-white/80 hover:text-white hover:underline transition-all text-sm"
                            href="#">Kontak</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-6 uppercase tracking-wider">Layanan</h4>
                <ul class="space-y-4">
                    <li><a class="text-white/80 hover:text-white hover:underline transition-all text-sm"
                            href="#">Cara Kerja</a></li>
                    <li><a class="text-white/80 hover:text-white hover:underline transition-all text-sm"
                            href="#">Beasiswa</a></li>
                    <li><a class="text-white/80 hover:text-white hover:underline transition-all text-sm"
                            href="#">Penyalur Dana</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold text-sm mb-6 uppercase tracking-wider">Legal</h4>
                <ul class="space-y-4">
                    <li><a class="text-white/80 hover:text-white hover:underline transition-all text-sm"
                            href="#">Kebijakan Privasi</a></li>
                    <li><a class="text-white/80 hover:text-white hover:underline transition-all text-sm"
                            href="#">Syarat &amp; Ketentuan</a></li>
                </ul>
            </div>
        </div>
    </footer>

    <script>
        // Typewriter
        const phrases = ["Untuk Pelajar", "Untuk Mahasiswa", "Untuk Profesional"];
        let phraseIndex = 0,
            charIndex = 0,
            isDeleting = false,
            typeSpeed = 150;
        const el = document.getElementById('typewriter');

        function type() {
            const current = phrases[phraseIndex];
            if (isDeleting) {
                el.textContent = current.substring(0, charIndex - 1);
                charIndex--;
                typeSpeed = 100;
            } else {
                el.textContent = current.substring(0, charIndex + 1);
                charIndex++;
                typeSpeed = 150;
            }
            if (!isDeleting && charIndex === current.length) {
                isDeleting = true;
                typeSpeed = 2000;
            } else if (isDeleting && charIndex === 0) {
                isDeleting = false;
                phraseIndex = (phraseIndex + 1) % phrases.length;
                typeSpeed = 500;
            }
            setTimeout(type, typeSpeed);
        }
        document.addEventListener('DOMContentLoaded', type);

        // Scroll reveal
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('opacity-100', 'translate-y-0');
                    e.target.classList.remove('opacity-0', 'translate-y-10');
                }
            });
        }, {
            threshold: 0.1
        });

        document.querySelectorAll('section > div').forEach(el => {
            el.classList.add('transition-all', 'duration-700', 'opacity-0', 'translate-y-10');
            observer.observe(el);
        });
    </script>
</body>

</html>
