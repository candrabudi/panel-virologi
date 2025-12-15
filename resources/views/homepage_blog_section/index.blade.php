@extends('template.app')

@section('title', 'Homepage Blog & Artikel')

@section('content')
    <div class="container-fluid">

        <div class="page-title-head d-flex align-items-center mb-4">
            <div class="flex-grow-1">
                <h4 class="page-main-title m-0">Homepage – Blog & Artikel</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item">Virologi</li>
                    <li class="breadcrumb-item">CMS</li>
                    <li class="breadcrumb-item active">Blog Section</li>
                </ol>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">

                <div class="card">
                    <div class="card-header fw-semibold">
                        Pengaturan Section Blog & Artikel
                    </div>
                    <div class="card-body">

                        <div id="alert-box"></div>

                        <form id="blog-section-form" onsubmit="return false;">

                            <div class="mb-3">
                                <label class="form-label">Judul Section</label>
                                <input type="text" name="title" class="form-control"
                                    value="{{ $section->title ?? '' }}" placeholder="Blog & Artikel">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sub Judul</label>
                                <textarea name="subtitle" class="form-control" rows="3"
                                    placeholder="Baca artikel untuk mendalami ilmu anda dalam bidang siber dan teknologi">{{ $section->subtitle ?? '' }}</textarea>
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    {{ $section->is_active ?? true ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    Tampilkan section ini di homepage
                                </label>
                            </div>

                            <button type="button" class="btn btn-primary" id="btn-save">
                                <span class="btn-text">Simpan Perubahan</span>
                                <span class="spinner-border spinner-border-sm d-none"></span>
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

            const csrf = document.querySelector('meta[name="csrf-token"]')
            if (csrf) {
                axios.defaults.headers.common['X-CSRF-TOKEN'] = csrf.getAttribute('content')
            }

            const form = document.getElementById('blog-section-form')
            const btn = document.getElementById('btn-save')
            const spinner = btn.querySelector('.spinner-border')
            const text = btn.querySelector('.btn-text')
            const alertBox = document.getElementById('alert-box')

            btn.addEventListener('click', async () => {
                alertBox.innerHTML = ''
                btn.disabled = true
                spinner.classList.remove('d-none')
                text.classList.add('d-none')

                try {
                    const formData = new FormData(form)
                    const res = await axios.post('{{ route('homepage.blog.section.store') }}', formData)

                    alertBox.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show">
                    ${res.data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `
                } catch (error) {
                    if (error.response?.status === 422) {
                        const errors = Object.values(error.response.data.errors)
                            .flat()
                            .join('<br>')

                        alertBox.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show">
                        ${errors}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `
                    } else {
                        alertBox.innerHTML = `
                    <div class="alert alert-danger alert-dismissible fade show">
                        Terjadi kesalahan sistem
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `
                    }
                }

                btn.disabled = false
                spinner.classList.add('d-none')
                text.classList.remove('d-none')
            })
        })
    </script>
@endpush
