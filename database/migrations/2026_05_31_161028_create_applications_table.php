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
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained('scholarships')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->enum('status', ['draft', 'submitted', 'under_review', 'accepted', 'rejected'])
                ->default('draft');
            $table->decimal('saw_score', 8, 4)->nullable();
            $table->unsignedInteger('saw_rank')->nullable();
            $table->text('rejection_note')->nullable();
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            // Satu user hanya bisa punya 1 lamaran per beasiswa
            $table->unique(['scholarship_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};
