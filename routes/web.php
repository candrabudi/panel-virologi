<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AiContextController;
use App\Http\Controllers\AiPromptBindingController;
use App\Http\Controllers\AiPromptTemplateController;
use App\Http\Controllers\AiRuleController;
use App\Http\Controllers\AiSettingController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleTagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\HomepageBlogSectionController;
use App\Http\Controllers\HomepageHeroController;
use App\Http\Controllers\HomepageThreatMapSectionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']);

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:30,1')
    ->name('login.process');

Route::post('/login/send-otp', [AuthController::class, 'sendOtp'])
    ->middleware('throttle:20,1');

Route::post('/login/verify-otp', [AuthController::class, 'verifyOtp'])
    ->middleware('throttle:20,1');

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware(['auth', 'throttle:300,1'])->group(function () {
    Route::get('/dashboard', fn () => view('welcome'))->name('dashboard');

    Route::prefix('website')->group(function () {
        Route::get('/', [WebsiteController::class, 'index']);
        Route::post('/general', [WebsiteController::class, 'saveGeneral']);
        Route::post('/contact', [WebsiteController::class, 'saveContact']);
        Route::post('/branding', [WebsiteController::class, 'saveBranding']);
    });

    Route::get('/homepage-hero', [HomepageHeroController::class, 'index']);
    Route::post('/homepage-hero', [HomepageHeroController::class, 'store']);

    Route::get('/homepage-blog-section', [HomepageBlogSectionController::class, 'index']);
    Route::post('/homepage-blog-section', [HomepageBlogSectionController::class, 'store']);

    Route::get('/homepage-threat-map', [HomepageThreatMapSectionController::class, 'index']);
    Route::post('/homepage-threat-map', [HomepageThreatMapSectionController::class, 'store']);

    Route::get('/footer', [FooterController::class, 'index']);
    Route::post('/footer/setting', [FooterController::class, 'saveSetting']);
    Route::post('/footer/quick-link', [FooterController::class, 'saveQuickLink']);
    Route::delete('/footer/quick-link/{id}', [FooterController::class, 'deleteQuickLink']);
    Route::post('/footer/contact', [FooterController::class, 'saveContact']);
    Route::delete('/footer/contact/{id}', [FooterController::class, 'deleteContact']);

    Route::get('/about-us', [AboutUsController::class, 'index']);
    Route::post('/about-us', [AboutUsController::class, 'store']);

    Route::get('/product-page', [ProductPageController::class, 'index']);
    Route::post('/product-page', [ProductPageController::class, 'store']);

    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index']);
        Route::get('/create', [ProductController::class, 'create']);
        Route::get('/{product}/edit', [ProductController::class, 'edit']);
    });

    Route::prefix('api/products')
        ->middleware('throttle:120,1')
        ->group(function () {
            Route::get('/', [ProductController::class, 'list']);
            Route::post('/', [ProductController::class, 'store']);
            Route::post('/{product}', [ProductController::class, 'update']);
            Route::delete('/{product}', [ProductController::class, 'destroy']);
        });

    Route::prefix('ai')
        ->middleware('throttle:120,1')
        ->group(function () {
            Route::get('/settings', [AiSettingController::class, 'index']);
            Route::post('/settings', [AiSettingController::class, 'store']);

            Route::get('/contexts', [AiContextController::class, 'index']);
            Route::post('/contexts', [AiContextController::class, 'store']);
            Route::put('/contexts/{id}', [AiContextController::class, 'update']);

            Route::get('/prompts', [AiPromptTemplateController::class, 'index']);
            Route::post('/prompts', [AiPromptTemplateController::class, 'store']);
            Route::put('/prompts/{id}', [AiPromptTemplateController::class, 'update']);

            Route::get('/bindings', [AiPromptBindingController::class, 'index']);
            Route::post('/bindings', [AiPromptBindingController::class, 'store']);
            Route::delete('/bindings/{id}', [AiPromptBindingController::class, 'destroy']);

            Route::get('/rules', [AiRuleController::class, 'index']);
            Route::post('/rules', [AiRuleController::class, 'store']);
            Route::put('/rules/{id}', [AiRuleController::class, 'update']);
            Route::delete('/rules/{id}', [AiRuleController::class, 'destroy']);
        });

    Route::prefix('article-categories')
        ->middleware('throttle:120,1')
        ->group(function () {
            Route::get('/', [ArticleCategoryController::class, 'index']);
            Route::post('/', [ArticleCategoryController::class, 'store']);
            Route::put('/{id}', [ArticleCategoryController::class, 'update']);
            Route::delete('/{id}', [ArticleCategoryController::class, 'destroy']);
        });

    Route::prefix('article-tags')
        ->middleware('throttle:120,1')
        ->group(function () {
            Route::get('/', [ArticleTagController::class, 'index']);
            Route::post('/', [ArticleTagController::class, 'store']);
            Route::put('/{id}', [ArticleTagController::class, 'update']);
            Route::delete('/{id}', [ArticleTagController::class, 'destroy']);
        });

    Route::prefix('articles')
        ->middleware('throttle:120,1')
        ->group(function () {
            Route::get('/', [ArticleController::class, 'index']);
            Route::get('/create', [ArticleController::class, 'create']);
            Route::get('/{article}/edit', [ArticleController::class, 'edit']);

            Route::post('/', [ArticleController::class, 'store']);
            Route::post('/{article}', [ArticleController::class, 'update']);
            Route::post('/{article}/toggle-publish', [ArticleController::class, 'togglePublish']);
            Route::delete('/{article}', [ArticleController::class, 'destroy']);
        });
});
