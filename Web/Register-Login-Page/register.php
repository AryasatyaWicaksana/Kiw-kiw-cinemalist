<?php
session_start();
require_once '../service/database.php';

$current_step = 1;
$email = "";
$register_message = "";
$verification_code = "";

if (isset($_SESSION["is_login"])) {
    header("Location: ../Dashboard/dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['submit_email'])) {
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $verification_code = "123456"; // Contoh code
            $_SESSION['verification_code'] = $verification_code;
            $current_step = 2;
        } else {
            $register_message = "Email tidak valid.";
        }
    } elseif (isset($_POST['submit_code'])) {
        $code = htmlspecialchars(trim($_POST['code']));
        
        if ($code === $_SESSION['verification_code']) {
            $current_step = 3;
        } else {
            $register_message = "Kode verifikasi salah.";
            $current_step = 2;
        }
    } elseif (isset($_POST['register'])) {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm-password'];

        if (empty($username) || empty($password) || empty($confirm_password)) {
            $register_message = "Username dan password wajib diisi!";
        } elseif ($password !== $confirm_password) {
            $register_message = "Password dan konfirmasi password tidak cocok!";
        } elseif (strlen($password) < 8) {
            $register_message = "Password harus memiliki minimal 8 karakter!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            try {
                $sql = "INSERT INTO users (username, email, password) VALUES ($1, $2, $3)";
                $result = pg_query_params($dbconn, $sql, array($username, $email, $hashed_password));

                if ($result) {
                    unset($_SESSION['verification_code']);
                    header('Location: login.php');
                    exit();
                } else {
                    $register_message = "Pendaftaran gagal. Silakan coba lagi.";
                }
            } catch (Exception $e) {
                $register_message = "Terjadi kesalahan saat memproses pendaftaran. Silakan coba lagi.";
                error_log("Error during registration: " . $e->getMessage());
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="../../Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Assets/img/Logo kiw-kiw.png">
    <title>Register Page</title>
</head>
<body class="text-white" style="background-color: #302e2e;">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card bg-dark p-4" style="width: 22rem; border-radius: 10px; box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);">
            <div class="card-body">
                <h2 class="text-center mb-4 text-white">Register</h2>

                <?php if ($current_step === 1): ?>
                <!-- Step 1: Email Form -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <input type="email" class="form-control bg-dark text-white border-0 border-bottom" id="email" name="email" autocomplete="off" required>
                        <label for="email" class="form-label text-light">Email</label>
                    </div>
                    <p class="text-danger"><?= $register_message ?></p>
                    <button type="submit" name="submit_email" class="btn btn-outline-danger w-100">Submit</button>
                    <div class="text-center mt-3">
                        <p class="text-light">Already have an account? <a href="login.php" class="text-danger">Login</a></p>
                    </div>
                </form>

                <?php elseif ($current_step === 2): ?>
                <!-- Step 2: Email Verification Form -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <p class="text-white"><?= htmlspecialchars($email) ?></p>
                        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                        <input type="number" class="form-control bg-dark text-white border-0 border-bottom" id="code" name="code" autocomplete="off" required>
                        <label for="code" class="form-label text-light">Verification Code</label>
                    </div>
                    <p class="text-danger"><?= $register_message ?></p>
                    <button type="submit" name="submit_code" class="btn btn-outline-danger w-100">Submit</button>
                </form>

                <?php elseif ($current_step === 3): ?>
                <!-- Step 3: Username and Password Form -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <p class="text-white"><?= htmlspecialchars($email) ?></p>
                        <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                        <input type="text" class="form-control bg-dark text-white border-0 border-bottom" id="username" name="username" autocomplete="off" required>
                        <label for="username" class="form-label text-light">Username</label>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control bg-dark text-white border-0 border-bottom" id="password" name="password" autocomplete="off" required>
                        <label for="password" class="form-label text-light">Password</label>
                        <button type="button" class="btn btn-link text-white position-absolute border-0" id="togglePassword1">
                            <i class="bi bi-eye-fill" id="toggleIcon1"></i>
                        </button>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control bg-dark text-white border-0 border-bottom" id="confirm-password" name="confirm-password" autocomplete="off" required>
                        <label for="confirm-password" class="form-label text-light">Confirm Password</label>
                        <button type="button" class="btn btn-link text-white position-absolute border-0" id="togglePassword2">
                            <i class="bi bi-eye-fill" id="toggleIcon2"></i>
                        </button>
                    </div>
                    <p class="text-danger"><?= $register_message ?></p>
                    <button type="submit" name="register" class="btn btn-outline-danger w-100">Register</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>