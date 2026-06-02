@extends('penyalur.layout.layout')

@section('content')
    <div class="container mx-auto px-4 py-4">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('penyalur.beasiswa') }}"
                class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-gray-200 bg-white text-gray-500 hover:bg-gray-50 hover:text-gray-700 transition shadow-sm">
                <i class="fa-solid fa-chevron-left text-xs"></i>
            </a>
            <div>
                <h2 class="text-xl font-bold text-gray-800 leading-tight">Setup Beasiswa</h2>
                <p class="text-sm text-gray-500 mt-0.5">
                    <span class="font-medium text-indigo-600">{{ $scholarship->name }}</span>
                </p>
            </div>
        </div>

        {{-- Flash --}}
        @if (session('success'))
            <div
                class="mb-4 flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-5 py-3 text-sm">
                <i class="fa-solid fa-circle-check text-emerald-500"></i> {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div
                class="mb-4 flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-3 text-sm">
                <i class="fa-solid fa-circle-xmark text-red-500"></i> {{ session('error') }}
            </div>
        @endif

        {{-- Tab Navigation --}}
        <div class="flex gap-1 mb-6 bg-gray-100 p-1 rounded-xl w-fit">
            <button type="button" onclick="switchTab('criteria')" id="tab-criteria"
                class="tab-btn px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200 bg-white text-indigo-600 shadow-sm">
                <i class="fa-solid fa-sliders mr-1.5"></i> Kriteria SPK
            </button>
            <button type="button" onclick="switchTab('documents')" id="tab-documents"
                class="tab-btn px-5 py-2 rounded-lg text-sm font-medium transition-all duration-200 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-file-lines mr-1.5"></i> Dokumen Persyaratan
            </button>
        </div>

        {{-- ══════════════════════════════════════════════
             TAB: KRITERIA SPK
        ══════════════════════════════════════════════ --}}
        <div id="panel-criteria">
            <form action="{{ route('penyalur.beasiswa.criteria.store', $scholarship->id) }}" method="POST"
                id="criteriaForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Kiri: Daftar Kriteria --}}
                    <div class="lg:col-span-2 space-y-4">

                        {{-- Total Bobot Indicator --}}
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700">Total Bobot</span>
                                <span id="totalWeightLabel" class="text-sm font-bold text-gray-900">0%</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div id="totalWeightBar"
                                    class="h-2.5 rounded-full transition-all duration-300 bg-indigo-500" style="width: 0%">
                                </div>
                            </div>
                            <p id="totalWeightNote" class="text-xs text-gray-400 mt-1.5">Tambahkan kriteria hingga total
                                bobot mencapai 100%</p>
                        </div>

                        {{-- Container Kriteria --}}
                        <div id="criteriaContainer" class="space-y-4">
                            @forelse ($scholarship->criteria as $index => $criteria)
                                <div class="criteria-card bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
                                    data-index="{{ $index }}">
                                    <div
                                        class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                                        <span class="text-sm font-semibold text-gray-700 criteria-title">
                                            {{ $criteria->name ?: 'Kriteria ' . ($index + 1) }}
                                        </span>
                                        <button type="button" onclick="removeCard(this)"
                                            class="text-rose-400 hover:text-rose-600 transition text-sm">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                    <div class="px-5 py-4 space-y-4">
                                        <input type="hidden" name="criteria[{{ $index }}][id]"
                                            value="{{ $criteria->id }}">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Nama
                                                    Kriteria</label>
                                                <input type="text" name="criteria[{{ $index }}][name]"
                                                    value="{{ $criteria->name }}" placeholder="misal: IPK"
                                                    class="criteria-name w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Bobot
                                                    (%)
                                                </label>
                                                <input type="number" name="criteria[{{ $index }}][weight]"
                                                    value="{{ $criteria->weight }}" min="1" max="100"
                                                    class="weight-input w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Tipe</label>
                                                <select name="criteria[{{ $index }}][type]"
                                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400 bg-white">
                                                    <option value="Benefit"
                                                        {{ $criteria->type === 'Benefit' ? 'selected' : '' }}>Benefit
                                                        (makin tinggi makin baik)</option>
                                                    <option value="Cost"
                                                        {{ $criteria->type === 'Cost' ? 'selected' : '' }}>Cost (makin
                                                        rendah makin baik)</option>
                                                </select>
                                            </div>
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">Cara Input
                                                    Pelamar</label>
                                                <select name="criteria[{{ $index }}][input_type]"
                                                    class="input-type-select w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400 bg-white"
                                                    onchange="toggleRangeSection(this)">
                                                    <option value="number"
                                                        {{ $criteria->input_type === 'number' ? 'selected' : '' }}>Angka
                                                        langsung (misal: IPK)</option>
                                                    <option value="range"
                                                        {{ $criteria->input_type === 'range' ? 'selected' : '' }}>Rentang
                                                        nilai (misal: Penghasilan)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="range-section {{ $criteria->input_type === 'range' ? '' : 'hidden' }}">
                                            <div class="flex items-center justify-between mb-2">
                                                <label class="text-xs font-medium text-gray-600">Definisi Rentang
                                                    Nilai</label>
                                                <button type="button" onclick="addRange(this)"
                                                    class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                                                    <i class="fa-solid fa-plus"></i> Tambah Rentang
                                                </button>
                                            </div>
                                            <div class="ranges-container space-y-2">
                                                @forelse ($criteria->ranges as $rIndex => $range)
                                                    <div class="range-row flex items-center gap-2">
                                                        <input type="hidden"
                                                            name="criteria[{{ $index }}][ranges][{{ $rIndex }}][id]"
                                                            value="{{ $range->id }}">
                                                        <input type="text"
                                                            name="criteria[{{ $index }}][ranges][{{ $rIndex }}][label]"
                                                            value="{{ $range->label }}" placeholder="Label"
                                                            class="flex-1 px-3 py-1.5 rounded-lg border border-gray-300 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
                                                        <input type="number"
                                                            name="criteria[{{ $index }}][ranges][{{ $rIndex }}][min_value]"
                                                            value="{{ $range->min_value }}" placeholder="Min"
                                                            class="w-20 px-3 py-1.5 rounded-lg border border-gray-300 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
                                                        <input type="number"
                                                            name="criteria[{{ $index }}][ranges][{{ $rIndex }}][max_value]"
                                                            value="{{ $range->max_value }}" placeholder="Max"
                                                            class="w-20 px-3 py-1.5 rounded-lg border border-gray-300 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
                                                        <input type="number"
                                                            name="criteria[{{ $index }}][ranges][{{ $rIndex }}][score]"
                                                            value="{{ $range->score }}" placeholder="Skor"
                                                            min="1" max="5"
                                                            class="w-16 px-3 py-1.5 rounded-lg border border-gray-300 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
                                                        <button type="button" onclick="removeRange(this)"
                                                            class="text-rose-400 hover:text-rose-600 transition flex-shrink-0">
                                                            <i class="fa-solid fa-xmark"></i>
                                                        </button>
                                                    </div>
                                                @empty
                                                @endforelse
                                            </div>
                                            <p class="text-xs text-gray-400 mt-2">
                                                <i class="fa-solid fa-circle-info"></i> Skor 1–5. Min/Max kosong = tidak
                                                ada batas.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                            @endforelse
                        </div>

                        <button type="button" onclick="addCriteria()"
                            class="w-full py-3 rounded-xl border-2 border-dashed border-gray-300 hover:border-indigo-400 text-gray-400 hover:text-indigo-600 transition text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fa-solid fa-plus"></i> Tambah Kriteria
                        </button>
                    </div>

                    {{-- Kanan: Preset & Panduan --}}
                    <div class="space-y-4">
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                                <h3 class="text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-wand-magic-sparkles text-indigo-400 mr-1"></i> Preset Kriteria
                                    Umum
                                </h3>
                            </div>
                            <div class="px-5 py-4 space-y-2">
                                @php
                                    $presets = [
                                        [
                                            'name' => 'IPK',
                                            'type' => 'Benefit',
                                            'input_type' => 'number',
                                            'weight' => 30,
                                        ],
                                        [
                                            'name' => 'Penghasilan Orang Tua',
                                            'type' => 'Cost',
                                            'input_type' => 'range',
                                            'weight' => 30,
                                        ],
                                        [
                                            'name' => 'Tanggungan Keluarga',
                                            'type' => 'Benefit',
                                            'input_type' => 'range',
                                            'weight' => 20,
                                        ],
                                        [
                                            'name' => 'Semester',
                                            'type' => 'Benefit',
                                            'input_type' => 'number',
                                            'weight' => 10,
                                        ],
                                        [
                                            'name' => 'Prestasi',
                                            'type' => 'Benefit',
                                            'input_type' => 'range',
                                            'weight' => 10,
                                        ],
                                    ];
                                @endphp
                                @foreach ($presets as $preset)
                                    <button type="button"
                                        onclick="addPreset('{{ $preset['name'] }}', '{{ $preset['type'] }}', '{{ $preset['input_type'] }}', {{ $preset['weight'] }})"
                                        class="w-full text-left px-3 py-2.5 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition text-sm text-gray-700 flex items-center justify-between group">
                                        <span>{{ $preset['name'] }}</span>
                                        <span
                                            class="text-xs text-gray-400 group-hover:text-indigo-400">{{ $preset['type'] }}
                                            &bull; {{ $preset['weight'] }}%</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="bg-amber-50 border border-amber-200 rounded-xl px-5 py-4 text-sm space-y-2">
                            <p class="font-semibold text-amber-700 flex items-center gap-2"><i
                                    class="fa-solid fa-lightbulb"></i> Panduan</p>
                            <ul class="text-xs text-amber-700 space-y-1.5 list-disc list-inside">
                                <li><strong>Benefit</strong> — nilai lebih tinggi = lebih baik</li>
                                <li><strong>Cost</strong> — nilai lebih rendah = lebih baik</li>
                                <li>Total bobot semua kriteria <strong>harus = 100%</strong></li>
                                <li>Skor rentang antara <strong>1 sampai 5</strong></li>
                            </ul>
                        </div>

                        <button type="submit" id="saveBtn"
                            class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm transition shadow-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Kriteria
                        </button>
                        <a href="{{ route('penyalur.beasiswa') }}"
                            class="block w-full py-3 rounded-xl border border-gray-300 text-center text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>

        {{-- ══════════════════════════════════════════════
             TAB: DOKUMEN PERSYARATAN
        ══════════════════════════════════════════════ --}}
        <div id="panel-documents" class="hidden">
            <form action="{{ route('penyalur.beasiswa.documents.store', $scholarship->id) }}" method="POST"
                id="documentsForm">
                @csrf
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                    {{-- Kiri: Daftar Dokumen --}}
                    <div class="lg:col-span-2 space-y-4">

                        {{-- Info jumlah --}}
                        <div
                            class="bg-white rounded-xl border border-gray-200 shadow-sm px-5 py-4 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-lg bg-indigo-50 flex items-center justify-center">
                                    <i class="fa-solid fa-file-lines text-indigo-500"></i>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-700">Dokumen Persyaratan</p>
                                    <p class="text-xs text-gray-400">
                                        {{ $scholarship->documents?->count() ?? 0 }}
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                <span class="flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-rose-400 inline-block"></span> Wajib
                                </span>
                                <span class="flex items-center gap-1">
                                    <span class="w-2 h-2 rounded-full bg-gray-300 inline-block"></span> Opsional
                                </span>
                            </div>
                        </div>

                        {{-- Container Dokumen --}}
                        <div id="documentsContainer" class="space-y-3">
                            @if (empty($scholarship->documents))
                                <div class="text-center text-gray-400 py-10">
                                    <i class="fa-solid fa-file-lines text-4xl mb-3"></i>
                                    <p class="text-sm">Belum ada dokumen persyaratan ditambahkan.</p>
                                </div>
                            @else
                                @forelse ($scholarship->documents as $dIndex => $doc)
                                    <div class="doc-card bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden"
                                        data-index="{{ $dIndex }}">
                                        <div
                                            class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                                            <div class="flex items-center gap-2">
                                                <i class="fa-solid fa-grip-vertical text-gray-300 cursor-grab text-xs"></i>
                                                <span class="text-sm font-semibold text-gray-700 doc-title">
                                                    {{ $doc->name ?: 'Dokumen ' . ($dIndex + 1) }}
                                                </span>
                                                @if ($doc->is_required)
                                                    <span
                                                        class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-rose-100 text-rose-600">Wajib</span>
                                                @else
                                                    <span
                                                        class="px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-500">Opsional</span>
                                                @endif
                                            </div>
                                            <button type="button" onclick="removeDocCard(this)"
                                                class="text-rose-400 hover:text-rose-600 transition text-sm">
                                                <i class="fa-solid fa-trash-can"></i>
                                            </button>
                                        </div>
                                        <div class="px-5 py-4 space-y-4">
                                            <input type="hidden" name="documents[{{ $dIndex }}][id]"
                                                value="{{ $doc->id }}">
                                            <input type="hidden" name="documents[{{ $dIndex }}][order]"
                                                value="{{ $doc->order }}" class="doc-order-input">

                                            {{-- Nama & Wajib --}}
                                            <div class="grid grid-cols-3 gap-4">
                                                <div class="col-span-2">
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">Nama
                                                        Dokumen</label>
                                                    <input type="text" name="documents[{{ $dIndex }}][name]"
                                                        value="{{ $doc->name }}"
                                                        placeholder="misal: Kartu Tanda Mahasiswa"
                                                        class="doc-name w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400"
                                                        oninput="updateDocTitle(this)" />
                                                </div>
                                                <div>
                                                    <label
                                                        class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                                                    <select name="documents[{{ $dIndex }}][is_required]"
                                                        class="is-required-select w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400 bg-white"
                                                        onchange="updateRequiredBadge(this)">
                                                        <option value="1" {{ $doc->is_required ? 'selected' : '' }}>
                                                            Wajib
                                                        </option>
                                                        <option value="0" {{ !$doc->is_required ? 'selected' : '' }}>
                                                            Opsional</option>
                                                    </select>
                                                </div>
                                            </div>

                                            {{-- Format & Ukuran --}}
                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                                        Format File Diizinkan
                                                        <span class="text-gray-400 font-normal">(pisah koma)</span>
                                                    </label>
                                                    <input type="text"
                                                        name="documents[{{ $dIndex }}][allowed_formats]"
                                                        value="{{ implode(',', $document->allowed_types ?? []) }}" placeholder="pdf,jpg,png"
                                                        class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
                                                </div>
                                                <div>
                                                    <label class="block text-xs font-medium text-gray-600 mb-1">
                                                        Ukuran Maksimal
                                                    </label>
                                                    <div class="relative">
                                                        <input type="number"
                                                            name="documents[{{ $dIndex }}][max_size_kb]"
                                                            value="{{ $doc->max_size_kb }}" min="1"
                                                            placeholder="2048"
                                                            class="w-full pl-3 pr-12 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
                                                        <span
                                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">KB</span>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Keterangan --}}
                                            <div>
                                                <label class="block text-xs font-medium text-gray-600 mb-1">
                                                    Keterangan <span class="text-gray-400 font-normal">(opsional)</span>
                                                </label>
                                                <input type="text" name="documents[{{ $dIndex }}][description]"
                                                    value="{{ $doc->description }}"
                                                    placeholder="misal: Scan halaman depan yang masih berlaku"
                                                    class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                @endforelse
                            @endif
                        </div>

                        <button type="button" onclick="addDocument()"
                            class="w-full py-3 rounded-xl border-2 border-dashed border-gray-300 hover:border-indigo-400 text-gray-400 hover:text-indigo-600 transition text-sm font-medium flex items-center justify-center gap-2">
                            <i class="fa-solid fa-plus"></i> Tambah Dokumen
                        </button>
                    </div>

                    {{-- Kanan: Preset & Panduan --}}
                    <div class="space-y-4">

                        {{-- Preset Dokumen Umum --}}
                        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
                            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                                <h3 class="text-sm font-semibold text-gray-700">
                                    <i class="fa-solid fa-wand-magic-sparkles text-indigo-400 mr-1"></i> Preset Dokumen
                                    Umum
                                </h3>
                            </div>
                            <div class="px-5 py-4 space-y-2">
                                @php
                                    $docPresets = [
                                        [
                                            'name' => 'KTM / Kartu Pelajar',
                                            'is_required' => true,
                                            'allowed_formats' => 'pdf,jpg,png',
                                            'max_size_kb' => 2048,
                                        ],
                                        [
                                            'name' => 'Transkrip Nilai',
                                            'is_required' => true,
                                            'allowed_formats' => 'pdf',
                                            'max_size_kb' => 2048,
                                        ],
                                        [
                                            'name' => 'Surat Keterangan Aktif',
                                            'is_required' => true,
                                            'allowed_formats' => 'pdf',
                                            'max_size_kb' => 1024,
                                        ],
                                        [
                                            'name' => 'Surat Keterangan Tidak Mampu',
                                            'is_required' => false,
                                            'allowed_formats' => 'pdf',
                                            'max_size_kb' => 1024,
                                        ],
                                        [
                                            'name' => 'Foto 3x4',
                                            'is_required' => true,
                                            'allowed_formats' => 'jpg,png',
                                            'max_size_kb' => 512,
                                        ],
                                        [
                                            'name' => 'Rekening Bank',
                                            'is_required' => false,
                                            'allowed_formats' => 'pdf,jpg,png',
                                            'max_size_kb' => 1024,
                                        ],
                                        [
                                            'name' => 'Sertifikat Prestasi',
                                            'is_required' => false,
                                            'allowed_formats' => 'pdf,jpg,png',
                                            'max_size_kb' => 2048,
                                        ],
                                    ];
                                @endphp
                                @foreach ($docPresets as $dp)
                                    <button type="button"
                                        onclick="addDocPreset('{{ $dp['name'] }}', {{ $dp['is_required'] ? 'true' : 'false' }}, '{{ $dp['allowed_formats'] }}', {{ $dp['max_size_kb'] }})"
                                        class="w-full text-left px-3 py-2.5 rounded-lg hover:bg-indigo-50 hover:text-indigo-700 transition text-sm text-gray-700 flex items-center justify-between group">
                                        <span>{{ $dp['name'] }}</span>
                                        <span
                                            class="text-[10px] px-1.5 py-0.5 rounded {{ $dp['is_required'] ? 'bg-rose-100 text-rose-500' : 'bg-gray-100 text-gray-400' }}">
                                            {{ $dp['is_required'] ? 'Wajib' : 'Opsional' }}
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        {{-- Panduan --}}
                        <div class="bg-blue-50 border border-blue-200 rounded-xl px-5 py-4 text-sm space-y-2">
                            <p class="font-semibold text-blue-700 flex items-center gap-2"><i
                                    class="fa-solid fa-lightbulb"></i> Panduan</p>
                            <ul class="text-xs text-blue-700 space-y-1.5 list-disc list-inside">
                                <li>Dokumen <strong>Wajib</strong> harus diupload sebelum submit lamaran</li>
                                <li>Format: pisahkan dengan koma, misal <code
                                        class="bg-blue-100 px-1 rounded">pdf,jpg,png</code></li>
                                <li>Ukuran dalam <strong>KB</strong> — 1 MB = 1024 KB</li>
                                <li>Keterangan membantu pelamar memahami dokumen yang diminta</li>
                            </ul>
                        </div>

                        <button type="submit"
                            class="w-full py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-medium text-sm transition shadow-sm flex items-center justify-center gap-2">
                            <i class="fa-solid fa-floppy-disk"></i> Simpan Dokumen
                        </button>
                        <a href="{{ route('penyalur.beasiswa') }}"
                            class="block w-full py-3 rounded-xl border border-gray-300 text-center text-sm font-medium text-gray-600 hover:bg-gray-50 transition">
                            Batal
                        </a>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection

@section('script')
    <script>
        let criteriaIndex = {{ $scholarship->criteria->count() }};
        let documentIndex = {{ $scholarship->documents?->count() ?? 0}};

        // ══════════════════════════════════════════════
        // TAB SWITCHING
        // ══════════════════════════════════════════════
        function switchTab(tab) {
            const panels = ['criteria', 'documents'];
            panels.forEach(p => {
                const panel = document.getElementById('panel-' + p);
                const btn = document.getElementById('tab-' + p);
                if (p === tab) {
                    panel.classList.remove('hidden');
                    btn.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
                    btn.classList.remove('text-gray-500');
                } else {
                    panel.classList.add('hidden');
                    btn.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
                    btn.classList.add('text-gray-500');
                }
            });
        }

        // ══════════════════════════════════════════════
        // KRITERIA
        // ══════════════════════════════════════════════
        function updateWeightBar() {
            let total = 0;
            document.querySelectorAll('.weight-input').forEach(i => total += parseFloat(i.value) || 0);
            const label = document.getElementById('totalWeightLabel');
            const bar = document.getElementById('totalWeightBar');
            const note = document.getElementById('totalWeightNote');
            label.textContent = total + '%';
            bar.style.width = Math.min(total, 100) + '%';
            if (total === 100) {
                bar.className = 'h-2.5 rounded-full transition-all duration-300 bg-emerald-500';
                note.textContent = '✓ Total bobot sudah tepat 100%';
                note.className = 'text-xs text-emerald-600 mt-1.5 font-medium';
            } else if (total > 100) {
                bar.className = 'h-2.5 rounded-full transition-all duration-300 bg-rose-500';
                note.textContent = `⚠ Melebihi 100% (kelebihan ${total - 100}%)`;
                note.className = 'text-xs text-rose-600 mt-1.5 font-medium';
            } else {
                bar.className = 'h-2.5 rounded-full transition-all duration-300 bg-indigo-500';
                note.textContent = `Sisa bobot: ${100 - total}%`;
                note.className = 'text-xs text-gray-400 mt-1.5';
            }
        }

        function toggleRangeSection(select) {
            const section = select.closest('.criteria-card').querySelector('.range-section');
            section.classList.toggle('hidden', select.value !== 'range');
        }

        function buildCriteriaCard(index, name = '', type = 'Benefit', inputType = 'number', weight = '') {
            return `
        <div class="criteria-card bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" data-index="${index}">
            <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                <span class="text-sm font-semibold text-gray-700 criteria-title">${name || 'Kriteria ' + (index + 1)}</span>
                <button type="button" onclick="removeCard(this)" class="text-rose-400 hover:text-rose-600 transition text-sm">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
            <div class="px-5 py-4 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Kriteria</label>
                        <input type="text" name="criteria[${index}][name]" value="${name}" placeholder="misal: IPK"
                            class="criteria-name w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400"
                            oninput="updateCardTitle(this)" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Bobot (%)</label>
                        <input type="number" name="criteria[${index}][weight]" value="${weight}" min="1" max="100"
                            class="weight-input w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400"
                            oninput="updateWeightBar()" />
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Tipe</label>
                        <select name="criteria[${index}][type]"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400 bg-white">
                            <option value="Benefit" ${type === 'Benefit' ? 'selected' : ''}>Benefit (makin tinggi makin baik)</option>
                            <option value="Cost" ${type === 'Cost' ? 'selected' : ''}>Cost (makin rendah makin baik)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Cara Input Pelamar</label>
                        <select name="criteria[${index}][input_type]"
                            class="input-type-select w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400 bg-white"
                            onchange="toggleRangeSection(this)">
                            <option value="number" ${inputType === 'number' ? 'selected' : ''}>Angka langsung (misal: IPK)</option>
                            <option value="range" ${inputType === 'range' ? 'selected' : ''}>Rentang nilai (misal: Penghasilan)</option>
                        </select>
                    </div>
                </div>
                <div class="range-section ${inputType === 'range' ? '' : 'hidden'}">
                    <div class="flex items-center justify-between mb-2">
                        <label class="text-xs font-medium text-gray-600">Definisi Rentang Nilai</label>
                        <button type="button" onclick="addRange(this)"
                            class="text-xs text-indigo-600 hover:text-indigo-800 font-medium flex items-center gap-1">
                            <i class="fa-solid fa-plus"></i> Tambah Rentang
                        </button>
                    </div>
                    <div class="ranges-container space-y-2"></div>
                    <p class="text-xs text-gray-400 mt-2"><i class="fa-solid fa-circle-info"></i> Skor 1–5. Min/Max kosong = tidak ada batas.</p>
                </div>
            </div>
        </div>`;
        }

        function buildRangeRow(criteriaIndex, rangeIndex) {
            return `
        <div class="range-row flex items-center gap-2">
            <input type="text" name="criteria[${criteriaIndex}][ranges][${rangeIndex}][label]"
                placeholder="Label (misal: Kurang dari 1 juta)"
                class="flex-1 px-3 py-1.5 rounded-lg border border-gray-300 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
            <input type="number" name="criteria[${criteriaIndex}][ranges][${rangeIndex}][min_value]"
                placeholder="Min" class="w-20 px-3 py-1.5 rounded-lg border border-gray-300 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
            <input type="number" name="criteria[${criteriaIndex}][ranges][${rangeIndex}][max_value]"
                placeholder="Max" class="w-20 px-3 py-1.5 rounded-lg border border-gray-300 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
            <input type="number" name="criteria[${criteriaIndex}][ranges][${rangeIndex}][score]"
                placeholder="Skor" min="1" max="5"
                class="w-16 px-3 py-1.5 rounded-lg border border-gray-300 text-xs focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
            <button type="button" onclick="removeRange(this)" class="text-rose-400 hover:text-rose-600 transition flex-shrink-0">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>`;
        }

        function addCriteria() {
            document.getElementById('criteriaContainer').insertAdjacentHTML('beforeend', buildCriteriaCard(criteriaIndex));
            criteriaIndex++;
        }

        function addPreset(name, type, inputType, weight) {
            document.getElementById('criteriaContainer').insertAdjacentHTML('beforeend', buildCriteriaCard(criteriaIndex,
                name, type, inputType, weight));
            criteriaIndex++;
            updateWeightBar();
        }

        function removeCard(btn) {
            btn.closest('.criteria-card').remove();
            updateWeightBar();
        }

        function addRange(btn) {
            const card = btn.closest('.criteria-card');
            const container = card.querySelector('.ranges-container');
            const rangeIndex = container.querySelectorAll('.range-row').length;
            container.insertAdjacentHTML('beforeend', buildRangeRow(card.dataset.index, rangeIndex));
        }

        function removeRange(btn) {
            btn.closest('.range-row').remove();
        }

        function updateCardTitle(input) {
            const card = input.closest('.criteria-card');
            card.querySelector('.criteria-title').textContent = input.value || ('Kriteria ' + (parseInt(card.dataset
                .index) + 1));
        }

        document.getElementById('criteriaForm').addEventListener('submit', function(e) {
            let total = 0;
            document.querySelectorAll('.weight-input').forEach(i => total += parseFloat(i.value) || 0);
            if (total !== 100) {
                e.preventDefault();
                alert(`Total bobot harus 100%. Saat ini: ${total}%`);
            }
        });

        // ══════════════════════════════════════════════
        // DOKUMEN
        // ══════════════════════════════════════════════
        function buildDocCard(index, name = '', isRequired = true, allowedFormats = 'pdf,jpg,png', maxSizeKb = 2048) {
            const requiredSelected = isRequired ? 'selected' : '';
            const optionalSelected = !isRequired ? 'selected' : '';
            const badgeClass = isRequired ?
                'px-1.5 py-0.5 rounded text-[10px] font-medium bg-rose-100 text-rose-600' :
                'px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-500';
            const badgeText = isRequired ? 'Wajib' : 'Opsional';

            return `
        <div class="doc-card bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" data-index="${index}">
            <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <i class="fa-solid fa-grip-vertical text-gray-300 cursor-grab text-xs"></i>
                    <span class="text-sm font-semibold text-gray-700 doc-title">${name || 'Dokumen ' + (index + 1)}</span>
                    <span class="doc-badge ${badgeClass}">${badgeText}</span>
                </div>
                <button type="button" onclick="removeDocCard(this)" class="text-rose-400 hover:text-rose-600 transition text-sm">
                    <i class="fa-solid fa-trash-can"></i>
                </button>
            </div>
            <div class="px-5 py-4 space-y-4">
                <input type="hidden" name="documents[${index}][order]" value="${index}" class="doc-order-input">
                <div class="grid grid-cols-3 gap-4">
                    <div class="col-span-2">
                        <label class="block text-xs font-medium text-gray-600 mb-1">Nama Dokumen</label>
                        <input type="text" name="documents[${index}][name]" value="${name}"
                            placeholder="misal: Kartu Tanda Mahasiswa"
                            class="doc-name w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400"
                            oninput="updateDocTitle(this)" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                        <select name="documents[${index}][is_required]"
                            class="is-required-select w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400 bg-white"
                            onchange="updateRequiredBadge(this)">
                            <option value="1" ${requiredSelected}>Wajib</option>
                            <option value="0" ${optionalSelected}>Opsional</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Format File <span class="text-gray-400 font-normal">(pisah koma)</span></label>
                        <input type="text" name="documents[${index}][allowed_formats]"
                            value="${allowedFormats}" placeholder="pdf,jpg,png"
                            class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-600 mb-1">Ukuran Maksimal</label>
                        <div class="relative">
                            <input type="number" name="documents[${index}][max_size_kb]"
                                value="${maxSizeKb}" min="1" placeholder="2048"
                                class="w-full pl-3 pr-12 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
                            <span class="absolute right-3 top-1/2 -translate-y-1/2 text-xs text-gray-400 pointer-events-none">KB</span>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Keterangan <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <input type="text" name="documents[${index}][description]"
                        placeholder="misal: Scan halaman depan yang masih berlaku"
                        class="w-full px-3 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400/30 focus:border-indigo-400" />
                </div>
            </div>
        </div>`;
        }

        function addDocument() {
            document.getElementById('documentsContainer').insertAdjacentHTML('beforeend', buildDocCard(documentIndex));
            documentIndex++;
            updateDocCount();
        }

        function addDocPreset(name, isRequired, allowedFormats, maxSizeKb) {
            document.getElementById('documentsContainer').insertAdjacentHTML('beforeend', buildDocCard(documentIndex, name,
                isRequired, allowedFormats, maxSizeKb));
            documentIndex++;
            updateDocCount();
        }

        function removeDocCard(btn) {
            btn.closest('.doc-card').remove();
            updateDocCount();
        }

        function updateDocTitle(input) {
            const card = input.closest('.doc-card');
            card.querySelector('.doc-title').textContent = input.value || ('Dokumen ' + (parseInt(card.dataset.index) + 1));
        }

        function updateRequiredBadge(select) {
            const card = select.closest('.doc-card');
            const badge = card.querySelector('.doc-badge');
            if (select.value === '1') {
                badge.className = 'doc-badge px-1.5 py-0.5 rounded text-[10px] font-medium bg-rose-100 text-rose-600';
                badge.textContent = 'Wajib';
            } else {
                badge.className = 'doc-badge px-1.5 py-0.5 rounded text-[10px] font-medium bg-gray-100 text-gray-500';
                badge.textContent = 'Opsional';
            }
        }

        function updateDocCount() {
            const count = document.querySelectorAll('.doc-card').length;
            document.getElementById('docCountLabel').textContent = count;
        }

        // ══════════════════════════════════════════════
        // INIT
        // ══════════════════════════════════════════════
        document.addEventListener('DOMContentLoaded', function() {
            updateWeightBar();

            document.querySelectorAll('.criteria-name').forEach(input => {
                input.addEventListener('input', function() {
                    updateCardTitle(this);
                });
            });
            document.querySelectorAll('.weight-input').forEach(input => {
                input.addEventListener('input', updateWeightBar);
            });
        });
    </script>
@endsection
