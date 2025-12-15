@extends('template.app')

@section('title', 'Footer Website')

@section('content')
    <div class="container-fluid">

        <div class="page-title-head d-flex align-items-center mb-4">
            <h4 class="page-main-title m-0">Footer Website</h4>
        </div>

        <div class="row g-4">

            <!-- LEFT : FOOTER SETTING -->
            <div class="col-xl-5">
                <div class="card shadow-sm">
                    <div class="card-header fw-semibold">Identitas Footer</div>
                    <div class="card-body">

                        <div id="alert-setting"></div>

                        <form id="form-setting" enctype="multipart/form-data" onsubmit="return false">

                            <div class="mb-3">
                                <label class="form-label">Logo Footer</label>
                                <input type="file" name="logo" class="form-control">

                                @if ($setting?->logo_path)
                                    <div class="mt-3">
                                        <img src="{{ asset('storage/' . $setting->logo_path) }}" style="max-height:60px">
                                    </div>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="description" rows="3">{{ $setting->description ?? '' }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Copyright</label>
                                <input class="form-control" name="copyright_text"
                                    value="{{ $setting->copyright_text ?? '' }}">
                            </div>

                            <button class="btn btn-primary w-100" id="btn-setting">
                                <span class="btn-text">Simpan Perubahan</span>
                                <span class="spinner-border spinner-border-sm d-none"></span>
                            </button>

                        </form>
                    </div>
                </div>
            </div>

            <!-- RIGHT : QUICK LINK + CONTACT -->
            <div class="col-xl-7">
                <div class="row g-4">

                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-header fw-semibold">Quick Links</div>
                            <div class="card-body">
                                <form id="form-link" class="mb-3">
                                    <input class="form-control mb-2" name="label" placeholder="Label">
                                    <input class="form-control mb-2" name="url" placeholder="URL">
                                    <button class="btn btn-outline-primary w-100">Tambah</button>
                                </form>

                                <ul class="list-group">
                                    @foreach ($links as $l)
                                        <li class="list-group-item d-flex justify-content-between">
                                            {{ $l->label }}
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="delLink({{ $l->id }})">✕</button>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="card shadow-sm">
                            <div class="card-header fw-semibold">Contact</div>
                            <div class="card-body">
                                <form id="form-contact" class="mb-3">
                                    <input class="form-control mb-2" name="type" placeholder="email / phone">
                                    <input class="form-control mb-2" name="label" placeholder="Label">
                                    <input class="form-control mb-2" name="value" placeholder="Value">
                                    <button class="btn btn-outline-primary w-100">Tambah</button>
                                </form>

                                <ul class="list-group">
                                    @foreach ($contacts as $c)
                                        <li class="list-group-item d-flex justify-content-between">
                                            {{ $c->value }}
                                            <button class="btn btn-sm btn-outline-danger"
                                                onclick="delContact({{ $c->id }})">✕</button>
                                        </li>
                                    @endforeach
                                </ul>
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

        const btn = document.getElementById('btn-setting')
        btn.onclick = async () => {
            btn.disabled = true
            btn.querySelector('.spinner-border').classList.remove('d-none')
            btn.querySelector('.btn-text').classList.add('d-none')

            try {
                await axios.post('{{ route('footer.setting.save') }}',
                    new FormData(document.getElementById('form-setting')))
                location.reload()
            } finally {
                btn.disabled = false
            }
        }

        document.getElementById('form-link').onsubmit = e => {
            e.preventDefault()
            axios.post('{{ route('footer.quick-link.save') }}', new FormData(e.target))
                .then(() => location.reload())
        }

        document.getElementById('form-contact').onsubmit = e => {
            e.preventDefault()
            axios.post('{{ route('footer.contact.save') }}', new FormData(e.target))
                .then(() => location.reload())
        }

        function delLink(id) {
            axios.delete('/footer/quick-link/' + id).then(() => location.reload())
        }

        function delContact(id) {
            axios.delete('/footer/contact/' + id).then(() => location.reload())
        }
    </script>
@endpush
