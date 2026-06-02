@extends('penyalur.layout.layout')

@section('title', 'Ranking SAW — ' . $scholarship->name)

@section('content')

{{-- Breadcrumb --}}
<div class="flex items-center gap-2 text-sm text-slate-400 mb-5">
    <a href="{{ route('penyalur.pelamar.index') }}" class="hover:text-teal-600 transition-colors">Manajemen Pelamar</a>
    <i class="fa-solid fa-chevron-right text-[10px]"></i>
    <a href="{{ route('penyalur.pelamar.show', $scholarship->id) }}" class="hover:text-teal-600 transition-colors">
        {{ Str::limit($scholarship->name, 30) }}
    </a>
    <i class="fa-solid fa-chevron-right text-[10px]"></i>
    <span class="text-slate-600 font-medium">Ranking SAW</span>
</div>

{{-- Alert --}}
@if(session('success'))
    <div class="mb-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm">
        <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
        <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
    </div>
@endif

{{-- ============================================================ --}}
{{-- BANNER INFO SAW                                              --}}
{{-- ============================================================ --}}
<div class="mb-5 flex gap-3 items-start bg-teal-50 border border-teal-200 rounded-xl px-4 py-3 text-sm text-teal-800 leading-relaxed">
    <i class="fa-solid fa-circle-info mt-0.5 flex-shrink-0 text-teal-500"></i>
    <p>
        Halaman ini menampilkan hasil seleksi otomatis menggunakan metode
        <strong>Simple Additive Weighting (SAW)</strong> — sistem penilaian yang objektif, terukur, dan transparan.
        Setiap pelamar mendapatkan skor berdasarkan kriteria yang sudah ditetapkan.
        <button
            onclick="toggleGuide()"
            class="underline font-semibold ml-1 hover:text-teal-600 transition-colors"
            id="btn-guide-toggle">
            Pelajari cara membaca halaman ini ↓
        </button>
    </p>
</div>

