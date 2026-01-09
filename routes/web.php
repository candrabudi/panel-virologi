<?php

use App\Http\Controllers\AboutSettingController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\AiChatController;
use App\Http\Controllers\AiChatSessionController;
use App\Http\Controllers\AiSettingController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleTagController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CyberSecurityServiceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EbookController;
use App\Http\Controllers\FooterController;
use App\Http\Controllers\ContactSettingController;
use App\Http\Controllers\FooterSettingController;
use App\Http\Controllers\HomepageBlogSectionController;
use App\Http\Controllers\HomepageHeroController;
use App\Http\Controllers\HomepageThreatMapSectionController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\WebsiteSettingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductPageController;
use App\Http\Controllers\HomeSectionController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\WebsiteController;
use App\Http\Controllers\LeakCheckController;
use App\Http\Controllers\LeakDataRequestController;
use App\Http\Controllers\SystemTrafficLogController;
use Illuminate\Support\Facades\Route;

/**
 * PUBLIC & AUTHENTICATION ROUTES
 */
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

/**
 * AUTHENTICATED ROUTES (Shared)
 */
Route::middleware(['auth', 'throttle:300,1'])->group(function () {
    Route::get('/dashboard', fn () => view('dashboard.index'))->name('dashboard');

    // AI Chat for Users (Self Ownership Check in Controller)
    Route::prefix('ai/chat')->group(function () {
        Route::get('/', [AiChatSessionController::class, 'index'])->name('ai_chat.index');
        Route::get('/sessions/{session}', [AiChatSessionController::class, 'show'])->name('ai_chat.show');
        Route::get('/list', [AiChatSessionController::class, 'list'])->name('ai_chat.list');
        Route::get('/detail/{session}', [AiChatSessionController::class, 'detail'])->name('ai_chat.detail');
        Route::delete('/sessions/{session}', [AiChatSessionController::class, 'destroy'])->name('ai_chat.destroy');
        Route::post('/send', [AiChatController::class, 'store'])->name('ai_chat.send');
    });

    /**
     * CONTENT MANAGEMENT (Admin & Editor)
     */
    Route::middleware(['role:admin,editor'])->group(function () {
        Route::get('/dashboard/summary', [DashboardController::class, 'aiAnalyticsSummary']);
        Route::get('/dashboard/ai-traffic-daily', [DashboardController::class, 'aiTrafficDaily']);

        // Website Sections
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

        Route::prefix('home-sections')->group(function () {
            Route::get('/', [HomeSectionController::class, 'index'])->name('home_sections.index');
            Route::get('/list', [HomeSectionController::class, 'list'])->name('home_sections.list');
            Route::get('/{section}/edit', [HomeSectionController::class, 'edit'])->name('home_sections.edit');
            Route::put('/{section}', [HomeSectionController::class, 'update'])->name('home_sections.update');
        });

        Route::prefix('pages')->group(function () {
            Route::get('/', [PageController::class, 'index'])->name('pages.index');
            Route::get('/list', [PageController::class, 'list'])->name('pages.list');
            Route::get('/{page}/edit', [PageController::class, 'edit'])->name('pages.edit');
            Route::put('/{page}', [PageController::class, 'update'])->name('pages.update');
        });

        Route::prefix('website-settings')->group(function () {
            Route::get('/', [WebsiteSettingController::class, 'index'])->name('website_settings.index');
            Route::put('/', [WebsiteSettingController::class, 'update'])->name('website_settings.update');
        });

        Route::prefix('footer-settings')->group(function () {
            Route::get('/', [FooterSettingController::class, 'index'])->name('footer_settings.index');
            Route::put('/', [FooterSettingController::class, 'update'])->name('footer_settings.update');
        });

        Route::prefix('contact-settings')->group(function () {
            Route::get('/', [ContactSettingController::class, 'edit'])->name('contact_settings.edit');
            Route::put('/', [ContactSettingController::class, 'update'])->name('contact_settings.update');
        });

        Route::prefix('about-settings')->group(function () {
            Route::get('/', [AboutSettingController::class, 'edit'])->name('about_settings.edit');
            Route::put('/', [AboutSettingController::class, 'update'])->name('about_settings.update');
        });

        Route::get('/about-us', [AboutUsController::class, 'index']);
        Route::get('/api/about-us', [AboutUsController::class, 'apiShow']);
        Route::post('/api/about-us', [AboutUsController::class, 'store']);

        // Leak Requests (Work items)
        Route::prefix('leak-request')->group(function () {
            Route::get('/', [LeakDataRequestController::class, 'index'])->name('leak_request.index');
            Route::get('/create', [LeakDataRequestController::class, 'create'])->name('leak_request.create');
            Route::post('/', [LeakDataRequestController::class, 'store'])->name('leak_request.store');
            Route::get('/{id}', [LeakDataRequestController::class, 'show'])->name('leak_request.show');
            Route::post('/{id}/update-status', [LeakDataRequestController::class, 'updateStatus'])->name('leak_request.update_status');
        });

        // Leak Check Tools
        Route::prefix('leak-check')->group(function () {
            Route::get('/', [LeakCheckController::class, 'index'])->name('leak_check.index');
            Route::get('/logs', [LeakCheckController::class, 'logs'])->name('leak_check.logs');
            Route::get('/logs/{log}', [LeakCheckController::class, 'showLog'])->name('leak_check.show_log');
            Route::post('/search', [LeakCheckController::class, 'search'])->name('leak_check.search');
            Route::get('/export', [LeakCheckController::class, 'exportCsv'])->name('leak_check.export');
            Route::get('/print', [LeakCheckController::class, 'printLogs'])->name('leak_check.print');
        });

        // Content: Articles, Ebooks, Products
        Route::prefix('articles')->group(function () {
            // Categories
            Route::prefix('categories')->group(function () {
                Route::get('/', [ArticleCategoryController::class, 'index'])->name('articles.categories.index');
                Route::get('/list', [ArticleCategoryController::class, 'list'])->name('articles.categories.list');
                Route::post('/', [ArticleCategoryController::class, 'store'])->name('articles.categories.store');
                Route::put('/{category}', [ArticleCategoryController::class, 'update'])->name('articles.categories.update');
                Route::delete('/{category}', [ArticleCategoryController::class, 'destroy'])->name('articles.categories.destroy');
            });

            // Tags
            Route::prefix('tags')->group(function () {
                Route::get('/', [ArticleTagController::class, 'index'])->name('articles.tags.index');
                Route::get('/list', [ArticleTagController::class, 'list'])->name('articles.tags.list');
                Route::post('/', [ArticleTagController::class, 'store'])->name('articles.tags.store');
                Route::put('/{tag}', [ArticleTagController::class, 'update'])->name('articles.tags.update');
                Route::delete('/{tag}', [ArticleTagController::class, 'destroy'])->name('articles.tags.destroy');
            });

            Route::get('/', [ArticleController::class, 'index'])->name('articles.index');
            Route::get('/list', [ArticleController::class, 'list'])->name('articles.list');
            Route::get('/create', [ArticleController::class, 'create'])->name('articles.create');
            Route::post('/', [ArticleController::class, 'store'])->name('articles.store');
            Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('articles.edit');
            Route::put('/{article}', [ArticleController::class, 'update'])->name('articles.update');
            Route::delete('/{article}/delete', [ArticleController::class, 'destroy'])->name('articles.destroy');
        });

        Route::prefix('products')->group(function () {
            Route::get('/', [ProductController::class, 'index'])->name('products.index');
            Route::get('/list', [ProductController::class, 'list'])->name('products.list');
            Route::post('/store', [ProductController::class, 'store'])->name('products.store');
            Route::put('/{product}/update', [ProductController::class, 'update'])->name('products.update');
            Route::delete('/{product}/delete', [ProductController::class, 'destroy'])->name('products.destroy');
        });

        Route::prefix('ebooks')->name('ebooks.')->group(function () {
            Route::get('/', [EbookController::class, 'index'])->name('index');
            Route::get('/list', [EbookController::class, 'list'])->name('list');
            Route::get('/create', [EbookController::class, 'create'])->name('create');
            Route::post('/', [EbookController::class, 'store'])->name('store');
            Route::get('/{ebook}/edit', [EbookController::class, 'edit'])->name('edit');
            Route::put('/{ebook}', [EbookController::class, 'update'])->name('update');
            Route::delete('/{ebook}/delete', [EbookController::class, 'destroy'])->name('destroy');
        });
    });

    /**
     * SYSTEM CONFIGURATION & TECHNICAL (Admin Only)
     */
    Route::middleware(['role:admin'])->group(function () {
        // User & Traffic Management
        Route::prefix('users')->name('users.')->group(function () {
            Route::get('/', [UserManagementController::class, 'index'])->name('index');
            Route::get('/list', [UserManagementController::class, 'list'])->name('list');
            Route::post('/', [UserManagementController::class, 'store'])->name('store');
            Route::put('/{user}/update', [UserManagementController::class, 'update'])->name('update');
            Route::delete('/{user}', [UserManagementController::class, 'destroy'])->name('destroy');
        });

        Route::prefix('traffic-logs')->group(function () {
            Route::get('/', [SystemTrafficLogController::class, 'index'])->name('traffic_logs.index');
            Route::get('/{log}', [SystemTrafficLogController::class, 'show'])->name('traffic_logs.show');
        });

        // Global Configuration (High Risk)
        Route::prefix('leak-check')->group(function () {
            Route::post('/settings', [LeakCheckController::class, 'updateSettings'])->name('leak_check.update_settings');
            Route::get('/logs/{log}/json', [LeakCheckController::class, 'downloadJson'])->name('leak_check.download_json');
        });

        Route::prefix('ai')->name('ai.')->middleware('throttle:60,1')->group(function () {
            Route::get('/settings', [AiSettingController::class, 'index']);
            Route::post('/settings', [AiSettingController::class, 'store']);
            Route::resource('performance', \App\Http\Controllers\AiPerformanceMetricController::class);
            Route::resource('knowledge', \App\Http\Controllers\AiKnowledgeBaseController::class);
        });
    });
});
