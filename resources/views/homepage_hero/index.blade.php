@extends('template.app')

@section('title', 'Homepage Hero')

@section('content')
    <div class="container-fluid">

        <div class="page-title-head d-flex align-items-center mb-4">
            <div class="flex-grow-1">
                <h4 class="page-main-title m-0">Homepage Hero</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item">Virologi</li>
                    <li class="breadcrumb-item">CMS</li>
                    <li class="breadcrumb-item active">Homepage Hero</li>
                </ol>
            </div>
        </div>

        <div class="row g-4">

            <!-- FORM -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header fw-semibold">
                        Pengaturan Hero Homepage
                    </div>
                    <div class="card-body">

                        <div id="alert-box"></div>

                        <form id="hero-form" onsubmit="return false;">

                            <div class="mb-3">
                                <label class="form-label">Pre Title</label>
                                <input type="text" name="pre_title" class="form-control"
                                    value="{{ $hero->pre_title ?? '' }}" placeholder="Contoh: Virologi">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Judul Utama</label>
                                <textarea name="title" class="form-control" rows="2" placeholder="Judul besar hero">{{ $hero->title ?? '' }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sub Judul</label>
                                <textarea name="subtitle" class="form-control" rows="3" placeholder="Deskripsi singkat">{{ $hero->subtitle ?? '' }}</textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Warna Overlay</label>
                                    <input type="color" name="overlay_color" class="form-control form-control-color"
                                        value="{{ $hero->overlay_color ?? '#000000' }}">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Opacity Overlay</label>
                                    <input type="number" step="0.05" min="0" max="1"
                                        name="overlay_opacity" class="form-control"
                                        value="{{ $hero->overlay_opacity ?? 0.5 }}">
                                </div>
                            </div>

                            <hr>

                            <h6 class="fw-semibold">Primary Button</h6>
                            <div class="mb-2">
                                <input type="text" name="primary_button_text" class="form-control"
                                    value="{{ $hero->primary_button_text ?? '' }}" placeholder="Teks tombol utama">
                            </div>
                            <div class="mb-3">
                                <input type="text" name="primary_button_url" class="form-control"
                                    value="{{ $hero->primary_button_url ?? '' }}" placeholder="URL tombol utama">
                            </div>

                            <h6 class="fw-semibold">Secondary Button</h6>
                            <div class="mb-2">
                                <input type="text" name="secondary_button_text" class="form-control"
                                    value="{{ $hero->secondary_button_text ?? '' }}" placeholder="Teks tombol kedua">
                            </div>
                            <div class="mb-3">
                                <input type="text" name="secondary_button_url" class="form-control"
                                    value="{{ $hero->secondary_button_url ?? '' }}" placeholder="URL tombol kedua">
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    {{ $hero->is_active ?? true ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    Aktifkan Hero Homepage
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

            <!-- PREVIEW -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header fw-semibold">
                        Preview Hero (Teks)
                    </div>
                    <div class="card-body">

                        <div class="p-4 rounded"
                            style="background-color: {{ $hero->overlay_color ?? '#000000' }};
                                opacity: {{ $hero->overlay_opacity ?? 0.5 }};">
                            <p class="fw-semibold mb-1 text-white">
                                {{ $hero->pre_title ?? 'Pre Title' }}
                            </p>
                            <h3 class="fw-bold text-white">
                                {{ $hero->title ?? 'Judul Hero' }}
                            </h3>
                            <p class="text-white-50">
                                {{ $hero->subtitle ?? 'Sub judul hero' }}
                            </p>

                            <div class="mt-3">
                                @if (!empty($hero->primary_button_text))
                                    <span class="badge bg-light text-dark me-2">
                                        {{ $hero->primary_button_text }}
                                    </span>
                                @endif

                                @if (!empty($hero->secondary_button_text))
                                    <span class="badge bg-outline-light text-white border">
                                        {{ $hero->secondary_button_text }}
                                    </span>
                                @endif
                            </div>
                        </div>

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

            const form = document.getElementById('hero-form')
            const btn = document.getElementById('btn-save')
            const alertBox = document.getElementById('alert-box')
            const spinner = btn.querySelector('.spinner-border')
            const text = btn.querySelector('.btn-text')

            btn.addEventListener('click', async () => {
                alertBox.innerHTML = ''
                btn.disabled = true
                spinner.classList.remove('d-none')
                text.classList.add('d-none')

                try {
                    const formData = new FormData(form)
                    const res = await axios.post('{{ route('homepage.hero.store') }}', formData)

                    alertBox.innerHTML = `
                <div class="alert alert-success alert-dismissible fade show">
                    ${res.data.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `
                } catch (error) {
                    if (error.response?.status === 422) {
                        const errors = Object.values(error.response.data.errors).flat().join('<br>')
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
