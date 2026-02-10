@php
    $active = request()->routeIs($route);
@endphp

<a href="{{ route($route) }}"
   class="flex items-center gap-3 px-4 py-2 rounded-md transition
   {{ $active ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
    
    @include('components.app.sidebar-icon', ['name' => $icon])

    <span class="font-medium">{{ $title }}</span>
</a>
