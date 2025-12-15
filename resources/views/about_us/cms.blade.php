@extends('template.app')

@section('title', 'Tentang Kami')

@section('content')
    <div class="container-fluid">

        <div class="page-title-head d-flex align-items-center mb-4">
            <h4 class="page-main-title">Tentang Kami</h4>
        </div>

        <div class="row g-4">

            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header fw-semibold">Konten Utama</div>
                    <div class="card-body">

                        <div id="alert-box"></div>

                        <form id="about-form" onsubmit="return false">

                            <input class="form-control mb-3" name="headline" placeholder="Headline"
                                value="{{ $about->headline ?? '' }}">

                            <textarea class="form-control mb-3" rows="6" name="left_content" placeholder="Konten kiri">{{ $about->left_content ?? '' }}</textarea>

                            <textarea class="form-control mb-3" rows="6" name="right_content" placeholder="Konten kanan">{{ $about->right_content ?? '' }}</textarea>

                            <label class="fw-semibold mb-2">Topik Bahasan</label>
                            <textarea class="form-control mb-3" rows="4" name="topics" placeholder="Satu baris satu topik">
@if ($about?->topics)
{{ implode("\n", $about->topics) }}
@endif
</textarea>

                            <label class="fw-semibold mb-2">Manifesto</label>
                            <textarea class="form-control mb-3" rows="4" name="manifesto" placeholder="Satu baris satu manifesto">
@if ($about?->manifesto)
{{ implode("\n", $about->manifesto) }}
@endif
</textarea>

                            <button class="btn btn-primary" id="btn-save">
                                <span class="btn-text">Simpan</span>
                                <span class="spinner-border spinner-border-sm d-none"></span>
                            </button>

                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-header fw-semibold">SEO</div>
                    <div class="card-body">

                        <input class="form-control mb-2" name="seo_title" form="about-form" placeholder="SEO Title"
                            value="{{ $about->seo_title ?? '' }}">

                        <textarea class="form-control mb-2" rows="3" name="seo_description" form="about-form"
                            placeholder="SEO Description">{{ $about->seo_description ?? '' }}</textarea>

                        <textarea class="form-control mb-2" rows="3" name="seo_keywords" form="about-form" placeholder="SEO Keywords">{{ $about->seo_keywords ?? '' }}</textarea>

                        <input class="form-control mb-2" name="og_title" form="about-form" placeholder="OG Title"
                            value="{{ $about->og_title ?? '' }}">

                        <textarea class="form-control mb-2" rows="3" name="og_description" form="about-form" placeholder="OG Description">{{ $about->og_description ?? '' }}</textarea>

                        <input class="form-control" name="canonical_url" form="about-form" placeholder="Canonical URL"
                            value="{{ $about->canonical_url ?? '' }}">
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

        document.getElementById('btn-save').onclick = async () => {
            const btn = event.currentTarget
            btn.disabled = true
            btn.querySelector('.spinner-border').classList.remove('d-none')
            btn.querySelector('.btn-text').classList.add('d-none')

            const form = document.getElementById('about-form')
            const data = new FormData(form)

            data.set('topics', JSON.stringify(form.topics.value.split('\n').filter(Boolean)))
            data.set('manifesto', JSON.stringify(form.manifesto.value.split('\n').filter(Boolean)))

            try {
                await axios.post('{{ route('about-us.store') }}', data)
                location.reload()
            } catch (e) {
                alert('Gagal menyimpan data')
            }
        }
    </script>
@endpush
