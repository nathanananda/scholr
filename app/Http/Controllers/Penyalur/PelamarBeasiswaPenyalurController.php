<?php

namespace App\Http\Controllers\Penyalur;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\ApplicationStatusLog;
use App\Models\Notification;
use App\Models\SawResult;
use App\Models\Scholarships;
use App\Models\SmartResult;
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
     * Jalankan perhitungan SAW + SMART sekaligus.
     *
     * Fungsi ini adalah "otak" dari sistem seleksi beasiswa. Dia ambil semua
     * pelamar yang sudah lolos verifikasi dokumen (status: under_review),
     * lalu hitung skor masing-masing pakai dua metode sekaligus: SAW dan SMART.
     * Hasilnya disimpan ke database dan pelamar dapat notifikasi otomatis.
     *
     * Flow besarnya:
     *   1. Ambil data beasiswa + kriterianya
     *   2. Validasi: semua dokumen harus sudah diverifikasi
     *   3. Bangun matrix nilai mentah [pelamar][kriteria]
     *   4. Hitung MAX & MIN tiap kriteria (dipakai kedua metode)
     *   5. Hitung skor SAW  → normalisasi rasio → kalikan bobot → jumlahkan
     *   6. Hitung skor SMART → normalisasi min-max → kalikan bobot → jumlahkan
     *   7. Ranking kedua metode, simpan ke tabel applications
     *   8. Kirim notifikasi ke semua pelamar
     *
     * @param  int  $scholarshipId  ID beasiswa yang mau dihitung
     * @return \Illuminate\Http\RedirectResponse
     */
    public function runSpk($scholarshipId)
    {
        // Ambil beasiswa milik penyalur yang sedang login, sekalian load kriterianya.
        // Kalau bukan punya dia, otomatis 404 — biar aman.
        $scholarship = Scholarships::where('penyalur_id', Auth::id())
            ->with('criteria')
            ->findOrFail($scholarshipId);

        // Cek dulu: masih ada nggak pelamar yang statusnya 'submitted' (belum diverifikasi)?
        // Kalau masih ada, perhitungan ditolak — nggak fair kalau ada yang belum dicek dokumennya.
        $stillSubmitted = Application::where('scholarship_id', $scholarshipId)
            ->where('status', 'submitted')
            ->exists();

        if ($stillSubmitted) {
            return back()->with('error', 'Masih ada lamaran berstatus submitted. Selesaikan verifikasi dokumen terlebih dahulu.');
        }

        // Ambil semua pelamar yang siap dihitung (status: under_review),
        // sekalian load nilai kriteria masing-masing.
        $applications = Application::where('scholarship_id', $scholarshipId)
            ->where('status', 'under_review')
            ->with('criteriaValues')
            ->get();

        // Kalau kosong, ya nggak ada yang bisa dihitung — balik dengan pesan error.
        if ($applications->isEmpty()) {
            return back()->with('error', 'Tidak ada pelamar berstatus under_review untuk dihitung.');
        }

        // SAW & SMART butuh minimal 2 alternatif untuk dibandingkan.
        // Kalau cuma 1 orang, normalisasi tidak bermakna (MAX = MIN = nilai itu sendiri).
        if ($applications->count() < 2) {
            return back()->with('error', 'Minimal 2 pelamar diperlukan untuk menjalankan perhitungan SPK. Saat ini baru ada ' . $applications->count() . ' pelamar yang berstatus under_review.');
        }

        $criteria = $scholarship->criteria;

        // --- Bangun matrix nilai mentah: [app_id][criteria_id] = raw_value ---
        // Ini struktur data utama yang jadi acuan semua perhitungan di bawah.
        // Bentuknya array 2 dimensi: baris = pelamar, kolom = kriteria.
        $matrix = [];
        foreach ($applications as $app) {
            foreach ($app->criteriaValues as $cv) {
                $matrix[$app->id][$cv->criteria_id] = (float) $cv->value;
            }
        }

        // --- Hitung MAX dan MIN per kriteria ---
        // Kedua nilai ini dipakai oleh SAW maupun SMART, jadi dihitung sekali di sini
        // supaya nggak perlu hitung ulang di dalam loop pelamar nanti.
        $maxVal = [];
        $minVal = [];
        foreach ($criteria as $c) {
            $vals = [];
            foreach ($applications as $app) {
                $vals[] = $matrix[$app->id][$c->id] ?? 0;
            }
            $maxVal[$c->id] = max($vals);
            $minVal[$c->id] = min($vals);
        }

        // Penampung skor akhir — diisi di dalam transaksi, dipakai di luar untuk ranking.
        $sawScores   = [];
        $smartScores = [];

        // Semua operasi tulis ke database dibungkus dalam satu transaksi.
        // Kalau ada yang gagal di tengah jalan, semua otomatis di-rollback — data tetap bersih.
        DB::transaction(function () use (
            $applications,
            $criteria,
            $matrix,
            $maxVal,
            $minVal,
            $scholarshipId,
            &$sawScores,   // pass by reference supaya nilai bisa dibawa keluar transaksi
            &$smartScores
        ) {
            $appIds = $applications->pluck('id');

            // Hapus hasil perhitungan lama kalau ada.
            // Ini penting supaya nggak dobel kalau penyalur jalankan ulang perhitungan.
            SawResult::whereIn('application_id', $appIds)->delete();
            SmartResult::whereIn('application_id', $appIds)->delete();

            foreach ($applications as $app) {
                $totalSaw   = 0;
                $totalSmart = 0;

                foreach ($criteria as $c) {
                    $raw    = $matrix[$app->id][$c->id] ?? 0;
                    $max    = $maxVal[$c->id];
                    $min    = $minVal[$c->id];

                    // Bobot disimpan dalam persen (misal: 30), dikonversi ke desimal (0.3)
                    $weight = $c->weight / 100;

                    // ── SAW: Normalisasi Rasio ───────────────────────────────────────
                    // Benefit → nilai / MAX  (makin besar makin baik, skor mendekati 1)
                    // Cost    → MIN  / nilai (makin kecil makin baik, skor mendekati 1)
                    // Edge case: kalau MAX atau nilai = 0, skor diberi 0 biar nggak error.
                    if ($c->type === 'Benefit') {
                        $sawNorm = $max > 0 ? $raw / $max : 0;
                    } else {
                        $sawNorm = $raw > 0 ? $min / $raw : 0;
                    }
                    $sawWeighted = $weight * $sawNorm;
                    $totalSaw   += $sawWeighted;

                    // Simpan detail hasil SAW per kriteria per pelamar ke tabel saw_results.
                    // Berguna untuk ditampilkan di halaman detail/matriks perhitungan.
                    SawResult::create([
                        'application_id'   => $app->id,
                        'criteria_id'      => $c->id,
                        'raw_value'        => $raw,
                        'normalized_value' => round($sawNorm, 6),
                        'weight'           => $weight,
                        'weighted_value'   => round($sawWeighted, 6),
                    ]);

                    // ── SMART: Normalisasi Min-Max (Utility) ─────────────────────────
                    // Rumus ini mengukur seberapa jauh nilai seorang pelamar
                    // dari nilai terburuk, relatif terhadap selisih terbaik-terburuk.
                    //
                    // Benefit → (nilai - MIN) / (MAX - MIN)
                    //   → nilai MIN dapat skor 0, nilai MAX dapat skor 1
                    // Cost    → (MAX - nilai) / (MAX - MIN)
                    //   → nilai MAX (termahal/terbesar) dapat skor 0,
                    //      nilai MIN (termurah/terkecil) dapat skor 1
                    //
                    // Kalau semua pelamar nilainya sama (range = 0), skor diberi 0
                    // karena tidak ada yang bisa dibedakan.
                    $range = $max - $min;
                    if ($c->type === 'Benefit') {
                        $smartNorm = $range > 0 ? ($raw - $min) / $range : 0;
                    } else {
                        $smartNorm = $range > 0 ? ($max - $raw) / $range : 0;
                    }
                    $smartWeighted = $weight * $smartNorm;
                    $totalSmart   += $smartWeighted;

                    // Simpan detail hasil SMART per kriteria per pelamar ke tabel smart_results.
                    SmartResult::create([
                        'application_id'   => $app->id,
                        'criteria_id'      => $c->id,
                        'raw_value'        => $raw,
                        'normalized_value' => round($smartNorm, 6),
                        'weight'           => $weight,
                        'weighted_value'   => round($smartWeighted, 6),
                    ]);
                }

                // Simpan total skor akhir masing-masing metode ke array penampung.
                // Ranking dihitung setelah semua pelamar selesai diproses.
                $sawScores[$app->id]   = round($totalSaw, 6);
                $smartScores[$app->id] = round($totalSmart, 6);
            }

            // ── Ranking SAW ──────────────────────────────────────────────────────
            // Urutkan dari skor tertinggi ke terendah, lalu beri nomor urut.
            // Pelamar dengan skor SAW tertinggi = rank 1.
            arsort($sawScores);
            $rank = 1;
            foreach ($sawScores as $appId => $score) {
                Application::where('id', $appId)->update([
                    'saw_score' => $score,
                    'saw_rank'  => $rank++,
                ]);
            }

            // ── Ranking SMART ────────────────────────────────────────────────────
            // Sama seperti SAW — skor tertinggi = rank 1.
            // Ranking ini bisa berbeda dengan SAW karena rumus normalisasinya berbeda.
            arsort($smartScores);
            $rank = 1;
            foreach ($smartScores as $appId => $score) {
                Application::where('id', $appId)->update([
                    'smart_score' => $score,
                    'smart_rank'  => $rank++,
                ]);
            }
        });

        // Setelah semua perhitungan selesai, kirim notifikasi ke tiap pelamar
        // supaya mereka tahu hasilnya sudah bisa dilihat.
        foreach ($applications as $app) {
            Notification::create([
                'user_id' => $app->user_id,
                'type'    => 'spk_calculated',
                'title'   => 'Hasil Seleksi Awal Tersedia',
                'body'    => 'Hasil seleksi awal beasiswa ' . $scholarship->name . ' sudah tersedia.',
                'data'    => json_encode(['scholarship_id' => $scholarshipId]),
            ]);
        }

        // Redirect ke halaman ranking dengan pesan sukses.
        return redirect()->route('penyalur.pelamar.ranking', $scholarshipId)
            ->with('success', 'Perhitungan SAW & SMART berhasil dijalankan.');
    }

    /**
     * Halaman ranking SPK (SAW + SMART + Perbandingan + Tetapkan Penerima).
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
                'smartResults.criteria',
            ])
            ->orderBy('saw_rank')
            ->get();

        if ($applications->isEmpty()) {
            return redirect()->route('penyalur.pelamar.show', $scholarshipId)
                ->with('error', 'Belum ada hasil SPK. Jalankan perhitungan terlebih dahulu.');
        }

        // Data perbandingan: siapa yang rankingnya berbeda antar metode
        $comparisons = $applications->map(function ($app) {
            $diff = abs(($app->saw_rank ?? 0) - ($app->smart_rank ?? 0));
            return [
                'app'        => $app,
                'rank_diff'  => $diff,
                'is_changed' => $diff > 0,
            ];
        })->sortBy('app.saw_rank')->values();

        $totalChanged = $comparisons->where('is_changed', true)->count();
        $maxDiff      = $comparisons->max('rank_diff');

        return view('penyalur.pelamar.ranking', compact(
            'scholarship',
            'applications',
            'comparisons',
            'totalChanged',
            'maxDiff'
        ));
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
