<?php

namespace App\Http\Controllers;

use App\Models\ProductPageSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductPageController extends Controller
{
    public function index()
    {
        $page = ProductPageSetting::first();

        return view('product_page.index', compact('page'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page_title' => 'required|string|max:255',
            'page_subtitle' => 'nullable|string|max:255',
            'cta_text' => 'nullable|string|max:100',
            'cta_url' => 'nullable|string|max:255',

            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string|max:300',
            'seo_keywords' => 'nullable|string|max:500',

            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:300',
            'canonical_url' => 'nullable|string|max:255',

            'is_active' => 'nullable|boolean',
        ], [
            'page_title.required' => 'Judul halaman wajib diisi',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $page = ProductPageSetting::first() ?? new ProductPageSetting();
        $data = $validator->validated();
        $data['is_active'] = $request->boolean('is_active', false);

        $page->fill($data)->save();

        return response()->json([
            'status' => true,
            'message' => 'Pengaturan halaman produk berhasil disimpan',
        ]);
    }
}
