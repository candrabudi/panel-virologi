@extends('template.app')

@section('title', 'Homepage Blog & Artikel')

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

                        <form id="blog-section-form" onsubmit="return false">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Judul Section</label>
                                <input type="text" name="title" class="form-control" placeholder="Blog & Artikel"
                                    required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Sub Judul</label>
                                <textarea name="subtitle" class="form-control" rows="3"
                                    placeholder="Baca artikel seputar cybersecurity dan teknologi"></textarea>
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

                        <div class="border rounded p-4 bg-light">
                            <h4 id="pv-title" class="fw-bold mb-2">
                                Blog & Artikel
                            </h4>

                            <p id="pv-subtitle" class="text-muted mb-3">
                                Sub judul section blog
                            </p>

                            <span id="pv-status" class="badge bg-success">
                                AKTIF
                            </span>
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

        const form = document.getElementById('blog-section-form')
        const btn = document.getElementById('btn-save')
        const alertBox = document.getElementById('alert-box')

        const pv = {
            title: document.getElementById('pv-title'),
            subtitle: document.getElementById('pv-subtitle'),
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

            pv.title.textContent = d.get('title') || 'Blog & Artikel'
            pv.subtitle.textContent = d.get('subtitle') || 'Sub judul section blog'

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
                const res = await axios.get('/homepage-blog-section/show')
                const d = res.data.data
                if (!d) return

                form.title.value = d.title ?? ''
                form.subtitle.value = d.subtitle ?? ''
                form.is_active.checked = !!d.is_active

                updatePreview()
            } catch {
                alertMsg('danger', 'Gagal memuat data section')
            }
        }

        btn.onclick = async () => {
            toggleLoading(true)

            const data = new FormData(form)
            if (!data.has('is_active')) data.append('is_active', 0)

            try {
                const res = await axios.post('/homepage-blog-section', data)
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
