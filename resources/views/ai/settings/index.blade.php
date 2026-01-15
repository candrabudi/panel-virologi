@extends('layouts.app')

@section('title', 'AI Settings')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mb-0">AI Settings</h4>
        </div>

        <div id="alert-box" class="alert d-none"></div>

        <form id="form-setting">
            @csrf

            <div class="row g-4">

                {{-- CONNECTION --}}
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white">
                            <strong>Connection</strong>
                            <div class="text-muted small">Provider & endpoint configuration</div>
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Provider</label>
                                <select name="provider" class="form-select">
                                    <option value="openai" @selected(($setting->provider ?? '') === 'openai')>OpenAI</option>
                                    <option value="azure" @selected(($setting->provider ?? '') === 'azure')>Azure OpenAI</option>
                                    <option value="custom" @selected(($setting->provider ?? '') === 'custom')>Custom</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Base URL</label>
                                <input name="base_url" class="form-control" placeholder="https://api.openai.com/v1"
                                    value="{{ $setting->base_url ?? '' }}">
                            </div>

                            <div class="mb-3">
                                <label class="form-label">API Key</label>
                                <input name="api_key" type="password" class="form-control" placeholder="••••••••••••••">
                                <div class="form-text">
                                    Kosongkan jika tidak ingin mengganti API key
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- MODEL --}}
                <div class="col-lg-6">
                    <div class="card shadow-sm h-100">
                        <div class="card-header bg-white">
                            <strong>Model & Limits</strong>
                            <div class="text-muted small">Model behavior & quota</div>
                        </div>
                        <div class="card-body">

                            <div class="mb-3">
                                <label class="form-label">Model</label>
                                <input name="model" class="form-control" value="{{ $setting->model ?? 'gpt-4.1-mini' }}">
                            </div>

                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Temperature</label>
                                    <input name="temperature" type="number" step="0.1" min="0" max="2"
                                        class="form-control" value="{{ $setting->temperature ?? 0.7 }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Max Tokens</label>
                                    <input name="max_tokens" type="number" min="1" max="8192"
                                        class="form-control" value="{{ $setting->max_tokens ?? 2048 }}">
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label">Timeout (sec)</label>
                                    <input name="timeout" type="number" min="1" max="120" class="form-control"
                                        value="{{ $setting->timeout ?? 30 }}">
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                {{-- SECURITY --}}
                <div class="col-lg-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <strong>Security & Policy</strong>
                            <div class="text-muted small">Operational restrictions</div>
                        </div>
                        <div class="card-body">

                            <div class="row g-4 align-items-center">

                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                            @checked($setting?->is_active)>
                                        <label class="form-check-label">
                                            AI Service Active
                                        </label>
                                        <div class="text-muted small">
                                            Nonaktifkan untuk mematikan seluruh AI service
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" name="cybersecurity_only"
                                            value="1" @checked($setting?->cybersecurity_only)>
                                        <label class="form-check-label">
                                            Cybersecurity Only Mode
                                        </label>
                                        <div class="text-muted small">
                                            Batasi AI hanya untuk topik keamanan siber
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>

                {{-- ACTION --}}
                <div class="col-lg-12">
                    <button class="btn btn-primary w-100">
                        Simpan Pengaturan
                    </button>
                </div>

            </div>
        </form>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const alertBox = document.getElementById('alert-box')

        function showAlert(type, msg) {
            alertBox.className = `alert alert-${type}`
            alertBox.textContent = msg
            alertBox.classList.remove('d-none')
            setTimeout(() => alertBox.classList.add('d-none'), 4000)
        }

        document.getElementById('form-setting').addEventListener('submit', e => {
            e.preventDefault()

            const formData = new FormData(e.target)

            if (!formData.has('is_active')) formData.append('is_active', 0)
            if (!formData.has('cybersecurity_only')) formData.append('cybersecurity_only', 0)

            axios.post('/ai/settings', formData)
                .then(res => {
                    showAlert('success', res.data.message || 'Pengaturan disimpan')
                })
                .catch(err => {
                    showAlert('danger', 'Gagal menyimpan pengaturan')
                })
        })
    </script>
@endpush
