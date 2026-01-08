@extends('layouts.app')

@section('title', 'AI Chat Sessions')

@section('content')
<div class="col-span-12">
    <div class="p-6">
        <!-- Header Section -->
        <div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">AI Chat Sessions</h1>
                <p class="text-slate-500">Daftar riwayat sesi percakapan AI untuk audit dan monitoring.</p>
            </div>
            <div class="flex gap-3">
                <button class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50">
                    <i class="ri-download-2-line"></i> Export
                </button>
                <button class="flex items-center gap-2 rounded-lg border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-600 transition-colors hover:bg-slate-50">
                    <i class="ri-filter-3-line"></i> Filters
                </button>
            </div>
        </div>

        <!-- Table Card -->
        <div class="rounded-xl border border-slate-200 bg-white shadow-sm overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50/50 px-6 py-4">
                <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="relative w-full md:w-96">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                            <i class="ri-search-line"></i>
                        </span>
                        <input id="search-input" type="text" 
                            class="w-full rounded-lg border border-slate-200 py-2 pl-10 pr-4 text-sm outline-none focus:border-primary focus:ring-1 focus:ring-primary/20" 
                            placeholder="Cari berdasarkan judul, model, atau IP...">
                    </div>
                    <div id="search-spinner" class="hidden">
                        <div class="h-4 w-4 animate-spin rounded-full border-2 border-primary border-t-transparent"></div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm text-slate-600">
                    <thead class="bg-slate-50 text-xs font-semibold uppercase text-slate-500">
                        <tr>
                            <th class="px-6 py-4 font-medium tracking-wider">Session & User</th>
                            <th class="px-6 py-4 font-medium tracking-wider">AI Model</th>
                            <th class="px-6 py-4 font-medium tracking-wider">Source Network</th>
                            <th class="px-6 py-4 font-medium tracking-wider">Aktivitas Terakhir</th>
                            <th class="px-6 py-4 text-right font-medium tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="table-body" class="divide-y divide-slate-200">
                        <!-- Data handles by JS -->
                    </tbody>
                </table>
            </div>

            <!-- Skeleton Loader -->
            <div id="skeleton" class="divide-y divide-slate-200">
                @for ($i = 0; $i < 5; $i++)
                <div class="animate-pulse px-6 py-5">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 rounded-full bg-slate-200"></div>
                        <div class="flex-1 space-y-2">
                            <div class="h-4 w-1/4 rounded bg-slate-200"></div>
                            <div class="h-3 w-1/3 rounded bg-slate-100"></div>
                        </div>
                    </div>
                </div>
                @endfor
            </div>

            <!-- Empty State -->
            <div id="empty-state" class="hidden px-6 py-20 text-center">
                <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-slate-50 text-slate-400">
                    <i class="ri-chat-history-line text-3xl"></i>
                </div>
                <h3 class="mt-4 text-lg font-semibold text-slate-700">Tidak ada sesi ditemukan</h3>
                <p class="text-slate-500">Coba kata kunci lain atau periksa filter Anda.</p>
            </div>

            <!-- Pagination -->
            <div class="border-t border-slate-200 px-6 py-4">
                <div class="flex items-center justify-between">
                    <p id="pagination-info" class="text-xs text-slate-500">Menampilkan 0 sampai 0 dari 0 data</p>
                    <div id="pagination-links" class="flex gap-2">
                        <!-- Pagination handles by JS -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="invisible fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 opacity-0 transition-all duration-300">
    <div class="w-full max-w-md transform rounded-xl bg-white p-6 shadow-xl transition-all scale-95">
        <div class="flex h-12 w-12 items-center justify-center rounded-full bg-red-100 text-red-600">
            <i class="ri-error-warning-line text-2xl"></i>
        </div>
        <h3 class="mt-4 text-xl font-bold text-slate-800">Hapus Sesi?</h3>
        <p class="mt-2 text-slate-600">Apakah Anda yakin ingin menghapus sesi "<span id="session-title-delete" class="font-semibold text-slate-900"></span>"? Tindakan ini tidak dapat dibatalkan.</p>
        <div class="mt-8 flex justify-end gap-3">
            <button onclick="closeModal()" class="rounded-lg px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-50">Batal</button>
            <button id="btn-confirm-delete" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-red-700">Ya, Hapus Sesi</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    const tableBody = document.getElementById('table-body')
    const skeleton = document.getElementById('skeleton')
    const emptyState = document.getElementById('empty-state')
    const searchInput = document.getElementById('search-input')
    const searchSpinner = document.getElementById('search-spinner')
    const paginationLinks = document.getElementById('pagination-links')
    const paginationInfo = document.getElementById('pagination-info')
    
    const deleteModal = document.getElementById('deleteModal')
    const btnConfirmDelete = document.getElementById('btn-confirm-delete')
    const sessionTitleDelete = document.getElementById('session-title-delete')

    let deleteId = null
    let currentPage = 1
    let debounceTimer = null

    function loadSessions(query = '', page = 1) {
        skeleton.classList.remove('hidden')
        tableBody.innerHTML = ''
        emptyState.classList.add('hidden')
        searchSpinner.classList.remove('hidden')
        currentPage = page

        axios.get('{{ route("ai_chat.list") }}', { params: { q: query, page: page } })
            .then(res => {
                const data = res.data.data
                renderSessions(data.data)
                renderPagination(data)
            })
            .catch(err => {
                console.error(err)
                Swal.fire('Error', 'Gagal memuat data sesi', 'error')
            })
            .finally(() => {
                skeleton.classList.add('hidden')
                searchSpinner.classList.add('hidden')
            })
    }

    function renderSessions(sessions) {
        tableBody.innerHTML = ''
        if (!sessions || sessions.length === 0) {
            emptyState.classList.remove('hidden')
            return
        }

        sessions.forEach(s => {
            const date = new Date(s.last_activity_at)
            const row = document.createElement('tr')
            row.className = 'hover:bg-slate-50/80 transition-colors group'
            
            const initials = s.user ? (s.user.email.substring(0,2).toUpperCase()) : 'AN'
            const avatarColor = s.user ? 'bg-primary/10 text-primary' : 'bg-slate-100 text-slate-500'

            row.innerHTML = `
                <td class="px-6 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-full text-xs font-bold ${avatarColor}">
                            ${initials}
                        </div>
                        <div class="min-w-0">
                            <div class="font-bold text-slate-800 truncate">${s.title || 'Untitled Session'}</div>
                            <div class="text-xs text-slate-500 truncate">${s.user ? s.user.email : 'Guest User'}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                        ${s.model}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <div class="flex flex-col">
                        <span class="text-xs font-semibold text-slate-700">${s.ip_address || '-'}</span>
                        <span class="text-[10px] text-slate-400">${s.messages_count || s.messages?.length || 0} messages</span>
                    </div>
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <div class="h-2 w-2 rounded-full bg-emerald-500"></div>
                        <span class="text-xs font-medium text-slate-700">${date.toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' })}</span>
                    </div>
                </td>
                <td class="px-6 py-4 text-right">
                    <div class="flex justify-end gap-2 transition-all duration-200">
                        <a href="/ai/chat/sessions/${s.id}" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:border-primary hover:text-primary hover:bg-primary/5 transition-all shadow-sm" title="Lihat Detail Chat">
                            <i class="ri-eye-line"></i>
                        </a>
                        <button onclick="confirmDelete(${s.id}, \`${s.title || 'Untitled'}\`)" class="flex h-8 w-8 items-center justify-center rounded-lg border border-slate-200 bg-white text-slate-500 hover:border-red-500 hover:text-red-500 hover:bg-red-50 transition-all shadow-sm" title="Hapus Sesi">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </div>
                </td>
            `
            tableBody.appendChild(row)
        })
    }

    function renderPagination(data) {
        paginationLinks.innerHTML = ''
        paginationInfo.textContent = `Menampilkan ${data.from || 0} sampai ${data.to || 0} dari ${data.total} data`

        if (data.last_page <= 1) return

        data.links.forEach(link => {
            if (link.url === null && link.label.includes('...')) return

            const btn = document.createElement('button')
            btn.className = `flex h-9 min-w-[36px] items-center justify-center rounded-lg border px-3 text-sm font-medium transition-all ${link.active ? 'border-primary bg-primary text-white shadow-sm' : 'border-slate-200 bg-white text-slate-600 hover:bg-slate-50'}`
            btn.innerHTML = link.label.replace('&laquo;', '<').replace('&raquo;', '>')
            
            if (link.url) {
                const pageNum = new URL(link.url).searchParams.get('page')
                btn.onclick = () => loadSessions(searchInput.value, pageNum)
            } else {
                btn.disabled = true
                btn.classList.add('opacity-50', 'cursor-not-allowed')
            }
            
            paginationLinks.appendChild(btn)
        })
    }

    searchInput.addEventListener('input', e => {
        clearTimeout(debounceTimer)
        debounceTimer = setTimeout(() => loadSessions(e.target.value, 1), 400)
    })

    function confirmDelete(id, title) {
        deleteId = id
        sessionTitleDelete.textContent = title
        deleteModal.classList.remove('invisible', 'opacity-0')
        deleteModal.querySelector('div').classList.remove('scale-95')
        deleteModal.querySelector('div').classList.add('scale-100')
    }

    function closeModal() {
        deleteModal.classList.add('opacity-0')
        deleteModal.querySelector('div').classList.remove('scale-100')
        deleteModal.querySelector('div').classList.add('scale-95')
        setTimeout(() => deleteModal.classList.add('invisible'), 300)
    }

    btnConfirmDelete.addEventListener('click', () => {
        if (!deleteId) return
        btnConfirmDelete.disabled = true
        btnConfirmDelete.innerHTML = '<span class="animate-spin mr-2">...</span> Deleting'

        axios.delete(`/ai/chat/sessions/${deleteId}`)
            .then(res => {
                closeModal()
                Swal.fire({ icon: 'success', title: 'Deleted!', text: res.data.message, timer: 1500, showConfirmButton: false })
                loadSessions(searchInput.value, currentPage)
            })
            .catch(err => {
                console.error(err)
                Swal.fire('Error', 'Gagal menghapus sesi', 'error')
            })
            .finally(() => {
                btnConfirmDelete.disabled = false
                btnConfirmDelete.textContent = 'Ya, Hapus Sesi'
                deleteId = null
            })
    })

    window.onload = () => loadSessions()
</script>
@endpush
