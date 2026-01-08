@extends('layouts.app')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="flex flex-col gap-1 mb-8">
            <h2 class="text-xl font-semibold group-[.mode--light]:text-white">
                Footer Settings Management
            </h2>
        </div>

        <div class="mt-3.5 grid grid-cols-12 gap-x-6 gap-y-10">
            <div class="relative col-span-12 flex flex-col gap-y-7">
                <div class="box box--stacked flex flex-col p-5">
                    
                    <form id="footer-form">
                        @csrf
                        @method('PUT')

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            {{-- LEFT: Company Info & Social --}}
                            <div class="space-y-8 p-5">
                                <div class="pb-3 border-b border-slate-200/70">
                                    <h3 class="text-sm font-semibold text-slate-700">Company Information</h3>
                                </div>
                                
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-3">Description</label>
                                    <textarea name="description" rows="4" class="form-control w-full rounded-md border-slate-200 shadow-sm focus:ring-primary/20 focus:border-primary">{{ $setting->description }}</textarea>
                                </div>
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-3">Address</label>
                                    <textarea name="address" rows="2" class="form-control w-full rounded-md border-slate-200 shadow-sm focus:ring-primary/20 focus:border-primary">{{ $setting->address }}</textarea>
                                </div>
                                <div>
                                    <label class="block mb-3 text-sm font-medium mt-3">Email</label>
                                    <input type="email" name="email" value="{{ $setting->email }}" class="form-control w-full rounded-md border-slate-200 shadow-sm focus:ring-primary/20 focus:border-primary">
                                </div>
                                 <div>
                                    <label class="block mb-3 text-sm font-medium mt-3">Phone</label>
                                    <input type="text" name="phone" value="{{ $setting->phone }}" class="form-control w-full rounded-md border-slate-200 shadow-sm focus:ring-primary/20 focus:border-primary">
                                </div>
                                 <div>
                                    <label class="block mb-3 text-sm font-medium mt-3">Copyright Text</label>
                                    <input type="text" name="copyright_text" value="{{ $setting->copyright_text }}" class="form-control w-full rounded-md border-slate-200 shadow-sm focus:ring-primary/20 focus:border-primary">
                                </div>

                                <div class="pb-3 border-b border-slate-200/70 mt-8">
                                    <h3 class="text-sm font-semibold text-slate-700 font-medium">Social Links</h3>
                                </div>
                                <div id="social-links-container" class="space-y-3">
                                    @php $socialLinks = $setting->social_links ?? []; @endphp
                                    @foreach($socialLinks as $platform => $url)
                                        <div class="flex gap-2 social-link-item items-center mb-2">
                                            <input type="text" placeholder="Platform" value="{{ $platform }}" class="form-control w-1/3 rounded-md border-slate-200 platform-name shadow-sm">
                                            <input type="text" placeholder="URL" value="{{ $url }}" class="form-control w-2/3 rounded-md border-slate-200 platform-url shadow-sm">
                                            <button type="button" class="group transition-all duration-200 hover:bg-danger/10 p-2 rounded-md remove-item border border-transparent hover:border-danger/30 text-danger/70 hover:text-danger">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                                <button type="button" id="add-social-link" class="flex items-center justify-center w-full px-4 py-2.5 mt-4 text-sm font-medium transition-all duration-200 border-2 border-dashed rounded-lg border-primary/30 text-primary hover:bg-primary/5 hover:border-primary/50 group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 transition-transform duration-200 group-hover:scale-110"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                    Add Social Link
                                </button>
                            </div>

                            {{-- RIGHT: Columns --}}
                            <div class="space-y-8 p-5">
                                <div class="pb-3 border-b border-slate-200/70">
                                    <h3 class="text-sm font-semibold text-slate-700">Footer Columns</h3>
                                </div>
                                
                                {{-- Column 1 --}}
                                <div class="border border-slate-200/60 p-5 rounded-xl bg-slate-50/30 space-y-5 mt-5">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-slate-600">Column 1 Title</label>
                                        <input type="text" name="column_1_title" value="{{ $setting->column_1_title }}" class="form-control w-full rounded-md border-slate-200 shadow-sm">
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-5 mb-2">Navigation Links</label>
                                        <div id="column-1-links-container" class="space-y-2.5">
                                            @php $col1Links = $setting->column_1_links ?? []; @endphp
                                            @foreach($col1Links as $link)
                                                <div class="flex gap-2 link-item items-center mb-2">
                                                    <input type="text" placeholder="Label" value="{{ $link['text'] ?? '' }}" class="form-control w-1/2 rounded-md border-slate-200 link-text shadow-sm">
                                                    <input type="text" placeholder="URL" value="{{ $link['url'] ?? '' }}" class="form-control w-1/2 rounded-md border-slate-200 link-url shadow-sm">
                                                    <button type="button" class="group transition-all duration-200 hover:bg-danger/10 p-2 rounded-md remove-item border border-transparent hover:border-danger/30 text-danger/70 hover:text-danger">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="flex items-center px-4 py-2 mt-3 text-xs font-semibold transition-all duration-200 bg-white border rounded-lg shadow-sm border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 add-column-link" data-target="column-1-links-container">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                            Add Link
                                        </button>
                                    </div>
                                </div>

                                {{-- Column 2 --}}
                                <div class="border border-slate-200/60 p-5 rounded-xl bg-slate-50/30 space-y-5 mt-5">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-slate-600">Column 2 Title</label>
                                        <input type="text" name="column_2_title" value="{{ $setting->column_2_title }}" class="form-control w-full rounded-md border-slate-200 shadow-sm">
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-5 mb-2">Navigation Links</label>
                                        <div id="column-2-links-container" class="space-y-2.5">
                                            @php $col2Links = $setting->column_2_links ?? []; @endphp
                                            @foreach($col2Links as $link)
                                                <div class="flex gap-2 link-item items-center mb-2">
                                                    <input type="text" placeholder="Label" value="{{ $link['text'] ?? '' }}" class="form-control w-1/2 rounded-md border-slate-200 link-text shadow-sm">
                                                    <input type="text" placeholder="URL" value="{{ $link['url'] ?? '' }}" class="form-control w-1/2 rounded-md border-slate-200 link-url shadow-sm">
                                                    <button type="button" class="group transition-all duration-200 hover:bg-danger/10 p-2 rounded-md remove-item border border-transparent hover:border-danger/30 text-danger/70 hover:text-danger">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="flex items-center px-4 py-2 mt-3 text-xs font-semibold transition-all duration-200 bg-white border rounded-lg shadow-sm border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 add-column-link" data-target="column-2-links-container">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                            Add Link
                                        </button>
                                    </div>
                                </div>

                                {{-- Column 3 --}}
                                <div class="border border-slate-200/60 p-5 rounded-xl bg-slate-50/30 space-y-5 mt-5">
                                    <div>
                                        <label class="block mb-2 text-sm font-medium text-slate-600">Column 3 Title</label>
                                        <input type="text" name="column_3_title" value="{{ $setting->column_3_title }}" class="form-control w-full rounded-md border-slate-200 shadow-sm">
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-5 mb-2">Navigation Links</label>
                                        <div id="column-3-links-container" class="space-y-2.5">
                                            @php $col3Links = $setting->column_3_links ?? []; @endphp
                                            @foreach($col3Links as $link)
                                                <div class="flex gap-2 link-item items-center mb-2">
                                                    <input type="text" placeholder="Label" value="{{ $link['text'] ?? '' }}" class="form-control w-1/2 rounded-md border-slate-200 link-text shadow-sm">
                                                    <input type="text" placeholder="URL" value="{{ $link['url'] ?? '' }}" class="form-control w-1/2 rounded-md border-slate-200 link-url shadow-sm">
                                                    <button type="button" class="group transition-all duration-200 hover:bg-danger/10 p-2 rounded-md remove-item border border-transparent hover:border-danger/30 text-danger/70 hover:text-danger">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>
                                                    </button>
                                                </div>
                                            @endforeach
                                        </div>
                                        <button type="button" class="flex items-center px-4 py-2 mt-3 text-xs font-semibold transition-all duration-200 bg-white border rounded-lg shadow-sm border-slate-200 text-slate-600 hover:bg-slate-50 hover:border-slate-300 add-column-link" data-target="column-3-links-container">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-1.5"><path d="M5 12h14"/><path d="M12 5v14"/></svg>
                                            Add Link
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-8 mt-10 border-t border-slate-200/70">
                            <button type="submit" id="btn-save" class="px-8 py-2.5 text-sm font-semibold text-white rounded-lg bg-primary hover:bg-primary/9 group flex items-center shadow-lg shadow-primary/20 transition-all duration-200 active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="mr-2 group-hover:animate-pulse"><path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2z"/><polyline points="17 21 17 13 7 13 7 21"/><polyline points="7 3 7 8 15 8"/></svg>
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
        
        const form = document.getElementById('footer-form')
        const btn = document.getElementById('btn-save')

        // Repeater Logic
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                e.target.closest('.flex').remove();
            }
        });

        const deleteIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-trash-2"><path d="M3 6h18"/><path d="M19 6v14c0 1-1 2-2 2H7c-1 0-2-1-2-2V6"/><path d="M8 6V4c0-1 1-2 2-2h4c1 0 2 1 2 2v2"/><line x1="10" x2="10" y1="11" y2="17"/><line x1="14" x2="14" y1="11" y2="17"/></svg>`;

        document.getElementById('add-social-link').addEventListener('click', function() {
            const container = document.getElementById('social-links-container');
            const div = document.createElement('div');
            div.className = 'flex gap-2 social-link-item items-center';
            div.innerHTML = `
                <input type="text" placeholder="Platform" class="form-control w-1/3 rounded-md border-slate-200 platform-name shadow-sm">
                <input type="text" placeholder="URL" class="form-control w-2/3 rounded-md border-slate-200 platform-url shadow-sm">
                <button type="button" class="group transition-all duration-200 hover:bg-danger/10 p-2 rounded-md remove-item border border-transparent hover:border-danger/30 text-danger/70 hover:text-danger">
                    ${deleteIcon}
                </button>
            `;
            container.appendChild(div);
        });

        document.querySelectorAll('.add-column-link').forEach(button => {
            button.addEventListener('click', function() {
                const targetId = this.getAttribute('data-target');
                const container = document.getElementById(targetId);
                const div = document.createElement('div');
                div.className = 'flex gap-2 link-item items-center mb-2';
                div.innerHTML = `
                    <input type="text" placeholder="Label" class="form-control w-1/2 rounded-md border-slate-200 link-text shadow-sm">
                    <input type="text" placeholder="URL" class="form-control w-1/2 rounded-md border-slate-200 link-url shadow-sm">
                    <button type="button" class="group transition-all duration-200 hover:bg-danger/10 p-2 rounded-md remove-item border border-transparent hover:border-danger/30 text-danger/70 hover:text-danger">
                        ${deleteIcon}
                    </button>
                `;
                container.appendChild(div);
            });
        });

        form.addEventListener('submit', async (e) => {
            e.preventDefault()
            const originalBtnText = btn.innerHTML;
            btn.disabled = true
            btn.innerHTML = '<span class="animate-spin mr-2">◌</span> Saving...'

            const formData = new FormData(form)
            const data = Object.fromEntries(formData.entries());

            // Collect Social Links
            const socialLinks = {};
            document.querySelectorAll('.social-link-item').forEach(item => {
                const platform = item.querySelector('.platform-name').value.trim();
                const url = item.querySelector('.platform-url').value.trim();
                if (platform) socialLinks[platform] = url;
            });
            data.social_links = socialLinks;

            // Collect Column Links
            ['column_1_links', 'column_2_links', 'column_3_links'].forEach(field => {
                const links = [];
                const containerId = field.replace(/_/g, '-') + '-container';
                document.querySelectorAll(`#${containerId} .link-item`).forEach(item => {
                    const text = item.querySelector('.link-text').value.trim();
                    const url = item.querySelector('.link-url').value.trim();
                    if (text) links.push({ text, url });
                });
                data[field] = links;
            });

            try {
                const res = await axios.put(`/footer-settings`, data)
                if (typeof showToast === 'function') {
                    showToast('success', 'Success', 'Footer settings updated')
                } else {
                    alert('Footer settings updated')
                }
            } catch (err) {
                console.error(err)
                if (typeof showToast === 'function') {
                    showToast('failed', 'Error', 'Failed to update footer settings')
                } else {
                    alert('Failed to update footer settings')
                }
            }

            btn.disabled = false
            btn.innerHTML = originalBtnText
        })
    </script>
@endsection
