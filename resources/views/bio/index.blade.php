<x-app-layout>
    <x-slot name="pageTitle">Link in Bio - HEL.ink</x-slot>
    <x-slot name="header">
        <x-page-header title="Link in Bio" subtitle="Create beautiful landing pages for your social media">
            <x-slot name="actions">
                <a href="{{ route('bio.create') }}" class="group relative overflow-hidden rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow-lg shadow-blue-500/30 transition-all hover:shadow-xl hover:shadow-blue-500/40 hover:scale-105">
                    <span class="relative z-10 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Create Bio Page
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-indigo-600 to-purple-600 opacity-0 transition-opacity group-hover:opacity-100"></div>
                </a>
            </x-slot>
        </x-page-header>
    </x-slot>

    @push('styles')
    <style>
        .bio-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .bio-card:hover {
            transform: translateY(-4px);
        }
        .bio-card:hover .bio-card-gradient {
            background-size: 200% 200%;
            animation: gradientShift 3s ease infinite;
        }
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        .stat-badge {
            backdrop-filter: blur(8px);
            background: rgba(255, 255, 255, 0.1);
        }
    </style>
    @endpush

    <div class="py-10" x-data="{ 
        deleteModal: { show: false, form: null, title: '' },
        confirmDelete(formId, title) {
            this.deleteModal.show = true;
            this.deleteModal.form = formId;
            this.deleteModal.title = title;
        },
        executeDelete() {
            if (this.deleteModal.form) {
                document.getElementById(this.deleteModal.form).submit();
            }
            this.deleteModal.show = false;
        }
    }">
        <div x-show="deleteModal.show" x-cloak class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50" @keydown.escape.window="deleteModal.show = false">
            <div class="rounded-xl bg-white dark:bg-slate-800 shadow-2xl p-6 w-full max-w-sm mx-4" @click.outside="deleteModal.show = false">
                <div class="flex items-center gap-3 mb-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">Delete Bio Page</h3>
                </div>
                <p class="mb-6 text-sm text-slate-600 dark:text-slate-400">
                    Are you sure you want to delete "<span x-text="deleteModal.title" class="font-medium"></span>"? This action cannot be undone.
                </p>
                <div class="flex justify-end gap-3">
                    <button @click="deleteModal.show = false" class="rounded-lg bg-slate-100 px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600 transition-colors">Cancel</button>
                    <button @click="executeDelete()" class="rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white hover:bg-red-700 transition-colors">Delete</button>
                </div>
            </div>
        </div>
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="rounded-lg border border-emerald-200 bg-emerald-50 p-4 dark:border-emerald-800 dark:bg-emerald-900/20">
                    <p class="text-sm text-emerald-800 dark:text-emerald-200">{{ session('success') }}</p>
                </div>
            @endif
            @if($bioPages->isEmpty())
                <div class="rounded-2xl border border-slate-200 bg-white p-12 text-center dark:border-slate-800 dark:bg-slate-900">
                    <div class="mx-auto max-w-md">
                        <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-blue-100 dark:bg-blue-900/30">
                            <svg class="h-8 w-8 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                        </div>
                        <h3 class="mb-2 text-lg font-semibold text-slate-900 dark:text-white">No bio pages yet</h3>
                        <p class="mb-6 text-sm text-slate-600 dark:text-slate-400">Create your first bio page to share all your important links in one beautiful place.</p>
                        <a href="{{ route('bio.create') }}" class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            Create Your First Bio Page
                        </a>
                    </div>
                </div>
            @else
                <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($bioPages as $bioPage)
                        @php
                            $gradients = [
                                'from-blue-500 via-indigo-500 to-purple-600',
                                'from-emerald-500 via-teal-500 to-cyan-600',
                                'from-rose-500 via-pink-500 to-fuchsia-600',
                                'from-amber-500 via-orange-500 to-red-600',
                                'from-violet-500 via-purple-500 to-indigo-600',
                                'from-cyan-500 via-blue-500 to-indigo-600',
                            ];
                            $gradient = $gradients[$loop->index % count($gradients)];
                        @endphp
                        <div class="bio-card group relative overflow-hidden rounded-2xl border border-slate-200/50 bg-white shadow-sm hover:shadow-xl dark:border-slate-800 dark:bg-slate-900">
                            <div class="bio-card-gradient aspect-[16/9] bg-gradient-to-br {{ $gradient }} p-6 relative overflow-hidden" style="background-size: 150% 150%;">
                                <div class="absolute inset-0 opacity-20">
                                    <div class="absolute -top-10 -right-10 w-40 h-40 rounded-full bg-white/20 blur-2xl"></div>
                                    <div class="absolute -bottom-10 -left-10 w-32 h-32 rounded-full bg-white/20 blur-2xl"></div>
                                </div>
                                <div class="flex h-full flex-col items-center justify-center text-center relative z-10">
                                    @if($bioPage->avatar_url)
                                        <img src="{{ Storage::url($bioPage->avatar_url) }}" alt="{{ $bioPage->title }}" class="mb-3 h-16 w-16 rounded-full border-3 border-white/90 object-cover shadow-lg transition-transform group-hover:scale-110">
                                    @else
                                        <div class="mb-3 flex h-16 w-16 items-center justify-center rounded-full border-3 border-white/90 bg-white/20 text-2xl font-bold text-white backdrop-blur-sm shadow-lg transition-transform group-hover:scale-110">
                                            {{ substr($bioPage->title, 0, 1) }}
                                        </div>
                                    @endif
                                    <h3 class="font-bold text-white text-lg drop-shadow-sm">{{ $bioPage->title }}</h3>
                                    <p class="text-xs text-white/90 font-medium mt-1 px-3 py-1 rounded-full stat-badge">hel.ink/b/{{ $bioPage->slug }}</p>
                                </div>
                            </div>
                            <div class="p-4">
                                <div class="mb-4 flex items-center gap-3 text-xs">
                                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <span class="font-medium">{{ number_format($bioPage->view_count) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        <span class="font-medium">{{ number_format($bioPage->getTotalClicks()) }}</span>
                                    </div>
                                    <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-400">
                                        <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
                                        </svg>
                                        <span class="font-medium">{{ $bioPage->links_count }}</span>
                                    </div>
                                    @if(!$bioPage->is_published)
                                        <span class="ml-auto rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Draft</span>
                                    @endif
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('bio.edit', $bioPage) }}" class="flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-center text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                                        Edit
                                    </a>
                                    <a href="{{ route('bio.analytics', $bioPage) }}" class="flex-1 rounded-xl border border-slate-200 bg-white px-3 py-2.5 text-center text-sm font-medium text-slate-700 hover:bg-slate-50 hover:border-slate-300 transition-all dark:border-slate-700 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700">
                                        Analytics
                                    </a>
                                    <a href="{{ url('/b/' . $bioPage->slug) }}" target="_blank" class="flex-1 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 px-3 py-2.5 text-center text-sm font-semibold text-white hover:from-blue-500 hover:to-indigo-500 transition-all shadow-sm hover:shadow-md">
                                        View
                                    </a>
                                </div>
                                <div class="mt-2 flex gap-2">
                                    <form action="{{ route('bio.duplicate', $bioPage) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full rounded-xl border border-slate-200 px-3 py-2 text-center text-xs font-medium text-slate-600 hover:bg-slate-50 hover:border-slate-300 transition-all dark:border-slate-700 dark:text-slate-400 dark:hover:bg-slate-800">
                                            Duplicate
                                        </button>
                                    </form>
                                    <form id="delete-form-{{ $bioPage->id }}" action="{{ route('bio.destroy', $bioPage) }}" method="POST" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" @click="confirmDelete('delete-form-{{ $bioPage->id }}', '{{ $bioPage->title }}')" class="w-full rounded-xl border border-red-200 px-3 py-2 text-center text-xs font-medium text-red-600 hover:bg-red-50 hover:border-red-300 transition-all dark:border-red-900 dark:text-red-400 dark:hover:bg-red-900/20">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
