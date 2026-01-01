<?php

namespace App\Http\Controllers;

use App\Models\Ebook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%'.$request->q.'%')
                  ->orWhere('summary', 'like', '%'.$request->q.'%');
            });
        }

        $ebooks = $query->paginate(10);

        return $this->ok($ebooks);
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
            'topic' => 'required|in:general,network_security,application_security,cloud_security,soc,pentest,malware,incident_response,governance',

            'ai_keywords' => 'nullable|array',
            'ai_keywords.*' => 'string|max:100',

            'cover_image' => 'nullable|image|max:2048',
            'file' => 'required|file|mimes:pdf|max:20480',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        $aiKeywords = null;
        if ($request->filled('ai_keywords')) {
            $aiKeywords = collect($request->ai_keywords)
                ->map(fn ($k) => trim($k))
                ->filter()
                ->unique()
                ->values()
                ->toArray();
        }

        $coverImage = null;
        if ($request->hasFile('cover_image')) {
            $coverImage = $request->file('cover_image')->store('ebooks/covers', 'public');
        }

        $filePath = $request->file('file')->store('ebooks/files', 'public');

        Ebook::create([
            'uuid' => Str::uuid(),
            'title' => $request->title,
            'slug' => Str::slug($request->title).'-'.Str::random(6),
            'summary' => $request->summary,
            'content' => $request->content,
            'level' => $request->level,
            'topic' => $request->topic,
            'ai_keywords' => $aiKeywords,

            'cover_image' => $coverImage ? asset('storage/'.$coverImage) : null,
            'file_path' => asset('storage/'.$filePath),
            'file_type' => 'pdf',
            'author' => $request->author,
            'published_at' => $request->published_at,
            'is_active' => $request->boolean('is_active', true),
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
            'topic' => 'required|in:general,network_security,application_security,cloud_security,soc,pentest,malware,incident_response,governance',
            // 'ai_keywords' => 'nullable|array',
            'ai_keywords.*' => 'string|max:100',
            'cover_image' => 'nullable|image|max:2048',
            'file' => 'nullable|file|mimes:pdf|max:20480',
            'author' => 'nullable|string|max:255',
            'published_at' => 'nullable|date',
            'is_active' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return $this->fail('Validation error', $validator->errors(), 422);
        }

        $coverImage = $ebook->cover_image;
        if ($request->hasFile('cover_image')) {
            if ($ebook->cover_image) {
                $oldPath = str_replace(asset('storage/'), '', $ebook->cover_image);
                Storage::disk('public')->delete($oldPath);
            }
            $coverImage = $request->file('cover_image')->store('ebooks/covers', 'public');
            $coverImage = asset('storage/'.$coverImage);
        }

        $filePath = $ebook->file_path;
        if ($request->hasFile('file')) {
            if ($ebook->file_path) {
                $oldFile = str_replace(asset('storage/'), '', $ebook->file_path);
                Storage::disk('public')->delete($oldFile);
            }
            $filePath = $request->file('file')->store('ebooks/files', 'public');
            $filePath = asset('storage/'.$filePath);
        }

        $ebook->update([
            'title' => $request->title,
            'slug' => Str::slug($request->title).'-'.Str::random(6),
            'summary' => $request->summary,
            'content' => $request->content,
            'level' => $request->level,
            'topic' => $request->topic,
            'ai_keywords' => $request->ai_keywords, // <<< array langsung
            'cover_image' => $coverImage,
            'file_path' => $filePath,
            'author' => $request->author,
            'published_at' => $request->published_at,
            'is_active' => $request->boolean('is_active', true),
        ]);

        return $this->ok([
            'redirect' => '/ebooks',
        ], 'Ebook updated');
    }

    public function destroy(Ebook $ebook)
    {
        if ($ebook->cover_image) {
            $oldCover = str_replace(asset('storage/'), '', $ebook->cover_image);
            Storage::disk('public')->delete($oldCover);
        }
        if ($ebook->file_path) {
            $oldFile = str_replace(asset('storage/'), '', $ebook->file_path);
            Storage::disk('public')->delete($oldFile);
        }

        $ebook->delete();

        return $this->ok(null, 'Ebook deleted');
    }
}
