@extends('template.app')

@section('title', 'Cyber Security Services')

@section('content')
    <div class="container-fluid">

        <div class="page-title-head d-flex justify-content-between align-items-center mb-4">
            <h4 class="page-main-title m-0">Cyber Security Services</h4>
            <a href="/cyber-security-services/create" class="btn btn-primary">
                <i class="ri ri-add-line"></i> Tambah Service
            </a>
        </div>

        <div class="card">
            <div class="card-body">

                <div id="loading" class="text-center py-5">
                    <div class="spinner-border text-primary"></div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle d-none" id="table">
                        <thead class="table-light">
                            <tr>
                                <th>Service</th>
                                <th>Kategori</th>
                                <th>AI</th>
                                <th>CTA</th>
                                <th width="130">Tanggal</th>
                                <th width="140" class="text-end">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tbody"></tbody>
                    </table>
                </div>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        })

        const tbody = document.getElementById('tbody')
        const table = document.getElementById('table')
        const loading = document.getElementById('loading')

        const loadData = async () => {
            loading.classList.remove('d-none')
            table.classList.add('d-none')
            tbody.innerHTML = ''

            const res = await axios.get('/api/cyber-security-services')

            res.data.forEach(s => {
                tbody.innerHTML += `
            <tr>
                <td>
                    <div class="fw-semibold">${s.name}</div>
                    <div class="text-muted small">${s.short_name ?? '-'}</div>
                </td>
                <td>${s.category.replaceAll('_',' ')}</td>
                <td>
                    ${s.is_ai_visible
                        ? '<span class="badge bg-success">AI Visible</span>'
                        : '<span class="badge bg-secondary">Hidden</span>'}
                </td>
                <td>${s.cta_label ?? '-'}</td>
                <td>${new Date(s.created_at).toLocaleDateString('id-ID')}</td>
                <td class="text-end">
                    <a href="/cyber-security-services/${s.id}/edit" class="btn btn-sm btn-warning">Edit</a>
                    <button class="btn btn-sm btn-danger" onclick="removeItem(${s.id})">Hapus</button>
                </td>
            </tr>
        `
            })

            loading.classList.add('d-none')
            table.classList.remove('d-none')
        }

        const removeItem = async id => {
            const res = await Swal.fire({
                icon: 'warning',
                title: 'Hapus service?',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus'
            })

            if (!res.isConfirmed) return

            await axios.delete('/api/cyber-security-services/' + id)

            Toast.fire({
                icon: 'success',
                title: 'Service dihapus'
            })
            loadData()
        }

        loadData()
    </script>
@endpush
