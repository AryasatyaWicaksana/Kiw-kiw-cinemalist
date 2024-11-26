<?php
session_start();
require_once '../service/database.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$email_name = $_ENV['EMAIL_NAME'];
$email_pass = $_ENV['EMAIL_PASS'];
$register_message = "";

// Default langkah
if (!isset($_SESSION['current_step'])) {
    $_SESSION['current_step'] = 1;
}

// Redirect jika sudah login
if (isset($_SESSION["is_login"])) {
    header("Location: ../Dashboard/dashboard.php");
    exit();
}

// Fungsi untuk mengirim kode verifikasi
function sendVerificationEmail($email, $verification_code, $email_name, $email_pass) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $email_name;
        $mail->Password = $email_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($email_name, 'KiwKiw Cinemalist');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Verification Code for KiwKiw Cinemalist';
        $mail->Body = "<h3>Hello!</h3><br>
            <p>Your verification code is: <b>{$verification_code}</b><br>
            Use this code to verify your email for registration.<br><br>
            If you didn't request this, please ignore this email.<br><br>
            Best regards,<br>KiwKiw Cinemalist</p>";

        $mail->send();
    } catch (Exception $e) {
        throw new Exception("Gagal mengirim email verifikasi: {$mail->ErrorInfo}");
    }
}

// Langkah 1: Validasi Email dan Kirim Kode Verifikasi
if (isset($_POST['submit_email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_message = "Email tidak valid.";
    } else {
        $check_email_query = "SELECT * FROM users WHERE email = $1";
        $check_email_result = pg_query_params($dbconn, $check_email_query, [$email]);

        if (!$check_email_result) {
            $register_message = "Gagal memeriksa email.";
        } elseif (pg_num_rows($check_email_result) > 0) {
            $register_message = "Email sudah terdaftar. Silakan gunakan email lain.";
        } else {
            $verification_code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
            $_SESSION['email'] = $email;
            $_SESSION['verification_code'] = $verification_code;
            $_SESSION['verification_expiry'] = time() + 300;

            try {
                sendVerificationEmail($email, $verification_code, $email_name, $email_pass);
                $_SESSION['current_step'] = 2;
            } catch (Exception $e) {
                $register_message = $e->getMessage();
            }
        }
    }
}

// Langkah 2: Validasi Kode Verifikasi
if (isset($_POST['submit_code']) && $_SESSION['current_step'] === 2) {
    $code = trim($_POST['code']);
    if (time() > $_SESSION['verification_expiry']) {
        $register_message = "Kode verifikasi telah kedaluwarsa.";
        session_destroy();
    } elseif ($code === $_SESSION['verification_code']) {
        unset($_SESSION['verification_code']);
        $_SESSION['current_step'] = 3;
    } else {
        $register_message = "Kode verifikasi salah.";
    }
}

// Langkah 3: Registrasi Pengguna
if (isset($_POST['register']) && $_SESSION['current_step'] === 3) {
    $username = htmlspecialchars(trim($_POST['username']));
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    if (empty($username) || empty($password) || empty($confirm_password)) {
        $register_message = "Semua field wajib diisi!";
    } elseif ($password !== $confirm_password) {
        $register_message = "Password dan konfirmasi password tidak cocok!";
    } elseif (strlen($password) < 8) {
        $register_message = "Password harus memiliki minimal 8 karakter!";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $email = $_SESSION['email'];
        $sql = "INSERT INTO users (username, email, password) VALUES ($1, $2, $3)";
        $result = pg_query_params($dbconn, $sql, [$username, $email, $hashed_password]);

        if ($result) {
            session_destroy();
            header('Location: login.php');
            exit();
        } else {
            $register_message = "Terjadi kesalahan saat memproses pendaftaran.";
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

                <?php if ($_SESSION['current_step'] === 1): ?>
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

                <?php elseif ($_SESSION['current_step'] === 2): ?>
                <!-- Step 2: Email Verification Form -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <p class="text-white"><?= htmlspecialchars($_SESSION['email']) ?></p>
                    </div>
                    <div class="mb-3">
                        <input type="number" class="form-control bg-dark text-white border-0 border-bottom" id="code" name="code" autocomplete="off" required>
                        <label for="code" class="form-label text-light">Verification Code</label>
                    </div>
                    <p class="text-danger"><?= $register_message ?></p>
                    <button type="submit" name="submit_code" class="btn btn-outline-danger w-100">Submit</button>
                </form>

                <?php elseif ($_SESSION['current_step'] === 3): ?>
                <!-- Step 3: Username and Password Form -->
                <form method="POST" action="">
                    <div class="mb-3">
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