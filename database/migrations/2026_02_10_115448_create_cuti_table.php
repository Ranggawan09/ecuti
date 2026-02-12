<?php
// database/migrations/xxxx_xx_xx_create_pengajuan_cuti_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengajuan_cuti', function (Blueprint $table) {
            $table->id();
            
            // Foreign key ke tabel users (bukan pegawai)
            // Pastikan tipe data sama dengan tabel users.id
            $table->unsignedBigInteger('pegawai_id');
            $table->unsignedBigInteger('atasan_id')->nullable();
            
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('jumlah_hari');
            $table->string('jenis_cuti');
            $table->text('keterangan')->nullable();
            $table->enum('status', ['menunggu', 'disetujui', 'ditolak'])->default('menunggu');
            $table->text('approval_notes')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            // Foreign key constraints
            // Pastikan tabel 'users' sudah ada sebelum migration ini
            $table->foreign('pegawai_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
                  
            $table->foreign('atasan_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
                  
            $table->foreign('approved_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengajuan_cuti');
    }
};