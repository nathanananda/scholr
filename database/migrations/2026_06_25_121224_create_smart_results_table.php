<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah kolom smart_score dan smart_rank di applications
        Schema::table('applications', function (Blueprint $table) {
            $table->decimal('smart_score', 8, 4)->nullable()->after('saw_rank');
            $table->integer('smart_rank')->nullable()->after('smart_score');
        });

        // Buat tabel smart_results
        Schema::create('smart_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('application_id')->constrained('applications')->cascadeOnDelete();
            $table->foreignId('criteria_id')->constrained('scholarship_criteria')->cascadeOnDelete();
            $table->decimal('raw_value', 10, 4);
            $table->decimal('normalized_value', 10, 4);
            $table->decimal('weight', 5, 4);
            $table->decimal('weighted_value', 10, 4);
            $table->timestamps();

            $table->unique(['application_id', 'criteria_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smart_results');

        Schema::table('applications', function (Blueprint $table) {
            $table->dropColumn(['smart_score', 'smart_rank']);
        });
    }
};
