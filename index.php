<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bank Sampah - Platform Digital</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #FF6B35;
            --primary-hover: #e85d2a;
            --dark-bg: #0F172A;
            --card-bg: #1E293B;
            --text-light: #F8FAFC;
            --text-gray: #94A3B8;
        }

        body {
            font-family: 'Outfit', sans-serif;
            color: var(--text-light);
            background-color: var(--dark-bg);
            overflow-x: hidden;
            margin: 0;
        }

        /* =========================================
           1. PRELOADER (LOADING SCREEN) - DIPERBAIKI
           ========================================= */
        #preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            /* Background Solid + Gradient Halus */
            background-color: var(--dark-bg);
            background-image: radial-gradient(circle at center, #1e293b 0%, #0F172A 100%);
            z-index: 99999;
            /* Paling depan */

            /* Flexbox untuk menengahkan konten */
            display: flex;
            flex-direction: column;
            justify-content: center;
            /* Vertikal Center */
            align-items: center;
            /* Horizontal Center */
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }

        .loader-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            /* Sedikit naik ke atas agar pas di mata */
            margin-top: -40px;
        }

        .loader-logo img {
            width: 110px;
            /* Ukuran Logo Pas */
            height: auto;
            object-fit: contain;
            margin-bottom: 5px;
            /* Jarak ke teks sangat dekat */
            animation: pulse 2s infinite ease-in-out;
        }

        .loader-tagline {
            color: var(--text-gray);
            font-size: 0.9rem;
            letter-spacing: 0.5px;
            font-weight: 400;
            margin-bottom: 20px;
            /* Jarak ke spinner dirapatkan */
            opacity: 0.8;
            text-align: center;
        }

        .loader-spinner {
            width: 30px;
            /* Spinner diperkecil sedikit */
            height: 30px;
            border: 3px solid rgba(255, 255, 255, 0.1);
            border-top: 3px solid var(--primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loader-footer {
            position: absolute;
            bottom: 30px;
            width: 100%;
            text-align: center;
            color: rgba(255, 255, 255, 0.15);
            font-size: 0.7rem;
            font-family: monospace;
            letter-spacing: 2px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }

            100% {
                transform: scale(1);
            }
        }

        /* =========================================
           2. NAVBAR
           ========================================= */
        .navbar {
            padding: 15px 0;
            transition: all 0.3s ease;
        }

        .navbar-container {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(10px);
            border-radius: 50px;
            padding: 8px 20px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            font-weight: 800;
            font-size: 1.1rem;
            color: #fff !important;
        }

        .nav-link {
            color: var(--text-gray) !important;
            margin: 0 10px;
            font-size: 0.9rem;
        }

        .nav-link:hover,
        .nav-link.active {
            color: var(--primary) !important;
        }

        .btn-login {
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: 6px 18px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 0.85rem;
            margin-right: 8px;
        }

        .btn-register {
            background: var(--primary);
            color: white;
            padding: 6px 18px;
            border-radius: 30px;
            text-decoration: none;
            font-size: 0.85rem;
            border: none;
        }

        @media (max-width: 991px) {
            .navbar-container {
                border-radius: 16px;
                flex-wrap: wrap;
                padding: 12px;
            }

            .navbar-collapse {
                width: 100%;
                margin-top: 15px;
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                padding-top: 10px;
            }

            .nav-link {
                display: block;
                padding: 10px 0;
                text-align: center;
            }

            .d-flex-mobile {
                display: flex;
                justify-content: center;
                margin-top: 15px;
                gap: 10px;
            }

            .btn-login,
            .btn-register {
                width: 45%;
                text-align: center;
                margin: 0;
                padding: 10px;
            }
        }

        /* =========================================
           3. HERO SECTION (TAMPILAN UTAMA)
           ========================================= */
        .hero-section {
            /* Jarak atas disesuaikan agar tidak terlalu turun */
            padding: 130px 0 60px;
            text-align: center;
            background: radial-gradient(circle at top, #1e293b 0%, #0F172A 70%);
        }

        .hero-badge {
            background: rgba(255, 255, 255, 0.1);
            color: var(--text-light);
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.75rem;
            letter-spacing: 1px;
            margin-bottom: 20px;
            display: inline-block;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .hero-title {
            font-size: 3.5rem;
            /* Default Desktop */
            font-weight: 800;
            color: white;
            margin-bottom: 20px;
            line-height: 1.1;
        }

        .hero-desc {
            color: var(--text-gray);
            margin-bottom: 35px;
            font-size: 1.1rem;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            line-height: 1.6;
        }

        /* Stats Desktop */
        .stats-box {
            margin-top: 50px;
            display: flex;
            justify-content: center;
            gap: 30px;
            flex-wrap: wrap;
        }

        .stat-item {
            background: rgba(255, 255, 255, 0.03);
            padding: 20px 40px;
            border-radius: 15px;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .stat-num {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
        }

        .stat-label {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-gray);
        }

        /* =========================================
           4. MEDIA QUERY KHUSUS HP (FIX KEGEDEAN)
           ========================================= */
        @media (max-width: 576px) {
            .hero-section {
                padding-top: 110px;
                /* Naikkan konten ke atas */
                padding-bottom: 40px;
            }

            .hero-title {
                font-size: 2.2rem;
                /* Kecilkan Font Judul (dari 3.5rem ke 2.2rem) */
            }

            .hero-desc {
                font-size: 0.95rem;
                /* Kecilkan Font Deskripsi */
                margin-bottom: 25px;
                padding: 0 10px;
            }

            /* Tombol Hero Vertikal & Full Width */
            .hero-btns {
                flex-direction: column;
                gap: 12px !important;
            }

            .btn-hero {
                width: 100%;
                padding: 12px 0;
                /* Padding tombol disesuaikan */
                font-size: 1rem;
            }

            /* Stats di HP */
            .stats-box {
                flex-direction: row;
                /* Tetap sejajar kiri-kanan jika muat */
                gap: 15px;
                margin-top: 30px;
            }

            .stat-item {
                flex: 1;
                /* Bagi dua rata */
                padding: 15px 10px;
                width: auto;
            }

            .stat-num {
                font-size: 1.5rem;
            }

            .stat-label {
                font-size: 0.65rem;
            }
        }

        /* Footer */
        footer {
            border-top: 1px solid rgba(255, 255, 255, 0.05);
            padding: 50px 0 30px;
            background: #0b1120;
            font-size: 0.9rem;
        }

        footer a {
            color: var(--text-gray);
            text-decoration: none;
            display: block;
            margin-bottom: 10px;
        }

        footer a:hover {
            color: var(--primary);
        }
    </style>
</head>

<body>

    <div id="preloader">
        <div class="loader-content">
            <div class="loader-logo">
                <img src="logo.png" alt="Logo Bank Sampah">
            </div>

            <div class="loader-tagline">Ubah Sampah Jadi Rupiah</div>

            <div class="loader-spinner"></div>
        </div>

        <div class="loader-footer">

        </div>
    </div>

    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <div class="navbar-container">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-recycle text-warning me-2"></i>Bank<span style="color:var(--primary)">Sampah</span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mobileMenu" style="border:none; color:white;">
                    <i class="fas fa-bars fs-3"></i>
                </button>
                <div class="collapse navbar-collapse" id="mobileMenu">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link active" href="#">Beranda</a></li>
                        <li class="nav-item"><a class="nav-link" href="#fitur">Layanan</a></li>
                        <li class="nav-item"><a class="nav-link" href="#cara">Proses</a></li>
                    </ul>
                    <div class="d-flex-mobile">
                        <a href="login.php" class="btn-login">Masuk</a>
                        <a href="register.php" class="btn-register">Daftar</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div data-aos="fade-up">
                <div class="hero-badge">Kelompok Aldi</div>

                <h1 class="hero-title">Kelola Sampah,<br>Ciptakan Rupiah.</h1>

                <p class="hero-desc">Bergabunglah dengan revolusi hijau. Sistem manajemen sampah pintar yang mengubah limbah rumah tangga menjadi saldo digital.</p>

                <div class="d-flex justify-content-center gap-3 hero-btns">
                    <a href="register.php" class="btn btn-hero fw-bold text-white rounded-pill px-4" style="background:var(--primary);">Gabung Sekarang</a>
                    <a href="#cara" class="btn btn-hero fw-bold text-white border border-secondary rounded-pill px-4">Cara Kerja</a>
                </div>
            </div>

            <div class="stats-box" data-aos="fade-up" data-aos-delay="200">
                <div class="stat-item">
                    <div class="stat-num">2.5K+</div>
                    <div class="stat-label">Pengguna Aktif</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">50 T</div>
                    <div class="stat-label">Sampah Didaur Ulang</div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-5">
        <div class="container py-4">
            <div class="row g-4">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-dark p-4 rounded-4 border border-secondary border-opacity-10 h-100">
                        <i class="fas fa-wallet fs-2 text-primary mb-3"></i>
                        <h4 class="fw-bold text-white">Tukar Jadi Uang</h4>
                        <p class="text-gray small m-0">Setor sampah, dapatkan poin yang bisa dicairkan ke saldo E-Wallet kapan saja.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-dark p-4 rounded-4 border border-secondary border-opacity-10 h-100">
                        <i class="fas fa-chart-line fs-2 text-primary mb-3"></i>
                        <h4 class="fw-bold text-white">Harga Transparan</h4>
                        <p class="text-gray small m-0">Harga sampah selalu diperbarui mengikuti pasar agar Anda untung maksimal.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-dark p-4 rounded-4 border border-secondary border-opacity-10 h-100">
                        <i class="fas fa-users fs-2 text-primary mb-3"></i>
                        <h4 class="fw-bold text-white">Komunitas</h4>
                        <p class="text-gray small m-0">Bergabung dengan ribuan orang yang peduli terhadap lingkungan sekitar.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-5">
                    <h5 class="text-white fw-bold mb-3">BANKSAMPAH</h5>
                    <p class="text-gray small">Aplikasi pengelolaan sampah modern untuk masa depan yang lebih bersih.</p>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="text-white mb-3">Navigasi</h6>
                    <a href="#">Beranda</a>
                    <a href="#fitur">Layanan</a>
                </div>
                <div class="col-6 col-lg-2">
                    <h6 class="text-white mb-3">Akun</h6>
                    <a href="login.php">Masuk</a>
                    <a href="register.php">Daftar</a>
                </div>
            </div>
            <div class="text-center border-top border-secondary border-opacity-10 mt-5 pt-4 text-gray small">
                &copy; 2025 Bank Sampah Digital.
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            once: true
        });
        window.addEventListener("load", function() {
            const preloader = document.getElementById('preloader');
            // Waktu loading
            setTimeout(function() {
                preloader.style.opacity = '0';
                preloader.style.visibility = 'hidden';
            }, 2000);
        });
    </script>
</body>

</html>