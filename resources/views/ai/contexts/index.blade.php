@extends('template.app')

@section('title', 'AI Contexts')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>AI Contexts</h4>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form id="form-add">
                    @csrf
                    <div class="row g-2">
                        <div class="col-md-3">
                            <input name="code" class="form-control" placeholder="Code (learning)">
                        </div>
                        <div class="col-md-4">
                            <input name="name" class="form-control" placeholder="Context Name">
                        </div>
                        <div class="col-md-3">
                            <select name="use_internal_source" class="form-select">
                                <option value="0">GPT Knowledge</option>
                                <option value="1">Internal Data Only</option>
                            </select>
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
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Name</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th width="160">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($contexts as $ctx)
                            <tr data-id="{{ $ctx->id }}">
                                <td>{{ $ctx->code }}</td>
                                <td>
                                    <input class="form-control form-control-sm name" value="{{ $ctx->name }}">
                                </td>
                                <td>
                                    <select class="form-select form-select-sm source">
                                        <option value="0" @selected(!$ctx->use_internal_source)>GPT</option>
                                        <option value="1" @selected($ctx->use_internal_source)>Internal</option>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm status">
                                        <option value="1" @selected($ctx->is_active)>Active</option>
                                        <option value="0" @selected(!$ctx->is_active)>Inactive</option>
                                    </select>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success btn-save">Simpan</button>
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
            axios.post('/ai/contexts', new FormData(this))
                .then(() => location.reload())
        })

        document.querySelectorAll('.btn-save').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr')
                axios.put('/cms/ai/contexts/' + tr.dataset.id, {
                    name: tr.querySelector('.name').value,
                    use_internal_source: tr.querySelector('.source').value,
                    is_active: tr.querySelector('.status').value
                }).then(() => location.reload())
            })
        })
    </script>
@endpush
