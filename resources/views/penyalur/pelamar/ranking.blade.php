@extends('penyalur.layout.layout')

@section('title', 'Ranking SPK — ' . $scholarship->name)

@php
    $fmt = fn($value, $decimals = 4) => rtrim(rtrim(number_format($value, $decimals), '0'), '.');
@endphp

@section('content')

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 mb-5">
        <a href="{{ route('penyalur.pelamar.index') }}" class="hover:text-teal-600 transition-colors">Manajemen Pelamar</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('penyalur.pelamar.show', $scholarship->id) }}" class="hover:text-teal-600 transition-colors">
            {{ Str::limit($scholarship->name, 30) }}
        </a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-slate-600 font-medium">Hasil SPK</span>
    </div>

    {{-- Alert --}}
    @if (session('success'))
        <div
            class="mb-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="font-display text-xl font-bold text-slate-800">Hasil Perhitungan SPK</h1>
            <p class="text-slate-500 text-sm mt-0.5">
                {{ $scholarship->name }} · Kuota:
                <span class="font-semibold text-slate-700">{{ $scholarship->quota }} penerima</span>
            </p>
        </div>
        @if ($scholarship->status !== 'Selesai')
            <form action="{{ route('penyalur.pelamar.runSpk', $scholarship->id) }}" method="POST"
                onsubmit="return confirm('Hitung ulang SAW & SMART? Hasil sebelumnya akan ditimpa.')">
                @csrf
                <button type="submit"
                    class="inline-flex items-center gap-2 bg-slate-700 hover:bg-slate-800 text-white text-sm font-semibold px-4 py-2.5 rounded-xl transition-colors">
                    <i class="fa-solid fa-rotate-right"></i> Hitung Ulang SPK
                </button>
            </form>
        @endif
    </div>

    {{-- Summary Cards --}}
    @php
        $totalPelamar = $applications->count();
        $topSaw = $applications->sortBy('saw_rank')->first();
        $topSmart = $applications->sortBy('smart_rank')->first();
        $avgSaw = $applications->avg('saw_score');
        $avgSmart = $applications->avg('smart_score');
    @endphp
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-6">
        <div class="bg-white rounded-2xl border border-slate-200 px-4 py-4">
            <p class="text-slate-400 text-xs mb-1">Total Pelamar</p>
            <p class="font-display font-bold text-2xl text-slate-800">{{ $totalPelamar }}</p>
            <p class="text-slate-400 text-[10px] mt-0.5">yang dihitung</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 px-4 py-4">
            <p class="text-slate-400 text-xs mb-1">Kuota Tersisa</p>
            @php $accepted = $applications->where('status', 'accepted')->count(); @endphp
            <p class="font-display font-bold text-2xl text-teal-600">{{ $scholarship->quota - $accepted }}</p>
            <p class="text-slate-400 text-[10px] mt-0.5">dari {{ $scholarship->quota }} kuota</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 px-4 py-4">
            <p class="text-slate-400 text-xs mb-1">Beda Ranking</p>
            <p class="font-display font-bold text-2xl {{ $totalChanged > 0 ? 'text-amber-500' : 'text-emerald-600' }}">
                {{ $totalChanged }}
            </p>
            <p class="text-slate-400 text-[10px] mt-0.5">pelamar beda posisi SAW↔SMART</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 px-4 py-4">
            <p class="text-slate-400 text-xs mb-1">Selisih Terbesar</p>
            <p class="font-display font-bold text-2xl {{ $maxDiff > 0 ? 'text-amber-500' : 'text-emerald-600' }}">
                {{ $maxDiff > 0 ? '+' . $maxDiff : '0' }}
            </p>
            <p class="text-slate-400 text-[10px] mt-0.5">posisi ranking antar metode</p>
        </div>
    </div>

    {{-- ── TAB NAVIGATION ─────────────────────────────────────── --}}
    <div class="border-b border-slate-200 mb-6">
        <nav class="flex gap-1 -mb-px">
            @foreach ([['id' => 'tab-saw', 'label' => 'Hasil SAW', 'icon' => 'fa-table-list'], ['id' => 'tab-smart', 'label' => 'Hasil SMART', 'icon' => 'fa-table-list'], ['id' => 'tab-compare', 'label' => 'Perbandingan', 'icon' => 'fa-arrows-left-right'], ['id' => 'tab-tetapkan', 'label' => 'Tetapkan Penerima', 'icon' => 'fa-user-check']] as $tab)
                <button onclick="switchTab('{{ $tab['id'] }}')" id="btn-{{ $tab['id'] }}"
                    class="tab-btn flex items-center gap-2 px-4 py-3 text-sm font-medium border-b-2 transition-colors whitespace-nowrap
                    {{ $tab['id'] === 'tab-saw' ? 'border-teal-600 text-teal-600' : 'border-transparent text-slate-500 hover:text-slate-700' }}">
                    <i class="fa-solid {{ $tab['icon'] }} text-xs"></i>
                    {{ $tab['label'] }}
                    @if ($tab['id'] === 'tab-compare' && $totalChanged > 0)
                        <span
                            class="ml-1 text-[10px] bg-amber-100 text-amber-600 font-bold px-1.5 py-0.5 rounded-full">{{ $totalChanged }}</span>
                    @endif
                    @if ($tab['id'] === 'tab-tetapkan' && $scholarship->status === 'Selesai')
                        <span
                            class="ml-1 text-[10px] bg-emerald-100 text-emerald-600 font-bold px-1.5 py-0.5 rounded-full">✓</span>
                    @endif
                </button>
            @endforeach
        </nav>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- TAB 1: HASIL SAW                                          --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div id="tab-saw" class="tab-panel">

        {{-- Formula SAW --}}
        <div
            class="mb-4 bg-blue-50 border border-blue-100 rounded-xl px-4 py-3 text-xs text-blue-800 flex flex-wrap gap-x-6 gap-y-1.5 items-center">
            <span class="font-semibold">Formula SAW:</span>
            <span>Benefit: <code class="font-mono">r = x / max(x)</code></span>
            <span>Cost: <code class="font-mono">r = min(x) / x</code></span>
            <span>Skor: <code class="font-mono">V = Σ(w × r)</code></span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div
                class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="font-display font-bold text-slate-800">Matriks Perhitungan SAW</h2>
                    <p class="text-slate-400 text-xs mt-0.5">
                        Nilai mentah <span class="font-mono text-slate-500">(xij)</span>,
                        normalisasi <span class="font-mono text-indigo-500">(rij)</span>,
                        tertimbang <span class="font-mono text-teal-600">(wj·rij)</span>
                    </p>
                </div>
                <div class="flex items-center gap-4 text-xs text-slate-400 flex-wrap">
                    <span class="flex items-center gap-1"><span
                            class="w-2 h-2 rounded-full bg-slate-400 inline-block"></span>xij</span>
                    <span class="flex items-center gap-1"><span
                            class="w-2 h-2 rounded-full bg-indigo-400 inline-block"></span>rij</span>
                    <span class="flex items-center gap-1"><span
                            class="w-2 h-2 rounded-full bg-teal-500 inline-block"></span>wj·rij</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th
                                class="sticky left-0 bg-slate-50 text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide whitespace-nowrap w-12">
                                Rank</th>
                            <th
                                class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide whitespace-nowrap min-w-[150px]">
                                Pelamar</th>
                            @foreach ($scholarship->criteria as $c)
                                <th colspan="3"
                                    class="text-center px-2 py-3 text-xs font-semibold text-slate-500 border-l border-slate-100 whitespace-nowrap">
                                    <div>{{ Str::limit($c->name, 16) }}</div>
                                    <div class="flex items-center justify-center gap-1.5 mt-1 font-normal normal-case">
                                        <span
                                            class="{{ $c->type === 'Benefit' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }} text-[10px] font-bold px-1.5 py-0.5 rounded">
                                            {{ $c->type === 'Benefit' ? 'B' : 'C' }}
                                        </span>
                                        <span
                                            class="text-slate-400 text-[10px]">{{ rtrim(rtrim(number_format($c->weight, 2), '0'), '.') }}%</span>
                                    </div>
                                </th>
                            @endforeach
                            <th
                                class="text-right px-4 py-3 text-xs font-semibold text-teal-600 uppercase tracking-wide whitespace-nowrap border-l border-slate-100">
                                Skor (Vi)</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                Status</th>
                        </tr>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-medium">
                            <th class="sticky left-0 bg-slate-50/50 px-1 py-1"></th>
                            <th class="px-1 py-1"></th>
                            @foreach ($scholarship->criteria as $c)
                                <th class="px-3 py-1 text-slate-400 border-l border-slate-100 text-center font-mono">xij
                                </th>
                                <th class="px-3 py-1 text-indigo-400 text-center font-mono">rij</th>
                                <th class="px-3 py-1 text-teal-500 text-center font-mono">wj·rij</th>
                            @endforeach
                            <th class="border-l border-slate-100"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($applications->sortBy('saw_rank') as $app)
                            @php
                                $inQuota = $app->saw_rank <= $scholarship->quota;
                                $rowBg =
                                    $app->status === 'accepted'
                                        ? 'bg-emerald-50/50'
                                        : ($inQuota
                                            ? 'bg-teal-50/30'
                                            : '');
                            @endphp
                            <tr class="{{ $rowBg }} hover:bg-slate-50 transition-colors">
                                <td class="sticky left-0 {{ $rowBg ?: 'bg-white' }} px-4 py-3 text-center font-bold">
                                    @if ($app->saw_rank === 1)
                                        🥇
                                    @elseif($app->saw_rank === 2)
                                        🥈
                                    @elseif($app->saw_rank === 3)
                                        🥉
                                    @else
                                        <span class="text-slate-500 text-sm">#{{ $app->saw_rank }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                                            <span
                                                class="text-[10px] font-bold text-teal-700 uppercase">{{ substr($app->user->name, 0, 2) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800 text-xs whitespace-nowrap">
                                                {{ $app->user->name }}</p>
                                            @if ($inQuota && $scholarship->status !== 'Selesai')
                                                <span
                                                    class="text-[9px] bg-teal-100 text-teal-700 px-1.5 py-0.5 rounded font-semibold">Dalam
                                                    Kuota</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                @foreach ($scholarship->criteria as $c)
                                    @php $r = $app->sawResults->firstWhere('criteria_id', $c->id); @endphp
                                    <td
                                        class="px-3 py-3 text-center text-xs text-slate-600 border-l border-slate-100 font-mono whitespace-nowrap">
                                        {{ $r ? number_format($r->raw_value, 2) : '—' }}</td>
                                    <td class="px-3 py-3 text-center text-xs text-indigo-600 font-mono whitespace-nowrap">
                                        {{ $r ? number_format($r->normalized_value, 4) : '—' }}</td>
                                    <td
                                        class="px-3 py-3 text-center text-xs text-teal-600 font-medium font-mono whitespace-nowrap">
                                        {{ $r ? number_format($r->weighted_value, 4) : '—' }}</td>
                                @endforeach
                                <td class="px-4 py-3 text-right border-l border-slate-100">
                                    <span
                                        class="font-display font-bold text-teal-700 text-base">{{ number_format($app->saw_score, 4) }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($app->status === 'accepted')
                                        <span
                                            class="text-xs bg-emerald-100 text-emerald-700 font-semibold px-2.5 py-1 rounded-full">Diterima</span>
                                    @elseif($app->status === 'rejected')
                                        <span
                                            class="text-xs bg-red-100 text-red-600 font-semibold px-2.5 py-1 rounded-full">Ditolak</span>
                                    @else
                                        <span
                                            class="text-xs bg-slate-100 text-slate-500 font-semibold px-2.5 py-1 rounded-full">Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div
                class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex flex-wrap gap-x-5 gap-y-1.5 text-xs text-slate-400">
                <span class="flex items-center gap-1.5"><span
                        class="w-3 h-2 rounded-sm bg-teal-100 border border-teal-200 inline-block"></span> Dalam
                    kuota</span>
                <span class="flex items-center gap-1.5"><span
                        class="w-3 h-2 rounded-sm bg-emerald-100 border border-emerald-200 inline-block"></span> Ditetapkan
                    diterima</span>
                <span class="ml-auto">Diurutkan skor SAW tertinggi</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- TAB 2: HASIL SMART                                        --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div id="tab-smart" class="tab-panel hidden">

        {{-- Formula SMART --}}
        <div
            class="mb-4 bg-violet-50 border border-violet-100 rounded-xl px-4 py-3 text-xs text-violet-800 flex flex-wrap gap-x-6 gap-y-1.5 items-center">
            <span class="font-semibold">Formula SMART:</span>
            <span>Benefit: <code class="font-mono">u = (x − min) / (max − min)</code></span>
            <span>Cost: <code class="font-mono">u = (max − x) / (max − min)</code></span>
            <span>Skor: <code class="font-mono">U = Σ(w × u)</code></span>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div
                class="px-5 py-4 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="font-display font-bold text-slate-800">Matriks Perhitungan SMART</h2>
                    <p class="text-slate-400 text-xs mt-0.5">
                        Nilai mentah <span class="font-mono text-slate-500">(xij)</span>,
                        normalisasi min-max <span class="font-mono text-violet-500">(uij)</span>,
                        tertimbang <span class="font-mono text-violet-700">(wj·uij)</span>
                    </p>
                </div>
                <div class="flex items-center gap-4 text-xs text-slate-400 flex-wrap">
                    <span class="flex items-center gap-1"><span
                            class="w-2 h-2 rounded-full bg-slate-400 inline-block"></span>xij</span>
                    <span class="flex items-center gap-1"><span
                            class="w-2 h-2 rounded-full bg-violet-400 inline-block"></span>uij</span>
                    <span class="flex items-center gap-1"><span
                            class="w-2 h-2 rounded-full bg-violet-600 inline-block"></span>wj·uij</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th
                                class="sticky left-0 bg-slate-50 text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide whitespace-nowrap w-12">
                                Rank</th>
                            <th
                                class="text-left px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide whitespace-nowrap min-w-[150px]">
                                Pelamar</th>
                            @foreach ($scholarship->criteria as $c)
                                <th colspan="3"
                                    class="text-center px-2 py-3 text-xs font-semibold text-slate-500 border-l border-slate-100 whitespace-nowrap">
                                    <div>{{ Str::limit($c->name, 16) }}</div>
                                    <div class="flex items-center justify-center gap-1.5 mt-1 font-normal normal-case">
                                        <span
                                            class="{{ $c->type === 'Benefit' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }} text-[10px] font-bold px-1.5 py-0.5 rounded">
                                            {{ $c->type === 'Benefit' ? 'B' : 'C' }}
                                            <span
                                                class="text-slate-400 text-[10px]">{{ rtrim(rtrim(number_format($c->weight, 2), '0'), '.') }}%</span>
                                    </div>
                                </th>
                            @endforeach
                            <th
                                class="text-right px-4 py-3 text-xs font-semibold text-violet-600 uppercase tracking-wide whitespace-nowrap border-l border-slate-100">
                                Skor (Ui)</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                Status</th>
                        </tr>
                        <tr class="bg-slate-50/50 border-b border-slate-100 text-[10px] font-medium">
                            <th class="sticky left-0 bg-slate-50/50 px-1 py-1"></th>
                            <th class="px-1 py-1"></th>
                            @foreach ($scholarship->criteria as $c)
                                <th class="px-3 py-1 text-slate-400 border-l border-slate-100 text-center font-mono">xij
                                </th>
                                <th class="px-3 py-1 text-violet-400 text-center font-mono">uij</th>
                                <th class="px-3 py-1 text-violet-600 text-center font-mono">wj·uij</th>
                            @endforeach
                            <th class="border-l border-slate-100"></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($applications->sortBy('smart_rank') as $app)
                            @php
                                $inQuota = $app->smart_rank <= $scholarship->quota;
                                $rowBg =
                                    $app->status === 'accepted'
                                        ? 'bg-emerald-50/50'
                                        : ($inQuota
                                            ? 'bg-violet-50/30'
                                            : '');
                            @endphp
                            <tr class="{{ $rowBg }} hover:bg-slate-50 transition-colors">
                                <td class="sticky left-0 {{ $rowBg ?: 'bg-white' }} px-4 py-3 text-center font-bold">
                                    @if ($app->smart_rank === 1)
                                        🥇
                                    @elseif($app->smart_rank === 2)
                                        🥈
                                    @elseif($app->smart_rank === 3)
                                        🥉
                                    @else
                                        <span class="text-slate-500 text-sm">#{{ $app->smart_rank }}</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-2">
                                        <div
                                            class="w-7 h-7 rounded-full bg-violet-100 flex items-center justify-center flex-shrink-0">
                                            <span
                                                class="text-[10px] font-bold text-violet-700 uppercase">{{ substr($app->user->name, 0, 2) }}</span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800 text-xs whitespace-nowrap">
                                                {{ $app->user->name }}</p>
                                            @if ($inQuota && $scholarship->status !== 'Selesai')
                                                <span
                                                    class="text-[9px] bg-violet-100 text-violet-700 px-1.5 py-0.5 rounded font-semibold">Dalam
                                                    Kuota</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                @foreach ($scholarship->criteria as $c)
                                    @php $r = $app->smartResults->firstWhere('criteria_id', $c->id); @endphp
                                    <td
                                        class="px-3 py-3 text-center text-xs text-slate-600 border-l border-slate-100 font-mono whitespace-nowrap">
                                        {{ $r ? number_format($r->raw_value, 2) : '—' }}</td>
                                    <td class="px-3 py-3 text-center text-xs text-violet-600 font-mono whitespace-nowrap">
                                        {{ $r ? number_format($r->normalized_value, 4) : '—' }}</td>
                                    <td
                                        class="px-3 py-3 text-center text-xs text-violet-700 font-medium font-mono whitespace-nowrap">
                                        {{ $r ? number_format($r->weighted_value, 4) : '—' }}</td>
                                @endforeach
                                <td class="px-4 py-3 text-right border-l border-slate-100">
                                    <span
                                        class="font-display font-bold text-violet-700 text-base">{{ number_format($app->smart_score, 4) }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if ($app->status === 'accepted')
                                        <span
                                            class="text-xs bg-emerald-100 text-emerald-700 font-semibold px-2.5 py-1 rounded-full">Diterima</span>
                                    @elseif($app->status === 'rejected')
                                        <span
                                            class="text-xs bg-red-100 text-red-600 font-semibold px-2.5 py-1 rounded-full">Ditolak</span>
                                    @else
                                        <span
                                            class="text-xs bg-slate-100 text-slate-500 font-semibold px-2.5 py-1 rounded-full">Menunggu</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div
                class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex flex-wrap gap-x-5 gap-y-1.5 text-xs text-slate-400">
                <span class="flex items-center gap-1.5"><span
                        class="w-3 h-2 rounded-sm bg-violet-100 border border-violet-200 inline-block"></span> Dalam
                    kuota</span>
                <span class="flex items-center gap-1.5"><span
                        class="w-3 h-2 rounded-sm bg-emerald-100 border border-emerald-200 inline-block"></span> Ditetapkan
                    diterima</span>
                <span class="ml-auto">Diurutkan skor SMART tertinggi</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- TAB 3: PERBANDINGAN                                       --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div id="tab-compare" class="tab-panel hidden">

        {{-- Ringkasan analisis --}}
        <div class="mb-5 bg-white border border-slate-200 rounded-2xl p-5">
            <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3">Ringkasan Analisis</p>
            <p class="text-sm text-slate-600 leading-relaxed">
                Dari <strong class="text-slate-800">{{ $totalPelamar }} pelamar</strong>,
                @if ($totalChanged === 0)
                    <span class="text-emerald-600 font-semibold">semua memiliki ranking yang sama</span>
                    di kedua metode — SAW dan SMART menghasilkan kesimpulan yang konsisten.
                @else
                    <span class="text-amber-600 font-semibold">{{ $totalChanged }} pelamar</span>
                    memiliki perbedaan posisi ranking antara SAW dan SMART,
                    dengan selisih terbesar <strong class="text-slate-800">{{ $maxDiff }} posisi</strong>.
                    Perbedaan ini disebabkan oleh perbedaan rumus normalisasi — SAW menggunakan nilai maksimum sebagai
                    acuan,
                    sedangkan SMART memperhitungkan seluruh rentang nilai (min–max).
                @endif
            </p>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="font-display font-bold text-slate-800">Tabel Perbandingan Ranking</h2>
                <p class="text-slate-400 text-xs mt-0.5">Baris kuning = ranking berbeda antar metode</p>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr
                            class="bg-slate-50 border-b border-slate-100 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                            <th class="text-left px-5 py-3 min-w-[160px]">Pelamar</th>
                            <th class="text-center px-4 py-3 text-teal-600 whitespace-nowrap">Rank SAW</th>
                            <th class="text-center px-4 py-3 text-teal-600 whitespace-nowrap">Skor SAW</th>
                            <th class="text-center px-4 py-3 text-violet-600 whitespace-nowrap">Rank SMART</th>
                            <th class="text-center px-4 py-3 text-violet-600 whitespace-nowrap">Skor SMART</th>
                            <th class="text-center px-4 py-3 whitespace-nowrap">Selisih Rank</th>
                            <th class="text-center px-4 py-3">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($comparisons as $item)
                            @php
                                $app = $item['app'];
                                $diff = $item['rank_diff'];
                                $changed = $item['is_changed'];
                                $sawRank = $app->saw_rank;
                                $smtRank = $app->smart_rank;
                                $rowBg = $changed ? 'bg-amber-50/50' : '';
                            @endphp
                            <tr class="{{ $rowBg }} hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center gap-2.5">
                                        <div
                                            class="w-7 h-7 rounded-full {{ $changed ? 'bg-amber-100' : 'bg-slate-100' }} flex items-center justify-center flex-shrink-0">
                                            <span
                                                class="text-[10px] font-bold {{ $changed ? 'text-amber-700' : 'text-slate-600' }} uppercase">{{ substr($app->user->name, 0, 2) }}</span>
                                        </div>
                                        <span class="font-medium text-slate-800 text-sm">{{ $app->user->name }}</span>
                                    </div>
                                </td>
                                {{-- SAW --}}
                                <td class="px-4 py-3.5 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-teal-100 text-teal-700 font-bold text-sm">
                                        {{ $sawRank }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center font-mono text-xs text-teal-700">
                                    {{ number_format($app->saw_score, 4) }}
                                </td>
                                {{-- SMART --}}
                                <td class="px-4 py-3.5 text-center">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-violet-100 text-violet-700 font-bold text-sm">
                                        {{ $smtRank }}
                                    </span>
                                </td>
                                <td class="px-4 py-3.5 text-center font-mono text-xs text-violet-700">
                                    {{ number_format($app->smart_score, 4) }}
                                </td>
                                {{-- Selisih --}}
                                <td class="px-4 py-3.5 text-center">
                                    @if ($diff === 0)
                                        <span
                                            class="text-xs bg-emerald-100 text-emerald-700 font-bold px-2.5 py-1 rounded-full">Sama</span>
                                    @else
                                        @php
                                            $arrow = $smtRank < $sawRank ? '↑' : '↓';
                                            $color = $smtRank < $sawRank ? 'text-emerald-600' : 'text-red-500';
                                        @endphp
                                        <span
                                            class="text-xs bg-amber-100 text-amber-700 font-bold px-2.5 py-1 rounded-full">
                                            {{ $arrow }} {{ $diff }}
                                        </span>
                                    @endif
                                </td>
                                {{-- Keterangan --}}
                                <td class="px-4 py-3.5 text-center text-xs text-slate-500">
                                    @if ($diff === 0)
                                        <span class="text-emerald-600">Konsisten</span>
                                    @elseif($smtRank < $sawRank)
                                        <span class="text-emerald-600">Naik di SMART</span>
                                    @else
                                        <span class="text-red-500">Turun di SMART</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div
                class="px-5 py-3 border-t border-slate-100 bg-slate-50/50 flex flex-wrap gap-x-5 gap-y-1.5 text-xs text-slate-400">
                <span class="flex items-center gap-1.5"><span
                        class="w-3 h-2 rounded-sm bg-amber-100 border border-amber-200 inline-block"></span> Ranking
                    berbeda antar metode</span>
                <span class="flex items-center gap-1.5"><span
                        class="w-3 h-2 rounded-sm bg-white border border-slate-200 inline-block"></span> Ranking
                    konsisten</span>
                <span class="ml-auto">↑ = naik di SMART · ↓ = turun di SMART</span>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════ --}}
    {{-- TAB 4: TETAPKAN PENERIMA                                  --}}
    {{-- ══════════════════════════════════════════════════════════ --}}
    <div id="tab-tetapkan" class="tab-panel hidden">

        @if ($scholarship->status === 'Selesai')
            <div class="bg-white rounded-2xl border border-emerald-200 p-8 text-center">
                <div class="w-14 h-14 rounded-full bg-emerald-100 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-2xl"></i>
                </div>
                <h2 class="font-display font-bold text-slate-800 text-lg mb-2">Penerima Sudah Ditetapkan</h2>
                <p class="text-slate-500 text-sm">Beasiswa ini sudah selesai. Lihat hasilnya di tab SAW atau SMART.</p>
            </div>
        @else
            {{-- Pilih acuan metode --}}
            <div class="mb-5 bg-white border border-slate-200 rounded-2xl p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-3">Pilih Acuan Metode</p>
                <p class="text-sm text-slate-600 mb-4">
                    Gunakan salah satu metode sebagai dasar urutan, atau pilih penerima secara manual.
                    Pilihan di bawah hanya mengubah <em>urutan tampilan</em> — kamu tetap bisa memilih secara manual.
                </p>
                <div class="flex gap-3 flex-wrap">
                    <button onclick="sortByMethod('saw')" id="btn-sort-saw"
                        class="sort-btn active-sort px-4 py-2 text-sm font-semibold rounded-xl border-2 border-teal-500 bg-teal-50 text-teal-700 transition-colors">
                        Urutkan by SAW
                    </button>
                    <button onclick="sortByMethod('smart')" id="btn-sort-smart"
                        class="sort-btn px-4 py-2 text-sm font-semibold rounded-xl border-2 border-slate-200 bg-white text-slate-600 hover:border-violet-400 hover:text-violet-600 transition-colors">
                        Urutkan by SMART
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                    <div>
                        <h2 class="font-display font-bold text-slate-800">Pilih Penerima Beasiswa</h2>
                        <p class="text-slate-400 text-xs mt-0.5">Kuota: <strong
                                class="text-slate-700">{{ $scholarship->quota }}</strong> penerima</p>
                    </div>
                    <span class="text-sm font-semibold text-slate-700">
                        <span id="selected-count">{{ $applications->where('status', 'accepted')->count() }}</span>
                        / {{ $scholarship->quota }} dipilih
                    </span>
                </div>

                <form action="{{ route('penyalur.pelamar.tetapkan', $scholarship->id) }}" method="POST"
                    id="form-tetapkan">
                    @csrf
                    <div class="p-4 space-y-2" id="penerima-list">
                        @foreach ($applications->sortBy('saw_rank') as $app)
                            <label data-saw-rank="{{ $app->saw_rank }}" data-smart-rank="{{ $app->smart_rank }}"
                                class="flex items-center gap-3 p-3.5 rounded-xl border border-slate-200 hover:border-teal-300 hover:bg-teal-50/30 cursor-pointer transition-all has-[:checked]:border-teal-400 has-[:checked]:bg-teal-50">
                                <input type="checkbox" name="application_ids[]" value="{{ $app->id }}"
                                    {{ $app->status === 'accepted' ? 'checked' : '' }}
                                    class="w-4 h-4 rounded accent-teal-600 flex-shrink-0" onchange="validateQuota()">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-medium text-slate-800 text-sm">{{ $app->user->name }}</span>
                                        @if ($app->saw_rank <= $scholarship->quota)
                                            <span
                                                class="text-[10px] bg-teal-100 text-teal-700 px-1.5 rounded font-semibold">Top
                                                SAW</span>
                                        @endif
                                        @if ($app->smart_rank <= $scholarship->quota)
                                            <span
                                                class="text-[10px] bg-violet-100 text-violet-700 px-1.5 rounded font-semibold">Top
                                                SMART</span>
                                        @endif
                                    </div>
                                    <p class="text-slate-400 text-xs mt-0.5">
                                        SAW: Rank #{{ $app->saw_rank }} · <span
                                            class="font-mono">{{ number_format($app->saw_score, 4) }}</span>
                                        &nbsp;·&nbsp;
                                        SMART: Rank #{{ $app->smart_rank }} · <span
                                            class="font-mono">{{ number_format($app->smart_score, 4) }}</span>
                                    </p>
                                </div>
                            </label>
                        @endforeach
                    </div>

                    <div class="px-5 py-4 border-t border-slate-100">
                        <div id="quota-warning"
                            class="hidden mb-3 flex items-center gap-2 bg-red-50 border border-red-200 text-red-600 rounded-xl px-3 py-2 text-xs">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                            <span>Jumlah yang dipilih melebihi kuota ({{ $scholarship->quota }}). Kurangi pilihan.</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <p class="text-xs text-slate-400">
                                <i class="fa-solid fa-triangle-exclamation text-amber-400 mr-1"></i>
                                Tindakan ini tidak dapat dibatalkan.
                            </p>
                            <button type="submit" id="btn-submit-tetapkan"
                                class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold bg-teal-600 hover:bg-teal-700 text-white rounded-xl transition-colors">
                                <i class="fa-solid fa-user-check"></i> Konfirmasi & Tetapkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>

@endsection

@section('script')
    <script>
        const quota = {{ $scholarship->quota }};

        // ── Tab switching ───────────────────────────────────────────
        function switchTab(tabId) {
            document.querySelectorAll('.tab-panel').forEach(p => p.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(b => {
                b.classList.remove('border-teal-600', 'text-teal-600');
                b.classList.add('border-transparent', 'text-slate-500');
            });
            document.getElementById(tabId).classList.remove('hidden');
            const btn = document.getElementById('btn-' + tabId);
            btn.classList.remove('border-transparent', 'text-slate-500');
            btn.classList.add('border-teal-600', 'text-teal-600');
        }

        // ── Sort penerima list by SAW or SMART ──────────────────────
        function sortByMethod(method) {
            const list = document.getElementById('penerima-list');
            const items = Array.from(list.querySelectorAll('label'));

            items.sort((a, b) => {
                const rankA = parseInt(a.dataset[method + 'Rank']);
                const rankB = parseInt(b.dataset[method + 'Rank']);
                return rankA - rankB;
            });

            items.forEach(item => list.appendChild(item));

            // Update active button style
            document.querySelectorAll('.sort-btn').forEach(b => {
                b.classList.remove('border-teal-500', 'bg-teal-50', 'text-teal-700',
                    'border-violet-400', 'bg-violet-50', 'text-violet-700');
                b.classList.add('border-slate-200', 'bg-white', 'text-slate-600');
            });

            const activeBtn = document.getElementById('btn-sort-' + method);
            const colors = method === 'saw' ? ['border-teal-500', 'bg-teal-50', 'text-teal-700'] : ['border-violet-400',
                'bg-violet-50', 'text-violet-700'
            ];
            activeBtn.classList.remove('border-slate-200', 'bg-white', 'text-slate-600');
            activeBtn.classList.add(...colors);
        }

        // ── Quota validation ────────────────────────────────────────
        function validateQuota() {
            const checked = document.querySelectorAll('input[name="application_ids[]"]:checked').length;
            const warning = document.getElementById('quota-warning');
            const btn = document.getElementById('btn-submit-tetapkan');
            const counter = document.getElementById('selected-count');

            if (counter) counter.textContent = checked;

            if (checked > quota) {
                warning?.classList.remove('hidden');
                if (btn) {
                    btn.disabled = true;
                    btn.classList.add('opacity-50', 'cursor-not-allowed');
                }
            } else {
                warning?.classList.add('hidden');
                if (btn) {
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        }

        // ── Konfirmasi submit ───────────────────────────────────────
        document.getElementById('form-tetapkan')?.addEventListener('submit', function(e) {
            const checked = document.querySelectorAll('input[name="application_ids[]"]:checked').length;
            if (!confirm(
                    `Tetapkan ${checked} pelamar sebagai penerima beasiswa?\n\nTindakan ini tidak dapat dibatalkan.`
                )) {
                e.preventDefault();
            }
        });
    </script>
@endsection
