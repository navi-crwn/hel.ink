<x-app-layout>
    <x-slot name="pageTitle">HEL.ink - SEO Configuration</x-slot>
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-white">SEO Settings</h1>
        <p class="mt-2 text-sm text-slate-600 dark:text-slate-400">Meta tags configuration</p>
    </div>
    <div class="py-4">
        <div class="mx-auto max-w-4xl space-y-4 px-4 md:px-6">
            @if (session('status'))
                <div class="rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-emerald-900 dark:border-emerald-900/40 dark:bg-emerald-900/20 dark:text-emerald-200">
                    {{ session('status') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="rounded-lg border border-red-300 bg-red-50 px-4 py-3 text-red-900 dark:border-red-900/40 dark:bg-red-900/20 dark:text-red-200">
                    <ul class="list-disc pl-4 space-y-1 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.seo.update') }}" class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900 space-y-4"
                x-data="{
                    siteTitle: @js(old('site_title', $seo->site_title ?? config('app.name'))),
                    metaDescription: @js(old('meta_description', $seo->meta_description ?? 'A fast shortlink studio for campaigns, QR codes, and analytics.')),
                    favicon: @js(old('favicon', $seo->favicon ?? '')),
                    logo: @js(old('logo', $seo->logo ?? '')),
                    ogTitle: @js(old('og_title', $seo->og_title ?? 'Share smarter with Hop Easy Link')),
                    ogDescription: @js(old('og_description', $seo->og_description ?? 'Track clicks, protect links, manage QR codes, and keep abuse away.'))
                }">
                @csrf
                <div class="rounded-2xl border border-gray-100 bg-white/80 p-4 shadow-inner dark:border-gray-800 dark:bg-slate-900/70">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-200">SERP preview</p>
                    <div class="mt-4 space-y-4">
                        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900">
                            <div class="flex items-center gap-2 text-xs text-emerald-600 dark:text-emerald-300">
                                <div class="h-4 w-4 overflow-hidden rounded" x-show="favicon">
                                    <img :src="favicon" alt="Favicon preview" class="h-full w-full object-contain">
                                </div>
                                <span>{{ url('/') }}</span>
                            </div>
                            <p class="mt-1 text-lg font-semibold text-blue-700 dark:text-blue-300" x-text="siteTitle || 'Hop Easy Link · Shortlink workspace'"></p>
                            <p class="text-sm text-gray-600 dark:text-gray-300" x-text="metaDescription || 'Type a helpful description so search engines know what Hop Easy Link offers.'"></p>
                        </div>
                        <div class="rounded-xl border border-gray-200 bg-white p-4 dark:border-gray-700 dark:bg-gray-900 flex gap-4">
                            <div class="h-16 w-16 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center overflow-hidden">
                                <img x-show="logo" :src="logo" alt="Logo preview" class="h-full w-full object-contain">
                                <span x-show="!logo" class="text-xs text-gray-400">Logo</span>
                            </div>
                            <div>
                                <p class="text-xs uppercase tracking-[0.3em] text-gray-400">Social preview</p>
                                <p class="text-base font-semibold text-gray-900 dark:text-white" x-text="ogTitle || siteTitle"></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400" x-text="ogDescription || metaDescription"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Site Title</label>
                        <input type="text" name="site_title" x-model="siteTitle" value="{{ old('site_title', $seo->site_title ?? '') }}" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Aim for 55–60 characters to avoid truncation.</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Keywords</label>
                        <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $seo->meta_keywords ?? '') }}" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="shortlink, hop easy link, hel.ink">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Optional, but useful for internal searching.</p>
                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Meta Description</label>
                    <textarea name="meta_description" rows="3" x-model="metaDescription" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">{{ old('meta_description', $seo->meta_description ?? '') }}</textarea>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Keep between 150–160 characters for the best preview.</p>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Open Graph Title</label>
                        <input type="text" name="og_title" x-model="ogTitle" value="{{ old('og_title', $seo->og_title ?? '') }}" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Open Graph Description</label>
                        <input type="text" name="og_description" x-model="ogDescription" value="{{ old('og_description', $seo->og_description ?? '') }}" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Displayed whenever links are shared to social media.</p>
                    </div>
                </div>
                <div class="grid gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">OG Image URL</label>
                        <input type="url" name="og_image" value="{{ old('og_image', $seo->og_image ?? '') }}" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="https://">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Use 1200×630px JPG/PNG for rich previews.</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Favicon / Icon URL</label>
                        <input type="url" name="favicon" x-model="favicon" value="{{ old('favicon', $seo->favicon ?? '') }}" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Supply a square PNG (at least 96×96; 512×512 works best).</p>
                    </div>
                </div>
                <div>
                    <label class="text-sm text-gray-600 dark:text-gray-300">Website logo URL</label>
                    <input type="url" name="logo" x-model="logo" value="{{ old('logo', $seo->logo ?? '') }}" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="https://">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Ideal for headers/emails; use a transparent PNG (min 256×256).</p>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="submit" class="rounded-xl bg-blue-600 px-6 py-2 text-sm font-semibold text-white hover:bg-blue-500">Save SEO</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
