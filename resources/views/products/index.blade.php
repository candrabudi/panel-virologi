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
                                <th>Produk</th>
                                <th>AI</th>
                                <th>CTA</th>
                                <th>SEO</th>
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

<script>
axios.defaults.headers.common['X-CSRF-TOKEN'] =
    document.querySelector('meta[name="csrf-token"]').getAttribute('content')

const tbody = document.getElementById('tbody')
const table = document.getElementById('table')
const loading = document.getElementById('loading')

const badge = (text, cls) => {
    if (!text) return `<span class="text-muted">-</span>`
    return `<span class="badge bg-${cls}">${text}</span>`
}

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

                <td>
                    <div class="fw-semibold">${p.name}</div>
                    <div class="text-muted small">${p.slug}</div>
                    <div class="small mt-1 text-muted">
                        ${p.summary ? p.summary.substring(0, 80) + '…' : '-'}
                    </div>
                </td>

                <td>
                    <div class="mb-1">${badge(p.product_type, 'secondary')}</div>
                    <div class="mb-1">${badge(p.ai_domain, 'info')}</div>
                    <div>${badge(p.ai_level, 'dark')}</div>
                </td>

                <td>
                    ${
                        p.cta_label && p.cta_url
                            ? `<a href="${p.cta_url}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    ${p.cta_label}
                               </a>`
                            : `<span class="text-muted">-</span>`
                    }
                </td>

                <td>
                    <div class="fw-semibold">${p.seo_title ?? '-'}</div>
                    <div class="small text-muted">
                        ${p.is_ai_visible ? 'AI Visible' : 'Hidden'}
                    </div>
                </td>

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
