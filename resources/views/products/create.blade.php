@extends('template.app')

@section('title', 'Tambah Produk')

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
            <h4 class="page-main-title m-0">Tambah Produk</h4>
            <a href="/products" class="btn btn-light">← Kembali</a>
        </div>

        <form id="form" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">

                <!-- KONTEN -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-header fw-semibold">
                            Informasi Produk
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="name" class="form-control" placeholder="Nama produk">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi Singkat</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Deskripsi singkat produk"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konten Detail Produk</label>
                                <div id="editor" style="min-height:220px"></div>
                                <small class="text-muted">
                                    Konten utama yang akan tampil di halaman detail produk
                                </small>
                            </div>

                        </div>
                    </div>

                    <div class="card mt-4">
                        <div class="card-header fw-semibold">
                            SEO Produk
                        </div>
                        <div class="card-body">

                            <input type="text" name="seo_title" class="form-control mb-3" placeholder="SEO Title">

                            <textarea name="seo_description" class="form-control mb-3" rows="2" placeholder="SEO Description"></textarea>

                            <textarea name="seo_keywords" class="form-control" rows="2" placeholder="SEO Keywords"></textarea>

                        </div>
                    </div>
                </div>

                <!-- MEDIA -->
                <div class="col-lg-4">

                    <!-- THUMBNAIL -->
                    <div class="card mb-4">
                        <div class="card-header fw-semibold">
                            Thumbnail Produk
                        </div>
                        <div class="card-body text-center">

                            <input type="file" name="thumbnail" accept="image/*" class="form-control mb-3"
                                onchange="previewThumbnail(this)">

                            <img id="thumb-preview" class="img-fluid rounded d-none" style="max-height:180px">

                            <small class="text-muted d-block mt-2">
                                Gambar utama (1 gambar)
                            </small>

                        </div>
                    </div>

                    <!-- GALERI -->
                    <div class="card">
                        <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                            <span>Galeri Produk</span>
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-image">
                                <i class="ri ri-add-line"></i> Tambah Gambar
                            </button>
                        </div>

                        <div class="card-body">
                            <div id="gallery-wrapper" class="row g-3"></div>

                            <small class="text-muted d-block mt-2">
                                Gambar pendukung produk (bisa lebih dari satu)
                            </small>
                        </div>
                    </div>

                </div>

            </div>

            <div class="mt-4 text-end">
                <button type="button" class="btn btn-primary px-4" onclick="saveProduct()">
                    Simpan Produk
                </button>
            </div>

        </form>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.7/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        const quill = new Quill('#editor', {
            theme: 'snow',
            placeholder: 'Tulis detail produk di sini...',
            modules: {
                toolbar: [
                    ['bold', 'italic', 'underline'],
                    [{
                        list: 'ordered'
                    }, {
                        list: 'bullet'
                    }],
                    ['link', 'code-block'],
                    ['clean']
                ]
            }
        })

        const previewThumbnail = input => {
            const img = document.getElementById('thumb-preview')
            img.src = URL.createObjectURL(input.files[0])
            img.classList.remove('d-none')
        }

        const galleryWrapper = document.getElementById('gallery-wrapper')
        const btnAddImage = document.getElementById('btn-add-image')

        btnAddImage.addEventListener('click', () => {
            const col = document.createElement('div')
            col.className = 'col-6'

            col.innerHTML = `
                <div class="gallery-item">
                    <button type="button" class="gallery-remove"
                        onclick="this.closest('.col-6').remove()">&times;</button>

                    <input type="file"
                           name="images[]"
                           accept="image/*"
                           class="form-control"
                           onchange="previewGalleryImage(this)">

                    <img class="d-none">
                </div>
            `
            galleryWrapper.appendChild(col)
        })

        window.previewGalleryImage = input => {
            const img = input.nextElementSibling
            img.src = URL.createObjectURL(input.files[0])
            img.classList.remove('d-none')
        }

        const saveProduct = async () => {
            const form = document.getElementById('form')
            const data = new FormData(form)
            data.append('content', quill.root.innerHTML)

            await axios.post('/api/products', data)

            window.location.href = '/products'
        }
    </script>
@endpush
