<!DOCTYPE html>
<html lang="en">

<head>
    <!--required meta tags-->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="google-site-verification" content="V2OgxJS_7AIPnVUk6F2XM2A75YmmVKeMJxLer3cpJD8" />

    <!--facebook og-->
    <meta name="facebook-domain-verification" content="frj0236lb038s4g0cimaj14ev4kv43" />

    <!--meta-->
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="PT. Nirmala Satya Development">

    <!--favicon icon-->
    <link rel="icon" href="https://tepad.id/nsd/favicon.ico" type="image/png" sizes="16x16">

    <!--title-->
    <title>Akses ditolak | PT Nirmala Satya Development</title>
    <link rel="canonical" href="" />

    <!--build:css-->
    <link rel="stylesheet" href="https://tepad.id/assets/quiety/css/main.css">
    <!-- endbuild -->

    <!--custom css start-->
    <link rel="stylesheet" href="https://tepad.id/assets/quiety/css/custom.css">
    <!--custom css end-->
</head>

<body>

    <!--preloader start-->
    <div id="preloader">
        <div class="preloader-wrap">
            <img src="assets/img/favicon.png" alt="logo" class="img-fluid preloader-icon" />
            <div class="loading-bar"></div>
        </div>
    </div>
    <!--preloader end-->
    <!--main content wrapper start-->
    <div class="main-wrapper">


        <!--coming soon section start-->
        <section class="coming-soon-section min-vh-100 ptb-120 overflow-hidden position-relative w-100 d-flex flex-column justify-content-center" style="background: url('https://tepad.id/assets//img/page-header-bg.svg')no-repeat bottom right">
            <div class="bg-dark fixed-bg"></div>
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-12">
                        <div class="coming-soon-content-wrap position-relative z-2">


                            <h5 class="text-white">Akses ditolak !</h5>
                            <h1 class="text-white">Anda tidak memiliki hak akses untuk halaman ini.</h1>
                            <div class="action-btns">
                                <a href="{{ url('/') }}" class="btn btn-primary mt-5">Beranda</a>
                                <livewire:auth.logout />
                            </div>


                        </div>
                    </div>
                </div>
                <!--popup form start-->
                <div id="notify-form" class="mfp-hide white-popup-block  rounded-custom bg-white p-5">
                    <div class="subscribe-info-wrap text-center position-relative z-2">
                        <div class="section-heading">
                            <h3>Subscribe for Latest News and Updated!</h3>
                            <p>We can help you to create your dream website for better business revenue.</p>
                        </div>
                        <div class="form-block-banner mw-60 m-auto">
                            <form id="email-form2" name="email-form" class="subscribe-form d-flex">
                                <input type="email" class="form-control me-2" name="Email" data-name="Email" placeholder="Your email" id="Email2" required="">
                                <input type="submit" value="Subscribe!" data-wait="Please wait..." class="btn btn-primary">
                            </form>
                        </div>
                        <ul class="nav justify-content-center subscribe-feature-list mt-3">
                            <li class="nav-item">
                                <span><i class="fad fa-dot-circle text-primary me-2"></i>No credit card required</span>
                            </li>
                            <li class="nav-item">
                                <span><i class="fad fa-dot-circle text-primary me-2"></i>Support 24/7</span>
                            </li>
                            <li class="nav-item">
                                <span><i class="fad fa-dot-circle text-primary me-2"></i>Cancel anytime</span>
                            </li>
                        </ul>
                    </div>
                </div>
                <!--popup form end-->
            </div>

            <ul class="animated-circle list-unstyled z--1">
                <li class="transition-delay-1 bg-danger"></li>
                <li class="transition-delay-2 bg-warning"></li>
                <li class="transition-delay-3 bg-primary"></li>
            </ul>
        </section>
        <!--coming soon section end-->

    </div>
    <!--main content wrapper end-->


    <!--build:js-->
    <script src="https://tepad.id/assets/quiety/js/vendors/jquery-3.6.0.min.js"></script>
    <script src="https://tepad.id/assets/quiety/js/vendors/bootstrap.bundle.min.js"></script>
    <script src="https://tepad.id/assets/quiety/js/vendors/swiper-bundle.min.js"></script>
    <script src="https://tepad.id/assets/quiety/js/vendors/jquery.magnific-popup.min.js"></script>
    <script src="https://tepad.id/assets/quiety/js/vendors/parallax.min.js"></script>
    <script src="https://tepad.id/assets/quiety/js/vendors/aos.js"></script>
    <script src="https://tepad.id/assets/quiety/js/app.js"></script>



</body>

</html>