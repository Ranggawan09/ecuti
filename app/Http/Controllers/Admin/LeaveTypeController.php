<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LeaveTypesExport;

class LeaveTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $leaveTypes = LeaveType::orderBy('name')->get();

        return view('pages.admin.leave-types.index', compact('leaveTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('leave_types', 'name')],
            'max_days' => 'nullable|integer|min:1',
            'deduct_balance' => 'required|in:0,1',
        ]);

        $leaveType = LeaveType::create([
            'name' => $validated['name'],
            'max_days' => $validated['max_days'] ?? null,
            'deduct_balance' => (bool) $validated['deduct_balance'],
        ]);

        return response()->json($leaveType, 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('leave_types', 'name')->ignore($leaveType->id)],
            'max_days' => 'nullable|integer|min:1',
            'deduct_balance' => 'required|in:0,1',
        ]);

        $leaveType->update([
            'name' => $validated['name'],
            'max_days' => $validated['max_days'] ?? null,
            'deduct_balance' => (bool) $validated['deduct_balance'],
        ]);

        return response()->json($leaveType);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();

        return response()->json(['message' => 'Berhasil dihapus'], 200);
    }

    /**
     * Export leave types data.
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'excel');

        if ($format === 'pdf') {
            return $this->exportPdf();
        }

        return $this->exportExcel();
    }

    private function exportExcel()
    {
        return Excel::download(new LeaveTypesExport, 'jenis-cuti-' . date('Y-m-d') . '.xlsx');
    }

    private function exportPdf()
    {
        $leaveTypes = LeaveType::orderBy('name')->get();

        $pdf = Pdf::loadView('pages.admin.leave-types.pdf', compact('leaveTypes'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('jenis-cuti-' . date('Y-m-d') . '.pdf');
    }
}