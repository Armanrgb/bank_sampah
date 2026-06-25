<?php
session_start();
include 'koneksi.php';

// Pastikan Timezone WIB
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Fungsi helper untuk format tanggal Indonesia
function format_tanggal_indo($tanggal)
{
    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $split = explode('-', $tanggal);
    return $split[2] . ' ' . $bulan[(int)$split[1]] . ' ' . $split[0];
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Transaksi</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #FF6B35;
            --primary-hover: #e85d2a;
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
            min-height: 100vh;
        }

        .main-container {
            max-width: 600px;
            /* Layout dipadatkan ala Mobile App */
            margin: 0 auto;
            padding: 20px;
            min-height: 100vh;
        }

        /* HEADER */
        .page-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 25px;
            position: sticky;
            top: 0;
            background: rgba(15, 23, 42, 0.95);
            /* Blur Effect */
            backdrop-filter: blur(10px);
            z-index: 10;
            padding: 15px 0;
            border-bottom: 1px solid var(--border-color);
        }

        /* DATE GROUP HEADER */
        .date-header {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-gray);
            margin: 25px 0 15px 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* TRANSACTION CARD */
        .transaction-item {
            background: var(--card-bg);
            padding: 20px;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 15px;
            /* Jarak antar kartu */
            border: 1px solid var(--border-color);
            transition: all 0.2s;
        }

        .transaction-item:active {
            transform: scale(0.98);
            background: rgba(255, 255, 255, 0.05);
        }

        /* ICON BULAT */
        .icon-wrapper {
            width: 50px;
            height: 50px;
            border-radius: 16px;
            /* Squircle modern */
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            flex-shrink: 0;
            margin-right: 15px;
        }

        /* WARNA ICON (Transparan) */
        .bg-soft-green {
            background: rgba(16, 185, 129, 0.15);
            color: #34D399;
        }

        .bg-soft-red {
            background: rgba(239, 68, 68, 0.15);
            color: #F87171;
        }

        /* TEXT INFO */
        .trx-info {
            flex-grow: 1;
            overflow: hidden;
        }

        .trx-title {
            font-weight: 600;
            font-size: 1rem;
            margin-bottom: 4px;
            color: var(--text-light);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .trx-time {
            font-size: 0.8rem;
            color: var(--text-gray);
        }

        /* NOMINAL */
        .trx-amount {
            text-align: right;
            font-weight: 700;
            font-size: 1rem;
            white-space: nowrap;
        }

        .amount-green {
            color: #34D399;
        }

        /* Hijau Terang */
        .amount-red {
            color: #F87171;
        }

        /* Merah Terang */

        .trx-status {
            font-size: 0.75rem;
            display: inline-block;
            margin-top: 4px;
            color: var(--text-gray);
        }

        /* BUTTON BACK */
        .btn-back {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-light);
            border-radius: 50px;
            padding: 8px 20px;
            font-size: 0.85rem;
            text-decoration: none;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
        }
    </style>
</head>

<body>

    <div class="main-container">

        <div class="page-header">
            <div>
                <h4 class="fw-bold m-0 text-white">Riwayat</h4>
                <small class="text-gray">Semua transaksi kamu</small>
            </div>
            <a href="dashboard.php" class="btn-back">
                <i class="fas fa-times me-2"></i> Tutup
            </a>
        </div>

        <div class="transaction-list">
            <?php
            $uid = $_SESSION['id'];
            $query = mysqli_query($conn, "SELECT * FROM transaksi WHERE user_id = '$uid' ORDER BY tanggal DESC, id DESC");

            if (mysqli_num_rows($query) > 0) {

                $current_date = ''; // Variabel penampung tanggal

                while ($row = mysqli_fetch_assoc($query)) {

                    // Ambil Tanggal (YYYY-MM-DD) dan Jam
                    $date_raw = date('Y-m-d', strtotime($row['tanggal']));
                    $jam = date('H:i', strtotime($row['tanggal']));

                    // Logic Grouping Tanggal
                    if ($date_raw != $current_date) {

                        if ($date_raw == date('Y-m-d')) {
                            $display_date = "Hari Ini";
                        } elseif ($date_raw == date('Y-m-d', strtotime("-1 days"))) {
                            $display_date = "Kemarin";
                        } else {
                            $display_date = format_tanggal_indo($date_raw);
                        }

                        echo '<div class="date-header">' . $display_date . '</div>';
                        $current_date = $date_raw;
                    }

                    // Logic Tampilan (Warna & Icon)
                    if ($row['tipe'] == 'setor') {
                        $bg_class = 'bg-soft-green';
                        $icon = 'fa-recycle';
                        $amount_class = 'amount-green';
                        $tanda = '+';
                        $sub_text = $row['berat'] . ' Kg';
                    } else {
                        $bg_class = 'bg-soft-red';
                        $icon = 'fa-arrow-up';
                        $amount_class = 'amount-red';
                        $tanda = '-';
                        $sub_text = 'Penarikan Saldo';
                    }
            ?>

                    <div class="transaction-item">
                        <div class="d-flex align-items-center w-100 overflow-hidden">
                            <div class="icon-wrapper <?php echo $bg_class; ?>">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>

                            <div class="trx-info">
                                <div class="trx-title"><?php echo $row['keterangan']; ?></div>
                                <div class="trx-time">
                                    <?php echo $jam; ?> WIB
                                </div>
                            </div>
                        </div>

                        <div class="text-end ms-2 flex-shrink-0">
                            <div class="trx-amount <?php echo $amount_class; ?>">
                                <?php echo $tanda; ?> Rp <?php echo number_format($row['jumlah'], 0, ',', '.'); ?>
                            </div>
                            <span class="trx-status"><?php echo $sub_text; ?></span>
                        </div>
                    </div>

                <?php
                }
            } else {
                ?>
                <div class="text-center py-5 mt-5">
                    <div class="bg-secondary bg-opacity-10 p-4 rounded-circle d-inline-block mb-3">
                        <i class="fas fa-history fs-1 text-gray opacity-50"></i>
                    </div>
                    <h6 class="fw-bold text-white">Belum ada riwayat</h6>
                    <p class="small text-gray">Semua transaksi kamu akan muncul di sini.</p>
                </div>
            <?php } ?>
        </div>

    </div>

</body>

</html>