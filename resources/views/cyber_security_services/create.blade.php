@extends('layouts.app')
@section('title', 'Tambah Layanan Keamanan Siber')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="flex flex-col gap-1 mb-10">
            <div class="flex items-center gap-2">
                <a href="/cyber-security-services"
                    class="p-2 bg-slate-100 hover:bg-slate-200 rounded-lg transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-slate-500 group-hover:text-primary">
                        <path d="m15 18-6-6 6-6" />
                    </svg>
                </a>
                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-slate-800 group-[.mode--light]:text-white">
                        Buat Layanan Baru
                    </h2>
                    <p class="text-sm text-slate-500 font-medium">Tambahkan penawaran keamanan profesional ke portofolio
                        Anda</p>
                </div>
            </div>
        </div>

        <form id="service-form" class="grid grid-cols-12 gap-8">
            @csrf

            {{-- MAIN CONTENT AREA --}}
            <div class="col-span-12 lg:col-span-8 space-y-8 mt-5">

                {{-- CARD 1: Basic Info --}}
                <div class="box box--stacked flex flex-col">
                    <div class="px-10 py-6 border-b border-slate-200/60 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-slate-700">Informasi Dasar Layanan</h3>
                        </div>
                        <span
                            class="text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-white dark:bg-darkmode-600 px-3 py-1.5 rounded border shadow-sm">Bagian
                            01</span>
                    </div>

                    <div class="p-10 space-y-8">
                        <div class="grid grid-cols-12 gap-6">
                            <div class="col-span-12 lg:col-span-8">
                                <label class="block mb-2 text-sm font-bold text-slate-700">Nama Lengkap Layanan <span
                                        class="text-danger">*</span></label>
                                <input type="text" name="name" required
                                    class="form-control w-full rounded-xl border-slate-200 py-3 px-4 shadow-sm focus:ring-4 focus:ring-primary/10 transition-all"
                                    placeholder="contoh: Managed Security Operations Center">
                                <p class="mt-2 text-xs text-slate-400">Gunakan nama lengkap yang deskriptif dan profesional.
                                </p>
                            </div>
                            <div class="col-span-12 lg:col-span-4">
                                <label class="block mb-2 text-sm font-bold text-slate-700">Nama Tampilan</label>
                                <input type="text" name="short_name"
                                    class="form-control w-full rounded-xl border-slate-200 py-3 px-4 shadow-sm focus:ring-4 focus:ring-primary/10 transition-all"
                                    placeholder="contoh: Managed SOC">
                                <p class="mt-2 text-xs text-slate-400">Versi singkat untuk navigasi/sidebar.</p>
                            </div>

                            <div class="col-span-12">
                                <label class="block mb-2 text-sm font-bold text-slate-700">Kategori Layanan</label>
                                <select name="category"
                                    class="form-select w-full rounded-xl border-slate-200 py-3 shadow-sm transition-all cursor-pointer">
                                    @foreach (['soc', 'pentest', 'audit', 'incident_response', 'cloud_security', 'governance', 'training', 'consulting'] as $c)
                                        <option value="{{ $c }}">
                                            {{ strtoupper(str_replace('_', ' ', $c)) }}
                                        </option>
                                    @endforeach
                                </select>
                                <p class="mt-2 text-xs text-slate-400">Tentukan kategori utama untuk filter pada halaman
                                    publik.</p>
                            </div>

                            <div class="col-span-12">
                                <label class="block mb-2 text-sm font-bold text-slate-700">Ringkasan Eksekutif <span
                                        class="text-danger">*</span></label>
                                <textarea name="summary" rows="3" required
                                    class="form-control w-full rounded-xl border-slate-200 p-4 shadow-sm focus:ring-4 focus:ring-primary/10 transition-all"
                                    placeholder="Tinjauan singkat yang menjelaskan nilai yang diberikan layanan ini..."></textarea>
                                <p class="mt-2 text-xs text-slate-400">Ringkasan singkat 1-2 kalimat (muncul pada card
                                    listing).</p>
                            </div>

                            <div class="col-span-12">
                                <label class="block mb-3 text-sm font-bold text-slate-700">Deskripsi Detail</label>
                                <div class="rounded-xl overflow-hidden border border-slate-200">
                                    <textarea id="content-editor" name="description" rows="15" class="form-control w-full border-none shadow-sm"></textarea>
                                </div>
                                <p class="mt-2 text-xs text-slate-400">Gunakan editor ini untuk menjelaskan fitur,
                                    keunggulan, dan filosofi layanan secara detail.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- REPEATER CARDS ROW --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-5">

                    {{-- Service Scope --}}
                    <div class="box box--stacked p-10 flex flex-col mt-5 hover:border-primary/30 transition-colors group">
                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="w-12 h-12 rounded-xl bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="m9 12 2 2 4-4" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-slate-700 uppercase tracking-wider">Cakupan Layanan</h4>
                                <p class="text-xs text-slate-400 font-medium">Apa saja yang dikerjakan?</p>
                            </div>
                        </div>
                        <div id="service-scope-container" class="space-y-4 flex-1">
                            {{-- Dynamic items --}}
                        </div>
                        <button type="button"
                            class="add-repeater-item mt-6 w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-500 font-bold text-xs hover:border-primary/50 hover:bg-primary/5 hover:text-primary transition-all overflow-hidden relative group"
                            data-target="service-scope-container" data-placeholder="contoh: 24/7 Monitoring Keamanan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="M12 5v14" />
                            </svg>
                            Tambah Item Cakupan
                        </button>
                    </div>

                    {{-- Deliverables --}}
                    <div class="box box--stacked p-10 flex flex-col mt-5 hover:border-indigo/30 transition-colors group">
                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="w-12 h-12 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-500 group-hover:bg-indigo-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                    <polyline points="14 2 14 8 20 8" />
                                    <line x1="16" y1="13" x2="8" y2="13" />
                                    <line x1="16" y1="17" x2="8" y2="17" />
                                    <polyline points="10 9 9 9 8 9" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-slate-700 uppercase tracking-wider">Hasil Akhir</h4>
                                <p class="text-xs text-slate-400 font-medium">Hasil akhir (laporan/dokumen)</p>
                            </div>
                        </div>
                        <div id="deliverables-container" class="space-y-4 flex-1">
                        </div>
                        <button type="button"
                            class="add-repeater-item mt-6 w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-500 font-bold text-xs hover:border-indigo/50 hover:bg-indigo/5 hover:text-indigo-600 transition-all"
                            data-target="deliverables-container" data-placeholder="contoh: Laporan Insiden Bulanan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="M12 5v14" />
                            </svg>
                            Tambah Hasil Akhir
                        </button>
                    </div>

                    {{-- Target Audience --}}
                    <div class="box box--stacked p-10 flex flex-col mt-5 hover:border-emerald/30 transition-colors group">
                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="w-12 h-12 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-500 group-hover:bg-emerald-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                    <circle cx="9" cy="7" r="4" />
                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                    <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-slate-700 uppercase tracking-wider">Target Audiens</h4>
                                <p class="text-xs text-slate-400 font-medium">Siapa target pasarnya?</p>
                            </div>
                        </div>
                        <div id="target-audience-container" class="space-y-4 flex-1">
                        </div>
                        <button type="button"
                            class="add-repeater-item mt-6 w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-500 font-bold text-xs hover:border-emerald/50 hover:bg-emerald/5 hover:text-emerald-600 transition-all"
                            data-target="target-audience-container" data-placeholder="contoh: Lembaga Keuangan">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="M12 5v14" />
                            </svg>
                            Tambah Audiens
                        </button>
                    </div>

                    {{-- AI Keywords --}}
                    <div class="box box--stacked p-10 flex flex-col mt-5 hover:border-amber/30 transition-colors group">
                        <div class="flex items-center gap-4 mb-8">
                            <div
                                class="w-12 h-12 rounded-xl bg-amber-50 flex items-center justify-center text-amber-500 group-hover:bg-amber-100 transition-colors">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="m12 3-1.912 5.813a2 2 0 0 1-1.275 1.275L3 12l5.813 1.912a2 2 0 0 1 1.275 1.275L12 21l1.912-5.813a2 2 0 0 1 1.275-1.275L21 12l-5.813-1.912a2 2 0 0 1-1.275-1.275L12 3Z" />
                                </svg>
                            </div>
                            <div>
                                <h4 class="text-base font-bold text-slate-700 uppercase tracking-wider">Tag Pelatihan AI
                                </h4>
                                <p class="text-xs text-slate-400 font-medium">Kata Kunci untuk Agen AI</p>
                            </div>
                        </div>
                        <div id="ai-keywords-container" class="space-y-4 flex-1">
                        </div>
                        <button type="button"
                            class="add-repeater-item mt-6 w-full flex items-center justify-center gap-2 py-3 px-4 rounded-xl border-2 border-dashed border-slate-200 text-slate-500 font-bold text-xs hover:border-amber/50 hover:bg-amber/5 hover:text-amber-600 transition-all"
                            data-target="ai-keywords-container" data-placeholder="contoh: cloud protection">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M5 12h14" />
                                <path d="M12 5v14" />
                            </svg>
                            Tambah Kata Kunci AI
                        </button>
                    </div>
                </div>
            </div>

            {{-- SIDEBAR AREA --}}
            <div class="col-span-12 lg:col-span-4 space-y-8">

                {{-- GUIDANCE BOX --}}
                <div
                    class="bg-gradient-to-br from-primary to-primary/80 rounded-2xl shadow-xl p-7 text-white relative overflow-hidden group">
                    <div
                        class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700">
                    </div>
                    <div class="relative z-10">
                        <div class="flex items-center gap-3 mb-5">
                            <div
                                class="w-10 h-10 bg-white shadow-lg rounded-xl flex items-center justify-center text-primary">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" class="animate-bounce">
                                    <circle cx="12" cy="12" r="10" />
                                    <path d="M12 16h.01" />
                                    <path d="M12 8v4" />
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-black">Panduan Cepat</h3>
                        </div>
                        <ul class="space-y-4 text-sm font-medium">
                            <li class="flex gap-3" style="color: #333 text-white;">
                                <span
                                    class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-[10px] font-bold text-black">1</span>
                                <span>Gunakan <b>Nama Lengkap</b> yang mengandung kata kunci keamanan siber.</span>
                            </li>
                            <li class="flex gap-3" style="color: #333 text-white;">
                                <span
                                    class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-[10px] font-bold text-black">2</span>
                                <span><b>Ringkasan Eksekutif</b> adalah teks yang pertama kali dibaca user, buatlah
                                    semenarik mungkin.</span>
                            </li>
                            <li class="flex gap-3" style="color: #333 text-white;">
                                <span
                                    class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-[10px] font-bold text-black">3</span>
                                <span><b>Field Repeater</b> di sebelah kiri akan tampil sebagai ikon daftar di halaman
                                    produk.</span>
                            </li>
                            <li class="flex gap-3" style="color: #333 text-white;">
                                <span
                                    class="flex-shrink-0 w-6 h-6 rounded-full bg-white/20 flex items-center justify-center text-[10px] font-bold text-black">4</span>
                                <span>Pastikan <b>Kata Kunci AI</b> relevan agar Chatbot AI bisa menyarankan produk
                                    ini.</span>
                            </li>
                        </ul>
                    </div>
                </div>

                {{-- VISIBILITY --}}
                <div class="box box--stacked p-10 flex flex-col mt-5">
                    <div class="pb-6 border-b border-slate-100 flex items-center justify-between mb-8">
                        <h3 class="text-lg font-bold text-slate-700">Konfigurasi</h3>
                        <div class="w-2.5 h-2.5 rounded-full bg-primary animate-pulse"></div>
                    </div>
                    <div class="space-y-6">
                        <div
                            class="flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-slate-50/50">
                            <div>
                                <label class="text-sm font-bold text-slate-700 block">Status Layanan</label>
                                <span class="text-[10px] text-slate-400 font-medium">Muncul di halaman depan</span>
                            </div>
                            <div class="form-check form-switch p-0">
                                <input name="is_active" class="form-check-input scale-125 transition-all shadow-sm"
                                    type="checkbox" value="1" checked>
                            </div>
                        </div>
                        <div
                            class="flex items-center justify-between p-4 rounded-xl border border-slate-100 bg-slate-50/50 mt-5">
                            <div>
                                <label class="text-sm font-bold text-slate-700 block">Saran AI</label>
                                <span class="text-[10px] text-slate-400 font-medium">Dikenali oleh Agen AI</span>
                            </div>
                            <div class="form-check form-switch p-0">
                                <input name="is_ai_visible" class="form-check-input scale-125 transition-all shadow-sm"
                                    type="checkbox" value="1" checked>
                            </div>
                        </div>
                        <div>
                            <label class="block mb-2 text-sm font-bold text-slate-700 mt-5">Prioritas Tampilan</label>
                            <input type="number" name="sort_order" value="0"
                                class="form-control w-full rounded-xl border-slate-200 py-3 shadow-sm" placeholder="0">
                            <p class="mt-2 text-[10px] text-slate-400">Angka lebih tinggi tampil di urutan terakhir.</p>
                        </div>
                    </div>
                </div>

                {{-- THUMBNAIL --}}
                <div class="box box--stacked p-10 flex flex-col mt-5">
                    <h3 class="text-lg font-bold text-slate-700 mb-6">Aset Visual</h3>
                    <div class="space-y-4">
                        <div
                            class="group relative w-full h-48 bg-slate-50 dark:bg-darkmode-600 rounded-2xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center text-slate-400 overflow-hidden hover:bg-slate-100 transition-all cursor-pointer">
                            <input type="file" name="thumbnail" accept="image/*"
                                class="absolute inset-0 opacity-0 cursor-pointer z-10" id="thumbnail-input">
                            <div id="thumbnail-preview" class="absolute inset-0 hidden">
                                <img src="" class="w-full h-full object-cover">
                            </div>
                            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" class="mb-2 group-hover:scale-110 transition-transform">
                                <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                <circle cx="9" cy="9" r="2" />
                                <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                            </svg>
                            <span class="text-xs font-bold">Letakkan Gambar atau Klik</span>
                        </div>
                        <p class="text-[10px] text-center text-slate-400 font-medium">Direkomendasikan: 800x600px | Maks
                            2MB</p>
                    </div>
                </div>

                {{-- ACTION --}}
                <button type="submit" id="btn-save"
                    class="w-full flex items-center justify-center px-8 py-4 text-sm font-bold text-white rounded-2xl bg-primary hover:bg-primary-dark group shadow-xl shadow-primary/30 transition-all active:scale-95 mt-5">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
                        stroke-linejoin="round" class="mr-3 group-hover:animate-spin-slow">
                        <path d="M5 12h14" />
                        <path d="M12 5v14" />
                    </svg>
                    <span id="btn-text">Publikasikan Sekarang</span>
                </button>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tinymce@6/tinymce.min.js"></script>

    <script>
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute(
            'content');

        // TinyMCE
        tinymce.init({
            selector: '#content-editor',
            height: 500,
            plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
                'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'table', 'help',
                'wordcount'
            ],
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
                    axios.post('/upload-image', fd).then(res => resolve(res.data.location)).catch(() =>
                        reject('Upload error'))
                })
            }
        });

        // Thumbnail Preview
        document.getElementById('thumbnail-input').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('thumbnail-preview');
            const previewImg = preview.querySelector('img');

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImg.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        });

        // Repeater Logic
        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                const item = e.target.closest('.repeater-item');
                item.style.transform = 'scale(0.9)';
                item.style.opacity = '0';
                setTimeout(() => item.remove(), 200);
            }
        });

        const deleteIcon =
            `<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>`;

        document.querySelectorAll('.add-repeater-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const target = document.getElementById(this.dataset.target);
                const placeholder = this.dataset.placeholder;
                const div = document.createElement('div');
                div.className =
                    'flex gap-2 repeater-item items-center mb-3 animate-in fade-in slide-in-from-top-4 duration-300';

                let inputClass =
                    'form-control flex-1 rounded-xl border-slate-200 text-sm shadow-sm py-3 px-4 focus:ring-4 focus:ring-slate-100 transition-all';
                if (this.dataset.target.includes('scope')) inputClass += ' scope-input';
                else if (this.dataset.target.includes('deliverable')) inputClass += ' deliverable-input';
                else if (this.dataset.target.includes('audience')) inputClass += ' audience-input';
                else if (this.dataset.target.includes('keyword')) inputClass += ' keyword-input';

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
            submitBtn.innerHTML = '<span class="animate-spin mr-3">◌</span> Mengirim...';

            const fd = new FormData(form);

            const collect = (selector) => Array.from(document.querySelectorAll(selector)).map(i => i.value
                .trim()).filter(Boolean);

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

            try {
                const res = await axios.post('/cyber-security-services/store', fd);
                showToast('success', 'Sempurna!', 'Layanan baru Anda telah berhasil dipublikasikan');
                setTimeout(() => window.location.href = '/cyber-security-services', 1500);
            } catch (err) {
                console.error(err);
                const errors = err.response?.data?.errors;
                let msg = 'Terjadi kesalahan saat menyimpan.';
                if (errors) msg = Object.values(errors).flat().join('<br>');
                showToast('error', 'Ups!', msg);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnHtml;
            }
        });
    </script>
@endpush
