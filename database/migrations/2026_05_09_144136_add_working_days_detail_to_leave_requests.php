<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            // Total hari kalender (inklusif mulai-selesai)
            $table->unsignedSmallInteger('calendar_days')->nullable()->after('total_days');
            // Hari Sabtu/Minggu yang dilewati (tidak dipotong jatah)
            $table->unsignedSmallInteger('skipped_weekend')->nullable()->after('calendar_days');
            // Tanggal merah yang dilewati (tidak dipotong jatah)
            $table->unsignedSmallInteger('skipped_holiday')->nullable()->after('skipped_weekend');
        });
    }

    public function down(): void
    {
        Schema::table('leave_requests', function (Blueprint $table) {
            $table->dropColumn(['calendar_days', 'skipped_weekend', 'skipped_holiday']);
        });
    }
};
