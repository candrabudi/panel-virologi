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
                        <div class="card-header fw-semibold">Informasi Produk</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Nama Produk</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Subtitle</label>
                                <input type="text" name="subtitle" class="form-control">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Ringkasan Produk</label>
                                <textarea name="summary" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Konten Detail Produk</label>
                                <div id="editor" style="min-height:220px"></div>
                            </div>

                        </div>
                    </div>

                    <!-- AI METADATA -->
                    <div class="card mt-4">
                        <div class="card-header fw-semibold">AI Metadata</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">AI Keywords</label>
                                <textarea name="ai_keywords" class="form-control" rows="2"
                                    placeholder="contoh: firewall, network monitoring, intrusion detection"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">AI Intents</label>
                                <textarea name="ai_intents" class="form-control" rows="2"
                                    placeholder="contoh: protect network, detect malware, monitoring traffic"></textarea>
                            </div>

                            <div>
                                <label class="form-label">AI Use Cases</label>
                                <textarea name="ai_use_cases" class="form-control" rows="2"
                                    placeholder="contoh: SOC monitoring, enterprise security, incident response"></textarea>
                            </div>

                        </div>
                    </div>

                    <!-- SEO -->
                    <div class="card mt-4">
                        <div class="card-header fw-semibold">SEO Produk</div>
                        <div class="card-body">

                            <input type="text" name="seo_title" class="form-control mb-3" placeholder="SEO Title">

                            <textarea name="seo_description" class="form-control mb-3" rows="2" placeholder="SEO Description"></textarea>

                            <input type="text" name="canonical_url" class="form-control mb-3"
                                placeholder="Canonical URL">

                            <textarea name="seo_keywords" class="form-control" rows="2" placeholder="SEO Keywords (pisahkan dengan koma)"></textarea>

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
                                <select name="product_type" class="form-select" required>
                                    <option value="digital">Digital</option>
                                    <option value="hardware">Hardware</option>
                                    <option value="service">Service</option>
                                    <option value="bundle">Bundle</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">AI Domain</label>
                                <select name="ai_domain" class="form-select">
                                    <option value="general">General</option>
                                    <option value="network_security">Network Security</option>
                                    <option value="application_security">Application Security</option>
                                    <option value="cloud_security">Cloud Security</option>
                                    <option value="soc">SOC</option>
                                    <option value="pentest">Pentest</option>
                                    <option value="malware">Malware</option>
                                    <option value="incident_response">Incident Response</option>
                                    <option value="governance">Governance</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">AI Level</label>
                                <select name="ai_level" class="form-select">
                                    <option value="all">All</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">AI Priority</label>
                                <input type="number" name="ai_priority" class="form-control" value="0"
                                    min="0">
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" name="is_ai_visible" value="1"
                                    checked>
                                <label class="form-check-label">Tampilkan di AI</label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_ai_recommended" value="1"
                                    checked>
                                <label class="form-check-label">AI Recommended</label>
                            </div>

                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header fw-semibold">Call To Action</div>
                        <div class="card-body">

                            <input type="text" name="cta_label" class="form-control mb-2" placeholder="CTA Label">

                            <input type="text" name="cta_url" class="form-control mb-2" placeholder="CTA URL">

                            <select name="cta_type" class="form-select">
                                <option value="external">External</option>
                                <option value="internal">Internal</option>
                                <option value="whatsapp">WhatsApp</option>
                                <option value="form">Form</option>
                            </select>

                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header fw-semibold">Thumbnail Produk</div>
                        <div class="card-body text-center">

                            <input type="file" name="thumbnail" class="form-control mb-3" accept="image/*"
                                onchange="previewThumbnail(this)">

                            <img id="thumb-preview" class="img-fluid rounded d-none" style="max-height:180px">

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
                            <div id="gallery-wrapper" class="row g-3"></div>
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

        const saveProduct = async () => {
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
                    title: 'Menyimpan Produk...',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                })

                await axios.post('/api/products', data)

                Swal.close()

                Toast.fire({
                    icon: 'success',
                    title: 'Produk berhasil disimpan'
                })

                setTimeout(() => {
                    window.location.href = '/products'
                }, 1200)

            } catch (err) {
                Swal.close()

                let msg = 'Gagal menyimpan produk'
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
