<x-app-layout>
    <x-slot name="pageTitle">HEL.ink - Create Link</x-slot>
    <x-slot name="header">
        <x-page-header title="Create Link" subtitle="Create a new short link">
            <x-slot name="actions">
                <a href="{{ route('dashboard') }}" class="rounded-full border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-slate-100">Back</a>
            </x-slot>
        </x-page-header>
    </x-slot>

    <div class="py-10">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <form method="POST" action="{{ route('links.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="status" value="active">
                <div>
                    <label class="text-sm text-slate-600">Destination URL</label>
                    <input type="url" name="target_url" value="{{ old('target_url') }}" required class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-3">
                </div>
                <div>
                    <label class="text-sm text-slate-600">Slug (optional)</label>
                    <input type="text" name="slug" value="{{ old('slug') }}" class="mt-1 w-full rounded-2xl border border-slate-200 px-4 py-2" placeholder="custom-slug">
                </div>
                <div class="flex justify-end gap-3">
                    <a href="{{ route('dashboard') }}" class="rounded-2xl border px-5 py-2">Cancel</a>
                    <button type="submit" class="rounded-2xl bg-blue-600 px-6 py-2 text-white">Save link</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
