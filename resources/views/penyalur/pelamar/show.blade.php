@extends('penyalur.layout.layout')

@section('title', 'Pelamar — ' . $scholarship->name)

@section('content')

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 mb-5">
        <a href="{{ route('penyalur.pelamar.index') }}" class="hover:text-teal-600 transition-colors">Manajemen Pelamar</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-slate-600 font-medium">{{ Str::limit($scholarship->name, 40) }}</span>
    </div>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="font-display text-xl font-bold text-slate-800">{{ $scholarship->name }}</h1>
            <div class="flex flex-wrap items-center gap-3 mt-1.5">
                <span class="text-slate-400 text-sm">Kuota: <span
                        class="font-semibold text-slate-700">{{ $scholarship->quota }}</span></span>
                <span class="text-slate-300">•</span>
                <span class="text-slate-400 text-sm">Total Pelamar: <span
                        class="font-semibold text-slate-700">{{ $applications->count() }}</span></span>
            </div>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            {{-- Tombol Run SAW --}}
            @php
                $canRunSaw =
                    $applications->where('status', 'under_review')->count() > 0 &&
                    $applications->where('status', 'submitted')->count() === 0;
                $sawDone = $applications->whereNotNull('saw_rank')->count() > 0;
            @endphp

            @if ($sawDone)
                <a href="{{ route('penyalur.pelamar.ranking', $scholarship->id) }}"
                    class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
                    <i class="fa-solid fa-trophy"></i> Lihat Ranking SAW
                </a>
            @endif

            @if ($canRunSaw)
                <form action="{{ route('penyalur.pelamar.run-saw', $scholarship->id) }}" method="POST"
                    onsubmit="return confirm('Jalankan perhitungan SAW untuk semua pelamar? Pastikan semua dokumen sudah diverifikasi.')">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-medium px-4 py-2.5 rounded-xl transition-colors">
                        <i class="fa-solid fa-calculator"></i> Jalankan SAW
                    </button>
                </form>
            @endif
        </div>
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

    {{-- Info banner jika ada submitted belum diverifikasi --}}
    @if ($applications->where('status', 'submitted')->count() > 0)
        <div
            class="mb-4 flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl px-4 py-3 text-sm">
            <i class="fa-solid fa-triangle-exclamation mt-0.5"></i>
            <div>
                <p class="font-semibold">Ada {{ $applications->where('status', 'submitted')->count() }} lamaran belum
                    diverifikasi dokumennya.</p>
                <p class="text-amber-600 mt-0.5">Selesaikan verifikasi semua dokumen sebelum menjalankan perhitungan SAW.
                </p>
            </div>
        </div>
    @endif

    {{-- Tabel pelamar --}}
    @if ($applications->isEmpty())
        <div
            class="flex flex-col items-center justify-center py-20 text-center bg-white rounded-2xl border border-slate-200">
            <div class="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center mb-3">
                <i class="fa-solid fa-inbox text-teal-400 text-xl"></i>
            </div>
            <p class="font-display font-semibold text-slate-700">Belum Ada Pelamar</p>
            <p class="text-slate-400 text-sm mt-1">Belum ada penerima yang mengajukan lamaran untuk beasiswa ini.</p>
        </div>
    @else
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table id="tbl-pelamar" class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-slate-100 bg-slate-50">
                            <th class="text-left px-5 py-3.5 font-semibold text-slate-500 text-xs uppercase tracking-wide">#
                            </th>
                            <th class="text-left px-5 py-3.5 font-semibold text-slate-500 text-xs uppercase tracking-wide">
                                Pelamar</th>
                            <th class="text-left px-5 py-3.5 font-semibold text-slate-500 text-xs uppercase tracking-wide">
                                Dokumen</th>
                            <th class="text-left px-5 py-3.5 font-semibold text-slate-500 text-xs uppercase tracking-wide">
                                Status</th>
                            <th class="text-left px-5 py-3.5 font-semibold text-slate-500 text-xs uppercase tracking-wide">
                                Skor SAW</th>
                            <th class="text-left px-5 py-3.5 font-semibold text-slate-500 text-xs uppercase tracking-wide">
                                Tanggal Lamar</th>
                            <th class="text-right px-5 py-3.5 font-semibold text-slate-500 text-xs uppercase tracking-wide">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($applications as $i => $app)
                            @php
                                $statusConfig = [
                                    'draft' => ['bg-slate-100 text-slate-500', 'Draft'],
                                    'submitted' => ['bg-blue-100 text-blue-700', 'Submitted'],
                                    'under_review' => ['bg-amber-100 text-amber-700', 'Under Review'],
                                    'accepted' => ['bg-emerald-100 text-emerald-700', 'Diterima'],
                                    'rejected' => ['bg-red-100 text-red-600', 'Ditolak'],
                                ];
                                [$sc, $sl] = $statusConfig[$app->status] ?? [
                                    'bg-slate-100 text-slate-500',
                                    $app->status,
                                ];
                            @endphp
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-5 py-4 text-slate-400 font-medium">{{ $i + 1 }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-teal-100 flex items-center justify-center flex-shrink-0">
                                            <span class="text-teal-700 text-xs font-bold uppercase">
                                                {{ substr($app->user->name, 0, 2) }}
                                            </span>
                                        </div>
                                        <div>
                                            <p class="font-medium text-slate-800">{{ $app->user->name }}</p>
                                            <p class="text-slate-400 text-xs">{{ $app->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    @php
                                        $totalDoc = $app->doc_total_required;
                                        $approvedDoc = $app->doc_approved;
                                        $rejectedDoc = $app->doc_rejected;
                                    @endphp
                                    <div class="flex items-center gap-2">
                                        <div class="flex-1 max-w-[80px] h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full bg-teal-500 rounded-full transition-all"
                                                style="width: {{ $totalDoc > 0 ? ($approvedDoc / $totalDoc) * 100 : 0 }}%">
                                            </div>
                                        </div>
                                        <span
                                            class="text-xs text-slate-500">{{ $approvedDoc }}/{{ $totalDoc }}</span>
                                        @if ($rejectedDoc > 0)
                                            <span class="text-xs text-red-500 font-medium">{{ $rejectedDoc }}
                                                ditolak</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-5 py-4">
                                    <span
                                        class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $sc }}">{{ $sl }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    @if ($app->saw_score !== null)
                                        <div class="flex items-center gap-1.5">
                                            <span
                                                class="font-display font-bold text-teal-700">{{ number_format($app->saw_score, 4) }}</span>
                                            <span class="text-xs text-slate-400">#{{ $app->saw_rank }}</span>
                                        </div>
                                    @else
                                        <span class="text-slate-300 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-slate-500 text-xs">
                                    {{ $app->created_at->format('d M Y') }}
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('penyalur.pelamar.detail', [$scholarship->id, $app->id]) }}"
                                        class="inline-flex items-center gap-1.5 text-xs font-medium text-teal-600 hover:text-teal-800 hover:bg-teal-50 px-3 py-1.5 rounded-lg transition-all">
                                        <i class="fa-solid fa-eye"></i> Detail
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#tbl-pelamar').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    paginate: {
                        previous: "‹",
                        next: "›"
                    },
                    emptyTable: "Tidak ada data",
                },
                columnDefs: [{
                    orderable: false,
                    targets: [2, 6]
                }],
            });
        });
    </script>
@endsection
