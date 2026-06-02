<?php

namespace App\Http\Controllers\Penyalur;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\ApplicationStatusLog;
use App\Models\Notification;
use App\Models\SawResult;
use App\Models\Scholarships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PelamarBeasiswaPenyalurController extends Controller
{
    public function index()
    {
        $scholarships = Scholarships::where('penyalur_id', Auth::id())
            ->withCount('applications')
            ->whereIn('status', ['Aktif', 'Selesai'])
            ->orderByDesc('created_at')
            ->get();

        return view('penyalur.pelamar.index', compact('scholarships'));
    }

    /**
     * Daftar pelamar untuk beasiswa tertentu.
     */
    public function show($scholarshipId)
    {
        $scholarship = Scholarships::where('penyalur_id', Auth::id())
            ->with([
                'criteria.ranges',
                'documents'
            ])
            ->findOrFail($scholarshipId);

        $applications = Application::where('scholarship_id', $scholarshipId)
            ->with([
                'user.penerimaProfile',
                'documents.template',
                'criteriaValues.criteria',
            ])
            ->latest()
            ->get();

        // Total dokumen wajib untuk beasiswa ini
        $totalRequiredDocuments = $scholarship->documents
            ->where('is_required', true)
            ->count();

        // Hitung statistik dokumen tiap pelamar
        $applications->each(function ($application) use ($totalRequiredDocuments) {

            $approvedCount = $application->documents
                ->where('status', 'approved')
                ->count();

            $rejectedCount = $application->documents
                ->where('status', 'rejected')
                ->count();

            $uploadedCount = $application->documents
                ->where('status', 'uploaded')
                ->count();

            $application->doc_total_required = $totalRequiredDocuments;
            $application->doc_approved = $approvedCount;
            $application->doc_rejected = $rejectedCount;
            $application->doc_uploaded = $uploadedCount;

            // Persentase progress verifikasi dokumen
            $application->doc_progress = $totalRequiredDocuments > 0
                ? round(($approvedCount / $totalRequiredDocuments) * 100)
                : 0;

            // Semua dokumen wajib sudah approved?
            $application->all_documents_approved =
                $approvedCount >= $totalRequiredDocuments &&
                $totalRequiredDocuments > 0;
        });

        return view(
            'penyalur.pelamar.show',
            compact(
                'scholarship',
                'applications'
            )
        );
    }
    /**
     * Detail pelamar: dokumen + nilai kriteria.
     */
    public function detail($scholarshipId, $applicationId)
    {
        $scholarship = Scholarships::where('penyalur_id', Auth::id())
            ->with([
                'criteria.ranges',
                'documents'
            ])
            ->findOrFail($scholarshipId);

        $application = Application::where('scholarship_id', $scholarshipId)
            ->with([
                'user.penerimaProfile',
                'documents.template',
                'criteriaValues.criteria',
            ])
            ->findOrFail($applicationId);

        return view(
            'penyalur.pelamar.detail',
            compact('scholarship', 'application')
        );
    }

    /**
     * Approve dokumen pelamar.
     */
    public function approveDocument(Request $request, $documentId)
    {
        // Load semua relasi yang dibutuhkan sekaligus
        $document = ApplicationDocument::with([
            'template',
            'application.scholarship.documentTemplates',
            'application.documents.template',
        ])->whereHas('application.scholarship', function ($q) {
            $q->where('penyalur_id', Auth::id());
        })->findOrFail($documentId);

        $document->update([
            'status'      => 'approved',
            'review_note' => null,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        // Cek apakah semua dokumen wajib sudah approved
        $application      = $document->application;
        $application = $document->application->fresh(['scholarship.documentTemplates', 'documents.template']);

        $totalRequired = $application->scholarship->documentTemplates
            ->where('is_required', 1)->count();

        $totalApproved = $application->documents
            ->filter(fn($d) => $d->template?->is_required == 1 && $d->status === 'approved')
            ->count();

        if ($totalRequired > 0 && $totalApproved >= $totalRequired) {
            $application->update(['status' => 'under_review']);
        }
        // Notifikasi ke penerima
        Notification::create([
            'user_id' => $application->user_id,
            'type'    => 'document_approved',
            'title'   => 'Dokumen Diverifikasi',
            'body' => 'Dokumen ' . $document->template->name . ' telah diverifikasi.',
            'data'    => json_encode(['application_id' => $document->application_id]),
        ]);

        return back()->with('success', 'Dokumen berhasil diapprove.');
    }
    /**
     * Reject dokumen pelamar.
     */
    public function rejectDocument(Request $request, $documentId)
    {
        $request->validate(['note' => 'required|string|max:500']);

        $document = ApplicationDocument::whereHas('application.scholarship', function ($q) {
            $q->where('penyalur_id', Auth::id());
        })->findOrFail($documentId);

        $document->update([
            'status'      => 'rejected',
            'review_note' => $request->note,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        // Update status lamaran ke under_review jika sebelumnya submitted
        $document->application->update(['status' => 'under_review']);

        // Notifikasi ke penerima
        Notification::create([
            'user_id' => $document->application->user_id,
            'type'    => 'document_rejected',
            'title'   => 'Dokumen Ditolak',
            'body' => 'Dokumen ' . $document->template->name . ' ditolak. Lihat catatan.',
            'data'    => json_encode([
                'application_id' => $document->application_id,
                'note'           => $request->note,
            ]),
        ]);

        return back()->with('success', 'Dokumen berhasil ditolak.');
    }

    /**
     * Jalankan perhitungan SAW untuk semua pelamar beasiswa ini.
     * Business rule: semua lamaran harus berstatus under_review.
     */
    public function runSaw($scholarshipId)
    {
        $scholarship = Scholarships::where('penyalur_id', Auth::id())
            ->with('criteria')
            ->findOrFail($scholarshipId);

        $applications = Application::where('scholarship_id', $scholarshipId)
            ->where('status', 'under_review')
            ->with('criteriaValues')
            ->get();

        if ($applications->isEmpty()) {
            return back()->with('error', 'Tidak ada pelamar berstatus under_review untuk dihitung.');
        }

        // Pastikan semua lamaran sudah under_review (tidak ada yg masih submitted)
        $stillSubmitted = Application::where('scholarship_id', $scholarshipId)
            ->where('status', 'submitted')
            ->exists();

        if ($stillSubmitted) {
            return back()->with('error', 'Masih ada lamaran berstatus submitted. Selesaikan verifikasi dokumen terlebih dahulu.');
        }

        $criteria = $scholarship->criteria;

        // --- Kumpulkan semua nilai mentah per kriteria ---
        // $matrix[application_id][criteria_id] = raw_value
        $matrix = [];
        foreach ($applications as $app) {
            foreach ($app->criteriaValues as $cv) {
                $matrix[$app->id][$cv->criteria_id] = $cv->value;
            }
        }

        // --- Cari max/min per kriteria ---
        $maxVal = [];
        $minVal = [];
        foreach ($criteria as $c) {
            $vals = array_column(array_map(fn($row) => ['v' => $row[$c->id] ?? 0], $matrix), 'v');
            $maxVal[$c->id] = max($vals);
            $minVal[$c->id] = min($vals);
        }

        // --- Hitung normalisasi dan skor ---
        $scores = [];
        DB::transaction(function () use ($applications, $criteria, $matrix, $maxVal, $minVal, $scholarshipId, &$scores) {
            // Hapus hasil SAW lama
            SawResult::whereIn('application_id', $applications->pluck('id'))->delete();

            foreach ($applications as $app) {
                $totalScore = 0;

                foreach ($criteria as $c) {
                    $raw = $matrix[$app->id][$c->id] ?? 0;

                    // Normalisasi
                    if ($c->type === 'Benefit') {
                        $normalized = $maxVal[$c->id] > 0 ? $raw / $maxVal[$c->id] : 0;
                    } else {
                        // cost
                        $normalized = $raw > 0 ? $minVal[$c->id] / $raw : 0;
                    }

                    $weight        = $c->weight / 100; // bobot dalam persen → desimal
                    $weightedScore = $weight * $normalized;
                    $totalScore   += $weightedScore;

                    SawResult::create([
                        'application_id'   => $app->id,
                        'criteria_id'      => $c->id,
                        'raw_value'        => $raw,
                        'normalized_value' => round($normalized, 6),
                        'weight'           => $weight,
                        'weighted_value'   => round($weightedScore, 6), // ← fix: weighted_value
                    ]);
                }

                $scores[$app->id] = round($totalScore, 6);
            }

            // Ranking: urutkan berdasarkan skor tertinggi
            arsort($scores);
            $rank = 1;
            foreach ($scores as $appId => $score) {
                Application::where('id', $appId)->update([
                    'saw_score' => $score,
                    'saw_rank'  => $rank,
                ]);
                $rank++;
            }
        });

        // Notifikasi semua pelamar
        foreach ($applications as $app) {
            Notification::create([
                'user_id' => $app->user_id,
                'type'    => 'saw_calculated',
                'title'   => 'Hasil Seleksi Awal Tersedia',
                'body' => 'Hasil seleksi awal tersedia. Cek rankingmu.',
                'data'    => json_encode(['scholarship_id' => $scholarshipId]),
            ]);
        }

        return redirect()->route('penyalur.pelamar.ranking', $scholarshipId)
            ->with('success', 'Perhitungan SAW berhasil dijalankan.');
    }

    /**
     * Halaman ranking SAW lengkap dengan detail matriks.
     */
    public function ranking($scholarshipId)
    {
        $scholarship = Scholarships::where('penyalur_id', Auth::id())
            ->with('criteria')
            ->findOrFail($scholarshipId);

        $applications = Application::where('scholarship_id', $scholarshipId)
            ->whereNotNull('saw_rank')
            ->with([
                'user.penerimaProfile',
                'sawResults.criteria',
            ])
            ->orderBy('saw_rank')
            ->get();

        if ($applications->isEmpty()) {
            return redirect()->route('penyalur.pelamar.show', $scholarshipId)
                ->with('error', 'Belum ada hasil SAW. Jalankan perhitungan terlebih dahulu.');
        }

        return view('penyalur.pelamar.ranking', compact('scholarship', 'applications'));
    }

    /**
     * Tetapkan penerima beasiswa berdasarkan ranking.
     */
    public function tetapkanPenerima(Request $request, $scholarshipId)
    {
        $request->validate([
            'application_ids'   => 'required|array',
            'application_ids.*' => 'exists:applications,id',
        ]);

        $scholarship = Scholarships::where('penyalur_id', Auth::id())
            ->findOrFail($scholarshipId);

        $selectedIds = $request->application_ids;

        // Validasi kuota
        if (count($selectedIds) > $scholarship->quota) {
            return back()->with('error', 'Jumlah penerima melebihi kuota (' . $scholarship->quota . ').');
        }

        DB::transaction(function () use ($scholarshipId, $selectedIds, $scholarship) {
            $allApplications = Application::where('scholarship_id', $scholarshipId)
                ->whereNotNull('saw_rank')
                ->get();

            foreach ($allApplications as $app) {
                $newStatus = in_array($app->id, $selectedIds) ? 'accepted' : 'rejected';
                $app->update(['status' => $newStatus]);

                // Catat ke status log
                ApplicationStatusLog::create([
                    'application_id' => $app->id,
                    'status'         => $newStatus,
                    'note'           => $newStatus === 'accepted'
                        ? 'Selamat! Kamu ditetapkan sebagai penerima beasiswa ' . $scholarship->name . '.'
                        : 'Maaf, lamaranmu untuk ' . $scholarship->name . ' tidak lolos seleksi akhir.',
                    'changed_by'     => Auth::id(),
                ]);

                // Notifikasi pelamar
                Notification::create([
                    'user_id' => $app->user_id,
                    'type'    => $newStatus === 'accepted' ? 'application_accepted' : 'application_rejected',
                    'title'   => $newStatus === 'accepted' ? 'Selamat! Lamaran Diterima' : 'Lamaran Tidak Lolos',
                    'body'    => $newStatus === 'accepted'
                        ? 'Lamaranmu diterima untuk ' . $scholarship->name . '.'
                        : 'Lamaranmu untuk ' . $scholarship->name . ' tidak lolos seleksi.',
                    'data'    => json_encode(['scholarship_id' => $scholarshipId]),
                ]);
            }


            // Update status beasiswa menjadi completed
            $scholarship->update(['status' => 'Selesai']);
        });

        return redirect()->route('penyalur.pelamar.ranking', $scholarshipId)
            ->with('success', 'Penerima beasiswa berhasil ditetapkan.');
    }
}
