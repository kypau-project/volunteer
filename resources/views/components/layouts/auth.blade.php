<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Login')</title>
    <link rel="shortcut icon" href="{{asset('images/cs.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('mazer/assets/compiled/css/auth.css') }}">
    @livewireStyles
</head>

<body>
    <script src="{{asset('mazer/assets/static/js/initTheme.js')}}"></script>

    <div id="auth">
        {{ $slot }}
    </div>
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>

</html>