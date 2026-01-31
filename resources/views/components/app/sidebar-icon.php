@switch($name)
    @case('home')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><path d="M3 12l9-9 9 9"/><path d="M9 21V9h6v12"/></svg>
        @break

    @case('calendar')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18"/><line x1="16" y1="2" x2="16" y2="6"/></svg>
        @break

    @case('clipboard')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><path d="M9 5h6"/><rect x="4" y="3" width="16" height="18"/></svg>
        @break

    @case('check-circle')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M9 12l2 2 4-4"/></svg>
        @break

    @case('shield-check')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><path d="M12 3l8 4v5c0 5-3 9-8 9s-8-4-8-9V7z"/></svg>
        @break

    @case('users')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><circle cx="9" cy="7" r="4"/><circle cx="17" cy="7" r="4"/></svg>
        @break

    @case('user-cog')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><circle cx="12" cy="8" r="4"/><path d="M6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"/></svg>
        @break

    @case('database')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><ellipse cx="12" cy="5" rx="9" ry="3"/><path d="M3 5v14c0 1.7 4 3 9 3s9-1.3 9-3V5"/></svg>
        @break

    @case('clipboard-list')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><path d="M9 5h6"/><path d="M9 12h6"/><path d="M9 19h6"/></svg>
        @break

    @case('logout')
        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
             viewBox="0 0 24 24"><path d="M17 16l4-4-4-4"/><path d="M7 12h14"/><path d="M3 21V3"/></svg>
        @break
@endswitch
