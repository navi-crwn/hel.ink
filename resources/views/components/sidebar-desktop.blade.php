@props(['isAdmin' => false, 'unreadReports' => 0])
<aside class="h-screen w-full border-r border-slate-200 bg-white dark:border-slate-800 dark:bg-slate-900">
    <div class="flex h-full flex-col">
        <div class="flex h-16 items-center border-b border-slate-200 px-6 dark:border-slate-800">
            <a href="{{ $isAdmin ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center gap-2">
                <x-application-logo class="h-8 w-auto" />
                <span class="font-semibold text-slate-900 dark:text-white">{{ $isAdmin ? 'HEL.ink Admin' : 'HEL.ink' }}</span>
            </a>
        </div>
        <nav class="flex-1 overflow-y-auto px-3 py-4" x-data="{}" @click="if ($event.target.closest('a') && window.innerWidth < 768) { $dispatch('close-sidebar') }">
            @if($isAdmin)
                @include('components.partials.admin-menu', ['unreadReports' => $unreadReports])
            @else
                @include('components.partials.user-menu')
            @endif
        </nav>
        <div class="border-t border-slate-200 p-4 dark:border-slate-800">
            <button type="button" onclick="toggleTheme()" id="theme-toggle-button" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
                <span>Toggle Theme</span>
            </button>
        </div>
    </div>
</aside>
