<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Ambil Saldo Terbaru
$uid = $_SESSION['id'];
$cek_saldo = mysqli_query($conn, "SELECT saldo FROM users WHERE id = '$uid'");
$data_saldo = mysqli_fetch_assoc($cek_saldo);
$saldo_sekarang = $data_saldo['saldo'];

// Update Session
$_SESSION['saldo'] = $saldo_sekarang;

// Variabel Notifikasi
$notif_type = "";
$notif_message = "";

if (isset($_POST['tarik'])) {
    $jumlah = $_POST['jumlah'];
    $metode = $_POST['metode'];

    // Validasi
    if ($jumlah > $saldo_sekarang) {
        $notif_type = "error";
        $notif_message = "Saldo tidak cukup!";
    } elseif ($jumlah < 10000) {
        $notif_type = "warning";
        $notif_message = "Minimal penarikan Rp 10.000";
    } else {
        $keterangan = "Tarik via " . $metode;

        // 1. Simpan Transaksi
        $query1 = "INSERT INTO transaksi (user_id, tipe, keterangan, jumlah) VALUES ('$uid', 'tarik', '$keterangan', '$jumlah')";

        // 2. Kurangi Saldo User
        $query2 = "UPDATE users SET saldo = saldo - $jumlah WHERE id = '$uid'";

        if (mysqli_query($conn, $query1) && mysqli_query($conn, $query2)) {
            $_SESSION['saldo'] -= $jumlah;
            $notif_type = "success";
            $notif_message = "Penarikan Berhasil! Uang akan masuk maksimal 1x24 Jam.";
        } else {
            $notif_type = "error";
            $notif_message = "Terjadi kesalahan sistem.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarik Saldo</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #FF6B35;
            /* Orange */
            --primary-hover: #e85d2a;
            --dark-bg: #0F172A;
            --card-bg: #1E293B;
            --input-bg: #0f172a;
            --text-light: #F8FAFC;
            --text-gray: #94A3B8;
            --border-color: rgba(255, 255, 255, 0.08);
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* --- GLASS CARD --- */
        .tarik-card {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            width: 100%;
            max-width: 420px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        /* Header dengan Gradient Orange (Tarik = Orange) */
        .card-header-img {
            background: linear-gradient(135deg, #FF6B35, #E85D2A);
            padding: 30px;
            text-align: center;
            position: relative;
            color: white;
        }

        /* Lingkaran Icon Floating */
        .icon-floating {
            width: 70px;
            height: 70px;
            background: var(--card-bg);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            color: #FF6B35;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            position: absolute;
            bottom: -35px;
            left: 50%;
            transform: translateX(-50%);
            border: 4px solid var(--card-bg);
        }

        /* Form Styling */
        .form-label {
            color: var(--text-gray);
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            color: white;
            padding: 12px 15px;
            border-radius: 12px;
            font-size: 0.95rem;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: var(--input-bg);
            border-color: var(--primary);
            box-shadow: 0 0 0 2px rgba(255, 107, 53, 0.2);
            color: white;
        }

        /* Chips (Tombol Cepat) */
        .chip-group {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 20px;
        }

        .chip {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--border-color);
            color: var(--text-light);
            padding: 8px 14px;
            border-radius: 50px;
            font-size: 0.8rem;
            cursor: pointer;
            transition: 0.2s;
        }

        .chip:hover {
            border-color: var(--primary);
            color: var(--primary);
            background: rgba(255, 107, 53, 0.1);
        }

        /* Tombol */
        .btn-tarik {
            background: var(--primary);
            border: none;
            color: white;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }

        .btn-tarik:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .btn-tarik:disabled {
            background: #475569;
            cursor: not-allowed;
            transform: none;
        }

        .btn-batal {
            background: transparent;
            border: 1px solid var(--border-color);
            color: var(--text-gray);
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            text-decoration: none;
            display: block;
            text-align: center;
            transition: 0.3s;
        }

        .btn-batal:hover {
            background: rgba(255, 255, 255, 0.05);
            color: white;
        }

        /* SweetAlert Dark Mode Fix */
        div:where(.swal2-popup) {
            background: var(--card-bg) !important;
            color: var(--text-light) !important;
            border: 1px solid var(--border-color);
            border-radius: 20px !important;
            width: 90% !important;
            /* Agar pas di HP */
            max-width: 320px !important;
        }

        div:where(.swal2-title) {
            color: white !important;
        }

        /* Responsive Fix untuk Layar Sangat Kecil */
        @media (max-width: 380px) {
            .card-header-img {
                padding: 20px;
            }

            .icon-floating {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
                bottom: -30px;
            }

            .p-4 {
                padding: 1.5rem !important;
            }
        }
    </style>
</head>

<body>

    <div class="tarik-card">
        <div class="card-header-img">
            <h5 class="fw-bold m-0 opacity-75 small text-uppercase ls-1">Saldo Kamu</h5>
            <h2 class="fw-bold m-0 mt-1">Rp <?php echo number_format($saldo_sekarang, 0, ',', '.'); ?></h2>
            <div class="icon-floating">
                <i class="fas fa-money-bill-wave"></i>
            </div>
        </div>

        <div class="p-4 pt-5 mt-2">
            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Tarik Ke</label>
                    <select name="metode" class="form-select" required>
                        <option value="Gopay">GoPay</option>
                        <option value="Dana">DANA</option>
                        <option value="OVO">OVO</option>
                        <option value="ShopeePay">ShopeePay</option>
                        <option value="Cash">Tunai (Ambil di Kantor)</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah Penarikan (Rp)</label>
                    <input type="number" name="jumlah" id="inputJumlah" class="form-control fw-bold text-warning" placeholder="Minimal 10.000" min="10000" required oninput="cekSaldo()">
                </div>

                <div class="chip-group">
                    <div class="chip" onclick="setNominal(20000)">20rb</div>
                    <div class="chip" onclick="setNominal(50000)">50rb</div>
                    <div class="chip" onclick="setNominal(100000)">100rb</div>
                    <div class="chip" onclick="setNominal(<?php echo $saldo_sekarang; ?>)">Semua</div>
                </div>

                <div id="pesanError" class="small text-danger fw-bold mb-3 text-center" style="display: none;">
                    <i class="fas fa-exclamation-circle me-1"></i> Saldo tidak mencukupi!
                </div>

                <div class="d-flex gap-3">
                    <a href="dashboard.php" class="btn-batal">Batal</a>
                    <button type="submit" name="tarik" id="btnSubmit" class="btn-tarik">Tarik</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        // Ambil saldo dari PHP ke JS
        const saldoMax = <?php echo $saldo_sekarang; ?>;

        function setNominal(nilai) {
            document.getElementById('inputJumlah').value = nilai;
            cekSaldo(); // Cek ulang setelah klik chip
        }

        function cekSaldo() {
            const input = document.getElementById('inputJumlah');
            const tombol = document.getElementById('btnSubmit');
            const pesan = document.getElementById('pesanError');
            const nilaiInput = parseInt(input.value) || 0;

            if (nilaiInput > saldoMax) {
                // Jika input lebih besar dari saldo
                input.classList.add('is-invalid');
                pesan.style.display = 'block';
                tombol.disabled = true;
                tombol.innerText = "Saldo Kurang";
            } else {
                // Jika aman
                input.classList.remove('is-invalid');
                pesan.style.display = 'none';
                tombol.disabled = false;
                tombol.innerText = "Tarik";
            }
        }
    </script>

    <?php if ($notif_type != ""): ?>
        <script>
            Swal.fire({
                icon: '<?php echo $notif_type; ?>',
                title: '<?php echo ($notif_type == "success") ? "Berhasil!" : "Gagal!"; ?>',
                text: '<?php echo $notif_message; ?>',
                background: '#1E293B',
                color: '#fff',
                confirmButtonColor: '#FF6B35',
                confirmButtonText: 'Oke',
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed && '<?php echo $notif_type; ?>' == 'success') {
                    window.location = 'dashboard.php';
                }
            });
        </script>
    <?php endif; ?>

</body>

</html>