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
        Schema::create('employees', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->cascadeOnDelete();
    $table->string('jabatan');
    $table->string('golongan');
    $table->string('unit_kerja');
    $table->foreignId('atasan_langsung_id')
          ->nullable()
          ->references('id')->on('users');
    $table->foreignId('atasan_tidak_langsung_id')
          ->nullable()
          ->references('id')->on('users');
    $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
