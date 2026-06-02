@extends('penyalur.layout.layout')

@section('title', 'Dashboard — Scholr Penyalur')

@section('content')

    @php
        $namaOrg = auth()->user()->penyalurProfile->nama_lembaga ?? 'Penyalur';
        $today = \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY');

        $totalAktif = $stats['total_aktif'] ?? 0;
        $totalPelamar = $stats['total_pelamar'] ?? 0;
        $docPending = $stats['doc_pending'] ?? 0;
        $penerimaDitetapkan = $stats['penerima_ditetapkan'] ?? 0;
        $totalKuota = $stats['total_kuota'] ?? 0;

        $newPelamar = $stats['new_pelamar'] ?? 0;
        $newAktif = $stats['new_aktif'] ?? 0;
    @endphp

    {{-- ============================================================ --}}
    {{-- TOP BAR                                                       --}}
    {{-- ============================================================ --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-xl font-semibold text-slate-800">Selamat datang, {{ $namaOrg }}!</h1>
            <p class="text-sm text-slate-500 mt-0.5 font-mono">{{ $today }} · Dashboard Penyalur</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Notifikasi --}}
            <div class="relative">
                <a href="{{ route('penyalur.notifikasi') }}"
                    class="w-9 h-9 bg-white border border-slate-200 rounded-xl flex items-center justify-center text-slate-500 hover:bg-slate-50 transition-colors">
                    <i class="fa-regular fa-bell text-base"></i>
                </a>
                @if (isset($unreadNotif) && $unreadNotif > 0)
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white"></span>
                @endif
            </div>
            {{-- CTA --}}
            <a href="{{ route('penyalur.beasiswa.create') }}"
                class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors shadow-sm shadow-teal-200">
                <i class="fa-solid fa-plus text-xs"></i> Buat Beasiswa
            </a>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- STATS CARDS                                                   --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-5">

        {{-- Beasiswa Aktif --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-4 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-xs text-slate-400">Beasiswa aktif</p>
                <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center">
                    <i class="fa-solid fa-award text-teal-600 text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-800 leading-none">{{ $totalAktif }}</p>
                @if ($newAktif > 0)
                    <p class="text-xs text-teal-600 mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up text-[10px]"></i> {{ $newAktif }} baru bulan ini
                    </p>
                @else
                    <p class="text-xs text-slate-400 mt-1.5">beasiswa berjalan</p>
                @endif
            </div>
        </div>

        {{-- Total Pelamar --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-4 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-xs text-slate-400">Total pelamar</p>
                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center">
                    <i class="fa-solid fa-users text-blue-600 text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-800 leading-none">{{ number_format($totalPelamar) }}</p>
                @if ($newPelamar > 0)
                    <p class="text-xs text-blue-600 mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-arrow-up text-[10px]"></i> {{ $newPelamar }} pelamar baru
                    </p>
                @else
                    <p class="text-xs text-slate-400 mt-1.5">dari semua beasiswa</p>
                @endif
            </div>
        </div>

        {{-- Dokumen Pending --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-4 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-xs text-slate-400">Dokumen pending</p>
                <div
                    class="w-8 h-8 rounded-lg {{ $docPending > 0 ? 'bg-amber-50' : 'bg-slate-50' }} flex items-center justify-center">
                    <i
                        class="fa-solid fa-file-circle-check {{ $docPending > 0 ? 'text-amber-600' : 'text-slate-400' }} text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-800 leading-none">{{ $docPending }}</p>
                @if ($docPending > 0)
                    <p class="text-xs text-amber-600 mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-clock text-[10px]"></i> Perlu ditinjau
                    </p>
                @else
                    <p class="text-xs text-teal-600 mt-1.5 flex items-center gap-1">
                        <i class="fa-solid fa-circle-check text-[10px]"></i> Semua beres
                    </p>
                @endif
            </div>
        </div>

        {{-- Penerima Ditetapkan --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-4 flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <p class="text-xs text-slate-400">Penerima ditetapkan</p>
                <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center">
                    <i class="fa-solid fa-user-check text-teal-600 text-sm"></i>
                </div>
            </div>
            <div>
                <p class="text-3xl font-bold text-slate-800 leading-none">{{ $penerimaDitetapkan }}</p>
                <p class="text-xs text-slate-400 mt-1.5">dari {{ $totalKuota }} total kuota</p>
            </div>
        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- QUICK ACTIONS                                                  --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-4 gap-3 mb-5">
        <a href="{{ route('penyalur.beasiswa.create') }}"
            class="bg-white border border-slate-200 rounded-xl p-3.5 flex flex-col items-center gap-2 hover:bg-slate-50 hover:border-teal-200 transition-all group">
            <div
                class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center group-hover:bg-teal-100 transition-colors">
                <i class="fa-solid fa-plus text-teal-600 text-base"></i>
            </div>
            <span class="text-xs text-slate-500 text-center leading-tight">Buat beasiswa baru</span>
        </a>
        <a href="{{ route('penyalur.pelamar.index') }}"
            class="bg-white border border-slate-200 rounded-xl p-3.5 flex flex-col items-center gap-2 hover:bg-slate-50 hover:border-blue-200 transition-all group">
            <div
                class="w-9 h-9 rounded-xl bg-blue-50 flex items-center justify-center group-hover:bg-blue-100 transition-colors">
                <i class="fa-solid fa-file-circle-check text-blue-600 text-base"></i>
            </div>
            <span class="text-xs text-slate-500 text-center leading-tight">Review dokumen</span>
        </a>
        <a href="{{ route('penyalur.beasiswa') }}"
            class="bg-white border border-slate-200 rounded-xl p-3.5 flex flex-col items-center gap-2 hover:bg-slate-50 hover:border-amber-200 transition-all group">
            <div
                class="w-9 h-9 rounded-xl bg-amber-50 flex items-center justify-center group-hover:bg-amber-100 transition-colors">
                <i class="fa-solid fa-chart-bar text-amber-600 text-base"></i>
            </div>
            <span class="text-xs text-slate-500 text-center leading-tight">Kelola beasiswa</span>
        </a>
        <a href="{{ route('penyalur.profile') }}"
            class="bg-white border border-slate-200 rounded-xl p-3.5 flex flex-col items-center gap-2 hover:bg-slate-50 hover:border-slate-300 transition-all group">
            <div
                class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center group-hover:bg-slate-200 transition-colors">
                <i class="fa-solid fa-building text-slate-600 text-base"></i>
            </div>
            <span class="text-xs text-slate-500 text-center leading-tight">Profil organisasi</span>
        </a>
    </div>

    {{-- ============================================================ --}}
    {{-- MAIN CONTENT GRID                                             --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

        {{-- Beasiswa berjalan --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-semibold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-award text-teal-600 text-sm"></i> Beasiswa berjalan
                </h2>
                <a href="{{ route('penyalur.beasiswa') }}"
                    class="text-xs text-teal-600 hover:text-teal-700 transition-colors">
                    Lihat semua →
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($beasiswaBerjalan ?? [] as $bs)
                    @php
                        $statusColor = match ($bs->status) {
                            'active' => 'bg-teal-100 text-teal-700',
                            'draft' => 'bg-slate-100 text-slate-500',
                            'completed' => 'bg-slate-100 text-slate-500',
                            default => 'bg-slate-100 text-slate-500',
                        };
                        $statusLabel = match ($bs->status) {
                            'active' => 'Aktif',
                            'draft' => 'Draft',
                            'completed' => 'Selesai',
                            default => $bs->status,
                        };
                        $isDeadlineSoon =
                            \Carbon\Carbon::parse($bs->close_date)->diffInDays(now()) <= 7 && $bs->status === 'active';
                    @endphp
                    <div class="flex items-center justify-between px-5 py-3 hover:bg-slate-50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div
                                class="w-2 h-2 rounded-full flex-shrink-0 {{ $bs->status === 'active' ? 'bg-teal-500' : 'bg-slate-300' }}">
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-slate-800 truncate">{{ $bs->name }}</p>
                                <p class="text-xs text-slate-400 mt-0.5">
                                    Tutup
                                    {{ \Carbon\Carbon::parse($bs->close_date)->locale('id')->isoFormat('D MMM YYYY') }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5 flex-shrink-0 ml-3">
                            <div class="text-right">
                                <p class="text-xs font-semibold text-slate-700">{{ $bs->applications_count ?? 0 }} pelamar
                                </p>
                            </div>
                            @if ($isDeadlineSoon)
                                <span
                                    class="text-[10px] font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full whitespace-nowrap">Segera
                                    tutup</span>
                            @else
                                <span
                                    class="text-[10px] font-semibold {{ $statusColor }} px-2 py-0.5 rounded-full">{{ $statusLabel }}</span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center">
                        <i class="fa-regular fa-folder-open text-slate-300 text-2xl mb-2"></i>
                        <p class="text-sm text-slate-400">Belum ada beasiswa aktif</p>
                        <a href="{{ route('penyalur.beasiswa.create') }}"
                            class="text-xs text-teal-600 hover:underline mt-1 inline-block">Buat beasiswa pertama →</a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Aktivitas terbaru --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-semibold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-teal-600 text-sm"></i> Aktivitas terbaru
                </h2>
                <a href="{{ route('penyalur.notifikasi') }}"
                    class="text-xs text-teal-600 hover:text-teal-700 transition-colors">
                    Semua →
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($recentActivities ?? [] as $activity)
                    @php
                        $initials = strtoupper(substr($activity->user->name ?? '?', 0, 2));
                        $avatarColors = [
                            'bg-teal-100 text-teal-700',
                            'bg-blue-100 text-blue-700',
                            'bg-amber-100 text-amber-700',
                            'bg-pink-100 text-pink-700',
                            'bg-emerald-100 text-emerald-700',
                        ];
                        $colorClass = $avatarColors[$loop->index % count($avatarColors)];
                    @endphp
                    <div class="flex items-start gap-3 px-5 py-3 hover:bg-slate-50 transition-colors">
                        <div
                            class="w-8 h-8 rounded-full {{ $colorClass }} flex items-center justify-center flex-shrink-0 text-[11px] font-semibold mt-0.5">
                            {{ $initials }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-slate-800">{{ $activity->user->name ?? '-' }}</p>
                            <p class="text-xs text-slate-500 mt-0.5 leading-snug">{{ $activity->note }}</p>
                        </div>
                        <span class="text-[11px] text-slate-400 whitespace-nowrap mt-0.5 flex-shrink-0">
                            {{ \Carbon\Carbon::parse($activity->created_at)->diffForHumans(null, true, true) }}
                        </span>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center">
                        <i class="fa-regular fa-clock text-slate-300 text-2xl mb-2"></i>
                        <p class="text-sm text-slate-400">Belum ada aktivitas terbaru</p>
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- ============================================================ --}}
    {{-- SECOND ROW                                                    --}}
    {{-- ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">

        {{-- Progres kuota --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100">
                <h2 class="font-semibold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-circle-half-stroke text-teal-600 text-sm"></i> Progres kuota per beasiswa
                </h2>
            </div>
            <div class="px-5 py-4 space-y-4">
                @forelse($progressKuota ?? [] as $item)
                    @php
                        $pct = $item['kuota'] > 0 ? min(100, round(($item['ditetapkan'] / $item['kuota']) * 100)) : 0;
                        $barColors = ['#1D9E75', '#378ADD', '#BA7517', '#888780', '#5DCAA5'];
                        $barColor = $barColors[$loop->index % count($barColors)];
                    @endphp
                    <div class="space-y-1.5">
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-slate-700 truncate max-w-[70%]">{{ $item['name'] }}</p>
                            <p class="text-xs text-slate-500 flex-shrink-0 ml-2">{{ $item['ditetapkan'] }} /
                                {{ $item['kuota'] }}</p>
                        </div>
                        <div class="h-1.5 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full transition-all duration-500"
                                style="width: {{ $pct }}%; background: {{ $barColor }}"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 text-center py-4">Belum ada data kuota</p>
                @endforelse
            </div>
        </div>

        {{-- Dokumen perlu ditinjau --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                <h2 class="font-semibold text-slate-800 text-sm flex items-center gap-2">
                    <i class="fa-solid fa-file-circle-exclamation text-amber-500 text-sm"></i> Dokumen perlu ditinjau
                    @if ($docPending > 0)
                        <span
                            class="text-[10px] font-semibold bg-amber-100 text-amber-700 px-1.5 py-0.5 rounded-full">{{ $docPending }}</span>
                    @endif
                </h2>
                <a href="{{ route('penyalur.pelamar.index') }}"
                    class="text-xs text-teal-600 hover:text-teal-700 transition-colors">
                    Review semua →
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm" style="table-layout: fixed">
                    <colgroup>
                        <col style="width: 35%">
                        <col style="width: 38%">
                        <col style="width: 27%">
                    </colgroup>
                    <thead>
                        <tr class="bg-slate-50/60 border-b border-slate-100">
                            <th
                                class="px-4 py-2.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wide">
                                Pelamar</th>
                            <th
                                class="px-4 py-2.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wide">
                                Beasiswa</th>
                            <th
                                class="px-4 py-2.5 text-left text-[11px] font-semibold text-slate-400 uppercase tracking-wide">
                                Dokumen</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dokumenPending ?? [] as $doc)
                            @php
                                $initials = strtoupper(substr($doc->application->user->name ?? '?', 0, 2));
                                $avatarColors = [
                                    'bg-teal-100 text-teal-700',
                                    'bg-blue-100 text-blue-700',
                                    'bg-amber-100 text-amber-700',
                                    'bg-pink-100 text-pink-700',
                                ];
                                $colorClass = $avatarColors[$loop->index % count($avatarColors)];
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-2.5">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div
                                            class="w-6 h-6 rounded-full {{ $colorClass }} flex items-center justify-center flex-shrink-0 text-[10px] font-semibold">
                                            {{ $initials }}
                                        </div>
                                        <span
                                            class="text-xs text-slate-700 truncate">{{ Str::limit($doc->application->user->name ?? '-', 14) }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-2.5 text-xs text-slate-500 truncate">
                                    {{ Str::limit($doc->application->scholarship->name ?? '-', 18) }}
                                </td>
                                <td class="px-4 py-2.5">
                                    <span
                                        class="text-[10px] font-semibold bg-amber-100 text-amber-700 px-2 py-0.5 rounded-full whitespace-nowrap">
                                        {{ Str::limit($doc->scholarshipDocument->name ?? '-', 10) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-center">
                                    <i class="fa-regular fa-circle-check text-teal-300 text-xl mb-1.5 block"></i>
                                    <p class="text-xs text-slate-400">Semua dokumen sudah ditinjau</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection
