@extends('penerima.layout.layout')

@section('content')
    <section class="page pb-32">

        {{-- Back --}}
        <a href="{{ route('penerima.beasiswa.show', $scholarship->id) }}"
            class="flex items-center gap-1.5 text-xs text-slate-500 mb-4 hover:text-teal-700 transition-colors -mt-1">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            {{ $scholarship->name }}
        </a>

        {{-- Header --}}
        <div class="mb-6">
            <h1 class="font-display text-xl font-bold text-teal-900 leading-tight">Formulir Lamaran</h1>
            <p class="text-xs text-slate-400 mt-0.5">Isi semua data dan upload dokumen yang diperlukan</p>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-5">
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-red-500 text-[18px] mt-0.5">error</span>
                    <div>
                        <p class="text-sm font-semibold text-red-700 mb-1">Terdapat kesalahan</p>
                        <ul class="text-xs text-red-600 space-y-0.5">
                            @foreach ($errors->all() as $error)
                                <li>&bull; {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('penerima.beasiswa.store', $scholarship->id) }}" method="POST"
            enctype="multipart/form-data" id="apply-form" x-data="applyForm()">

            @csrf

            {{-- ===== SECTION 1: KRITERIA SAW ===== --}}
            @if ($scholarship->criteria->count())
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-6 h-6 rounded-full bg-teal-900 flex items-center justify-center">
                            <span class="text-white text-[11px] font-bold">1</span>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Kriteria Penilaian</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach ($scholarship->criteria as $criterion)
                            <div class="bg-white border border-slate-200 rounded-xl p-4">
                                {{-- Criterion Header --}}
                                <div class="flex items-start justify-between gap-2 mb-3">
                                    <div>
                                        <label class="text-sm font-semibold text-slate-800 block mb-0.5">
                                            {{ $criterion->name }}
                                            <span class="text-red-500">*</span>
                                        </label>
                                        @if ($criterion->description)
                                            <p class="text-xs text-slate-400">{{ $criterion->description }}</p>
                                        @endif
                                    </div>
                                    <span
                                        class="flex-shrink-0 text-[9px] font-bold px-2 py-0.5 rounded-full
                                    {{ $criterion->attribute_type === 'benefit' ? 'bg-green-100 text-green-800' : 'bg-orange-100 text-orange-800' }}">
                                        Bobot {{ $criterion->weight / 1 }}%
                                    </span>
                                </div>

                                {{-- Input: Number --}}
                                @if ($criterion->input_type === 'number')
                                    <input type="number" name="criteria[{{ $criterion->id }}]"
                                        value="{{ old('criteria.' . $criterion->id) }}" step="0.01" min="0"
                                        placeholder="Masukkan nilai {{ $criterion->name }}" required
                                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg
                                           focus:outline-none focus:border-teal-500 focus:ring-1 focus:ring-teal-500
                                           transition-all placeholder:text-slate-300" />

                                    {{-- Input: Range (pilihan) --}}
                                @elseif ($criterion->input_type === 'range')
                                    <div class="space-y-2">
                                        @foreach ($criterion->ranges->sortByDesc('score') as $range)
                                            <label
                                                class="flex items-center gap-3 p-3 border rounded-lg cursor-pointer transition-all
                                                      has-[:checked]:border-teal-500 has-[:checked]:bg-teal-50
                                                      border-slate-200 hover:border-teal-300">
                                                <input type="radio" name="criteria[{{ $criterion->id }}]"
                                                    value="{{ $range->id }}"
                                                    {{ old('criteria.' . $criterion->id) == $range->id ? 'checked' : '' }}
                                                    required class="accent-teal-700 w-4 h-4 flex-shrink-0" />
                                                <div class="flex-1 min-w-0">
                                                    <span class="text-sm text-slate-700 block">{{ $range->label }}</span>
                                                    @if ($range->description)
                                                        <span
                                                            class="text-xs text-slate-400">{{ $range->description }}</span>
                                                    @endif
                                                </div>
                                                <span class="text-xs font-bold text-teal-700 flex-shrink-0">
                                                    Skor {{ $range->score }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ===== SECTION 2: UPLOAD DOKUMEN ===== --}}
            @if ($scholarship->documents->count())
                <div class="mb-6">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="w-6 h-6 rounded-full bg-teal-900 flex items-center justify-center">
                            <span class="text-white text-[11px] font-bold">2</span>
                        </div>
                        <h2 class="text-sm font-bold text-slate-800">Upload Dokumen</h2>
                    </div>

                    <div class="space-y-4">
                        @foreach ($scholarship->documents as $doc)
                            @php
                                $formats = $doc->allowed_formats ?? ['pdf'];
                                $accept = collect($formats)->map(fn($f) => '.' . strtolower($f))->implode(',');
                                $maxMb = number_format(($doc->max_size_kb ?? 2048) / 1024, 1);
                            @endphp

                            <div class="bg-white border border-slate-200 rounded-xl p-4" x-data="fileUpload('doc_{{ $doc->id }}')">

                                {{-- Doc Header --}}
                                <div class="flex items-start gap-2 mb-3">
                                    <div
                                        class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center flex-shrink-0">
                                        <span class="material-symbols-outlined text-teal-700 text-[16px]">description</span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="text-sm font-semibold text-slate-800">{{ $doc->name }}</span>
                                            @if ($doc->is_required)
                                                <span
                                                    class="text-[9px] bg-red-100 text-red-700 font-bold px-1.5 py-0.5 rounded-full">Wajib</span>
                                            @else
                                                <span
                                                    class="text-[9px] bg-slate-100 text-slate-500 font-bold px-1.5 py-0.5 rounded-full">Opsional</span>
                                            @endif
                                        </div>
                                        @if ($doc->description)
                                            <p class="text-xs text-slate-400 mt-0.5">{{ $doc->description }}</p>
                                        @endif
                                        <p class="text-[10px] text-slate-400 mt-0.5">
                                            {{ strtoupper(implode(', ', $formats)) }} &bull; Maks. {{ $maxMb }} MB
                                        </p>
                                    </div>
                                </div>

                                {{-- Drop Zone --}}
                                <div @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                                    @drop.prevent="handleDrop($event, '{{ $accept }}', {{ ($doc->max_size_kb ?? 2048) * 1024 }})"
                                    :class="dragging
                                        ?
                                        'border-teal-400 bg-teal-50' :
                                        (fileName ? 'border-green-400 bg-green-50' : 'border-slate-200 bg-slate-50')"
                                    class="border-2 border-dashed rounded-xl p-4 text-center transition-all">

                                    <input type="file" name="documents[{{ $doc->id }}]"
                                        id="doc_{{ $doc->id }}" accept="{{ $accept }}"
                                        {{ $doc->is_required ? 'required' : '' }}
                                        @change="handleFileSelect($event, '{{ $accept }}', {{ ($doc->max_size_kb ?? 2048) * 1024 }})"
                                        class="absolute opacity-0 w-0 h-0" />

                                    {{-- No file selected --}}
                                    <template x-if="!fileName">
                                        <div>
                                            <span
                                                class="material-symbols-outlined text-slate-300 text-3xl mb-1">upload_file</span>
                                            <p class="text-xs text-slate-500 font-medium">
                                                Drag & drop atau
                                                <button type="button"
                                                    @click="document.getElementById('doc_{{ $doc->id }}').click()"
                                                    class="text-teal-700 underline underline-offset-2">pilih file</button>
                                            </p>
                                        </div>
                                    </template>

                                    {{-- File selected --}}
                                    <template x-if="fileName">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="material-symbols-outlined text-green-600 text-[20px]">check_circle</span>
                                            <div class="flex-1 min-w-0 text-left">
                                                <p class="text-xs font-semibold text-slate-700 truncate" x-text="fileName">
                                                </p>
                                                <p class="text-[10px] text-slate-400" x-text="fileSize"></p>
                                            </div>
                                            <button type="button" @click="clearFile('doc_{{ $doc->id }}')"
                                                class="text-red-400 hover:text-red-600 transition-colors">
                                                <span class="material-symbols-outlined text-[18px]">close</span>
                                            </button>
                                        </div>
                                    </template>

                                    {{-- Error --}}
                                    <p x-show="fileError" x-text="fileError"
                                        class="text-xs text-red-500 mt-1.5 font-medium"></p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ===== SECTION 3: KONFIRMASI ===== --}}
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-amber-600 text-[18px] mt-0.5 flex-shrink-0">info</span>
                    <div>
                        <p class="text-xs font-semibold text-amber-800 mb-1">Perhatian</p>
                        <ul class="text-xs text-amber-700 space-y-1">
                            <li>&bull; Lamaran yang sudah disubmit tidak dapat diedit.</li>
                            <li>&bull; Pastikan semua data dan dokumen sudah benar sebelum mengirim.</li>
                            <li>&bull; Penyalur akan mereview dokumen Anda sebelum perhitungan SAW.</li>
                        </ul>
                    </div>
                </div>
            </div>

            {{-- Konfirmasi Checkbox --}}
            <label class="flex items-start gap-3 mb-6 cursor-pointer">
                <input type="checkbox" name="confirm" required x-model="confirmed"
                    class="accent-teal-700 w-4 h-4 mt-0.5 flex-shrink-0" />
                <span class="text-xs text-slate-600 leading-relaxed">
                    Saya menyatakan bahwa semua informasi yang saya isi adalah <strong>benar dan dapat
                        dipertanggungjawabkan</strong>.
                </span>
            </label>

        </form>

    </section>

    {{-- Sticky Submit --}}
    <div class="sticky bottom-0 bg-white/90 backdrop-blur-sm border-t border-slate-100 p-4 mt-6"
        style="padding-bottom: calc(0.75rem + env(safe-area-inset-bottom))" x-data x-ref="cta">
        <button type="submit" form="apply-form"
            class="w-full flex items-center justify-center gap-2 py-3.5 rounded-xl text-sm font-bold transition-all
               bg-teal-900 text-white hover:bg-teal-700 active:scale-[0.98]
               disabled:bg-slate-200 disabled:text-slate-400 disabled:cursor-not-allowed"
            id="submit-btn">
            <span class="material-symbols-outlined text-[16px]" id="submit-icon">send</span>
            <span id="submit-label">Submit Lamaran</span>
        </button>
    </div>
