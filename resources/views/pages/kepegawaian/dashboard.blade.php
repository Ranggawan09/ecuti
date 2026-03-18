<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

        {{-- Header --}}
        <div class="sm:flex sm:justify-between sm:items-center mb-8">
            <div class="mb-4 sm:mb-0">
                <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">Dashboard Kepegawaian</h1>
            </div>
        </div>

        {{-- ======================================================== --}}
        {{-- FORM: No Urut & Nomor Surat Surat Terbaru                --}}
        {{-- ======================================================== --}}
        <div class="max-w-2xl">
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">

                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-indigo-100 dark:bg-indigo-900/50 rounded-xl flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-base font-semibold text-gray-800 dark:text-gray-100">Informasi Surat Terbaru</h2>
                        <p class="text-xs text-gray-400 dark:text-gray-500">
                            @if($latestPrinted)
                                Berdasarkan surat terakhir dicetak:
                                <span class="font-medium text-gray-600 dark:text-gray-300">
                                    {{ optional($latestPrinted->employee)->user->nama ?? '-' }}
                                </span>
                                &mdash;
                                {{ $latestPrinted->printed_at ? \Carbon\Carbon::parse($latestPrinted->printed_at)->locale('id')->isoFormat('D MMM YYYY') : '-' }}
                            @else
                                Belum ada surat yang dicetak.
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Alert --}}
                <div id="saveAlert" class="hidden mb-4 p-3 rounded-lg text-sm"></div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                    {{-- No Urut --}}
                    <div>
                        <label for="noUrutInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            No Urut Surat
                        </label>
                        <input
                            type="number"
                            id="noUrutInput"
                            min="1"
                            value="{{ $latestPrinted->no_urut ?? '' }}"
                            placeholder="Contoh: 1"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >
                    </div>

                    {{-- Nomor Surat --}}
                    <div>
                        <label for="nomorSuratInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Nomor Surat
                        </label>
                        <input
                            type="text"
                            id="nomorSuratInput"
                            value="{{ $latestPrinted->nomor_surat ?? '' }}"
                            placeholder="Contoh: 1/KPN.W14.U5/KP5.3/III/2026"
                            class="w-full border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2.5 text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition"
                        >
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button onclick="saveLetterNumber()"
                            id="saveBtn"
                            @if(!$latestPrinted) disabled @endif
                            class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg transition">
                        <svg id="saveSpinner" class="w-4 h-4 animate-spin hidden" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                        </svg>
                        Simpan
                    </button>

                    @if($latestPrinted)
                    <a href="{{ route('kepegawaian.leave-requests.print', $latestPrinted->id) }}"
                       target="_blank"
                       class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-medium border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-lg transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Ulang
                    </a>
                    @endif
                </div>

            </div>
        </div>

    </div>

    <script>
        const leaveRequestId = {{ $latestPrinted->id ?? 'null' }};
        const baseUrl = "{{ url('/kepegawaian/leave-requests') }}";
        const csrfToken = "{{ csrf_token() }}";

        async function saveLetterNumber() {
            if (!leaveRequestId) return;

            const noUrut     = document.getElementById('noUrutInput').value;
            const nomorSurat = document.getElementById('nomorSuratInput').value;
            const spinner    = document.getElementById('saveSpinner');
            const btn        = document.getElementById('saveBtn');
            const alertEl    = document.getElementById('saveAlert');

            if (!noUrut || !nomorSurat) {
                showAlert('Harap isi semua field.', 'error');
                return;
            }

            spinner.classList.remove('hidden');
            btn.disabled = true;

            try {
                const res = await fetch(`${baseUrl}/${leaveRequestId}/letter-number`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ no_urut: noUrut, nomor_surat: nomorSurat })
                });

                const data = await res.json();

                if (data.success) {
                    showAlert('Berhasil disimpan!', 'success');
                } else {
                    throw new Error(data.message || 'Gagal menyimpan.');
                }
            } catch (err) {
                showAlert(err.message, 'error');
            } finally {
                spinner.classList.add('hidden');
                btn.disabled = false;
            }
        }

        function showAlert(message, type) {
            const alertEl = document.getElementById('saveAlert');
            alertEl.textContent = message;
            alertEl.className = type === 'success'
                ? 'mb-4 p-3 rounded-lg text-sm bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300'
                : 'mb-4 p-3 rounded-lg text-sm bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300';
            alertEl.classList.remove('hidden');
            if (type === 'success') {
                setTimeout(() => alertEl.classList.add('hidden'), 3000);
            }
        }
    </script>
</x-app-layout>
