@extends('template.app')

@section('title', 'AI Chat Sessions')

@section('content')
    <div class="container-fluid py-4">

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body px-4 py-4 d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                <div>
                    <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
                        <i class="bi bi-chat-dots text-primary fs-3"></i>
                        AI Chat Sessions
                    </h3>
                    <p class="text-muted mb-0 fs-6">
                        Riwayat percakapan AI & audit response
                    </p>
                </div>
            </div>
        </div>

        <div id="alert-box" class="alert d-none rounded-3 shadow-sm fs-6"></div>

        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="card-header bg-white border-bottom px-4 py-3">
                <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
                    <h5 class="fw-bold mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-list-ul text-secondary fs-5"></i>
                        Daftar Session
                    </h5>

                    <div class="input-group shadow-sm" style="max-width: 380px;">
                        <span class="input-group-text bg-light border-0 fs-5">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input id="search-input" type="text" class="form-control border-0 fs-6"
                            placeholder="Cari session...">
                        <span class="input-group-text d-none bg-white" id="search-spinner">
                            <span class="spinner-border spinner-border-sm"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body p-0">

                <div id="skeleton">
                    @for ($i = 0; $i < 5; $i++)
                        <div class="px-4 py-4 border-bottom placeholder-glow">
                            <span class="placeholder col-4 mb-2"></span>
                            <span class="placeholder col-2"></span>
                        </div>
                    @endfor
                </div>

                <div class="table-responsive d-none" id="session-table">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light fs-6">
                            <tr>
                                <th>Title</th>
                                <th>Model</th>
                                <th>Last Activity</th>
                                <th width="160" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="table-body" class="fs-6"></tbody>
                    </table>
                </div>

                <div id="empty-state" class="text-center py-5 d-none">
                    <i class="bi bi-chat fs-1 text-muted"></i>
                    <p class="fw-semibold mt-3 mb-1 fs-5">Belum ada session</p>
                    <p class="text-muted fs-6 mb-0">Session AI akan muncul di sini</p>
                </div>

            </div>
        </div>

    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Hapus Session</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Hapus session:</p>
                    <p class="fw-bold text-danger mb-0" id="session-title-delete"></p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-outline-secondary rounded-pill" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger rounded-pill" id="btn-confirm-delete">Hapus</button>
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
        const table = document.getElementById('session-table')
        const skeleton = document.getElementById('skeleton')
        const emptyState = document.getElementById('empty-state')
        const searchInput = document.getElementById('search-input')
        const searchSpinner = document.getElementById('search-spinner')
        const alertBox = document.getElementById('alert-box')

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'))
        const btnConfirmDelete = document.getElementById('btn-confirm-delete')
        const sessionTitleDelete = document.getElementById('session-title-delete')

        let deleteId = null
        let debounceTimer = null

        function alertMsg(type, msg) {
            alertBox.className = `alert alert-${type} rounded-3 shadow-sm`
            alertBox.innerHTML = msg
            alertBox.classList.remove('d-none')
            setTimeout(() => alertBox.classList.add('d-none'), 4000)
        }

        function renderSessions(sessions) {
            tableBody.innerHTML = ''
            skeleton.classList.add('d-none')
            table.classList.add('d-none')
            emptyState.classList.add('d-none')

            if (!sessions || sessions.length === 0) {
                emptyState.classList.remove('d-none')
                return
            }

            table.classList.remove('d-none')

            sessions.forEach(s => {
                const row = document.createElement('tr')
                row.dataset.id = s.id
                row.innerHTML = `
            <td>
                <div class="fw-semibold">${s.title ?? 'Untitled Session'}</div>
                <div class="text-muted fs-6">${s.session_token}</div>
            </td>
            <td>
                <span class="badge bg-light text-dark rounded-pill">${s.model}</span>
            </td>
            <td class="text-muted">
                ${s.last_activity_at ?? '-'}
            </td>
            <td class="text-end">
                <a href="/ai-chat/sessions/${s.id}"
                   class="btn btn-sm btn-outline-primary rounded-pill me-1">
                    <i class="bi bi-eye"></i>
                </a>
                <button class="btn btn-sm btn-outline-danger rounded-pill btn-delete">
                    <i class="bi bi-trash3"></i>
                </button>
            </td>
        `
                tableBody.appendChild(row)
            })
        }

        function loadSessions(query = '') {
            skeleton.classList.remove('d-none')
            table.classList.add('d-none')
            emptyState.classList.add('d-none')
            searchSpinner.classList.remove('d-none')

            axios.get('/ai-chat/list', {
                    params: {
                        q: query
                    }
                })
                .then(res => renderSessions(res.data.data))
                .catch(() => {
                    alertMsg('danger', 'Gagal memuat session')
                    emptyState.classList.remove('d-none')
                })
                .finally(() => {
                    skeleton.classList.add('d-none')
                    searchSpinner.classList.add('d-none')
                })
        }

        searchInput.addEventListener('input', e => {
            clearTimeout(debounceTimer)
            debounceTimer = setTimeout(() => loadSessions(e.target.value), 500)
        })

        tableBody.addEventListener('click', e => {
            if (e.target.closest('.btn-delete')) {
                const row = e.target.closest('tr')
                deleteId = row.dataset.id
                sessionTitleDelete.textContent = row.querySelector('.fw-semibold').textContent
                deleteModal.show()
            }
        })

        btnConfirmDelete.addEventListener('click', () => {
            if (!deleteId) return

            btnConfirmDelete.disabled = true

            axios.delete(`/ai-chat/sessions/${deleteId}`)
                .then(res => {
                    alertMsg('success', res.data.message)
                    loadSessions(searchInput.value)
                })
                .catch(() => alertMsg('danger', 'Gagal menghapus session'))
                .finally(() => {
                    deleteModal.hide()
                    btnConfirmDelete.disabled = false
                    deleteId = null
                })
        })

        window.onload = () => loadSessions()
    </script>
@endpush
