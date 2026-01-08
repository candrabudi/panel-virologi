@extends('layouts.app')

@section('title', 'AI Chat Detail')

@section('content')
<div class="col-span-12">
    <!-- Breadcrumb -->
    <div class="mb-5 flex items-center gap-3 px-1">
        <a href="{{ route('ai_chat.index') }}" class="flex h-9 w-9 items-center justify-center rounded-full bg-white border border-slate-200 text-slate-500 hover:text-primary transition-all shadow-sm">
            <i class="ri-arrow-left-line"></i>
        </a>
        <h2 class="text-base font-bold text-slate-800 uppercase tracking-tight">Audit Sesi #{{ $session->id }}</h2>
    </div>

    <!-- Main Chat Hub Wrapper -->
    <div class="flex flex-col w-full gap-y-7">
        <div class="flex flex-col p-5 bg-white rounded-[2rem] border border-slate-200 shadow-sm shadow-slate-200/50">
            
            <!-- Header Section -->
            <div class="flex items-center gap-3.5 border-b border-dashed border-slate-200 pb-6 mb-6">
                <div>
                    <div class="w-12 h-12 overflow-hidden rounded-full border-[3px] border-slate-100 bg-primary/10 flex items-center justify-center text-primary font-bold shadow-sm">
                        {{ strtoupper(substr($session->user->username ?: $session->user->email, 0, 2)) }}
                    </div>
                </div>
                <div class="min-w-0">
                    <div class="font-black truncate text-slate-800 tracking-tight">{{ $session->user->username ?: 'Anonymous' }}</div>
                    <div class="text-slate-400 mt-1 truncate text-[10px] font-bold uppercase tracking-widest flex items-center gap-2" style="margin-left: -15px;">
                        <span class="h-2 w-2 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.4)]"></span> 
                        {{ $session->model }} Intelligence
                    </div>
                </div>
                <div class="flex gap-2 ml-auto items-center">
                    <div class="hidden md:flex flex-col items-end mr-4">
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em] leading-none">Access Point</span>
                        <span class="text-[11px] font-bold text-slate-500 mt-1.5 font-mono">{{ $session->ip_address }}</span>
                    </div>
                    <div class="flex gap-1.5">
                        <button class="cursor-pointer flex items-center justify-center border rounded-full w-9 h-9 border-slate-200 bg-white text-slate-400 hover:text-primary hover:border-primary/30 transition-all shadow-sm">
                            <i class="ri-shield-user-line text-sm"></i>
                        </button>
                        <!-- <button onclick="confirmDelete()" class="cursor-pointer flex items-center justify-center border rounded-full w-9 h-9 border-red-100 bg-red-50/50 text-red-400 hover:bg-red-50 hover:text-red-600 transition-all shadow-sm">
                            <i class="ri-delete-bin-7-line text-sm"></i>
                        </button> -->
                    </div>
                </div>
            </div>

            <!-- Messages Viewport -->
            <div id="chat-container" class="h-[calc(100vh-420px)] -mx-5 overflow-y-auto px-5 scroll-smooth">
                <div class="flex flex-col gap-10 py-5">
                    @forelse($session->messages as $msg)
                        <!-- Universal Message Wrapper -->
                        <div class="w-full flex {{ $msg->role === 'user' ? 'justify-end' : 'justify-start' }}">
                            
                            <!-- Bubble Container -->
                            <div class="flex flex-col {{ $msg->role === 'user' ? 'items-end' : 'items-start' }} max-w-[85%] md:max-w-[75%] gap-2.5">
                                
                                <!-- Label Row -->
                                <div class="flex items-center gap-2 px-1">
                                    @if($msg->role === 'user')
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest order-2">{{ $session->user->username ?: 'You' }}</span>
                                        <span class="text-[10px] text-slate-300 font-bold order-1">{{ $msg->created_at->format('H:i') }}</span>
                                    @else
                                        <span class="text-[10px] font-black text-primary uppercase tracking-widest">Virologi AI</span>
                                        <span class="text-[10px] text-slate-300 font-bold">•</span>
                                        <span class="text-[10px] text-slate-300 font-bold">{{ $msg->created_at->format('H:i') }}</span>
                                    @endif
                                </div>

                                <!-- Actual Bubble (Exactly per Requested Template Style) -->
                                <div class="flex items-end gap-3 {{ $msg->role === 'user' ? 'flex-row-reverse' : '' }}">
                                    <!-- Avatar for desktop -->
                                    <div class="hidden sm:block flex-shrink-0">
                                        <div class="w-10 h-10 overflow-hidden rounded-full border-2 border-slate-100 {{ $msg->role === 'user' ? 'bg-slate-800 text-black' : 'bg-white text-primary border-slate-200' }} flex items-center justify-center text-[10px] font-black shadow-sm">
                                            {{ $msg->role === 'user' ? 'US' : 'AI' }}
                                        </div>
                                    </div>

                                    <!-- The Box -->
                                    <div class="border px-6 py-4 rounded-3xl shadow-sm transition-all duration-300 
                                        {{ $msg->role === 'user' 
                                            ? 'bg-slate-50/80 border-slate-200/80 text-slate-700 rounded-tl-none' 
                                            : 'bg-slate-50/80 border-slate-200/80 text-slate-700 rounded-tl-none' }}">
                                        
                                        <div class="text-[0.93rem] leading-relaxed whitespace-pre-wrap font-medium">{{ $msg->content }}</div>

                                        <!-- Meta stats for AI only -->
                                        @if($msg->role !== 'user' && ($msg->tokens || $msg->latency_ms))
                                            <div class="flex items-center gap-3 mt-4 pt-3 border-t border-slate-200/50">
                                                <div class="flex items-center gap-1.5">
                                                    <i class="ri-flashlight-line text-[11px] text-amber-500"></i>
                                                    <span class="text-[9px] font-black text-slate-400 tracking-tighter uppercase">{{ $msg->latency_ms }}ms</span>
                                                </div>
                                                <div class="flex items-center gap-1.5">
                                                    <i class="ri-cpu-line text-[11px] text-blue-500"></i>
                                                    <span class="text-[9px] font-black text-slate-400 tracking-tighter uppercase">{{ $msg->tokens }} Tokens</span>
                                                </div>
                                                <div class="ml-auto flex items-center gap-1">
                                                    <i class="ri-shield-check-line text-emerald-500 text-[10px]"></i>
                                                    <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest">Verified Log</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex flex-col items-center justify-center py-28 opacity-20">
                            <div class="h-20 w-20 rounded-full bg-slate-100 flex items-center justify-center mb-5">
                                <i class="ri-chat-voice-line text-4xl text-slate-400"></i>
                            </div>
                            <span class="text-xs font-black uppercase tracking-[0.4em] text-slate-400">Archive Void</span>
                        </div>
                    @endforelse
                    <div id="anchor" class="h-5"></div>
                </div>
            </div>

            <!-- Lock Message Footer -->
            <div class="mt-6">
                <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-2xl border-dashed">
                    <div class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-xl bg-emerald-50 text-emerald-500 shadow-sm">
                            <i class="ri-lock-2-line"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest leading-none">Session Locked</p>
                            <p class="text-[9px] font-bold text-slate-400 mt-1">Sesi ini telah diarsipkan dan tidak dapat dimodifikasi.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    const chatContainer = document.getElementById('chat-container');
    window.onload = () => {
        chatContainer.scrollTop = chatContainer.scrollHeight;
    };

    function confirmDelete() {
        Swal.fire({
            title: 'Hapus Sesi Log?',
            text: "Data audit ini akan dihapus secara permanen dari server.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Hapus Permanen',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#f1f5f9',
            customClass: {
                title: 'text-slate-800 font-bold',
                cancelButton: 'text-slate-600 font-medium'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete('{{ route("ai_chat.destroy", $session->id) }}')
                    .then(() => window.location.href = '{{ route("ai_chat.index") }}')
                    .catch(() => Swal.fire('Error', 'Gagal memproses penghapusan.', 'error'));
            }
        });
    }
</script>

<style>
    #chat-container::-webkit-scrollbar { width: 4px; }
    #chat-container::-webkit-scrollbar-track { background: transparent; }
    #chat-container::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    #chat-container::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endpush