{{-- ============================================================ --}}
{{-- PANEL PANDUAN (collapsible)                                  --}}
{{-- ============================================================ --}}
<div id="panel-saw-guide" class="hidden mb-6">
    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">

        {{-- Panel header --}}
        <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/60">
            <div class="flex items-center gap-2.5">
                <div class="w-7 h-7 rounded-lg bg-teal-100 flex items-center justify-center">
                    <i class="fa-solid fa-book-open text-teal-600 text-xs"></i>
                </div>
                <h2 class="font-display font-bold text-slate-800">Panduan Membaca Halaman Ini</h2>
            </div>
            <button onclick="toggleGuide()" class="text-slate-400 hover:text-slate-600 transition-colors text-sm w-7 h-7 flex items-center justify-center hover:bg-slate-100 rounded-lg">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>

        <div class="p-5 space-y-6">

            {{-- Bagian 1: Apa itu SAW --}}
            <div>
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-3">Apa itu metode SAW?</p>
                <p class="text-slate-600 text-sm leading-relaxed mb-4">
                    SAW (<em>Simple Additive Weighting</em>) adalah metode matematika yang menghitung skor setiap pelamar
                    secara otomatis. Semua kriteria — seperti IPK, penghasilan orang tua, dan semester — dinilai sekaligus
                    dengan bobot yang berbeda, lalu dijumlahkan menjadi satu skor akhir. Hasilnya berupa <strong>peringkat
                    yang adil dan tidak bisa dimanipulasi</strong>.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-3 right-3 w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center">
                            <span class="text-xs font-bold text-teal-700">1</span>
                        </div>
                        <i class="fa-solid fa-pen-to-square text-teal-500 mb-2 text-base"></i>
                        <p class="font-semibold text-slate-800 text-sm mb-1">Kumpulkan nilai</p>
                        <p class="text-slate-500 text-xs leading-relaxed">Setiap pelamar mengisi nilai untuk tiap kriteria yang ditetapkan penyalur (contoh: IPK = 3.75).</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-3 right-3 w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center">
                            <span class="text-xs font-bold text-teal-700">2</span>
                        </div>
                        <i class="fa-solid fa-scale-balanced text-teal-500 mb-2 text-base"></i>
                        <p class="font-semibold text-slate-800 text-sm mb-1">Normalisasi nilai</p>
                        <p class="text-slate-500 text-xs leading-relaxed">Nilai diseragamkan ke skala 0–1 agar semua kriteria bisa dibandingkan secara setara dan adil.</p>
                    </div>
                    <div class="bg-slate-50 rounded-xl p-4 border border-slate-100 relative overflow-hidden">
                        <div class="absolute top-3 right-3 w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center">
                            <span class="text-xs font-bold text-teal-700">3</span>
                        </div>
                        <i class="fa-solid fa-trophy text-teal-500 mb-2 text-base"></i>
                        <p class="font-semibold text-slate-800 text-sm mb-1">Hitung skor akhir</p>
                        <p class="text-slate-500 text-xs leading-relaxed">Nilai dikalikan bobot kriteria lalu dijumlahkan. Skor tertinggi mendapat peringkat terbaik.</p>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100"></div>

            {{-- Bagian 2: Arti kolom tabel --}}
            <div>
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-3">Arti kolom di tabel</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div class="flex gap-3 items-start bg-slate-50 rounded-xl p-3.5 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center flex-shrink-0 font-mono text-slate-600 text-xs font-bold">xij</div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800 mb-0.5">Nilai mentah</p>
                            <p class="text-xs text-slate-500 leading-relaxed">Angka asli yang diisi pelamar. Contoh: IPK 3.75, atau penghasilan orang tua Rp 2.000.000.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-start bg-slate-50 rounded-xl p-3.5 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center flex-shrink-0 font-mono text-indigo-600 text-xs font-bold">rij</div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800 mb-0.5">Nilai normalisasi</p>
                            <p class="text-xs text-slate-500 leading-relaxed">Nilai mentah yang sudah diseragamkan ke skala 0–1. Ini yang dibandingkan antar pelamar.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-start bg-slate-50 rounded-xl p-3.5 border border-slate-100">
                        <div class="w-10 h-10 rounded-lg bg-white border border-slate-200 flex items-center justify-center flex-shrink-0 font-mono text-teal-600 text-xs font-bold leading-tight text-center">wj<br>·rij</div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800 mb-0.5">Nilai tertimbang</p>
                            <p class="text-xs text-slate-500 leading-relaxed">Nilai normalisasi × bobot kriteria. Ini adalah kontribusi nyata tiap kriteria terhadap skor akhir.</p>
                        </div>
                    </div>
                    <div class="flex gap-3 items-start bg-teal-50 rounded-xl p-3.5 border border-teal-100">
                        <div class="w-10 h-10 rounded-lg bg-teal-600 flex items-center justify-center flex-shrink-0 font-mono text-white text-xs font-bold">Vi</div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800 mb-0.5">Skor akhir (Vi)</p>
                            <p class="text-xs text-slate-500 leading-relaxed">Jumlahan semua nilai tertimbang. <strong class="text-slate-700">Kolom inilah yang menentukan peringkat.</strong> Semakin tinggi = semakin baik.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100"></div>

            {{-- Bagian 3: Benefit vs Cost + Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                {{-- Benefit vs Cost --}}
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-3">Tipe kriteria</p>
                    <div class="space-y-2.5">
                        <div class="flex gap-3 items-start">
                            <span class="flex-shrink-0 text-[10px] font-bold bg-emerald-100 text-emerald-700 px-2 py-1 rounded-md">B</span>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Benefit</p>
                                <p class="text-xs text-slate-500 leading-relaxed">Semakin <em>tinggi</em> nilainya, semakin baik peluangnya. Contoh: IPK, nilai ujian, prestasi.</p>
                            </div>
                        </div>
                        <div class="flex gap-3 items-start">
                            <span class="flex-shrink-0 text-[10px] font-bold bg-red-100 text-red-600 px-2 py-1 rounded-md">C</span>
                            <div>
                                <p class="text-sm font-semibold text-slate-800">Cost</p>
                                <p class="text-xs text-slate-500 leading-relaxed">Semakin <em>rendah</em> nilainya, semakin baik peluangnya. Contoh: penghasilan orang tua (untuk beasiswa kebutuhan).</p>
                            </div>
                        </div>
                        <div class="bg-amber-50 border border-amber-100 rounded-xl px-3 py-2.5 mt-1">
                            <p class="text-xs text-amber-800 leading-relaxed">
                                <i class="fa-solid fa-lightbulb text-amber-500 mr-1"></i>
                                <strong>Contoh:</strong> Untuk beasiswa kebutuhan, penghasilan orang tua rendah justru memberi nilai normalisasi yang tinggi karena tipenya <em>Cost</em>.
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Arti status & warna baris --}}
                <div>
                    <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-3">Arti status & warna baris</p>
                    <div class="space-y-2.5">
                        <div class="flex items-center gap-3">
                            <span class="text-base w-6 text-center flex-shrink-0">🥇</span>
                            <p class="text-xs text-slate-600 leading-relaxed">Peringkat 1–3 ditandai medali untuk kemudahan identifikasi.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="flex-shrink-0 text-[9px] font-bold bg-teal-100 text-teal-700 px-1.5 py-0.5 rounded whitespace-nowrap">Dalam Kuota</span>
                            <p class="text-xs text-slate-600 leading-relaxed">Pelamar masuk batas kuota. Berpeluang ditetapkan sebagai penerima.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="flex-shrink-0 text-xs font-semibold bg-emerald-100 text-emerald-700 px-2.5 py-0.5 rounded-full whitespace-nowrap">Diterima</span>
                            <p class="text-xs text-slate-600 leading-relaxed">Sudah resmi ditetapkan sebagai penerima beasiswa.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="flex-shrink-0 text-xs font-semibold bg-red-100 text-red-600 px-2.5 py-0.5 rounded-full whitespace-nowrap">Ditolak</span>
                            <p class="text-xs text-slate-600 leading-relaxed">Tidak lolos seleksi karena skor di bawah kuota.</p>
                        </div>
                        <div class="flex items-center gap-3">
                            <span class="flex-shrink-0 text-xs font-semibold bg-slate-100 text-slate-500 px-2.5 py-0.5 rounded-full whitespace-nowrap">—</span>
                            <p class="text-xs text-slate-600 leading-relaxed">SAW sudah dihitung, penerima belum ditetapkan secara resmi.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100"></div>

            {{-- Bagian 4: FAQ --}}
            <div>
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-widest mb-3">Pertanyaan umum</p>
                <div class="space-y-3" id="faq-container">

                    <div class="faq-item border border-slate-100 rounded-xl overflow-hidden">
                        <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-slate-50 transition-colors">
                            <span class="text-sm font-medium text-slate-800">Apakah skor SAW bisa berubah setelah dihitung?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform faq-icon flex-shrink-0 ml-3"></i>
                        </button>
                        <div class="faq-answer hidden px-4 pb-3">
                            <p class="text-xs text-slate-500 leading-relaxed">Tidak. Setelah penyalur menjalankan perhitungan SAW, skor dan peringkat bersifat final. Perubahan hanya bisa terjadi jika penyalur menjalankan ulang perhitungan — dan itu hanya bisa dilakukan <strong class="text-slate-700">sebelum penerima resmi ditetapkan</strong>.</p>
                        </div>
                    </div>

                    <div class="faq-item border border-slate-100 rounded-xl overflow-hidden">
                        <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-slate-50 transition-colors">
                            <span class="text-sm font-medium text-slate-800">Kenapa skor saya rendah padahal IPK tinggi?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform faq-icon flex-shrink-0 ml-3"></i>
                        </button>
                        <div class="faq-answer hidden px-4 pb-3">
                            <p class="text-xs text-slate-500 leading-relaxed">SAW mempertimbangkan <strong class="text-slate-700">semua kriteria sekaligus</strong>, bukan hanya IPK. Pelamar lain bisa unggul jika memiliki nilai lebih baik di kriteria dengan bobot lebih besar. Perhatikan kolom bobot (w=…%) di header tabel untuk memahami kriteria mana yang paling berpengaruh.</p>
                        </div>
                    </div>

                    <div class="faq-item border border-slate-100 rounded-xl overflow-hidden">
                        <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-slate-50 transition-colors">
                            <span class="text-sm font-medium text-slate-800">Apakah penyalur bisa memilih pelamar di luar peringkat?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform faq-icon flex-shrink-0 ml-3"></i>
                        </button>
                        <div class="faq-answer hidden px-4 pb-3">
                            <p class="text-xs text-slate-500 leading-relaxed">Secara teknis ya, penyalur bebas memilih pelamar mana pun. Namun sistem memberi <strong class="text-slate-700">peringatan</strong> jika jumlah yang dipilih melebihi kuota, dan menampilkan badge "Dalam Kuota" untuk memudahkan pengambilan keputusan.</p>
                        </div>
                    </div>

                    <div class="faq-item border border-slate-100 rounded-xl overflow-hidden">
                        <button onclick="toggleFaq(this)" class="w-full flex items-center justify-between px-4 py-3 text-left hover:bg-slate-50 transition-colors">
                            <span class="text-sm font-medium text-slate-800">Apa yang terjadi setelah penerima ditetapkan?</span>
                            <i class="fa-solid fa-chevron-down text-slate-400 text-xs transition-transform faq-icon flex-shrink-0 ml-3"></i>
                        </button>
                        <div class="faq-answer hidden px-4 pb-3">
                            <p class="text-xs text-slate-500 leading-relaxed">Status beasiswa berubah menjadi <strong class="text-slate-700">Completed</strong> dan tidak bisa diubah lagi. Semua pelamar akan otomatis mendapat notifikasi — yang diterima mendapat kabar gembira, yang tidak lolos mendapat notifikasi penolakan. Tombol "Tetapkan Penerima" akan dikunci.</p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>

