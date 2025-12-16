@extends('template.app')

@section('title', 'AI Prompt Templates')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">AI Prompt Templates</h4>
        </div>

        <div id="alert-box" class="alert d-none"></div>

        <div class="row g-4">

            {{-- LEFT FORM --}}
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <strong id="form-title">Tambah Prompt</strong>
                    </div>
                    <div class="card-body">
                        <form id="form-prompt">
                            @csrf
                            <input type="hidden" id="prompt_id">

                            <div class="mb-3">
                                <label class="form-label">Type</label>
                                <select id="type" class="form-select">
                                    <option value="system">System</option>
                                    <option value="context">Context</option>
                                    <option value="fallback">Fallback</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Prompt Content</label>
                                <textarea id="content" class="form-control" rows="8" placeholder="Tulis prompt AI..." required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Status</label>
                                <select id="is_active" class="form-select">
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary" id="btn-submit">Simpan</button>
                                <button type="button" class="btn btn-light d-none" id="btn-cancel">Batal</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- RIGHT TABLE --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <strong>Daftar Prompt</strong>
                    </div>
                    <div class="card-body p-0">

                        {{-- Skeleton --}}
                        <div id="skeleton">
                            @for ($i = 0; $i < 5; $i++)
                                <div class="d-flex gap-3 px-4 py-3 border-bottom">
                                    <div class="skeleton w-15"></div>
                                    <div class="skeleton flex-fill"></div>
                                    <div class="skeleton w-10"></div>
                                    <div class="skeleton w-15"></div>
                                </div>
                            @endfor
                        </div>

                        <table class="table table-hover mb-0 align-middle d-none" id="table">
                            <thead class="table-light">
                                <tr>
                                    <th width="100">Type</th>
                                    <th>Prompt</th>
                                    <th width="100">Active</th>
                                    <th width="160" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody"></tbody>
                        </table>

                        <div id="empty" class="text-center py-5 text-muted d-none">
                            Belum ada prompt
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
        const alertBox = document.getElementById('alert-box')
        const tbody = document.getElementById('tbody')
        const table = document.getElementById('table')
        const skeleton = document.getElementById('skeleton')
        const empty = document.getElementById('empty')

        let action = null
        let targetId = null
        let toggleValue = null
        const modal = new bootstrap.Modal(document.getElementById('confirmModal'))

        function showAlert(type, msg) {
            alertBox.className = `alert alert-${type}`
            alertBox.textContent = msg
            alertBox.classList.remove('d-none')
            setTimeout(() => alertBox.classList.add('d-none'), 4000)
        }

        function resetForm() {
            document.getElementById('form-title').textContent = 'Tambah Prompt'
            document.getElementById('prompt_id').value = ''
            document.getElementById('type').disabled = false
            document.getElementById('form-prompt').reset()
            document.getElementById('btn-cancel').classList.add('d-none')
        }

        function loadPrompts() {
            skeleton.classList.remove('d-none')
            table.classList.add('d-none')
            empty.classList.add('d-none')

            axios.get('/ai/prompts/list').then(res => {
                const rows = res.data.data.data
                tbody.innerHTML = ''
                skeleton.classList.add('d-none')

                if (!rows.length) {
                    empty.classList.remove('d-none')
                    return
                }

                table.classList.remove('d-none')

                rows.forEach(p => {
                    const tr = document.createElement('tr')
                    tr.dataset.prompt = JSON.stringify(p)
                    tr.dataset.id = p.id
                    tr.innerHTML = `
                <td><span class="badge bg-secondary">${p.type.toUpperCase()}</span></td>
                <td class="text-muted">${p.content.substring(0,120)}...</td>
                <td class="text-center">
                    <div class="form-check form-switch d-inline-block">
                        <input class="form-check-input toggle" type="checkbox"
                            ${p.is_active ? 'checked' : ''}>
                    </div>
                </td>
                <td class="text-end">
                    <button class="btn btn-sm btn-outline-primary btn-edit">Edit</button>
                    <button class="btn btn-sm btn-outline-danger btn-delete ms-1">Hapus</button>
                </td>
            `
                    tbody.appendChild(tr)
                })
            })
        }

        tbody.addEventListener('click', e => {
            const tr = e.target.closest('tr')
            if (!tr) return

            const data = JSON.parse(tr.dataset.prompt)
            targetId = tr.dataset.id

            if (e.target.classList.contains('btn-edit')) {
                document.getElementById('form-title').textContent = 'Edit Prompt'
                document.getElementById('prompt_id').value = data.id
                document.getElementById('type').value = data.type
                document.getElementById('type').disabled = true
                document.getElementById('content').value = data.content
                document.getElementById('is_active').value = data.is_active ? '1' : '0'
                document.getElementById('btn-cancel').classList.remove('d-none')
            }

            if (e.target.classList.contains('toggle')) {
                action = 'toggle'
                toggleValue = e.target.checked ? 1 : 0
                document.getElementById('modalTitle').textContent = 'Ubah Status'
                document.getElementById('modalBody').textContent = 'Ubah status prompt ini?'
                modal.show()
            }

            if (e.target.classList.contains('btn-delete')) {
                action = 'delete'
                document.getElementById('modalTitle').textContent = 'Hapus Prompt'
                document.getElementById('modalBody').textContent = 'Prompt ini akan dihapus permanen.'
                modal.show()
            }
        })

        document.getElementById('modalConfirm').onclick = () => {
            if (action === 'toggle') {
                axios.patch(`/ai/prompts/${targetId}/toggle`, {
                    is_active: toggleValue
                }).then(() => {
                    showAlert('success', 'Status berhasil diubah')
                    loadPrompts()
                })
            }

            if (action === 'delete') {
                axios.delete(`/ai/prompts/${targetId}`)
                    .then(() => {
                        showAlert('success', 'Prompt berhasil dihapus')
                        loadPrompts()
                    })
            }

            modal.hide()
        }

        document.getElementById('form-prompt').addEventListener('submit', e => {
            e.preventDefault()

            const id = document.getElementById('prompt_id').value
            const payload = {
                content: document.getElementById('content').value,
                is_active: document.getElementById('is_active').value
            }

            if (id) {
                axios.patch(`/ai/prompts/${id}`, payload)
                    .then(() => {
                        showAlert('success', 'Prompt diperbarui')
                        resetForm()
                        loadPrompts()
                    })
            } else {
                payload.type = document.getElementById('type').value
                axios.post('/ai/prompts', payload)
                    .then(() => {
                        showAlert('success', 'Prompt ditambahkan')
                        resetForm()
                        loadPrompts()
                    })
            }
        })

        document.getElementById('btn-cancel').onclick = resetForm

        loadPrompts()
    </script>
@endpush
