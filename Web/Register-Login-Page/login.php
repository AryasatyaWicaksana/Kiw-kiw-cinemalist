<?php
session_start();
require_once '../service/database.php';

$login_message = "";

if (isset($_SESSION["is_login"])) {
    header("location: ../Dashboard/dashboard.php");
    exit();
}

if (isset($_SESSION['current_step']) || isset($_SESSION['current_fstep'])) {
    session_destroy();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $password = trim($_POST['password']);

    if (empty($email) || empty($password)) {
        $login_message = "Email and password are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $login_message = "The email is invalid.";
    } else {
        try {
            $sql = "SELECT * FROM user_list WHERE email = $1";
            $result = pg_query_params($dbconn, $sql, array($email));

            if ($result && pg_num_rows($result) > 0) {
                $user = pg_fetch_assoc($result);

                if (password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['user_profile'] = $user['profile_picture'];
                    $_SESSION["is_login"] = true;

                    pg_close($dbconn);
                    header('Location: ../Dashboard/dashboard.php');
                    exit();
                } else {
                    $login_message = "Wrong email or password.";
                }
            } else {
                $login_message = "The account with the email was not found.";
            }
        } catch (Exception $e) {
            error_log("Error during login: " . $e->getMessage());
            $login_message = "An error occurred while logging in. Please try again.";
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
    <title>Login Page</title>
</head>
<body class="text-white" style="background-color: #302e2e;">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card bg-dark p-4" style="width: 22rem; border-radius: 10px; box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);">
            <div class="card-body">
                <h2 class="text-center mb-4 text-white">Login</h2>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <input type="email" class="form-control bg-dark text-white border-0 border-bottom" id="email" name="email" autocomplete="off" required>
                        <label for="email" class="form-label text-light">Email</label>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control bg-dark text-white border-0 border-bottom" id="password" name="password" autocomplete="off" required>
                        <label for="password" class="form-label text-light">Password</label>
                        <button type="button" class="btn btn-link text-white position-absolute border-0" id="togglePassword1">
                            <i class="bi bi-eye-fill" id="toggleIcon1"></i>
                        </button>
                    </div>
                    <div class="forgot-password mb-3">
                        <p class="text-light"><a href="forgot_password.php" class="text-danger">Forgot Password?</a></p>
                    </div>
                    <p class="text-danger mt-5 mb-1"><?= $login_message ?></p>
                    <button type="submit" name="login" class="btn btn-outline-danger w-100 position-relative mb-1">
                        <span class="animation-layer"></span>
                        <span class="animation-layer"></span>
                        <span class="animation-layer"></span>
                        <span class="animation-layer"></span>
                        Login
                    </button>
                    <div class="text-center mt-3">
                        <p class="text-light">Don't have an account? <a href="register.php" class="text-danger">Register</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>