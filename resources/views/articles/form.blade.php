@extends('template.app')

@section('title', $article ? 'Edit Article' : 'Create Article')

@push('styles')
    {{-- Memastikan Bootstrap Icons tersedia untuk tampilan modern --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
@endpush

@section('content')
    <div class="container-fluid">

        <h4 class="mb-4 fw-bold text-primary">{{ $article ? 'Edit Article: ' . $article->title : 'Create New Article' }}</h4>

        <div id="alert-box" class="alert d-none rounded-3 shadow-sm"></div>

        <form id="form" enctype="multipart/form-data">
            @csrf

            @if ($article)
                {{-- Method spoofing untuk UPDATE --}}
                @method('PUT')
            @endif

            <div class="row g-4">

                <div class="col-lg-8">
                    {{-- Konten Utama --}}
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-body">
                            <label class="form-label fw-bold">Title</label>
                            <input name="title" id="title-input" class="form-control mb-3 rounded-pill"
                                placeholder="Enter article title" value="{{ $article->title ?? '' }}">
                            <div class="invalid-feedback d-none" id="error-title"></div>

                            <label class="form-label fw-bold">Excerpt / Ringkasan</label>
                            <textarea name="excerpt" class="form-control mb-3 rounded-3" placeholder="Short summary of the article" rows="2">{{ $article->excerpt ?? '' }}</textarea>
                            <div class="invalid-feedback d-none" id="error-excerpt"></div>

                            <label class="form-label fw-bold">Content</label>
                            {{-- ID ini digunakan oleh TinyMCE --}}
                            <textarea name="content" id="content-editor" class="form-control" placeholder="Full article content" rows="10">{{ $article->content ?? '' }}</textarea>
                            <div class="invalid-feedback d-none" id="error-content"></div>
                        </div>
                    </div>

                    {{-- SEO Section --}}
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold text-muted">SEO Tags</h6>
                        </div>
                        <div class="card-body">
                            <label class="form-label">SEO Title</label>
                            <input name="seo_title" class="form-control mb-2 rounded-pill"
                                placeholder="SEO Title (optional)" value="{{ $article->seo_title ?? '' }}">

                            <label class="form-label">SEO Description</label>
                            <textarea name="seo_description" class="form-control mb-2 rounded-3" placeholder="SEO Description (optional)"
                                rows="2">{{ $article->seo_description ?? '' }}</textarea>

                            <label class="form-label">SEO Keywords</label>
                            <input name="seo_keywords" class="form-control mb-2 rounded-pill"
                                placeholder="Keywords, separated by commas (optional)"
                                value="{{ $article->seo_keywords ?? '' }}">
                        </div>
                    </div>

                    {{-- Open Graph Section (Opsional, tapi bagus untuk sharing) --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold text-muted">Open Graph (Social Sharing)</h6>
                        </div>
                        <div class="card-body">
                            <label class="form-label">OG Title</label>
                            <input name="og_title" class="form-control mb-2 rounded-pill" placeholder="OG Title (optional)"
                                value="{{ $article->og_title ?? '' }}">

                            <label class="form-label">OG Description</label>
                            <textarea name="og_description" class="form-control mb-2 rounded-3" placeholder="OG Description (optional)"
                                rows="2">{{ $article->og_description ?? '' }}</textarea>

                            <label class="form-label">OG Image URL</label>
                            <input name="og_image" class="form-control mb-2 rounded-pill"
                                placeholder="OG Image URL (optional)" value="{{ $article->og_image ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">

                    {{-- Thumbnail --}}
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold text-muted">Thumbnail</h6>
                        </div>
                        <div class="card-body">
                            <label class="form-label">Upload Thumbnail File</label>
                            <input type="file" name="thumbnail" class="form-control mb-3">
                            <div class="invalid-feedback d-none" id="error-thumbnail"></div>

                            @if ($article?->thumbnail)
                                <img src="{{ asset('storage/' . $article->thumbnail) }}"
                                    class="img-fluid rounded shadow-sm border d-block mx-auto"
                                    style="max-height: 200px; object-fit: cover;">
                                <small class="text-muted d-block mt-2">Current Image</small>
                            @endif
                        </div>
                    </div>

                    {{-- Categories --}}
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold text-muted">Categories (Min 1)</h6>
                        </div>
                        <div class="card-body" id="category-checks">
                            @foreach ($categories as $cat)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]"
                                        id="cat-{{ $cat->id }}" value="{{ $cat->id }}"
                                        @checked($article && $article->categories->contains($cat->id))>
                                    <label class="form-check-label" for="cat-{{ $cat->id }}">
                                        {{ $cat->name }}
                                    </label>
                                </div>
                            @endforeach
                            <div class="text-danger small mt-2 d-none" id="error-categories"></div>
                        </div>
                    </div>

                    {{-- Tags --}}
                    <div class="card shadow-sm mb-4 border-0">
                        <div class="card-header bg-light">
                            <h6 class="mb-0 fw-bold text-muted">Tags (Optional)</h6>
                        </div>
                        <div class="card-body">
                            @foreach ($tags as $tag)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tags[]"
                                        id="tag-{{ $tag->id }}" value="{{ $tag->id }}"
                                        @checked($article && $article->tags->contains($tag->id))>
                                    <label class="form-check-label" for="tag-{{ $tag->id }}">
                                        {{ $tag->name }}
                                    </label>
                                </div>
                            @endforeach
                            <div class="invalid-feedback d-none" id="error-tags"></div>
                        </div>
                    </div>

                    {{-- Action Card --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-body">
                            <div class="form-check form-switch mb-3">
                                <input type="checkbox" name="is_published" class="form-check-input"
                                    id="is_published_switch" value="1" @checked($article?->is_published)>
                                <label class="form-check-label" for="is_published_switch">
                                    Publish Article Now
                                </label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100 shadow-sm" id="submit-btn">
                                <span id="btn-text">{{ $article ? 'Update Article' : 'Simpan Article' }}</span>
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
    {{-- TinyMCE Script --}}
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Inisialisasi TinyMCE
        tinymce.init({
            selector: '#content-editor',
            plugins: 'advlist autolink lists link charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime table help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            menubar: 'file edit view insert format tools help',
            height: 500,
            skin: 'oxide',
            content_css: 'default',
            // Nonaktifkan fitur yang berhubungan dengan gambar dan file
            image_title: false,
            automatic_uploads: false,
            file_picker_callback: (cb, value, meta) => {
                // Hanya izinkan link/media biasa, tidak ada upload
                if (meta.filetype == 'file' || meta.filetype == 'media') {
                    // Blokir file/media upload
                    return false;
                }
            }
        });

        // --- GLOBAL VARIABLES ---
        const form = document.getElementById('form');
        const submitBtn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const btnSpinner = document.getElementById('btn-spinner');
        const alertBox = document.getElementById('alert-box');

        // --- HELPER FUNCTIONS ---

        function alertMsg(type, msg) {
            alertBox.classList.remove('d-none', 'alert-success', 'alert-danger');
            alertBox.classList.add(`alert-${type}`);
            alertBox.innerHTML = msg;
        }

        function clearErrors() {
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback:not(.d-none)').forEach(el => el.classList.add('d-none'));
            document.querySelectorAll('.text-danger.small:not(.d-none)').forEach(el => el.classList.add('d-none'));
        }

        function displayErrors(errors) {
            clearErrors();
            for (const key in errors) {
                const errorMsg = errors[key][0];
                let inputElement = null;
                let errorDisplayElement = null;

                // Handle single input fields
                if (key.match(/^[a-z_]+$/)) {
                    inputElement = form.querySelector(`[name="${key}"]`);
                    errorDisplayElement = document.getElementById(`error-${key}`);
                }

                // Handle array inputs (categories[], tags[])
                if (key === 'categories') {
                    errorDisplayElement = document.getElementById('error-categories');
                }

                // If input element exists, add invalid class
                if (inputElement) {
                    inputElement.classList.add('is-invalid');
                }

                // If dedicated error element exists, display message
                if (errorDisplayElement) {
                    errorDisplayElement.innerHTML = errorMsg;
                    errorDisplayElement.classList.remove('d-none');
                } else if (inputElement) {
                    // Fallback: If no dedicated element, use the default browser feedback (though TinyMCE makes this tricky)
                    inputElement.setCustomValidity(errorMsg);
                    inputElement.reportValidity();
                } else if (key === 'content' && tinymce.get('content-editor')) {
                    // Special handling for TinyMCE content
                    alertMsg('danger', `<strong>Content Error:</strong> ${errorMsg}`);
                }
            }
        }

        // --- SUBMIT HANDLER ---
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Perbarui konten TinyMCE ke textarea sebelum submit
            tinymce.triggerSave();

            clearErrors();
            alertBox.classList.add('d-none');

            // Set loading state
            submitBtn.disabled = true;
            btnText.classList.add('d-none');
            btnSpinner.classList.remove('d-none');

            const formData = new FormData(this);
            const articleId = '{{ $article->id ?? null }}';
            const url = articleId ? `/articles/${articleId}` : '/articles';

            // Jika method PUT, FormData harus ditambahkan _method: PUT
            if (articleId) {
                formData.append('_method', 'PUT');
            }

            // Hapus field _token yang sudah diurus oleh header atau method spoofing
            formData.delete('_token');

            // AXIOS CALL
            axios.post(url, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    }
                })
                .then(res => {
                    if (res.data.status === true) {
                        // Sukses
                        alertMsg('success', 'Article berhasil disimpan! Redirecting...');
                        setTimeout(() => {
                            window.location.href = res.data.redirect || '/articles';
                        }, 1000);
                    }
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        // Error Validasi dari controller
                        alertMsg('danger', 'Terdapat kesalahan validasi pada data yang Anda masukkan.');
                        displayErrors(error.response.data.errors);
                    } else {
                        // Error lainnya
                        console.error("Submission Error:", error);
                        alertMsg('danger', 'Terjadi kesalahan saat menyimpan artikel. Silakan coba lagi.');
                    }
                })
                .finally(() => {
                    // Reset loading state
                    submitBtn.disabled = false;
                    btnText.classList.remove('d-none');
                    btnSpinner.classList.add('d-none');
                    window.scrollTo(0, 0); // Gulir ke atas untuk melihat pesan alert
                });
        });
    </script>
@endpush
