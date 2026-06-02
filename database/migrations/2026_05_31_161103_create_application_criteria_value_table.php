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
        Schema::create('application_criteria_value', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained('scholarship_criteria')->cascadeOnDelete();

            // Salah satu diisi tergantung input_type kriteria
            $table->decimal('value', 10, 2)->nullable();            // untuk input_type: number
            $table->foreignId('criteria_range_id')                  // untuk input_type: range
                ->nullable()
                ->constrained('criteria_range')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['application_id', 'criteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_criteria_value');
    }
};
