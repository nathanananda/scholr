<?php

namespace App\Http\Controllers\Penyalur;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\ApplicationStatusLog;
use App\Models\Notification;
use App\Models\Scholarships;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardPenyalurController extends Controller
{
    public function dashboard()
    {
        $userId = Auth::id();

        $beasiswaAktif = Scholarships::where('penyalur_id', $userId)
            ->whereIn('status', ['Aktif', 'Draft', 'Selesai'])
            ->get();

        $ids = $beasiswaAktif->pluck('id')->toArray();

        $startOfMonth = Carbon::now()->startOfMonth();

        $stats = [
            'total_aktif' => $beasiswaAktif->where('status', 'Aktif')->count(),

            'total_pelamar' => empty($ids)
                ? 0
                : Application::whereIn('scholarship_id', $ids)->count(),

            'doc_pending' => empty($ids)
                ? 0
                : ApplicationDocument::whereHas(
                    'application',
                    fn($q) =>
                    $q->whereIn('scholarship_id', $ids)
                )
                ->where('status', 'uploaded')
                ->count(),

            'penerima_ditetapkan' => empty($ids)
                ? 0
                : Application::whereIn('scholarship_id', $ids)
                ->where('status', 'accepted')
                ->count(),

            'total_kuota' => $beasiswaAktif->sum('quota'),

            'new_pelamar' => empty($ids)
                ? 0
                : Application::whereIn('scholarship_id', $ids)
                ->where('created_at', '>=', $startOfMonth)
                ->count(),

            'new_aktif' => Scholarships::where('penyalur_id', $userId)
                ->where('status', 'Aktif')
                ->where('created_at', '>=', $startOfMonth)
                ->count(),
        ];

        $beasiswaBerjalan = Scholarships::where('penyalur_id', $userId)
            ->whereIn('status', ['Aktif', 'Draft', 'Selesai'])
            ->withCount('applications')
            ->orderByRaw("FIELD(status, 'Aktif', 'Draft', 'Selesai')")
            ->orderBy('end_date', 'asc')
            ->get();

        $recentActivities = empty($ids)
            ? collect()
            : ApplicationStatusLog::with('changedBy')
            ->whereHas(
                'application',
                fn($q) =>
                $q->whereIn('scholarship_id', $ids)
            )
            ->latest()
            ->limit(5)
            ->get();

        $progressKuota = $beasiswaAktif
            ->whereIn('status', ['Aktif', 'Draft'])
            ->map(fn($b) => [
                'name'       => $b->name,
                'kuota'      => $b->quota,
                'ditetapkan' => Application::where('scholarship_id', $b->id)
                    ->where('status', 'accepted')
                    ->count(),
            ])
            ->values();

        $dokumenPending = empty($ids)
            ? collect()
            : ApplicationDocument::with([
                'application.user',
                'application.scholarship',
                'scholarshipDocument',
            ])
            ->whereHas(
                'application',
                fn($q) =>
                $q->whereIn('scholarship_id', $ids)
            )
            ->where('status', 'uploaded')
            ->latest()
            ->limit(5)
            ->get();

        $unreadNotif = Notification::where('user_id', $userId)
            ->whereNull('read_at')
            ->count();

        return view('penyalur.dashboard', compact(
            'stats',
            'beasiswaBerjalan',
            'recentActivities',
            'progressKuota',
            'dokumenPending',
            'unreadNotif',
        ));
    }
}
