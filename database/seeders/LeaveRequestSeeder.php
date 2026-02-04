<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LeaveRequest;
use App\Models\Employee;
use App\Models\LeaveType;

class LeaveRequestSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::with('user')->get();
        $leaveTypes = LeaveType::all();

        // Sample leave requests with varied data
        $sampleRequests = [
            [
                'employee_index' => 0,
                'leave_type' => 'Cuti Tahunan',
                'start_date' => '2025-12-24',
                'end_date' => '2025-12-26',
                'total_days' => 3,
                'reason' => 'Liburan akhir tahun bersama keluarga',
                'address' => 'Surabaya, Jawa Timur',
                'status' => 'menunggu_atasan_langsung',
            ],
            [
                'employee_index' => 1,
                'leave_type' => 'Cuti Sakit',
                'start_date' => '2025-11-15',
                'end_date' => '2025-11-17',
                'total_days' => 3,
                'reason' => 'Sakit demam dan flu',
                'address' => 'Jombang, Jawa Timur',
                'status' => 'disetujui',
            ],
            [
                'employee_index' => 2,
                'leave_type' => 'Cuti Tahunan',
                'start_date' => '2026-01-10',
                'end_date' => '2026-01-15',
                'total_days' => 6,
                'reason' => 'Keperluan keluarga di kampung halaman',
                'address' => 'Malang, Jawa Timur',
                'status' => 'menunggu_atasan_tidak_langsung',
            ],
            [
                'employee_index' => 3,
                'leave_type' => 'Cuti Melahirkan',
                'start_date' => '2025-10-01',
                'end_date' => '2025-12-31',
                'total_days' => 92,
                'reason' => 'Cuti melahirkan anak pertama',
                'address' => 'Jombang, Jawa Timur',
                'status' => 'disetujui',
            ],
            [
                'employee_index' => 4,
                'leave_type' => 'Cuti Tahunan',
                'start_date' => '2025-08-17',
                'end_date' => '2025-08-19',
                'total_days' => 3,
                'reason' => 'Perayaan hari kemerdekaan',
                'address' => 'Jakarta',
                'status' => 'ditolak',
            ],
            [
                'employee_index' => 5,
                'leave_type' => 'Cuti Besar',
                'start_date' => '2026-02-10',
                'end_date' => '2026-02-20',
                'total_days' => 11,
                'reason' => 'Umroh bersama keluarga',
                'address' => 'Makkah, Arab Saudi',
                'status' => 'draft',
            ],
            [
                'employee_index' => 0,
                'leave_type' => 'Cuti Sakit',
                'start_date' => '2025-09-05',
                'end_date' => '2025-09-06',
                'total_days' => 2,
                'reason' => 'Kontrol kesehatan rutin',
                'address' => 'Jombang, Jawa Timur',
                'status' => 'disetujui',
            ],
            [
                'employee_index' => 1,
                'leave_type' => 'Cuti Tahunan',
                'start_date' => '2026-03-01',
                'end_date' => '2026-03-05',
                'total_days' => 5,
                'reason' => 'Liburan keluarga ke Bali',
                'address' => 'Bali',
                'status' => 'menunggu_atasan_langsung',
            ],
        ];

        foreach ($sampleRequests as $request) {
            // Get employee by index (cycle through if not enough employees)
            $employee = $employees[$request['employee_index'] % $employees->count()];
            
            // Find leave type
            $leaveType = $leaveTypes->firstWhere('name', $request['leave_type']);
            
            if ($employee && $leaveType) {
                LeaveRequest::create([
                    'employee_id' => $employee->id,
                    'leave_type_id' => $leaveType->id,
                    'start_date' => $request['start_date'],
                    'end_date' => $request['end_date'],
                    'total_days' => $request['total_days'],
                    'reason' => $request['reason'],
                    'address_during_leave' => $request['address'],
                    'status' => $request['status'],
                ]);
            }
        }
    }
}
