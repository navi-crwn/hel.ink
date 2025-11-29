<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-semibold leading-tight text-gray-800 dark:text-gray-100">Edit Link</h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-3xl sm:px-6 lg:px-8">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm dark:border-gray-800 dark:bg-gray-900">
                <form
                    x-data="{ submitting: false }"
                    x-on:submit="submitting = true"
                    method="POST"
                    action="{{ route('links.update', $link) }}"
                    class="space-y-4"
                >
                    @csrf
                    @method('PUT')
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300" for="target_url">Destination URL</label>
                        <input id="target_url" type="url" name="target_url" value="{{ old('target_url', $link->target_url) }}" required class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" />
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300" for="slug">Slug</label>
                        <div class="mt-1 flex rounded-xl border border-gray-200 dark:border-gray-700">
                            <span class="inline-flex items-center border-r border-gray-200 px-3 text-gray-500 dark:border-gray-700">hel.ink/</span>
                            <input id="slug" type="text" name="slug" value="{{ old('slug', $link->slug) }}" class="w-full rounded-r-xl border-0 bg-transparent focus:ring-0 dark:text-white" />
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300" for="status">Status</label>
                        <select id="status" name="status" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                            <option value="active" @selected($link->status === 'active')>Active</option>
                            <option value="inactive" @selected($link->status === 'inactive')>Inactive</option>
                        </select>
                    </div>
                    
                    <div class="grid gap-4 md:grid-cols-3">
                        
                        <div class="relative" x-data='{"isOpen": false, "search": "", "selectedId": "{{ old('folder_id', $link->folder_id) }}", "folders": @json($folders)}' @click.away="isOpen = false; search = ''">
                            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Folder</label>
                            <button type="button" @click="isOpen = !isOpen" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-left flex items-center justify-between text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <span x-text="folders.find(f => f.id == selectedId)?.name || 'Select folder'"></span>
                                <svg class="w-4 h-4 flex-shrink-0 transition-transform" :class="isOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <input type="hidden" name="folder_id" :value="selectedId" required>
                            <div x-show="isOpen" x-transition class="absolute left-0 top-full z-[200] mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-800" style="display: none;">
                                <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                                    <input type="text" x-model="search" placeholder="Search folders..." class="w-full px-3 py-2 text-sm rounded border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white" @click.stop>
                                </div>
                                <div class="max-h-60 overflow-y-auto">
                                    <template x-if="folders.length === 0">
                                        <p class="text-xs text-center text-gray-500 dark:text-gray-400 py-4">No folders</p>
                                    </template>
                                    <template x-for="folder in folders.filter(f => !search || f.name.toLowerCase().includes(search.toLowerCase()))" :key="folder.id">
                                        <button type="button" @click="selectedId = folder.id; isOpen = false; search = ''" class="w-full text-left px-3 py-2.5 text-sm border-b border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 last:border-b-0" :class="selectedId == folder.id ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300'">
                                            <span x-text="folder.name"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative" x-data='{"isOpen": false, "selectedValue": "{{ old('redirect_type', $link->redirect_type) }}", "options": { "302": "302 (Temp)", "301": "301 (Perm)", "307": "307" } }' @click.away="isOpen = false">
                            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Redirect</label>
                            <button type="button" @click="isOpen = !isOpen" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-left flex items-center justify-between text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <span x-text="options[selectedValue]"></span>
                                <svg class="w-4 h-4 flex-shrink-0 transition-transform" :class="isOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <input type="hidden" name="redirect_type" :value="selectedValue">
                            <div x-show="isOpen" x-transition class="absolute z-[200] w-full mt-1 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-2xl" style="display: none;">
                                <div class="overflow-y-auto" style="max-height: 220px;">
                                    <template x-for="(label, value) in options" :key="value">
                                        <button type="button" @click="selectedValue = value; isOpen = false" class="w-full text-left px-3 py-2.5 text-sm border-b border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 last:border-b-0" :class="selectedValue == value ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-300' : 'text-gray-700 dark:text-gray-300'">
                                            <span x-text="label"></span>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                        
                        <div class="relative" x-data='{"isOpen": false, "search": "", "selectedTags": @json(old('tags', $link->tags->pluck('id')->all())), "tags": @json($tags)}' @click.away="isOpen = false; search = ''">
                            <label class="block text-sm text-gray-600 dark:text-gray-300 mb-1">Tags</label>
                            <button type="button" @click="isOpen = !isOpen" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-left flex items-center justify-between text-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                                <span x-text="selectedTags.length === 0 ? 'No tags selected' : selectedTags.length + ' tag(s) selected'"></span>
                                <svg class="w-4 h-4 flex-shrink-0 transition-transform" :class="isOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div x-show="isOpen" x-transition class="absolute left-0 top-full z-[200] mt-1 w-full rounded-xl border border-gray-200 bg-white shadow-2xl dark:border-gray-700 dark:bg-gray-800" style="display: none;">
                                <div class="p-2 border-b border-gray-200 dark:border-gray-700">
                                    <input type="text" x-model="search" placeholder="Search tags..." class="w-full px-3 py-2 text-sm rounded border-gray-200 dark:border-gray-600 dark:bg-gray-900 dark:text-white" @click.stop>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <template x-if="tags.length === 0">
                                        <p class="text-xs text-center text-gray-500 dark:text-gray-400 py-4">No tags</p>
                                    </template>
                                    <template x-for="tag in tags.filter(t => !search || t.name.toLowerCase().includes(search.toLowerCase()))" :key="tag.id">
                                        <label class="flex items-center justify-between px-3 py-2.5 text-sm border-b border-gray-100 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700 last:border-b-0 cursor-pointer">
                                            <span class="text-gray-700 dark:text-gray-300" x-text="tag.name"></span>
                                            <input type="checkbox" name="tags[]" :value="tag.id" :checked="selectedTags.includes(tag.id)" @change="$event.target.checked ? selectedTags.push(tag.id) : selectedTags = selectedTags.filter(id => id !== tag.id)" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:border-gray-600">
                                        </label>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-300">Password (set a new value or leave blank)</label>
                            <input type="password" name="password" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="******">
                            <label class="mt-2 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                <input type="checkbox" name="remove_password" value="1" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500 dark:bg-gray-800 dark:border-gray-600">
                                Remove existing password
                            </label>
                        </div>
                        <div>
                            <label class="text-sm text-gray-600 dark:text-gray-300">Expires at</label>
                            <input type="datetime-local" name="expires_at" value="{{ old('expires_at', optional($link->expires_at)->format('Y-m-d\TH:i')) }}" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white">
                        </div>
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">Custom preview</label>
                        <input type="text" name="custom_title" value="{{ old('custom_title', $link->custom_title) }}" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="Social preview title">
                        <textarea name="custom_description" rows="2" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="Description">{{ old('custom_description', $link->custom_description) }}</textarea>
                        <input type="url" name="custom_image_url" value="{{ old('custom_image_url', $link->custom_image_url) }}" class="mt-2 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="https://...jpg">
                    </div>
                    <div>
                        <label class="text-sm text-gray-600 dark:text-gray-300">New note</label>
                        <textarea name="comment" rows="2" class="mt-1 w-full rounded-xl border-gray-200 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-700 dark:bg-gray-800 dark:text-white" placeholder="Internal note"></textarea>
                    </div>
                    @if ($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                            <ul class="list-disc pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="flex justify-between">
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-500 hover:underline">Back</a>
                        <button type="submit" class="rounded-xl bg-blue-600 px-6 py-3 text-white hover:bg-blue-500 disabled:opacity-70 disabled:cursor-not-allowed" :disabled="submitting">
                            <span x-show="!submitting">Save changes</span>
                            <span x-show="submitting" x-cloak>Saving...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
