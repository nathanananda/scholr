<?php

namespace App\Http\Controllers\Penyalur;

use App\Http\Controllers\Controller;
use App\Models\Scholarships;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class BeasiswaPenyalurController extends Controller
{
    public function index()
    {
        $scholarships = Scholarships::latest()
            ->paginate(10);
        return view('penyalur.beasiswa.index', compact('scholarships'));
    }

    public function create()
    {
        return view('penyalur.beasiswa.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:150'],
            'category'          => ['required', Rule::in(['Internal', 'Eksternal', 'Prestasi', 'Sosial'])],
            'education_level'   => ['required', Rule::in(['all', 'sd', 'smp', 'sma', 'd3', 's1', 's2', 's3'])],
            'description'       => ['nullable', 'string'],
            'benefit_amount'    => ['nullable', 'numeric', 'min:0'],
            'benefit_period'    => ['nullable', Rule::in(['monthly', 'per_semester', 'yearly', 'once'])],
            'benefit_detail'    => ['nullable', 'string'],
            'quota'             => ['required', 'integer', 'min:1'],
            'start_date'        => ['required', 'date'],
            'end_date'          => ['required', 'date', 'after_or_equal:start_date'],
            'announcement_date' => ['nullable', 'date', 'after_or_equal:end_date'],
            'status'            => ['required', Rule::in(['Draft', 'Aktif', 'Seleksi', 'Selesai'])],
        ], [
            'name.required'                   => 'Nama beasiswa wajib diisi.',
            'name.max'                        => 'Nama beasiswa maksimal 150 karakter.',
            'category.required'               => 'Kategori wajib dipilih.',
            'category.in'                     => 'Kategori tidak valid.',
            'education_level.required'        => 'Jenjang pendidikan wajib dipilih.',
            'education_level.in'              => 'Jenjang pendidikan tidak valid.',
            'benefit_amount.numeric'          => 'Nominal dana harus berupa angka.',
            'benefit_amount.min'              => 'Nominal dana tidak boleh negatif.',
            'benefit_period.in'               => 'Periode pencairan tidak valid.',
            'quota.required'                  => 'Kuota penerima wajib diisi.',
            'quota.integer'                   => 'Kuota harus berupa angka.',
            'quota.min'                       => 'Kuota minimal 1 orang.',
            'start_date.required'             => 'Tanggal mulai wajib diisi.',
            'start_date.date'                 => 'Format tanggal mulai tidak valid.',
            'end_date.required'               => 'Tanggal selesai wajib diisi.',
            'end_date.date'                   => 'Format tanggal selesai tidak valid.',
            'end_date.after_or_equal'         => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'announcement_date.date'          => 'Format tanggal pengumuman tidak valid.',
            'announcement_date.after_or_equal' => 'Tanggal pengumuman tidak boleh sebelum tanggal selesai.',
            'status.required'                 => 'Status wajib dipilih.',
            'status.in'                       => 'Status tidak valid.',
        ]);

        $validated['penyalur_id'] = Auth::id();

        Scholarships::create($validated);

        return redirect()->route('penyalur.beasiswa')
            ->with('success', 'Beasiswa berhasil ditambahkan.');
    }
    /**
     * Display the specified scholarship.
     */
    public function show(Scholarships $scholarship)
    {

        $this->authorizeOwner($scholarship);
        $scholarship->load('criteria.ranges');
        return view('penyalur.beasiswa.show', compact('scholarship'));
    }
    /**
     * Show the form for editing the specified scholarship.
     */
    public function edit(Scholarships $scholarship)
    {
        $this->authorizeOwner($scholarship);

        return view('penyalur.beasiswa.edit', compact('scholarship'));
    }

    /**
     * Update the specified scholarship in storage.
     */
    public function update(Request $request, Scholarships $scholarship)
    {
        $this->authorizeOwner($scholarship);

        $validated = $request->validate([
            'name'              => ['required', 'string', 'max:150'],
            'category'          => ['required', Rule::in(['Internal', 'Eksternal', 'Prestasi', 'Sosial'])],
            'education_level'   => ['required', Rule::in(['all', 'sd', 'smp', 'sma', 'd3', 's1', 's2', 's3'])],
            'description'       => ['nullable', 'string'],
            'benefit_amount'    => ['nullable', 'numeric', 'min:0'],
            'benefit_period'    => ['nullable', Rule::in(['monthly', 'per_semester', 'yearly', 'once'])],
            'benefit_detail'    => ['nullable', 'string'],
            'quota'             => ['required', 'integer', 'min:1'],
            'start_date'        => ['required', 'date'],
            'end_date'          => ['required', 'date', 'after_or_equal:start_date'],
            'announcement_date' => ['nullable', 'date', 'after_or_equal:end_date'],
            'status'            => ['required', Rule::in(['Draft', 'Aktif', 'Seleksi', 'Selesai'])],
        ], [
            'name.required'                    => 'Nama beasiswa wajib diisi.',
            'name.max'                         => 'Nama beasiswa maksimal 150 karakter.',
            'category.required'                => 'Kategori wajib dipilih.',
            'category.in'                      => 'Kategori tidak valid.',
            'education_level.required'         => 'Jenjang pendidikan wajib dipilih.',
            'education_level.in'               => 'Jenjang pendidikan tidak valid.',
            'benefit_amount.numeric'           => 'Nominal dana harus berupa angka.',
            'benefit_amount.min'               => 'Nominal dana tidak boleh negatif.',
            'benefit_period.in'                => 'Periode pencairan tidak valid.',
            'quota.required'                   => 'Kuota penerima wajib diisi.',
            'quota.integer'                    => 'Kuota harus berupa angka.',
            'quota.min'                        => 'Kuota minimal 1 orang.',
            'start_date.required'              => 'Tanggal mulai wajib diisi.',
            'start_date.date'                  => 'Format tanggal mulai tidak valid.',
            'end_date.required'                => 'Tanggal selesai wajib diisi.',
            'end_date.date'                    => 'Format tanggal selesai tidak valid.',
            'end_date.after_or_equal'          => 'Tanggal selesai tidak boleh sebelum tanggal mulai.',
            'announcement_date.date'           => 'Format tanggal pengumuman tidak valid.',
            'announcement_date.after_or_equal' => 'Tanggal pengumuman tidak boleh sebelum tanggal selesai.',
            'status.required'                  => 'Status wajib dipilih.',
            'status.in'                        => 'Status tidak valid.',
        ]);

        $scholarship->update($validated);

        return redirect()->route('penyalur.beasiswa')
            ->with('success', 'Beasiswa berhasil diperbarui.');
    }

    /**
     * Remove the specified scholarship from storage.
     */
    public function destroy(Scholarships $scholarship)
    {
        $this->authorizeOwner($scholarship);

        $scholarship->delete();

        return redirect()->route('penyalur.beasiswa')
            ->with('success', 'Beasiswa berhasil dihapus.');
    }

    /**
     * Pastikan hanya penyalur pemilik yang bisa akses.
     */
    private function authorizeOwner(Scholarships $scholarship): void
    {
        if ($scholarship->penyalur_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke beasiswa ini.');
        }
    }
}
