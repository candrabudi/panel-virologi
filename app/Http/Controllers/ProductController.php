<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

    public function list()
    {
        return response()->json(
            Product::orderByDesc('id')->get()
        );
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
            'name' => 'required|string|max:255',
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

        // $data['uuid'] = \Str::uuid();
        $data['slug'] = \Str::slug($data['name']);

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $i => $img) {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $img->store('products/gallery', 'public'),
                    'is_primary' => $i === 0,
                ]);
            }
        }

        return response()->json(['success' => true]);
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
            'name' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'summary' => 'nullable|string',
            'content' => 'nullable|string',

            'product_type' => 'required|string',
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

            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|array',
            'canonical_url' => 'nullable|string|max:255',
        ])->validate();

        $data['slug'] = \Str::slug($data['name']);

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                \Storage::disk('public')->delete($product->thumbnail);
            }
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        $product->update($data);

        return response()->json(['success' => true]);
    }

    public function destroy(Product $product)
    {
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        foreach ($product->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $product->delete();

        return response()->json(['success' => true]);
    }
}
