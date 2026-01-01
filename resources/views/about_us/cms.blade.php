@extends('layouts.app')
@section('title', 'Tentang Kami')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="mb-8">
            <h2 class="text-xl font-semibold">Tentang Kami</h2>
            <p class="text-sm text-slate-500">
                Kelola halaman Tentang Kami
            </p>
        </div>

        <form id="about-form" class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-8 space-y-6">

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Konten Utama Tentang Kami
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Headline</label>
                        <input type="text" name="headline" value="{{ old('headline', $aboutPage->headline ?? '') }}"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="Headline utama">
                    </div>

                    <div class="mt-5">
                        <label class="block mb-2 text-sm font-medium">Konten Kiri</label>
                        <textarea id="left_content" name="left_content"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            rows="6">{{ old('left_content', $aboutPage->left_content ?? '') }}</textarea>
                    </div>

                    <div class="mt-5">
                        <label class="block mb-2 text-sm font-medium">Konten Kanan</label>
                        <textarea id="right_content" name="right_content"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            rows="6">{{ old('right_content', $aboutPage->right_content ?? '') }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-4 space-y-6">
                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Topik Bahasan
                    </h3>
                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Topik Bahasan</label>
                        <textarea name="topics" rows="4"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="Satu baris satu topik">{{ old('topics', is_array($aboutPage->topics) ? implode("\n", $aboutPage->topics) : $aboutPage->topics ?? '') }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Manifesto</label>
                        <textarea name="manifesto" rows="4"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="Satu baris satu manifesto">{{ old('manifesto', is_array($aboutPage->manifesto) ? implode("\n", $aboutPage->manifesto) : $aboutPage->manifesto ?? '') }}</textarea>
                    </div>

                    <div class="form-check form-switch mt-4">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                            {{ old('is_active', $aboutPage->is_active ?? 0) ? 'checked' : '' }}>
                        <label class="form-check-label">Aktifkan halaman About Us</label>
                    </div>
                </div>
                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        SEO & Meta
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">SEO Title</label>
                        <input type="text" name="seo_title" value="{{ old('seo_title', $aboutPage->seo_title ?? '') }}"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="SEO title">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">SEO Description</label>
                        <textarea name="seo_description" rows="3"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="SEO description">{{ old('seo_description', $aboutPage->seo_description ?? '') }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">SEO Keywords</label>
                        <textarea name="seo_keywords" rows="3"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="keyword1, keyword2">{{ old('seo_keywords', is_array($aboutPage->seo_keywords) ? implode(',', $aboutPage->seo_keywords) : $aboutPage->seo_keywords ?? '') }}</textarea>
                        <p class="text-sm text-gray-500 mt-1">
                            Pisahkan dengan koma
                        </p>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">OG Title</label>
                        <input type="text" name="og_title" value="{{ old('og_title', $aboutPage->og_title ?? '') }}"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="OG title">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">OG Description</label>
                        <textarea name="og_description" rows="3"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="OG description">{{ old('og_description', $aboutPage->og_description ?? '') }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Canonical URL</label>
                        <input type="text" name="canonical_url"
                            value="{{ old('canonical_url', $aboutPage->canonical_url ?? '') }}"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="https://domain.com/about-us">
                    </div>
                    <button type="submit" id="btn-save"
                        class="px-6 py-2.5 text-sm font-semibold text-white mt-5 rounded-md bg-primary hover:bg-primary/90 flex items-center gap-2">

                        <span class="btn-text">Simpan Produk</span>

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
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute(
            'content');

        tinymce.init({
            selector: '#left_content,#right_content',
            plugins: 'advlist autolink lists link image charmap preview anchor searchreplace visualblocks code fullscreen insertdatetime table help wordcount',
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | removeformat | help',
            menubar: 'file edit view insert format tools help',
            height: 500,
            skin: 'oxide',
            content_css: 'default',
            automatic_uploads: true,
            images_upload_handler: function(blobInfo) {
                return new Promise((resolve, reject) => {
                    const fd = new FormData();
                    fd.append('file', blobInfo.blob());
                    axios.post('/upload-image', fd)
                        .then(res => {
                            if (res.data.location) resolve(res.data.location);
                            else reject('Upload gagal');
                        })
                        .catch(() => reject('Upload error'));
                });
            }
        });

        document.getElementById('btn-save')?.addEventListener('click', async (event) => {
            const btn = event.currentTarget;
            const spinner = document.getElementById('btn-spinner');
            const btnText = btn.querySelector('.btn-text');

            if (btn) btn.disabled = true;
            if (spinner) spinner.classList.remove('hidden');
            if (btnText) btnText.classList.add('hidden');

            const form = document.getElementById('about-form');
            if (!form) return;

            const fd = new FormData(form);

            fd.set('left_content', tinymce.get('left_content')?.getContent() || '');
            fd.set('right_content', tinymce.get('right_content')?.getContent() || '');
            fd.set('topics', JSON.stringify(
                (form.topics?.value || '').split('\n').map(v => v.trim()).filter(Boolean)
            ));
            fd.set('manifesto', JSON.stringify(
                (form.manifesto?.value || '').split('\n').map(v => v.trim()).filter(Boolean)
            ));

            if (!fd.has('is_active')) fd.append('is_active', 0);

            try {
                const res = await axios.post('/api/about-us', fd);
                showToast('success', 'Berhasil', res.data.message || 'Berhasil disimpan');
            } catch (e) {
                if (e.response?.status === 422) {
                    const errors = Object.values(e.response.data.errors || {}).flat().join(' | ');
                    showToast('danger', errors || 'Validasi gagal');
                } else {
                    showToast('danger', 'Gagal menyimpan data');
                }
            }

            if (btn) btn.disabled = false;
            if (spinner) spinner.classList.add('hidden');
            if (btnText) btnText.classList.remove('hidden');
        });
    </script>
@endpush
