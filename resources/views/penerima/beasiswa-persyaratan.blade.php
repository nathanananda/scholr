@extends('penerima.layout.layout')

@section('content')
<section class="page" x-data="{ active: 0 }">

    {{-- Tabs per Lamaran --}}
    <div class="flex border-b border-slate-200 mb-5 overflow-x-auto scrollbar-hide -mx-4 px-4">
        @foreach ($applications as $i => $application)
            @php
                $orgName = $application->scholarship->penyalur->penyalurProfile->organization_name
                    ?? $application->scholarship->penyalur->name;
            @endphp
            <button
                @click="active = {{ $i }}"
                :class="active === {{ $i }}
                    ? 'text-teal-800 border-teal-800 font-semibold'
                    : 'text-slate-400 border-transparent hover:text-teal-700'"
                class="px-5 py-2.5 text-sm border-b-2 -mb-px transition-all whitespace-nowrap flex-shrink-0">
                {{ Str::limit($application->scholarship->name, 22) }}
            </button>
        @endforeach

        {{-- Empty state tab --}}
        @if ($applications->isEmpty())
            <span class="px-5 py-2.5 text-sm text-slate-400">Tidak ada lamaran</span>
        @endif
    </div>

    {{-- Empty State --}}
    @if ($applications->isEmpty())
        <div class="flex flex-col items-center justify-center py-16 text-center">
            <span class="material-symbols-outlined text-5xl text-slate-300 mb-3">folder_open</span>
            <p class="text-sm font-semibold text-slate-600">Belum ada lamaran</p>
            <p class="text-xs text-slate-400 mt-1 mb-5">Daftar beasiswa terlebih dahulu</p>
            <a href="{{ route('penerima.beasiswa.index') }}"
               class="text-xs font-semibold text-teal-700 border border-teal-700 px-4 py-2 rounded-lg hover:bg-teal-50 transition-colors">
                Jelajahi Beasiswa
            </a>
        </div>

    @else
        <div>
            @foreach ($applications as $i => $application)
                @php
                    $scholarship   = $application->scholarship;
                    $orgName       = $scholarship->penyalur->penyalurProfile->organization_name
                                     ?? $scholarship->penyalur->name;
                    $allDocs       = $scholarship->documents;
                    $uploadedDocs  = $application->documents->keyBy('scholarship_document_id');

                    $approvedCount = $application->documents->where('status', 'approved')->count();
                    $totalRequired = $allDocs->where('is_required', true)->count();
                    $totalAll      = $allDocs->count();
                    $uploadedCount = $application->documents->count();
                    $progressPct   = $totalAll > 0 ? round(($uploadedCount / $totalAll) * 100) : 0;

                    $statusMap = [
                        'draft'        => ['label' => 'Draft',           'bg' => 'bg-cyan-50',    'text' => 'text-cyan-800',   'dot' => 'bg-cyan-500'],
                        'submitted'    => ['label' => 'Disubmit',        'bg' => 'bg-blue-100',   'text' => 'text-blue-800',   'dot' => 'bg-blue-500'],
                        'under_review' => ['label' => 'Seleksi Dokumen', 'bg' => 'bg-amber-100',  'text' => 'text-amber-800',  'dot' => 'bg-amber-500'],
                        'accepted'     => ['label' => 'Diterima',        'bg' => 'bg-green-100',  'text' => 'text-green-800',  'dot' => 'bg-green-500'],
                        'rejected'     => ['label' => 'Ditolak',         'bg' => 'bg-red-100',    'text' => 'text-red-700',    'dot' => 'bg-red-400'],
                    ];
                    $st = $statusMap[$application->status] ?? $statusMap['draft'];

                    $canReupload = in_array($application->status, ['draft', 'under_review']);
                @endphp

                <div x-show="active === {{ $i }}" x-cloak>

                    {{-- Scholarship Header --}}
                    <div class="bg-white border border-slate-200 rounded-xl p-4 flex items-center gap-3 mb-4">
                        <div class="w-11 h-11 rounded-xl bg-teal-50 overflow-hidden flex items-center justify-center flex-shrink-0 border border-teal-100">
                            @if ($scholarship->penyalur->penyalurProfile->logo)
                                <img src="{{ Storage::url($scholarship->penyalur->penyalurProfile->logo) }}"
                                     class="w-full h-full object-cover" alt="" />
                            @else
                                <span class="font-bold text-teal-900 text-[11px]">
                                    {{ strtoupper(substr($orgName, 0, 2)) }}
                                </span>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-teal-900 text-sm leading-snug truncate">{{ $scholarship->name }}</p>
                            <p class="text-slate-400 text-xs mt-0.5">
                                {{ $orgName }}
                                @if ($scholarship->registration_end)
                                    · Deadline: {{ $scholarship->registration_end->format('d M Y') }}
                                @endif
                            </p>
                        </div>
                        <span class="inline-flex items-center gap-1 {{ $st['bg'] }} {{ $st['text'] }} text-[11px] font-semibold px-2.5 py-1 rounded-full flex-shrink-0">
                            <span class="w-1.5 h-1.5 rounded-full {{ $st['dot'] }} inline-block"></span>
                            {{ $st['label'] }}
                        </span>
                    </div>

                    {{-- Progress Bar --}}
                    <div class="bg-white border border-slate-200 rounded-xl p-4 mb-4">
                        <div class="flex justify-between text-xs mb-2">
                            <span class="text-slate-500">Kelengkapan Dokumen</span>
                            <span class="font-bold text-teal-700">{{ $uploadedCount }} / {{ $totalAll }}</span>
                        </div>
                        <div class="bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-teal-400 h-full rounded-full transition-all duration-500"
                                 style="width: {{ $progressPct }}%"></div>
                        </div>
                        @if ($approvedCount > 0)
                            <p class="text-[10px] text-teal-600 mt-1.5 font-medium">
                                {{ $approvedCount }} dokumen terverifikasi
                            </p>
                        @endif
                    </div>

                    {{-- Rejection notice --}}
                    @if ($application->documents->where('status', 'rejected')->count())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-3 mb-4 flex items-start gap-2">
                            <span class="material-symbols-outlined text-red-500 text-[17px] mt-0.5 flex-shrink-0">warning</span>
                            <p class="text-xs text-red-700 font-medium">
                                {{ $application->documents->where('status', 'rejected')->count() }} dokumen ditolak.
                                Silakan upload ulang sesuai catatan penyalur.
                            </p>
                        </div>
                    @endif

                    {{-- Document List --}}
                    <div class="flex flex-col gap-2.5">
                        @foreach ($allDocs->sortBy('order') as $doc)
                            @php
                                $uploaded = $uploadedDocs->get($doc->id);

                                [$iconBg, $iconColor, $icon, $borderClass] = match(true) {
                                    $uploaded?->status === 'approved'
                                        => ['bg-green-100', 'text-green-700',  'task_alt',    'border-slate-200'],
                                    $uploaded?->status === 'rejected'
                                        => ['bg-red-100',   'text-red-500',    'error',       'border-red-200'],
                                    $uploaded?->status === 'uploaded'
                                        => ['bg-amber-100', 'text-amber-600',  'upload_file', 'border-slate-200'],
                                    default
                                        => ['bg-slate-100', 'text-slate-400',  'upload',      'border-slate-200'],
                                };

                                $badgeMap = [
                                    'approved' => ['bg-green-100 text-green-800', 'Terverifikasi'],
                                    'rejected' => ['bg-red-100 text-red-700',     'Ditolak'],
                                    'uploaded' => ['bg-amber-100 text-amber-800', 'Menunggu Review'],
                                ];
                                [$badgeCls, $badgeLabel] = $badgeMap[$uploaded?->status ?? ''] ?? [null, null];

                                $formats = strtoupper(implode('/', $doc->allowed_formats ?? ['PDF']));
                                $maxMb   = number_format(($doc->max_size_kb ?? 2048) / 1024, 1);
                                $accept  = collect($doc->allowed_formats ?? ['pdf'])
                                    ->map(fn($f) => '.' . strtolower($f))->implode(',');
                            @endphp

                            <div class="bg-white border {{ $borderClass }} rounded-xl px-4 py-3 flex items-center gap-3">
                                {{-- Icon --}}
                                <div class="w-9 h-9 rounded-lg {{ $iconBg }} {{ $iconColor }} flex items-center justify-center shrink-0">
                                    <span class="material-symbols-outlined text-[18px]">{{ $icon }}</span>
                                </div>

                                {{-- Info --}}
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-1.5 flex-wrap">
                                        <p class="text-sm font-semibold text-teal-900">{{ $doc->name }}</p>
                                        @if ($doc->is_required)
                                            <span class="text-[9px] bg-red-100 text-red-700 font-bold px-1.5 py-0.5 rounded-full">Wajib</span>
                                        @endif
                                    </div>

                                    @if ($uploaded)
                                        <p class="text-xs text-slate-400 mt-0.5 truncate">
                                            {{ $uploaded->original_filename }}
                                            · {{ $uploaded->updated_at->diffForHumans() }}
                                        </p>
                                        {{-- Rejection note --}}
                                        @if ($uploaded->status === 'rejected' && $uploaded->rejection_note)
                                            <p class="text-[11px] text-red-600 mt-1 font-medium">
                                                <span class="material-symbols-outlined text-[12px] align-middle">info</span>
                                                {{ $uploaded->rejection_note }}
                                            </p>
                                        @endif
                                    @else
                                        <p class="text-xs text-slate-400 mt-0.5">
                                            Belum diunggah · {{ $formats }}, maks {{ $maxMb }} MB
                                        </p>
                                    @endif
                                </div>

                                {{-- Action / Badge --}}
                                @if ($badgeCls && $uploaded?->status === 'approved')
                                    <span class="{{ $badgeCls }} text-[10px] font-bold px-2.5 py-1 rounded-full flex-shrink-0">
                                        {{ $badgeLabel }}
                                    </span>

                                @elseif ($uploaded?->status === 'uploaded')
                                    <span class="{{ $badgeCls }} text-[10px] font-bold px-2.5 py-1 rounded-full flex-shrink-0 text-center leading-snug">
                                        Menunggu<br>Review
                                    </span>

                                @elseif ($canReupload)
                                    {{-- Upload / Re-upload button --}}
                                    <form action="{{ route('penerima.lamaran.reupload', [$application->id, $doc->id]) }}"
                                          method="POST"
                                          enctype="multipart/form-data"
                                          class="flex-shrink-0"
                                          id="upload-form-{{ $application->id }}-{{ $doc->id }}">
                                        @csrf
                                        @method('PATCH')

                                        <input type="file"
                                               name="document"
                                               id="file-{{ $application->id }}-{{ $doc->id }}"
                                               accept="{{ $accept }}"
                                               class="hidden"
                                               onchange="submitUploadForm({{ $application->id }}, {{ $doc->id }})" />

                                        <button type="button"
                                                onclick="document.getElementById('file-{{ $application->id }}-{{ $doc->id }}').click()"
                                                class="flex items-center gap-1.5 border border-teal-700 text-teal-700 text-[11px] font-semibold px-3 py-1.5 rounded-lg hover:bg-teal-50 transition-colors whitespace-nowrap">
                                            <span class="material-symbols-outlined text-[13px]">upload</span>
                                            {{ $uploaded ? 'Upload Ulang' : 'Unggah' }}
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- Submit / Status CTA --}}
                    <div class="mt-5">
                        @if ($application->status === 'accepted')
                            <div class="w-full py-3 bg-green-100 text-green-800 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">celebration</span>
                                Selamat! Lamaran Diterima
                            </div>

                        @elseif ($application->status === 'rejected')
                            <div class="w-full py-3 bg-red-100 text-red-700 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">close</span>
                                Lamaran Tidak Lolos Seleksi
                            </div>

                        @elseif (in_array($application->status, ['submitted', 'under_review']))
                            <div class="w-full py-3 bg-amber-50 text-amber-800 border border-amber-200 rounded-xl font-bold text-sm flex items-center justify-center gap-2">
                                <span class="material-symbols-outlined text-[16px]">hourglass_empty</span>
                                Sedang Direview Penyalur
                            </div>

                        @else
                            {{-- Draft: bisa submit jika semua dokumen wajib sudah terupload --}}
                            @php
                                $requiredDocIds      = $allDocs->where('is_required', true)->pluck('id');
                                $uploadedDocIds      = $application->documents->pluck('scholarship_document_id');
                                $allRequiredUploaded = $requiredDocIds->diff($uploadedDocIds)->isEmpty();
                            @endphp

                            @if ($allRequiredUploaded)
                                <form action="{{ route('penerima.lamaran.submit', $application->id) }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit"
                                            class="w-full py-3 bg-teal-900 text-white rounded-xl font-bold text-sm flex items-center justify-center gap-2 hover:bg-teal-700 active:scale-[0.98] transition-all">
                                        <span class="material-symbols-outlined text-[16px]">send</span>
                                        Submit Lamaran
                                    </button>
                                </form>
                            @else
                                <button disabled
                                        class="w-full py-3 bg-slate-200 text-slate-400 rounded-xl font-bold text-sm flex items-center justify-center gap-2 cursor-not-allowed">
                                    <span class="material-symbols-outlined text-[16px]">send</span>
                                    Lengkapi semua dokumen untuk submit
                                </button>
                            @endif
                        @endif
                    </div>

                </div>
            @endforeach
        </div>
    @endif

</section>
@endsection

@section('script')
<script>
    function submitUploadForm(appId, docId) {
        const file = document.getElementById(`file-${appId}-${docId}`).files[0];
        if (!file) return;
        document.getElementById(`upload-form-${appId}-${docId}`).submit();
    }
</script>
@endsection
