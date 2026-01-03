<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreEbookRequest;
use App\Models\Ebook;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class EbookController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'throttle:60,1']);
    }

    /**
     * Consistent authorization check.
     */
    private function authorizeManage(): void
    {
        $user = auth()->user();

        if ($user && ($user->role === 'admin' || $user->role === 'editor' || (method_exists($user, 'can') && $user->can('manage-ebook')))) {
            return;
        }

        Log::warning("Unauthorized attempt to manage ebooks by User ID: " . (auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to ebook management');
    }

    /**
     * Display the index page (Blade).
     */
    public function index(): View
    {
        $this->authorizeManage();
        return view('ebooks.index', [
            'ebooks' => Ebook::orderByDesc('id')->get(),
        ]);
    }

    /**
     * API: List ebooks with pagination and search.
     */
    public function list(Request $request): JsonResponse
    {
        $this->authorizeManage();

        $query = Ebook::query()->orderByDesc('id');

        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('summary', 'like', "%{$search}%");
            });
        }

        $ebooks = $query->paginate(10);

        return ResponseHelper::ok($ebooks);
    }

    /**
     * Display creation form.
     */
    public function create(): View
    {
        $this->authorizeManage();
        return view('ebooks.form', ['ebook' => null]);
    }

    /**
     * Display editing form.
     */
    public function edit(Ebook $ebook): View
    {
        $this->authorizeManage();
        return view('ebooks.form', ['ebook' => $ebook]);
    }

    /**
     * API: Store a new ebook.
     */
    public function store(StoreEbookRequest $request): JsonResponse
    {
        $this->authorizeManage();

        try {
            $data = $request->validated();
            
            $data['uuid'] = (string) Str::uuid();
            $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
            $data['file_type'] = 'pdf'; // Fixed for now based on validation

            // File Handling
            if ($request->hasFile('cover_image')) {
                $path = $request->file('cover_image')->store('ebooks/covers', 'public');
                $data['cover_image'] = asset('storage/' . $path);
            }

            if ($request->hasFile('file')) {
                $path = $request->file('file')->store('ebooks/files', 'public');
                $data['file_path'] = asset('storage/' . $path);
            }

            $ebook = DB::transaction(function () use ($data) {
                return Ebook::create($data);
            });

            Log::info("Ebook created: ID {$ebook->id} ('{$ebook->title}') by User ID " . auth()->id());

            return ResponseHelper::ok([
                'redirect' => '/ebooks',
                'id' => $ebook->id,
            ], 'Ebook berhasil disimpan', 201);
        } catch (\Throwable $e) {
            Log::error("Failed to create ebook: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menyimpan ebook', null, 500);
        }
    }

    /**
     * API: Update an existing ebook.
     */
    public function update(StoreEbookRequest $request, Ebook $ebook): JsonResponse
    {
        $this->authorizeManage();

        try {
            $data = $request->validated();
            
            // Re-generate slug if title changed (optional, but keep for consistency)
            if ($request->filled('title')) {
                $data['slug'] = Str::slug($data['title']) . '-' . Str::random(6);
            }

            // Cover Image Handling
            if ($request->hasFile('cover_image')) {
                $this->safeDelete($ebook->cover_image);
                $path = $request->file('cover_image')->store('ebooks/covers', 'public');
                $data['cover_image'] = asset('storage/' . $path);
            }

            // File Handling
            if ($request->hasFile('file')) {
                $this->safeDelete($ebook->file_path);
                $path = $request->file('file')->store('ebooks/files', 'public');
                $data['file_path'] = asset('storage/' . $path);
            }

            DB::transaction(function () use ($ebook, $data) {
                $ebook->update($data);
            });

            Log::info("Ebook updated: ID {$ebook->id} by User ID " . auth()->id());

            return ResponseHelper::ok([
                'redirect' => '/ebooks',
            ], 'Ebook berhasil diperbarui');
        } catch (\Throwable $e) {
            Log::error("Failed to update ebook ID {$ebook->id}: " . $e->getMessage());
            return ResponseHelper::fail('Gagal memperbarui ebook', null, 500);
        }
    }

    /**
     * API: Delete an ebook.
     */
    public function destroy(Ebook $ebook): JsonResponse
    {
        $this->authorizeManage();

        try {
            $ebookId = $ebook->id;
            $ebookTitle = $ebook->title;

            $this->safeDelete($ebook->cover_image);
            $this->safeDelete($ebook->file_path);

            DB::transaction(fn () => $ebook->delete());

            Log::info("Ebook deleted: ID {$ebookId} ('{$ebookTitle}') by User ID " . auth()->id());

            return ResponseHelper::ok(null, 'Ebook berhasil dihapus');
        } catch (\Throwable $e) {
            Log::error("Failed to delete ebook ID {$ebook->id}: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menghapus ebook', null, 500);
        }
    }

    /**
     * Safe file deletion from asset URL.
     */
    private function safeDelete(?string $url): void
    {
        if (!$url) return;

        $path = str_replace(asset('storage/'), '', $url);
        if (Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }
}
