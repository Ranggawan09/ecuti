<div class="min-w-fit">
    <!-- Sidebar backdrop (mobile only) -->
    <div
        class="fixed inset-0 bg-gray-900/30 z-40 lg:hidden lg:z-auto transition-opacity duration-200"
        :class="sidebarOpen ? 'opacity-100' : 'opacity-0 pointer-events-none'"
        aria-hidden="true"
        x-cloak
    ></div>

    <!-- Sidebar -->
    <div
        id="sidebar"
        class="flex lg:flex! flex-col absolute z-40 left-0 top-0 lg:static lg:left-auto lg:top-auto lg:translate-x-0 h-[100dvh] overflow-y-scroll lg:overflow-y-auto no-scrollbar w-64 lg:w-20 lg:sidebar-expanded:!w-64 2xl:w-64! shrink-0 bg-white dark:bg-gray-800 p-4 transition-all duration-200 ease-in-out border-r border-gray-200 dark:border-gray-700/60"
        :class="sidebarOpen ? 'max-lg:translate-x-0' : 'max-lg:-translate-x-64'"
        @click.outside="sidebarOpen = false"
        @keydown.escape.window="sidebarOpen = false"
    >

        <!-- Sidebar header -->
        <div class="flex justify-between mb-10 pr-3 sm:px-2">
            <!-- Close button -->
            <button class="lg:hidden text-gray-500 hover:text-gray-400" @click.stop="sidebarOpen = !sidebarOpen" aria-controls="sidebar" :aria-expanded="sidebarOpen">
                <span class="sr-only">Close sidebar</span>
                <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path d="M10.7 18.7l1.4-1.4L7.8 13H20v-2H7.8l4.3-4.3-1.4-1.4L4 12z" />
                </svg>
            </button>
            <!-- Logo -->
            @php
                $dashboardRoute = match(auth()->user()->role) {
                    'pegawai' => route('pegawai.dashboard'),
                    'atasan_langsung' => route('atasan-langsung.dashboard'),
                    'atasan_tidak_langsung' => route('atasan-tidak-langsung.dashboard'),
                    'kepegawaian' => route('kepegawaian.dashboard'),
                    'admin' => route('admin.dashboard'),
                    default => '#',
                };
            @endphp
            <a class="block" href="{{ $dashboardRoute }}">
                <svg class="fill-violet-500" xmlns="http://www.w3.org/2000/svg" width="32" height="32">
                    <path d="M31.956 14.8C31.372 6.92 25.08.628 17.2.044V5.76a9.04 9.04 0 0 0 9.04 9.04h5.716ZM14.8 26.24v5.716C6.92 31.372.63 25.08.044 17.2H5.76a9.04 9.04 0 0 1 9.04 9.04Zm11.44-9.04h5.716c-.584 7.88-6.876 14.172-14.756 14.756V26.24a9.04 9.04 0 0 1 9.04-9.04ZM.044 14.8C.63 6.92 6.92.628 14.8.044V5.76a9.04 9.04 0 0 1-9.04 9.04H.044Z" />
                </svg>                
            </a>
        </div>

        <!-- Links -->
        <div class="space-y-8">
            
            {{-- ================= PEGAWAI ================= --}}
            @if(auth()->user()->role === 'pegawai')
            <!-- Menu group -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Menu Pegawai</span>
                </h3>
                <ul class="mt-3">
                    <!-- Dashboard -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('pegawai.dashboard')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('pegawai.dashboard')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('pegawai.dashboard') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('pegawai.dashboard')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z" />
                                    <path d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <!-- Ajukan Cuti -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('pegawai.leave-requests.create')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('pegawai.leave-requests.create')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('pegawai.leave-requests.create') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('pegawai.leave-requests.create')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M14.3 2.3L5 11.6 1.7 8.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4l4 4c.2.2.4.3.7.3.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Ajukan Cuti</span>
                            </div>
                        </a>
                    </li>
                    <!-- Riwayat Cuti -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('pegawai.leave-requests.index')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('pegawai.leave-requests.index')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('pegawai.leave-requests.index') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('pegawai.leave-requests.index')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M14 0H2C.9 0 0 .9 0 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V2c0-1.1-.9-2-2-2ZM2 14V2h12v12H2Z" />
                                    <path d="M4 8h3V5H4v3Zm0 4h3V9H4v3Zm5-8v3h3V4H9Zm0 4h3v3H9V8Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Riwayat Cuti</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            @endif

            {{-- ================= ATASAN LANGSUNG ================= --}}
            @if(auth()->user()->role === 'atasan_langsung')
            <!-- Menu group -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Atasan Langsung</span>
                </h3>
                <ul class="mt-3">
                    <!-- Dashboard -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('atasan-langsung.dashboard')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('atasan-langsung.dashboard')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('atasan-langsung.dashboard') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('atasan-langsung.dashboard')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z" />
                                    <path d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <!-- Persetujuan Cuti -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('atasan-langsung.approvals.index')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('atasan-langsung.approvals.index')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('atasan-langsung.approvals.index') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('atasan-langsung.approvals.index')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M14.3 2.3L5 11.6 1.7 8.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4l4 4c.2.2.4.3.7.3.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Persetujuan Cuti</span>
                            </div>
                        </a>
                    </li>
                    <!-- Riwayat Cuti -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('atasan-langsung.leave-history.index')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('atasan-langsung.leave-history.index')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('atasan-langsung.leave-history.index') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('atasan-langsung.leave-history.index')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M14 0H2C.9 0 0 .9 0 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V2c0-1.1-.9-2-2-2ZM2 14V2h12v12H2Z" />
                                    <path d="M4 8h3V5H4v3Zm0 4h3V9H4v3Zm5-8v3h3V4H9Zm0 4h3v3H9V8Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Riwayat Cuti</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            @endif

            {{-- ================= ATASAN TIDAK LANGSUNG ================= --}}
            @if(auth()->user()->role === 'atasan_tidak_langsung')
            <!-- Menu group -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Atasan Tidak Langsung</span>
                </h3>
                <ul class="mt-3">
                    <!-- Dashboard -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('atasan-tidak-langsung.dashboard')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('atasan-tidak-langsung.dashboard')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('atasan-tidak-langsung.dashboard') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('atasan-tidak-langsung.dashboard')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z" />
                                    <path d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <!-- Persetujuan Final -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('atasan-tidak-langsung.approvals.index')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('atasan-tidak-langsung.approvals.index')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('atasan-tidak-langsung.approvals.index') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('atasan-tidak-langsung.approvals.index')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M14.3 2.3L5 11.6 1.7 8.3c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4l4 4c.2.2.4.3.7.3.3 0 .5-.1.7-.3l10-10c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Persetujuan Final</span>
                            </div>
                        </a>
                    </li>
                    <!-- Riwayat Cuti -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('atasan-tidak-langsung.leave-history.index')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('atasan-tidak-langsung.leave-history.index')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('atasan-tidak-langsung.leave-history.index') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('atasan-tidak-langsung.leave-history.index')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M14 0H2C.9 0 0 .9 0 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V2c0-1.1-.9-2-2-2ZM2 14V2h12v12H2Z" />
                                    <path d="M4 8h3V5H4v3Zm0 4h3V9H4v3Zm5-8v3h3V4H9Zm0 4h3v3H9V8Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Riwayat Cuti</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            @endif

            {{-- ================= KEPEGAWAIAN ================= --}}
            @if(auth()->user()->role === 'kepegawaian')
            <!-- Menu group -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Kepegawaian</span>
                </h3>
                <ul class="mt-3">
                    <!-- Dashboard -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('kepegawaian.dashboard')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('kepegawaian.dashboard')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('kepegawaian.dashboard') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('kepegawaian.dashboard')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z" />
                                    <path d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <!-- Data Cuti Pegawai -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('kepegawaian.leave-requests.index')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('kepegawaian.leave-requests.index')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('kepegawaian.leave-requests.index') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('kepegawaian.leave-requests.index')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M14 0H2C.9 0 0 .9 0 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V2c0-1.1-.9-2-2-2ZM2 14V2h12v12H2Z" />
                                    <path d="M4 8h3V5H4v3Zm0 4h3V9H4v3Zm5-8v3h3V4H9Zm0 4h3v3H9V8Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Data Cuti Pegawai</span>
                            </div>
                        </a>
                    </li>
                    <!-- Riwayat Pengajuan Cuti -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('kepegawaian.leave-requests.history')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('kepegawaian.leave-requests.history')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('kepegawaian.leave-requests.history') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('kepegawaian.leave-requests.history')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8Zm1 12H7V7h2v5Zm0-6H7V4h2v2Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Riwayat Pengajuan Cuti</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            @endif

            {{-- ================= ADMIN ================= --}}
            @if(auth()->user()->role === 'admin')
            <!-- Menu group -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Administrator</span>
                </h3>
                <ul class="mt-3">
                    <!-- Dashboard -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('admin.dashboard')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('admin.dashboard')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('admin.dashboard') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('admin.dashboard')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M5.936.278A7.983 7.983 0 0 1 8 0a8 8 0 1 1-8 8c0-.722.104-1.413.278-2.064a1 1 0 1 1 1.932.516A5.99 5.99 0 0 0 2 8a6 6 0 1 0 6-6c-.53 0-1.045.076-1.548.21A1 1 0 1 1 5.936.278Z" />
                                    <path d="M6.068 7.482A2.003 2.003 0 0 0 8 10a2 2 0 1 0-.518-3.932L3.707 2.293a1 1 0 0 0-1.414 1.414l3.775 3.775Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Dashboard</span>
                            </div>
                        </a>
                    </li>
                    <!-- Manajemen User -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('admin.users.index')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('admin.users.index')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('admin.users.index') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('admin.users.index')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M11 9.5a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7ZM5.5 7a3.5 3.5 0 1 1-7 0 3.5 3.5 0 0 1 7 0ZM11 10a5 5 0 0 1 5 5v1H6v-1a5 5 0 0 1 5-5ZM5 10a5 5 0 0 0-5 5v1h5v-1a4.99 4.99 0 0 1 .65-2.43A4.984 4.984 0 0 0 5 10Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Manajemen User</span>
                            </div>
                        </a>
                    </li>
                    <!-- Master Jenis Cuti -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('admin.leave-types.index')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('admin.leave-types.index')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('admin.leave-types.index') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('admin.leave-types.index')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M14 0H2C.9 0 0 .9 0 2v12c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V2c0-1.1-.9-2-2-2ZM2 14V2h12v12H2Z" />
                                    <path d="M4 8h3V5H4v3Zm0 4h3V9H4v3Zm5-8v3h3V4H9Zm0 4h3v3H9V8Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Master Jenis Cuti</span>
                            </div>
                        </a>
                    </li>
                    <!-- Log Aktivitas -->
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0 @if(Route::is('admin.activity-logs.index')){{ 'bg-gradient-to-r from-violet-500/[0.12] dark:from-violet-500/[0.24] to-violet-500/[0.04]' }}@endif">
                        <a class="block text-gray-800 dark:text-gray-100 truncate transition @if(!Route::is('admin.activity-logs.index')){{ 'hover:text-gray-900 dark:hover:text-white' }}@endif" href="{{ route('admin.activity-logs.index') }}">
                            <div class="flex items-center">
                                <svg class="shrink-0 fill-current @if(Route::is('admin.activity-logs.index')){{ 'text-violet-500' }}@else{{ 'text-gray-400 dark:text-gray-500' }}@endif" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M2 6H0V2h2v4ZM5 6H3V0h2v6ZM8 6H6V3h2v3ZM11 6H9V1h2v5ZM14 6h-2V4h2v2ZM16 8v7H0V8h16Zm-2 5V10H2v3h12Z" />
                                </svg>
                                <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Log Aktivitas</span>
                            </div>
                        </a>
                    </li>
                </ul>
            </div>
            @endif

            <!-- Logout Section -->
            <div>
                <h3 class="text-xs uppercase text-gray-400 dark:text-gray-500 font-semibold pl-3">
                    <span class="hidden lg:block lg:sidebar-expanded:hidden 2xl:hidden text-center w-6" aria-hidden="true">•••</span>
                    <span class="lg:hidden lg:sidebar-expanded:block 2xl:block">Account</span>
                </h3>
                <ul class="mt-3">
                    <li class="pl-4 pr-3 py-2 rounded-lg mb-0.5 last:mb-0">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left block text-gray-800 dark:text-gray-100 truncate transition hover:text-red-600 dark:hover:text-red-400">
                                <div class="flex items-center">
                                    <svg class="shrink-0 fill-current text-red-500" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                                        <path d="M15 5h-4V4c0-1.1-.9-2-2-2H3C1.9 2 1 2.9 1 4v8c0 1.1.9 2 2 2h6c1.1 0 2-.9 2-2v-1h4c.6 0 1-.4 1-1V6c0-.6-.4-1-1-1ZM9 12H3V4h6v8Zm5-2h-3V6h3v4Z" />
                                    </svg>
                                    <span class="text-sm font-medium ml-4 lg:opacity-0 lg:sidebar-expanded:opacity-100 2xl:opacity-100 duration-200">Logout</span>
                                </div>
                            </button>
                        </form>
                    </li>
                </ul>
            </div>

        </div>

        <!-- Expand / collapse button -->
        <div class="pt-3 hidden lg:inline-flex 2xl:hidden justify-end mt-auto">
            <div class="w-12 pl-4 pr-3 py-2">
                <button class="text-gray-400 hover:text-gray-500 dark:text-gray-500 dark:hover:text-gray-400 transition-colors" @click="sidebarExpanded = !sidebarExpanded">
                    <span class="sr-only">Expand / collapse sidebar</span>
                    <svg class="shrink-0 fill-current text-gray-400 dark:text-gray-500 sidebar-expanded:rotate-180" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16">
                        <path d="M15 16a1 1 0 0 1-1-1V1a1 1 0 1 1 2 0v14a1 1 0 0 1-1 1ZM8.586 7H1a1 1 0 1 0 0 2h7.586l-2.793 2.793a1 1 0 1 0 1.414 1.414l4.5-4.5A.997.997 0 0 0 12 8.01M11.924 7.617a.997.997 0 0 0-.217-.324l-4.5-4.5a1 1 0 0 0-1.414 1.414L8.586 7M12 7.99a.996.996 0 0 0-.076-.373Z" />
                    </svg>
                </button>
            </div>
        </div>

    </div>
</div>