@extends('template.app')

@section('title', 'AI Prompt Binding')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">AI Prompt Binding</h4>
        </div>

        <div class="card mb-4 shadow-sm">
            <div class="card-body">
                <form id="form-bind">
                    @csrf
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">AI Context</label>
                            <select name="ai_context_id" class="form-select" required>
                                @foreach ($contexts as $ctx)
                                    <option value="{{ $ctx->id }}">{{ $ctx->code }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Prompt Template</label>
                            <select name="ai_prompt_template_id" class="form-select" required>
                                @foreach ($prompts as $p)
                                    <option value="{{ $p->id }}">
                                        {{ strtoupper($p->type) }} — {{ \Illuminate\Support\Str::limit($p->content, 80) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary">Bind</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body p-0">

                <div id="skeleton">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="d-flex align-items-center px-4 py-3 border-bottom gap-3">
                            <div class="skeleton w-25"></div>
                            <div class="skeleton w-15"></div>
                            <div class="skeleton flex-fill"></div>
                            <div class="skeleton w-10"></div>
                        </div>
                    @endfor
                </div>

                <table class="table mb-0 d-none" id="table">
                    <thead class="table-light">
                        <tr>
                            <th>Context</th>
                            <th>Type</th>
                            <th>Prompt</th>
                            <th class="text-center" width="120">Active</th>
                        </tr>
                    </thead>
                    <tbody id="tbody"></tbody>
                </table>

                <div id="empty" class="text-center py-5 d-none text-muted">
                    No bindings found
                </div>

            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        .skeleton {
            height: 14px;
            border-radius: 6px;
            background: linear-gradient(90deg, #eee, #f5f5f5, #eee);
            background-size: 200% 100%;
            animation: shimmer 1.2s infinite;
        }

        .w-10 {
            width: 10%
        }

        .w-15 {
            width: 15%
        }

        .w-25 {
            width: 25%
        }

        @keyframes shimmer {
            0% {
                background-position: 200% 0
            }

            100% {
                background-position: -200% 0
            }
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const skeleton = document.getElementById('skeleton')
        const table = document.getElementById('table')
        const tbody = document.getElementById('tbody')
        const empty = document.getElementById('empty')

        function loadData() {
            axios.get('/ai/bindings/list')
                .then(res => {
                    skeleton.classList.add('d-none')
                    const rows = res.data.data.data

                    if (!rows.length) {
                        empty.classList.remove('d-none')
                        return
                    }

                    table.classList.remove('d-none')
                    tbody.innerHTML = ''

                    rows.forEach(row => {
                        const tr = document.createElement('tr')
                        tr.dataset.id = row.id
                        tr.innerHTML = `
                    <td>${row.context.code}</td>
                    <td><span class="badge bg-secondary">${row.prompt.type.toUpperCase()}</span></td>
                    <td class="text-muted">${row.prompt.content_preview}</td>
                    <td class="text-center">
                        <div class="form-check form-switch d-inline-block">
                            <input class="form-check-input toggle"
                                type="checkbox"
                                ${row.is_active ? 'checked' : ''}>
                        </div>
                    </td>
                `
                        tbody.appendChild(tr)
                    })
                })
        }

        document.getElementById('form-bind').addEventListener('submit', e => {
            e.preventDefault()
            axios.post('/ai/bindings', new FormData(e.target))
                .then(() => {
                    table.classList.add('d-none')
                    empty.classList.add('d-none')
                    skeleton.classList.remove('d-none')
                    loadData()
                })
        })

        tbody.addEventListener('change', e => {
            if (!e.target.classList.contains('toggle')) return
            const tr = e.target.closest('tr')
            axios.patch('/ai/bindings/' + tr.dataset.id, {
                is_active: e.target.checked ? 1 : 0
            }).catch(() => {
                e.target.checked = !e.target.checked
            })
        })

        loadData()
    </script>
@endpush
