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

            <form action="{{ route('penyalur.beasiswa.update', $scholarship->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

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
                            <input type="text" id="name" name="name"
                                value="{{ old('name', $scholarship->name) }}" maxlength="150"
                                placeholder="Contoh: Beasiswa Unggulan 2025"
                                class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 placeholder-gray-400 transition-all duration-200
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                    @error('name') border-red-400 bg-red-50 focus:ring-red-400/30 focus:border-red-400 @else border-gray-300 bg-white hover:border-gray-400 @enderror" />
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
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
                                    @foreach (['Internal', 'Eksternal', 'Prestasi', 'Sosial'] as $cat)
                                        <option value="{{ $cat }}"
                                            {{ old('category', $scholarship->category) === $cat ? 'selected' : '' }}>
                                            {{ $cat }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
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
                                    @php
                                        $levels = [
                                            'all' => 'Semua Jenjang',
                                            'sd' => 'SD',
                                            'smp' => 'SMP',
                                            'sma' => 'SMA',
                                            'd3' => 'D3',
                                            's1' => 'S1',
                                            's2' => 'S2',
                                            's3' => 'S3',
                                        ];
                                    @endphp
                                    @foreach ($levels as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('education_level', $scholarship->education_level) === $val ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('education_level')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
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
                                    @error('description') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror">{{ old('description', $scholarship->description) }}</textarea>
                            @error('description')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
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
                                    <span
                                        class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-gray-400 pointer-events-none select-none">Rp</span>
                                    <input type="number" id="benefit_amount" name="benefit_amount"
                                        value="{{ old('benefit_amount', $scholarship->benefit_amount) }}" min="0"
                                        step="1000" placeholder="0"
                                        class="w-full pl-10 pr-4 py-2.5 rounded-xl border text-sm text-gray-900 placeholder-gray-400 transition-all duration-200
                                            focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                            @error('benefit_amount') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror" />
                                </div>
                                @error('benefit_amount')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
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
                                            'monthly' => 'Per Bulan',
                                            'per_semester' => 'Per Semester',
                                            'yearly' => 'Per Tahun',
                                            'once' => 'Sekali Cair',
                                        ];
                                    @endphp
                                    @foreach ($periods as $val => $label)
                                        <option value="{{ $val }}"
                                            {{ old('benefit_period', $scholarship->benefit_period) === $val ? 'selected' : '' }}>
                                            {{ $label }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('benefit_period')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>

                        {{-- Preview nominal --}}
                        <div id="benefit-preview"
                            class="{{ old('benefit_amount', $scholarship->benefit_amount) && old('benefit_period', $scholarship->benefit_period) ? '' : 'hidden' }}">
                            <div
                                class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-50 border border-emerald-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-500 shrink-0"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75" />
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
                                    @error('benefit_detail') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror">{{ old('benefit_detail', $scholarship->benefit_detail) }}</textarea>
                            @error('benefit_detail')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
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
                                <input type="number" id="quota" name="quota"
                                    value="{{ old('quota', $scholarship->quota) }}" min="1"
                                    placeholder="Contoh: 50"
                                    class="w-full pl-4 pr-16 py-2.5 rounded-xl border text-sm text-gray-900 placeholder-gray-400 transition-all duration-200
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                        @error('quota') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror" />
                                <span
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">orang</span>
                            </div>
                            @error('quota')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
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
                                <input type="date" id="start_date" name="start_date"
                                    value="{{ old('start_date', \Carbon\Carbon::parse($scholarship->start_date)->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 transition-all duration-200 cursor-pointer
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                        @error('start_date') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror" />
                                @error('start_date')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                                    Tanggal Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" id="end_date" name="end_date"
                                    value="{{ old('end_date', \Carbon\Carbon::parse($scholarship->end_date)->format('Y-m-d')) }}"
                                    class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 transition-all duration-200 cursor-pointer
                                        focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                        @error('end_date') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror" />
                                @error('end_date')
                                    <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
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
                                value="{{ old('announcement_date', $scholarship->announcement_date ? \Carbon\Carbon::parse($scholarship->announcement_date)->format('Y-m-d') : '') }}"
                                class="w-full px-4 py-2.5 rounded-xl border text-sm text-gray-900 transition-all duration-200 cursor-pointer
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500/30 focus:border-indigo-400
                                    @error('announcement_date') border-red-400 bg-red-50 @else border-gray-300 bg-white hover:border-gray-400 @enderror" />
                            <p class="mt-1.5 text-xs text-gray-400">Tanggal ini akan ditampilkan ke pelamar sebagai
                                estimasi pengumuman hasil seleksi.</p>
                            @error('announcement_date')
                                <p class="mt-1.5 text-xs text-red-500 flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </div>

                {{-- ── Card: Status Publikasi ───────────────────────────────── --}}
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/60">
                        <div class="flex items-center gap-2.5">
                            <div class="w-7 h-7 rounded-lg bg-amber-100 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-amber-600" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.325.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.43.992a6.759 6.759 0 010 .255c-.008.378.137.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-700">Status Publikasi</h3>
                        </div>
                    </div>

                    <div class="px-6 py-5">
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            @php
                                $statuses = [
                                    'Draft' => [
                                        'label' => 'Draft',
                                        'color' => 'gray',
                                        'desc' => 'Belum publish',
                                        'icon' =>
                                            'M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10',
                                    ],
                                    'Aktif' => [
                                        'label' => 'Aktif',
                                        'color' => 'emerald',
                                        'desc' => 'Pendaftaran buka',
                                        'icon' => 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
                                    ],
                                    'Seleksi' => [
                                        'label' => 'Seleksi',
                                        'color' => 'red',
                                        'desc' => 'Pendaftaran ditutup, proses seleksi berjalan',
                                        'icon' =>
                                            'M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636',
                                    ],
                                    'Selesai' => [
                                        'label' => 'Selesai',
                                        'color' => 'purple',
                                        'desc' => 'Hasil final keluar',
                                        'icon' =>
                                            'M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z',
                                    ],
                                ];
                                $colorMap = [
                                    'gray' => [
                                        'border' => 'border-gray-500',
                                        'bg' => 'bg-gray-50',
                                        'text' => 'text-gray-700',
                                        'icon' => 'text-gray-500',
                                    ],
                                    'emerald' => [
                                        'border' => 'border-emerald-500',
                                        'bg' => 'bg-emerald-50',
                                        'text' => 'text-emerald-700',
                                        'icon' => 'text-emerald-500',
                                    ],
                                    'red' => [
                                        'border' => 'border-red-500',
                                        'bg' => 'bg-red-50',
                                        'text' => 'text-red-700',
                                        'icon' => 'text-red-500',
                                    ],
                                    'purple' => [
                                        'border' => 'border-purple-500',
                                        'bg' => 'bg-purple-50',
                                        'text' => 'text-purple-700',
                                        'icon' => 'text-purple-500',
                                    ],
                                ];
                                $currentStatus = old('status', $scholarship->status);
                            @endphp

                            @foreach ($statuses as $value => $meta)
                                @php $c = $colorMap[$meta['color']]; @endphp
                                <label
                                    class="status-card relative flex flex-col items-center gap-2 p-4 rounded-xl border-2 cursor-pointer transition-all duration-200
                                        {{ $currentStatus === $value ? $c['border'] . ' ' . $c['bg'] : 'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50' }}"
                                    data-value="{{ $value }}" data-border="{{ $c['border'] }}"
                                    data-bg="{{ $c['bg'] }}" data-text="{{ $c['text'] }}"
                                    data-icon="{{ $c['icon'] }}">
                                    <input type="radio" name="status" value="{{ $value }}"
                                        {{ $currentStatus === $value ? 'checked' : '' }} class="sr-only" />
                                    <svg xmlns="http://www.w3.org/2000/svg"
                                        class="status-icon w-5 h-5 {{ $currentStatus === $value ? $c['icon'] : 'text-gray-400' }}"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $meta['icon'] }}" />
                                    </svg>
                                    <div class="text-center">
                                        <p
                                            class="status-label text-xs font-semibold {{ $currentStatus === $value ? $c['text'] : 'text-gray-600' }}">
                                            {{ $meta['label'] }}
                                        </p>
                                        <p class="text-[10px] text-gray-400 mt-0.5 leading-tight">{{ $meta['desc'] }}</p>
                                    </div>
                                    @if ($currentStatus === $value)
                                        <div
                                            class="status-dot absolute top-2 right-2 w-4 h-4 rounded-full border {{ $c['border'] }} flex items-center justify-center {{ $c['bg'] }}">
                                            <div
                                                class="w-2 h-2 rounded-full {{ str_replace('text-', 'bg-', $c['icon']) }}">
                                            </div>
                                        </div>
                                    @else
                                        <div
                                            class="status-dot hidden absolute top-2 right-2 w-4 h-4 rounded-full border flex items-center justify-center">
                                            <div class="w-2 h-2 rounded-full"></div>
                                        </div>
                                    @endif
                                </label>
                            @endforeach
                        </div>

                        @error('status')
                            <p class="mt-2 text-xs text-red-500 flex items-center gap-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
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
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.5 3.75V16.5L12 21l-4.5-4.5V3.75m9 0H7.5m9 0h2.25A2.25 2.25 0 0121 6v1.5" />
                        </svg>
                        Simpan Perubahan
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
            card.addEventListener('click', function() {
                // Reset semua card
                document.querySelectorAll('.status-card').forEach(c => {
                    c.classList.remove(
                        c.dataset.border, c.dataset.bg,
                        'border-emerald-500', 'bg-emerald-50',
                        'border-gray-500', 'bg-gray-50',
                        'border-blue-500', 'bg-blue-50',
                        'border-purple-500', 'bg-purple-50',
                    );
                    c.classList.add('border-gray-200', 'bg-white');

                    const icon = c.querySelector('.status-icon');
                    const label = c.querySelector('.status-label');
                    icon.setAttribute('data-active-color', c.dataset.icon);
                    icon.style.color = '';
                    label.style.color = '';
                    icon.classList.remove(
                        'text-gray-500', 'text-emerald-500', 'text-blue-500', 'text-purple-500'
                    );
                    label.classList.remove(
                        'text-gray-700', 'text-emerald-700', 'text-blue-700', 'text-purple-700'
                    );
                    icon.classList.add('text-gray-400');
                    label.classList.add('text-gray-600');

                    c.querySelector('.status-dot').classList.add('hidden');
                });

                // Aktifkan card yang dipilih
                this.classList.remove('border-gray-200', 'bg-white');
                this.classList.add(this.dataset.border, this.dataset.bg);

                const icon = this.querySelector('.status-icon');
                const label = this.querySelector('.status-label');

                icon.classList.remove('text-gray-400');
                label.classList.remove('text-gray-600');
                icon.classList.add(this.dataset.icon);
                label.classList.add(this.dataset.text);

                const dot = this.querySelector('.status-dot');
                const dotInner = dot.querySelector('div');
                dot.classList.remove('hidden');
                dot.classList.add(this.dataset.border, this.dataset.bg);
                dotInner.classList.add(this.dataset.icon.replace('text-', 'bg-'));

                this.querySelector('input[type="radio"]').checked = true;
            });
        });

        // ── Date validation ───────────────────────────────────────────────────────
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const announcementDate = document.getElementById('announcement_date');

        startDate.addEventListener('change', function() {
            if (endDate.value && endDate.value < this.value) endDate.value = this.value;
            endDate.min = this.value;
            if (announcementDate.value && announcementDate.value < this.value) announcementDate.value = '';
            announcementDate.min = this.value;
        });

        endDate.addEventListener('change', function() {
            if (announcementDate.value && announcementDate.value < this.value) announcementDate.value = this.value;
            announcementDate.min = this.value;
        });

        // ── Benefit preview ───────────────────────────────────────────────────────
        const amountInput = document.getElementById('benefit_amount');
        const periodSelect = document.getElementById('benefit_period');
        const preview = document.getElementById('benefit-preview');
        const previewText = document.getElementById('preview-text');

        const periodLabels = {
            monthly: 'per bulan',
            per_semester: 'per semester',
            yearly: 'per tahun',
            once: '(sekali cair)',
        };

        function updatePreview() {
            const amount = amountInput.value;
            const period = periodSelect.value;
            if (amount && parseInt(amount) > 0 && period) {
                const formatted = new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumFractionDigits: 0
                }).format(amount);
                previewText.textContent = `${formatted} ${periodLabels[period]}`;
                preview.classList.remove('hidden');
            } else {
                preview.classList.add('hidden');
            }
        }

        amountInput.addEventListener('input', updatePreview);
        periodSelect.addEventListener('change', updatePreview);
        updatePreview();
    </script>
@endsection
