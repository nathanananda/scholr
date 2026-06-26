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
        Schema::table('saw_result', function (Blueprint $table) {
            $table->decimal('raw_value', 15, 4)->change();
            $table->decimal('weighted_value', 15, 4)->change();
        });

        Schema::table('smart_results', function (Blueprint $table) {
            $table->decimal('raw_value', 15, 4)->change();
            $table->decimal('weighted_value', 15, 4)->change();
        });
    }

    public function down(): void
    {
        Schema::table('saw_result', function (Blueprint $table) {
            $table->decimal('raw_value', 10, 4)->change();
            $table->decimal('weighted_value', 10, 4)->change();
        });

        Schema::table('smart_results', function (Blueprint $table) {
            $table->decimal('raw_value', 10, 4)->change();
            $table->decimal('weighted_value', 10, 4)->change();
        });
    }
};
