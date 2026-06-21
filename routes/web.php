<?php

use App\Http\Controllers\Admin\DashboardAdminController;
use App\Http\Controllers\Admin\VerifikasiAdminController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Penerima\BeasiswaPenerimaController;
use App\Http\Controllers\Penerima\DashboardPenerimaController;
use App\Http\Controllers\Penerima\LamaranPenerimaController;
use App\Http\Controllers\Penerima\PersyaratanPenerimaController;
use App\Http\Controllers\Penerima\ProfilePenerimaController;
use App\Http\Controllers\Penyalur\BeasiswaPenyalurController;
use App\Http\Controllers\Penyalur\CriteriaPenyalurController;
use App\Http\Controllers\Penyalur\DashboardPenyalurController;
use App\Http\Controllers\Penyalur\NotifikasiPenyalurController;
use App\Http\Controllers\Penyalur\PelamarBeasiswaPenyalurController;
use App\Http\Controllers\Penyalur\ProfilePenyalurController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login.authenticate');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/register', [AuthController::class, 'storeRegister'])->name('register.store');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Route::prefix('penyalur')->group(function () {
        Route::middleware('penyalur.verified')->group(function () {
            Route::get('/', [DashboardPenyalurController::class, 'dashboard'])->name('penyalur.dashboard');

            Route::prefix('beasiswa')->group(function () {
                Route::get('/', [BeasiswaPenyalurController::class, 'index'])->name('penyalur.beasiswa');
                Route::get('/create', [BeasiswaPenyalurController::class, 'create'])->name('penyalur.beasiswa.create');
                Route::post('/', [BeasiswaPenyalurController::class, 'store'])->name('penyalur.beasiswa.store');
                Route::get('/{scholarship}', [BeasiswaPenyalurController::class, 'show'])->name('penyalur.beasiswa.show');
                Route::get('/{scholarship}/edit', [BeasiswaPenyalurController::class, 'edit'])->name('penyalur.beasiswa.edit');
                Route::put('/{scholarship}', [BeasiswaPenyalurController::class, 'update'])->name('penyalur.beasiswa.update');
                Route::delete('/{scholarship}', [BeasiswaPenyalurController::class, 'destroy'])->name('penyalur.beasiswa.destroy');


                Route::get('{scholarship}/criteria', [CriteriaPenyalurController::class, 'index'])
                    ->name('penyalur.beasiswa.criteria');
                Route::post('{scholarship}/criteria', [CriteriaPenyalurController::class, 'store'])
                    ->name('penyalur.beasiswa.criteria.store');
                Route::post('{scholarship}/documents', [CriteriaPenyalurController::class, 'storeDocuments'])
                    ->name('penyalur.beasiswa.documents.store');
            });
            Route::prefix('pelamar')->group(function () {

                // Daftar beasiswa + jumlah pelamar
                Route::get('/', [PelamarBeasiswaPenyalurController::class, 'index'])->name('penyalur.pelamar.index');

                // Daftar pelamar per beasiswa
                Route::get('/{scholarshipId}', [PelamarBeasiswaPenyalurController::class, 'show'])->name('penyalur.pelamar.show');
                // ✅ Ranking & run-saw HARUS di atas /{scholarshipId}/{applicationId}
                Route::get('/{scholarshipId}/ranking', [PelamarBeasiswaPenyalurController::class, 'ranking'])->name('penyalur.pelamar.ranking');
                Route::post('/{scholarshipId}/run-saw', [PelamarBeasiswaPenyalurController::class, 'runSaw'])->name('penyalur.pelamar.run-saw');
                Route::post('/{scholarshipId}/tetapkan', [PelamarBeasiswaPenyalurController::class, 'tetapkanPenerima'])->name('penyalur.pelamar.tetapkan');

                // ⬇️ Wildcard ganda taruh paling bawah
                Route::get('/{scholarshipId}/{applicationId}', [PelamarBeasiswaPenyalurController::class, 'detail'])->name('penyalur.pelamar.detail');

                // Approve / Reject dokumen (pakai /document/ prefix jadi aman)
                Route::post('/document/{documentId}/approve', [PelamarBeasiswaPenyalurController::class, 'approveDocument'])->name('penyalur.pelamar.document.approve');
                Route::post('/document/{documentId}/reject', [PelamarBeasiswaPenyalurController::class, 'rejectDocument'])->name('penyalur.pelamar.document.reject');
            });
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', [ProfilePenyalurController::class, 'show'])->name('penyalur.profile');
            Route::put('/', [ProfilePenyalurController::class, 'update'])->name('penyalur.profile.update');
        });

        Route::prefix('notifikasi')->group(function () {
            Route::get('/', [NotifikasiPenyalurController::class, 'index'])->name('penyalur.notifikasi');
            Route::post('/mark-all-read', [NotifikasiPenyalurController::class, 'markAllRead'])->name('penyalur.notifikasi.markAllRead');
            Route::delete('/{id}/dismiss', [NotifikasiPenyalurController::class, 'dismiss'])->name('penyalur.notifikasi.dismiss');
        });
    });

    Route::prefix('penerima')->group(function () {
        Route::get('/', [DashboardPenerimaController::class, 'dashboard'])->name('penerima.dashboard');
        Route::prefix('beasiswa')->group(function () {
            Route::get('/', [BeasiswaPenerimaController::class, 'index'])->name('penerima.beasiswa');
            Route::get('/{slug}', [BeasiswaPenerimaController::class, 'show'])->name('penerima.beasiswa.show');
            Route::get('/{slug}/apply', [BeasiswaPenerimaController::class, 'apply'])->name('penerima.beasiswa.apply');
            Route::post('/{slug}/apply', [BeasiswaPenerimaController::class, 'store'])->name('penerima.beasiswa.store');
        });

        Route::prefix('status-lamaran')->group(function () {
            Route::get('/',        [LamaranPenerimaController::class, 'index'])->name('penerima.lamaran.index');
            Route::get('/{id}',   [LamaranPenerimaController::class, 'show'])->name('penerima.lamaran.show');
            Route::post('/doc/{id}/reupload', [LamaranPenerimaController::class, 'reupload'])->name('penerima.lamaran.reupload');
        });

        Route::get('/persyaratan', [PersyaratanPenerimaController::class, 'index'])->name('penerima.persyaratan.index');

        // Upload ulang satu dokumen
        Route::patch('/lamaran/{application}/dokumen/{doc}/reupload', [PersyaratanPenerimaController::class, 'reupload'])
            ->name('lamaran.reupload');

        // Submit lamaran dari draft
        Route::post('/lamaran/{application}/submit', [PersyaratanPenerimaController::class, 'submit'])
            ->name('lamaran.submit');


        Route::get('/profile', [ProfilePenerimaController::class, 'index'])->name('penerima.profile');
        Route::put('/profile', [ProfilePenerimaController::class, 'update'])->name('penerima.profile.update');
    });


    Route::prefix('admin')->group(function () {
        Route::get('/dashboard', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
        
        Route::prefix('verifikasi-penyalur')->group(function () {
            Route::get('/', [VerifikasiAdminController::class, 'index'])->name('admin.verifikasi-penyalur.index');
            Route::get('/{id}', [VerifikasiAdminController::class, 'show'])->name('admin.verifikasi-penyalur.show');
            Route::post('/{id}/approve', [VerifikasiAdminController::class, 'approve'])->name('admin.verifikasi-penyalur.approve');
            Route::post('/{id}/reject', [VerifikasiAdminController::class, 'reject'])->name('admin.verifikasi-penyalur.reject');
        });
    });
});
