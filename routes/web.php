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
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\HomepageBlogSectionController;
use App\Http\Controllers\HomepageHeroController;
use App\Http\Controllers\HomepageThreatMapSectionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::get('/login', [AuthController::class, 'showLogin']);

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

Route::middleware(['auth', 'throttle:300,1'])->group(function () {
    Route::get('/dashboard', fn () => view('welcome'))->name('dashboard');
    Route::get('/dashboard/summary', [DashboardController::class, 'summary'])
        ->middleware('auth');

    Route::get('/dashboard/ai-traffic-daily', [DashboardController::class, 'aiTrafficDaily'])
    ->middleware('auth');

    Route::prefix('website')->group(function () {
        Route::get('/', [WebsiteController::class, 'index']);
        Route::post('/general', [WebsiteController::class, 'saveGeneral'])->name('website.general');
        Route::post('/contact', [WebsiteController::class, 'saveContact'])->name('website.contact');
        Route::post('/branding', [WebsiteController::class, 'saveBranding'])->name('website.branding');
    });

    Route::get('/homepage-hero', [HomepageHeroController::class, 'index']);

    Route::get('/homepage-hero/show', [HomepageHeroController::class, 'show']);
    Route::post('/homepage-hero', [HomepageHeroController::class, 'store']);

    Route::get('/homepage-blog-section', [HomepageBlogSectionController::class, 'index']);

    Route::get('/homepage-blog-section/show', [HomepageBlogSectionController::class, 'show']);
    Route::post('/homepage-blog-section', [HomepageBlogSectionController::class, 'store']);

    Route::get('/homepage-threat-map', [HomepageThreatMapSectionController::class, 'index']);

    Route::get('/homepage-threat-map/show', [HomepageThreatMapSectionController::class, 'show']);
    Route::post('/homepage-threat-map', [HomepageThreatMapSectionController::class, 'store']);
    Route::prefix('footer')->group(function () {
        Route::get('/', [FooterController::class, 'index']);
        // SETTINGS
        Route::get('/setting', [FooterController::class, 'setting']);
        Route::post('/setting', [FooterController::class, 'saveSetting']);

        // QUICK LINKS
        Route::get('/quick-links', [FooterController::class, 'listQuickLinks']);
        Route::post('/quick-links', [FooterController::class, 'storeQuickLink']);
        Route::delete('/quick-links/{id}', [FooterController::class, 'deleteQuickLink']);

        // CONTACTS
        Route::get('/contacts', [FooterController::class, 'listContacts']);
        Route::post('/contacts', [FooterController::class, 'storeContact']);
        Route::delete('/contacts/{id}', [FooterController::class, 'deleteContact']);
    });
    Route::get('/about-us', [AboutUsController::class, 'index']);

    Route::get('/api/about-us', [AboutUsController::class, 'apiShow']);
    Route::post('/api/about-us', [AboutUsController::class, 'store']);

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
        ->name('ai.')
        ->middleware('throttle:120,1')
        ->group(function () {
            Route::get('/settings', [AiSettingController::class, 'index']);
            Route::post('/settings', [AiSettingController::class, 'store']);

            Route::get('/contexts', [AiContextController::class, 'index']);
            Route::get('/contexts/list', [AiContextController::class, 'list']);
            Route::post('/contexts', [AiContextController::class, 'store']);
            Route::put('/contexts/{id}', [AiContextController::class, 'update']);

            Route::get('/prompts', [AiPromptTemplateController::class, 'index']);
            Route::get('/prompts/list', [AiPromptTemplateController::class, 'list']);
            Route::post('/prompts', [AiPromptTemplateController::class, 'store']);
            Route::patch('/prompts/{id}', [AiPromptTemplateController::class, 'update']);
            Route::patch('/prompts/{id}/toggle', [AiPromptTemplateController::class, 'toggle']);
            Route::delete('/prompts/{id}', [AiPromptTemplateController::class, 'destroy']);

            Route::get('/bindings', [AiPromptBindingController::class, 'index']);
            Route::get('/bindings/list', [AiPromptBindingController::class, 'list']);
            Route::post('/bindings', [AiPromptBindingController::class, 'store']);
            Route::patch('/bindings/{id}', [AiPromptBindingController::class, 'update']);

            Route::get('/rules', [AiRuleController::class, 'index']);
            Route::get('/rules/list', [AiRuleController::class, 'list']);
            Route::post('/rules', [AiRuleController::class, 'store']);
            Route::patch('/rules/{id}', [AiRuleController::class, 'update']);
            Route::delete('/rules/{id}', [AiRuleController::class, 'destroy']);
        });

    Route::prefix('articles/categories')->middleware(['auth'])->group(function () {
        Route::get('/', [ArticleCategoryController::class, 'index']);
        Route::get('/list', [ArticleCategoryController::class, 'list']);
        Route::post('/', [ArticleCategoryController::class, 'store']);
        Route::put('/{id}', [ArticleCategoryController::class, 'update']);
        Route::delete('/{id}', [ArticleCategoryController::class, 'destroy']);
    });
    Route::prefix('articles/tags')
            ->middleware('throttle:120,1')
            ->group(function () {
                Route::get('/', [ArticleTagController::class, 'index']);
                Route::get('/list', [ArticleTagController::class, 'list']);
                Route::post('/', [ArticleTagController::class, 'store']);
                Route::put('/{id}', [ArticleTagController::class, 'update']);
                Route::delete('/{id}', [ArticleTagController::class, 'destroy']);
            });

    Route::prefix('articles')->group(function () {
        Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
        Route::get('/create', [ArticleController::class, 'create'])->name('articles.create');
        Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');

        Route::get('/list', [ArticleController::class, 'list'])->name('articles.list');

        Route::post('/', [ArticleController::class, 'store'])->name('articles.store');
        Route::put('/{article}', [ArticleController::class, 'update'])->name('articles.update');
        Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('articles.destroy');
    });

    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserManagementController::class, 'index'])
            ->name('index');

        Route::get('/list', [UserManagementController::class, 'list'])
            ->name('list');

        Route::post('/', [UserManagementController::class, 'store'])
            ->name('store');

        Route::put('/{id}', [UserManagementController::class, 'update'])
            ->name('update');

        Route::delete('/{id}', [UserManagementController::class, 'destroy'])
            ->name('destroy');
    });
});
