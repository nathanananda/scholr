@extends('penyalur.layout.layout')

@section('title', 'Detail Pelamar — ' . $application->user->name)

@section('content')

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 mb-5">
        <a href="{{ route('penyalur.pelamar.index') }}" class="hover:text-teal-600 transition-colors">Manajemen Pelamar</a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <a href="{{ route('penyalur.pelamar.show', $scholarship->id) }}" class="hover:text-teal-600 transition-colors">
            {{ Str::limit($scholarship->name, 30) }}
        </a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-slate-600 font-medium">{{ $application->user->name }}</span>
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

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ========== Kolom Kiri: Profil Pelamar ========== --}}
        <div class="lg:col-span-1 flex flex-col gap-4">

            {{-- Kartu profil --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 rounded-2xl bg-teal-100 flex items-center justify-center flex-shrink-0">
                        <span class="font-display text-teal-700 font-bold text-lg uppercase">
                            {{ substr($application->user->name, 0, 2) }}
                        </span>
                    </div>
                    <div>
                        <p class="font-display font-bold text-slate-800">{{ $application->user->name }}</p>
                        <p class="text-slate-400 text-xs">{{ $application->user->email }}</p>
                    </div>
                </div>

                @php $profile = $application->user->penerimaProfile; @endphp

                @if ($profile)
                    <div class="space-y-2.5 text-sm">
                        <div class="flex justify-between">
                            <span class="text-slate-400">Jenjang</span>
                            <span
                                class="font-medium text-slate-700">{{ strtoupper($profile->education_level ?? '—') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Institusi</span>
                            <span
                                class="font-medium text-slate-700 text-right max-w-[60%]">{{ $profile->institution ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">IPK</span>
                            <span class="font-medium text-slate-700">{{ $profile->gpa ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Semester</span>
                            <span class="font-medium text-slate-700">{{ $profile->semester ?? '—' }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-slate-400">Penghasilan OT</span>
                            <span class="font-medium text-slate-700">
                                Rp {{ number_format($profile->parent_income ?? 0, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                @else
                    <p class="text-slate-400 text-sm">Data profil tidak tersedia.</p>
                @endif
            </div>

            {{-- Status lamaran --}}
            @php
                $statusConfig = [
                    'draft' => ['bg-slate-100 text-slate-500 border-slate-200', 'Draft', 'fa-file'],
                    'submitted' => ['bg-blue-50 text-blue-700 border-blue-200', 'Submitted', 'fa-paper-plane'],
                    'under_review' => [
                        'bg-amber-50 text-amber-700 border-amber-200',
                        'Under Review',
                        'fa-magnifying-glass',
                    ],
                    'accepted' => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'Diterima', 'fa-circle-check'],
                    'rejected' => ['bg-red-50 text-red-600 border-red-200', 'Ditolak', 'fa-circle-xmark'],
                ];
                [$sc, $sl, $si] = $statusConfig[$application->status] ?? [
                    'bg-slate-100 text-slate-500 border-slate-200',
                    $application->status,
                    'fa-circle',
                ];
            @endphp
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Status Lamaran</p>
                <div class="flex items-center gap-3 p-3 rounded-xl border {{ $sc }}">
                    <i class="fa-solid {{ $si }} text-lg"></i>
                    <span class="font-display font-bold">{{ $sl }}</span>
                </div>
                <p class="text-slate-400 text-xs mt-3">Diajukan: {{ $application->created_at->format('d M Y, H:i') }}</p>
                @if ($application->saw_score !== null)
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <div class="flex justify-between text-sm">
                            <span class="text-slate-400">Skor SAW</span>
                            <span
                                class="font-display font-bold text-teal-700">{{ number_format($application->saw_score, 4) }}</span>
                        </div>
                        <div class="flex justify-between text-sm mt-1">
                            <span class="text-slate-400">Ranking</span>
                            <span class="font-display font-bold text-slate-700">#{{ $application->saw_rank }}</span>
                        </div>
                    </div>
                @endif
            </div>

        </div>

        {{-- ========== Kolom Kanan: Dokumen + Kriteria ========== --}}
        <div class="lg:col-span-2 flex flex-col gap-5">

            {{-- Dokumen Persyaratan --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h2 class="font-display font-bold text-slate-800">Dokumen Persyaratan</h2>
                    <p class="text-slate-400 text-xs mt-0.5">Review dan verifikasi dokumen yang diupload pelamar.</p>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($application->documents as $doc)
                        @php
                            $docStatus = match ($doc->status) {
                                'approved' => ['text-emerald-600 bg-emerald-50', 'fa-circle-check', 'Approved'],
                                'rejected' => ['text-red-600 bg-red-50', 'fa-circle-xmark', 'Ditolak'],
                                default => ['text-slate-500 bg-slate-50', 'fa-clock', 'Menunggu'],
                            };
                        @endphp
                        <div class="px-5 py-4">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex items-start gap-3 flex-1 min-w-0">
                                    <div
                                        class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                                        <i class="fa-solid fa-file-alt text-slate-500"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="font-medium text-slate-800 text-sm">{{ $doc->template->name }}</p>
                                        @if ($doc->template->is_required)
                                            <span class="text-[10px] text-red-500 font-semibold">Wajib</span>
                                        @else
                                            <span class="text-[10px] text-slate-400">Opsional</span>
                                        @endif
                                        @if ($doc->file_path)
                                            <a href="{{ Storage::url($doc->file_path) }}" target="_blank"
                                                class="flex items-center gap-1 text-xs text-teal-600 hover:underline mt-1">
                                                <i class="fa-solid fa-arrow-up-right-from-square text-[10px]"></i>
                                                Lihat File
                                            </a>
                                        @else
                                            <p class="text-slate-400 text-xs mt-1 italic">Belum diupload</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 flex-shrink-0">
                                    {{-- Badge status --}}
                                    <span
                                        class="text-xs font-semibold px-2.5 py-1 rounded-full flex items-center gap-1 {{ $docStatus[0] }}">
                                        <i class="fa-solid {{ $docStatus[1] }} text-[10px]"></i>
                                        {{ $docStatus[2] }}
                                    </span>

                                    {{-- Tombol aksi (hanya jika ada file dan belum approved) --}}
                                    @if ($doc->file_path && $doc->status !== 'approved')
                                        <form action="{{ route('penyalur.pelamar.document.approve', $doc->id) }}"
                                            method="POST">
                                            @csrf
                                            <button type="submit"
                                                class="text-xs bg-emerald-600 hover:bg-emerald-700 text-white px-3 py-1.5 rounded-lg font-medium transition-colors">
                                                Approve
                                            </button>
                                        </form>
                                    @endif

                                    @if ($doc->file_path && $doc->status !== 'rejected')
                                        <button type="button" onclick="openRejectModal({{ $doc->id }})"
                                            class="text-xs bg-red-50 hover:bg-red-100 text-red-600 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                            Tolak
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Catatan penolakan --}}
                            @if ($doc->status === 'rejected' && $doc->review_note)
                                <div class="mt-3 bg-red-50 border border-red-100 rounded-lg px-3 py-2 text-xs text-red-600">
                                    <span class="font-semibold">Catatan:</span> {{ $doc->review_note }}
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="px-5 py-8 text-center text-slate-400 text-sm">
                            Belum ada dokumen yang diupload.
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Nilai Kriteria SAW --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h2 class="font-display font-bold text-slate-800">Nilai Kriteria SAW</h2>
                    <p class="text-slate-400 text-xs mt-0.5">Nilai yang diisi pelamar untuk setiap kriteria penilaian.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th
                                    class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                    Kriteria</th>
                                <th
                                    class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                    Tipe</th>
                                <th
                                    class="text-left px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                    Bobot</th>
                                <th
                                    class="text-right px-5 py-3 text-xs font-semibold text-slate-500 uppercase tracking-wide">
                                    Nilai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach ($scholarship->criteria as $criteria)
                                @php
                                    $val = $application->criteriaValues->firstWhere('criteria_id', $criteria->id);
                                @endphp
                                <tr class="hover:bg-slate-50">
                                    <td class="px-5 py-3 font-medium text-slate-700">{{ $criteria->name }}</td>
                                    <td class="px-5 py-3">
                                        @if ($criteria->type === 'Benefit')
                                            <span
                                                class="text-xs bg-emerald-50 text-emerald-700 font-semibold px-2 py-0.5 rounded-full">Benefit</span>
                                        @else
                                            <span
                                                class="text-xs bg-red-50 text-red-600 font-semibold px-2 py-0.5 rounded-full">Cost</span>
                                        @endif
                                    </td>
                                    <td class="px-5 py-3 text-slate-500">{{ $criteria->weight / 1 }}%</td>
                                    <td class="px-5 py-3 text-right font-display font-bold text-slate-800">
                                        {{ $val ? $val->value : '—' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    {{-- Modal Reject Dokumen --}}
    <div id="modal-reject"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <h3 class="font-display font-bold text-slate-800 text-lg mb-1">Tolak Dokumen</h3>
            <p class="text-slate-400 text-sm mb-4">Berikan catatan alasan penolakan agar pelamar dapat memperbaikinya.</p>

            <form id="form-reject" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Catatan Penolakan <span
                            class="text-red-500">*</span></label>
                    <textarea name="note" rows="3" required
                        placeholder="Contoh: Dokumen tidak terbaca, mohon upload ulang dengan resolusi lebih tinggi."
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent resize-none"></textarea>
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeRejectModal()"
                        class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-4 py-2 text-sm font-medium bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script>
        function openRejectModal(docId) {
            const base = "{{ url('penyalur/pelamar/document') }}";
            document.getElementById('form-reject').action = `${base}/${docId}/reject`;
            document.getElementById('modal-reject').classList.remove('hidden');
        }

        function closeRejectModal() {
            document.getElementById('modal-reject').classList.add('hidden');
        }
        // Close on backdrop click
        document.getElementById('modal-reject').addEventListener('click', function(e) {
            if (e.target === this) closeRejectModal();
        });
    </script>
@endsection
