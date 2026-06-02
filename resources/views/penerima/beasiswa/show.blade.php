@extends('penerima.layout.layout')

@section('content')
    @php
        $isOpen =
            $scholarship->status === 'Aktif' && $scholarship->start_date <= now() && $scholarship->end_date >= now();

        $isDeadlineSoon = $scholarship->end_date && $scholarship->end_date->diffInDays(now()) <= 7 && $isOpen;

        $hasApplied = $userApplication !== null;
        $orgName = $scholarship->penyalur->penyalurProfile->organization_name ?? $scholarship->penyalur->name;

        $benefitText = match (true) {
            $scholarship->benefit_type === 'full' => 'Full Coverage',
            $scholarship->benefit_amount > 0 && $scholarship->benefit_currency === 'USD' => 'USD ' .
                number_format($scholarship->benefit_amount, 0),
            $scholarship->benefit_amount > 0 => 'Rp ' .
                number_format($scholarship->benefit_amount / 1_000_000, 1) .
                'jt' .
                match ($scholarship->benefit_period) {
                    'monthly' => '/bln',
                    'semester' => '/smt',
                    'yearly' => '/thn',
                    default => '',
                },
            default => '—',
        };

        $daysLeft = $scholarship->end_date ? (int) now()->diffInDays($scholarship->end_date, false) : null;
    @endphp

    {{-- ─── Page wrapper with bottom CTA padding ─── --}}
    <div class="pb-24 -mx-4 -mt-2">

        {{-- ══════════ HERO ══════════ --}}
        <div class="relative">

            {{-- Banner image / fallback --}}
            <div class="h-52 bg-[#0D3D30] overflow-hidden">
                @if ($scholarship->banner_image)
                    <img src="{{ Storage::url($scholarship->banner_image) }}" alt=""
                        class="w-full h-full object-cover opacity-60" />
                @endif
            </div>

            {{-- Overlay: back button + status pill --}}
            <div class="absolute top-0 left-0 right-0 flex items-center justify-between px-4 pt-4">
                <a href="{{ route('penerima.beasiswa') }}"
                    class="w-8 h-8 rounded-full bg-black/25 backdrop-blur-sm flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-[18px]">arrow_back</span>
                </a>

                @if ($isDeadlineSoon)
                    <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-red-500 text-white">
                        Tutup {{ $daysLeft }}h lagi
                    </span>
                @elseif ($isOpen)
                    <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-[#0D3D30] text-[#5DCAA5]">
                        Pendaftaran Dibuka
                    </span>
                @else
                    <span class="text-[11px] font-bold px-3 py-1 rounded-full bg-black/25 text-white/70">
                        Ditutup
                    </span>
                @endif
            </div>

            {{-- Floating card identity —  pulls up over the banner --}}
            <div class="mx-4 -mt-8 relative z-10 bg-white rounded-2xl border border-slate-100 p-4 flex items-center gap-3">
                {{-- Logo --}}
                <div
                    class="w-12 h-12 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center flex-shrink-0">
                    @if ($scholarship->penyalur->penyalurProfile->logo ?? false)
                        <img src="{{ Storage::url($scholarship->penyalur->penyalurProfile->logo) }}"
                            class="w-full h-full object-cover" alt="" />
                    @else
                        <span class="text-xs font-bold text-[#0D3D30]">
                            {{ strtoupper(substr($orgName, 0, 2)) }}
                        </span>
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <p class="text-[11px] text-slate-400 truncate">{{ $orgName }}</p>
                    <h1 class="text-[15px] font-bold text-[#0D3D30] leading-snug mt-0.5">
                        {{ $scholarship->name }}
                    </h1>
                </div>
            </div>
        </div>

        {{-- ══════════ KEY STATS ══════════ --}}
        <div
            class="grid grid-cols-3 gap-0 mx-4 mt-4 rounded-2xl border border-slate-100 overflow-hidden bg-white divide-x divide-slate-100">
            <div class="px-3 py-3 text-center">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-medium mb-1">Dana</p>
                <p class="text-[13px] font-bold text-[#0D3D30] leading-tight">{{ $benefitText }}</p>
            </div>
            <div class="px-3 py-3 text-center">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-medium mb-1">Kuota</p>
                <p class="text-[13px] font-bold text-slate-800 leading-tight">{{ $scholarship->quota ?? '∞' }}</p>
            </div>
            <div class="px-3 py-3 text-center">
                <p class="text-[10px] text-slate-400 uppercase tracking-widest font-medium mb-1">Deadline</p>
                <p class="text-[13px] font-bold {{ $isDeadlineSoon ? 'text-red-500' : 'text-slate-800' }} leading-tight">
                    {{ $scholarship->end_date?->format('d M') ?? '—' }}
                </p>
            </div>
        </div>

        {{-- ══════════ TAGS ══════════ --}}
        <div class="flex flex-wrap gap-2 px-4 mt-4">
            @foreach ((array) $scholarship->education_level as $level)
                <span
                    class="text-[11px] font-semibold px-3 py-1 rounded-full
                         bg-[#E1F5EE] text-[#0F6E56]">{{ $level }}</span>
            @endforeach
            @if ($scholarship->category)
                <span
                    class="text-[11px] font-semibold px-3 py-1 rounded-full
                         bg-slate-100 text-slate-500 capitalize">{{ $scholarship->category }}</span>
            @endif
            @if ($scholarship->start_date)
                <span class="text-[11px] font-medium px-3 py-1 rounded-full bg-slate-50 text-slate-400">
                    Dibuka {{ $scholarship->start_date->format('d M Y') }}
                </span>
            @endif
        </div>

        {{-- ══════════ TABS ══════════ --}}
        <div class="mt-5" x-data="{ tab: 'info' }">

            {{-- Tab bar --}}
            <div class="flex border-b border-slate-100 px-4 overflow-x-auto scrollbar-hide gap-1">
                @foreach (['info' => 'Informasi', 'kriteria' => 'Kriteria', 'dokumen' => 'Dokumen', 'faq' => 'FAQ'] as $key => $label)
                    <button @click="tab = '{{ $key }}'"
                        :class="tab === '{{ $key }}'
                            ?
                            'text-[#0D3D30] border-b-2 border-[#0D3D30] font-semibold' :
                            'text-slate-400'"
                        class="text-[13px] px-4 py-2.5 whitespace-nowrap transition-all flex-shrink-0">
                        {{ $label }}
                    </button>
                @endforeach
            </div>

            {{-- ── Tab: Informasi ── --}}
            <div x-show="tab === 'info'" class="px-4 pt-5">
                @if ($scholarship->description)
                    <div class="text-[13px] text-slate-500 leading-relaxed">
                        {!! nl2br(e($scholarship->description)) !!}
                    </div>
                @else
                    <p class="text-[13px] text-slate-400 italic">Deskripsi belum tersedia.</p>
                @endif

                @if ($scholarship->announcement_date)
                    <div class="mt-5 flex items-center gap-3 p-3.5 rounded-xl bg-amber-50 border border-amber-100">
                        <span class="material-symbols-outlined text-amber-500 text-[20px] flex-shrink-0">event</span>
                        <div>
                            <p class="text-[10px] text-amber-600 font-semibold uppercase tracking-wide">Tanggal Pengumuman
                            </p>
                            <p class="text-sm font-bold text-amber-900 mt-0.5">
                                {{ $scholarship->announcement_date->format('d F Y') }}
                            </p>
                        </div>
                    </div>
                @endif
            </div>

            {{-- ── Tab: Kriteria SAW ── --}}
            <div x-show="tab === 'kriteria'" class="px-4 pt-5 space-y-3">
                @forelse ($scholarship->criteria as $criterion)
                    <div class="rounded-xl border border-slate-100 overflow-hidden">
                        {{-- Header row --}}
                        <div class="flex items-center justify-between px-4 py-3 bg-slate-50">
                            <p class="text-[13px] font-semibold text-slate-800">{{ $criterion->name }}</p>
                            <div class="flex items-center gap-2">
                                <span
                                    class="text-[10px] font-bold px-2 py-0.5 rounded-full
                                {{ $criterion->attribute_type === 'benefit' ? 'bg-[#E1F5EE] text-[#0F6E56]' : 'bg-orange-50 text-orange-600' }}">
                                    {{ $criterion->attribute_type === 'benefit' ? 'BENEFIT' : 'COST' }}
                                </span>
                                <span class="text-[11px] font-bold text-[#0D3D30]">
                                    {{ $criterion->weight * 100 }}%
                                </span>
                            </div>
                        </div>

                        {{-- Range options --}}
                        @if ($criterion->input_type === 'range' && $criterion->ranges->count())
                            <div class="divide-y divide-slate-50">
                                @foreach ($criterion->ranges->sortByDesc('score') as $range)
                                    <div class="flex items-center justify-between px-4 py-2.5">
                                        <span class="text-[12px] text-slate-600">{{ $range->label }}</span>
                                        <span class="text-[11px] font-bold text-[#0D3D30]">{{ $range->score }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="px-4 py-2.5">
                                <span class="text-[11px] text-slate-400">Input angka langsung</span>
                            </div>
                        @endif
                    </div>
                @empty
                    <p class="text-[13px] text-slate-400 italic">Kriteria belum dikonfigurasi.</p>
                @endforelse
            </div>

            {{-- ── Tab: Dokumen ── --}}
            <div x-show="tab === 'dokumen'" class="px-4 pt-5 space-y-2.5">
                @forelse ($scholarship->documents as $doc)
                    <div class="flex items-start gap-3 rounded-xl border border-slate-100 p-3.5">
                        <div class="w-8 h-8 rounded-lg bg-[#E1F5EE] flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-[#0F6E56] text-[16px]">description</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <p class="text-[13px] font-semibold text-slate-800">{{ $doc->name }}</p>
                                <span
                                    class="text-[9px] font-bold px-1.5 py-0.5 rounded-full
                                {{ $doc->is_required ? 'bg-red-50 text-red-500' : 'bg-slate-100 text-slate-400' }}">
                                    {{ $doc->is_required ? 'WAJIB' : 'OPSIONAL' }}
                                </span>
                            </div>
                            @if ($doc->description)
                                <p class="text-[11px] text-slate-400 mt-0.5">{{ $doc->description }}</p>
                            @endif
                            <p class="text-[10px] text-slate-300 mt-1">
                                {{ strtoupper(implode(' · ', $doc->allowed_formats ?? ['PDF'])) }}
                                &nbsp;·&nbsp; maks {{ number_format(($doc->max_size_kb ?? 2048) / 1024, 0) }} MB
                            </p>
                        </div>
                    </div>
                @empty
                    <p class="text-[13px] text-slate-400 italic">Tidak ada dokumen persyaratan.</p>
                @endforelse
            </div>

            {{-- ── Tab: FAQ ── --}}
            <div x-show="tab === 'faq'" class="px-4 pt-5" x-data="{ open: null }">
                @forelse ($scholarship->faqs as $i => $faq)
                    <div class="border-b border-slate-100 last:border-0">
                        <button @click="open = open === {{ $i }} ? null : {{ $i }}"
                            class="w-full flex items-center justify-between py-3.5 text-left gap-3">
                            <span class="text-[13px] font-medium text-slate-700">{{ $faq->question }}</span>
                            <span
                                class="material-symbols-outlined text-slate-300 text-[18px] flex-shrink-0 transition-transform duration-200"
                                :class="open === {{ $i }} ? 'rotate-180' : ''">
                                expand_more
                            </span>
                        </button>
                        <div x-show="open === {{ $i }}"
                            x-transition:enter="transition-all duration-200 ease-out"
                            x-transition:enter-start="opacity-0 -translate-y-1"
                            x-transition:enter-end="opacity-100 translate-y-0" class="pb-3.5 -mt-1">
                            <p class="text-[12px] text-slate-400 leading-relaxed">{{ $faq->answer }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-[13px] text-slate-400 italic">Belum ada FAQ.</p>
                @endforelse
            </div>

        </div>
    </div>

    {{-- ══════════ STICKY CTA ══════════ --}}
    <div class="sticky bottom-0 bg-white/90 backdrop-blur-sm border-t border-slate-100 p-4 mt-6"
        style="padding-bottom: calc(0.75rem + env(safe-area-inset-bottom))">

        @if ($hasApplied)
            <a href="{{ route('penerima.persyaratan.index') }}"
                class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl
                  bg-teal-800 text-[#E1F5EE] text-[13px] font-bold transition-all active:scale-[0.98]">
                <span class="material-symbols-outlined text-[15px]">check_circle</span>
                Lihat Status Lamaran
            </a>
        @elseif ($isOpen)
            <a href="{{ route('penerima.beasiswa.apply', $scholarship->id) }}"
                class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl
                  bg-teal-800 text-white text-[13px] font-bold transition-all
                  hover:bg-teal-700 active:scale-[0.98]">
                <span class="material-symbols-outlined text-[15px]">school</span>
                Daftar Beasiswa Ini
                @if ($daysLeft !== null && $daysLeft >= 0)
                    <span class="ml-1 text-[#5DCAA5] font-normal">· {{ $daysLeft }}h lagi</span>
                @endif
            </a>
        @else
            <div
                class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl
                    bg-slate-100 text-slate-400 text-[13px] font-bold cursor-not-allowed">
                <span class="material-symbols-outlined text-[15px]">lock</span>
                Pendaftaran Ditutup
            </div>
        @endif

    </div>
@endsection
