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
        Schema::create('penerima_profile', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            // Data pribadi
            $table->string('phone', 20)->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->text('address')->nullable();

            // Data pendidikan
            $table->enum('education_level', ['sd', 'smp', 'sma', 'd3', 's1', 's2', 's3'])->nullable();
            $table->string('school_name')->nullable();
            $table->string('major')->nullable();         // jurusan, untuk kuliah
            $table->unsignedTinyInteger('semester')->nullable();
            $table->decimal('gpa', 4, 2)->nullable();   // IPK, untuk kuliah
            $table->string('student_id_path')->nullable(); // foto KTM / kartu pelajar

            // Data ekonomi
            $table->decimal('parent_income', 15, 2)->nullable(); // penghasilan orang tua
            $table->unsignedTinyInteger('dependents')->nullable(); // jumlah tanggungan

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penerima_profile');
    }
};
