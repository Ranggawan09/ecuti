<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PublicHoliday extends Model
{
    protected $fillable = [
        'tahun',
        'bulan',
        'tanggal_merah',
        'cuti_bersama',
    ];

    protected $casts = [
        'tahun'  => 'integer',
        'bulan'  => 'integer',
    ];

    /**
     * Kembalikan tanggal merah sebagai array integer.
     */
    public function getTanggalMerahArrayAttribute(): array
    {
        if (empty($this->tanggal_merah)) return [];
        return array_filter(array_map('intval', explode(',', $this->tanggal_merah)));
    }

    /**
     * Kembalikan cuti bersama sebagai array integer.
     */
    public function getCutiBersamaArrayAttribute(): array
    {
        if (empty($this->cuti_bersama)) return [];
        return array_filter(array_map('intval', explode(',', $this->cuti_bersama)));
    }

    /**
     * Sanitasi string tanggal: pisah koma, buang spasi dan duplikat, sort, gabung kembali.
     */
    public static function sanitizeDates(?string $input): ?string
    {
        if (is_null($input) || trim($input) === '') return null;
        $dates = array_filter(
            array_unique(array_map('intval', preg_split('/[\s,]+/', trim($input))))
        );
        sort($dates);
        return implode(',', $dates) ?: null;
    }
}
