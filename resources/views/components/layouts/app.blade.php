<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400..700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <!-- Styles -->
    @livewireStyles

    <style>
        [x-cloak] { display: none !important; }
    </style>

    <script>
        if (localStorage.getItem('dark-mode') === 'false' || !('dark-mode' in localStorage)) {
            document.querySelector('html').classList.remove('dark');
            document.querySelector('html').style.colorScheme = 'light';
        } else {
            document.querySelector('html').classList.add('dark');
            document.querySelector('html').style.colorScheme = 'dark';
        }
    </script>
</head>
<body class="font-inter antialiased bg-gray-100 dark:bg-gray-900 text-gray-600 dark:text-gray-400" :class="{ 'sidebar-expanded': sidebarExpanded }" x-data="{ sidebarOpen: false, sidebarExpanded: localStorage.getItem('sidebar-expanded') == 'true' }" x-init="$watch('sidebarExpanded', value => localStorage.setItem('sidebar-expanded', value))">

    <script>
        if (localStorage.getItem('sidebar-expanded') == 'true') {
            document.querySelector('body').classList.add('sidebar-expanded');
        } else {
            document.querySelector('body').classList.remove('sidebar-expanded');
        }
    </script>

    <!-- Page wrapper -->
    <div class="flex h-[100dvh] overflow-hidden">

        <!-- Sidebar -->
        <x-app.sidebar :variant="$attributes->get('sidebarVariant')" />

        <!-- Content area -->
        <div class="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden {{ $attributes->get('background', '') }}" x-ref="contentarea">

            <!-- Header -->
            <x-app.header :variant="$attributes->get('headerVariant')" />

            <!-- Main content -->
            <main class="grow">

                <!-- Flash Messages -->
                @if(session('success'))
                <div class="m-4" x-data="{ show: true }" x-show="show" x-transition>
                    <div class="px-4 py-2 rounded-lg bg-emerald-100 dark:bg-emerald-500/30 border border-emerald-200 dark:border-emerald-500/60 text-emerald-600 dark:text-emerald-400">
                        <div class="flex w-full justify-between items-start">
                            <div class="flex">
                                <svg class="shrink-0 fill-current opacity-80 mt-[3px] mr-3" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8ZM7 11.4L3.6 8 5 6.6l2 2 4-4L12.4 6 7 11.4Z" />
                                </svg>
                                <div class="text-sm font-medium">{{ session('success') }}</div>
                            </div>
                            <button class="opacity-70 hover:opacity-80 ml-3 mt-[3px]" @click="show = false">
                                <span class="sr-only">Close</span>
                                <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M12.7 3.3c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0L8 5.2 4.7 1.9c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4L6.6 6.6 3.3 9.9c-.4.4-.4 1 0 1.4.2.2.4.3.7.3.3 0 .5-.1.7-.3l3.3-3.3 3.3 3.3c.2.2.5.3.7.3.2 0 .5-.1.7-.3.4-.4.4-1 0-1.4L9.4 6.6l3.3-3.3Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                @if(session('error'))
                <div class="m-4" x-data="{ show: true }" x-show="show" x-transition>
                    <div class="px-4 py-2 rounded-lg bg-red-100 dark:bg-red-500/30 border border-red-200 dark:border-red-500/60 text-red-600 dark:text-red-400">
                        <div class="flex w-full justify-between items-start">
                            <div class="flex">
                                <svg class="shrink-0 fill-current opacity-80 mt-[3px] mr-3" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M8 0C3.6 0 0 3.6 0 8s3.6 8 8 8 8-3.6 8-8-3.6-8-8-8Zm3.5 10.1l-1.4 1.4L8 9.4l-2.1 2.1-1.4-1.4L6.6 8 4.5 5.9l1.4-1.4L8 6.6l2.1-2.1 1.4 1.4L9.4 8l2.1 2.1Z" />
                                </svg>
                                <div class="text-sm font-medium">{{ session('error') }}</div>
                            </div>
                            <button class="opacity-70 hover:opacity-80 ml-3 mt-[3px]" @click="show = false">
                                <span class="sr-only">Close</span>
                                <svg class="fill-current shrink-0" width="16" height="16" viewBox="0 0 16 16">
                                    <path d="M12.7 3.3c.4-.4.4-1 0-1.4-.4-.4-1-.4-1.4 0L8 5.2 4.7 1.9c-.4-.4-1-.4-1.4 0-.4.4-.4 1 0 1.4L6.6 6.6 3.3 9.9c-.4.4-.4 1 0 1.4.2.2.4.3.7.3.3 0 .5-.1.7-.3l3.3-3.3 3.3 3.3c.2.2.5.3.7.3.2 0 .5-.1.7-.3.4-.4.4-1 0-1.4L9.4 6.6l3.3-3.3Z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
                @endif

                {{ $slot }}

            </main>

        </div>

    </div>

    @livewireScriptConfig
</body>
</html>