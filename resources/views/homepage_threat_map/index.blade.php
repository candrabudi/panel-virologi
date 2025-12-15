@extends('template.app')

@section('title', 'Homepage Cyber Threat Map')

@section('content')
    <div class="container-fluid">

        <div class="page-title-head d-flex align-items-center mb-4">
            <div class="flex-grow-1">
                <h4 class="page-main-title m-0">Homepage – Cyber Threat Map</h4>
            </div>
            <div class="text-end">
                <ol class="breadcrumb m-0 py-0">
                    <li class="breadcrumb-item">Virologi</li>
                    <li class="breadcrumb-item">CMS</li>
                    <li class="breadcrumb-item active">Threat Map</li>
                </ol>
            </div>
        </div>

        <div class="row g-4">

            <!-- FORM -->
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header fw-semibold">
                        Pengaturan Section
                    </div>
                    <div class="card-body">

                        <div id="alert-box"></div>

                        <form id="threat-map-form" onsubmit="return false;">

                            <div class="mb-3">
                                <label class="form-label">Pre Title</label>
                                <input type="text" name="pre_title" class="form-control"
                                    value="{{ $section->pre_title ?? '' }}" placeholder="Cyber Threat Map">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Judul Utama</label>
                                <textarea name="title" class="form-control" rows="2" placeholder="Tetap Update dengan Serangan Siber">{{ $section->title ?? '' }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control" rows="4"
                                    placeholder="Informasi serangan real-time seluruh dunia...">{{ $section->description ?? '' }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Teks Tombol (CTA)</label>
                                <input type="text" name="cta_text" class="form-control"
                                    value="{{ $section->cta_text ?? '' }}" placeholder="View Live Threat Map">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">URL Tombol</label>
                                <input type="text" name="cta_url" class="form-control"
                                    value="{{ $section->cta_url ?? '' }}" placeholder="/threat-map">
                            </div>

                            <div class="form-check mb-4">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    {{ $section->is_active ?? true ? 'checked' : '' }}>
                                <label class="form-check-label">
                                    Tampilkan section di homepage
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
                        Preview Section
                    </div>
                    <div class="card-body" style="background:#000; border-radius:8px;">

                        <span class="badge mb-3" style="background:#ef4444;">
                            {{ $section->pre_title ?? 'Cyber Threat Map' }}
                        </span>

                        <h3 class="fw-bold text-white mt-2" style="line-height:1.3;">
                            {!! nl2br(e($section->title ?? 'Tetap Update dengan Serangan Siber')) !!}
                        </h3>

                        <p class="text-muted mt-3" style="max-width:420px;">
                            {{ $section->description ?? 'Informasi serangan real-time seluruh dunia dapat dipantau langsung.' }}
                        </p>

                        @if (!empty($section->cta_text))
                            <div class="mt-4">
                                <span class="badge bg-light text-dark px-3 py-2">
                                    {{ $section->cta_text }}
                                </span>
                            </div>
                        @endif

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

            const form = document.getElementById('threat-map-form')
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
                    const res = await axios.post('{{ route('homepage.threat-map.store') }}', formData)

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
