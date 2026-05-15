<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = \App\Models\User::where('email', 'pegawai@go.id')->first();
$e = $u->employee;
$t = \App\Models\LeaveType::where('name', 'like', '%Tahunan%')->first();

$balances = $e->leaveBalances()
    ->where('leave_type_id', $t->id)
    ->whereIn('year', [2024, 2025, 2026])
    ->get();

$pending = $e->leaveRequests()
    ->where('leave_type_id', $t->id)
    ->whereIn('status', ['menunggu_atasan_langsung', 'menunggu_atasan_tidak_langsung'])
    ->get();

echo "Balances:\n";
foreach ($balances as $b) {
    echo "Year {$b->year}: Rem={$b->remaining_days}, Carr={$b->carried_over_days}\n";
}
echo "Total Rem: " . $balances->sum('remaining_days') . "\n";
echo "Total Carr: " . $balances->sum('carried_over_days') . "\n";
echo "Pending Requests:\n";
foreach ($pending as $p) {
    echo "ID {$p->id}: {$p->total_days} days\n";
}
echo "Total Pending Days: " . $pending->sum('total_days') . "\n";
