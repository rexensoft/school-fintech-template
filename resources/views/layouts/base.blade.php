<!DOCTYPE html>

<html lang="en">
    <head>
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}">
        @yield('head')
    </head>
    <body>
        @yield('content')

        <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
        <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/app.js') }}"></script>
        @yield('scripts')
        @stack('scripts')
    </body>
</html>