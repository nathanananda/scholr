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
            $table->decimal('raw_value', 20, 4)->change();
        });
    }

    public function down(): void
    {
        Schema::table('saw_result', function (Blueprint $table) {
            $table->decimal('raw_value', 8, 2)->change();
        });
    }
};
