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
        Schema::create('scholarships', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel user yang bertindak sebagai penyalur
            $table->foreignId('penyalur_id')->constrained('users')->onDelete('cascade');

            $table->string('name', 150);
            $table->enum('category', ['Internal', 'Eksternal', 'Prestasi', 'Sosial']);
            $table->text('description')->nullable();
            $table->integer('quota');
            $table->date('start_date');
            $table->date('end_date');

            // Draft = Belum publish, Aktif = Pendaftaran buka,
            // Seleksi = Ditutup & sedang hitung SAW, Selesai = Hasil ranking final keluar
            $table->enum('status', ['Draft', 'Aktif', 'Seleksi', 'Selesai'])->default('Draft');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarships');
    }
};
