@extends('template.app')

@section('title', 'Articles')

@section('content')
    <div class="container-fluid mt-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="fw-bold text-primary">Article Management</h4>
            <a href="{{ route('articles.create') }}" class="btn btn-primary shadow-lg border-0">
                <i class="bi bi-plus-lg me-1"></i> Tambah Article
            </a>
        </div>

        <div id="alert-box" class="alert d-none rounded-3 shadow-sm"></div>

        <div class="card shadow-lg border-0">
            <div
                class="card-header bg-white p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <h6 class="mb-2 mb-md-0 fw-bold text-muted">Daftar Artikel</h6>
                <div class="input-group" style="max-width: 400px; width: 100%;">
                    <span class="input-group-text rounded-start-3 bg-light text-muted">
                        <i class="bi bi-search"></i>
                    </span>
                    <input id="search-input" type="text" class="form-control rounded-end-3"
                        placeholder="Cari berdasarkan judul...">
                    <span class="input-group-text d-none bg-white" id="search-spinner">
                        <span class="spinner-border spinner-border-sm"></span>
                    </span>
                </div>
            </div>

            <div class="card-body p-0">
                <div id="skeleton">
                    @for ($i = 0; $i < 5; $i++)
                        <div class="p-3 border-bottom placeholder-glow d-flex align-items-center">
                            <span class="placeholder rounded me-3" style="width: 60px; height: 60px;"></span>
                            <div class="flex-grow-1">
                                <span class="placeholder col-6 mb-1"></span>
                                <span class="placeholder col-4"></span>
                            </div>
                        </div>
                    @endfor
                </div>

                <table class="table table-striped table-hover mb-0 d-none" id="article-table">
                    <thead class="table-light">
                        <tr>
                            <th width="80">Thumb</th>
                            <th>Title / Slug</th>
                            <th>Categories</th>
                            <th>Tags</th>
                            <th>Status</th>
                            <th>Published At</th>
                            <th width="150" class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">

                    </tbody>
                </table>

                <div id="empty-state" class="text-center py-5 text-muted d-none">
                    <p class="mb-1"><i class="bi bi-x-octagon fs-3"></i></p>
                    <p class="mb-0">Data artikel tidak ditemukan.</p>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0">
                <div class="modal-header bg-danger text-white rounded-top-4 p-3">
                    <h5 class="modal-title fs-5">Konfirmasi Hapus Artikel</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body p-4">
                    <p>Apakah Anda yakin ingin menghapus artikel "<span id="article-title-to-delete"
                            class="fw-bold text-danger"></span>"?</p>
                    <p class="text-muted small mb-0">Aksi ini tidak dapat dibatalkan.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary rounded-pill"
                        data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger rounded-pill shadow-sm" id="btn-confirm-delete">
                        <i class="bi bi-trash me-1"></i> Hapus Permanen
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
            document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]')
            .getAttribute('content') : ''

        const tableBody = document.getElementById('table-body')
        const articleTable = document.getElementById('article-table')
        const skeleton = document.getElementById('skeleton')
        const emptyState = document.getElementById('empty-state')
        const alertBox = document.getElementById('alert-box')
        const searchInput = document.getElementById('search-input')
        const searchSpinner = document.getElementById('search-spinner')
        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'))
        const btnConfirmDelete = document.getElementById('btn-confirm-delete')
        const articleTitleToDelete = document.getElementById('article-title-to-delete')

        let deleteArticleId = null
        let debounceTimer = null

        function alertMsg(type, msg) {
            alertBox.className = `alert alert-${type} rounded-3 shadow-sm`
            alertBox.innerHTML = msg
            alertBox.classList.remove('d-none')
            setTimeout(() => alertBox.classList.add('d-none'), 4000)
        }

        function formatDateTime(dateString) {
            if (!dateString) return '-';
            const options = {
                day: '2-digit',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            return new Date(dateString).toLocaleDateString('id-ID', options).replace(/\./g, '');
        }

        function getThumbnailUrl(path) {
            return path ? `${path}` : `https://placehold.co/60x60/343a40/ffffff?text=IMG`;
        }
        function renderArticles(articles) {
            tableBody.innerHTML = ''

            skeleton.classList.add('d-none')
            articleTable.classList.add('d-none')
            emptyState.classList.add('d-none')

            if (!articles || articles.length === 0) {
                emptyState.classList.remove('d-none')
                return
            }

            articleTable.classList.remove('d-none')

            articles.forEach(article => {
                const isPublished = article.is_published === 1
                const statusClass = isPublished ? 'bg-success' : 'bg-secondary'
                const statusText = isPublished ? 'Published' : 'Draft'

                const categoriesHtml = article.categories.map(cat =>
                    `<span class="badge rounded-pill bg-light text-primary fw-normal border me-1">${cat.name}</span>`
                ).join('')

                const tagsHtml = article.tags.map(tag =>
                    `<span class="badge rounded-pill bg-info-subtle text-info fw-normal me-1">${tag.name}</span>`
                ).join('')

                const row = document.createElement('tr')
                row.dataset.id = article.id
                row.innerHTML = `
                    <td class="py-3 align-middle">
                        <img src="${getThumbnailUrl(article.thumbnail)}" class="rounded shadow-sm" width="60" height="60" style="object-fit: cover;">
                    </td>
                    <td class="align-middle">
                        <strong class="text-dark">${article.title}</strong>
                        <div class="text-muted small">${article.slug}</div>
                    </td>
                    <td class="align-middle">${categoriesHtml || '<span class="text-muted small">-</span>'}</td>
                    <td class="align-middle">${tagsHtml || '<span class="text-muted small">-</span>'}</td>
                    <td class="align-middle">
                        <span class="badge ${statusClass} rounded-pill">${statusText}</span>
                    </td>
                    <td class="align-middle text-muted small">${formatDateTime(article.published_at)}</td>
                    <td class="text-end align-middle">
                        <a href="/articles/${article.id}/edit" class="btn btn-sm btn-outline-primary me-1 rounded-pill">
                            <i class="bi bi-pencil-square"></i> Edit
                        </a>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete rounded-pill">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </td>
                `
                tableBody.appendChild(row)
            })
        }

        function loadArticles(query = '') {
            skeleton.classList.remove('d-none')
            articleTable.classList.add('d-none')
            emptyState.classList.add('d-none')
            searchSpinner.classList.remove('d-none')

            axios.get('{{ route('articles.list') }}', {
                    params: {
                        q: query
                    }
                })
                .then(response => {
                    renderArticles(response.data.data)
                })
                .catch(error => {
                    console.error("Error loading articles:", error)
                    alertMsg('danger', 'Gagal memuat data artikel.')
                    emptyState.classList.remove('d-none')
                })
                .finally(() => {
                    skeleton.classList.add('d-none')
                    searchSpinner.classList.add('d-none')
                })
        }

        searchInput.addEventListener('input', e => {
            clearTimeout(debounceTimer)
            debounceTimer = setTimeout(() => {
                loadArticles(e.target.value)
            }, 500)
        })

        tableBody.addEventListener('click', (e) => {
            if (e.target.closest('.btn-delete')) {
                const row = e.target.closest('tr')
                deleteArticleId = row.dataset.id

                const titleElement = row.querySelector('strong')
                articleTitleToDelete.textContent = titleElement ? titleElement.textContent : 'Artikel ini'

                deleteModal.show()
            }
        })

        btnConfirmDelete.addEventListener('click', () => {
            if (!deleteArticleId) return

            btnConfirmDelete.disabled = true
            const originalText = btnConfirmDelete.textContent
            btnConfirmDelete.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Menghapus...'

            axios.delete(`/articles/${deleteArticleId}`)
                .then(response => {
                    alertMsg('success', response.data.message || 'Artikel berhasil dihapus.')
                    loadArticles(searchInput.value)
                })
                .catch(error => {
                    alertMsg('danger', 'Gagal menghapus artikel.')
                })
                .finally(() => {
                    deleteModal.hide()
                    btnConfirmDelete.disabled = false
                    btnConfirmDelete.textContent = originalText
                    btnConfirmDelete.innerHTML = '<i class="bi bi-trash me-1"></i> Hapus Permanen'
                    deleteArticleId = null
                })
        })
        window.onload = () => {
            loadArticles()
        }
    </script>
@endpush
