@extends('template.app')

@section('title', 'Article Tags')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Article Tags</h4>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form id="form-add">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-10">
                            <input name="name" class="form-control" placeholder="Tag name">
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Tambah</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body p-0">
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tags as $tag)
                            <tr data-id="{{ $tag->id }}">
                                <td>
                                    <input class="form-control form-control-sm name" value="{{ $tag->name }}">
                                </td>
                                <td>{{ $tag->slug }}</td>
                                <td>
                                    <button class="btn btn-sm btn-success btn-save">Simpan</button>
                                    <button class="btn btn-sm btn-danger btn-delete">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('form-add').addEventListener('submit', function(e) {
            e.preventDefault()
            axios.post('/article-tags', new FormData(this))
                .then(() => location.reload())
        })

        document.querySelectorAll('.btn-save').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr')
                axios.put('/article-tags/' + tr.dataset.id, {
                    name: tr.querySelector('.name').value
                }).then(() => location.reload())
            })
        })

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr')
                axios.delete('/article-tags/' + tr.dataset.id)
                    .then(() => location.reload())
            })
        })
    </script>
@endpush
