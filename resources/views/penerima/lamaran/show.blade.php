@extends('penerima.layout.layout')

@section('content')
    <section>

        {{-- Back --}}
        <a href="{{ route('penerima.lamaran.index') }}"
            class="inline-flex items-center gap-1.5 text-xs text-slate-400 hover:text-teal-700
               mb-4 transition-colors">
            <i class="fa-solid fa-arrow-left text-[11px]"></i> Kembali ke daftar lamaran
        </a>

        {{-- Header --}}
        @php
            $statusConfig = [
                'draft' => ['bg-cyan-50 text-cyan-800', 'Draft'],
                'submitted' => ['bg-blue-50 text-blue-800', 'Tersubmit'],
                'under_review' => ['bg-amber-100 text-amber-800', 'Seleksi Dokumen'],
                'accepted' => ['bg-green-100 text-green-800', 'Diterima'],
                'rejected' => ['bg-red-100 text-red-800', 'Tidak Lolos'],
            ];
            $cfg = $statusConfig[$application->status] ?? $statusConfig['draft'];
        @endphp

        <div class="bg-white border border-slate-200 rounded-xl p-5 mb-4 flex items-center gap-4">
            <div
                class="w-12 h-12 rounded-xl bg-teal-50 flex items-center justify-center
                    font-display font-bold text-teal-800 text-xs shrink-0">
                {{ strtoupper(substr($application->scholarship->name, 0, 4)) }}
            </div>
            <div class="flex-1 min-w-0">
                <h2 class="font-display font-bold text-teal-900 text-base truncate">
                    {{ $application->scholarship->name }}
                </h2>
                <p class="text-slate-400 text-xs mt-0.5">
                    {{ $application->scholarship->penyalur->name }} ·
                    {{ $application->scholarship->jenjang }} ·
                    {{ $application->scholarship->kategori }}
                </p>
            </div>
            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full shrink-0 {{ $cfg[0] }}">
                {{ $cfg[1] }}
            </span>
        </div>

        {{-- Tabs --}}
        <div class="flex flex-col gap-1 mb-4" x-data="{ tab: 'timeline' }">
            <div class="flex justify-start items-center space-x-2">
                <button @click="tab='timeline'"
                    :class="tab === 'timeline' ? 'bg-white text-teal-700 border-slate-200 font-semibold' :
                        'text-teal-800 font-semibold border-transparent'"
                    class="text-xs px-4 py-2 rounded-xl border transition-all hover:bg-white">
                    Timeline
                </button>
                <button @click="tab='saw'"
                    :class="tab === 'saw' ? 'bg-white text-teal-700 border-slate-200 font-semibold' :
                        'text-teal-800 font-semibold border-transparent'"
                    class="text-xs px-4 py-2 rounded-xl border transition-all hover:bg-white">
                    Skor SAW
                </button>
                <button @click="tab='dokumen'"
                    :class="tab === 'dokumen' ? 'bg-white text-teal-700 border-slate-200 font-semibold' :
                        'text-teal-800 font-semibold border-transparent'"
                    class="text-xs px-4 py-2 rounded-xl border transition-all hover:bg-white">
                    Dokumen
                </button>
            </div>

            {{-- ===== TAB: TIMELINE ===== --}}
            <template x-if="tab==='timeline'">
                <div class="bg-white border border-slate-200 rounded-xl p-5 w-full mt-0">
                    <h3 class="font-display text-sm font-bold text-teal-900 mb-5">
                        {{ $application->scholarship->name }} — Detail Tahapan
                    </h3>
                    <div class="flex flex-col">
                        @foreach ($statusLogs as $index => $log)
                            @php
                                $isLast = $index === count($statusLogs) - 1;
                                $iconMap = [
                                    'submitted' => ['bg-green-100 text-green-700', 'check'],
                                    'under_review' => ['bg-amber-100 text-amber-700', 'hourglass_empty'],
                                    'accepted' => ['bg-teal-50 text-teal-700 border-2 border-teal-500', 'emoji_events'],
                                    'rejected' => ['bg-red-100 text-red-700', 'close'],
                                ];
                                $iconCfg = $iconMap[$log->status] ?? [
                                    'bg-slate-100 text-slate-500',
                                    'radio_button_unchecked',
                                ];
                            @endphp
                            <div class="flex gap-4">
                                <div class="flex flex-col items-center">
                                    <div
                                        class="w-7 h-7 rounded-full {{ $iconCfg[0] }} flex items-center
                                            justify-content-center shrink-0">
                                        <span class="material-symbols-outlined text-[14px]">{{ $iconCfg[1] }}</span>
                                    </div>
                                    @if (!$isLast)
                                        <div class="w-0.5 bg-teal-400 flex-1 my-1 min-h-[20px]"></div>
                                    @endif
                                </div>
                                <div class="{{ $isLast ? 'pb-2' : 'pb-5' }}">
                                    <p
                                        class="text-sm font-semibold {{ $log->status === 'accepted' ? 'text-teal-700' : 'text-teal-900' }}">
                                        {{ $log->label }}
                                    </p>
                                    <p class="text-xs text-slate-400 mt-0.5">
                                        {{ $log->created_at->format('d F Y') }}
                                        @if ($log->note)
                                            · {{ $log->note }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </template>

            {{-- ===== TAB: SAW ===== --}}
            <template x-if="tab==='saw'">
                <div class="bg-white border border-slate-200 rounded-xl p-5 w-full">
                    @if ($application->saw_score)
                        {{-- Info banner --}}
                        <div
                            class="bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 flex gap-3
                                items-start text-xs text-blue-800 mb-5">
                            <i class="fa-solid fa-circle-info mt-0.5 shrink-0"></i>
                            <span>Skor dihitung otomatis menggunakan metode <strong>Simple Additive Weighting
                                    (SAW)</strong>.
                                Setiap kriteria dinormalisasi lalu dikalikan bobotnya.</span>
                        </div>

                        {{-- Score hero --}}
                        <div class="bg-teal-50 rounded-xl p-5 flex items-center justify-around gap-4 mb-5">
                            <div class="text-center">
                                <p class="text-3xl font-extrabold text-teal-700">
                                    {{ number_format($application->saw_score, 3) }}
                                </p>
                                <p class="text-xs text-slate-500 mt-1">Skor SAW kamu</p>
                            </div>
                            <div class="w-px h-14 bg-teal-200"></div>
                            <div class="text-center">
                                <p class="text-3xl font-extrabold text-teal-700">#{{ $application->saw_rank }}</p>
                                <p class="text-xs text-slate-500 mt-1">Ranking kamu</p>
                                <p class="text-xs text-slate-400">dari {{ $totalApplicants }} pelamar</p>
                            </div>
                            <div class="w-px h-14 bg-teal-200"></div>
                            <div class="text-center">
                                <p
                                    class="text-sm font-bold text-teal-700 bg-white border border-teal-200
                                      px-3 py-1.5 rounded-lg">
                                    Kuota: {{ $application->scholarship->quota }}
                                </p>
                                @if ($application->saw_rank <= $application->scholarship->quota)
                                    <p class="text-xs text-green-600 font-semibold mt-2">✓ Masuk kuota</p>
                                @else
                                    <p class="text-xs text-red-500 font-semibold mt-2">✗ Di luar kuota</p>
                                @endif
                            </div>
                        </div>

                        {{-- Criteria breakdown --}}
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs border-collapse">
                                <thead>
                                    <tr class="bg-slate-50">
                                        <th
                                            class="text-left px-3 py-2.5 text-slate-400 font-semibold uppercase tracking-wide">
                                            Kriteria</th>
                                        <th
                                            class="text-left px-3 py-2.5 text-slate-400 font-semibold uppercase tracking-wide">
                                            Tipe</th>
                                        <th class="px-3 py-2.5 text-slate-400 font-semibold uppercase tracking-wide">Nilai
                                        </th>
                                        <th class="px-3 py-2.5 text-slate-400 font-semibold uppercase tracking-wide">
                                            Normalisasi</th>
                                        <th class="px-3 py-2.5 text-slate-400 font-semibold uppercase tracking-wide">Bobot
                                        </th>
                                        <th class="px-3 py-2.5 text-slate-400 font-semibold uppercase tracking-wide">
                                            Kontribusi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($sawResults as $result)
                                        <tr class="border-t border-slate-100">
                                            <td class="px-3 py-2.5 font-semibold text-teal-900">
                                                {{ $result->criteria->name }}
                                            </td>
                                            <td class="px-3 py-2.5">
                                                <span
                                                    class="text-[10px] font-bold px-2 py-0.5 rounded-md
                                                {{ $result->criteria->type === 'benefit' ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">
                                                    {{ ucfirst($result->criteria->type) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2.5 text-center text-slate-700">
                                                {{ $result->raw_value }}
                                            </td>
                                            <td class="px-3 py-2.5">
                                                <div class="flex items-center gap-2">
                                                    <div class="flex-1 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                                        <div class="h-full bg-teal-500 rounded-full"
                                                            style="width: {{ $result->normalized_value * 100 }}%"></div>
                                                    </div>
                                                    <span class="text-teal-700 font-semibold min-w-[36px] text-right">
                                                        {{ number_format($result->normalized_value, 3) }}
                                                    </span>
                                                </div>
                                            </td>
                                            <td class="px-3 py-2.5 text-center text-slate-600">
                                                {{ $result->criteria->weight / 1 }}%
                                            </td>
                                            <td class="px-3 py-2.5 text-center font-bold text-teal-900">
                                                {{ number_format($result->weighted_value, 3) }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-teal-50 border-t-2 border-teal-200">
                                        <td colspan="5" class="px-3 py-2.5 text-xs text-teal-700">
                                            Total skor SAW (Σ bobot × normalisasi)
                                        </td>
                                        <td class="px-3 py-2.5 text-center text-base font-extrabold text-teal-700">
                                            {{ number_format($application->saw_score, 3) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-3">
                            Formula: Benefit → nilai / max · Cost → min / nilai. Skor akhir = Σ (bobot × nilai normalisasi).
                        </p>
                    @else
                        <div class="flex flex-col items-center justify-center py-14 gap-3 text-slate-400">
                            <i class="fa-solid fa-chart-bar text-4xl opacity-30"></i>
                            <p class="text-sm text-center">Skor SAW belum tersedia.<br>
                                Perhitungan dilakukan setelah semua dokumen terverifikasi.</p>
                        </div>
                    @endif
                </div>
            </template>

            {{-- ===== TAB: DOKUMEN ===== --}}
            <template x-if="tab==='dokumen'">
                <div class="bg-white border border-slate-200 rounded-xl p-5 w-full">
                    <div class="flex flex-col gap-3">
                        @foreach ($applicationDocuments as $doc)
                            @php
                                $docCfg = [
                                    'uploaded' => ['bg-blue-50 text-blue-700', 'Menunggu Review'],
                                    'approved' => ['bg-green-100 text-green-800', 'Disetujui'],
                                    'rejected' => ['bg-red-100 text-red-800', 'Ditolak'],
                                ];
                                $dc = $docCfg[$doc->status] ?? ['bg-slate-100 text-slate-600', 'Belum diupload'];
                            @endphp
                            <div
                                class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200
                                    {{ $doc->status === 'rejected' ? 'bg-red-50 border-red-200' : 'bg-white' }}">
                                <div class="w-9 h-9 rounded-lg bg-slate-50 flex items-center justify-center shrink-0">
                                    <i class="fa-solid fa-file-lines text-slate-400"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-semibold text-teal-900">
                                        {{ $doc->scholarshipDocument->name }}
                                    </p>
                                    @if ($doc->rejection_note)
                                        <p class="text-xs text-red-500 mt-0.5">
                                            <i class="fa-solid fa-triangle-exclamation text-[10px]"></i>
                                            {{ $doc->rejection_note }}
                                        </p>
                                    @else
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            {{ $doc->updated_at?->format('d M Y') ?? '-' }}
                                        </p>
                                    @endif
                                </div>
                                <span class="text-[11px] font-bold px-2.5 py-1 rounded-full shrink-0 {{ $dc[0] }}">
                                    {{ $dc[1] }}
                                </span>
                                @if ($doc->status === 'rejected')
                                    <a href="{{ route('penerima.lamaran.reupload', $doc->id) }}"
                                        class="text-xs font-semibold text-white bg-teal-600 px-3 py-1.5
                                           rounded-lg hover:bg-teal-700 transition-all shrink-0">
                                        Upload Ulang
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </template>
        </div>

    </section>
@endsection
