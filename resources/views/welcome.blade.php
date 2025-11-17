<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Volunteer Management System - Jadilah Bagian dari Perubahan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            --bg-primary: #F2F7FF;
            --bg-secondary: #FFFFFF;
            --text-primary: #151522;
            --text-secondary: #64748b;
            --accent: #3950A2;
            --accent-hover: #2d3d7f;
            --border-color: #e2e8f0;
            --shadow: rgba(57, 80, 162, 0.1);
        }

        [data-theme="dark"] {
            --bg-primary: #151522;
            --bg-secondary: #1e1e2e;
            --text-primary: #F2F7FF;
            --text-secondary: #94a3b8;
            --accent: #3950A2;
            --accent-hover: #4a5fc4;
            --border-color: #2d2d3d;
            --shadow: rgba(0, 0, 0, 0.3);
        }

        * {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
        }

        body {
            background-color: var(--bg-primary);
            color: var(--text-primary);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }

        .navbar {
            background-color: var(--bg-secondary) !important;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--border-color);
            padding: 1rem 0;
        }

        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary) !important;
        }

        .nav-link {
            color: var(--text-secondary) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .nav-link:hover {
            color: var(--accent) !important;
            background-color: rgba(57, 80, 162, 0.1);
        }

        .btn-accent {
            background: var(--accent);
            color: white !important;
            border: none;
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-accent:hover {
            background: var(--accent-hover);
            transform: translateY(-2px);
            box-shadow: 0 10px 25px var(--shadow);
        }

        .theme-toggle {
            background: var(--bg-secondary);
            border: 2px solid var(--border-color);
            border-radius: 50px;
            padding: 0.5rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s ease;
        }

        .theme-toggle:hover {
            border-color: var(--accent);
        }

        .hero-section {
            background: linear-gradient(135deg, var(--accent) 0%, #2d3d7f 100%);
            padding: 120px 0 100px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -250px;
            right: -100px;
            animation: float 6s ease-in-out infinite;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            bottom: -100px;
            left: -50px;
            animation: float 8s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .hero-content {
            position: relative;
            z-index: 1;
        }

        .hero-section h1 {
            font-size: clamp(2.5rem, 5vw, 4rem);
            font-weight: 800;
            margin-bottom: 1.5rem;
            color: white;
            line-height: 1.2;
        }

        .hero-section p {
            font-size: clamp(1.1rem, 2vw, 1.4rem);
            margin-bottom: 2.5rem;
            color: rgba(255, 255, 255, 0.9);
        }

        .btn-hero {
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }

        .btn-hero-primary {
            background: white;
            color: var(--accent);
        }

        .btn-hero-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
        }

        .btn-hero-secondary {
            background: transparent;
            color: white;
            border-color: white;
        }

        .btn-hero-secondary:hover {
            background: white;
            color: var(--accent);
        }

        .stats-section {
            background-color: var(--bg-secondary);
            padding: 80px 0;
            margin-top: -50px;
            position: relative;
            z-index: 2;
        }

        .stat-card {
            text-align: center;
            padding: 2rem;
            background: var(--bg-primary);
            border-radius: 20px;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px var(--shadow);
            border-color: var(--accent);
        }

        .stat-number {
            font-size: 3.5rem;
            font-weight: 800;
            background: linear-gradient(135deg, var(--accent), #2d3d7f);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .stat-label {
            color: var(--text-secondary);
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .section-title {
            font-size: clamp(2rem, 4vw, 3rem);
            font-weight: 800;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .section-subtitle {
            color: var(--text-secondary);
            font-size: 1.2rem;
            margin-bottom: 3rem;
        }

        .feature-card {
            background: var(--bg-secondary);
            border-radius: 20px;
            padding: 2.5rem;
            border: 1px solid var(--border-color);
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px var(--shadow);
            border-color: var(--accent);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--accent), #2d3d7f);
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
            color: white;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--text-primary);
        }

        .feature-description {
            color: var(--text-secondary);
            line-height: 1.7;
        }

        .step-circle {
            width: 90px;
            height: 90px;
            background: linear-gradient(135deg, var(--accent), #2d3d7f);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0 auto 1.5rem;
            box-shadow: 0 10px 30px rgba(57, 80, 162, 0.3);
        }

        .step-title {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 0.8rem;
            color: var(--text-primary);
        }

        .step-description {
            color: var(--text-secondary);
        }

        .cta-section {
            background: linear-gradient(135deg, #2d3d7f 0%, var(--accent) 100%);
            padding: 100px 0;
            position: relative;
            overflow: hidden;
        }

        .cta-section::before {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            top: -200px;
            left: -100px;
        }

        .cta-title {
            font-size: clamp(2rem, 4vw, 3.5rem);
            font-weight: 800;
            color: white;
            margin-bottom: 1.5rem;
        }

        .cta-description {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 2.5rem;
        }

        footer {
            background-color: var(--bg-secondary);
            padding: 60px 0 30px;
            border-top: 1px solid var(--border-color);
        }

        .footer-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--text-primary);
        }

        .footer-link {
            color: var(--text-secondary);
            text-decoration: none;
            display: block;
            padding: 0.5rem 0;
            transition: all 0.3s ease;
        }

        .footer-link:hover {
            color: var(--accent);
            padding-left: 5px;
        }

        .section {
            padding: 100px 0;
        }

        @media (max-width: 768px) {
            .hero-section {
                padding: 80px 0 60px;
            }

            .section {
                padding: 60px 0;
            }

            .stats-section {
                padding: 60px 0;
                margin-top: -30px;
            }

            .stat-card {
                margin-bottom: 1rem;
            }
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi-heart-fill" style="color: #3950A2;"></i> Volunteer
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ url('/list-laporan') }}">Acara</a>
                    </li>
                    @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn-accent" href="{{ route('register') }}">Daftar Sekarang</a>
                    </li>
                    @else
                    <li class="nav-item">
                        @php $role = optional(auth()->user())->role; @endphp
                        <a class="nav-link btn-accent" href="{{ $role === 'admin' ? route('admin.dashboard') : route('coordinator.dashboard') }}">Dashboard</a>
                    </li>
                    @endguest
                    <li class="nav-item ms-3">
                        <div class="theme-toggle" onclick="toggleTheme()">
                            <i class="bi-sun-fill" id="theme-icon" style="color: var(--accent);"></i>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" style="margin-top: 76px;">
        <div class="container hero-content">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <h1>Jadilah Bagian dari Perubahan</h1>
                    <p>Platform manajemen relawan modern untuk menciptakan dampak positif bagi masyarakat</p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="{{ route('register') }}" class="btn btn-hero btn-hero-primary">
                            <i class="bi-person-plus-fill me-2"></i>Daftar Sebagai Relawan
                        </a>
                        <a href="{{ url('/list-laporan') }}" class="btn btn-hero btn-hero-secondary">
                            <i class="bi-search me-2"></i>Jelajahi Acara
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-number">500+</div>
                        <div class="stat-label">Relawan Aktif</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-number">150+</div>
                        <div class="stat-label">Acara Terlaksana</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-number">10K+</div>
                        <div class="stat-label">Jam Kontribusi</div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-number">50+</div>
                        <div class="stat-label">Organisasi Partner</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="section">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Fitur Unggulan</h2>
                <p class="section-subtitle">Solusi lengkap untuk manajemen relawan dan acara sosial</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi-calendar-event"></i>
                        </div>
                        <h4 class="feature-title">Manajemen Acara</h4>
                        <p class="feature-description">Buat dan kelola acara dengan mudah. Atur jadwal, lokasi, dan kuota relawan secara efisien.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi-people-fill"></i>
                        </div>
                        <h4 class="feature-title">Database Relawan</h4>
                        <p class="feature-description">Kelola profil relawan lengkap dengan keterampilan dan riwayat partisipasi mereka.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi-award-fill"></i>
                        </div>
                        <h4 class="feature-title">Sertifikat Digital</h4>
                        <p class="feature-description">Berikan apresiasi dengan sertifikat digital otomatis untuk setiap kontribusi relawan.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi-clipboard-check-fill"></i>
                        </div>
                        <h4 class="feature-title">Tracking Kehadiran</h4>
                        <p class="feature-description">Catat kehadiran dan hitung jam kontribusi secara otomatis dan real-time.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi-graph-up-arrow"></i>
                        </div>
                        <h4 class="feature-title">Dashboard Statistik</h4>
                        <p class="feature-description">Monitor performa dengan grafik dan analitik yang komprehensif dan mudah dipahami.</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="feature-card">
                        <div class="feature-icon">
                            <i class="bi-bell-fill"></i>
                        </div>
                        <h4 class="feature-title">Notifikasi Otomatis</h4>
                        <p class="feature-description">Dapatkan reminder dan notifikasi status pendaftaran secara instant.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works -->
    <section class="section" style="background-color: var(--bg-secondary);">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="section-title">Cara Kerja</h2>
                <p class="section-subtitle">Empat langkah mudah untuk memulai perjalanan Anda</p>
            </div>
            <div class="row g-5">
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="step-circle">1</div>
                        <h5 class="step-title">Daftar</h5>
                        <p class="step-description">Buat akun gratis sebagai relawan atau organisasi dalam hitungan menit</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="step-circle">2</div>
                        <h5 class="step-title">Cari Acara</h5>
                        <p class="step-description">Jelajahi berbagai acara sosial yang sesuai dengan minat dan keahlian Anda</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="step-circle">3</div>
                        <h5 class="step-title">Daftar</h5>
                        <p class="step-description">Daftarkan diri ke acara pilihan dan tunggu konfirmasi dari penyelenggara</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="text-center">
                        <div class="step-circle">4</div>
                        <h5 class="step-title">Kontribusi</h5>
                        <p class="step-description">Ikuti acara, buat dampak positif, dan dapatkan sertifikat apresiasi</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta-section">
        <div class="container text-center">
            <h2 class="cta-title">Siap Membuat Perbedaan?</h2>
            <p class="cta-description">Bergabunglah dengan ribuan relawan yang telah menciptakan dampak positif di masyarakat</p>
            <a href="{{ route('register') }}" class="btn btn-hero btn-hero-primary">
                <i class="bi-rocket-takeoff-fill me-2"></i>Mulai Sekarang
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <h5 class="footer-title">
                        <i class="bi-heart-fill" style="color: #3950A2;"></i> Volunteer Management
                    </h5>
                    <p class="footer-link" style="cursor: default;">Platform terpercaya untuk mengelola relawan dan acara sosial dengan mudah dan efisien.</p>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">Quick Links</h5>
                    <a href="{{ url('/list-laporan') }}" class="footer-link">Acara</a>
                    <a href="{{ route('login') }}" class="footer-link">Login</a>
                    <a href="{{ route('register') }}" class="footer-link">Register</a>
                </div>
                <div class="col-lg-2 col-md-6">
                    <h5 class="footer-title">Tentang</h5>
                    <a href="#" class="footer-link">Tim Kami</a>
                    <a href="#" class="footer-link">Karir</a>
                    <a href="#" class="footer-link">Blog</a>
                </div>
                <div class="col-lg-3 col-md-6">
                    <h5 class="footer-title">Kontak</h5>
                    <a href="mailto:support@volunteer.com" class="footer-link">
                        <i class="bi-envelope-fill me-2"></i>support@volunteer.com
                    </a>
                    <a href="tel:+6281234567890" class="footer-link">
                        <i class="bi-telephone-fill me-2"></i>+62 812 3456 7890
                    </a>
                </div>
            </div>
            <hr style="border-color: var(--border-color); margin: 2.5rem 0 1.5rem;">
            <div class="text-center" style="color: var(--text-secondary);">
                <p>&copy; 2024 Volunteer Management System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const icon = document.getElementById('theme-icon');
            const currentTheme = html.getAttribute('data-theme');

            if (currentTheme === 'dark') {
                html.removeAttribute('data-theme');
                icon.classList.remove('bi-moon-stars-fill');
                icon.classList.add('bi-sun-fill');
                localStorage.setItem('theme', 'light');
            } else {
                html.setAttribute('data-theme', 'dark');
                icon.classList.remove('bi-sun-fill');
                icon.classList.add('bi-moon-stars-fill');
                localStorage.setItem('theme', 'dark');
            }
        }

        // Load saved theme
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme');
            const icon = document.getElementById('theme-icon');

            if (savedTheme === 'dark') {
                document.documentElement.setAttribute('data-theme', 'dark');
                icon.classList.remove('bi-sun-fill');
                icon.classList.add('bi-moon-stars-fill');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>

</html>