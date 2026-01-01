@php
    use App\Models\Website;
    $setting = Website::first();
@endphp

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" class="opacity-0" lang="en"><!-- BEGIN: Head -->
<meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') | {{ $setting ? $setting->name : 'default' }}</title>
    <!-- BEGIN: CSS Assets-->
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/tippy.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/vendors/simplebar.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/themes/havoc.css') }}">
    <link rel="stylesheet" href="{{ asset('dist/css/app.css') }}"> <!-- END: CSS Assets-->
    <style>
        @keyframes toast-slide-in {
            from {
                opacity: 0;
                transform: translateX(120%);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes toast-slide-out {
            from {
                opacity: 1;
                transform: translateX(0);
            }

            to {
                opacity: 0;
                transform: translateX(120%);
            }
        }

        .toast-animate-in {
            animation: toast-slide-in 0.35s ease-out forwards;
        }

        .toast-animate-out {
            animation: toast-slide-out 0.25s ease-in forwards;
        }
    </style>
    @stack('styles')

</head>
<!-- END: Head -->

<body>
    <div
        class="havoc before:content-[''] before:bg-gradient-to-b before:from-slate-100 before:to-slate-50 before:h-screen before:w-full before:fixed before:top-0 after:content-[''] after:fixed after:inset-0 after:bg-[radial-gradient(rgb(0_0_0_/_10%)_1px,_transparent_0)] after:bg-[length:25px_25px]">
        <div id="toast-container" class="fixed top-4 right-4 z-[9999] flex flex-col gap-3">
        </div>

        <div
            class="[&.loading-page--before-hide]:h-screen [&.loading-page--before-hide]:relative loading-page loading-page--before-hide [&.loading-page--before-hide]:before:block [&.loading-page--hide]:before:opacity-0 before:content-[''] before:transition-opacity before:duration-300 before:hidden before:inset-0 before:h-screen before:w-screen before:fixed before:bg-gradient-to-b before:from-theme-1 before:to-theme-2 before:z-[60] [&.loading-page--before-hide]:after:block [&.loading-page--hide]:after:opacity-0 after:content-[''] after:transition-opacity after:duration-300 after:hidden after:h-16 after:w-16 after:animate-pulse after:fixed after:opacity-50 after:inset-0 after:m-auto after:bg-loading-puff after:bg-cover after:z-[61]">
            <div class="fixed top-0 left-0 z-50 h-screen side-menu group side-menu--collapsed">
                @include('layouts.navbar')
                @include('layouts.side_menu')
            </div>
            <div
                class="content transition-[margin,width] duration-100 pl-5 xl:pl-10 pr-5 mt-[65px] pt-[31px] pb-16 relative z-10 content--compact xl:ml-[275px] [&.content--compact]:xl:ml-[91px]">
                <div class="container">
                    <div class="grid grid-cols-12 gap-x-6 gap-y-10">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- BEGIN: Vendor JS Assets-->
    <script src="{{ asset('dist/js/vendors/dom.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/tailwind-merge.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/lucide.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/tippy.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/simplebar.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/transition.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/popper.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/dropdown.js') }}"></script>
    <script src="{{ asset('dist/js/vendors/modal.js') }}"></script>

    <script src="{{ asset('dist/js/components/base/theme-color.js') }}"></script>
    <script src="{{ asset('dist/js/components/base/lucide.js') }}"></script>
    <script src="{{ asset('dist/js/components/base/tippy.js') }}"></script>

    <script src="{{ asset('dist/js/themes/havoc.js') }}"></script>
    <script src="{{ asset('dist/js/components/quick-search.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        function logout() {
            axios.post("{{ route('logout') }}")
                .then(res => {
                    if (res.data?.success) {
                        showToast(
                            'success',
                            'Logout',
                            res.data.message ?? 'Berhasil logout'
                        )

                        setTimeout(() => {
                            window.location.href = '/login'
                        }, 1200)
                    } else {
                        showToast(
                            'error',
                            'Logout gagal',
                            res.data?.message ?? 'Terjadi kesalahan'
                        )
                    }
                })
                .catch(() => {
                    showToast(
                        'error',
                        'Logout gagal',
                        'Gagal logout'
                    )
                })
        }
    </script>

    <script>
        function showToast(type, title, message) {
            const toast = document.createElement('div')
            toast.className = 'toastify on toastify-right toastify-top toast-animate-in'
            toast.setAttribute('aria-live', 'polite')

            toast.style.position = 'fixed'
            toast.style.top = '15px'
            toast.style.right = '15px'
            toast.style.width = 'auto'
            toast.style.maxWidth = '420px'
            toast.style.zIndex = '9999'

            toast.innerHTML = `
        <div class="py-5 pl-5 pr-14 bg-white border border-slate-200/60 rounded-lg shadow-xl
                    dark:bg-darkmode-600 dark:text-slate-300 dark:border-darkmode-600 flex items-start">

            ${
                type === 'success'
                    ? `
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-check-circle stroke-[1] w-5 h-5 text-success">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="M20 6 9 17l-5-5"></path>
                                                </svg>`
                    : `
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    class="lucide lucide-x-circle stroke-[1] w-5 h-5 text-danger">
                                                    <circle cx="12" cy="12" r="10"></circle>
                                                    <path d="m15 9-6 6"></path>
                                                    <path d="m9 9 6 6"></path>
                                                </svg>`
            }

            <div class="ml-4 mr-4">
                <div class="font-medium">${title}</div>
                <div class="mt-1 text-slate-500 text-sm">${message}</div>
            </div>
        </div>

        <button type="button" class="toast-close"
            style="position:absolute; top:10px; right:10px">
            ✖
        </button>
    `

            toast.querySelector('.toast-close').onclick = () => closeToast(toast)
            document.body.appendChild(toast)

            setTimeout(() => closeToast(toast), 4000)
        }

        function closeToast(toast) {
            toast.classList.remove('toast-animate-in')
            toast.classList.add('toast-animate-out')

            setTimeout(() => toast.remove(), 250)
        }
    </script>
    @stack('scripts')
</body>

<!-- Mirrored from tailwise-html.vercel.app/havoc-departments.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 27 Dec 2025 16:15:40 GMT -->

</html>
