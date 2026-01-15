@extends('layouts.app')

@section('title', 'Tentang Kami')

@section('content')
    <div class="container-fluid mt-3">

        <div id="alert-box" class="alert d-none"></div>

        <form id="about-form" onsubmit="return false">
            @csrf

            <div class="row g-4">

                <div class="col-lg-8">
                    <div class="card shadow-sm">
                        <div class="card-header fw-semibold">Konten Utama</div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Headline</label>
                                <input class="form-control" name="headline" placeholder="Headline utama">
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Konten Kiri</label>
                                <textarea id="left_content" name="left_content"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Konten Kanan</label>
                                <textarea id="right_content" name="right_content"></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Topik Bahasan</label>
                                <textarea class="form-control" rows="4" name="topics" placeholder="Satu baris satu topik"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="form-label">Manifesto</label>
                                <textarea class="form-control" rows="4" name="manifesto" placeholder="Satu baris satu manifesto"></textarea>
                            </div>

                            <div class="form-check form-switch mb-4">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1">
                                <label class="form-check-label">Aktifkan halaman About Us</label>
                            </div>

                            <button class="btn btn-primary" id="btn-save">
                                <span class="btn-text">Simpan Perubahan</span>
                                <span class="spinner-border spinner-border-sm d-none"></span>
                            </button>

                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm">
                        <div class="card-header fw-semibold">SEO & Meta</div>
                        <div class="card-body">

                            <div class="mb-2">
                                <label class="form-label">SEO Title</label>
                                <input class="form-control" name="seo_title" placeholder="SEO title">
                            </div>

                            <div class="mb-2">
                                <label class="form-label">SEO Description</label>
                                <textarea class="form-control" rows="3" name="seo_description" placeholder="SEO description"></textarea>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">SEO Keywords</label>
                                <textarea class="form-control" rows="3" name="seo_keywords" placeholder="keyword1, keyword2"></textarea>
                            </div>

                            <div class="mb-2">
                                <label class="form-label">OG Title</label>
                                <input class="form-control" name="og_title" placeholder="OG title">
                            </div>

                            <div class="mb-2">
                                <label class="form-label">OG Description</label>
                                <textarea class="form-control" rows="3" name="og_description" placeholder="OG description"></textarea>
                            </div>

                            <div>
                                <label class="form-label">Canonical URL</label>
                                <input class="form-control" name="canonical_url" placeholder="https://domain.com/about-us">
                            </div>

                        </div>
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

        const alertBox = document.getElementById('alert-box')

        function alertMsg(type, msg) {
            alertBox.className = `alert alert-${type}`
            alertBox.textContent = msg
            alertBox.classList.remove('d-none')
            setTimeout(() => alertBox.classList.add('d-none'), 4000)
        }

        function setBtnLoading(btn, loading) {
            btn.disabled = loading
            btn.querySelector('.spinner-border').classList.toggle('d-none', !loading)
            btn.querySelector('.btn-text').classList.toggle('d-none', loading)
        }

        /* TinyMCE: NO image upload */
        tinymce.init({
            selector: '#left_content,#right_content',
            height: 280,
            menubar: false,
            branding: false,
            plugins: 'lists link code',
            toolbar: 'undo redo | bold italic underline | bullist numlist | link | code',
            content_style: 'body{font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial; font-size:14px;}'
        })

        async function loadAbout() {
            try {
                const res = await axios.get('/api/about-us')
                const d = res.data.data
                if (!d) return

                document.querySelector('[name=headline]').value = d.headline ?? ''

                const left = () => tinymce.get('left_content')
                const right = () => tinymce.get('right_content')

                const waitEditors = setInterval(() => {
                    if (left() && right()) {
                        clearInterval(waitEditors)
                        left().setContent(d.left_content ?? '')
                        right().setContent(d.right_content ?? '')
                    }
                }, 80)

                document.querySelector('[name=topics]').value = (d.topics ?? []).join("\n")
                document.querySelector('[name=manifesto]').value = (d.manifesto ?? []).join("\n")

                document.querySelector('[name=seo_title]').value = d.seo_title ?? ''
                document.querySelector('[name=seo_description]').value = d.seo_description ?? ''
                document.querySelector('[name=seo_keywords]').value = d.seo_keywords ?? ''
                document.querySelector('[name=og_title]').value = d.og_title ?? ''
                document.querySelector('[name=og_description]').value = d.og_description ?? ''
                document.querySelector('[name=canonical_url]').value = d.canonical_url ?? ''

                document.querySelector('[name=is_active]').checked = !!d.is_active
            } catch (e) {
                alertMsg('danger', 'Gagal memuat data About Us')
            }
        }

        document.getElementById('btn-save').onclick = async (event) => {
            const btn = event.currentTarget
            setBtnLoading(btn, true)

            const form = document.getElementById('about-form')
            const data = new FormData(form)

            data.set('left_content', tinymce.get('left_content') ? tinymce.get('left_content').getContent() : '')
            data.set('right_content', tinymce.get('right_content') ? tinymce.get('right_content').getContent() : '')

            data.set('topics', JSON.stringify(
                form.topics.value.split('\n').map(v => v.trim()).filter(Boolean)
            ))
            data.set('manifesto', JSON.stringify(
                form.manifesto.value.split('\n').map(v => v.trim()).filter(Boolean)
            ))

            if (!data.has('is_active')) data.append('is_active', 0)

            try {
                const res = await axios.post('/api/about-us', data)
                alertMsg('success', res.data.message || 'Berhasil disimpan')
            } catch (e) {
                if (e.response && e.response.status === 422) {
                    const errors = Object.values(e.response.data.errors || {}).flat().join(' | ')
                    alertMsg('danger', errors || 'Validasi gagal')
                } else {
                    alertMsg('danger', 'Gagal menyimpan data')
                }
            }

            setBtnLoading(btn, false)
        }

        loadAbout()
    </script>
@endpush
