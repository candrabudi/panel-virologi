<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Http\Requests\StoreProductPageRequest;
use App\Models\ProductPageSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class ProductPageController extends Controller
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

        if ($user && ($user->role === 'admin' || (method_exists($user, 'can') && $user->can('manage-cms')))) {
            return;
        }

        Log::warning("Unauthorized attempt to manage product page settings by User ID: " . (auth()->id() ?? 'Guest'));
        abort(403, 'Unauthorized access to product page CMS management');
    }

    /**
     * Display the index page (Blade).
     */
    public function index(): View
    {
        $this->authorizeManage();
        $page = ProductPageSetting::first();
        return view('product_page.index', compact('page'));
    }

    /**
     * API: Store or Update the product page settings.
     */
    public function store(StoreProductPageRequest $request): JsonResponse
    {
        // Authorization is handled by StoreProductPageRequest
        $this->authorizeManage();

        try {
            $data = $request->validated();

            $page = ProductPageSetting::first() ?? new ProductPageSetting();
            $page->fill($data)->save();

            Log::info("Product page settings updated by User ID " . auth()->id());

            return ResponseHelper::ok(null, 'Pengaturan halaman produk berhasil disimpan');
        } catch (\Throwable $e) {
            Log::error("Failed to save product page settings: " . $e->getMessage());
            return ResponseHelper::fail('Gagal menyimpan pengaturan halaman', null, 500);
        }
    }
}
