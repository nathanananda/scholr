<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\PenyalurProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifikasiAdminController extends Controller
{
    /**
     * List semua penyalur (bisa filter by status).
     */
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $profiles = PenyalurProfile::with('user')
            ->when($status !== 'all', fn($q) => $q->where('verification_status', $status))
            ->orderByRaw("FIELD(verification_status, 'pending', 'rejected', 'verified')")
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        $counts = [
            'pending'  => PenyalurProfile::where('verification_status', 'pending')->count(),
            'verified' => PenyalurProfile::where('verification_status', 'verified')->count(),
            'rejected' => PenyalurProfile::where('verification_status', 'rejected')->count(),
        ];

        return view('admin.verifikasi.index', compact('profiles', 'counts', 'status'));
    }

    /**
     * Detail profil penyalur.
     */
    public function show($id)
    {
        $profile = PenyalurProfile::with('user')->findOrFail($id);
        return view('admin.verifikasi.show', compact('profile'));
    }

    /**
     * Approve penyalur.
     */
    public function approve($id)
    {
        $profile = PenyalurProfile::with('user')->findOrFail($id);

        $profile->update([
            'verification_status' => 'verified',
            'verification_note'   => null,
            'verified_at'         => now(),
            'verified_by'         => Auth::id(),
        ]);

        Notification::create([
            'user_id' => $profile->user_id,
            'type'    => 'account_verified',
            'title'   => 'Akun Terverifikasi',
            'body' => 'Akun Anda telah diverifikasi. Mulai buat beasiswa!',
            'data'    => json_encode([]),
        ]);

        return redirect()->route('admin.verifikasi-penyalur.index')
            ->with('success', "Akun {$profile->organization_name} berhasil diverifikasi.");
    }

    /**
     * Reject penyalur dengan catatan.
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'verification_note' => 'required|string|max:1000',
        ], [
            'verification_note.required' => 'Catatan penolakan wajib diisi.',
        ]);

        $profile = PenyalurProfile::with('user')->findOrFail($id);

        $profile->update([
            'verification_status' => 'rejected',
            'verification_note'   => $request->verification_note,
            'verified_at'         => null,
            'verified_by'         => Auth::id(),
        ]);

        Notification::create([
            'user_id' => $profile->user_id,
            'type'    => 'account_rejected',
            'title'   => 'Akun Ditolak',
            'body' => 'Akun ditolak. Lihat catatan dan perbaiki data.',
            'data'    => json_encode(['note' => $request->verification_note]),
        ]);

        return redirect()->route('admin.verifikasi-penyalur.index')
            ->with('error', "Akun {$profile->organization_name} ditolak.");
    }
}
