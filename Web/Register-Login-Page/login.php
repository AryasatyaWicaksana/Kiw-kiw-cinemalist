<?php
    require_once '../service/database.php';

    if (isset($_POST['login'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];

        if (empty($username) || empty($password)) {
            echo "Username dan password wajib diisi!";
            exit();
        }

        $sql = "SELECT * FROM users WHERE username = $1";
        $result = pg_query_params($dbconn, $sql, array($username));

        if ($result && pg_num_rows($result) > 0) {
            $user = pg_fetch_assoc($result);
            if (password_verify($password, $user['password'])) {
                header('Location: ../Dashboard/dashboard.php');
                exit();
            } else {
                echo "Username atau password salah.";
            }
        }
        echo "Belum ada akun";
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
    <title>Login Page</title>
</head>
<body class="text-white" style="background-color: #302e2e;">
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="card bg-dark p-4" style="width: 22rem; border-radius: 10px; box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);">
            <div class="card-body">
                <h2 class="text-center mb-4 text-white">Login</h2>
                <form action="login.php" method="POST">
                    <div class="mb-3">
                        <input type="text" class="form-control bg-dark text-white border-0 border-bottom" id="username" name="username" autocomplete="off" required>
                        <label for="username" class="form-label text-light">Username</label>
                    </div>
                    <div class="mb-3">
                        <input type="password" class="form-control bg-dark text-white border-0 border-bottom" id="password" name="password" required>
                        <label for="password" class="form-label text-light">Password</label>
                        <button type="button" class="btn btn-link text-white position-absolute border-0" id="togglePassword">
                            <i class="bi bi-eye-fill" id="toggleIcon"></i>
                        </button>
                    </div>
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