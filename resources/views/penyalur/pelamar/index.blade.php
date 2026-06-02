@extends('penyalur.layout.layout')

@section('title', 'Manajemen Pelamar')

@section('content')

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="font-display text-2xl font-bold text-slate-800">Manajemen Pelamar</h1>
        <p class="text-slate-500 text-sm mt-1">Pilih beasiswa untuk melihat dan mengelola pelamar.</p>
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

    {{-- Grid beasiswa --}}
    @if ($scholarships->isEmpty())
        <div class="flex flex-col items-center justify-center py-20 text-center">
            <div class="w-16 h-16 bg-teal-50 rounded-2xl flex items-center justify-center mb-4">
                <i class="fa-solid fa-users text-teal-400 text-2xl"></i>
            </div>
            <p class="font-display font-semibold text-slate-700">Belum Ada Beasiswa Aktif</p>
            <p class="text-slate-400 text-sm mt-1">Publikasikan beasiswa terlebih dahulu untuk mulai menerima pelamar.</p>
            <a href="{{ route('penyalur.beasiswa') }}"
                class="mt-4 inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2 rounded-xl transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Ke Halaman Beasiswa
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
            @foreach ($scholarships as $s)
                @php
                    $total = $s->applications_count;
                    $statusColor = match ($s->status) {
                        'active' => 'bg-emerald-100 text-emerald-700',
                        'completed' => 'bg-slate-100 text-slate-600',
                        default => 'bg-yellow-100 text-yellow-700',
                    };
                    $statusLabel = match ($s->status) {
                        'active' => 'Aktif',
                        'completed' => 'Selesai',
                        default => $s->status,
                    };
                @endphp
                <a href="{{ route('penyalur.pelamar.show', $s->id) }}"
                    class="group bg-white rounded-2xl border border-slate-200 p-5 hover:border-teal-300 hover:shadow-md hover:shadow-teal-50 transition-all duration-200 flex flex-col gap-4">

                    {{-- Header --}}
                    <div class="flex items-start justify-between gap-3">
                        <div class="w-10 h-10 bg-teal-50 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-graduation-cap text-teal-600"></i>
                        </div>
                        <span
                            class="text-[11px] font-semibold px-2.5 py-1 rounded-full {{ $statusColor }}">{{ $statusLabel }}</span>
                    </div>

                    {{-- Info --}}
                    <div>
                        <p
                            class="font-display font-semibold text-slate-800 group-hover:text-teal-700 transition-colors line-clamp-2 leading-snug">
                            {{ $s->name }}
                        </p>
                        <p class="text-slate-400 text-xs mt-1">
                            Kuota: <span class="font-medium text-slate-600">{{ $s->quota }} orang</span>
                        </p>
                    </div>

                    {{-- Stats --}}
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <div class="flex items-center gap-2 text-slate-500 text-sm">
                            <i class="fa-solid fa-users text-teal-500 text-xs"></i>
                            <span><span class="font-semibold text-slate-700">{{ $total }}</span> pelamar</span>
                        </div>
                        <span class="text-teal-600 text-xs font-medium group-hover:underline">Lihat Detail →</span>
                    </div>
                </a>
            @endforeach
        </div>
    @endif

@endsection
