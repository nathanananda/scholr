<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\ApplicationStatusLog;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PersyaratanPenerimaController extends Controller
{
    /**
     * GET /penerima/persyaratan
     * Semua lamaran milik user beserta dokumennya.
     */
    public function index()
    {
        $applications = Application::with([
            'scholarship.penyalur.penyalurProfile',
            'scholarship.documents',
            'documents',
        ])
            ->where('user_id', Auth::id())
            ->orderByDesc('created_at')
            ->get();

        return view('penerima.beasiswa-persyaratan', compact('applications'));
    }

    /**
     * PATCH /penerima/lamaran/{application}/dokumen/{doc}/reupload
     * Upload ulang satu dokumen (hanya jika status rejected atau belum diupload).
     */
    public function reupload(Request $request, int $applicationId, int $docId)
    {
        $application = Application::where('user_id', Auth::id())
            ->findOrFail($applicationId);

        // Guard: hanya draft / under_review yang boleh reupload
        abort_unless(
            in_array($application->status, ['draft', 'under_review']),
            403,
            'Lamaran tidak dapat dimodifikasi pada status ini.'
        );

        // Validasi file
        $scholarshipDoc = $application->scholarship->documents()->findOrFail($docId);
        $formats        = $scholarshipDoc->allowed_formats ?? ['pdf'];
        $maxKb          = $scholarshipDoc->max_size_kb ?? 2048;
        $mimes          = $this->formatsToMimes($formats);

        $request->validate([
            'document' => "required|file|mimetypes:{$mimes}|max:{$maxKb}",
        ], [
            'document.required'  => 'File wajib dipilih.',
            'document.max'       => 'Ukuran file melebihi batas ' . number_format($maxKb / 1024, 1) . ' MB.',
            'document.mimetypes' => 'Format file tidak didukung. Gunakan: ' . strtoupper(implode(', ', $formats)) . '.',
        ]);

        DB::transaction(function () use ($request, $application, $scholarshipDoc) {
            $existing = ApplicationDocument::where('application_id', $application->id)
                ->where('scholarship_document_id', $scholarshipDoc->id)
                ->first();

            // Hapus file lama jika ada
            if ($existing && $existing->file_path) {
                Storage::disk('local')->delete($existing->file_path);
            }

            $file = $request->file('document');
            $path = $file->store("applications/{$application->id}/documents", 'local');

            if ($existing) {
                $existing->update([
                    'file_path'         => $path,
                    'original_filename' => $file->getClientOriginalName(),
                    'file_size_kb'      => (int) ceil($file->getSize() / 1024),
                    'status'            => 'uploaded',
                    'rejection_note'    => null,
                ]);
            } else {
                ApplicationDocument::create([
                    'application_id'          => $application->id,
                    'scholarship_document_id' => $scholarshipDoc->id,
                    'file_path'               => $path,
                    'original_filename'       => $file->getClientOriginalName(),
                    'file_size_kb'            => (int) ceil($file->getSize() / 1024),
                    'status'                  => 'uploaded',
                ]);
            }

            // Log jika re-upload setelah rejected
            ApplicationStatusLog::create([
                'application_id' => $application->id,
                'from_status'    => $application->status,
                'to_status'      => $application->status,
                'changed_by'     => Auth::id(),
                'note'           => "Dokumen \"{$scholarshipDoc->name}\" di-upload ulang oleh penerima.",
            ]);
        });

        return redirect()->route('penerima.persyaratan.index')
            ->with('success', "Dokumen \"{$scholarshipDoc->name}\" berhasil diupload.");
    }

    /**
     * POST /penerima/lamaran/{application}/submit
     * Submit lamaran dari status draft → submitted.
     */
    public function submit(int $applicationId)
    {
        $application = Application::with(['scholarship.documents', 'documents'])
            ->where('user_id', Auth::id())
            ->findOrFail($applicationId);

        abort_unless($application->status === 'draft', 403, 'Lamaran sudah disubmit.');

        // Pastikan semua dokumen wajib sudah terupload
        $requiredDocIds  = $application->scholarship->documents
            ->where('is_required', true)->pluck('id');
        $uploadedDocIds  = $application->documents->pluck('scholarship_document_id');
        $missing         = $requiredDocIds->diff($uploadedDocIds);

        if ($missing->isNotEmpty()) {
            return redirect()->route('penerima.persyaratan.index')
                ->with('error', 'Masih ada dokumen wajib yang belum diupload.');
        }

        DB::transaction(function () use ($application) {
            $application->update([
                'status'       => 'submitted',
                'submitted_at' => now(),
            ]);

            ApplicationStatusLog::create([
                'application_id' => $application->id,
                'from_status'    => 'draft',
                'to_status'      => 'submitted',
                'changed_by'     => Auth::id(),
                'note'           => 'Lamaran disubmit oleh penerima.',
            ]);

            // Notifikasi ke penyalur
            Notification::create([
                'user_id' => $application->scholarship->penyalur_id,
                'type'    => 'new_application',
                'title'   => 'Pelamar Baru',
                'message' => 'Ada pelamar baru untuk beasiswa ' . $application->scholarship->name,
                'data'    => json_encode(['application_id' => $application->id]),
            ]);
        });

        return redirect()->route('penerima.persyaratan.index')
            ->with('success', 'Lamaran berhasil disubmit! Penyalur akan mereview dokumen Anda.');
    }

    // -------------------------------------------------------
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
            ->filter()->unique()->implode(',');
    }
}