{{-- Header --}}
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="font-display text-xl font-bold text-slate-800">Hasil Ranking SAW</h1>
        <p class="text-slate-500 text-sm mt-0.5">{{ $scholarship->name }} · Kuota: <span class="font-semibold text-slate-700">{{ $scholarship->quota }} penerima</span></p>
    </div>
    @if($scholarship->status !== 'completed')
        <button onclick="document.getElementById('modal-tetapkan').classList.remove('hidden')"
            class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors shadow-sm shadow-teal-200">
            <i class="fa-solid fa-user-check"></i> Tetapkan Penerima
        </button>
    @else
        <span class="inline-flex items-center gap-2 bg-slate-100 text-slate-500 text-sm font-medium px-4 py-2 rounded-xl">
            <i class="fa-solid fa-lock text-xs"></i> Penerima Sudah Ditetapkan
        </span>
    @endif
</div>

{{-- Summary cards --}}
<div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
    @php
        $totalPelamar  = $applications->count();
        $accepted      = $applications->where('status', 'accepted')->count();
        $topScore      = $applications->first()?->saw_score ?? 0;
        $avgScore      = $applications->avg('saw_score');
    @endphp
    <div class="bg-white rounded-2xl border border-slate-200 px-4 py-4">
        <p class="text-slate-400 text-xs mb-1">Total Pelamar</p>
        <p class="font-display font-bold text-2xl text-slate-800">{{ $totalPelamar }}</p>
        <p class="text-slate-400 text-[10px] mt-0.5">yang mengajukan lamaran</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 px-4 py-4">
        <p class="text-slate-400 text-xs mb-1">Kuota Tersisa</p>
        <p class="font-display font-bold text-2xl text-teal-600">{{ $scholarship->quota - $accepted }}</p>
        <p class="text-slate-400 text-[10px] mt-0.5">dari {{ $scholarship->quota }} kuota tersedia</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 px-4 py-4">
        <p class="text-slate-400 text-xs mb-1">Skor Tertinggi</p>
        <p class="font-display font-bold text-2xl text-slate-800">{{ number_format($topScore, 4) }}</p>
        <p class="text-slate-400 text-[10px] mt-0.5">skor terbaik dari pelamar</p>
    </div>
    <div class="bg-white rounded-2xl border border-slate-200 px-4 py-4">
        <p class="text-slate-400 text-xs mb-1">Rata-rata Skor</p>
        <p class="font-display font-bold text-2xl text-slate-800">{{ number_format($avgScore, 4) }}</p>
        <p class="text-slate-400 text-[10px] mt-0.5">rata-rata semua pelamar</p>
    </div>
