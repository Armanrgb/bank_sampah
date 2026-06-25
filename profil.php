<?php
session_start();
include 'koneksi.php';

// Cek Login
if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$id = $_SESSION['id'];

// PROSES UPLOAD FOTO & UPDATE DATA
if (isset($_POST['simpan'])) {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $hp = mysqli_real_escape_string($conn, $_POST['hp']);
    $password_baru = $_POST['password'];

    // 1. Update Data Teks Dulu
    $query_update = "UPDATE users SET nama='$nama', email='$email', no_hp='$hp' WHERE id='$id'";
    $update = mysqli_query($conn, $query_update);

    // 2. Cek Password Baru
    if (!empty($password_baru)) {
        $password_hash = password_hash($password_baru, PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET password='$password_hash' WHERE id='$id'");
    }

    // 3. Proses Upload Foto
    if ($_FILES['foto']['error'] === 0) {
        $ekstensi_diperbolehkan = ['png', 'jpg', 'jpeg'];
        $nama_file = $_FILES['foto']['name'];
        $x = explode('.', $nama_file);
        $ekstensi = strtolower(end($x));
        $ukuran = $_FILES['foto']['size'];
        $file_tmp = $_FILES['foto']['tmp_name'];

        if (in_array($ekstensi, $ekstensi_diperbolehkan) === true) {
            if ($ukuran < 2048000) { // Maks 2MB
                // Buat nama file unik biar gak bentrok
                $nama_file_baru = "profile_" . $id . "_" . time() . "." . $ekstensi;
                $folder_tujuan = "img/" . $nama_file_baru;

                // Upload file
                if (move_uploaded_file($file_tmp, $folder_tujuan)) {
                    // Update nama file di database
                    mysqli_query($conn, "UPDATE users SET foto='$nama_file_baru' WHERE id='$id'");
                }
            } else {
                echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB');</script>";
            }
        } else {
            echo "<script>alert('Ekstensi file tidak diperbolehkan! Hanya PNG, JPG, JPEG');</script>";
        }
    }

    if ($update) {
        $_SESSION['nama'] = $nama;
        echo "<script>alert('Profil Berhasil Diupdate! ✨'); window.location='profil.php';</script>";
    } else {
        echo "<script>alert('Gagal update profil.');</script>";
    }
}

// AMBIL DATA USER TERBARU
$query = mysqli_query($conn, "SELECT * FROM users WHERE id = '$id'");
$data = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil | Bank Sampah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #059669;
            --bg-light: #F3F4F6;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--bg-light);
            padding-bottom: 50px;
        }

        .card-profile {
            border: none;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            background: white;
        }

        .profile-banner {
            height: 160px;
            background: linear-gradient(135deg, #059669, #047857);
            position: relative;
        }

        .profile-banner::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40px;
            background: white;
            border-radius: 24px 24px 0 0;
            opacity: 0.2;
        }

        .profile-img-container {
            margin-top: -80px;
            text-align: center;
            position: relative;
        }

        .profile-img {
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            object-fit: cover;
            background: white;
        }

        /* Tombol Upload Kecil */
        .btn-upload-icon {
            position: absolute;
            bottom: 5px;
            right: 50%;
            margin-right: -55px;
            /* Geser ke kanan foto */
            background: white;
            border: 1px solid #E5E7EB;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
            cursor: pointer;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: 0.3s;
        }

        .btn-upload-icon:hover {
            background: var(--primary);
            color: white;
        }

        .role-badge {
            background: #D1FAE5;
            color: #065F46;
            padding: 5px 15px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.85rem;
            margin-top: 10px;
            display: inline-block;
        }

        .form-label {
            font-weight: 600;
            font-size: 0.9rem;
            color: #4B5563;
        }

        .input-group-text {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-right: none;
            color: #6B7280;
            border-radius: 12px 0 0 12px;
        }

        .form-control {
            background: #F9FAFB;
            border: 1px solid #E5E7EB;
            border-left: none;
            padding: 12px;
            border-radius: 0 12px 12px 0;
            transition: all 0.3s;
        }

        .form-control:focus {
            background: white;
            border-color: var(--primary);
            box-shadow: none;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
            background: white;
            color: var(--primary);
        }

        .btn-simpan {
            background: var(--primary);
            color: white;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-simpan:hover {
            background: #047857;
            transform: translateY(-2px);
        }

        .btn-batal {
            background: #F3F4F6;
            color: #4B5563;
            border-radius: 12px;
            padding: 12px;
            font-weight: 600;
            border: none;
            transition: 0.3s;
        }

        .btn-batal:hover {
            background: #E5E7EB;
            color: #1F2937;
        }
    </style>
</head>

<body>

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">

                <div class="card card-profile">

                    <div class="profile-banner">
                        <a href="<?php echo ($_SESSION['role'] == 'admin') ? 'admin_dashboard.php' : 'dashboard.php'; ?>" class="btn btn-light btn-sm rounded-circle m-3 position-absolute top-0 start-0" style="width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                    </div>

                    <div class="card-body px-4 px-md-5 pb-5">

                        <form method="POST" enctype="multipart/form-data">

                            <div class="profile-img-container mb-4">
                                <?php
                                if (!empty($data['foto']) && file_exists('img/' . $data['foto'])) {
                                    $foto_profil = 'img/' . $data['foto'];
                                } else {
                                    $foto_profil = 'https://ui-avatars.com/api/?name=' . urlencode($data['nama']) . '&background=059669&color=fff&size=200';
                                }
                                ?>
                                <img src="<?php echo $foto_profil; ?>" class="profile-img" id="previewImg">

                                <label for="uploadFoto" class="btn-upload-icon" title="Ganti Foto">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" name="foto" id="uploadFoto" class="d-none" accept="image/*" onchange="previewFile()">

                                <div class="mt-3">
                                    <h3 class="fw-bold mb-0"><?php echo $data['nama']; ?></h3>
                                    <span class="role-badge">
                                        <i class="fas fa-shield-alt me-1"></i> <?php echo ucfirst($data['role']); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label">Nama Lengkap</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        <input type="text" name="nama" class="form-control" value="<?php echo $data['nama']; ?>" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Alamat Email</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        <input type="email" name="email" class="form-control" value="<?php echo $data['email']; ?>" required>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <label class="form-label">Nomor WhatsApp</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        <input type="text" name="hp" class="form-control" value="<?php echo $data['no_hp']; ?>" required>
                                    </div>
                                </div>

                                <div class="col-12 my-2">
                                    <hr class="text-muted opacity-25">
                                </div>

                                <div class="col-12">
                                    <label class="form-label text-danger">Ganti Password <small class="text-muted fw-normal">(Opsional)</small></label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-lock text-danger"></i></span>
                                        <input type="password" name="password" class="form-control" placeholder="Isi jika ingin mengubah password">
                                    </div>
                                </div>

                                <div class="col-12 d-flex gap-3 mt-4">
                                    <?php
                                    $link_kembali = ($_SESSION['role'] == 'admin') ? 'admin_dashboard.php' : 'dashboard.php';
                                    ?>
                                    <a href="<?php echo $link_kembali; ?>" class="btn btn-batal w-50">Batal</a>
                                    <button type="submit" name="simpan" class="btn btn-simpan w-50">Simpan Perubahan</button>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function previewFile() {
            const preview = document.getElementById('previewImg');
            const file = document.querySelector('input[type=file]').files[0];
            const reader = new FileReader();

            reader.addEventListener("load", function() {
                // Convert image file to base64 string
                preview.src = reader.result;
            }, false);

            if (file) {
                reader.readAsDataURL(file);
            }
        }
    </script>

</body>

</html>