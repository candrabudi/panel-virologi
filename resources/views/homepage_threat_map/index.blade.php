@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">

        {{-- Header --}}
        <div class="flex flex-col gap-1 mb-8">
            <h2 class="text-xl font-semibold group-[.mode--light]:text-white">
                Homepage Threat Map
            </h2>
            <p class="text-sm text-slate-500 group-[.mode--light]:text-white/80">
                Kelola konten Threat Map dan status tampil di homepage
            </p>
        </div>

        <div class="mt-3.5 grid grid-cols-12 gap-x-6 gap-y-10">
            <div class="relative col-span-12 flex flex-col gap-y-7">
                <div class="box box--stacked flex flex-col p-5">

                    <form id="threat-map-form">
                        @csrf

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

                            {{-- LEFT --}}
                            <div class="space-y-8 p-5">
                                <div class="pb-3 border-b border-slate-200/70">
                                    <h3 class="text-sm font-semibold text-slate-700">
                                        Threat Map Content
                                    </h3>
                                </div>

                                <div class="space-y-6">

                                    <div>
                                        <label class="block mt-5 mb-2 text-sm font-medium">
                                            Pre Title
                                        </label>
                                        <input type="text" name="pre_title"
                                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control">
                                    </div>

                                    <div>
                                        <label class="block mt-5 mb-2 text-sm font-medium">
                                            Title
                                        </label>
                                        <input type="text" name="title" required
                                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control">
                                    </div>

                                    <div>
                                        <label class="block mt-5 mb-2 text-sm font-medium">
                                            Description
                                        </label>
                                        <textarea name="description" rows="4"
                                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control"></textarea>
                                    </div>

                                    <div>
                                        <label class="block mt-5 mb-2 text-sm font-medium">
                                            CTA Text (Optional)
                                        </label>
                                        <input type="text" name="cta_text"
                                            class="disabled:bg-slate-100 disabled:cursor-not-allowed dark:disabled:bg-darkmode-800/50 dark:disabled:border-transparent [&[readonly]]:bg-slate-100 [&[readonly]]:cursor-not-allowed [&[readonly]]:dark:bg-darkmode-800/50 [&[readonly]]:dark:border-transparent transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md placeholder:text-slate-400/90 focus:ring-4 focus:ring-primary focus:ring-opacity-20 focus:border-primary focus:border-opacity-40 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 dark:placeholder:text-slate-500/80 [&[type='file']]:border file:mr-4 file:py-2 file:px-4 file:rounded-l-md file:border-0 file:border-r-[1px] file:border-slate-100/10 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-500/70 hover:file:bg-200 group-[.form-inline]:flex-1 group-[.input-group]:rounded-none group-[.input-group]:[&:not(:first-child)]:border-l-transparent group-[.input-group]:first:rounded-l group-[.input-group]:last:rounded-r group-[.input-group]:z-10 form-control">
                                    </div>

                                </div>
                            </div>

                            {{-- RIGHT --}}
                            <div class="space-y-8 p-5">
                                <div class="pb-3 border-b border-slate-200/70">
                                    <h3 class="text-sm font-semibold text-slate-700">
                                        Preview & Status
                                    </h3>
                                </div>

                                <div class="space-y-6">

                                    {{-- Preview Box --}}
                                    <div class="rounded-md border p-5 bg-slate-50 dark:bg-darkmode-700">
                                        <span id="pv-pre" class="text-xs uppercase tracking-wide text-slate-500">
                                            Global Threat Landscape
                                        </span>

                                        <h3 id="pv-title" class="mt-2 text-lg font-semibold">
                                            Cyber Threat Map
                                        </h3>

                                        <p id="pv-desc" class="mt-2 text-sm text-slate-500">
                                            Visualisasi serangan siber secara global
                                        </p>

                                        <span id="pv-cta"
                                            class="inline-block mt-4 text-sm font-semibold text-primary d-none">
                                            View Threat Map →
                                        </span>

                                        <div class="mt-4">
                                            <span id="pv-status" class="badge bg-secondary">
                                                NONAKTIF
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Active Toggle --}}
                                    <div>
                                        <h4 class="text-sm font-semibold text-slate-600 mt-3">
                                            Aktifkan Threat Map Section
                                        </h4>
                                        <div class="mt-3">
                                            <input type="checkbox" name="is_active" value="1"
                                                class="transition-all duration-100 ease-in-out shadow-sm border-slate-200 cursor-pointer focus:ring-4 focus:ring-offset-0 focus:ring-primary focus:ring-opacity-20 dark:bg-darkmode-800 dark:border-transparent dark:focus:ring-slate-700 dark:focus:ring-opacity-50 [&[type='radio']]:checked:bg-primary [&[type='radio']]:checked:border-primary [&[type='radio']]:checked:border-opacity-10 [&[type='checkbox']]:checked:bg-primary [&[type='checkbox']]:checked:border-primary [&[type='checkbox']]:checked:border-opacity-10 [&:disabled:not(:checked)]:bg-slate-100 [&:disabled:not(:checked)]:cursor-not-allowed [&:disabled:not(:checked)]:dark:bg-darkmode-800/50 [&:disabled:checked]:opacity-70 [&:disabled:checked]:cursor-not-allowed [&:disabled:checked]:dark:bg-darkmode-800/50 w-[38px] h-[24px] p-px rounded-full relative before:w-[20px] before:h-[20px] before:shadow-[1px_1px_3px_rgba(0,0,0,0.25)] before:transition-[margin-left] before:duration-200 before:ease-in-out before:absolute before:inset-y-0 before:my-auto before:rounded-full before:dark:bg-darkmode-600 checked:bg-primary checked:border-primary checked:bg-none before:checked:ml-[14px] before:checked:bg-white mr-0">
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>

                        {{-- Action --}}
                        <div class="flex justify-end pt-8 mt-10 border-t border-slate-200/70">
                            <button type="button" id="btn-save"
                                class="px-8 py-2.5 text-sm font-semibold text-white rounded-md
                                   bg-primary hover:bg-primary/90 flex items-center gap-2">
                                <span class="spinner-border spinner-border-sm d-none"></span>
                                <span class="btn-text">Simpan Threat Map</span>
                            </button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        const form = document.getElementById('threat-map-form')
        const btn = document.getElementById('btn-save')

        const pv = {
            pre: document.getElementById('pv-pre'),
            title: document.getElementById('pv-title'),
            desc: document.getElementById('pv-desc'),
            cta: document.getElementById('pv-cta'),
            status: document.getElementById('pv-status'),
        }

        function toggleLoading(on) {
            btn.disabled = on
            btn.querySelector('.spinner-border')?.classList.toggle('d-none', !on)
            btn.querySelector('.btn-text')?.classList.toggle('d-none', on)
        }

        function updatePreview() {
            const d = new FormData(form)

            pv.pre.textContent = d.get('pre_title') || 'Global Threat Landscape'
            pv.title.textContent = d.get('title') || 'Cyber Threat Map'
            pv.desc.textContent = d.get('description') || 'Visualisasi serangan siber secara global'

            if (d.get('cta_text')) {
                pv.cta.textContent = d.get('cta_text')
                pv.cta.classList.remove('d-none')
            } else {
                pv.cta.classList.add('d-none')
            }

            if (form.is_active.checked) {
                pv.status.textContent = 'AKTIF'
                pv.status.className = 'badge bg-success'
            } else {
                pv.status.textContent = 'NONAKTIF'
                pv.status.className = 'badge bg-secondary'
            }
        }

        form.querySelectorAll('input,textarea').forEach(el =>
            el.addEventListener('input', updatePreview)
        )
        form.is_active.addEventListener('change', updatePreview)

        async function loadSection() {
            try {
                const res = await axios.get('/homepage-threat-map/show')
                const d = res.data.data
                if (!d) return

                Object.entries(d).forEach(([k, v]) => {
                    const el = form.querySelector(`[name="${k}"]`)
                    if (!el) return

                    if (el.type === 'checkbox') {
                        el.checked = !!v
                    } else {
                        el.value = v ?? ''
                    }
                })

                updatePreview()
            } catch {
                showToast(
                    'danger',
                    'Gagal',
                    'Tidak dapat memuat data Threat Map'
                )
            }
        }

        btn.onclick = async () => {
            toggleLoading(true)

            const data = new FormData(form)
            if (!data.has('is_active')) data.append('is_active', 0)

            try {
                const res = await axios.post('/homepage-threat-map', data)
                showToast(
                    'success',
                    'Berhasil',
                    res.data.message || 'Threat Map berhasil disimpan'
                )
            } catch (e) {
                if (e.response?.status === 422) {
                    const errors = Object.values(e.response.data.errors || {})
                        .flat()
                        .join(' | ')

                    showToast(
                        'danger',
                        'Validasi Gagal',
                        errors
                    )
                } else {
                    showToast(
                        'danger',
                        'Error',
                        'Terjadi kesalahan sistem'
                    )
                }
            }

            toggleLoading(false)
        }

        loadSection()
    </script>
@endpush
