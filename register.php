<?php
include 'koneksi.php';

// Variabel untuk menyimpan status notifikasi
$notif_type = "";
$notif_message = "";

// Logika PHP
if (isset($_POST['daftar'])) {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $hp = $_POST['hp'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $checkEmail = mysqli_query($conn, "SELECT email FROM users WHERE email = '$email'");

    if (mysqli_num_rows($checkEmail) > 0) {
        $notif_type = "warning";
        $notif_message = "Email sudah terdaftar! Gunakan email lain.";
    } else {
        $query = "INSERT INTO users (nama, email, no_hp, password) VALUES ('$nama', '$email', '$hp', '$password')";

        if (mysqli_query($conn, $query)) {
            $notif_type = "success";
            $notif_message = "Akun Berhasil Dibuat!";
        } else {
            $notif_type = "error";
            $notif_message = "Terjadi Kesalahan Sistem.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Bank Sampah</title>

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
            --input-border: #334155;
            --text-light: #F8FAFC;
            --text-gray: #94A3B8;
            --success-green: #10B981;
            /* Hijau Neon Terang */
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-light);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: linear-gradient(to bottom, rgba(15, 23, 42, 0.9), rgba(15, 23, 42, 1)),
                url('https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?q=80&w=1920&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            padding: 20px;
        }

        .register-card {
            background: rgba(30, 41, 59, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 35px 30px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.4rem;
            color: white;
            text-align: center;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .register-subtitle {
            text-align: center;
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-bottom: 25px;
        }

        .form-label {
            font-weight: 500;
            font-size: 0.85rem;
            color: var(--text-gray);
            margin-bottom: 6px;
            margin-left: 2px;
        }

        .custom-input {
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            color: white;
            padding: 12px 16px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .custom-input::placeholder {
            color: #475569;
            font-size: 0.9rem;
        }

        .custom-input:focus {
            background-color: var(--input-bg);
            border-color: var(--primary);
            box-shadow: none;
            color: white;
        }

        .input-group-text {
            background-color: var(--input-bg);
            border: 1px solid var(--input-border);
            border-right: none;
            color: var(--text-gray);
            width: 45px;
            justify-content: center;
        }

        .input-group .input-group-text:first-child {
            border-radius: 12px 0 0 12px;
        }

        .input-group .form-control:last-child {
            border-radius: 0 12px 12px 0;
        }

        .input-group .form-control.pass-field {
            border-radius: 0;
        }

        .input-group .input-group-text:last-child {
            border-radius: 0 12px 12px 0;
            border-left: none;
        }

        .btn-register {
            background-color: var(--primary);
            color: white;
            padding: 12px;
            border-radius: 50px;
            font-weight: 700;
            width: 100%;
            border: none;
            margin-top: 15px;
            transition: all 0.3s;
            font-size: 1rem;
            box-shadow: 0 10px 20px -5px rgba(255, 107, 53, 0.4);
        }

        .btn-register:hover {
            background-color: var(--primary-hover);
            transform: translateY(-2px);
            color: white;
        }

        .link-login {
            color: var(--primary);
            font-weight: 700;
            text-decoration: none;
            transition: 0.3s;
        }

        .link-login:hover {
            text-decoration: underline;
            color: var(--primary-hover);
        }

        /* =========================================
           FIX CSS SWEETALERT (Agar Icon Tidak Terpotong)
           ========================================= */
        div:where(.swal2-popup) {
            background: var(--card-bg) !important;
            color: var(--text-light) !important;
            border: 1px solid var(--border-color);
            border-radius: 20px !important;
            width: 90% !important;
            max-width: 320px !important;
            padding: 20px !important;
            /* Padding cukup agar konten tidak mepet */
        }

        /* Container Icon */
        div:where(.swal2-icon) {
            width: 5em !important;
            height: 5em !important;
            margin: 20px auto 20px auto !important;
            /* Margin aman atas bawah */
            border-width: 4px !important;
            /* Border tebal */
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        /* FIX KHUSUS ICON SUKSES */
        div:where(.swal2-icon).swal2-success {
            border-color: var(--success-green) !important;
            color: var(--success-green) !important;
        }

        /* Ring tipis di background */
        div:where(.swal2-icon).swal2-success .swal2-success-ring {
            position: absolute !important;
            width: 100% !important;
            height: 100% !important;
            border: 4px solid rgba(16, 185, 129, 0.2) !important;
            border-radius: 50% !important;
            box-sizing: content-box !important;
            /* Mencegah terpotong */
            left: -4px !important;
            top: -4px !important;
            z-index: 1 !important;
        }

        /* Garis Centang (Checkmark) */
        div:where(.swal2-icon).swal2-success [class^='swal2-success-line'] {
            background-color: var(--success-green) !important;
            height: 6px !important;
            /* Lebih tebal biar jelas */
            z-index: 2 !important;
        }

        /* Teks Judul */
        div:where(.swal2-title) {
            color: white !important;
            font-size: 1.5em !important;
            font-weight: 800 !important;
            margin-bottom: 10px !important;
        }

        /* Teks Deskripsi */
        div:where(.swal2-html-container) {
            color: var(--text-gray) !important;
            font-size: 0.95em !important;
            margin-bottom: 20px !important;
        }

        /* Tombol Oke */
        div:where(.swal2-confirm) {
            background-color: var(--primary) !important;
            box-shadow: 0 5px 15px -3px rgba(255, 107, 53, 0.4) !important;
            border-radius: 50px !important;
            padding: 12px 30px !important;
            font-weight: 700 !important;
            width: 100% !important;
            /* Tombol full width agar mudah dipencet */
        }
    </style>
</head>

<body>

    <div class="register-card">
        <div class="brand-title">
            <i class="fas fa-recycle text-warning me-2"></i> Bank<span style="color:var(--primary)">Sampah</span>
        </div>
        <p class="register-subtitle">Buat akun baru dalam hitungan detik.</p>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Nama Lengkap</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="nama" class="form-control custom-input border-start-0 ps-1" placeholder="Nama Lengkap Anda" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control custom-input border-start-0 ps-1" placeholder="contoh@email.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Nomor WhatsApp</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-phone"></i></span>
                    <input type="number" name="hp" class="form-control custom-input border-start-0 ps-1" placeholder="08xxxxxxxx" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" id="passInput" class="form-control custom-input border-start-0 ps-1 pass-field border-end-0" placeholder="Buat password kuat" required>
                    <span class="input-group-text bg-transparent" style="cursor: pointer; border-color: var(--input-border);" onclick="togglePass()">
                        <i class="far fa-eye text-secondary" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" name="daftar" class="btn-register">
                Daftar Sekarang
            </button>
        </form>

        <p class="text-center mt-4 text-muted small">
            Sudah punya akun? <a href="login.php" class="link-login">Masuk disini</a>
        </p>
    </div>

    <script>
        function togglePass() {
            var x = document.getElementById("passInput");
            var icon = document.getElementById("eyeIcon");
            if (x.type === "password") {
                x.type = "text";
                icon.classList.remove("fa-eye");
                icon.classList.add("fa-eye-slash");
            } else {
                x.type = "password";
                icon.classList.remove("fa-eye-slash");
                icon.classList.add("fa-eye");
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
                // Animasi halus agar tidak kaget
                showClass: {
                    popup: 'animate__animated animate__fadeInDown animate__faster'
                },
                hideClass: {
                    popup: 'animate__animated animate__fadeOutUp animate__faster'
                }
            }).then((result) => {
                if (result.isConfirmed && '<?php echo $notif_type; ?>' == 'success') {
                    window.location = 'login.php';
                }
            });
        </script>
    <?php endif; ?>

</body>

</html>