@extends('penerima.layout.layout')

@section('title', 'Dashboard — Scholr')

@section('content')

@php
    $nama       = Auth::user()->name;
    $profil     = Auth::user()->penerimaProfile;
    $profilPct  = $profilPct ?? 0;
@endphp

{{-- ============================================================ --}}
{{-- WELCOME CARD                                                  --}}
{{-- ============================================================ --}}
<div class="relative bg-teal-900 rounded-2xl p-6 mb-5 overflow-hidden">
    {{-- Decorative circles --}}
    <div class="absolute -right-6 -top-6 w-36 h-36 rounded-full bg-white/5"></div>
    <div class="absolute right-14 -bottom-8 w-24 h-24 rounded-full bg-white/5"></div>
    <div class="absolute -left-4 bottom-0 w-20 h-20 rounded-full bg-white/5"></div>

    <div class="relative z-10">
        <p class="text-white/50 text-xs mb-1">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
        <h2 class="font-display text-xl font-bold text-white mb-1">
            Selamat datang, {{ Str::words($nama, 2, '') }}! 👋
        </h2>
        <p class="text-white/60 text-sm mb-4 max-w-sm">
            @if($profilPct < 100)
                Lengkapi profilmu untuk meningkatkan peluang mendapat beasiswa yang sesuai.
            @else
                Profil kamu sudah lengkap. Yuk mulai lamar beasiswa yang tersedia!
            @endif
        </p>

        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('penerima.beasiswa') }}"
               class="inline-flex items-center gap-1.5 bg-teal-400 hover:bg-teal-300 text-teal-900 px-4 py-2 rounded-lg text-xs font-bold transition-colors">
                <i class="fa-solid fa-magnifying-glass text-[11px]"></i> Jelajahi Beasiswa
            </a>
            @if($profilPct < 100)
                <a href="{{ route('penerima.profile') }}"
                   class="inline-flex items-center gap-1.5 bg-white/10 hover:bg-white/20 text-white px-4 py-2 rounded-lg text-xs font-semibold transition-colors">
                    <i class="fa-regular fa-user text-[11px]"></i> Lengkapi Profil
                </a>
            @endif
        </div>

        {{-- Progress bar profil --}}
        <div class="flex items-center gap-3 mt-4">
            <div class="flex-1 bg-white/20 rounded-full h-1.5 overflow-hidden">
                <div class="bg-teal-400 h-full rounded-full transition-all duration-700"
                     style="width: {{ $profilPct }}%"></div>
            </div>
            <span class="text-white/70 text-xs whitespace-nowrap">{{ $profilPct }}% profil lengkap</span>
        </div>
    </div>
</div>

{{-- ============================================================ --}}
{{-- ALERT DOKUMEN DITOLAK                                        --}}
{{-- ============================================================ --}}
@if(isset($docDitolak) && $docDitolak->count() > 0)
    <div class="mb-5 flex gap-3 items-start bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <i class="fa-solid fa-circle-exclamation text-red-500 mt-0.5 flex-shrink-0 text-sm"></i>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-red-700">{{ $docDitolak->count() }} dokumen ditolak — perlu di-upload ulang</p>
            <p class="text-xs text-red-500 mt-0.5 truncate">
                {{ $docDitolak->first()->scholarshipDocument->name ?? '' }}
                pada {{ $docDitolak->first()->application->scholarship->name ?? '' }}
                @if($docDitolak->count() > 1) dan {{ $docDitolak->count() - 1 }} lainnya @endif
            </p>
        </div>
        <a href="{{ route('penerima.lamaran.index') }}" class="flex-shrink-0 text-xs font-semibold text-red-600 hover:text-red-700 transition-colors whitespace-nowrap">
            Tinjau →
        </a>
    </div>
@endif

{{-- ============================================================ --}}
{{-- STATS CARDS                                                   --}}
{{-- ============================================================ --}}
<div class="grid grid-cols-3 gap-3 mb-5">

    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-1.5 text-slate-400 text-[11px] font-semibold uppercase tracking-wide mb-2.5">
            <i class="fa-regular fa-file-lines text-[13px]"></i> Lamaran Aktif
        </div>
        <p class="font-display text-3xl font-bold text-teal-900">{{ $stats['aktif'] }}</p>
        <p class="text-slate-400 text-[11px] mt-1.5">
            @if($stats['diproses'] > 0)
                {{ $stats['diproses'] }} sedang diproses
            @else
                belum ada yang diproses
            @endif
        </p>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-1.5 text-slate-400 text-[11px] font-semibold uppercase tracking-wide mb-2.5">
            <i class="fa-solid fa-trophy text-[13px]"></i> Diterima
        </div>
        <p class="font-display text-3xl font-bold text-teal-900">{{ $stats['diterima'] }}</p>
        <p class="text-slate-400 text-[11px] mt-1.5">
            @if($stats['diterima'] > 0)
                {{ $stats['nama_diterima'] }}
            @else
                belum ada yang diterima
            @endif
        </p>
    </div>

    <div class="bg-white border border-slate-200 rounded-xl p-4">
        <div class="flex items-center gap-1.5 text-slate-400 text-[11px] font-semibold uppercase tracking-wide mb-2.5">
            <i class="fa-solid fa-star text-[13px]"></i> Rekomendasi
        </div>
        <p class="font-display text-3xl font-bold text-teal-900">{{ $stats['rekomendasi'] }}</p>
        <p class="text-slate-400 text-[11px] mt-1.5">cocok dengan profilmu</p>
    </div>

