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
        Schema::table('scholarships', function (Blueprint $table) {
            $table->enum('education_level', ['sd', 'smp', 'sma', 'd3', 's1', 's2', 's3', 'all'])
                ->default('all')
                ->after('category');
            $table->decimal('benefit_amount', 15, 2)->nullable()->after('quota');
            $table->enum('benefit_period', ['monthly', 'per_semester', 'yearly', 'once'])
                ->nullable()
                ->after('benefit_amount');
            $table->text('benefit_detail')->nullable()->after('benefit_period');
            $table->timestamp('announcement_date')->nullable()->after('end_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholarships', function (Blueprint $table) {
            $table->dropColumn([
                'education_level',
                'benefit_amount',
                'benefit_period',
                'benefit_detail',
                'announcement_date',
            ]);
        });
    }
};
