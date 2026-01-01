@extends('layouts.app')
@push('styles')
    <style>
        .d-none {
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="col-span-12">
        <div class="mb-8">
            <h2 class="text-xl font-semibold">Data Tag</h2>
        </div>

        <div class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-4 space-y-6">

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3" id="form-title">
                        Add Tag
                    </h3>
                    <form id="form-tag">
                        @csrf
                        <div class="mt-3">
                            <input type="hidden" id="tag_id">
                            <label class="block mb-2 text-sm font-medium">Tag Name</label>
                            <input type="text" name="title"
                                class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control"
                                placeholder="Judul artikel" id="name">
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-4 px-4">
                            <button type="button"
                                class="px-5 py-2 rounded-md border text-sm
        text-slate-600 hover:bg-slate-100 transition d-none"
                                id="btn-cancel">
                                Batal
                            </button>

                            <button type="submit"
                                class="px-5 py-2 rounded-md border border-primary
        text-primary hover:bg-primary/10 transition">
                                Simpan
                            </button>
                        </div>

                    </form>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-8 space-y-6">
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
                    </div>
                    <div class="overflow-auto xl:overflow-visible">
                        <table class="w-full text-left border-b border-slate-200/60">
                            <thead class="">
                                <tr class="">
                                    <td
                                        class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                        Name
                                    </td>
                                    <td
                                        class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                        Slug
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
    </div>

    <div id="delete-modal" tabindex="-1" aria-hidden="true"
        class="modal group bg-gradient-to-b from-theme-1/50 via-theme-2/50 to-black/50 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 overflow-y-hidden z-[60] [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.1s] overflow-y-auto">

        <div
            class="relative mx-auto my w-[95%] scale-95 transition-transform group-[.show]:scale-100 sm:mt-40 sm:w-[600px] lg:w-[700px]">

            <div
                class="global-search global-search--show-result group relative z-10 max-h-[468px] overflow-y-auto rounded-lg bg-white pb-1 shadow-lg sm:max-h-[615px]">
                <div class="flex items-center justify-between px-5 py-4 border-b">
                    <h3 class="text-base font-semibold text-slate-800">
                        Hapus Kategori
                    </h3>
                </div>
                <div class="px-5 py-4 text-slate-600 text-sm leading-relaxed">
                    Kategori ini akan
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

        const idEl = document.getElementById('tag_id')
        const nameEl = document.getElementById('name')
        const formTitle = document.getElementById('form-title')
        const btnSubmit = document.getElementById('btn-submit')

        const tableBody = document.getElementById('table-body')
        const skeleton = document.getElementById('skeleton')
        const emptyState = document.getElementById('empty-state')
        const searchInput = document.getElementById('search-input')
        const searchSpinner = document.getElementById('search-spinner')
        const btnCancel = document.getElementById('btn-cancel')

        let deletetagId = null
        let debounceTimer = null

        function formatDate(date) {
            if (!date) return '-'
            return new Date(date).toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'long',
                year: 'numeric'
            })
        }

        function resetForm() {
            idEl.value = ''
            nameEl.value = ''
            formTitle.textContent = 'Add Tag'
            btnCancel.classList.add('d-none')
        }

        function renderTags(tags) {
            tableBody.innerHTML = ''

            skeleton?.classList.add('hidden')
            emptyState?.classList.add('hidden')

            if (!tags || tags.length === 0) {
                emptyState?.classList.remove('hidden')
                return
            }

            tags.forEach(tag => {
                const statusText = tag.is_published ? 'Published' : 'Draft'
                const statusClass = tag.is_published ? 'text-success' : 'text-slate-400'

                const categories = tag.categories?.map(cat => `
                    <span class="text-xs px-2 py-0.5 rounded-full bg-slate-100 text-slate-600 mr-1">
                        ${cat.name}
                    </span>
                `).join('') || '-'

                const tr = document.createElement('tr')
                tr.dataset.id = tag.id
                tr.className = '[&_td]:last:border-b-0'
                tr.dataset.item = JSON.stringify(tag)
                tr.innerHTML = `
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                        ${tag.name}
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                        ${tag.slug}
                    </td>

                    <td class="px-5 border-b dark:border-darkmode-300 relative border-dashed py-3 dark:bg-darkmode-600">
                        <div class="flex items-center justify-center gap-2">
                            <button
                                data-tw-merge
                                class="transition duration-200 border shadow-sm inline-flex items-center justify-center
                                py-1 px-2 w-20 text-sm rounded-md font-medium cursor-pointer
                                focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none
                                dark:focus:ring-slate-700 dark:focus:ring-opacity-50
                                [&:hover:not(:disabled)]:bg-opacity-90
                                [&:hover:not(:disabled)]:border-opacity-90
                                disabled:opacity-70 disabled:cursor-not-allowed
                                border-warning text-warning dark:border-warning
                                [&:hover:not(:disabled)]:bg-warning/10 btn-edit">
                                Edit
                            </button>

                           <div
                                class="cursor-pointer inline-flex items-center justify-center
                                py-1 px-3 text-sm rounded-md font-medium
                                border border-danger text-danger
                                hover:bg-danger/10 transition"
                                data-tw-toggle="modal"
                                data-tw-target="#delete-modal"
                                data-id="${tag.id}">
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

        function loadTags(query = '', page = 1) {
            if (query instanceof Event) query = ''

            currentQuery = query
            currentPage = page

            const status = document.getElementById('filter-status')?.value || ''

            axios.get('/articles/tags/list', {
                params: {
                    q: currentQuery,
                    page: currentPage,
                    per_page: currentPerPage
                }
            }).then(res => {
                const paginator = res.data.data
                renderTags(paginator.data)
                renderPagination(paginator)
            })
        }

        searchInput?.addEventListener('input', e => {
            clearTimeout(debounceTimer)
            debounceTimer = setTimeout(() => loadTags(e.target.value), 500)
        })

        tableBody.addEventListener('click', e => {
            const tr = e.target.closest('tr')
            if (!tr) return
            const data = JSON.parse(tr.dataset.item)

            if (e.target.classList.contains('btn-edit')) {
                idEl.value = data.id
                nameEl.value = data.name
                formTitle.textContent = 'Edit Tag'
                btnCancel.classList.remove('d-none')
            }

            if (e.target.classList.contains('btn-delete')) {
                deleteId = data.id
                modal.show()
            }
        })

        tableBody.addEventListener('click', e => {
            const btn = e.target.closest('.btn-delete')
            if (!btn) return

            const row = btn.closest('tr')
            deletetagId = row.dataset.id

            if (!confirm('Yakin ingin menghapus artikel ini?')) return

            axios.delete(`/tags/${deletetagId}`)
                .then(() => loadTags(searchInput?.value || ''))
                .finally(() => deletetagId = null)
        })

        const perPageSelect = document.getElementById('per-page')

        perPageSelect.addEventListener('change', e => {
            currentPerPage = e.target.value
            loadTags(currentQuery, 1)
        })

        btnCancel.onclick = resetForm

        window.addEventListener('load', loadTags())

        document.getElementById('form-tag').addEventListener('submit', (e) => {
            e.preventDefault();

            // btnSubmit.disabled = true
            // spinner.classList.remove('d-none')
            // btnText.classList.add('d-none')

            const payload = {
                name: nameEl.value
            }

            const req = idEl.value ?
                axios.put(`/articles/tags/${idEl.value}`, payload) :
                axios.post('/articles/categories', payload)

            req.then(() => {
                showToast(
                    'success',
                    'Berhasil',
                    'Kategori berhasil di simpan. load data terbaru...'
                )
                resetForm()
                loadTags()
            }).catch(err => {
                const msg = err.response?.data?.errors ?
                    Object.values(err.response.data.errors).flat().join('<br>') :
                    'Terjadi kesalahan'
                alertMsg('danger', msg)
            }).finally(() => {
                btnSubmit.disabled = false
                spinner.classList.add('d-none')
                btnText.classList.remove('d-none')
            })
        })
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
                        ${page ? `onclick="loadTags(currentQuery, ${page})"` : ''}
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

            axios.delete(`/articles/tags/${deleteId}/delete`, {
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
                        'Tag berhasil dihapus. load data terbaru...'
                    )
                    document
                        .getElementById('delete-modal')
                        .classList.remove('show')

                    deleteId = null
                    loadTags(currentQuery, 1)
                }
            }).finally(() => {
                confirmBtn.disabled = false
                confirmBtn.textContent = 'Hapus'
            })
        })
    </script>
@endpush
