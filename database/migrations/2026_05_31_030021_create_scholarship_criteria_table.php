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
        Schema::create('scholarship_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained('scholarships')->onDelete('cascade');

            $table->string('name', 100);               // misal: "IPK", "Penghasilan Orang Tua"
            $table->enum('type', ['Benefit', 'Cost']); // Benefit = makin tinggi makin baik
            $table->decimal('weight', 5, 2);           // bobot dalam persen, total harus = 100
            $table->enum('input_type', ['number', 'range']); // number = isi langsung, range = pilih rentang

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scholarship_criteria');
    }
};
