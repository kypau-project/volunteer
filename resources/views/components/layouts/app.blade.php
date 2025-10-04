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

        <livewire:atom.sidebar />

        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>

            {{ $slot }}

            <livewire:atom.footer />

        </div>
    </div>

    <script src="{{asset('mazer/assets/static/js/components/dark.js')}}"></script>
    <script src="{{asset('mazer/assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js')}}"></script>
    <script src="{{asset('mazer/assets/compiled/js/app.js')}}"></script>
    <script src="{{asset('mazer/assets/extensions/apexcharts/apexcharts.min.js')}}"></script>
    <script src="{{asset('mazer/assets/static/js/pages/dashboard.js')}}"></script>
    @stack('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('swal:toast', (event) => {
                const {
                    icon,
                    title,
                    timer
                } = event[0];
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: timer || 3000,
                    timerProgressBar: true
                });
                Toast.fire({
                    icon,
                    title
                });
            });

            Livewire.on('swal', (event) => {
                const {
                    icon,
                    title,
                    text
                } = event[0];
                Swal.fire({
                    icon,
                    title,
                    text
                });
            });
        });
    </script>
</body>

</html>