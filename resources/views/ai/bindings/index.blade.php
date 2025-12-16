@extends('template.app')

@section('title', 'AI Prompt Binding')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-4">
            <h4>AI Prompt Binding</h4>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form id="form-bind">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-4">
                            <select name="ai_context_id" class="form-select">
                                @foreach ($contexts as $ctx)
                                    <option value="{{ $ctx->id }}">{{ $ctx->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <select name="ai_prompt_template_id" class="form-select">
                                @foreach ($prompts as $p)
                                    <option value="{{ $p->id }}">
                                        {{ strtoupper($p->type) }} — {{ \Illuminate\Support\Str::limit($p->content, 60) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">Bind</button>
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
                            <th>Context</th>
                            <th>Prompt Type</th>
                            <th>Prompt</th>
                            <th width="120">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bindings as $b)
                            <tr data-id="{{ $b->id }}">
                                <td>{{ $b->context->code }}</td>
                                <td>{{ strtoupper($b->prompt->type) }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($b->prompt->content, 120) }}</td>
                                <td>
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
        document.getElementById('form-bind').addEventListener('submit', function(e) {
            e.preventDefault()
            axios.post('/ai/bindings', new FormData(this))
                .then(() => location.reload())
        })

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr')
                axios.delete('/ai/bindings/' + tr.dataset.id)
                    .then(() => location.reload())
            })
        })
    </script>
@endpush
