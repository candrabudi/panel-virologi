@extends('layouts.app')
@section('title', 'Data Requests')

@section('content')
    <div class="col-span-12">
        <div class="mb-8 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold">Request History</h2>
                <p class="text-sm text-slate-500">
                    Track your data access requests and their status.
                </p>
            </div>
            <a href="{{ route('leak_request.create') }}" class="btn btn-primary">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> New Request
            </a>
        </div>

        <div class="box box--stacked flex flex-col">
            <div class="overflow-auto xl:overflow-visible">
                <table class="w-full text-left border-b border-slate-200/60">
                    <thead class="bg-slate-50 text-slate-500 font-medium">
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-slate-200/60">Date</th>
                            <th class="px-5 py-3 border-b-2 border-slate-200/60">Subject / Query</th>
                            <th class="px-5 py-3 border-b-2 border-slate-200/60">Department</th>
                            <th class="px-5 py-3 border-b-2 border-slate-200/60">Reason</th>
                            <th class="px-5 py-3 border-b-2 border-slate-200/60">Status</th>
                            <th class="px-5 py-3 border-b-2 border-slate-200/60 text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($requests as $req)
                            <tr class="border-b border-dashed border-slate-200 hover:bg-slate-50/50 transition-colors">
                                <td class="px-5 py-3">
                                    <div class="text-slate-700 font-medium">{{ $req->created_at->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-400">{{ $req->created_at->format('H:i') }}</div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="font-semibold text-primary">{{ $req->query }}</div>
                                    @if($req->leakCheckLog)
                                        <div class="text-[10px] text-slate-400">Ref ID: {{ $req->leakCheckLog->id }}</div>
                                    @endif
                                </td>
                                <td class="px-5 py-3">
                                    <div class="text-slate-600">{{ $req->department ?? '-' }}</div>
                                </td>
                                <td class="px-5 py-3">
                                    <div class="text-slate-500 text-xs truncate max-w-[200px]" title="{{ $req->reason }}">
                                        {{ $req->reason }}
                                    </div>
                                </td>
                                <td class="px-5 py-3">
                                    @if($req->status === 'approved')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-success/20 text-success border border-success/20">Approved</span>
                                    @elseif($req->status === 'rejected')
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-danger/20 text-danger border border-danger/20">Rejected</span>
                                    @else
                                        <span class="px-2 py-1 rounded-full text-xs font-bold bg-warning/20 text-warning border border-warning/20">Pending</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3 text-right">
                                    <a href="{{ route('leak_request.show', $req->id) }}" class="btn btn-sm btn-outline-secondary">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                                    <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                    No requests found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="p-5 flex justify-center">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
@endsection
