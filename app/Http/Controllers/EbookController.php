<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class EbookController extends Controller
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
        return view('ebooks.index', [
            'ebooks' => Ebook::orderByDesc('id')->get(),
        ]);
    }

    public function list(Request $request)
    {
        $query = Ebook::query()->orderByDesc('id');

        if ($request->filled('q')) {
            $query->where('title', 'like', '%'.$request->q.'%')
                  ->orWhere('summary', 'like', '%'.$request->q.'%');
        }

        return $this->ok($query->get());
    }

    public function create()
    {
        return view('ebooks.form', [
            'ebook' => null,
        ]);
    }

    public function edit(Ebook $ebook)
    {
        return view('ebooks.form', [
            'ebook' => $ebook,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'topic' => 'required|string',
            'ai_keywords' => 'nullable|array',
            'cover_image' => 'nullable|image|max:2048',
            'file' => 'required|file|mimes:pdf|max:20480',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        $coverImage = null;
        if ($request->hasFile('cover_image')) {
            $coverImage = asset('storage/'.$request->file('cover_image')->store('ebooks/covers', 'public'));
        }

        $filePath = asset('storage/'.$request->file('file')->store('ebooks/files', 'public'));

        $ebook = Ebook::create([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'summary' => $request->summary,
            'content' => $request->content,
            'level' => $request->level,
            'topic' => $request->topic,
            'ai_keywords' => $request->ai_keywords,
            'cover_image' => $coverImage,
            'file_path' => $filePath,
            'file_type' => 'pdf',
            'author' => $request->author,
            'published_at' => $request->published_at,
            'is_active' => $request->is_active ? 1 : 0,
        ]);

        return $this->ok([
            'redirect' => '/ebooks',
        ], 'Ebook created');
    }

    public function update(Request $request, Ebook $ebook)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',
            'level' => 'required|in:beginner,intermediate,advanced',
            'topic' => 'required|string',
            'ai_keywords' => 'nullable|array',
            'cover_image' => 'nullable|image|max:2048',
            'file' => 'nullable|file|mimes:pdf|max:20480',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        $coverImage = $ebook->cover_image;
        if ($request->hasFile('cover_image')) {
            $coverImage = asset('storage/'.$request->file('cover_image')->store('ebooks/covers', 'public'));
        }

        $filePath = $ebook->file_path;
        if ($request->hasFile('file')) {
            $filePath = asset('storage/'.$request->file('file')->store('ebooks/files', 'public'));
        }

        $ebook->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'summary' => $request->summary,
            'content' => $request->content,
            'level' => $request->level,
            'topic' => $request->topic,
            'ai_keywords' => $request->ai_keywords,
            'cover_image' => $coverImage,
            'file_path' => $filePath,
            'author' => $request->author,
            'published_at' => $request->published_at,
            'is_active' => $request->is_active ? 1 : 0,
        ]);

        return $this->ok([
            'redirect' => '/ebooks',
        ], 'Ebook updated');
    }

    public function destroy(Ebook $ebook)
    {
        $ebook->delete();

        return $this->ok(null, 'Ebook deleted');
    }
}
