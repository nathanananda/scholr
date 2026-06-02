@extends('penyalur.layout.layout')

@section('title', 'Profil Organisasi')

@section('content')

    @php
        $profile = auth()->user()->penyalurProfile;
        $statusConfig = [
            'pending' => ['bg-amber-50 text-amber-700 border-amber-200', 'fa-clock', 'Menunggu Verifikasi'],
            'verified' => ['bg-emerald-50 text-emerald-700 border-emerald-200', 'fa-circle-check', 'Terverifikasi'],
            'rejected' => ['bg-red-50 text-red-600 border-red-200', 'fa-circle-xmark', 'Ditolak'],
        ];
        $status = $profile?->verification_status ?? 'pending';
        [$sc, $si, $sl] = $statusConfig[$status];
    @endphp

    {{-- Page Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="font-display text-2xl font-bold text-slate-800">Profil Organisasi</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola informasi organisasi dan data penanggung jawab.</p>
        </div>
        {{-- Status Badge --}}
        <div class="flex items-center gap-2 px-4 py-2 rounded-xl border {{ $sc }} text-sm font-semibold">
            <i class="fa-solid {{ $si }} text-sm"></i>
            {{ $sl }}
        </div>
    </div>

    {{-- Alert verifikasi ditolak --}}
    @if ($status === 'rejected' && $profile?->verification_note)
        <div class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <i class="fa-solid fa-triangle-exclamation mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="font-semibold">Akun ditolak oleh admin.</p>
                <p class="mt-0.5 text-red-600">Catatan: {{ $profile->verification_note }}</p>
                <p class="mt-1 text-red-500 text-xs">Perbaiki data di bawah lalu simpan untuk mengajukan ulang.</p>
            </div>
        </div>
    @endif

    {{-- Alert info pending --}}
    @if ($status === 'pending')
        <div
            class="mb-5 flex items-start gap-3 bg-amber-50 border border-amber-200 text-amber-700 rounded-xl px-4 py-3 text-sm">
            <i class="fa-solid fa-clock mt-0.5 flex-shrink-0"></i>
            <div>
                <p class="font-semibold">Profil sedang menunggu verifikasi admin.</p>
                <p class="mt-0.5 text-amber-600">Kamu bisa memperbarui data sambil menunggu. Admin akan mereview kembali
                    setelah ada perubahan.</p>
            </div>
        </div>
    @endif

    {{-- Session alerts --}}
    @if (session('success'))
        <div
            class="mb-5 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm">
            <i class="fa-solid fa-circle-check"></i> {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="mb-5 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
            <i class="fa-solid fa-circle-exclamation"></i> {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('penyalur.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

            {{-- ========== Kolom Kiri: Logo + Info Singkat ========== --}}
            <div class="lg:col-span-1 flex flex-col gap-4">

                {{-- Logo --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5 flex flex-col items-center text-center gap-4">
                    <div class="relative group">
                        @if ($profile?->logo_path)
                            <img src="{{ Storage::url($profile->logo_path) }}" alt="Logo"
                                class="w-24 h-24 rounded-2xl object-cover border-2 border-slate-200">
                        @else
                            <div
                                class="w-24 h-24 rounded-2xl bg-teal-50 border-2 border-dashed border-teal-200 flex items-center justify-center">
                                <i class="fa-solid fa-building text-teal-300 text-3xl"></i>
                            </div>
                        @endif
                        {{-- Overlay upload --}}
                        <label for="logo"
                            class="absolute inset-0 rounded-2xl bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer flex items-center justify-center">
                            <i class="fa-solid fa-camera text-white text-xl"></i>
                        </label>
                        <input type="file" id="logo" name="logo" accept="image/*" class="hidden"
                            onchange="previewLogo(this)">
                    </div>
                    <div>
                        <p class="font-display font-bold text-slate-800">{{ $profile?->organization_name ?? '—' }}</p>
                        <p class="text-slate-400 text-xs mt-0.5">{{ auth()->user()->email }}</p>
                    </div>
                    <label for="logo"
                        class="inline-flex items-center gap-2 text-xs text-teal-600 hover:text-teal-800 hover:bg-teal-50 px-3 py-1.5 rounded-lg transition-all cursor-pointer font-medium border border-teal-200">
                        <i class="fa-solid fa-upload text-[10px]"></i> Upload Logo
                    </label>
                    <p class="text-slate-400 text-[11px]">JPG, PNG, maks. 2MB</p>
                </div>

                {{-- KTP PIC --}}
                <div class="bg-white rounded-2xl border border-slate-200 p-5">
                    <p class="text-xs font-semibold text-slate-500 uppercase tracking-wide mb-3">KTP Penanggung Jawab</p>
                    @if ($profile?->pic_id_card_path)
                        <img src="{{ Storage::url($profile->pic_id_card_path) }}" alt="KTP PIC"
                            class="w-full rounded-xl border border-slate-200 object-cover mb-3">
                    @else
                        <div
                            class="w-full h-28 rounded-xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center gap-2 mb-3">
                            <i class="fa-solid fa-id-card text-slate-300 text-2xl"></i>
                            <p class="text-slate-400 text-xs">Belum ada foto KTP</p>
                        </div>
                    @endif
                    <label for="pic_id_card"
                        class="w-full inline-flex items-center justify-center gap-2 text-xs text-teal-600 hover:bg-teal-50 px-3 py-2 rounded-lg transition-all cursor-pointer font-medium border border-teal-200">
                        <i class="fa-solid fa-upload text-[10px]"></i> Upload Foto KTP
                    </label>
                    <input type="file" id="pic_id_card" name="pic_id_card" accept="image/*,application/pdf"
                        class="hidden">
                    <p class="text-slate-400 text-[11px] mt-2 text-center">JPG, PNG, PDF · maks. 5MB</p>
                </div>

            </div>

            {{-- ========== Kolom Kanan: Form ========== --}}
            <div class="lg:col-span-2 flex flex-col gap-5">

                {{-- Informasi Organisasi --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h2 class="font-display font-bold text-slate-800">Informasi Organisasi</h2>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Nama Organisasi --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Nama Organisasi <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="organization_name"
                                value="{{ old('organization_name', $profile?->organization_name) }}"
                                placeholder="PT. Maju Bersama / Yayasan Peduli Pendidikan"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent @error('organization_name') border-red-400 @enderror">
                            @error('organization_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Tipe Organisasi --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Tipe Organisasi <span class="text-red-500">*</span>
                            </label>
                            <select name="organization_type"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent @error('organization_type') border-red-400 @enderror">
                                <option value="">-- Pilih Tipe --</option>
                                @foreach (['perusahaan' => 'Perusahaan', 'yayasan' => 'Yayasan', 'pemerintah' => 'Pemerintah', 'perguruan_tinggi' => 'Perguruan Tinggi', 'lainnya' => 'Lainnya'] as $val => $label)
                                    <option value="{{ $val }}"
                                        {{ old('organization_type', $profile?->organization_type) === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('organization_type')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- NPWP --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">NPWP</label>
                            <input type="text" name="npwp" value="{{ old('npwp', $profile?->npwp) }}"
                                placeholder="00.000.000.0-000.000"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
                        </div>

                        {{-- Nomor Telepon --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Nomor Telepon <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="phone" value="{{ old('phone', $profile?->phone) }}"
                                placeholder="021-xxxx-xxxx"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent @error('phone') border-red-400 @enderror">
                            @error('phone')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Website --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Website</label>
                            <input type="url" name="website" value="{{ old('website', $profile?->website) }}"
                                placeholder="https://organisasi.co.id"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
                        </div>

                        {{-- Alamat --}}
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Alamat <span class="text-red-500">*</span>
                            </label>
                            <textarea name="address" rows="3" placeholder="Jl. Sudirman No. 1, Jakarta Pusat, DKI Jakarta 10220"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent resize-none @error('address') border-red-400 @enderror">{{ old('address', $profile?->address) }}</textarea>
                            @error('address')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Data Penanggung Jawab (PIC) --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h2 class="font-display font-bold text-slate-800">Penanggung Jawab (PIC)</h2>
                        <p class="text-slate-400 text-xs mt-0.5">Data ini digunakan untuk keperluan verifikasi oleh admin.
                        </p>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">

                        {{-- Nama PIC --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Nama PIC <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pic_name" value="{{ old('pic_name', $profile?->pic_name) }}"
                                placeholder="Nama lengkap penanggung jawab"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent @error('pic_name') border-red-400 @enderror">
                            @error('pic_name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Telepon PIC --}}
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                                Nomor HP PIC <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="pic_phone" value="{{ old('pic_phone', $profile?->pic_phone) }}"
                                placeholder="08xx-xxxx-xxxx"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent @error('pic_phone') border-red-400 @enderror">
                            @error('pic_phone')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- Akun --}}
                <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100">
                        <h2 class="font-display font-bold text-slate-800">Informasi Akun</h2>
                    </div>
                    <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm text-slate-700 focus:outline-none focus:ring-2 focus:ring-teal-400 focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email</label>
                            <input type="email" value="{{ auth()->user()->email }}" disabled
                                class="w-full border border-slate-100 rounded-xl px-4 py-2.5 text-sm text-slate-400 bg-slate-50 cursor-not-allowed">
                            <p class="text-[11px] text-slate-400 mt-1">Email tidak dapat diubah.</p>
                        </div>
                    </div>
                </div>

                {{-- Submit --}}
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('penyalur.dashboard') }}"
                        class="px-5 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors shadow-sm shadow-teal-200">
                        <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
                    </button>
                </div>

            </div>
        </div>
    </form>

@endsection

@section('script')
    <script>
        function previewLogo(input) {
            if (!input.files || !input.files[0]) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                const existing = document.querySelector('.w-24.h-24');
                if (existing.tagName === 'IMG') {
                    existing.src = e.target.result;
                } else {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.className = 'w-24 h-24 rounded-2xl object-cover border-2 border-slate-200';
                    img.alt = 'Logo Preview';
                    existing.replaceWith(img);
                }
            };
            reader.readAsDataURL(input.files[0]);
        }
    </script>
@endsection
