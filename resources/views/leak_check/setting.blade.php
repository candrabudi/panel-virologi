@extends('layouts.app')
@section('title', 'Leak Check Settings')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="mb-8">
            <h2 class="text-xl font-semibold">Settings</h2>
            <p class="text-sm text-slate-500">
                Configure Leak Intelligence API
            </p>
        </div>

        <form id="ajax-form" data-url="{{ route('leak_check.update_settings') }}" onsubmit="return false;" class="grid grid-cols-12 gap-6" autocomplete="off">

            <div class="col-span-12 lg:col-span-12 space-y-6">

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 pb-3">
                        API Configuration
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">API Endpoint</label>
                        <input type="url" name="api_endpoint" value="{{ $setting->api_endpoint ?? '' }}"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="https://api.leakcheck.io/public" autocomplete="off">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">API Token / Key</label>
                        <div class="relative">
                            <input type="password" id="api_token" name="api_token" value="{{ $setting->api_token ?? '' }}"
                                class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control pr-10"
                                placeholder="Your Secret API Token" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');">
                            <button type="button" class="absolute inset-y-0 right-0 px-3 flex items-center text-slate-400 hover:text-primary transition-colors focus:outline-none" onclick="toggleTokenVisibility()">
                                <i id="eye-icon" data-lucide="eye" class="w-4 h-4"></i>
                                <i id="eye-off-icon" data-lucide="eye-off" class="w-4 h-4 hidden"></i>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-slate-400">Keep this token secure. It is used to authenticate requests.</p>
                    </div>

                    <div class="mt-3 grid grid-cols-12 gap-4">
                        <div class="col-span-12 sm:col-span-6">
                            <label class="block mb-2 text-sm font-medium">Bot Name</label>
                            <input type="text" name="bot_name" value="{{ $setting->bot_name ?? 'LeakAuditBot' }}"
                                class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                                placeholder="Bot Name">
                        </div>
                        <div class="col-span-12 sm:col-span-6">
                             <label class="block mb-2 text-sm font-medium">Default Limit</label>
                            <input type="number" name="default_limit" value="{{ $setting->default_limit ?? 100 }}" min="1" max="10000"
                                class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                                placeholder="100">
                        </div>
                    </div>
                     <div class="mt-3 grid grid-cols-12 gap-4 mb-3">
                         <div class="col-span-12 sm:col-span-6">
                             <label class="block mb-2 text-sm font-medium">Language</label>
                             <select name="lang" class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control">
                                 <option value="en" {{ ($setting->lang ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                                 <option value="ru" {{ ($setting->lang ?? '') == 'ru' ? 'selected' : '' }}>Russian</option>
                                 <option value="zh" {{ ($setting->lang ?? '') == 'zh' ? 'selected' : '' }}>Chinese</option>
                             </select>
                         </div>
                        <div class="col-span-12 sm:col-span-6">
                            <label class="block mb-2 text-sm font-medium">Feature Status</label>
                            <select name="is_enabled"
                                class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control">
                                <option value="1" {{ ($setting->is_enabled ?? 0) == 1 ? 'selected' : '' }}>Enabled</option>
                                <option value="0" {{ ($setting->is_enabled ?? 0) == 0 ? 'selected' : '' }}>Disabled</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" id="btn-save"
                        class="px-6 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90 flex items-center gap-2 mt-3">
                        <span class="btn-text">Save Configuration</span>

                        <svg id="btn-spinner" class="hidden w-4 h-4 animate-spin" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                                fill="none" opacity="0.25" />
                            <path d="M22 12a10 10 0 0 1-10 10" stroke="currentColor" stroke-width="4" />
                        </svg>
                    </button>

                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        function toggleTokenVisibility() {
            const tokenInput = document.getElementById('api_token');
            const eyeIcon = document.getElementById('eye-icon');
            const eyeOffIcon = document.getElementById('eye-off-icon');

            if (tokenInput.type === 'password') {
                tokenInput.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeOffIcon.classList.remove('hidden');
            } else {
                tokenInput.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeOffIcon.classList.add('hidden');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {

            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
            axios.defaults.headers.common['X-CSRF-TOKEN'] =
                document.querySelector('meta[name="csrf-token"]').getAttribute('content')

            const form = document.getElementById('ajax-form')
            if (!form) return

            const btn = document.getElementById('btn-save')
            const spinner = document.getElementById('btn-spinner')
            const text = btn.querySelector('.btn-text')
            const url = form.dataset.url

            form.addEventListener('submit', async (e) => {
                e.preventDefault()

                btn.disabled = true
                spinner.classList.remove('hidden')
                text.classList.add('hidden')
                
                // Remove previous styles errors
                form.querySelectorAll('.border-danger').forEach(el => el.classList.remove('border-danger'));

                try {
                    const formData = new FormData(form)
                    // Explicitly handle checkbox/boolean if needed, but select works fine
                    
                    const response = await axios.post(url, formData)

                    showToast(
                        'success',
                        'Success',
                        response.data.message ?? 'Settings updated successfully'
                    )

                    // Optional: form.reset() - usually we want to keep settings visible

                } catch (error) {

                    if (error.response?.status === 422) {
                        const errors = error.response.data.errors;
                        const messages = Object.values(errors)
                            .flat()
                            .join('<br>')

                        showToast('failed', 'Validation Error', messages)
                        
                        // Highlight fields
                         Object.keys(errors).forEach(key => {
                            const input = form.querySelector(`[name="${key}"]`);
                            if(input) input.classList.add('border-danger');
                        });

                    } else {
                        showToast('failed', 'Error', 'System error occurred. Please try again.')
                    }
                }

                btn.disabled = false
                spinner.classList.add('hidden')
                text.classList.remove('hidden')
            })
        })
    </script>
@endpush