</div>

{{-- Konteks kuota (jika belum completed) --}}
@if($scholarship->status !== 'completed')
    @php $inQuota = $applications->where('saw_rank', '<=', $scholarship->quota)->count(); @endphp
    <div class="mb-4 flex items-center gap-3 bg-slate-50 border border-slate-200 rounded-xl px-4 py-3 text-sm text-slate-600">
        <i class="fa-solid fa-circle-info text-slate-400 flex-shrink-0"></i>
        <span>
            <strong class="text-slate-700">{{ $inQuota }} pelamar</strong> saat ini masuk dalam kuota (ditandai latar <span class="inline-block w-3 h-3 rounded bg-teal-100 border border-teal-200 align-middle mx-0.5"></span> teal muda).
            Peringkat bisa berubah jika penyalur memilih secara manual melalui tombol <strong class="text-slate-700">Tetapkan Penerima</strong>.
        </span>
    </div>
@endif

{{-- Tabel Matriks SAW --}}
<div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
        <div>
            <h2 class="font-display font-bold text-slate-800">Detail Matriks Perhitungan</h2>
            <p class="text-slate-400 text-xs mt-0.5">Nilai mentah <span class="font-mono text-slate-500">(xij)</span>, normalisasi <span class="font-mono text-indigo-500">(rij)</span>, dan nilai tertimbang <span class="font-mono text-teal-600">(wj·rij)</span> per kriteria.</p>
        </div>
        {{-- Legend --}}
        <div class="flex items-center gap-4 text-xs text-slate-400 flex-wrap">
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-slate-400 inline-block"></span>xij = nilai asli
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-indigo-400 inline-block"></span>rij = normalisasi
            </span>
            <span class="flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full bg-teal-500 inline-block"></span>wj·rij = tertimbang
            </span>
            <span><span class="font-semibold text-emerald-600">B</span> = Benefit &nbsp;·&nbsp; <span class="font-semibold text-red-500">C</span> = Cost</span>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-100">
                    <th class="sticky left-0 bg-slate-50 text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide whitespace-nowrap">Rank</th>
                    <th class="sticky left-10 bg-slate-50 text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide whitespace-nowrap min-w-[160px]">Pelamar</th>

                    @foreach($scholarship->criteria as $c)
                        <th colspan="3" class="text-center px-2 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide border-l border-slate-100 whitespace-nowrap">
                            <div>{{ Str::limit($c->name, 18) }}</div>
                            <div class="flex items-center justify-center gap-2 mt-1 font-normal normal-case">
                                <span class="{{ $c->type === 'Benefit' ? 'text-emerald-600 bg-emerald-50' : 'text-red-500 bg-red-50' }} font-semibold text-[10px] px-1.5 py-0.5 rounded">
                                    {{ $c->type === 'Benefit' ? 'B' : 'C' }}
                                </span>
                                <span class="text-slate-400 text-[10px]">bobot {{ $c->weight }}%</span>
                            </div>
                        </th>
                    @endforeach

                    <th class="text-right px-5 py-3 text-xs font-semibold text-teal-600 uppercase tracking-wide whitespace-nowrap border-l border-slate-100">
                        Skor Akhir (Vi)
                    </th>
                    <th class="text-center px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">Status</th>
                </tr>
                {{-- Sub-header kriteria --}}
                <tr class="bg-slate-50/50 border-b border-slate-100">
                    <th class="sticky left-0 bg-slate-50/50 px-1 py-1.5"></th>
                    <th class="sticky left-10 bg-slate-50/50 px-1 py-1.5"></th>
                    @foreach($scholarship->criteria as $c)
                        <th class="px-3 py-1.5 text-[10px] font-medium text-slate-400 border-l border-slate-100 text-center font-mono">xij</th>
                        <th class="px-3 py-1.5 text-[10px] font-medium text-indigo-400 text-center font-mono">rij</th>
                        <th class="px-3 py-1.5 text-[10px] font-medium text-teal-500 text-center font-mono">wj·rij</th>
                    @endforeach
                    <th class="border-l border-slate-100"></th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach($applications as $app)
                    @php
                        $isAccepted = $app->status === 'accepted';
                        $isRejected = $app->status === 'rejected';
                        $inQuotaRow = $app->saw_rank <= $scholarship->quota;
                        $rowBg = $isAccepted ? 'bg-emerald-50/50' : ($inQuotaRow ? 'bg-teal-50/30' : '');
                    @endphp
                    <tr class="{{ $rowBg }} hover:bg-slate-50 transition-colors">
                        {{-- Rank --}}
                        <td class="sticky left-0 {{ $rowBg ?: 'bg-white' }} px-5 py-3.5 font-display font-bold text-center">
                            @if($app->saw_rank === 1)
                                <span class="text-amber-500 text-lg" title="Peringkat 1">🥇</span>
                            @elseif($app->saw_rank === 2)
                                <span class="text-slate-400 text-lg" title="Peringkat 2">🥈</span>
                            @elseif($app->saw_rank === 3)
                                <span class="text-amber-700 text-lg" title="Peringkat 3">🥉</span>
                            @else
                                <span class="text-slate-500 text-sm">#{{ $app->saw_rank }}</span>
                            @endif
                        </td>

                        {{-- Nama --}}
                        <td class="sticky left-10 {{ $rowBg ?: 'bg-white' }} px-5 py-3.5">
                            <div class="flex items-center gap-2.5">
                                <div class="w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                                    <span class="text-[10px] font-bold text-teal-700 uppercase">{{ substr($app->user->name, 0, 2) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-slate-800 whitespace-nowrap text-xs">{{ $app->user->name }}</p>
                                    @if($inQuotaRow && $scholarship->status !== 'completed')
                                        <span class="text-[9px] bg-teal-100 text-teal-700 px-1.5 py-0.5 rounded font-semibold">Dalam Kuota</span>
                                    @endif
                                </div>
                            </div>
                        </td>

                        {{-- Per kriteria --}}
                        @foreach($scholarship->criteria as $c)
                            @php
                                $sawResult = $app->sawResults->firstWhere('criteria_id', $c->id);
                            @endphp
                            <td class="px-3 py-3.5 text-center text-xs text-slate-600 border-l border-slate-100 whitespace-nowrap font-mono">
                                {{ $sawResult ? number_format($sawResult->raw_value, 2) : '—' }}
                            </td>
                            <td class="px-3 py-3.5 text-center text-xs text-indigo-600 whitespace-nowrap font-mono">
                                {{ $sawResult ? number_format($sawResult->normalized_value, 4) : '—' }}
                            </td>
                            <td class="px-3 py-3.5 text-center text-xs text-teal-600 font-medium whitespace-nowrap font-mono">
                                {{ $sawResult ? number_format($sawResult->weighted_value, 4) : '—' }}
                            </td>
                        @endforeach

                        {{-- Skor akhir --}}
                        <td class="px-5 py-3.5 text-right border-l border-slate-100">
                            <span class="font-display font-bold text-teal-700 text-base">
                                {{ number_format($app->saw_score, 4) }}
                            </span>
                        </td>

                        {{-- Status --}}
                        <td class="px-5 py-3.5 text-center">
                            @if($app->status === 'accepted')
                                <span class="text-xs bg-emerald-100 text-emerald-700 font-semibold px-2.5 py-1 rounded-full">Diterima</span>
                            @elseif($app->status === 'rejected')
                                <span class="text-xs bg-red-100 text-red-600 font-semibold px-2.5 py-1 rounded-full">Ditolak</span>
                            @else
                                <span class="text-xs bg-slate-100 text-slate-500 font-semibold px-2.5 py-1 rounded-full">Menunggu</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Table footer hint --}}
    <div class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex flex-wrap items-center gap-x-5 gap-y-1.5 text-xs text-slate-400">
        <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-2 rounded-sm bg-emerald-100 border border-emerald-200"></span> Sudah ditetapkan diterima</span>
        <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-2 rounded-sm bg-teal-100 border border-teal-200"></span> Masuk dalam kuota</span>
        <span class="flex items-center gap-1.5"><span class="inline-block w-3 h-2 rounded-sm bg-white border border-slate-200"></span> Di luar kuota</span>
        <span class="ml-auto">Diurutkan dari skor tertinggi ke terendah</span>
    </div>
