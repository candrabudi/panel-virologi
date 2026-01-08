@extends('layouts.app')
@section('title', 'Edit Cyber Security Service')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="flex flex-col gap-1 mb-10">
            <div class="flex items-center gap-2">
                <a href="/cyber-security-services" class="p-2 bg-slate-100 dark:bg-darkmode-400 hover:bg-slate-200 rounded-lg transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-500 group-hover:text-primary"><path d="m15 18-6-6 6-6"/></svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-800 group-[.mode--light]:text-white">
                        Edit Service
                    </h2>
                    <p class="text-sm text-slate-500 font-medium">Fine-tune your security offering details</p>
                </div>
            </div>
        </div>

        <form id="service-form" class="grid grid-cols-12 gap-8">
            <input type="hidden" name="id" value="{{ $cyberSecurityService->id }}">
            @csrf

            {{-- MAIN CONTENT AREA --}}
            <div class="col-span-12 lg:col-span-8 space-y-8">
                
                {{-- CARD 1: Basic Info --}}
                <div class="box box--stacked flex flex-col">
                    <div class="px-10 py-6 border-b border-slate-200/60 dark:border-darkmode-400 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-orange/10 flex items-center justify-center text-orange-600">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-700">General Information</h3>
                        </div>
                    </div>

                    <div class="p-10 space-y-8">
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 lg:col-span-8">
                                <label class="block mb-2 text-sm font-bold text-slate-700">Full Service Name <span class="text-danger">*</span></label>
                                <input type="text" name="name" value="{{ $cyberSecurityService->name }}" required
                                    class="form-control w-full rounded-xl border-slate-200 py-3 px-4 shadow-sm focus:ring-4 focus:ring-primary/10 transition-all font-medium"
                                    placeholder="e.g. Managed Security Operations Center">
                                <p class="mt-2 text-xs text-slate-400">Nama layanan utama yang akan muncul di heading.</p>
                            </div>
                            <div class="col-span-12 lg:col-span-4">
                                <label class="block mb-2 text-sm font-bold text-slate-700">Short Name</label>
                                <input type="text" name="short_name" value="{{ $cyberSecurityService->short_name }}"
                                    class="form-control w-full rounded-xl border-slate-200 py-3 px-4 shadow-sm focus:ring-4 focus:ring-primary/10 transition-all"
                                    placeholder="e.g. Managed SOC">
                            </div>

                            <div class="col-span-12">
                                <label class="block mb-2 text-sm font-bold text-slate-700">Category</label>
                                <select name="category" class="form-select w-full rounded-xl border-slate-200 py-3 shadow-sm transition-all cursor-pointer">
                                    @foreach (['soc', 'pentest', 'audit', 'incident_response', 'cloud_security', 'governance', 'training', 'consulting'] as $c)
                                        <option value="{{ $c }}" {{ $cyberSecurityService->category == $c ? 'selected' : '' }}>
                                            {{ strtoupper(str_replace('_', ' ', $c)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-span-12">
                                <label class="block mb-2 text-sm font-bold text-slate-700">Executive Summary <span class="text-danger">*</span></label>
                                <textarea name="summary" rows="3" required
                                    class="form-control w-full rounded-xl border-slate-200 p-4 shadow-sm focus:ring-4 focus:ring-primary/10 transition-all"
                                    placeholder="Brief overview explaining what value this service provides...">{{ $cyberSecurityService->summary }}</textarea>
                                <p class="mt-2 text-xs text-slate-400">Ringkasan singkat ini sangat penting untuk SEO dan preview card.</p>
                            </div>

                            <div class="col-span-12">
                                <label class="block mb-3 text-sm font-bold text-slate-700">Full Description</label>
                                <div class="rounded-xl overflow-hidden border border-slate-200 dark:border-darkmode-400">
                                    <textarea id="content-editor" name="description" rows="15"
                                        class="form-control w-full border-none shadow-sm">{{ $cyberSecurityService->description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- REPEATER ROWS --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    {{-- Service Scope --}}
                    <div class="box box--stacked p-10 flex flex-col hover:border-blue/30 transition-colors group">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
                            </div>
                            <h4 class="text-base font-bold text-slate-700 uppercase tracking-wider">Service Scope</h4>
                        </div>
                        <div id="service-scope-container" class="space-y-4 flex-1">
                            @foreach($cyberSecurityService->service_scope ?? [] as $item)
                                <div class="flex gap-2 repeater-item items-center mb-3">
                                    <input type="text" value="{{ $item }}" class="form-control flex-1 rounded-xl border-slate-200 text-sm shadow-sm py-3 px-4 scope-input">
                                    <button type="button" class="p-3 text-slate-400 hover:bg-danger/10 hover:text-danger rounded-xl remove-item transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="add-repeater-item mt-6 w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-500 font-bold text-xs hover:border-primary/50 hover:bg-primary/5 hover:text-primary transition-all" data-target="service-scope-container" data-placeholder="e.g. 24/7 Monitoring">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Add Scope
                        </button>
                    </div>

                    {{-- Deliverables --}}
                    <div class="box box--stacked p-10 flex flex-col hover:border-indigo/30 transition-colors group">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                            </div>
                            <h4 class="text-base font-bold text-slate-700 uppercase tracking-wider">Deliverables</h4>
                        </div>
                        <div id="deliverables-container" class="space-y-4 flex-1">
                            @foreach($cyberSecurityService->deliverables ?? [] as $item)
                                <div class="flex gap-2 repeater-item items-center mb-3">
                                    <input type="text" value="{{ $item }}" class="form-control flex-1 rounded-xl border-slate-200 text-sm shadow-sm py-3 px-4 deliverable-input">
                                    <button type="button" class="p-3 text-slate-400 hover:bg-danger/10 hover:text-danger rounded-xl remove-item transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="add-repeater-item mt-6 w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-500 font-bold text-xs hover:border-indigo/50 hover:bg-indigo/5 hover:text-indigo-600 transition-all" data-target="deliverables-container" data-placeholder="e.g. Monthly Security Report">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Add Deliverable
                        </button>
                    </div>

                    {{-- Target Audience --}}
                    <div class="box box--stacked p-10 flex flex-col hover:border-emerald/30 transition-colors group">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:bg-emerald-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            </div>
                            <h4 class="text-base font-bold text-slate-700 uppercase tracking-wider">Target Audience</h4>
                        </div>
                        <div id="target-audience-container" class="space-y-4 flex-1">
                            @foreach($cyberSecurityService->target_audience ?? [] as $item)
                                <div class="flex gap-2 repeater-item items-center mb-3">
                                    <input type="text" value="{{ $item }}" class="form-control flex-1 rounded-xl border-slate-200 text-sm shadow-sm py-3 px-4 audience-input">
                                    <button type="button" class="p-3 text-slate-400 hover:bg-danger/10 hover:text-danger rounded-xl remove-item transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="add-repeater-item mt-6 w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-500 font-bold text-xs hover:border-emerald/50 hover:bg-emerald/5 hover:text-emerald-600 transition-all" data-target="target-audience-container" data-placeholder="e.g. Enterprises">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Add Audience
                        </button>
                    </div>

                    {{-- AI Keywords --}}
                    <div class="box box--stacked p-10 flex flex-col hover:border-amber/30 transition-colors group">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:bg-amber-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z"/></svg>
                            </div>
                            <h4 class="text-base font-bold text-slate-700 uppercase tracking-wider">AI Keywords</h4>
                        </div>
                        <div id="ai-keywords-container" class="space-y-4 flex-1">
                            @foreach($cyberSecurityService->ai_keywords ?? [] as $item)
                                <div class="flex gap-2 repeater-item items-center mb-3">
                                    <input type="text" value="{{ $item }}" class="form-control flex-1 rounded-xl border-slate-200 text-sm shadow-sm py-3 px-4 keyword-input">
                                    <button type="button" class="p-3 text-slate-400 hover:bg-danger/10 hover:text-danger rounded-xl remove-item transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                        <button type="button" class="add-repeater-item mt-6 w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-500 font-bold text-xs hover:border-amber/50 hover:bg-amber/5 hover:text-amber-600 transition-all" data-target="ai-keywords-container" data-placeholder="e.g. pentest">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                            Add Keyword
                        </button>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR AREA --}}
            <div class="col-span-12 lg:col-span-4 space-y-8">
                
                {{-- STATUS CARD --}}
                <div class="box box--stacked p-10 flex flex-col">
                    <h3 class="text-lg font-bold text-slate-700 mb-6">Status & Visibility</h3>
                    <div class="space-y-6">
                        <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 dark:border-darkmode-400 bg-slate-50/50 dark:bg-darkmode-600">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">Active Status</label>
                            <div class="form-check form-switch p-0">
                                <input name="is_active" class="form-check-input scale-125" type="checkbox" value="1" {{ $cyberSecurityService->is_active ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div class="flex items-center justify-between p-4 rounded-xl border border-slate-100 dark:border-darkmode-400 bg-slate-50/50 dark:bg-darkmode-600">
                            <label class="text-sm font-bold text-slate-700 dark:text-slate-300">AI Visible</label>
                            <div class="form-check form-switch p-0">
                                <input name="is_ai_visible" class="form-check-input scale-125" type="checkbox" value="1" {{ $cyberSecurityService->is_ai_visible ? 'checked' : '' }}>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-slate-700">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ $cyberSecurityService->sort_order }}"
                                class="form-control w-full rounded-xl border-slate-200 py-3 shadow-sm" placeholder="0">
                        </div>
                    </div>
                </div>

                {{-- THUMBNAIL CARD --}}
                <div class="box box--stacked p-10 flex flex-col">
                    <h3 class="text-lg font-bold text-slate-700 mb-6">Thumbnail Asset</h3>
                    <div class="space-y-4">
                        @if ($cyberSecurityService->thumbnail)
                            <div class="relative group rounded-2xl overflow-hidden border border-slate-200 dark:border-darkmode-400 shadow-sm">
                                <img src="{{ $cyberSecurityService->thumbnail }}" alt="Thumbnail" class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center backdrop-blur-[2px]">
                                    <span class="text-white text-xs font-bold bg-white/20 backdrop-blur-md px-4 py-2 rounded-full border border-white/30">Change Image</span>
                                </div>
                                <input type="file" name="thumbnail" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                            </div>
                        @else
                           <div class="group relative w-full h-48 bg-slate-50 dark:bg-darkmode-600 rounded-2xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 hover:bg-slate-100 transition-all cursor-pointer">
                                <input type="file" name="thumbnail" accept="image/*" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" class="mb-2"><rect width="18" height="18" x="3" y="3" rx="2" ry="2"/><circle cx="9" cy="9" r="2"/><path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21"/></svg>
                                <span class="text-xs font-bold">Upload Image</span>
                           </div>
                        @endif
                    </div>
                </div>

                {{-- AI DOMAIN --}}
                <div class="box box--stacked p-10 flex flex-col">
                    <h3 class="text-lg font-bold text-slate-700 mb-6">AI Classification</h3>
                    <div>
                        <label class="block mb-2 text-sm font-bold text-slate-700">AI Domain</label>
                        <input type="text" name="ai_domain" value="{{ $cyberSecurityService->ai_domain }}"
                            class="form-control w-full rounded-xl border-slate-200 py-3 shadow-sm" placeholder="e.g. soc">
                    </div>
                </div>

                {{-- CTA CARD --}}
                <div class="box box--stacked p-10 flex flex-col">
                    <h3 class="text-lg font-bold text-slate-700 mb-6">Call to Action</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-2 text-sm font-bold text-slate-700 text-xs">Button Label</label>
                            <input type="text" name="cta_label" value="{{ $cyberSecurityService->cta_label }}"
                                class="form-control w-full rounded-xl border-slate-200 py-3 shadow-sm" placeholder="Hubungi Kami">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-slate-700 text-xs">Target URL</label>
                            <input type="text" name="cta_url" value="{{ $cyberSecurityService->cta_url }}"
                                class="form-control w-full rounded-xl border-slate-200 py-3 shadow-sm" placeholder="https://...">
                        </div>
                    </div>
                </div>

                {{-- SEO --}}
                <div class="box box--stacked p-10 flex flex-col">
                    <h3 class="text-lg font-bold text-slate-700 mb-6">SEO Optimization</h3>
                    <div class="space-y-4">
                        <div>
                            <label class="block mb-2 text-sm font-bold text-slate-700 text-xs">Meta Title</label>
                            <input type="text" name="seo_title" value="{{ $cyberSecurityService->seo_title }}"
                                class="form-control w-full rounded-xl border-slate-200 py-2.5 shadow-sm text-sm">
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-slate-700 text-xs">Meta Description</label>
                            <textarea name="seo_description" rows="3"
                                class="form-control w-full rounded-xl border-slate-200 p-3 shadow-sm text-sm">{{ $cyberSecurityService->seo_description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit" id="btn-save" class="w-full flex items-center justify-center px-8 py-4 text-sm font-bold text-white rounded-2xl bg-primary hover:bg-primary-dark group shadow-xl shadow-primary/30 transition-all active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="mr-3 group-hover:rotate-12 transition-transform"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
                        <span id="btn-text">Save Changes</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        tinymce.init({
            selector: '#content-editor',
            height: 500,
            plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'table', 'help', 'wordcount'],
            toolbar: 'undo redo | formatselect | fontselect fontsizeselect | bold italic underline forecolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table link image | removeformat | help',
            automatic_uploads: true,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            content_style: 'body { font-family:Inter,Helvetica,Arial,sans-serif; font-size:14px }',
            images_upload_handler: function(blobInfo) {
                return new Promise((resolve, reject) => {
                    const fd = new FormData()
                    fd.append('file', blobInfo.blob())
                    axios.post('/upload-image', fd).then(res => resolve(res.data.location)).catch(() => reject('Upload error'))
                })
            }
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                const item = e.target.closest('.repeater-item');
                item.style.transform = 'scale(0.95)';
                item.style.opacity = '0';
                setTimeout(() => item.remove(), 200);
            }
        });

        const deleteIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>`;

        document.querySelectorAll('.add-repeater-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.getElementById(this.dataset.target);
                const placeholder = this.dataset.placeholder;
                const div = document.createElement('div');
                div.className = 'flex gap-2 repeater-item items-center mb-3 animate-in fade-in slide-in-from-top-4 duration-300';
                
                let inputClass = 'form-control flex-1 rounded-xl border-slate-200 text-sm shadow-sm py-3 px-4 focus:ring-4 focus:ring-slate-100 transition-all';
                if(this.dataset.target.includes('scope')) inputClass += ' scope-input';
                else if(this.dataset.target.includes('deliverable')) inputClass += ' deliverable-input';
                else if(this.dataset.target.includes('audience')) inputClass += ' audience-input';
                else if(this.dataset.target.includes('keyword')) inputClass += ' keyword-input';

                div.innerHTML = `
                    <input type="text" placeholder="${placeholder}" class="${inputClass}">
                    <button type="button" class="p-3 text-slate-400 hover:bg-danger/10 hover:text-danger rounded-xl remove-item transition-all">
                        ${deleteIcon}
                    </button>
                `;
                target.appendChild(div);
                div.querySelector('input').focus();
            });
        });

        const form = document.getElementById('service-form');
        const submitBtn = document.getElementById('btn-save');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            tinymce.triggerSave();
            
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="animate-spin mr-3">◌</span> Saving...';

            const fd = new FormData(form);
            const serviceId = fd.get('id');

            const collect = (selector) => Array.from(document.querySelectorAll(selector)).map(i => i.value.trim()).filter(Boolean);
            
            const arrayData = {
                service_scope: collect('.scope-input'),
                deliverables: collect('.deliverable-input'),
                target_audience: collect('.audience-input'),
                ai_keywords: collect('.keyword-input')
            };

            ['service_scope', 'deliverables', 'target_audience', 'ai_keywords'].forEach(key => {
                fd.delete(key); 
                arrayData[key].forEach(val => fd.append(`${key}[]`, val));
            });

            fd.set('is_active', form.querySelector('[name="is_active"]').checked ? "1" : "0");
            fd.set('is_ai_visible', form.querySelector('[name="is_ai_visible"]').checked ? "1" : "0");

            fd.append('_method', 'PUT');

            try {
                const res = await axios.post(`/cyber-security-services/${serviceId}/update`, fd);
                showToast('success', 'Update Successful', 'Service details have been updated and synced.');
                setTimeout(() => window.location.href = '/cyber-security-services', 1500);
            } catch (err) {
                console.error(err);
                const errors = err.response?.data?.errors;
                let msg = 'Failed to update service details.';
                if(errors) msg = Object.values(errors).flat().join('<br>');
                showToast('error', 'Update Failed', msg);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        });
    </script>
@endpush
