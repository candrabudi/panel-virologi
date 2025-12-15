@extends('template.app')

@section('title', 'Produk')

@section('content')
    <div class="container-fluid">

        <div class="page-title-head d-flex justify-content-between align-items-center mb-4">
            <h4 class="page-main-title m-0">Produk</h4>
            <a href="/products/create" class="btn btn-primary">
                <i class="ri ri-add-line"></i> Tambah Produk
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
                                <th width="80">Thumbnail</th>
                                <th>Nama</th>
                                <th>Slug</th>
                                <th>Deskripsi</th>
                                <th>SEO Title</th>
                                <th width="150">Tanggal</th>
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

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        const tbody = document.getElementById('tbody')
        const table = document.getElementById('table')
        const loading = document.getElementById('loading')

        const loadData = async () => {
            loading.classList.remove('d-none')
            table.classList.add('d-none')

            const res = await axios.get('/api/products')

            tbody.innerHTML = ''

            res.data.forEach(p => {
                tbody.innerHTML += `
                    <tr>
                        <td>
                            ${
                                p.thumbnail
                                    ? `<img src="/storage/${p.thumbnail}" class="rounded" style="width:50px;height:50px;object-fit:cover">`
                                    : `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:50px;height:50px">
                                                <i class="ri-image-line text-muted"></i>
                                           </div>`
                            }
                        </td>

                        <td class="fw-semibold">${p.name}</td>
                        <td class="text-muted">${p.slug}</td>
                        <td class="text-muted" style="max-width:240px">
                            ${p.description ? p.description.substring(0, 80) + '...' : '-'}
                        </td>
                        <td class="text-muted">${p.seo_title ?? '-'}</td>
                        <td class="text-muted">
                            ${new Date(p.created_at).toLocaleDateString('id-ID')}
                        </td>
                        <td class="text-end">
                            <a href="/products/${p.id}/edit" class="btn btn-sm btn-warning">
                                Edit
                            </a>
                            <button class="btn btn-sm btn-danger" onclick="removeProduct(${p.id})">
                                Hapus
                            </button>
                        </td>
                    </tr>
                `
            })

            loading.classList.add('d-none')
            table.classList.remove('d-none')
        }

        const removeProduct = async (id) => {
            if (!confirm('Hapus produk ini?')) return
            await axios.delete('/api/products/' + id)
            loadData()
        }

        loadData()
    </script>
@endpush
