@extends('layouts.app')
@section('title', 'Tambah User')
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="col-span-12">
        <div class="mb-8">
            <h2 class="text-xl font-semibold">User</h2>
            <p class="text-sm text-slate-500">
                Tambah User Baru
            </p>
        </div>

        <form id="ajax-form" data-url="{{ route('users.store') }}" onsubmit="return false;" autocomplete="off" class="grid grid-cols-12 gap-6">

            <div class="col-span-12 lg:col-span-12 space-y-6">

                <div class="bg-white rounded-lg border border-slate-200 p-6 space-y-6 p-5">
                    <h3 class="text-sm font-semibold text-slate-700 pb-3">
                        Informasi User
                    </h3>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Username</label>
                        <input type="text" name="username"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="Username">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Email</label>
                        <input type="email" name="email"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="Email">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Password</label>
                        <input type="password" name="password"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="Password">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">Nama Lengkap</label>
                        <input type="text" name="full_name"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="Nama Lengkap">
                    </div>

                    <div class="mt-3">
                        <label class="block mb-2 text-sm font-medium">No HP</label>
                        <input type="text" name="phone_number"
                            class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control"
                            placeholder="+62xxxx">
                    </div>

                    <div class="mt-3 grid grid-cols-12 gap-4">
                        <div class="col-span-6">
                            <label class="block mb-2 text-sm font-medium">Role</label>
                            <select name="role"
                                class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control">
                                <option value="admin">Admin</option>
                                <option value="editor">Editor</option>
                                <option value="user">User</option>
                            </select>
                        </div>

                        <div class="col-span-6">
                            <label class="block mb-2 text-sm font-medium">Status</label>
                            <select name="status"
                                class="transition duration-200 ease-in-out w-full text-sm border-slate-200 shadow-sm rounded-md form-control">
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                                <option value="blocked">Blocked</option>
                            </select>
                        </div>
                    </div>

                    <button type="submit" id="btn-save"
                        class="px-6 py-2.5 text-sm font-semibold text-white rounded-md bg-primary hover:bg-primary/90 flex items-center gap-2 mt-3">
                        <span class="btn-text">Simpan User</span>

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

                try {
                    const formData = new FormData(form)
                    const response = await axios.post(url, formData)

                    showToast(
                        'success',
                        'Berhasil',
                        response.data.message ?? 'User berhasil dibuat'
                    )

                    form.reset()

                } catch (error) {

                    if (error.response?.status === 422) {
                        const messages = Object.values(error.response.data.errors)
                            .flat()
                            .join('<br>')

                        showToast('failed', 'Gagal', messages)
                    } else {
                        showToast('failed', 'Gagal', 'Terjadi kesalahan sistem')
                    }
                }

                btn.disabled = false
                spinner.classList.add('hidden')
                text.classList.remove('hidden')
            })
        })
    </script>
@endpush
