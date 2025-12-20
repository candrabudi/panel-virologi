<div class="sidenav-menu">
    <!-- Brand Logo -->
    <a href="index.html" class="logo">
        <span class="logo logo-light">
            <span class="logo-lg"><img src="{{ $setting ? $setting->logo_rectangle : '' }}" alt="logo" /></span>
            <span class="logo-sm"><img src="{{ $setting ? $setting->logo_rectangle : '' }}" alt="small logo" /></span>
        </span>

        <span class="logo logo-dark">
            <span class="logo-lg"><img src="{{ $setting ? $setting->logo_rectangle : '' }}" alt="dark logo" /></span>
            <span class="logo-sm"><img src="{{ $setting ? $setting->logo_rectangle : '' }}" alt="small logo" /></span>
        </span>
    </a>
    <button class="button-on-hover">
        <i class="ri ri-circle-line align-middle"></i>
    </button>
    <button class="button-close-offcanvas">
        <i class="ri ri-menu-line align-middle"></i>
    </button>

    <div class="scrollbar" data-simplebar="">
        @include('template.user_profile')
        <div id="sidenav-menu">
            <ul class="side-nav">

                <li class="side-nav-title">Main</li>

                <li class="side-nav-item">
                    <a href="/dashboard" class="side-nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-dashboard-2-line"></i>
                        </span>
                        <span class="menu-text">Dashboard</span>
                    </a>
                </li>

                <li class="side-nav-title">Homepage</li>

                <li class="side-nav-item">
                    <a href="/homepage-hero" class="side-nav-link {{ request()->is('homepage-hero') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-home-4-line"></i>
                        </span>
                        <span class="menu-text">Homepage Hero</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/homepage-blog-section"
                        class="side-nav-link {{ request()->is('homepage-blog-section') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-article-line"></i>
                        </span>
                        <span class="menu-text">Blog Section</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/homepage-threat-map"
                        class="side-nav-link {{ request()->is('homepage-threat-map') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-shield-flash-line"></i>
                        </span>
                        <span class="menu-text">Cyber Threat Map</span>
                    </a>
                </li>

                <li class="side-nav-title">Artikel</li>

                <li class="side-nav-item">
                    <a href="/articles" class="side-nav-link {{ request()->is('articles*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-file-text-line"></i>
                        </span>
                        <span class="menu-text">List Artikel</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/articles/categories"
                        class="side-nav-link {{ request()->is('articles/categories*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-folder-line"></i>
                        </span>
                        <span class="menu-text">Kategori Artikel</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/articles/tags"
                        class="side-nav-link {{ request()->is('articles/tags*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-price-tag-3-line"></i>
                        </span>
                        <span class="menu-text">Tag Artikel</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/ebooks" class="side-nav-link {{ request()->is('ebooks*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-book-2-line"></i>
                        </span>
                        <span class="menu-text">Ebook Cyber Security</span>
                    </a>
                </li>

                <li class="side-nav-title">Produk & Service</li>

                <li class="side-nav-item">
                    <a href="/product-page" class="side-nav-link {{ request()->is('product-page') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-settings-3-line"></i>
                        </span>
                        <span class="menu-text">Pengaturan Halaman</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/products" class="side-nav-link {{ request()->is('products*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-shopping-bag-3-line"></i>
                        </span>
                        <span class="menu-text">List Produk</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/cyber-security-services"
                        class="side-nav-link {{ request()->is('cyber-security-services*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-shield-keyhole-line"></i>
                        </span>
                        <span class="menu-text">Cyber Security Services</span>
                    </a>
                </li>

                <li class="side-nav-title">Profil</li>

                <li class="side-nav-item">
                    <a href="/about-us" class="side-nav-link {{ request()->is('about-us') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-information-line"></i>
                        </span>
                        <span class="menu-text">Tentang Kami</span>
                    </a>
                </li>

                <li class="side-nav-title">Pengaturan</li>

                <li class="side-nav-item">
                    <a href="/users" class="side-nav-link {{ request()->is('users*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-user-settings-line"></i>
                        </span>
                        <span class="menu-text">Manajemen User</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/website" class="side-nav-link {{ request()->is('website*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-global-line"></i>
                        </span>
                        <span class="menu-text">Pengaturan Website</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/footer" class="side-nav-link {{ request()->is('footer*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-layout-bottom-line"></i>
                        </span>
                        <span class="menu-text">Footer Website</span>
                    </a>
                </li>

                <li class="side-nav-title">AI</li>

                <!-- 🤖 AI SESSION -->
                <li class="side-nav-item">
                    <a href="/ai/chat" class="side-nav-link {{ request()->is('ai/chat*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-chat-voice-line"></i>
                        </span>
                        <span class="menu-text">AI Chat</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/ai/settings" class="side-nav-link {{ request()->is('ai/settings') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-cpu-line"></i>
                        </span>
                        <span class="menu-text">AI Settings</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/ai/contexts" class="side-nav-link {{ request()->is('ai/contexts') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-brain-line"></i>
                        </span>
                        <span class="menu-text">AI Context</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/ai/bindings" class="side-nav-link {{ request()->is('ai/bindings') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-links-line"></i>
                        </span>
                        <span class="menu-text">AI Bindings</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/ai/prompts" class="side-nav-link {{ request()->is('ai/prompts') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-file-code-line"></i>
                        </span>
                        <span class="menu-text">AI Prompt</span>
                    </a>
                </li>

                <li class="side-nav-item">
                    <a href="/ai/rules" class="side-nav-link {{ request()->is('ai/rules') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-shield-keyhole-line"></i>
                        </span>
                        <span class="menu-text">AI Rules</span>
                    </a>
                </li>

            </ul>



        </div>
    </div>
</div>
