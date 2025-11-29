@props(['isAdmin' => false, 'unreadReports' => 0])

<div class="max-h-[70vh] overflow-y-auto px-4 py-4">
    <nav x-data="{}" @click="if ($event.target.closest('a')) { $dispatch('close-sidebar') }">
        @if($isAdmin)
            @include('components.partials.admin-menu', ['unreadReports' => $unreadReports])
        @else
            @include('components.partials.user-menu')
        @endif
    </nav>

    <div class="mt-4 border-t border-slate-200 pt-4 dark:border-slate-800">
        <button type="button" onclick="toggleTheme()" class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-800">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
            </svg>
            <span>Toggle Theme</span>
        </button>
    </div>
</div>
