@extends('template.app')

@section('title', 'AI Settings')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between mb-4">
            <h4>AI Settings</h4>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="form-setting">
                    @csrf

                    <div class="row g-3">

                        <div class="col-md-4">
                            <label class="form-label">Provider</label>
                            <input name="provider" class="form-control" value="{{ $setting->provider ?? 'openai' }}">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">Base URL</label>
                            <input name="base_url" class="form-control" value="{{ $setting->base_url ?? '' }}"
                                placeholder="https://api.openai.com/v1">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label">API Key</label>
                            <input name="api_key" type="password" class="form-control"
                                value="{{ $setting->api_key ?? '' }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Model</label>
                            <input name="model" class="form-control" value="{{ $setting->model ?? 'gpt-4.1-mini' }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Temperature</label>
                            <input name="temperature" type="number" step="0.1" class="form-control"
                                value="{{ $setting->temperature ?? 0.7 }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Max Tokens</label>
                            <input name="max_tokens" type="number" class="form-control"
                                value="{{ $setting->max_tokens ?? 2048 }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Timeout (sec)</label>
                            <input name="timeout" type="number" class="form-control" value="{{ $setting->timeout ?? 30 }}">
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">AI Status</label>
                            <select name="is_active" class="form-select">
                                <option value="1" @selected($setting?->is_active)>Active</option>
                                <option value="0" @selected(!$setting?->is_active)>Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label">Cybersecurity Only</label>
                            <select name="cybersecurity_only" class="form-select">
                                <option value="1" @selected($setting?->cybersecurity_only)>Yes</option>
                                <option value="0" @selected(!$setting?->cybersecurity_only)>No</option>
                            </select>
                        </div>

                        <div class="col-md-6 d-flex align-items-end">
                            <button class="btn btn-primary w-100">
                                Simpan Pengaturan
                            </button>
                        </div>

                    </div>
                </form>
            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('form-setting').addEventListener('submit', function(e) {
            e.preventDefault()
            axios.post('/ai/settings', new FormData(this))
                .then(() => location.reload())
        })
    </script>
@endpush
