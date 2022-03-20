<!DOCTYPE html>

<html>
  <head>
    <title>
      @hasSection('title') @yield('title') @else Guest Route @endif
    </title>
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}">
    <link rel="icon" href="{{ asset('assets/img/brand.png') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="author" content="Abdul">
    <meta name="description" content="Type your web description">
    <meta name="theme-color" content="#FFF">
    <meta name="title" content="Guest Route">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @yield('head')
  </head>
  <body>
    <x-sweetalert/>
    
    @yield('content')

    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    @yield('scripts')
    @stack('scripts')
  </body>
</html>