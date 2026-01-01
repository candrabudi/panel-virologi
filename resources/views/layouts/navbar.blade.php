<div class="fixed inset-x-0 top-0 z-10 flex h-[65px] bg-gradient-to-r from-theme-1 to-theme-2 shadow-lg">
    <div
        class="side-menu__content flex-none flex items-center z-10 ml-5 pl-0.5 pr-5 h-full xl:w-[275px] overflow-hidden relative duration-300 group-[.side-menu--collapsed]:xl:w-[91px] group-[.side-menu--collapsed.side-menu--on-hover]:xl:w-[275px] group-[.side-menu--collapsed.side-menu--on-hover]:xl:shadow-[6px_0_12px_-4px_#0000001f] before:content-[''] before:hidden before:xl:block before:absolute before:right-0 before:border-r before:border-dashed before:border-white/[0.15] before:h-4/6 before:group-[.side-menu--collapsed.side-menu--on-hover]:xl:hidden after:content-[''] after:hidden after:xl:block after:absolute after:w-full after:h-full after:bg-[length:100vw_65px] after:z-[-1] after:bg-gradient-to-r after:from-theme-1 after:to-theme-2">
        <a class="hidden items-center transition-[margin] xl:flex group-[.side-menu--collapsed.side-menu--on-hover]:xl:ml-0 group-[.side-menu--collapsed]:xl:ml-8"
            href="#">
            <div
                class="transition-transform ease-in-out group-[.side-menu--collapsed.side-menu--on-hover]:xl:-rotate-180">
                <div class="relative h-[18px] w-[18px] -rotate-45 [&_div]:bg-white">
                    <div class="absolute inset-y-0 left-0 my-auto h-[75%] w-[21%] rounded-full opacity-50">
                    </div>
                    <div class="absolute inset-0 m-auto h-[120%] w-[21%] rounded-full"></div>
                    <div class="absolute inset-y-0 right-0 my-auto h-[75%] w-[21%] rounded-full opacity-50">
                    </div>
                </div>
            </div>
            <div
                class="ml-3.5 font-medium text-white transition-opacity group-[.side-menu--collapsed.side-menu--on-hover]:xl:opacity-100 group-[.side-menu--collapsed]:xl:opacity-0">
                VIROLOGI
            </div>
        </a>
        <a class="toggle-compact-menu ml-auto hidden h-[20px] w-[20px] items-center justify-center rounded-full border border-white/40 text-white transition-[opacity,transform] hover:bg-white/5 group-[.side-menu--collapsed]:xl:rotate-180 group-[.side-menu--collapsed.side-menu--on-hover]:xl:opacity-100 group-[.side-menu--collapsed]:xl:opacity-0 3xl:flex"
            href="#">
            <i data-tw-merge="" data-lucide="arrow-left" class="h-3.5 w-3.5 stroke-[1.3]"></i>
        </a>
        <div class="flex items-center gap-1 xl:hidden">
            <a class="open-mobile-menu rounded-full p-2 hover:bg-white/5" href="#">
                <i data-tw-merge="" data-lucide="align-justify" class="stroke-[1] h-[18px] w-[18px] text-white"></i>
            </a>
            <a class="rounded-full p-2 hover:bg-white/5" data-tw-toggle="modal" data-tw-target="#quick-search"
                href="javascript:;">
                <i data-tw-merge="" data-lucide="search" class="stroke-[1] h-[18px] w-[18px] text-white"></i>
            </a>
        </div>
    </div>
    <div
        class="absolute inset-x-0 ml-5 h-full transition-[padding] duration-100 xl:pl-[275px] group-[.side-menu--collapsed]:xl:pl-[91px]">
        <div class="flex h-full w-full items-center px-5">


            <div class="flex flex-1 items-center">
                <div class="ml-auto flex items-center gap-1">
                    <a class="request-full-screen rounded-full p-2 hover:bg-white/5" href="javascript:;">
                        <i data-tw-merge="" data-lucide="expand" class="stroke-[1] h-[18px] w-[18px] text-white"></i>
                    </a>
                </div>
                <div data-tw-merge="" data-tw-placement="bottom-end" class="dropdown relative ml-5">
                    <button data-tw-toggle="dropdown" aria-expanded="false"
                        class="cursor-pointer image-fit h-[36px] w-[36px] overflow-hidden rounded-full border-[3px] border-white/[0.15]"><img
                            src="dist/images/users/user7-50x50.jpg" alt="Tailwise - Admin Dashboard Template">
                    </button>
                    <div data-transition="" data-selector=".show" data-enter="transition-all ease-linear duration-150"
                        data-enter-from="absolute !mt-5 invisible opacity-0 translate-y-1"
                        data-enter-to="!mt-1 visible opacity-100 translate-y-0"
                        data-leave="transition-all ease-linear duration-150"
                        data-leave-from="!mt-1 visible opacity-100 translate-y-0"
                        data-leave-to="absolute !mt-5 invisible opacity-0 translate-y-1"
                        class="dropdown-menu absolute z-[9999] hidden">
                        <div data-tw-merge=""
                            class="dropdown-content rounded-md border-transparent bg-white p-2 shadow-[0px_3px_10px_#00000017] dark:border-transparent dark:bg-darkmode-600 mt-1 w-56">
                            <a href="javascript:void(0);" onclick="logout()"
                                class="cursor-pointer flex items-center p-2 transition duration-300 ease-in-out rounded-md hover:bg-slate-200/60 dark:bg-darkmode-600 dark:hover:bg-darkmode-400 dropdown-item">
                                <i data-lucide="power" class="stroke-[1] mr-2 h-4 w-4"></i>
                                Logout
                            </a>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
