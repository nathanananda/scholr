<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationCriteriaValue;
use App\Models\ApplicationDocument;
use App\Models\ApplicationStatusLog;
use App\Models\Scholarships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BeasiswaPenerimaController extends Controller
{
    public function index(Request $request)
    {
        $query = Scholarships::with([
            'penyalur.penyalurProfile',
        ])
            ->where('status', 'Aktif')
            ->orderByDesc('created_at');

        // Filter jenjang pendidikan
        if ($request->filled('jenjang')) {
            $query->whereJsonContains('education_levels', $request->jenjang);
        }

        // Filter sedang dibuka
        if ($request->boolean('open')) {
            $query->where('start_date', '<=', now())
                ->where('end_date', '>=', now());
        }

        // Search by name or organization
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($sub) use ($q) {
                $sub->where('name', 'like', "%{$q}%")
                    ->orWhereHas('penyalur.penyalurProfile', function ($p) use ($q) {
                        $p->where('organization_name', 'like', "%{$q}%");
                    });
            });
        }

        $scholarships = $query->paginate(10)->withQueryString();

        // Beasiswa yang sudah dilamar oleh user ini
        $userApplications = Application::where('user_id', Auth::id())
            ->pluck('scholarship_id');


        return view('penerima.beasiswa.index', compact('scholarships', 'userApplications'));
    }

    public function show(string $slug)
    {
        $scholarship = Scholarships::with([
            'penyalur.penyalurProfile',
            'criteria.ranges',
            'documents',
            'faqs' => fn($q) => $q->orderBy('order'),
        ])
            ->where('id', $slug)
            ->where('status', 'Aktif')
            ->firstOrFail();

        $userApplication = Application::where('user_id', Auth::id())
            ->where('scholarship_id', $scholarship->id)
            ->first();

        return view('penerima.beasiswa.show', compact('scholarship', 'userApplication'));
    }


    /**
     * GET /penerima/beasiswa/{slug}/apply
     * Tampilkan form lamaran.
     */
    public function apply(string $slug)
    {
        $scholarship = Scholarships::with([
            'criteria.ranges',
            'documents',
        ])
            ->where('id', $slug)
            ->where('status', 'Aktif')
            ->firstOrFail();

        // Guard: sudah mendaftar
        $exists = Application::where('user_id', Auth::id())
            ->where('scholarship_id', $scholarship->id)
            ->exists();

        if ($exists) {
            return redirect()->route('penerima.beasiswa.show', $slug)
                ->with('info', 'Kamu sudah mendaftar beasiswa ini.');
        }

        // Guard: pendaftaran ditutup
        $isOpen = $scholarship->start_date <= now()
            && $scholarship->end_date >= now();

        if (!$isOpen) {
            return redirect()->route('penerima.beasiswa.show', $slug)
                ->with('error', 'Pendaftaran beasiswa ini sudah ditutup.');
        }

        return view('penerima.beasiswa.apply', compact('scholarship'));
    }

    /**
     * POST /penerima/beasiswa/{slug}/apply
     * Simpan lamaran (status: submitted).
     */
    public function store(Request $request, string $slug)
    {
        $scholarship = Scholarships::with(['criteria.ranges', 'documents'])
            ->where('id', $slug)
            ->where('status', 'Aktif')
            ->firstOrFail();

        // Guard: sudah mendaftar
        if (Application::where('user_id', Auth::id())
            ->where('scholarship_id', $scholarship->id)
            ->exists()
        ) {
            return redirect()->route('penerima.beasiswa.show', $slug)
                ->with('info', 'Kamu sudah mendaftar beasiswa ini.');
        }

        // ---------- Build validation rules ----------
        $rules    = ['confirm' => 'accepted'];
        $messages = [];

        foreach ($scholarship->criteria as $criterion) {
            $key = "criteria.{$criterion->id}";
            if ($criterion->input_type === 'number') {
                $rules[$key]    = 'required|numeric|min:0';
                $messages[$key . '.required'] = "Nilai kriteria \"{$criterion->name}\" wajib diisi.";
                $messages[$key . '.numeric']  = "Nilai kriteria \"{$criterion->name}\" harus berupa angka.";
            } else {
                // range: value is a criteria_range id
                $validRangeIds = $criterion->ranges->pluck('id')->toArray();
                $rules[$key]   = 'required|in:' . implode(',', $validRangeIds);
                $messages[$key . '.required'] = "Pilihan untuk kriteria \"{$criterion->name}\" wajib dipilih.";
                $messages[$key . '.in']       = "Pilihan kriteria \"{$criterion->name}\" tidak valid.";
            }
        }

        foreach ($scholarship->documents as $doc) {
            $key     = "documents.{$doc->id}";
            $formats = collect($doc->allowed_formats ?? ['pdf'])
                ->map(fn($f) => strtolower($f))
                ->implode(',');
            $maxKb   = $doc->max_size_kb ?? 2048;

            $rules[$key] = ($doc->is_required ? 'required' : 'nullable')
                . "|file|mimetypes:" . $this->formatsToMimes($doc->allowed_formats ?? ['pdf'])
                . "|max:{$maxKb}";

            $messages[$key . '.required']  = "Dokumen \"{$doc->name}\" wajib diupload.";
            $messages[$key . '.max']       = "Ukuran file \"{$doc->name}\" melebihi batas " . number_format($maxKb / 1024, 1) . " MB.";
            $messages[$key . '.mimetypes'] = "Format file \"{$doc->name}\" tidak didukung. Gunakan: " . strtoupper(implode(', ', $doc->allowed_formats ?? ['PDF'])) . ".";
        }

        $request->validate($rules, $messages);

        // ---------- Save inside transaction ----------
        DB::transaction(function () use ($request, $scholarship) {
            // 1. Create application
            $application = Application::create([
                'scholarship_id' => $scholarship->id,
                'user_id'        => Auth::id(),
                'status'         => 'submitted',
                'submitted_at'   => now(),
            ]);

            // 2. Save criteria values
            foreach ($scholarship->criteria as $criterion) {

                $rawValue = $request->input("criteria.{$criterion->id}");

                if ($criterion->input_type === 'range') {

                    $range = $criterion->ranges
                        ->firstWhere('id', $rawValue);

                    ApplicationCriteriaValue::create([
                        'application_id'    => $application->id,
                        'criteria_id'       => $criterion->id,
                        'value'             => $range->score,
                        'criteria_range_id' => $range->id,
                    ]);
                } else {

                    ApplicationCriteriaValue::create([
                        'application_id'    => $application->id,
                        'criteria_id'       => $criterion->id,
                        'value'             => $rawValue,
                        'criteria_range_id' => null,
                    ]);
                }
            }

            // 3. Upload & save documents
            foreach ($scholarship->documents as $doc) {
                $file = $request->file("documents.{$doc->id}");
                if (!$file) continue;

                $path = $file->store(
                    "applications/{$application->id}/documents",
                    'local'
                );

                ApplicationDocument::create([
                    'application_id'           => $application->id,
                    'scholarship_document_id'  => $doc->id,
                    'file_path'                => $path,
                    'original_filename'        => $file->getClientOriginalName(),
                    'file_size_kb'             => (int) ceil($file->getSize() / 1024),
                    'status'                   => 'uploaded',
                ]);
            }

            // 4. Audit log
            ApplicationStatusLog::create([
                'application_id' => $application->id,
                'from_status'    => null,
                'to_status'      => 'submitted',
                'changed_by'     => Auth::id(),
                'note'           => 'Lamaran berhasil disubmit oleh penerima.',
            ]);
        });

        return redirect()->route('penerima.beasiswa')
            ->with('success', 'Lamaran berhasil dikirim! Penyalur akan mereview dokumen Anda.');
    }

     // -------------------------------------------------------
    // Helpers
    // -------------------------------------------------------

    /**
     * Map extension list to MIME types for validation.
     */
    private function formatsToMimes(array $formats): string
    {
        $map = [
            'pdf'  => 'application/pdf',
            'jpg'  => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png'  => 'image/png',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        ];

        return collect($formats)
            ->map(fn($f) => $map[strtolower($f)] ?? null)
            ->filter()
            ->unique()
            ->implode(',');
    }

    public function beasiswaPersyaratan()
    {
        return view('penerima.beasiswa-persyaratan');
    }
}
