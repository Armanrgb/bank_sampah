<?php
session_start();
include 'koneksi.php';

// Cek Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Variabel Notifikasi
$status_sukses = false;
$status_gagal = false;

// Logika Update Harga
if (isset($_POST['update_harga'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $harga_baru = mysqli_real_escape_string($conn, $_POST['harga']);

    $update = mysqli_query($conn, "UPDATE jenis_sampah SET harga_per_kg = '$harga_baru' WHERE id = '$id'");

    if ($update) {
        $status_sukses = true;
    } else {
        $status_gagal = true;
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Harga - Bank Sampah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #FF6B35;
            --primary-hover: #e85d2a;
            --dark-bg: #0F172A;
            --card-bg: #1E293B;
            --sidebar-bg: #111827;
            --text-light: #F8FAFC;
            --text-gray: #94A3B8;
            --border-color: rgba(255, 255, 255, 0.08);
            --input-bg: #0f172a;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-light);
            overflow-x: hidden;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            width: 260px;
            height: 100vh;
            background: var(--sidebar-bg);
            position: fixed;
            top: 0;
            left: 0;
            padding: 25px;
            border-right: 1px solid var(--border-color);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
        }

        .sidebar .brand {
            font-size: 1.2rem;
            font-weight: 800;
            color: white;
            margin-bottom: 40px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .nav-link {
            color: var(--text-gray);
            padding: 14px 18px;
            border-radius: 12px;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            text-decoration: none;
            transition: 0.3s;
            font-weight: 500;
            font-size: 0.95rem;
        }

        .nav-link:hover {
            background: rgba(255, 255, 255, 0.03);
            color: white;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(255, 107, 53, 0.15), transparent);
            color: var(--primary);
            border-left: 3px solid var(--primary);
            border-radius: 4px 12px 12px 4px;
        }

        .nav-link i {
            width: 25px;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        /* --- CONTENT WRAPPER --- */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            transition: all 0.3s;
        }

        /* --- HEADER --- */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        /* --- PRICE CARD STYLE (DARK MODE) --- */
        .price-card {
            border: 1px solid var(--border-color);
            border-radius: 20px;
            background: var(--card-bg);
            transition: all 0.3s ease;
            overflow: hidden;
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Aksen warna di atas kartu */
        .price-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            opacity: 0.8;
        }

        .price-card.green::before {
            background: #10B981;
        }

        .price-card.blue::before {
            background: #3B82F6;
        }

        .price-card.orange::before {
            background: #F97316;
        }

        .price-card.purple::before {
            background: #8B5CF6;
        }

        .price-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255, 255, 255, 0.2);
            box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.5);
        }

        .icon-circle {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            margin-bottom: 15px;
        }

        /* Warna icon dengan opacity */
        .bg-soft-green {
            background: rgba(16, 185, 129, 0.15);
            color: #34D399;
        }

        .bg-soft-blue {
            background: rgba(59, 130, 246, 0.15);
            color: #60A5FA;
        }

        .bg-soft-orange {
            background: rgba(249, 115, 22, 0.15);
            color: #FB923C;
        }

        .bg-soft-purple {
            background: rgba(139, 92, 246, 0.15);
            color: #A78BFA;
        }

        /* Input Harga */
        .form-control-price {
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-radius: 10px;
            padding: 10px;
            font-weight: 700;
            color: white;
            text-align: center;
            font-size: 1rem;
            transition: 0.3s;
        }

        .form-control-price:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(255, 107, 53, 0.2);
            outline: none;
        }

        .btn-save {
            font-size: 0.9rem;
            padding: 10px;
            border-radius: 10px;
            font-weight: 600;
            background: var(--primary);
            border: none;
            color: white;
            margin-top: 10px;
            transition: 0.3s;
        }

        .btn-save:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        /* --- SWEETALERT DARK THEME --- */
        div:where(.swal2-popup) {
            background: var(--card-bg) !important;
            color: var(--text-light) !important;
            border: 1px solid var(--border-color);
        }

        div:where(.swal2-title) {
            color: white !important;
        }

        /* --- MOBILE ELEMENTS --- */
        .btn-toggle-sidebar {
            display: none;
            background: var(--card-bg);
            color: white;
            border: 1px solid var(--border-color);
            padding: 8px 12px;
            border-radius: 10px;
            margin-right: 15px;
        }

        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(3px);
            z-index: 999;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
                box-shadow: 10px 0 20px rgba(0, 0, 0, 0.5);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
            }

            .btn-toggle-sidebar {
                display: inline-block;
            }

            .overlay.active {
                display: block;
            }

            .top-header h4 {
                font-size: 1.2rem;
            }

            /* Sembunyikan teks 'Update' di tombol HP agar muat */
            .btn-save span {
                display: none;
            }

            .btn-save i {
                margin: 0;
            }
        }
    </style>
