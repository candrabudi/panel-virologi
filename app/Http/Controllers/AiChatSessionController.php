<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\AiChatSession;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AiChatSessionController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    private function authorizeSession(AiChatSession $session): void
    {
        $user = auth()->user();
        if ($user->role === 'admin' || $user->id === $session->user_id) {
            return;
        }

        Log::warning("Unauthorized AiChat session access attempt: Session ID {$session->id} by User ID " . auth()->id());
        abort(403, 'Anda tidak memiliki hak akses untuk sesi chat ini.');
    }

    public function index(): View
    {
        return view('ai_chat.sessions');
    }

    public function show(AiChatSession $session): View
    {
        $this->authorizeSession($session);
        $session->load(['user', 'messages' => fn ($q) => $q->orderBy('created_at')]);
        return view('ai_chat.session_detail', compact('session'));
    }

    /**
     * API: List chat sessions with filtering and pagination.
     */
    public function list(Request $request): JsonResponse
    {
        $user = auth()->user();
        $query = AiChatSession::with(['user', 'messages'])->orderByDesc('last_activity_at');

        // If not admin, only show own sessions
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('model', 'like', "%{$search}%")
                  ->orWhere('session_token', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%");
            });
        }

        if ($request->filled('model')) {
            $query->where('model', $request->model);
        }

        return ResponseHelper::ok($query->paginate(15));
    }

    /**
     * API: Get session detail with messages.
     */
    public function detail(AiChatSession $session): JsonResponse
    {
        $this->authorizeSession($session);
        $session->load(['user', 'messages' => fn ($q) => $q->orderBy('created_at')]);
        return ResponseHelper::ok($session);
    }

    /**
     * Remove the specified chat session.
     */
    public function destroy(AiChatSession $session): JsonResponse
    {
        $this->authorizeSession($session);
        try {
            $session->messages()->delete();
            $session->delete();
            return ResponseHelper::ok(null, 'Session chat berhasil dihapus.');
        } catch (\Throwable $e) {
            Log::error("Failed to delete chat session ID {$session->id}: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menghapus session chat.', null, 500);
        }
    }
}
