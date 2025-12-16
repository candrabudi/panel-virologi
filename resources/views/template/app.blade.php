@php
    use App\Models\Website;
    $setting = Website::first();
@endphp

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{ $setting ? $setting->name : 'default' }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ $setting ? $setting->favicon : '' }}" />
    <script src="{{ asset('assets/js/config.js') }}"></script>

    <link href="{{ asset('assets/css/vendors.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" />

    @stack('styles')
</head>

<body>
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
                            © <script>document.write(new Date().getFullYear())</script>
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
