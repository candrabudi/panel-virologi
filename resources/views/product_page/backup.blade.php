@extends('layouts.app')

@section('title', 'Halaman Produk')

@section('content')
    <div class="container-fluid">

        <div class="page-title-head d-flex align-items-center mb-4">
            <div class="flex-grow-1">
                <h4 class="page-main-title m-0">Halaman Produk</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item">Virologi</li>
                    <li class="breadcrumb-item">Produk</li>
                    <li class="breadcrumb-item active">Pengaturan Halaman</li>
                </ol>
            </div>
        </div>

        <div class="row g-4">

            <!-- LEFT -->
            <div class="col-lg-7">
                <div class="card">
                    <div class="card-header fw-semibold">Section Produk</div>
                    <div class="card-body">

                        <div id="alert-box"></div>

                        <form id="page-form" onsubmit="return false">

                            <div class="mb-3">
                                <label class="form-label">Judul Halaman</label>
                                <input type="text" name="page_title" class="form-control"
                                    value="{{ $page->page_title ?? '' }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sub Judul</label>
                                <input type="text" name="page_subtitle" class="form-control"
                                    value="{{ $page->page_subtitle ?? '' }}">
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">CTA Text</label>
                                    <input type="text" name="cta_text" class="form-control"
                                        value="{{ $page->cta_text ?? 'Hubungi Kami' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">CTA URL</label>
                                    <input type="text" name="cta_url" class="form-control"
                                        value="{{ $page->cta_url ?? '/contact' }}">
                                </div>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    {{ $page?->is_active ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    Aktifkan halaman produk
                                </label>
                            </div>

                            <button class="btn btn-primary mt-4" id="btn-save">
                                <span class="btn-text">Simpan</span>
                                <span class="spinner-border spinner-border-sm d-none"></span>
                            </button>

                        </form>
                    </div>
                </div>
            </div>

            <!-- RIGHT -->
            <div class="col-lg-5">
                <div class="card">
                    <div class="card-header fw-semibold">SEO Halaman Produk</div>
                    <div class="card-body">

                        <input class="form-control mb-2" name="seo_title" form="page-form" placeholder="SEO Title"
                            value="{{ $page->seo_title ?? '' }}">

                        <textarea class="form-control mb-2" rows="3" name="seo_description" form="page-form"
                            placeholder="SEO Description">{{ $page->seo_description ?? '' }}</textarea>

                        <textarea class="form-control mb-2" rows="3" name="seo_keywords" form="page-form" placeholder="SEO Keywords">{{ $page->seo_keywords ?? '' }}</textarea>

                        <input class="form-control mb-2" name="og_title" form="page-form" placeholder="OG Title"
                            value="{{ $page->og_title ?? '' }}">

                        <textarea class="form-control mb-2" rows="3" name="og_description" form="page-form" placeholder="OG Description">{{ $page->og_description ?? '' }}</textarea>

                        <input class="form-control" name="canonical_url" form="page-form" placeholder="Canonical URL"
                            value="{{ $page->canonical_url ?? '' }}">
                    </div>
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

        const btn = document.getElementById('btn-save')
        btn.onclick = async () => {
            btn.disabled = true
            btn.querySelector('.spinner-border').classList.remove('d-none')
            btn.querySelector('.btn-text').classList.add('d-none')

            try {
                const form = document.getElementById('page-form')
                const data = new FormData(form)

                const res = await axios.post('{{ route('product.page.store') }}', data)

                document.getElementById('alert-box').innerHTML = `
            <div class="alert alert-success alert-dismissible fade show">
                ${res.data.message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `
            } catch (e) {
                document.getElementById('alert-box').innerHTML = `
            <div class="alert alert-danger">
                Gagal menyimpan data
            </div>
        `
            }

            btn.disabled = false
            btn.querySelector('.spinner-border').classList.add('d-none')
            btn.querySelector('.btn-text').classList.remove('d-none')
        }
    </script>
@endpush
