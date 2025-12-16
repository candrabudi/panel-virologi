@extends('template.app')

@section('title', 'AI Prompt Templates')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>AI Prompt Templates</h4>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form id="form-add">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="type" class="form-select">
                                <option value="system">System</option>
                                <option value="context">Context</option>
                                <option value="fallback">Fallback</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <textarea name="content" class="form-control" rows="2" placeholder="Prompt content"></textarea>
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
                            <th width="120">Type</th>
                            <th>Prompt</th>
                            <th width="120">Status</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prompts as $prompt)
                            <tr data-id="{{ $prompt->id }}">
                                <td>{{ strtoupper($prompt->type) }}</td>
                                <td>
                                    <textarea class="form-control form-control-sm content" rows="3">{{ $prompt->content }}</textarea>
                                </td>
                                <td>
                                    <select class="form-select form-select-sm status">
                                        <option value="1" @selected($prompt->is_active)>Active</option>
                                        <option value="0" @selected(!$prompt->is_active)>Inactive</option>
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
            axios.post('/ai/prompts', new FormData(this))
                .then(() => location.reload())
        })

        document.querySelectorAll('.btn-save').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr')
                axios.put('/ai/prompts/' + tr.dataset.id, {
                    content: tr.querySelector('.content').value,
                    is_active: tr.querySelector('.status').value
                }).then(() => location.reload())
            })
        })
    </script>
@endpush
