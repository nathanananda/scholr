<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PenerimaProfile;
use App\Models\PenyalurProfile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login()
    {
        return view('auth.login');
    }

    public function authenticate(Request $request)
    {
        $validate = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $dataUsers = User::where('email', $validate['email'])->first();
        if ($dataUsers && Hash::check($validate['password'], $dataUsers->password)) {
            Auth::login($dataUsers);
            if (Auth::user()->role === 'penyalur') {
                return redirect()->route('penyalur.dashboard');
            }

            if (Auth::user()->role === 'penerima') {
                return redirect()->route('penerima.dashboard');
            }

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function register()
    {
        return view('auth.register');
    }

    public function storeRegister(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:8',
            'role'         => 'required|in:penerima,penyalur',
        ], [
            'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
            'email.required'        => 'Email wajib diisi.',
            'email.email'           => 'Format email tidak valid.',
            'email.unique'          => 'Email sudah terdaftar.',
            'password.required'     => 'Password wajib diisi.',
            'password.min'          => 'Password minimal 8 karakter.',
            'role.required'         => 'Role wajib dipilih.',
        ]);

        // Buat user baru
        $user = User::create([
            'name'     => $request->nama_lengkap,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => $request->role,
        ]);

        // Buat profil kosong sesuai role agar record sudah ada di DB
        if ($request->role === 'penerima') {
            PenerimaProfile::create(['user_id' => $user->id]);
        } else {
            PenyalurProfile::create([
                'user_id'             => $user->id,
                'verification_status' => 'pending',
            ]);
        }

        // Login otomatis setelah register
        Auth::login($user);

        // Redirect ke halaman biodata sesuai role
        if ($request->role === 'penerima') {
            return redirect()->route('penerima.profile')->with('info', 'Akun berhasil dibuat! Lengkapi biodata kamu terlebih dahulu.');
        }

        return redirect()->route('penyalur.profile')->with('info', 'Akun berhasil dibuat! Lengkapi profil organisasi kamu.');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }
}
