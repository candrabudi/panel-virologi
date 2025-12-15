<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\HomepageBlogSectionController;
use App\Http\Controllers\HomepageHeroController;
use App\Http\Controllers\HomepageThreatMapSectionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin']);
Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:30,1')
    ->name('login.process');

Route::post('/login/send-otp', [AuthController::class, 'sendOtp'])
    ->middleware('throttle:20,1')
    ->name('login.sendOtp');

Route::post('/login/verify-otp', [AuthController::class, 'verifyOtp'])
    ->middleware('throttle:20,1')
    ->name('login.verify');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('welcome');
    });

    Route::get('/website', [WebsiteController::class, 'index'])->name('website.index');

    Route::post('/website/general', [WebsiteController::class, 'saveGeneral'])->name('website.general');
    Route::post('/website/contact', [WebsiteController::class, 'saveContact'])->name('website.contact');
    Route::post('/website/branding', [WebsiteController::class, 'saveBranding'])->name('website.branding');
    Route::post('/website/seo', [WebsiteController::class, 'saveSeo'])->name('website.seo');

    Route::get('/homepage-hero', [HomepageHeroController::class, 'index'])
       ->name('homepage.hero.index');

    Route::post('/homepage-hero', [HomepageHeroController::class, 'store'])
        ->name('homepage.hero.store');

    Route::get('/homepage-blog-section', [HomepageBlogSectionController::class, 'index'])
    ->name('homepage.blog.section.index');

    Route::post('/homepage-blog-section', [HomepageBlogSectionController::class, 'store'])
        ->name('homepage.blog.section.store');

    Route::get('/homepage-threat-map', [HomepageThreatMapSectionController::class, 'index'])
        ->name('homepage.threat-map.index');

    Route::post('/homepage-threat-map', [HomepageThreatMapSectionController::class, 'store'])
        ->name('homepage.threat-map.store');

    Route::get('/footer', [FooterController::class, 'index'])
    ->name('footer.index');

    Route::post('/footer/setting', [FooterController::class, 'saveSetting'])
        ->name('footer.setting.save');

    Route::post('/footer/quick-link', [FooterController::class, 'saveQuickLink'])
        ->name('footer.quick-link.save');

    Route::delete('/footer/quick-link/{id}', [FooterController::class, 'deleteQuickLink'])
        ->name('footer.quick-link.delete');

    Route::post('/footer/contact', [FooterController::class, 'saveContact'])
        ->name('footer.contact.save');

    Route::delete('/footer/contact/{id}', [FooterController::class, 'deleteContact'])
        ->name('footer.contact.delete');

    Route::get('/cms/about-us', [AboutUsController::class, 'index']);
    Route::post('/cms/about-us', [AboutUsController::class, 'store'])
        ->name('about-us.store');

    Route::get('/product-page', [ProductPageController::class, 'index']);
    Route::post('/product-page', [ProductPageController::class, 'store'])
        ->name('product.page.store');

    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/create', [ProductController::class, 'create']);
    Route::get('/products/{product}/edit', [ProductController::class, 'edit']);

    Route::prefix('api/products')->group(function () {
        Route::get('/', [ProductController::class, 'list']);
        Route::post('/', [ProductController::class, 'store']);
        Route::post('/{product}', [ProductController::class, 'update']);
        Route::delete('/{product}', [ProductController::class, 'destroy']);
    });
});
