<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penyalur_profile', function (Blueprint $table) {
            $table->string('organization_name')->nullable()->change();
            $table->string('organization_type')->nullable()->change();
            $table->text('address')->nullable()->change();
            $table->string('phone')->nullable()->change();
            $table->string('pic_name')->nullable()->change();
            $table->string('pic_phone')->nullable()->change();
            $table->text('verification_note')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('penyalur_profile', function (Blueprint $table) {
            $table->string('organization_name')->nullable(false)->change();
            $table->string('organization_type')->nullable(false)->change();
            $table->text('address')->nullable(false)->change();
            $table->string('phone')->nullable(false)->change();
            $table->string('pic_name')->nullable(false)->change();
            $table->string('pic_phone')->nullable(false)->change();
            $table->text('verification_note')->nullable(false)->change();
        });
    }
};
