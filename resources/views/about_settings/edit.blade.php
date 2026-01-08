@extends('layouts.app')
@section('title', 'About Page Settings')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-10">
            <div class="flex flex-col gap-1">
                <h2 class="text-2xl font-extrabold tracking-tight text-slate-800 dark:text-slate-200">
                    Corporate Identity
                </h2>
                <div class="flex items-center gap-2 text-sm text-slate-500 font-medium mt-1">
                    <span class="flex items-center gap-1.5 px-3 py-1 bg-primary/5 text-primary dark:bg-darkmode-400 rounded-lg text-[10px] border border-primary/10 uppercase tracking-widest font-black">Brand Architecture Node</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button type="submit" form="about-form" id="btn-save" class="transition-all active:scale-95 inline-flex items-center justify-center px-8 py-4 text-sm font-bold text-white bg-primary rounded-xl shadow-xl shadow-primary/30 hover:bg-primary-dark group">
                    <i data-lucide="shield-check" class="w-4 h-4 mr-2 group-hover:scale-110 transition-transform"></i>
                    <span id="btn-text">Synchronize Brand Node</span>
                </button>
            </div>
        </div>

        <form id="about-form" class="grid grid-cols-12 gap-10 mt-10" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            {{-- LEFT: CORE CONTENT --}}
            <div class="col-span-12 lg:col-span-8 space-y-10">
                
                {{-- HERO CONFIGURATION --}}
                <div class="box box--stacked p-10 flex flex-col border-none shadow-sm relative overflow-hidden bg-white dark:bg-darkmode-600">
                    <div class="pb-6 mb-10 border-b border-slate-100 dark:border-darkmode-400 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-primary/5 rounded-2xl flex items-center justify-center text-primary">
                                <i data-lucide="layout" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-700 dark:text-slate-200 leading-none">Hero Interface</h3>
                                <p class="text-[10px] text-slate-400 font-medium mt-2 uppercase tracking-[0.2em] leading-none">Primary Branding Layer</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-y-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Stage Badge</label>
                                <input type="text" name="hero_badge" value="{{ $setting->hero_badge ?? '' }}" 
                                    class="form-control w-full rounded-xl border-none bg-slate-50 dark:bg-darkmode-400 py-4 px-6 text-sm font-bold shadow-sm focus:ring-4 focus:ring-primary/10 transition-all border border-slate-200/50"
                                    placeholder="e.g. CORPORATE PROFILE">
                            </div>
                            
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Hero Backdrop</label>
                                <div class="relative group">
                                    <input type="file" name="hero_image" id="hero-image-input" class="hidden" accept="image/*">
                                    <label for="hero-image-input" class="flex flex-col items-center justify-center w-full h-40 rounded-2xl border-2 border-dashed border-slate-200 dark:border-darkmode-400 bg-slate-50/50 dark:bg-darkmode-700/30 hover:bg-slate-50 hover:border-primary/50 transition-all cursor-pointer relative overflow-hidden group">
                                        @if($setting->hero_image)
                                            <img id="hero-image-preview" src="{{ $setting->hero_image }}" class="absolute inset-0 w-full h-full object-cover opacity-10 group-hover:opacity-20 transition-opacity">
                                        @else
                                            <img id="hero-image-preview" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                                        @endif
                                        <div class="flex flex-col items-center gap-2 relative z-10">
                                            <i data-lucide="image" class="w-8 h-8 text-slate-300 group-hover:text-primary transition-colors"></i>
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-primary-dark">Upload Cover</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Principal Headline</label>
                            <textarea name="hero_title" rows="2" 
                                class="form-control w-full rounded-xl border-none bg-slate-50 dark:bg-darkmode-400 py-5 px-6 text-base font-medium shadow-sm focus:ring-4 focus:ring-primary/10 transition-all leading-snug border border-slate-200/50 uppercase"
                                placeholder="THE NEXT FRONTIER OF INNOVATION.">{{ $setting->hero_title ?? '' }}</textarea>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Corporate Brief</label>
                            <textarea name="hero_description" rows="3" 
                                class="form-control w-full rounded-xl border-none bg-slate-50 dark:bg-darkmode-400 py-5 px-6 text-sm font-medium leading-relaxed shadow-sm border border-slate-200/50"
                                placeholder="A concise manifesto of your company's mission and presence...">{{ $setting->hero_description ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- CORE STORY --}}
                <div class="box box--stacked p-10 flex flex-col border-none shadow-sm relative overflow-hidden bg-white dark:bg-darkmode-600">
                    <div class="pb-6 mb-10 border-b border-slate-100 dark:border-darkmode-400 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-500/5 rounded-2xl flex items-center justify-center text-blue-500">
                                <i data-lucide="book-open" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-700 dark:text-slate-200 leading-none">Historical Narrative</h3>
                                <p class="text-[10px] text-slate-400 font-medium mt-2 uppercase tracking-[0.2em] leading-none">Our Authentic Journey</p>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-y-10">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Registry Title</label>
                                <input type="text" name="story_title" value="{{ $setting->story_title ?? '' }}" 
                                    class="form-control w-full rounded-xl border-none bg-slate-50 dark:bg-darkmode-400 py-4 px-6 text-sm font-bold shadow-sm border border-slate-200/50"
                                    placeholder="e.g. Beyond the Foundation">
                            </div>
                            
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Story Visual</label>
                                <div class="relative group">
                                    <input type="file" name="story_image" id="story-image-input" class="hidden" accept="image/*">
                                    <label for="story-image-input" class="flex flex-col items-center justify-center w-full h-40 rounded-2xl border-2 border-dashed border-slate-200 dark:border-darkmode-400 bg-slate-50/50 dark:bg-darkmode-700/30 hover:bg-slate-50 hover:border-blue-500/50 transition-all cursor-pointer relative overflow-hidden group">
                                        @if($setting->story_image)
                                            <img id="story-image-preview" src="{{ $setting->story_image }}" class="absolute inset-0 w-full h-full object-cover opacity-10 group-hover:opacity-20 transition-opacity">
                                        @else
                                            <img id="story-image-preview" src="" class="absolute inset-0 w-full h-full object-cover hidden">
                                        @endif
                                        <div class="flex flex-col items-center gap-2 relative z-10">
                                            <i data-lucide="camera" class="w-8 h-8 text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest group-hover:text-blue-600">Upload Visual</span>
                                        </div>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Full Narrative Content</label>
                            <textarea id="story-editor" name="story_content" rows="8" 
                                class="form-control w-full rounded-xl border-none bg-slate-50 dark:bg-darkmode-400 py-5 px-6 text-sm font-medium leading-relaxed shadow-sm border border-slate-200/50"
                                placeholder="Delve into the history, core values, and the driving force behind the company... ">{{ $setting->story_content ?? '' }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- IMPACT METRICS --}}
                <div class="box box--stacked p-10 flex flex-col border-none shadow-sm bg-white dark:bg-darkmode-600">
                    <div class="pb-6 mb-10 border-b border-slate-100 dark:border-darkmode-400 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-amber-500/5 rounded-2xl flex items-center justify-center text-amber-500">
                                <i data-lucide="award" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-700 dark:text-slate-200 leading-none">Dynamic Metrics</h3>
                                <p class="text-[10px] text-slate-400 font-medium mt-2 uppercase tracking-[0.2em] leading-none">Achievement Pulse</p>
                            </div>
                        </div>
                        <button type="button" id="add-stat" class="w-12 h-12 flex items-center justify-center bg-slate-50 dark:bg-darkmode-400 hover:bg-primary hover:text-white transition-all rounded-2xl border border-slate-200/50 text-slate-500 shadow-sm group">
                            <i data-lucide="plus" class="w-5 h-5 group-hover:rotate-90 transition-transform duration-500"></i>
                        </button>
                    </div>

                    <div id="stats-container" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @php $stats = $setting->stats ?? []; @endphp
                        @foreach($stats as $stat)
                            <div class="repeater-item bg-slate-50/50 dark:bg-darkmode-700/30 rounded-2xl p-8 border border-slate-100/50 relative group transition-all hover:bg-white hover:shadow-xl hover:shadow-slate-200/20">
                                <button type="button" class="remove-item absolute top-4 right-4 p-2 text-slate-300 hover:text-danger hover:bg-danger/5 rounded-xl transition-all">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                                <div class="grid grid-cols-3 gap-6">
                                    <div class="col-span-2 space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Registry Key</label>
                                        <input type="text" value="{{ $stat['title'] ?? '' }}" class="form-control w-full rounded-xl border-slate-200 bg-white dark:bg-darkmode-600 py-3 px-4 text-xs font-bold stat-title" placeholder="e.g. Success Rate">
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Value</label>
                                        <input type="text" value="{{ $stat['value'] ?? '' }}" class="form-control w-full rounded-xl border-slate-200 bg-white dark:bg-darkmode-600 py-3 px-4 text-xs font-bold stat-value" placeholder="99">
                                    </div>
                                    <div class="col-span-3 space-y-2">
                                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Unit / Suffix</label>
                                        <input type="text" value="{{ $stat['suffix'] ?? '' }}" class="form-control w-full rounded-xl border-slate-200 bg-white dark:bg-darkmode-600 py-3 px-4 text-xs font-bold stat-suffix" placeholder="e.g. % or +">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- RIGHT: STRATEGIC & SEO --}}
            <div class="col-span-12 lg:col-span-4 space-y-10">
                
                {{-- VISION MISSION --}}
                <div class="box box--stacked p-10 flex flex-col border-none shadow-sm bg-white dark:bg-darkmode-600">
                    <div class="pb-6 mb-10 border-b border-slate-100 dark:border-darkmode-400 flex items-center gap-4">
                        <div class="w-12 h-12 bg-rose-500/5 rounded-2xl flex items-center justify-center text-rose-500">
                            <i data-lucide="target" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-700 dark:text-slate-200 leading-none">Strategic Path</h3>
                            <p class="text-[10px] text-slate-400 font-medium mt-2 uppercase tracking-[0.2em] leading-none">Vision & Mission</p>
                        </div>
                    </div>

                    <div class="space-y-10">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Vision Statement</label>
                            <input type="text" name="vision_title" value="{{ $setting->vision_title ?? '' }}" class="form-control w-full rounded-xl border-none bg-slate-50 dark:bg-darkmode-400 py-4 px-6 text-sm font-bold shadow-sm border border-slate-200/50" placeholder="Identity of our future">
                            <textarea name="vision_content" rows="3" class="form-control w-full rounded-xl border-none bg-slate-50 dark:bg-darkmode-400 py-4 px-6 text-[11px] font-medium leading-relaxed shadow-sm border border-slate-200/50" placeholder="The ultimate destination... ">{{ $setting->vision_content ?? '' }}</textarea>
                        </div>

                        <div class="pt-10 border-t border-slate-100 dark:border-darkmode-400">
                            <div class="flex items-center justify-between mb-6">
                                <label class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Mission Points</label>
                                <button type="button" id="add-mission" class="w-8 h-8 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-primary hover:text-white transition-all shadow-sm border border-slate-100">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                            <input type="text" name="mission_title" value="{{ $setting->mission_title ?? '' }}" class="form-control w-full mb-4 rounded-xl border-none bg-slate-50 dark:bg-darkmode-400 py-4 px-6 text-sm font-bold shadow-sm border border-slate-200/50" placeholder="Mission Header">
                            <div id="mission-container" class="space-y-3">
                                @php $missionItems = $setting->mission_items ?? []; @endphp
                                @foreach($missionItems as $mItem)
                                    <div class="flex gap-3 repeater-item group items-center">
                                        <div class="w-2 h-2 rounded-full bg-primary/30 group-hover:bg-primary transition-colors"></div>
                                        <input type="text" value="{{ $mItem }}" class="form-control flex-1 rounded-xl border-none bg-slate-50 dark:bg-darkmode-400 text-[10px] py-3.5 px-5 mission-input font-bold border border-slate-200/50">
                                        <button type="button" class="remove-item p-2 text-slate-300 hover:text-danger transition-all opacity-0 group-hover:opacity-100">
                                            <i data-lucide="x" class="w-3.5 h-3.5"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CORE VALUES --}}
                <div class="box box--stacked p-10 flex flex-col border-none shadow-sm bg-white dark:bg-darkmode-600">
                    <div class="pb-6 mb-10 border-b border-slate-100 dark:border-darkmode-400 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-indigo-500/5 rounded-2xl flex items-center justify-center text-indigo-500">
                                <i data-lucide="shield" class="w-6 h-6"></i>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-slate-700 dark:text-slate-200 leading-none">Core Ethics</h3>
                                <p class="text-[10px] text-slate-400 font-medium mt-2 uppercase tracking-[0.2em] leading-none">Culture Pillars</p>
                            </div>
                        </div>
                        <button type="button" id="add-value" class="w-8 h-8 flex items-center justify-center bg-slate-50 text-slate-400 rounded-xl hover:bg-indigo-500 hover:text-white transition-all shadow-sm border border-slate-100">
                            <i data-lucide="plus" class="w-4 h-4"></i>
                        </button>
                    </div>

                    <div id="values-container" class="space-y-4">
                        @php $values = $setting->core_values ?? []; @endphp
                        @foreach($values as $value)
                            <div class="repeater-item bg-slate-50/50 dark:bg-darkmode-700/30 rounded-2xl p-6 border border-slate-100/50 relative group transition-all hover:bg-white hover:shadow-xl">
                                <button type="button" class="remove-item absolute top-4 right-4 p-1.5 text-slate-300 hover:text-danger transition-all opacity-0 group-hover:opacity-100">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                </button>
                                <div class="space-y-3">
                                    <div class="flex items-center gap-3">
                                        <select class="form-select w-20 rounded-xl border-slate-200 bg-white py-2.5 px-3 text-[10px] font-bold value-icon cursor-pointer">
                                            @foreach(['shield', 'zap', 'target', 'heart', 'star', 'users', 'globe'] as $icon)
                                                <option value="{{ $icon }}" {{ ($value['icon'] ?? '') == $icon ? 'selected' : '' }}>{{ strtoupper($icon) }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" value="{{ $value['title'] ?? '' }}" class="form-control flex-1 rounded-xl border-slate-200 bg-white py-2.5 px-4 text-xs font-bold value-title" placeholder="Value Key">
                                    </div>
                                    <textarea class="form-control w-full rounded-xl border-slate-200 bg-white py-3 px-4 text-[10px] font-medium value-description" rows="2" placeholder="Value Manifest...">{{ $value['description'] ?? '' }}</textarea>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- SEO NODE --}}
                <div class="box box--stacked p-10 flex flex-col border-none shadow-sm bg-white dark:bg-darkmode-600">
                    <div class="pb-6 mb-10 border-b border-slate-100 dark:border-darkmode-400 flex items-center gap-4">
                        <div class="w-12 h-12 bg-emerald-500/5 rounded-2xl flex items-center justify-center text-emerald-500">
                            <i data-lucide="search" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h3 class="text-base font-bold text-slate-700 dark:text-slate-200 leading-none">Visibility Arc</h3>
                            <p class="text-[10px] text-slate-400 font-medium mt-2 uppercase tracking-[0.2em] leading-none">Meta Indexing Architecture</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-y-8">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Search Descriptor</label>
                            <input type="text" name="seo_title" value="{{ $setting->seo_title ?? '' }}" class="form-control w-full rounded-xl border-none bg-slate-50 dark:bg-darkmode-400 py-4 px-6 text-sm font-bold shadow-sm border border-slate-200/50" placeholder="Corporate Biography">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest pl-1">Meta Manifest</label>
                            <textarea name="seo_description" rows="4" class="form-control w-full rounded-xl border-none bg-slate-50 dark:bg-darkmode-400 py-4 px-6 text-[11px] font-medium leading-relaxed shadow-sm border border-slate-200/50" placeholder="Global indexing brief... ">{{ $setting->seo_description ?? '' }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        // Initialize TinyMCE for Story Editor
        tinymce.init({
            selector: '#story-editor',
            height: 400,
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image',
                'charmap', 'preview', 'anchor', 'searchreplace',
                'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'table', 'help', 'wordcount'
            ],
            toolbar: `
                undo redo | formatselect |
                bold italic underline backcolor |
                alignleft aligncenter alignright alignjustify |
                bullist numlist outdent indent |
                link image | removeformat | help
            `,
            automatic_uploads: true,
            relative_urls: false,
            remove_script_host: false,
            convert_urls: false,
            images_upload_handler: function(blobInfo) {
                return new Promise((resolve, reject) => {
                    const fd = new FormData()
                    fd.append('file', blobInfo.blob())
                    axios.post('/upload-image', fd)
                        .then(res => {
                            if (res.data.location) {
                                resolve(res.data.location)
                            } else {
                                reject('Upload gagal')
                            }
                        })
                        .catch(() => reject('Upload error'))
                })
            }
        });

        // Image Previews
        function setupPreview(inputId, previewId) {
            const input = document.getElementById(inputId);
            const preview = document.getElementById(previewId);
            input.addEventListener('change', function() {
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        preview.src = e.target.result;
                        preview.classList.remove('hidden');
                        preview.classList.add('opacity-40');
                    };
                    reader.readAsDataURL(this.files[0]);
                }
            });
        }
        setupPreview('hero-image-input', 'hero-image-preview');
        setupPreview('story-image-input', 'story-image-preview');

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                const item = e.target.closest('.repeater-item');
                item.style.opacity = '0';
                item.style.transform = 'scale(0.95)';
                setTimeout(() => item.remove(), 300);
            }
        });

        // Add Stat
        document.getElementById('add-stat').addEventListener('click', function() {
            const container = document.getElementById('stats-container');
            const div = document.createElement('div');
            div.className = 'repeater-item bg-slate-50/50 dark:bg-darkmode-700/30 rounded-2xl p-8 border border-slate-100/50 relative group transition-all hover:bg-white hover:shadow-xl animate-in slide-in-from-bottom-2 duration-500';
            div.innerHTML = `
                <button type="button" class="remove-item absolute top-4 right-4 p-2 text-slate-300 hover:text-danger hover:bg-danger/5 rounded-xl transition-all">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
                <div class="grid grid-cols-3 gap-6">
                    <div class="col-span-2 space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Registry Key</label>
                        <input type="text" class="form-control w-full rounded-xl border-slate-200 bg-white py-3 px-4 text-xs font-bold stat-title" placeholder="e.g. Partners">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Value</label>
                        <input type="text" class="form-control w-full rounded-xl border-slate-200 bg-white py-3 px-4 text-xs font-bold stat-value" placeholder="100">
                    </div>
                    <div class="col-span-3 space-y-2">
                        <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest pl-1">Unit / Suffix</label>
                        <input type="text" class="form-control w-full rounded-xl border-slate-200 bg-white py-3 px-4 text-xs font-bold stat-suffix" placeholder="+">
                    </div>
                </div>`;
            container.appendChild(div);
            lucide.createIcons();
            div.querySelector('input').focus();
        });

        // Add Value
        document.getElementById('add-value').addEventListener('click', function() {
            const container = document.getElementById('values-container');
            const div = document.createElement('div');
            div.className = 'repeater-item bg-slate-50/50 dark:bg-darkmode-700/30 rounded-2xl p-6 border border-slate-100/50 relative group transition-all hover:bg-white hover:shadow-xl animate-in slide-in-from-bottom-2 duration-500';
            div.innerHTML = `
                <button type="button" class="remove-item absolute top-4 right-4 p-1.5 text-slate-300 hover:text-danger transition-all opacity-0 group-hover:opacity-100">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
                <div class="space-y-3">
                    <div class="flex items-center gap-3">
                        <select class="form-select w-20 rounded-xl border-slate-200 bg-white py-2.5 px-3 text-[10px] font-bold value-icon cursor-pointer">
                            <option value="shield">SHIELD</option>
                            <option value="zap">ZAP</option>
                            <option value="target">TARGET</option>
                            <option value="heart">HEART</option>
                            <option value="star">STAR</option>
                        </select>
                        <input type="text" class="form-control flex-1 rounded-xl border-slate-200 bg-white py-2.5 px-4 text-xs font-bold value-title" placeholder="Value Key">
                    </div>
                    <textarea class="form-control w-full rounded-xl border-slate-200 bg-white py-3 px-4 text-[10px] font-medium value-description" rows="2" placeholder="Value Manifest..."></textarea>
                </div>`;
            container.appendChild(div);
            lucide.createIcons();
            div.querySelector('input').focus();
        });

        // Add Mission
        document.getElementById('add-mission').addEventListener('click', function() {
            const container = document.getElementById('mission-container');
            const div = document.createElement('div');
            div.className = 'flex gap-3 repeater-item group items-center animate-in slide-in-from-top-2 duration-300';
            div.innerHTML = `
                <div class="w-2 h-2 rounded-full bg-primary/30 group-hover:bg-primary transition-colors"></div>
                <input type="text" class="form-control flex-1 rounded-xl border-none bg-slate-50 dark:bg-darkmode-400 text-[10px] py-3.5 px-5 mission-input font-bold border border-slate-200/50">
                <button type="button" class="remove-item p-2 text-slate-300 hover:text-danger transition-all opacity-0 group-hover:opacity-100">
                    <i data-lucide="x" class="w-3.5 h-3.5"></i>
                </button>`;
            container.appendChild(div);
            div.querySelector('input').focus();
        });

        const form = document.getElementById('about-form');
        const submitBtn = document.getElementById('btn-save');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            // Save TinyMCE content to textarea
            if (typeof tinymce !== 'undefined') {
                tinymce.triggerSave();
            }
            
            const originalBtnHtml = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i data-lucide="refresh-cw" class="w-4 h-4 mr-2 animate-spin"></i> SYNCING...';
            lucide.createIcons();

            const formData = new FormData(form);
            
            // Collect Repeater Data for Mission
            Array.from(document.querySelectorAll('.mission-input')).forEach((input, i) => {
                if (input.value.trim()) formData.append(`mission_items[${i}]`, input.value.trim());
            });

            // Collect Stats Data
            document.querySelectorAll('#stats-container .repeater-item').forEach((item, i) => {
                const title = item.querySelector('.stat-title').value.trim();
                const value = item.querySelector('.stat-value').value.trim();
                const suffix = item.querySelector('.stat-suffix').value.trim();
                if (title && value) {
                    formData.append(`stats[${i}][title]`, title);
                    formData.append(`stats[${i}][value]`, value);
                    formData.append(`stats[${i}][suffix]`, suffix);
                }
            });

            // Collect Core Values
            document.querySelectorAll('#values-container .repeater-item').forEach((item, i) => {
                const title = item.querySelector('.value-title').value.trim();
                if (title) {
                    formData.append(`core_values[${i}][title]`, title);
                    formData.append(`core_values[${i}][icon]`, item.querySelector('.value-icon').value);
                    formData.append(`core_values[${i}][description]`, item.querySelector('.value-description').value.trim());
                }
            });

            // Method spoofing for PUT in FormData
            formData.append('_method', 'PUT');

            try {
                await axios.post('/about-settings', formData, {
                    headers: { 'Content-Type': 'multipart/form-data' }
                });
                if (typeof showToast === 'function') {
                    showToast('success', 'Node Synchronized', 'Brand architecture parameters synchronized successfully.');
                }
            } catch (err) {
                console.error(err);
                if (typeof showToast === 'function') {
                    showToast('error', 'Sync Failed', 'Identity synchronization failed.');
                }
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
                lucide.createIcons();
            }
        });
    </script>
@endpush
