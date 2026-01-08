@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        {{-- Header --}}
        <div class="flex flex-col gap-1 mb-8">
            <h2 class="text-xl font-semibold group-[.mode--light]:text-white">
                Edit Section: {{ $section->section_name }}
            </h2>
            <a href="{{ route('home_sections.index') }}" class="text-primary hover:underline">
                &larr; Back to List
            </a>
        </div>

        <div class="mt-3.5 grid grid-cols-12 gap-x-6 gap-y-10">
            <div class="relative col-span-12 flex flex-col gap-y-7">
                <div class="box box--stacked flex flex-col p-5">

                    <form id="section-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $section->id }}">

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            {{-- LEFT --}}
                            <div class="space-y-8 p-5">
                                <div class="pb-3 border-b border-slate-200/70">
                                    <h3 class="text-sm font-semibold text-slate-700">Content</h3>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Title</label>
                                        <input type="text" name="title" value="{{ $section->title }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Subtitle</label>
                                        <input type="text" name="subtitle" value="{{ $section->subtitle }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Description</label>
                                        <textarea name="description" rows="4" class="form-control w-full rounded-md border-slate-200">{{ $section->description }}</textarea>
                                    </div>
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Badge Text</label>
                                        <input type="text" name="badge_text" value="{{ $section->badge_text }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT --}}
                            <div class="space-y-8 p-5">
                                <div class="pb-3 border-b border-slate-200/70">
                                    <h3 class="text-sm font-semibold text-slate-700">Settings & Actions</h3>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Primary Button Text</label>
                                        <input type="text" name="primary_button_text" value="{{ $section->primary_button_text }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Primary Button URL</label>
                                        <input type="text" name="primary_button_url" value="{{ $section->primary_button_url }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                    <div class="border-t border-slate-200/50 pt-4">
                                        <label class="block mb-3 text-sm font-medium mt-5">Secondary Button Text</label>
                                        <input type="text" name="secondary_button_text" value="{{ $section->secondary_button_text }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Secondary Button URL</label>
                                        <input type="text" name="secondary_button_url" value="{{ $section->secondary_button_url }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                    
                                     <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Background Image URL</label>
                                        <input type="text" name="background_image" value="{{ $section->background_image }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>

                                    <div class="flex items-center gap-4 mt-6">
                                        <label class="text-sm font-medium">Active Status</label>
                                        <input type="checkbox" name="is_active" value="1" {{ $section->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-200 text-primary focus:ring-primary">
                                    </div>
                                </div>
                            </div>
                        </div>

                         {{-- Settings JSON --}}
                        <div class="p-5 border-t border-slate-200/70 mt-5">
                             <div class="pb-3 border-b border-slate-200/70 mb-4">
                                <h3 class="text-sm font-semibold text-slate-700">Advanced Settings (JSON)</h3>
                            </div>
                             <textarea name="settings" rows="5" class="form-control w-full rounded-md border-slate-200 font-mono text-xs">{{ json_encode($section->settings, JSON_PRETTY_PRINT) }}</textarea>
                             <p class="text-xs text-slate-500 mt-1">Ensure valid JSON format.</p>
                        </div>

                        <div class="flex justify-end pt-8 mt-10 border-t border-slate-200/70">
                            <button type="submit" id="btn-save" class="px-8 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90">
                                Save Changes
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
        
        const form = document.getElementById('section-form')
        const btn = document.getElementById('btn-save')
        const sectionId = "{{ $section->id }}"

        form.addEventListener('submit', async (e) => {
            e.preventDefault()
            btn.disabled = true
            btn.innerText = 'Saving...'

            const formData = new FormData(form)
            // Handle checkbox manually if unchecked
            if (!formData.has('is_active')) formData.append('is_active', 0)
            
            // Handle settings JSON parsing if needed, but the backend casts array, 
            // so we might need to send it as array. 
            // Actually, FormData sends strings. Laravel cast 'array' expects the input to be an array OR a JSON string that it automatically decodes? 
            // Wait, standard Laravel Request casts: if I send "settings" as a string, it might not cast correctly if it expects an array from input.
            // But if I use $casts in model, it casts when retrieving/saving to DB.
            // In Controller, $request->validate(['settings' => 'nullable|array']) will fail if I send a string.
            // So I should parse it here and send as JSON content type or handle in controller.
            // Let's send key-value pairs? No, hierarchical data in FormData is tricky.
            // Best to send as JSON payload instead of FormData.

            const data = Object.fromEntries(formData.entries());
            data.is_active = formData.has('is_active') ? 1 : 0;
            
            try {
                 if (data.settings) {
                    try {
                        data.settings = JSON.parse(data.settings)
                    } catch (err) {
                        alert('Invalid JSON in Settings')
                        btn.disabled = false;
                        btn.innerText = 'Save Changes';
                        return
                    }
                }
            } catch(e) {}

            try {
                const res = await axios.put(`/home-sections/${sectionId}`, data)
                // Use the showToast function if available (from global scripts), else alert
                if (typeof showToast === 'function') {
                    showToast('success', 'Success', 'Section updated successfully')
                } else {
                    alert('Section updated successfully')
                }
            } catch (err) {
                console.error(err)
                if (typeof showToast === 'function') {
                    showToast('failed', 'Error', 'Failed to update section')
                } else {
                    alert('Failed to update section')
                }
            }

            btn.disabled = false
            btn.innerText = 'Save Changes'
        })
    </script>
@endsection
