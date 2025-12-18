<?php

namespace App\Http\Controllers;

use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class AiChatController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    private function ok($data = null, string $message = 'OK', int $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    private function fail(string $message = 'Request failed', $errors = null, int $code = 400)
    {
        $payload = [
            'status' => false,
            'message' => $message,
        ];

        if (!is_null($errors)) {
            $payload['errors'] = $errors;
        }

        return response()->json($payload, $code);
    }

    public function index()
    {
        return view('ai_chat.sessions', [
            'sessions' => AiChatSession::orderByDesc('last_activity_at')->get(),
        ]);
    }

    public function list(Request $request)
    {
        $query = AiChatSession::query()->orderByDesc('last_activity_at');

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->q.'%')
                  ->orWhere('model', 'like', '%'.$request->q.'%');
        }

        return $this->ok($query->get());
    }

    public function show(AiChatSession $session)
    {
        return view('ai_chat.session_detail', [
            'session' => $session->load([
                'messages' => fn ($q) => $q->orderBy('id'),
            ]),
        ]);
    }

    public function detail(AiChatSession $session)
    {
        return $this->ok([
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string',
            'session_id' => 'nullable|exists:ai_chat_sessions,id',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        if ($request->filled('session_id')) {
            $session = AiChatSession::find($request->session_id);
        } else {
            $session = AiChatSession::create([
                'user_id' => auth()->id(),
                'title' => Str::limit($request->message, 50),
                'model' => 'Virologi-o1',
                'session_token' => Str::random(40),
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'last_activity_at' => now(),
            ]);
        }

        $userMessage = AiChatMessage::create([
            'session_id' => $session->id,
            'role' => 'user',
            'content' => $request->message,
        ]);

        $aiContent = 'Permintaan Anda sedang diproses oleh Virologi AI.';

        $aiMessage = AiChatMessage::create([
            'session_id' => $session->id,
            'role' => 'assistant',
            'content' => $aiContent,
            'response_engine' => 'Virologi-o1',
        ]);

        $session->update([
            'last_activity_at' => now(),
        ]);

        return $this->ok([
            'session_id' => $session->id,
            'messages' => [
                $userMessage,
                $aiMessage,
            ],
        ], 'Message sent');
    }

    public function destroy(AiChatSession $session)
    {
        $session->messages()->delete();
        $session->delete();

        return $this->ok(null, 'Chat session deleted');
    }
}