</div>

{{-- Formula keterangan --}}
<div class="mt-4 bg-white rounded-2xl border border-slate-200 p-5">
    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
        <div>
            <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">Formula SAW yang Digunakan</p>
            <div class="flex flex-wrap gap-x-8 gap-y-3 text-sm text-slate-600">
                <div class="flex items-baseline gap-2">
                    <span class="font-semibold text-slate-700 text-xs">Normalisasi Benefit:</span>
                    <span class="font-mono text-teal-700">r<sub>ij</sub> = x<sub>ij</sub> / max(x<sub>ij</sub>)</span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-semibold text-slate-700 text-xs">Normalisasi Cost:</span>
                    <span class="font-mono text-teal-700">r<sub>ij</sub> = min(x<sub>ij</sub>) / x<sub>ij</sub></span>
                </div>
                <div class="flex items-baseline gap-2">
                    <span class="font-semibold text-slate-700 text-xs">Skor Akhir:</span>
                    <span class="font-mono text-teal-700">V<sub>i</sub> = Σ (w<sub>j</sub> × r<sub>ij</sub>)</span>
                </div>
            </div>
        </div>
        <div class="bg-slate-50 rounded-xl px-4 py-3 text-xs text-slate-500 leading-relaxed max-w-xs">
            <p class="font-semibold text-slate-700 mb-1">Keterangan simbol</p>
            <p><span class="font-mono text-slate-600">x<sub>ij</sub></span> = nilai asli pelamar ke-i pada kriteria ke-j</p>
            <p><span class="font-mono text-slate-600">r<sub>ij</sub></span> = nilai normalisasi (skala 0–1)</p>
            <p><span class="font-mono text-slate-600">w<sub>j</sub></span> = bobot kriteria ke-j (total = 100%)</p>
            <p><span class="font-mono text-slate-600">V<sub>i</sub></span> = skor akhir pelamar ke-i</p>
        </div>
    </div>