</div>

{{-- ============================================================ --}}
{{-- MAIN CONTENT                                                  --}}
{{-- ============================================================ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

    {{-- Lamaran terbaru (col 2) --}}
    <div class="lg:col-span-2 flex flex-col gap-4">

        {{-- Lamaran terakhir --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-display font-bold text-sm text-teal-900">Lamaran Terakhir</h3>
                <a href="{{ route('penerima.lamaran.index') }}" class="text-xs text-teal-500 hover:text-teal-600 transition-colors font-semibold">
                    Lihat semua →
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($lamaranTerbaru as $app)
                    @php
                        $statusConfig = match($app->status) {
                            'accepted'     => ['bg-green-100 text-green-800',  'bg-green-600',  'Diterima'],
                            'rejected'     => ['bg-red-100 text-red-700',      'bg-red-500',    'Ditolak'],
                            'submitted'    => ['bg-amber-100 text-amber-800',  'bg-amber-500',  'Seleksi Dokumen'],
                            'under_review' => ['bg-purple-100 text-purple-800','bg-purple-500', 'Seleksi SAW'],
                            'draft'        => ['bg-slate-100 text-slate-600',  'bg-slate-400',  'Draft'],
                            default        => ['bg-slate-100 text-slate-600',  'bg-slate-400',  ucfirst($app->status)],
                        };
                        $initials = strtoupper(substr($app->scholarship->name, 0, 2));
                    @endphp
                    <a href="{{ route('penerima.lamaran.show', $app->id) }}"
                       class="flex items-center gap-3.5 px-5 py-3.5 hover:bg-slate-50 transition-colors">
                        <div class="w-10 h-10 rounded-xl bg-teal-50 flex items-center justify-center font-display font-bold text-teal-700 text-[11px] flex-shrink-0">
                            {{ $initials }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm text-teal-900 truncate">{{ $app->scholarship->name }}</p>
                            <p class="text-slate-400 text-xs mt-0.5">{{ $app->scholarship->penyalur->name ?? '-' }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <span class="inline-flex items-center gap-1 {{ $statusConfig[0] }} text-[11px] font-semibold px-2.5 py-1 rounded-full">
                                <span class="w-1.5 h-1.5 rounded-full {{ $statusConfig[1] }} inline-block"></span>
                                {{ $statusConfig[2] }}
                            </span>
                            <p class="text-xs text-slate-400 mt-1">
                                {{ \Carbon\Carbon::parse($app->updated_at)->locale('id')->diffForHumans() }}
                            </p>
                        </div>
                    </a>
                @empty
                    <div class="px-5 py-8 text-center">
                        <i class="fa-regular fa-folder-open text-slate-300 text-2xl mb-2 block"></i>
                        <p class="text-sm text-slate-400 mb-2">Belum ada lamaran</p>
                        <a href="{{ route('penerima.beasiswa') }}"
                           class="text-xs text-teal-500 hover:text-teal-600 font-semibold transition-colors">
                            Mulai lamar beasiswa →
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Deadline mendekat --}}
        @if($deadlineMendekat->count() > 0)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100">
                    <h3 class="font-display font-bold text-sm text-teal-900 flex items-center gap-2">
                        <i class="fa-solid fa-clock text-amber-500 text-sm"></i>
                        Deadline Mendekat
                    </h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($deadlineMendekat as $app)
                        @php
                            $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($app->scholarship->close_date), false);
                        @endphp
                        <a href="{{ route('penerima.lamaran.show', $app->id) }}"
                           class="flex items-center gap-3.5 px-5 py-3.5 hover:bg-slate-50 transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center flex-shrink-0">
                                <i class="fa-regular fa-calendar-xmark text-amber-600 text-base"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-sm text-teal-900 truncate">{{ $app->scholarship->name }}</p>
                                <p class="text-slate-400 text-xs mt-0.5">
                                    Tutup {{ \Carbon\Carbon::parse($app->scholarship->close_date)->locale('id')->isoFormat('D MMM YYYY') }}
                                </p>
                            </div>
                            <div class="flex-shrink-0">
                                @if($daysLeft <= 3)
                                    <span class="text-[11px] font-bold bg-red-100 text-red-600 px-2.5 py-1 rounded-full">{{ $daysLeft }}h lagi</span>
                                @else
                                    <span class="text-[11px] font-bold bg-amber-100 text-amber-700 px-2.5 py-1 rounded-full">{{ $daysLeft }}h lagi</span>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    {{-- Sidebar kanan --}}
    <div class="flex flex-col gap-4">

        {{-- Kelengkapan profil --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5">
            <h3 class="font-display font-bold text-sm text-teal-900 mb-4">Kelengkapan Profil</h3>
            <div class="space-y-3">
                @foreach($profilItems as $item)
                    <div class="flex items-center gap-2.5">
                        @if($item['done'])
                            <div class="w-5 h-5 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-check text-teal-600 text-[10px]"></i>
                            </div>
                        @else
                            <div class="w-5 h-5 rounded-full bg-slate-100 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-minus text-slate-400 text-[10px]"></i>
                            </div>
                        @endif
                        <span class="text-xs {{ $item['done'] ? 'text-slate-600' : 'text-slate-400' }} flex-1">
                            {{ $item['label'] }}
                        </span>
                        @if(!$item['done'])
                            <span class="text-[10px] font-semibold text-amber-600 bg-amber-50 px-1.5 py-0.5 rounded">Kosong</span>
                        @endif
                    </div>
                @endforeach
            </div>
            @if($profilPct < 100)
                <a href="{{ route('penerima.profile') }}"
                   class="mt-4 w-full flex items-center justify-center gap-1.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-semibold py-2.5 rounded-xl transition-colors">
                    <i class="fa-solid fa-pen text-[10px]"></i> Lengkapi Sekarang
                </a>
            @endif
        </div>

        {{-- Rekomendasi beasiswa --}}
        <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
            <div class="px-5 py-3.5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-display font-bold text-sm text-teal-900">Rekomendasi</h3>
                <a href="{{ route('penerima.beasiswa') }}" class="text-xs text-teal-500 hover:text-teal-600 font-semibold transition-colors">
                    Semua →
                </a>
            </div>
            <div class="divide-y divide-slate-100">
                @forelse($rekomendasiBeasiswa as $bs)
                    <a href="{{ route('penerima.beasiswa.show', $bs->id) }}"
                       class="flex items-start gap-3 px-5 py-3.5 hover:bg-slate-50 transition-colors">
                        <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center font-bold text-teal-700 text-[10px] flex-shrink-0 mt-0.5">
                            {{ strtoupper(substr($bs->name, 0, 2)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-semibold text-teal-900 truncate leading-snug">{{ Str::limit($bs->name, 28) }}</p>
                            <p class="text-[11px] text-slate-400 mt-0.5 truncate">{{ $bs->penyalur->name ?? '-' }}</p>
                            <p class="text-[11px] font-semibold text-teal-600 mt-0.5">
                                @if($bs->benefit_amount)
                                    @if($bs->benefit_currency === 'IDR')
                                        Rp {{ number_format($bs->benefit_amount / 1000, 0, ',', '.') }}rb
                                    @else
                                        USD {{ number_format($bs->benefit_amount, 0) }}
                                    @endif
                                    / {{ $bs->benefit_period }}
                                @else
                                    Full Coverage
                                @endif
                            </p>
                        </div>
                        <i class="fa-solid fa-chevron-right text-slate-300 text-[10px] mt-1 flex-shrink-0"></i>
                    </a>
                @empty
                    <div class="px-5 py-6 text-center">
                        <p class="text-xs text-slate-400">Lengkapi profilmu agar kami bisa merekomendasikan beasiswa yang tepat.</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Notifikasi terbaru --}}
        @if(isset($notifikasiTerbaru) && $notifikasiTerbaru->count() > 0)
            <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
                <div class="px-5 py-3.5 border-b border-slate-100">
                    <h3 class="font-display font-bold text-sm text-teal-900">Notifikasi</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($notifikasiTerbaru as $notif)
                        <div class="flex items-start gap-3 px-5 py-3 {{ is_null($notif->read_at) ? 'bg-teal-50/40' : '' }}">
                            <div class="w-6 h-6 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <i class="fa-solid fa-bell text-teal-600 text-[10px]"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs text-slate-700 leading-snug">{{ $notif->message }}</p>
                                <p class="text-[10px] text-slate-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($notif->created_at)->diffForHumans() }}
                                </p>
                            </div>
                            @if(is_null($notif->read_at))
                                <span class="w-1.5 h-1.5 rounded-full bg-teal-500 flex-shrink-0 mt-1.5"></span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

</div>

@endsection
