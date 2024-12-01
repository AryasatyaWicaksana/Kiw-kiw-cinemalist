<?php
require_once '../service/database.php';
session_start();

if (isset($_SESSION['user_id']) === false) {
    header("Location: ../../index.php");
    exit();
}

$edit_message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["submit"])) {
    try {
        $user_id = $_SESSION["user_id"];
        $username = trim(htmlspecialchars($_POST['name'] ?? ''));
        $newPassword = trim($_POST['newPassword'] ?? '');
        $oldPassword = trim($_POST['oldPassword'] ?? '');

        if (!empty($username) && empty($newPassword) && empty($oldPassword)) {
            $query = "UPDATE user_list SET username = $1 WHERE id = $2";
            $result = pg_query_params($dbconn, $query, [$username, $user_id]);

            if(!$result){
                throw new Exception("Error updating username  : ". pg_last_error($dbconn));
            } else {
                $_SESSION['username'] = $username;
                header("Location: profile.php");
            }
        } else if (!empty($newPassword) && !empty($oldPassword) && empty($username) && strlen($newPassword) >= 8) {
            $sql = "SELECT * FROM user_list WHERE id = $1";
            $result = pg_query_params($dbconn, $sql, [$user_id]);

            if ($result && pg_num_rows($result) > 0) {
                $user = pg_fetch_assoc($result);
                
                if (password_verify($oldPassword, $user['password'])) {
                    $query = "UPDATE user_list SET password = $1 WHERE id = $2";
                    $result = pg_query_params($dbconn, $query, [password_hash($newPassword, PASSWORD_DEFAULT), $user_id]);

                    if(!$result){
                        throw new Exception("Error updating password : " . pg_last_error($dbconn));
                    } else {
                        header("Location: profile.php");
                    }
                } else {
                    $edit_message = "*Old password is incorrect.";
                }
            }
        } else if (!empty($username) && !empty($newPassword) && !empty($oldPassword) && strlen($newPassword) >= 8) {
            $sql = "SELECT * FROM user_list WHERE id = $1";
            $result = pg_query_params($dbconn, $sql, [$user_id]);

            if ($result && pg_num_rows($result) > 0) {
                $user = pg_fetch_assoc($result);

                if (password_verify($oldPassword, $user['password'])) {
                    $query = "UPDATE user_list SET username = $1, password = $2 WHERE id = $3";
                    $result = pg_query_params($dbconn, $query, [$username, password_hash($newPassword, PASSWORD_DEFAULT), $user_id]);

                    if(!$result){
                        throw new Exception("Error updating password : " . pg_last_error($dbconn));
                    } else {
                        $_SESSION['username'] = $username;
                        header("Location: profile.php");
                    } 
                } else {
                    $edit_message = "*Old password is incorrect.";
                }
            }
        } else {
            $edit_message = "*Password must be at least 8 character!";
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
    <title>Edit Profile</title>
    <link href="../../Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Assets/img/icon fix kiw kiw.png">
</head>
<body>
    <div class="container mt-5">
        <a href="delete_acc.php" class="edit-profile delete-acc btn">Delete Account</a>
        <h1 class="text-center mb-4">Edit Profile</h1>
        <form action="edit_profile.php" method="POST" enctype="multipart/form-data">
            <div class="mb-4 text-center">
                <label for="profilePhoto" class="form-label">Profile Photo</label>
                <div>
                    <a href="profile_update.php" class="position-relative">
                        <img id="previewImage" src="../Assets/profile-img/<?= $_SESSION['user_profile'] ?>" 
                            alt="Profile Preview" class="rounded-circle" style="width: 150px; height: 150px; object-fit: cover;">
                            <i class="bi bi-pencil-square text-decoration-none text-bg-dark position-absolute"></i>
                    </a>
                </div>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Username</label>
                <input type="text" class="form-control" id="name" name="name" placeholder="Enter a new username" value="">
            </div>
            <div class="mb-3">
                <label for="oldPassword" class="form-label">Old Password<i class="text-danger ms-2"><?= $edit_message ?></i></label>
                <input type="password" class="form-control" id="oldPassword" name="oldPassword" placeholder="Enter Your Old password"> 
                </input>
            </div>
            <div class="mb-3">
                <label for="newPassword" class="form-label">New Password</label>
                <input type="password" class="form-control" id="newPassword" name="newPassword" placeholder="Enter a new password">
            </div>
            <div class="text-center mt-5 mb-3">
                <button name="submit" type="submit" class="edit-profile m-2">Save Changes</button>
                <a href="profile.php" class="edit-profile btn m-2">Cancel</a>
            </div>
        </form>
    </div>
    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
