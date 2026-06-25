<?php
session_start();
include 'koneksi.php';

// 1. JIKA SUDAH LOGIN, CEK ROLE DAN LEMPAR KE DASHBOARD YANG SESUAI
if (isset($_SESSION['login'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
}

// 2. LOGIKA PROSES LOGIN
if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Cek User di Database
    $result = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email'");

    if (mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verifikasi Password
        if (password_verify($password, $row['password'])) {

            // Set Session secara lengkap
            $_SESSION['login'] = true;
            $_SESSION['id'] = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['role'] = $row['role']; // Mengambil 'admin' atau 'user' dari database

            // PENGALIHAN BERDASARKAN ROLE
            if ($row['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: dashboard.php");
            }
            exit;
        }
    }
    // Jika salah email/password
    $error = true;
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk - Bank Sampah</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

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
            position: relative;
            overflow: hidden;
        }

        /* Background Decoration */
        body::before {
            content: '';
            position: absolute;
            width: 150%;
            height: 150%;
            background: radial-gradient(circle at center, rgba(30, 41, 59, 0.5) 0%, var(--dark-bg) 70%);
            z-index: -1;
        }

        .btn-back-home {
            position: absolute;
            top: 25px;
            left: 25px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--text-gray);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 500;
            padding: 10px 20px;
            border-radius: 50px;
            border: 1px solid transparent;
            transition: all 0.3s ease;
            z-index: 10;
        }

        .btn-back-home:hover {
            color: white;
            background: rgba(255, 255, 255, 0.05);
            border-color: var(--border-color);
        }

        .login-card {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            position: relative;
            z-index: 1;
        }

        .brand-logo {
            font-size: 1.5rem;
            font-weight: 800;
            color: white;
            text-align: center;
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }

        .welcome-text {
            text-align: center;
            color: var(--text-gray);
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .form-label {
            font-size: 0.85rem;
            color: var(--text-gray);
            margin-bottom: 8px;
        }

        .input-group-text {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            border-right: none;
            color: var(--text-gray);
        }

        .form-control {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            border-left: none;
            color: white;
            padding: 12px;
        }

        .form-control:focus {
            background: var(--input-bg);
            border-color: var(--primary);
            box-shadow: none;
            color: white;
        }

        .input-group:focus-within .input-group-text {
            border-color: var(--primary);
        }

        .btn-toggle-password {
            background: var(--input-bg);
            border: 1px solid var(--border-color);
            border-left: none;
            color: var(--text-gray);
        }

        .input-group:focus-within .btn-toggle-password {
            border-color: var(--primary);
        }

        .btn-primary-custom {
            background: var(--primary);
            border: none;
            color: white;
            width: 100%;
            padding: 14px;
            border-radius: 12px;
            font-weight: 700;
            margin-top: 20px;
            transition: 0.3s;
            box-shadow: 0 10px 20px -5px rgba(255, 107, 53, 0.3);
        }

        .btn-primary-custom:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
        }

        .footer-link {
            text-align: center;
            margin-top: 20px;
            font-size: 0.9rem;
            color: var(--text-gray);
        }

        .footer-link a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
        }

        .alert-custom {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: #FCA5A5;
            font-size: 0.85rem;
            border-radius: 10px;
            padding: 10px;
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

    <a href="index.php" class="btn-back-home">
        <i class="fas fa-arrow-left"></i>
        <span>Beranda</span>
    </a>

    <div class="login-card">
        <div class="brand-logo">
            <i class="fas fa-recycle text-warning me-2"></i>Bank<span style="color:var(--primary)">Sampah</span>
        </div>
        <p class="welcome-text">Selamat datang kembali, Admin & Nasabah!</p>

        <?php if (isset($error)) : ?>
            <div class="alert-custom">
                <i class="fas fa-exclamation-circle me-1"></i> Email atau Password salah!
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="contoh@email.com" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" id="passwordInput" class="form-control" placeholder="••••••••" required>
                    <button class="btn btn-toggle-password" type="button" onclick="togglePassword()">
                        <i class="fas fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" name="login" class="btn-primary-custom">
                Masuk Sekarang <i class="fas fa-arrow-right ms-2"></i>
            </button>

            <div class="footer-link">
                Belum punya akun? <a href="register.php">Daftar disini</a>
            </div>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('passwordInput');
            const eyeIcon = document.getElementById('eyeIcon');
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.classList.remove('fa-eye');
                eyeIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                eyeIcon.classList.remove('fa-eye-slash');
                eyeIcon.classList.add('fa-eye');
            }
        }
    </script>
</body>

</html>