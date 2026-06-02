<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penyalur_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Data organisasi
            $table->string('organization_name');
            $table->enum('organization_type', ['perusahaan', 'yayasan', 'pemerintah', 'perguruan_tinggi', 'lainnya']);
            $table->string('npwp', 30)->nullable();
            $table->text('address');
            $table->string('phone', 20);
            $table->string('website')->nullable();
            $table->string('logo_path')->nullable();

            // Data penanggung jawab
            $table->string('pic_name');
            $table->string('pic_phone', 20);
            $table->string('pic_id_card_path')->nullable(); // foto KTP PIC

            // Verifikasi oleh admin
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending');
            $table->text('verification_note')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penyalur_profiles');
    }
};
