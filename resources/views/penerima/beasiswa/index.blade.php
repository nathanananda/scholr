@extends('penerima.layout.layout')

@section('content')
    <section class="page">

        {{-- Search Bar --}}
        <div class="mb-4">
            <form method="GET" action="{{ route('penerima.beasiswa') }}" id="filter-form">
                <div class="relative">
                    <span
                        class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-[18px]">search</span>
                    <input type="text" name="q" value="{{ request('q') }}"
                        placeholder="Cari nama beasiswa atau penyalur..."
                        class="w-full pl-9 pr-4 py-2.5 text-sm bg-white border border-slate-200 rounded-xl focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500 transition-all" />
                </div>

                {{-- Filter Pills --}}
                <div class="flex flex-wrap gap-2 mt-3">
                    @php
                        $jenjangOptions = [
                            '' => 'Semua',
                            'SD' => 'SD',
                            'SMP' => 'SMP',
                            'SMA' => 'SMA',
                            'D3' => 'D3',
                            'S1' => 'S1',
                            'S2' => 'S2',
                            'S3' => 'S3',
                        ];
                        $activeJenjang = request('jenjang', '');
                    @endphp

                    @foreach ($jenjangOptions as $val => $label)
                        <button type="button" onclick="setFilter('jenjang', '{{ $val }}')"
                            class="filter-pill text-xs font-medium px-4 py-1.5 rounded-full border transition-all
                            {{ $activeJenjang === $val
                                ? 'bg-teal-900 text-white border-teal-900'
                                : 'bg-white text-slate-600 border-slate-200 hover:bg-teal-900 hover:text-white hover:border-teal-900' }}">
                            {{ $label }}
                        </button>
                    @endforeach

                    <input type="hidden" name="jenjang" id="input-jenjang" value="{{ $activeJenjang }}" />

                    {{-- Filter Sedang Dibuka --}}
                    <button type="button" onclick="toggleFilter('open')"
                        class="filter-pill text-xs font-medium px-4 py-1.5 rounded-full border transition-all
                        {{ request('open') ? 'bg-teal-900 text-white border-teal-900' : 'bg-white text-slate-600 border-slate-200 hover:bg-teal-900 hover:text-white hover:border-teal-900' }}">
                        Sedang Dibuka
                    </button>
                    <input type="hidden" name="open" id="input-open" value="{{ request('open', '') }}" />
                </div>
            </form>
        </div>

        {{-- Stats Summary --}}
        <div class="flex items-center justify-between mb-4">
            <p class="text-xs text-slate-400">
                Menampilkan <span class="font-semibold text-slate-700">{{ $scholarships->total() }}</span> beasiswa
            </p>
            @if ($activeJenjang || request('q') || request('open'))
                <a href="{{ route('penerima.beasiswa') }}" class="text-xs text-teal-700 font-medium hover:underline">Reset
                    filter</a>
            @endif
        </div>

        {{-- Empty State --}}
        @if ($scholarships->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <span class="material-symbols-outlined text-5xl text-slate-300 mb-3">search_off</span>
                <p class="text-sm font-semibold text-slate-600">Beasiswa tidak ditemukan</p>
                <p class="text-xs text-slate-400 mt-1">Coba ubah filter atau kata kunci pencarian</p>
            </div>

            {{-- Grid Beasiswa --}}
        @else
            <div class="grid grid-cols-2 gap-4">
                @foreach ($scholarships as $scholarship)
                    @php
                        $isOpen =
                            $scholarship->status === 'Aktif' &&
                            $scholarship->start_date <= now() &&
                            $scholarship->end_date >= now();



                        $isDeadlineSoon =
                            $scholarship->end_date &&
                            $scholarship->end_date->diffInDays(now()) <= 7 &&
                            $isOpen;

                        $hasApplied = $userApplications->contains('scholarship_id', $scholarship->id);

                        $badgeClass = match (true) {
                            $isDeadlineSoon => 'bg-red-100 text-red-700',
                            $isOpen => 'bg-green-100 text-green-800',
                            default => 'bg-slate-100 text-slate-500',
                        };
                        $badgeLabel = match (true) {
                            $isDeadlineSoon => 'Segera Tutup',
                            $isOpen => 'Dibuka',
                            default => 'Ditutup',
                        };

                        $benefitText = match ($scholarship->benefit_type) {
                            'full' => 'Full Coverage',
                            'partial' => 'Parsial',
                            default => $scholarship->benefit_amount
                                ? ($scholarship->benefit_currency === 'USD'
                                        ? 'USD ' . number_format($scholarship->benefit_amount, 0)
                                        : 'Rp ' . number_format($scholarship->benefit_amount / 1000000, 0) . 'jt') .
                                    match ($scholarship->benefit_period) {
                                        'monthly' => '/Bulan',
                                        'semester' => '/Semester',
                                        'yearly' => '/Tahun',
                                        default => '',
                                    }
                                : '-',
                        };
                    @endphp

                    <a href="{{ route('penerima.beasiswa.show', $scholarship->id) }}"
                        class="bg-white border border-slate-200 rounded-2xl overflow-hidden hover:-translate-y-0.5 hover:shadow-lg transition-all block">

                        {{-- Banner --}}
                        <div class="h-28 relative overflow-hidden bg-teal-900">
                            @if ($scholarship->banner_image)
                                <img src="{{ Storage::url($scholarship->banner_image) }}" alt="{{ $scholarship->name }}"
                                    class="w-full h-full object-cover" />
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-white/20 text-6xl">school</span>
                                </div>
                            @endif

                            {{-- Status Badge --}}
                            <span
                                class="absolute top-2.5 right-2.5 text-[10px] font-bold px-2.5 py-0.5 rounded-full {{ $badgeClass }}">
                                {{ $badgeLabel }}
                            </span>
                        </div>

                        {{-- Body --}}
                        <div class="p-4">
                            {{-- Penyalur --}}
                            <div class="flex items-center gap-2 mb-2.5">
                                <div
                                    class="w-7 h-7 rounded-md bg-slate-100 overflow-hidden flex items-center justify-center flex-shrink-0">
                                    @if ($scholarship->penyalur->penyalurProfile->logo)
                                        <img src="{{ Storage::url($scholarship->penyalur->penyalurProfile->logo) }}"
                                            class="w-full h-full object-cover" alt="" />
                                    @else
                                        <span class="font-bold text-teal-900 text-[9px]">
                                            {{ strtoupper(substr($scholarship->penyalur->name, 0, 2)) }}
                                        </span>
                                    @endif
                                </div>
                                <span class="text-slate-400 text-[11px] truncate">
                                    {{ $scholarship->penyalur->penyalurProfile->organization_name ?? $scholarship->penyalur->name }}
                                </span>
                            </div>

                            {{-- Nama Beasiswa --}}
                            <h3 class="font-display text-sm font-bold text-teal-900 mb-3 leading-snug line-clamp-2">
                                {{ $scholarship->name }}
                            </h3>

                            {{-- Dana & Deadline --}}
                            <div class="flex justify-between pt-2.5 border-t border-slate-100">
                                <div>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wide">Dana</p>
                                    <p class="text-xs font-bold text-teal-700">{{ $benefitText }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wide">Deadline</p>
                                    <p class="text-xs font-bold {{ $isDeadlineSoon ? 'text-red-500' : 'text-slate-700' }}">
                                        {{ $scholarship->end_date ? $scholarship->end_date->format('d M Y') : '-' }}
                                    </p>
                                </div>
                            </div>

                            {{-- CTA Button --}}
                            @if ($hasApplied)
                                <div
                                    class="w-full mt-3 py-2 bg-green-100 text-green-800 rounded-lg text-xs font-bold flex items-center justify-center gap-1.5">
                                    <span class="material-symbols-outlined text-[13px]">check</span>
                                    Sudah Mendaftar
                                </div>
                            @elseif ($isOpen)
                                <div
                                    class="w-full mt-3 py-2 bg-teal-900 text-white rounded-lg text-xs font-bold flex items-center justify-center gap-1.5">
                                    <span class="material-symbols-outlined text-[13px]">add</span>
                                    Daftar Sekarang
                                </div>
                            @else
                                <div
                                    class="w-full mt-3 py-2 bg-slate-100 text-slate-400 rounded-lg text-xs font-bold flex items-center justify-center gap-1.5">
                                    <span class="material-symbols-outlined text-[13px]">lock</span>
                                    Pendaftaran Ditutup
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            {{-- Pagination --}}
            @if ($scholarships->hasPages())
                <div class="mt-6">
                    {{ $scholarships->appends(request()->query())->links('penerima.partials.pagination') }}
                </div>
            @endif
        @endif

    </section>
@endsection

@push('scripts')
    <script>
        function setFilter(name, value) {
            document.getElementById('input-' + name).value = value;
            document.getElementById('filter-form').submit();
        }

        function toggleFilter(name) {
            const input = document.getElementById('input-' + name);
            input.value = input.value ? '' : '1';
            document.getElementById('filter-form').submit();
        }
    </script>
@endpush