</head>

<body>

    <div class="overlay" id="overlay" onclick="toggleSidebar()"></div>

    <div class="sidebar" id="sidebar">
        <div class="brand">
            <div class="d-flex align-items-center">
                <i class="fas fa-recycle text-warning me-2"></i> Bank<span style="color:var(--primary)">Sampah</span>
            </div>
            <button class="btn btn-sm text-white d-lg-none" onclick="toggleSidebar()"><i class="fas fa-times"></i></button>
        </div>

        <nav class="nav flex-column">
            <small class="fw-bold mb-2 px-3" style="font-size: 0.75rem; letter-spacing: 1px; color: #64748B;">MENU UTAMA</small>

            <a href="admin_dashboard.php" class="nav-link">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="admin_transaksi.php" class="nav-link">
                <i class="fas fa-exchange-alt"></i> Transaksi
            </a>
            <a href="admin_harga.php" class="nav-link active">
                <i class="fas fa-tags"></i> Kelola Harga
            </a>
            <a href="admin_nasabah.php" class="nav-link">
                <i class="fas fa-users"></i> Data Nasabah
            </a>

            <small class="fw-bold mb-2 px-3 mt-4" style="font-size: 0.75rem; letter-spacing: 1px; color: #64748B;">LAINNYA</small>
            <a href="logout.php" class="nav-link text-danger">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </a>
        </nav>
    </div>

    <div class="main-content">

        <div class="top-header">
            <div class="d-flex align-items-center">
                <button class="btn-toggle-sidebar" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </button>
                <div>
                    <h4 class="fw-bold m-0 text-white">Kelola Harga Sampah</h4>
                    <p class="m-0 small d-none d-sm-block" style="color: #94A3B8;">Update harga pasar sampah per kilogram.</p>
                </div>
            </div>

            <a href="admin_dashboard.php" class="btn btn-outline-light btn-sm rounded-pill px-3" style="border-color: var(--border-color);">
                <i class="fas fa-arrow-left"></i> <span class="d-none d-md-inline ms-2">Kembali</span>
            </a>
        </div>

        <div class="row g-3">
            <?php
            // Array warna untuk variasi kartu
            $colors = ['bg-soft-green', 'bg-soft-blue', 'bg-soft-orange', 'bg-soft-purple'];
            $border_class = ['green', 'blue', 'orange', 'purple'];
            $i = 0;

            $query = mysqli_query($conn, "SELECT * FROM jenis_sampah");
            while ($row = mysqli_fetch_assoc($query)) {
                $idx = $i % count($colors); // Loop warna agar tidak habis
                $bg_color = $colors[$idx];
                $card_border = $border_class[$idx];
                $i++;
            ?>
                <div class="col-6 col-md-4 col-lg-3">
                    <div class="price-card p-4 text-center <?php echo $card_border; ?>">

                        <div class="icon-circle <?php echo $bg_color; ?> mx-auto">
                            <i class="fas <?php echo $row['icon']; ?>"></i>
                        </div>

                        <h6 class="fw-bold text-white mb-1" style="font-size: 1rem;"><?php echo $row['nama_sampah']; ?></h6>
                        <p class="text-muted small mb-3" style="font-size: 0.75rem;">
                            Saat ini: <strong class="text-warning">Rp <?php echo number_format($row['harga_per_kg'], 0, ',', '.'); ?></strong> /kg
                        </p>

                        <form method="POST" class="mt-auto w-100">
                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

                            <div class="mb-2">
                                <input type="number" name="harga" class="form-control form-control-price"
                                    value="<?php echo $row['harga_per_kg']; ?>" required placeholder="0">
                            </div>

                            <button type="submit" name="update_harga" class="btn btn-save w-100">
                                <i class="fas fa-save"></i> <span class="ms-1">Update</span>
                            </button>
                        </form>

                    </div>
                </div>
            <?php } ?>
        </div>

    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <?php if ($status_sukses): ?>
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: 'Harga berhasil diperbarui.',
                icon: 'success',
                confirmButtonText: 'Oke',
                confirmButtonColor: '#FF6B35',
                background: '#1E293B',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location = 'admin_harga.php';
                }
            });
        </script>
    <?php endif; ?>

    <?php if ($status_gagal): ?>
        <script>
            Swal.fire({
                title: 'Gagal!',
                text: 'Terjadi kesalahan sistem.',
                icon: 'error',
                confirmButtonText: 'Coba Lagi',
                background: '#1E293B',
                color: '#fff'
            });
        </script>
    <?php endif; ?>

</body>

</html>