@extends('penyalur.layout.layout')

@section('content')
    <div class="container mx-auto px-4 py-4">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Master Data Beasiswa</h2>
                <p class="text-sm text-gray-500 mt-1">Kelola seluruh program dan kuota beasiswa yang aktif maupun arsip.</p>
            </div>
            <a href="{{ route('penyalur.beasiswa.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 px-4 rounded-lg shadow-sm transition duration-150 ease-in-out flex items-center gap-2">
                <i class="fa-solid fa-plus text-sm"></i> Tambah Beasiswa
            </a>
        </div>

        {{-- Flash message --}}
        @if (session('success'))
            <div
                class="mb-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-3 text-sm">
                <i class="fa-solid fa-circle-check text-emerald-500"></i>
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse" id="myTable">
                    <thead>
                        <tr
                            class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            <th class="px-6 py-4">Nama Beasiswa</th>
                            <th class="px-6 py-4">Periode Pendaftaran</th>
                            <th class="px-6 py-4">Kuota</th>
                            <th class="px-6 py-4">Status</th>
                            <th class="px-6 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-700">

                        @forelse ($scholarships as $scholarship)
                            @php
                                $categoryIcon = match ($scholarship->category) {
                                    'Prestasi' => [
                                        'icon' => 'fa-graduation-cap',
                                        'bg' => 'bg-indigo-50',
                                        'text' => 'text-indigo-600',
                                    ],
                                    'Sosial' => [
                                        'icon' => 'fa-award',
                                        'bg' => 'bg-amber-50',
                                        'text' => 'text-amber-600',
                                    ],
                                    'Internal' => [
                                        'icon' => 'fa-building-columns',
                                        'bg' => 'bg-blue-50',
                                        'text' => 'text-blue-600',
                                    ],
                                    'Eksternal' => [
                                        'icon' => 'fa-earth-asia',
                                        'bg' => 'bg-teal-50',
                                        'text' => 'text-teal-600',
                                    ],
                                    default => [
                                        'icon' => 'fa-file-invoice',
                                        'bg' => 'bg-gray-100',
                                        'text' => 'text-gray-500',
                                    ],
                                };

                                $statusBadge = match ($scholarship->status) {
                                    'Draft' => [
                                        'label' => 'Draft',
                                        'bg' => 'bg-gray-100',
                                        'text' => 'text-gray-700',
                                        'border' => 'border-gray-200',
                                        'dot' => 'bg-gray-400',
                                    ],
                                    'Aktif' => [
                                        'label' => 'Aktif',
                                        'bg' => 'bg-emerald-50',
                                        'text' => 'text-emerald-700',
                                        'border' => 'border-emerald-200',
                                        'dot' => 'bg-emerald-500',
                                    ],
                                    'Seleksi' => [
                                        'label' => 'Tahap Seleksi (SAW)',
                                        'bg' => 'bg-amber-50',
                                        'text' => 'text-amber-700',
                                        'border' => 'border-amber-200',
                                        'dot' => 'bg-amber-500',
                                    ],
                                    'Selesai' => [
                                        'label' => 'Selesai',
                                        'bg' => 'bg-gray-100',
                                        'text' => 'text-gray-700',
                                        'border' => 'border-gray-200',
                                        'dot' => 'bg-gray-400',
                                    ],
                                    default => [
                                        'label' => $scholarship->status,
                                        'bg' => 'bg-gray-100',
                                        'text' => 'text-gray-700',
                                        'border' => 'border-gray-200',
                                        'dot' => 'bg-gray-400',
                                    ],
                                };
                            @endphp

                            <tr class="hover:bg-gray-50 transition duration-150">

                                {{-- Nama & Kategori --}}
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-10 h-10 {{ $categoryIcon['bg'] }} rounded-lg flex items-center justify-center {{ $categoryIcon['text'] }}">
                                            <i class="fa-solid {{ $categoryIcon['icon'] }} text-lg"></i>
                                        </div>
                                        <div>
                                            <span class="font-semibold text-gray-900 block">{{ $scholarship->name }}</span>
                                            <span class="text-xs text-gray-500">Kategori:
                                                {{ $scholarship->category }}</span>
                                        </div>
                                    </div>
                                </td>

                                {{-- Periode --}}
                                <td class="px-6 py-4">
                                    <div class="flex flex-col">
                                        @if ($scholarship->status === 'Selesai')
                                            <span class="font-medium text-gray-400">Selesai pada</span>
                                            <span class="text-xs text-gray-500">
                                                {{ \Carbon\Carbon::parse($scholarship->end_date)->format('d M Y') }}
                                            </span>
                                        @else
                                            <span class="font-medium text-gray-800">
                                                {{ \Carbon\Carbon::parse($scholarship->start_date)->format('d M Y') }}
                                            </span>
                                            @if ($scholarship->status === 'Seleksi')
                                                <span class="text-xs text-rose-500 font-medium">Pendaftaran Ditutup</span>
                                            @else
                                                <span class="text-xs text-gray-400">
                                                    s/d {{ \Carbon\Carbon::parse($scholarship->end_date)->format('d M Y') }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>

                                {{-- Kuota --}}
                                <td class="px-6 py-4">
                                    <div
                                        class="flex items-center gap-1 {{ $scholarship->status === 'Selesai' ? 'text-gray-400' : '' }}">
                                        <span class="font-semibold text-gray-900">{{ $scholarship->quota }}</span>
                                        <span class="text-gray-400">orang</span>
                                    </div>
                                </td>

                                {{-- Status --}}
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center gap-1.5 py-1 px-2.5 rounded-full text-xs font-medium
                                        {{ $statusBadge['bg'] }} {{ $statusBadge['text'] }} border {{ $statusBadge['border'] }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusBadge['dot'] }}"></span>
                                        {{ $statusBadge['label'] }}
                                    </span>
                                </td>

                                {{-- Aksi --}}
                                <td class="px-6 py-4 text-right whitespace-nowrap">
                                    <div class="inline-flex items-center gap-2">

                                        @if ($scholarship->status === 'Seleksi')
                                            <a href="" title="Proses Hitung SAW"
                                                class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-medium text-xs py-1.5 px-3 rounded-md transition flex items-center gap-1">
                                                <i class="fa-solid fa-calculator"></i> Hitung SPK
                                            </a>
                                        @endif

                                        @if ($scholarship->status === 'Selesai')
                                            <a href="" title="Lihat Laporan"
                                                class="text-gray-600 hover:text-gray-900 font-medium text-xs py-1.5 px-3 border border-gray-300 rounded-md hover:bg-gray-50 transition flex items-center gap-1 ml-auto">
                                                <i class="fa-solid fa-print"></i> Laporan
                                            </a>
                                        @endif

                                        @if (in_array($scholarship->status, ['Draft', 'Aktif']))
                                            <a href="{{ route('penyalur.beasiswa.criteria', $scholarship->id) }}"
                                                title="Atur Kriteria SPK"
                                                class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition">
                                                <i class="fa-solid fa-sliders fa-fw"></i>
                                            </a>
                                        @endif

                                        @if ($scholarship->status !== 'Selesai')
                                            <a href="{{ route('penyalur.beasiswa.edit', $scholarship->id) }}"
                                                title="Edit Beasiswa"
                                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition">
                                                <i class="fa-solid fa-pen-to-square fa-fw"></i>
                                            </a>
                                        @endif

                                        @if ($scholarship->status === 'Draft')
                                            <form action="{{ route('penyalur.beasiswa.destroy', $scholarship->id) }}"
                                                method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus beasiswa ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" title="Hapus"
                                                    class="p-2 text-rose-600 hover:bg-rose-50 rounded-lg transition">
                                                    <i class="fa-solid fa-trash fa-fw"></i>
                                                </button>
                                            </form>
                                        @endif

                                        <a href="{{ route('penyalur.beasiswa.show', $scholarship->id) }}"
                                            title="Lihat Detail"
                                            class="p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition">
                                            <i class="fa-solid fa-eye fa-fw"></i>


                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3 text-gray-400">
                                        <i class="fa-solid fa-folder-open text-4xl"></i>
                                        <p class="text-sm font-medium">Belum ada data beasiswa</p>
                                        <a href="{{ route('penyalur.beasiswa.create') }}"
                                            class="text-indigo-600 hover:underline text-xs font-medium">
                                            + Tambah beasiswa pertama
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('#myTable').DataTable({
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ data",
                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                    infoEmpty: "Tidak ada data tersedia",
                    infoFiltered: "(difilter dari _MAX_ total data)",
                    zeroRecords: "Tidak ada data yang cocok",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Selanjutnya",
                        previous: "Sebelumnya",
                    },
                },
                columnDefs: [{
                    orderable: false,
                    targets: [3, 4]
                }]
            });
        });
    </script>
@endsection
