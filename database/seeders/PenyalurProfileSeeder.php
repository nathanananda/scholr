<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PenyalurProfileSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('penyalur_profile')->insert([
            'user_id' => 2,
            'organization_name' => 'PT Djarum Foundation',
            'organization_type' => 'Yayasan',
            'npwp' => '12.345.678.9-123.000',
            'address' => 'Jl. Merdeka No. 123, Jakarta Pusat',
            'phone' => '081234567890',
            'website' => 'https://yayasanbeasiswa.id',
            'logo_path' => null,
            'pic_name' => 'Budi Santoso',
            'pic_phone' => '081298765432',
            'pic_id_card_path' => null,
            'verification_status' => 'verified',
            'verification_note' => 'Data organisasi telah diverifikasi.',
            'verified_at' => Carbon::now(),
            'verified_by' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
