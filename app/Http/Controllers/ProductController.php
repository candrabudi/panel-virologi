<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
            Product::latest()->get()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'images.*' => 'nullable|image|max:4096',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);

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
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'thumbnail' => 'nullable|image|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|string',
        ]);

        $data['slug'] = Str::slug($data['name']);

        if ($request->hasFile('thumbnail')) {
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
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
