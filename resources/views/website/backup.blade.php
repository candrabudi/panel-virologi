@extends('layouts.app')
@section('title', 'Pengaturan Website')
@section('content')
<div class="container-fluid">

    <div class="page-title-head d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <h4 class="page-main-title mb-1">Pengaturan Website</h4>
            <small class="text-muted">Kelola identitas, kontak, dan branding website</small>
        </div>
        <ol class="breadcrumb m-0">
            <li class="breadcrumb-item">Virologi</li>
            <li class="breadcrumb-item">CMS</li>
            <li class="breadcrumb-item active">Website</li>
        </ol>
    </div>

    <div class="row g-4">

        {{-- LEFT --}}
        <div class="col-lg-6 d-flex flex-column gap-4">

            {{-- GENERAL --}}
            <div class="card shadow-sm rounded-3">
                <div class="card-header bg-white fw-semibold">
                    <i class="ri ri-global-line me-1"></i> Informasi Website
                </div>
                <div class="card-body">
                    <div class="alert-area mb-3"></div>

                    <form class="ajax-form" data-url="{{ route('website.general') }}" onsubmit="return false;">
                        <div class="mb-3">
                            <label class="form-label">Nama Website</label>
                            <input name="name" class="form-control"
                                   value="{{ $website->name ?? '' }}"
                                   placeholder="Nama website">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tagline</label>
                            <input name="tagline" class="form-control"
                                   value="{{ $website->tagline ?? '' }}"
                                   placeholder="Tagline singkat">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Deskripsi</label>
                            <textarea name="description"
                                      class="form-control"
                                      rows="4"
                                      placeholder="Deskripsi singkat website">{{ $website->description ?? '' }}</textarea>
                        </div>

                        <button type="button" class="btn btn-primary w-100 btn-save">
                            <span class="btn-text">Simpan Perubahan</span>
                            <span class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- CONTACT --}}
            <div class="card shadow-sm rounded-3">
                <div class="card-header bg-white fw-semibold">
                    <i class="ri ri-phone-line me-1"></i> Kontak
                </div>
                <div class="card-body">
                    <div class="alert-area mb-3"></div>

                    <form class="ajax-form" data-url="{{ route('website.contact') }}" onsubmit="return false;">
                        <div class="mb-3">
                            <label class="form-label">Nomor Telepon</label>
                            <input name="phone" class="form-control"
                                   value="{{ $website->phone ?? '' }}"
                                   placeholder="+62xxxx">
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Email</label>
                            <input name="email" class="form-control"
                                   value="{{ $website->email ?? '' }}"
                                   placeholder="admin@domain.com">
                        </div>

                        <button type="button" class="btn btn-primary w-100 btn-save">
                            <span class="btn-text">Simpan Kontak</span>
                            <span class="spinner-border spinner-border-sm d-none"></span>
                        </button>
                    </form>
                </div>
            </div>

        </div>

        {{-- RIGHT --}}
        <div class="col-lg-6">

            {{-- BRANDING --}}
            <div class="card shadow-sm rounded-3 h-100">
                <div class="card-header bg-white fw-semibold">
                    <i class="ri ri-palette-line me-1"></i> Branding
                </div>
                <div class="card-body">
                    <div class="alert-area mb-3"></div>

                    <form class="ajax-form"
                          data-url="{{ route('website.branding') }}"
                          enctype="multipart/form-data"
                          onsubmit="return false;">

                        <div class="mb-4">
                            <label class="form-label">Logo Rectangle</label>
                            <input type="file" name="logo_rectangle" class="form-control">
                            @if ($website?->logo_rectangle)
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-dark">Current</span>
                                    <img src="{{ asset('storage/'.$website->logo_rectangle) }}"
                                         class="rounded border"
                                         height="40">
                                </div>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Logo Square</label>
                            <input type="file" name="logo_square" class="form-control">
                            @if ($website?->logo_square)
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-dark">Current</span>
                                    <img src="{{ asset('storage/'.$website->logo_square) }}"
                                         class="rounded border"
                                         height="40">
                                </div>
                            @endif
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Favicon</label>
                            <input type="file" name="favicon" class="form-control">
                            @if ($website?->favicon)
                                <div class="mt-2 d-flex align-items-center gap-2">
                                    <span class="badge bg-light text-dark">Current</span>
                                    <img src="{{ asset('storage/'.$website->favicon) }}"
                                         class="rounded border"
                                         height="24">
                                </div>
                            @endif
                        </div>

                        <button type="button" class="btn btn-primary w-100 btn-save">
                            <span class="btn-text">Simpan Branding</span>
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

            document.querySelectorAll('.ajax-form').forEach(form => {

                const btn = form.querySelector('.btn-save')
                const spinner = btn.querySelector('.spinner-border')
                const text = btn.querySelector('.btn-text')
                const alertArea = form.querySelector('.alert-area') ||
                    form.closest('.card-body').querySelector('.alert-area')
                const url = form.dataset.url

                btn.addEventListener('click', async () => {
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

                        if (error.response && error.response.status === 422) {
                            const messages = Object.values(error.response.data.errors)
                                .flat()
                                .join('<br>')

                            alertArea.innerHTML = `
                        <div class="alert alert-danger alert-dismissible fade show">
                            ${messages}
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
