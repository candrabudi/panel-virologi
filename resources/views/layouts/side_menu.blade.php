 <div class="side-menu__content absolute inset-y-0 z-10 xl:top-[65px] xl:z-0 xl:py-3.5">
     <div
         class="box rounded-none xl:rounded-xl xl:ml-5 w-[275px] duration-300 transition-[width,margin] group-[.side-menu--collapsed]:xl:w-[91px] group-[.side-menu--collapsed.side-menu--on-hover]:xl:shadow-[6px_0_12px_-4px_#0000000f] group-[.side-menu--collapsed.side-menu--on-hover]:xl:w-[275px] relative overflow-hidden h-full flex flex-col after:content-[''] after:fixed after:inset-0 after:bg-black/80 after:z-[-1] after:xl:hidden group-[.side-menu--mobile-menu-open]:ml-0 group-[.side-menu--mobile-menu-open]:after:block -ml-[275px] after:hidden">
         <div
             class="close-mobile-menu fixed ml-[275px] w-10 h-10 items-center justify-center xl:hidden [&.close-mobile-menu--mobile-menu-open]:flex hidden">
             <a class="ml-5 mt-5" href="#">
                 <i data-tw-merge="" data-lucide="x" class="stroke-[1] h-8 w-8 text-white"></i>
             </a>
         </div>
         <div
             class="scrollable-ref w-full h-full z-20 px-5 overflow-y-auto overflow-x-hidden pb-3 [-webkit-mask-image:-webkit-linear-gradient(top,rgba(0,0,0,0),black_30px)] [&:-webkit-scrollbar]:w-0 [&:-webkit-scrollbar]:bg-transparent [&_.simplebar-content]:p-0 [&_.simplebar-track.simplebar-vertical]:w-[10px] [&_.simplebar-track.simplebar-vertical]:mr-0.5 [&_.simplebar-track.simplebar-vertical_.simplebar-scrollbar]:before:bg-slate-400/30">
             <ul class="scrollable">

                 <li class="side-menu__divider">
                     DASHBOARDS
                 </li>
                 <li>
                     <a href="/dashboard"
                         class="side-menu__link {{ request()->is('dashboard') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="command" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Analytics</div>
                     </a>
                 </li>


                 <li class="side-menu__divider">
                     HOMEPAGE
                 </li>
                 <li>
                     <a href="/homepage-hero"
                         class="side-menu__link {{ request()->is('homepage-hero') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="file" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Hero</div>
                     </a>
                 </li>
                 <li>
                     <a href="/homepage-blog-section"
                         class="side-menu__link {{ request()->is('homepage-blog-section') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="book" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Blog Section</div>
                     </a>
                 </li>
                 <li>
                     <a href="/homepage-threat-map"
                         class="side-menu__link {{ request()->is('homepage-threat-map') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="map" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Threat Map</div>
                     </a>
                 </li>

                 <li class="side-menu__divider">
                     Artikel
                 </li>
                 <li>
                     <a href="/articles"
                         class="side-menu__link {{ request()->is('articles') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="file" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Data Artikel</div>
                     </a>
                 </li>
                 <li>
                     <a href="/articles/categories"
                         class="side-menu__link {{ request()->is('articles/categories') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="book" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Data Kategori</div>
                     </a>
                 </li>
                 <li>
                     <a href="/articles/tags"
                         class="side-menu__link {{ request()->is('articles/tags') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="map" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Data Tag</div>
                     </a>
                 </li>
                 <li class="side-menu__divider">
                     Ebook
                 </li>
                 <li>
                     <a href="/ebooks"
                         class="side-menu__link {{ request()->is('ebooks') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="file" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Data E-Book</div>
                     </a>
                 </li>
                 <li class="side-menu__divider">
                     Produk
                 </li>
                 <li>
                     <a href="/product-page"
                         class="side-menu__link {{ request()->is('product-page') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="cog" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Pengaturan Halaman</div>
                     </a>
                 </li>
                 <li>
                     <a href="/products"
                         class="side-menu__link {{ request()->is('products') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="file" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Data Produk</div>
                     </a>
                 </li>
                 <li class="side-menu__divider">
                     Layanan
                 </li>
                 <li>
                     <a href="/cyber-security-services"
                         class="side-menu__link {{ request()->is('cyber-security-services') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="file" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Cyber Security Services</div>
                     </a>
                 </li>
                 <li class="side-menu__divider">
                     Pengaturan
                 </li>
                 <li>
                     <a href="/about-us"
                         class="side-menu__link {{ request()->is('about-us') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="file" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Tentang Kami</div>
                     </a>
                 </li>
                 <li>
                     <a href="/users"
                         class="side-menu__link {{ request()->is('users') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="users" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Manajemen Pengguna</div>
                     </a>
                 </li>
                 <li>
                     <a href="/website"
                         class="side-menu__link {{ request()->is('website') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="globe" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Pengaturan Website</div>
                     </a>
                 </li>
             </ul>
         </div>
     </div>
 </div>
