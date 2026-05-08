<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_holidays', function (Blueprint $table) {
            $table->id();
            $table->smallInteger('tahun');
            $table->tinyInteger('bulan'); // 1–12
            // Tanggal disimpan sebagai angka tanggal dipisah koma, mis. "1,2,17"
            $table->string('tanggal_merah', 100)->nullable();
            $table->string('cuti_bersama', 100)->nullable();
            $table->timestamps();

            $table->unique(['tahun', 'bulan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_holidays');
    }
};
