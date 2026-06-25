<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Harga Sampah</title>

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

        /* --- HERO HEADER --- */
        .hero-header {
            background: linear-gradient(135deg, #FF6B35 0%, #E85D2A 100%);
            padding: 40px 20px 80px;
            /* Padding bawah besar untuk efek floating card */
            text-align: center;
            border-radius: 0 0 30px 30px;
            position: relative;
            z-index: 1;
        }

        .btn-back {
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            color: white;
            padding: 8px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            margin-top: 15px;
            transition: 0.3s;
        }

        .btn-back:hover {
            background: white;
            color: var(--primary);
        }

        /* --- CARD CONTAINER --- */
        .card-container {
            margin-top: -50px;
            /* Floating effect */
            position: relative;
            z-index: 2;
            padding-bottom: 40px;
        }

        /* --- PRICE CARD (DARK) --- */
        .price-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            padding: 25px 20px;
            text-align: center;
            height: 100%;
            transition: transform 0.3s, box-shadow 0.3s;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .price-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px -5px rgba(255, 107, 53, 0.15);
            border-color: var(--primary);
        }

        /* ICON CIRCLE */
        .icon-circle {
            width: 60px;
            height: 60px;
            background: rgba(255, 107, 53, 0.1);
            color: var(--primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 15px;
            border: 1px solid rgba(255, 107, 53, 0.2);
        }

        .item-name {
            font-weight: 600;
            font-size: 1rem;
            color: var(--text-light);
            margin-bottom: 5px;
        }

        .item-price {
            font-weight: 800;
            font-size: 1.2rem;
            color: #34D399;
            /* Hijau untuk harga */
            margin-bottom: 8px;
        }

        .item-unit {
            background: rgba(255, 255, 255, 0.05);
            color: var(--text-gray);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
            border: 1px solid var(--border-color);
        }

        /* INFO BOX */
        .info-box {
            background: rgba(234, 179, 8, 0.1);
            /* Kuning transparan */
            border: 1px dashed rgba(234, 179, 8, 0.3);
            color: #FACC15;
            border-radius: 12px;
            padding: 15px;
            font-size: 0.85rem;
            margin-top: 30px;
            text-align: center;
        }
    </style>
</head>

<body>

    <div class="hero-header">
        <div class="container">
            <h3 class="fw-bold text-white mb-1">Daftar Harga 🏷️</h3>
            <p class="text-white text-opacity-75 small mb-0">Update harga terbaru per Kilogram (Kg)</p>

            <a href="dashboard.php" class="btn-back">
                <i class="fas fa-arrow-left me-2"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="container card-container">
        <div class="row g-3 justify-content-center">

            <?php
            $query = mysqli_query($conn, "SELECT * FROM jenis_sampah ORDER BY harga_per_kg DESC");

            if (mysqli_num_rows($query) > 0) {
                while ($row = mysqli_fetch_assoc($query)) {

                    // Logic Auto Icon
                    $nama = $row['nama_sampah'];
                    $nama_lower = strtolower($nama);
                    $icon = "fa-recycle"; // Default

                    if (strpos($nama_lower, 'tembaga') !== false) {
                        $icon = "fa-coins";
                    } elseif (strpos($nama_lower, 'besi') !== false) {
                        $icon = "fa-hammer";
                    } elseif (strpos($nama_lower, 'plastik') !== false) {
                        $icon = "fa-bottle-water";
                    } elseif (strpos($nama_lower, 'kertas') !== false) {
                        $icon = "fa-newspaper";
                    } elseif (strpos($nama_lower, 'kardus') !== false) {
                        $icon = "fa-box-open";
                    } elseif (strpos($nama_lower, 'kaca') !== false) {
                        $icon = "fa-wine-glass";
                    } elseif (strpos($nama_lower, 'minyak') !== false) {
                        $icon = "fa-droplet";
                    } elseif (strpos($nama_lower, 'kaleng') !== false) {
                        $icon = "fa-prescription-bottle";
                    }
            ?>

                    <div class="col-6 col-md-4 col-lg-3">
                        <div class="price-card">
                            <div class="icon-circle">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>

                            <div class="item-name"><?php echo $nama; ?></div>

                            <div class="item-price">
                                Rp <?php echo number_format($row['harga_per_kg'], 0, ',', '.'); ?>
                            </div>

                            <div class="item-unit">per 1 Kg</div>
                        </div>
                    </div>

                <?php
                }
            } else {
                ?>
                <div class="col-12 text-center py-5">
                    <div class="bg-secondary bg-opacity-10 p-4 rounded-circle d-inline-block mb-3">
                        <i class="fas fa-tags fs-1 text-gray opacity-50"></i>
                    </div>
                    <p class="text-gray">Belum ada data harga.</p>
                </div>
            <?php } ?>

        </div>

        <div class="info-box">
            <i class="fas fa-info-circle me-1"></i>
            Harga dapat berubah sewaktu-waktu mengikuti pasar.
        </div>

    </div>

</body>

</html>