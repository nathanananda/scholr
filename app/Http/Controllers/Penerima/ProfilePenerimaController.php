<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use App\Models\PenerimaProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePenerimaController extends Controller
{

    /**
     * GET /penerima/profil
     */
    public function index()
    {
        return view('penerima.profile');
    }

    /**
     * PUT /penerima/profil
     */
    public function update(Request $request)
    {
        $request->validate([
            // users
            'name'            => ['required', 'string', 'max:255'],
            // penerima_profiles
            'phone'           => ['nullable', 'string', 'max:20'],
            'gender'          => ['nullable', 'in:male,female'],
            'birth_place'     => ['nullable', 'string', 'max:100'],
            'birth_date'      => ['nullable', 'date', 'before:today'],
            'address'         => ['nullable', 'string', 'max:500'],
            'education_level' => ['nullable', 'in:SD,SMP,SMA,D3,S1,S2,S3'],
            'school_name'     => ['nullable', 'string', 'max:255'],
            'major'           => ['nullable', 'string', 'max:255'],
            'semester'        => ['nullable', 'integer', 'min:1', 'max:14'],
            'gpa'             => ['nullable', 'numeric', 'min:0', 'max:4'],
            'student_id_path' => ['nullable', 'file', 'mimetypes:image/jpeg,image/png,application/pdf', 'max:2048'],
            'parent_income'   => ['nullable', 'integer', 'min:0'],
            'dependents'      => ['nullable', 'integer', 'min:0', 'max:20'],
        ], [
            'name.required'       => 'Nama lengkap wajib diisi.',
            'birth_date.before'   => 'Tanggal lahir harus sebelum hari ini.',
            'gpa.max'             => 'IPK maksimal 4.00.',
            'gpa.min'             => 'IPK tidak boleh negatif.',
            'student_id_path.max' => 'Ukuran file KTM maksimal 2 MB.',
        ]);

        $user = Auth::user();

        // Update nama di tabel users
        $user->update(['name' => $request->name]);

        // Resolve atau buat profil
        $profile = PenerimaProfile::firstOrNew(['user_id' => $user->id]);

        $profile->fill([
            'phone'           => $request->phone,
            'gender'          => $request->gender,
            'birth_place'     => $request->birth_place,
            'birth_date'      => $request->birth_date,
            'address'         => $request->address,
            'education_level' => $request->education_level,
            'school_name'     => $request->school_name,
            'major'           => $request->major,
            'semester'        => $request->semester,
            'gpa'             => $request->gpa,
            'parent_income'   => $request->parent_income,
            'dependents'      => $request->dependents,
        ]);

        // Handle upload KTM
        if ($request->hasFile('student_id_path')) {
            // Hapus file lama
            if ($profile->student_id_path) {
                Storage::disk('local')->delete($profile->student_id_path);
            }

            $profile->student_id_path = $request->file('student_id_path')
                ->store("penerima/{$user->id}/ktm", 'local');
        }

        $profile->save();

        return redirect()->route('penerima.profil.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
