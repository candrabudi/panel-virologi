@extends('layouts.app')
@section('title', 'Contact Settings')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="mb-8">
            <h2 class="text-xl font-semibold">Settings</h2>
            <p class="text-sm text-slate-500">
                Contact Page Configuration
            </p>
        </div>

        <form id="contact-form" class="grid grid-cols-12 gap-6" autocomplete="off">
            @csrf
            @method('PUT')
            
            <div class="col-span-12 lg:col-span-12 space-y-6">
                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    
                    {{-- HERO CONFIG --}}
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700 pb-3 border-b border-slate-200 mb-5">
                            Hero Interface
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block mb-2 text-sm font-medium">Badge Label</label>
                                <input type="text" name="hero_badge" value="{{ $setting->hero_badge ?? '' }}" 
                                    class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                                    placeholder="e.g. Contact Us">
                            </div>

                            <div class="space-y-3">
                                <label class="block mb-2 text-sm font-medium">Title Presets</label>
                                <select id="title-preset" class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control cursor-pointer">
                                    <option value="" selected disabled>-- Select a Preset --</option>
                                    <option value="Get in <br /> <span class='text-primary'>Touch.</span>">Standard: Get in Touch</option>
                                    <option value="Let's Start a <br /> <span class='text-primary'>Conversation.</span>">Friendly: Let's Start</option>
                                    <option value="Need Help? <br /> <span class='text-primary'>Contact Us.</span>">Direct: Need Help?</option>
                                    <option value="Connect with our <br /> <span class='text-primary'>Expert Team.</span>">Corporate: Experts</option>
                                </select>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3 relative">
                            <label class="block mb-2 text-sm font-medium">Headline Text</label>
                            
                            <div class="relative group/input">
                                <textarea name="hero_title" id="hero-title-input" rows="3" 
                                    class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                                    placeholder="Get in <br /> Touch.">{{ $setting->hero_title ?? '' }}</textarea>
                                
                                <div class="absolute bottom-3 right-3 flex items-center gap-1.5 ">
                                    <button type="button" onclick="wrapText('hero-title-input', '<span class=\'text-primary\'>', '</span>')" class="w-7 h-7 flex items-center justify-center bg-white border border-slate-200 rounded shadow-sm text-primary hover:bg-slate-50" title="Highlight Primary">
                                        <i data-lucide="highlighter" class="w-3.5 h-3.5"></i>
                                    </button>
                                    <button type="button" onclick="insertAtCursor('hero-title-input', '<br />')" class="w-7 h-7 flex items-center justify-center bg-white border border-slate-200 rounded shadow-sm text-slate-500 hover:bg-slate-50" title="Line Break">
                                        <i data-lucide="corner-down-left" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-2 p-4 rounded-md bg-slate-50 border border-slate-200 text-center">
                                <div id="hero-title-preview" class="text-lg font-bold">
                                    {!! $setting->hero_title ?? 'PREVIEW' !!}
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <label class="block mb-2 text-sm font-medium">Description</label>
                            <textarea name="hero_description" rows="3" 
                                class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                                placeholder="Short description...">{{ $setting->hero_description ?? '' }}</textarea>
                        </div>
                    </div>


                    {{-- CONTACT CHANNELS --}}
                    <div>
                        <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-5">
                            <h3 class="text-sm font-semibold text-slate-700">Contact Channels</h3>
                            <button type="button" id="add-channel" class="btn btn-sm btn-outline-primary">
                                <i data-lucide="plus" class="w-3 h-3 mr-1"></i> Add Channel
                            </button>
                        </div>

                        <div id="channels-container" class="space-y-4">
                            @php $channels = $setting->channels ?? []; @endphp
                            @foreach($channels as $index => $channel)
                                <div class="repeater-item border border-slate-200 rounded-md p-5 bg-slate-50 relative group">
                                    <div class="absolute top-3 right-3">
                                        <button type="button" class="remove-item text-slate-400 hover:text-danger">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-xs font-medium text-slate-500">Label</label>
                                            <input type="text" value="{{ $channel['title'] ?? '' }}" class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control channel-title" placeholder="e.g. Email">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-xs font-medium text-slate-500">Value (Display)</label>
                                            <input type="text" value="{{ $channel['value'] ?? '' }}" class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control channel-value" placeholder="e.g. hello@example.com">
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-xs font-medium text-slate-500">Link (Href)</label>
                                            <input type="text" value="{{ $channel['link'] ?? '' }}" class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control channel-link" placeholder="e.g. mailto:hello@example.com">
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="space-y-2">
                                                <label class="text-xs font-medium text-slate-500">Icon</label>
                                                <select class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control channel-icon cursor-pointer">
                                                    @foreach(['email', 'phone', 'location', 'message', 'globe', 'shield'] as $icon)
                                                        <option value="{{ $icon }}" {{ ($channel['icon'] ?? '') == $icon ? 'selected' : '' }}>{{ strtoupper($icon) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="space-y-2">
                                                <label class="text-xs font-medium text-slate-500">Color</label>
                                                <select class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control channel-color cursor-pointer">
                                                    @foreach(['sky', 'indigo', 'slate', 'emerald', 'rose', 'amber'] as $color)
                                                        <option value="{{ $color }}" {{ ($channel['color'] ?? '') == $color ? 'selected' : '' }}>{{ strtoupper($color) }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="md:col-span-2 space-y-2">
                                            <label class="text-xs font-medium text-slate-500">Description</label>
                                            <input type="text" value="{{ $channel['description'] ?? '' }}" class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control channel-description" placeholder="Small helper text">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                     {{-- SOCIAL FEED --}}
                    <div>
                        <h3 class="text-sm font-semibold text-slate-700 pb-3 border-b border-slate-200 mb-5">
                            Social Feed Section
                        </h3>
                         <div class="space-y-3 relative">
                            <label class="block mb-2 text-sm font-medium">Feed Title</label>
                            
                            <div class="relative group/input">
                                <textarea name="social_title" id="social-title-input" rows="2" 
                                    class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                                    placeholder="Follow the Feed.">{{ $setting->social_title ?? '' }}</textarea>
                                
                                <div class="absolute bottom-3 right-3 flex items-center gap-1.5">
                                    <button type="button" onclick="wrapText('social-title-input', '<span class=\'text-primary\'>', '</span>')" class="w-7 h-7 flex items-center justify-center bg-white border border-slate-200 rounded shadow-sm text-primary hover:bg-slate-50">
                                        <i data-lucide="highlighter" class="w-3.5 h-3.5"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-2 p-3 rounded-md bg-slate-50 border border-slate-200">
                                <div id="social-title-preview" class="text-base font-bold">
                                    {!! $setting->social_title ?? 'PREVIEW' !!}
                                </div>
                            </div>
                        </div>

                         <div class="mt-6 space-y-3">
                            <label class="block mb-2 text-sm font-medium">Feed Description</label>
                            <textarea name="social_description" rows="3" 
                                class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                                placeholder="Short description...">{{ $setting->social_description ?? '' }}</textarea>
                        </div>
                    </div>
                
                    {{-- SEO & BUTTONS --}}
                     <div>
                        <h3 class="text-sm font-semibold text-slate-700 pb-3 border-b border-slate-200 mb-5">
                            SEO Metadata
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-3">
                                <label class="block mb-2 text-sm font-medium">Meta Title</label>
                                <input type="text" name="seo_title" value="{{ $setting->seo_title ?? '' }}" 
                                    class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                                    placeholder="Contact Us - Name">
                            </div>

                             <div class="space-y-3 md:col-span-2">
                                <label class="block mb-2 text-sm font-medium">Meta Description</label>
                                <textarea name="seo_description" rows="2" 
                                    class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                                    placeholder="Page description for search engines.">{{ $setting->seo_description ?? '' }}</textarea>
                            </div>
                            
                            <div class="md:col-span-2 space-y-3">
                                <label class="block mb-2 text-sm font-medium">Keywords</label>
                                <div id="seo-keywords-container" class="flex flex-wrap gap-2 mb-2">
                                    @php $seoTags = $setting->seo_keywords ?? []; @endphp
                                    @foreach($seoTags as $tag)
                                        <div class="flex items-center gap-1.5 keyword-item bg-slate-100 border border-slate-200 rounded px-2 py-1">
                                            <input type="text" value="{{ $tag }}" class="bg-transparent border-none p-0 text-xs w-24 keyword-input focus:ring-0">
                                            <button type="button" class="remove-item text-slate-400 hover:text-danger">
                                                <i data-lucide="x" class="w-3 h-3"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" id="add-seo-keyword" class="text-xs text-primary font-medium flex items-center hover:underline">
                                    <i data-lucide="plus" class="w-3 h-3 mr-1"></i> Add Keyword
                                </button>
                            </div>
                        </div>
                    </div>

                    
                    <button type="submit" id="btn-save"
                        class="px-6 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90 flex items-center gap-2 mt-5">
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
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                const item = e.target.closest('.repeater-item') || e.target.closest('.keyword-item');
                item.remove();
            }
        });

        document.getElementById('title-preset').addEventListener('change', function() {
            const input = document.getElementById('hero-title-input');
            input.value = this.value;
            updatePreview('hero-title-input');
        });

        document.getElementById('add-channel').addEventListener('click', function() {
            const container = document.getElementById('channels-container');
            const div = document.createElement('div');
            
            div.className = 'repeater-item border border-slate-200 rounded-md p-5 bg-slate-50 relative group';
            
            div.innerHTML = `
                <div class="absolute top-3 right-3">
                    <button type="button" class="remove-item text-slate-400 hover:text-danger">
                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-slate-500">Label</label>
                        <input type="text" class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control channel-title" placeholder="e.g. Email">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-slate-500">Value (Display)</label>
                        <input type="text" class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control channel-value" placeholder="e.g. hello@example.com">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-medium text-slate-500">Link (Href)</label>
                        <input type="text" class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control channel-link" placeholder="e.g. mailto:hello@example.com">
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-500">Icon</label>
                            <select class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control channel-icon cursor-pointer">
                                <option value="email">EMAIL</option>
                                <option value="phone">PHONE</option>
                                <option value="location">LOCATION</option>
                                <option value="message">MESSAGE</option>
                                <option value="globe">GLOBE</option>
                                <option value="shield">SHIELD</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-xs font-medium text-slate-500">Color</label>
                            <select class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control channel-color cursor-pointer">
                                <option value="sky">SKY</option>
                                <option value="indigo">INDIGO</option>
                                <option value="slate">SLATE</option>
                                <option value="emerald">EMERALD</option>
                                <option value="rose">ROSE</option>
                                <option value="amber">AMBER</option>
                            </select>
                        </div>
                    </div>
                     <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-medium text-slate-500">Description</label>
                        <input type="text" class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control channel-description" placeholder="Small helper text">
                    </div>
                </div>
            `;
            container.appendChild(div);
            lucide.createIcons();
            div.querySelector('input').focus();
        });

        document.getElementById('add-seo-keyword').addEventListener('click', function() {
            const container = document.getElementById('seo-keywords-container');
            const div = document.createElement('div');
            div.className = 'flex items-center gap-1.5 keyword-item bg-slate-100 border border-slate-200 rounded px-2 py-1';
            div.innerHTML = `
                <input type="text" class="bg-transparent border-none p-0 text-xs w-24 keyword-input focus:ring-0" placeholder="New keyword">
                <button type="button" class="remove-item text-slate-400 hover:text-danger">
                    <i data-lucide="x" class="w-3 h-3"></i>
                </button>
            `;
            container.appendChild(div);
            lucide.createIcons();
            div.querySelector('input').focus();
        });

        // Format Helpers
        function insertAtCursor(id, text) {
            const el = document.getElementById(id);
            const start = el.selectionStart;
            const end = el.selectionEnd;
            const val = el.value;
            el.value = val.substring(0, start) + text + val.substring(end);
            el.selectionStart = el.selectionEnd = start + text.length;
            el.focus();
            updatePreview(id);
        }

        function wrapText(id, open, close) {
            const el = document.getElementById(id);
            const start = el.selectionStart;
            const end = el.selectionEnd;
            const val = el.value;
            const selected = val.substring(start, end);
            if (selected.length === 0) return;
            
            el.value = val.substring(0, start) + open + selected + close + val.substring(end);
            el.focus();
            updatePreview(id);
        }

        function updatePreview(id) {
            const val = document.getElementById(id).value;
            const previewId = id.replace('-input', '-preview');
            const previewEl = document.getElementById(previewId);
            if (previewEl) {
                previewEl.innerHTML = val || '<span class="opacity-20 italic">No Content</span>';
            }
        }

        ['hero-title-input', 'social-title-input'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', () => updatePreview(id));
            }
        });

        const form = document.getElementById('contact-form');
        const submitBtn = document.getElementById('btn-save');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnSpinner = document.getElementById('btn-spinner');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            submitBtn.disabled = true;
            btnText.classList.add('hidden');
            btnSpinner.classList.remove('hidden');

            // Remove previous styles errors
            form.querySelectorAll('.border-danger').forEach(el => el.classList.remove('border-danger'));

            const channels = [];
            document.querySelectorAll('#channels-container .repeater-item').forEach((item, i) => {
                const titleVal = item.querySelector('.channel-title').value.trim();
                const valueVal = item.querySelector('.channel-value').value.trim();
                
                // Allow saving if at least Title OR Value is present
                if (titleVal || valueVal) {
                    channels.push({
                        id: i + 1,
                        title: titleVal,
                        icon: item.querySelector('.channel-icon').value,
                        value: valueVal,
                        link: item.querySelector('.channel-link').value.trim(),
                        description: item.querySelector('.channel-description').value.trim(),
                        color: item.querySelector('.channel-color').value,
                    });
                }
            });

            const keywords = Array.from(document.querySelectorAll('.keyword-input')).map(i => i.value.trim()).filter(Boolean);

            const data = {
                hero_badge: form.querySelector('[name="hero_badge"]').value,
                hero_title: form.querySelector('[name="hero_title"]').value,
                hero_description: form.querySelector('[name="hero_description"]').value,
                social_title: form.querySelector('[name="social_title"]').value,
                social_description: form.querySelector('[name="social_description"]').value,
                seo_title: form.querySelector('[name="seo_title"]').value,
                seo_description: form.querySelector('[name="seo_description"]').value,
                channels: channels,
                seo_keywords: keywords,
                _method: 'PUT'
            };

            try {
                const response = await axios.post('/contact-settings', data);
                if (typeof showToast === 'function') {
                    showToast('success', 'Success', response.data.message || 'Contact settings updated successfully.');
                }
            } catch (error) {
                console.error(error);
                if (error.response?.status === 422) {
                    const errors = error.response.data.errors;
                    const messages = Object.values(errors).flat().join('<br>');
                    
                    if (typeof showToast === 'function') {
                        showToast('failed', 'Validation Error', messages);
                    } else {
                        alert('Validation Error: ' + messages);
                    }

                    // Highlight fields (simplified logic for nested names, might need adjustment if strict)
                     Object.keys(errors).forEach(key => {
                        const input = form.querySelector(`[name="${key}"]`);
                        if(input) input.classList.add('border-danger');
                    });
                } else {
                     if (typeof showToast === 'function') {
                        showToast('failed', 'Error', 'System error occurred. Please try again.');
                    } else {
                        alert('Error saving settings.');
                    }
                }
            } finally {
                submitBtn.disabled = false;
                btnText.classList.remove('hidden');
                btnSpinner.classList.add('hidden');
            }
        });
    </script>
@endpush
