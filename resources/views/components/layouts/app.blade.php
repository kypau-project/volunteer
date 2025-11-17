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
                    <i class="bi-justify fs-3"></i>
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
        (function() {
            function normalize(payload) {
                if (Array.isArray(payload)) return payload[0] || {};
                return payload || {};
            }

            function showSwal(raw) {
                const p = normalize(raw);
                const icon = p.icon || p.type || 'info';
                const title = p.title || '';
                const text = p.text || p.message || '';

                if (p.toast) {
                    const Toast = Swal.mixin({
                        toast: true,
                        position: p.position || 'top-end',
                        showConfirmButton: false,
                        timer: p.timer || 3000,
                        timerProgressBar: true,
                    });
                    Toast.fire({
                        icon,
                        title
                    });
                    return;
                }

                Swal.fire({
                    icon,
                    title,
                    text
                });
            }

            // Catch browser events dispatched via dispatchBrowserEvent
            window.addEventListener('swal', function(e) {
                const payload = (e && e.detail) ? e.detail : e;
                showSwal(payload);
            });

            // Also listen for Livewire-emitted events (older versions)
            document.addEventListener('livewire:load', function() {
                if (typeof Livewire === 'undefined') return;
                Livewire.on('swal', function(payload) {
                    showSwal(payload);
                });
                Livewire.on('swal:toast', function(payload) {
                    payload = normalize(payload);
                    payload.toast = true;
                    showSwal(payload);
                });
            });
        })();
    </script>
</body>

</html>