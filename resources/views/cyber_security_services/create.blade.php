@extends('template.app')

@section('title', 'Tambah Cyber Security Service')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')
    <div class="container-fluid mt-3">

        <h3 class="mb-4 fw-bold text-primary d-flex align-items-center gap-2">
            <i class="bi bi-shield-lock fs-2"></i>
            Tambah Cyber Security Service
        </h3>

        <form id="form">
            @csrf

            <div class="row g-4">

                {{-- LEFT COLUMN --}}
                <div class="col-lg-8">

                    {{-- BASIC INFO --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">

                            <label class="form-label fw-bold fs-5">Nama Service</label>
                            <input name="name" class="form-control fs-5 mb-3" placeholder="Nama Service">

                            <label class="form-label fw-bold fs-5">Short Name</label>
                            <input name="short_name" class="form-control fs-5 mb-3" placeholder="Short Name">

                            <label class="form-label fw-bold fs-5">Ringkasan</label>
                            <textarea name="summary" class="form-control fs-5 mb-3 rounded-3" rows="3"
                                placeholder="Ringkasan singkat service"></textarea>

                            <label class="form-label fw-bold fs-5">Deskripsi Lengkap</label>
                            <textarea name="description" id="content-editor" class="form-control fs-5" rows="10"
                                placeholder="Deskripsi lengkap service"></textarea>

                        </div>
                    </div>

                    {{-- SERVICE DETAILS --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h6 class="fw-bold text-muted mb-0 fs-5">
                                <i class="bi bi-diagram-3 me-1"></i> Service Details
                            </h6>
                        </div>
                        <div class="card-body">

                            <label class="form-label fs-5">Service Scope</label>
                            <textarea name="service_scope" class="form-control fs-5 mb-3"
                                placeholder="contoh: vulnerability assessment, monitoring, response"></textarea>

                            <label class="form-label fs-5">Deliverables</label>
                            <textarea name="deliverables" class="form-control fs-5 mb-3" placeholder="contoh: laporan, dashboard, rekomendasi"></textarea>

                            <label class="form-label fs-5">Target Audience</label>
                            <textarea name="target_audience" class="form-control fs-5" placeholder="contoh: enterprise, startup, government"></textarea>

                        </div>
                    </div>

                    {{-- AI --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light">
                            <h6 class="fw-bold text-muted mb-0 fs-5">
                                <i class="bi bi-cpu me-1"></i> AI Configuration
                            </h6>
                        </div>
                        <div class="card-body">

                            <label class="form-label fs-5">AI Keywords</label>
                            <textarea name="ai_keywords" class="form-control fs-5" placeholder="contoh: ransomware, soc, pentest, cloud security"></textarea>
                            <small class="text-muted fs-6">
                                Pisahkan dengan koma. Digunakan untuk AI Agent & rekomendasi.
                            </small>

                        </div>
                    </div>

                </div>

                {{-- RIGHT COLUMN --}}
                <div class="col-lg-4">

                    {{-- METADATA --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h6 class="fw-bold text-muted mb-0 fs-5">
                                <i class="bi bi-sliders me-1"></i> Metadata
                            </h6>
                        </div>
                        <div class="card-body">

                            <label class="form-label fw-bold fs-5">Kategori</label>
                            <select name="category" class="form-select fs-5 mb-3">
                                @foreach (['soc', 'pentest', 'audit', 'incident_response', 'cloud_security', 'governance', 'training', 'consulting'] as $c)
                                    <option value="{{ $c }}">
                                        {{ strtoupper(str_replace('_', ' ', $c)) }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    {{-- CTA --}}
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h6 class="fw-bold text-muted mb-0 fs-5">
                                <i class="bi bi-megaphone me-1"></i> Call To Action
                            </h6>
                        </div>
                        <div class="card-body">

                            <label class="form-label fs-5">CTA Label</label>
                            <input name="cta_label" class="form-control fs-5 mb-3" value="Hubungi Kami">

                            <label class="form-label fs-5">CTA URL</label>
                            <input name="cta_url" class="form-control fs-5" placeholder="https://example.com/contact">

                        </div>
                    </div>

                    {{-- SUBMIT --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <button type="button" class="btn btn-primary w-100 fs-5 fw-semibold shadow-sm"
                                onclick="save()">
                                <i class="bi bi-save me-1"></i> Simpan Service
                            </button>
                        </div>
                    </div>

                </div>

            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tinymce.init({
            selector: '#content-editor',
            height: 420,
            plugins: 'lists link table code fullscreen',
            toolbar: 'undo redo | bold italic | bullist numlist | link table | code fullscreen'
        })

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            timer: 3000,
            showConfirmButton: false
        })

        const save = async () => {
            tinymce.triggerSave()

            const f = document.getElementById('form')
            const d = new FormData(f)

            ;
            ['service_scope', 'deliverables', 'target_audience', 'ai_keywords']
            .forEach(k => {
                if (f[k]) {
                    d.set(k, JSON.stringify(
                        (f[k].value || '').split(',').map(v => v.trim()).filter(Boolean)
                    ))
                }
            })

            try {
                Swal.showLoading()
                await axios.post('/api/cyber-security-services', d)
                Swal.close()

                Toast.fire({
                    icon: 'success',
                    title: 'Service berhasil disimpan'
                })

                setTimeout(() => location.href = '/cyber-security-services', 1200)
            } catch (e) {
                Swal.close()
                Swal.fire('Error', 'Gagal menyimpan service', 'error')
            }
        }
    </script>
@endpush
