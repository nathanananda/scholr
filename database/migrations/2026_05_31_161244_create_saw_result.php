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
        Schema::create('saw_result', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained('scholarship_criteria')->cascadeOnDelete();

            $table->decimal('raw_value', 10, 4);         // nilai mentah dari penerima
            $table->decimal('normalized_value', 10, 4);  // rij hasil normalisasi
            $table->decimal('weight', 5, 4);             // bobot kriteria saat SAW dijalankan (0.00 - 1.00)
            $table->decimal('weighted_value', 10, 4);    // weight × normalized_value

            $table->timestamps();

            $table->unique(['application_id', 'criteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saw_result');
    }
};
