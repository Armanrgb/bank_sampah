<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

// Variabel Notifikasi
$notif_type = "";
$notif_message = "";

// PROSES FORM
if (isset($_POST['kirim'])) {
    $nama_sampah = $_POST['jenis'];
    $berat = $_POST['berat'];
    $uid = $_SESSION['id'];

    // 1. Ambil harga terbaru
    $cek_harga = mysqli_query($conn, "SELECT harga_per_kg FROM jenis_sampah WHERE nama_sampah = '$nama_sampah'");

    if (mysqli_num_rows($cek_harga) > 0) {
        $data_harga = mysqli_fetch_assoc($cek_harga);
        $harga_per_kg = $data_harga['harga_per_kg'];
    } else {
        $harga_per_kg = 0;
    }

    // 2. Hitung Total
    $total_uang = $berat * $harga_per_kg;
    $keterangan = "Setor " . $nama_sampah;

    if ($total_uang > 0) {
        // 3. Simpan Transaksi & Update Saldo
        $query1 = "INSERT INTO transaksi (user_id, tipe, keterangan, berat, jumlah) VALUES ('$uid', 'setor', '$keterangan', '$berat', '$total_uang')";
        $query2 = "UPDATE users SET saldo = saldo + $total_uang WHERE id = '$uid'";

        if (mysqli_query($conn, $query1) && mysqli_query($conn, $query2)) {
            $_SESSION['saldo'] += $total_uang;
            $notif_type = "success";
            $notif_message = "Berhasil! Saldo bertambah Rp " . number_format($total_uang, 0, ',', '.');
        } else {
            $notif_type = "error";
            $notif_message = "Terjadi kesalahan sistem.";
        }
    } else {
        $notif_type = "warning";
        $notif_message = "Jenis sampah tidak valid atau harga 0!";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setor Sampah</title>

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
            /* Padding body penting untuk HP */
        }

        /* --- GLASS CARD (RESPONSIVE) --- */
        .setor-card {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            width: 100%;
            max-width: 420px;
            /* Maksimal lebar di desktop */
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        /* Header dengan Gradient Hijau (Setor = Hijau) */
        .card-header-img {
            background: linear-gradient(135deg, #10B981, #059669);
            /* Hijau */
            padding: 30px;
            text-align: center;
            position: relative;
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
            color: #10B981;
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
            border-color: #10B981;
            /* Fokus warna hijau */
            box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
            color: white;
        }

        /* Placeholder & Icon di input group */
        .input-group-text {
            background-color: var(--input-bg);
            border: 1px solid var(--border-color);
            border-left: none;
            color: var(--text-gray);
        }

        /* Box Estimasi */
        .estimation-box {
            background: rgba(16, 185, 129, 0.1);
            /* Hijau transparan */
            border: 1px dashed rgba(16, 185, 129, 0.3);
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            margin: 25px 0;
        }

        /* Tombol */
        .btn-setor {
            background: #10B981;
            border: none;
            color: white;
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            transition: 0.3s;
        }

        .btn-setor:hover {
            background: #059669;
            transform: translateY(-2px);
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

        /* Responsive Fix untuk Layar Sangat Kecil (iPhone SE dll) */
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

            /* Kurangi padding body card */
        }
    </style>
</head>

<body>

    <div class="setor-card">
        <div class="card-header-img">
            <h4 class="text-white fw-bold m-0 mb-4">Setor Sampah</h4>
            <div class="icon-floating">
                <i class="fas fa-leaf"></i>
            </div>
        </div>

        <div class="p-4 pt-5 mt-2">
            <form method="POST">

                <div class="mb-3">
                    <label class="form-label">Pilih Jenis Sampah</label>
                    <select name="jenis" id="jenisInput" class="form-select" required onchange="hitungEstimasi()">
                        <option value="" data-harga="0" selected disabled>-- Pilih Kategori --</option>
                        <?php
                        $q = mysqli_query($conn, "SELECT * FROM jenis_sampah");
                        while ($row = mysqli_fetch_assoc($q)) {
                            echo '<option value="' . $row['nama_sampah'] . '" data-harga="' . $row['harga_per_kg'] . '">
                                    ' . $row['nama_sampah'] . ' (Rp ' . number_format($row['harga_per_kg']) . '/kg)
                                  </option>';
                        }
                        ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Berat Sampah</label>
                    <div class="input-group">
                        <input type="number" step="0.1" name="berat" id="beratInput" class="form-control border-end-0" placeholder="0.0" required oninput="hitungEstimasi()">
                        <span class="input-group-text fw-bold" style="border-radius: 0 12px 12px 0;">Kg</span>
                    </div>
                </div>

                <div class="estimation-box">
                    <small class="text-gray d-block mb-1 text-uppercase ls-1" style="font-size: 0.7rem;">Estimasi Pendapatan</small>
                    <h2 class="fw-bold text-white m-0" id="totalEstimasi">Rp 0</h2>
                </div>

                <div class="d-flex gap-3">
                    <a href="dashboard.php" class="btn-batal">Batal</a>
                    <button type="submit" name="kirim" class="btn-setor">Kirim</button>
                </div>

            </form>
        </div>
    </div>

    <script>
        function hitungEstimasi() {
            var jenisSelect = document.getElementById('jenisInput');
            var beratInput = document.getElementById('beratInput');
            var totalLabel = document.getElementById('totalEstimasi');

            var hargaPerKg = jenisSelect.options[jenisSelect.selectedIndex].getAttribute('data-harga') || 0;
            var berat = beratInput.value || 0;

            var total = hargaPerKg * berat;

            totalLabel.innerText = "Rp " + new Intl.NumberFormat('id-ID').format(total);
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
                confirmButtonColor: '#10B981',
                /* Hijau */
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