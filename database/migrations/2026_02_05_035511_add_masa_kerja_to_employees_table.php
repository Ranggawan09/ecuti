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
        Schema::table('employees', function (Blueprint $table) {
            $table->integer('masa_kerja_tahun')->nullable()->after('unit_kerja');
            $table->integer('masa_kerja_bulan')->nullable()->after('masa_kerja_tahun');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['masa_kerja_tahun', 'masa_kerja_bulan']);
        });
    }
};
