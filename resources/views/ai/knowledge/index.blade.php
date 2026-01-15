@extends('layouts.app')
@section('title', 'AI Knowledge Base')

@section('content')
    <div class="col-span-12">
        <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
            <div class="text-base font-medium group-[.mode--light]:text-white flex items-center gap-2">
                <i data-lucide="database" class="w-5 h-5 text-primary"></i>
                AI Knowledge Management
            </div>
            <div class="flex flex-col gap-x-3 gap-y-2 sm:flex-row md:ml-auto">
                <a href="{{ route('ai.knowledge.create') }}" class="btn btn-primary shadow-md group">
                    <i data-lucide="plus" class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform"></i>
                    Add New Knowledge
                </a>
            </div>
        </div>

        {{-- SUMMARY STATS (Static for now, could be made dynamic too) --}}
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm dark:bg-darkmode-600">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ $totalItems }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Total Items</div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm dark:bg-darkmode-600">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-pending/10 rounded-xl flex items-center justify-center text-pending">
                        <i data-lucide="layers" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ $totalCategories }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Categories</div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm dark:bg-darkmode-600">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-success/10 rounded-xl flex items-center justify-center text-success">
                        <i data-lucide="zap" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ $totalUsage }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Total Usage</div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm bg-theme-1 text-white">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="target" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ number_format($avgRelevance, 1) }}</div>
                        <div class="text-[9px] text-white/70 font-bold uppercase tracking-widest">Avg Relevance</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT BOX --}}
        <div class="grid grid-cols-12 gap-6 mt-8">
            <div class="col-span-12">
                <div class="box box--stacked bg-white dark:bg-darkmode-600 flex flex-col">
                    {{-- TOOLBAR --}}
                    <div class="flex flex-col gap-y-2 p-5 sm:flex-row sm:items-center">
                        <div>
                            <div class="relative">
                                <i data-lucide="search" class="absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4 stroke-[1.3] text-slate-500"></i>
                                <input type="text" placeholder="Search topic or content..." id="search-input"
                                    class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 rounded-[0.5rem] pl-9 sm:w-64">
                            </div>
                        </div>
                        <div class="flex flex-col gap-x-3 gap-y-2 sm:ml-auto sm:flex-row">
                            {{-- Filter dropdown could go here if needed --}}
                        </div>
                    </div>

                    {{-- TABLE --}}
                    <div class="overflow-auto xl:overflow-visible">
                        <table class="w-full text-left border-b border-slate-200/60">
                            <thead>
                                <tr>
                                    <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 uppercase tracking-widest text-[10px]">Topic / Category</td>
                                    <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 uppercase tracking-widest text-[10px]">Content & Tags</td>
                                    <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 uppercase tracking-widest text-[10px] text-center">Stats</td>
                                    <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 uppercase tracking-widest text-[10px] text-center">Last Used</td>
                                    <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 uppercase tracking-widest text-[10px] text-right">Actions</td>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                {{-- JS Rendered Content --}}
                            </tbody>
                        </table>
                    </div>

                    {{-- LOADING SKELETON --}}
                    <div id="skeleton" class="hidden p-5 text-center text-slate-400">
                        <div class="flex justify-center">
                            <i data-lucide="loader-2" class="w-8 h-8 animate-spin"></i>
                        </div>
                    </div>

                    {{-- EMPTY STATE --}}
                    <div id="empty-state" class="hidden p-10 text-center">
                         <div class="text-slate-500 text-lg">No Item Found</div>
                         <div class="text-slate-400 mt-2">Try adjusting your search or add a new knowledge item.</div>
                    </div>

                    {{-- PAGINATION --}}
                    <div class="flex-reverse flex flex-col-reverse flex-wrap items-center gap-y-2 p-5 sm:flex-row">
                        <nav class="mr-auto w-full flex-1 sm:w-auto">
                            <ul id="pagination-container" class="flex w-full mr-0 sm:mr-auto sm:w-auto"></ul>
                        </nav>
                        <select id="per-page"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent rounded-[0.5rem] sm:w-20">
                            <option value="5">5</option>
                            <option value="10" selected>10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- DELETE MODAL --}}
    <div id="delete-modal" tabindex="-1" aria-hidden="true"
        class="modal group bg-gradient-to-b from-theme-1/50 via-theme-2/50 to-black/50 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 overflow-y-hidden z-[60] [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.1s] overflow-y-auto">
        <div class="relative mx-auto my w-[95%] scale-95 transition-transform group-[.show]:scale-100 sm:mt-40 sm:w-[600px] lg:w-[700px]">
            <div class="global-search global-search--show-result group relative z-10 max-h-[468px] overflow-y-auto rounded-lg bg-white pb-1 shadow-lg sm:max-h-[615px]">
                <div class="flex items-center justify-between px-5 py-4 border-b">
                    <h3 class="text-base font-semibold text-slate-800">Hapus Knowledge Item</h3>
                </div>
                <div class="px-5 py-4 text-slate-600 text-sm leading-relaxed">
                    Item ini akan <span class="font-semibold text-danger">dihapus permanen</span>. Tindakan ini tidak bisa dibatalkan.
                </div>
                <div class="flex justify-end gap-2 px-5 py-4 border-t">
                    <button data-tw-dismiss="modal" class="px-4 py-2 rounded-lg border text-slate-600 hover:bg-slate-100 transition">Batal</button>
                    <button id="confirm-delete" class="px-4 py-2 rounded-lg bg-danger text-white hover:bg-danger/90">Hapus</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        
        const tableBody = document.getElementById('table-body')
        const skeleton = document.getElementById('skeleton')
        const emptyState = document.getElementById('empty-state')
        const searchInput = document.getElementById('search-input')
        
        let deleteId = null
        let debounceTimer = null
        let currentQuery = ''
        let currentPage = 1
        let currentPerPage = 10

        // Parse tags that might be JSON string or comma-separated
        function parseTags(tags) {
            if (!tags) return [];
            
            // Try formatting if it's already encoded
            let cleanTags = tags;
            if (typeof tags === 'string' && (tags.startsWith('[') || tags.includes('&quot;'))) {
                const txt = document.createElement("textarea");
                txt.innerHTML = tags;
                cleanTags = txt.value;
            }

            if (typeof cleanTags === 'string') {
                cleanTags = cleanTags.trim();
                // Check if JSON array
                if (cleanTags.startsWith('[')) {
                    try {
                        return JSON.parse(cleanTags);
                    } catch (e) {
                         // Fallback: strip brackets and split
                        return cleanTags.replace(/[\[\]"]/g, '').split(',');
                    }
                }
                // Check if comma separated
                if (cleanTags.includes(',')) {
                    return cleanTags.split(',');
                }
                
                return [cleanTags]; // Single tag
            }
            return Array.isArray(cleanTags) ? cleanTags : [];
        }

        function renderData(items) {
            tableBody.innerHTML = ''
            skeleton?.classList.add('hidden')
            emptyState?.classList.add('hidden')

            if (!items || items.length === 0) {
                emptyState?.classList.remove('hidden')
                return
            }

            items.forEach(item => {
                const tagList = parseTags(item.tags).slice(0, 3).map(t => {
                    return `<span class="bg-slate-100 text-[10px] px-2 py-0.5 rounded text-slate-600 border border-slate-200">${t}</span>`
                }).join('')

                const timeAgo = item.last_used_at ? new Date(item.last_used_at).toLocaleDateString() : 'Never'

                const tr = document.createElement('tr')
                tr.dataset.id = item.id
                tr.className = '[&_td]:last:border-b-0 hover:bg-slate-50/50 transition'

                tr.innerHTML = `
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 align-top">
                        <div class="font-bold text-slate-700 dark:text-slate-200 mb-1 leading-snug">${item.topic || '-'}</div>
                        <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-primary/10 text-primary border border-primary/20">${item.category || 'General'}</span>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 align-top max-w-sm">
                        <div class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed mb-2">
                             ${item.content ? (item.content.length > 120 ? item.content.substring(0, 120) + '...' : item.content) : '-'}
                        </div>
                        <div class="flex flex-wrap gap-1">
                            ${tagList}
                        </div>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 align-top text-center">
                        <div class="flex flex-col gap-1 items-center">
                             <span class="text-xs font-bold text-success">${item.relevance_score || 0}%</span>
                             <span class="text-[10px] text-slate-400">${item.usage_count || 0} uses</span>
                        </div>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 align-top text-center">
                         <span class="text-xs text-slate-500">${timeAgo}</span>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:border-darkmode-300 relative dark:bg-darkmode-600 align-top text-right w-24">
                        <div class="flex justify-end gap-2">
                            <a href="/ai/knowledge/${item.id}/edit" class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-1.5 px-2 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 border-secondary text-slate-500 hover:bg-slate-100">
                                <i data-lucide="edit-3" class="w-4 h-4"></i>
                            </a>
                            <button 
                                data-tw-toggle="modal" 
                                data-tw-target="#delete-modal" 
                                data-id="${item.id}"
                                class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-1.5 px-2 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 border-secondary text-danger hover:bg-red-50 hover:border-danger/30">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </td>
                `
                tableBody.appendChild(tr)
            })
            lucide.createIcons()
        }

        function loadData(query = '', page = 1) {
            currentQuery = query
            currentPage = page
            
            skeleton?.classList.remove('hidden')
            tableBody.innerHTML = ''
            emptyState?.classList.add('hidden')
            
            axios.get('{{ route("ai.knowledge.list") }}', {
                params: {
                    search: currentQuery,
                    page: currentPage,
                    per_page: currentPerPage
                }
            }).then(res => {
                const paginator = res.data.data
                renderData(paginator.data)
                renderPagination(paginator)
            }).finally(() => {
                skeleton?.classList.add('hidden')
            })
        }

        // Pagination Renderer from previous examples
        function renderPagination(paginator) {
            const container = document.getElementById('pagination-container')
            container.innerHTML = ''

            const current = paginator.current_page
            const last = paginator.last_page

            const li = (content, page = null, active = false) => `
                <li class="flex-1 sm:flex-initial">
                    <a ${page ? `onclick="loadData(currentQuery, ${page})"` : ''}
                        class="transition duration-200 border items-center justify-center py-2 rounded-md cursor-pointer
                        min-w-0 sm:min-w-[40px] shadow-none font-normal flex
                        border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3
                        ${active ? '!box dark:bg-darkmode-400 font-bold bg-white border-slate-200' : 'hover:bg-slate-100'}">
                        ${content}
                    </a>
                </li>`

            if (current > 1) {
                container.innerHTML += li('<i data-lucide="chevron-left" class="w-4 h-4"></i>', current - 1)
            }

            paginator.links.forEach((link, index) => {
                // Simplified pagination logic: show first, last, current, and surrounding
                if (link.label.includes('Previous') || link.label.includes('Next')) return
                
                // Show standard links
                 if (link.label === '...') {
                    container.innerHTML += li('...')
                    return
                }

                const page = link.url ? new URL(link.url).searchParams.get('page') : null
                if (page) {
                     // Optimization: Only show 1st, Last, Current, Current-1, Current+1
                    if (page == 1 || page == last || (page >= current - 1 && page <= current + 1)) {
                         container.innerHTML += li(link.label, page, link.active)
                    } else if (container.lastChild?.innerHTML !== '...') {
                        // container.innerHTML += li('...') // Too complex to handle dots correctly in simple logic, full render is fine for now
                    }
                }
            })

            if (current < last) {
                container.innerHTML += li('<i data-lucide="chevron-right" class="w-4 h-4"></i>', current + 1)
            }
            lucide.createIcons();
        }

        // Events
        searchInput?.addEventListener('input', e => {
            clearTimeout(debounceTimer)
            debounceTimer = setTimeout(() => loadData(e.target.value), 500)
        })

        const perPageSelect = document.getElementById('per-page')
        perPageSelect?.addEventListener('change', e => {
            currentPerPage = e.target.value
            loadData(currentQuery, 1)
        })

        // Delete Logic
        document.addEventListener('click', function(e) {
            const openBtn = e.target.closest('[data-tw-target="#delete-modal"]')
            if (openBtn) {
                deleteId = openBtn.getAttribute('data-id')
            }

            const confirmBtn = e.target.closest('#confirm-delete')
            if (!confirmBtn || !deleteId) return

            confirmBtn.disabled = true
            confirmBtn.textContent = 'Deleting...'

            axios.delete(`/ai/knowledge/${deleteId}`)
                .then(res => {
                    // Assuming controller redirects, but with JSON request it might behave differently defined earlier
                    // In the previous step I didn't change Destroy to return JSON, it returns Redirect.
                    // But standard axios delete follows redirect or succeeds.
                    // Let's manually reload data.
                     document.querySelector(`[data-id="${deleteId}"]`)?.remove()
                     document.getElementById('delete-modal').classList.remove('show')
                     deleteId = null
                     loadData(currentQuery, currentPage)
                     
                     // Show simple toast if available or alert
                     // alert('Item deleted') 
                }).catch(err => {
                    console.error(err);
                    alert('Failed to delete item')
                }).finally(() => {
                    confirmBtn.disabled = false
                    confirmBtn.textContent = 'Hapus'
                })
        })

        window.addEventListener('load', () => {
            loadData()
            lucide.createIcons()
        })
    </script>
@endpush
