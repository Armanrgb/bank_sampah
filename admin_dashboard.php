<?php
session_start();
include 'koneksi.php';

// Pastikan Timezone WIB
date_default_timezone_set('Asia/Jakarta');

// 1. Cek Login & Admin
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id_saya = $_SESSION['id'];
$cek_user = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id_saya'");
$me = mysqli_fetch_assoc($cek_user);

if ($me['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// 2. DATA STATISTIK
$total_user = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE role='user'"));

$query_berat = mysqli_query($conn, "SELECT SUM(berat) as total FROM transaksi WHERE tipe='setor'");
$data_berat = mysqli_fetch_assoc($query_berat);
$total_sampah = $data_berat['total'] ?? 0;

$query_tarik = mysqli_query($conn, "SELECT SUM(jumlah) as total FROM transaksi WHERE tipe='tarik'");
$data_tarik = mysqli_fetch_assoc($query_tarik);
$total_tarik = $data_tarik['total'] ?? 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Bank Sampah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #FF6B35;
            --primary-hover: #e85d2a;
            --dark-bg: #0F172A;
            --card-bg: #1E293B;
            --sidebar-bg: #111827;
            --text-light: #F8FAFC;
            /* PERBAIKAN: Warna text-gray dibuat lebih terang agar terbaca */
            --text-gray: #94A3B8;
            --text-subtle: #64748B;
            --border-color: rgba(255, 255, 255, 0.08);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-light);
            overflow-x: hidden;
        }

        /* SIDEBAR */
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
            background: rgba(255, 255, 255, 0.05);
            color: white;
            transform: translateX(5px);
        }

        .nav-link.active {
            background: linear-gradient(90deg, rgba(255, 107, 53, 0.2), transparent);
            color: var(--primary);
            border-left: 3px solid var(--primary);
            border-radius: 4px 12px 12px 4px;
        }

        .nav-link i {
            width: 25px;
            margin-right: 10px;
            font-size: 1.1rem;
        }

        /* MAIN CONTENT */
        .main-content {
            margin-left: 260px;
            padding: 30px;
            transition: all 0.3s;
        }

        /* HEADER */
        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        /* STAT CARD */
        .stat-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid var(--border-color);
            height: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            transition: 0.3s;
        }

        /* Label text di dalam card diperbaiki warnanya */
        .stat-label {
            color: #CBD5E1;
            /* Abu terang */
            font-size: 0.75rem;
            letter-spacing: 1px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
            display: block;
        }

        .stat-icon-box {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        /* LIST CARD */
        .list-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 30px;
            margin-top: 30px;
            border: 1px solid var(--border-color);
        }

        .transaction-item {
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 15px;
            margin-bottom: 15px;
        }

        .transaction-item:last-child {
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 0;
        }

        /* Text deskripsi transaksi diperbaiki */
        .trans-desc {
            color: #94A3B8;
            /* Abu medium terang */
            font-size: 0.8rem;
        }

        /* MOBILE & TABLET ELEMENTS */
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

        /* RESPONSIVE LOGIC */
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

            .stat-card {
                padding: 20px;
            }

            /* Memastikan teks tidak kepotong di HP */
            .top-header h4 {
                font-size: 1.2rem;
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

            <a href="admin_dashboard.php" class="nav-link active">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="admin_transaksi.php" class="nav-link">
                <i class="fas fa-exchange-alt"></i> Transaksi
            </a>
            <a href="admin_harga.php" class="nav-link">
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
                    <h4 class="fw-bold m-0 text-white">Dashboard Overview</h4>
                    <p class="m-0 small d-none d-sm-block" style="color: #94A3B8;">Pantau aktivitas bank sampah hari ini.</p>
                </div>
            </div>

            <div class="d-flex align-items-center bg-dark bg-opacity-50 px-3 py-2 rounded-pill border border-secondary border-opacity-25">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($me['nama']); ?>&background=FF6B35&color=fff&rounded=true" class="rounded-circle me-2" width="30">
                <div class="d-none d-sm-block lh-1 text-end">
                    <div class="fw-bold small text-white"><?php echo explode(' ', $me['nama'])[0]; ?></div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div>
                        <span class="stat-label">Total Nasabah</span>
                        <h2 class="fw-bold m-0 text-white"><?php echo $total_user; ?></h2>
                        <small class="text-success"><i class="fas fa-arrow-up"></i> User Aktif</small>
                    </div>
                    <div class="stat-icon-box" style="background: rgba(59, 130, 246, 0.15); color: #60A5FA;">
                        <i class="fas fa-users"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div>
                        <span class="stat-label">Sampah Terkumpul</span>
                        <h2 class="fw-bold m-0 text-white"><?php echo number_format($total_sampah, 1, ',', '.'); ?> <span class="fs-6 text-secondary">Kg</span></h2>
                        <small class="text-success"><i class="fas fa-recycle"></i> Terus bertambah</small>
                    </div>
                    <div class="stat-icon-box" style="background: rgba(16, 185, 129, 0.15); color: #34D399;">
                        <i class="fas fa-leaf"></i>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4">
                <div class="stat-card">
                    <div>
                        <span class="stat-label">Dana Dicairkan</span>
                        <h2 class="fw-bold m-0 text-white">Rp <?php echo number_format($total_tarik, 0, ',', '.'); ?></h2>
                        <small class="text-warning"><i class="fas fa-wallet"></i> Total payout</small>
                    </div>
                    <div class="stat-icon-box" style="background: rgba(245, 158, 11, 0.15); color: #FBBF24;">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="list-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0 text-white">Transaksi Terakhir</h5>
                <a href="admin_transaksi.php" class="btn btn-sm btn-outline-light rounded-pill px-3" style="font-size: 0.8rem; border-color: rgba(255,255,255,0.2);">Lihat Semua</a>
            </div>

            <div class="d-flex flex-column gap-3">
                <?php
                $query_last = mysqli_query($conn, "SELECT t.*, u.nama FROM transaksi t JOIN users u ON t.user_id = u.id ORDER BY t.id DESC LIMIT 5");

                if (mysqli_num_rows($query_last) > 0) {
                    while ($row = mysqli_fetch_assoc($query_last)) {
                        $is_setor = ($row['tipe'] == 'setor');

                        // Konfigurasi Tampilan
                        $icon = $is_setor ? 'fa-arrow-down' : 'fa-arrow-up';
                        $bg_icon = $is_setor ? 'background: rgba(16, 185, 129, 0.2); color: #34D399;' : 'background: rgba(239, 68, 68, 0.2); color: #F87171;';
                        $tanda = $is_setor ? '+' : '-';

                        // Format Nilai
                        if ($is_setor) {
                            $nilai = $row['berat'] . ' Kg';
                            $text_val = 'text-white';
                        } else {
                            $nilai = 'Rp ' . number_format($row['jumlah'], 0, ',', '.');
                            $text_val = 'text-warning';
                        }
                ?>

                        <div class="transaction-item d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center overflow-hidden">
                                <div class="rounded-circle d-flex align-items-center justify-content-center me-3 flex-shrink-0"
                                    style="width: 45px; height: 45px; <?php echo $bg_icon; ?>">
                                    <i class="fas <?php echo $icon; ?>"></i>
                                </div>

                                <div class="overflow-hidden">
                                    <h6 class="fw-bold m-0 text-white text-truncate" style="font-size: 0.95rem;">
                                        <?php echo $row['nama']; ?>
                                    </h6>
                                    <small class="trans-desc text-truncate d-block">
                                        <?php echo $row['keterangan']; ?> &bull; <span style="color: #64748B;"><?php echo date('d M, H:i', strtotime($row['tanggal'])); ?></span>
                                    </small>
                                </div>
                            </div>

                            <div class="text-end ps-2 flex-shrink-0">
                                <span class="fw-bold <?php echo $text_val; ?>" style="font-size: 0.95rem;">
                                    <?php echo $tanda; ?> <?php echo $nilai; ?>
                                </span>
                            </div>
                        </div>

                <?php
                    }
                } else {
                    echo '<div class="text-center small py-4" style="color: #64748B;">Belum ada transaksi.</div>';
                }
                ?>
            </div>
        </div>

    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>