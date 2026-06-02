@extends('penerima.layout.layout')

@section('content')
    <section>
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
                <span class="bg-green-100 text-green-800 text-[11px] font-bold px-2.5 py-1 rounded-full">Diterima</span>
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
                <span class="bg-amber-100 text-amber-800 text-[11px] font-bold px-2.5 py-1 rounded-full">Seleksi
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
                <span class="bg-cyan-50 text-cyan-800 text-[11px] font-bold px-2.5 py-1 rounded-full">Draft</span>
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
@endsection
