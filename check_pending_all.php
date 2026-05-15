<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = \App\Models\User::where('email', 'pegawai@go.id')->first();
$e = $u->employee;

$pendingAll = $e->leaveRequests()
    ->whereIn('status', ['menunggu_atasan_langsung', 'menunggu_atasan_tidak_langsung'])
    ->with('leaveType')
    ->get();

echo "All Pending Requests:\n";
foreach ($pendingAll as $p) {
    echo "ID {$p->id}: Type={$p->leaveType->name}, Days={$p->total_days}\n";
}
echo "Total Pending Days (All Types): " . $pendingAll->sum('total_days') . "\n";