@endsection

@section('script')
    <script>
        function applyForm() {
            return {
                confirmed: false,
            }
        }

        function fileUpload(inputId) {
            return {
                dragging: false,
                fileName: null,
                fileSize: null,
                fileError: null,

                handleDrop(event, accept, maxBytes) {
                    this.dragging = false;
                    const file = event.dataTransfer.files[0];
                    if (!file) return;
                    this.validateAndSet(file, accept, maxBytes, inputId);
                },

                handleFileSelect(event, accept, maxBytes) {
                    const file = event.target.files[0];
                    if (!file) return;
                    this.validateAndSet(file, accept, maxBytes, inputId);
                },

                validateAndSet(file, accept, maxBytes, id) {
                    this.fileError = null;
                    const ext = '.' + file.name.split('.').pop().toLowerCase();
                    const allowedExts = accept.split(',');

                    if (!allowedExts.includes(ext)) {
                        this.fileError = `Format tidak didukung. Gunakan: ${accept}`;
                        return;
                    }
                    if (file.size > maxBytes) {
                        this.fileError = `Ukuran file melebihi batas (maks. ${(maxBytes / 1024 / 1024).toFixed(1)} MB)`;
                        return;
                    }

                    this.fileName = file.name;
                    this.fileSize = (file.size / 1024).toFixed(0) + ' KB';

                    // Transfer to actual input
                    const input = document.getElementById(id);
                    const dt = new DataTransfer();
                    dt.items.add(file);
                    input.files = dt.files;
                },

                clearFile(id) {
                    this.fileName = null;
                    this.fileSize = null;
                    this.fileError = null;
                    document.getElementById(id).value = '';
                }
            }
        }

        // Submit loading state
        document.getElementById('apply-form').addEventListener('submit', function(e) {
            const btn = document.getElementById('submit-btn');
            const icon = document.getElementById('submit-icon');
            const label = document.getElementById('submit-label');
            btn.disabled = true;
            icon.textContent = 'hourglass_empty';
            label.textContent = 'Mengirim...';
        });
    </script>
@endsection
