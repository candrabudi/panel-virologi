<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreAiChatMessageRequest;
use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    private function authorizeSession(AiChatSession $session): void
    {
        if ($session->user_id !== auth()->id()) {
            Log::warning("Unauthorized chat session access attempt: ID {$session->id} by User ID " . auth()->id());
            abort(403, 'Forbidden');
        }
    }

    public function index()
    {
        return view('ai_chat.sessions', [
            'sessions' => AiChatSession::where('user_id', auth()->id())
                ->orderByDesc('last_activity_at')
                ->get(),
        ]);
    }

    public function list(Request $request)
    {
        $query = AiChatSession::query()
            ->where('user_id', auth()->id())
            ->orderByDesc('last_activity_at');

        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($w) use ($q) {
                $w->where('title', 'like', '%' . $q . '%')
                  ->orWhere('model', 'like', '%' . $q . '%');
            });
        }

        return ResponseHelper::ok($query->get());
    }

    public function show(AiChatSession $session)
    {
        $this->authorizeSession($session);

        return view('ai_chat.session_detail', [
            'session' => $session->load([
                'messages' => fn ($q) => $q->orderBy('id'),
            ]),
        ]);
    }

    public function detail(AiChatSession $session)
    {
        $this->authorizeSession($session);

        return ResponseHelper::ok([
            'session' => [
                'id' => $session->id,
                'title' => $session->title,
                'model' => $session->model,
                'created_at' => $session->created_at,
                'last_activity_at' => $session->last_activity_at,
            ],
            'messages' => $session->messages()->orderBy('id')->get(),
        ]);
    }

    public function store(StoreAiChatMessageRequest $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                if ($request->filled('session_id')) {
                    $session = AiChatSession::where('user_id', auth()->id())
                        ->findOrFail($request->session_id);
                } else {
                    $session = AiChatSession::create([
                        'user_id'          => auth()->id(),
                        'title'            => Str::limit($request->message, 50),
                        'model'            => 'Virologi-o1',
                        'session_token'    => Str::random(40),
                        'ip_address'       => $request->ip(),
                        'user_agent'       => $request->userAgent(),
                        'last_activity_at' => now(),
                    ]);
                    Log::info("Chat session created: ID {$session->id} by User ID " . auth()->id());
                }

                $userMessage = AiChatMessage::create([
                    'session_id' => $session->id,
                    'role'       => 'user',
                    'content'    => $request->message,
                ]);

                // Simulation AI response
                $aiContent = 'Permintaan Anda sedang diproses oleh Virologi AI.';

                $aiMessage = AiChatMessage::create([
                    'session_id'      => $session->id,
                    'role'            => 'assistant',
                    'content'         => $aiContent,
                    'response_engine' => 'Virologi-o1',
                ]);

                $session->update([
                    'last_activity_at' => now(),
                ]);

                return ResponseHelper::ok([
                    'session_id' => $session->id,
                    'messages'   => [
                        $userMessage,
                        $aiMessage,
                    ],
                ], 'Message sent', 201);
            });
        } catch (\Throwable $e) {
            Log::error("Failed to process chat: " . $e->getMessage());
            return ResponseHelper::fail('Gagal memproses pesan chat.', null, 500);
        }
    }

    public function destroy(AiChatSession $session)
    {
        $this->authorizeSession($session);

        try {
            DB::transaction(function () use ($session) {
                $session->messages()->delete();
                $session->delete();
            });

            Log::info("Chat session deleted: ID {$session->id} by User ID " . auth()->id());
            return ResponseHelper::ok(null, 'Chat session deleted');
        } catch (\Throwable $e) {
            Log::error("Failed to delete chat session: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menghapus sesi chat.', null, 500);
        }
    }
}
