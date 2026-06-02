<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Notification;
use App\Models\Scholarships;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardPenerimaController extends Controller
{
    public function dashboard()
    {
        $user   = Auth::user();
        $profil = $user->penerimaProfile;

        // ----------------------------------------------------------------
        // 1. PERSENTASE KELENGKAPAN PROFIL
        // ----------------------------------------------------------------
        $profilItems = $this->getProfilItems($user, $profil);
        $donCount    = collect($profilItems)->where('done', true)->count();
        $profilPct   = (int) round(($donCount / count($profilItems)) * 100);

        // ----------------------------------------------------------------
        // 2. STATS CARDS
        // ----------------------------------------------------------------
        $lamaran = Application::where('user_id', $user->id)->with('scholarship')->get();

        $aktif     = $lamaran->whereNotIn('status', ['accepted', 'rejected'])->count();
        $diproses  = $lamaran->whereIn('status', ['submitted', 'under_review'])->count();
        $diterima  = $lamaran->where('status', 'accepted')->count();
        $namaDiterima = $lamaran->where('status', 'accepted')->first()?->scholarship?->name ?? '';

        // Rekomendasi: beasiswa aktif yang sesuai jenjang penerima dan belum dilamar
        $sudahDilamar = $lamaran->pluck('scholarship_id');
        $rekomendasiCount = Scholarships::where('status', 'Aktif')
            ->when(
                $profil?->education_level,
                fn($q) =>
                $q->where(function ($query) use ($profil) {
                    $query->where('education_level', $profil->education_level)
                        ->orWhere('education_level', 'all');
                })
            )
            ->whereNotIn('id', $sudahDilamar)
            ->count();

        $stats = [
            'aktif'        => $aktif,
            'diproses'     => $diproses,
            'diterima'     => $diterima,
            'nama_diterima' => $namaDiterima,
            'rekomendasi'  => $rekomendasiCount,
        ];

        // ----------------------------------------------------------------
        // 3. ALERT DOKUMEN DITOLAK
        // ----------------------------------------------------------------
        $docDitolak = ApplicationDocument::whereHas(
            'application',
            fn($q) =>
            $q->where('user_id', $user->id)
        )
            ->where('status', 'rejected')
            ->with([
                'application.scholarship',
                'scholarshipDocument',
            ])
            ->get();

        // ----------------------------------------------------------------
        // 4. LAMARAN TERBARU (5 terakhir)
        // ----------------------------------------------------------------
        $lamaranTerbaru = Application::where('user_id', $user->id)
            ->with(['scholarship.penyalur'])
            ->orderByDesc('updated_at')
            ->limit(5)
            ->get();

        // ----------------------------------------------------------------
        // 5. DEADLINE MENDEKAT
        //    Lamaran aktif (draft/submitted/under_review) dengan sisa
        //    <= 7 hari sebelum close_date beasiswa
        // ----------------------------------------------------------------
        $deadlineMendekat = Application::where('user_id', $user->id)
            ->whereIn('status', ['draft', 'submitted', 'under_review'])
            ->with('scholarship')
            ->get()
            ->filter(function ($app) {
                $closeDate = Carbon::parse($app->scholarship?->close_date);
                $daysLeft  = Carbon::now()->diffInDays($closeDate, false);
                return $daysLeft >= 0 && $daysLeft <= 7;
            })
            ->sortBy(fn($app) => $app->scholarship->close_date)
            ->values();

        // ----------------------------------------------------------------
        // 6. REKOMENDASI BEASISWA (maks. 5)
        // ----------------------------------------------------------------
        $rekomendasiBeasiswa = Scholarships::where('status', 'Aktif')
            ->when(
                $profil?->education_level,
                fn($q) =>
                $q->where(function ($query) use ($profil) {
                    $query->where('education_level', $profil->education_level)
                        ->orWhere('education_level', 'all');
                })
            )
            ->whereNotIn('id', $sudahDilamar)
            ->with('penyalur')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ----------------------------------------------------------------
        // 7. NOTIFIKASI TERBARU (5 terakhir)
        // ----------------------------------------------------------------
        $notifikasiTerbaru = Notification::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // ----------------------------------------------------------------
        // RENDER
        // ----------------------------------------------------------------
        return view('penerima.dashboard', compact(
            'profilPct',
            'profilItems',
            'stats',
            'docDitolak',
            'lamaranTerbaru',
            'deadlineMendekat',
            'rekomendasiBeasiswa',
            'notifikasiTerbaru',
        ));
    }

    // ----------------------------------------------------------------
    // HELPER: item-item kelengkapan profil
    // ----------------------------------------------------------------
    private function getProfilItems($user, $profil): array
    {
        return [
            // Data akun dasar
            [
                'label' => 'Nama lengkap',
                'done'  => filled($user->name),
            ],
            [
                'label' => 'Nomor HP',
                'done'  => filled($profil?->phone),
            ],
            [
                'label' => 'Tempat & tanggal lahir',
                'done'  => filled($profil?->birth_place) && filled($profil?->birth_date),
            ],
            [
                'label' => 'Jenis kelamin',
                'done'  => filled($profil?->gender),
            ],
            [
                'label' => 'Alamat',
                'done'  => filled($profil?->address),
            ],

            // Data akademik
            [
                'label' => 'Jenjang pendidikan',
                'done'  => filled($profil?->education_level),
            ],
            [
                'label' => 'Nama sekolah / universitas',
                'done'  => filled($profil?->school_name),
            ],
            [
                'label' => 'Jurusan',
                'done'  => filled($profil?->major),
            ],
            [
                'label' => 'Semester',
                'done'  => filled($profil?->semester),
            ],
            [
                'label' => 'IPK / Nilai rata-rata',
                'done'  => filled($profil?->gpa),
            ],

            // Data ekonomi
            [
                'label' => 'Penghasilan orang tua',
                'done'  => filled($profil?->parent_income),
            ],
            [
                'label' => 'Jumlah tanggungan',
                'done'  => filled($profil?->dependents_count),
            ],

            // Dokumen identitas
            [
                'label' => 'KTM / Kartu pelajar',
                'done'  => filled($profil?->identity_document_path),
            ],
        ];
    }
}
