<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreProductRequest;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
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

        if ($user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-product')))) {
            return;
        }

        Log::warning('Unauthorized attempt to manage products by User ID: '.(auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to product management');
    }

    /**
     * Display the index page (Blade).
     */
    public function index(): View
    {
        $this->authorizeManage();

        return view('products.index');
    }

    /**
     * Display creation form.
     */
    public function create(): View
    {
        $this->authorizeManage();

        return view('products.create');
    }

    /**
     * Display editing form.
     */
    public function edit(Product $product): View
    {
        $this->authorizeManage();
        $product->load('images');

        return view('products.edit', compact('product'));
    }

    /**
     * API: List products with pagination and search.
     */
    public function list(Request $request): JsonResponse
    {
        $this->authorizeManage();

        $query = Product::query()->orderByDesc('id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('subtitle', 'like', "%{$search}%");
        }

        $perPage = $request->get('per_page', 10);

        return ResponseHelper::ok($query->paginate($perPage));
    }

    /**
     * API: Store a new product.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        // Authorization is handled by StoreProductRequest
        $this->authorizeManage();

        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['product_name']);
            $data['name'] = $data['product_name'];

            $product = DB::transaction(function () use ($request, $data) {
                if ($request->hasFile('thumbnail')) {
                    $path = $request->file('thumbnail')->store('products', 'public');
                    $data['thumbnail'] = asset('storage/'.$path);
                }

                $product = Product::create($data);

                if ($request->hasFile('images')) {
                    foreach ($request->file('images') as $i => $img) {
                        $path = $img->store('products/gallery', 'public');
                        ProductImage::create([
                            'product_id' => $product->id,
                            'image_path' => asset('storage/'.$path),
                            'is_primary' => $i === 0,
                        ]);
                    }
                }

                return $product;
            });

            Log::info("Product created: ID {$product->id} ('{$product->name}') by User ID ".auth()->id());

            return ResponseHelper::ok([
                'id' => $product->id,
            ], 'Produk berhasil disimpan', 201);
        } catch (\Throwable $e) {
            Log::error('Failed to create product: '.$e->getMessage());

            return ResponseHelper::fail('Gagal membuat produk', null, 500);
        }
    }

    /**
     * API: Update an existing product.
     */
    public function update(StoreProductRequest $request, Product $product): JsonResponse
    {
        // Authorization is handled by StoreProductRequest
        $this->authorizeManage();

        try {
            $data = $request->validated();
            $data['slug'] = Str::slug($data['product_name']);
            $data['name'] = $data['product_name'];

            DB::transaction(function () use ($request, $product, $data) {
                if ($request->hasFile('thumbnail')) {
                    // Delete old thumbnail if exists
                    if ($product->thumbnail) {
                        $oldPath = str_replace(asset('storage/'), '', $product->thumbnail);
                        Storage::disk('public')->delete($oldPath);
                    }
                    $path = $request->file('thumbnail')->store('products', 'public');
                    $data['thumbnail'] = asset('storage/'.$path);
                }

                $product->update($data);

                // Note: Gallery update logic usually handled separately or via another endpoint
                // if needed to add images during update, but here we just follow the controller update scope.
            });

            Log::info("Product updated: ID {$product->id} by User ID ".auth()->id());

            return ResponseHelper::ok(null, 'Produk berhasil diperbarui');
        } catch (\Throwable $e) {
            Log::error("Failed to update product ID {$product->id}: ".$e->getMessage());

            return ResponseHelper::fail('Gagal memperbarui produk '.$e->getMessage(), null, 500);
        }
    }

    /**
     * API: Delete a product.
     */
    public function destroy(Product $product): JsonResponse
    {
        $this->authorizeManage();

        try {
            $productId = $product->id;
            $productName = $product->name;

            DB::transaction(function () use ($product) {
                // Delete thumbnail
                if ($product->thumbnail) {
                    $oldPath = str_replace(asset('storage/'), '', $product->thumbnail);
                    Storage::disk('public')->delete($oldPath);
                }

                // Delete gallery images
                foreach ($product->images as $img) {
                    $oldPath = str_replace(asset('storage/'), '', $img->image_path);
                    Storage::disk('public')->delete($oldPath);
                    $img->delete();
                }

                $product->delete();
            });

            Log::info("Product deleted: ID {$productId} ('{$productName}') by User ID ".auth()->id());

            return ResponseHelper::ok([
                'redirect' => route('products.index'),
            ], 'Produk berhasil dihapus');
        } catch (\Throwable $e) {
            Log::error("Failed to delete product ID {$product->id}: ".$e->getMessage());

            return ResponseHelper::fail('Gagal menghapus produk', null, 500);
        }
    }
}
