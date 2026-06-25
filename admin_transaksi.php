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

// 2. Ambil Data Transaksi (Disimpan dalam array agar bisa diloop 2 kali untuk Desktop & Mobile)
$query = mysqli_query($conn, "SELECT t.*, u.nama FROM transaksi t JOIN users u ON t.user_id = u.id ORDER BY t.id DESC");
$transaksi_data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $transaksi_data[] = $row;
}
$total_transaksi = count($transaksi_data);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Transaksi - Bank Sampah</title>

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
            --text-gray: #94A3B8;
            /* Warna abu terang agar terbaca di dark mode */
            --border-color: rgba(255, 255, 255, 0.08);
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

        /* --- CONTENT --- */
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

        /* --- TABLE CARD (DESKTOP) --- */
        .table-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid var(--border-color);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .table {
            color: var(--text-light);
            margin-bottom: 0;
            white-space: nowrap;
        }

        .table th {
            background: rgba(255, 255, 255, 0.03);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-gray);
            padding: 15px;
            border-bottom: 1px solid var(--border-color);
            border-top: none;
        }

        .table td {
            padding: 15px;
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid var(--border-color);
            background: transparent;
            color: var(--text-light);
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table-hover tbody tr:hover td {
            background: rgba(255, 255, 255, 0.02);
        }

        /* --- MOBILE LIST CARD (HP VIEW) --- */
        .mobile-list-item {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mobile-icon {
            width: 45px;
            height: 45px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
            margin-right: 15px;
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

            /* Sembunyikan Tabel Desktop di HP */
            .desktop-view {
                display: none;
            }

            /* Tampilkan List Mobile di HP */
            .mobile-view {
                display: block;
            }
        }

        @media (min-width: 992px) {
            .desktop-view {
                display: block;
            }

            .mobile-view {
                display: none;
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
            <a href="admin_transaksi.php" class="nav-link active">
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
                    <h4 class="fw-bold m-0 text-white">Kelola Transaksi</h4>
                    <p class="m-0 small d-none d-sm-block" style="color: #94A3B8;">Daftar semua riwayat transaksi nasabah.</p>
                </div>
            </div>

            <div class="d-flex align-items-center bg-dark bg-opacity-50 px-3 py-2 rounded-pill border border-secondary border-opacity-25">
                <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($me['nama']); ?>&background=FF6B35&color=fff&rounded=true" class="rounded-circle me-2" width="30">
                <div class="d-none d-sm-block lh-1 text-end">
                    <div class="fw-bold small text-white"><?php echo explode(' ', $me['nama'])[0]; ?></div>
                    <div class="text-muted" style="font-size: 0.7rem;">Administrator</div>
                </div>
            </div>
        </div>

        <div class="desktop-view table-card">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="fw-bold m-0 text-white">Riwayat Transaksi</h5>
                <span class="badge bg-secondary bg-opacity-25 text-secondary border border-secondary border-opacity-25 px-3 py-2 rounded-pill">Total: <?php echo $total_transaksi; ?></span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th class="ps-3 border-0 rounded-start">No</th>
                            <th class="border-0">Nasabah</th>
                            <th class="border-0">Tipe</th>
                            <th class="border-0">Keterangan</th>
                            <th class="border-0">Jumlah</th>
                            <th class="pe-3 border-0 rounded-end text-end">Waktu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        if (count($transaksi_data) > 0) {
                            foreach ($transaksi_data as $row) {
                                if ($row['tipe'] == 'setor') {
                                    $badge = '<span class="badge rounded-pill px-3 py-2" style="background: rgba(16, 185, 129, 0.2); color: #34D399;">Setor Sampah</span>';
                                    $val = $row['berat'] . ' Kg';
                                    $val_class = 'text-white';
                                } else {
                                    $badge = '<span class="badge rounded-pill px-3 py-2" style="background: rgba(239, 68, 68, 0.2); color: #F87171;">Tarik Saldo</span>';
                                    $val = 'Rp ' . number_format($row['jumlah']);
                                    $val_class = 'text-warning';
                                }
                        ?>
                                <tr>
                                    <td class="ps-3 text-secondary" style="width: 50px;"><?php echo $no++; ?></td>
                                    <td class="fw-bold text-white text-truncate" style="max-width: 150px;"><?php echo $row['nama']; ?></td>
                                    <td><?php echo $badge; ?></td>
                                    <td class="text-secondary text-truncate" style="max-width: 200px;"><?php echo $row['keterangan']; ?></td>
                                    <td class="fw-bold <?php echo $val_class; ?>"><?php echo $val; ?></td>
                                    <td class="pe-3 text-secondary text-end" style="font-size: 0.85rem;">
                                        <?php echo date('d M Y, H:i', strtotime($row['tanggal'])); ?>
                                    </td>
                                </tr>
                        <?php }
                        } else {
                            echo '<tr><td colspan="6" class="text-center text-secondary py-5">Belum ada data.</td></tr>';
                        } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mobile-view">
            <h5 class="fw-bold text-white mb-3">Daftar Transaksi</h5>
            <?php
            if (count($transaksi_data) > 0) {
                foreach ($transaksi_data as $row) {
                    $is_setor = ($row['tipe'] == 'setor');
                    // Style Icon & Teks untuk Mobile
                    if ($is_setor) {
                        $icon = 'fa-leaf';
                        $icon_bg = 'background: rgba(16, 185, 129, 0.15); color: #34D399;';
                        $val = '+ ' . $row['berat'] . ' Kg';
                        $val_class = 'text-success';
                        $label = 'Setor Sampah';
                    } else {
                        $icon = 'fa-arrow-up';
                        $icon_bg = 'background: rgba(239, 68, 68, 0.15); color: #F87171;';
                        $val = '- Rp ' . number_format($row['jumlah']);
                        $val_class = 'text-warning';
                        $label = 'Tarik Saldo';
                    }
            ?>
                    <div class="mobile-list-item">
                        <div class="d-flex align-items-center overflow-hidden">
                            <div class="mobile-icon" style="<?php echo $icon_bg; ?>">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>
                            <div class="overflow-hidden">
                                <h6 class="fw-bold text-white m-0 text-truncate"><?php echo $row['nama']; ?></h6>
                                <small class="text-gray" style="font-size: 0.75rem;">
                                    <?php echo date('d M, H:i', strtotime($row['tanggal'])); ?> &bull; <span class="text-secondary"><?php echo $label; ?></span>
                                </small>
                            </div>
                        </div>
                        <div class="text-end flex-shrink-0 ms-2">
                            <span class="fw-bold <?php echo $val_class; ?>" style="font-size: 0.9rem;"><?php echo $val; ?></span>
                        </div>
                    </div>
            <?php }
            } else {
                echo '<div class="text-center text-secondary py-5">Belum ada data.</div>';
            } ?>
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