</div>

{{-- ========== Modal Tetapkan Penerima ========== --}}
@if($scholarship->status !== 'completed')
<div id="modal-tetapkan" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg mx-4 max-h-[90vh] flex flex-col">
        {{-- Header --}}
        <div class="px-6 pt-6 pb-4 border-b border-slate-100">
            <h3 class="font-display font-bold text-slate-800 text-lg">Tetapkan Penerima Beasiswa</h3>
            <p class="text-slate-400 text-sm mt-0.5">
                Pilih pelamar yang akan ditetapkan sebagai penerima. Kuota: <strong class="text-slate-700">{{ $scholarship->quota }}</strong> penerima.
            </p>
            {{-- Petunjuk modal --}}
            <div class="mt-3 bg-amber-50 border border-amber-100 rounded-xl px-3 py-2 flex gap-2 items-start">
                <i class="fa-solid fa-triangle-exclamation text-amber-500 text-xs mt-0.5 flex-shrink-0"></i>
                <p class="text-xs text-amber-800 leading-relaxed">
                    Tindakan ini <strong>tidak dapat dibatalkan</strong>. Pelamar yang dipilih akan mendapat status <em>Diterima</em> dan sisanya <em>Ditolak</em>. Semua pelamar akan menerima notifikasi hasil seleksi.
                </p>
            </div>
        </div>

        <form action="{{ route('penyalur.pelamar.tetapkan', $scholarship->id) }}" method="POST" id="form-tetapkan">
            @csrf
            {{-- Counter --}}
            <div class="px-6 pt-3 pb-1 flex items-center justify-between text-xs">
                <span class="text-slate-500">Pilih pelamar yang akan diterima:</span>
                <span id="quota-counter" class="font-semibold text-slate-700">
                    <span id="selected-count">{{ $applications->where('status', 'accepted')->count() }}</span> / {{ $scholarship->quota }} dipilih
                </span>
            </div>

            {{-- List pelamar dengan checkbox --}}
            <div class="flex-1 overflow-y-auto px-6 py-2 space-y-2">
                @foreach($applications as $app)
                    <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-200 hover:border-teal-300 hover:bg-teal-50/30 cursor-pointer transition-all has-[:checked]:border-teal-400 has-[:checked]:bg-teal-50">
                        <input type="checkbox" name="application_ids[]" value="{{ $app->id }}"
                            {{ $app->status === 'accepted' ? 'checked' : '' }}
                            class="w-4 h-4 rounded accent-teal-600 flex-shrink-0"
                            onchange="validateQuota()">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <span class="font-medium text-slate-800 text-sm">{{ $app->user->name }}</span>
                                @if($app->saw_rank <= $scholarship->quota)
                                    <span class="text-[10px] bg-teal-100 text-teal-700 px-1.5 rounded font-semibold">Top {{ $scholarship->quota }}</span>
                                @endif
                            </div>
                            <p class="text-slate-400 text-xs">Rank #{{ $app->saw_rank }} · Skor SAW: <span class="font-mono">{{ number_format($app->saw_score, 4) }}</span></p>
                        </div>
                    </label>
                @endforeach
            </div>

            {{-- Footer --}}
            <div class="px-6 py-4 border-t border-slate-100">
                <div id="quota-warning" class="hidden mb-3 flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 rounded-xl px-3 py-2 text-xs">
                    <i class="fa-solid fa-triangle-exclamation"></i>
                    <span>Jumlah yang dipilih melebihi kuota ({{ $scholarship->quota }}). Kurangi pilihan sebelum melanjutkan.</span>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('modal-tetapkan').classList.add('hidden')"
                        class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="btn-submit-tetapkan"
                        class="px-5 py-2.5 text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white rounded-xl transition-colors">
                        <i class="fa-solid fa-user-check mr-1.5"></i> Konfirmasi & Tetapkan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif

