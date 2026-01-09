@extends('layouts.app')
@section('title', 'Add Knowledge Base')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        {{-- Header --}}
        <div class="flex flex-col gap-1 mb-8">
            <h2 class="text-xl font-semibold group-[.mode--light]:text-white">
                Add New Knowledge
            </h2>
            <a href="{{ route('ai.knowledge.index') }}" class="text-primary hover:underline">
                &larr; Back to List
            </a>
        </div>

        <div class="mt-3.5 grid grid-cols-12 gap-x-6 gap-y-10">
            <div class="relative col-span-12 flex flex-col gap-y-7">
                <div class="box box--stacked flex flex-col p-5">

                    <form id="knowledge-form">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                            {{-- LEFT: Content --}}
                            <div class="space-y-8 p-5">
                                <div class="pb-3 border-b border-slate-200/70">
                                    <h3 class="text-sm font-semibold text-slate-700">Knowledge Content</h3>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Topic / Subject <span class="text-danger">*</span></label>
                                        <input type="text" name="topic" required class="form-control w-full rounded-md border-slate-200" placeholder="e.g., How to reset password">
                                    </div>
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Main Content / Answer <span class="text-danger">*</span></label>
                                        <textarea name="content" rows="15" required class="form-control w-full rounded-md border-slate-200" placeholder=" The core information the AI should know..."></textarea>
                                        <div class="text-xs text-slate-500 mt-1">This is the primary knowledge the AI will retrieve.</div>
                                    </div>
                                    
                                    {{-- Advanced Context --}}
                                    <div class="pt-6 border-t border-slate-200/50">
                                        <label class="block mb-3 text-sm font-medium mt-5">Context / Scenarios</label>
                                        <textarea name="context" rows="10" class="form-control w-full rounded-md border-slate-200" placeholder="When does this apply?"></textarea>
                                    </div>
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Examples / Variations</label>
                                        <textarea name="examples" rows="10" class="form-control w-full rounded-md border-slate-200" placeholder="Example user queries matching this..."></textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- RIGHT: Settings --}}
                            <div class="space-y-8 p-5">
                                <div class="pb-3 border-b border-slate-200/70">
                                    <h3 class="text-sm font-semibold text-slate-700">Settings & Metadata</h3>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Category <span class="text-danger">*</span></label>
                                        <select name="category" required class="form-select w-full rounded-md border-slate-200">
                                            <option value="">Select Category...</option>
                                            <option value="General">General</option>
                                            <option value="Technical Support">Technical Support</option>
                                            <option value="Billing">Billing</option>
                                            <option value="Security">Security</option>
                                            <option value="Policy">Policy</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Source</label>
                                        <input type="text" name="source" value="Admin Entry" class="form-control w-full rounded-md border-slate-200" placeholder="e.g., Documentation">
                                    </div>

                                    {{-- TAGS MANAGER --}}
                                    <div>
                                        <label class="block mb-3 text-sm font-medium mt-5">Tags (Keywords)</label>
                                        <div class="p-2 border border-slate-200 rounded-md bg-white flex flex-wrap gap-2 items-center focus-within:ring-1 focus-within:ring-primary focus-within:border-primary">
                                            <div id="tags-container" class="flex flex-wrap gap-2"></div>
                                            <input type="text" id="tag-input" class="border-none focus:ring-0 text-sm p-1 min-w-[100px] flex-1" placeholder="Type & Hit Enter...">
                                        </div>
                                        {{-- Hidden Input for Serialization --}}
                                        <input type="hidden" name="tags" id="tags-hidden">
                                    </div>
                                    
                                     {{-- REFERENCES MANAGER --}}
                                     <div>
                                        <div class="flex items-center justify-between mt-5 mb-3">
                                            <label class="block text-sm font-medium">References</label>
                                            <button type="button" onclick="addReferenceRow()" class="text-xs text-primary font-bold hover:underline">+ Add Link</button>
                                        </div>
                                        <div id="references-container" class="space-y-2">
                                            {{-- Rows will be injected here --}}
                                        </div>
                                        {{-- Hidden Input for Serialization --}}
                                        <input type="hidden" name="references" id="references-hidden">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-8 mt-10 border-t border-slate-200/70">
                            <button type="submit" id="btn-save" class="px-8 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90">
                                Save Knowledge
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
        axios.defaults.headers.common['Accept'] = 'application/json';
        
        // --- TAGS LOGIC ---
        let tags = []; 
        const tagInput = document.getElementById('tag-input');
        const tagsContainer = document.getElementById('tags-container');
        const tagsHidden = document.getElementById('tags-hidden');

        function renderTags() {
            tagsContainer.innerHTML = '';
            tags.forEach((tag, index) => {
                const badge = document.createElement('div');
                badge.className = 'px-3 py-1 text-xs font-semibold bg-primary/10 text-primary rounded-full flex items-center gap-2 border border-primary/20 animate-fadeIn';
                badge.innerHTML = `
                    ${tag}
                    <button type="button" onclick="removeTag(${index})" class="hover:text-danger"><i data-lucide="x" class="w-3 h-3"></i></button>
                `;
                tagsContainer.appendChild(badge);
            });
            tagsHidden.value = JSON.stringify(tags); // Serialization
            if(window.lucide) lucide.createIcons();
        }

        tagInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                const val = e.target.value.trim();
                if (val && !tags.includes(val)) {
                    tags.push(val);
                    renderTags();
                    e.target.value = '';
                }
            } else if (e.key === 'Backspace' && !e.target.value && tags.length > 0) {
                tags.pop();
                renderTags();
            }
        });

        window.removeTag = (index) => {
            tags.splice(index, 1);
            renderTags();
        }

        // --- REFERENCES LOGIC ---
        const refContainer = document.getElementById('references-container');
        const refHidden = document.getElementById('references-hidden');

        window.addReferenceRow = (label = '', url = '') => {
            const row = document.createElement('div');
            row.className = 'grid grid-cols-12 gap-2 animate-fadeIn ref-row';
            row.innerHTML = `
                <div class="col-span-4">
                    <input type="text" class="ref-label form-control w-full text-xs rounded-md border-slate-200" placeholder="Title/Label" value="${label}">
                </div>
                <div class="col-span-7">
                    <input type="text" class="ref-url form-control w-full text-xs rounded-md border-slate-200" placeholder="https://example.com" value="${url}">
                </div>
                <div class="col-span-1 flex items-center justify-center">
                    <button type="button" onclick="this.closest('.ref-row').remove()" class="text-slate-400 hover:text-danger"><i data-lucide="trash-2" class="w-4 h-4"></i></button>
                </div>
            `;
            refContainer.appendChild(row);
             if(window.lucide) lucide.createIcons();
        }

        function serializeReferences() {
            const refs = {};
            document.querySelectorAll('.ref-row').forEach(row => {
                const label = row.querySelector('.ref-label').value.trim();
                const url = row.querySelector('.ref-url').value.trim();
                if (label && url) {
                    refs[label] = url;
                }
            });
            return JSON.stringify(refs);
        }

        // Initialize empty rows
        if(refContainer.children.length === 0) {
            // Optional: Start empty or with one row. User can add.
        }


        // --- FORM SUBMIT ---
        const form = document.getElementById('knowledge-form');
        const btn = document.getElementById('btn-save');

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const originalText = btn.innerText;
            btn.disabled = true;
            btn.innerText = 'Saving...';

            // Sync hidden inputs
            tagsHidden.value = JSON.stringify(tags);
            refHidden.value = serializeReferences();

            const formData = new FormData(form);
            const data = Object.fromEntries(formData.entries());

            try {
                const res = await axios.post("{{ route('ai.knowledge.store') }}", data);
                
                if (typeof showToast === 'function') {
                    showToast('success', 'Success', 'Knowledge item created successfully');
                } else {
                    alert('Knowledge item created successfully');
                }

                setTimeout(() => {
                    window.location.href = "{{ route('ai.knowledge.index') }}";
                }, 1000);

            } catch (err) {
                console.error(err);
                if (err.response && err.response.data && err.response.data.errors) {
                    const errors = Object.values(err.response.data.errors).flat().join('\n');
                     if (typeof showToast === 'function') {
                        showToast('failed', 'Validation Error', errors);
                    } else {
                        alert('Validation Error:\n' + errors);
                    }
                } else {
                     if (typeof showToast === 'function') {
                        showToast('failed', 'Error', 'Failed to save knowledge item');
                    } else {
                        alert('Failed to save knowledge item');
                    }
                }
                btn.disabled = false;
                btn.innerText = originalText;
            }
        });
    </script>
@endsection
