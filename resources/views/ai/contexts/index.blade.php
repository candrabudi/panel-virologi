@extends('layouts.app')

@section('title', 'AI Contexts')

@section('content')
    <div class="container-fluid">

        <div class="mb-4">
            <h4 class="mb-1">AI Contexts</h4>
            <small class="text-muted">Editable AI context & knowledge source management</small>
        </div>

        <div class="card shadow-sm rounded-3 mb-4">
            <div class="card-body">
                <form id="form-add">
                    @csrf
                    <div class="row g-2 align-items-end">
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Code</label>
                            <input name="code" class="form-control" placeholder="learning">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small text-muted">Context Name</label>
                            <input name="name" class="form-control" placeholder="Cybersecurity Learning">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small text-muted">Knowledge Source</label>
                            <select name="use_internal_source" class="form-select">
                                <option value="0">GPT Knowledge</option>
                                <option value="1">Internal Only</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-primary w-100">
                                <i class="ri ri-add-line me-1"></i> Tambah
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow-sm rounded-3">
            <div class="card-body p-0">

                <div id="skeleton">
                    @for ($i = 0; $i < 6; $i++)
                        <div class="d-flex align-items-center gap-3 px-4 py-3 border-bottom">
                            <div class="skeleton w-10"></div>
                            <div class="skeleton w-30"></div>
                            <div class="skeleton w-20"></div>
                            <div class="skeleton w-15"></div>
                            <div class="skeleton w-10"></div>
                        </div>
                    @endfor
                </div>

                <table class="table mb-0 d-none" id="table">
                    <thead class="table-light">
                        <tr>
                            <th width="140">Code</th>
                            <th>Name</th>
                            <th width="160">Source</th>
                            <th width="120">Status</th>
                            <th width="80"></th>
                        </tr>
                    </thead>
                    <tbody id="tbody"></tbody>
                </table>

            </div>
        </div>

    </div>
@endsection

@push('styles')
    <style>
        .skeleton {
            height: 14px;
            border-radius: 6px;
            background: linear-gradient(90deg, #eee 25%, #e0e0e0 37%, #eee 63%);
            background-size: 400% 100%;
            animation: shimmer 1.4s ease infinite;
        }

        .w-10 {
            width: 10%
        }

        .w-15 {
            width: 15%
        }

        .w-20 {
            width: 20%
        }

        .w-30 {
            width: 30%
        }

        @keyframes shimmer {
            0% {
                background-position: 100% 0
            }

            100% {
                background-position: 0 0
            }
        }

        .toggle {
            width: 42px;
            height: 22px;
            border-radius: 999px;
            background: #dee2e6;
            position: relative;
            cursor: pointer;
            transition: all .2s;
        }

        .toggle.active {
            background: #0d6efd;
        }

        .toggle span {
            position: absolute;
            top: 3px;
            left: 3px;
            width: 16px;
            height: 16px;
            background: white;
            border-radius: 50%;
            transition: all .2s;
        }

        .toggle.active span {
            transform: translateX(20px);
        }

        .row-saving {
            opacity: .6;
            pointer-events: none;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] =
            document.querySelector('meta[name="csrf-token"]').getAttribute('content')

        const tbody = document.getElementById('tbody')
        const table = document.getElementById('table')
        const skeleton = document.getElementById('skeleton')

        function loadData() {
            skeleton.classList.remove('d-none')
            table.classList.add('d-none')

            axios.get('/ai/contexts/list')
                .then(res => {
                    tbody.innerHTML = ''

                    res.data.data.data.forEach(row => {
                        tbody.innerHTML += `
                <tr data-id="${row.id}">
                    <td class="fw-semibold">${row.code}</td>
                    <td>
                        <input class="form-control form-control-sm name" value="${row.name}">
                    </td>
                    <td>
                        <div class="toggle ${row.use_internal_source ? 'active' : ''} toggle-source">
                            <span></span>
                        </div>
                        <small class="text-muted ms-2 source-label">
                            ${row.use_internal_source ? 'Internal' : 'GPT'}
                        </small>
                    </td>
                    <td>
                        <div class="toggle ${row.is_active ? 'active' : ''} toggle-status">
                            <span></span>
                        </div>
                    </td>
                    <td class="text-muted small status-text"></td>
                </tr>`
                    })

                    skeleton.classList.add('d-none')
                    table.classList.remove('d-none')
                })
        }

        loadData()

        document.getElementById('form-add').addEventListener('submit', e => {
            e.preventDefault()
            axios.post('/ai/contexts', new FormData(e.target))
                .then(() => {
                    e.target.reset()
                    loadData()
                })
        })

        function saveRow(tr) {
            tr.classList.add('row-saving')
            tr.querySelector('.status-text').innerText = 'Saving...'

            axios.put('/ai/contexts/' + tr.dataset.id, {
                name: tr.querySelector('.name').value,
                use_internal_source: tr.querySelector('.toggle-source').classList.contains('active') ? 1 : 0,
                is_active: tr.querySelector('.toggle-status').classList.contains('active') ? 1 : 0
            }).then(() => {
                tr.querySelector('.status-text').innerText = 'Saved'
                setTimeout(() => tr.querySelector('.status-text').innerText = '', 1000)
            }).finally(() => {
                tr.classList.remove('row-saving')
            })
        }

        document.addEventListener('change', e => {
            if (e.target.classList.contains('name')) {
                saveRow(e.target.closest('tr'))
            }
        })

        document.addEventListener('click', e => {
            if (e.target.closest('.toggle')) {
                const toggle = e.target.closest('.toggle')
                toggle.classList.toggle('active')

                if (toggle.classList.contains('toggle-source')) {
                    toggle.nextElementSibling.innerText =
                        toggle.classList.contains('active') ? 'Internal' : 'GPT'
                }

                saveRow(toggle.closest('tr'))
            }
        })
    </script>
@endpush
