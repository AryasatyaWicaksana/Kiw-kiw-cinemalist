<?php
require_once '../service/database.php';
session_start();

if (isset($_SESSION['user_id']) === false) {
    header("Location: ../../index.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit"])) { 
    try {
        $user_id = $_SESSION["user_id"];
        $email = trim(htmlspecialchars($_POST['email'] ?? ''));
        $password = trim($_POST['password'] ?? '');

        if (!empty($email) && !empty($password) && strlen($password) >= 8) {

            $queryStatusTable = "DELETE FROM movie_status WHERE id_user = $1";
            $resultStatusTable = pg_query_params($dbconn, $queryStatusTable, [$user_id]);
            
            if ($resultStatusTable){
                $sql = "SELECT * FROM user_list WHERE id = $1";
                $result = pg_query_params($dbconn, $sql, [$user_id]);

                if ($result && pg_num_rows($result) > 0) {
                    $user = pg_fetch_assoc($result);
                    
                    if (password_verify($password, $user['password'])) {
                        $queryUserTable = "DELETE FROM user_list WHERE id = $1";
                        $resultUserTable = pg_query_params($dbconn, $queryUserTable, [$user_id]);
        
                        if (!$resultUserTable){
                            throw new Exception("Data not found");
                        } else {
                            session_unset();
                            session_destroy();
                            header("location: ../../index.php");
                            exit();
                        }
                    }
                }
            } else {
                throw new Exception("Data not found");
            }
        } else {
            throw new Exception("Email and password are required!");
        }
    } catch (Exception $error) {
        error_log($error->getMessage());
        exit;
    }
    
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Account</title>
    <link href="../../Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Assets/img/icon fix kiw kiw.png">
</head>
<body>
    <div class="container mt-5">
        <h1 class="text-center mb-4">Delete Account</h1>
        <form action="delete_acc.php" method="POST" enctype="multipart/form-data">
            <div class="mb-4 text-center">
                <label for="profilePhoto" class="form-label">Profile Photo</label>
                <div>
                    <img id="previewImage" src="../Assets/img/blank-profile-picture.png" 
                        alt="Profile Preview" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                </div>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Enter email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Enter Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <div class="text-center">
                <button type="submit" name="submit" class="edit-profile">Delete Account</button>
                <a href="profile.php" class="edit-profile">Cancel</a>
            </div>
        </form>
    </div>
    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>