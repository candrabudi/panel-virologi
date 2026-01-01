@extends('layouts.app')
@section('title', 'Pengaturan Website')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="mb-8">
            <h2 class="text-xl font-semibold">Website</h2>
            <p class="text-sm text-slate-500">
                Kelola Website
            </p>
        </div>

        <form id="ajax-form" data-url="{{ route('website.general') }}" onsubmit="return false;" class="grid grid-cols-12 gap-6">

            <div class="col-span-12 lg:col-span-12 space-y-6">

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 pb-3">
                        General Website Information
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Nama Website</label>
                        <input type="text" name="name" value="{{ old('name', $website->name ?? '') }}"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="Nama website">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Tagline</label>
                        <input type="text" name="tagline" value="{{ old('tagline', $website->tagline ?? '') }}"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="Tagline singkat">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Deskripsi</label>
                        <textarea name="description" rows="4"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="Deskripsi singkat website">{{ old('description', $website->description ?? '') }}</textarea>
                    </div>
                    <button type="submit" id="btn-save"
                        class="px-6 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90 flex items-center gap-2 mt-3">
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

        <form id="contact-form" data-url="{{ route('website.contact') }}" onsubmit="return false;"
            class="grid grid-cols-12 gap-6 mt-3">

            <div class="col-span-12 lg:col-span-12 space-y-6">

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 pb-3">
                        Informasi Kontak Website
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Nomor Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $website->phone ?? '') }}"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="+62xxxx">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Email</label>
                        <input type="email" name="email" value="{{ old('email', $website->email ?? '') }}"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="admin@domain.com">
                    </div>

                    <button type="submit" id="btn-contact-save"
                        class="px-6 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90 flex items-center gap-2 mt-3">
                        <span class="btn-text">Simpan Kontak</span>

                        <svg id="btn-contact-spinner" class="hidden w-4 h-4 animate-spin" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                                fill="none" opacity="0.25" />
                            <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" />
                        </svg>
                    </button>
                </div>

            </div>
        </form>


        <form id="branding-form" data-url="{{ route('website.branding') }}" enctype="multipart/form-data"
            onsubmit="return false;" class="grid grid-cols-12 gap-6 mt-3">

            <div class="col-span-12 lg:col-span-12 space-y-6">

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 pb-3">
                        Branding Website
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Logo Rectangle</label>
                        <input type="file" name="logo_rectangle"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control">

                        @if ($website?->logo_rectangle)
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-xs px-2 py-1 rounded bg-slate-100 text-slate-600">
                                    Current
                                </span>
                                <img src="{{ $website->logo_rectangle }}" class="h-10 rounded border">
                            </div>
                        @endif
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Logo Square</label>
                        <input type="file" name="logo_square"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control">

                        @if ($website?->logo_square)
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-xs px-2 py-1 rounded bg-slate-100 text-slate-600">
                                    Current
                                </span>
                                <img src="{{ $website->logo_square }}" class="h-10 rounded border">
                            </div>
                        @endif
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Favicon</label>
                        <input type="file" name="favicon"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control">

                        @if ($website?->favicon)
                            <div class="mt-2 flex items-center gap-2">
                                <span class="text-xs px-2 py-1 rounded bg-slate-100 text-slate-600">
                                    Current
                                </span>
                                <img src="{{ $website->favicon }}" class="h-6 rounded border">
                            </div>
                        @endif
                    </div>

                    <button type="submit" id="btn-branding-save"
                        class="px-6 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90 flex items-center gap-2 mt-3">
                        <span class="btn-text">Simpan Branding</span>

                        <svg id="btn-branding-spinner" class="hidden w-4 h-4 animate-spin" viewBox="0 0 24 24">
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

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
            axios.defaults.headers.common['X-CSRF-TOKEN'] =
                document.querySelector('meta[name="csrf-token"]').getAttribute('content')

            const form = document.getElementById('ajax-form')
            if (!form) return

            const btn = document.getElementById('btn-save')
            const spinner = document.getElementById('btn-spinner')
            const text = btn.querySelector('.btn-text')
            const url = form.dataset.url

            form.addEventListener('submit', async (e) => {
                e.preventDefault()

                btn.disabled = true
                spinner.classList.remove('hidden')
                text.classList.add('hidden')

                try {
                    const formData = new FormData(form)
                    const response = await axios.post(url, formData)

                    showToast(
                        'success',
                        'Berhasil',
                        response.data.message ?? 'Berhasil disimpan',
                    )

                } catch (error) {

                    if (error.response?.status === 422) {
                        const messages = Object.values(error.response.data.errors)
                            .flat()
                            .join('<br>')

                        showToast('failed', 'Gagal', messages)
                    } else {
                        showToast('failed', 'Gagal', 'Terjadi kesalahan sistem')
                    }
                }

                btn.disabled = false
                spinner.classList.add('hidden')
                text.classList.remove('hidden')
            })
        })
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {

            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
            axios.defaults.headers.common['X-CSRF-TOKEN'] =
                document.querySelector('meta[name="csrf-token"]').getAttribute('content')

            const form = document.getElementById('contact-form')
            if (!form) return

            const btn = document.getElementById('btn-contact-save')
            const spinner = document.getElementById('btn-contact-spinner')
            const text = btn.querySelector('.btn-text')
            const url = form.dataset.url

            form.addEventListener('submit', async (e) => {
                e.preventDefault()

                btn.disabled = true
                spinner.classList.remove('hidden')
                text.classList.add('hidden')

                try {
                    const formData = new FormData(form)
                    const response = await axios.post(url, formData)

                    showToast(
                        'success',
                        'Berhasil',
                        response.data.message ?? 'Kontak berhasil disimpan',
                    )

                } catch (error) {

                    if (error.response?.status === 422) {
                        const messages = Object.values(error.response.data.errors)
                            .flat()
                            .join('<br>')

                        showToast('failed', 'Gagal', messages)
                    } else {
                        showToast('failed', 'Gagal', 'Terjadi kesalahan sistem')
                    }
                }

                btn.disabled = false
                spinner.classList.add('hidden')
                text.classList.remove('hidden')
            })
        })
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', () => {

            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
            axios.defaults.headers.common['X-CSRF-TOKEN'] =
                document.querySelector('meta[name="csrf-token"]').getAttribute('content')

            const form = document.getElementById('branding-form')
            if (!form) return

            const btn = document.getElementById('btn-branding-save')
            const spinner = document.getElementById('btn-branding-spinner')
            const text = btn.querySelector('.btn-text')
            const url = form.dataset.url

            form.addEventListener('submit', async (e) => {
                e.preventDefault()

                btn.disabled = true
                spinner.classList.remove('hidden')
                text.classList.add('hidden')

                try {
                    const formData = new FormData(form)
                    const response = await axios.post(url, formData)

                    showToast(
                        'success', 
                        'Berhasil',
                        response.data.message ?? 'Branding berhasil disimpan',
                    )

                } catch (error) {

                    if (error.response?.status === 422) {
                        const messages = Object.values(error.response.data.errors)
                            .flat()
                            .join('<br>')

                        showToast('failed', 'Gagal', messages)
                    } else {
                        showToast('failed', 'Gagal', 'Terjadi kesalahan sistem')
                    }
                }

                btn.disabled = false
                spinner.classList.add('hidden')
                text.classList.remove('hidden')
            })
        })
    </script>
@endpush
