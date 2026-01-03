@extends('layouts.app')
@section('title', 'Articles')
@push('styles')
@endpush
@section('content')
    <div class="col-span-12">
        <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
            <div class="text-base font-medium group-[.mode--light]:text-white"> Data Artikel </div>
            <div class="flex flex-col gap-x-3 gap-y-2 sm:flex-row md:ml-auto">
                <a href="/articles/create"
                    class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;:hover:not(:disabled)]:bg-opacity-90 [&amp;:hover:not(:disabled)]:border-opacity-90 [&amp;:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-primary border-primary text-white dark:border-primary group-[.mode--light]:!border-transparent group-[.mode--light]:!bg-white/[0.12] group-[.mode--light]:!text-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        data-lucide="pen-line" class="lucide lucide-pen-line mr-2 h-4 w-4 stroke-[1.3]">
                        <path d="M12 20h9"></path>
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4Z"></path>
                    </svg> Tambah Artikel </a>
            </div>
        </div>
        <div class="mt-3.5 flex flex-col gap-8">
            <div class="box box--stacked flex flex-col">
                <div class="flex flex-col gap-y-2 p-5 sm:flex-row sm:items-center">
                    <div>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" data-lucide="search"
                                class="lucide lucide-search absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4 stroke-[1.3] text-slate-500">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                            <input type="text" placeholder="Search users..." id="search-input"
                                class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 [&amp;[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&amp;[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&amp;:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 rounded-[0.5rem] pl-9 sm:w-64">
                        </div>
                    </div>
                    <div class="flex flex-col gap-x-3 gap-y-2 sm:ml-auto sm:flex-row">
                        <div data-tw-placement="bottom-end" class="dropdown relative inline-block"><button
                                data-tw-toggle="dropdown" aria-expanded="false"
                                class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&amp;:hover:not(:disabled)]:bg-opacity-90 [&amp;:hover:not(:disabled)]:border-opacity-90 [&amp;:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&amp;:hover:not(:disabled)]:bg-secondary/20 [&amp;:hover:not(:disabled)]:dark:bg-darkmode-100/10 w-full sm:w-auto"><svg
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" data-lucide="arrow-down-wide-narrow"
                                    class="lucide lucide-arrow-down-wide-narrow mr-2 h-4 w-4 stroke-[1.3]">
                                    <path d="m3 16 4 4 4-4"></path>
                                    <path d="M7 20V4"></path>
                                    <path d="M11 4h10"></path>
                                    <path d="M11 8h7"></path>
                                    <path d="M11 12h4"></path>
                                </svg>
                                Filter
                            <div data-transition="" data-selector=".show"
                                data-enter="transition-all ease-linear duration-150"
                                data-enter-from="absolute !mt-5 invisible opacity-0 translate-y-1"
                                data-enter-to="!mt-1 visible opacity-100 translate-y-0"
                                data-leave="transition-all ease-linear duration-150"
                                data-leave-from="!mt-1 visible opacity-100 translate-y-0"
                                data-leave-to="absolute !mt-5 invisible opacity-0 translate-y-1"
                                class="dropdown-menu z-[9999] hidden absolute invisible opacity-0 translate-y-1"
                                data-state="leave" id="_zkirctvge"
                                style="display: none; position: absolute; inset: 0px 0px auto auto; margin: 0px; transform: translate(-41px, 511px);"
                                data-popper-placement="bottom-end">
                                <div
                                    class="dropdown-content rounded-md border-transparent bg-white p-2 shadow-[0px_3px_10px_#00000017] dark:border-transparent dark:bg-darkmode-600">
                                    <div class="p-2">
                                        <div>
                                            <div class="text-left text-slate-500">
                                                Status
                                            </div>
                                            <select id="filter-status"
                                                class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 [&amp;[readonly]]:bg-slate-100 [&amp;[readonly]]:cursor-not-allowed [&amp;[readonly]]:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 group-[.form-inline]:flex-1 mt-2 flex-1 w-32"
                                                style="display:block; width: 120px;">
                                                <option value="">
                                                    All
                                                </option>
                                                <option value="0">
                                                    Draft
                                                </option>
                                                <option value="1">
                                                    Published
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="overflow-auto xl:overflow-visible">
                    <table class="w-full text-left border-b border-slate-200/60">
                        <thead class="">
                            <tr class="">
                                <td
                                    class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                    Title & Slug
                                </td>
                                <td
                                    class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                    Kategori
                                </td>
                                <td
                                    class="px-5 border-b dark:border-darkmode-300 w-52 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 text-center">
                                    Status
                                </td>
                                <td
                                    class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 text-center font-medium text-slate-500">
                                    Date
                                </td>
                                <td
                                    class="px-5 border-b dark:border-darkmode-300 w-20 border-t border-slate-200/60 bg-slate-50 py-4 text-center font-medium text-slate-500">
                                    Action </td>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                        </tbody>
                    </table>
                </div>
                <div class="flex-reverse flex flex-col-reverse flex-wrap items-center gap-y-2 p-5 sm:flex-row">
                    <nav class="mr-auto w-full flex-1 sm:w-auto">
                        <ul id="pagination-container" class="flex w-full mr-0 sm:mr-auto sm:w-auto"></ul>
                    </nav>
                    <select id="per-page"
                        class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50
    transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm
    py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20
    dark:bg-darkmode-800 dark:border-transparent rounded-[0.5rem] sm:w-20">
                        <option value="5">5</option>
                        <option value="10">10</option>
                        <option value="25">25</option>
                        <option value="35">35</option>
                        <option value="50">50</option>
                    </select>

                </div>
            </div>
        </div>
    </div>
    <div id="delete-modal" tabindex="-1" aria-hidden="true"
        class="modal group bg-gradient-to-b from-theme-1/50 via-theme-2/50 to-black/50 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 overflow-y-hidden z-[60] [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.1s] overflow-y-auto">

        <div
            class="relative mx-auto my w-[95%] scale-95 transition-transform group-[.show]:scale-100 sm:mt-40 sm:w-[600px] lg:w-[700px]">

            <div
                class="global-search global-search--show-result group relative z-10 max-h-[468px] overflow-y-auto rounded-lg bg-white pb-1 shadow-lg sm:max-h-[615px]">
                <div class="flex items-center justify-between px-5 py-4 border-b">
                    <h3 class="text-base font-semibold text-slate-800">
                        Hapus Artikel
                    </h3>
                </div>
                <div class="px-5 py-4 text-slate-600 text-sm leading-relaxed">
                    Artikel ini akan
                    <span class="font-semibold text-danger">dihapus permanen</span>.
                    Tindakan ini tidak bisa dibatalkan.
                </div>
                <div class="flex justify-end gap-2 px-5 py-4 border-t">
                    <button data-tw-dismiss="modal"
                        class="px-4 py-2 rounded-lg border text-slate-600 hover:bg-slate-100 transition">
                        Batal
                    </button>

                    <form id="delete-form" method="POST">
                        @csrf
                        @method('DELETE')

                        <button id="confirm-delete" class="px-4 py-2 rounded-lg bg-danger text-white hover:bg-danger/90">
                            Hapus
                        </button>

                    </form>
                </div>

            </div>
        </div>
    </div>


@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''

        const tableBody = document.getElementById('table-body')
        const skeleton = document.getElementById('skeleton')
        const emptyState = document.getElementById('empty-state')
        const searchInput = document.getElementById('search-input')
        const searchSpinner = document.getElementById('search-spinner')

        let deleteArticleId = null
        let debounceTimer = null

        function formatDate(date) {
            if (!date) return '-'
            return new Date(date).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            })
        }

        function getThumbnail(path) {
            return path || 'https://placehold.co/40x40?text=IMG'
        }

        function renderArticles(articles) {
            tableBody.innerHTML = ''

            skeleton?.classList.add('hidden')
            emptyState?.classList.add('hidden')

            if (!articles || articles.length === 0) {
                emptyState?.classList.remove('hidden')
                return
            }

            articles.forEach(article => {
                const statusText = article.is_published ? 'Published' : 'Draft'
                const statusClass = article.is_published ? 'text-success' : 'text-slate-400'

                const categories = article.categories?.map(cat => `
                    <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 mr-1">
                        ${cat.name}
                    </span>
                `).join('') || '-'

                const tr = document.createElement('tr')
                tr.dataset.id = article.id
                tr.className = '[&_td]:last:border-b-0'

                tr.innerHTML = `
                    <td class="px-5 border-b w-80 border-dashed py-4 dark:bg-darkmode-600">
                        <div class="flex items-center">
                            <div class="image-fit zoom-in h-9 w-9">
                                <img src="${getThumbnail(article.thumbnail)}"
                                    class="rounded-full shadow cursor-pointer">
                            </div>
                            <div class="ml-3.5">
                                <a class="whitespace-nowrap font-medium" href="#" title="${article.title}">
                                    ${(article.title || '').length > 80
                                        ? (article.title || '').substring(0, 80) + '...'
                                        : article.title || ''}
                                </a>
                                <div class="mt-0.5 whitespace-nowrap text-xs text-slate-500" title="${article.slug}">
                                    ${(article.slug || '').length > 80
                                        ? (article.slug || '').substring(0, 80) + '...'
                                        : article.slug || ''}
                                </div>
                            </div>

                        </div>
                    </td>

                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                        ${categories}
                    </td>

                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                        <div class="flex items-center justify-center ${statusClass}">
                            <div class="ml-1.5 whitespace-nowrap">${statusText}</div>
                        </div>
                    </td>

                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 text-center">
                        <div class="whitespace-nowrap">
                            ${formatDate(article.published_at)}
                        </div>
                    </td>

                    <td class="px-5 border-b dark:border-darkmode-300 relative border-dashed py-3 dark:bg-darkmode-600">
                        <div class="flex items-center justify-center gap-2">
                            <a href="/articles/${article.id}/edit"
                                data-tw-merge
                                class="transition duration-200 border shadow-sm inline-flex items-center justify-center
                                py-1 px-2 w-20 text-sm rounded-md font-medium cursor-pointer
                                focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none
                                dark:focus:ring-slate-700 dark:focus:ring-opacity-50
                                [&:hover:not(:disabled)]:bg-opacity-90
                                [&:hover:not(:disabled)]:border-opacity-90
                                disabled:opacity-70 disabled:cursor-not-allowed
                                border-warning text-warning dark:border-warning
                                [&:hover:not(:disabled)]:bg-warning/10">
                                Edit
                            </a>

                           <div
                                class="cursor-pointer inline-flex items-center justify-center
                                py-1 px-3 text-sm rounded-md font-medium
                                border border-danger text-danger
                                hover:bg-danger/10 transition"
                                data-tw-toggle="modal"
                                data-tw-target="#delete-modal"
                                data-id="${article.id}">
                                Hapus
                            </div>
                        </div>
                    </td>

                    `
                tableBody.appendChild(tr)
            })
        }

        let currentPage = 1
        let currentQuery = ''
        let currentPerPage = 5

        function loadArticles(query = '', page = 1) {
            if (query instanceof Event) query = ''

            currentQuery = query
            currentPage = page

            const status = document.getElementById('filter-status')?.value || ''

            axios.get('{{ route('articles.list') }}', {
                params: {
                    q: currentQuery,
                    is_published: status ?? true,
                    page: currentPage,
                    per_page: currentPerPage
                }
            }).then(res => {
                const paginator = res.data.data
                renderArticles(paginator.data)
                renderPagination(paginator)
            })
        }


        document.getElementById('filter-status')?.addEventListener('change', () => {
            loadArticles(currentQuery, 1)
        })

        searchInput?.addEventListener('input', e => {
            clearTimeout(debounceTimer)
            debounceTimer = setTimeout(() => loadArticles(e.target.value), 500)
        })

        tableBody.addEventListener('click', e => {
            const btn = e.target.closest('.btn-delete')
            if (!btn) return

            const row = btn.closest('tr')
            deleteArticleId = row.dataset.id

            if (!confirm('Yakin ingin menghapus artikel ini?')) return

            axios.delete(`/articles/${deleteArticleId}`)
                .then(() => loadArticles(searchInput?.value || ''))
                .finally(() => deleteArticleId = null)
        })

        const perPageSelect = document.getElementById('per-page')

        perPageSelect.addEventListener('change', e => {
            currentPerPage = e.target.value
            loadArticles(currentQuery, 1)
        })


        window.addEventListener('load', loadArticles())
    </script>
    <script>
        function renderPagination(paginator) {
            const container = document.getElementById('pagination-container')
            container.innerHTML = ''

            const current = paginator.current_page
            const last = paginator.last_page

            const li = (content, page = null, active = false) => `
                <li class="flex-1 sm:flex-initial">
                    <a
                        ${page ? `onclick="loadArticles(currentQuery, ${page})"` : ''}
                        class="transition duration-200 border items-center justify-center py-2 rounded-md cursor-pointer
                        focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none
                        dark:focus:ring-slate-700 dark:focus:ring-opacity-50
                        [&:hover:not(:disabled)]:bg-opacity-90
                        [&:hover:not(:disabled)]:border-opacity-90
                        [&:not(button)]:text-center
                        min-w-0 sm:min-w-[40px] shadow-none font-normal flex
                        border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3
                        ${active ? '!box dark:bg-darkmode-400' : ''}">
                        ${content}
                    </a>
                </li>`

            if (current > 1) {
                container.innerHTML += li(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="h-4 w-4">
                    <path d="m11 17-5-5 5-5"></path>
                    <path d="m18 17-5-5 5-5"></path>
                    </svg>`,
                    1)

                container.innerHTML += li(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="h-4 w-4">
                    <path d="m15 18-6-6 6-6"></path>
                    </svg>`,
                    current - 1)
            }

            paginator.links.forEach(link => {
                if (
                    link.label.includes('Previous') ||
                    link.label.includes('Next')
                ) return

                if (link.label === '...') {
                    container.innerHTML += li('...')
                    return
                }

                const page = link.url ?
                    new URL(link.url).searchParams.get('page') :
                    null

                if (page) {
                    container.innerHTML += li(link.label, page, link.active)
                }
            })

            if (current < last) {
                container.innerHTML += li(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="h-4 w-4">
                    <path d="m9 18 6-6-6-6"></path>
                    </svg>`,
                    current + 1)

                container.innerHTML += li(`
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                    class="h-4 w-4">
                    <path d="m6 17 5-5-5-5"></path>
                    <path d="m13 17 5-5-5-5"></path>
                    </svg>`,
                    last)
            }
        }


        function getPageFromUrl(url) {
            if (!url) return null
            const params = new URL(url).searchParams
            return params.get('page')
        }
    </script>
    <script>
        let deleteId = null

        document.addEventListener('click', function(e) {
            const openBtn = e.target.closest('[data-tw-target="#delete-modal"]')
            if (openBtn) {
                deleteId = openBtn.getAttribute('data-id')
            }

            const confirmBtn = e.target.closest('#confirm-delete')
            if (!confirmBtn || !deleteId) return

            confirmBtn.disabled = true
            confirmBtn.textContent = 'Menghapus...'

            axios.delete(`/articles/${deleteId}/delete`, {
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .getAttribute('content')
                }
            }).then(res => {
                if (res.data.status) {
                    document.querySelector(`[data-row-id="${deleteId}"]`)?.remove()

                     showToast(
                        'success',
                        'Berhasil',
                        'Artikel berhasil dihapus. load data terbaru...'
                    )
                    document
                        .getElementById('delete-modal')
                        .classList.remove('show')

                    deleteId = null
                     loadArticles(currentQuery, 1)
                }
            }).finally(() => {
                confirmBtn.disabled = false
                confirmBtn.textContent = 'Hapus'
            })
        })
    </script>
@endpush
