@extends('layouts.app')
@section('title', 'Edit Produk')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="mb-8">
            <h2 class="text-xl font-semibold">Tambah Produk</h2>
            <p class="text-sm text-slate-500">
                Buat dan kelola Produk
            </p>
        </div>

        <form id="product-form" class="grid grid-cols-12 gap-6" method="POST"
            action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="col-span-12 lg:col-span-8 space-y-6">

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Product Content
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Nama Produk</label>
                        <input type="text" name="product_name" value="{{ old('product_name', $product->name) }}"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Masukkan Nama Produk">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Sub Judul</label>
                        <input type="text" name="subtitle" value="{{ old('subtitle', $product->subtitle) }}"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Masukkan Sub Judul">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Ringkasan Produk</label>
                        <textarea rows="3" name="summary"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Masukkan Ringkasan Produk">{{ old('summary', $product->summary) }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Konten Detail</label>
                        <textarea id="content-editor" name="content"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Masukkan Konten Detail">{{ old('content', $product->content) }}</textarea>
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
                            placeholder="contoh: firewall, network monitoring, intrusion detection">{{ is_array(old('ai_keywords', $product->ai_keywords))
                                ? implode("\n", old('ai_keywords', $product->ai_keywords))
                                : old('ai_keywords', $product->ai_keywords) }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">AI Intents</label>
                        <textarea name="ai_intents" rows="2"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="contoh: protect network, detect malware, monitoring traffic">{{ is_array(old('ai_intents', $product->ai_intents))
                                ? implode("\n", old('ai_intents', $product->ai_intents))
                                : old('ai_intents', $product->ai_intents) }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">AI Use Cases</label>
                        <textarea name="ai_use_cases" rows="2"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="contoh: SOC monitoring, enterprise security, incident response">{{ is_array(old('ai_use_cases', $product->ai_use_cases))
                                ? implode(', ', old('ai_use_cases', $product->ai_use_cases))
                                : old('ai_use_cases', $product->ai_use_cases) }}</textarea>
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
                            <option value="digital" {{ $product->product_type == 'digital' ? 'selected' : '' }}>Digital
                            </option>
                            <option value="hardware" {{ $product->product_type == 'hardware' ? 'selected' : '' }}>Hardware
                            </option>
                            <option value="service" {{ $product->product_type == 'service' ? 'selected' : '' }}>Service
                            </option>
                            <option value="bundle" {{ $product->product_type == 'bundle' ? 'selected' : '' }}>Bundle
                            </option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">AI Domain</label>
                        <select name="ai_domain"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-select">
                            <option value="general" {{ $product->ai_domain == 'general' ? 'selected' : '' }}>General
                            </option>
                            <option value="network_security"
                                {{ $product->ai_domain == 'network_security' ? 'selected' : '' }}>Network Security</option>
                            <option value="application_security"
                                {{ $product->ai_domain == 'application_security' ? 'selected' : '' }}>Application Security
                            </option>
                            <option value="cloud_security" {{ $product->ai_domain == 'cloud_security' ? 'selected' : '' }}>
                                Cloud Security</option>
                            <option value="soc" {{ $product->ai_domain == 'soc' ? 'selected' : '' }}>SOC</option>
                            <option value="pentest" {{ $product->ai_domain == 'pentest' ? 'selected' : '' }}>Pentest
                            </option>
                            <option value="malware" {{ $product->ai_domain == 'malware' ? 'selected' : '' }}>Malware
                            </option>
                            <option value="incident_response"
                                {{ $product->ai_domain == 'incident_response' ? 'selected' : '' }}>Incident Response
                            </option>
                            <option value="governance" {{ $product->ai_domain == 'governance' ? 'selected' : '' }}>
                                Governance
                            </option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">AI Level</label>
                        <select name="ai_level"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-select">
                            <option value="all" {{ $product->ai_level == 'all' ? 'selected' : '' }}>All</option>
                            <option value="beginner" {{ $product->ai_level == 'beginner' ? 'selected' : '' }}>Beginner
                            </option>
                            <option value="intermediate" {{ $product->ai_level == 'intermediate' ? 'selected' : '' }}>
                                Intermediate</option>
                            <option value="advanced" {{ $product->ai_level == 'advanced' ? 'selected' : '' }}>Advanced
                            </option>
                        </select>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">AI Priority</label>
                        <input type="number" name="ai_priority" min="0"
                            value="{{ old('ai_priority', $product->ai_priority) }}"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-control">
                    </div>

                    <div class="mt-3 flex items-center gap-4">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_ai_visible" value="1"
                                {{ $product->is_ai_visible ? 'checked' : '' }} class="form-check-input">
                            <span class="ml-2 text-sm text-slate-700">Tampilkan di AI</span>
                        </label>

                        <label class="inline-flex items-center">
                            <input type="checkbox" name="is_ai_recommended" value="1"
                                {{ $product->is_ai_recommended ? 'checked' : '' }} class="form-check-input">
                            <span class="ml-2 text-sm text-slate-700">AI Recommended</span>
                        </label>
                    </div>
                </div>

                <!-- CTA Section -->
                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5 mb-4">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Call To Action
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">CTA Label</label>
                        <input type="text" name="cta_label" value="{{ old('cta_label', $product->cta_label) }}"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-control"
                            placeholder="CTA Label">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">CTA URL</label>
                        <input type="text" name="cta_url" value="{{ old('cta_url', $product->cta_url) }}"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-control"
                            placeholder="CTA URL">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">CTA Type</label>
                        <select name="cta_type"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary form-select">
                            <option value="external" {{ $product->cta_type == 'external' ? 'selected' : '' }}>External
                            </option>
                            <option value="internal" {{ $product->cta_type == 'internal' ? 'selected' : '' }}>Internal
                            </option>
                            <option value="whatsapp" {{ $product->cta_type == 'whatsapp' ? 'selected' : '' }}>WhatsApp
                            </option>
                            <option value="form" {{ $product->cta_type == 'form' ? 'selected' : '' }}>Form</option>
                        </select>
                    </div>
                </div>

                <!-- Thumbnail Section -->
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
                        @if ($product->thumbnail)
                            <img id="thumb-preview" src="{{ $product->thumbnail }}"
                                class="img-fluid rounded" style="max-height:180px">
                        @else
                            <img id="thumb-preview" class="img-fluid rounded d-none" style="max-height:180px">
                        @endif
                    </div>
                </div>

                <!-- SEO Section -->
                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5 mt-4">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        SEO Produk
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">SEO Title</label>
                        <input type="text" name="seo_title" value="{{ old('seo_title', $product->seo_title) }}"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="SEO Title">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">SEO Description</label>
                        <textarea name="seo_description" rows="2"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="SEO Description">{{ old('seo_description', $product->seo_description) }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Canonical URL</label>
                        <input type="text" name="canonical_url"
                            value="{{ old('canonical_url', $product->canonical_url) }}"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Canonical URL">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">SEO Keywords</label>
                        <textarea name="seo_keywords" rows="2"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="SEO Keywords (pisahkan dengan koma)">{{ is_array(old('seo_keywords', $product->seo_keywords))
                                ? implode(', ', old('seo_keywords', $product->seo_keywords))
                                : old('seo_keywords', $product->seo_keywords) }}
</textarea>
                    </div>
                </div>


                <div class="pt-6 flex justify-end mt-3">
                    <button type="submit" id="submit-btn"
                        class="px-6 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90 flex items-center gap-2">
                        <span class="btn-text">Simpan Perubahan</span>
                        <svg id="btn-spinner" class="hidden w-4 h-4 animate-spin" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                                fill="none" opacity="0.25" />
                            <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" />
                        </svg>
                    </button>
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

                // Submit ke endpoint update produk (sesuaikan route dengan ID produk)
                const res = await axios.post(`/products/{{ $product->id }}/update`, fd);

                if (res.data.status === true) {
                    showToast('success', 'Berhasil', 'Produk berhasil disimpan.');
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

        // Preload thumbnail jika ada
        window.addEventListener('DOMContentLoaded', () => {
            const thumbPreview = document.getElementById('thumb-preview');
            @if ($product->thumbnail)
                thumbPreview.src = "{{ asset('storage/' . $product->thumbnail) }}";
                thumbPreview.classList.remove('d-none');
            @endif
        });
    </script>
@endpush
