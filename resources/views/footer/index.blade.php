@extends('template.app')

@section('title', 'Footer Website')

@section('content')
    <div class="container-fluid">

        <div class="page-title-head d-flex align-items-center mb-4">
            <h4 class="page-main-title m-0">Footer Website</h4>
        </div>

        <div id="alert-box" class="alert d-none"></div>

        <div class="row g-4">

            {{-- LEFT --}}
            <div class="col-xl-5">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Identitas Footer</div>
                    <div class="card-body">

                        <form id="form-setting" enctype="multipart/form-data">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Logo Footer</label>
                                <input type="file" name="logo" class="form-control">
                                <div id="logo-preview" class="mt-3 d-none">
                                    <img class="border rounded" style="max-height:60px">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Copyright</label>
                                <input class="form-control" name="copyright_text">
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1">
                                <label class="form-check-label">Footer Aktif</label>
                            </div>

                            <button class="btn btn-primary w-100" id="btn-setting">
                                <span class="btn-text">Simpan Perubahan</span>
                                <span class="spinner-border spinner-border-sm d-none"></span>
                            </button>
                        </form>

                    </div>
                </div>
            </div>

            {{-- RIGHT --}}
            <div class="col-xl-7">
                <div class="row g-4">

                    {{-- QUICK LINKS --}}
                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-header fw-semibold">Quick Links</div>
                            <div class="card-body">

                                <form id="form-link" class="mb-3">
                                    <input class="form-control mb-2" name="label" placeholder="Label" required>
                                    <input class="form-control mb-2" name="url" placeholder="URL" required>
                                    <button class="btn btn-outline-primary w-100">Tambah</button>
                                </form>

                                <ul class="list-group" id="list-links"></ul>

                            </div>
                        </div>
                    </div>

                    {{-- CONTACT --}}
                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-header fw-semibold">Contact</div>
                            <div class="card-body">

                                <form id="form-contact" class="mb-3">
                                    <input class="form-control mb-2" name="type" placeholder="email / phone" required>
                                    <input class="form-control mb-2" name="label" placeholder="Label">
                                    <input class="form-control mb-2" name="value" placeholder="Value" required>
                                    <button class="btn btn-outline-primary w-100">Tambah</button>
                                </form>

                                <ul class="list-group" id="list-contacts"></ul>

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

        const alertBox = document.getElementById('alert-box')

        function alertMsg(type, msg) {
            alertBox.className = `alert alert-${type}`
            alertBox.textContent = msg
            alertBox.classList.remove('d-none')
            setTimeout(() => alertBox.classList.add('d-none'), 4000)
        }

        /* =======================
           LOAD DATA
        ======================= */
        async function loadSetting() {
            const res = await axios.get('/api/footer/setting')
            const d = res.data.data

            document.querySelector('[name=description]').value = d.description ?? ''
            document.querySelector('[name=copyright_text]').value = d.copyright_text ?? ''
            document.querySelector('[name=is_active]').checked = d.is_active

            if (d.logo_url) {
                const preview = document.getElementById('logo-preview')
                preview.classList.remove('d-none')
                preview.querySelector('img').src = d.logo_url
            }
        }

        async function loadLinks() {
            const res = await axios.get('/api/footer/quick-links')
            const list = document.getElementById('list-links')
            list.innerHTML = ''

            res.data.data.forEach(l => {
                list.innerHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>${l.label}</span>
                <button class="btn btn-sm btn-outline-danger"
                    onclick="deleteLink(${l.id})">✕</button>
            </li>`
            })
        }

        async function loadContacts() {
            const res = await axios.get('/api/footer/contacts')
            const list = document.getElementById('list-contacts')
            list.innerHTML = ''

            res.data.data.forEach(c => {
                list.innerHTML += `
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <span>${c.value}</span>
                <button class="btn btn-sm btn-outline-danger"
                    onclick="deleteContact(${c.id})">✕</button>
            </li>`
            })
        }

        /* =======================
           SUBMIT
        ======================= */
        document.getElementById('form-setting').onsubmit = async e => {
            e.preventDefault()

            const btn = document.getElementById('btn-setting')
            btn.disabled = true
            btn.querySelector('.spinner-border').classList.remove('d-none')
            btn.querySelector('.btn-text').classList.add('d-none')

            try {
                const form = new FormData(e.target)
                if (!form.has('is_active')) form.append('is_active', 0)

                await axios.post('/api/footer/setting', form)
                alertMsg('success', 'Footer berhasil disimpan')
                loadSetting()
            } catch {
                alertMsg('danger', 'Gagal menyimpan footer')
            }

            btn.disabled = false
            btn.querySelector('.spinner-border').classList.add('d-none')
            btn.querySelector('.btn-text').classList.remove('d-none')
        }

        document.getElementById('form-link').onsubmit = async e => {
            e.preventDefault()
            await axios.post('/api/footer/quick-links', new FormData(e.target))
            e.target.reset()
            loadLinks()
        }

        document.getElementById('form-contact').onsubmit = async e => {
            e.preventDefault()
            await axios.post('/api/footer/contacts', new FormData(e.target))
            e.target.reset()
            loadContacts()
        }

        /* =======================
           DELETE
        ======================= */
        function deleteLink(id) {
            if (!confirm('Hapus quick link ini?')) return
            axios.delete('/api/footer/quick-links/' + id).then(loadLinks)
        }

        function deleteContact(id) {
            if (!confirm('Hapus contact ini?')) return
            axios.delete('/api/footer/contacts/' + id).then(loadContacts)
        }

        /* INIT */
        loadSetting()
        loadLinks()
        loadContacts()
    </script>
@endpush
