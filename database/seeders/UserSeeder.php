<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            // 1. Akun Admin Utama
            [
                'name' => 'Super Admin Scholr',
                'email' => 'admin@scholr.id',
                'password' => Hash::make('password123'), // Password: password123
                'role' => 'admin', // Sesuaikan dengan nama kolom role di tabel users kamu
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 2. Akun Penyalur Beasiswa (Instansi / Sponsor)
            [
                'name' => 'PT Djarum Foundation',
                'email' => 'penyalur@scholr.id',
                'password' => Hash::make('password123'),
                'role' => 'penyalur',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // 3. Akun Penerima Beasiswa (Mahasiswa Pendaftar)
            [
                'name' => 'Budi Setiawan',
                'email' => 'penerima@scholr.id',
                'password' => Hash::make('password123'),
                'role' => 'penerima',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
