@extends('admin.layout.layout')

@section('title', 'Verifikasi Penyalur')

@section('content')

    {{-- Page Header --}}
    <div class="mb-6">
        <h1 class="font-display text-2xl font-bold text-slate-800">Verifikasi Penyalur</h1>
        <p class="text-slate-500 text-sm mt-1">Review dan verifikasi akun penyalur yang mendaftar ke platform.</p>
    </div>

    {{-- Session alerts --}}
    @if (session('success'))
        <div
            class="mb-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm">
            <i class="fa-solid fa-circle-check flex-shrink-0"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <i class="fa-solid fa-circle-xmark flex-shrink-0"></i> {{ session('error') }}
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-slate-200 px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-clock text-amber-500"></i>
            </div>
            <div>
                <p class="text-slate-400 text-xs">Menunggu</p>
                <p class="font-display font-bold text-2xl text-slate-800">{{ $counts['pending'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
            </div>
            <div>
                <p class="text-slate-400 text-xs">Terverifikasi</p>
                <p class="font-display font-bold text-2xl text-slate-800">{{ $counts['verified'] }}</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 px-5 py-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                <i class="fa-solid fa-circle-xmark text-red-500"></i>
            </div>
            <div>
                <p class="text-slate-400 text-xs">Ditolak</p>
                <p class="font-display font-bold text-2xl text-slate-800">{{ $counts['rejected'] }}</p>
            </div>
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
        {{-- Tab bar --}}
        <div class="flex border-b border-slate-100">
            @foreach (['pending' => 'Menunggu', 'verified' => 'Terverifikasi', 'rejected' => 'Ditolak', 'all' => 'Semua'] as $key => $label)
                <a href="{{ route('admin.verifikasi-penyalur.index', ['status' => $key]) }}"
                    class="px-5 py-3.5 text-sm font-medium transition-colors border-b-2
                {{ $status === $key
                    ? 'border-teal-600 text-teal-700 font-semibold'
                    : 'border-transparent text-slate-400 hover:text-slate-600' }}">
                    {{ $label }}
                    @if ($key !== 'all')
                        <span
                            class="ml-1.5 text-xs px-1.5 py-0.5 rounded-full
                        {{ $status === $key ? 'bg-teal-100 text-teal-700' : 'bg-slate-100 text-slate-500' }}">
                            {{ $counts[$key] ?? 0 }}
                        </span>
                    @endif
                </a>
            @endforeach
        </div>

        {{-- Table --}}
        @if ($profiles->isEmpty())
            <div class="flex flex-col items-center justify-center py-16 text-center">
                <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center mb-3">
                    <i class="fa-solid fa-building text-slate-300 text-xl"></i>
                </div>
                <p class="font-display font-semibold text-slate-600">Tidak ada data</p>
                <p class="text-slate-400 text-sm mt-1">Belum ada penyalur dengan status ini.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">#
                            </th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                Organisasi</th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                Tipe</th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                PIC</th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                Daftar</th>
                            <th class="text-left px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                Status</th>
                            <th class="text-right px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($profiles as $i => $profile)
                            @php
                                $statusConfig = [
                                    'pending' => ['bg-amber-100 text-amber-700', 'Menunggu'],
                                    'verified' => ['bg-emerald-100 text-emerald-700', 'Terverifikasi'],
                                    'rejected' => ['bg-red-100 text-red-600', 'Ditolak'],
                                ];
                                [$sc, $sl] = $statusConfig[$profile->verification_status] ?? [
                                    'bg-slate-100 text-slate-500',
                                    $profile->verification_status,
                                ];

                                $typeLabel =
                                    [
                                        'perusahaan' => 'Perusahaan',
                                        'yayasan' => 'Yayasan',
                                        'pemerintah' => 'Pemerintah',
                                        'perguruan_tinggi' => 'Perguruan Tinggi',
                                        'lainnya' => 'Lainnya',
                                    ][$profile->organization_type] ?? $profile->organization_type;
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-4 text-slate-400 text-xs">{{ $profiles->firstItem() + $i }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        {{-- Logo / Avatar --}}
                                        @if ($profile->logo_path)
                                            <img src="{{ Storage::url($profile->logo_path) }}" alt="Logo"
                                                class="w-9 h-9 rounded-xl object-cover border border-slate-200 flex-shrink-0">
                                        @else
                                            <div
                                                class="w-9 h-9 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center flex-shrink-0">
                                                <i class="fa-solid fa-building text-teal-400 text-sm"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-slate-800">
                                                {{ $profile->organization_name ?? '—' }}</p>
                                            <p class="text-slate-400 text-xs">{{ $profile->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-slate-500 text-xs">{{ $typeLabel }}</td>
                                <td class="px-5 py-4">
                                    <p class="text-slate-700 text-xs font-medium">{{ $profile->pic_name ?? '—' }}</p>
                                    <p class="text-slate-400 text-xs">{{ $profile->pic_phone ?? '' }}</p>
                                </td>
                                <td class="px-5 py-4 text-slate-400 text-xs whitespace-nowrap">
                                    {{ $profile->created_at->format('d M Y') }}
                                </td>
                                <td class="px-5 py-4">
                                    <span
                                        class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc }}">{{ $sl }}</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('admin.verifikasi-penyalur.show', $profile->id) }}"
                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-teal-600 hover:text-teal-800 hover:bg-teal-50 px-3 py-1.5 rounded-lg transition-all">
                                        <i class="fa-solid fa-eye text-[10px]"></i> Review
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($profiles->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $profiles->links() }}
                </div>
            @endif
        @endif
    </div>

@endsection
