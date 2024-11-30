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
    <div class="container position-relative position-absolute bottom-0 start-50 translate-middle-x" style="height: 500px">
        <a href="../Dashboard/dashboard.php" class="back-button position-absolute top-0 end-0 mt-2 me-3" style="font-size: 25px;">X</a>
        <h1 class="text-center">Profile</h1>
        <div class="profile-photo text-center mb-5">
            <img src="../Assets/img/blank-profile-picture.png" alt="Profile Photo">
        </div>
        <div class="profile-item text-center mt-5">
            <label>Username:</label>
            <span class="user-name"><?= $_SESSION["username"] ?></span>
        </div>

        <br><br><br><br>
        
        <div class="d-flex justify-content-between align-items-center btn-container" >
            <a href="edit_profile.php" class="edit-profile text-center">Edit Profile</a>
            <a href="like.php" class="edit-profile text-center">Liked</a>
            <a href="completed.php" class="edit-profile text-center">Completed</a>
            <a href="plan-to-watch.php" class="edit-profile text-center">Plan to Watch</a>
            <form action="profile.php" method="POST" class="text-center">
                <button type="submit" class="logout-btn btn-lg" name="logout">Logout</button>
            </form>
        </div>

    </div>
    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
