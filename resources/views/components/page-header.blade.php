@props([
    'title',
    'subtitle' => null,
])
<div class="flex flex-wrap items-center justify-between gap-4">
    <div>
        @if ($subtitle)
            <p class="text-xs uppercase tracking-[0.3em] text-slate-400 dark:text-slate-500">{{ $subtitle }}</p>
        @endif
        <h2 class="text-2xl font-semibold leading-tight text-slate-900 dark:text-white">{{ $title }}</h2>
    </div>
    <div class="flex flex-wrap items-center gap-3">
        {{ $actions ?? '' }}
    </div>
</div>
