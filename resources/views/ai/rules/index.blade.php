@extends('template.app')

@section('title', 'AI Rules')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-4">
            <h4>AI Rules</h4>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form id="form-add">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="allow_keyword">Allow Keyword</option>
                                <option value="block_topic">Block Topic</option>
                                <option value="regex">Regex</option>
                                <option value="source_policy">Source Policy</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="ai_context_id" class="form-select">
                                <option value="">Global</option>
                                @foreach ($contexts as $ctx)
                                    <option value="{{ $ctx->id }}">{{ $ctx->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input name="value" class="form-control" placeholder="Rule value">
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
                            <th>Type</th>
                            <th>Context</th>
                            <th>Value</th>
                            <th>Status</th>
                            <th width="140">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rules as $r)
                            <tr data-id="{{ $r->id }}">
                                <td>{{ strtoupper(str_replace('_', ' ', $r->type)) }}</td>
                                <td>{{ $r->context?->code ?? 'GLOBAL' }}</td>
                                <td>
                                    <input class="form-control form-control-sm value" value="{{ $r->value }}">
                                </td>
                                <td>
                                    <select class="form-select form-select-sm status">
                                        <option value="1" @selected($r->is_active)>Active</option>
                                        <option value="0" @selected(!$r->is_active)>Inactive</option>
                                    </select>
                                </td>
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
            axios.post('/ai/rules', new FormData(this))
                .then(() => location.reload())
        })

        document.querySelectorAll('.btn-save').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr')
                axios.put('/ai/rules/' + tr.dataset.id, {
                    value: tr.querySelector('.value').value,
                    is_active: tr.querySelector('.status').value
                }).then(() => location.reload())
            })
        })

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr')
                axios.delete('/ai/rules/' + tr.dataset.id)
                    .then(() => location.reload())
            })
        })
    </script>
@endpush
