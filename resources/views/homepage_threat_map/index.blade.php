@extends('template.app')

@section('title', 'Homepage – Cyber Threat Map')

@section('content')
    <div class="container-fluid mt-3">

        <div id="alert-box" class="alert d-none"></div>

        <div class="row g-4">

            <!-- LEFT : FORM -->
            <div class="col-xl-5 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold bg-white">
                        Pengaturan Section
                    </div>
                    <div class="card-body">

                        <form id="threat-map-form" onsubmit="return false">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Pre Title</label>
                                <input class="form-control" name="pre_title" placeholder="Contoh: Global Threat Landscape">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Judul Utama</label>
                                <input class="form-control" name="title" placeholder="Cyber Threat Map" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="description" rows="4"
                                    placeholder="Visualisasi serangan siber secara global dan realtime"></textarea>
                            </div>

                            <hr>

                            <h6 class="fw-semibold mb-2">Call To Action</h6>

                            <div class="mb-2">
                                <input class="form-control" name="cta_text" placeholder="Contoh: Lihat Threat Map">
                            </div>

                            <div class="mb-4">
                                <input class="form-control" name="cta_url" placeholder="https://threatmap.example.com">
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-4 p-3 border rounded">
                                <div>
                                    <div class="fw-semibold">Status Section</div>
                                    <small class="text-muted">Tampilkan section ini di homepage</small>
                                </div>
                                <div class="form-check form-switch m-0">
                                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                </div>
                            </div>

                            <button type="button" class="btn btn-primary w-100" id="btn-save">
                                <span class="btn-text">Simpan Perubahan</span>
                                <span class="spinner-border spinner-border-sm d-none"></span>
                            </button>

                        </form>

                    </div>
                </div>
            </div>

            <!-- RIGHT : PREVIEW -->
            <div class="col-xl-7 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold bg-white">
                        Preview Section
                    </div>
                    <div class="card-body">

                        <div class="border rounded p-4 bg-dark text-white">
                            <small id="pv-pre" class="text-uppercase text-secondary">
                                Global Threat Landscape
                            </small>

                            <h3 id="pv-title" class="fw-bold mt-2">
                                Cyber Threat Map
                            </h3>

                            <p id="pv-desc" class="text-white-50">
                                Visualisasi serangan siber secara global dan realtime
                            </p>

                            <div class="mt-3">
                                <span id="pv-cta" class="badge bg-danger d-none">
                                    CTA
                                </span>
                            </div>

                            <div class="mt-4">
                                <span id="pv-status" class="badge bg-success">
                                    AKTIF
                                </span>
                            </div>
                        </div>

                    </div>
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
        const alertBox = document.getElementById('alert-box')

        const pv = {
            pre: document.getElementById('pv-pre'),
            title: document.getElementById('pv-title'),
            desc: document.getElementById('pv-desc'),
            cta: document.getElementById('pv-cta'),
            status: document.getElementById('pv-status'),
        }

        function alertMsg(type, msg) {
            alertBox.className = `alert alert-${type}`
            alertBox.textContent = msg
            alertBox.classList.remove('d-none')
            setTimeout(() => alertBox.classList.add('d-none'), 4000)
        }

        function toggleLoading(on) {
            btn.disabled = on
            btn.querySelector('.spinner-border').classList.toggle('d-none', !on)
            btn.querySelector('.btn-text').classList.toggle('d-none', on)
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
                    if (el.type === 'checkbox') el.checked = v
                    else el.value = v ?? ''
                })

                updatePreview()
            } catch {
                alertMsg('danger', 'Gagal memuat data Threat Map')
            }
        }

        btn.onclick = async () => {
            toggleLoading(true)

            const data = new FormData(form)
            if (!data.has('is_active')) data.append('is_active', 0)

            try {
                const res = await axios.post('/homepage-threat-map', data)
                alertMsg('success', res.data.message || 'Berhasil disimpan')
            } catch (e) {
                if (e.response?.status === 422) {
                    const errors = Object.values(e.response.data.errors || {})
                        .flat()
                        .join(' | ')
                    alertMsg('danger', errors)
                } else {
                    alertMsg('danger', 'Terjadi kesalahan sistem')
                }
            }

            toggleLoading(false)
        }

        loadSection()
    </script>
@endpush
