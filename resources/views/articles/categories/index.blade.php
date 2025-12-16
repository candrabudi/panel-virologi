@extends('template.app')

@section('title', 'Article Categories')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-4">
            <h4>Article Categories</h4>
        </div>

        <div id="alert-box" class="alert d-none"></div>

        <div class="row g-4">

            {{-- FORM --}}
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold" id="form-title">Tambah Kategori</div>
                    <div class="card-body">
                        <form id="form-category">

                            @csrf
                            <input type="hidden" id="category_id">

                            <div class="mb-3">
                                <label class="form-label">Nama Kategori</label>
                                <input id="name" class="form-control" placeholder="Contoh: Cyber Security">
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary" id="btn-submit">
                                    <span class="btn-text">Simpan</span>
                                    <span class="spinner-border spinner-border-sm d-none"></span>
                                </button>

                                <button type="button" class="btn btn-light d-none" id="btn-cancel">
                                    Batal
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            {{-- TABLE --}}
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                        <span>Daftar Kategori</span>
                        <input id="search" class="form-control form-control-sm w-50" placeholder="Cari kategori...">
                    </div>

                    <div class="card-body p-0">

                        {{-- SKELETON --}}
                        <div id="skeleton">
                            @for ($i = 0; $i < 5; $i++)
                                <div class="px-4 py-3 border-bottom placeholder-glow">
                                    <span class="placeholder col-4"></span>
                                    <span class="placeholder col-3 ms-2"></span>
                                </div>
                            @endfor
                        </div>

                        <table class="table table-hover mb-0 d-none" id="table">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama</th>
                                    <th>Slug</th>
                                    <th width="140" class="text-end">Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="tbody"></tbody>
                        </table>

                        <div id="empty" class="text-center py-5 text-muted d-none">
                            Belum ada kategori
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- MODAL DELETE --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Hapus Kategori</h5>
                </div>
                <div class="modal-body">
                    Yakin ingin menghapus kategori ini?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-danger" id="btn-confirm-delete">Hapus</button>
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

        const tbody = document.getElementById('tbody')
        const table = document.getElementById('table')
        const skeleton = document.getElementById('skeleton')
        const empty = document.getElementById('empty')
        const alertBox = document.getElementById('alert-box')

        const idEl = document.getElementById('category_id')
        const nameEl = document.getElementById('name')
        const formTitle = document.getElementById('form-title')
        const btnSubmit = document.getElementById('btn-submit')
        const btnCancel = document.getElementById('btn-cancel')
        const spinner = btnSubmit.querySelector('.spinner-border')
        const btnText = btnSubmit.querySelector('.btn-text')

        const modal = new bootstrap.Modal(document.getElementById('deleteModal'))
        let deleteId = null
        let debounceTimer = null

        function alertMsg(type, msg) {
            alertBox.className = `alert alert-${type}`
            alertBox.innerHTML = msg
            alertBox.classList.remove('d-none')
            setTimeout(() => alertBox.classList.add('d-none'), 4000)
        }

        function resetForm() {
            idEl.value = ''
            nameEl.value = ''
            formTitle.textContent = 'Tambah Kategori'
            btnText.textContent = 'Simpan'
            btnCancel.classList.add('d-none')
        }

        function loadCategories(q = '') {
            skeleton.classList.remove('d-none')
            table.classList.add('d-none')
            empty.classList.add('d-none')

            axios.get('/articles/categories/list', {
                    params: {
                        q
                    }
                })
                .then(res => {
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
                        tr.dataset.item = JSON.stringify(r)
                        tr.innerHTML = `
                    <td>${r.name}</td>
                    <td class="text-muted">${r.slug}</td>
                    <td class="text-end">
                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit">Edit</button>
                        <button type="button" class="btn btn-sm btn-outline-danger btn-delete ms-1">Hapus</button>
                    </td>
                `
                        tbody.appendChild(tr)
                    })
                })
        }

        document.getElementById('form-category').addEventListener('submit', (e) => {
            e.preventDefault();

            btnSubmit.disabled = true
            spinner.classList.remove('d-none')
            btnText.classList.add('d-none')

            const payload = {
                name: nameEl.value
            }

            const req = idEl.value ?
                axios.put(`/articles/categories/${idEl.value}`, payload) :
                axios.post('/articles/categories', payload)

            req.then(() => {
                alertMsg('success', 'Kategori berhasil disimpan')
                resetForm()
                loadCategories()
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

        tbody.addEventListener('click', e => {
            const tr = e.target.closest('tr')
            if (!tr) return
            const data = JSON.parse(tr.dataset.item)

            if (e.target.classList.contains('btn-edit')) {
                idEl.value = data.id
                nameEl.value = data.name
                formTitle.textContent = 'Edit Kategori'
                btnText.textContent = 'Update'
                btnCancel.classList.remove('d-none')
            }

            if (e.target.classList.contains('btn-delete')) {
                deleteId = data.id
                modal.show()
            }
        })

        document.getElementById('btn-confirm-delete').onclick = () => {
            axios.delete(`/articles/categories/${deleteId}`)
                .then(() => {
                    alertMsg('success', 'Kategori dihapus')
                    loadCategories()
                })
            modal.hide()
        }

        btnCancel.onclick = resetForm

        document.getElementById('search').addEventListener('input', e => {
            clearTimeout(debounceTimer)
            debounceTimer = setTimeout(() => {
                loadCategories(e.target.value)
            }, 400)
        })

        loadCategories()
    </script>
@endpush
