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
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Assets/img/icon fix kiw kiw.png">
</head>
<body>
    <div class="container position-relative">
        <a href="../Dashboard/dashboard.php" class="back-button position-absolute top-0 end-0 mt-2 me-3" style="font-size: 25px;">X</a>
        <h1 class="text-center mt-3">Profile</h1>
        <div class="profile-photo text-center mb-3">
            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Photo">
        </div>
        <div class="profile-item text-center mb-3">
            <label>Name:</label>
            <span class="user-name"><?= $_SESSION["username"] ?></span>
        </div>
        
        <div class="text-center mb-4">
            <a href="edit_profile.php" class="edit-profile">Edit Profile</a>
        </div>

        <div class="d-flex justify-content-center align-items-center btn-container mb-4">
            <a href="like-list.php" class="edit-profile text-center mx-2">Liked</a>
            <a href="completed-list.php" class="edit-profile text-center mx-5">Completed</a>
            <a href="plant-to-watch.php" class="edit-profile text-center mx-2">Plan to Watch</a>
        </div>

        <form action="profile.php" method="POST" class="text-center mt-4">
            <button type="submit" class="logout-btn btn-lg" name="logout">Logout</button>
        </form>
    </div>
    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
