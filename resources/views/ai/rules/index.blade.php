@extends('layouts.app')

@section('title', 'AI Rules')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">AI Rules</h4>
        </div>

        <div id="alert-box" class="alert d-none"></div>

        <div class="row g-4">

            {{-- LEFT FORM --}}
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <strong>Tambah Rule</strong>
                    </div>
                    <div class="card-body">
                        <form id="form-add">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Rule Type</label>
                                <select name="type" class="form-select" required>
                                    <option value="allow_keyword">Allow Keyword</option>
                                    <option value="block_topic">Block Topic</option>
                                    <option value="regex">Regex</option>
                                    <option value="source_policy">Source Policy</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Context</label>
                                <select name="ai_context_id" class="form-select">
                                    <option value="">GLOBAL</option>
                                    @foreach ($contexts as $ctx)
                                        <option value="{{ $ctx->id }}">{{ $ctx->code }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Rule Value</label>
                                <textarea name="value" class="form-control" rows="4" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Note</label>
                                <input name="note" class="form-control">
                            </div>

                            <div class="d-grid">
                                <button class="btn btn-primary">Tambah Rule</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- RIGHT TABLE --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">

                        <div class="row g-2 align-items-center">
                            <div class="col-md-3">
                                <select id="filter-type" class="form-select form-select">
                                    <option value="">All Type</option>
                                    <option value="allow_keyword">Allow</option>
                                    <option value="block_topic">Block</option>
                                    <option value="regex">Regex</option>
                                    <option value="source_policy">Policy</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select id="filter-context" class="form-select form-select">
                                    <option value="">All Context</option>
                                    @foreach ($contexts as $ctx)
                                        <option value="{{ $ctx->id }}">{{ $ctx->code }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-3">
                                <select id="filter-active" class="form-select form-select">
                                    <option value="">All Status</option>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <input id="search" class="form-control form-control"
                                    placeholder="Search rule value...">
                            </div>
                        </div>

                    </div>

                    <div class="card-body p-0">

                        {{-- Skeleton --}}
                        <div id="skeleton">
                            @for ($i = 0; $i < 5; $i++)
                                <div class="d-flex gap-3 px-4 py-3 border-bottom">
                                    <div class="skeleton w-20"></div>
                                    <div class="skeleton w-20"></div>
                                    <div class="skeleton flex-fill"></div>
                                    <div class="skeleton w-10"></div>
                                    <div class="skeleton w-15"></div>
                                </div>
                            @endfor
                        </div>

                        <table class="table table-hover mb-0 align-middle d-none" id="table">
                            <thead class="table-light">
                                <tr>
                                    <th width="140">Type</th>
                                    <th width="120">Context</th>
                                    <th>Value</th>
                                    <th width="90">Active</th>
                                    <th width="140" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody"></tbody>
                        </table>

                        <div id="empty" class="text-center py-5 text-muted d-none">
                            Tidak ada rule
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- CONFIRM MODAL --}}
    <div class="modal fade" id="confirmModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle"></h5>
                </div>
                <div class="modal-body" id="modalBody"></div>
                <div class="modal-footer">
                    <button class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-danger" id="modalConfirm">Ya</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .skeleton {
            height: 14px;
            border-radius: 6px;
            background: linear-gradient(90deg, #eee, #f5f5f5, #eee);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite;
        }

        .w-10 {
            width: 10%
        }

        .w-15 {
            width: 15%
        }

        .w-20 {
            width: 20%
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0
            }

            100% {
                background-position: -200% 0
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const tbody = document.getElementById('tbody')
        const table = document.getElementById('table')
        const skeleton = document.getElementById('skeleton')
        const empty = document.getElementById('empty')
        const alertBox = document.getElementById('alert-box')

        const modal = new bootstrap.Modal(document.getElementById('confirmModal'))

        let action = null
        let targetId = null
        let toggleValue = null
        let debounceTimer = null

        function alertMsg(type, msg) {
            alertBox.className = `alert alert-${type}`
            alertBox.textContent = msg
            alertBox.classList.remove('d-none')
            setTimeout(() => alertBox.classList.add('d-none'), 4000)
        }

        function loadRules() {
            skeleton.classList.remove('d-none')
            table.classList.add('d-none')
            empty.classList.add('d-none')

            axios.get('/ai/rules/list', {
                params: {
                    type: document.getElementById('filter-type').value,
                    ai_context_id: document.getElementById('filter-context').value,
                    is_active: document.getElementById('filter-active').value,
                    q: document.getElementById('search').value,
                }
            }).then(res => {
                const rows = res.data.data
                tbody.innerHTML = ''
                skeleton.classList.add('d-none')

                if (!rows.length) {
                    empty.classList.remove('d-none')
                    return
                }

                table.classList.remove('d-none')

                rows.forEach(r => {
                    const tr = document.createElement('tr')
                    tr.dataset.id = r.id
                    tr.innerHTML = `
                <td>${r.type.replaceAll('_',' ').toUpperCase()}</td>
                <td>${r.context_code ?? 'GLOBAL'}</td>
                <td class="text-muted">${r.value}</td>
                <td class="text-center">
                    <input type="checkbox" class="form-check-input toggle" ${r.is_active ? 'checked' : ''}>
                </td>
                <td class="text-end">
                    <button class="btn btn-sm btn-danger btn-delete">Hapus</button>
                </td>
            `
                    tbody.appendChild(tr)
                })
            })
        }

        function debounceLoad() {
            clearTimeout(debounceTimer)
            debounceTimer = setTimeout(loadRules, 300)
        }

        ['filter-type', 'filter-context', 'filter-active', 'search'].forEach(id => {
            document.getElementById(id).addEventListener('input', debounceLoad)
        })

        tbody.addEventListener('click', e => {
            const tr = e.target.closest('tr')
            if (!tr) return
            targetId = tr.dataset.id

            if (e.target.classList.contains('toggle')) {
                action = 'toggle'
                toggleValue = e.target.checked ? 1 : 0
                document.getElementById('modalTitle').textContent = 'Ubah Status'
                document.getElementById('modalBody').textContent = 'Ubah status rule ini?'
                modal.show()
            }

            if (e.target.classList.contains('btn-delete')) {
                action = 'delete'
                document.getElementById('modalTitle').textContent = 'Hapus Rule'
                document.getElementById('modalBody').textContent = 'Rule ini akan dihapus permanen.'
                modal.show()
            }
        })

        document.getElementById('modalConfirm').onclick = () => {
            if (action === 'toggle') {
                axios.patch(`/ai/rules/${targetId}`, {
                        is_active: toggleValue
                    })
                    .then(() => {
                        alertMsg('success', 'Status diperbarui')
                        loadRules()
                    })
            }

            if (action === 'delete') {
                axios.delete(`/ai/rules/${targetId}`)
                    .then(() => {
                        alertMsg('success', 'Rule dihapus')
                        loadRules()
                    })
            }

            modal.hide()
        }

        document.getElementById('form-add').addEventListener('submit', e => {
            e.preventDefault()
            axios.post('/ai/rules', new FormData(e.target))
                .then(() => {
                    alertMsg('success', 'Rule ditambahkan')
                    e.target.reset()
                    loadRules()
                })
                .catch(err => {
                    alertMsg('danger', 'Gagal menambahkan rule')
                })
        })

        loadRules()
    </script>
@endpush
