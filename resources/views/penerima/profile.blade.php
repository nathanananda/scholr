@extends('penerima.layout.layout')

@section('content')
@php
    $profile    = Auth::user()->penerimaProfile;
    $user       = Auth::user();
    $nama       = $user->name;
    $initials   = collect(explode(' ', $nama))->take(2)->map(fn($w) => strtoupper($w[0]))->implode('');

    // Hitung persentase kelengkapan profil
    $fields = [
        $user->name,
        $user->email,
        $profile?->phone,
        $profile?->birth_place,
        $profile?->birth_date,
        $profile?->gender,
        $profile?->address,
        $profile?->education_level,
        $profile?->school_name,
        $profile?->major,
        $profile?->semester,
        $profile?->gpa,
        $profile?->student_id_path,
        $profile?->parent_income,
        $profile?->dependents,
    ];
    $filled     = collect($fields)->filter(fn($v) => !is_null($v) && $v !== '')->count();
    $total      = count($fields);
    $percentage = (int) round(($filled / $total) * 100);

    $educationLevels = ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3'];
    $genderOptions   = ['male' => 'Laki-laki', 'female' => 'Perempuan'];
@endphp

<section class="page pb-10">

    {{-- Errors --}}
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-4 flex items-start gap-2">
            <span class="material-symbols-outlined text-red-500 text-[18px] mt-0.5 flex-shrink-0">error</span>
            <ul class="text-xs text-red-600 space-y-0.5">
                @foreach ($errors->all() as $err)
                    <li>&bull; {{ $err }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('success'))
        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-green-600 text-[18px]">check_circle</span>
            <p class="text-xs text-green-700 font-medium">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Profile Card --}}
    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4 mb-5">
        {{-- Avatar --}}
        <div class="w-16 h-16 rounded-full bg-teal-400 flex items-center justify-center font-display font-bold text-white text-xl shrink-0 select-none">
            {{ $initials }}
        </div>

        {{-- Info --}}
        <div class="flex-1 min-w-0">
            <p class="font-display text-base font-bold text-teal-900 truncate">{{ $nama }}</p>
            <p class="text-slate-400 text-xs mt-0.5 truncate">{{ $user->email }}</p>
            <div class="flex flex-wrap gap-1.5 mt-2">
                @if ($profile?->school_name)
                    <span class="text-[11px] font-medium px-2.5 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-100 truncate max-w-[120px]">
                        {{ $profile->school_name }}
                    </span>
                @endif
                @if ($profile?->major)
                    <span class="text-[11px] font-medium px-2.5 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-100">
                        {{ $profile->major }}
                    </span>
                @endif
                @if ($profile?->semester)
                    <span class="text-[11px] font-medium px-2.5 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-100">
                        Semester {{ $profile->semester }}
                    </span>
                @endif
                @if ($profile?->gpa)
                    <span class="text-[11px] font-medium px-2.5 py-1 rounded-full bg-teal-50 text-teal-800 border border-teal-100">
                        IPK {{ number_format($profile->gpa, 2) }}
                    </span>
                @endif
            </div>
        </div>

        {{-- Completeness --}}
        <div class="text-center shrink-0">
            <p class="font-display text-3xl font-extrabold {{ $percentage >= 80 ? 'text-teal-600' : ($percentage >= 50 ? 'text-amber-500' : 'text-red-400') }}">
                {{ $percentage }}%
            </p>
            <p class="text-slate-400 text-[10px] mt-1">Profil lengkap</p>
        </div>
    </div>

    {{-- Form --}}
    <form action="{{ route('penerima.profile.update') }}"
          method="POST"
          enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="bg-white border border-slate-200 rounded-2xl p-5 space-y-6">

            {{-- ===== DATA PRIBADI ===== --}}
            <div>
                <p class="font-display text-sm font-bold text-teal-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-teal-600">person</span>
                    Data Pribadi
                </p>
                <div class="grid grid-cols-2 gap-3">

                    {{-- Nama Lengkap (dari tabel users) --}}
                    <div class="flex flex-col gap-1.5 col-span-2">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}"
                               placeholder="Nama lengkap sesuai KTP"
                               class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                    </div>

                    {{-- Nomor HP --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Nomor HP</label>
                        <input type="tel" name="phone" value="{{ old('phone', $profile?->phone) }}"
                               placeholder="08xx-xxxx-xxxx"
                               class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                    </div>

                    {{-- Jenis Kelamin --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Jenis Kelamin</label>
                        <select name="gender"
                                class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors">
                            <option value="">-- Pilih --</option>
                            @foreach ($genderOptions as $val => $label)
                                <option value="{{ $val }}"
                                    {{ old('gender', $profile?->gender) === $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tempat Lahir --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Tempat Lahir</label>
                        <input type="text" name="birth_place" value="{{ old('birth_place', $profile?->birth_place) }}"
                               placeholder="Kota kelahiran"
                               class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                    </div>

                    {{-- Tanggal Lahir --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Tanggal Lahir</label>
                        <input type="date" name="birth_date"
                               value="{{ old('birth_date', $profile?->birth_date?->format('Y-m-d')) }}"
                               class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                    </div>

                    {{-- Alamat --}}
                    <div class="flex flex-col gap-1.5 col-span-2">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Alamat</label>
                        <textarea name="address" rows="2"
                                  placeholder="Jl. ... No. ..., Kota, Provinsi"
                                  class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors resize-none">{{ old('address', $profile?->address) }}</textarea>
                    </div>

                </div>
            </div>

            <div class="h-px bg-slate-100"></div>

            {{-- ===== DATA AKADEMIK ===== --}}
            <div>
                <p class="font-display text-sm font-bold text-teal-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-teal-600">school</span>
                    Data Akademik
                </p>
                <div class="grid grid-cols-2 gap-3">

                    {{-- Jenjang Pendidikan --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Jenjang</label>
                        <select name="education_level"
                                class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors">
                            <option value="">-- Pilih --</option>
                            @foreach ($educationLevels as $level)
                                <option value="{{ $level }}"
                                    {{ old('education_level', $profile?->education_level) === $level ? 'selected' : '' }}>
                                    {{ $level }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Semester (hanya relevan untuk D3-S3) --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Semester</label>
                        <select name="semester"
                                class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors">
                            <option value="">-- Pilih --</option>
                            @for ($s = 1; $s <= 14; $s++)
                                <option value="{{ $s }}"
                                    {{ old('semester', $profile?->semester) == $s ? 'selected' : '' }}>
                                    Semester {{ $s }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    {{-- Nama Sekolah/Universitas --}}
                    <div class="flex flex-col gap-1.5 col-span-2">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Nama Sekolah / Universitas</label>
                        <input type="text" name="school_name" value="{{ old('school_name', $profile?->school_name) }}"
                               placeholder="Nama institusi pendidikan"
                               class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                    </div>

                    {{-- Jurusan / Program Studi --}}
                    <div class="flex flex-col gap-1.5 col-span-2">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Jurusan / Program Studi</label>
                        <input type="text" name="major" value="{{ old('major', $profile?->major) }}"
                               placeholder="Contoh: Teknik Informatika"
                               class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                    </div>

                    {{-- IPK --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">IPK</label>
                        <input type="number" name="gpa" step="0.01" min="0" max="4"
                               value="{{ old('gpa', $profile?->gpa) }}"
                               placeholder="0.00 – 4.00"
                               class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                    </div>

                    {{-- Upload KTM / Kartu Pelajar --}}
                    <div class="flex flex-col gap-1.5" x-data="{ fileName: null }">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">KTM / Kartu Pelajar</label>

                        {{-- Existing file --}}
                        @if ($profile?->student_id_path)
                            <div class="flex items-center gap-2 px-3 py-2 rounded-lg border border-green-200 bg-green-50">
                                <span class="material-symbols-outlined text-green-600 text-[15px]">check_circle</span>
                                <span class="text-xs text-green-700 font-medium truncate flex-1">
                                    {{ basename($profile->student_id_path) }}
                                </span>
                                <a href="{{ Storage::url($profile->student_id_path) }}"
                                   target="_blank"
                                   class="text-teal-700 hover:underline text-[11px]">Lihat</a>
                            </div>
                            <p class="text-[10px] text-slate-400 -mt-1">Upload file baru untuk mengganti</p>
                        @endif

                        <label class="flex items-center gap-2 px-3 py-2 rounded-lg border border-dashed border-slate-300 bg-slate-50 cursor-pointer hover:border-teal-400 hover:bg-teal-50 transition-colors">
                            <span class="material-symbols-outlined text-slate-400 text-[17px]">upload_file</span>
                            <span class="text-xs text-slate-500 flex-1" x-text="fileName ?? 'Pilih file (JPG/PNG/PDF, maks 2MB)'"></span>
                            <input type="file" name="student_id_path"
                                   accept=".jpg,.jpeg,.png,.pdf"
                                   class="hidden"
                                   @change="fileName = $event.target.files[0]?.name ?? null" />
                        </label>
                    </div>

                </div>
            </div>

            <div class="h-px bg-slate-100"></div>

            {{-- ===== DATA EKONOMI ===== --}}
            <div>
                <p class="font-display text-sm font-bold text-teal-900 mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px] text-teal-600">account_balance_wallet</span>
                    Data Ekonomi
                </p>
                <div class="grid grid-cols-2 gap-3">

                    {{-- Penghasilan Orang Tua --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Penghasilan Orang Tua / Bulan</label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-xs text-slate-400 font-medium">Rp</span>
                            <input type="number" name="parent_income" min="0"
                                   value="{{ old('parent_income', $profile?->parent_income) }}"
                                   placeholder="0"
                                   class="pl-9 pr-3 py-2 w-full rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                        </div>
                    </div>

                    {{-- Jumlah Tanggungan --}}
                    <div class="flex flex-col gap-1.5">
                        <label class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Jumlah Tanggungan</label>
                        <input type="number" name="dependents" min="0" max="20"
                               value="{{ old('dependents', $profile?->dependents) }}"
                               placeholder="Jumlah orang"
                               class="px-3 py-2 rounded-lg border border-slate-200 bg-slate-50 text-sm text-slate-900 focus:outline-none focus:border-teal-400 focus:bg-white transition-colors" />
                    </div>

                </div>
            </div>

            {{-- Save Button --}}
            <button type="submit"
                    class="w-full flex items-center justify-center gap-2 bg-teal-900 text-white px-5 py-3 rounded-xl font-bold text-sm hover:bg-teal-700 active:scale-[0.98] transition-all">
                <span class="material-symbols-outlined text-[16px]">save</span>
                Simpan Perubahan
            </button>

        </div>
    </form>

</section>
@endsection
