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
                        class="side-menu__link {{ request()->is('cyber-security-services*') ? 'side-menu__link--active' : '' }}">
                        <i data-tw-merge="" data-lucide="shield" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                        <div class="side-menu__link__title">Cyber Security Services</div>
                    </a>
                </li>
                <li class="side-menu__divider">
                    Leak Intelligence
                </li>
                <li>
                    <a href="{{ route('leak_check.index') }}"
                        class="side-menu__link {{ request()->is('leak-check') ? 'side-menu__link--active' : '' }}">
                        <i data-tw-merge="" data-lucide="key" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                        <div class="side-menu__link__title">Pengaturan API</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('leak_check.logs') }}"
                        class="side-menu__link {{ request()->is('leak-check/logs*') ? 'side-menu__link--active' : '' }}">
                        <i data-tw-merge="" data-lucide="database" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                        <div class="side-menu__link__title">Data Leak Logs</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('leak_request.index') }}"
                        class="side-menu__link {{ request()->is('leak-request*') ? 'side-menu__link--active' : '' }}">
                        <i data-tw-merge="" data-lucide="inbox" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                        <div class="side-menu__link__title">Data Access Requests</div>
                    </a>
                </li>
                 <li class="side-menu__divider">
                    System Monitor
                </li>
                <li>
                    <a href="{{ route('traffic_logs.index') }}"
                        class="side-menu__link {{ request()->is('traffic-logs*') ? 'side-menu__link--active' : '' }}">
                        <i data-tw-merge="" data-lucide="radio" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                        <div class="side-menu__link__title">Traffic Observatory</div>
                    </a>
                </li>
                <li class="side-nav-title side-menu__divider">
                    AI
                </li>
                <li>
                    <a href="/ai/chat" class="side-menu__link {{ request()->is('ai/chat') ? 'side-menu__link--active' : '' }}">
                        <i data-tw-merge="" data-lucide="message-square" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                        <div class="side-menu__link__title">AI Chat</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ai_chat.index') }}" class="side-menu__link {{ request()->is('ai/chat/sessions*') ? 'side-menu__link--active' : '' }}">
                        <i data-tw-merge="" data-lucide="history" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                        <div class="side-menu__link__title">AI Sessions</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ai.performance.index') }}" class="side-menu__link {{ request()->is('ai/performance*') ? 'side-menu__link--active' : '' }}">
                        <i data-tw-merge="" data-lucide="bar-chart-2" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                        <div class="side-menu__link__title">Performance Metrics</div>
                    </a>
                </li>
                <li>
                    <a href="{{ route('ai.knowledge.index') }}" class="side-menu__link {{ request()->is('ai/knowledge*') ? 'side-menu__link--active' : '' }}">
                        <i data-tw-merge="" data-lucide="database" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                        <div class="side-menu__link__title">Knowledge Base</div>
                    </a>
                </li>
                 <li class="side-menu__divider">
                     Pengaturan
                 </li>
                 <li>
                     <a href="/users"
                         class="side-menu__link {{ request()->is('users') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="users" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Manajemen Pengguna</div>
                     </a>
                 </li>
                   <li>
                     <a href="/home-sections"
                         class="side-menu__link {{ request()->is('home-sections*') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="layout-template" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Home Sections</div>
                     </a>
                 </li>
                 <li>
                     <a href="/pages"
                         class="side-menu__link {{ request()->is('pages*') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="file-text" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Pages Management</div>
                     </a>
                 </li>
                 <li>
                     <a href="/footer-settings"
                         class="side-menu__link {{ request()->is('footer-settings*') ? 'side-menu__link--active' : '' }}">
                         <i data-tw-merge="" data-lucide="settings" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                         <div class="side-menu__link__title">Footer Settings</div>
                     </a>
                 </li>
                <li>
                    <a href="/contact-settings"
                        class="side-menu__link {{ request()->is('contact-settings*') ? 'side-menu__link--active' : '' }}">
                        <i data-tw-merge="" data-lucide="phone" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                        <div class="side-menu__link__title">Contact Settings</div>
                    </a>
                </li>
                <li>
                    <a href="/about-settings"
                        class="side-menu__link {{ request()->is('about-settings*') ? 'side-menu__link--active' : '' }}">
                        <i data-tw-merge="" data-lucide="info" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                        <div class="side-menu__link__title">About Settings</div>
                    </a>
                </li>
                <li>
                    <a href="/website-settings"
                        class="side-menu__link {{ request()->is('website-settings*') ? 'side-menu__link--active' : '' }}">
                        <i data-tw-merge="" data-lucide="monitor" class="stroke-[1] w-5 h-5 side-menu__link__icon"></i>
                        <div class="side-menu__link__title">Website Settings</div>
                    </a>
                </li>
             </ul>
         </div>
     </div>
 </div>
