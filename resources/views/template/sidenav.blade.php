<div class="sidenav-menu">
    <!-- Brand Logo -->
    <a href="index.html" class="logo">
        <span class="logo logo-light">
            <span class="logo-lg"><img src="assets/images/logo.png" alt="logo" /></span>
            <span class="logo-sm"><img src="assets/images/logo-sm.png" alt="small logo" /></span>
        </span>

        <span class="logo logo-dark">
            <span class="logo-lg"><img src="assets/images/logo-black.png" alt="dark logo" /></span>
            <span class="logo-sm"><img src="assets/images/logo-sm.png" alt="small logo" /></span>
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

                <li class="side-nav-title mt-2" data-lang="main">Main</li>

                <li class="side-nav-item">
                    <a href="/dashboard" class="side-nav-link">
                        <span class="menu-icon">
                            <i class="ri ri-dashboard-2-line"></i>
                        </span>
                        <span class="menu-text" data-lang="dashboard">Dashboard</span>
                    </a>
                </li>

                <!-- ================== HOMEPAGE ================== -->
                <li class="side-nav-title mt-3" data-lang="homepage">Homepage</li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#menuHomepage"
                        aria-expanded="{{ request()->is('homepage-*') ? 'true' : 'false' }}"
                        aria-controls="menuHomepage" class="side-nav-link">
                        <span class="menu-icon">
                            <i class="ri ri-home-4-line"></i>
                        </span>
                        <span class="menu-text">Homepage</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <div class="collapse {{ request()->is('homepage-*') ? 'show' : '' }}" id="menuHomepage">
                        <ul class="sub-menu">

                            <li class="side-nav-item">
                                <a href="/homepage-hero"
                                    class="side-nav-link {{ request()->is('homepage-hero') ? 'active' : '' }}">
                                    <span class="menu-text">Homepage Hero</span>
                                </a>
                            </li>

                            <li class="side-nav-item">
                                <a href="/homepage-blog-section"
                                    class="side-nav-link {{ request()->is('homepage-blog-section') ? 'active' : '' }}">
                                    <span class="menu-text">Blog & Artikel</span>
                                </a>
                            </li>

                            <li class="side-nav-item">
                                <a href="/homepage-threat-map"
                                    class="side-nav-link {{ request()->is('homepage-threat-map') ? 'active' : '' }}">
                                    <span class="menu-text">Cyber Threat Map</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>

                <li class="side-nav-title mt-3" data-lang="produk">Produk</li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#menuProduk"
                        aria-expanded="{{ request()->is('product-page*', 'products*') ? 'true' : 'false' }}"
                        aria-controls="menuProduk" class="side-nav-link">

                        <span class="menu-icon">
                            <i class="ri ri-shopping-bag-3-line"></i>
                        </span>

                        <span class="menu-text">Produk</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <div class="collapse {{ request()->is('product-page*', 'products*') ? 'show' : '' }}"
                        id="menuProduk">

                        <ul class="sub-menu">

                            <li class="side-nav-item">
                                <a href="/product-page"
                                    class="side-nav-link {{ request()->is('product-page') ? 'active' : '' }}">
                                    <span class="menu-text">Pengaturan Halaman</span>
                                </a>
                            </li>

                            <li class="side-nav-item">
                                <a href="/products"
                                    class="side-nav-link {{ request()->is('products*') ? 'active' : '' }}">
                                    <span class="menu-text">List Produk</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>


                <li class="side-nav-title mt-3" data-lang="profil">Profil</li>

                <li class="side-nav-item">
                    <a href="/cms/about-us" class="side-nav-link {{ request()->is('cms/about-us') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ri ri-information-line"></i>
                        </span>
                        <span class="menu-text">Tentang Kami</span>
                    </a>
                </li>



                <!-- ================== PENGATURAN ================== -->
                <li class="side-nav-title mt-3" data-lang="pengaturan">Pengaturan</li>

                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#menuWebsiteSetting"
                        aria-expanded="{{ request()->is('website*', 'footer*') ? 'true' : 'false' }}"
                        aria-controls="menuWebsiteSetting" class="side-nav-link">

                        <span class="menu-icon">
                            <i class="ri ri-global-line"></i>
                        </span>

                        <span class="menu-text">Website</span>
                        <span class="menu-arrow"></span>
                    </a>

                    <div class="collapse {{ request()->is('website*', 'footer*') ? 'show' : '' }}"
                        id="menuWebsiteSetting">

                        <ul class="sub-menu">

                            <li class="side-nav-item">
                                <a href="/website"
                                    class="side-nav-link {{ request()->is('website') ? 'active' : '' }}">
                                    <span class="menu-text">Pengaturan Website</span>
                                </a>
                            </li>

                            <li class="side-nav-item">
                                <a href="/footer"
                                    class="side-nav-link {{ request()->is('footer') ? 'active' : '' }}">
                                    <span class="menu-text">Footer Website</span>
                                </a>
                            </li>

                        </ul>
                    </div>
                </li>


            </ul>


        </div>
    </div>
</div>
