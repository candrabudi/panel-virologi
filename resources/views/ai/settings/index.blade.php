@extends('layouts.app')

@section('title', 'AI Settings')

@push('styles')
    <style>
        .ai-settings {
            --ai-ink: #1f2937;
            --ai-muted: #64748b;
            --ai-border: #e5e7eb;
            --ai-control-border: #cbd5e1;
            --ai-accent: #03045e;
            --ai-surface: #fff;
            width: 100%;
            min-width: 0;
            color: var(--ai-ink);
        }

        .ai-settings,
        .ai-settings * {
            box-sizing: border-box;
        }

        .ai-settings__header {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: 1.5rem;
            margin-bottom: 1.75rem;
        }

        .ai-settings__title {
            margin: 0;
            color: #1e293b;
            font-size: 1.5rem;
            font-weight: 600;
            line-height: 1.25;
        }

        .ai-settings__intro {
            max-width: 42rem;
            margin: .5rem 0 0;
            color: var(--ai-muted);
            font-size: .875rem;
            line-height: 1.5rem;
        }

        .ai-settings__status {
            display: inline-flex;
            flex: none;
            align-items: center;
            gap: .625rem;
            min-height: 2.5rem;
            border: 1px solid var(--ai-border);
            border-radius: .5rem;
            padding: .5rem .75rem;
            background: var(--ai-surface);
            color: #475569;
            font-size: .8125rem;
            font-weight: 500;
            line-height: 1.25rem;
        }

        .ai-settings__status-dot {
            width: .5rem;
            height: .5rem;
            flex: none;
            border-radius: 50%;
            background: #94a3b8;
        }

        .ai-settings__status.is-active .ai-settings__status-dot {
            background: #16a34a;
        }

        .ai-settings__status.is-inactive .ai-settings__status-dot {
            background: #94a3b8;
        }

        .ai-settings__alert {
            display: flex;
            align-items: flex-start;
            gap: .625rem;
            margin: 0 0 1.25rem;
            border: 1px solid #bbf7d0;
            border-radius: .5rem;
            padding: .75rem .875rem;
            background: #f0fdf4;
            color: #166534;
            font-size: .875rem;
            line-height: 1.375rem;
        }

        .ai-settings__alert.is-error {
            border-color: #fecaca;
            background: #fef2f2;
            color: #991b1b;
        }

        .ai-settings__alert[hidden],
        .ai-settings__spinner[hidden] {
            display: none;
        }

        .ai-settings__sections {
            display: flex;
            flex-direction: column;
        }

        .ai-settings__section {
            display: grid;
            grid-template-columns: minmax(13rem, .85fr) minmax(0, 1.65fr);
            gap: 2rem;
            border-bottom: 1px solid var(--ai-border);
            padding: 1.75rem 0;
        }

        .ai-settings__section:first-child {
            padding-top: 0;
        }

        .ai-settings__section:last-child {
            border-bottom: 0;
            padding-bottom: 1.5rem;
        }

        .ai-settings__section-copy {
            padding-top: .375rem;
        }

        .ai-settings__section-title {
            margin: 0;
            color: #1f2937;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.5rem;
        }

        .ai-settings__section-description {
            max-width: 26rem;
            margin: .5rem 0 0;
            color: var(--ai-muted);
            font-size: .875rem;
            line-height: 1.375rem;
        }

        .ai-settings__panel {
            min-width: 0;
            border: 1px solid var(--ai-border);
            border-radius: .625rem;
            padding: 1.25rem;
            background: var(--ai-surface);
        }

        .ai-settings__field {
            display: grid;
            min-width: 0;
            gap: .5rem;
        }

        .ai-settings__field + .ai-settings__field,
        .ai-settings__field-grid + .ai-settings__field,
        .ai-settings__field + .ai-settings__field-grid {
            margin-top: 1rem;
        }

        .ai-settings__field-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 1rem;
        }

        .ai-settings__label {
            display: block;
            color: #374151;
            font-size: .875rem;
            font-weight: 500;
            line-height: 1.25rem;
        }

        .ai-settings__control {
            display: block;
            width: 100%;
            height: 2.5rem;
            min-width: 0;
            border: 1px solid var(--ai-control-border);
            border-radius: .4375rem;
            padding: 0 .75rem;
            background: #fff;
            color: #1f2937;
            font: inherit;
            font-size: .875rem;
            line-height: 1.25rem;
            transition: border-color .15s ease, box-shadow .15s ease;
        }

        .ai-settings__control::placeholder {
            color: #94a3b8;
            opacity: 1;
        }

        .ai-settings__control:focus {
            border-color: var(--ai-accent);
            outline: 0;
            box-shadow: 0 0 0 3px rgb(3 4 94 / 10%);
        }

        .ai-settings select.ai-settings__control {
            appearance: none;
            padding-right: 2.5rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none'%3E%3Cpath d='m5 7.5 5 5 5-5' stroke='%2364748b' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-position: right .75rem center;
            background-repeat: no-repeat;
            background-size: 1rem 1rem;
        }

        .ai-settings__control.is-invalid {
            border-color: #dc2626;
        }

        .ai-settings__helper {
            margin: 0;
            color: var(--ai-muted);
            font-size: .75rem;
            line-height: 1.125rem;
        }

        .ai-settings__secret {
            position: relative;
        }

        .ai-settings__secret .ai-settings__control {
            padding-right: 2.75rem;
        }

        .ai-settings__secret-toggle {
            position: absolute;
            top: 0;
            right: 0;
            display: inline-flex;
            width: 2.5rem;
            height: 2.5rem;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 0 .4375rem .4375rem 0;
            padding: 0;
            background: transparent;
            color: #64748b;
            cursor: pointer;
        }

        .ai-settings__secret-toggle:hover {
            color: #1f2937;
        }

        .ai-settings__secret-toggle:focus-visible,
        .ai-settings__submit:focus-visible {
            outline: 2px solid var(--ai-accent);
            outline-offset: 2px;
        }

        .ai-settings__icon {
            display: block;
            width: 1rem;
            height: 1rem;
            flex: none;
            stroke-width: 1.75;
        }

        .ai-settings__icon[hidden] {
            display: none;
        }

        .ai-settings__switch-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
        }

        .ai-settings__switch-row + .ai-settings__switch-row {
            margin-top: 1rem;
            border-top: 1px solid #eef0f3;
            padding-top: 1rem;
        }

        .ai-settings__switch-copy {
            min-width: 0;
        }

        .ai-settings__switch-label {
            display: block;
            color: #1f2937;
            font-size: .875rem;
            font-weight: 500;
            line-height: 1.25rem;
            cursor: pointer;
        }

        .ai-settings__switch-description {
            margin: .25rem 0 0;
            color: var(--ai-muted);
            font-size: .8125rem;
            line-height: 1.25rem;
        }

        .ai-settings__switch {
            position: relative;
            display: block;
            width: 2.5rem;
            height: 1.5rem;
            flex: none;
            appearance: none;
            border: 0;
            border-radius: 9999px;
            padding: 0;
            background: #cbd5e1;
            cursor: pointer;
            transition: background-color .15s ease;
        }

        .ai-settings__switch::before {
            position: absolute;
            top: .1875rem;
            left: .1875rem;
            width: 1.125rem;
            height: 1.125rem;
            border-radius: 50%;
            background: #fff;
            content: '';
            transition: transform .15s ease;
        }

        .ai-settings__switch:checked {
            background: #15803d;
        }

        .ai-settings__switch:checked::before {
            transform: translateX(1rem);
        }

        .ai-settings__switch:focus-visible {
            outline: 2px solid #15803d;
            outline-offset: 3px;
        }

        .ai-settings__actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            border-top: 1px solid var(--ai-border);
            padding-top: 1.25rem;
        }

        .ai-settings__actions-note {
            margin: 0;
            color: var(--ai-muted);
            font-size: .8125rem;
            line-height: 1.25rem;
        }

        .ai-settings__submit {
            display: inline-flex;
            min-height: 2.625rem;
            flex: none;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            border: 1px solid var(--ai-accent);
            border-radius: .4375rem;
            padding: .5rem 1rem;
            background: var(--ai-accent);
            color: #fff;
            font: inherit;
            font-size: .875rem;
            font-weight: 500;
            line-height: 1.25rem;
            cursor: pointer;
            transition: background-color .15s ease, border-color .15s ease;
        }

        .ai-settings__submit:hover:not(:disabled) {
            border-color: #10116f;
            background: #10116f;
        }

        .ai-settings__submit:disabled {
            cursor: wait;
            opacity: .7;
        }

        .ai-settings__spinner {
            width: 1rem;
            height: 1rem;
            border: 2px solid rgb(255 255 255 / 40%);
            border-top-color: #fff;
            border-radius: 50%;
            animation: ai-settings-spin .7s linear infinite;
        }

        @keyframes ai-settings-spin {
            to { transform: rotate(360deg); }
        }

        .dark .ai-settings {
            --ai-ink: #e2e8f0;
            --ai-muted: #94a3b8;
            --ai-border: #334155;
            --ai-control-border: #475569;
            --ai-surface: #1e293b;
            color: var(--ai-ink);
        }

        .dark .ai-settings__title,
        .dark .ai-settings__section-title,
        .dark .ai-settings__label,
        .dark .ai-settings__switch-label {
            color: #e2e8f0;
        }

        .dark .ai-settings__status {
            color: #cbd5e1;
        }

        .dark .ai-settings__control {
            border-color: var(--ai-control-border);
            background: #0f172a;
            color: #e2e8f0;
        }

        .dark .ai-settings__control:focus {
            border-color: #818cf8;
            box-shadow: 0 0 0 3px rgb(129 140 248 / 18%);
        }

        .dark .ai-settings select.ai-settings__control {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 20 20' fill='none'%3E%3Cpath d='m5 7.5 5 5 5-5' stroke='%23cbd5e1' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
        }

        .dark .ai-settings__switch-row + .ai-settings__switch-row {
            border-color: #334155;
        }

        @media (max-width: 900px) {
            .ai-settings__section {
                grid-template-columns: minmax(0, 1fr) minmax(0, 1.6fr);
                gap: 1.5rem;
            }
        }

        @media (max-width: 700px) {
            .ai-settings__header {
                align-items: flex-start;
                flex-direction: column;
                gap: 1rem;
                margin-bottom: 1.25rem;
            }

            .ai-settings__section {
                grid-template-columns: minmax(0, 1fr);
                gap: .875rem;
                padding: 1.375rem 0;
            }

            .ai-settings__section:first-child {
                padding-top: 0;
            }

            .ai-settings__section-copy {
                padding-top: 0;
            }
        }

        @media (max-width: 600px) {
            .ai-settings__panel {
                padding: 1rem;
            }

            .ai-settings__field-grid {
                grid-template-columns: minmax(0, 1fr);
                gap: .875rem;
            }

            .ai-settings__actions {
                align-items: stretch;
                flex-direction: column;
            }

            .ai-settings__submit {
                width: 100%;
            }
        }
    </style>
