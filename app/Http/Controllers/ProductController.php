<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return view('products.index');
    }

    public function create()
    {
        return view('products.create');
    }

    public function edit(Product $product)
    {
        $product->load('images');

        return view('products.edit', compact('product'));
    }

    private function ok($data = null, string $message = 'OK', int $code = 200)
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    public function list(Request $request)
    {
        $query = Product::query()->orderByDesc('id');

        // Search by name (bisa diganti field lain sesuai kebutuhan)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        // Pagination
        $perPage = $request->get('per_page', 10);
        $products = $query->paginate($perPage);

        return $this->ok($products);
    }

    public function store(Request $request)
    {
        $payload = $request->all();

        foreach (['ai_keywords', 'ai_intents', 'ai_use_cases', 'seo_keywords'] as $field) {
            if (isset($payload[$field]) && is_string($payload[$field])) {
                $payload[$field] = json_decode($payload[$field], true) ?: [];
            }
        }

        $data = validator($payload, [
            'product_name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',

            'product_type' => 'required|in:digital,hardware,service,bundle',
            'ai_domain' => 'nullable|string',
            'ai_level' => 'nullable|string',

            'ai_keywords' => 'nullable|array',
            'ai_intents' => 'nullable|array',
            'ai_use_cases' => 'nullable|array',

            'ai_priority' => 'nullable|integer|min:0',
            'is_ai_visible' => 'boolean',
            'is_ai_recommended' => 'boolean',

            'cta_label' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'cta_type' => 'nullable|string',

            'thumbnail' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:4096',

            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|array',
            'canonical_url' => 'nullable|string|max:255',
        ])->validate();

        $data['slug'] = \Str::slug($data['product_name']);
        $data['name'] = $data['product_name'];

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = asset('storage/'.$request->file('thumbnail')->store('products', 'public'));
        }

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => asset('storage/'.$img->store('products/gallery', 'public')),
                    'is_primary' => $i === 0,
                ]);
            }
        }

        // Response disesuaikan untuk showToast()
        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil disimpan',
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $payload = $request->all();

        foreach (['ai_keywords', 'ai_intents', 'ai_use_cases', 'seo_keywords'] as $field) {
            if (isset($payload[$field]) && is_string($payload[$field])) {
                $payload[$field] = json_decode($payload[$field], true) ?: [];
            }
        }

        $data = validator($payload, [
            'product_name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',

            'product_type' => 'required|in:digital,hardware,service,bundle',
            'ai_domain' => 'nullable|string',
            'ai_level' => 'nullable|string',

            'ai_keywords' => 'nullable|array',
            'ai_intents' => 'nullable|array',
            'ai_use_cases' => 'nullable|array',

            'ai_priority' => 'nullable|integer|min:0',
            'is_ai_visible' => 'boolean',
            'is_ai_recommended' => 'boolean',

            'cta_label' => 'nullable|string|max:255',
            'cta_url' => 'nullable|string|max:255',
            'cta_type' => 'nullable|string',

            'thumbnail' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:4096',

            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|array',
            'canonical_url' => 'nullable|string|max:255',
        ])->validate();

        $data['slug'] = \Str::slug($data['product_name']);
        $data['name'] = $data['product_name'];

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                \Storage::disk('public')->delete(str_replace(asset('storage/'), '', $product->thumbnail));
            }
            $data['thumbnail'] = asset('storage/'.$request->file('thumbnail')->store('products', 'public'));
        }

        $product->update($data);

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil diperbarui',
        ]);
    }

    public function destroy(Product $product)
    {
        if ($product->thumbnail) {
            \Storage::disk('public')->delete(str_replace(asset('storage/'), '', $product->thumbnail));
        }

        foreach ($product->images as $img) {
            \Storage::disk('public')->delete(str_replace(asset('storage/'), '', $img->image_path));
            $img->delete();
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil dihapus',
            'redirect' => route('products.index'),
        ]);
    }
}
