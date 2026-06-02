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
        Schema::create('criteria_range', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')->constrained('scholarship_criteria')->onDelete('cascade');

            $table->string('label', 150);       // misal: "Kurang dari 1 juta"
            $table->decimal('min_value', 15, 2)->nullable(); // null = tidak ada batas bawah
            $table->decimal('max_value', 15, 2)->nullable(); // null = tidak ada batas atas
            $table->unsignedTinyInteger('score');             // nilai SAW: 1–5

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria_range');
    }
};
