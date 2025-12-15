@extends('template.app')

@section('content')
    <div class="container-fluid">

        <div class="page-title-head d-flex align-items-center mb-4">
            <div class="flex-grow-1">
                <h4 class="page-main-title m-0">Pengaturan Website</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item">Virologi</li>
                    <li class="breadcrumb-item">CMS</li>
                    <li class="breadcrumb-item active">Website</li>
                </ol>
            </div>
        </div>

        <div class="row g-4">

            <!-- KIRI -->
            <div class="col-lg-6">

                <!-- INFORMASI -->
                <div class="card">
                    <div class="card-header fw-semibold">Informasi Website</div>
                    <div class="card-body">
                        <div class="alert-area"></div>

                        <form class="ajax-form" data-url="{{ route('website.general') }}" onsubmit="return false;">
                            <input name="name" class="form-control mb-2" placeholder="Nama Website"
                                value="{{ $website->name ?? '' }}">
                            <input name="tagline" class="form-control mb-2" placeholder="Tagline"
                                value="{{ $website->tagline ?? '' }}">
                            <textarea name="description" class="form-control mb-2" placeholder="Deskripsi Singkat">{{ $website->description ?? '' }}</textarea>
                            <textarea name="long_description" class="form-control mb-3" placeholder="Deskripsi Panjang">{{ $website->long_description ?? '' }}</textarea>

                            <button type="button" class="btn btn-primary btn-save">
                                <span class="btn-text">Simpan</span>
                                <span class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- KONTAK -->
                <div class="card mt-4">
                    <div class="card-header fw-semibold">Kontak</div>
                    <div class="card-body">
                        <div class="alert-area"></div>

                        <form class="ajax-form" data-url="{{ route('website.contact') }}" onsubmit="return false;">
                            <input name="phone_number" class="form-control mb-2" placeholder="Nomor Handphone"
                                value="{{ $website->phone_number ?? '' }}">
                            <input name="email" class="form-control mb-3" placeholder="Email"
                                value="{{ $website->email ?? '' }}">

                            <button type="button" class="btn btn-primary btn-save">
                                <span class="btn-text">Simpan</span>
                                <span class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </form>
                    </div>
                </div>

            </div>

            <!-- KANAN -->
            <div class="col-lg-6">

                <!-- BRANDING -->
                <div class="card">
                    <div class="card-header fw-semibold">Branding</div>
                    <div class="card-body">
                        <div class="alert-area"></div>

                        <form class="ajax-form" data-url="{{ route('website.branding') }}" enctype="multipart/form-data"
                            onsubmit="return false;">
                            <input type="file" name="logo_rectangle" class="form-control mb-2">
                            <input type="file" name="logo_square" class="form-control mb-2">
                            <input type="file" name="favicon" class="form-control mb-3">

                            <button type="button" class="btn btn-primary btn-save">
                                <span class="btn-text">Simpan</span>
                                <span class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- SEO -->
                <div class="card mt-4">
                    <div class="card-header fw-semibold">SEO & Open Graph</div>
                    <div class="card-body">
                        <div class="alert-area"></div>

                        <form class="ajax-form" data-url="{{ route('website.seo') }}" enctype="multipart/form-data"
                            onsubmit="return false;">
                            <input name="meta_title" class="form-control mb-2" placeholder="Meta Title"
                                value="{{ $website->meta_title ?? '' }}">
                            <textarea name="meta_description" class="form-control mb-2" placeholder="Meta Description">{{ $website->meta_description ?? '' }}</textarea>
                            <textarea name="meta_keywords" class="form-control mb-2" placeholder="Meta Keywords">{{ $website->meta_keywords ?? '' }}</textarea>
                            <input name="og_title" class="form-control mb-2" placeholder="OG Title"
                                value="{{ $website->og_title ?? '' }}">
                            <textarea name="og_description" class="form-control mb-2" placeholder="OG Description">{{ $website->og_description ?? '' }}</textarea>
                            <input type="file" name="og_image" class="form-control mb-3">

                            <button type="button" class="btn btn-primary btn-save">
                                <span class="btn-text">Simpan</span>
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
    axios.defaults.headers.common['X-CSRF-TOKEN'] =
        document.querySelector('meta[name="csrf-token"]').getAttribute('content')

    const forms = document.querySelectorAll('.ajax-form')

    console.log('Ajax forms found:', forms.length)

    forms.forEach(form => {
        const btn = form.querySelector('.btn-save')
        const spinner = btn.querySelector('.spinner-border')
        const text = btn.querySelector('.btn-text')
        const alertArea = form.closest('.card-body').querySelector('.alert-area')
        const url = form.dataset.url

        if (!btn || !url) {
            console.warn('Button or URL not found for form', form)
            return
        }

        btn.addEventListener('click', async () => {
            console.log('Submitting to:', url)

            alertArea.innerHTML = ''
            btn.disabled = true
            spinner.classList.remove('d-none')
            text.classList.add('d-none')

            try {
                const formData = new FormData(form)
                const response = await axios.post(url, formData)

                alertArea.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show">
                        ${response.data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `
            } catch (error) {
                if (error.response?.status === 422) {
                    const errors = Object.values(error.response.data.errors)
                        .flat()
                        .join('<br>')

                    alertArea.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show">
                            ${errors}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `
                } else {
                    alertArea.innerHTML = `
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
})
</script>
@endpush
