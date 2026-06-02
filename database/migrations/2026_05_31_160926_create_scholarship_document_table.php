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
        Schema::create('scholarship_document', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained('scholarships')->cascadeOnDelete();

            $table->string('name');                         // "Kartu Tanda Mahasiswa"
            $table->text('description')->nullable();        // instruksi tambahan
            $table->boolean('is_required')->default(true);
            $table->json('allowed_types');                  // ["pdf","jpg","png"]
            $table->unsignedInteger('max_size_kb')->default(2048);
            $table->unsignedTinyInteger('order')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_document');
    }
};
