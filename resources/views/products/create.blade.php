@extends('layouts.app')
@section('title', 'Tambah Produk')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="mb-8">
            <h2 class="text-xl font-semibold">Tambah Produk</h2>
            <p class="text-sm text-slate-500">
                Buat dan kelola Produk
            </p>
        </div>

        <form id="product-form" class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-8 space-y-6">

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Product Content
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Nama Produk</label>
                        <input type="text" name="product_name"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Masukkan Nama Produk">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Sub Judul</label>
                        <input type="text" name="subtitle"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Masukkan Sub Judul">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Ringkasan Produk</label>
                        <textarea rows="3" name="summary"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Masukkan Ringkasan Produk"></textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Konten Detail</label>
                        <textarea id="content-editor" name="content"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Masukkan Konten Detail"></textarea>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5 mt-4">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        AI Metadata
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">AI Keywords</label>
                        <textarea name="ai_keywords" rows="2"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="contoh: firewall, network monitoring, intrusion detection"></textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">AI Intents</label>
                        <textarea name="ai_intents" rows="2"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="contoh: protect network, detect malware, monitoring traffic"></textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">AI Use Cases</label>
                        <textarea name="ai_use_cases" rows="2"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="contoh: SOC monitoring, enterprise security, incident response"></textarea>
                    </div>
                </div>

            </div>
            <div class="col-span-12 lg:col-span-4 space-y-6">
                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5 mb-4">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Pengaturan Produk
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Tipe Produk</label>
                        <select name="product_type"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-select">
                            <option value="digital">Digital</option>
                            <option value="hardware">Hardware</option>
                            <option value="service">Service</option>
                            <option value="bundle">Bundle</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">AI Domain</label>
                        <select name="ai_domain"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-select">
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

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">AI Level</label>
                        <select name="ai_level"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-select">
                            <option value="all">All</option>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">AI Priority</label>
                        <input type="number" name="ai_priority" min="0" value="0"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-control">
                    </div>

                    <div class="mt-3 flex items-center gap-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_ai_visible" value="1" checked class="form-check-input">
                            <span class="ml-2 text-sm text-slate-700">Tampilkan di AI</span>
                        </label>

                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_ai_recommended" value="1" checked
                                class="form-check-input">
                            <span class="ml-2 text-sm text-slate-700">AI Recommended</span>
                        </label>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5 mb-4">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Call To Action
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">CTA Label</label>
                        <input type="text" name="cta_label"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-control"
                            placeholder="CTA Label">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">CTA URL</label>
                        <input type="text" name="cta_url"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-control"
                            placeholder="CTA URL">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">CTA Type</label>
                        <select name="cta_type"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-select">
                            <option value="external">External</option>
                            <option value="internal">Internal</option>
                            <option value="whatsapp">WhatsApp</option>
                            <option value="form">Form</option>
                        </select>
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5 mb-4 text-center">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Thumbnail Produk
                    </h3>

                    <div class="mt-3">
                        <input type="file" name="thumbnail" accept="image/*"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            onchange="previewThumbnail(this)">
                    </div>

                    <div class="mt-3">
                        <img id="thumb-preview" class="img-fluid rounded d-none" style="max-height:180px">
                    </div>
                </div>

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5 mt-4">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        SEO Produk
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">SEO Title</label>
                        <input type="text" name="seo_title"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="SEO Title">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">SEO Description</label>
                        <textarea name="seo_description" rows="2"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="SEO Description"></textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Canonical URL</label>
                        <input type="text" name="canonical_url"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Canonical URL">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">SEO Keywords</label>
                        <textarea name="seo_keywords" rows="2"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="SEO Keywords (pisahkan dengan koma)"></textarea>
                    </div>
                    <div class="pt-6 flex justify-end mt-3">
                        <button type="submit" id="submit-btn"
                            class="px-6 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90 flex items-center gap-2">

                            <span class="btn-text">Simpan Produk</span>

                            <svg id="btn-spinner" class="hidden w-4 h-4 animate-spin" viewBox="0 0 24 24">
                                <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                                    fill="none" opacity="0.25" />
                                <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" />
                            </svg>

                        </button>
                    </div>
                </div>


            </div>

        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        tinymce.init({
            selector: '#content-editor',
            height: 500,

            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image',
                'charmap', 'preview', 'anchor', 'searchreplace',
                'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'table', 'help', 'wordcount'
            ],

            toolbar: `
            undo redo |
            formatselect |
            bold italic underline backcolor |
            alignleft aligncenter alignright alignjustify |
            bullist numlist outdent indent |
            link image |
            removeformat | help
        `,

            automatic_uploads: true,

            /* 🔥 INI KUNCI UTAMA */
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,

            images_upload_handler: function(blobInfo) {
                return new Promise((resolve, reject) => {
                    const fd = new FormData()
                    fd.append('file', blobInfo.blob())

                    axios.post('/upload-image', fd)
                        .then(res => {
                            if (res.data.location) {
                                resolve(res.data.location)
                            } else {
                                reject('Upload gagal')
                            }
                        })
                        .catch(() => reject('Upload error'))
                })
            }
        });

        const previewThumbnail = input => {
            const img = document.getElementById('thumb-preview');
            img.src = URL.createObjectURL(input.files[0]);
            img.classList.remove('d-none');
        }

        document.getElementById('product-form').addEventListener('submit', async e => {
            e.preventDefault();
            tinymce.triggerSave();

            const btn = document.getElementById('submit-btn');
            const spinner = document.getElementById('btn-spinner');
            const btnText = btn.querySelector('.btn-text');

            btn.disabled = true;
            spinner.classList.remove('hidden');
            btnText.classList.add('hidden');

            try {
                const form = document.getElementById('product-form');
                const fd = new FormData(form);

                fd.set('content', tinymce.get('content-editor').getContent());

                // AI Metadata
                ['ai_keywords', 'ai_intents', 'ai_use_cases'].forEach(name => {
                    const el = form.querySelector(`[name="${name}"]`);
                    if (el && el.value.trim() !== '') {
                        el.value
                            .split(',')
                            .map(v => v.trim())
                            .filter(Boolean)
                            .forEach(v => fd.append(`${name}[]`, v));
                    }
                });

                // SEO Keywords
                const seoKeywords = form.querySelector('[name="seo_keywords"]');
                if (seoKeywords && seoKeywords.value.trim() !== '') {
                    seoKeywords.value
                        .split(',')
                        .map(v => v.trim())
                        .filter(Boolean)
                        .forEach(v => fd.append('seo_keywords[]', v));
                }

                // Submit ke endpoint store produk
                const res = await axios.post('/products/store', fd);

                if (res.data.status === true) {
                    showToast(
                        'success',
                        'Berhasil',
                        'Produk berhasil disimpan.'
                    );

                    setTimeout(() => {
                        if (res.data.redirect) window.location.href = res.data.redirect;
                    }, 1200);
                }

            } catch (err) {
                if (err.response?.status === 422) {
                    const msg = Object.values(err.response.data.errors || {}).flat().join('<br>');
                    showToast('error', 'Validasi Gagal', msg);
                } else {
                    showToast('error', 'Error', 'Terjadi kesalahan sistem');
                }
            }

            btn.disabled = false;
            spinner.classList.add('hidden');
            btnText.classList.remove('hidden');
        });
    </script>
@endpush
