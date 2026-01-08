@extends('layouts.app')
@section('title', 'Pages Management')
@section('content')
    <div class="col-span-12">
        <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
            <div class="text-base font-medium group-[.mode--light]:text-white"> Pages Management </div>
        </div>
        <div class="mt-3.5 flex flex-col gap-8">
            <div class="box box--stacked flex flex-col">
                <div class="overflow-auto xl:overflow-visible">
                    <table class="w-full text-left border-b border-slate-200/60">
                        <thead class="">
                            <tr class="">
                                <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                    Page Title / Key
                                </td>
                                <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500">
                                    Hero Title
                                </td>
                                <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 font-medium text-slate-500 text-center">
                                    Status
                                </td>
                                <td class="px-5 border-b dark:border-darkmode-300 border-t border-slate-200/60 bg-slate-50 py-4 text-center font-medium text-slate-500">
                                    Action
                                </td>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        const tableBody = document.getElementById('table-body')

        function renderPages(pages) {
            tableBody.innerHTML = ''
            if (!pages || pages.length === 0) {
                tableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4">No pages found</td></tr>'
                return
            }

            pages.forEach(page => {
                const statusClass = page.is_active ? 'text-success' : 'text-slate-400'
                const statusText = page.is_active ? 'Active' : 'Inactive'

                const tr = document.createElement('tr')
                tr.innerHTML = `
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                        <div class="font-medium">${page.title}</div>
                        <div class="text-xs text-slate-500">${page.key}</div>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                        <div class="font-medium truncate w-64">${page.hero_title || '-'}</div>
                        <div class="text-xs text-slate-500 truncate w-64">${page.hero_subtitle || '-'}</div>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600">
                         <div class="flex items-center justify-center ${statusClass}">
                            <div class="ml-1.5 whitespace-nowrap">${statusText}</div>
                        </div>
                    </td>
                    <td class="px-5 border-b border-dashed py-4 dark:bg-darkmode-600 text-center">
                         <a href="/pages/${page.id}/edit" class="text-primary hover:underline">Edit</a>
                    </td>
                `
                tableBody.appendChild(tr)
            })
        }

        function loadPages() {
            axios.get('/pages/list')
                .then(res => {
                    renderPages(res.data.data)
                })
                .catch(err => {
                    console.error('Failed to load pages', err)
                })
        }

        window.addEventListener('load', loadPages)
    </script>
@endpush
