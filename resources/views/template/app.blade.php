<!doctype html>
<html lang="en">

<!-- Mirrored from coderthemes.com/dhonu/layouts/pages-empty.html by HTTrack Website Copier/3.x [XR&CO'2014], Mon, 15 Dec 2025 08:07:53 GMT -->

<head>
    <meta charset="utf-8" />
    <title>Starter Page | Dhonu - Responsive Bootstrap 5 Admin Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description"
        content="Dhonu is a modern, responsive admin dashboard available on ThemeForest. Ideal for building CRM, CMS, project management tools, and custom web applications with a clean UI, flexible layouts, and rich features." />
    <meta name="keywords"
        content="Dhonu, admin dashboard, ThemeForest, Bootstrap 5 admin, responsive admin, CRM dashboard, CMS admin, web app UI, admin theme, premium admin template" />
    <meta name="author" content="Coderthemes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}" />
    <!-- Theme Config Js -->
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <!-- Vendor css -->
    <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- App css -->
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />
@stack('styles')
</head>

<body>
    <!-- Begin page -->
    <div class="wrapper">
        @include('template.header')
        @include('template.sidenav')
        <div class="content-page">
            <div class="container-fluid">
                @yield('content')
            </div>
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12 text-center">
                            ©
                            <script>
                                document.write(new Date().getFullYear())
                            </script>
                            Dhonu By <span class="fw-semibold">Coderthemes</span>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>
    <script src="{{ asset('assets/js/vendors.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @stack('scripts')
</body>

</html>