@endpush

@section('content')
    <div class="col-span-12 ai-settings">
        <header class="ai-settings__header">
            <div>
                <h1 class="ai-settings__title">AI settings</h1>
                <p class="ai-settings__intro">
                    Configure the provider, model limits, and security rules used by AI features.
                </p>
            </div>

            <div id="ai-service-status"
                class="ai-settings__status {{ ($setting?->is_active) ? 'is-active' : 'is-inactive' }}"
                aria-live="polite">
                <span class="ai-settings__status-dot" aria-hidden="true"></span>
                <span id="ai-service-status-label">{{ ($setting?->is_active) ? 'AI service active' : 'AI service disabled' }}</span>
            </div>
        </header>

        <div id="ai-settings-alert" class="ai-settings__alert" role="status" aria-live="polite" hidden></div>

        <form id="form-setting" method="post" action="{{ url('/ai/settings') }}"
            data-url="{{ url('/ai/settings') }}" autocomplete="off">
            @csrf

            <div class="ai-settings__sections">
                <section class="ai-settings__section" aria-labelledby="ai-connection-title">
                    <div class="ai-settings__section-copy">
                        <h2 id="ai-connection-title" class="ai-settings__section-title">Connection</h2>
                        <p class="ai-settings__section-description">
                            Choose the provider and endpoint used to connect AI features.
                        </p>
                    </div>

                    <div class="ai-settings__panel">
                        <div class="ai-settings__field">
                            <label for="ai-provider" class="ai-settings__label">Provider</label>
                            <select id="ai-provider" name="provider" class="ai-settings__control" required>
                                <option value="openai" @selected(($setting->provider ?? 'openai') === 'openai')>OpenAI</option>
                                <option value="azure" @selected(($setting->provider ?? '') === 'azure')>Azure OpenAI</option>
                                <option value="custom" @selected(($setting->provider ?? '') === 'custom')>Custom provider</option>
                            </select>
                        </div>

                        <div class="ai-settings__field">
                            <label for="ai-base-url" class="ai-settings__label">Base URL</label>
                            <input id="ai-base-url" name="base_url" type="url" class="ai-settings__control"
                                placeholder="https://api.openai.com/v1" value="{{ $setting->base_url ?? '' }}"
                                aria-describedby="ai-base-url-help">
                            <p id="ai-base-url-help" class="ai-settings__helper">
                                Use the provider endpoint. Leave blank if the selected provider uses its default URL.
                            </p>
                        </div>

                        <div class="ai-settings__field">
                            <label for="ai-api-key" class="ai-settings__label">API key</label>
                            <div class="ai-settings__secret">
                                <input id="ai-api-key" name="api_key" type="password" maxlength="255"
                                    class="ai-settings__control" placeholder="Enter a new API key"
                                    autocomplete="new-password" aria-describedby="ai-api-key-help">
                                <button id="ai-api-key-toggle" type="button" class="ai-settings__secret-toggle"
                                    aria-label="Show API key" aria-pressed="false" title="Show API key">
                                    <i data-lucide="eye" class="ai-settings__icon" aria-hidden="true"></i>
                                    <i data-lucide="eye-off" class="ai-settings__icon" aria-hidden="true" hidden></i>
                                </button>
                            </div>
                            <p id="ai-api-key-help" class="ai-settings__helper">
                                The saved key is never displayed. Leave this empty to keep the current key.
                            </p>
                        </div>
                    </div>
                </section>

                <section class="ai-settings__section" aria-labelledby="ai-model-title">
                    <div class="ai-settings__section-copy">
                        <h2 id="ai-model-title" class="ai-settings__section-title">Model and limits</h2>
                        <p class="ai-settings__section-description">
                            Set the model identifier and control response length and request timing.
                        </p>
                    </div>

                    <div class="ai-settings__panel">
                        <div class="ai-settings__field">
                            <label for="ai-model" class="ai-settings__label">Model</label>
                            <input id="ai-model" name="model" type="text" maxlength="100" required
                                class="ai-settings__control" value="{{ $setting->model ?? 'gpt-4.1-mini' }}"
                                placeholder="gpt-4.1-mini">
                        </div>

                        <div class="ai-settings__field-grid">
                            <div class="ai-settings__field">
                                <label for="ai-temperature" class="ai-settings__label">Temperature</label>
                                <input id="ai-temperature" name="temperature" type="number" step="0.1"
                                    min="0" max="2" required class="ai-settings__control"
                                    value="{{ $setting->temperature ?? 0.7 }}">
                                <p class="ai-settings__helper">0 to 2</p>
                            </div>

                            <div class="ai-settings__field">
                                <label for="ai-max-tokens" class="ai-settings__label">Max tokens</label>
                                <input id="ai-max-tokens" name="max_tokens" type="number" min="1"
                                    max="8192" required class="ai-settings__control"
                                    value="{{ $setting->max_tokens ?? 2048 }}">
                                <p class="ai-settings__helper">1 to 8,192</p>
                            </div>

                            <div class="ai-settings__field">
                                <label for="ai-timeout" class="ai-settings__label">Timeout</label>
                                <input id="ai-timeout" name="timeout" type="number" min="1" max="120"
                                    required class="ai-settings__control" value="{{ $setting->timeout ?? 30 }}">
                                <p class="ai-settings__helper">Seconds, up to 120</p>
                            </div>
                        </div>
                    </div>
                </section>

                <section class="ai-settings__section" aria-labelledby="ai-security-title">
                    <div class="ai-settings__section-copy">
                        <h2 id="ai-security-title" class="ai-settings__section-title">Security and policy</h2>
                        <p class="ai-settings__section-description">
                            Decide whether AI features are available and which topics they can handle.
                        </p>
                    </div>

                    <div class="ai-settings__panel">
                        <div class="ai-settings__switch-row">
                            <div class="ai-settings__switch-copy">
                                <label for="ai-is-active" class="ai-settings__switch-label">AI service active</label>
                                <p id="ai-is-active-help" class="ai-settings__switch-description">
                                    Turn this off to disable all AI features.
                                </p>
                            </div>
                            <input id="ai-is-active" class="ai-settings__switch" type="checkbox"
                                name="is_active" value="1" aria-describedby="ai-is-active-help"
                                @checked($setting?->is_active)>
                        </div>

                        <div class="ai-settings__switch-row">
                            <div class="ai-settings__switch-copy">
                                <label for="ai-cybersecurity-only" class="ai-settings__switch-label">
                                    Cybersecurity only mode
                                </label>
                                <p id="ai-cybersecurity-only-help" class="ai-settings__switch-description">
                                    Restrict AI responses to cybersecurity topics.
                                </p>
                            </div>
                            <input id="ai-cybersecurity-only" class="ai-settings__switch" type="checkbox"
                                name="cybersecurity_only" value="1" aria-describedby="ai-cybersecurity-only-help"
                                @checked($setting?->cybersecurity_only)>
                        </div>
                    </div>
                </section>
            </div>

            <div class="ai-settings__actions">
                <p class="ai-settings__actions-note">Changes take effect after you save.</p>
                <button id="ai-settings-submit" type="submit" class="ai-settings__submit">
                    <span class="ai-settings__spinner" aria-hidden="true" hidden></span>
                    <span id="ai-settings-submit-label">Save changes</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.getElementById('form-setting');
            const alertBox = document.getElementById('ai-settings-alert');
            const saveButton = document.getElementById('ai-settings-submit');
            const saveLabel = document.getElementById('ai-settings-submit-label');
            const spinner = saveButton.querySelector('.ai-settings__spinner');
            const apiKeyInput = document.getElementById('ai-api-key');
            const apiKeyToggle = document.getElementById('ai-api-key-toggle');
            const serviceStatus = document.getElementById('ai-service-status');
            const serviceStatusLabel = document.getElementById('ai-service-status-label');

            function showAlert(type, message) {
                alertBox.classList.toggle('is-error', type === 'error');
                alertBox.textContent = message;
                alertBox.hidden = false;
            }

            function setSaving(isSaving) {
                saveButton.disabled = isSaving;
                saveButton.setAttribute('aria-busy', isSaving ? 'true' : 'false');
                spinner.hidden = !isSaving;
                saveLabel.textContent = isSaving ? 'Saving…' : 'Save changes';
            }

            function updateServiceStatus(isActive) {
                serviceStatus.classList.toggle('is-active', isActive);
                serviceStatus.classList.toggle('is-inactive', !isActive);
                serviceStatusLabel.textContent = isActive ? 'AI service active' : 'AI service disabled';
            }

            apiKeyToggle.addEventListener('click', function () {
                const isVisible = apiKeyInput.type === 'text';
                apiKeyInput.type = isVisible ? 'password' : 'text';
                this.setAttribute('aria-pressed', isVisible ? 'false' : 'true');
                this.setAttribute('aria-label', isVisible ? 'Show API key' : 'Hide API key');
                this.title = isVisible ? 'Show API key' : 'Hide API key';
                this.querySelector('[data-lucide="eye"]').hidden = !isVisible;
                this.querySelector('[data-lucide="eye-off"]').hidden = isVisible;
                apiKeyInput.focus();
            });

            form.querySelectorAll('.ai-settings__control').forEach(function (field) {
                field.addEventListener('input', function () {
                    field.classList.remove('is-invalid');
                });
                field.addEventListener('change', function () {
                    field.classList.remove('is-invalid');
                });
            });

            form.addEventListener('submit', async function (event) {
                event.preventDefault();
                alertBox.hidden = true;
                form.querySelectorAll('.is-invalid').forEach(function (field) {
                    field.classList.remove('is-invalid');
                });

                const formData = new FormData(form);
                if (!formData.has('is_active')) formData.append('is_active', '0');
                if (!formData.has('cybersecurity_only')) formData.append('cybersecurity_only', '0');

                setSaving(true);

                try {
                    const response = await axios.post(form.dataset.url, formData, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    });

                    updateServiceStatus(formData.get('is_active') === '1');
                    showAlert('success', response.data?.message || 'AI settings saved successfully.');
                } catch (error) {
                    const errors = error.response?.data?.errors || {};
                    const messages = Object.values(errors).flat();

                    Object.keys(errors).forEach(function (name) {
                        const field = form.querySelector('[name="' + name + '"]');
                        if (field) field.classList.add('is-invalid');
                    });

                    const message = messages[0]
                        || error.response?.data?.message
                        || 'Could not save AI settings. Please try again.';
                    showAlert('error', message);
                } finally {
                    setSaving(false);
                }
            });
        });
    </script>
@endpush
