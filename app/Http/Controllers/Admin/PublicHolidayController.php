<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PublicHoliday;
use Illuminate\Http\Request;

class PublicHolidayController extends Controller
{
    /**
     * Tampilkan daftar tahun yang ada, dan siapkan data untuk view.
     */
    public function index(Request $request)
    {
        // Ambil semua tahun yang sudah punya data
        $existingYears = PublicHoliday::select('tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun')
            ->toArray();

        // Selalu sertakan tahun berjalan
        $currentYear = now()->year;
        if (!in_array($currentYear, $existingYears)) {
            array_unshift($existingYears, $currentYear);
        }

        // Tambahkan 1 tahun ke depan sebagai opsi
        $nextYear = $currentYear + 1;
        if (!in_array($nextYear, $existingYears)) {
            array_unshift($existingYears, $nextYear);
        }

        rsort($existingYears);

        return view('pages.admin.public-holidays.index', compact('existingYears', 'currentYear'));
    }

    /**
     * Kembalikan semua baris untuk satu tahun (JSON).
     */
    public function getByYear(int $tahun)
    {
        $rows = PublicHoliday::where('tahun', $tahun)
            ->orderBy('bulan')
            ->get(['bulan', 'tanggal_merah']);

        // Buat array lengkap 12 bulan
        $result = [];
        for ($m = 1; $m <= 12; $m++) {
            $row = $rows->firstWhere('bulan', $m);
            $result[] = [
                'bulan'         => $m,
                'tanggal_merah' => $row?->tanggal_merah ?? '',
            ];
        }

        return response()->json($result);
    }

    /**
     * Simpan/update satu baris (satu bulan).
     */
    public function upsert(Request $request)
    {
        $request->validate([
            'tahun'         => 'required|integer|min:2000|max:2100',
            'bulan'         => 'required|integer|min:1|max:12',
            'tanggal_merah' => 'nullable|string|max:200',
        ]);

        PublicHoliday::updateOrCreate(
            [
                'tahun' => $request->tahun,
                'bulan' => $request->bulan,
            ],
            [
                'tanggal_merah' => PublicHoliday::sanitizeDates($request->tanggal_merah),
            ]
        );

        return response()->json(['success' => true]);
    }

    /**
     * Simpan seluruh 12 bulan sekaligus untuk satu tahun (bulk).
     */
    public function bulkSave(Request $request)
    {
        $request->validate([
            'tahun'               => 'required|integer|min:2000|max:2100',
            'rows'                => 'required|array|size:12',
            'rows.*.bulan'        => 'required|integer|min:1|max:12',
            'rows.*.tanggal_merah'=> 'nullable|string|max:200',
        ]);

        foreach ($request->rows as $row) {
            PublicHoliday::updateOrCreate(
                [
                    'tahun' => $request->tahun,
                    'bulan' => (int) $row['bulan'],
                ],
                [
                    'tanggal_merah' => PublicHoliday::sanitizeDates($row['tanggal_merah'] ?? null),
                ]
            );
        }

        return response()->json(['success' => true]);
    }
}
