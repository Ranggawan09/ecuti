<x-authentication-layout>
    <h1 class="text-3xl text-gray-800 dark:text-gray-100 font-bold mb-2 text-center">{{ __('eCuti') }}</h1>
    <div class="text-center text-sm text-gray-600 dark:text-gray-400 mb-6">
        Sistem ini hanya untuk ASN (PNS dan P3K) di Pengadilan Negeri Jombang.
    </div>
    @if (session('status'))
    <div class="mb-4 font-medium text-sm text-green-600">
        {{ session('status') }}
    </div>
    @endif
    <!-- Form -->
    <form method="POST" action="{{ route('login') }}">
        @csrf
        <div class="space-y-4">
            <div>
                <x-label for="email" value="{{ __('Email') }}" />
                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus />
            </div>
            <div>
                <x-label for="password" value="{{ __('Password') }}" />
                <x-input id="password" type="password" name="password" required autocomplete="current-password" />
            </div>
        </div>
        <div class="flex items-center justify-center mt-6">
            <x-button class="ml-3">
                {{ __('Masuk') }}
            </x-button>
        </div>
    </form>
    <x-validation-errors class="mt-4" />
</x-authentication-layout>