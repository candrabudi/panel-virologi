@extends('layouts.app')
@section('title', 'Data Serangan Cyber')
@section('content')
    <div class="col-span-12">
        <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
            <div class="text-base font-medium group-[.mode--light]:text-white">Data Serangan Cyber</div>
            <div class="flex flex-col gap-x-3 gap-y-2 sm:flex-row md:ml-auto">
                <a href="/cyber-attacks/import"
                    class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed bg-success border-success text-white dark:border-success group-[.mode--light]:!border-transparent group-[.mode--light]:!bg-white/[0.12] group-[.mode--light]:!text-slate-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="lucide lucide-upload mr-2 h-4 w-4 stroke-[1.3]">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                        <polyline points="17 8 12 3 7 8"></polyline>
                        <line x1="12" x2="12" y1="3" y2="15"></line>
                    </svg> Import Data
                </a>
            </div>
        </div>
        <div class="mt-3.5 flex flex-col gap-8">
            <div class="box box--stacked flex flex-col">
                <div class="flex flex-col gap-y-2 p-5 sm:flex-row sm:items-center">
                    <div>
                        <div class="relative">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round"
                                class="lucide lucide-search absolute inset-y-0 left-0 z-10 my-auto ml-3 h-4 w-4 stroke-[1.3] text-slate-500">
                                <circle cx="11" cy="11" r="8"></circle>
                                <path d="m21 21-4.3-4.3"></path>
                            </svg>
                            <input type="text" placeholder="Cari berdasarkan IP, attack ID, atau negara..." id="search-input"
                                class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 rounded-[0.5rem] pl-9 sm:w-64">
                        </div>
                    </div>
                    <div class="flex flex-col gap-x-3 gap-y-2 sm:ml-auto sm:flex-row">
                        <div data-tw-placement="bottom-end" class="dropdown relative inline-block">
                            <button data-tw-toggle="dropdown" aria-expanded="false"
                                class="transition duration-200 border shadow-sm inline-flex items-center justify-center py-2 px-3 rounded-md font-medium cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus-visible:outline-none dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&:hover:not(:disabled)]:bg-opacity-90 [&:hover:not(:disabled)]:border-opacity-90 [&:not(button)]:text-center disabled:opacity-70 disabled:cursor-not-allowed border-secondary text-slate-500 dark:border-darkmode-100/40 dark:text-slate-300 [&:hover:not(:disabled)]:bg-secondary/20 [&:hover:not(:disabled)]:dark:bg-darkmode-100/10 w-full sm:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="lucide lucide-filter mr-2 h-4 w-4 stroke-[1.3]">
                                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                                </svg>
                                Filter
                            </button>
                            <div class="dropdown-menu z-[9999] hidden">
                                <div
                                    class="dropdown-content rounded-md border-transparent bg-white p-2 shadow-[0px_3px_10px_#00000017] dark:border-transparent dark:bg-darkmode-600">
                                    <div class="p-2 space-y-3">
                                        <div>
                                            <div class="text-left text-slate-500 text-sm mb-1">
                                                Jenis Serangan
                                            </div>
                                            <select id="filter-attack-type"
                                                class="disabled:bg-slate-100 disabled:cursor-not-allowed transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 focus:ring-4 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800">
                                                <option value="">Semua</option>
                                                <option value="DDoS">DDoS</option>
                                                <option value="Malware">Malware</option>
                                                <option value="Phishing">Phishing</option>
                                                <option value="SQL Injection">SQL Injection</option>
                                                <option value="XSS">XSS</option>
                                            </select>
                                        </div>
                                        <div>
                                            <div class="text-left text-slate-500 text-sm mb-1">
                                                Protocol
                                            </div>
                                            <select id="filter-protocol"
                                                class="disabled:bg-slate-100 disabled:cursor-not-allowed transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md py-2 px-3 focus:ring-4 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800">
                                                <option value="">Semua</option>
                                                <option value="TCP">TCP</option>
                                                <option value="UDP">UDP</option>
                                                <option value="HTTP">HTTP</option>
                                                <option value="HTTPS">HTTPS</option>
                                                <option value="ICMP">ICMP</option>
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
                        <thead>
                            <tr>
                                <td
                                    class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                    Attack ID
                                </td>
                                <td
                                    class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                    Source IP
                                </td>
                                <td
                                    class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                    Destination IP
                                </td>
                                <td
                                    class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                    Attack Type
                                </td>
                                <td
                                    class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 text-center">
                                    Protocol
                                </td>
                                <td
                                    class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 text-center">
                                    Confidence
                                </td>
                                <td
                                    class="px-5 border-b dark:border-darkmode-300 w-20 border-t border-slate-200/60 bg-slate-50 py-4 text-center font-medium text-slate-500">
                                    Action
                                </td>
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
                        class="disabled:bg-slate-100 disabled:cursor-not-allowed disabled:dark:bg-darkmode-800/50 transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm py-2 px-3 pr-8 focus:ring-4 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent rounded-[0.5rem] sm:w-20">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="delete-modal" tabindex="-1" aria-hidden="true"
        class="modal group bg-gradient-to-b from-theme-1/50 via-theme-2/50 to-black/50 transition-[visibility,opacity] w-screen h-screen fixed left-0 top-0 overflow-y-hidden z-[60] [&:not(.show)]:duration-[0s,0.2s] [&:not(.show)]:delay-[0.2s,0s] [&:not(.show)]:invisible [&:not(.show)]:opacity-0 [&.show]:visible [&.show]:opacity-100 [&.show]:duration-[0s,0.1s] overflow-y-auto">
        <div
            class="relative mx-auto my w-[95%] scale-95 transition-transform group-[.show]:scale-100 sm:mt-40 sm:w-[600px] lg:w-[700px]">
            <div
                class="global-search global-search--show-result group relative z-10 max-h-[468px] overflow-y-auto rounded-lg bg-white pb-1 shadow-lg sm:max-h-[615px]">
                <div class="flex items-center justify-between px-5 py-4 border-b">
                    <h3 class="text-base font-semibold text-slate-800">
                        Hapus Data Serangan
                    </h3>
                </div>
                <div class="px-5 py-4 text-slate-600 text-sm leading-relaxed">
                    Data serangan ini akan
                    <span class="font-semibold text-danger">dihapus permanen</span>.
                    Tindakan ini tidak bisa dibatalkan.
                </div>
                <div class="flex justify-end gap-2 px-5 py-4 border-t">
                    <button data-tw-dismiss="modal"
                        class="px-4 py-2 rounded-lg border text-slate-600 hover:bg-slate-100 transition">
                        Batal
                    </button>
                    <button id="confirm-delete" class="px-4 py-2 rounded-lg bg-danger text-white hover:bg-danger/90">
                        Hapus
                    </button>
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
        const searchInput = document.getElementById('search-input')
        let debounceTimer = null
        let deleteId = null

        function truncateText(text = '', max = 30) {
            if (!text) return '-'
            return text.length > max ? text.substring(0, max) + '...' : text
        }

        function renderAttacks(attacks) {
            tableBody.innerHTML = ''

            if (!attacks || attacks.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="7" class="text-center py-4 text-slate-500">Tidak ada data</td></tr>'
                return
            }

            attacks.forEach(attack => {
                const confidenceClass = attack.confidence_score >= 0.8 ? 'text-danger' : attack.confidence_score >= 0.5 ? 'text-warning' : 'text-success'
                
                const tr = document.createElement('tr')
                tr.dataset.id = attack.id
                tr.innerHTML = `
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                        <div class="font-medium text-xs">${attack.attack_id || '-'}</div>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                        <div class="font-medium">${attack.source_ip || '-'}</div>
                        <div class="text-xs text-slate-500">${attack.source_country || '-'}</div>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                        <div class="font-medium">${attack.destination_ip || '-'}</div>
                        <div class="text-xs text-slate-500">${attack.destination_country || '-'}</div>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                        <span class="text-xs px-2 py-1 rounded-full bg-danger/10 text-danger font-medium">
                            ${attack.attack_type || '-'}
                        </span>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 text-center">
                        <span class="text-xs px-2 py-1 rounded-full bg-slate-100 text-slate-600">
                            ${attack.protocol || '-'}
                        </span>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 text-center">
                        <span class="${confidenceClass} font-semibold">
                            ${attack.confidence_score ? (attack.confidence_score * 100).toFixed(1) + '%' : '-'}
                        </span>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 text-center">
                        <div class="cursor-pointer inline-flex items-center justify-center py-1 px-3 text-sm rounded-md font-medium border border-danger text-danger hover:bg-danger/10 transition"
                            data-tw-toggle="modal" data-tw-target="#delete-modal" data-id="${attack.id}">
                            Hapus
                        </div>
                    </td>
                `
                tableBody.appendChild(tr)
            })
        }

        let currentPage = 1
        let currentQuery = ''
        let currentPerPage = 10

        function loadAttacks(query = '', page = 1) {
            if (query instanceof Event) query = ''

            currentQuery = query
            currentPage = page

            const attackType = document.getElementById('filter-attack-type')?.value || ''
            const protocol = document.getElementById('filter-protocol')?.value || ''

            axios.get('/cyber-attacks/list', {
                params: {
                    q: currentQuery,
                    attack_type: attackType,
                    protocol: protocol,
                    page: currentPage,
                    per_page: currentPerPage
                }
            }).then(res => {
                const paginator = res.data.data
                renderAttacks(paginator.data)
                renderPagination(paginator)
            }).catch(err => {
                console.error('Failed to load attacks', err)
            })
        }

        document.getElementById('filter-attack-type')?.addEventListener('change', () => loadAttacks(currentQuery, 1))
        document.getElementById('filter-protocol')?.addEventListener('change', () => loadAttacks(currentQuery, 1))

        searchInput?.addEventListener('input', e => {
            clearTimeout(debounceTimer)
            debounceTimer = setTimeout(() => loadAttacks(e.target.value), 500)
        })

        document.getElementById('per-page')?.addEventListener('change', e => {
            currentPerPage = e.target.value
            loadAttacks(currentQuery, 1)
        })

        // Delete functionality
        document.addEventListener('click', function(e) {
            const openBtn = e.target.closest('[data-tw-target="#delete-modal"]')
            if (openBtn) {
                deleteId = openBtn.getAttribute('data-id')
            }

            const confirmBtn = e.target.closest('#confirm-delete')
            if (!confirmBtn || !deleteId) return

            confirmBtn.disabled = true
            confirmBtn.textContent = 'Menghapus...'

            axios.delete(`/cyber-attacks/${deleteId}`, {
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            }).then(res => {
                if (res.data.status) {
                    showToast('success', 'Berhasil', 'Data berhasil dihapus')
                    document.getElementById('delete-modal').classList.remove('show')
                    deleteId = null
                    loadAttacks(currentQuery, currentPage)
                }
            }).finally(() => {
                confirmBtn.disabled = false
                confirmBtn.textContent = 'Hapus'
            })
        })

        window.addEventListener('load', () => loadAttacks())
    </script>
    <script>
        function renderPagination(paginator) {
            const container = document.getElementById('pagination-container')
            container.innerHTML = ''

            const current = paginator.current_page
            const last = paginator.last_page

            const li = (content, page = null, active = false) => `
                <li class="flex-1 sm:flex-initial">
                    <a ${page ? `onclick="loadAttacks(currentQuery, ${page})"` : ''}
                        class="transition duration-200 border items-center justify-center py-2 rounded-md cursor-pointer focus:ring-4 focus:ring-primary focus:ring-opacity-20 min-w-0 sm:min-w-[40px] shadow-none font-normal flex border-transparent text-slate-800 sm:mr-2 dark:text-slate-300 px-1 sm:px-3 ${active ? '!box dark:bg-darkmode-400' : ''}">
                        ${content}
                    </a>
                </li>`

            if (current > 1) {
                container.innerHTML += li('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m11 17-5-5 5-5"></path><path d="m18 17-5-5 5-5"></path></svg>', 1)
                container.innerHTML += li('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m15 18-6-6 6-6"></path></svg>', current - 1)
            }

            paginator.links.forEach(link => {
                if (link.label.includes('Previous') || link.label.includes('Next')) return
                if (link.label === '...') {
                    container.innerHTML += li('...')
                    return
                }

                const page = link.url ? new URL(link.url).searchParams.get('page') : null
                if (page) {
                    container.innerHTML += li(link.label, page, link.active)
                }
            })

            if (current < last) {
                container.innerHTML += li('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m9 18 6-6-6-6"></path></svg>', current + 1)
                container.innerHTML += li('<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-4 w-4"><path d="m6 17 5-5-5-5"></path><path d="m13 17 5-5-5-5"></path></svg>', last)
            }
        }
    </script>
@endpush
