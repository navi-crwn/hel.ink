<div class="space-y-6">
    <!-- SEO Optimization -->
    <div class="rounded-lg bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md dark:bg-slate-800 border-t-4 border-green-600 dark:border-green-400">
        <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">🔍 Search Engine Optimization</h3>
        <p class="mb-6 text-sm text-slate-600 dark:text-slate-400">Optimize how your bio page appears in search engine results</p>
        
        <div class="space-y-6">
            <!-- SEO Title -->
            <div>
                <label class="mb-3 block text-base font-medium text-slate-700 dark:text-slate-300">
                    SEO Title
                    <span class="text-sm text-slate-500 dark:text-slate-400" x-text="'(' + ((bioPage.seo_title?.length || 0) + 13) + '/60)'"></span>
                </label>
                <input type="text" 
                       x-model="bioPage.seo_title" 
                       maxlength="47"
                       placeholder="Custom title for search engines"
                       class="w-full rounded-lg border border-slate-300 px-4 py-3 text-base dark:border-slate-700 dark:bg-slate-900 dark:text-white">
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">Leave empty to use profile title. Optimal: 50-60 characters total.</p>
            </div>

            <!-- Meta Description -->
            <div>
                <label class="mb-3 block text-base font-medium text-slate-700 dark:text-slate-300">
                    Meta Description
                    <span class="text-sm text-slate-500 dark:text-slate-400" x-text="'(' + (bioPage.seo_description?.length || 0) + '/155)'"></span>
                </label>
                <textarea x-model="bioPage.seo_description" 
                          rows="3" 
                          maxlength="155"
                          placeholder="Description that appears in search results (max 155 chars)"
                          class="w-full rounded-lg border border-slate-300 px-4 py-3 text-base dark:border-slate-700 dark:bg-slate-900 dark:text-white"></textarea>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">This text appears under your link in Google search results. Optimal: 120-155 characters.</p>
            </div>

            <!-- Google Search Preview -->
            <div>
                <label class="mb-3 block text-base font-medium text-slate-700 dark:text-slate-300">
                    Preview
                </label>
                <div class="rounded-lg border-2 border-slate-200 bg-white p-4 dark:border-slate-700 dark:bg-slate-900">
                    <div class="space-y-1">
                        <p class="text-xs text-slate-500 dark:text-slate-400">{{ config('app.url') }}/b/{{ $bioPage->slug }}</p>
                        <p class="text-lg font-medium text-blue-600 hover:underline dark:text-blue-400" x-text="(bioPage.seo_title || bioPage.title || 'Your Title') + ' | HEL.ink Bio'"></p>
                        <p class="text-sm text-slate-600 dark:text-slate-400 line-clamp-2" x-text="bioPage.seo_description || bioPage.bio || 'Your meta description will appear here. Make it compelling to improve click-through rates.'"></p>
                    </div>
                </div>
                <p class="mt-2 text-xs text-slate-500 dark:text-slate-400">This is how your page will appear in Google search results.</p>
            </div>
        </div>
    </div>

    <!-- Indexing Control -->
    <div class="rounded-lg bg-white p-6 shadow-sm transition-all duration-200 hover:shadow-md dark:bg-slate-800">
        <h3 class="mb-4 text-lg font-semibold text-slate-900 dark:text-white">🤖 Search Engine Indexing</h3>
        
        <div class="space-y-4">
            <label class="flex items-start gap-3 cursor-pointer">
                <input type="checkbox" 
                       x-model="bioPage.seo_noindex"
                       class="mt-1 h-5 w-5 rounded border-slate-300 text-blue-600 focus:ring-2 focus:ring-blue-500">
                <div>
                    <p class="font-medium text-slate-900 dark:text-white">Prevent search engine indexing (noindex)</p>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Check this if you don't want this page to appear in search results. Useful for private or work-in-progress pages.</p>
                </div>
            </label>
        </div>
    </div>

    <!-- SEO Tips -->
    <div class="rounded-lg bg-blue-50 p-6 dark:bg-blue-900/20">
        <h4 class="mb-3 flex items-center gap-2 font-semibold text-blue-900 dark:text-blue-300">
            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
            </svg>
            SEO Best Practices
        </h4>
        <ul class="space-y-2 text-sm text-blue-900 dark:text-blue-300">
            <li class="flex gap-2">
                <span>✓</span>
                <span>Keep titles concise and descriptive (50-60 characters)</span>
            </li>
            <li class="flex gap-2">
                <span>✓</span>
                <span>Write compelling meta descriptions (120-155 characters)</span>
            </li>
            <li class="flex gap-2">
                <span>✓</span>
                <span>Use your avatar as social preview image for better engagement</span>
            </li>
            <li class="flex gap-2">
                <span>✓</span>
                <span>Include relevant keywords naturally in your descriptions</span>
            </li>
            <li class="flex gap-2">
                <span>✓</span>
                <span>Update your bio page regularly to keep it fresh for search engines</span>
            </li>
        </ul>
    </div>
</div>
