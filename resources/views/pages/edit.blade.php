@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        {{-- Header --}}
        <div class="flex flex-col gap-1 mb-8">
            <h2 class="text-xl font-semibold group-[.mode--light]:text-white">
                Edit Page: {{ $page->title }}
            </h2>
            <a href="{{ route('pages.index') }}" class="text-primary hover:underline">
                &larr; Back to List
            </a>
        </div>

        <div class="mt-3.5 grid grid-cols-12 gap-x-6 gap-y-10">
            <div class="relative col-span-12 flex flex-col gap-y-7">
                <div class="box box--stacked flex flex-col p-5">

                    <form id="page-form">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="id" value="{{ $page->id }}">

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            {{-- LEFT --}}
                            <div class="space-y-8 p-5">
                                <div class="pb-3 border-b border-slate-200/70">
                                    <h3 class="text-sm font-semibold text-slate-700">Hero Section Content</h3>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Hero Title</label>
                                        <input type="text" name="hero_title" value="{{ $page->hero_title }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Hero Subtitle</label>
                                        <input type="text" name="hero_subtitle" value="{{ $page->hero_subtitle }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Hero Description</label>
                                        <textarea name="hero_description" rows="4" class="form-control w-full rounded-md border-slate-200">{{ $page->hero_description }}</textarea>
                                    </div>
                                     <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Hero Background Image URL</label>
                                        <input type="text" name="hero_bg_image" value="{{ $page->hero_bg_image }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                </div>

                                <div class="pb-3 border-b border-slate-200/70 mt-8">
                                    <h3 class="text-sm font-semibold text-slate-700">SEO Settings</h3>
                                </div>
                                <div class="space-y-6">
                                     <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Meta Title</label>
                                        <input type="text" name="meta_title" value="{{ $page->meta_title }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                     <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Meta Description</label>
                                        <textarea name="meta_description" rows="3" class="form-control w-full rounded-md border-slate-200">{{ $page->meta_description }}</textarea>
                                    </div>
                                     <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Meta Keywords</label>
                                        <input type="text" name="meta_keywords" value="{{ $page->meta_keywords }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                     <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">OG Image URL</label>
                                        <input type="text" name="og_image" value="{{ $page->og_image }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT --}}
                            <div class="space-y-8 p-5">
                                <div class="pb-3 border-b border-slate-200/70">
                                    <h3 class="text-sm font-semibold text-slate-700">Buttons & Actions</h3>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Primary Button Text</label>
                                        <input type="text" name="primary_button_text" value="{{ $page->primary_button_text }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Primary Button URL</label>
                                        <input type="text" name="primary_button_url" value="{{ $page->primary_button_url }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                    <div class="border-t border-slate-200/50 pt-4">
                                        <label class="block mb-3 text-sm font-medium mt-5">Secondary Button Text</label>
                                        <input type="text" name="secondary_button_text" value="{{ $page->secondary_button_text }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Secondary Button URL</label>
                                        <input type="text" name="secondary_button_url" value="{{ $page->secondary_button_url }}" class="form-control w-full rounded-md border-slate-200">
                                    </div>
                                    
                                    <div class="flex items-center gap-4 mt-6">
                                        <label class="text-sm font-medium">Active Status</label>
                                        <input type="checkbox" name="is_active" value="1" {{ $page->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-200 text-primary focus:ring-primary">
                                    </div>
                                </div>
                            </div>
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
        
        const form = document.getElementById('page-form')
        const btn = document.getElementById('btn-save')
        const pageId = "{{ $page->id }}"

        form.addEventListener('submit', async (e) => {
            e.preventDefault()
            btn.disabled = true
            btn.innerText = 'Saving...'

            const formData = new FormData(form)
            // Handle checkbox manually if unchecked
            if (!formData.has('is_active')) formData.append('is_active', 0)
            
            const data = Object.fromEntries(formData.entries());
            data.is_active = formData.has('is_active') ? 1 : 0;

            try {
                const res = await axios.put(`/pages/${pageId}`, data)
                if (typeof showToast === 'function') {
                    showToast('success', 'Success', 'Page updated successfully')
                } else {
                    alert('Page updated successfully')
                }
            } catch (err) {
                console.error(err)
                if (typeof showToast === 'function') {
                    showToast('failed', 'Error', 'Failed to update page')
                } else {
                    alert('Failed to update page')
                }
            }

            btn.disabled = false
            btn.innerText = 'Save Changes'
        })
    </script>
@endsection
