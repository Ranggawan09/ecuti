<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
        $table->id();

        // Identitas dasar
        $table->string('nama');
        $table->string('nip')->unique();

        // Kontak
        $table->string('email')->nullable()->unique();
        $table->string('whatsapp')->nullable();

        // Autentikasi
        $table->string('password');

        // Role & akses
        $table->enum('role', [
            'pegawai',
            'atasan_langsung',
            'atasan_tidak_langsung',
            'kepegawaian',
            'admin'
        ]);

        // Profil
        $table->string('profile_photo_path')->nullable();

        // Standar Laravel
        $table->timestamp('email_verified_at')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
