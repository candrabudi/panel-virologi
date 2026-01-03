@extends('layouts.app')
@section('title', 'Edit Layanan')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="mb-8">
            <h2 class="text-xl font-semibold">Edit Layanan</h2>
            <p class="text-sm text-slate-500">
                Edit dan kelola Layanan
            </p>
        </div>

        <form id="service-form" class="grid grid-cols-12 gap-6">
            <input type="hidden" name="id" value="{{ $cyberSecurityService->id }}">

            <div class="col-span-12 lg:col-span-8 space-y-6">

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Cyber Security Service Content
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Nama Service</label>
                        <input type="text" name="name" value="{{ old('name', $cyberSecurityService->name) }}"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Masukkan Nama Service">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Short Name</label>
                        <input type="text" name="short_name"
                            value="{{ old('short_name', $cyberSecurityService->short_name) }}"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Masukkan Short Name">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Ringkasan Service</label>
                        <textarea rows="3" name="summary"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Masukkan Ringkasan Service">{{ old('summary', is_array($cyberSecurityService->summary) ? implode(',', $cyberSecurityService->summary) : $cyberSecurityService->summary) }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Deskripsi Lengkap</label>
                        <textarea id="content-editor" name="description" rows="10"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Masukkan Deskripsi Lengkap Service">{{ old('description', $cyberSecurityService->description) }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-span-12 lg:col-span-4 space-y-6">
                <div class="bg-white mt-3 rounded-lg border border-slate-200 p-6 space-y-6 p-5 mt-4">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Service Details
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Thumbnail</label>

                        @if ($cyberSecurityService->thumbnail)
                            <img src="{{ asset('storage/' . $cyberSecurityService->thumbnail) }}"
                                class="w-full h-40 object-cover rounded-md border mb-2">
                        @endif

                        <input type="file" name="thumbnail" accept="image/*"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md focus:ring-4 focus:ring-primary focus:border-primary form-control">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Service Scope</label>
                        <textarea name="service_scope" rows="3"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="contoh: vulnerability assessment, monitoring, response">{{ old('service_scope', implode(',', $cyberSecurityService->service_scope ?? [])) }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Deliverables</label>
                        <textarea name="deliverables" rows="3"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="contoh: laporan, dashboard, rekomendasi">{{ old('deliverables', implode(',', $cyberSecurityService->deliverables ?? [])) }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Target Audience</label>
                        <textarea name="target_audience" rows="3"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="contoh: enterprise, startup, government">{{ old('target_audience', implode(',', $cyberSecurityService->target_audience ?? [])) }}</textarea>
                    </div>
                </div>


                <div class="bg-white mt-3 rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        AI Configuration
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">AI Keywords</label>
                        <textarea name="ai_keywords" rows="3"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="contoh: ransomware, soc, pentest, cloud security">{{ old('ai_keywords', implode(',', $cyberSecurityService->ai_keywords ?? [])) }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">
                            Pisahkan dengan koma. Digunakan untuk AI Agent & rekomendasi.
                        </p>
                    </div>
                </div>

                {{-- METADATA --}}
                <div class="bg-white mt-3 rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Metadata
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Kategori</label>
                        <select name="category"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 form-select">
                            @foreach (['soc', 'pentest', 'audit', 'incident_response', 'cloud_security', 'governance', 'training', 'consulting'] as $c)
                                <option value="{{ $c }}"
                                    {{ old('category', $cyberSecurityService->category ?? '') === $c ? 'selected' : '' }}>
                                    {{ strtoupper(str_replace('_', ' ', $c)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- CTA --}}
                <div class="bg-white mt-3 rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Call To Action
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">CTA Label</label>
                        <input type="text" name="cta_label"
                            value="{{ old('cta_label', $cyberSecurityService->cta_label ?? 'Hubungi Kami') }}"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 form-control">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">CTA URL</label>
                        <input type="text" name="cta_url"
                            value="{{ old('cta_url', $cyberSecurityService->cta_url ?? '') }}"
                            placeholder="https://example.com/contact"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 form-control">
                    </div>
                </div>


                <div class="pt-6 flex justify-end mt-3">
                    <button type="submit" id="submit-btn"
                        class="px-6 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90 flex items-center gap-2">

                        <span class="btn-text">Simpan Layanan</span>

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

        document.getElementById('service-form').addEventListener('submit', async e => {
            e.preventDefault();
            tinymce.triggerSave();

            const btn = document.getElementById('submit-btn');
            const spinner = document.getElementById('btn-spinner');
            const btnText = btn.querySelector('.btn-text');

            btn.disabled = true;
            spinner.classList.remove('hidden');
            btnText.classList.add('hidden');

            try {
                const form = document.getElementById('service-form');
                const fd = new FormData(form);

                // TinyMCE content
                fd.set('description', tinymce.get('content-editor').getContent());

                // AI Metadata dan service arrays
                ['ai_keywords', 'service_scope', 'deliverables', 'target_audience'].forEach(name => {
                    const el = form.querySelector(`[name="${name}"]`);
                    if (el && el.value.trim() !== '') {
                        el.value
                            .split(',')
                            .map(v => v.trim())
                            .filter(Boolean)
                            .forEach(v => fd.append(`${name}[]`, v));
                    }
                });

                // Ambil ID service dari hidden input
                const serviceId = form.querySelector('[name="id"]').value;

                // Submit ke endpoint update CyberSecurityService
                const res = await axios.post(`/cyber-security-services/${serviceId}/update`, fd, {
                    headers: {
                        'X-HTTP-Method-Override': 'PUT'
                    } // Laravel bisa menerima PUT via POST
                });

                if (res.data.status === true) {
                    showToast(
                        'success',
                        'Berhasil',
                        res.data.message || 'Service berhasil diperbarui.'
                    );

                    setTimeout(() => {
                        if (res.data.redirect) window.location.href = res.data.redirect;
                        else window.location.href = '/cyber-security-services';
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
