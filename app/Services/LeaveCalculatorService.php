<?php

namespace App\Services;

use App\Models\PublicHoliday;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class LeaveCalculatorService
{
    /**
     * Hitung hari kerja efektif antara dua tanggal.
     * Hari Sabtu, Minggu, dan Tanggal Merah (public_holidays.tanggal_merah)
     * tidak dihitung sebagai hari cuti.
     *
     * @param  string $startDate  Format: Y-m-d
     * @param  string $endDate    Format: Y-m-d
     * @return array [
     *     'working_days'      => int,   // hari kerja yang dipotong dari jatah
     *     'calendar_days'     => int,   // total hari kalender (inklusif)
     *     'skipped_weekend'   => int,   // hari Sabtu/Minggu yang dilewati
     *     'skipped_holiday'   => int,   // tanggal merah yang dilewati
     * ]
     */
    public static function calculate(string $startDate, string $endDate): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end   = Carbon::parse($endDate)->startOfDay();

        $calendarDays   = $start->diffInDays($end) + 1;
        $skippedWeekend = 0;
        $skippedHoliday = 0;
        $workingDays    = 0;

        // Kumpulkan semua tanggal merah yang diperlukan dari DB
        // Ambil berdasarkan tahun & bulan yang dicakup rentang cuti
        $publicHolidays = self::fetchHolidayDates($start, $end);

        $current = $start->copy();
        while ($current->lte($end)) {
            $dayOfWeek = $current->dayOfWeek; // 0=Minggu, 6=Sabtu

            if ($dayOfWeek === 0 || $dayOfWeek === 6) {
                // Sabtu atau Minggu
                $skippedWeekend++;
            } elseif ($publicHolidays->contains($current->format('Y-m-d'))) {
                // Tanggal merah
                $skippedHoliday++;
            } else {
                // Hari kerja biasa
                $workingDays++;
            }

            $current->addDay();
        }

        return [
            'working_days'    => $workingDays,
            'calendar_days'   => $calendarDays,
            'skipped_weekend' => $skippedWeekend,
            'skipped_holiday' => $skippedHoliday,
        ];
    }

    /**
     * Ambil semua tanggal merah dari DB untuk rentang bulan yang dicakup.
     * Mengembalikan Collection berisi string tanggal format 'Y-m-d'.
     */
    private static function fetchHolidayDates(Carbon $start, Carbon $end): Collection
    {
        // Kumpulkan semua kombinasi tahun-bulan yang perlu dicek
        $pairs = collect();
        $cur   = $start->copy()->startOfMonth();
        while ($cur->lte($end)) {
            $pairs->push(['tahun' => $cur->year, 'bulan' => $cur->month]);
            $cur->addMonth();
        }

        if ($pairs->isEmpty()) {
            return collect();
        }

        // Query sekali untuk semua bulan yang dibutuhkan
        $rows = PublicHoliday::where(function ($q) use ($pairs) {
            foreach ($pairs as $pair) {
                $q->orWhere(function ($q2) use ($pair) {
                    $q2->where('tahun', $pair['tahun'])
                       ->where('bulan', $pair['bulan']);
                });
            }
        })->get(['tahun', 'bulan', 'tanggal_merah']);

        $dates = collect();
        foreach ($rows as $row) {
            if (empty($row->tanggal_merah)) continue;

            $tanggals = array_filter(
                array_map('intval', explode(',', $row->tanggal_merah))
            );

            foreach ($tanggals as $tgl) {
                // Pastikan tanggal valid untuk bulan tersebut
                try {
                    $date = Carbon::createFromDate($row->tahun, $row->bulan, $tgl);
                    $dates->push($date->format('Y-m-d'));
                } catch (\Exception $e) {
                    // Tanggal tidak valid, lewati
                }
            }
        }

        return $dates;
    }

    /**
     * Kembalikan array semua tanggal merah (format 'Y-m-d') untuk tahun tertentu.
     * Digunakan untuk mengirim ke frontend (JavaScript).
     */
    public static function getHolidayDatesForYear(int $year): array
    {
        $rows = PublicHoliday::where('tahun', $year)->get(['bulan', 'tanggal_merah']);

        $dates = [];
        foreach ($rows as $row) {
            if (empty($row->tanggal_merah)) continue;

            $tanggals = array_filter(
                array_map('intval', explode(',', $row->tanggal_merah))
            );

            foreach ($tanggals as $tgl) {
                try {
                    $date = Carbon::createFromDate($year, $row->bulan, $tgl);
                    $dates[] = $date->format('Y-m-d');
                } catch (\Exception $e) {
                    // skip
                }
            }
        }

        return $dates;
    }

    /**
     * Buat keterangan singkat dari hasil kalkulasi.
     * Contoh: "2 weekend + 1 libur nasional dilewati"
     */
    public static function describe(array $result): string
    {
        $parts = [];

        if ($result['skipped_weekend'] > 0) {
            $parts[] = $result['skipped_weekend'] . ' weekend';
        }
        if ($result['skipped_holiday'] > 0) {
            $parts[] = $result['skipped_holiday'] . ' libur nasional';
        }

        if (empty($parts)) {
            return '';
        }

        return implode(' + ', $parts) . ' tidak dihitung';
    }
}
