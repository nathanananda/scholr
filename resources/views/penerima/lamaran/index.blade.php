@extends('penerima.layout.layout')

@section('content')
    <section>

        {{-- Page Title --}}
        <div class="mb-5">
            <h1 class="font-display font-bold text-teal-900 text-lg">Lamaran Saya</h1>
            <p class="text-slate-400 text-xs mt-0.5">Pantau progres dan transparansi seleksi beasiswa Anda</p>
        </div>

        {{-- Application List --}}
        <div class="flex flex-col gap-2.5 mb-6">
            @forelse ($applications as $app)
                @php
                    $statusConfig = [
                        'draft' => ['border-cyan-400', 'bg-cyan-50 text-cyan-800', 'Draft'],
                        'submitted' => ['border-blue-400', 'bg-blue-50 text-blue-800', 'Tersubmit'],
                        'under_review' => ['border-amber-400', 'bg-amber-100 text-amber-800', 'Seleksi Dokumen'],
                        'accepted' => ['border-green-500', 'bg-green-100 text-green-800', 'Diterima'],
                        'rejected' => ['border-red-400', 'bg-red-100 text-red-800', 'Tidak Lolos'],
                    ];
                    $cfg = $statusConfig[$app->status] ?? $statusConfig['draft'];
                @endphp
                <a href="{{ route('penerima.lamaran.show', $app->id) }}"
                    class="bg-white border-l-4 {{ $cfg[0] }} border border-slate-200 rounded-xl
                       px-4 py-3.5 flex items-center gap-3.5 hover:shadow-md transition-shadow">
                    <div
                        class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center
                            font-display font-bold text-teal-900 text-[11px] shrink-0">
                        {{ strtoupper(substr($app->scholarship->name, 0, 4)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm text-teal-900 truncate">{{ $app->scholarship->name }}</p>
                        <p class="text-slate-400 text-xs mt-0.5">{{ $app->scholarship->penyalur->name }}</p>
                    </div>
                    <span class="text-[11px] font-bold px-2.5 py-1 rounded-full shrink-0 {{ $cfg[1] }}">
                        {{ $cfg[2] }}
                    </span>
                </a>
            @empty
                <div class="bg-white border border-slate-200 rounded-xl px-6 py-12 text-center">
                    <p class="text-slate-400 text-sm">Belum ada lamaran. Cari beasiswa yang sesuai profilmu!</p>
                    <a href="{{ route('penerima.beasiswa') }}"
                        class="inline-block mt-3 text-sm font-semibold text-teal-700 bg-teal-50
                           px-4 py-2 rounded-xl hover:bg-teal-100 transition-all">
                        Jelajahi Beasiswa
                    </a>
                </div>
            @endforelse
        </div>

    </section>
@endsection
