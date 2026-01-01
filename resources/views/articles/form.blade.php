@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">

        {{-- Header --}}
        <div class="mb-8">
            <h2 class="text-xl font-semibold">Article Editor</h2>
            <p class="text-sm text-slate-500">
                Buat dan kelola konten artikel
            </p>
        </div>

        <form id="article-form" data-id="{{ $article->id ?? '' }}" class="grid grid-cols-12 gap-6">

            {{-- LEFT --}}
            <div class="col-span-12 lg:col-span-8 space-y-6">

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Article Content
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Title</label>
                        <input type="text" name="title"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control"
                            placeholder="Judul artikel" value="{{ $article->title ?? '' }}">
                    </div>

                    {{-- Excerpt --}}
                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Excerpt</label>
                        <textarea name="excerpt" rows="3"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control"
                            placeholder="Ringkasan singkat artikel">{{ $article->excerpt ?? '' }}</textarea>
                    </div>

                    {{-- Content --}}
                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Content</label>
                        <textarea id="content-editor" name="content">{{ $article->content ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            <div class="col-span-12 lg:col-span-4 space-y-6">
                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">

                    <h3 class="text-sm font-semibold text-slate-700 border-b pb-3">
                        Publishing
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Thumbnail</label>
                        <input type="file" name="thumbnail"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control">

                        @if ($article?->thumbnail)
                            <img src="{{ $article->thumbnail }}" class="img-fluid rounded shadow-sm border d-block mx-auto"
                                style="max-height: 200px; object-fit: cover;">
                            <small class="text-muted d-block mt-2">Current Image</small>
                        @endif
                    </div>

                    <div class="mt-3">
                        <p class="text-sm font-semibold text-slate-600 mb-2">Categories</p>
                        <div class="space-y-2 text-sm">
                            @foreach ($categories as $cat)
                                <label class="flex items-center gap-2">
                                    <input type="checkbox" name="categories[]" value="{{ $cat->id }}"
                                        class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer rounded focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&[type='radio']]:checked:bg-primary [&[type='radio']]:checked:border-primary [&[type='radio']]:checked:border-opacity-10 [&[type='checkbox']]:checked:bg-primary [&[type='checkbox']]:checked:border-primary [&[type='checkbox']]:checked:border-opacity-10 [&:disabled:not(:checked)]:bg-slate-100 [&:disabled:not(:checked)]:cursor-not-allowed [&:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&:disabled:checked]:opacity-70 [&:disabled:checked]:cursor-not-allowed [&:disabled:checked]:dark:bg-darkmode-800/50"
                                        @checked($article && $article->categories->contains($cat->id))>
                                    {{ $cat->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <h3 class="text-sm font-semibold text-slate-700 pb-3 mt-5">
                        Status Artikel
                    </h3>
                    <div>
                        <input type="checkbox" name="is_published" value="1"
                            class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&[type='radio']]:checked:bg-primary [&[type='radio']]:checked:border-primary [&[type='radio']]:checked:border-opacity-10 [&[type='checkbox']]:checked:bg-primary [&[type='checkbox']]:checked:border-primary [&[type='checkbox']]:checked:border-opacity-10 [&:disabled:not(:checked)]:bg-slate-100 [&:disabled:not(:checked)]:cursor-not-allowed [&:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&:disabled:checked]:opacity-70 [&:disabled:checked]:cursor-not-allowed [&:disabled:checked]:dark:bg-darkmode-800/50 w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 checked:bg-primary checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white mr-0"
                            @checked($article?->is_published)>
                    </div>

                    <h3 class="text-sm font-semibold text-slate-700 pb-3 mt-5">
                        SEO
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">SEO Title</label>
                        <input type="text" name="seo_title"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control"
                            placeholder="SEO Title (optional)" value="{{ $article->seo_title ?? '' }}">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">SEO Description</label>
                        <textarea name="seo_description" rows="4"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control"
                            placeholder="SEO Description (optional)">{{ $article->seo_description ?? '' }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">SEO Keywords</label>
                        <input type="text" name="seo_keywords"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control"
                            placeholder="keyword1, keyword2, keyword3" value="{{ $article->seo_keywords ?? '' }}">
                    </div>

                    {{-- ACTION --}}
                    <div class="pt-6 border-t flex justify-end mt-3">
                        <button type="submit" id="submit-btn"
                            class="px-6 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90 flex items-center gap-2">

                            <span class="btn-text">Simpan Article</span>

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
            document.querySelector('meta[name="csrf-token"]').getAttribute('content')

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
        })

        const form = document.getElementById('article-form')
        const btn = document.getElementById('submit-btn')
        const spinner = document.getElementById('btn-spinner')
        const btnText = btn.querySelector('.btn-text')

        form.addEventListener('submit', async e => {
            e.preventDefault()
            tinymce.triggerSave()

            btn.disabled = true
            spinner.classList.remove('hidden')
            btnText.classList.add('hidden')

            try {
                const fd = new FormData(form)

                const articleId = form.dataset.id || null
                let url = '/articles'

                if (articleId) {
                    url += '/' + articleId
                    fd.append('_method', 'PUT')
                }

                const res = await axios.post(url, fd)

                if (res.data.status === true) {
                    showToast(
                        'success',
                        'Berhasil',
                        articleId ?
                        'Artikel berhasil diperbarui. Mengalihkan halaman...' :
                        'Artikel berhasil disimpan. Mengalihkan halaman...'
                    )

                    setTimeout(() => {
                        if (res.data.redirect) {
                            window.location.href = res.data.redirect
                        }
                    }, 2000)
                }

            } catch (e) {
                if (e.response?.status === 422) {
                    const msg = Object.values(e.response.data.errors || {})
                        .flat()
                        .join('<br>')
                    showToast('error', 'Validasi Gagal', msg)
                } else {
                    showToast('error', 'Error', 'Terjadi kesalahan sistem')
                }
            }

            btn.disabled = false
            spinner.classList.add('hidden')
            btnText.classList.remove('hidden')
        })
    </script>
@endpush
