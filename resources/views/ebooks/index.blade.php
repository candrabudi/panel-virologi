@extends('template.app')

@section('title', 'Ebooks')

@section('content')
    <div class="container-fluid py-4">

        <!-- PAGE HEADER -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body px-4 py-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div>
                    <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-book-half text-primary fs-3"></i>
                        Ebook Cyber Security
                    </h3>
                    <p class="text-muted mb-0 fs-6">
                        Repository ebook untuk knowledge base & AI security agent
                    </p>
                </div>
                <a href="{{ route('ebooks.create') }}"
                    class="btn btn-primary rounded-pill px-4 fw-semibold fs-6 d-flex align-items-center gap-2 mt-3 mt-md-0">
                    <i class="bi bi-plus-lg fs-6"></i>
                    Tambah Ebook
                </a>
            </div>
        </div>

        <!-- ALERT -->
        <div id="alert-box" class="alert d-none rounded-3 shadow-sm fs-6"></div>

        <!-- CONTENT -->
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom px-4 py-3">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-list-ul text-secondary fs-5"></i>
                        Daftar Ebook
                    </h5>

                    <div class="input-group shadow-sm" style="max-width: 380px;">
                        <span class="input-group-text bg-light border-0 fs-5">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input id="search-input" type="text" class="form-control border-0 fs-6"
                            placeholder="Cari judul ebook...">
                        <span class="input-group-text d-none bg-white" id="search-spinner">
                            <span class="spinner-border spinner-border-sm"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">

                <!-- SKELETON -->
                <div id="skeleton">
                    @for ($i = 0; $i < 5; $i++)
                        <div class="d-flex align-items-center gap-3 px-4 py-4 border-bottom placeholder-glow">
                            <span class="placeholder rounded-3" style="width:80px;height:80px;"></span>
                            <div class="flex-grow-1">
                                <span class="placeholder col-5 mb-2"></span>
                                <span class="placeholder col-3"></span>
                            </div>
                        </div>
                    @endfor
                </div>

                <!-- TABLE -->
                <div class="table-responsive d-none" id="ebook-table">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light fs-6">
                            <tr>
                                <th width="100">Cover</th>
                                <th>Judul</th>
                                <th>Level</th>
                                <th>Topik</th>
                                <th>AI Keywords</th>
                                <th>Status</th>
                                <th width="170" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-body" class="fs-6"></tbody>
                    </table>

                    <!-- PAGINATION -->
                    <nav id="pagination" class="mt-3 px-3"></nav>
                </div>

                <!-- EMPTY STATE -->
                <div id="empty-state" class="text-center py-5 d-none">
                    <i class="bi bi-book fs-1 text-muted"></i>
                    <p class="fw-semibold mt-3 mb-1 fs-5">Belum ada ebook</p>
                    <p class="text-muted fs-6 mb-0">
                        Tambahkan ebook untuk memperkaya knowledge AI
                    </p>
                </div>

            </div>
        </div>

    </div>

    <!-- DELETE MODAL -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white rounded-top-4">
                    <h5 class="modal-title fs-4 d-flex align-items-center gap-2">
                        <i class="bi bi-trash3"></i>
                        Hapus Ebook
                    </h5>
                    <button type="button" class="btn-close btn-close-white fs-6" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body fs-6">
                    <p class="mb-1">Anda yakin ingin menghapus ebook:</p>
                    <p class="fw-bold text-danger mb-0" id="ebook-title-to-delete"></p>
                    <p class="text-muted mt-2">Aksi ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary rounded-pill fs-6" data-bs-dismiss="modal">
                        <i class="bi bi-x-lg me-1"></i>Batal
                    </button>
                    <button class="btn btn-danger rounded-pill fw-semibold fs-6" id="btn-confirm-delete">
                        <i class="bi bi-trash3 me-1"></i>Hapus
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
            document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        const tableBody = document.getElementById('table-body')
        const ebookTable = document.getElementById('ebook-table')
        const skeleton = document.getElementById('skeleton')
        const emptyState = document.getElementById('empty-state')
        const alertBox = document.getElementById('alert-box')
        const searchInput = document.getElementById('search-input')
        const searchSpinner = document.getElementById('search-spinner')
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'))
        const btnConfirmDelete = document.getElementById('btn-confirm-delete')
        const ebookTitleToDelete = document.getElementById('ebook-title-to-delete')

        let deleteId = null
        let debounceTimer = null
        let currentPage = 1
        let lastPage = 1

        function alertMsg(type, msg) {
            alertBox.className = `alert alert-${type} rounded-3 shadow-sm fs-6`
            alertBox.innerHTML = msg
            alertBox.classList.remove('d-none')
            setTimeout(() => alertBox.classList.add('d-none'), 4000)
        }

        function coverUrl(path) {
            if (!path) return 'https://placehold.co/80x80/1f2937/ffffff?text=PDF'
            return path.startsWith('http') ? path : `{{ url('') }}/${path}`
        }

        function renderEbooks(ebooks) {
            tableBody.innerHTML = ''
            skeleton.classList.add('d-none')
            ebookTable.classList.add('d-none')
            emptyState.classList.add('d-none')

            if (!ebooks || ebooks.length === 0) {
                emptyState.classList.remove('d-none')
                return
            }

            ebookTable.classList.remove('d-none')

            ebooks.forEach(e => {
                const keywords = (e.ai_keywords || []).slice(0, 5)
                    .map(k => `<span class="badge bg-info-subtle text-info rounded-pill fs-6">${k}</span>`)
                    .join('')

                const row = document.createElement('tr')
                row.dataset.id = e.id
                row.innerHTML = `
        <td>
            <img src="${coverUrl(e.cover_image)}"
                 class="rounded-3 shadow-sm"
                 width="80" height="80"
                 style="object-fit:cover">
        </td>
        <td>
            <div class="fw-semibold fs-5">${e.title}</div>
            <div class="text-muted fs-6">${e.slug}</div>
        </td>
        <td>
            <span class="badge bg-light text-dark rounded-pill px-3 fs-6">${e.level}</span>
        </td>
        <td class="text-muted fs-6">${e.topic}</td>
        <td>
            <div class="d-flex flex-wrap gap-1" style="max-width:300px">
                ${keywords || '<span class="text-muted fs-6">-</span>'}
            </div>
        </td>
        <td>
            ${e.is_active
                ? '<span class="badge bg-success rounded-pill fs-6">Active</span>'
                : '<span class="badge bg-secondary rounded-pill fs-6">Inactive</span>'}
        </td>
        <td class="text-end">
            <a href="/ebooks/${e.id}/edit"
               class="btn btn-sm btn-outline-primary rounded-pill fs-6 me-1"
               title="Edit">
                <i class="bi bi-pencil-square"></i>
            </a>
            <button class="btn btn-sm btn-outline-danger rounded-pill fs-6 btn-delete"
                    title="Hapus">
                <i class="bi bi-trash3"></i>
            </button>
        </td>
        `
                tableBody.appendChild(row)
            })
        }

        function renderPagination(meta) {
            const pagination = document.getElementById('pagination')
            pagination.innerHTML = ''
            currentPage = meta.current_page
            lastPage = meta.last_page

            if (lastPage <= 1) return

            const ul = document.createElement('ul')
            ul.className = 'pagination justify-content-center'

            // Previous
            const prevLi = document.createElement('li')
            prevLi.className = `page-item ${!meta.prev_page_url ? 'disabled' : ''}`
            prevLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage - 1}">&laquo; Previous</a>`
            ul.appendChild(prevLi)

            // Pages
            for (let i = 1; i <= lastPage; i++) {
                const li = document.createElement('li')
                li.className = `page-item ${i === currentPage ? 'active' : ''}`
                li.innerHTML = `<a class="page-link" href="#" data-page="${i}">${i}</a>`
                ul.appendChild(li)
            }

            // Next
            const nextLi = document.createElement('li')
            nextLi.className = `page-item ${!meta.next_page_url ? 'disabled' : ''}`
            nextLi.innerHTML = `<a class="page-link" href="#" data-page="${currentPage + 1}">Next &raquo;</a>`
            ul.appendChild(nextLi)

            pagination.appendChild(ul)
            pagination.querySelectorAll('a.page-link').forEach(a => {
                a.addEventListener('click', e => {
                    e.preventDefault()
                    const page = parseInt(a.dataset.page)
                    if (page >= 1 && page <= lastPage) {
                        loadEbooks(searchInput.value, page)
                    }
                })
            })
        }

        function loadEbooks(query = '', page = 1) {
            skeleton.classList.remove('d-none')
            ebookTable.classList.add('d-none')
            emptyState.classList.add('d-none')
            searchSpinner.classList.remove('d-none')

            axios.get('{{ route('ebooks.list') }}', {
                    params: {
                        q: query,
                        page
                    }
                })
                .then(res => {
                    renderEbooks(res.data.data.data)
                    renderPagination(res.data.data)
                })
                .catch(() => {
                    alertMsg('danger', 'Gagal memuat data ebook.')
                    emptyState.classList.remove('d-none')
                })
                .finally(() => {
                    skeleton.classList.add('d-none')
                    searchSpinner.classList.add('d-none')
                })
        }

        searchInput.addEventListener('input', e => {
            clearTimeout(debounceTimer)
            debounceTimer = setTimeout(() => loadEbooks(e.target.value), 500)
        })

        tableBody.addEventListener('click', e => {
            if (e.target.closest('.btn-delete')) {
                const row = e.target.closest('tr')
                deleteId = row.dataset.id
                ebookTitleToDelete.textContent = row.querySelector('.fw-semibold').textContent
                deleteModal.show()
            }
        })

        btnConfirmDelete.addEventListener('click', () => {
            if (!deleteId) return

            btnConfirmDelete.disabled = true
            btnConfirmDelete.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghapus...'

            axios.delete(`/ebooks/${deleteId}`)
                .then(res => {
                    alertMsg('success', res.data.message || 'Ebook berhasil dihapus.')
                    loadEbooks(searchInput.value, currentPage)
                })
                .catch(() => alertMsg('danger', 'Gagal menghapus ebook.'))
                .finally(() => {
                    deleteModal.hide()
                    btnConfirmDelete.disabled = false
                    btnConfirmDelete.innerHTML = '<i class="bi bi-trash3 me-1"></i>Hapus'
                    deleteId = null
                })
        })

        window.onload = () => loadEbooks()
    </script>
@endpush