@endsection

@section('script')
<script>
    const quota = {{ $scholarship->quota }};
    let guideOpen = false;

    function toggleGuide() {
        const panel = document.getElementById('panel-saw-guide');
        const btn = document.getElementById('btn-guide-toggle');
        guideOpen = !guideOpen;
        panel.classList.toggle('hidden', !guideOpen);
        btn.textContent = guideOpen
            ? 'Tutup panduan ↑'
            : 'Pelajari cara membaca halaman ini ↓';
        if (guideOpen) {
            panel.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }

    function toggleFaq(btn) {
        const answer = btn.nextElementSibling;
        const icon = btn.querySelector('.faq-icon');
        const isOpen = !answer.classList.contains('hidden');
        answer.classList.toggle('hidden', isOpen);
        icon.style.transform = isOpen ? '' : 'rotate(180deg)';
    }

    function validateQuota() {
        const checked = document.querySelectorAll('input[name="application_ids[]"]:checked').length;
        const warning = document.getElementById('quota-warning');
        const btn = document.getElementById('btn-submit-tetapkan');
        const counter = document.getElementById('selected-count');

        if (counter) counter.textContent = checked;

        if (checked > quota) {
            warning.classList.remove('hidden');
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            warning.classList.add('hidden');
            btn.disabled = false;
            btn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // Close modal on backdrop click
    document.getElementById('modal-tetapkan')?.addEventListener('click', function (e) {
        if (e.target === this) this.classList.add('hidden');
    });

    // Konfirmasi submit
    document.getElementById('form-tetapkan')?.addEventListener('submit', function (e) {
        const checked = document.querySelectorAll('input[name="application_ids[]"]:checked').length;
        if (!confirm(`Tetapkan ${checked} pelamar sebagai penerima beasiswa?\n\nTindakan ini tidak dapat dibatalkan. Semua pelamar akan menerima notifikasi hasil seleksi.`)) {
            e.preventDefault();
        }
    });
</script>
@endsection
