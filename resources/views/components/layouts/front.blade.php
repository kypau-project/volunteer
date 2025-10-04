<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <link rel="shortcut icon" href="{{asset('images/cs.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('mazer/assets/compiled/css/iconly.css') }}">
    @stack('css')
</head>

<body>
    <script src="{{asset('mazer/assets/static/js/initTheme.js')}}"></script>
    <div id="app">
        <div id="main" class="layout-horizontal">
            <header class="mb-5">
                <livewire:front.atom.header />
                <livewire:front.atom.navbar />
            </header>

            <div class="content-wrapper container">

                {{ $slot }}

            </div>
            <livewire:front.atom.footer />
        </div>
    </div>
    <script src="{{asset('mazer/assets/static/js/pages/horizontal-layout.js')}}"></script>
    <script src="{{asset('mazer/assets/static/js/components/dark.js')}}"></script>
    <script src="{{asset('mazer/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('mazer/assets/compiled/js/app.js')}}"></script>
    <script src="{{asset('mazer/assets/extensions/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{asset('mazer/assets/static/js/pages/dashboard.js')}}"></script>
    @stack('js')
</body>

</html>