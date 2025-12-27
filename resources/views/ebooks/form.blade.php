@extends('template.app')

@section('title', $ebook ? 'Edit Ebook' : 'Create Ebook')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')
    <div class="container-fluid mt-3">

        <h3 class="mb-4 fw-bold text-primary d-flex align-items-center gap-2">
            <i class="bi bi-book-half fs-2"></i>
            {{ $ebook ? 'Edit Ebook: ' . $ebook->title : 'Create New Ebook' }}
        </h3>

        <div id="alert-box" class="alert d-none rounded-3 shadow-sm fs-5"></div>

        <form id="form" enctype="multipart/form-data">
            @csrf
            @if ($ebook)
                @method('PUT')
            @endif

            <div class="row g-4">

                <!-- LEFT COLUMN -->
                <div class="col-lg-8">

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body">

                            <label class="form-label fw-bold fs-5">Judul Ebook</label>
                            <input type="text" name="title" class="form-control fs-5 mb-3" placeholder="Judul ebook"
                                value="{{ old('title', $ebook->title ?? '') }}">
                            <div class="invalid-feedback d-none" id="error-title"></div>

                            <label class="form-label fw-bold fs-5">Ringkasan</label>
                            <textarea name="summary" class="form-control fs-5 mb-3 rounded-3" rows="4" placeholder="Ringkasan singkat ebook">{{ old('summary', $ebook->summary ?? '') }}</textarea>
                            <div class="invalid-feedback d-none" id="error-summary"></div>

                            <label class="form-label fw-bold fs-5">Deskripsi Lengkap</label>
                            <textarea name="content" id="content-editor" class="form-control fs-5" rows="10"
                                placeholder="Deskripsi / konten ebook">{{ old('content', $ebook->content ?? '') }}</textarea>
                            <div class="invalid-feedback d-none" id="error-content"></div>

                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light">
                            <h6 class="fw-bold text-muted mb-0 fs-5">
                                <i class="bi bi-cpu me-1"></i> AI Keywords
                            </h6>
                        </div>
                        <div class="card-body">
                            <label class="form-label fs-5">Keyword untuk AI Agent</label>
                            <input type="text" name="ai_keywords" class="form-control fs-5"
                                placeholder="contoh: ransomware, soc, incident response"
                                value="{{ old('ai_keywords', isset($ebook->ai_keywords) ? implode(',', $ebook->ai_keywords) : '') }}">
                            <small class="text-muted fs-6">
                                Pisahkan dengan koma. Digunakan untuk rekomendasi AI.
                            </small>
                            <div class="invalid-feedback d-none" id="error-ai_keywords"></div>
                        </div>
                    </div>

                </div>

                <!-- RIGHT COLUMN -->
                <div class="col-lg-4">

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h6 class="fw-bold text-muted mb-0 fs-5">
                                <i class="bi bi-image me-1"></i> Cover Ebook
                            </h6>
                        </div>
                        <div class="card-body">
                            <input type="file" name="cover_image" class="form-control fs-5 mb-3">
                            <div class="invalid-feedback d-none" id="error-cover_image"></div>

                            @if ($ebook?->cover_image)
                                <img src="{{ $ebook->cover_image }}"
                                    class="img-fluid rounded shadow-sm border mx-auto d-block"
                                    style="max-height:220px; object-fit:cover;">
                                <small class="text-muted d-block mt-2">Cover saat ini</small>
                            @endif
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h6 class="fw-bold text-muted mb-0 fs-5">
                                <i class="bi bi-file-earmark-pdf me-1"></i> File Ebook (PDF)
                            </h6>
                        </div>
                        <div class="card-body">
                            <input type="file" name="file" class="form-control fs-5">
                            @if ($ebook?->file_path)
                                <small class="d-block mt-2 text-muted">File saat ini:
                                    <a href="{{ $ebook->file_path }}" target="_blank">Download PDF</a>
                                </small>
                            @endif
                            <div class="invalid-feedback d-none" id="error-file"></div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light">
                            <h6 class="fw-bold text-muted mb-0 fs-5">
                                <i class="bi bi-sliders me-1"></i> Metadata
                            </h6>
                        </div>
                        <div class="card-body">

                            <label class="form-label fs-5 fw-bold">Level</label>
                            <select name="level" class="form-select fs-5 mb-3">
                                @foreach (['beginner', 'intermediate', 'advanced'] as $level)
                                    <option value="{{ $level }}" @selected(old('level', $ebook->level ?? '') === $level)>{{ ucfirst($level) }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback d-none" id="error-level"></div>

                            <label class="form-label fs-5 fw-bold">Topik</label>
                            <select name="topic" class="form-select fs-5 mb-3">
                                @php
                                    $topics = [
                                        'general' => 'General',
                                        'network_security' => 'Network Security',
                                        'application_security' => 'Application Security',
                                        'cloud_security' => 'Cloud Security',
                                        'soc' => 'SOC (Security Operations Center)',
                                        'pentest' => 'Penetration Testing',
                                        'malware' => 'Malware Analysis',
                                        'incident_response' => 'Incident Response',
                                        'governance' => 'Governance & Compliance',
                                    ];
                                @endphp
                                @foreach ($topics as $key => $label)
                                    <option value="{{ $key }}" @selected(old('topic', $ebook->topic ?? 'general') === $key)>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback d-none" id="error-topic"></div>

                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                    @checked(old('is_active', $ebook?->is_active ?? true))>
                                <label class="form-check-label fs-5">
                                    Aktifkan Ebook
                                </label>
                            </div>

                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <button type="submit" class="btn btn-primary w-100 fs-5 fw-semibold shadow-sm"
                                id="submit-btn">
                                <span id="btn-text">
                                    {{ $ebook ? 'Update Ebook' : 'Simpan Ebook' }}
                                </span>
                                <span id="btn-spinner" class="spinner-border spinner-border-sm d-none"></span>
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

    <script>
        tinymce.init({
            selector: '#content-editor',
            height: 420,
            plugins: 'lists link table code fullscreen',
            toolbar: 'undo redo | bold italic | bullist numlist | link table | code fullscreen'
        });

        const form = document.getElementById('form');
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');
        const alertBox = document.getElementById('alert-box');

        function alertMsg(type, msg) {
            alertBox.className = `alert alert-${type} rounded-3 shadow-sm fs-5`;
            alertBox.innerHTML = msg;
            alertBox.classList.remove('d-none');
        }

        function clearErrors() {
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.classList.add('d-none'));
        }

        function displayErrors(errors) {
            clearErrors();
            Object.keys(errors).forEach(key => {
                const input = document.querySelector(`[name="${key}"]`);
                const errorBox = document.getElementById(`error-${key}`);
                if (input) input.classList.add('is-invalid');
                if (errorBox) {
                    errorBox.textContent = errors[key][0];
                    errorBox.classList.remove('d-none');
                }
            });
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            tinymce.triggerSave();

            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            btnSpinner.classList.remove('d-none');
            alertBox.classList.add('d-none');

            const formData = new FormData(this);

            const aiInput = form.querySelector('[name="ai_keywords"]');
            if (aiInput) {
                const keywords = aiInput.value.split(',').map(k => k.trim()).filter(k => k.length > 0);
                keywords.forEach(k => formData.append('ai_keywords[]', k)); // kirim array langsung
            }

            const ebookId = '{{ $ebook->id ?? null }}';
            let url = '/ebooks';
            if (ebookId) {
                url += '/' + ebookId;
                formData.append('_method', 'PUT');
            }

            axios.post(url, formData)
                .then(res => {
                    alertMsg('success', 'Ebook berhasil disimpan. Redirecting...');
                    setTimeout(() => {
                        window.location.href = res.data.redirect || '/ebooks';
                    }, 1000);
                })
                .catch(err => {
                    if (err.response?.status === 422) {
                        alertMsg('danger', 'Validasi gagal. Periksa input Anda.');
                        displayErrors(err.response.data.errors);
                    } else {
                        alertMsg('danger', 'Terjadi kesalahan server.');
                    }
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    btnText.classList.remove('d-none');
                    btnSpinner.classList.add('d-none');
                    window.scrollTo(0, 0);
                });
        });
    </script>
@endpush
