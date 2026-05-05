{{-- resources/views/pages/atasan_langsung/approvals/show.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Detail Cuti Pegawai</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400">

<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

    <!-- Page header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <a href="{{ route('atasan-langsung.approvals.index') }}" class="btn bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700/60 hover:border-gray-300 dark:hover:border-gray-600 text-gray-600 dark:text-gray-300">
                <svg class="fill-current shrink-0 mr-2" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M6.6 13.4L1.2 8l5.4-5.4 1.4 1.4L4.4 7.6h10.4v2H4.4l3.6 3.6-1.4 1.4z" />
                </svg>
                <span>Kembali</span>
            </a>
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Detail Cuti Pegawai</h1>
        </div>
    </div>

    <!-- Detail Card -->
    @include('pages.atasan_langsung.approvals._show_partial')

</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radios = document.querySelectorAll('input[name="decision"]');
    const catatanSection = document.getElementById('catatanSection');
    const alasanSection = document.getElementById('alasanSection');
    const alasanInput = document.getElementById('alasan_penolakan');
    const form = document.getElementById('approvalForm');

    radios.forEach(radio => {
        radio.addEventListener('change', function() {
            if (this.value === 'approve') {
                catatanSection.classList.remove('hidden');
                alasanSection.classList.add('hidden');
                alasanInput.removeAttribute('required');
                form.action = "{{ route('atasan-langsung.approvals.approve', $leaveRequest) }}";
            } else {
                alasanSection.classList.remove('hidden');
                catatanSection.classList.add('hidden');
                alasanInput.setAttribute('required', 'required');
                form.action = "{{ route('atasan-langsung.approvals.reject', $leaveRequest) }}";
            }
        });
    });

    form.addEventListener('submit', function(e) {
        const decision = document.querySelector('input[name="decision"]:checked');
        
        if (!decision) {
            e.preventDefault();
            alert('Silakan pilih keputusan terlebih dahulu');
            return;
        }

        if (decision.value === 'reject') {
            const alasan = alasanInput.value.trim();
            if (alasan.length < 10) {
                e.preventDefault();
                alert('Alasan penolakan harus diisi minimal 10 karakter');
                return;
            }
        }

        const confirmMsg = decision.value === 'approve' 
            ? 'Apakah Anda yakin ingin menyetujui permohonan cuti ini?' 
            : 'Apakah Anda yakin ingin menolak permohonan cuti ini?';
        
        if (!confirm(confirmMsg)) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>