<?php
session_start();
include 'koneksi.php';

// 1. Cek Login & Admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$id_saya = $_SESSION['id'];

// 2. Logika Hapus Nasabah
if (isset($_GET['hapus'])) {
    $id_hapus = $_GET['hapus'];
    if ($id_hapus != $id_saya) {
        mysqli_query($conn, "DELETE FROM transaksi WHERE user_id='$id_hapus'");
        $hapus = mysqli_query($conn, "DELETE FROM users WHERE id='$id_hapus'");
        if ($hapus) {
            $notif = "hapus_sukses";
        }
    }
}

// 3. Logika Ubah Role
if (isset($_GET['ubah_role']) && isset($_GET['ke'])) {
    $id_target = $_GET['ubah_role'];
    $role_baru = $_GET['ke'];
    if ($id_target != $id_saya) {
        mysqli_query($conn, "UPDATE users SET role='$role_baru' WHERE id='$id_target'");
        $notif = "role_sukses";
    }
}

// 4. Ambil Data Nasabah
$query = mysqli_query($conn, "SELECT * FROM users ORDER BY id DESC");
$nasabah_data = [];
while ($row = mysqli_fetch_assoc($query)) {
    $nasabah_data[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Nasabah - Bank Sampah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #FF6B35;
            --dark-bg: #0F172A;
            --card-bg: #1E293B;
            --sidebar-bg: #111827;
            --text-light: #F8FAFC;
            --text-gray: #CBD5E1;
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
            transition: 0.3s;
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
            color: #94A3B8;
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

        .main-content {
            margin-left: 260px;
            padding: 30px;
            transition: 0.3s;
        }

        .top-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        /* TABLE */
        .table-card {
            background: var(--card-bg);
            border-radius: 20px;
            padding: 0;
            border: 1px solid var(--border-color);
            overflow: hidden;
        }

        .table {
            margin-bottom: 0;
            color: var(--text-light);
            vertical-align: middle;
        }

        .table th {
            background: rgba(0, 0, 0, 0.2);
            color: #94A3B8;
            padding: 18px 25px;
            font-size: 0.75rem;
            text-transform: uppercase;
        }

        .table td {
            padding: 15px 25px;
            border-bottom: 1px solid var(--border-color);
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: rgba(255, 107, 53, 0.1);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            border: 1px solid rgba(255, 107, 53, 0.2);
            flex-shrink: 0;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.03);
            color: #94A3B8;
            text-decoration: none;
            transition: 0.2s;
        }

        .btn-delete:hover {
            background: rgba(239, 68, 68, 0.2);
            color: #F87171;
        }

        /* CUSTOM SWEETALERT MOBILE */
        @media (max-width: 768px) {
            .swal2-popup {
                width: 80% !important;
                padding: 1rem !important;
                border-radius: 20px !important;
                background: var(--card-bg) !important;
                color: white !important;
            }

            .swal2-title {
                font-size: 1.1rem !important;
                margin-bottom: 5px !important;
            }

            .swal2-html-container {
                font-size: 0.8rem !important;
            }

            .swal2-icon {
                transform: scale(0.6);
                margin: 5px auto !important;
            }

            .swal2-styled {
                padding: 7px 15px !important;
                font-size: 0.8rem !important;
            }
        }

        /* RESPONSIVE */
        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 15px;
            }

            .overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .overlay.active {
                display: block;
            }

            .desktop-view {
                display: none;
            }

            .mobile-view {
                display: block;
            }

            /* Card Mobile yang Padat */
            .mobile-card {
                background: var(--card-bg);
                border-radius: 12px;
                padding: 12px;
                margin-bottom: 10px;
                border: 1px solid var(--border-color);
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
            <div class="d-flex align-items-center"><i class="fas fa-recycle text-warning me-2"></i> Bank<span style="color:var(--primary)">Sampah</span></div>
            <button class="btn btn-sm text-white d-lg-none" onclick="toggleSidebar()"><i class="fas fa-times"></i></button>
        </div>
        <nav class="nav flex-column">
            <small class="fw-bold mb-2 px-3" style="font-size: 0.75rem; color: #64748B;">MENU UTAMA</small>
            <a href="admin_dashboard.php" class="nav-link"><i class="fas fa-th-large"></i> Dashboard</a>
            <a href="admin_transaksi.php" class="nav-link"><i class="fas fa-exchange-alt"></i> Transaksi</a>
            <a href="admin_harga.php" class="nav-link"><i class="fas fa-tags"></i> Kelola Harga</a>
            <a href="admin_nasabah.php" class="nav-link active"><i class="fas fa-users"></i> Data Nasabah</a>

            <small class="fw-bold mb-2 px-3 mt-4" style="font-size: 0.75rem; color: #64748B;">LAINNYA</small>
            <a href="logout.php" class="nav-link text-danger"><i class="fas fa-sign-out-alt"></i> Keluar</a>
        </nav>
    </div>

    <div class="main-content">
        <div class="top-header">
            <div class="d-flex align-items-center">
                <button class="btn btn-dark btn-sm d-lg-none me-3" onclick="toggleSidebar()"><i class="fas fa-bars"></i></button>
                <h4 class="fw-bold m-0 text-white" style="font-size: 1.1rem;">Data Nasabah</h4>
            </div>
        </div>

        <div class="desktop-view table-card">
            <table class="table">
                <thead>
                    <tr>
                        <th class="ps-4">Nama & Role</th>
                        <th>Kontak</th>
                        <th>Saldo</th>
                        <th class="text-end pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($nasabah_data as $row) : $inisial = strtoupper(substr($row['nama'], 0, 1));
                        $is_admin = ($row['role'] === 'admin'); ?>
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="user-avatar me-3"><?php echo $inisial; ?></div>
                                    <div>
                                        <h6 class="fw-bold text-white mb-0" style="font-size: 0.9rem;"><?php echo $row['nama']; ?></h6>
                                        <small style="color:var(--primary); font-weight:800; text-transform:uppercase; font-size:10px;"><?php echo $row['role']; ?></small>
                                    </div>
                                </div>
                            </td>
                            <td style="color: var(--text-gray); font-size: 0.8rem;"><?php echo $row['email']; ?><br><?php echo $row['no_hp']; ?></td>
                            <td><span style="color:#34D399; font-weight:bold; font-size: 0.85rem;">Rp <?php echo number_format($row['saldo'], 0, ',', '.'); ?></span></td>
                            <td class="text-end pe-4">
                                <?php if ($row['id'] != $id_saya) : ?>
                                    <a href="#" onclick="confirmRole(<?php echo $row['id']; ?>, '<?php echo $is_admin ? 'user' : 'admin'; ?>')" class="btn-action me-1"><i class="fas <?php echo $is_admin ? 'fa-user-minus' : 'fa-user-shield'; ?>"></i></a>
                                    <a href="#" onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn-action btn-delete"><i class="fas fa-trash-alt"></i></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="mobile-view">
            <?php foreach ($nasabah_data as $row) : $inisial = strtoupper(substr($row['nama'], 0, 1));
                $is_admin = ($row['role'] === 'admin'); ?>
                <div class="mobile-card">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center overflow-hidden">
                            <div class="user-avatar me-2" style="width:35px; height:35px; font-size:0.85rem;"><?php echo $inisial; ?></div>
                            <div class="overflow-hidden">
                                <h6 class="fw-bold text-white m-0 text-truncate" style="font-size: 0.85rem; max-width: 140px;"><?php echo $row['nama']; ?></h6>
                                <small style="color:var(--primary); font-weight:800; font-size:9px;"><?php echo $row['role']; ?></small>
                            </div>
                        </div>
                        <div class="d-flex gap-2">
                            <?php if ($row['id'] != $id_saya) : ?>
                                <a href="#" onclick="confirmRole(<?php echo $row['id']; ?>, '<?php echo $is_admin ? 'user' : 'admin'; ?>')" class="btn-action"><i class="fas <?php echo $is_admin ? 'fa-user-minus' : 'fa-user-shield'; ?>"></i></a>
                                <a href="#" onclick="confirmDelete(<?php echo $row['id']; ?>)" class="btn-action btn-delete"><i class="fas fa-trash-alt text-danger"></i></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div style="color: var(--text-gray); font-size: 0.7rem; border-top: 1px solid var(--border-color); margin-top: 8px; padding-top: 8px;">
                        <?php echo $row['email']; ?> | <?php echo $row['no_hp']; ?>
                        <span class="float-end text-success fw-bold">Rp <?php echo number_format($row['saldo'], 0, ',', '.'); ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            document.getElementById('sidebar').classList.toggle('active');
            document.getElementById('overlay').classList.toggle('active');
        }

        function confirmDelete(id) {
            Swal.fire({
                title: 'Hapus?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                background: '#1E293B',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = 'admin_nasabah.php?hapus=' + id;
            })
        }

        function confirmRole(id, ke) {
            Swal.fire({
                title: 'Ubah Role?',
                text: "Ubah menjadi " + ke,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#FF6B35',
                background: '#1E293B',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) window.location.href = `admin_nasabah.php?ubah_role=${id}&ke=${ke}`;
            })
        }
    </script>
</body>

</html>