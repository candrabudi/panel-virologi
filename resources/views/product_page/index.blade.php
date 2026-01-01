@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="mb-8">
            <h2 class="text-xl font-semibold">Halaman Produk</h2>
            <p class="text-sm text-slate-500">
                Kelola Halaman Produk
            </p>
        </div>

        <form id="page-form" onsubmit="return false" class="grid grid-cols-12 gap-6">
            <div class="col-span-12 lg:col-span-7 space-y-6">

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 pb-3">
                        Section Produk
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Judul Halaman</label>
                        <input type="text" name="page_title"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Contoh: Koleksi Produk Terbaru" value="{{ $page->page_title ?? '' }}">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Sub Judul</label>
                        <input type="text" name="page_subtitle"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Contoh: Pilih produk favoritmu" value="{{ $page->page_subtitle ?? '' }}">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">CTA Text (Tombol Aksi)</label>
                        <input type="text" name="cta_text"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Contoh: Lihat Produk" value="{{ $page->cta_text ?? '/contact' }}">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">CTA URL</label>
                        <input type="text" name="cta_url"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Contoh: /contact atau link produk" value="{{ $page->cta_url ?? '/contact' }}">
                    </div>

                    <h3 class="text-sm font-semibold text-slate-700 pb-3 mt-5">
                        Status Halaman
                    </h3>
                    <div class="flex items-center gap-3">
                        <input type="checkbox" name="is_active" value="1"
                            class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 checked:bg-primary checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white"
                            {{ $page?->is_active ? 'checked' : '' }}>
                        <span class="text-sm text-slate-600 dark:text-slate-400">Aktifkan halaman produk</span>
                    </div>
                </div>

            </div>
            <div class="col-span-12 lg:col-span-5 space-y-6">
                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">

                    <h3 class="text-sm font-semibold text-slate-700 pb-3">
                        SEO
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Judul SEO (untuk mesin pencari)</label>
                        <input type="text" name="seo_title"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control"
                            placeholder="Masukkan judul yang SEO friendly" value="{{ $page->seo_title ?? '' }}">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Deskripsi SEO (ringkas & menarik)</label>
                        <textarea name="seo_description"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 form-control"
                            placeholder="Tulis deskripsi singkat untuk mesin pencari">{{ $page->seo_description ?? '' }}</textarea>
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Kata Kunci SEO (pisahkan dengan koma)</label>
                        <input type="text" name="seo_keywords"
                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control"
                            placeholder="Contoh: tutorial, tips, Laravel" value="{{ $page->seo_keywords ?? '' }}">
                    </div>

                    <div class="pt-6 flex justify-end mt-3">
                        <button type="submit" id="submit-btn"
                            class="px-6 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90 flex items-center gap-2">

                            <span class="btn-text">Simpan</span>

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
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        const btn = document.getElementById('submit-btn')
        btn.onclick = async () => {
            btn.disabled = true
            btn.querySelector('#btn-spinner').classList.remove('d-none')
            btn.querySelector('.btn-text').classList.add('d-none')

            try {
                const form = document.getElementById('page-form')
                const data = new FormData(form)

                const res = await axios.post('{{ route('product.page.store') }}', data)

                if (res.data.status === true) {
                    showToast(
                        'success',
                        'Berhasil',
                        res.data.message || 'Data berhasil disimpan'
                    )
                } else {
                    showToast(
                        'error',
                        'Gagal',
                        res.data.message || 'Terjadi kesalahan'
                    )
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
            btn.querySelector('#btn-spinner').classList.add('d-none')
            btn.querySelector('.btn-text').classList.remove('d-none')
        }
    </script>
@endpush
