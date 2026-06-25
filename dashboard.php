<?php
session_start();
include 'koneksi.php';

// 1. Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id_user = $_SESSION['id'];
$query_user = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id_user'");
$data_user = mysqli_fetch_assoc($query_user);

// 2. Logika Sapaan Waktu
date_default_timezone_set('Asia/Jakarta');
$jam = date('H');
if ($jam >= 5 && $jam < 12) {
    $sapaan = "Selamat Pagi";
} elseif ($jam >= 12 && $jam < 15) {
    $sapaan = "Selamat Siang";
} elseif ($jam >= 15 && $jam < 18) {
    $sapaan = "Selamat Sore";
} else {
    $sapaan = "Selamat Malam";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Bank Sampah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #FF6B35;
            --dark-bg: #0F172A;
            --card-bg: #1E293B;
            --text-light: #F8FAFC;
            --text-gray: #94A3B8;
            --border-color: rgba(255, 255, 255, 0.08);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-light);
            padding-bottom: 30px;
        }

        /* --- NAVBAR --- */
        .navbar {
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border-color);
            padding: 12px 0;
        }

        /* --- BALANCE PANEL --- */
        .balance-panel {
            background: linear-gradient(145deg, rgba(30, 41, 59, 0.9), rgba(15, 23, 42, 0.9));
            border: 1px solid rgba(255, 107, 53, 0.2);
            border-radius: 20px;
            padding: 20px;
            position: relative;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            margin-bottom: 25px;
            text-align: center;
        }

        .balance-label {
            font-size: 0.75rem;
            color: var(--text-gray);
            letter-spacing: 1px;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .balance-amount {
            font-size: 2.2rem;
            font-weight: 800;
            margin: 10px 0;
            letter-spacing: -1px;
        }

        .btn-eye {
            background: none;
            border: none;
            color: var(--text-gray);
            font-size: 0.9rem;
            cursor: pointer;
        }

        /* --- MENU GRID --- */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 30px;
        }

        .menu-item {
            text-decoration: none;
            text-align: center;
            transition: 0.3s;
        }

        .menu-icon {
            width: 55px;
            height: 55px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            margin: 0 auto 8px;
            color: white;
            transition: 0.3s;
        }

        .menu-item:active {
            transform: scale(0.95);
        }

        .btn-setor {
            background: linear-gradient(135deg, #10B981, #059669);
        }

        .btn-tarik {
            background: linear-gradient(135deg, #3B82F6, #2563EB);
        }

        .btn-riwayat {
            background: linear-gradient(135deg, #F97316, #EA580C);
        }

        .btn-harga {
            background: linear-gradient(135deg, #8B5CF6, #7C3AED);
        }

        .menu-label {
            font-size: 0.75rem;
            color: var(--text-gray);
            font-weight: 600;
        }

        /* --- TRANSACTION LIST --- */
        .trx-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 14px;
            padding: 12px 15px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .trx-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 12px;
            font-size: 1rem;
        }

        .text-truncate-custom {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        @media (max-width: 576px) {
            .balance-amount {
                font-size: 1.8rem;
            }

            .main-container {
                padding: 0 15px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar sticky-top">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-bold text-white fs-6" href="#">
                <i class="fas fa-recycle text-warning me-1"></i>Bank<span style="color:var(--primary)">Sampah</span>
            </a>
            <a href="logout.php" class="btn btn-sm btn-outline-danger border-0">
                <i class="fas fa-sign-out-alt"></i>
            </a>
        </div>
    </nav>

    <div class="container main-container mt-4">
        <div class="text-center mb-4">
            <small class="text-gray d-block mb-1"><?php echo $sapaan; ?>,</small>
            <h4 class="fw-bold text-white"><?php echo explode(' ', $data_user['nama'])[0]; ?>! 👋</h4>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-8 col-12">

                <div class="balance-panel">
                    <div class="balance-label">
                        Total Saldo Kamu
                        <button class="btn-eye" onclick="toggleBalance()">
                            <i class="fas fa-eye-slash" id="eyeIcon"></i>
                        </button>
                    </div>
                    <div class="balance-amount" id="balanceText">••••••</div>
                </div>

                <div class="menu-grid">
                    <a href="setor.php" class="menu-item">
                        <div class="menu-icon btn-setor"><i class="fas fa-plus-circle"></i></div>
                        <span class="menu-label">Setor</span>
                    </a>
                    <a href="tarik.php" class="menu-item">
                        <div class="menu-icon btn-tarik"><i class="fas fa-wallet"></i></div>
                        <span class="menu-label">Tarik</span>
                    </a>
                    <a href="riwayat.php" class="menu-item">
                        <div class="menu-icon btn-riwayat"><i class="fas fa-list-ul"></i></div>
                        <span class="menu-label">Riwayat</span>
                    </a>
                    <a href="harga.php" class="menu-item">
                        <div class="menu-icon btn-harga"><i class="fas fa-tag"></i></div>
                        <span class="menu-label">Harga</span>
                    </a>
                </div>

                <div class="mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold small">Transaksi Terakhir</span>
                        <a href="riwayat.php" class="text-primary text-decoration-none" style="font-size: 0.75rem;">Lihat Semua</a>
                    </div>

                    <div class="trx-list">
                        <?php
                        $query_riwayat = mysqli_query($conn, "SELECT * FROM transaksi WHERE user_id = '$id_user' ORDER BY id DESC LIMIT 5");
                        if (mysqli_num_rows($query_riwayat) > 0) {
                            while ($row = mysqli_fetch_assoc($query_riwayat)) {
                                $isSetor = ($row['tipe'] == 'setor');
                                $color = $isSetor ? 'rgba(16, 185, 129, 0.15)' : 'rgba(239, 68, 68, 0.15)';
                                $iconColor = $isSetor ? '#34D399' : '#F87171';
                                $symbol = $isSetor ? '+' : '-';
                        ?>
                                <div class="trx-card">
                                    <div class="d-flex align-items-center overflow-hidden">
                                        <div class="trx-icon" style="background: <?php echo $color; ?>; color: <?php echo $iconColor; ?>;">
                                            <i class="fas <?php echo $isSetor ? 'fa-arrow-down' : 'fa-arrow-up'; ?>"></i>
                                        </div>
                                        <div class="overflow-hidden">
                                            <div class="fw-bold text-white small text-truncate-custom"><?php echo $row['keterangan']; ?></div>
                                            <small class="text-gray" style="font-size: 0.65rem;"><?php echo date('d M, H:i', strtotime($row['tanggal'])); ?></small>
                                        </div>
                                    </div>
                                    <div class="fw-bold <?php echo $isSetor ? 'text-success' : 'text-danger'; ?>" style="font-size: 0.85rem;">
                                        <?php echo $symbol; ?>Rp<?php echo number_format($row['jumlah'], 0, ',', '.'); ?>
                                    </div>
                                </div>
                        <?php
                            }
                        } else {
                            echo '<div class="text-center text-gray small py-4">Belum ada transaksi.</div>';
                        }
                        ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        // Fitur Hide/Show Saldo
        const saldoAsli = "Rp<?php echo number_format($data_user['saldo'], 0, ',', '.'); ?>";
        const saldoHidden = "••••••";
        let isHidden = true;

        function toggleBalance() {
            const text = document.getElementById('balanceText');
            const icon = document.getElementById('eyeIcon');

            if (isHidden) {
                text.innerText = saldoAsli;
                icon.classList.replace('fa-eye-slash', 'fa-eye');
                icon.style.color = 'white';
            } else {
                text.innerText = saldoHidden;
                icon.classList.replace('fa-eye', 'fa-eye-slash');
                icon.style.color = '';
            }
            isHidden = !isHidden;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>