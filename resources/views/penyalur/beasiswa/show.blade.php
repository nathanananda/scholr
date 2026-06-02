@extends('penyalur.layout.layout')

@section('content')
    <div class="container mx-auto px-4 py-4">

        {{-- Header --}}
        <div class="flex items-start justify-between gap-4 mb-6">
            <div class="flex items-center gap-3">
                <a href="{{ route('penyalur.beasiswa') }}"
                    class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition shadow-sm">
                    <i class="fa-solid fa-chevron-left text-xs"></i>
                </a>
                <div>
                    <h2 class="text-xl font-bold text-gray-800 leading-tight">Detail Beasiswa</h2>
                    <p class="text-sm text-gray-500 mt-0.5">Informasi lengkap & kriteria penilaian</p>
                </div>
            </div>

            {{-- Aksi cepat --}}
            <div class="flex items-center gap-2">
                @if ($scholarship->status !== 'Selesai')
                    <a href="{{ route('penyalur.beasiswa.edit', $scholarship->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                        <i class="fa-solid fa-pen-to-square text-blue-500"></i> Edit
                    </a>
                @endif
                @if (in_array($scholarship->status, ['Draft', 'Aktif']))
                    <a href="{{ route('penyalur.beasiswa.criteria', $scholarship->id) }}"
                        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium transition shadow-sm">
                        <i class="fa-solid fa-sliders"></i> Atur Kriteria
                    </a>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- Kiri: Detail Beasiswa --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- Card Utama --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

                    {{-- Banner atas dengan warna sesuai status --}}
                    @php
                        $bannerColor = match ($scholarship->status) {
                            'Aktif' => 'from-emerald-500 to-emerald-600',
                            'Seleksi' => 'from-blue-500 to-blue-600',
                            'Selesai' => 'from-gray-400 to-gray-500',
                            default => 'from-indigo-500 to-indigo-600',
                        };
                        $categoryIcon = match ($scholarship->category) {
                            'Prestasi' => 'fa-graduation-cap',
                            'Sosial' => 'fa-hand-holding-heart',
                            'Internal' => 'fa-building-columns',
                            'Eksternal' => 'fa-earth-asia',
                            default => 'fa-award',
                        };
                    @endphp

                    <div class="bg-gradient-to-r {{ $bannerColor }} px-6 py-5">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid {{ $categoryIcon }} text-white text-xl"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-lg font-bold text-white leading-snug">{{ $scholarship->name }}</h3>
                                <p class="text-white/70 text-sm mt-1">Kategori: {{ $scholarship->category }}</p>
                            </div>
                            @php
                                $statusBadge = match ($scholarship->status) {
                                    'Draft' => 'bg-gray-100 text-gray-700',
                                    'Aktif' => 'bg-emerald-100 text-emerald-700',
                                    'Seleksi' => 'bg-amber-100 text-amber-700',
                                    'Selesai' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span
                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold {{ $statusBadge }} flex-shrink-0">
                                {{ $scholarship->status }}
                            </span>
                        </div>
                    </div>

                    {{-- Info Grid --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 divide-x divide-y divide-gray-100 border-b border-gray-100">
                        <div class="px-5 py-4">
                            <p class="text-xs text-gray-400 mb-1">Kuota</p>
                            <p class="text-lg font-bold text-gray-900">{{ number_format($scholarship->quota) }}</p>
                            <p class="text-xs text-gray-500">orang</p>
                        </div>
                        <div class="px-5 py-4">
                            <p class="text-xs text-gray-400 mb-1">Tanggal Mulai</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($scholarship->start_date)->translatedFormat('d M Y') }}
                            </p>
                        </div>
                        <div class="px-5 py-4">
                            <p class="text-xs text-gray-400 mb-1">Tanggal Selesai</p>
                            <p class="text-sm font-semibold text-gray-900">
                                {{ \Carbon\Carbon::parse($scholarship->end_date)->translatedFormat('d M Y') }}
                            </p>
                        </div>
                    </div>

                    {{-- Deskripsi --}}
                    <div class="px-6 py-5">
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-2">Deskripsi</p>
                        @if ($scholarship->description)
                            <p class="text-sm text-gray-700 leading-relaxed whitespace-pre-line">
                                {{ $scholarship->description }}</p>
                        @else
                            <p class="text-sm text-gray-400 italic">Tidak ada deskripsi.</p>
                        @endif
                    </div>

                </div>

                {{-- Card Kriteria --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60 flex items-center justify-between">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <i class="fa-solid fa-sliders text-indigo-600 text-xs"></i>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-700">Kriteria Penilaian SPK</h3>
                        </div>
                        @if ($scholarship->criteria->count() > 0)
                            <span class="text-xs text-gray-400">
                                {{ $scholarship->criteria->count() }} kriteria &bull;
                                total bobot
                                <span
                                    class="{{ $scholarship->criteria->sum('weight') == 100 ? 'text-emerald-600 font-semibold' : 'text-rose-500 font-semibold' }}">
                                    {{ $scholarship->criteria->sum('weight') }}%
                                </span>
                            </span>
                        @endif
                    </div>

                    @if ($scholarship->criteria->count() > 0)
                        <div class="divide-y divide-gray-100">
                            @foreach ($scholarship->criteria as $criteria)
                                <div class="px-6 py-4">
                                    <div class="flex items-start justify-between gap-4 mb-3">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0
                                                {{ $criteria->type === 'Benefit' ? 'bg-emerald-50 text-emerald-600' : 'bg-rose-50 text-rose-600' }}">
                                                <i
                                                    class="fa-solid {{ $criteria->type === 'Benefit' ? 'fa-arrow-trend-up' : 'fa-arrow-trend-down' }} text-sm"></i>
                                            </div>
                                            <div>
                                                <p class="text-sm font-semibold text-gray-900">{{ $criteria->name }}</p>
                                                <p class="text-xs text-gray-400 mt-0.5">
                                                    {{ $criteria->input_type === 'number' ? 'Input angka langsung' : 'Input pilihan rentang' }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 flex-shrink-0">
                                            <span
                                                class="text-xs px-2.5 py-1 rounded-full font-medium
                                                {{ $criteria->type === 'Benefit' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-rose-50 text-rose-700 border border-rose-200' }}">
                                                {{ $criteria->type }}
                                            </span>
                                            <span
                                                class="text-sm font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-200">
                                                {{ $criteria->weight }}%
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Bobot bar --}}
                                    <div class="w-full bg-gray-100 rounded-full h-1.5 mb-3 overflow-hidden">
                                        <div class="h-1.5 rounded-full bg-indigo-400 transition-all"
                                            style="width: {{ $criteria->weight }}%"></div>
                                    </div>

                                    {{-- Range tabel --}}
                                    @if ($criteria->input_type === 'range' && $criteria->ranges->count() > 0)
                                        <div class="mt-2 rounded-xl border border-gray-100 overflow-hidden">
                                            <table class="w-full text-xs">
                                                <thead>
                                                    <tr class="bg-gray-50 text-gray-500 font-semibold">
                                                        <th class="px-4 py-2.5 text-left">Label</th>
                                                        <th class="px-4 py-2.5 text-center">Min</th>
                                                        <th class="px-4 py-2.5 text-center">Max</th>
                                                        <th class="px-4 py-2.5 text-center">Skor</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-gray-100">
                                                    @foreach ($criteria->ranges as $range)
                                                        <tr class="hover:bg-gray-50">
                                                            <td class="px-4 py-2.5 text-gray-700">{{ $range->label }}</td>
                                                            <td class="px-4 py-2.5 text-center text-gray-500">
                                                                {{ $range->min_value !== null ? number_format($range->min_value) : '—' }}
                                                            </td>
                                                            <td class="px-4 py-2.5 text-center text-gray-500">
                                                                {{ $range->max_value !== null ? number_format($range->max_value) : '—' }}
                                                            </td>
                                                            <td class="px-4 py-2.5 text-center">
                                                                <span
                                                                    class="inline-flex items-center justify-center w-7 h-7 rounded-lg font-bold
                                                                    {{ $range->score >= 4 ? 'bg-emerald-100 text-emerald-700' : ($range->score >= 3 ? 'bg-amber-100 text-amber-700' : 'bg-red-100 text-red-600') }}">
                                                                    {{ $range->score }}
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    @elseif ($criteria->input_type === 'number')
                                        <p class="text-xs text-gray-400 italic mt-1">
                                            <i class="fa-solid fa-circle-info mr-1"></i>
                                            Nilai diisi langsung oleh pelamar, dinormalisasi otomatis oleh SAW.
                                        </p>
                                    @else
                                        <p class="text-xs text-amber-500 italic mt-1">
                                            <i class="fa-solid fa-triangle-exclamation mr-1"></i>
                                            Belum ada rentang nilai yang didefinisikan.
                                        </p>
                                    @endif

                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-gray-400">
                                <i class="fa-solid fa-sliders text-3xl"></i>
                                <p class="text-sm font-medium">Belum ada kriteria yang ditentukan</p>
                                @if (in_array($scholarship->status, ['Draft', 'Aktif']))
                                    <a href="{{ route('penyalur.beasiswa.criteria', $scholarship->id) }}"
                                        class="text-indigo-600 hover:underline text-xs font-medium">
                                        + Tambah kriteria sekarang
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            {{-- Kanan: Sidebar Info --}}
            <div class="space-y-5">

                {{-- Ringkasan --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                        <h3 class="text-sm font-semibold text-gray-700">Ringkasan</h3>
                    </div>
                    <div class="px-5 py-4 space-y-3 text-sm">
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Status</span>
                            @php
                                $pill = match ($scholarship->status) {
                                    'Draft' => 'bg-gray-100 text-gray-600',
                                    'Aktif' => 'bg-emerald-100 text-emerald-700',
                                    'Seleksi' => 'bg-amber-100 text-amber-700',
                                    'Selesai' => 'bg-purple-100 text-purple-700',
                                    default => 'bg-gray-100 text-gray-600',
                                };
                            @endphp
                            <span class="px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $pill }}">
                                {{ $scholarship->status }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kategori</span>
                            <span class="font-medium text-gray-900">{{ $scholarship->category }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Kuota</span>
                            <span class="font-medium text-gray-900">{{ number_format($scholarship->quota) }} orang</span>
                        </div>
                        <div class="border-t border-gray-100 pt-3 flex justify-between">
                            <span class="text-gray-500">Dibuat</span>
                            <span class="text-gray-700">{{ $scholarship->created_at->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Diperbarui</span>
                            <span class="text-gray-700">{{ $scholarship->updated_at->translatedFormat('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Periode --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                        <h3 class="text-sm font-semibold text-gray-700">Periode Pendaftaran</h3>
                    </div>
                    <div class="px-5 py-4">
                        @php
                            $start = \Carbon\Carbon::parse($scholarship->start_date);
                            $end = \Carbon\Carbon::parse($scholarship->end_date);
                            $today = now();
                            $duration = $start->diffInDays($end);

                            if ($today->lt($start)) {
                                $progressPct = 0;
                                $progressLabel = 'Belum dimulai';
                                $progressColor = 'bg-gray-300';
                            } elseif ($today->gt($end)) {
                                $progressPct = 100;
                                $progressLabel = 'Sudah berakhir';
                                $progressColor = 'bg-gray-400';
                            } else {
                                $elapsed = $start->diffInDays($today);
                                $progressPct = $duration > 0 ? round(($elapsed / $duration) * 100) : 100;
                                $progressLabel = $end->diffForHumans() . ' lagi';
                                $progressColor = 'bg-indigo-500';
                            }
                        @endphp

                        <div class="flex items-center justify-between text-xs text-gray-500 mb-1.5">
                            <span>{{ $start->translatedFormat('d M Y') }}</span>
                            <span>{{ $end->translatedFormat('d M Y') }}</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden mb-2">
                            <div class="h-2 rounded-full {{ $progressColor }} transition-all"
                                style="width: {{ $progressPct }}%"></div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-gray-400">{{ $duration }} hari total</span>
                            <span class="text-xs font-medium text-gray-600">{{ $progressLabel }}</span>
                        </div>
                    </div>
                </div>

                {{-- Bobot Kriteria Visual --}}
                @if ($scholarship->criteria->count() > 0)
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50/60">
                            <h3 class="text-sm font-semibold text-gray-700">Distribusi Bobot</h3>
                        </div>
                        <div class="px-5 py-4 space-y-2.5">
                            @foreach ($scholarship->criteria as $criteria)
                                <div>
                                    <div class="flex justify-between text-xs mb-1">
                                        <span class="text-gray-600 truncate max-w-[70%]">{{ $criteria->name }}</span>
                                        <span class="font-semibold text-gray-900">{{ $criteria->weight }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                        <div class="h-1.5 rounded-full bg-indigo-400 transition-all"
                                            style="width: {{ $criteria->weight }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
