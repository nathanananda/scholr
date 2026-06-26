@extends('penyalur.layout.layout')

@section('content')
    <div class="py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Alert Validasi --}}
            @if ($errors->any())
                <div class="mb-6 flex gap-3 items-start bg-red-50 border border-red-200 text-red-700 rounded-2xl px-5 py-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mt-0.5 shrink-0 text-red-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                    <div>
                        <p class="font-medium text-sm">Terdapat beberapa kesalahan:</p>
                        <ul class="mt-1.5 text-sm list-disc list-inside space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('penyalur.beasiswa.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- ── Card: Informasi Dasar ─────────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-indigo-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-indigo-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-700">Informasi Dasar</h3>
                        </div>
                    </div>

                    <div class="px-6 py-5 space-y-5">

                        {{-- Nama Beasiswa --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Nama Beasiswa <span class="text-red-500">*</span>
                            </label>
                            <input type="text" id="name" name="name" value="{{ old('name') }}" maxlength="150"
                                placeholder="Contoh: Beasiswa Unggulan 2025"
                                class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 placeholder-gray-400 transition-all duration-200
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                    @error('name') border-red-400 bg-red-50 focus:ring-red-400/30 focus:border-red-400 @else border-gray-300 bg-white hover:border-gray-400 @enderror" />
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Kategori & Jenjang Pendidikan --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Kategori <span class="text-red-500">*</span>
                                </label>
                                <select id="category" name="category"
                                    class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 transition-all duration-200 appearance-none cursor-pointer
                                        bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 20 20%22%3E%3Cpath stroke=%22%236b7280%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%221.5%22 d=%22m6 8 4 4 4-4%22/%3E%3C/svg%3E')] bg-no-repeat bg-[right_12px_center] bg-[length:18px]
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                        @error('category') border-red-400 bg-red-50 @else border-gray-300 hover:border-gray-400 @enderror">
                                    <option value="" disabled {{ old('category') ? '' : 'selected' }}>Pilih kategori</option>
                                    @foreach (['Internal', 'Eksternal', 'Prestasi', 'Sosial'] as $cat)
                                        <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="education_level" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Jenjang Pendidikan <span class="text-red-500">*</span>
                                </label>
                                <select id="education_level" name="education_level"
                                    class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 transition-all duration-200 appearance-none cursor-pointer
                                        bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 20 20%22%3E%3Cpath stroke=%22%236b7280%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%221.5%22 d=%22m6 8 4 4 4-4%22/%3E%3C/svg%3E')] bg-no-repeat bg-[right_12px_center] bg-[length:18px]
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                        @error('education_level') border-red-400 bg-red-50 @else border-gray-300 hover:border-gray-400 @enderror">
                                    <option value="" disabled {{ old('education_level') ? '' : 'selected' }}>Pilih jenjang</option>
                                    @php
                                        $levels = [
                                            'all' => 'Semua Jenjang',
                                            'sd'  => 'SD',
                                            'smp' => 'SMP',
                                            'sma' => 'SMA',
                                            'd3'  => 'D3',
                                            's1'  => 'S1',
                                            's2'  => 'S2',
                                            's3'  => 'S3',
                                        ];
                                    @endphp
                                    @foreach ($levels as $val => $label)
                                        <option value="{{ $val }}" {{ old('education_level') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('education_level')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Deskripsi
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <textarea id="description" name="description" rows="4"
                                placeholder="Jelaskan persyaratan, manfaat, dan informasi penting lainnya..."
                                class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 placeholder-gray-400 resize-none transition-all duration-200
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                    @error('description') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- ── Card: Benefit ────────────────────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-700">Informasi Benefit</h3>
                        </div>
                    </div>

                    <div class="px-6 py-5 space-y-5">

                        {{-- Nominal & Periode --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="benefit_amount" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Nominal Dana
                                    <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <div class="relative">
                                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 pointer-events-none select-none">Rp</span>
                                    <input type="number" id="benefit_amount" name="benefit_amount"
                                        value="{{ old('benefit_amount') }}"
                                        min="0" step="1000" placeholder="0"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm text-gray-900 placeholder-gray-400 transition-all duration-200
                                            focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                            @error('benefit_amount') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror" />
                                </div>
                                @error('benefit_amount')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="benefit_period" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Periode Pencairan
                                    <span class="text-gray-400 font-normal">(opsional)</span>
                                </label>
                                <select id="benefit_period" name="benefit_period"
                                    class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 transition-all duration-200 appearance-none cursor-pointer
                                        bg-[url('data:image/svg+xml;charset=utf-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 fill=%22none%22 viewBox=%220 0 20 20%22%3E%3Cpath stroke=%22%236b7280%22 stroke-linecap=%22round%22 stroke-linejoin=%22round%22 stroke-width=%221.5%22 d=%22m6 8 4 4 4-4%22/%3E%3C/svg%3E')] bg-no-repeat bg-[right_12px_center] bg-[length:18px]
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                        @error('benefit_period') border-red-400 bg-red-50 @else border-gray-300 hover:border-gray-400 @enderror">
                                    <option value="">Pilih periode</option>
                                    @php
                                        $periods = [
                                            'monthly'      => 'Per Bulan',
                                            'per_semester' => 'Per Semester',
                                            'yearly'       => 'Per Tahun',
                                            'once'         => 'Sekali Cair',
                                        ];
                                    @endphp
                                    @foreach ($periods as $val => $label)
                                        <option value="{{ $val }}" {{ old('benefit_period') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('benefit_period')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Preview nominal --}}
                        <div id="benefit-preview" class="hidden">
                            <div class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 border border-emerald-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75" />
                                </svg>
                                <p class="text-sm text-emerald-700">
                                    Penerima akan mendapatkan
                                    <span id="preview-text" class="font-semibold"></span>
                                </p>
                            </div>
                        </div>

                        {{-- Keterangan Benefit --}}
                        <div>
                            <label for="benefit_detail" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Keterangan Benefit
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <textarea id="benefit_detail" name="benefit_detail" rows="3"
                                placeholder="Contoh: Mencakup biaya UKT + biaya hidup Rp 500.000/bulan..."
                                class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 placeholder-gray-400 resize-none transition-all duration-200
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                    @error('benefit_detail') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror">{{ old('benefit_detail') }}</textarea>
                            @error('benefit_detail')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- ── Card: Kuota & Jadwal ──────────────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-blue-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-blue-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-700">Kuota & Jadwal</h3>
                        </div>
                    </div>

                    <div class="px-6 py-5 space-y-5">

                        {{-- Kuota --}}
                        <div>
                            <label for="quota" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Kuota Penerima <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="number" id="quota" name="quota" value="{{ old('quota') }}"
                                    min="1" placeholder="Contoh: 50"
                                    class="w-full pl-4 pr-16 py-2.5 rounded-xl border text-sm text-gray-900 placeholder-gray-400 transition-all duration-200
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                        @error('quota') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror" />
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">orang</span>
                            </div>
                            @error('quota')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- Tanggal Mulai & Selesai --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tanggal Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date') }}"
                                    class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 transition-all duration-200 cursor-pointer
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                        @error('start_date') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror" />
                                @error('start_date')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tanggal Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}"
                                    class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 transition-all duration-200 cursor-pointer
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                        @error('end_date') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror" />
                                @error('end_date')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Tanggal Pengumuman --}}
                        <div>
                            <label for="announcement_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                                Rencana Tanggal Pengumuman
                                <span class="text-gray-400 font-normal">(opsional)</span>
                            </label>
                            <input type="date" id="announcement_date" name="announcement_date"
                                value="{{ old('announcement_date') }}"
                                class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 transition-all duration-200 cursor-pointer
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                    @error('announcement_date') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror" />
                            <p class="mt-1.5 text-xs text-gray-400">Tanggal ini akan ditampilkan ke pelamar sebagai estimasi pengumuman hasil seleksi.</p>
                            @error('announcement_date')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </div>
                
                {{-- Tombol Aksi --}}
                <div class="flex items-center justify-end gap-3 pt-1 pb-4">
                    <a href="{{ route('penyalur.beasiswa') }}"
                        class="px-5 py-2.5 rounded-xl border border-gray-300 text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 hover:border-gray-400 transition-all duration-200">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-medium transition-all duration-200 shadow-sm hover:shadow-md">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Simpan Beasiswa
                    </button>
                </div>

            </form>
        </div>
    </div>
@endsection

@section('script')
<script>
    // ── Status radio cards ────────────────────────────────────────────────────
    document.querySelectorAll('.status-card').forEach(card => {
        card.addEventListener('click', function () {
            // Reset semua card
            document.querySelectorAll('.status-card').forEach(c => {
                c.classList.remove(
                    c.dataset.border, c.dataset.bg,
                    'border-emerald-500', 'bg-emerald-50',
                    'border-gray-500',   'bg-gray-50',
                    'border-red-500',    'bg-red-50',
                    'border-purple-500', 'bg-purple-50',
                );
                c.classList.add('border-gray-200', 'bg-white');
                c.querySelector('.status-icon').className  = c.querySelector('.status-icon').className.replace(/text-\S+/g, 'text-gray-400');
                c.querySelector('.status-label').className = c.querySelector('.status-label').className.replace(/text-\S+/g, 'text-gray-600');
                c.querySelector('.status-dot').classList.add('hidden');
            });

            // Aktifkan card yang dipilih
            const border = this.dataset.border;
            const bg     = this.dataset.bg;
            const text   = this.dataset.text;
            const icon   = this.dataset.icon;

            this.classList.remove('border-gray-200', 'bg-white');
            this.classList.add(border, bg);
            this.querySelector('.status-icon').className  = this.querySelector('.status-icon').className.replace(/text-\S+/g, icon);
            this.querySelector('.status-label').className = this.querySelector('.status-label').className.replace(/text-\S+/g, text);

            const dot       = this.querySelector('.status-dot');
            const dotInner  = dot.querySelector('div');
            dot.classList.remove('hidden');
            dot.classList.add(border, bg);
            dotInner.className = dotInner.className.replace(/bg-\S+/g, icon.replace('text-', 'bg-'));

            this.querySelector('input[type="radio"]').checked = true;
        });
    });

    // ── Date validation ───────────────────────────────────────────────────────
    const startDate        = document.getElementById('start_date');
    const endDate          = document.getElementById('end_date');
    const announcementDate = document.getElementById('announcement_date');

    startDate.addEventListener('change', function () {
        if (endDate.value && endDate.value < this.value) endDate.value = this.value;
        endDate.min = this.value;
        if (announcementDate.value && announcementDate.value < this.value) announcementDate.value = '';
        announcementDate.min = this.value;
    });

    endDate.addEventListener('change', function () {
        if (announcementDate.value && announcementDate.value < this.value) announcementDate.value = this.value;
        announcementDate.min = this.value;
    });

    // ── Benefit preview ───────────────────────────────────────────────────────
    const amountInput  = document.getElementById('benefit_amount');
    const periodSelect = document.getElementById('benefit_period');
    const preview      = document.getElementById('benefit-preview');
    const previewText  = document.getElementById('preview-text');

    const periodLabels = {
        monthly:      'per bulan',
        per_semester: 'per semester',
        yearly:       'per tahun',
        once:         '(sekali cair)',
    };

    function updatePreview() {
        const amount = amountInput.value;
        const period = periodSelect.value;

        if (amount && parseInt(amount) > 0 && period) {
            const formatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
            previewText.textContent = `${formatted} ${periodLabels[period]}`;
            preview.classList.remove('hidden');
        } else {
            preview.classList.add('hidden');
        }
    }

    amountInput.addEventListener('input', updatePreview);
    periodSelect.addEventListener('change', updatePreview);

    // Trigger on load jika ada old value
    updatePreview();
</script>
@endsection
