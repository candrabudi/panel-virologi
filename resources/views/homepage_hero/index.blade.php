@extends('template.app')

@section('title', 'Homepage Hero')

@section('content')
    <div class="container-fluid mt-3">

        <div id="alert-box" class="alert d-none"></div>

        <div class="row g-4">

            <!-- FORM -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Pengaturan Hero</div>
                    <div class="card-body">

                        <form id="hero-form" onsubmit="return false">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Pre Title</label>
                                <input class="form-control" name="pre_title" placeholder="Contoh: Virologi">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Judul Utama</label>
                                <textarea class="form-control" rows="2" name="title" required></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Sub Judul</label>
                                <textarea class="form-control" rows="3" name="subtitle"></textarea>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Overlay Color</label>
                                    <input type="color" name="overlay_color" class="form-control form-control-color"
                                        value="#000000">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Overlay Opacity</label>
                                    <input type="number" step="0.05" min="0" max="1"
                                        name="overlay_opacity" class="form-control" value="0.5">
                                </div>
                            </div>

                            <hr>

                            <h6 class="fw-semibold">Primary Button</h6>
                            <div class="mb-2">
                                <input class="form-control" name="primary_button_text" placeholder="Text tombol utama">
                            </div>
                            <div class="mb-3">
                                <input class="form-control" name="primary_button_url" placeholder="URL tombol utama">
                            </div>

                            <h6 class="fw-semibold">Secondary Button</h6>
                            <div class="mb-2">
                                <input class="form-control" name="secondary_button_text" placeholder="Text tombol kedua">
                            </div>
                            <div class="mb-3">
                                <input class="form-control" name="secondary_button_url" placeholder="URL tombol kedua">
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                <label class="form-check-label">Aktifkan Hero</label>
                            </div>

                            <button class="btn btn-primary" id="btn-save">
                                <span class="btn-text">Simpan Perubahan</span>
                                <span class="spinner-border spinner-border-sm d-none"></span>
                            </button>

                        </form>
                    </div>
                </div>
            </div>

            <!-- PREVIEW -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Live Preview</div>
                    <div class="card-body">

                        <div id="preview" class="p-4 rounded text-white" style="background:#000; opacity:.5">
                            <p id="pv-pre" class="fw-semibold mb-1">Pre Title</p>
                            <h3 id="pv-title" class="fw-bold">Judul Hero</h3>
                            <p id="pv-sub" class="text-white-50">Sub judul hero</p>

                            <div class="mt-3">
                                <span id="pv-btn-1" class="badge bg-light text-dark d-none"></span>
                                <span id="pv-btn-2" class="badge border border-light text-white d-none"></span>
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

        const form = document.getElementById('hero-form')
        const btn = document.getElementById('btn-save')
        const alertBox = document.getElementById('alert-box')

        const pv = {
            box: document.getElementById('preview'),
            pre: document.getElementById('pv-pre'),
            title: document.getElementById('pv-title'),
            sub: document.getElementById('pv-sub'),
            btn1: document.getElementById('pv-btn-1'),
            btn2: document.getElementById('pv-btn-2'),
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

            pv.pre.textContent = d.get('pre_title') || 'Pre Title'
            pv.title.textContent = d.get('title') || 'Judul Hero'
            pv.sub.textContent = d.get('subtitle') || 'Sub judul hero'

            pv.box.style.background = d.get('overlay_color') || '#000'
            pv.box.style.opacity = d.get('overlay_opacity') || 0.5

            if (d.get('primary_button_text')) {
                pv.btn1.textContent = d.get('primary_button_text')
                pv.btn1.classList.remove('d-none')
            } else pv.btn1.classList.add('d-none')

            if (d.get('secondary_button_text')) {
                pv.btn2.textContent = d.get('secondary_button_text')
                pv.btn2.classList.remove('d-none')
            } else pv.btn2.classList.add('d-none')
        }

        form.querySelectorAll('input,textarea').forEach(el =>
            el.addEventListener('input', updatePreview)
        )

        btn.onclick = async () => {
            toggleLoading(true)
            const data = new FormData(form)
            if (!data.has('is_active')) data.append('is_active', 0)

            try {
                const res = await axios.post('/homepage-hero', data)
                alertMsg('success', res.data.message || 'Berhasil disimpan')
            } catch (e) {
                alertMsg('danger', 'Gagal menyimpan data')
            }
            toggleLoading(false)
        }

        async function loadHero() {
            try {
                const res = await axios.get('/homepage-hero/show')
                if (!res.data.data) return

                Object.entries(res.data.data).forEach(([k, v]) => {
                    const el = form.querySelector(`[name="${k}"]`)
                    if (!el) return
                    if (el.type === 'checkbox') el.checked = v
                    else el.value = v ?? ''
                })
                updatePreview()
            } catch {}
        }

        loadHero()
    </script>
@endpush
