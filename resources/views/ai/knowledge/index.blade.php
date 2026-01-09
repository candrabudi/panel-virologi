@extends('layouts.app')
@section('title', 'AI Knowledge Base')

@section('content')
    <div class="col-span-12">
        <div class="flex flex-col gap-y-3 md:h-10 md:flex-row md:items-center">
            <div class="text-base font-medium group-[.mode--light]:text-white flex items-center gap-2">
                <i data-lucide="database" class="w-5 h-5 text-primary"></i>
                AI Knowledge Management
            </div>
            <div class="flex flex-col gap-x-3 gap-y-2 sm:flex-row md:ml-auto">
                <a href="{{ route('ai.knowledge.create') }}" class="btn btn-primary shadow-md group">
                    <i data-lucide="plus" class="w-4 h-4 mr-2 group-hover:rotate-90 transition-transform"></i>
                    Add New Knowledge
                </a>
            </div>
        </div>

        {{-- SUMMARY STATS --}}
        <div class="grid grid-cols-12 gap-6 mt-5">
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm dark:bg-darkmode-600">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-primary/10 rounded-xl flex items-center justify-center text-primary">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ $totalItems }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Total Items</div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm dark:bg-darkmode-600">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-pending/10 rounded-xl flex items-center justify-center text-pending">
                        <i data-lucide="layers" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ $totalCategories }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Categories</div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm dark:bg-darkmode-600">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-success/10 rounded-xl flex items-center justify-center text-success">
                        <i data-lucide="zap" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ $totalUsage }}</div>
                        <div class="text-[9px] text-slate-500 font-bold uppercase tracking-widest">Total Usage</div>
                    </div>
                </div>
            </div>
            <div class="col-span-6 sm:col-span-3 box p-5 border-none shadow-sm bg-theme-1 text-white">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                        <i data-lucide="target" class="w-5 h-5"></i>
                    </div>
                    <div class="ml-4">
                        <div class="text-xl font-bold">{{ number_format($avgRelevance, 1) }}</div>
                        <div class="text-[9px] text-white/70 font-bold uppercase tracking-widest">Avg Relevance</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-12 gap-6 mt-8">
            <div class="col-span-12">
                <div class="box box--stacked bg-white dark:bg-darkmode-600">
                    <div class="p-5 border-b border-slate-100 dark:border-darkmode-400 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-2">
                            <i data-lucide="list" class="w-4 h-4 text-slate-400"></i>
                            <span class="text-xs font-bold uppercase tracking-widest text-slate-600">Knowledge Repository</span>
                        </div>
                        
                        <form method="GET" action="{{ route('ai.knowledge.index') }}" class="relative w-full sm:w-64">
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search topic or content..."
                                class="form-control pl-10 pr-4 rounded-full border-slate-200 dark:border-darkmode-400 dark:bg-darkmode-800">
                            <i data-lucide="search" class="w-4 h-4 absolute top-1/2 left-3 -translate-y-1/2 text-slate-400"></i>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50 dark:bg-darkmode-400">
                                <tr>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest">Topic / Category</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest w-1/3">Content Preview</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Stats</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-center">Last Used</th>
                                    <th class="px-6 py-4 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-darkmode-400">
                                @forelse($knowledge as $item)
                                    <tr class="hover:bg-primary/5 dark:hover:bg-darkmode-700/50 transition-all border-l-4 border-transparent hover:border-primary">
                                        <td class="px-6 py-4 align-top">
                                            <div class="font-bold text-slate-700 dark:text-slate-200 mb-1">{{ $item->topic }}</div>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 dark:bg-darkmode-700 text-slate-500 border border-slate-200 dark:border-darkmode-500">
                                                {{ $item->category }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <div class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2 leading-relaxed">
                                                {{ Str::limit($item->content, 120) }}
                                            </div>
                                            @if($item->tags)
                                                <div class="flex gap-1 mt-2 flex-wrap">
                                                    @foreach(array_slice(explode(',', $item->tags), 0, 3) as $tag)
                                                        <span class="text-[9px] text-primary italic">#{{ trim($tag) }}</span>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 align-top text-center">
                                            <div class="flex flex-col gap-1 items-center">
                                                <span class="text-xs font-bold text-success">{{ $item->relevance_score }}%</span>
                                                <span class="text-[10px] text-slate-400">{{ $item->usage_count }} uses</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-top text-center">
                                            <span class="text-xs text-slate-500">
                                                {{ $item->last_used_at ? $item->last_used_at->diffForHumans() : 'Never' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 align-top text-right">
                                            <div class="flex justify-end gap-2">
                                                <a href="{{ route('ai.knowledge.edit', $item->id) }}" class="btn btn-sm btn-outline-secondary border-slate-200 dark:border-darkmode-500 p-1.5" title="Edit">
                                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                                </a>
                                                <form action="{{ route('ai.knowledge.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this item?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger border-slate-200 dark:border-darkmode-500 p-1.5" title="Delete">
                                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-12 text-center text-slate-400 italic">
                                            No knowledge items found. Start by adding one!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="p-5 border-t border-slate-100 dark:border-darkmode-400">
                        {{ $knowledge->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/lucide@latest"></script>
    <script>
        lucide.createIcons();
    </script>
@endpush
