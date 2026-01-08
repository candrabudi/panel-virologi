@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="flex flex-col gap-1 mb-8">
            <h2 class="text-xl font-semibold group-[.mode--light]:text-white">
                Website Settings management
            </h2>
        </div>

        <div class="mt-3.5 grid grid-cols-12 gap-x-6 gap-y-10">
            <div class="relative col-span-12 flex flex-col gap-y-7">
                <div class="box box--stacked flex flex-col p-5">
                    
                    <form id="website-form" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            {{-- LEFT: Branding & SEO --}}
                            <div class="space-y-8 p-5">
                                <div class="pb-3 border-b border-slate-200/70">
                                    <h3 class="text-sm font-semibold text-slate-700">Branding & Identity</h3>
                                </div>
                                
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-5">Site Name</label>
                                    <input type="text" name="site_name" value="{{ $setting->site_name ?? '' }}" class="form-control w-full rounded-md border-slate-200">
                                </div>
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-5">Site Logo</label>
                                    @if($setting && $setting->site_logo)
                                        <div class="mb-3">
                                            <img src="{{ $setting->site_logo }}" alt="Logo" class="h-12 object-contain border p-1 rounded">
                                        </div>
                                    @endif
                                    <input type="file" name="site_logo" class="form-control w-full border-slate-200">
                                </div>
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-5">Footer Logo</label>
                                    @if($setting && $setting->site_logo_footer)
                                        <div class="mb-3">
                                            <img src="{{ $setting->site_logo_footer }}" alt="Footer Logo" class="h-12 object-contain border p-1 rounded">
                                        </div>
                                    @endif
                                    <input type="file" name="site_logo_footer" class="form-control w-full border-slate-200">
                                </div>
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-5">Favicon</label>
                                    @if($setting && $setting->site_favicon)
                                        <div class="mb-3">
                                            <img src="{{ $setting->site_favicon }}" alt="Favicon" class="h-8 w-8 object-contain border p-1 rounded">
                                        </div>
                                    @endif
                                    <input type="file" name="site_favicon" class="form-control w-full border-slate-200">
                                </div>

                                <div class="pb-3 border-b border-slate-200/70 mt-10">
                                    <h3 class="text-sm font-semibold text-slate-700">SEO Meta Settings</h3>
                                </div>
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-5">Meta Title</label>
                                    <input type="text" name="meta_title" value="{{ $setting->meta_title ?? '' }}" class="form-control w-full rounded-md border-slate-200">
                                </div>
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-5">Meta Description</label>
                                    <textarea name="meta_description" rows="3" class="form-control w-full rounded-md border-slate-200">{{ $setting->meta_description ?? '' }}</textarea>
                                </div>
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-5">Meta Keywords</label>
                                    <textarea name="meta_keywords" rows="2" class="form-control w-full rounded-md border-slate-200">{{ $setting->meta_keywords ?? '' }}</textarea>
                                </div>
                            </div>

                            {{-- RIGHT: Analytics & Scripts --}}
                            <div class="space-y-8 p-5">
                                <div class="pb-3 border-b border-slate-200/70">
                                    <h3 class="text-sm font-semibold text-slate-700">Analytics & Verification</h3>
                                </div>
                                
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-5">Google Analytics ID</label>
                                    <input type="text" name="google_analytics_id" value="{{ $setting->google_analytics_id ?? '' }}" class="form-control w-full rounded-md border-slate-200" placeholder="G-XXXXXXXXXX">
                                </div>
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-5">Google Search Console Verification</label>
                                    <input type="text" name="google_console_verification" value="{{ $setting->google_console_verification ?? '' }}" class="form-control w-full rounded-md border-slate-200">
                                </div>

                                <div class="pb-3 border-b border-slate-200/70 mt-10">
                                    <h3 class="text-sm font-semibold text-slate-700">Custom Scripts</h3>
                                </div>
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-5">Custom Head Scripts</label>
                                    <textarea name="custom_head_scripts" rows="6" class="form-control w-full rounded-md border-slate-200 font-mono text-xs">{{ $setting->custom_head_scripts ?? '' }}</textarea>
                                    <p class="text-xs text-slate-500 mt-1">Scripts that will be injected into the &lt;head&gt; section.</p>
                                </div>
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-5">Custom Body Scripts</label>
                                    <textarea name="custom_body_scripts" rows="6" class="form-control w-full rounded-md border-slate-200 font-mono text-xs">{{ $setting->custom_body_scripts ?? '' }}</textarea>
                                    <p class="text-xs text-slate-500 mt-1">Scripts that will be injected right before the &lt;/body&gt; closing tag.</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-8 mt-10 border-t border-slate-200/70">
                            <button type="submit" id="btn-save" class="px-8 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        
        const form = document.getElementById('website-form')
        const btn = document.getElementById('btn-save')

        form.addEventListener('submit', async (e) => {
            e.preventDefault()
            const originalBtnText = btn.innerText;
            btn.disabled = true
            btn.innerText = 'Saving...'

            const formData = new FormData(form)
            // Axios automatically handles multipart/form-data when sending FormData
            // We use POST + _method PUT because some servers have issues with large PUT bodies containing files
            formData.append('_method', 'PUT')

            try {
                const res = await axios.post(`/website-settings`, formData)
                if (typeof showToast === 'function') {
                    showToast('success', 'Success', 'Website settings updated successfully')
                } else {
                    alert('Website settings updated successfully')
                }
                
                // Reload to refresh image previews
                setTimeout(() => {
                    window.location.reload()
                }, 1000)
                
            } catch (err) {
                console.error(err)
                const errorMsg = err.response?.data?.message || 'Failed to update website settings';
                if (typeof showToast === 'function') {
                    showToast('failed', 'Error', errorMsg)
                } else {
                    alert(errorMsg)
                }
            }

            btn.disabled = false
            btn.innerText = originalBtnText
        })
    </script>
@endsection
