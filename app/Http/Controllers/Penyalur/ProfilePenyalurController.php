<?php

namespace App\Http\Controllers\Penyalur;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfilePenyalurController extends Controller
{
    public function show()
    {
        return view('penyalur.profile.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'organization_name' => 'required|string|max:255',
            'organization_type' => 'required|in:perusahaan,yayasan,pemerintah,perguruan_tinggi,lainnya',
            'phone'             => 'required|string|max:20',
            'address'           => 'required|string',
            'pic_name'          => 'required|string|max:255',
            'pic_phone'         => 'required|string|max:20',
            'npwp'              => 'nullable|string|max:30',
            'website'           => 'nullable|url|max:255',
            'logo'              => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'pic_id_card'       => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'name'              => 'required|string|max:255',
        ], [
            'organization_name.required' => 'Nama organisasi wajib diisi.',
            'organization_type.required' => 'Tipe organisasi wajib dipilih.',
            'phone.required'             => 'Nomor telepon wajib diisi.',
            'address.required'           => 'Alamat wajib diisi.',
            'pic_name.required'          => 'Nama PIC wajib diisi.',
            'pic_phone.required'         => 'Nomor HP PIC wajib diisi.',
            'website.url'                => 'Format website tidak valid.',
            'logo.image'                 => 'Logo harus berupa gambar.',
            'logo.max'                   => 'Ukuran logo maksimal 2MB.',
        ]);

        $user    = Auth::user();
        $profile = $user->penyalurProfile;

        // Update nama user
        $user->update(['name' => $request->name]);

        $data = $request->only([
            'organization_name',
            'organization_type',
            'phone',
            'address',
            'pic_name',
            'pic_phone',
            'npwp',
            'website',
        ]);

        // Upload logo
        if ($request->hasFile('logo')) {
            if ($profile?->logo_path) {
                Storage::delete($profile->logo_path);
            }
            $data['logo_path'] = $request->file('logo')->store('penyalur/logo', 'public');
        }

        // Upload KTP PIC
        if ($request->hasFile('pic_id_card')) {
            if ($profile?->pic_id_card_path) {
                Storage::delete($profile->pic_id_card_path);
            }
            $data['pic_id_card_path'] = $request->file('pic_id_card')->store('penyalur/ktp', 'public');
        }

        // Jika sebelumnya rejected, reset ke pending agar admin review ulang
        if ($profile?->verification_status === 'rejected') {
            $data['verification_status'] = 'pending';
            $data['verification_note']   = null;
        }

        $profile->update($data);

        return back()->with('success', 'Profil berhasil diperbarui.');
    }
}
