<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PengajuanCuti extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_cuti';

    protected $fillable = [
        'pegawai_id',
        'jenis_cuti',
        'tanggal_mulai',
        'tanggal_selesai',
        'durasi_hari',
        'alasan',
        'alamat_cuti',
        'nomor_telepon',
        'dokumen_pendukung',
        'status',
        'disetujui_oleh',
        'tanggal_disetujui',
        'catatan_persetujuan',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'tanggal_disetujui' => 'datetime',
    ];

    /**
     * Relasi ke tabel Pegawai
     */
    public function pegawai(): BelongsTo
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_id');
    }

    /**
     * Relasi ke User yang menyetujui
     */
    public function penyetuju(): BelongsTo
    {
        return $this->belongsTo(User::class, 'disetujui_oleh');
    }

    /**
     * Accessor untuk label jenis cuti
     */
    public function getJenisCutiLabelAttribute(): string
    {
        $labels = [
            'tahunan' => 'Cuti Tahunan',
            'sakit' => 'Cuti Sakit',
            'melahirkan' => 'Cuti Melahirkan',
            'menikah' => 'Cuti Menikah',
            'khusus' => 'Cuti Khusus',
            'alasan_penting' => 'Cuti Alasan Penting',
        ];

        return $labels[$this->jenis_cuti] ?? $this->jenis_cuti;
    }

    /**
     * Accessor untuk label status
     */
    public function getStatusLabelAttribute(): string
    {
        $labels = [
            'pending' => 'Menunggu Persetujuan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
        ];

        return $labels[$this->status] ?? $this->status;
    }

    /**
     * Accessor untuk badge status
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => 'warning',
            'disetujui' => 'success',
            'ditolak' => 'danger',
        ];

        return $badges[$this->status] ?? 'secondary';
    }

    /**
     * Scope untuk filter berdasarkan pegawai
     */
    public function scopeByPegawai($query, $pegawaiId)
    {
        return $query->where('pegawai_id', $pegawaiId);
    }

    /**
     * Scope untuk filter berdasarkan status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope untuk cuti tahun ini
     */
    public function scopeTahunIni($query)
    {
        return $query->whereYear('tanggal_mulai', date('Y'));
    }
}