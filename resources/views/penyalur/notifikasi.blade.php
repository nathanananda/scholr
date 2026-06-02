@extends('penyalur.layout.layout')

@section('title', 'Notifikasi')

@section('content')

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="font-display font-bold text-slate-800 text-xl">Notifikasi</h1>
            <p class="text-slate-500 text-sm mt-0.5">Semua aktivitas dan pemberitahuan akun Anda</p>
        </div>
        <form action="{{ route('penyalur.notifikasi.markAllRead') }}" method="POST">
            @csrf
            <button type="submit"
                class="text-sm font-semibold text-teal-700 bg-teal-50 border border-teal-200
                   px-4 py-2 rounded-xl hover:bg-teal-100 transition-all">
                Tandai semua dibaca
            </button>
        </form>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-slate-50 rounded-xl p-4">
            <p class="text-xs text-slate-500 mb-1">Total notifikasi</p>
            <p class="text-2xl font-bold text-slate-700">{{ $notifications->total() }}</p>
        </div>
        <div class="bg-slate-50 rounded-xl p-4">
            <p class="text-xs text-slate-500 mb-1">Belum dibaca</p>
            <p class="text-2xl font-bold text-teal-600">{{ $unreadCount }}</p>
        </div>
        <div class="bg-slate-50 rounded-xl p-4">
            <p class="text-xs text-slate-500 mb-1">Hari ini</p>
            <p class="text-2xl font-bold text-slate-700">{{ $todayCount }}</p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="flex items-center gap-2 mb-4 flex-wrap">
        @foreach (['all' => 'Semua', 'pelamar' => 'Pelamar', 'dokumen' => 'Dokumen', 'seleksi' => 'Seleksi', 'beasiswa' => 'Beasiswa'] as $val => $label)
            <a href="{{ request()->fullUrlWithQuery(['type' => $val, 'page' => 1]) }}"
                class="text-xs font-medium px-3 py-1.5 rounded-lg border transition-all
                   {{ request('type', 'all') === $val
                       ? 'bg-teal-50 text-teal-700 border-teal-200 font-semibold'
                       : 'bg-white text-slate-500 border-slate-200 hover:bg-slate-50' }}">
                {{ $label }}
            </a>
        @endforeach

        <form class="ml-auto" method="GET">
            <input type="hidden" name="type" value="{{ request('type', 'all') }}">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari notifikasi..."
                class="text-xs border border-slate-200 rounded-lg px-3 py-1.5 pl-8 w-48
                   focus:outline-none focus:border-teal-400 bg-white" />
        </form>
    </div>

    {{-- Notification List --}}
    <div class="space-y-2">
        @forelse ($notifications as $notif)
            @php
                $isUnread = is_null($notif->read_at);
                $typeConfig = [
                    'pelamar' => ['bg-teal-50 text-teal-700', 'fa-solid fa-user', 'bg-teal-50 text-teal-700'],
                    'dokumen' => ['bg-blue-50 text-blue-700', 'fa-solid fa-file-lines', 'bg-blue-50 text-blue-700'],
                    'seleksi' => ['bg-green-50 text-green-700', 'fa-solid fa-trophy', 'bg-green-50 text-green-700'],
                    'beasiswa' => [
                        'bg-amber-50 text-amber-700',
                        'fa-solid fa-graduation-cap',
                        'bg-amber-50 text-amber-700',
                    ],
                    'sistem' => ['bg-slate-100 text-slate-600', 'fa-solid fa-bell', 'bg-slate-100 text-slate-600'],
                ];
                $cfg = $typeConfig[$notif->type] ?? $typeConfig['sistem'];
            @endphp

            <div
                class="bg-white rounded-xl border border-slate-200 flex items-start gap-4 px-4 py-3.5
                    hover:bg-slate-50 transition-all group relative
                    {{ $isUnread ? 'border-l-[3px] border-l-teal-500 bg-teal-50/30' : '' }}">

                {{-- Icon --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 {{ $cfg[0] }}">
                    <i class="{{ $cfg[1] }} text-[15px]"></i>
                </div>

                {{-- Body --}}
                <div class="flex-1 min-w-0">
                    <p
                        class="text-[13px] font-semibold mb-0.5
                          {{ $isUnread ? 'text-teal-700' : 'text-slate-800' }}">
                        {{ $notif->title }}
                    </p>
                    <p class="text-[12px] text-slate-500 leading-relaxed mb-1.5">
                        {{ $notif->body }}
                    </p>
                    <div class="flex items-center gap-2">
                        <span class="text-[11px] text-slate-400">
                            {{ $notif->created_at->diffForHumans() }}
                        </span>
                        <span class="text-[10px] font-semibold px-2 py-0.5 rounded-md {{ $cfg[2] }}">
                            {{ $notif->type }}
                        </span>
                    </div>
                </div>

                {{-- Right --}}
                <div class="flex flex-col items-end gap-2 flex-shrink-0">
                    @if ($isUnread)
                        <span class="w-2 h-2 bg-teal-500 rounded-full mt-1"></span>
                    @endif
                    <form action="{{ route('notifikasi.dismiss', $notif->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-7 h-7 rounded-lg flex items-center justify-center text-slate-300
                               hover:bg-slate-100 hover:text-slate-500 transition-all
                               opacity-0 group-hover:opacity-100">
                            <i class="fa-solid fa-xmark text-[12px]"></i>
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div
                class="bg-white rounded-xl border border-slate-200 flex flex-col items-center
                    justify-center py-16 gap-3 text-slate-400">
                <i class="fa-regular fa-bell-slash text-4xl opacity-40"></i>
                <p class="text-sm">Tidak ada notifikasi</p>
            </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $notifications->withQueryString()->links() }}
    </div>

@endsection
