<?php
    session_start();

    if(isset($_SESSION["is_login"]) == false) {
        header("location: ../../index.php");
        exit();
    }

    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("location: ../../index.php");
        exit();
    }
?>

<!DOCTYPE html> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="../../Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Assets/img/icon fix kiw kiw.png">
</head>
<body>
    <div class="container my-5 position-relative" style=" align-items: center; width: 100%;">  
        <a href="../Dashboard/dashboard.php" class="back-button position-absolute top-0 end-0 mt-2 me-3" style="font-size: 25px;">X</a>
        <h1 class="text-center">Profile</h1>
        <div class="profile-photo text-center mb-4">
            <img src="../Assets/img/blank-profile-picture.png" alt="Profile Photo" class="img-fluid rounded-circle" style="max-width: 150px;">
        </div>
        <div class="profile-item text-center mb-4">
            <label class="fs-4">Username:</label>
            <span class="user-name"><?= $_SESSION["username"] ?></span>
        </div>

        <div class="d-flex flex-wrap justify-content-center align-items-center btn-container">
            <a href="edit_profile.php" class="edit-profile btn btn-primary m-2">Edit Profile</a>
            <a href="like.php" class="edit-profile btn btn-secondary m-2">Liked</a>
            <a href="completed.php" class="edit-profile btn btn-success m-2">Completed</a>
            <a href="plan-to-watch.php" class="edit-profile btn btn-warning m-2">Plan to Watch</a>
        </div>
        <div class="d-flex flex-wrap justify-content-center align-items-center btn-container">
            <form action="profile.php" method="POST" class="text-center m-2">
                <button type="submit" class="logout-btn btn btn-danger btn-lg" name="logout">Logout</button>
            </form>
        </div>
    </div>
    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>