@extends('template.app')

@section('title', 'Edit Produk')

@push('styles')
    <link href="https://cdn.quilljs.com/1.3.7/quill.snow.css" rel="stylesheet">
    <style>
        .gallery-item {
            border: 1px dashed #e5e7eb;
            border-radius: 12px;
            padding: 10px;
            position: relative;
            background: #fafafa;
        }

        .gallery-item img {
            width: 100%;
            aspect-ratio: 1 / 1;
            object-fit: cover;
            border-radius: 8px;
            margin-top: 8px;
        }

        .gallery-remove {
            position: absolute;
            top: 6px;
            right: 6px;
            width: 28px;
            height: 28px;
            border-radius: 50%;
            border: none;
            background: rgba(0, 0, 0, .65);
            color: #fff;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">

        <div class="page-title-head d-flex justify-content-between align-items-center mb-4">
            <h4 class="page-main-title m-0">Edit Produk</h4>
            <a href="/products" class="btn btn-light">← Kembali</a>
        </div>

        <form id="form" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row g-4">

                <!-- KONTEN -->
                <div class="col-lg-8">

                    <div class="card">
                        <div class="card-header fw-semibold">Informasi Produk</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="name" class="form-control" value="{{ $product->name }}"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subtitle</label>
                                <input type="text" name="subtitle" class="form-control" value="{{ $product->subtitle }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ringkasan Produk</label>
                                <textarea name="summary" class="form-control" rows="3">{{ $product->summary }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konten Detail Produk</label>
                                <div id="editor" style="min-height:220px">{!! $product->content !!}</div>
                            </div>

                        </div>
                    </div>

                    <!-- AI METADATA -->
                    <div class="card mt-4">
                        <div class="card-header fw-semibold">AI Metadata</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">AI Keywords</label>
                                <textarea name="ai_keywords" class="form-control" rows="2">
{{ is_array($product->ai_keywords) ? implode(',', $product->ai_keywords) : '' }}
                            </textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">AI Intents</label>
                                <textarea name="ai_intents" class="form-control" rows="2">
{{ is_array($product->ai_intents) ? implode(',', $product->ai_intents) : '' }}
                            </textarea>
                            </div>

                            <div>
                                <label class="form-label">AI Use Cases</label>
                                <textarea name="ai_use_cases" class="form-control" rows="2">
{{ is_array($product->ai_use_cases) ? implode(',', $product->ai_use_cases) : '' }}
                            </textarea>
                            </div>

                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="card mt-4">
                        <div class="card-header fw-semibold">SEO Produk</div>
                        <div class="card-body">

                            <input type="text" name="seo_title" class="form-control mb-3"
                                value="{{ $product->seo_title }}">

                            <textarea name="seo_description" class="form-control mb-3" rows="2">{{ $product->seo_description }}</textarea>

                            <input type="text" name="canonical_url" class="form-control mb-3"
                                value="{{ $product->canonical_url }}">

                            <textarea name="seo_keywords" class="form-control" rows="2">
{{ is_array($product->seo_keywords) ? implode(',', $product->seo_keywords) : '' }}
                        </textarea>

                        </div>
                    </div>

                </div>

                <!-- SIDEBAR -->
                <div class="col-lg-4">

                    <div class="card mb-4">
                        <div class="card-header fw-semibold">Pengaturan Produk</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Tipe Produk</label>
                                <select name="product_type" class="form-select">
                                    @foreach (['digital', 'hardware', 'service', 'bundle'] as $t)
                                        <option value="{{ $t }}" @selected($product->product_type === $t)>
                                            {{ ucfirst($t) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">AI Domain</label>
                                <select name="ai_domain" class="form-select">
                                    @foreach (['general', 'network_security', 'application_security', 'cloud_security', 'soc', 'pentest', 'malware', 'incident_response', 'governance'] as $d)
                                        <option value="{{ $d }}" @selected($product->ai_domain === $d)>
                                            {{ str_replace('_', ' ', ucwords($d, '_')) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">AI Level</label>
                                <select name="ai_level" class="form-select">
                                    @foreach (['all', 'beginner', 'intermediate', 'advanced'] as $l)
                                        <option value="{{ $l }}" @selected($product->ai_level === $l)>
                                            {{ ucfirst($l) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">AI Priority</label>
                                <input type="number" name="ai_priority" class="form-control"
                                    value="{{ $product->ai_priority }}" min="0">
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_ai_visible" value="1"
                                    @checked($product->is_ai_visible)>
                                <label class="form-check-label">Tampilkan di AI</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_ai_recommended" value="1"
                                    @checked($product->is_ai_recommended)>
                                <label class="form-check-label">AI Recommended</label>
                            </div>

                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header fw-semibold">Call To Action</div>
                        <div class="card-body">

                            <input type="text" name="cta_label" class="form-control mb-2"
                                value="{{ $product->cta_label }}">

                            <input type="text" name="cta_url" class="form-control mb-2"
                                value="{{ $product->cta_url }}">

                            <select name="cta_type" class="form-select">
                                @foreach (['external', 'internal', 'whatsapp', 'form'] as $c)
                                    <option value="{{ $c }}" @selected($product->cta_type === $c)>
                                        {{ ucfirst($c) }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header fw-semibold">Thumbnail Produk</div>
                        <div class="card-body text-center">

                            <input type="file" name="thumbnail" class="form-control mb-3" accept="image/*"
                                onchange="previewThumbnail(this)">

                            <img id="thumb-preview"
                                src="{{ $product->thumbnail ? asset('storage/' . $product->thumbnail) : '' }}"
                                class="img-fluid rounded {{ $product->thumbnail ? '' : 'd-none' }}"
                                style="max-height:180px">

                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header fw-semibold d-flex justify-content-between">
                            <span>Galeri Produk</span>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-image">
                                <i class="ri ri-add-line"></i>
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="gallery-wrapper" class="row g-3">
                                @foreach ($product->images as $img)
                                    <div class="col-6">
                                        <div class="gallery-item">
                                            <button type="button" class="gallery-remove"
                                                onclick="this.closest('.col-6').remove()">&times;</button>
                                            <input type="hidden" name="old_images[]" value="{{ $img->id }}">
                                            <img src="{{ asset('storage/' . $img->image_path) }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <div class="mt-4 text-end">
                <button type="button" class="btn btn-primary px-4" onclick="updateProduct()">
                    Update Produk
                </button>
            </div>

        </form>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        })

        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Tulis detail produk di sini...'
        })

        const previewThumbnail = input => {
            const img = document.getElementById('thumb-preview')
            img.src = URL.createObjectURL(input.files[0])
            img.classList.remove('d-none')
        }

        document.getElementById('btn-add-image').onclick = () => {
            const col = document.createElement('div')
            col.className = 'col-6'
            col.innerHTML = `
        <div class="gallery-item">
            <button type="button" class="gallery-remove"
                onclick="this.closest('.col-6').remove()">&times;</button>
            <input type="file" name="images[]" class="form-control" accept="image/*"
                onchange="previewGalleryImage(this)">
            <img class="d-none">
        </div>
    `
            document.getElementById('gallery-wrapper').appendChild(col)
        }

        window.previewGalleryImage = input => {
            const img = input.nextElementSibling
            img.src = URL.createObjectURL(input.files[0])
            img.classList.remove('d-none')
        }

        const updateProduct = async () => {
            const form = document.getElementById('form')
            const data = new FormData(form)

            data.append('content', quill.root.innerHTML)

            ;
            ['ai_keywords', 'ai_intents', 'ai_use_cases', 'seo_keywords'].forEach(field => {
                const el = form[field]
                data.set(field, JSON.stringify(
                    (el?.value || '').split(',').map(v => v.trim()).filter(Boolean)
                ))
            })

            try {
                Swal.fire({
                    title: 'Mengupdate Produk...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                })

                await axios.post('/api/products/{{ $product->id }}', data)

                Swal.close()

                Toast.fire({
                    icon: 'success',
                    title: 'Produk berhasil diperbarui'
                })

                setTimeout(() => {
                    window.location.href = '/products'
                }, 1200)

            } catch (err) {
                Swal.close()

                let msg = 'Gagal memperbarui produk'
                if (err.response?.data?.message) msg = err.response.data.message

                Swal.fire({
                    icon: 'error',
                    title: 'Terjadi Kesalahan',
                    text: msg
                })
            }
        }
    </script>
@endpush
