@extends('admin.layout.layout')

@section('title', 'Detail Penyalur — ' . ($profile->organization_name ?? 'N/A'))

@section('content')

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-slate-400 mb-5">
        <a href="{{ route('admin.verifikasi-penyalur.index') }}" class="hover:text-teal-600 transition-colors">
            Verifikasi Penyalur
        </a>
        <i class="fa-solid fa-chevron-right text-[10px]"></i>
        <span class="text-slate-600 font-medium">{{ Str::limit($profile->organization_name ?? 'Detail', 40) }}</span>
    </div>

    @php
        $statusConfig = [
            'pending' => ['bg-amber-50 text-amber-700 border-amber-200', 'fa-clock', 'Menunggu Verifikasi'],
            'verified' => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'fa-circle-check', 'Terverifikasi'],
            'rejected' => ['bg-red-50 text-red-600 border-red-200', 'fa-circle-xmark', 'Ditolak'],
        ];
        [$sc, $si, $sl] = $statusConfig[$profile->verification_status] ?? [
            'bg-slate-100 text-slate-500 border-slate-200',
            'fa-circle',
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

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div class="flex items-center gap-4">
            @if ($profile->logo_path)
                <img src="{{ Storage::url($profile->logo_path) }}" alt="Logo"
                    class="w-14 h-14 rounded-2xl object-cover border border-slate-200 flex-shrink-0">
            @else
                <div
                    class="w-14 h-14 rounded-2xl bg-teal-50 border border-teal-100 flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-building text-teal-400 text-2xl"></i>
                </div>
            @endif
            <div>
                <h1 class="font-display text-xl font-bold text-slate-800">{{ $profile->organization_name ?? '—' }}</h1>
                <p class="text-slate-400 text-sm mt-0.5">{{ $profile->user->email }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 rounded-xl border {{ $sc }} text-sm font-semibold">
            <i class="fa-solid {{ $si }}"></i> {{ $sl }}
        </div>
    </div>

    {{-- Catatan penolakan sebelumnya --}}
    @if ($profile->verification_status === 'rejected' && $profile->verification_note)
        <div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <i class="fa-solid fa-triangle-exclamation mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="font-semibold">Catatan penolakan sebelumnya:</p>
                <p class="mt-0.5">{{ $profile->verification_note }}</p>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- ===== Kolom Kiri ===== --}}
        <div class="lg:col-span-1 flex flex-col gap-4">

            {{-- Info Singkat --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-4">Info Akun</p>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between gap-2">
                        <span class="text-slate-400">Nama Akun</span>
                        <span class="font-medium text-slate-700 text-right">{{ $profile->user->name }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-slate-400">Email</span>
                        <span class="font-medium text-slate-700 text-right">{{ $profile->user->email }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-slate-400">Tipe</span>
                        <span class="font-medium text-slate-700">{{ $typeLabel }}</span>
                    </div>
                    <div class="flex justify-between gap-2">
                        <span class="text-slate-400">Daftar</span>
                        <span class="font-medium text-slate-700">{{ $profile->created_at->format('d M Y') }}</span>
                    </div>
                    @if ($profile->verified_at)
                        <div class="flex justify-between gap-2">
                            <span class="text-slate-400">Diverifikasi</span>
                            <span class="font-medium text-slate-700">{{ $profile->verified_at->format('d M Y') }}</span>
                        </div>
                    @endif
                </div>
            </div>

            {{-- KTP PIC --}}
            <div class="bg-white rounded-2xl border border-slate-200 p-5">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Foto KTP PIC</p>
                @if ($profile->pic_id_card_path)
                    @php $ext = pathinfo($profile->pic_id_card_path, PATHINFO_EXTENSION); @endphp
                    @if (in_array(strtolower($ext), ['jpg', 'jpeg', 'png']))
                        <a href="{{ Storage::url($profile->pic_id_card_path) }}" target="_blank">
                            <img src="{{ Storage::url($profile->pic_id_card_path) }}" alt="KTP PIC"
                                class="w-full rounded-xl border border-slate-200 object-cover hover:opacity-90 transition-opacity">
                        </a>
                    @else
                        <a href="{{ Storage::url($profile->pic_id_card_path) }}" target="_blank"
                            class="flex items-center gap-3 p-3 bg-slate-50 border border-slate-200 rounded-xl hover:bg-slate-100 transition-colors">
                            <i class="fa-solid fa-file-pdf text-red-500 text-xl"></i>
                            <span class="text-sm text-teal-600 font-medium hover:underline">Lihat File KTP</span>
                        </a>
                    @endif
                @else
                    <div
                        class="w-full h-24 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-2">
                        <i class="fa-solid fa-id-card text-slate-300 text-xl"></i>
                        <p class="text-slate-400 text-xs">Belum ada foto KTP</p>
                    </div>
                @endif
            </div>

        </div>

        {{-- ===== Kolom Kanan ===== --}}
        <div class="lg:col-span-2 flex flex-col gap-5">

            {{-- Detail Organisasi --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h2 class="font-display font-bold text-slate-800">Informasi Organisasi</h2>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Nama Organisasi</p>
                        <p class="font-semibold text-slate-800">{{ $profile->organization_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Tipe Organisasi</p>
                        <p class="font-semibold text-slate-800">{{ $typeLabel }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">NPWP</p>
                        <p class="font-semibold text-slate-800">{{ $profile->npwp ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Nomor Telepon</p>
                        <p class="font-semibold text-slate-800">{{ $profile->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Website</p>
                        @if ($profile->website)
                            <a href="{{ $profile->website }}" target="_blank"
                                class="font-semibold text-teal-600 hover:underline">{{ $profile->website }}</a>
                        @else
                            <p class="font-semibold text-slate-800">—</p>
                        @endif
                    </div>
                    <div class="sm:col-span-2">
                        <p class="text-xs text-slate-400 mb-0.5">Alamat</p>
                        <p class="font-semibold text-slate-800">{{ $profile->address ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- Data PIC --}}
            <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100">
                    <h2 class="font-display font-bold text-slate-800">Penanggung Jawab (PIC)</h2>
                </div>
                <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-4 text-sm">
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Nama PIC</p>
                        <p class="font-semibold text-slate-800">{{ $profile->pic_name ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 mb-0.5">Nomor HP PIC</p>
                        <p class="font-semibold text-slate-800">{{ $profile->pic_phone ?? '—' }}</p>
                    </div>
                </div>
            </div>

            {{-- ===== Action Panel ===== --}}
            @if ($profile->verification_status !== 'verified')
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h2 class="font-display font-bold text-slate-800">Keputusan Verifikasi</h2>
                        <p class="text-slate-400 text-xs mt-0.5">Pilih untuk menyetujui atau menolak akun penyalur ini.</p>
                    </div>
                    <div class="p-5 flex flex-col sm:flex-row gap-3">

                        {{-- Approve --}}
                        <form action="{{ route('admin.verifikasi-penyalur.approve', $profile->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin memverifikasi akun {{ addslashes($profile->organization_name) }}?')">
                            @csrf
                            <button type="submit"
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors shadow-sm shadow-emerald-100">
                                <i class="fa-solid fa-circle-check"></i> Verifikasi Akun
                            </button>
                        </form>

                        {{-- Reject (buka modal) --}}
                        <button type="button" onclick="document.getElementById('modal-reject').classList.remove('hidden')"
                            class="w-full sm:w-auto inline-flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors border border-red-200">
                            <i class="fa-solid fa-circle-xmark"></i> Tolak Akun
                        </button>

                    </div>
                </div>
            @else
                <div class="bg-emerald-50 border border-emerald-200 rounded-2xl px-5 py-4 flex items-center gap-3">
                    <i class="fa-solid fa-circle-check text-emerald-600 text-lg flex-shrink-0"></i>
                    <div>
                        <p class="font-semibold text-emerald-700 text-sm">Akun sudah terverifikasi</p>
                        <p class="text-emerald-600 text-xs mt-0.5">Diverifikasi pada
                            {{ $profile->verified_at?->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- Modal Reject --}}
    <div id="modal-reject"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fa-solid fa-circle-xmark text-red-500 text-lg"></i>
                </div>
                <div>
                    <h3 class="font-display font-bold text-slate-800">Tolak Akun</h3>
                    <p class="text-slate-400 text-xs">{{ $profile->organization_name }}</p>
                </div>
            </div>

            <form action="{{ route('admin.verifikasi-penyalur.reject', $profile->id) }}" method="POST">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-semibold text-slate-700 mb-1.5">
                        Catatan Penolakan <span class="text-red-500">*</span>
                    </label>
                    <textarea name="verification_note" rows="4" required
                        placeholder="Jelaskan alasan penolakan agar penyalur dapat memperbaiki datanya. Contoh: Data NPWP tidak valid, mohon upload ulang dokumen KTP yang lebih jelas."
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-red-400 focus:border-transparent resize-none"></textarea>
                    @error('verification_note')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="document.getElementById('modal-reject').classList.add('hidden')"
                        class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 text-sm font-semibold bg-red-600 hover:bg-red-700 text-white rounded-xl transition-colors">
                        Konfirmasi Tolak
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@section('script')
    <script>
        // Close modal on backdrop click
        document.getElementById('modal-reject').addEventListener('click', function(e) {
            if (e.target === this) this.classList.add('hidden');
        });
    </script>
@endsection
