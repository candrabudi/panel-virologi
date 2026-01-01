@extends('template.app')

@section('title', 'User Management')

@section('content')
    <div class="container-fluid mt-3">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>User Management</h4>
            <button class="btn btn-primary" onclick="openCreate()">Tambah User</button>
        </div>

        <div class="card mb-3">
            <div class="card-body d-flex gap-2">
                <input id="q" class="form-control" placeholder="Cari username / email">
                <select id="role" class="form-select">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="editor">Editor</option>
                    <option value="user">User</option>
                </select>
                <select id="status" class="form-select">
                    <option value="">Semua Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="blocked">Blocked</option>
                </select>
                <button class="btn btn-secondary" onclick="loadData()">Filter</button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">

                <div id="loading" class="text-center py-5">
                    <div class="spinner-border"></div>
                </div>

                <table class="table d-none" id="table">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Nama</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Last Login</th>
                            <th width="150">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody"></tbody>
                </table>

            </div>
        </div>

    </div>

    <div class="modal fade" id="modal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="form">
                    @csrf
                    <input type="hidden" id="id">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body row g-3">
                        <div class="col-md-6">
                            <input class="form-control" id="username" placeholder="Username">
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" id="email" placeholder="Email">
                        </div>
                        <div class="col-md-6">
                            <input type="password" class="form-control" id="password" placeholder="Password">
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" id="full_name" placeholder="Nama Lengkap">
                        </div>
                        <div class="col-md-6">
                            <input class="form-control" id="phone_number" placeholder="No HP">
                        </div>
                        <div class="col-md-3">
                            <select id="roleInput" class="form-select">
                                <option value="admin">Admin</option>
                                <option value="editor">Editor</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select id="statusInput" class="form-select">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="blocked">Blocked</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary" type="submit">Simpan</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        let modal = new bootstrap.Modal(document.getElementById('modal'))

        function loadData() {
            document.getElementById('loading').classList.remove('d-none')
            document.getElementById('table').classList.add('d-none')

            axios.get('/users/list', {
                params: {
                    q: q.value,
                    role: role.value,
                    status: status.value
                }
            }).then(res => {
                tbody.innerHTML = ''
                res.data.data.forEach(u => {
                    tbody.innerHTML += `
                <tr>
                    <td>${u.username}</td>
                    <td>${u.email}</td>
                    <td>${u.full_name ?? '-'}</td>
                    <td>${u.role}</td>
                    <td>${u.status}</td>
                    <td>${u.last_login_at ?? '-'}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick='openEdit(${JSON.stringify(u)})'>Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="remove(${u.id})">Hapus</button>
                    </td>
                </tr>
            `
                })

                document.getElementById('loading').classList.add('d-none')
                document.getElementById('table').classList.remove('d-none')
            })
        }

        function openCreate() {
            form.reset()
            id.value = ''
            password.required = true
            modalTitle.innerText = 'Tambah User'
            modal.show()
        }

        function openEdit(u) {
            id.value = u.id
            username.value = u.username
            email.value = u.email
            full_name.value = u.full_name ?? ''
            phone_number.value = u.phone_number ?? ''
            roleInput.value = u.role
            statusInput.value = u.status
            password.value = ''
            password.required = false
            modalTitle.innerText = 'Edit User'
            modal.show()
        }

        form.onsubmit = e => {
            e.preventDefault()

            let payload = {
                username: username.value,
                email: email.value,
                password: password.value,
                full_name: full_name.value,
                phone_number: phone_number.value,
                role: roleInput.value,
                status: statusInput.value
            }

            let req = id.value ?
                axios.put('/users/' + id.value, payload) :
                axios.post('/users', payload)

            req.then(() => {
                modal.hide()
                loadData()
                Swal.fire('Berhasil', 'Data tersimpan', 'success')
            }).catch(err => {
                Swal.fire('Error', err.response.data.message ?? 'Gagal', 'error')
            })
        }

        function remove(id) {
            Swal.fire({
                title: 'Hapus user?',
                icon: 'warning',
                showCancelButton: true
            }).then(r => {
                if (r.isConfirmed) {
                    axios.delete('/users/' + id).then(() => {
                        loadData()
                        Swal.fire('Terhapus', 'User dihapus', 'success')
                    })
                }
            })
        }

        loadData()
    </script>
@endpush
