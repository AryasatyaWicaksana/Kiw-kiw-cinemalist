<?php
require_once 'verify_mail.php';
require_once '../service/database.php';

$fpass_message = "";

// Redirect jika sudah login
if (isset($_SESSION["is_login"])) {
    header("Location: ../Dashboard/dashboard.php");
    exit();
}

// Default langkah
if (!isset($_SESSION['current_fstep'])) {
    $_SESSION['current_fstep'] = 1;
}

// Langkah 1: Validasi Email dan Kirim Kode Verifikasi
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_email'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);

    if (empty($email)) {
        $fpass_message = "Email required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $fpass_message = "The email is invalid.";
    } else {
        try {
            $sql = "SELECT * FROM user_list WHERE email = $1";
            $result = pg_query_params($dbconn, $sql, [$email]);

            if ($result && pg_num_rows($result) > 0) {
                $verification_code = str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);

                $_SESSION['email'] = $email;
                $_SESSION['verification_code'] = $verification_code;
                $_SESSION['verification_expiry'] = time() + 300; // 5 minutes validity period

                if (!sendVerificationEmail($email, $verification_code, $email_name, $email_pass)) {
                    throw new Exception("Failed to send verification email. Please try again.");
                } else {
                    $_SESSION['current_fstep'] = 2;
                }
            } else {
                $fpass_message = "The email was not found.";
            }
        } catch (Exception $e) {
            error_log("Error during email verification: " . $e->getMessage());
            $fpass_message = "An error occurred. Please try again.";
        } finally {
            pg_close($dbconn);
        }
    }
}

// Langkah 2: Validasi Kode Verifikasi
if (isset($_POST['submit_code']) && $_SESSION['current_fstep'] === 2) {
    $code = trim($_POST['code']);
    if (time() > $_SESSION['verification_expiry']) {
        $fpass_message = "The verification code has expired.";
        session_destroy();
    } elseif ($code === $_SESSION['verification_code']) {
        unset($_SESSION['verification_code']);
        $_SESSION['current_fstep'] = 3;
    } else {
        $fpass_message = "The verification code is incorrect.";
    }
}

// Langkah 3: Reset Password
if (isset($_POST['fpass']) && $_SESSION['current_fstep'] === 3) {
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];

    if (empty($password) || empty($confirm_password)) {
        $fpass_message = "All fields are required!";
    } elseif ($password !== $confirm_password) {
        $fpass_message = "Password and password confirmation do not match!";
    } elseif (strlen($password) < 8) {
        $fpass_message = "Passwords must be at least 8 characters long!";
    } else {
        try {
            $email = $_SESSION['email'];
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql = "UPDATE user_list SET password = $1 WHERE email = $2";
            $result = pg_query_params($dbconn, $sql, [$hashed_password, $email]);

            if ($result) {
                session_destroy();
                pg_close($dbconn);
                header('Location: login.php');
                exit();
            } else {
                $fpass_message = "An error occurred while updating the password.";
            }
        } catch (Exception $e) {
            error_log("Error during password reset: " . $e->getMessage());
            $fpass_message = "An error occurred. Please try again.";
        } finally {
            pg_close($dbconn);
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
    <link rel="icon" href="../Assets/img/icon fix kiw kiw.png">
    <title>Forgot Password</title>
</head>
<body class="text-white" style="background-color: #302e2e;">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card bg-dark p-4" style="width: 22rem; border-radius: 10px; box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);">
            <div class="card-body">
                <h2 class="text-center mb-4 text-white">Forgot Password</h2>
                <?php if ($_SESSION['current_fstep'] === 1): ?>
                <!-- Step 1: Email Form -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <input type="email" class="form-control bg-dark text-white border-0 border-bottom" id="email" name="email" autocomplete="off" required>
                        <label for="email" class="form-label text-light">Email</label>
                    </div>
                    <p class="text-danger"><?= $fpass_message ?></p>
                    <button type="submit" name="submit_email" class="btn btn-outline-danger w-100">Submit</button>
                    <div class="text-center mt-3">
                        <p class="text-light">Don't have an account? <a href="register.php" class="text-danger">Register</a></p>
                    </div>
                </form>

                <?php elseif ($_SESSION['current_fstep'] === 2): ?>
                <!-- Step 2: Email Verification Form -->
                <form method="POST" action="">
                    <div class="mb-3">
                        <p class="text-white"><?= htmlspecialchars($_SESSION['email']) ?></p>
                    </div>
                    <div class="mb-3">
                        <input type="number" class="form-control bg-dark text-white border-0 border-bottom" id="code" name="code" autocomplete="off" required>
                        <label for="code" class="form-label text-light">Verification Code</label>
                    </div>
                    <p class="text-danger"><?= $fpass_message ?></p>
                    <button type="submit" name="submit_code" class="btn btn-outline-danger w-100">Submit</button>
                </form>

                <?php elseif ($_SESSION['current_fstep'] === 3): ?>
                <!-- Step 3: Username and Password Form -->
                <form method="POST" action="">
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
                    <p class="text-danger"><?= $fpass_message ?></p>
                    <button type="submit" name="fpass" class="btn btn-outline-danger w-100">Submit</button>
                </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>